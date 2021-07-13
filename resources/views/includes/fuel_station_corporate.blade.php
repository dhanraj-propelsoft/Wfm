		
	@if($plan_name =='Corporate')

		<li class="header"><span> Fuel Station </span></li>

	  	<li><a data-link="dashboard" href="{{ route('fuel_station.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span> Dashboard </span></a></li>
	  	<li><a data-link="easy_way" href="{{ route('easy_way.index') }}"><i class="fa fa-plus-circle"></i><span>Easy Way</span></a></li>

		

	  	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Masters </span></a>

			<div class="sidebar-submenu">
			  	<ul>
					<li><a data-link="discount" href="{{ route('discount.index') }}"><span> Discount </span></a></li>
				    <li><a data-link="unit" href="{{ route('unit.index') }}"><span> Units </span></a></li>
					<li><a data-link="shipment/mode" href="{{ route('shipment_mode.index') }}"><span> Shipment Mode</span></a>
					</li>
					<!-- <li><a data-link="payment/method" href=""><span> Payment Method </span></a></li> -->
				   <li><a data-link="items" data-toggle="tooltip" data-placement="top" title="Items" href="{{ route('item.index', ['items']) }}"><span> Items </span></a></li>
				    <li><a data-link="tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
				     <li><a data-link="tank" href="{{ route('tank.index') }}"><span> Tank</span></a>
					</li>
					<li><a data-link="pump_mechine" href="{{ route('pumpmechine.index') }}"><span> Pump Mechine</span></a>
					</li>
		  			
					<li><a data-link="pump" href="{{ route('pump.index') }}"><span> Pump</span></a>
					</li>
					
		  			
				</ul>
			</div>
			
		</li>
		
	  	<li><a data-link="contact" href="{{ route('contact.index', ['customer']) }}"><i class="fa fa-user"></i><span>Customer</span></a></li>
	
	 	<li><a data-link="customer_grouping" href="{{ route('fsm_customer_grouping.index') }}"><i class="fa fa-user"></i><span>Customer Grouping</span></a></li>
	 		<li ><a data-link="registered-vehicles" data-toggle="tooltip" data-placement="top" title="Registered Vehicles" href="{{ route('admin_vehicle_registered.index') }}"><i class="fa fa-car"></i><span>Registered Vehicles</span></a></li>
	


	  	<li><a data-link="contact" href="{{ route('under_construction.index') }}"><i class="fa fa-bell"></i><span>DailyDipAlert</span></a></li>
	  	<li><a data-link="dipreading" href="{{ route('dipreading.index') }}"><i class="fa fa-book"></i><span>DailyReadingBook</span></a></li>
	  	<li><a data-link="stackbook" href="{{ route('stackbook.index') }}"><i class="fa fa-book"></i><span>DailyStockBook</span></a></li>
	    <li class="fsm_todayrate"><a data-link="today_rate" href="{{ route('change_itemrate') }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Todays Rate</span></a></li>

		

	
	
		
		<li class="header"><span>Transactions</span></li>
		
		<li><a data-link="shiftmanagement" href="{{ route('shiftmanagement.index') }}"><i class="fa fa-tasks"></i><span>ShiftManagement</span></a></li>
		
	
		<li><a data-link="invoice" href="{{ route('fsm_invoice') }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span> Invoice</span></a></li>
		
		<li><a data-link="adjustment" href="{{ route('fsm_adjustment.index') }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span> Adjustment</span></a></li>
		
<!-- 
		<li><a data-link="testing_adjustment" href="{{ route('fsm_testing_adjustment.index') }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span> Testing Adjustment</span></a></li> -->

		<!-- <li><a data-link="job_card" href="{{ route('under_construction.index') }}"><i class="fa icon-ecommerce-cart"></i><span>Delivery Challan</span></a></li> -->
		<li class="header"><span>Reports</span></li>
		
		<li><a data-link="receipt" href="{{ route('under_construction.index') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li>

		<li class="header"><span>Reports</span></li>

		@permission('gst-report')
		<li><a data-link="gst-trade" href="{{ route('gst_report.index','wms_sales') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li>
		@endpermission

		<!--<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span> Reports </span></a>-->

		<!--	<div class="sidebar-submenu">-->
		<!--	  	<ul>-->
		<!--			<li><a data-link="business-customer/report" href="{{ route('business_customer_report') }}"><span> Bussiness Customer sales </span></a></li>-->
		<!--		    <li><a data-link="shifttime_vs_sales" href="{{ route('shift_vs_sales') }}"><span> Shift Time VS Sales </span></a></li>-->
		<!--			<li><a data-link="invoice_base_sales" href="{{ route('invoice_base_sales') }}"><span> Invoice Base Sale</span></a>-->
		<!--			</li>-->
					<!-- <li><a data-link="payment/method" href=""><span> Payment Method </span></a></li> -->
		<!--		    <li><a data-link="supplier_list" href="{{ route('supplier_list') }}"><span> Suppliers Details </span></a></li>-->

		  			
		  			
		<!--		</ul>-->
		<!--	</div>-->
			
		<!--</li>-->

	@endif		