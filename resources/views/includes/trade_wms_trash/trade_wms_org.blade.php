@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('trade_wms', Session::get('organization_id')))

	  	<li class="header"><span> Trade-WMS </span></li>
	  	
		<li><a data-link="job_board" data-toggle="tooltip" data-placement="top" title="Job Board" href="{{ route('trade_wms.job_board') }}"><i class="fa icon-basic-accelerator"></i><span>Job Board</span></a></li>
		

	  	
	  	

	  

	  	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Propel Management </span></a>

			<div class="sidebar-submenu">
			  	<ul>
			  	     @permission('today_summary')
			  	    <li><a data-link="jobstatus_dashboard" data-toggle="tooltip" data-placement="top" title="Today Summary" href="{{ route('trade_wms.today_summary') }}"><span>Today Summary</span></a></li>
			  	    @endpermission

					<li><a data-link="dashboard" href="{{ route('trade_wms.dashboard') }}"><span> Dashboard </span></a></li>
				    @permission('wms-job-status-list')
						<li><a data-link="job_status" href="{{ route('Jobstatus.index') }}"><span>Job Status</span></a></li>
					@endpermission
					@permission('jc-stock-report')
				   	<li><a data-link="low-stock-report" href="{{ route('jc_stock_report.index') }}"><span>JC Stock Report</span></a></li>
					@endpermission
					 <li><a data-link="customer_promotion" href="{{ route('customer_promotion') }}"><span>Customer Promotion</span></a></li>
							
				 	@permission('WMS-Scheduleboard')
	  		        <li><a data-link="schedule_board" href="{{ route('trade_wms.schedule_board') }}">
	  	        	<i class="fa fa-calendar"></i><span>Schedule Board</span></a></li>
	            	@endpermission
		  			<li ><a data-link="visiting_jobcard" data-toggle="tooltip" data-placement="top"  title="Next Visit View Vechile" href="{{ route('visiting_jobcard') }}"><span>Next Visit View Vechile</span></a></li>
		  			
				</ul>
			</div>
			
		</li>

	  	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Masters </span></a>

			<div class="sidebar-submenu">
			  	<ul>
					<li><a data-link="discount" href="{{ route('discount.index') }}"><span> Discount </span></a></li>
				    <li><a data-link="unit" href="{{ route('unit.index') }}"><span> Units </span></a></li>
					<li><a data-link="shipment/mode" href="{{ route('shipment_mode.index') }}"><span> Shipment Mode</span></a>
					</li>
					<!-- <li><a data-link="payment/method" href=""><span> Payment Method </span></a></li> -->
				    <li><a data-link="items" href="{{ route('item.index', ['items']) }}"><span> Items </span></a></li>
				    <li><a data-link="tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
				     @permission('customer-grouping')
					<li><a data-link="customer_grouping" data-toggle="tooltip" data-placement="top" title="Customer Grouping" href="{{ route('customer_grouping.index') }}"><span>Customer Grouping </span></a></li>
					@endpermission
					 <li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Vehicle Masters"><span> Vehicle Masters</span></a>
						<div class="sidebar-submenu">
							<ul  style="margin-left: 20px;">
							@permission('service-type-list')
					  		<li><a data-link="service-type" href="{{ route('service_type.index') }}"><span> Service Type </span></a></li>
					  		@endpermission
					  		@permission('vehicle-category-list')
				  			<li><a data-link="vehicle/category" href="{{ route('vehicle_category.index') }}"><span> Vehicle Category </span></a></li>
				  			@endpermission
					  		@permission('vehicle-make-list')
				  			<li><a data-link="vehicle/make" href="{{ route('vehicle_make.index') }}"><span> Vehicle Make </span></a></li>
				  			@endpermission
				  			@permission('vehicle-model-list')
				  			<li><a data-link="vehicle/model" href="{{ route('vehicle_model.index') }}"><span> Vehicle Model </span></a></li>
				  			@endpermission
				  			@permission('variant-list')
				  			<li><a data-link="variant" href="{{ route('vehicle_variant.index') }}"><span> Vehicle Variant </span></a></li>
				  			@endpermission
				  			@permission('readingfactor-list')
				  			<li><a data-link="reading-factor" href="{{ route('reading_factor.index') }}"><span> Reading Factor </span></a></li>
				  			@endpermission
				  			@permission('checklist-list')
				  			<li><a data-link="vehicle/checklist" href="{{ route('VehicleChecklist.index') }}"><span> Checklist</span></a></li>
				  			@endpermission
				  			@permission('permit-type-list')
				  			<li><a data-link="vehicle/permit-type" href="{{ route('permit_type.index') }}"><span> Vehicle Permit Type </span></a></li>
				  			@endpermission
				  			@permission('specifiaction-master')
				  			<li><a data-link="master_specification" href="{{ route('specification_master.index') }}"><span> Specification Master</span></a></li>
				  			@endpermission
				  			@permission('vehicle-specifications')
				  			<li><a data-link="vehicle/specification" href="{{ route('specification.index') }}"><span> Vehicle Specifications </span></a></li>
				  			@endpermission
				  			@permission('specification-values')
				  			<li><a data-link="specification_values" href="{{ route('specification_values.index') }}"><span> Specification Values </span></a></li>
				  			@endpermission

							</ul>
						</div>
					</li>
					 <li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Pricing"><span>Pricing</span></a>
						<div class="sidebar-submenu">
							<ul  style="margin-left: 20px;">
								@permission('segment-list')
								<li><a data-link="pricingsegment" href="{{ route('segment.index') }}"><span> Pricing Segment </span></a></li>
								@endpermission
								@permission('segment-details')
								<li><a data-link="segmentdetails" href="{{ route('VehicleSegmentDetail.index') }}"><span>Pricing Segment Details</span></a></li>
								@endpermission
								@permission('price-list')
								<li><a data-link="item-price-list" href="{{ route('wms_item_price_list') }}"><span> Price Lists </span></a></li>
								@endpermission
							</ul>
						</div>
					</li>
		  			
		  			
				</ul>
			</div>
			
		</li>

		<!-- <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Vehicle Masters </span></a>
		
		  	<div class="sidebar-submenu">
			  	<ul>
			  		@permission('service-type-list')
			  		<li><a data-link="service-type" href="{{ route('service_type.index') }}"><span> Service Type </span></a></li>
			  		@endpermission
			  		@permission('vehicle-category-list')
		  			<li><a data-link="vehicle/category" href="{{ route('vehicle_category.index') }}"><span> Vehicle Category </span></a></li>
		  			@endpermission
			  		@permission('vehicle-make-list')
		  			<li><a data-link="vehicle/make" href="{{ route('vehicle_make.index') }}"><span> Vehicle Make </span></a></li>
		  			@endpermission
		  			@permission('vehicle-model-list')
		  			<li><a data-link="vehicle/model" href="{{ route('vehicle_model.index') }}"><span> Vehicle Model </span></a></li>
		  			@endpermission
		  			@permission('variant-list')
		  			<li><a data-link="variant" href="{{ route('vehicle_variant.index') }}"><span> Vehicle Variant </span></a></li>
		  			@endpermission
		  			@permission('readingfactor-list')
		  			<li><a data-link="reading-factor" href="{{ route('reading_factor.index') }}"><span> Reading Factor </span></a></li>
		  			@endpermission
		  			@permission('checklist-list')
		  			<li><a data-link="vehicle/checklist" href="{{ route('VehicleChecklist.index') }}"><span> Checklist</span></a></li>
		  			@endpermission
		  			@permission('permit-type-list')
		  			<li><a data-link="vehicle/permit-type" href="{{ route('permit_type.index') }}"><span> Vehicle Permit Type </span></a></li>
		  			@endpermission
		  			@permission('specifiaction-master')
		  			<li><a data-link="master_specification" href="{{ route('specification_master.index') }}"><span> Specification Master</span></a></li>
		  			@endpermission
		  			@permission('vehicle-specifications')
		  			<li><a data-link="vehicle/specification" href="{{ route('specification.index') }}"><span> Vehicle Specifications </span></a></li>
		  			@endpermission
		  			@permission('specification-values')
		  			<li><a data-link="specification_values" href="{{ route('specification_values.index') }}"><span> Specification Values </span></a></li>
		  			@endpermission
		
		  			<li><a data-link="body/type" href="{{ route('body_type.index') }}"><span> Body Type </span></a></li>
		  			<li><a data-link="drivetrain" href="{{ route('drivetrain.index') }}"><span> Drivetrain </span></a></li>
		  			<li><a data-link="fuel-type" href="{{ route('fuel_type.index') }}"><span> Fuel Type </span></a></li>
		  			<li><a data-link="no-of-wheels" href="{{ route('vehicle_wheels.index') }}"><span> No of Wheels </span></a></li>
		  			<li><a data-link="rim/wheel" href="{{ route('vehicle_rim.index') }}"><span> Rim / Wheel </span></a></li>
		  			<li><a data-link="tyre-type" href="{{ route('tyre_type.index') }}"><span> Tyre Type </span></a></li>
		  			<li><a data-link="tyre-size" href="{{ route('tyre_size.index') }}"><span> Tyre Size </span></a></li>
		  			 
		  			<li><a data-link="vehicle/configuration" href="{{ route('vehicle_configuration.index') }}"><span> Vehicle Configuration</span></a></li>
			  	</ul>
			</div>
		</li>
		
		<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Pricing </span></a>
		
			<div class="sidebar-submenu">
			  	<ul>
			  		@permission('segment-list')
					<li><a data-link="pricingsegment" href="{{ route('segment.index') }}"><span> Pricing Segment </span></a></li>
					@endpermission
					@permission('segment-details')
					<li><a data-link="segmentdetails" href="{{ route('VehicleSegmentDetail.index') }}"><span>Pricing Segment Details</span></a></li>
					@endpermission
					@permission('price-list')
					<li><a data-link="item-price-list" href="{{ route('wms_item_price_list') }}"><span> Price Lists </span></a></li>
					@endpermission
		  			
				</ul>
			</div>
		
		</li> -->
		  
		<!-- <li><a data-link="employees" href="{{ route('staff.index') }}"><i class="fa fa-users"></i><span>Employees</span></a></li> -->
		@permission('customer-info-list')
		  <li><a data-link="contact" href="{{ route('contact.index', ['wms-customer']) }}"><i class="fa fa-user"></i><span>Customer</span></a></li>
		@endpermission
		<!-- @permission('customer-grouping')
		 <li><a data-link="contact" href="{{ route('under_construction.index') }}"><i class="fa fa-user"></i><span>Customer Grouping </span></a></li>
		@endpermission -->
		@permission('vehicle-register')
		<li><a data-link="registered-vehicles/list" href="{{ route('vehicle_registered.index') }}"><i class="fa fa-car"></i><span>Registered Vehicles</span></a></li>
		@endpermission

		

	
		
		<li class="header"><span>Transactions</span></li>	 
		@permission('wms-jobcard-list')
		<li><a data-link="job_card" href="{{ route('transaction.index', ['job_card']) }}"><i class="fa icon-ecommerce-cart"></i><span>Job Card</span></a></li>
		@endpermission
		@permission('wms-estimation-list')
		<li><a data-link="job_request" href="{{ route('transaction.index', ['job_request']) }}"><i class="fa icon-ecommerce-bag-cloud"></i><span>Estimation</span></a></li>
		@endpermission
		<!-- @permission('wms-job-status-list')
		<li><a data-link="job_status" href="{{ route('Jobstatus.index') }}"><i class="fa icon-ecommerce-cart"></i><span>Job Status</span></a></li>
		@endpermission -->
		@permission('wms-job-invoice-list')
		<li><a data-link="job_invoice" href="{{ route('transaction.index', ['job_invoice']) }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Job Invoice</span></a></li>
		@endpermission
		<!-- <li><a data-link="delivery_note" href="{{ route('transaction.index', ['delivery_note']) }}"><i class="fa icon-basic-todo-pen"></i><span>Delivery Note</span></a></li> -->
		<!-- @permission('wms-deliverynote-list')
		<li><a data-link="" href="{{route('under_construction.index')}}"><i class="fa icon-basic-todo-pen"></i><span>Delivery Note</span></a></li>
		@endpermission  -->
		<!-- <li><a data-link="warehouse/summary" href="{{ route('warehouse_summary.index') }}"><i class="fa fa-building"></i><span>Warehouse Summary</span></a></li> -->
		@if (!App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
			<li><a data-link="purchase_order" href="{{ route('transaction.index', ['purchase_order']) }}"><i class="fa icon-ecommerce-basket-cloud"></i><span>Purchase Order</span></a></li>
		  	<li><a data-link="purchases" href="{{ route('transaction.index', ['purchases']) }}"><i class="fa icon-ecommerce-bag-check"></i><span>Purchase</span></a></li>
		@endif
		@permission('wms-receipt-list')
		<li><a data-link="receipt" href="{{ route('cash_transaction.index', ['wms_receipt']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li>
		@endpermission
		<!-- <li><a data-link="receipt" href="{{ route('under_construction.index') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li> -->

		<li class="header"><span>Reports</span></li>
		@permission('gst-report')
		<li><a data-link="gst-trade" href="{{ route('gst_report.index','wms_sales') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li>
		@endpermission

		<!-- <li><a data-link="gst-trade" href="{{ route('under_construction.index') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li> -->

		@permission('vehicle-report')
		 <li><a data-link="vehicle/list" href="{{ route('vehicle_list_report') }}"><i class="fa fa-truck"></i><span>Vehicle Invoice Report</span></a></li>
		 @endpermission
		<!-- <li><a data-link="vehicle/list" href="{{ route('under_construction.index') }}"><i class="fa fa-truck"></i><span>Vehicle Report</span></a></li> -->

		<!-- <li><a data-link="service-history" href="{{ route('service_history_report') }}"><i class="fa fa-address-card"></i><span>Service History</span></a></li> -->
		<!-- @permission('service-history-report')
		<li><a data-link="service-history" href="{{ route('under_construction.index') }}"><i class="fa fa-address-card"></i><span>Service History</span></a></li>
		@endpermission -->

		<!-- <li><a data-link="vehicle/maintanance-history" href="{{ route('maintanance_history_report') }}"><i class="fa fa-car"></i><span>Vehicle Maintanance History</span></a></li> -->
		<!-- <li><a data-link="vehicle/maintanance-history" href="{{ route('under_construction.index') }}"><i class="fa fa-car"></i><span>Vehicle Maintanance History</span></a></li> -->

		<!-- <li><a data-link="business-customer/report" href="{{ route('business_customer_report') }}"><i class="fa fa-users"></i><span>Business Customer Report</span></a></li> -->
		<!-- <li><a data-link="business-customer/report" href="{{ route('under_construction.index') }}"><i class="fa fa-users"></i><span>Business Customer Report</span></a></li> -->

	
@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'trade_wms');
?>