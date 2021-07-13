
//selected parts table 
var selected_item_parts_table = $('#selected_item_parts_table').DataTable({
	"lengthMenu": [[16, 20, 30, -1], [16, 20, 30, "All"]],
	"paging": false,
	"columns": [
		{ width: '50%' },
		{ width: '30%' },
		{ width: '10%' },
		{ width: '5%' },
		{ width: '5%' }
	],
	drawCallback: function(data) {
		var api = this.api();
		console.log(api);
		$.contextMenu({
			selector: '#selected_item_parts_table tbody tr td',
			callback: function(key, options) {

				console.log("Context Menu selected key ");
				console.log(key);

				var row = $(this).closest('tr');
				console.log(row);
				var rowData = selected_item_parts_table.row(row).data();
				console.log(rowData);

				//delete item
				if (key == "delete") {
					console.log("inside delete");
					//call delete event with rowData
					selected_item_parts_table.row(row).remove().draw(false);
				}

			},
			items: {
				"delete": { name: "Delete", icon: "fa-trash" }
			},
			events: {
				show: function(options) {
					console.log('Context Menu - Show');
					console.log(options);
				},
				hide: function(options) {
					console.log('Context Menu - hide');
					console.log(options);

				},
				activated: function(options) {
					console.log('Context Menu -  Activated');
					console.log(options);
				}
			}
		});
	}
});

//selected service table
var selected_item_service_table = $('#selected_item_service_table').DataTable({
	"lengthMenu": [[16, 20, 30, -1], [16, 20, 30, "All"]],
	"paging": false,
	drawCallback: function(data) {
		var api = this.api();
		console.log(api);
		$.contextMenu({
			selector: '#selected_item_service_table tbody tr td',
			callback: function(key, options) {

				console.log("Context Menu selected key ");
				console.log(key);

				var row = $(this).closest('tr');
				var rowData = selected_item_service_table.row(row).data();
				console.log(rowData);

				//delete item
				if (key == "delete") {
					console.log("inside delete");
					//call delete event with rowData
					selected_item_service_table.row(row).remove().draw(false);
				}

			},
			items: {
				"delete": { name: "Delete", icon: "fa-trash" }
			},
			events: {
				show: function(options) {
					console.log('Context Menu - Show');
					console.log(options);
				},
				hide: function(options) {
					console.log('Context Menu - hide');
					console.log(options);

				},
				activated: function(options) {
					console.log('Context Menu -  Activated');
					console.log(options);
				}
			}
		});
	}
});


/* Image Upload */

Dropzone.autoDiscover = false;

$('.dropzone').each(function(index) {
	$(this).dropzone({
		url: jobcard_store_route,
		autoProcessQueue: false,
		paramName: 'postedFile',
		uploadMultiple: true, // uplaod files in a single request
		//  parallelUploads: 10, // use it with uploadMultiple
		addRemoveLinks: true,
		dictDefaultMessage: 'Drop files or click here to upload',
		acceptedFiles: 'image/jpeg,image/png',
		maxFilesize: 1, // MB
		maxFiles: 5 // maximum upload files
	});
});

var customer_table = $('#customer_table').DataTable({ searching: false, paging: false, info: false });
var $serviceField = $('select[name=service_type]');
var $shipmentModeField = $('select[name=shipment_mode_id]');

var $vehicleExistingField = $('input[name=vehicle_existing]');
var $customerExistingField = $('input[name=customer_existing]');
var $span_mgeEle = $('.span_mge');
var $engineNoField = $('#engine_no');
var $chassisNoField = $('#chassis_no');
var $manuYearField = $('#manufacturing_year');
var $lastUpdateDateField = $('#last_update_date');
var $lastUpdateJCField = $('#last_update_jc');
var $vehicleCategoryField = $('input[name=vehicle_category]');
var $vehicleInsuranceField = $('#vehicle_insurance');
var $insuranceDueField = $('#insurance_due');
var $permitDueField = $('#permit_due');
var $permitTypeField = $('#permit_type');
var $taxDueField = $('#tax_due');
var $warrantyKMField = $('#warranty_km');
var $warrantyYearsField = $('#warrenty_yrs');
var $monthDueField = $('#month_due_date');
var $bankLoanField = $('#bank_loan');
var $fcDueField = $('#fc_due');
var $vehicleConfigField = $('#vehicle_config');
var $customerMobileNoField = $('input[name=customer_mobile_number]');
var $driverField = $('#driver');
var $driverContactField = $('#driver_contact');
var $customerTypeField = $('input[name=customer_type]');
var $employeeField = $('select[name=employee_id]');
var $assignedEmployeeField = $(assigned_to);
//var  = $('select[name=assigned_employee_id]');
var $itemCategoryField = $('select[name=item_category_id]');
var $itemCategoryTypeField = $('select[name=item_category_type_id]');
var $itemMakeField = $('select[name=item_make_id]');
var $stateField = $('select[name=state]');
var $billingStateField = $('select[name=billing_state]');
var $shippingStateField = $('select[name=shipping_state]');
var $billingCityField = $('select[name=billing_city]');
var $shippingCityField = $('select[name=shipping_city]');
var $vehicleRegNoField = $('input[name=vehicle_registration_number]');

/* Customer, billing, shipping fields */
var $custEmailField = $('input[name=customer_email]');

var $mobileFields = $('input[name=customer_mobile_number],input[name=billing_mobile],input[name=shipping_mobile]');
var $nameFields = $('input[name=billing_name],input[name=shipping_name]');
var $billingNameField = $('input[name=billing_name]');
var $billingMobileNoField = $('input[name=billing_mobile]');
var $billingEmailField = $('input[name=billing_email]');
var $billingAddrField = $('textarea[name=billing_address]');
var $billingPinCodeField = $('input[name=billing_pincode]');

var $shippingNameField = $('input[name=shipping_name]');
var $shippingMobileNoField = $('input[name=shipping_mobile]');
var $shippingEmailField = $('input[name=shipping_email]');
var $shippingAddrField = $('textarea[name=shipping_address]');
var $shippingPinCodeField = $('input[name=shipping_pincode]');

var $emailFields = $('input[name=customer_email],input[name=billing_email],input[name=shipping_email]');
var $addressFields = $('textarea[name=customer_address],textarea[name=billing_address],textarea[name=shipping_address]');
var $stateFields = $('select[name=state],select[name=billing_state],select[name=shipping_state]');
var $cityFields = $('select[name=city],select[name=billing_city],select[name=shipping_city]');
var $PINcodeFields = $('input[name=pincode],input[name=billing_pincode],input[name=shipping_pincode]');
var $custStateField = $('select[name=state]');
var $custCityField = $('select[name=city]');

var $billAndShippNameFields = $('input[name=billing_name],input[name=shipping_name]');
var $billAndShippMobileNoFields = $('input[name=billing_mobile],input[name=shipping_mobile]');
var $billAndShippEmailFields = $('input[name=billing_email],input[name=shipping_email]');
var $billAndShippAddressFields = $('textarea[name=billing_address],textarea[name=shipping_address]');
var $billAndShippStateFields = $('select[name=billing_state],select[name=shipping_state]');
var $billAndShippCityFields = $('select[name=billing_city],select[name=shipping_city]');
var $billAndShippPINFields = $('input[name=billing_pincode],input[name=shipping_pincode]');
/* set value */
var jobstatuses = '';
var items = '';
var status_dropdown = '';
var existVehicleNo = '';
var existCustomerMobileNo = '';
var existingCustomerType = false;
var vehicleConfigList = "";
var states = "";
var isForAllStateFields = "";
var defalutCities = "";
//estimate details
var pHasEstimate = 'False';
var pEstimateType = '';
var pEstimateId = '';
//invoice details
var pHasInvoice = 'False';
var pInvoiceType = '';
var pInvoiceId = '';
var pIsInvoiceApproved = 'False';

var formValidation = "";
var vehicleConfigList = '';

var showConfirmationPOPUP = false;


/* jobcard Images */

var jobcardImages = '';

//var statusDropdown = $('job-card-status')

/* form data */
var entityForm = "";

//disable vehicle field while loading
$vehicleRegNoField.prop('disabled', true);
$customerMobileNoField.prop('disabled', true);

// disable customer mobile number field


/* Loading Indicator */
new imageLoader(cImageSrc, 'startAnimation()');


var product_selector = $('#productSelector').DataTable({ searching: false, paging: false, info: false });

