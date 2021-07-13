@section('sidebar')
@parent
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('workshop', Session::get('organization_id')))
	  <li class="header"><span>Overview</span></li>
	  <li><a data-link="dashboard" href="{{ route('workshop.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>
	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>
		<div class="sidebar-submenu">
		  <ul>
			  	<li><a data-link="service-type" href="{{ route('service_type.index') }}"><span>Service Type</span></a></li>
		  		<li><a data-link="model" href="{{ route('vehicle_model.index') }}"><span>Model</span></a></li>
		  		<li><a data-link="item-type/division" href="{{ route('division.index', ['division']) }}"><span>Division</span></a></li>
		  		<li><a data-link="type/works" href="{{ route('work.index', ['works']) }}"><span>Work</span></a></li>
		  		<li><a data-link="group-type/work-group" href="{{ route('work_group.index', ['work-group']) }}"><span>Work Group</span></a></li>
		  		<li><a data-link="job-allocation" href="{{ route('work_allocation.index') }}"><span>Job Allocation</span></a></li>
			</ul>
		</div>
	  </li>
	<li class="header"><span>Transactions</span></li>
	  <li><a data-link="estimation" href="{{ route('job_estimation.index', ['estimation', 'job']) }}"><i class="fa icon-ecommerce-receipt"></i><span>Estimate</span></a></li>
	  <li><a data-link="job_card" href="{{ route('transaction.index', ['job_card']) }}"><i class="fa icon-basic-spread-text-bookmark"></i><span>Job Card</span></a></li>
	  <!-- <li><a data-link="jobs" href="{{ route('jobs.index') }}"><i class="fa icon-basic-pin2"></i><span>Jobs</span></a></li>
	  <li><a><i class="fa icon-basic-share"></i><span>Resources</span></a></li> -->

	<li class="header"><span>Others</span></li>
	  <li><a data-link="vehicles" href="{{ route('vehicles.index') }}"><i class="fa icon-basic-accelerator"></i><span>Vehicles</span></a></li>
	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Reports</span></a></li>
@endif
@endif
@stop

@section('dom_links')
@parent
@stop