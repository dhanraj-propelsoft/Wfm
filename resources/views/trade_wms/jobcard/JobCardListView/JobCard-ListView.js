
// hide dafalut loader
$('.loader_wall').hide();
$('.loader_wall_onspot').hide();

console.log('Naveen1');
console.log(dateRangeList1);

var datatable = null;
var isFirstIteration = true;



let from_date = "";
let to_date = "";
let closedStatus = "";
let $fromDateField = $('input[name=from_date]');
let $toDateField = $('input[name=to_date]');
let $dateRangeField = $('select[name=date_range]');
let $searchTextField = $('input[name=search_text]').val();
//let dateRangeList = <? php echo json_encode(dateRange());?>;
let dateRangeList = dateRangeList1;
console.log($searchTextField);

function getcurrrentTime() {
	var date = new Date();
	var dateformat = String(date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()).padEnd(3, '0') + '.' + String(date.getMilliseconds()).padEnd(6, '0');
	return dateformat;
}


function onSetDefalutDateRange() {
	let selectedKey = $dateRangeField.val();
	console.log(selectedKey);
	if(selectedKey == 'CUSTOM'){
		from_date = $fromDateField.val();
		to_date = $toDateField.val();
/*		var activeDateRange = {
			fromDate: from_date,
​			toDate: to_date
			};
*/		
		onSetDateToFields(from_date, to_date);
	}else{
		let activeDateRange = dateRangeList[selectedKey];
		console.log(activeDateRange);
		onSetDateToFields(activeDateRange.fromDate, activeDateRange.toDate);
	}
}

