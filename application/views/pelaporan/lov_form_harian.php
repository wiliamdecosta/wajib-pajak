<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/moment.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>
<div id="modal_lov_form_harian" class="modal fade" tabindex="-1" style="overflow-y: scroll;">
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

            <!-- modal body -->
            <div class="modal-body">
			
				<div class="tab-pane active">
					<table id="grid-table-laporan"></table>
					<div id="grid-pager-laporan"></div>
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

<script>
modal_lov_form_harian
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
		mydata = new Array();
		mydata["Tanggal", "No. Urut Faktur Awal'", "No. Urut Faktur Akhir","Jumlah Faktur","Jumlah Penjualan","Deskripsi"] = new Array();
		while(i < diffDays){
			dateM1 = moment(the_id_field).add(i,'days');   
			mydata['Tanggal'][i] = dateM1;
			i++;
		}
		alert(mydata['Tanggal']);
		// xyz = moment(the_id_field).format('DD-MM-YYYY');
		// mydata = [{
			// Tanggal: xyz,
			// No_UrutAwal: "Canada",
			// No_UrutAkhir: "North America"
		// }, {
			// Tanggal: the_code_field,
			// No_UrutAwal: "USA",
			// No_UrutAkhir: "North America"
		// }, {
			// Tanggal: "Silicon Valley",
			// No_UrutAwal: "USA",
			// No_UrutAkhir: "North America"
		// }, {
			// Tanggal: "Paris",
			// No_UrutAwal: "France",
			// No_UrutAkhir: "Europe"
		// }];
		
			jQuery(function($) {
			var grid_selector = "#grid-table-laporan";
			var pager_selector = "#grid-pager-laporan";
	
			jQuery("#grid-table-laporan").jqGrid({
				// url: '<?php echo WS_JQGRID."history.history_transaksi_controller/read"; ?>',
				// datatype: "json",
				data: mydata,
				datatype: "local",
				// mtype: "POST",
				colNames: ["Tanggal", "No. Urut Faktur Awal'", "No. Urut Faktur Akhir","Jumlah Faktur","Jumlah Penjualan","Deskripsi"],			
				colModel: [
					{label: 'Tanggal', name: 'Tanggal', hidden: false, cellEdit: true},              
					{label: 'No. Urut Faktur Awal', name: 'No_UrutAwal', hidden: false, cellEdit: true},               
					{label: 'No. Urut Faktur Akhir', name: 'No_UrutAkhir', hidden: false, editable: true},
					{label: 'Jumlah Faktur', name: 'tgl_pelaporan', hidden: false, editable: true, cellEdit: true},
					{label: 'Jumlah Penjualan', name: 'total_transaksi', hidden: false, editable: true, cellEdit: true},
					{label: 'Deskripsi', name: 'total_pajak', hidden: false, editable: true, cellEdit: true}                
				],
				height: '100%',
				width:'300px',
				autowidth: true,
				viewrecords: true,
				rowNum: 10,
				rowList: [10,20,50],
				rownumbers: true, // show row numbers
				// rownumWidth: 35, // the width of the row numbers columns
				altRows: true,
				shrinkToFit: false,
				multiboxonly: true,
				cellEdit : true,
				cellsubmit : 'remote',
				// width:'100%',
				onSelectRow: function (rowid) {	
					
				},
				ondblClickRow: function(rowid) {
					// jQuery(this).jqGrid('editGridRow', rowid);			
					alert($('#modal_lov_form_harian_id_val').val());
					alert($('#modal_lov_form_harian_code_val').val());
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
			$(grid_selector).jqGrid( 'setGridWidth', $(".form-body").width() );
			$(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );
	
		}
    
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

</script>

<script>

	var mydata = [];
			
	
</script>