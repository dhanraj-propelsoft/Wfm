@section('sidebar')
@parent
<li class="header"><span>Settings</span></li>


	@permission('Notification-Folders-List')
	<li><a data-link="notifications" href="{{ route('notifications') }}"><i class="fa fa-list"></i><span> Notifications </span></a></li>
	@endpermission

	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Profile</span></a>
			<div class="sidebar-submenu">
				<ul>
				
				<li><a data-link="theme" href="{{ route('theme_settings') }}"><span>Theme Settings</span></a></li>
				
				<li><a data-link="theme" href="{{ route('person_profile.show', [Auth::user()->person_id]) }}"><span>Profile</span></a></li>	
				
				<li><a data-link="password/change" href="{{ route('change_password') }}"><span>Change Password</span></a></li>				

				</ul>
			</div>
	</li>
	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Company</span></a>
			<div class="sidebar-submenu">
				<ul>
				@if(Session::get('organization_id'))

				@permission('Company-Profile')
				<li><a data-link="business_profile" href="{{ route('business.show', [App\Organization::find(Session::get('organization_id'))->business_id]) }}"><span>Company Profile</span></a></li>
				@endpermission

				@endif
				<!-- <li><a data-link="password/change" href="{{ route('change_password') }}"><span>Change Password</span></a></li> -->
				@permission('Branches')
				<li><a data-link="branches" href="{{ route('branches.index') }}"><span>Branches</span></a></li>
				@endpermission

				</ul>
			</div>
	</li>

@if(Session::get('organization_id'))



	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Privileges</span></a>
			<div class="sidebar-submenu">
				<ul>

				<!-- @permission('Peoples')
				<li><a data-link="people" href="{{ route('all-people.index') }}" title="Labels &amp; Badges"><span> People </span></a></li>
				@endpermission -->
				
				<li><a data-link="roles" href="{{ route('roles.index') }}" title="Labels &amp; Badges"><span>Roles</span></a></li>
				

				@permission('User-Privileges')
				<li><a data-link="privileges" href="{{ route('privilege.index') }}" title="Labels &amp; Badges"><span>User Privileges</span></a></li>
				@endpermission

				@permission('User-Log')
				<li><a data-link="user-log" href="{{ route('user_logs') }}" title="Labels &amp; Badges"><span>User Log</span></a></li>
				@endpermission
				
				</ul>
			</div>
	</li>

	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Subscriptions</span></a>
			<div class="sidebar-submenu">
				<ul>
				@permission('Subscription-History')
				<li><a data-link="subscription"  href="{{route('subscription')}}"><span>Subscription History</span></a></li>
				@endpermission

				@permission('Addon-History')
				<li><a data-link="subscription"  href="{{route('addon_subscription')}}"><span>Addon History</span></a></li>
				@endpermission
				
				@permission('My-Plan')
				<li><a data-link="plan"  href="{{route('plan')}}"><span>My Plan</span></a></li>
				@endpermission

				@permission('Preferred-Payment-Method')
				<li><a><span>Preferred Payment Method</span></a></li>
				@endpermission
				<li><a><span>Terms</span></a></li>

				</ul>
			</div>
	</li>

	<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Voucher</span></a>
			<div class="sidebar-submenu">
				<ul>

				@permission('Print-Template')
				<li><a data-link="print" href="{{ route('print.index') }}"><span>Printing Templates</span></a></li>
				@endpermission

				@permission('Voucher-Format')
				<li class="nav-item"><a data-link="voucher-format"  href="{{route('voucher_format.index')}}"><span>Voucher Format</span></a></li> 
				@endpermission

				@permission('Voucher')
				<li><a data-link="settings-voucher" href="{{ route('settings_voucher.index') }}"><span>Voucher</span></a></li> 
				@endpermission
				
				</ul>
			</div>
	</li>

	@permission('Application-Support-Tickets-List')
	<li><a data-link="support_ticket" href="{{ route('support_ticket.index') }}"><i class="fa icon-basic-folder-multiple"></i><span>Support Ticket</span></a></li>
	@endpermission

	@permission('Application-Custom-Values-List')
	<li><a data-link="custom" href="{{ route('settings.custom_values') }}"><i class="fa icon-basic-folder-multiple"></i><span>Custom Values</span></a></li>
	@endpermission
	<li ><a data-link="more from propel" data-toggle="tooltip" data-placement="top"  title="More From Propel" href="{{ route('morefrom_propel') }}"><i class="fa fa-Example of info-circle fa-info-circle"></i><span>More from Propel</span></a></li>

	<li><a data-link="SMS Template" data-toggle="tooltip" data-placement="top"  title="SMS Template" href="{{ route('sms_template') }}"><i class="fa fa-envelope"></i><span>SMS Template</span></a></li>

	


@endif

@if(Session::get('organization_id'))
@if (App\Organization::checkModuleExists('books', Session::get('organization_id')))
			
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