$(document).ready(function() {

	/* Loading Indicator */
	new imageLoader(cImageSrc, 'startAnimation()');

	/* FORMAT DATE */
	function currentDate() {
		var d = new Date(),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();
	
		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;
	
		return [year, month, day].join('-');
	}

	/* START Export Excel Action */

	var oldExportAction = function (self, e, dt, button, config) {
		if (button[0].className.indexOf('buttons-excel') >= 0) {
			if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
				$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
			}
			else {
				$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
			}
		} else if (button[0].className.indexOf('buttons-print') >= 0) {
			$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
		}
	}; 

	var newExportAction = function (e, dt, button, config) {
		var self = this;
		var oldStart = dt.settings()[0]._iDisplayStart;
	
		dt.one('preXhr', function (e, s, data) {
			// Just this once, load all data from the server...
			data.start = 0;
			data.length = 2147483647;
	
			dt.one('preDraw', function (e, settings) {
				// Call the original action function 
				oldExportAction(self, e, dt, button, config);
	
				dt.one('preXhr', function (e, s, data) {
					// DataTables thinks the first item displayed is index 0, but we're not drawing that.
					// Set the property to what it was before exporting.
					settings._iDisplayStart = oldStart;
					data.start = oldStart;
				});
	
				// Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
				setTimeout(dt.ajax.reload, 0);
	
				// Prevent rendering of the full data to the DOM
				return false;
			});
		});
	
		// Requery the server with the new one-time export settings
		dt.ajax.reload();
	};
/* END Export Excel Action */

	/*set defalut range and fill the  date fields  */
	onSetDefalutDateRange();

	/* Approach By Manimaran */
	datatable = $('#datatable').on('processing.dt', function(e, settings, processing) {
		if (processing) {
			//$('.loader_wall').show();
			new imageLoader(cImageSrc, 'startAnimation()');
		} else {
			//$('.loader_wall').hide();
			new imageLoader(cImageSrc, 'stopAnimation()');
		}
	}).DataTable({
		dom: 'lBfrtip',
		buttons: [
			{
				extend: 'excel',
				action: newExportAction,
				exportOptions: {
					columns: ":not(.noExport)",
			
				},
				footer: false,
				title: orgName+" Jobcard report - "+currentDate(),
				init: function( api, node, config) {
					$(node).hide()
				 }
			}],
		

		"processing": true,
		"serverSide": true,
		"lengthMenu": [[15, 20, 30, -1], [15, 20, 30, "All"]],
		"order": [[5, "desc"]],
		"language": {
			"infoFiltered": "(filtered from _MAX_ total records)"
		}, initComplete: function() {
			//hide button
			var $buttons = $('.dt-buttons');
			
			//initialize button click event
			$('.excel_export').on('click', function() {
				var btnClass = ".buttons-excel";
				$buttons.find(btnClass).trigger("click"); 
				
				
			})
       },
		
		"ajax": {
			url: jobcard_index_route,
			dataSrc: 'data',
			start_time: getcurrrentTime(),
			data: function(d) {
				d.from_date = from_date;
				d.to_date = to_date;
				d.jobcard_status = $('select[name=jobcard_status]').val();
				d.search_text =  $('input[name=search_text]').val();
			},
			complete: function(data) {
				console.log("Ajax Request to Server - " + this.start_time);
				console.log("Ajax Reponse from Server - " + getcurrrentTime());
				let response = data.responseJSON;
				if (response.from_date && response.to_date) {
					onSetDateToFields(response.from_date, response.to_date);
				}
			}
		},

		"columns": [

			{ data: 'pOrderNo', name: 'pOrderNo' },
			{ data: 'pVehicleRegisterNo', name: 'pVehicleRegisterNo' },
			{ data: 'pCustomer', name: 'pCustomer' },
			{ data: 'pAssignToEmployee', name: 'pAssignToEmployee' },
			{ data: 'pAdvanceAmount', name: 'pAdvanceAmount' },
			{ data: 'pLastModified', name: 'pLastModified' },
			{ data: 'pCreated', name: 'pCreated' },
		],
		"columnDefs": [

			{
				"targets": 7,
				"render": function(data, type, row) {
					let html = '';
					let jobCardStatusId = parseInt(row.pStatusId);
					let className = onSetLabelClass(jobCardStatusId);
					let jobCardStatus = onSetJobCardStatus(jobCardStatusId);
					html = `<label class="grid_label badge ` + className + ` job_status">` + jobCardStatus + `</label>`;
					//console.log(html);
					return html;
				}

			}],
		drawCallback: function(data) {
			var api = this.api();
			//console.log(api);
			$.contextMenu({
				selector: 'tbody tr td',
				build: function($triggerElement, e) {

					// Get selected row data                                
					var row = $triggerElement.closest('tr');
					var rowData = datatable.row(row).data();
					//console.log(rowData);

					var delete_disable	=	false;

					//get jc number and set it to the first input field
					var pOrderNo = rowData.pOrderNo;
					//console.log(pOrderNo);

					//Estimate change label to create/update based on estimate presence
					//console.log(rowData.pHasEstimate);
					var create_est = 'Create Estimation';
					var view_est_disable = true;
					var estimate_id;
					if (rowData.pHasEstimate == 'True') {
						create_est = 'Update Estimate';
						view_est_disable = false;
						estimate_id = rowData.pEstimateId;

						//disable delete if estimate exist
						delete_disable	=	true;
					}

					//Invoice change label to create/update based on estimate presence
					//console.log(rowData.pHasEstimate);
					var create_inv = 'Create Invoice';
					var create_inv_disable = false;
					var view_inv_disable = true;
					var create_inv_k1_disable = false;
					var create_inv_k2_disable = false;
					var invoice_id;
					if (rowData.pHasInvoice == 'True') {
						create_inv = 'Update Invoice';
						view_inv_disable = false;
						invoice_id = rowData.pInvoiceId;

						if (rowData.pIsInvoiceApproved == 'True') {
						    create_inv_disable = true;
							create_inv_k1_disable = true;
							create_inv_k2_disable = true;
						}else{
                            if (rowData.pInvoiceType == 'job_invoice') {
                                create_inv_k1_disable = true;
                            }else if (rowData.pInvoiceType == 'job_invoice_cash'){
                                create_inv_k2_disable = true;
                            }
						}

						//disable delete if invoice exist
						delete_disable	=	true;
					}

					//Job Card Status disable the current status.
					var status = rowData.pStatusId;
					//console.log(status);
					var cStatusItemK1_disable = false;
					var cStatusItemK2_disable = false;
					var cStatusItemK3_disable = false;
					var cStatusItemK4_disable = false;
					var cStatusItemK5_disable = false;
					var cStatusItemK6_disable = false;
					var cStatusItemK7_disable = false;
					var cStatusItemK8_disable = false;
					if (status == 1) {
						cStatusItemK1_disable = true;
					} else if (status == 2) {
						cStatusItemK2_disable = true;
					}
					else if (status == 3) {
						cStatusItemK3_disable = true;
					}
					else if (status == 4) {
						cStatusItemK4_disable = true;
					}
					else if (status == 5) {
						cStatusItemK5_disable = true;
					}
					else if (status == 6) {
						cStatusItemK6_disable = true;
					}
					else if (status == 7) {
						cStatusItemK7_disable = true;
					}
					else if (status == 8) {
						cStatusItemK8_disable = true;
					}

					return {
						callback: function(key, options) {
							
							//Edit jobcard
							if (key == "edit") {
								//console.log("inside Edit?");
								redirectEditPage(rowData);
							}
							//Print jobcard
							if (key == "print") {
								console.log("print");
								console.log(rowData);
								
								if(rowData.pAckURL){
									
								var newWindow = window.open(rowData.pAckURL);
								newWindow.print();
								
								}
							}

							//Jobcard Advance payment
							if (key == "advance") {
								console.log("advance");
								jobcard_advance_payment(rowData.pId);
							}


							// update jobcard status
							if (key == "cStatus_key1" || key == "cStatus_key2" || key == "cStatus_key3" || key == "cStatus_key4" || key == "cStatus_key5" ||
								key == "cStatus_key6" || key == "cStatus_key7" || key == "cStatus_key8") {

								//console.log("inside Change Job Status");
								var id = rowData.pId;
								var status = options.items.cStatus.commands[key].data;
								//console.log(id);
								//console.log(status);

								//td status
								var tdStatus = row.find("td:last");
								if ($(tdStatus).has('label').length > 0) {
									var labelEle = $(tdStatus).find('label');
									onChangeJobCardStatus(id, status, labelEle);
								}

							}

							//create estimate
							if (key == "create_est") {
								//console.log("create estimation");
								create_estimation(rowData.pId);
							}

							//view estimate
							if (key == "view_est") {
								//console.log("view estimation");
								//console.log(options.items.view_est.data);
								view_estimation(options.items.view_est.data);
							}

							//Create Invoice
							if (key == "create_inv_key2" || key == "create_inv_key1") {
								console.log("create invoice");
								console.log("key"+key);
								console.log("rowData"+rowData.pStatusId);
								if(rowData.pStatusId == 6|| rowData.pStatusId == 7 || rowData.pStatusId == 8)
								{
									create_invoice(rowData.pId,key);

								}
								else if(rowData.pStatusId)
								{
									showAlertMsg("Job Card status should be in 'Final Inspected' or 'Vehicle Ready' to create invoice.","error");
								}


							}

							//view Invoice
							if (key == "view_inv") {
								console.log("view invoice");
								console.log(options.items.view_inv.data);
								// TODO: Need to change the route for view invoice which Anitha has already developed, waiting for her checkin
								view_invoice(options.items.view_inv.data,rowData.pInvoiceType);
							}


							//jobcard acknowledgement
							if(key == "ack_customer"){
								console.log("ack_customer");
								/* Loading Indicator */
								new imageLoader(cImageSrc, 'startAnimation()');
								jobcard_acknowledgement(rowData.pId);
							}


						},
						items: {
							"jcno": {
								name: "",
								type: 'text',
								disabled: true,
								value: pOrderNo,
								className: 'contextmenu-item-custom_text'
							},
							"edit": { name: 'Edit', icon: "fa-edit" },
							// "print": { name: "Print", icon: "fa-cut" }, <i class="fa fa-comment"></i> Ack to Customer
							//"sms": { name: "Ack to Customer", icon: "fa fa-comment" },
							"sep1": "---------",
							"advance": { name: "Advance Payment", icon: "fa-money" },
							"sep2": "---------",
							"cStatus": {
								name: "Change Status", icon: "fa-step-forward",
								"items": {
									"cStatus_key1": { "name": "New", "disabled": cStatusItemK1_disable, data: 1 },
									"cStatus_key2": { "name": "First Inspected", "disabled": cStatusItemK2_disable, data: 2 },
									"cStatus_key3": { "name": "Estimation Pending", "disabled": cStatusItemK3_disable, data: 3 },
									"cStatus_key4": { "name": "Estimation Approved", "disabled": cStatusItemK4_disable, data: 4 },
									"cStatus_key5": { "name": "Work in Progress", "disabled": cStatusItemK5_disable, data: 5 },
									"cStatus_key6": { "name": "Final Inspected", "disabled": cStatusItemK6_disable, data: 6 },
									"cStatus_key7": { "name": "Vehicle Ready", "disabled": cStatusItemK7_disable, data: 7 },
									"cStatus_key8": { "name": "Close", "disabled": cStatusItemK8_disable, data: 8 }
								}
							},
							"sep3": "---------",
							"create_est": { name: create_est, icon: "fa-calculator", },
							"view_est": { name: "View Estimation", icon: "fa-eye", "disabled": view_est_disable, data: estimate_id },
							"sep4": "---------",
							//"create_inv": { name: create_inv, icon: "fa-list-alt" },
							"create_inv": {
								name: create_inv, icon: "fa-step-forward","disabled": create_inv_disable,
								"items": {
									"create_inv_key1": { "name": "Cash",icon: "fa-money", "disabled": create_inv_k1_disable},
									"create_inv_key2": { "name": "Credit", icon: "fa-credit-card", "disabled": create_inv_k2_disable},
								}
							},
							
							"view_inv": { name: "View Invoice", icon: "fa-eye", "disabled": view_inv_disable , data: invoice_id },
							"sep5": "---------",
							"ack_customer": { name: "Ack to Customer", icon: "fa fa-comment" },
							"sep6": "---------",
							"print": { name: "print", icon: "fa-print" },
						},
						events: {
							show: function(options) {
								//console.log('Context Menu - Show');
							},
							hide: function(options) {
								//console.log('Context Menu - hide');
							},
							activated: function(options) {
								//console.log('Context Menu -  Activated');
							}
						}
					};
				}
			});


		},


	});
});

