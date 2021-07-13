		
	@if($plan_name =='Professional')

		<li class="header"><span> Trade-WMS </span></li>
		@permission('WMS-jobboard')
		<li><a data-link="job_board" data-toggle="tooltip" data-placement="top" title="Job Board" href="{{ route('trade_wms.job_board') }}"><i class="fa icon-basic-accelerator"></i><span>Job Board</span></a></li>
		@endpermission

		
		

	  	@permission('wms_homepage')
	  	<li><a data-link="home_page" data-toggle="tooltip" data-placement="top" title="Home Page" href="{{ route('home_page.index') }}"><i class="fa fa-home"></i><span> Home Page </span></a></li>
	  		@endpermission
	  	

	 
	  
	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title=" Propel Management"><i class="fa icon-basic-folder-multiple"></i><span> Propel Management </span></a>

			<div class="sidebar-submenu">
			  	<ul>
			  	     @permission('today_summary')
			  	    <li><a data-link="jobstatus_dashboard" data-toggle="tooltip" data-placement="top" title="Today Summary" href="{{ route('trade_wms.today_summary') }}"><span>Today Summary</span></a></li>
			  	    @endpermission

					@permission('WMS-Main-Dashboard')
	  	            <li><a data-link="dashboard" data-toggle="tooltip" data-placement="top" title="Dashboard" href="{{ route('trade_wms.dashboard') }}"><span> Dashboard </span></a></li>
	            	@endpermission
				    @permission('wms-job-status-list')
						<li><a data-link="job_status" data-toggle="tooltip" data-placement="top" title="Job Status" href="{{ route('Jobstatus.index') }}"><span>Job Status</span></a></li>
					@endpermission
					@permission('WMS-JC-Stock-Report')
				   	<li><a data-link="low-stock-report" data-toggle="tooltip" data-placement="top" title="JC Stock Report" href="{{ route('jc_stock_report.index') }}"><span>JC Stock Report</span></a></li>
					@endpermission
					@permission('WMS-JC-CustomerPromotion-Report')
					 <li><a data-link="customer_promotion" data-toggle="tooltip" data-placement="top" title="Customer Promotion" href="{{ route('customer_promotion') }}"><span>Customer Promotion</span></a></li>
					@endpermission
					
					@permission('ALL-Reports-Section')
					<li><a data-link="all_reports" data-toggle="tooltip" data-placement="top" title="Reports" href="{{ route('all_reports.index') }}"><span>Reports</span></a></li>	
					@endpermission
					@permission('WMS-Scheduleboard')
	  		        <li><a data-link="schedule_board" href="{{ route('trade_wms.schedule_board') }}">
	  	        	<span>Schedule Board</span></a></li>
	            	@endpermission
	            	<li ><a data-link="visiting_jobcard" data-toggle="tooltip" data-placement="top"  title="Next Visit View Vechile" href="{{ route('visiting_jobcard') }}"><span>Next Visit View Vechile</span></a></li>
				 	 

				 
		  			
		  			
				</ul>
			</div>
			
		</li>

	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Masters"><i class="fa icon-basic-folder-multiple"></i><span> Masters </span></a>

			<div class="sidebar-submenu">
			  	<ul>
					<li><a data-link="discount" data-toggle="tooltip" data-placement="top" title="Discount" href="{{ route('discount.index') }}"><span> Discount </span></a></li>
				    <li><a data-link="unit" data-toggle="tooltip" data-placement="top" title="Units" href="{{ route('unit.index') }}"><span> Units </span></a></li>
					<li><a data-link="shipment/mode" data-toggle="tooltip" data-placement="top" title=" Shipment Mode" href="{{ route('shipment_mode.index') }}"><span> Shipment Mode</span></a>
					</li>
					<!-- <li><a data-link="payment/method" href=""><span> Payment Method </span></a></li> -->
				    <li><a data-link="items" data-toggle="tooltip" data-placement="top" title="Items" href="{{ route('item.index', ['items']) }}"><span> Items </span></a></li>
				    <li><a data-link="tax" data-toggle="tooltip" data-placement="top" title="Tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
				     @permission('customer-grouping')
					<li><a data-link="customer_grouping" data-toggle="tooltip" data-placement="top" title="Customer Grouping" href="{{ route('customer_grouping.index') }}"><span>Customer Grouping </span></a></li>
					@endpermission
		  			
		  			<li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Vehicle Masters"><span> Vehicle Masters</span></a>
						<div class="sidebar-submenu">
							<ul  style="margin-left: 20px;">
								@permission('service-type-list')
						  		<li><a data-link="service-type" data-toggle="tooltip" data-placement="top" title="Service Type " href="{{ route('service_type.index') }}"><span> Service Type </span></a></li>
						  		@endpermission
						  		@permission('vehicle-category-list')
					  			<li><a data-link="vehicle/category" data-toggle="tooltip" data-placement="top" title=" Vehicle Category" href="{{ route('vehicle_category.index') }}"><span> Vehicle Category </span></a></li>
					  			@endpermission
						  		@permission('vehicle-make-list')
					  			<li><a data-link="vehicle/make" data-toggle="tooltip" data-placement="top" title="Vehicle Make" href="{{ route('vehicle_make.index') }}"><span> Vehicle Make </span></a></li>
					  			@endpermission
					  			@permission('vehicle-model-list')
					  			<li><a data-link="vehicle/model" data-toggle="tooltip" data-placement="top" title="Vehicle Model" href="{{ route('vehicle_model.index') }}"><span> Vehicle Model </span></a></li>
					  			@endpermission
					  			@permission('variant-list')
					  			<li><a data-link="variant" data-toggle="tooltip" data-placement="top" title="Vehicle Variant" href="{{ route('vehicle_variant.index') }}"><span> Vehicle Variant </span></a></li>
					  			@endpermission
					  			@permission('readingfactor-list')
					  			<li><a data-link="reading-factor" data-toggle="tooltip" data-placement="top" title="Reading Factor" href="{{ route('reading_factor.index') }}"><span> Reading Factor </span></a></li>
					  			@endpermission
					  			@permission('checklist-list')
					  			<li><a data-link="vehicle/checklist" data-toggle="tooltip" data-placement="top" title=" Checklist" href="{{ route('VehicleChecklist.index') }}"><span> Checklist</span></a></li>
					  			@endpermission
					  			@permission('permit-type-list')
					  			<li><a data-link="vehicle/permit-type" data-toggle="tooltip" data-placement="top" title=" Vehicle Permit Type" href="{{ route('permit_type.index') }}"><span> Vehicle Permit Type </span></a></li>
					  			@endpermission
					  			 @permission('specifiaction-master')
					  			<li><a data-link="master_specification" data-toggle="tooltip" data-placement="top" title="Specification Master" href="{{ route('specification_master.index') }}"><span> Specification Master</span></a></li>
					  			@endpermission
					  			@permission('vehicle-specifications')
					  			<li><a data-link="vehicle/specification" data-toggle="tooltip" data-placement="top" title="Vehicle Specifications" href="{{ route('specification.index') }}"><span> Vehicle Specifications </span></a></li>
					  			@endpermission
					  			@permission('specification-values')
					  			<li><a data-link="specification_values" data-toggle="tooltip" data-placement="top" title="Specification Values " href="{{ route('specification_values.index') }}"><span> Specification Values </span></a></li>
					  			@endpermission 
							</ul>
						</div>
					</li>
					<li ><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Pricing"><span> Pricing</span></a>
						<div class="sidebar-submenu">
							<ul  style="margin-left: 20px;">
								@permission('segment-list')
								<li><a data-link="pricingsegment" data-toggle="tooltip" data-placement="top" title=" Pricing Segment" href="{{ route('segment.index') }}"><span> Pricing Segment </span></a></li>
								@endpermission
								@permission('segment-details')
								<li><a data-link="segmentdetails" data-toggle="tooltip" data-placement="top" title="Pricing Segment Details" href="{{ route('VehicleSegmentDetail.index') }}"><span>Pricing Segment Details</span></a></li>
								@endpermission
								@permission('price-list')
								<li><a data-link="item-price-list" data-toggle="tooltip" data-placement="top" title="Price Lists" href="{{ route('wms_item_price_list') }}"><span> Price Lists </span></a></li>
								@endpermission
							</ul>
						</div>
					</li>
		  			
				</ul>
			</div>
			
		</li>

		<!-- <li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Vehicle Masters"><i class="fa icon-basic-folder-multiple"></i><span> Vehicle Masters </span></a>
		
		  	<div class="sidebar-submenu">
			  	<ul>
			  		@permission('service-type-list')
			  		<li><a data-link="service-type" data-toggle="tooltip" data-placement="top" title="Service Type " href="{{ route('service_type.index') }}"><span> Service Type </span></a></li>
			  		@endpermission
			  		@permission('vehicle-category-list')
		  			<li><a data-link="vehicle/category" data-toggle="tooltip" data-placement="top" title=" Vehicle Category" href="{{ route('vehicle_category.index') }}"><span> Vehicle Category </span></a></li>
		  			@endpermission
			  		@permission('vehicle-make-list')
		  			<li><a data-link="vehicle/make" data-toggle="tooltip" data-placement="top" title="Vehicle Make" href="{{ route('vehicle_make.index') }}"><span> Vehicle Make </span></a></li>
		  			@endpermission
		  			@permission('vehicle-model-list')
		  			<li><a data-link="vehicle/model" data-toggle="tooltip" data-placement="top" title="Vehicle Model" href="{{ route('vehicle_model.index') }}"><span> Vehicle Model </span></a></li>
		  			@endpermission
		  			@permission('variant-list')
		  			<li><a data-link="variant" data-toggle="tooltip" data-placement="top" title="Vehicle Variant" href="{{ route('vehicle_variant.index') }}"><span> Vehicle Variant </span></a></li>
		  			@endpermission
		  			@permission('readingfactor-list')
		  			<li><a data-link="reading-factor" data-toggle="tooltip" data-placement="top" title="Reading Factor" href="{{ route('reading_factor.index') }}"><span> Reading Factor </span></a></li>
		  			@endpermission
		  			@permission('checklist-list')
		  			<li><a data-link="vehicle/checklist" data-toggle="tooltip" data-placement="top" title=" Checklist" href="{{ route('VehicleChecklist.index') }}"><span> Checklist</span></a></li>
		  			@endpermission
		  			@permission('permit-type-list')
		  			<li><a data-link="vehicle/permit-type" data-toggle="tooltip" data-placement="top" title=" Vehicle Permit Type" href="{{ route('permit_type.index') }}"><span> Vehicle Permit Type </span></a></li>
		  			@endpermission
		  			 @permission('specifiaction-master')
		  			<li><a data-link="master_specification" data-toggle="tooltip" data-placement="top" title="Specification Master" href="{{ route('specification_master.index') }}"><span> Specification Master</span></a></li>
		  			@endpermission
		  			@permission('vehicle-specifications')
		  			<li><a data-link="vehicle/specification" data-toggle="tooltip" data-placement="top" title="Vehicle Specifications" href="{{ route('specification.index') }}"><span> Vehicle Specifications </span></a></li>
		  			@endpermission
		  			@permission('specification-values')
		  			<li><a data-link="specification_values" data-toggle="tooltip" data-placement="top" title="Specification Values " href="{{ route('specification_values.index') }}"><span> Specification Values </span></a></li>
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
		
		<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Pricing"><i class="fa icon-basic-folder-multiple"></i><span> Pricing </span></a>
		
			<div class="sidebar-submenu">
			  	<ul>
			  		@permission('segment-list')
					<li><a data-link="pricingsegment" data-toggle="tooltip" data-placement="top" title=" Pricing Segment" href="{{ route('segment.index') }}"><span> Pricing Segment </span></a></li>
					@endpermission
					@permission('segment-details')
					<li><a data-link="segmentdetails" data-toggle="tooltip" data-placement="top" title="Pricing Segment Details" href="{{ route('VehicleSegmentDetail.index') }}"><span>Pricing Segment Details</span></a></li>
					@endpermission
					@permission('price-list')
					<li><a data-link="item-price-list" data-toggle="tooltip" data-placement="top" title="Price Lists" href="{{ route('wms_item_price_list') }}"><span> Price Lists </span></a></li>
					@endpermission
		  			
				</ul>
			</div>
		
		</li> -->
		  
		<!-- <li><a data-link="employees" href="{{ route('staff.index') }}"><i class="fa fa-users"></i><span>Employees</span></a></li> -->
		@permission('wms-customer-info-list')
		  <li><a data-link="contact" data-toggle="tooltip" data-placement="top" title="Customer" href="{{ route('contact.index', ['wms-customer']) }}"><i class="fa fa-user"></i><span>Customer</span></a></li>
		@endpermission
		<!-- @permission('customer-grouping')
		  <li><a data-link="customer_grouping" data-toggle="tooltip" data-placement="top" title="Customer Grouping" href="{{ route('customer_grouping.index') }}"><i class="fa fa-user"></i><span>Customer Grouping </span></a></li>
		@endpermission -->
		@permission('vehicle-register')
		<li style = "width:80%"><a data-link="registered-vehicles/list" data-toggle="tooltip" data-placement="top" title="Registered Vehicles" href="{{ route('vehicle_registered.index') }}"><i class="fa fa-car"></i><span>Registered Vehicles</span></a><span class="pull-right add_vehicle" style="position: absolute;right: -40px;top: 0px;color: #868e96;font-size:20px;"><i class="fa fa-plus-circle" data-toggle='tooltip' title="Add Vehicle"></i></span></li>
		@endpermission

	
		<li class="header"><span>Transactions</span></li>	 
		@permission('wms-jobcard-list')
	 	<li style = "width:70%"><a data-link="job_card" data-toggle="tooltip" data-placement="top" title="Job Card" href="{{ route('jobcard.index') }}"><i class="fa icon-ecommerce-cart"></i><span>Job Card</span></a><span class="pull-right add_jobcard" style="position: absolute;right: -60px;top: 0px;color: #868e96;font-size: 20px;"><i class="fa fa-plus-circle" data-toggle='tooltip' title="Add Job Card"></i></span></li>
		@endpermission
		
		@permission('wms-estimation-list')
		<li><a data-link="job_request" data-toggle="tooltip" data-placement="top" title="Estimation" href="{{ route('transaction.index', ['job_request']) }}"><i class="fa icon-ecommerce-bag-cloud"></i><span>Estimation</span></a></li>
		@endpermission
		<!-- @permission('wms-job-status-list')
		<li><a data-link="job_status" href="{{ route('Jobstatus.index') }}"><i class="fa icon-ecommerce-cart"></i><span>Job Status</span></a></li>
		@endpermission -->
		@permission('wms-job-invoice-list')
		<li><a data-link="job_invoice" data-toggle="tooltip" data-placement="top" title="Job Invoice" href="{{ route('transaction.index', ['job_invoice']) }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Job Invoice</span></a></li>
		@endpermission
		<!-- <li><a data-link="delivery_note" href="{{ route('transaction.index', ['delivery_note']) }}"><i class="fa icon-basic-todo-pen"></i><span>Delivery Note</span></a></li> -->
		<!-- @permission('wms-deliverynote-list')
		<li><a data-link="" href="{{route('under_construction.index')}}"><i class="fa icon-basic-todo-pen"></i><span>Delivery Note</span></a></li>
		@endpermission  -->
		<!-- <li><a data-link="warehouse/summary" href="{{ route('warehouse_summary.index') }}"><i class="fa fa-building"></i><span>Warehouse Summary</span></a></li> -->
		<!-- @if (!App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
			<li><a data-link="purchase_order" href="{{ route('transaction.index', ['purchase_order']) }}"><i class="fa icon-ecommerce-basket-cloud"></i><span>Purchase Order</span></a></li>
		  	<li><a data-link="purchases" href="{{ route('transaction.index', ['purchases']) }}"><i class="fa icon-ecommerce-bag-check"></i><span>Purchase</span></a></li>
		@endif -->
		@permission('WMS-Receivables')
		<li><a data-link="receipt" data-toggle="tooltip" data-placement="top" title="Receivables" href="{{ route('cash_transaction.index', ['wms_receipt']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li>
		@endpermission
		<!-- <li><a data-link="receipt" href="{{ route('under_construction.index') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li> -->

		<li class="header"><span>Reports</span></li>
		@permission('gst-report')
		<li><a data-link="gst-trade" data-toggle="tooltip" data-placement="top" title="GST Report" href="{{ route('gst_report.index','wms_sales') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li>
		@endpermission

		<!-- <li><a data-link="gst-trade" href="{{ route('under_construction.index') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li> -->

		@permission('vehicle-report')
		 <li><a data-link="vehicle/list" data-toggle="tooltip" data-placement="top" title="Vehicle Invoice Report" href="{{ route('vehicle_list_report') }}"><i class="fa fa-truck"></i><span>Vehicle Invoice Report</span></a></li>
		 @endpermission

		 <!-- <li><a data-link="customer_promotion" href="{{ route('customer_promotion') }}"><i class="fa fa-users"></i><span>Customer Promotion</span></a></li> -->

	     <li><a data-link="receivables_report" data-toggle="tooltip" data-placement="top" title="Receivables Report" href="{{ route('receivables_report') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables Report</span></a></li> 
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