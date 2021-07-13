@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('books', Session::get('organization_id')))
	<li class="header"><span>Accounts</span></li>
	<li><a data-link="dashboard"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>
	<div class="sidebar-submenu">
		<ul>
		  	<li><a data-link="groups" href="{{ route('ledger_groups.index') }}"><span>Ledger Groups</span></a></li>
		 	<li><a data-link="ledgers" href="{{ route('ledgers.index') }}"><span>Ledgers</span></a></li>
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
	<li class="header"><span>Transactions</span></li>
	<li><a data-link="transactions" href="{{ route('vouchers.index') }}"><i class="fa icon-basic-sheet-txt"></i><span>Transactions</span></a></li> 
	<li><a data-link="cheque-book" href="{{ route('cheque_book.index') }}"><i class="fa icon-basic-notebook"></i><span>Cheque Books</span></a></li> 
	<li class="header"><span>Others</span></li>
	<!-- <li><a data-link="contacts" href="{{ route('cheque_book.index') }}"><i class="fa fa-user-o"></i><span>Contacts</span></a></li> -->
	<li><a class="sub-menu"><i class="fa icon-ecommerce-graph-increase"></i><span>Reports</span></a>
		<div class="sidebar-submenu">
		  	<ul>
			  	@permission('transactions-list')
				<!-- <li><a data-link="transactions" href="{{-- route('transactions') --}}">Transactions</a></li> -->
			  	@endpermission

			  	@permission('general-ledger-list')
			   	<!--  <li><a data-link="ledger/statement"  href="{{-- route('general_ledger') --}}">General Ledgers</a></li> -->
			  	@endpermission
				<li><a data-link="balance-sheet"  href="{{ route('balance_sheet') }}">Balance Sheet</a> </li>
				<li><a data-link="income-expense"  href="{{ route('profit_and_loss') }}">Incomes and Expenses</a></li>
				<li><a data-link="journal-report"  href="{{ route('journal_report') }}">Journal Report</a></li>
				<li><a data-link="ledger-statement"  href="{{ route('ledger_statement.index') }}">Statement of Accounts</a></li>
				<li><a data-link="trial-balance"  href="{{ route('trial_balance') }}">Trial Balance</a></li>
			</ul>
		</div>
	</li>
	<!-- <li><a data-link="" href=""><i class="fa icon-basic-notebook"></i><span>People</span></a></li>  -->
	<li><a class="sub-menu"><i class="fa icon-basic-gear"></i><span>Settings</span></a>
		<div class="sidebar-submenu">
		  	<ul>
			  	<li class="nav-item">
				<?php $financialyear = App\AccountFinancialYear::where('organization_id', Session::get('organization_id'))->where('status', '1')->first(); ?>

				<a data-link="financial-year"  href="{{ route('financial_year.edit', [$financialyear->id]) }}">
				  Financial Year</a>
			  	</li>

			  	@permission('voucher-list')
					<li><a data-link="voucher/list"  href="{{ route('voucher_list.index') }}"><span class="title">Vouchers</span></a></li>
			  	@endpermission
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
	Session::put('module_name', 'accounts');
?>