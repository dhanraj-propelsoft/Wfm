		
	@if($plan_name == 'Corporate')

		<li class="header"><span>Trade</span></li>

	  @permission('Trade-Main-Dashboard')
	  	<li><a data-link="dashboard" href="{{ route('trade.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	  	@endpermission
	  	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>

			<div class="sidebar-submenu">
			  	<ul>
					<!-- <li><a data-link="lead/status" href="{{ route('lead_status.index') }}"><span>Lead Status</span></a></li>
					<li><a data-link="lead/source" href="{{ route('lead_source.index') }}"><span>Lead Source</span></a></li> -->
					@permission('discount-list')
					<li><a data-link="discount" href="{{ route('discount.index') }}"><span>Discount</span></a></li>
					@endpermission
					@permission('unit-list')
				    <li><a data-link="unit" href="{{ route('unit.index') }}"><span>Units</span></a></li>
				    @endpermission
				    @permission('shipment-mode-list')
					<li><a data-link="shipment/mode" href="{{ route('shipment_mode.index') }}"><span>Shipment Mode</span></a></li>
					@endpermission
					@permission('item-list')
				    <li><a data-link="items" href="{{ route('item.index', ['items']) }}"><span>Items</span></a></li>@endpermission
				     <li><a data-link="group-type/item-group" href="{{ route('item_group.index', ['item-group']) }}"><span>Item Group</span></a></li>
				    @permission('tax-list')

		  			<li><a data-link="tax" href="{{ route('tax.index') }}"><span>Tax</span></a></li>
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
		@permission('customer-info-list')
	  	<li><a data-link="contact" href="{{ route('contact.index', ['customer']) }}"><i class="fa fa-user"></i><span>Customer</span></a></li>
	  	@endpermission

	  	<li class="header"><span>Transactions</span></li>

	  	@permission('estimate-list')
	  	<li><a data-link="estimation" href="{{ route('transaction.index', ['estimation']) }}"><i class="fa icon-ecommerce-cart"></i><span>Estimate</span></a></li>
	  	@endpermission

		@permission('sale-order-list')
	  	<li><a data-link="sale_order" href="{{ route('transaction.index', ['sale_order']) }}"><i class="fa icon-ecommerce-bag-cloud"></i><span>Sale Order</span></a></li>
		@endpermission 

		@permission('sales-list')
		<li><a data-link="transaction/sales" href="{{ route('transaction.index', ['sales']) }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Invoice</span></a></li>
		@endpermission
	  	<!-- <li><a data-link="recurring-transactions/sales" href="{{ route('recurring_transaction', ['sales']) }}"><i class="fa icon-ecommerce-receipt-rupee"></i><span>Recurring Invoice</span></a></li>  -->
	  	@permission('delivery-challan-list')
	  	<li><a data-link="delivery_note" href="{{ route('transaction.index', ['delivery_note']) }}"><i class="fa icon-basic-todo-pen"></i><span>Delivery Challan</span></a></li>@endpermission

	   	@permission('sales-return-list')
	  	<li><a data-link="credit_note" href="{{ route('transaction.index', ['credit_note']) }}"><i class="fa icon-ecommerce-bag-upload"></i><span>Sale Return</span></a></li>@endpermission

		@permission('warehouse-summary-list')
	  	<li><a data-link="warehouse/summary" href="{{ route('warehouse_summary.index') }}"><i class="fa fa-building"></i><span>Warehouse Summary</span></a></li>@endpermission

	  	<!-- @if (!App\Organization::checkModuleExists('inventory', Session::get('organization_id')))
	  	<li><a data-link="purchase_order" href="{{ route('transaction.index', ['purchase_order']) }}"><i class="fa icon-ecommerce-basket-cloud"></i><span>Purchase Order</span></a></li>
	  	<li><a data-link="purchases" href="{{ route('transaction.index', ['purchases']) }}"><i class="fa icon-ecommerce-bag-check"></i><span>Purchase</span></a></li>
	  	@endif -->

	  	@permission('receivables-list')
	  	<li><a data-link="receipt" href="{{ route('cash_transaction.index', ['receipt']) }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables</span></a></li>
	  	@endpermission

	  	<li class="header"><span>Reports</span></li>

	 	@permission('gst-report-list')
	  	<li><a data-link="gst-trade" href="{{ route('gst_report.index','sales') }}"><i class="fa icon-elaboration-todolist-check"></i><span>GST Report</span></a></li>
	 	@endpermission

	 	<li><a data-link="receipt_report" href="{{ route('receipt_report') }}"><i class="fa icon-ecommerce-wallet"></i><span>Receivables Report</span></a></li>
	 	

	@endif 	