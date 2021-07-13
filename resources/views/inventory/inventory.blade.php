@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
			<li class="header"><span>Inventory</span></li>
	  <li><a data-link="dashboard" href="{{ route('inventory.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>
		<div class="sidebar-submenu">
		  <ul>
			  <li><a data-link="warehouse" href="{{ route('warehouse.index') }}"><span>Warehouse</span></a></li>
			  <li><a data-link="stores" href="{{ route('stores.index') }}"><span>Stores</span></a></li>
			  <li><a data-link="rack" href="{{ route('rack.index') }}"><span>Rack</span></a></li>
			  <li><a data-link="discount" href="{{ route('discount.index') }}"><span>Discount</span></a></li>
			  <li><a data-link="unit" href="{{ route('unit.index') }}"><span>Units</span></a></li>
			  <li><a data-link="shipment/mode" href="{{ route('shipment_mode.index') }}"><span>Shipment Mode</span></a></li>
			  <li><a data-link="items" href="{{ route('item.index', ['items']) }}"><span>Items</span></a></li>
			  <!-- <li><a data-link="group-type/item-group" href="{{ route('item_group.index', ['item-group']) }}"><span>Item Group</span></a></li> -->
			  <li><a data-link="tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
			  @if (!App\Organization::checkModuleExists('hrm', Session::get('organization_id')))
				@permission('department-list')
					<li><a  data-link="departments" href="{{ route('hrm_departments.index') }}" title="Buttons" class="sfActive"><span>Department</span></a></li>
				@endpermission
				@permission('designation-list')
					<li><a data-link="designations" href="{{ route('designations.index') }}" title="Labels &amp; Badges"><span>Designation</span></a></li>
				@endpermission
			@endif
			</ul>
		</div>
	  </li>

	  <!-- <li><a data-link="employees" href="{{ route('staff.index') }}"><i class="fa fa-users"></i><span>Employees</span></a></li> -->
	  <li><a data-link="contact" href="{{ route('contact.index', ['vendor']) }}"><i class="fa fa-users"></i><span>Supplier</span></a></li>
	  <li class="header"><span>Transactions</span></li>
	  <li><a data-link="purchase_order" href="{{ route('transaction.index', ['purchase_order']) }}"><i class="fa icon-ecommerce-basket-cloud"></i><span>Purchase Order</span></a></li>
	  <li><a data-link="purchases" href="{{ route('transaction.index', ['purchases']) }}"><i class="fa icon-ecommerce-bag-check"></i><span>Purchase</span></a></li>
	  <li><a data-link="goods_receipt_note" href="{{ route('transaction.index', ['goods_receipt_note']) }}"><i class="fa icon-ecommerce-receipt"></i><span>Goods Receipt Note</span></a></li>
	  <li><a data-link="debit_note" href="{{ route('transaction.index', ['debit_note']) }}"><i class="fa icon-ecommerce-basket-remove"></i><span>Purchase Return</span></a></li>
	  <li><a data-link="internal-consumption" href="{{ route('internal_consumption.index') }}"><i class="fa icon-music-shuffle-button"></i><span>Internal Consumption</span></a></li>
	  <li><a data-link="payment" href="{{ route('cash_transaction.index', ['payment']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Payables</span></a></li>

	  <li><a class="sub-menu"><i class="fa icon-basic-gear"></i><span>Inventory Management</span></a>
		<div class="sidebar-submenu">
			<ul>

			  <li><a data-link="material-receipt" href="{{ route('material_receipt.index') }}"><!-- <i class="fa icon-elaboration-todolist-check"></i> --><span>Material Receipt</span></a></li>
			  <li><a data-link="adjustment" href="{{ route('adjustment.index') }}"><!--<i class="fa icon-basic-mixer2"></i> --><span>Adjustment</span></a></li>
			  <li><a data-link="low-stock-report" href="{{ route('low_stock_report.index') }}"><!-- <i class="fa icon-basic-mixer2"></i> --><span>Low Stock Report</span></a></li>

			<li><a data-link="gst-report" href="{{ route('gst_report.index') }}"><!-- <i class="fa icon-elaboration-todolist-check"></i> --><span>GST Report</span></a></li>


		  </ul>
	  </div>
	  </li>

@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'inventory');
?>