//to print job card

	function print_transaction(id) 
	{
		console.log("jc_print");
		// $('.loader_wall_onspot').show();
		// $('body').css('overflow', 'hidden');
		
		/* Loading Indicator */
		new imageLoader(cImageSrc, 'startAnimation()');
		$('.print_content').animate({ height: ($(window).height() + 1000) + 'px' }, 400, function() {

			$.ajax({
				url: jobcard_print_route,
				type: 'post',
				data: {
					_token : csrf_token,
					id: id
				},
				success:function(data, textStatus, jqXHR) {

					$('.print_content').show();
					$('.print_content').find('.modal-footer').show();

					var container = $('.print_content').find("#print");
					container.html("");

					var specifications = data.specification;
					var spec ='';
					
					if(specifications == null){
						spec = '';
					}else{
						specifications = data.specification;
						spec = specifications.split(",",4).join('<br>');
					}
					/*var spec ='';
					if(specifications == null){
						specifications = '';
						spec = '';
					}else{
						specifications = data.specification.spec;
						spec = specifications.split(",",4).join('<br>');
					}*/


					if(container.html(data.transaction_data)) {

						$('#print').css({'height' : ($(window).height() - 100) + 'px', 'overflow-y' : 'auto' });

						container.find("[data-value='voucher_type']").text(data.transaction_type);
						container.find("[data-value='po']").text(data.po_no);
						container.find("[data-value='purchase']").text(data.purchase_no);
						container.find("[data-value='grn']").text(data.grn_no);
						container.find("[data-value='date']").text(data.date);
						container.find("[data-value='payment_mode']").text(data.payment_mode);
						container.find("[data-value='resource_person']").text(data.resource_person);
						container.find("[data-value='customer_address']").text(data.customer_address);
						container.find("[data-value='shipping_address']").text(data.shipping_address);
						container.find("[data-value='billing_address']").text(data.billing_address);
						container.find("[data-value='customer_vendor']").text(data.customer_vendor);
						container.find("[data-value='vehicle_number']").text(data.vehicle_number);
						container.find("[data-value='make_model_variant']").text(data.make_model_variant);
						container.find("[data-value='company_name']").text(data.company_name);
						container.find("[data-value='company_phone']").text(data.company_phone);
						container.find("[data-value='company_address']").text(data.company_address);
						container.find("[data-value='email_id']").text(data.email_id);
						container.find("[data-value='amount']").text(data.amount);
						container.find("[data-value='payment_method']").text(data.payment_method);
						container.find("[data-value='km']").text(data.km);
						container.find("[data-value='assigned_to']").text(data.assigned_to);
						container.find("[data-value='company_gst']").text(data.company_gst);
						container.find("[data-value='customer_gst']").text(data.customer_gst);
						container.find("[data-value='driver']").text(data.driver);
						container.find("[data-value='driver_mobile_no']").text(data.driver_mobile_no);
						container.find("[data-value='warranty']").text(data.warranty);
						container.find("[data-value='insurance']").text(data.insurance);
						container.find("[data-value='mileage']").text(data.mileage);
						container.find("[data-value='engine_no']").text(data.engine_no);
						container.find("[data-value='chassis_no']").text(data.chassis_no);
						container.find("[data-value='specification']").html(spec);
						container.find("[data-value='job_due_on']").text(data.job_due_on);
						container.find("[data-value='last_visit_on']").text(data.last_visit_on);
						container.find("[data-value='next_visit_on']").text(data.next_visit_on);
						container.find("[data-value='service_on']").text(data.service_on);
						container.find("[data-value='last_visit_jc']").text(data.last_visit_jc);


						/*Job card print*/
						

						var row = container.find('.job_card_table tbody tr').clone();

						var job_card_item = ``;

						var total_job_items_length = 12;

						var job_card_length = total_job_items_length - (data.job_card_items).length;
					

							for (var i = 0; i < (data.job_card_items).length; i++) {

							var j = i + 1;

							var new_row = row.clone();



							new_row.find('.col_s_no').text(j);

							new_row.find('.col_items').text(data.job_card_items[i].item_name);

							new_row.find('.col_qty').text(data.job_card_items[i].qty);

							new_row.find('.col_total_price').text(data.job_card_items[i].amt);

							job_card_item += `<tr>`+new_row.html()+`</tr>`;

						}

						for(var i=1; i <= job_card_length;i++){
							var job_new_row = row.clone();
							job_card_item += `<tr>`+job_new_row.html()+`</tr>`;

						}
						
						container.find('.job_card_table tbody').empty();

						container.find('.job_card_table tbody').append(job_card_item);

						var complaints = data.complaints;
						if(complaints != null){
							var vehicle_complaints = complaints.split('\n',8).join('<br>');
						}else{
							var vehicle_complaints = '';
						}

						container.find("[data-value='complaints']").html(vehicle_complaints);

						var checklist_details = Object.values(data.checklist_details);

						var row = container.find('.checklist tbody tr').clone();

						var checklist = ``;

						var total_checklist = 12;
						
						var checklist_total = total_checklist - (checklist_details).length;

						for (var i = 0; i < (checklist_details).length; i++) {
							var check_new_row = row.clone();
							
							check_new_row.find('.col_checklist').text(checklist_details[i].checklist);
							check_new_row.find('.col_notes').text(checklist_details[i].notes);

							checklist += `<tr>`+check_new_row.html()+`</tr>`;
						}
						
						for(var i=1; i <= checklist_total;i++){
							var check_new_row = row.clone();
							checklist += `<tr>`+check_new_row.html()+`</tr>`;

						}

						
						container.find('.checklist tbody').empty();
						container.find('.checklist tbody').append(checklist);
						var fuel_value = data.fuel_level;
						var fuel = ``;
						if(fuel_value != null){
							fuel = data.fuel_level[0].notes;
						}else{
							fuel = '';
						}
						
						container.find("[data-value='fuel_checklist']").text(fuel);
						container.find("[data-value='top']").text(data.first_checklists[4].notes);
						container.find("[data-value='right']").text(data.first_checklists[3].notes);
						container.find("[data-value='left']").text(data.first_checklists[2].notes);
						container.find("[data-value='front']").text(data.first_checklists[1].notes);
						container.find("[data-value='back']").text(data.first_checklists[0].notes);

						/*END*/
					
						
						var row_color = container.find('.item_table tbody tr:nth-child(2)').css('backgroundColor');

						var row = container.find('.invoice_item_table tbody tr').clone();

						var invoice_items = ``;
						var total_amount = 0;
						var total_discount = 0;
						var  total_length= 10;
						var length = total_length - (data.invoice_items).length;
						for (var i = 0; i < (data.invoice_items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();
							var discount = data.invoice_items[i].discount;
							var discount_value = $.parseJSON(discount);
							var amount= data.items[i].rate * data.items[i].quantity - discount_value.amount;
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.invoice_items[i].name);
							new_row.find('.col_hsn').text(data.invoice_items[i].hsn);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_discount').text(discount_value.amount);
							new_row.find('.col_rate').text(parseFloat(data.items[i].rate).toFixed(2));
							new_row.find('.col_amount').text(parseFloat(data.items[i].amount).toFixed(2));
							new_row.find('.col_t_amount').text(parseFloat(amount).toFixed(2));
							
						var total_amount = parseFloat(amount)+parseFloat(total_amount);

						var total_discount = parseFloat(discount_value.amount)+parseFloat(total_discount);

							invoice_items += `<tr>`+new_row.html()+`</tr>`;
						}

						for(var i=1; i <= length;i++){

							var new_row = row.clone();
							
							invoice_items += `<tr>`+new_row.html()+`</tr>`;

						}

						container.find("[data-value='total_discount']").text(total_discount);
						container.find("[data-value='total_amount']").text(parseFloat(total_amount).toFixed(2));
						container.find('.invoice_item_table tbody').empty();
						container.find('.invoice_item_table tbody').append(invoice_items);
						
						var hsn_invoice_tax_values = Object.values(data.hsn_based_invoice_tax);
					
					//HSN based tax table
						var hsn_row = container.find('.hsnbasedTable tbody tr').clone();
						var hsn_tax = ``;
						var  totalhsn_length= 6;
						var hsn_length = totalhsn_length - hsn_invoice_tax_values.length;
						for(var i = 0; i < hsn_invoice_tax_values.length; i++){
							var hsn_new_row = hsn_row.clone();
							var taxable = parseFloat(hsn_invoice_tax_values[i].taxable).toFixed(2);
							var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
							var gst = hsn_invoice_tax_values[i].name;
							var sgst = hsn_invoice_tax_values[i].display_name;
							if(sgst != null){
								var sgst_value = sgst.split('CGST');
							}else{
								sgst_value = '';
							}
							
							if(gst == null){
								var exact_tax = '';
							}else{
								var exact_tax = sgst_value[0];
							}
							if(hsn_invoice_tax_values[i].tax_type == 1){
							hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
							hsn_new_row.find('.col_tax_value').text(taxable);
							hsn_new_row.find('.col_igst').text("");
							hsn_new_row.find('.col_igst_amount').text("");
							hsn_new_row.find('.col_cgst').text(exact_tax);
							hsn_new_row.find('.col_cgst_amount').text(tax_amount);
							hsn_new_row.find('.col_sgst').text(exact_tax);
							hsn_new_row.find('.col_sgst_amount').text(tax_amount);
							}else{
								var tax_amount = parseFloat(hsn_invoice_tax_values[i].Tax_amount).toFixed(2);
								var igst = hsn_invoice_tax_values[i].display_name;
								if(igst != null){
									var exact_igst = igst.split('IGST');
								}else{
									exact_igst = '';
								}
								
								if(gst == null){
									var exact_tax = '';
								}else
								{
									var exact_tax = exact_igst[0];
								}
							hsn_new_row.find('.col_sac').text(hsn_invoice_tax_values[i].hsn);
							hsn_new_row.find('.col_tax_value').text(taxable);
							hsn_new_row.find('.col_igst').text(exact_tax);
							hsn_new_row.find('.col_igst_amount').text(tax_amount);
							hsn_new_row.find('.col_cgst').text("");
							hsn_new_row.find('.col_cgst_amount').text("");
							hsn_new_row.find('.col_sgst').text("");
							hsn_new_row.find('.col_sgst_amount').text("");
							}
							hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;
						}

						for(var i=1; i <= hsn_length;i++){

							var hsn_new_row = hsn_row.clone();
							
							hsn_tax += `<tr>`+hsn_new_row.html()+`</tr>`;


						}
						container.find('.hsnbasedTable tbody').empty();
						container.find('.hsnbasedTable tbody').append(hsn_tax);
						var invoice_tax_values = Object.values(data.invoice_tax);
						var tax_row = container.find('.floatedTable tbody tr').clone();
						var invoice_tax = ``;
						var  total_length= 6;
						var gst_length = total_length - invoice_tax_values.length;
						var total_cgst = 0;
						var total_sgst = 0;
						var total_igst = 0;
						for (var i = 0; i < invoice_tax_values.length; i++) {
							var new_row = tax_row.clone();  
							var gst = invoice_tax_values[i].name;
							var sgst = invoice_tax_values[i].display_name;
							var tax_amount = parseFloat(invoice_tax_values[i].Tax_amount).toFixed(2);
							var taxable = parseFloat(invoice_tax_values[i].taxable).toFixed(2);
							if(gst == null){
								var exact_value = '';
								var exact_sgst  = '';

							}else{
								var exact_value = gst.split('GST');
								var exact_sgst = sgst.split('SGST');
							}
						if(invoice_tax_values[i].tax_type == 1){
							new_row.find('.col_gst').html(exact_value[0]);
							new_row.find('.col_tax_value').text(taxable);
							new_row.find('.col_igst').text("");
							new_row.find('.col_igst_amount').text("");
							new_row.find('.col_cgst').text(exact_sgst[0]);
							new_row.find('.col_cgst_amount').text(tax_amount);
							new_row.find('.col_sgst').text(exact_sgst[0]);
							new_row.find('.col_sgst_amount').text(tax_amount);

							
							var total_cgst = parseFloat(tax_amount)+parseInt(total_cgst);
							

							var total_sgst = parseFloat(tax_amount)+parseInt(total_sgst);


							
					}
					else{
						if(gst == null){
								var exact_value = '';
								var exact_sgst  = '';
								var taxable = '';
								var tax_amount = '';
							}else{
								var exact_value = gst.split('IGST');
								var exact_sgst = sgst.split('IGST');
							}

							new_row.find('.col_gst').text(exact_value[0]);
							new_row.find('.col_tax_value').text(taxable);
							new_row.find('.col_igst').text(exact_sgst[0]);
							new_row.find('.col_igst_amount').text(tax_amount);
							new_row.find('.col_cgst').text("");
							new_row.find('.col_cgst_amount').text("");
							new_row.find('.col_sgst').text("");
							new_row.find('.col_sgst_amount').text("");
					
							if (tax_amount == ''){
								var tax_amount = 0;
							}else{
								var tax_amount = tax_amount;
							}
						var total_igst = parseFloat(tax_amount)+parseInt(total_igst);



					}		

							
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						
				}

				
						for(var i=1; i <= gst_length;i++){

							var new_row = tax_row.clone();
							
							invoice_tax += `<tr>`+new_row.html()+`</tr>`;


						}

						

				var  total_tax = total_cgst + total_sgst + total_igst;
				var round_of = Math.ceil(total_tax);
				var Rount_off_value = round_of - total_tax;
				var total = total_tax + total_amount;
				var total_amount= Rount_off_value + total;

			var total_withtax = Math.ceil(total_amount);
						var words = new Array();
									words[0] = '';
									words[1] = 'One';
									words[2] = 'Two';
									words[3] = 'Three';
									words[4] = 'Four';
									words[5] = 'Five';
									words[6] = 'Six';
									words[7] = 'Seven';
									words[8] = 'Eight';
									words[9] = 'Nine';
									words[10] = 'Ten';
									words[11] = 'Eleven';
									words[12] = 'Twelve';
									words[13] = 'Thirteen';
									words[14] = 'Fourteen';
									words[15] = 'Fifteen';
									words[16] = 'Sixteen';
									words[17] = 'Seventeen';
									words[18] = 'Eighteen';
									words[19] = 'Nineteen';
									words[20] = 'Twenty';
									words[30] = 'Thirty';
									words[40] = 'Forty';
									words[50] = 'Fifty';
									words[60] = 'Sixty';
									words[70] = 'Seventy';
									words[80] = 'Eighty';
									words[90] = 'Ninety';
									amount = total_withtax.toString();
									var atemp = amount.split(".");
									var number = atemp[0].split(",").join("");
									var n_length = number.length;
									var words_string = "";
					if (n_length <= 9) {
						var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
						var received_n_array = new Array();
						for (var i = 0; i < n_length; i++) {
							received_n_array[i] = number.substr(i, 1);
						}
						for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
							n_array[i] = received_n_array[j];
						}
						for (var i = 0, j = 1; i < 9; i++, j++) {
							if (i == 0 || i == 2 || i == 4 || i == 7) {
								if (n_array[i] == 1) {
									n_array[j] = 10 + parseInt(n_array[j]);
									n_array[i] = 0;
								}
							}
						}
						value = "";
						for (var i = 0; i < 9; i++) {
							if (i == 0 || i == 2 || i == 4 || i == 7) {
								value = n_array[i] * 10;
							} else {
								value = n_array[i];
							}
							if (value != 0) {
								words_string += words[value] + " ";
							}
							if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
								words_string += "Crores ";
							}
							if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
								words_string += "Lakhs ";
							}
							if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
								words_string += "Thousand ";
							}
							if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
								words_string += "Hundred and ";
							} else if (i == 6 && value != 0) {
								words_string += "Hundred ";
							}
						}
						words_string = words_string.split("  ").join(" ");
					}






					container.find("[data-value='total_cgst']").text(total_cgst.toFixed(2));
					container.find("[data-value='total_sgst']").text(total_sgst.toFixed(2));
					container.find("[data-value='total_igst']").text(total_igst.toFixed(2));
					container.find("[data-value='round_off']").text(Rount_off_value.toFixed(2));
					container.find("[data-value='total_amountwithtax']").text(parseFloat(total_withtax).toFixed(2));
					container.find("[data-value='rupees']").text(words_string+"Only");
					container.find('.floatedTable tbody').empty();
					container.find('.floatedTable tbody').append(invoice_tax);                     

					var row = container.find('.no_tax_item_table tbody tr').clone();

						var no_tax_sale = ``;
						var total_tax_amount = 0;
						var sub_total_amount = 0;
						for (var i = 0; i < (data.no_tax_sale).length; i++) {
							var j = i + 1;
							var new_row = row.clone();
							
							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.no_tax_sale[i].name);
							new_row.find('.col_quantity').text(data.no_tax_sale[i].quantity);
							new_row.find('.col_rate').text(data.no_tax_sale[i].rate);
							new_row.find('.col_discount').text(data.no_tax_sale[i].discount);
							new_row.find('.col_amount').text(parseFloat(data.no_tax_sale[i].amount).toFixed(2));
							var tax_amount = data.no_tax_sale[i].tax_amount;
							var total_tax_amount = parseFloat(tax_amount) + parseFloat(total_tax_amount);
							var sub_total_amount = parseFloat(data.no_tax_sale[i].amount) + parseFloat(sub_total_amount);
							no_tax_sale += `<tr>`+new_row.html()+`</tr>`;
						}

						
						var total_amount_withtax = parseFloat(total_tax_amount) + parseFloat(sub_total_amount);
						
						container.find('.total_table .invoice_sub_total').text(parseFloat(sub_total_amount).toFixed(2));
						container.find('.total_table .tax_value').text(parseFloat(total_tax_amount).toFixed(2));
						container.find('.total_table .invoice_total_amount').text(total_amount_withtax.toFixed(2));
						container.find('.no_tax_item_table tbody').empty();
						container.find('.no_tax_item_table tbody').append(no_tax_sale);


						var row = container.find('.item_table tbody tr').clone();

						var items = ``;

						for (var i = 0; i < (data.items).length; i++) {
							var j = i + 1;
							var new_row = row.clone();

							new_row.find('.col_id').text(j);
							new_row.find('.col_desc').text(data.items[i].name);
							new_row.find('.col_hsn').text(data.items[i].hsn);
							new_row.find('.col_gst').text(data.items[i].gst);
							new_row.find('.col_discount').text(data.items[i].discount);
							new_row.find('.col_quantity').text(data.items[i].quantity);
							new_row.find('.col_rate').text(data.items[i].rate);
							new_row.find('.col_amount').text(data.items[i].amount);

							items += `<tr>`+new_row.html()+`</tr>`;
						}

						container.find('.item_table tbody').empty();

						container.find('.item_table tbody').append(items);

						container.find('.total_table .sub_total').text(data.sub_total);
						container.find('.total_table .total').text(data.total);

						var discount_row = container.find('.total_table .discounts').clone();
						var tax_row = container.find('.total_table .taxes').clone();

						var total = ``;

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.discounts).length; i++) {

							var new_row = discount_row.clone();

							new_row.find('.discount_name').text(data.discounts[i].key);
							new_row.find('.discount_value').text(data.discounts[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}

						for (var i = 0; i < (data.taxes).length; i++) {

							var new_row = tax_row.clone();

							new_row.find('.tax_name').text(data.taxes[i].key);
							new_row.find('.tax_value').text(data.taxes[i].value);

							total += `<tr>`+new_row.html()+`</tr>`;
						}
						container.find('.total_table .discounts, .total_table .taxes').remove();
						container.find(".total_table tr").first().after(total);

						var divToPrint=document.getElementById('print');
						var newWin=window.open('','Propel');


						newWin.document.open();
						newWin.document.write(`<html>
							<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
							<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></scr`+`ipt>
							<style> .item_table { border-collapse: collapse; border-width: 0px; border: none; } .total_container td { padding: 5px; } @media print {  } </style> <body>`+divToPrint.innerHTML+`
							<script> 

							window.onload=function() { window.print(); }

							$(document).ready(function() {
		


								$('body').on('click', '.print', function() {
								//printDiv();
								});



						}); </scr`+`ipt>


						</body></html>`);

						
						newWin.document.close();

						$('.print_content #print').removeAttr('style');
						$('.print_content #print').html("");
						$('.print_content').removeAttr('style');
						$('.print_content .modal-footer').hide();
						$('.print_content').animate({top: '0px'}); 
						$('body').css('overflow', '');

					}

					
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');

				}
			});

		});
			
	}
