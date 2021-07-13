

	@if($plan_name == 'Corporate')

		<li class="header"><span>Inventory</span></li>

	  	@permission('Inventory-Main-Dashboard')
	  	<li><a data-link="dashboard" data-toggle="tooltip" data-placement="top" title="Dashboard" href="{{ route('inventory.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	  	@endpermission


	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Masters"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>

			<div class="sidebar-submenu">

			  <ul>@permission('warehouse-list')
				  <li><a data-link="warehouse" data-toggle="tooltip" data-placement="top" title="Warehouse" href="{{ route('warehouse.index') }}"><span>Warehouse</span></a></li>@endpermission
				  @permission('stores-list')
				  <li><a data-link="stores" data-toggle="tooltip" data-placement="top" title="Stores" href="{{ route('stores.index') }}"><span>Stores</span></a></li>@endpermission
				  @permission('rack-list')
				  <li><a data-link="rack" data-toggle="tooltip" data-placement="top" title="Rack" href="{{ route('rack.index') }}"><span>Rack</span></a></li>@endpermission
				  @permission('discount-list')
				  <li><a data-link="discount" data-toggle="tooltip" data-placement="top" title="Discount" href="{{ route('discount.index') }}"><span>Discount</span></a></li>@endpermission
				  @permission('unit-list')
				  <li><a data-link="unit" data-toggle="tooltip" data-placement="top" title="Units" href="{{ route('unit.index') }}"><span>Units</span></a></li>@endpermission
				  @permission('shipment-mode-list')
				  <li><a data-link="shipment/mode" data-toggle="tooltip" data-placement="top" title="Shipment Mode" href="{{ route('shipment_mode.index') }}"><span>Shipment Mode</span></a></li>@endpermission

				  <!-- <li><a data-link="category" href="{{ route('category.index', ['category']) }}"><span>Categories</span></a></li> -->

				  @permission('item-list')
				  <li><a data-link="items" data-toggle="tooltip" data-placement="top" title="Items" href="{{ route('item.index', ['items']) }}"><span>Items</span></a></li>@endpermission
				  
				  <!-- <li><a data-link="group-type/item-group" href="{{ route('item_group.index', ['item-group']) }}"><span>Item Group</span></a></li> -->

				  @permission('tax-list')
				  <li><a data-link="tax" data-toggle="tooltip" data-placement="top" title="Tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
				  @endpermission
				  <!-- @if (!App\Organization::checkModuleExists('hrm', Session::get('organization_id')))
					@permission('department-list')
						<li><a  data-link="departments" href="{{ route('hrm_departments.index') }}" title="Buttons" class="sfActive"><span>Department</span></a></li>
					@endpermission
					@permission('designation-list')
						<li><a data-link="designations" href="{{ route('designations.index') }}" title="Labels &amp; Badges"><span>Designation</span></a></li>
					@endpermission
				@endif -->
				</ul>

			</div>

	  	</li>

	  	<!-- <li><a data-link="employees" href="{{ route('staff.index') }}"><i class="fa fa-users"></i><span>Employees</span></a></li> -->
	   	@permission('supplier-info-list')
	  	<li><a data-link="contact" data-toggle="tooltip" data-placement="top" title="Supplier" href="{{ route('contact.index', ['vendor']) }}"><i class="fa fa-users"></i><span>Supplier</span></a></li>
	  	@endpermission

	  	<li class="header"><span>Transactions</span></li>

	   	@permission('purchase-order-list')
	  	<li><a data-link="purchase_order" data-toggle="tooltip" data-placement="top" title="Purchase Order" href="{{ route('transaction.index', ['purchase_order']) }}"><i class="fa icon-ecommerce-basket-cloud"></i><span>Purchase Order</span></a></li>@endpermission

	   	@permission('purchase-list')
	  	<li><a data-link="purchases" data-toggle="tooltip" data-placement="top" title="Purchase" href="{{ route('transaction.index', ['purchases']) }}"><i class="fa icon-ecommerce-bag-check"></i><span>Purchase</span></a></li>@endpermission
	  
	 	@permission('goods-receipt-note-list')
	  	<li><a data-link="goods_receipt_note" data-toggle="tooltip" data-placement="top" title="Goods Receipt Note" href="{{ route('transaction.index', ['goods_receipt_note']) }}"><i class="fa icon-ecommerce-receipt"></i><span>Goods Receipt Note</span></a></li>@endpermission
	   
	   	@permission('purchase-return-list')
	  	<li><a data-link="debit_note" data-toggle="tooltip" data-placement="top" title="Purchase Return" href="{{ route('transaction.index', ['debit_note']) }}"><i class="fa icon-ecommerce-basket-remove"></i><span>Purchase Return</span></a></li>
	  	@endpermission

	   	@permission('internal-consumption-list')
	  	<li><a data-link="internal-consumption" data-toggle="tooltip" data-placement="top" title="Internal Consumption" href="{{ route('internal_consumption.index') }}"><i class="fa icon-music-shuffle-button"></i><span>Internal Consumption</span></a></li>@endpermission

	   	@permission('payables-list')
	  	<li><a data-link="payment" data-toggle="tooltip" data-placement="top" title="Payables" href="{{ route('cash_transaction.index', ['payment']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Payables</span></a></li>
	  	@endpermission

	  	<li><a class="sub-menu" data-toggle="tooltip" data-placement="top" title="Inventory Management"><i class="fa icon-basic-gear"></i><span>Inventory Management</span></a>

			<div class="sidebar-submenu">
				<ul>
				  	@permission('material-receipt-list')
				  	<li><a data-link="material-receipt" data-toggle="tooltip" data-placement="top" title="Material Receipt" href="{{ route('material_receipt.index') }}"><!-- <i class="fa icon-elaboration-todolist-check"></i> --><span>Material Receipt</span></a></li>@endpermission
				   	@permission('adjustment-list')
				  	<li><a data-link="adjustment" data-toggle="tooltip" data-placement="top" title="Adjustment" href="{{ route('adjustment.index') }}"><!--<i class="fa icon-basic-mixer2"></i> --><span>Adjustment</span></a></li>@endpermission

				  	@permission('low-stock-report-list')
				  	<li><a data-link="low-stock-report" data-toggle="tooltip" data-placement="top" title="Low Stock Report" href="{{ route('low_stock_report.index') }}"><!-- <i class="fa icon-basic-mixer2"></i> --><span>Low Stock Report</span></a></li>@endpermission
					<!-- @permission('jc-stock-report')
									   	<li><a data-link="low-stock-report" href="{{ route('jc_stock_report.index') }}"><i class="fa icon-basic-mixer2"></i><span>JC Stock Report</span></a></li>
					@endpermission -->
					@permission('gst-report-list')
					<li><a data-link="gst-report" data-toggle="tooltip" data-placement="top" title="GST Report" href="{{ route('gst_report.index','purchases') }}"><!-- <i class="fa icon-elaboration-todolist-check"></i> --><span>GST Report</span></a></li>@endpermission
					<li><a data-link="receipts_report" data-toggle="tooltip" data-placement="top" title="Receivables Report" href="{{ route('receipts_report') }}"><span>Receivables Report</span></a></li>
					<li><a data-link="market_place" data-toggle="tooltip" data-placement="top" title="Market Place " href="{{ route('get_market_place') }}"><span>Market Place</span></a></li> 
					 @permission('age-of-products')
					<li><a data-link="age_of_goods" data-toggle="tooltip" data-placement="top" title="Age of Goods" href="{{route('age_of_goods')}}"><span>Age Of Goods</span></a></li>
					@endpermission
					<li><a data-link="today_stock_report" data-toggle="tooltip" data-placement="top" title="Today Stock" href="{{route('today_stock_report')}}"><span>Today Stock Ledgers</span></a></li>
			  	</ul>
		  	</div>
	  	</li>
     

       
	@endif  	