$(document).ready(function() {

	// validator ignore condition
	$.validator.setDefaults({
		ignore: []
	});


	// set defalut job status
	onSetJobCardStatus(1);

	basic_functions();

	getMaserData();

	$('[data-toggle="tooltip"]').tooltip();


	// set placeholder
	$('select').select2({
		placeholder: function(){
			$(this).data('placeholder');
		}
	  });

	$('.make_year').datepicker({
		autoclose: true,
		viewMode: "years",
		minViewMode: "years",
		format: 'yyyy'
	});


	// Accordion related code
	var accordionHeaders = $('#jq_accordion .accordion-header');
	var accordionContentAreas = $('#jq_accordion .ui-accordion-content ').hide();
	var accordionContentAreasOpen = $('#jq_accordion .ui-accordion-default-open ').show();

	accordionHeaders.click(function() {
		var panel = $(this).next();
		var isOpen = panel.is(':visible');
		// open or close as necessary
		panel[isOpen ? 'slideUp' : 'slideDown']()
			// trigger the correct custom event
			.trigger(isOpen ? 'hide' : 'show');

		// stop the link from causing a pagescroll
		return false;
	});

	//hide alerts accordion initially, will show only if there are errors during save
	$("#jq_accordion_alerts").hide();

	var accordionHeaders_alerts = $('#jq_accordion_alerts .accordion-header');
	var accordionContentAreas_alerts = $('#jq_accordion_alerts .ui-accordion-content ').hide();
	var accordionContentAreasOpen_alerts = $('#jq_accordion_alerts .ui-accordion-default-open ').show();

	accordionHeaders_alerts.click(function() {
		var panel = $(this).next();
		var isOpen = panel.is(':visible');
		// open or close as necessary
		panel[isOpen ? 'slideUp' : 'slideDown']()
			// trigger the correct custom event
			.trigger(isOpen ? 'hide' : 'show');

		// stop the link from causing a pagescroll
		return false;
	});

	/* Item Selector and filter START */
	$("#sortable1, #sortable2").sortable({
		connectWith: ".connectedSortable"
	}).disableSelection();

	$("#itemSearch").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		filterProduct();
		/*$("#sortable1 li").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});*/
	});

	$('.itemFilterByItemCategory').on('change', function() {
		var optionVal = $('option:selected', this).attr('value');
		getAllItems();
	});

	$('.itemFilterByItemCategoryType').on('change', function() {
		getAllItems();
	});

	$('.itemFilterByItemMake').on('change', function() {
		getAllItems();
	});

	/* Get org inventory items using ajax Call */
	function getAllItems() {
		console.log("Inside getAllItems");
		if (!items) {
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');
			$.ajax({
				url: jobcard_item_route,
				//url: masterDataURL,  // VehicleVariantController
				type: 'get',
				dataType: "json",
				start_time: getcurrrentTime(),
				success: function(data, textStatus, jqXHR) {
					console.log("Ajax Request to Server - " + this.start_time);
					console.log("Ajax Reponse from Server - " + getcurrrentTime());
					console.log("Inside getMasterData : success?")
					console.log(data);
					//return;
					if (data.status == "SUCCESS") {
						items = data.data.items;
						filterProduct();
					}
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
					//alert("New Request Failed " +textStatus);
					showAlertMsg("Connection to server failed. Please reload the page.","error");
				}
			});
		} else {
			//items list already exist
			filterProduct();
		}
	}

	function filterProduct() {
		console.log('insder item filter......');
		$('#sortable1 li').remove();

		//itemFilterByItemCategory
		//console.log($( "#itemFilterByItemCategory option:selected" ).attr('value'));
		var itemFilterByItemCategoryValue = $("#itemFilterByItemCategory option:selected").attr('value');
		if (itemFilterByItemCategoryValue > 0) {
			var itemFilterByItemCategory = $("#itemFilterByItemCategory option:selected").attr('data-item-category');
			var dataItemCat = JSON.parse(itemFilterByItemCategory);
			var itemFilterByItemCategoryDispName = dataItemCat.Itemcategory;
		} else if (itemFilterByItemCategoryValue == 0) {
			var itemFilterByItemCategoryDispName = "ALL";
		} else {
			var itemFilterByItemCategoryDispName = "NONE";
		}

		//itemFilterByItemCategoryType
		//console.log($( "#itemFilterByItemCategoryType option:selected" ).attr('value'));
		var itemFilterByItemCategoryTypeValue = $("#itemFilterByItemCategoryType option:selected").attr('value');
		if (itemFilterByItemCategoryTypeValue > 0) {
			var itemFilterByItemCategory = $("#itemFilterByItemCategory option:selected").attr('data-item-category-type');
			var itemFilterByItemCategoryTypeValue = $("#itemFilterByItemCategoryType option:selected").attr('value');
		} else if (itemFilterByItemCategoryTypeValue == 0) {
			var itemFilterByItemCategoryTypeValue = "ALL";
		} else {
			var itemFilterByItemCategoryTypeValue = "NONE";
		}

		//itemFilterByItemMake
		//console.log($( "#itemFilterByItemMake option:selected" ).attr('value'));
		var itemFilterByItemMakeValue = $("#itemFilterByItemMake option:selected").attr('value');
		if (itemFilterByItemMakeValue > 0) {
			var itemFilterByItemMake = $("#itemFilterByItemMake option:selected").attr('data-item-make');
			var itemFilterByItemMakeValue = $("#itemFilterByItemMake option:selected").attr('value');
		} else if (itemFilterByItemMakeValue == 0) {
			var itemFilterByItemMakeValue = "ALL";
		} else {
			var itemFilterByItemMakeValue = "NONE";
		}

		$.each(items, function(i, item) {
			var itemValid = false;
			//                 if ((itemFilterByItemCategoryDispName == item.category || itemFilterByItemCategoryDispName == "ALL") && (itemFilterByItemCategoryTypeValue == item.category_type_id || itemFilterByItemCategoryTypeValue == "ALL") ) {
			//                    itemValid = true;
			//                 }else if ((itemFilterByItemCategoryDispName == "NONE") && (itemFilterByItemCategoryTypeValue == item.category_type_id || itemFilterByItemCategoryTypeValue == "ALL") ) {
			//                    itemValid = true;
			//                 }else if ((itemFilterByItemCategoryDispName == item.category || itemFilterByItemCategoryDispName == "ALL") && (itemFilterByItemCategoryTypeValue == "NONE") ) {
			//                    itemValid = true;
			//                 }

			//ItemCategory
			if (!itemValid && (itemFilterByItemCategoryDispName == item.category || itemFilterByItemCategoryDispName == "ALL")) {
				//ItemCategoryType
				if (itemFilterByItemCategoryTypeValue == item.category_type_id || itemFilterByItemCategoryTypeValue == "ALL" || itemFilterByItemCategoryTypeValue == "NONE") {
					//ItemMake
					if (itemFilterByItemMakeValue == item.make_id || itemFilterByItemMakeValue == "ALL" || itemFilterByItemMakeValue == "NONE") {
						itemValid = true;
					}

				}
			}
			//ItemCategoryType
			if (!itemValid && (itemFilterByItemCategoryTypeValue == item.category_type_id || itemFilterByItemCategoryTypeValue == "ALL")) {
				//ItemCategory
				if (itemFilterByItemCategoryDispName == item.category || itemFilterByItemCategoryDispName == "ALL" || itemFilterByItemCategoryDispName == "NONE") {
					//ItemMake
					if (itemFilterByItemMakeValue == item.make_id || itemFilterByItemMakeValue == "ALL" || itemFilterByItemMakeValue == "NONE") {
						itemValid = true;
					}
				}
			}
			//ItemMake
			if (!itemValid && (itemFilterByItemMakeValue == item.make_id || itemFilterByItemMakeValue == "ALL")) {
				//ItemCategory
				if (itemFilterByItemCategoryDispName == item.category || itemFilterByItemCategoryDispName == "ALL" || itemFilterByItemCategoryDispName == "NONE") {
					//ItemCategoryType
					if (itemFilterByItemCategoryTypeValue == item.category_type_id || itemFilterByItemCategoryTypeValue == "ALL" || itemFilterByItemCategoryTypeValue == "NONE") {
						itemValid = true;
					}
				}
			}

			if (itemValid) {

				//filter using item search field
				var value = $("#itemSearch").val().toLowerCase();
				//console.log(value);
				var itemSearchValid = false;
				if (value) {
					//console.log('value true');
					var item_name = item.name;
					if (item_name.toLowerCase().indexOf(value) > -1) {
						console.log('value match');
						itemSearchValid = true;
					}
				} else {
					//console.log('value false');
					itemSearchValid = true;
				}

				if (itemSearchValid) {
					//console.log('add item');
					var item_Stock = item.in_stock;
					if (item.in_stock === null) {
						item_Stock = '';
					}

					$("#sortable1").append('<li id="' + item.id + '" data-item-name="' + item.name + '" data-item-category="' + item.category + '" data-item-make="' + item.ItemMake + '" data-item-category-type-id="' + item.category_type_id + '" data-item-in-stock="' + item.in_stock + '"><div class="row"> <div class="form-group col-md-10">' + item.name + '(' + item.ItemMake + ')</div><div class="form-group col-md-2">' + item_Stock + '</div> </div> </li>');
				}
			}
		});

	}

	$("#sortable2").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#sortable1 li").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});

	$('.add_items').on('click', function() {

		console.log('naveen...1');
		$('#sortable2 li').each(function() {


			var itemId = $(this).attr('id');
			var itemName = $(this).attr('data-item-name');
			var categoryName = $(this).attr('data-item-category');
			var categoryTypeId = $(this).attr('data-item-category-type-id');
			var itemMake = $(this).attr('data-item-make');
			//console.log(categoryTypeId);
			var in_stock = $(this).attr('data-item-in-stock');

			var count = $('.items_table').find('tr').length;
			var i = 0;
			// find all selected inputs
			var existingItems = [];
			$('.transaction_items').each(function(i) {
				existingItems.push($(this).val());
			});

			// check if item already exist or not
			if (existingItems.includes(itemId)) {
				//alert("Item already exist");
				return;
			}


			//return;
			if (categoryTypeId == '2') {
				var assignedToFieldName = 'transaction_item[' + itemId + '][assigned_employee_id]';
				var jobStatusFieldName = 'transaction_item[' + itemId + '][job_item_status]';


				var assignedTo = $(assigned_to).attr('name',assignedToFieldName );
				var jobItemStatusDropdown = $(job_item_status_dropdown).attr('name', jobStatusFieldName);


				selected_item_service_table.row.add([
					itemName + '<input type="hidden" name="transaction_item[' + itemId + '][item_id]" class="transaction_items" value="' + itemId + '"><input type="hidden" name="transaction_item[' + itemId + '][quantity]" class="transaction_items" value="1.0">',
					'<textarea id="row-2-age" name="transaction_item[' + itemId + '][description]"  style="height:25px;"></textarea>',
					assignedTo.prop('outerHTML'),
					'<input type="text" id="start_time" name="transaction_item[' + itemId + '][start_time]" value="" class="form-control datetimepicker2" >',
					'<input type="text" id="row-2-age" name="transaction_item[' + itemId + '][duration]"  size="3">',
					jobItemStatusDropdown.prop('outerHTML')

				]).draw(false);

				// set select2 to dropdown field
				setSelect2Field(assignedToFieldName);
				// set time picker to start time fields

			} else {

				selected_item_parts_table.row.add([
					itemName + '<input type="hidden" name="transaction_item[' + itemId + '][item_id]" class="transaction_items" value="' + itemId + '">',
					categoryName,
					itemMake,
					in_stock,
					'<input type="text" id="row-2-age" name="transaction_item[' + itemId + '][quantity]" value="1.0" size="3">'

				]).draw(false);
			}
		});


		/* Set datepicker to fields */
		setDatePickerToItemServiceField();
		$('#sortable2').empty();
	});


	 
	/* Item Selector and filter END */


	/* Job Status Update  Event*/
	$('.jobcard_status_update').on('click', function() {
		var id = $(this).data('id');
		console.log(id);
		onSetJobCardStatus(id);
	})


	//Find vehicle by reg no
	$vehicleRegNoField.on('blur', function(event) {
		console.log("reg number blur function");
		console.log(getcurrrentTime());
		var vehicleNo = $('input[name=vehicle_registration_number]').val();


		// tabout the existing vehicle, nothing to perform action
		if(vehicleNo && existVehicleNo && vehicleNo == existVehicleNo){
			return;
		}
		//clear data table	
		// clear customer fields
		// disable customer fields
		//disable customer selection
		disableCustomerSelection();
		customer_table.clear().draw();
		$('#customer_table').hide();
		clearCustomerFields();
		disableCustomerFields();
		/* customer find alert */
		$('.customer-find-table').hide();
		$('.customer-find-table').text('');

		// reset billing and shipping fields
		resetBillingAndShippingDetailFields();

		//if(existVehicleNo && existVehicleNo )
		if (vehicleNo && existVehicleNo != vehicleNo) {


			console.log("inside If condition?");
			console.log(getcurrrentTime());
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');
			existVehicleNo = vehicleNo;
			// unset message
			//$('.span_mge').find('span').remove();
			$('.span_mge').hide();
			$('.span_mge').text('');

			$.ajax({
				url: find_vehicle_url + vehicleNo,  // VehicleVariantController
				type: 'get',
				dataType: "json",
				start_time: getcurrrentTime(),
				success: function(data, textStatus, jqXHR) {

					console.log("reg number blur function response");
					console.log("Ajax Request to Server - " + this.start_time);
					console.log("Ajax Reponse from Server - " + getcurrrentTime());
					console.log(data);
					//disable customer selection
					disableCustomerSelection();

					// Reset Dropdown fields
					onResetDropDown($vehicleConfigField);
					onResetDropDown($permitTypeField);
					onResetDropDown($bankLoanField);

					//return;
					// set dropdown fields
					if (data.status >= 0) {

						let bankLoanList = "";
						let bankLoanId = "";
						let vehicleConfigList = "";
						let permitTypeList = "";
						let vehicleConfigId = "";
						let vehiclePermitId = "";

						if (data.status == 0) {

							vehicleConfigList = data.data.vehicleConfigList;
							bankLoanList = data.data.bankLoanList;
							vehicleConfigList = data.data.configurationList;
							permitTypeList = data.data.permitTypeList;

							//if vehicle Not exist, set to field  value in upper case
							//set values to uppercase
							var vehicleRegNo = stringConvertToUpperCase(vehicleNo);
							$vehicleRegNoField.val(vehicleRegNo);
							console.log("vehicle Number?");
							console.log($vehicleRegNoField.val());

						} else if (data.status == 1) {
							// if vechicle exist, get only existing  active dropdown data
							bankLoanList = data.data.vehicleWithCustomerDetail.bankLoanList;
							vehicleConfigList = data.data.vehicleWithCustomerDetail.configurationList;
							permitTypeList = data.data.vehicleWithCustomerDetail.permitTypeList;
							vehicleConfigId = data.data.vehicleWithCustomerDetail.configurationId;
							vehiclePermitId = data.data.vehicleWithCustomerDetail.permitTypeId;
							bankLoanId = data.data.vehicleWithCustomerDetail.bankLoan;

						}



						console.log("before setDropDown " + getcurrrentTime());
						console.log("vehicleConfigId " + vehicleConfigId);
						// trigger to get the vehicle category
						onSetDropDownOptions($vehicleConfigField, vehicleConfigList, false,"Vehilce Configuration");
						$vehicleConfigField.val(vehicleConfigId).select2();

						onSetDropDownOptions($permitTypeField, permitTypeList,false,"Permit Type");
						// set value to dropdown not trigger
						$permitTypeField.val(vehiclePermitId).select2();
						onSetDropDownOptions($bankLoanField, bankLoanList,false,'Bank Loan');
						$bankLoanField.val(bankLoanId).select2();

						if (data.status == 1) {

							var vehicleWithCustomerDetail = data.data.vehicleWithCustomerDetail;
							var lastJobCardDetail = data.data.lastJobCardDetail;
							$('input[name=vehicle_existing]').val("true");
							console.log( data.data.vehicleWithCustomerDetail.categoryId);
							
							//enable customer type
							enableCustType();

							// if states doesn't exist,  set the states to field
							if (!states) {
								console.log("State Not Set");
								onResetDropDown($billAndShippStateFields);
								states = data.data.states;
							}

							setLastJobCardDetail(lastJobCardDetail);
							setCustomerAndVehicleDetail(vehicleWithCustomerDetail, true);
							
							//Enable Customer/Vehicle Empty Required Fields  
							enableVehicleEmptyRequiredFields();
							enableCustomerEmptyRequiredFields();

						} else {

							$msg = data.message;
							$('input[name=vehicle_existing]').val("false");
							$customerExistingField.val("false");

							$('.span_mge').show();
							$('.span_mge').text($msg);

							$('input[name=vehicle_id]').val('');
							$('input[name=customer_id]').val('');
							$('input[name=people_id]').val('');

							enableVehicleFields();
							$customerMobileNoField.val('').prop('disabled', true);

						}
					}

					console.log("Ajax call end - " + getcurrrentTime());
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
					//alert("New Request Failed " +textStatus);
				}
			});
		}

	});


	// Confirmation Popup  Event
	function CancelEvent(){
		return;
	}
	function ConfirmEvent(){
		$("#customer-tab").find(':input').not("#customer_type,#customer_existing").val('');
		$("#customer-tab").find('select').val('').select2();
		
		// clear customer table
		customer_table.clear().draw();
		$('#customer_table').hide();
		
		//clear alert message
		$('.customer-find-table').hide();
		$('.customer-find-table').text('');
	}



	//to set customer type value and UI changes
	$('.customer_selection').on('click', function() {
		console.log('customer type clicked');

		var customer_existing = $customerExistingField.val();
		var vehicleRegNo = $vehicleRegNoField.val();
		var vehicleConfig = $vehicleConfigField.val();
		var customerType =  $(this).data('value');

		console.log(customer_existing);
		console.log(vehicleRegNo);
		console.log(vehicleConfig);
		console.log("existingCustomerType?");
		console.log(existingCustomerType);
		


		if(!transaction_id){
			
			// console.log("Existing Customer Type?");
			// console.log(existingCustomerType);

			// console.log(" Customer Type?");
			// console.log(customerType);
			// customer Mobile No Field 

			console.log("Customer Type?");
			console.log(customerType);

			
			if(existingCustomerType === false){

				existingCustomerType = customerType;

			}
			if($customerMobileNoField.val()){
				console.log("Inside Valid Mobile Number?");
				console.log(existingCustomerType);
				console.log(customerType);
				 if(existingCustomerType !== customerType){
					
					$(".confirmation_modal_ajax").modal('show');
					var msg = "If you change customer type, will lose the data. Are you sure to change? ";
					var selectedEle = $(this);
					var popupResponse = customerTypeChangeConfirmationModel(msg,"No","Yes",CancelEvent,ConfirmEvent,customerType,selectedEle);
					console.log("Inside Popup Window?");
					console.log("popupResponse?");
					console.log(popupResponse);
				}
			}
		}


		if (customer_existing == "false" && (vehicleRegNo || vehicleRegNo != '') && (vehicleConfig || vehicleConfig != '') && showConfirmationPOPUP == false) {
			console.log('customer type clicked valid for change');
			var id = $(this).attr('data-id');
			var customer_type = $(this).data('value');
			$(this).nextAll().removeClass('chevron1_active');
			$(this).nextAll().removeClass('chevron1_inactive');
			$(this).nextAll().addClass('chevron1_inactive');
			$(this).prevAll().removeClass('chevron1_active');
			$(this).prevAll().removeClass('chevron1_inactive');
			$(this).prevAll().addClass('chevron1_inactive');
			$(this).removeClass('chevron1_inactive');
			$(this).addClass('chevron1_active');

			/* disable fields */
			$(".disable-business").hide();
			$(".disable-individual").hide();

			$customerTypeField.val(customer_type);
			$customerMobileNoField.prop('disabled', false);

			console.log(typeof customer_type);
			existCustomerMobileNo = '0';
			console.log(customer_type);
			if (customer_type >= 0) {
				console.log("Inside If");

				/* customer alert message  */
				$('.custAlertMsg').hide()
				$('.custAlertMsg').text("");


				if (customer_type == 1) {
					$('.show_gst').show();
					$(".disable-business").show();
					$(".disable-individual").hide();
				}
				else {
					$('.show_gst').hide();
					$(".disable-business").hide();
					$(".disable-individual").show();
				}
			}
		}


	});

	//to add new customer
	$customerMobileNoField.on('blur', function(event) {
		var mobile_number = $(this).val();
		var customerType = $customerTypeField.val();
		console.log("trigger customer mobile number field");

		/* customer alert */
		$('.custAlertMsg').hide()
		$('.custAlertMsg').text("");

		/* vehicle Alert message */
		$('.span_mge').hide();
		$('.span_mge').text('');

		/*  */
		// if vehicle exist edit the missing mobile number field
		console.log("vehicle_existing?");
		console.log($('input[name=vehicle_existing]').val());
		if($('input[name=vehicle_existing]').val() == 'true'){
			console.log("Inside If");
			return;
		}


		//customerType = Number(customerType) ;
		console.log("type", typeof customerType);
		console.log("type", customerType);
		if (mobile_number && !customerType) {
			console.log("inside customerType", customerType);
			/* customer alert */
			$('.custAlertMsg').show();
			$('.custAlertMsg').text("Please Select Customer Type");
		} else
			if (mobile_number && existCustomerMobileNo != mobile_number) {

				existCustomerMobileNo = mobile_number;
				/* Loading Indicator */
				new imageLoader(cImageSrc, 'startAnimation()');
				/* customer find alert */
				$('.customer-find-table').hide();
				$('.customer-find-table').text('');
				clearCustomerFields();
				disableCustomerFields();

				$.ajax({
					url: find_customer_route,  // VehicleVariantController
					type: 'post',
					data: {
						_token: csrf_token,
						mobile_number: mobile_number,
						customer_type: $customerTypeField.val()
					},
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						console.log("inside findCustomerDetail?");
						console.log(data);

						console.log(customer_details);

						if (data.status == "SUCCESS") {
							var customer_details = data.data.customerDetail;
							console.log(states);
							// set state to fields
							if (!states) {
								console.log("State Not Set");
								states = data.data.states;

							}

							//clear dropdown
							onResetDropDown($stateFields);
							onResetDropDown($cityFields);
							onSetDropDownOptions($stateFields, states,false,"State");
							onSetDropDownOptions($cityFields, [],false,"City");
							$stateFields.val(null).select2();


							if (customer_details.length > 0) {


								/* customer find alert */
								$('.customer-find-table').show();
								$('.customer-find-table').text('Customer(s) found. Please choose one from table below.');

								$('.show_customer_details').removeAttr('style');
								disableCustomerFields();

								customer_table.clear().draw();
								$('#customer_table').show();

								for (var i in customer_details) {


									console.log(customer_details[i]);
									console.log(customer_details[i].orgId);


									var customerName = '';
									if (customerType == 0) {
										customerName = customer_details[i].firstName + ' ' + customer_details[i].lastName;
									} if (customerType == 1) {
										customerName = customer_details[i].businessName;
									}
									var isAssociated = customer_details[i].isAssoicated;
									var people_id = customer_details[i].peopleId;
									var associatedType = customer_details[i].associatedType;
									customer_table.row.add([

										customer_details[i].mobileNo,
										customerName,
										isAssociated,
										associatedType

									] ).draw( false );

									$("#customer_table tr:last").addClass("customer_exist").attr({"data-id": customer_details[i].id,"data-cust_type_id": customer_details[i].associatedTypeId,"data-people_id":people_id,"data-customer_name":customerName,"data-first_name":customer_details[i].firstName,"data-last_name":customer_details[i].lastName,"data-business_name":  customer_details[i].businessName,"data-business_alias_name":  customer_details[i].businessAliasName, "data-email": customer_details[i].email,"data-gst":customer_details[i].GST,"data-mobile_no":customer_details[i].mobileNo,"data-address":customer_details[i].address,"data-pin":customer_details[i].PIN,"data-city_id":customer_details[i].cityId,"data-state_id":customer_details[i].stateId,"data-states":JSON.stringify(customer_details[i].activeStateDropDown),"data-cities":JSON.stringify(customer_details[i].activeCityDropDown)});

								}
								customer_table.row.add([

									mobile_number,
									'Add New Customer',
									'NO',
									'Add as Customer'

								]).draw(false);
								$("#customer_table tr:last").addClass("add_new_customer").attr({ "data-mobile_no": mobile_number });

							}
							else {
								$('.customer-find-table').show();
								$('.customer-find-table').text('Customer not found. Check Mobile number again or fill required fields below to register.');

								customer_table.clear().draw();


								$('input[name=customer_id]').val('');
								$('input[name=people_id]').val('');
								enableCustomerFields();

								$('input[name=billing_name],input[name=shipping_name],input[name=billing_email],input[name=shipping_email],textarea[name=billing_address],textarea[name=shipping_address],input[name=billing_pincode],input[name=shipping_pincode],input[name=billing_gst],input[name=billing_mobile],input[name=shipping_mobile],input[name=driver],input[name=driver_contact]').val('');
								$('select[name=billing_state],select[name=shipping_state],select[name=billing_city],select[name=shipping_city]').val('').trigger('change');
								//set value to mobile number fields
								$billAndShippMobileNoFields.val(mobile_number);

							}
						}
						/* Loading Indicator */
						new imageLoader(cImageSrc, 'stopAnimation()');


					},
					error: function(jqXHR, textStatus, errorThrown) {
						/* Loading Indicator */
						new imageLoader(cImageSrc, 'stopAnimation()');

					}
				});
			}
			else if (existCustomerMobileNo != mobile_number) {
				$('input[name=customer_name],input[name=billing_name],input[name=shipping_name],input[name=customer_email],input[name=billing_email],input[name=shipping_email],textarea[name=customer_address],textarea[name=billing_address],textarea[name=shipping_address],input[name=pincode],input[name=billing_pincode],input[name=shipping_pincode],input[name=customer_gst],input[name=billing_gst],input[name=billing_mobile],input[name=shipping_mobile],input[name=driver],input[name=driver_contact]').val('');
				$('select[name=state],select[name=city],select[name=billing_state],select[name=shipping_state],select[name=billing_city],select[name=shipping_city]').val('').trigger('change');

			}

	});

	//to append data to all fields when click yes or no in already exixts field

	function removeCustomerAlert() {

		$('.customer-find-table').hide();
		$('.customer-find-table').text("");
	};

	$('body').on('click', '.customer_exist', function() {
		console.log("click customer already Exist?");
		let data = $(this).data();

		var cust_type_id = data.cust_type_id;
		//clear alert msg
		removeCustomerAlert();

		//clear dropdown
		onResetDropDown($stateFields);
		onResetDropDown($cityFields);

		// exist customer 
		$customerExistingField.val("true");

		if (cust_type_id && cust_type_id == 2) {
			console.log("click customer already Exist as Customer?");
			disableCustomerFields();
			$('input[name=customer_id]').val($(this).data('id'));
			$('input[name=people_id]').val($(this).data('people_id'));

			
		} else {
			console.log("click customer already Exist NOT as Customer?");
			$('input[name=customer_id]').val($(this).data('id'));
			$('input[name=people_id]').val('');
			enableCustomerFields();
			$('input[name=billing_name],input[name=shipping_name],input[name=billing_email],input[name=shipping_email],textarea[name=billing_address],textarea[name=shipping_address],input[name=billing_pincode],input[name=shipping_pincode],input[name=billing_gst],input[name=billing_mobile],input[name=shipping_mobile],input[name=driver],input[name=driver_contact]').val('');
			$('select[name=billing_state],select[name=shipping_state],select[name=billing_city],select[name=shipping_city]').val('').trigger('change');
			
		}
		//console.log("Name:"+$(this).data('name'));
		var customerName = onSetCustomerName(data);
		console.log("data?");
		console.log(data);

		$nameFields.val(customerName);
		$emailFields.val($(this).data('email'));
		$addressFields.val($(this).data('address'));
		$PINcodeFields.val($(this).data('pin'));

		let stateId = $(this).data('state_id');
		let cityId = $(this).data('city_id');

		if (states) {
			// defalutly set state city fields
			onSetDropDownOptions($stateFields, states,false,"State");
			$stateFields.val(stateId).select2();
			findCityByStateId(stateId, $cityFields, cityId);
		}

		$('input[name=customer_gst],input[name=billing_gst]').val($(this).data('gst'));
		$billAndShippMobileNoFields.val($(this).data('mobile_no'));

		// enable customer empty required fields
		enableCustomerEmptyRequiredFields();
	});

	//data exist of the mobile number but user wants to add them as a new customer, probably with different user name
	$('body').on('click', '.add_new_customer', function() {
		//remove msg
		removeCustomerAlert();
		$customerExistingField.val("false");
		$('input[name=customer_id]').val('');
		$('input[name=people_id]').val('');
		enableCustomerFields();
		$('input[name=billing_name],input[name=shipping_name],input[name=billing_email],input[name=shipping_email],textarea[name=billing_address],textarea[name=shipping_address],input[name=billing_pincode],input[name=shipping_pincode],input[name=billing_gst],input[name=billing_mobile],input[name=shipping_mobile],input[name=driver],input[name=driver_contact]').val('');
		$('select[name=billing_state],select[name=shipping_state],select[name=billing_city],select[name=shipping_city]').val('').trigger('change');
		$billAndShippMobileNoFields.val($customerMobileNoField.val());
		console.log("Inside Add Customer!");
		console.log($customerMobileNoField.val());
	});


	/* customer state events */

	$custStateField.on('change', function() {



		console.log("Inside customer State?");
		var stateId = $(this).val();
		console.log(stateId);
		if (stateId) {
			// if not jobcard edit
			if (!transaction_id) {
				if (!$shippingStateField.val() && !$billingStateField.val()) {
					$stateFields.val(stateId).select2();
					findCityByStateId(stateId, $cityFields);
					console.log("Inside All States ,Cities Dropdown Triggert");

				} else {
					$custStateField.val(stateId).select2();
					findCityByStateId(stateId, $custCityField);
					console.log("Inside State Triggert");
				//	$custCityField.val('').select2();
				}

			}
		}


	});

	/* customer city events */
	$custCityField.on('change', function() {
		//if($('#vehicle_existing').val()== 'false' && $("#customer_existing").val() == 'false') {
		console.log("Inside customer State?");
		var cityId = $(this).val();
		console.log(cityId);
		if (cityId) {
			console.log(cityId);
			$billAndShippCityFields.val(cityId).select2();;

		}

	})

	/* set pincode to billing and shipping fields  */
	$('input[name=pincode]').on('blur', function() {
		console.log("inside Pincode?");
		var pincode = $(this).val();

		if (pincode) {

			if (!$('input[name=billing_pincode]').val()) {
				$('input[name=billing_pincode]').val(pincode)
			}

			if (!$('input[name=shipping_pincode]').val()) {
				$('input[name=shipping_pincode]').val(pincode)
			}

		}
	});

	/* set GST to billing and shipping fields  */
	$('input[name=customer_gst]').on('blur', function() {
		console.log("inside Customer GST?");
		var GST = $(this).val();

		if (GST) {

			if (!$('input[name=billing_gst]').val()) {
				$('input[name=billing_gst]').val(GST)
			}



		}
	});

	//	$('input[name=customer_gst],input[name=billing_gst]').val(vehicleWithCustomerDetail.customerDetail.GST);

	/* Shipping, Billing State events */
	$billingStateField.on('change', function() {
		console.log("Inside Billing State?");
		var stateId = $(this).val();
		console.log(stateId);
		console.log(isForAllStateFields);

		//defalut cities for billing and shipping


		if (stateId && !isForAllStateFields) {
			//if shipping state field is empty the set the value
			if (!$shippingStateField.val()) {

				$shippingStateField.val(stateId).trigger('change');
				//var $billAndShippCityFields = $('select[name=billing_city],select[name=shipping_city]');
				findCityByStateId(stateId, $billAndShippCityFields);
			} else {
				findCityByStateId(stateId, $billingCityField);
			}
		}
	});


	$shippingStateField.on('change', function() {
		console.log("Inside Shipping State?");
		var stateId = $(this).val();
		console.log(stateId);
		if (stateId && !isForAllStateFields) {
			//if shipping state field is empty the set the value
			if (!$billingStateField.val())  {

				$billingStateField.val(stateId).trigger('change');

				findCityByStateId(stateId, $billAndShippCityFields);
			} else {
				findCityByStateId(stateId, $shippingCityField);
			}
		}
	});

	/* Shipping, Billing City events */
	$billingCityField.on('change', function() {
		console.log("Inside Billing City?");
		var cityId = $(this).val();
		console.log(cityId);
		if (cityId) {
			//if shipping state field is empty the set the value
			if (!$shippingCityField.val()) {

				$shippingCityField.val(cityId).trigger('change');
				//var $billAndShippCityFields = $('select[name=billing_city],select[name=shipping_city]');
				//findCityByStateId(cityId,$billAndShippCityFields);
			}
		}
	});

	$shippingCityField.on('change', function() {
		console.log("Inside shipping City?");
		var cityId = $(this).val();
		console.log(cityId);
		if (cityId) {
			//if shipping state field is empty the set the value
			if (!$billingCityField.val()) {

				$billingCityField.val(cityId).trigger('change');
				//var $billAndShippCityFields = $('select[name=billing_city],select[name=shipping_city]');
				//findCityByStateId(cityId,$billAndShippCityFields);
			}
		}
	});


	// find city by state Id
	function findCityByStateId(stateId, $cityField, cityId = false) {

		//reset dropdown
		onResetDropDown($cityField);

		$.ajax({
			url: find_city_route,
			type: 'post',
			data: {
				_token: csrf_token,
				state: stateId
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR) {

				onSetCityDropDown($cityField, data.result, cityId);

			}
		});
	}

	$('.transactionform').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		rules: {

			//DETAIL: Vehicle :
			vehicle_registration_number: {
				required: true,
	
			},
			vehicle_config: {
				required: true
			},
			vehicle_category: {
				required: true
			},
			vehicle_mileage: {
				required: true
			},

				// DETAIL: CUSTOMER :
			customer_type: {
					required: true,
			},
			customer_mobile_number: {
				required: true
			},
			business_name: {
				required:  function() {
					return ($customerMobileNoField.val() && $customerTypeField.val() == "1");
				   }
			},
			first_name: {
				required:  function() {
					return ($customerMobileNoField.val() &&  $customerTypeField.val() == "0" );
				   }
			},
			last_name: {
				required:  function() {
					return ($customerMobileNoField.val() && $customerTypeField.val() == "0");
				   }
			},
			customer_address: {
				required: true,
			},
			state: {
				required: true
			},
			city: {
				required: true
			},
			pincode: {

				required: true
			},
			customer_gst: {
				required: true
			},

			// DETAIL: JOB DETAILS :
			service_type: {
				required: true
			},

			// DETAIL: FOLLOW UP VISIT :
			next_visit_reason: {
				required: true
			},
			next_visit_mileage: {
				required: true
			},
			next_visit_mileage: {
				required: true
			},
			next_visit_date: {
				required: true
			},
		},

		messages: {
			// DETAIL: Vehicle :
			vehicle_registration_number: {
			//	required: '{"tab": "Detail", sub_section: "Vehicle", "field": "Registration Number #","error_message":"Registration Number # failed to generate. Please try save again."}',
				required: 'DETAIL: Vehicle: Registration Number :Is required, cannot be empty.',
			
			},
			vehicle_config: {
				//required:  "DETAIL: Vehicle -> Vehicle Configuration cannot be EMPTY!",
				required: 'DETAIL: Vehicle: Make/ Modal / variant / Version :Is required, cannot be empty.',
			},
			vehicle_category: {
				//	required:   "DETAIL: Vehicle -> Vehicle Category  cannot be EMPTY!",
				required: 'DETAIL: Vehicle: Category :Is required, cannot be empty.',
			},
			vehicle_mileage: {
				required: 'DETAIL: Vehicle: Odometer Mileage :Is required, cannot be empty.',
			},

				// DETAIL: CUSTOMER :
			customer_type: {
					//required: "DETAIL: Customer -> Please Select Customer Type!",
					required: 'DETAIL: Customer: Customer Type : Select Organization or Individual(Person).',
			},
			customer_mobile_number: {
				required: 'DETAIL: Customer: Customer Mobile Number :Is required, cannot be empty.',
				//required: "DETAIL: Customer -> Mobile Number cannot be EMPTY!",
				min:8, // minimum - 8
				max:15 // maximum -15
			},
			first_name: {
				required: 'DETAIL: Customer: First Name :Is required, cannot be empty.',
		
			},
			last_name: {
				required: 'DETAIL: Customer: Last Name :Is required, cannot be empty.',
		
			},
			business_name: {
				required: 'DETAIL: Customer: Business Name :Is required, cannot be empty.',
		
			},
			customer_address: {
				//required: "DETAIL: Customer -> Address cannot be EMPTY!",
				required: "DETAIL: Customer: Address  :Is required, cannot be empty."
			},
			state: {
				required: "DETAIL: Customer: State :Is required, cannot be empty."
			},
			city: {
				required: "DETAIL: Customer: City :Is required, cannot be empty."
			},
			pincode: {
				required: "DETAIL: Customer: Pincode  :Is required, cannot be empty."
				//required:  "DETAIL: Customer -> PINcode cannot be EMPTY!",
			},
			customer_gst: {
				required:  "DETAIL: Customer: GST :Is required, cannot be empty."
			},

			// DETAIL: JOB DETAILS :
			service_type: {
				required:  "DETAIL: Job Details: Service Type :Is required, cannot be empty."
			},

			// DETAIL: FOLLOW UP VISIT :
			next_visit_reason: {
				//required:  "DETAIL: FOLLOW UP VISIT -> Next Visit Reason cannot be EMPTY!",
				required:  "DETAIL: Follow Up Visit:Next Visit Reason :Is required, cannot be empty."
			},
			next_visit_mileage: {
				//required:  "DETAIL: FOLLOW UP VISIT -> Next Visit - Odometer Mileage cannot be EMPTY!",
				required:  "DETAIL: Follow Up Visit:Next Visit - Odometer Mileage :Is required, cannot be empty."
			},
			next_visit_date: {
				required:  "DETAIL: Follow Up Visit:Next Visit - Date :Is required, cannot be empty."
			},
		},


		errorPlacement: function(error, element) {
		

		},
		invalidHandler: function(event, validator) { //display error alert on form submit   

			$('.alert-danger', $('.login-form')).show();

		
		},
		highlight: function(element) { // hightlight error inputs

			//	$(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},

		success: function(label) {
			label.closest('.form-group').removeClass('has-error');
			label.remove();
		},

		submitHandler: function(form) {

			console.log("validation");
			console.log("route?");
			console.log(jobcard_store_route);
			console.log("form data?");
			//	formData();			

			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');

			//before get form data,remove disabled attr from disabled input and select elements
			var disabled = $('.transactionform').find(':input:disabled').removeAttr('disabled');
			var disabled2 = $('.transactionform').find('select:disabled').removeAttr('disabled');

			let formData2 = $('.transactionform')[0];


			entityForm = new FormData(formData2);

			//after get form data,  add disabled  attr to   input and select elements
			disabled.attr('disabled', 'disabled');
			disabled2.attr('disabled', 'disabled');

			var beforeJobCardImages = $('#before_image').get(0).dropzone.getAcceptedFiles();
			var progressJobCardImages = $('#progress_image').get(0).dropzone.getAcceptedFiles();
			var afterJobCardImages = $('#after_image').get(0).dropzone.getAcceptedFiles();


			if (beforeJobCardImages.length > 0) {

				appendFileToForm('before_image[]', beforeJobCardImages);
			}
			if (progressJobCardImages.length > 0) {

				appendFileToForm('progress_image[]', progressJobCardImages);
			}
			if (afterJobCardImages.length > 0) {

				appendFileToForm('after_image[]', afterJobCardImages);
			}

			// disable alerts
			$("#jq_accordion_alerts").hide();

			$.ajax({

				url: jobcard_store_route,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': csrf_token
				},

				data: entityForm,
				processData: false,
				contentType: false,
				beforeSend: function() {
					//$('.loader_wall_onspot').show();
					console.log("before Send?");
					//console.log($('.transactionform').serializeArray());
				},
				success: function(data, textStatus, jqXHR) {

					// After save data, hide customer finding table
					// set value false to hidden fields for update vehicle and customer detail if required field is empty
					customer_table.clear().draw();
					$('#customer_table').hide();
					$('#IsCustomerRequiredFieldEmpty').val('false');
					$('#IsVehicleRequiredFieldEmpty').val('false');

					// after save automatically disable the  form select fields
					// so we enable the all fields without item fields
					//	$('.transactionform').find('select:not([name*=vehicle_config]):not([href^=permit_type]):not([href^=bank_loan]):not([name*=state]):not([href^=city])').removeAttr('disabled');

					if (data.message) {


						if (data.message == 'SUCCESS') {
							var msg = "Jobcard Save Successfully!";

							if (transaction_id) {
								msg = "Jobcard Update Successfully!";
							}
							
							showAlertMsg(msg,"success");
						}
			

						console.log(data.data);

						if (data.data.isVehicleSaved) {
							$('input[name=vehicle_existing]').val(data.data.isVehicleSaved);
						}
						if (data.data.isCustomerSaved) {
							$('input[name=customer_existing]').val(data.data.isCustomerSaved);
						}

						if (data.data.customerId) {
							$('input[name=customer_id]').val(data.data.customerId);
							$('input[name=people_id]').val(data.data.peopleId);
						}

						if (data.data.vehicleId) {
							$('input[name=vehicle_id]').val(data.data.vehicleId);
						}

						if (data.data.jobcardNo) {
							$('.title-order-no').text(data.data.jobcardNo);
							$('input[name=job_card_no]').val(data.data.jobcardNo);
						}

						// after saving new jobcard
						if (data.data.id) {

							if (!transaction_id) {
								$('input[name=id]').val(data.data.id);
								$('.title-vehicle-no').text($vehicleRegNoField.val());
								$('.title-vehicle-config').text($vehicleConfigField.find("option:selected").text());
								var customerName = "";
								if ($('#customer_type').val() == "0") {
									customerName = $('input[name=first_name]').val();

									if ($('input[name=last_name]').val()) {
										customerName += " " + $('input[name=last_name]').val();
									}

								} else if ($('#customer_type').val() == "1") {

									customerName = $('input[name=business_name]').val();
								}

								customerName += "-" + $customerMobileNoField.val();
								$('.title-customer-name').text(customerName);
								transaction_id	=	data.data.id;
							}
							console.log(data.data.id);
							console.log(transaction_id);
							
							// after save jobcard, clear item data table;
							selected_item_service_table.clear().draw();
							selected_item_parts_table.clear().draw();
							
							// clear existing image from edit screen
							$(".dropzone").each(function() {
								$(this).find(".img-wrap").remove();
							  });

							// remove all image files in attachment 
							Dropzone.forElement('#before_image').removeAllFiles(true)
							Dropzone.forElement('#progress_image').removeAllFiles(true)
							Dropzone.forElement('#after_image').removeAllFiles(true)

							findJobCardDetail(transaction_id);

					
							
							//change to Detail tab 
							$('.nav-item a[href="#order_details"]').tab('show'); 
							jobcardImages = '';

							console.log("naveeeen ????????????????????????");
							
							console.log("naveeen oitside ????????????????????????");
						}

					}
					// alerts have message it will be show!
					if (data.data.alerts.length>0) {
						showResponseAlerts(data.data.alerts);
						$("#jq_accordion_alerts").show();
					}

					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
					console.log(data.data);


					//var url = '{{ route("jobcard.index") }}';
					//window.location.href = url;
				},
				error: function(jqXHR, textStatus, errorThrown) {
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
					//alert("New Request Failed " +textStatus);
					// TODO Show this accordian ONLY if there are errors during save
					$("#jq_accordion_alerts").show();

				}
			});


		}

	});

	function appendFileToForm(type, files) {
		$.each(files, function(key, item) {
			//console.log(item);
			//entityForm.push({name:type,value:item});
			entityForm.append(type, item);
		});

		console.log(entityForm);
	}


	function findJobCardDetail(transaction_id) {

		console.log("inside findJobCardDetail ################# function?");
		console.log(transaction_id);

		/* Loading Indicator */
		new imageLoader(cImageSrc, 'startAnimation()');

		$.ajax({
			url: find_jobcard_detail_url + transaction_id,
			type: 'get',
			success: function(data, textStatus, jqXHR) {

				if(data.status == 'SUCCESS'){
				console.log("Inside order success function?");
				console.log(data);
				console.log(data.data.lastJobCardDetail);
				
				/* set billing and shipping address */
				//TODO Newly Added
				$('input[name=vehicle_registration_number]').prop('disabled', true);
				// TODO
				var jobCardDetail = data.data.jobCardDetail;
				var vehicleWithCustomerDetail = data.data.vehicleWithCustomerDetail;
				var lastJobCardDetail = data.data.lastJobCardDetail;
				// To remove save button if job card status id is close
				if(jobCardDetail.jobStatusId == '8')
				{
					$('.tab_save_btn').remove();
				}
				
				// set job status
				onSetJobCardStatus(data.data.jobCardDetail.jobStatusId);

				//estimate details
				pHasEstimate = jobCardDetail.pHasEstimate;
				pEstimateType = jobCardDetail.pEstimateType;
				pEstimateId = jobCardDetail.pEstimateId;

				//invoice details
				pHasInvoice = jobCardDetail.pHasInvoice;
				pInvoiceType = jobCardDetail.pInvoiceType;
				pInvoiceId = jobCardDetail.pInvoiceId;
				pIsInvoiceApproved = jobCardDetail.pIsInvoiceApproved;

				//Action Link enable
				if (transaction_id) {
					$('a#advance_payment').removeClass('disableLink');

					$('a#estimate_create_update').removeClass('disableLink');
					if (pHasEstimate == 'True') {
						$('a#estimate_create_update').text('Update Estimate');
						$('a#estimate_view').removeClass('disableLink');
					}

					if (pHasInvoice == 'True') {
						$('a#invoice_view').removeClass('disableLink');
						//credit invoice
						if (pInvoiceType == 'job_invoice') {
							$('a#invoiceCredit_create_update').text('Update Invoice (Credit)');
							$('a#invoiceCash_create_update').hide();
							if (pIsInvoiceApproved == 'False') {
								$('a#invoiceCredit_create_update').removeClass('disableLink');
							}
						} else if (pInvoiceType == 'job_invoice_cash') { // Cash Invoice
							$('a#invoiceCash_create_update').text('Update Invoice (Cash)');
							$('a#invoiceCredit_create_update').hide();
							if (pIsInvoiceApproved == 'False') {
								$('a#invoiceCash_create_update').removeClass('disableLink');
							}
						}
					} else {
						$('a#invoiceCredit_create_update').removeClass('disableLink');
						$('a#invoiceCash_create_update').removeClass('disableLink');
					}

					$('a#ack_customer').removeClass('disableLink');
					$('a#print_ack').removeClass('disableLink');

					// for JobcardAcknowledgement
					var ackEncryptedURL = data.data.encryptedAckURL;
					$('a#print_ack').attr('data-url',ackEncryptedURL);
					$('a#ack_customer').attr('data-id',transaction_id);

					if (pHasEstimate == 'False' && pHasInvoice == 'False') {
						$('a#delete').removeClass('disableLink');
					}
				}

				/* set Dropdown Details */
				var bankLoanList = data.data.vehicleWithCustomerDetail.bankLoanList;
				var vehicleConfigList = data.data.vehicleWithCustomerDetail.configurationList;
				var permitTypeList = data.data.vehicleWithCustomerDetail.permitTypeList;
				var vehicleConfigId = data.data.vehicleWithCustomerDetail.configurationId;
				var vehiclePermitId = data.data.vehicleWithCustomerDetail.permitTypeId;
				var bankLoan = data.data.vehicleWithCustomerDetail.bank_loan;


				//reset dropdown fields
				onResetDropDown($vehicleConfigField);
				onResetDropDown($permitTypeField);
				onResetDropDown($bankLoanField);


				onSetDropDownOptions($vehicleConfigField, vehicleConfigList);
				$vehicleConfigField.val(vehicleConfigId).select2();

				onSetDropDownOptions($permitTypeField, permitTypeList, vehiclePermitId);
				onSetDropDownOptions($bankLoanField, bankLoanList, bankLoan,"Bank Loan");			
				$employeeField.val(jobCardDetail.assignedTo).select2();


				/*set Customer Details  */
				//enableCustType();
				setCustomerAndVehicleDetail(vehicleWithCustomerDetail);
				


				setBillingAndShippingInfo(jobCardDetail);
				setLastJobCardDetail(lastJobCardDetail);

				// jobcard Images
				console.log("Job card Images?");
				console.log(data.data.jobCardImages);
				findJobcardImages(data.data.jobCardImages);


				$('input[name=vehicle_registration_number]').val(jobCardDetail.vehicleNo);
				$('input[name=vehicle_mileage]').val(jobCardDetail.vehicleMileage);

				/* set titles */
				$('#job_card_no').val(jobCardDetail.orderNo);
				$('.title-order-no').text(jobCardDetail.orderNo);
				$('.title-vehicle-no').text(jobCardDetail.vehicleNo);
				$('.title-vehicle-config').text(vehicleWithCustomerDetail.vehicleName);



				$driverField.val(jobCardDetail.driverName);
				$driverContactField.val(jobCardDetail.driverNumber);

				// set date to fields
				setDateToField($('input[name=job_date]'), jobCardDetail.jobcardDate);
				setDateToField($('input[name=job_completed_date]'), jobCardDetail.JCCompletedDate);
				setDateToField($('input[name=due_date]'), jobCardDetail.jobDueDate);
				setDateToField($('input[name=next_visit_date]'), jobCardDetail.vehicleNextVisitDate);

				$('textarea[name=complaint]').val(jobCardDetail.complaints);
				$('textarea[name=vehicle_note]').val(jobCardDetail.vehicleNote);
				$('input[name=next_visit_reason]').val(jobCardDetail.vehicleNextVisitReason);
				$('input[name=next_visit_mileage]').val(jobCardDetail.vehicleNextVisitMileage);

				$('.select_item').each(function() {
					var select = $(this);
					if (select.data('select2')) {
						select.select2("destroy");
					}
				});


				console.log("transaction_items?");

				console.log("onSetJobItemStatusDropDownOptions");
				console.log(job_item_status_dropdown);
				var partsArray = data.data.jobCardItems.parts;
				var serviceArray = data.data.jobCardItems.service;

				if (partsArray.length > 0) {

					partsArray.forEach(function(item) {
						selected_item_parts_table.row.add([
									item.item_name+'<input type="hidden" name="transaction_item['+item.item_id+'][item_id]" class="transaction_items" value="'+item.item_id+'"><input type="hidden" name="transaction_item['+item.item_id+'][id]" value="'+item.item_id+'"><input type="hidden" name="transaction_item['+item.item_id+'][job_card_id]" value="'+item.job_card_id+'">',
							item.category,
							item.make,
							item.in_stock,
							'<input type="text" id="row-2-age" name="transaction_item[' + item.item_id + '][quantity]" value=' + item.quantity + ' size="3">'
						]).draw(false);
					});

				}

				if (serviceArray.length > 0) {

					serviceArray.forEach(function(item) {

						var dropdown = "";

						var assignedToFieldName = 'transaction_item[' + item.item_id + '][assigned_employee_id]';
						var jobStatusFieldName = 'transaction_item[' + item.item_id + '][job_item_status]';

						// set assigned employee dropdown value
						if (item.assigned_employee_id) {
							console.log(item.assigned_employee_id);
							// change dropdown values
							dropdown = $(assigned_to).val(item.assigned_employee_id).attr('name', assignedToFieldName);
							dropdown.find("option:selected").attr('selected', true);
						} else {
							dropdown = $(assigned_to).attr('name', assignedToFieldName);
						}
						dropdown = $(dropdown).prop('outerHTML');


						var itemStatusDropDown = "";

						//set job item status in dropdown
						if (item.job_item_status) {
							// change dropdown values
							
							itemStatusDropDown = $(job_item_status_dropdown).val(item.job_item_status).attr('name', jobStatusFieldName);
							itemStatusDropDown.find("option:selected").attr('selected', true);//adds "selected" attribute to the selected option
						} else {
							itemStatusDropDown = $(job_item_status_dropdown).attr('name', jobStatusFieldName);
						}

						itemStatusDropDown = $(itemStatusDropDown).prop('outerHTML');

						var item_description_textarea = 'transaction_item[' + item.item_id + '][description]';
						var item_start_time_input = 'transaction_item[' + item.item_id + '][start_time]';
						var item_duration_input = 'transaction_item[' + item.item_id + '][duration]';

						//if item quantity doesn't exist in service, set defalut value 1.0
						item.quantity = item.quantity?item.quantity:"1.0";

						selected_item_service_table.row.add([
							item.item_name + '<input type="hidden" name="transaction_item[' + item.item_id + '][item_id]" class="transaction_items" value="' + item.item_id + '"><input type="hidden" name="transaction_item[' + item.item_id + '][job_card_id]" value="' + item.job_card_id + '"><input type="hidden" name="transaction_item[' + item.item_id + '][quantity]" value="' + item.quantity+ '">',
							'<textarea id="row-2-age" name="' + item_description_textarea + '"  style="height:25px;"></textarea>',
							dropdown,
							'<input type="text" id="start_time" name="' + item_start_time_input + '"  class="form-control datetimepicker2" data-date-format="dd-mm-yyyy hh:mm:ss">',
							'<input type="text" id="row-2-age" name="' + item_duration_input + '"  size="3">',
							itemStatusDropDown
						]).draw(false);

						//set values to field
						$('textarea[name="' + item_description_textarea + '"]').val(item.description);
						$('input[name="' + item_start_time_input + '"]').val(item.start_time);
						$('input[name="' + item_duration_input + '"]').val(item.duration);
						
						// set select2 to dropdown field
						setSelect2Field(assignedToFieldName);
						//$('select[name="'+assignedToFieldName+'"]').select2();
					});

					/* Set datepicker to fields */
					setDatePickerToItemServiceField();

				}

				$('.select_item').select2();
				
				// disable customer and vehicle fields
				disableCustomerFields();
				disableVehicleFields();

				//Enable Customer/Vehicle  Empty Required Fields  
				enableVehicleEmptyRequiredFields();
				enableCustomerEmptyRequiredFields();
				
		

			}
				/* Loading Indicator */
				new imageLoader(cImageSrc, 'stopAnimation()');
				console.log("insideFunction ");

			},
			error: function(jqXHR, textStatus, errorThrown) {
				/* Loading Indicator */
				new imageLoader(cImageSrc, 'stopAnimation()');
				showAlertMsg("Your request failed. Please reload the page. If problem continues, please contact propel customer care.","error");
			}
		});

	}
	
	/* set select2 to  fields  */
	 function setSelect2Field(fieldName){

		 $('select[name="'+fieldName+'"]').select2();
	 }

	/* find all jobcard Image  */
	function findJobcardImages(images) {

		console.log("inside findJobcardImages function?");
		console.log(images);
		let beforeImg = images.beforeImg;
		let progressImg = images.progressImg;
		let afterImg = images.afterImg;


		let beforeImgId = $('#before_image');
		let progressImgId = $('#progress_image');
		let afterImgId = $('#after_image');

		console.log("Before Img?");
		console.log(beforeImgId.length);
		console.log(beforeImg);

		console.log("Progress Img?");
		console.log(progressImgId.length);


		console.log("After Img?");
		console.log(afterImgId.length);

		renderImage(beforeImgId, beforeImg);
		renderImage(progressImgId, progressImg);
		renderImage(afterImgId, afterImg);

	}



	function onRenderCheckList(data) {
		console.log(data);
		var checkListContent = '';
		let i = 1;

		data.forEach(function(item) {

			console.log(item);
			let checkbox = "";
			if (item.job_card_checklist && item.job_card_checklist.id) {
				console.log('has ck');
			checkbox = `<td  style="text-align: center; vertical-align: middle;">  <input type="checkbox" name="wms_checklist_status[]" id="checklist`+item.id+`"  checked="true" value="`+item.id+`"/> 	<label for="checklist`+item.id+`"><span></span></label></td>`
			} else {
				console.log('no ck');
				checkbox = `<td  style="text-align: center; vertical-align: middle;">  <input type="checkbox" name="wms_checklist_status[]" id="checklist` + item.id + `"   value="` + item.id + `"/> 	<label for="checklist` + item.id + `"><span></span></label></td>`


			}
			checkListContent += `<tr>
			<td>
			<span style="float: right; padding-left: 5px;">`+ i + `</span>
			</td>
		<td>`+ item.name + `
			<input type="hidden" name="checklist_id[]" value="${item.id}"/>
		</td>		
		`+ checkbox + `
		<td><input name="wms_checklist_notes[]" class = "form-control properCase"  value='${item.job_card_checklist && item.job_card_checklist.checklist_notes ? item.job_card_checklist.checklist_notes : ""}'></td>
	</tr>`;
			i++;
		});
		$("#checklist-table").append(checkListContent);
	}

	/* functions only for select 2 dropdown	 */
	// START
	/* onSetDropDownOptions  */
	function onSetDropDownOptions($element, data, defalutValue,selectType = false) {
		$element.select2("val", "");

		// set dropdown defalut select option
		if(selectType){
			$element.append($("<option />").val("").text(" --- Select "+selectType+" --- "));
		}
		
		// set value to fields
		$.each(data, function(key, item) {
			$element.append($("<option />").val(key).text(item));
		});
		if (defalutValue) {
			//set value to fields
			console.log(defalutValue);
			$element.val(defalutValue).trigger('change');
			//$element.select2().;
		}
	}

	function convertToDropDownData(data) {

		let dropdownData = Object.keys(data).map(function(value) {
			return { "id": value, "text": data[value] };
		});
		return dropdownData;
	}
	/* END */
	/* functions only for select 2 dropdown	 */


	/* Get master Data using ajax Call */
	function getMaserData() {
		console.log("Inside getMasterData?");
		console.log(masterDataURL);

		$.ajax({
			url: masterDataURL,  // VehicleVariantController
			type: 'get',
			dataType: "json",
			start_time: getcurrrentTime(),
			success: function(data, textStatus, jqXHR) {
				console.log("Ajax Request to Server - " + this.start_time);
				console.log("Ajax Reponse from Server - " + getcurrrentTime());
				console.log("Inside getMasterData : success?")
				console.log(data);
				//return;
				if (data.status == "SUCCESS") {

					/* Enable fields after get response */
					$vehicleRegNoField.prop('disabled', false);
					$customerMobileNoField.prop('disabled', true);


					//set data
					let vehicleSevicesData = data.data.vehicleSevices;
					let vehicleServiceId = data.data.vehicleServiceId;
					let shipmentModes = data.data.shipmentModes;
					let shipmentModeId = data.data.shipmentModeId;
					let employees = data.data.employees;

					//let states = data.data.states;
					let employeeId = data.data.employeeId;

					/* Item Related Data */
					//items = data.data.items;
					console.log(items.length);
					itemCategories = data.data.itemCategories;
					itemCategoryTypes = data.data.itemCategoryTypes;
					itemMakes = data.data.itemMakes;
					console.log("Ajax Reponse before item  - " + getcurrrentTime());

					console.log("Ajax Reponse after item  - " + getcurrrentTime());
					/* Item Related Data  */


					jobstatuses = data.data.jobCardStatuses;
					jobItemStatus = data.data.jobItemStatus;

					//setDropDownData
					onSetDropDownOptions($serviceField, vehicleSevicesData, vehicleServiceId);
					onSetDropDownOptions($shipmentModeField, shipmentModes, shipmentModeId);
					onSetDropDownOptions($employeeField, employees, employeeId);


					//console.log(status_dropdown);

					// set dropdown options
					onSetJobCardStatusDropDownOptions(jobstatuses);
					onSetEmployeeDropdownOptions(employees);
					onSetJobItemStatusDropDownOptions(jobItemStatus);

					//console.log(jobstatuses);

					//set Item Related Data to dropdown
					console.log("ItemCategories?");
					//	console.log(itemCategories);
					onSetItemCategoriesToDropDown(itemCategories);
					onSetItemCategoryTypesToDropDown(itemCategoryTypes);
					onSetItemMakesToDropDown(itemMakes);

					let checkListData = data.data.vehicleCheckListData;
					console.log("checkListData?");
					//	console.log(checkListData);
					onRenderCheckList(checkListData);

					/* TODO: TESTING purpose */
					//onSetJobCardStatus(2);
					console.log("Ajax Reponse after complete rendering - " + getcurrrentTime());


					/* After Success render  the jobcard Details*/
					if (transaction_id) {
						findJobCardDetail(transaction_id);
						console.log("inside ?");
						// $('a[href="#attachments"]').on("click", function() {
						// 	if (!jobcardImages) {
						// 		console.log("Before findJobcardImages?")
						// 		findJobcardImages(transaction_id);
						// 	}
						// });

					}
				}
				/* Loading Indicator */
				new imageLoader(cImageSrc, 'stopAnimation()');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//alert("New Request Failed " +textStatus);
				/* Loading Indicator */
				new imageLoader(cImageSrc, 'stopAnimation()');
			}
		});
	}

	$('.customerName').on('blur', function() {
		console.log("Customer Name?");
		//convert number
		var customerType = Number($customerTypeField.val());

		var customerName = "";
		if (customerType >= 0) {
			if (customerType == 0) {
				if ($('input[name=last_name]').val()) {

					customerName = $('input[name=first_name]').val() + ' ' + $('input[name=last_name]').val();
				}

			} else {
				customerName = $('input[name=business_name]').val();
			}
		}
		console.log("customerType?");
		console.log(customerType);
		$billAndShippNameFields.val(customerName);
	});



	$('input[name=customer_email]').on('blur', function() {
		var customerEmail = $(this).val();
		console.log(customerEmail);
		$billAndShippEmailFields.val(customerEmail);
	});

	$('textarea[name=customer_address]').on('blur', function() {
		var customerAddr = $(this).val();
		$billAndShippAddressFields.val(customerAddr);
	});


	$vehicleConfigField.on("change", function() {
		console.log("trigger on change");
		let vehicleConfigId = $(this).val();
		console.log(vehicleConfigId);

		if (vehicleConfigId) {

			 enableCustType();
			url = vehicle_category_url.replace(':id', vehicleConfigId);
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');


			$.ajax({
				url: url,  // VehicleVariantController
				type: 'get',
				dataType: "json",
				success: function(data, textStatus, jqXHR) {

					
					if (data.status == "SUCCESS" && data.data) {

						$vehicleCategoryField.val(data.data.display_name);
						$('input[name=vehicle_category_id]').val(data.data.id);
						//	$vehicleCategoryField.prop('disabled',true);
					}
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					//alert("New Request Failed " +textStatus);
					/* Loading Indicator */
					new imageLoader(cImageSrc, 'stopAnimation()');
				}
			});
		}

		//	console.log( $(this).val());
	});

	// set function dropdown status
	function onSetJobCardStatusDropDownOptions(jobstatuses) {


		appendOptions($jobcardStatusEle, jobstatuses);
		status_dropdown = $jobcardStatusEle.prop('outerHTML');
		//$('#job-card-status').select2().trigger('change');
	}

	// set function dropdown status
	function onSetJobItemStatusDropDownOptions(jobItemStatus) {

		appendOptions($jobItemStatusEle, jobItemStatus);
		job_item_status_dropdown = $jobItemStatusEle.prop('outerHTML');
		//$('#job_item_status').select2().trigger('change');
		console.log("onSetJobItemStatusDropDownOptions");
		console.log(job_item_status_dropdown);
	}

	function appendOptions($element, data) {
		Object.keys(data).map(function(value) {
			$element.append(new Option(data[value], value));
		});
	}

	function onSetEmployeeDropdownOptions(employeee) {
		console.log("setEmployeeDropDown?");
		appendOptions($assignedEle, employeee);
		assigned_to = $assignedEle.prop('outerHTML');

	}

	// set item categories to dropdown
	function onSetItemCategoriesToDropDown(data) {
		$.each(data, function(i, item) {
			//	console.log(item);
			var data_str = JSON.stringify(item);
			var option = $('<option  value="' + item.id + '">' + item.Itemcategory + '</option>').attr('data-item-category', data_str);
			$itemCategoryField.append(option);
		})
		$itemCategoryField.select2();
	}

	function onSetCityDropDown($Ele, data, defalutValue = false) {

		$Ele.append($("<option />").val("").text(" --- Select City --- "));
		$.each(data, function(key, item) {
			$Ele.append($("<option />").val(item.id).text(item.name));
		});
		if (defalutValue) {
			//set value to fields
			$Ele.val(defalutValue).select2();
		}
	}


	// set item category type to dropdown
	function onSetItemCategoryTypesToDropDown(data) {
		$.each(data, function(i, item) {
			console.log(item);
			var data_str = JSON.stringify(item);
			var option = $('<option  value="' + item.id + '">' + item.display_name + '</option>').attr('data-item-category-type', data_str);
			$itemCategoryTypeField.append(option);
		})
		$itemCategoryTypeField.select2();
	}

	// set item makes to dropdown
	function onSetItemMakesToDropDown(data) {
		$.each(data, function(i, item) {
			console.log(item);
			var data_str = JSON.stringify(item);
			var option = $('<option  value="' + item.id + '">' + item.ItemMake + '</option>').attr('data-item-make', data_str);
			$itemMakeField.append(option);
		})
		$itemMakeField.select2();
	}




	// set jobcard status
	function onSetJobCardStatus(statusId) {
		console.log("inside onSetJobCardStatus");
		console.log(statusId);

		// set value to field && highlight active  arrow
		$('.chevron_active').removeClass('chevron_active').addClass('chevron');
		$('.job_status_' + statusId).removeClass('chevron');
		$('.job_status_' + statusId).addClass('chevron_active');
		$('input[name=jobcard_status_id]').val(statusId);
	}

	function onResetDropDown($element) {
		//$element.select2('data', {id: null, text: null})
		$element.empty();
		console.log("After Clear DropDown?");
		console.log($element);
		console.log($element.html());
	}


	function disableVehicleFields() {
		$vehicleExistingField.val("true");
		$customerExistingField.val("true");
		$span_mgeEle.find('span').remove();
		$engineNoField.prop('disabled', true);
		$chassisNoField.prop('disabled', true);
		$manuYearField.prop('disabled', true);

		$lastUpdateDateField.prop('disabled', true);
		$lastUpdateJCField.prop('disabled', true);

		//$vehicleCategoryField.prop('disabled',true);

		$("input[name=vehicle_category]:not(:hidden)").prop('disabled', true);
		$vehicleInsuranceField.prop('disabled', true);
		$insuranceDueField.prop('disabled', true);
		$permitDueField.prop('disabled', true);
		$permitTypeField.prop('disabled', true);
		$fcDueField.prop('disabled', true);
		$taxDueField.prop('disabled', true);
		$warrantyKMField.prop('disabled', true);
		$warrantyYearsField.prop('disabled', true);
		$monthDueField.prop('disabled', true);
		$bankLoanField.prop('disabled', true);
		$vehicleConfigField.prop('disabled', true);
		$customerMobileNoField.prop('disabled', true);
	}

	function enableVehicleFields() {
		$engineNoField.val('').removeAttr('disabled');
		$chassisNoField.val('').removeAttr('disabled');
		$manuYearField.val('').removeAttr('disabled');

		$lastUpdateDateField.val('');
		$lastUpdateJCField.val('');

		//	$vehicleCategoryField.val('').removeAttr('disabled');
		$vehicleInsuranceField.val('').removeAttr('disabled');
		$insuranceDueField.val('').removeAttr('disabled');
		$permitDueField.val('').removeAttr('disabled');
		$permitTypeField.val('').trigger('change').removeAttr('disabled');
		$fcDueField.val('').removeAttr('disabled');
		$taxDueField.val('').removeAttr('disabled');
		$warrantyKMField.val('').removeAttr('disabled');
		$warrantyYearsField.val('').removeAttr('disabled');
		$monthDueField.val('').removeAttr('disabled');
		$vehicleCategoryField.val('');
		$('input[name=vehicle_category_id]').val('');
		$bankLoanField.val('').trigger('change').removeAttr('disabled');
		$vehicleConfigField.val('').trigger('change').removeAttr('disabled');

	}

	function setLastJobCardDetail(lastJobCardDetail) {
		// jobcard fields
		$lastUpdateDateField.val(lastJobCardDetail.lastUpdateDate);
		$lastUpdateJCField.val(lastJobCardDetail.lastUpdateJC);

		//
		console.log("encrypted url?");
		console.log(lastJobCardDetail.encryptedURL);

		if(lastJobCardDetail.encryptedURL){
			//var lastJobDetailURL = '<div class="row"><div class="form-group  col-md-3"><br><a style="color: #3366ff;" class="reference" target="_blank" href="'+lastJobCardDetail.encryptedURL+'" ><i class="fa fa-history"></i><span>&nbsp;Show Job Card History</span></a></div></div>';
            var lastJobDetailURL = '<a style="color: #3366ff;" class="reference" target="_blank" href="'+lastJobCardDetail.encryptedURL+'" ><i class="fa fa-history"></i><span>&nbsp;Show History</span></a>';
            $(".previous-visit-link").empty();
			$(".previous-visit-link").append(lastJobDetailURL);
		}
	}

	function onSetValuesToFields(data) {
		console.log("inside onSetValuesToFields?");
		console.log(data);
		$engineNoField.val(data.engineNo);
		$chassisNoField.val(data.chassisNo);
		$manuYearField.val(data.manufactYear);
		$permitTypeField.val(data.permitTypeId).trigger('change');
		$fcDueField.val(data.fcDue);
		$taxDueField.val(data.taxDue);
		$vehicleInsuranceField.val(data.insurance);
		$bankLoanField.val(data.bankLoan).trigger('change');
		$warrantyKMField.val(data.warrantyKM);
		$warrantyYearsField.val(data.warrantyYR);
		$vehicleCategoryField.val(data.categoryName);
		$('input[name=vehicle_category_id]').val( data.categoryId);
							

		// set date field
		console.log("Insurance Due?");
		console.log(data.insuranceDue);
		setDateToField($insuranceDueField, data.insuranceDue);
		setDateToField($permitDueField, data.permitDue);
		setDateToField($monthDueField, data.monthDueDate);

	}

	function disableCustomerFields() {
		//$('input[name=customer_name]').prop('disabled',true);
		$('input[name=customer_email]').prop('disabled', true);
		$('textarea[name=customer_address]').prop('disabled', true);
		$('select[name=state]').prop('disabled', true);
		$('select[name=city]').prop('disabled', true);
		$('input[name=pincode]').prop('disabled', true);
		$('input[name=customer_gst]').prop('disabled', true);
		// disable name fields
		console.log("disableCustomerFields");
		if ($customerTypeField.val() >= 0) {
			console.log("insise If");
			if ($customerTypeField.val() == 0) {
				$('input[name=first_name]').prop('disabled', true);
				$('input[name=last_name]').prop('disabled', true);
			} else {
				$('input[name=business_name]').prop('disabled', true);
			}
		}
	}
	// function setBillingAndShippingStates()
	// {

	// }

	

	function setBillingAndShippingInfo(jobCardDetail) {
		console.log("inside setBillingAndShippingInfo?");


		/* set billing and shipping details */
		$billingNameField.val(jobCardDetail.billingName);
		$billingMobileNoField.val(jobCardDetail.billingMobileNo);
		$billingEmailField.val(jobCardDetail.billingEmail);
		console.log("Billing Addr Field?");
		console.log(jobCardDetail.billingAddr);
		$billingAddrField.val(jobCardDetail.billingAddr);
		$billingPinCodeField.val(jobCardDetail.billingPinCode);

		$shippingNameField.val(jobCardDetail.shippingName);
		$shippingMobileNoField.val(jobCardDetail.shippingMobileNo);
		$shippingEmailField.val(jobCardDetail.shippingEmail);
		console.log("Shipping Addr Field?");

		console.log(jobCardDetail.shippingAddr);
		$shippingAddrField.val(jobCardDetail.shippingAddr);
		$shippingPinCodeField.val(jobCardDetail.shippingPinCode);

		// if state doesn't exist, set state to varialble
		if (!states) {
			states = jobCardDetail.states;
		}

		var billingStateId = jobCardDetail.billingStateId;
		var shippingStateId = jobCardDetail.shippingStateId;

		var billingCityId = jobCardDetail.billingCityId;
		var shippingCityId = jobCardDetail.shippingCityId;

		//set billing and shipping state fields
		if (billingStateId) {
			var stateFieldSet = "";
			if (shippingStateId) {
				stateFieldSet = $billAndShippStateFields;
			} else {
				stateFieldSet = $billingStateField;
			}

			onSetDropDownOptions(stateFieldSet, states,false,"State");

			if (shippingStateId && billingStateId == shippingStateId) {

				stateFieldSet.val(billingStateId).select2();
			} else {
				$billingStateField.val(billingStateId).select2();

				if (shippingStateId) {

					$shippingStateField.val(shippingStateId).select2();
				}
			}

			if (billingCityId) {
				console.log(billingCityId);
				console.log(shippingCityId);
					if(shippingCityId && billingCityId == shippingCityId){
					console.log("Inside Same City?");
					findCityByStateId(billingStateId, $billAndShippCityFields, billingCityId);
					//	$billAndShippCityFields.val(shippingCityId).select2().trigger('change');
				} else {
					findCityByStateId(billingStateId, $billingCityField, billingCityId);
					console.log("Inside Different City?");
					console.log(shippingCityId);
					if (shippingCityId) {
						findCityByStateId(shippingStateId, $shippingCityField, shippingCityId);
					}
				}
			}
		}

	}

	function setCustomerAndVehicleDetail(vehicleWithCustomerDetail, defalutFieldsForAll = false) {


		$('input[name=vehicle_id]').val('');
		$('input[name=customer_id]').val('');
		$('input[name=people_id]').val('');


		// set vehicle details
		$('input[name=vehicle_id]').val(vehicleWithCustomerDetail.id);
		disableVehicleFields();
		onSetValuesToFields(vehicleWithCustomerDetail);

		/* SET FIELDS TO CUSTOMER  TYPE*/
		// enable customer type
		enableCustType();

		$('input[name=customer_type]').val(vehicleWithCustomerDetail.customerDetail.userType);
		if (vehicleWithCustomerDetail.customerDetail.userType == 1) {
			console.log("inside UserType" + 1);
			$('#IndividualCust').removeClass('chevron1_active');

			$('#IndividualCust').addClass('chevron1_inactive');

			$('#OrganizationCust').removeClass('chevron1_inactive');
			$('#OrganizationCust').addClass('chevron1_active');
			$(".disable-business").show();
			$(".disable-individual").hide();

		}
		else if (vehicleWithCustomerDetail.customerDetail.userType == 0) {
			console.log("inside UserType" + 0);
			$(".disable-business").hide();
			$(".disable-individual").show();
			$('#OrganizationCust').removeClass('chevron1_active');
			$('#OrganizationCust').addClass('chevron1_inactive')

			$('#IndividualCust').removeClass('chevron1_inactive');
			$('#IndividualCust').addClass('chevron1_active');

		}
	

		console.log("customer Details");
		console.log(vehicleWithCustomerDetail.customerDetail);

		var name = onSetCustomerName(vehicleWithCustomerDetail.customerDetail);

		// reset customer dropdown fields
		onResetDropDown($custCityField);
		onResetDropDown($custStateField);

		// set customer fields to billing  and shipping/
		console.log("befroe set state city DropDown " + getcurrrentTime());
		/* Customer, billing, shipping  fields */
		var customerCities = vehicleWithCustomerDetail.customerDetail.activeCityDropDown;
		var customerStates = vehicleWithCustomerDetail.customerDetail.activeStateDropDown;
		var customerStateId = vehicleWithCustomerDetail.customerDetail.stateId;
		var customerCityId = vehicleWithCustomerDetail.customerDetail.cityId;

		if(!customerStates){
			customerStates = vehicleWithCustomerDetail.customerDetail.states;
		}


		//set customer detail
		$('input[name=customer_id]').val(vehicleWithCustomerDetail.customerDetail.id);
		console.log("people Id?");
		console.log(vehicleWithCustomerDetail.customerDetail.peopleId);
		$('input[name=people_id]').val(vehicleWithCustomerDetail.customerDetail.peopleId);
		$customerTypeField.val(vehicleWithCustomerDetail.customerDetail.userType);
		$customerMobileNoField.val(vehicleWithCustomerDetail.customerDetail.mobileNo);
		$custEmailField.val(vehicleWithCustomerDetail.customerDetail.email);
		$('textarea[name=customer_address]').val(vehicleWithCustomerDetail.customerDetail.address);
		console.log("Pin?");
		console.log(vehicleWithCustomerDetail.customerDetail.PIN);
		$('input[name=pincode]').val(vehicleWithCustomerDetail.customerDetail.PIN);
		console.log("Active cities?");
		console.log(customerCities);
		console.log(customerStates);
		console.log(customerStateId);

		onSetDropDownOptions($custStateField, customerStates,false,"State");
		$custStateField.val(customerStateId).select2();
		onSetDropDownOptions($custCityField, customerCities, customerCityId);

		$('input[name=customer_gst]').val(vehicleWithCustomerDetail.customerDetail.GST);

		if(transaction_id){
			// set customer title
			console.log("set Inside title customer name?")
			$('.title-customer-name').text(vehicleWithCustomerDetail.customerDetail.nameWithMobileNo);
		}
		// defalut for billing ans shipping 
		if (defalutFieldsForAll) {
			console.log("Inside Defalut for all");
			$billAndShippNameFields.val(name);
			$billAndShippMobileNoFields.val(vehicleWithCustomerDetail.customerDetail.mobileNo);
			$billAndShippEmailFields.val(vehicleWithCustomerDetail.customerDetail.email);
			$billAndShippAddressFields.val(vehicleWithCustomerDetail.customerDetail.address);
			console.log("states?");
			console.log(states);

			//set city state to fields
			onSetDropDownOptions($billAndShippStateFields, states,false,"States");
			$billAndShippStateFields.val(customerStateId).select2();
			findCityByStateId(customerStateId, $billAndShippCityFields, customerCityId);
			$('input[name=billing_gst]').val(vehicleWithCustomerDetail.customerDetail.GST);
			$PINcodeFields.val(vehicleWithCustomerDetail.customerDetail.PIN);

		}

		console.log("after set state city DropDown " + getcurrrentTime());


		if (vehicleWithCustomerDetail.customerDetail.userType == 1) {
			$('.show_gst').show();
		}
		else {
			$('.show_gst').hide();
		}

	}


	function setDateToField($dateField, date) {

		console.log("Inside setDateToField");
		if (date) {
			$dateField.datepicker('setDate', new Date(date));
			$dateField.datepicker("dateFormat", 'dd-mm-yy');
			$dateField.datepicker("defaultDate", new Date(date));
			$dateField.datepicker("update");
		}
	}

	//	function onSetJobCardStatus
	function onSetCustomerName(data) {
		var customerType = $('input[name=customer_type]').val();

		let name = '';
		console.log("inside Customer Type?");
		console.log(customerType);
		console.log(data);
		if (customerType == 0) {
			console.log("inside Person");

			// set name to fields for person
			let firstName = data.first_name ? data.first_name : data.firstName;
			let lastName = "";

			if (data && data.last_name) {
				lastName = data.last_name;
			} else if (data.lastName) {
				lastName = data.lastName;
			}
			$('input[name=first_name]').val(firstName);
			$('input[name=last_name]').val(lastName);
			$('input[name=business_name]').val('');
			$('input[name=alias_name]').val('');
			name = firstName + ' ' + lastName;
		} else if (customerType == 1) {

			// set name to fields for business
			let businessName = data.business_name ? data.business_name : data.businessName;

			let businessAliasName = "";
			if (data && data.business_alias_name) {
				businessAliasName = data.business_alias_name;
			} else if (data.businessAliasName) {
				businessAliasName = data.businessAliasName;
			}

			$('input[name=first_name]').val('');
			$('input[name=last_name]').val('');
			$('input[name=alias_name]').val(businessAliasName);
			$('input[name=business_name]').val(businessName);
			name = businessName;
		}
		return name;
	}

	function enableCustomerFields() {
		$('input[name=business_name]').val('').removeAttr('disabled');
		$('input[name=alias_name]').val('').removeAttr('disabled');
		$('input[name=first_name]').val('').removeAttr('disabled');
		$('input[name=last_name]').val('').removeAttr('disabled');
		$('input[name=customer_email],textarea[name=customer_address],input[name=pincode],input[name=customer_gst]').val('').removeAttr('disabled');
		$('select[name=state],select[name=city]').val('').trigger('change').removeAttr('disabled');
	}

	function clearCustomerFields() {
		$('input[name=business_name]').val('');
		$('input[name=first_name]').val('');
		$('input[name=last_name]').val('');
		$('input[name=customer_name],input[name=customer_email],textarea[name=customer_address],input[name=pincode],input[name=customer_gst]').val('');
		$('select[name=state],select[name=city]').val('').trigger('change');
	}


	function stringConvertToUpperCase(data) {
		return data.toUpperCase();

	}


	/* Print Acknowledgement */
	
		$('a#print_ack').on("click",function(){
			var url = $(this).attr('data-url');
			console.log("inside Click Event?");
			console.log(url);
			if(url){
				var newWindow = window.open(url);
				newWindow.print();
			}
		})

		/* Send SMS to Customer */
		$('a#ack_customer').on("click",function(){
			var id = $(this).attr('data-id');
			console.log("inside Click Event?");
			console.log(id);
			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');
			jobcard_acknowledgement(id);
		
		})


	$('body').on("click", ".delete-img", function() {
		console.log("Inside delete Img?");
		var id = $(this).data('id');
		var $imgEle = $(this).closest('.img-wrap');
			//console.log($imgEle.html());

		$('.delete_modal_ajax').modal('show');
		$('.delete_modal_ajax_btn').off().on('click', function() {
			$('.delete_modal_ajax').modal('hide');

			/* Loading Indicator */
			new imageLoader(cImageSrc, 'startAnimation()');

			if (id) {
				/* Delete Action */
				$.ajax({
					url: jobcard_img_destory_route + "/" + id,
					type: 'get',
					dataType: "json",
					beforeSend: function() {

					},
					success: function(data, textStatus, jqXHR) {

						/* Loading Indicator */
						new imageLoader(cImageSrc, 'stopAnimation()');
						console.log(data);
						if (data.message) {
							if (data.message == "SUCCESS") {
								if (data.error) {
									alert(data.error);
								}
								// remove img preview
								$imgEle.remove();
								console.log("Img Removed?");
							}
						}

					},
					error: function(jqXHR, textStatus, errorThrown) {
						/* Loading Indicator */
						new imageLoader(cImageSrc, 'stopAnimation()');
						console.log("error");
						console.log(errorThrown);
					}
				});

			}

		});

	})



	/* Set datepicker to fields */
	function setDatePickerToItemServiceField() {
		$(".datetimepicker2").datetimepicker({
			language: 'en',
			format: 'dd-mm-yyyy hh:mm:ss',
			pick12HourFormat: true,
		//	autoclose: true,
			//	defaultDate: new Date()
		}).on('changeDate', function(){
			//hide datetimepicker
			 $(this).datetimepicker('hide');
		});;
	}



	// enable customer type buttons
	function enableCustType() {
		console.log('custtupe enable');
		var custType = $customerTypeField.val();
		console.log(custType);
		if (custType >= 0) {
			console.log('custtupe enable inside');
			$('#IndividualCust').removeClass('chevron1_active');
			$('#IndividualCust').removeClass('chevron1_disable');
			$('#IndividualCust').addClass('chevron1_inactive');

			$('#OrganizationCust').removeClass('chevron1_active');
			$('#OrganizationCust').removeClass('chevron1_disable');
			$('#OrganizationCust').addClass('chevron1_inactive');
		}
	}

	function onSetStateBillAndShippDropDown(states) {
		onSetDropDownOptions($billAndShippStateFields, states);
	}

	function resetBillingAndShippingDetailFields() {
		$billAndShippNameFields.val("");
		$billAndShippMobileNoFields.val("");
		$billAndShippEmailFields.val("");
		$('input[name=billing_gst]').val("");
		// reset selection
		$billAndShippStateFields.val("").trigger('change');
		$billAndShippAddressFields.val("");
		$billAndShippPINFields.val("");
	}

	function disableCustomerSelection() {
		$('#IndividualCust').removeClass('chevron1_active');
		$('#OrganizationCust').removeClass('chevron1_active');
		$('#IndividualCust').removeClass('chevron1_inactive');
		$('#OrganizationCust').removeClass('chevron1_inactive');
		$(".disable-business").hide();
		$(".disable-individual").hide();
	}

	function renderImage($Ele, data) {
		let html = '';
		$.each(data, function(i, item) {
			console.log(item.img_url);
			html += `<div class="img-wrap" style="padding:5px">
				<span class="close delete-img" data-id="${item.id}">&times;</span>
				<a target="_blank" href="${item.image_url}">
		
				<img alt="Select Image" data-id="${item.id}"  src="${item.image_url}" width="120" height="120" />
			</a>
			</div>`;
		});
		console.log("AppendImage?");
		console.log(html);
		$Ele.append(html);
	}

	// show alerts 
	function showAlerts(errorMessages) {
		var msgHTML = "";
		$.each(errorMessages, function(index, value) {
			if (value.message != true) {
				msgHTML += `<li><div class="row"> <div class="form-group col-md-8">${value.message}</div></div> </li>`;
			}
		});
		//	return msgHTML;
		$("#errors_ul").empty();
		$("#errors_ul").append(msgHTML);
		$('#jq_accordion_alerts').show();
	}

	// show alerts 
	function showResponseAlerts(alerts) {
		console.log(alerts);
		$("#alerts_bc").empty();
		$.each(alerts, function(i, alert) {
			console.log(alert);
			//$("#errors_ul").append('<li><div class="row"> <div class="form-group col-md-1">DETAIL</div><div class="form-group col-md-2">Job Card #</div><div class="form-group col-md-7">'+alert+'</div> </div> </li>');
			var msgHTML = "";

			if (alert.tab !== "" && typeof alert.tab !== "undefined") {
				console.log('tab');
				msgHTML += '<li class="breadcrumb-item">' + alert.tab + '</li>';
			}

			if (alert.sub_section !== "" && typeof alert.sub_section !== "undefined") {
				console.log('sub_section');
				msgHTML += '<li class="breadcrumb-item">' + alert.sub_section + '</li>';
			}

			if (alert.field !== "" && typeof alert.field !== "undefined") {
				console.log('field');
				msgHTML += '<li class="breadcrumb-item">' + alert.field + '</li>';
			}

			if (alert.error_message !== "" && typeof alert.error_message !== "undefined") {
				console.log('error_message');
				msgHTML += '<li class="breadcrumb-item">' + alert.error_message + '</li>';
			}

			//$("#alerts_bc").append('<ol class="breadcrumb"><li class="breadcrumb-item">'+alert.tab+'</li><li class="breadcrumb-item">'+alert.sub_section+'</li><li class="breadcrumb-item">'+alert.field+'</li><li class="breadcrumb-item">'+alert.error_message+'</li></ol>');
			if (msgHTML !== "") {
				$("#alerts_bc").append('<ol class="breadcrumb">' + msgHTML + '</ol>');
			}


		});
		$('#jq_accordion_alerts').show();
	}

	// confirmation model - 2 callback function change customerType
	function customerTypeChangeConfirmationModel(msg,btn1Text,btnDangerText,cancelCallBackEvent,confirmCallBackEvent,customerType,selectedEle = false){
		$("div.confirmation_modal_ajax  h4.modal-title").text('Alert');
		$("div.confirmation_modal_ajax  div.modal-body").text(msg); // confirmation model msg
		showConfirmationPOPUP = true;


		$("div.confirmation_modal_ajax  div.modal-footer button.btn.default").text(btn1Text).on('click', function () {			
			$(".confirmation_modal_ajax").modal('hide');
			showConfirmationPOPUP = false;
			cancelCallBackEvent();
			return;
		});
		
		
		$("div.confirmation_modal_ajax  div.modal-footer button.delete_modal_ajax_btn").text(btnDangerText).on('click', function () {
			
			confirmCallBackEvent();
			$(".confirmation_modal_ajax").modal('hide');
			showConfirmationPOPUP = false;
			
			// trigger click event
			if(selectedEle){	
				$(selectedEle).trigger("click");
			}
		
			existingCustomerType = customerType;
			
		});
	}

		// confirmation model - 3 callback function close event and stay event callback
		function closeConfirmationModel(msg,btn1Text,btnDangerText){
			$("div.confirmation_modal_ajax  h4.modal-title").text('Alert');
			$("div.confirmation_modal_ajax  div.modal-body").text(msg); // confirmation model msg
	
	
			$("div.confirmation_modal_ajax  div.modal-footer button.btn.default").text(btn1Text).on('click', function () {			
				$(".confirmation_modal_ajax").modal	;

				 return;
				
			});
			
			
			$("div.confirmation_modal_ajax  div.modal-footer button.delete_modal_ajax_btn").text(btnDangerText).on('click', function () {		
				$(".confirmation_modal_ajax").modal('hide');
				window.location.href = jobcard_index_route;
			});
		}

	//Action Button & Links
	//Save button
	$(".tab_save_btn").off().on('click', function(e) {
		console.log("tab_save_btn");
		e.preventDefault();
		

	
		var disabledInputFields = "";
		var disabledSelectFields = "";
		var disableFollowUpFields = "";
		var disableGSTField = "";
		

		console.log("Customer Type?");
		console.log($customerTypeField.val());
		$GSTField = $("input[name=customer_gst]");
		

		//before validate form data,remove disabled attr from disabled input and select elements
		if($vehicleExistingField.val() == "false" && $customerExistingField.val() == "false"){
			 disabledInputFields = $('.transactionform').find(':input:disabled').removeAttr('disabled');
			 disabledSelectFields = $('.transactionform').find('select:disabled').removeAttr('disabled');
		} else if($vehicleExistingField.val() == "false" && $customerExistingField.val() == "true"){
			 disabledInputFields = $("#customer-tab").find(':input:disabled').removeAttr('disabled');
			 disabledSelectFields = $("#customer-tab").find('select:disabled').removeAttr('disabled');
		}
		
		
		disableGSTField = $GSTField.attr("disabled","disabled");
		if($customerTypeField.val() && $customerTypeField.val() == "1"){
			disableGSTField.removeAttr("disabled");
		}
		console.log("JobCard Status?");
		console.log($(".jobcard_status_id").val());
		if($(".jobcard_status_id").val() == '8'){
			console.log("Inside Jobcard Status?")
			 $("#follow-up-visit-tab").find(':input').removeAttr("disabled");
			 disableFollowUpFields = "";
		}else{
			disableFollowUpFields = $("#follow-up-visit-tab").find(':input').attr("disabled","disabled");
		}
		
		
		formValidation = $('.transactionform');
		console.log("formValidation?");
		console.log(formValidation);
		var validator = formValidation.validate();
		console.log("ValidateForm?");
		console.log(validator);
		if (validator.checkForm() == true) {

			$('.form-group').removeClass('has-error');
			$('.help-block').remove();

			if (formValidation.valid()) {
				console.log("transactionform submit");
				formValidation.submit();
			}

		}
		else {


			//validator.showErrors();
			console.log("errors?");
			console.log(validator.errorList);
			let errors = validator.errorList;

			

			let errors2 = validator.errorList.map(function(error){ 

				console.log("error");
				console.log(error);
				
				if(error.message){
					console.log("message?");   
					console.log(error.message);
					var array = error.message.split(":");
				//	console.log(array);
				//	'{"tab": "Detail", sub_section: "Vehicle", "field": "Registration Number #","error_message":"Registration Number # failed to generate. Please try save again."}',
					var tab = array[0];
					var subSection = array[1];
					var field = array[2];
					var errorMessages = array[3];
				//	console.log(tab);
				//	var obj = JSON.parse(`{ "tab":${tab},"sub_section":${subSection},"field":${field},"error_message":${errorMessages}}`);
					var obj = JSON.parse(`{ "tab":"${tab}","sub_section":"${subSection}","field":"${field}","error_message":"${errorMessages}"}`);
					//console.log(obj);
					return obj;
				}
			});
			console.log("Error?");
			console.log(errors2);

			showResponseAlerts(errors2);
		}

		//after validate form data,  add disabled  attr to   input and select elements
		if(disabledInputFields && disabledSelectFields){
			disabledInputFields.attr('disabled', 'disabled');
			disabledSelectFields.attr('disabled', 'disabled');
		}
		if(disableFollowUpFields){
			disableFollowUpFields.removeAttr('disabled');
		}

		if(disableGSTField){
			disableGSTField.removeAttr("disabled");
		}
	});

	// In Edit Jobcard, Existing user/ vehicle fields are empty,it should be enable
	function enableVehicleEmptyRequiredFields(){
		console.log("Enable Vehicle Empty Required Fields?");
		var vehicleRequiredFields = $("#vehicle-tab").find('.required-field');
		$.each(vehicleRequiredFields, function(i, field) {
			if(!$(field).val()){

				if($("#vehicle_required_field").val() == 'false' ){
					$("#vehicle_required_field").val('true');
				}  
				$(field).removeAttr('disabled');
			}

		});
	
	}

	function enableCustomerEmptyRequiredFields(){
		console.log("Enable Customer  Empty Required Fields?");
		var customerRequiredFields = $("#customer-tab").find('.required-field');

		$.each(customerRequiredFields, function(i, field) {

			if(!$(field).val()){
				console.log($("#customer_required_field").val());
				console.log("Field Empty");
				if($("#customer_required_field").val() == 'false' ){
					$("#customer_required_field").val('true');
				}  
				$(field).removeAttr('disabled');
			}

		});
		
	}

	//TODO Newly Added
	//to close jobcard
	$('.cancel_transaction').on('click', function() {


	if(!transaction_id){
		if($vehicleRegNoField.val()){
			// show close confirmation model
			$(".confirmation_modal_ajax").modal('show');
			closeConfirmationModel("Unsaved changes will be lost. Do you want to continue?","No","Yes");
		}else{
			window.location.href = jobcard_index_route;
		}
	}else{
		window.location.href = jobcard_index_route;
	}

	});

	//advance payment
	$('.advance_payment').on('click', function() {
		if (transaction_id) {
			jobcard_advance_payment(transaction_id);
		}
	});

	//create estimated
	$('.estimate_create_update').on('click', function() {
		//console.log(pHasEstimate);
		if (transaction_id) {
			//console.log(pHasEstimate);
			create_estimation(transaction_id);
		}
	});

	//view estimated
	$('.estimate_view').on('click', function() {
		//console.log(pHasEstimate);
		if (pHasEstimate == 'True' && pEstimateId > 0) {
			view_estimation(pEstimateId);
		}
	});

	//create invoice
	$('.invoice_create_update').on('click', function() {
		console.log(pHasInvoice);

		if (transaction_id) {
			var status_id = $('input[name=jobcard_status_id]').val();

			if(status_id && status_id == 6 || status_id == 7 || status_id == 8)
			{
				console.log(pHasInvoice);
				var type = $(this).attr('data');
				create_invoice(transaction_id, type);
			}
			else if(status_id)
			{
				showAlertMsg("Job Card status should be in 'Final Inspected' or 'Vehicle Ready' to create invoice.","error");
			}
		}
	});

	//view invoice
	$('.invoice_view').on('click', function() {
		//console.log(pHasInvoice);
		if (pHasInvoice == 'True' && pInvoiceId > 0) {
			view_invoice(pInvoiceId, pInvoiceType);
		}
	});

	//delete
	$('.delete').on('click', function() {
		if (transaction_id) {
			jobcard_delete(transaction_id);
		}
	});

	//alert window close
	$('.alert_close').on('click', function() {
		console.log('Test Naveen alert close');
		$('a#alert_close').hide();
		$('a#alert_open').show();
	});
	$('.alert_open').on('click', function() {
		console.log('Test Naveen  alert_open');
		$('a#alert_open').hide();
		$('a#alert_close').show();
	});

});