//jobcard advance payment

//function jobcard_advance_payment(id)
//{
//	console.log("jobcard_advance_payment");
//	$.ajax({
//		url: jobcard_advance_route,
//		type: 'post',
//		data:
//		{
//			_token: csrf_token,
//			id :id,
//
//		},
//		success:function(data)
//		{
//			console.log(data);
//			var payment = data.payment;
//			var ledgers = data.ledgers;
//			//$('select[name=job_card]').html(`<option value=`+data.selected_job_card.id+`>`+data.selected_job_card.order_no+`</option>`);
//			$('input[name=job_card]').val(data.selected_job_card.order_no);
//			$('input[name=job_card]').attr('data-id',data.selected_job_card.id);
//			$('.people').hide();
//			if(data.name.user_type == 0)
//			{
//				$('.people').show();
//				$('.business').hide();
//				$('.people').find('select').prop('disabled', false);
//				$('.business').find('select').prop('disabled', true);
//				$('#people_type').prop('checked',true);
//				// $('select[name=people_id]').html("<option value='"+data.name.person_id+"'>"+data.name.display_name+"</option>")
//				// $('select[name=people_id]').val(data.name.person_id);
//				$('input[name=people_id]').val(data.name.display_name);
//				$('input[name=people_id]').attr('data-id',data.name.person_id);
//
//			}
//			else if(data.name.user_type == 1)
//			{
//				$('.business').show();
//				$('.people').hide();
//				$('.business').find('select').prop('disabled', false);
//				$('.people').find('select').prop('disabled', true);
//				$('#business_type').prop('checked',true);
//				// $('select[name=people_id]').html("<option value='"+data.name.business_id+"'>"+data.name.display_name+"</option>")
//				// $('select[name=people_id]').val(data.name.business_id);
//				$('input[name=people_id]').val(data.name.display_name);
//				$('input[name=people_id]').attr('data-id',data.name.business_id);
//
//			}
//				$('.invoice_modal').find('select[name=invoice_payment_method]').html('');
//				$('.invoice_modal').find('select[name=invoice_payment_ledger]').html('');
//				for (var i in payment)
//				{
//					$('.invoice_modal').find('select[name=invoice_payment_method]').append("<option value='"+payment[i].id+"'>"+payment[i].display_name+"</option>");
//				}
//				for (var i in ledgers)
//				{
//					$('.invoice_modal').find('select[name=invoice_payment_ledger]').append("<option value='"+ledgers[i].id+"'>"+ledgers[i].name+"</option>");
//				}
//
//				$('.invoice_modal').modal('show');
//
//
//		}
//	});
//}


