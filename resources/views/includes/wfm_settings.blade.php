@section('sidebar')
@parent
<li class="header"><a href="{{ route('wfm.dashboard') }}"><i class="fa fa-arrow-left"></i><span>Back to WFM HOME</span></a></li>
<li class="header"><span>Settings</span></li>
@if(Session::get('organization_id'))
<li><a href="{{ route('projectcategory.index') }}"><i class="fa fa-building"></i><span>Project Category</span></a></li>
@endif
@if(Session::get('organization_id'))
<!-- <li><a  href="{{ route('priority.index') }}"><i class="fa li_user"></i><span> Priority </span></a></li> -->
<li><a  href="{{ route('roles.index') }}"><i class="fa fa-user-o"></i><span>Role and Permission</span></a></li>
<li><a  href="{{ route('label.index') }}"><i class="fa li_user"></i><span>Label</span></a></li>
<li><a  href="{{ route('size.index') }}"><i class="fa icon-basic-notebook"></i><span>Size</span></a></li>


@endif
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('wfm', Session::get('organization_id')))
			
@endif
@endif
@stop

@section('dom_links')
@parent

@stop