@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('project', Session::get('organization_id')))
	  <li class="header"><span>Overview</span></li>
	  <li><a><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>
		<div class="sidebar-submenu">
		  <ul>
			  <li><a data-link="rack" href="{{ route('rack.index') }}"><span>Service Frequency</span></a></li>
	  		  <li><a data-link="rack" href="{{ route('rack.index') }}"><span>Work</span></a></li>
	  		  <li><a data-link="rack" href="{{ route('rack.index') }}"><span>Job</span></a></li>
	  		  <li><a data-link="rack" href="{{ route('rack.index') }}"><span>Work Allocation</span></a></li>
			</ul>
		</div>
	  </li>
	<li class="header"><span>Transactions</span></li>
	  <li><a><i class="fa icon-basic-folder-multiple"></i><span>Milestone</span></a>
	  <li><a><i class="fa icon-basic-folder-multiple"></i><span>Tasks</span></a>
	  <li><a><i class="fa icon-basic-folder-multiple"></i><span>Timesheet</span></a>
	  <li><a><i class="fa icon-basic-folder-multiple"></i><span>Employees</span></a>

	<li class="header"><span>Others</span></li>
	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Work Reports</span></a>
@endif
@endif
@stop

@section('dom_links')
@parent
@stop