//on row select event
$('#datatable tbody').on('dblclick', 'tr>td', function() {
	// get data from datatable row
	// passing param in route
	//doesn't allow for status column
	var data = datatable.row(this).data();
	redirectEditPage(data);

	//console.log(data);
});

//reset dateFields
function onSetDateToFields(fromDate, toDate) {
	from_date = fromDate;
	to_date = toDate;
	$fromDateField.datepicker('setDate', new Date(from_date));
	$toDateField.datepicker('setDate', new Date(to_date));
}




//reset dateFields
function onRestDateFields() {
	from_date = "";
	to_date = "";
	$fromDateField.datepicker('setDate', null);
	$toDateField.datepicker('setDate', null);
}

//reset pagination
$('body').on('click', '.reset', function(e) {
	console.log("Inside Reset");
	$('select[name=date_range]').val('LAST_24_HOURS');
	$('select[name=jobcard_status]').val('ALL');
	$('input[name=search_text]').val('');
	onRestDateFields();
	datatable.ajax.reload();
});





//on row select
function redirectEditPage(data) {
	var url = jobcard_edit_route;
	url = url.replace(':id', data.pId);
	new imageLoader(cImageSrc, 'startAnimation()');
	window.location.href = url;
}

$('.add').on('click', function() {
	var url = jobcard_create_route;
	new imageLoader(cImageSrc, 'startAnimation()');
	window.location.href = url;

});



