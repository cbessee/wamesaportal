/** New JS controller for wpDataTables **/

var wpDataTables = {};
var wpDataTablesSelRows = {};
var wpDataTablesFunctions = {};
var wpDataTablesFixedHeaders = {};
var wpDataTablesUpdatingFlags = {};
var wpDataTablesResponsiveHelpers = {};
var wdtBreakpointDefinition = {
    tablet: 1024,
    phone : 480
};

(function($) {
	$(function() {
		
		$('table.wpDataTable').each(function(){
			var tableDescription = $(this).data('description');
			
			// Parse the DataTable init options
			var dataTableOptions = tableDescription.dataTableParams;
	
			// Responsive-mode related stuff
			if(tableDescription.responsive){
				wpDataTablesResponsiveHelpers[tableDescription.tableId] = false;
				dataTableOptions.fnPreDrawCallback = function(){
					if (!wpDataTablesResponsiveHelpers[tableDescription.tableId]) {
			            wpDataTablesResponsiveHelpers[tableDescription.tableId] = new ResponsiveDatatablesHelper($(tableDescription.selector).dataTable(), wdtBreakpointDefinition);
			        }					
				}
				dataTableOptions.fnRowCallback = function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
					wpDataTablesResponsiveHelpers[tableDescription.tableId].createExpandIcon(nRow);
				}
				if(!tableDescription.editable){
					dataTableOptions.fnDrawCallback = function(){
						wpDataTablesResponsiveHelpers[tableDescription.tableId].respond();
					}
				}
			}


			// Loading spinner
			if(typeof dataTableOptions.oLanguage === 'undefined'){
				dataTableOptions.oLanguage = {};
			}
			dataTableOptions.oLanguage.sProcessing = '<img src="'+tableDescription.spinnerSrc+'" />';

			if(tableDescription.editable){

				if(typeof wpDataTablesFunctions[tableDescription.tableId] === 'undefined'){
					wpDataTablesFunctions[tableDescription.tableId] = {};
				}
				
				wpDataTablesSelRows[tableDescription.tableId] = -1;
				
				dataTableOptions.fnDrawCallback = function(){
					if(tableDescription.responsive){
						wpDataTablesResponsiveHelpers[tableDescription.tableId].respond();
					}
					
					if(wpDataTablesSelRows[tableDescription.tableId] == -2){
						var sel_row_index = wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength-1;
						$(tableDescription.selector+' tbody tr').removeClass('selected');
						wpDataTablesSelRows[tableDescription.tableId] = wpDataTables[tableDescription.tableId].fnGetPosition($(tableDescription.selector+' tbody tr:eq('+sel_row_index+')').get(0));
					}else if (wpDataTablesSelRows[tableDescription.tableId] == -3){
						var sel_row_index = 0;
						$(tableDescription.selector+' tbody tr').removeClass('selected');
						wpDataTablesSelRows[tableDescription.tableId] = wpDataTables[tableDescription.tableId].fnGetPosition($(tableDescription.selector+' tbody tr:eq('+sel_row_index+')').get(0));
					}
					if(!$(tableDescription.selector+'_edit_dialog').is(':ui-dialog')) { return; } 
					if($(tableDescription.selector+'_edit_dialog').dialog('isOpen')){
						var data = wpDataTables[tableDescription.tableId].fnGetData(wpDataTablesSelRows[tableDescription.tableId]);
						wpDataTablesFunctions[tableDescription.tableId].applyData(data);
					}	
					if(wpDataTablesUpdatingFlags[tableDescription.tableId]){
						$(tableDescription.selector+' tbody tr:eq('+wpDataTablesSelRows[tableDescription.tableId]+')').click();
					}
					wpDataTablesUpdatingFlags[tableDescription.tableId] = false;
					
				}
				
				// Data apply function for editable tables
				wpDataTablesFunctions[tableDescription.tableId].applyData = function(data){
				 	$(data).each(function(index, el){
				 		if(el) {
					 		var val = el.toString();
				 		}else{
				 			var val = '';
				 		}
						if(val.indexOf('span') != -1){ val = val.replace(/<span>/g, '').replace(/<\/span>/g,''); }
						if(val.indexOf('<br/>') != -1) {  val = val.replace(/<br\/>/g,"\n");}
						if($('#'+tableDescription.tableId+'_edit_dialog .editDialogInput:eq('+index+')').data('input_type')=='multi-selectbox'){
							$('#'+tableDescription.tableId+'_edit_dialog .editDialogInput:eq('+index+') option').removeAttr('selected');
							var values = val.split(', ');
							$(values).each(function(){
								$('#'+tableDescription.tableId+'_edit_dialog .editDialogInput:eq('+index+') option[value="'+this+'"]').attr('selected','selected');
							});
						}else {
							if($('#'+tableDescription.tableId+'_edit_dialog .editDialogInput:eq('+index+')').data('input_type')=='attachment'){
								if(val!=''){
									val = $(val).attr('href');
									val = val.substring(val.lastIndexOf('/')+1);
								}
							}else{
								if(val.indexOf('<a href') != -1){ val = $(val).html(); }
							}
							$('#'+tableDescription.tableId+'_edit_dialog .editDialogInput:eq('+index+')').val(val).css('border','');
						}
				 	});
					if($('.fileupload_'+tableDescription.tableId+'').length){
						var $fileupload_el = $('.fileupload_'+tableDescription.tableId+'');
				 		$fileupload_el.closest('.fileinput-button').show();
				 		$fileupload_el.closest('td').find('.progress').show();
			 	   		var id_key = $('#'+tableDescription.tableId+'_edit_dialog tr.idRow .editDialogInput').data('key');
			 	   		var id_val = $('#'+tableDescription.tableId+'_edit_dialog tr.idRow .editDialogInput').val();
						$('.progress-bar').css('width', '0%');
			 	   		$('.fileupload_'+tableDescription.tableId+'').each(function(){
					 	   	$(this).fileupload('option', 'url', tableDescription.fileUploadBaseUrl+'&key='+$(this).data('key')+'&id_key='+id_key+'&id_val='+id_val);
		 		            $('#files_'+tableDescription.tableId+'_'+$(this).data('key')).html('<p>'+$('#'+tableDescription.tableId+'_'+$(this).data('key')).val()+' [<a href="#" data-key="'+$(this).data('key')+'" class="wdtdeleteFile">remove</a>]</p>');
				 	   		if($('#'+tableDescription.tableId+'_'+$(this).data('key')).val()==''){
				 	   			$('#files_'+tableDescription.tableId+'_'+$(this).data('key')).hide();
				 	   		}else{
				 	   			$('#files_'+tableDescription.tableId+'_'+$(this).data('key')).show();
				 	   		}
			 	   		});
					}
				}
				
				// Saving of the table data for frontend 
				wpDataTablesFunctions[tableDescription.tableId].saveTableData = function(forceRedraw){
						if(typeof(forceRedraw) === undefined){
							forceRedraw = false;
						}
						wpDataTablesUpdatingFlags[tableDescription.tableId] = true;
						var formdata = {table_id: tableDescription.tableWpId};
						var valid = true;	
						
						$(tableDescription.selector+'_edit_dialog .editDialogInput').each(function(){
							// validation
							if($(this).data('input_type') == 'email'){
								if($(this).val()!=''){
									var field_valid = wdtValidateEmail($(this).val());
									if(!field_valid){
										valid = false;
										$(this).css('border','2px solid #f00');
									}else{
										$(this).css('border','');
									}
								}
							}else if($(this).data('input_type') == 'link'){
								if($(this).val()!=''){
									var field_valid = wdtValidateURL($(this).val());
									if(!field_valid){
										valid = false;
										$(this).css('border','2px solid #f00');
									}else{
										$(this).css('border','');
									}
								}
							}
							if($(this).hasClass('datepicker')){
								formdata[$(this).data('key')] = $.datepicker.formatDate(tableDescription.datepickFormat, $(this).datepicker('getDate'));
							}else if($(this).data('input_type')=='multi-selectbox'){
								if($(this).val()){
									formdata[$(this).data('key')] = $(this).val().join(', ');
								}
							}else{
								formdata[$(this).data('key')] = $(this).val();
							}
						});
						if(!valid){ return false; }
						wpDataTablesUpdatingFlags[tableDescription.tableId] = true;		
						$.ajax({
							url: tableDescription.adminAjaxBaseUrl,
							type: 'POST',
							data: {
								action: 'wdt_save_table_frontend',
								formdata: formdata
							},
							success: function(insert_id){
								if(insert_id){
									$(tableDescription.selector+'_edit_dialog tr.idRow .editDialogInput').val(insert_id);
								 	if($('.fileupload_'+tableDescription.tableId).length){
								 		$('.fileupload_'+tableDescription.tableId).closest('.fileinput-button').show();
								 		$('.fileupload_'+tableDescription.tableId).closest('td').find('.progress').show();
								 	}
								 	if(forceRedraw){
										wpDataTables[tableDescription.tableId].fnDraw(false);
								 	}
								}else{
									wpDataTables[tableDescription.tableId].fnDraw(false);
								}
							}
						});
						return true;
					}
				
			}
			
			// Init the DataTable itself
			wpDataTables[tableDescription.tableId] = $(tableDescription.selector).dataTable(dataTableOptions);
			
			// Init table grouping if enabled
			if((tableDescription.columnsFixed == 0) && (tableDescription.groupingEnabled)){
				wpDataTables[tableDescription.tableId].rowGrouping({ iGroupingColumnIndex: tableDescription.groupingColumnIndex });
			}
			
			// Init the advanced filtering if enabled
			if(tableDescription.advancedFilterEnabled){
				wpDataTables[tableDescription.tableId].columnFilter(tableDescription.advancedFilterOptions);
				$.datepicker.regional[""].dateFormat = tableDescription.datepickFormat;
			    $.datepicker.setDefaults($.datepicker.regional['']);
			}
			
			// Init the fixed headers if enabled
			if(tableDescription.columnsFixed > 0){
				wpDataTablesFixedHeaders[tableDescription.tableId] = new FixedHeader(wpDataTables[tableDescription.tableId], 
										   { left: tableDescription.columnsFixed, 
											 tableId: tableDescription.tableId,
											 offsetLeft: tableDescription.offsetLeft,
											 offsetTop: tableDescription.offseTop
										   } );
			}
			
			
			
			if(tableDescription.editable){
					/**
					 * Init edit dialog on page load
					 */
					 $(tableDescription.selector+'_edit_dialog').dialog({
					 	show: { 'effect': 'fade', 'duration': 300 },
					 	hide: { 'effect': 'fade', 'duration': 300 },
					 	modal: true,
					 	autoOpen: false,
					 	draggable: false,
					    width: 500,
					    height: 500,
					    dialogClass: 'wdtEditDialog',
					    buttons: {
					    	'Cancel': function(){
					    		$(this).dialog('close');
					    	},
					    	'<< Prev': function(){
								var sel_row_index = $(tableDescription.selector+' tr.selected').index();
								if(sel_row_index > 0) {
									$(tableDescription.selector+' tbody tr.selected').removeClass('selected');
									$(tableDescription.selector+' tbody tr:eq('+(sel_row_index-1)+')').addClass('selected', 300);
									wpDataTablesSelRows[tableDescription.tableId] = wpDataTables[tableDescription.tableId].fnGetPosition($(tableDescription.selector+' tbody tr.selected').get(0));
									var data = wpDataTables[tableDescription.tableId].fnGetData(wpDataTablesSelRows[tableDescription.tableId]);
									wpDataTablesFunctions[tableDescription.tableId].applyData(data);
								}else{
									var cur_page = Math.ceil( wpDataTables[tableDescription.tableId].fnSettings()._iDisplayStart /  wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength) + 1;
									if(cur_page == 1) return;
									wpDataTablesSelRows[tableDescription.tableId] = -2;
									wpDataTablesUpdatingFlags[tableDescription.tableId] = true;
									wpDataTables[tableDescription.tableId].fnPageChange( 'previous' );
								}
					    	},
					    	'Next >>': function(){
					    		var sel_row_index = $(tableDescription.selector+' tr.selected').index();
								if(sel_row_index < wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength-1) {
									$(tableDescription.selector+' tbody tr.selected').removeClass('selected');
									$(tableDescription.selector+' tbody tr:eq('+(sel_row_index+1)+')').addClass('selected', 300);
									wpDataTablesSelRows[tableDescription.tableId] = wpDataTables[tableDescription.tableId].fnGetPosition($(tableDescription.selector+' tbody tr.selected').get(0));;
									var data = wpDataTables[tableDescription.tableId].fnGetData(wpDataTablesSelRows[tableDescription.tableId]);
									wpDataTablesFunctions[tableDescription.tableId].applyData(data);
								}else{
									var cur_page = Math.ceil( wpDataTables[tableDescription.tableId].fnSettings()._iDisplayStart / wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength) + 1;
									var total_pages = Math.ceil( wpDataTables[tableDescription.tableId].fnSettings()._iRecordsTotal /  wpDataTables[tableDescription.tableId].fnSettings()._iDisplayLength);
									if(cur_page == total_pages) return;
									wpDataTablesSelRows[tableDescription.tableId] = -3;
									wpDataTablesUpdatingFlags[tableDescription.tableId] = true;
									wpDataTables[tableDescription.tableId].fnPageChange( 'next' );
									wpDataTables[tableDescription.tableId].fnDraw(false);
								}
					    	},
					    	'Apply': function(){
					    		wpDataTablesFunctions[tableDescription.tableId].saveTableData();
					    	},
					    	'OK': function(){
					    		if(wpDataTablesFunctions[tableDescription.tableId].saveTableData(true)){
						    		$(this).dialog('close');
					    		}
					    	}
					    }
				 	 })
				 	 
				 	 /**
				 	  * Apply maskmoney
				 	  */
				 	  $(tableDescription.selector+'_edit_dialog input.maskMoney').maskMoney();
			
				 	 /**
				 	  * Apply datepicker
				 	  */
				 	  $(tableDescription.selector+'_edit_dialog input.datepicker').datepicker();
				 	  
				 	  /**
				 	   * Apply fileuploaders
				 	   */
				 	   if($('.fileupload_'+tableDescription.tableId).length){
					 	   $('.fileupload_'+tableDescription.tableId).parent().button();
					 	   $('.fileupload_'+tableDescription.tableId).each(function(){
					 	   		var key = $(this).data('key');
						 	   	$(this).fileupload({
							        dataType: 'json',
							        done: function (e, data) {
								            $.each(data.result.files, function (index, file) {
								                $('#files_'+tableDescription.tableId+'_'+key).html('<p>'+file.name+' [<a href="#" data-key="'+key+'" class="wdtdeleteFile">remove</a>]</p>');
								                $(tableDescription.selector+'_'+key).val(file.url);
									 	   		if($(tableDescription.selector+'_'+key).val()==''){
									 	   			$(tableDescription.selector+'_'+key).hide();
									 	   		}else{
									 	   			$(tableDescription.selector+'_'+key).show();
									 	   		}
								            });
								        },
								        progressall: function (e, data) {
								            var progress = parseInt(data.loaded / data.total * 100, 10);
								            $('#progress_'+tableDescription.tableId+'_'+key+' .progress-bar').css(
								                'width',
								                progress + '%'
								            );
								        }
						 	   	});
					 	   });
				 	   }
			
					
					/**
					 * Show edit dialog
					 */
					 $('#edit_'+tableDescription.tableId).click(function(){
					 	var row = $(tableDescription.selector+' tr.selected').get(0);
					 	var data = wpDataTables[tableDescription.tableId].fnGetData(row);
						wpDataTablesFunctions[tableDescription.tableId].applyData(data);
					 	$(tableDescription.selector+'_edit_dialog').dialog('open');
					 });
					 
					 
					/**
					 * Add new entry dialog
					 */
					 $('#new_'+tableDescription.tableId).click(function(){
					 	$(tableDescription.selector+'_edit_dialog .editDialogInput').val('').css('border','');
					 	$(tableDescription.selector+'_edit_dialog tr.idRow .editDialogInput').val('0');
					 	$(tableDescription.selector+'_edit_dialog').dialog('open');
					 	if($('.fileupload_'+tableDescription.tableId).length){
					 		var $fileupload_el = $('.fileupload_'+tableDescription.tableId);
					 		$fileupload_el.closest('.fileinput-button').hide();
					 		$fileupload_el.closest('td').find('.progress').hide();
					 		$('#files_'+tableDescription.tableId+'_'+$fileupload_el.data('key')).html('');
					 	}
					 });
					 
					 /**
					  * Delete an entry dialog
					  */
					  $('#delete_'+tableDescription.tableId).click(function(){
					  	var confirm_dialog_str = '<div title="Are you sure?">Delete this entry?</div>';
					  	$(confirm_dialog_str).dialog({
					  		autoOpen: true,
					  		dialogClass: 'wdtEditDialog',
					  		modal: true,
					  		buttons: {
					  			'Yes': function(){
								 	var row = $(tableDescription.selector+' tr.selected').get(0);
								 	var data = wpDataTables[tableDescription.tableId].fnGetData(row);
								 	var id_val = data[tableDescription.idColumnIndex];
								 	var that = this;
								 	$.ajax({
										url: tableDescription.adminAjaxBaseUrl,
										type: 'POST',
										data: {
											action: 'wdt_delete_table_row',
											id_key: tableDescription.idColumnKey,
											id_val: id_val,
											table_id: tableDescription.tableWpId	
										},
										success: function(){
											wpDataTables[tableDescription.tableId].fnDraw(false);
							  				$(that).dialog('close');
							  				$(that).dialog('destroy');
										}					 		
							 		});
					  			},
					  			'No': function(){
					  				$(this).dialog('close');
					  				$(this).dialog('destroy');
					  			}
					  		}
					  	});
					  });
					  
					$('#edit_'+tableDescription.tableId).prependTo(tableDescription.selector+'_wrapper .DTTT_container').css('float','right');	
					$('#new_'+tableDescription.tableId).prependTo(tableDescription.selector+'_wrapper .DTTT_container').css('float','right');	
					$('#delete_'+tableDescription.tableId).prependTo(tableDescription.selector+'_wrapper .DTTT_container').css('float','right');
					 
					var clickEvent = function(e){
						if($(this).hasClass('selected')) {
							$(tableDescription.selector+' tbody tr').removeClass('selected');
							wpDataTablesSelRows[tableDescription.tableId] = -1;
						}else{
							$(tableDescription.selector+'  tbody tr').removeClass('selected');
							$(this).addClass('selected');
							wpDataTablesSelRows[tableDescription.tableId] = wpDataTables[tableDescription.tableId].fnGetPosition($(tableDescription.selector+' tbody tr.selected').get(0));
						}
						if($(tableDescription.selector+' tbody tr.selected').length > 0){
							$('#edit_'+tableDescription.tableId).removeAttr('disabled');
							$('#delete_'+tableDescription.tableId).removeAttr('disabled');
						}else{
							$('#edit_'+tableDescription.tableId).attr('disabled','disabled');
							$('#delete_'+tableDescription.tableId).attr('disabled','disabled');
						}
					}
					
					var ua = navigator.userAgent,
				    event = (ua.match(/iPad/i)) ? "touchstart" : "click";
					
					$(tableDescription.selector+' tbody tr').live(event, clickEvent);
					
					$(tableDescription.selector+'_edit_dialog a.wdtdeleteFile').live('click',function(e){
						e.preventDefault();
						e.stopImmediatePropagation();
						  	var confirm_dialog_str = '<div title="Are you sure?">Delete this file?</div>';
						  	var key = $(this).data('key');
						  	var hiddeninput = $(tableDescription.selector+'_'+key);
						  	var filesblock = $(this).closest('.files');
						  	$(confirm_dialog_str).dialog({
						  		autoOpen: true,
						  		dialogClass: 'wdtEditDialog',
						  		modal: true,
						  		buttons: {
						  			'Yes': function(){
									 	var id_val =  $(tableDescription.selector+'_edit_dialog tr.idRow .editDialogInput').val();
									 	var that = this;
									 	$.ajax({
											url: tableDescription.adminAjaxBaseUrl,
											type: 'POST',
											data: {
												action: 'wdt_delete_uploaded_file',
												id_key: tableDescription.idColumnKey,
												id_val: id_val,
												table_id: tableDescription.tableWpId,
												key: key
											},
											success: function(){
												filesblock.html('');
												hiddeninput.val('');
								  				$(that).dialog('close');
								  				$(that).dialog('destroy');
											}					 		
								 		});
						  			},
						  			'No': function(){
						  				$(this).dialog('close');
						  				$(this).dialog('destroy');
						  			}
						  		}
						  	});
					});
			 
			}
			 
		});
				
	})
})(jQuery);