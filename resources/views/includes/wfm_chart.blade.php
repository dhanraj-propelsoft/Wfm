@section('sidebar')
@parent
<li class="header"><a href="{{ route('wfm.dashboard') }}"><i class="fa fa-arrow-left"></i><span>Back to WFM Home</span></a></li>

@if(Session::get('organization_id'))
<!-- <li><a  href="{{ route('priority.index') }}"><i class="fa li_user"></i><span> Priority </span></a></li> -->

          @permission('wfm-chart-view-menu')
        <li ><a href="{{ url('wfm/dashboard/summary') }}/All">Chart View</a></li>
         @endpermission

          @permission('wfm-manage-projects-menu')
         <li><a href="{{ route('wfm.project_list') }}">Manage Projects</a></li>
         @endpermission
          @permission('wfm-master-dataset-menu')
          <li><a href="{{ url('wfm/wfm_settings') }}">Master Dataset</a></li>
          @endpermission

@endif
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))
			
@endif
@endif
@stop

@section('dom_links')
@parent

@stop