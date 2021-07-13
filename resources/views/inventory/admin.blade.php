@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('super_admin', Session::get('organization_id')))
	<li class="header"><span>Admin</span></li>
	<li><a data-link="dashboard" href="{{ route('admin.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	<li><a data-link="organization" href="{{ route('organization.index') }}"><i class="fa fa-building"></i><span>Organization</span></a></li>
	<li><a data-link="modules" href="{{ route('modules.index') }}"><i class="fa fa-sitemap"></i><span>Modules</span></a></li>

	<li><a class="sub-menu"><i class="fa icon-basic-notebook"></i><span>Books</span></a>
	<div class="sidebar-submenu">
		<ul>
		  	<li><a data-link="bank-account-type" href="{{ route('bank_account_type.index') }}"><span>Bank Account Types</span></a></li>
		 	<li><a data-link="account-person-type" href="{{ route('account_person_type.index') }}"><span>Person Types</span></a></li>
		</ul>
	</div>
	</li>

	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Items</span></a>
	<div class="sidebar-submenu">
		<ul>
		  	<li><a data-link="make" href="{{ route('make.index') }}"><span>Make</span></a></li>
		 	<li><a data-link="main-categories" href="{{ route('main_category.index') }}"><span>Main Category</span></a></li>
		 	<li><a data-link="category" href="{{ route('category.index') }}"><span>Category</span></a></li>
		 	<li><a data-link="item-type" href="{{ route('type.index') }}"><span>Type</span></a></li>
		 	<li><a data-link="items" href="{{ route('model.index') }}"><span>Item</span></a></li>
		</ul>
	</div>
	<li><a data-link="banks" href="{{ route('banks.index') }}"><i class="fa icon-basic-accelerator"></i><span>Banks</span></a></li>
	</li>

	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>SMS</span></a>
	<div class="sidebar-submenu">
		<ul>
		 	<li><a data-link="sender-id" href="{{ route('sender_id') }}"><span>Sender-ID</span></a></li>
		 	<li><a data-link="sms" href="{{ route('sent_sms') }}"><span>Sent-SMS</span></a></li>
		</ul>
	</div>
	</li>

	<li><a data-link="packages" href="{{ route('package.index') }}"><i class="fa icon-basic-accelerator"></i><span>Packages</span></a></li>
	<li><a data-link="ledgers" href="{{ route('package_ledger.index') }}"><i class="fa icon-basic-accelerator"></i><span>Ledgers</span></a></li>

	
@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'super_admin');
?>