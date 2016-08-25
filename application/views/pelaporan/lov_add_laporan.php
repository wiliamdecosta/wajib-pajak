<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>
<div id="modal_lov_add_laporan" class="modal fade" tabindex="-1" style="overflow-y: scroll;">
    <div class="modal-dialog" style="width:570px;">
        <div class="modal-content">
            <!-- modal title -->
            <div class="modal-header no-padding">
                <div class="table-header">
                    <span class="form-add-edit-title"> Tambah Data Pelaporan</span>
                </div>
            </div>
            <input type="hidden" id="modal_lov_add_laporan_id_val" value="" />
            <input type="hidden" id="modal_lov_add_laporan_code_val" value="" />
            <input type="hidden" id="month_id" value="" />

            <!-- modal body -->
            <div class="modal-body">
			
				<div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">NPWPD:</label>
					<input class="col-md-6" value="<?php echo $this->session->userdata('user_name'); ?>">
				</div>
                <div class="row top-buffer">
					<label class="col-md-3 col-md-offset-1">PERIODE:</label>
					<select id="months" class="col-md-6"></select>
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Klasifikasi:</label>
					<select id="klasifikasi" class="col-md-6">
					</select>
				</div>
                <div class="row  top-buffer" id="rincian_form">
					<label class="col-md-3 col-md-offset-1">Rincian:</label>
					<select id="rincian" class="col-md-6">
					</select>
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Masa Pajak:</label>
					<input class="col-md-3 date-picker" type="text" id="datepicker" readonly=""> 
					<label class="col-md-1">s/d</label>
					<input class="col-md-3 date-picker" type="text" id="datepicker2" readonly="">
				</div>
                <div class="row top-buffer">
					<a class="col-md-3 col-md-offset-4 btn btn-primary" style="font-size:10px">Upload File Transaksi</a>
					<label class="col-md-1">atau</label>
					<a class="col-md-3 btn btn-primary" style="font-size:10px" id="isiformtransaksi">Isi Form Transaksi</a>
				</div>
				<div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Nilai Omzet:</label>
					<input class="col-md-5" readonly="" id="omzet_value"  style="text-align:right;">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Pajak yang Harus dibayar:</label>
					<input class="col-md-5" readonly=""  id="val_pajak" style="text-align:right;">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Denda:</label>
					<input class="col-md-5" readonly="" id="val_denda" style="text-align:right;">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Total Bayar:</label>
					<input class="col-md-5" readonly="" id="totalBayar" style="text-align:right;">
				</div>
                
            </div>
            <!-- modal footer -->
            <div class="modal-footer no-margin-top">
                <div class="bootstrap-dialog-footer">
                    <div class="bootstrap-dialog-footer-buttons">
                        <button class="btn btn-danger btn-xs radius-4" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
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
							$('#rincian').append('<option value='+ data.rows[0].vat_code +' data-id='+ data.rows[0].vat_pct +'>'+ data.rows[0].vat_code +'</option>');
						i++;	
						}
					} else{
						$('#rincian_form').hide(100);
					}					
			}
        });
	});	
    jQuery(function($) {
        $("#modal_lov_add_laporan_btn_blank").on('click', function() {
            $("#"+ $("#modal_lov_add_laporan_id_val").val()).val("");
            $("#"+ $("#modal_lov_add_laporan_code_val").val()).val("");
            $("#modal_lov_add_laporan").modal("toggle");
        });
    });

    function modal_lov_add_laporan_show(the_id_field, the_code_field, customer_ref) {
        modal_lov_add_laporan_set_field_value(the_id_field, the_code_field);
        $("#modal_lov_add_laporan").modal({backdrop: 'static'});
    }


    function modal_lov_add_laporan_set_field_value(the_id_field, the_code_field) {
         $("#modal_lov_add_laporan_id_val").val(the_id_field);
         $("#modal_lov_add_laporan_code_val").val(the_code_field);
    }

    function modal_lov_add_laporan_set_value(the_id_val, the_code_val) {
         $("#"+ $("#modal_lov_add_laporan_id_val").val()).val(the_id_val);
         $("#"+ $("#modal_lov_add_laporan_code_val").val()).val(the_code_val);
         $("#modal_lov_add_laporan").modal("toggle");

         $("#"+ $("#modal_lov_add_laporan_id_val").val()).change();
    }

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