<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/moment.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>
<div id="modal_lov_form_harian" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="width:960px;">
        <div class="modal-content">
            <!-- modal title -->
            <div class="modal-header no-padding">
                <div class="table-header">
                    <span class="form-add-edit-title"> Isi Form Harian</span>
                </div>
            </div>
            <input type="hidden" id="modal_lov_form_harian_id_val" value="" />
            <input type="hidden" id="modal_lov_form_harian_code_val" value="" />
            <input type="hidden" id="modal_lov_vat_pct_val" value="" />

            <!-- modal body -->
            <div class="modal-body" style="height:500px; overflow-y:scroll;">
			
				<div class="tab-pane active">
					<table id="grid-table-laporan"></table>
					<div id="grid-pager-laporan"></div>
				</div>
                
            </div>
            <!-- modal footer -->
            <div class="modal-footer no-margin-top">
                <div class="bootstrap-dialog-footer">
                    <div class="bootstrap-dialog-footer-buttons">
                        <button class="btn btn-default btn-xs radius-4" id="simpan" data-dismiss="modal">
                            <i class="ace-icon fa fa-floppy"></i>
                            Simpan
                        </button>
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

<script>
	$('#simpan').click(function(){
		var $grid = $('#grid-table-laporan');
		var colSum = $grid.jqGrid('getCol', 'jum_penjualan', false, 'sum');
		alert(colSum);
		$('#omzet_value').html(omzet_value);
		$('#omzet_value').val(colSum);
		
		$.ajax({
            async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl_cls",
			datatype: "json",            
            type: "POST",
            success: function (response) {
					var data = $.parseJSON(response);
					var i = 0;
					if (data.rows.length > 0){
						while(i<=data.rows.length){
							$('#rincian').append('<option value='+ data.rows[0].vat_code +' data-id='+ data.rows[0].vat_pct +'>'+ data.rows[0].vat_code +'</option>');
						i++;	
						}
					} else{
						$('#rincian_form').hide(100);
						$.ajax({
							url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl",
							datatype: "json",            
							type: "POST",
							success: function (response) {
								var data = $.parseJSON(response);
								$('#val_pajak').val( (data.rows[0].vat_pct * parseInt($('#omzet_value').val())) / 100 );
							}
						});			
					}
			}
		});
		
		var date_denda_signed = false;
			$.ajax({							
				async: false,
				url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/get_fined_start",
				datatype: "json",            
				type: "POST",
				data: {nowdate:moment($('#datepicker').val()).format("YYYY-MM-"),
						getdate:moment($('#datepicker').val()).format("MM-YYYY")
				},
				success: function (response) {
					var data = $.parseJSON(response);
					alert(data.rows[0].booldenda);
					if(parseInt(data.rows[0].booldenda) >= 0){
						$('#val_denda').val( 2 / 100 * parseInt($('#val_pajak').val()) );
						// alert("AJAX");
					};
				}
			});		
		// Hitung Denda
		// i = 0; val_akhir = 0; val_denda = 0; va = $('#val_pajak').val();
		// while (i < $("#grid-table-laporan").getRowData().length){
			// rowId = $('#grid-table-laporan').jqGrid('getCell',i,'keyid');
			// if(rowId == -1){
				// $('#val_denda').val( $('#val_pajak').val() * 2 / 100 );
				// break;
			// }	 			
		// i++;
		// }			
		// alert("val denda" + $('#val_denda').val());
		$('#totalBayar').val( parseInt($('#val_pajak').val()) + parseInt($('#val_denda').val()) );
		i=0;
			
		while (i < $("#grid-table-laporan").getRowData().length){
			Tanggal = $('#grid-table-laporan').jqGrid('getCell',i,'Tanggal');
			No_UrutAwal = $('#grid-table-laporan').jqGrid('getCell',i,'No_UrutAwal');
			No_UrutAkhir = $('#grid-table-laporan').jqGrid('getCell',i,'No_UrutAkhir');
			jum_faktur = $('#grid-table-laporan').jqGrid('getCell',i,'jum_faktur');
			jum_penjualan = $('#grid-table-laporan').jqGrid('getCell',i,'jum_penjualan');
			descript = $('#grid-table-laporan').jqGrid('getCell',i,'descript');
			if((No_UrutAwal.length >0) || (No_UrutAkhir.length >0) || (jum_faktur !=0) || (jum_penjualan !=0) || (descript.length >0)){
				// items[i] = {trans_date:''};
				// items[i] = {bill_no:''};
				// items[i] = {bill_no_end:''};
				// items[i] = {bill_count:''};
				// items[i] = {service_desc:''};
				// items[i] = {service_charge:''};
				// items[i] = {descript:''};
				
				// mydata[i].trans_date = Tanggal;
				// mydata[i].bill_no = No_UrutAwal;
				// mydata[i].bill_no_end = No_UrutAkhir;
				// mydata[i].bill_count = jum_faktur;
				// mydata[i].service_desc = null;
				// mydata[i].service_charge = jum_penjualan;
				// mydata[i].descript = description;			
			}
		i++;
		};
	});

    jQuery(function($) {
        $("#modal_lov_form_harian_btn_blank").on('click', function() {
            $("#"+ $("#modal_lov_form_harian_id_val").val()).val("");
            $("#"+ $("#modal_lov_form_harian_code_val").val()).val("");
            $("#modal_lov_form_harian").modal("toggle");
        });
    });

    function modal_lov_form_harian_show(the_id_field, the_code_field, diffDays) {
        modal_lov_form_harian_set_field_value(the_id_field, the_code_field);
        $("#modal_lov_form_harian").modal({backdrop: 'static'});
		i = 0;
		mydata = [];
		var date_denda_signed = false;
		$.ajax({							
			async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/get_fined_start",
			datatype: "json",            
			type: "POST",
			data: {nowdate:moment($('#datepicker').val()).format("YYYY-MM-"),
					getdate:moment($('#datepicker').val()).format("MM-YYYY")
			},
			success: function (response) {
				var data = $.parseJSON(response);

				if(data.rows[0].booldenda);
				// date_denda_signed = data.rows[0].due_in_day;
			}
		});	
		while(i < diffDays+1){
			dateM1 = moment(the_id_field).add(i,'days');
			dateFormatted = moment(dateM1).format('DD-MM-YYYY');
			mydata[i] = {Tanggal:''};
			mydata[i] = {keyid:''};
			mydata[i] = {jum_faktur:''};
			mydata[i] = {jum_penjualan:''};
			mydata[i].Tanggal = dateFormatted;
			mydata[i].jum_faktur = 0;
			mydata[i].jum_penjualan = 0;
			i++;
		}		
			jQuery(function($) {
			var grid_selector = "#grid-table-laporan";
			var pager_selector = "#grid-pager-laporan";
	
			jQuery("#grid-table-laporan").jqGrid({
				// url: '<?php echo WS_JQGRID."history.history_transaksi_controller/read"; ?>',
				// datatype: "json",
				data: mydata,
				datatype: "local",
				// mtype: "POST",
				colNames: ["keyid","Tanggal", "No. Urut Faktur Awal", "No. Urut Faktur Akhir","Jumlah Faktur","Jumlah Penjualan","Deskripsi"],			
				colModel: [
					{label: 'keyid', name: 'keyid', hidden: false, cellEdit: false},					              
					{label: 'Tanggal', name: 'Tanggal', hidden: false, cellEdit: false},              
					{label: 'No. Urut Faktur Awal', name: 'No_UrutAwal', hidden: false, editable: true, cellEdit: true},               
					{label: 'No. Urut Faktur Akhir', name: 'No_UrutAkhir', hidden: false, editable: true, cellEdit: true},
					{label: 'Jumlah Faktur', name: 'jum_faktur', hidden: false, editable: true, cellEdit: true},
					{label: 'Jumlah Penjualan', name: 'jum_penjualan', hidden: false, editable: true, cellEdit: true},
					{label: 'Deskripsi', name: 'descript', hidden: false, editable: true, cellEdit: true}                
				],
				height: '100%',
				width:900,
				autowidth: false,
				viewrecords: true,
				rowNum: 100,
				rowList: [100,200,500],
				rownumbers: true, // show row numbers
				altRows: true,
				shrinkToFit: false,
				multiboxonly: true,
				cellEdit : true,
				cellsubmit : 'clientArray',
				onSelectRow: function (rowid) {						
				},
				beforeSaveCell: function(rowid,celname,value,iRow,iCol) {
					alert('New cell value: "'+value+'"');
					// alert('New cell value: "'+value+'"');
				},
				sortorder:'',
				pager: '#grid-pager-laporan',
				jsonReader: {
					root: 'rows',
					id: 'id',
					repeatitems: false
				},
				loadComplete: function (response) {
					if(response.success == false) {
						swal({title: 'Attention', text: response.message, html: true, type: "warning"});
					}
					responsive_jqgrid(grid_selector,pager_selector);
				},
				//memanggil controller jqgrid yang ada di controller crud
				editurl: '',
				caption: "Tax Details"
	
			});
			jQuery('#grid-table-laporan').jqGrid('navGrid', '#grid-pager-laporan',
				{   //navbar options
					edit: true,
					excel: true,
					editicon: 'fa fa-pencil blue bigger-120',
					add: true,				
					addicon: 'fa fa-plus-circle purple bigger-120',
					del: true,
					delicon: 'fa fa-trash-o red bigger-120',
					search: true,
					searchicon: 'fa fa-search orange bigger-120',
					refresh: true,
					afterRefresh: function () {
						// some code here
					},
	
					refreshicon: 'fa fa-refresh green bigger-120',
					view: false,
					viewicon: 'fa fa-search-plus grey bigger-120'
				},
	
				{
					// options for the Edit Dialog
					closeAfterEdit: true,
					closeOnEscape:true,
					recreateForm: true,
					serializeEditData: serializeJSON,
					width: 'auto',
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText
					},
					beforeShowForm: function (e, form) {
						var form = $(e[0]);
						style_edit_form(form);
	
					},
					afterShowForm: function(form) {
						form.closest('.ui-jqdialog').center();
					},
					afterSubmit:function(response,postdata) {
						var response = jQuery.parseJSON(response.responseText);
						if(response.success == false) {
							return [false,response.message,response.responseText];
						}
						return [true,"",response.responseText];
					}
				},
				{
					//new record form
					editData: {
						p_finance_period_id: function() {
							return <?php echo $this->input->post('p_finance_period_id'); ?>;
						}
					},
					closeAfterAdd: false,
					clearAfterAdd : true,
					closeOnEscape:true,
					recreateForm: true,
					width: 'auto',
					errorTextFormat: function (data) {
						return 'Error: ' + data.responseText
					},
					serializeEditData: serializeJSON,
					viewPagerButtons: false,
					beforeShowForm: function (e, form) {
						var form = $(e[0]);
						style_edit_form(form);
					},
					afterShowForm: function(form) {
						form.closest('.ui-jqdialog').center();
					},
					afterSubmit:function(response,postdata) {
						var response = jQuery.parseJSON(response.responseText);
						if(response.success == false) {
							return [false,response.message,response.responseText];
						}
	
						$(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
						var tinfoel = $(".tinfo").show();
						tinfoel.delay(3000).fadeOut();
	
	
						return [true,"",response.responseText];
					}
				},
				{
					//delete record form
					serializeDelData: serializeJSON,
					recreateForm: true,
					beforeShowForm: function (e) {
						var form = $(e[0]);
						style_delete_form(form);
	
					},
					afterShowForm: function(form) {
						form.closest('.ui-jqdialog').center();
					},
					onClick: function (e) {
						//alert(1);
					},
					afterSubmit:function(response,postdata) {
						var response = jQuery.parseJSON(response.responseText);
						if(response.success == false) {
							return [false,response.message,response.responseText];
						}
						return [true,"",response.responseText];
					}
				},
				{
					//search form
					closeAfterSearch: false,
					recreateForm: true,
					afterShowSearch: function (e) {
						var form = $(e[0]);
						style_search_form(form);
						form.closest('.ui-jqdialog').center();
					},
					afterRedraw: function () {
						style_search_filters($(this));
					}
				},
				{
					//view record form
					recreateForm: true,
					beforeShowForm: function (e) {
						var form = $(e[0]);
					}
				}
			)
		});	
				
		
    
	}


    function modal_lov_form_harian_set_field_value(the_id_field, the_code_field) {
         $("#modal_lov_form_harian_id_val").val(the_id_field);
         $("#modal_lov_form_harian_code_val").val(the_code_field);
    }

    function modal_lov_form_harian_set_value(the_id_val, the_code_val) {
         $("#"+ $("#modal_lov_form_harian_id_val").val()).val(the_id_val);
         $("#"+ $("#modal_lov_form_harian_code_val").val()).val(the_code_val);
         $("#modal_lov_form_harian").modal("toggle");

         $("#"+ $("#modal_lov_form_harian_id_val").val()).change();
    }
	
	function serializeJSON(postdata) {
		var items;
		if(postdata.oper != 'del') {
			items = JSON.stringify(postdata, function(key,value){
				if (typeof value === 'function') {
					return value();
				} else {
				return value;
				}
			});
		}else {
			items = postdata.id;
		}

		var jsondata = {items:items, oper:postdata.oper, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'};
		return jsondata;
	}

	function style_edit_form(form) {

		//update buttons classes
		var buttons = form.next().find('.EditButton .fm-button');
		buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
		buttons.eq(0).addClass('btn-primary');
		buttons.eq(1).addClass('btn-danger');


	}

	function style_delete_form(form) {
		var buttons = form.next().find('.EditButton .fm-button');
		buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
		buttons.eq(0).addClass('btn-danger');
		buttons.eq(1).addClass('btn-default');
	}

	function style_search_filters(form) {
		form.find('.delete-rule').val('X');
		form.find('.add-rule').addClass('btn btn-xs btn-primary');
		form.find('.add-group').addClass('btn btn-xs btn-success');
		form.find('.delete-group').addClass('btn btn-xs btn-danger');
	}

	function style_search_form(form) {
		var dialog = form.closest('.ui-jqdialog');
		var buttons = dialog.find('.EditTable')
		buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'fa fa-retweet');
		buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'fa fa-comment-o');
		buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-success').find('.ui-icon').attr('class', 'fa fa-search');
	}

	function responsive_jqgrid(grid_selector, pager_selector) {

		var parent_column = $(grid_selector).closest('[class*="col-"]');
		$(grid_selector).jqGrid( 'setGridWidth', $(".modal-body").width() );
		$(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

	}
</script>

