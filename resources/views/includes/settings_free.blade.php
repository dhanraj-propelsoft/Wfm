



	@if($plan_name == 'Free14Days')



<li class="header"><span>Settings</span></li>



<li><a data-link="notifications" href="{{ route('notifications') }}"><i class="fa fa-list"></i><span> Notifications </span></a></li>

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

			<li><a data-link="business_profile" href="{{ route('business_profile.show', [App\Organization::find(Session::get('organization_id'))->business_id]) }}"><span>Company Profile</span></a></li>

			@endif

			<!-- <li><a data-link="password/change" href="{{ route('change_password') }}"><span>Change Password</span></a></li> -->

			<li><a data-link="branches" href="{{ route('branches.index') }}"><span>Branches</span></a></li>



			</ul>

		</div>

</li>



@if(Session::get('organization_id'))



<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Privileges</span></a>

		<div class="sidebar-submenu">

			<ul>

			<!-- <li><a data-link="people" href="{{ route('people.index') }}" title="Labels &amp; Badges"><span> People </span></a></li> -->

			<li><a data-link="roles" href="{{ route('roles.index') }}" title="Labels &amp; Badges"><span>Roles</span></a></li>

			<li><a data-link="privileges" href="{{ route('privilege.index') }}" title="Labels &amp; Badges"><span>User Privileges</span></a></li>

			<li><a data-link="user-log" href="{{ route('user_logs') }}" title="Labels &amp; Badges"><span>User Log</span></a></li>

			</ul>

		</div>

</li>



<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Subscriptions</span></a>

		<div class="sidebar-submenu">

			<ul>

			<li><a data-link="subscription"  href="{{route('subscription')}}"><span>My Subscriptions</span></a></li>

			<li><a data-link="plan"  href="{{route('plan')}}"><span>My Plan</span></a></li>

			<li><a><span>Preferred Payment Method</span></a></li>

			<li><a><span>Terms</span></a></li>

			</ul>

		</div>

</li>



<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Voucher</span></a>

		<div class="sidebar-submenu">

			<ul>

			<li><a data-link="print" href="{{ route('print.index') }}"><span>Printing Templates</span></a></li>

			<li class="nav-item"><a data-link="voucher-format"  href="{{route('voucher_format.index')}}"><span>Voucher Format</span></a></li> 

			@permission('voucher-list')

			<li><a data-link="settings-voucher"  href="{{ route('settings_voucher.index') }}"><span>Voucher</span></a></li> 

			@endpermission

			

			</ul>

		</div>

</li>

<li><a data-link="support_ticket" href="{{ route('support_ticket.index') }}"><i class="fa icon-basic-folder-multiple"></i><span>Support Ticket</span></a></li>







@endif

@endif

