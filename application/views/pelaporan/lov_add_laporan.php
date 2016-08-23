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
					<input class="col-md-6">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Rincian:</label>
					<input class="col-md-6">
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
					<input class="col-md-5" readonly="">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Pajak yang Harus dibayar:</label>
					<input class="col-md-5" readonly="">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Denda:</label>
					<input class="col-md-5" readonly="">
				</div>
                <div class="row  top-buffer">
					<label class="col-md-3 col-md-offset-1">Total Bayar:</label>
					<input class="col-md-5" readonly="">
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

<?php $this->load->view('pelaporan/lov_form_harian.php'); ?>

<script>
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
        modal_lov_add_laporan_prepare_table(customer_ref);
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

    function modal_lov_add_laporan_prepare_table(customer_ref) {
        $("#modal_lov_add_laporan_grid_selection").bootgrid("destroy");
        $("#modal_lov_add_laporan_grid_selection").bootgrid({
             formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="javascript:;" title="Set Value" onclick="modal_lov_add_laporan_set_value(\''+ row.account_num +'\', \''+ row.account_name +'\')" class="blue"><i class="fa fa-pencil-square-o bigger-130"></i></a>';
                }
             },
             rowCount:[5,10],
             ajax: true,
             requestHandler:function(request) {
                if(request.sort) {
                    var sortby = Object.keys(request.sort)[0];
                    request.dir = request.sort[sortby];

                    delete request.sort;
                    request.sort = sortby;
                }
                return request;
             },
             responseHandler:function (response) {
                if(response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }
                return response;
             },
             url: '<?php echo WS_URL."account.account_controller/readLov"; ?>',
             post: function(){
                return {
                    customer_ref:customer_ref
                };
             },
             selection: true,
             sorting:true
        });

        $('.bootgrid-header span.glyphicon-search').removeClass('glyphicon-search')
        .html('<i class="fa fa-search"></i>');
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
				$('#months').append('<option value='+ months +' >' + months + '</option>');				
				i++;
				}
			}
        });
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
					alert("Diff days: "+diffDays+"<br>"
								+"Num days in month: "+numDaysMonth+"<br>"
								+"3rd field calc: "+division);
						// $('#nxtDate').datepicker({
							// dateFormat: "dd-M-yy", 
						// });
						// $("#currDate").datepicker({
							// dateFormat: "dd-M-yy", 
							// minDate:  0,
							// onSelect: function(date){
								// var date2 = $('#currDate').datepicker('getDate');								
								// $('#nxtDate').datepicker('setDate', date2);
							// }
						// });
								alert(date1);
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