//job card status update
function onChangeJobCardStatus(id, status, labelEle) {
	
	new imageLoader(cImageSrc, 'startAnimation()');
	$.ajax({
		url: jobcard_change_Status_route,
		type: 'post',
		data: {
			_token: csrf_token,
			id: id,
			status: status
		},
		dataType: "json",
		success: function(data, textStatus, jqXHR) {
			//console.log(data);

			new imageLoader(cImageSrc, 'stopAnimation()');
			if (data.status == "SUCCESS") {

				//remove all similar 'badge-' class
				// $(labelEle).removeClass(function(index, className) {
				// 	return (className.match(/(^|\s)badge-\S+/g) || []).join(' ');
				// });

				// let badgeClass = onSetLabelClass(status);
				// let jobCardStatus = onSetJobCardStatus(status);
				// $(labelEle).addClass(badgeClass);
				// $(labelEle).text(jobCardStatus);
				datatable.ajax.reload();
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			//alert("New Request Failed " +textStatus);
		}
	});

}

function isValidDateInputs(from_date, to_date) {
	if (from_date && to_date) {
		if (Date.parse(new Date(from_date)) > Date.parse(new Date(to_date))) {
			showAlertMsg("From Date should not be greater than To Date.","error");
			return false;
		}
		return true;
	}else if (!from_date && !to_date) {
		showAlertMsg("Please choose From Date and To Date(optional).","error");
		return false;
	}
	if (from_date && !to_date) {
		$toDateField.datepicker('setDate', new Date());
		return true;
	}
}

function onSetLabelClass(status) {
	let badgeClass = '';
	if (status == 1) {
		badgeClass = 'badge-default';
	} else if (status == 2) {
		badgeClass = 'badge-success';
	}
	else if (status == 3) {
		badgeClass = 'badge-warning';
	}
	else if (status == 4) {
		badgeClass = 'badge-danger';
	}
	else if (status == 5) {
		badgeClass = 'badge-default';
	}
	else if (status == 6) {
		badgeClass = 'badge-primary';
	}
	else if (status == 7) {
		badgeClass = 'badge-info';
	}
	else if (status == 8) {
		badgeClass = 'badge-warning';
	}
	return badgeClass;
}

function onSetJobCardStatus(status) {
	console.log(status);
	let jobStatus = '';
	if (status == 1) {
		jobCardStatus = 'New';
	}
	else if (status == 2) {
		jobCardStatus = 'First Inspected';
	}
	else if (status == 3) {
		jobCardStatus = 'Estimation Pending';
	}
	else if (status == 4) {
		jobCardStatus = 'Estimation Approved';
	}
	else if (status == 5) {
		jobCardStatus = 'Work in Progress';
	}
	else if (status == 6) {
		jobCardStatus = 'Final Inspected';
	}
	else if (status == 7) {
		jobCardStatus = 'Vehicle Ready';
	}
	else if (status == 8) {
		jobCardStatus = 'Closed';
	}

	return jobCardStatus;
}

//filter in index page
$('.search').on('click', function(e) {
	e.preventDefault();
	//console.log("inside Search?");
	from_date = $fromDateField.val();
	to_date = $toDateField.val();

	//validate from date and to date
	if (isValidDateInputs(from_date, to_date)) {
		$('#datatable').DataTable().draw(true);
	}
});

$('select[name=jobcard_status]').on('change', function() {
	//console.log($(this).val());
	if (!$(this).val()) {
		$(this).val('ALL');
	}
})

$dateRangeField.on('change', function() {

	// $('.loader_wall').show();
	//console.log(dateRangeList);
	let dateRangeKey = $(this).val();
	//console.log(dateRangeKey);
	if (!dateRangeKey) {
		dateRangeKey = 'LAST_24_HOURS';
		$(this).val(dateRangeKey);
	}


	if (dateRangeKey != 'CUSTOM') {
		$fromDateField.attr("disabled", true);
		$toDateField.attr("disabled", true);
		onSetDateRangetoFields(dateRangeKey);
	} else {
		$fromDateField.attr("disabled", false);
		$toDateField.attr("disabled", false);
		onRestDateFields();
	}
})


/* onSetDateRangetoFields */

function onSetDateRangetoFields(dateRangeKey) {
	//
	let selectedDateRange = dateRangeList[dateRangeKey];
	onSetDateToFields(selectedDateRange.fromDate, selectedDateRange.toDate);

}
/* TODO: DATE Validation But Its throw Maximum call stack size exceeded Error */
// $toDateField.on("change",function(e){
//     console.log(e);
//     $toDateField.datepicker();
//    let toDate = $toDateField.val();
//    let fromDate = $fromDateField.val();
//    let dateInputs = isValidDateInputs(fromDate,toDate);

//    if(!dateInputs){
//        onRestDateFields();
//    }
//    //to avoid maximum call exceed
// })