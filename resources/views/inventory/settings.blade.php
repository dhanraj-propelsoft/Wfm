@section('sidebar')
@parent
<li class="header"><span>Settings</span></li>
<li><a data-link="theme" href="{{ route('theme_settings') }}"><i class="fa icon-basic-display"></i><span>Theme Settings</span></a></li>
@if(Session::get('organization_id'))
<li><a data-link="business_profile" href="{{ route('business_profile.show', [App\Organization::find(Session::get('organization_id'))->business_id]) }}"><i class="fa fa-building"></i><span>Company Profile</span></a></li>
@endif
<li><a data-link="password/change" href="{{ route('change_password') }}"><i class="fa icon-basic-key"></i><span>Change Password</span></a></li>
@if(Session::get('organization_id'))
<li><a data-link="people" href="{{ route('people.index') }}"><i class="fa li_user"></i><span> People </span></a></li>
<li><a data-link="roles" href="{{ route('roles.index') }}"><i class="fa fa-user-o"></i><span>Roles</span></a></li>
<li><a data-link="privileges" href="{{ route('privilege.index') }}"><i class="fa li_user"></i><span>User Privileges</span></a></li>
<li><a data-link="user-log" href="{{ route('user_logs') }}"><i class="fa icon-basic-notebook"></i><span>User Log</span></a></li>
<li><a data-link="print" href="{{ route('print.index') }}"><i class="fa icon-software-layout-header-complex"></i><span>Printing Templates</span></a></li>
<li><a data-link="branches" href="{{ route('branches.index') }}"><i class="fa icon-arrows-hamburger1"></i><span>Branches</span></a></li>
<li><a data-link="subscription"  href="{{route('subscription')}}"><i class="fa icon-ecommerce-cart-check"></i><span>My Subscriptions</span></a></li>
<li><a data-link="plan"  href="{{route('plan')}}"><i class="fa icon-ecommerce-cart-check"></i><span>My Plan</span></a></li>
<li class="nav-item"><a data-link="voucher-format"  href="{{route('voucher_format.index')}}"><i class="fa fa-sitemap"></i><span>Voucher Format</span></a></li> 
<li><a><i class="fa fa-sitemap"></i><span>Preferred Payment Method</span></a></li>
<li><a><i class="fa fa-sitemap"></i><span>Terms</span></a></li>
@permission('voucher-list')
<li><a data-link="settings-voucher"  href="{{ route('settings_voucher.index') }}"><i class="fa fa-sitemap"></i><span>Voucher</span></a></li> 
@endpermission
@endif
@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('accounts', Session::get('organization_id')))
			
@endif
@endif
@stop

@section('dom_links')
@parent
<script>
    $(document).ready(function() {
    	$('.accounts').addClass('selected');
    });
</script>
@stop