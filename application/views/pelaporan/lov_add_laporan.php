<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>

<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Pelaporan Pajak</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>
	<div id="modal_lov_add_laporan" class="portlet light bordered">

		<!-- modal title -->		
		<div class="portlet-title">
			<div class="caption font-red-sunglo">
				<span class="caption-subject bold uppercase">Tambah Data Pembayaran</span>
			</div>
		</div>
		<input type="hidden" id="modal_lov_add_laporan_id_val" value="" />
		<input type="hidden" id="modal_lov_add_laporan_code_val" value="" />
		<input type="hidden" id="month_id" value="" />
	
		<!-- modal body -->
		<div class="portlet-body form">
		
			<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">NPWPD:</label>
				<input class="col-md-3" value="<?php echo $this->session->userdata('user_name'); ?>">
			</div>
				<div class="row top-buffer">
				<label class="col-md-2 col-md-offset-1">PERIODE:</label>
				<select id="months" class="col-md-3"></select>
			</div>
				<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Klasifikasi:</label>
				<select id="klasifikasi" class="col-md-3">
				</select>
			</div>
				<div class="row  top-buffer" id="rincian_form">
				<label class="col-md-2 col-md-offset-1">Rincian:</label>
				<select id="rincian" class="col-md-3">
				</select>
			</div>
				<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Masa Pajak:</label>
				<input class="col-md-2 date-picker" type="text" id="datepicker" readonly=""> 
				<label class="col-md-1">s/d</label>
				<input class="col-md-2 date-picker" type="text" id="datepicker2" readonly="">
			</div>
				<div class="row top-buffer">
				<a class="col-md-2 col-md-offset-3 btn btn-primary" style="font-size:10px">Upload File Transaksi</a>
				<label class="col-md-1">atau</label>
				<a class="col-md-2 btn btn-primary" style="font-size:10px" id="isiformtransaksi">Isi Form Transaksi</a>
			</div>
			<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Nilai Omzet:</label>
				<input class="col-md-3" readonly="" id="omzet_value"  style="text-align:right;">
			</div>
				<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Pajak yang Harus dibayar:</label>
				<input class="col-md-3" readonly=""  id="val_pajak" style="text-align:right;">
			</div>
				<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Denda:</label>
				<input class="col-md-3" readonly="" id="val_denda" style="text-align:right;">
			</div>
				<div class="row  top-buffer">
				<label class="col-md-2 col-md-offset-1">Total Bayar:</label>
				<input class="col-md-3" readonly="" id="totalBayar" style="text-align:right;">
			</div>
			<div class="form-actions right">
				<button class="btn default" type="button">Cancel</button>
				<button class="btn green" type="submit">Submit</button>
			</div>
		</div>			
	</div><!-- /.end modal -->

<?php  $this->load->view('pelaporan/lov_form_harian.php'); ?>

<script>
	$(document).ready(function(){
		$.ajax({
            //async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl",
			datatype: "json",            
            type: "POST",
            success: function (response) {
					var data = $.parseJSON(response);
					$('#klasifikasi').append('<option selected value='+ data.rows[0].vat_code +'>'+ data.rows[0].vat_code +'</option>');
					$('#vat_pct').append('<option value='+ data.rows[0].vat_code +' data-id='+ data.rows[0].vat_pct +' >'+ data.rows[0].vat_code +'</option>');
				}
        });
	});
	$(document).ready(function(){
		$.ajax({
            //async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl_cls",
			datatype: "json",            
            type: "POST",
            success: function (response) {
					var data1 = $.parseJSON(response);
					i=0;
					if (data1.rows.length >0){
						while(i<=data1.rows.length){
							$('#rincian').append('<option value='+ data1.rows[0].vat_code +' data-id='+ data1.rows[0].vat_pct +'>'+ data1.rows[0].vat_code +'</option>');
						i++;	
						}
					} else{
						$('#rincian_form').hide(100);
					}					
			}
        });
	});	
    

    $('#months').click(function(){
		$.ajax({
            // async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/pelaporan_bulan",
			datatype: "json",            
            type: "POST",
            success: function (response) {
				var data = $.parseJSON(response);
				i = 0;
				while(i < data.rows.length){
				var months = data.rows[i].code;
				var start_date = data.rows[i].start_date_string;
				var end_date = data.rows[i].end_date_string;
				$('#months').append('<option value="'+ start_date +'" data-id="'+ end_date +'" >' + months + '</option>');			
				i++;
				}
			}
        });
	});
	
	$('#months').change(function(){
		StartDate = $('#months').find(':selected').val();		
		EndDate = $('#months').find(':selected').data("id");
				
		$("#datepicker").datepicker('setDate',StartDate);
		$("#datepicker2").datepicker('setDate',EndDate);
		
	});
	
	
	$(function() {
        $( "#datepicker" ).datepicker();
        $( "#datepicker2" ).datepicker();
    });

	$('#isiformtransaksi').on('click',function() {
		var date = $("#datepicker").datepicker('getDate');
		var date1 = $("#datepicker2").datepicker('getDate');
		var dates = $("#datepicker2").val();
		var dates1 = $("#datepicker2").val();
		
		if ((dates.length != 0) && (dates1.length != 0)){
			var diffDays = Math.ceil((date1.getTime() - date.getTime())/1000/3600/24);
			var numDaysMonth = new Date(date1.getYear(), date1.getMonth()+1, 0).getDate();
			var division = parseInt($("#number").val())/numDaysMonth*diffDays;
				if (diffDays>=0){					
					modal_lov_form_harian_show(date,date1,diffDays);
				} else
				{
					swal('error','Input masa pajak tidak valid. Penanggalan awal pajak harus lebih awal dari akhir pajak','error');
				}
		} else
		{
			swal('error','Isi terlebih dahulu periode masa pajak secara lengkap','error');
		}
		
      });
</script>