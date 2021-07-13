@section('sidebar')
@parent
@if(Session::get('organization_id'))

@if (App\Organization::checkModuleExists('hrm', Session::get('organization_id')))

	<?php 
		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];		
	?>

	

		<li class="header"><span>Hrm</span></li>

		  <li><a data-link="dashboard" href="{{ route('hrm.dashboard') }}"><i class="fa icon-basic-accelerator"></i><span>Dashboard</span></a></li>

		  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Masters</span></a>

			<div class="sidebar-submenu">
				<ul>
					@permission('work-break-list')
						<li><a data-link="breaks" href="{{ route('breaks.index') }}" title="Labels &amp; Badges"><span>Break</span></a></li>
					@endpermission

					@permission('department-list')
						<li><a  data-link="departments" href="{{ route('hrm_departments.index') }}" title="Buttons" class="sfActive"><span>Department</span></a></li>
					@endpermission
					@permission('designation-list')
						<li><a data-link="designations" href="{{ route('designations.index') }}" title="Labels &amp; Badges"><span>Designation</span></a></li>
					@endpermission
					@permission('work-shift-list')
						<li><a data-link="shifts" href="{{ route('shifts.index') }}" title="Labels &amp; Badges"><span>Shift</span></a></li>
					@endpermission
					@permission('leave-types-list')
						<li><a data-link="leave-types" href="{{ route('leave_types.index') }}" title="Labels &amp; Badges"><span>Leave Types</span></a></li>
					@endpermission
					@permission('holiday-types-list')
						<li><a data-link="holiday-types" href="{{ route('holiday_types.index') }}" title="Labels &amp; Badges"><span>Holiday Types</span></a></li>
					@endpermission
					@permission('holidays-list')
						<li><a data-link="holidays" href="{{ route('holidays.index') }}" title="Labels &amp; Badges"><span>Holidays</span></a></li>
					@endpermission
					@permission('week-off-list')
						<li><a data-link="week-off" href="{{ route('weekoff.index') }}" title="Labels &amp; Badges"><span>Week-off</span></a></li>
					@endpermission
					@permission('attendance-type-list')
						<li><a data-link="attendance/types" href="{{ route('attendance_types.index') }}" title="Labels &amp; Badges"><span>Attendance Types</span></a></li>
					@endpermission
					@permission('person-types-list')
						<li><a data-link="person-types" href="{{ route('person_types.index') }}" title="Labels &amp; Badges"><span>Person Types</span></a></li>
					@endpermission
					@permission('payroll-frequency-list')
						<li><a data-link="payroll-frequency" href="{{ route('payroll_frequency.index') }}" title="Content boxes"><span>Payroll Frequency</span></a></li>
					@endpermission
					@permission('pay-head-list')
						<li><a data-link="pay-head" href="{{ route('pay_head.index') }}" title="Content boxes"><span>Pay Head</span></a></li>
					@endpermission
					@permission('salary-scale-list')
						<li><a data-link="salary-scale" href="{{ route('salary_scale.index') }}" title="Labels &amp; Badges"><span>Salary Scale</span></a></li>
					@endpermission

					@permission('hrm-appraisal-kpi-list')
					<li><a data-link="salary-scale" href="{{ route('appraisal_kpi.index') }}" title="Labels &amp; Badges"><span>Appraisal KPIs</span></a></li>
					@endpermission
				</ul>
			</div>
		</li>

	
	  
	  <li class="header"><span>Transactions</span></li>
	  <!-- <li><a><i class="fa icon-basic-bolt"></i><span>Recruitments</span></a></li> -->

		<li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Attendance</span></a>

		<div class="sidebar-submenu">
			<ul>
				@permission('attendance-list')
	  			<li><a href="{{ route('hrm_attendance.index') }}" title="Labels &amp; Badges"><span>Attendance</span></a></li>
	 			@endpermission

				@permission('leaves-list')
	  			<li><a data-link="leaves" href="{{ route('leaves.index') }}" title="Labels &amp; Badges"><span>Leave Request</span></a></li>
	  			@endpermission

	  			@permission('permissions-list')
	  			<li><a data-link="permissions" href="{{ route('permissions.index') }}" title="Labels &amp; Badges"><span>Permission Request</span></a></li>
	 			 @endpermission
				
				
			</ul>
		</div>

	  </li>

	  <li><a class="sub-menu"><i class="fa icon-basic-folder-multiple"></i><span>Recruit</span></a>
			<div class="sidebar-submenu">
				<ul>
					@permission('hrm-vacancy-list')
					 <li><a data-link="vacancy" href="{{ route('vacancy.index') }}" ><span>Vacancies</span></a></li>
					 @endpermission
					 @permission('hrm-candidate-list')
		   			<li><a data-link="candidate" href="{{ route('candidates.index') }}"><span>Candidates</span></a></li>
		   			@endpermission
		
				</ul>
			</div>
	  </li>
	  

	  @permission('employee-list')
	  	<li><a data-link="employees" href="{{ route('employees.index') }}"><i class="fa fa-users"></i><span>Employees</span></a></li>
	  @endpermission
	  
	  <!-- <li><a><i class="fa icon-basic-alarm"></i><span>OT Register</span></a></li> -->
	  @permission('team-list')
	  	<li><a data-link="teams" href="{{ route('team.index') }}"><i class="fa icon-basic-star"></i><span>Team</span></a></li>
	  @endpermission
	  <!-- <li><a data-link="roaster" href="{{ route('roaster.index') }}"><i class="fa icon-arrows-rotate"></i><span>Roaster</span></a></li> -->
	  @permission('ot-register-list')
	  	<li><a data-link="log-register" href="{{ route('log_registers.index') }}"><i class="fa icon-basic-todolist-pencil"></i><span>In-Out Register</span></a></li>
	  @endpermission
	  
	  @permission('employee-relieve-list')
	  	<li><a data-link="employee-relieve" href="{{ route('employee_relieve.index') }}"><i class="fa fa-sign-out"></i><span> Employee Relieve</span></a></li>
	  @endpermission
	  @permission('permissions-list')
	  	<li><a data-link="payroll" href="{{ route('payroll.index') }}"><i class="fa icon-ecommerce-banknote"></i><span> Payroll</span></a></li>
	  @endpermission	

	  @permission('hrm-document-list')
	  <li><a data-link="document" href="{{ route('documents.index') }}"><i class="fa fa-book"></i><span>Documents</span></a></li>	  
	  @endpermission

	@permission('hrm-appraisal-list')
		<li><a data-link="appraisals" href="{{ route('appraisals.index') }}"><i class="fa fa-book"></i><span>Appraisals</span></a></li>
	@endpermission

	  <!--  <li class="header"><span>Others</span></li>
	  	  <li><a class="sub-menu"><i class="fa icon-elaboration-document-graph"></i><span>Reports</span></a>
	  	  		<div class="sidebar-submenu">
	  	  		  <ul>
	  	  			<li><a data-link="balance-sheet"  href="{{ route('balance_sheet') }}">Leaves</a> </li>
	  	  			<li><a data-link="balance-sheet"  href="{{ route('balance_sheet') }}">Attendance</a> </li>
	  	  				<li><a data-link="profit-loss"  href="{{ route('profit_and_loss') }}">Timesheet</a></li>
	  	  		  	@permission('permissions-list')
	  	  			<li><a data-link="payroll-report" href="{{ route('payroll.index') }}" title="Buttons"><span>Payroll</span></a></li>
	  	  			@endpermission
	  	  		  </ul>
	  	  		</div>
	  	  </li> -->
	  <!-- <li><a class="sub-menu"><i class="fa icon-basic-gear"></i><span>Settings</span></a>
	  			<div class="sidebar-submenu">
	  			  <ul>
	  				<li><a  data-link="attendance/settings" href="{{ route('attendance_setting.index') }}" title="Buttons"><span>Attendance Settings</span></a></li>
	  			  </ul>
	  			</div>
	  	</li> -->	
@endif
@endif
@stop

@section('dom_links')
@parent
@stop

<?php
	Session::put('module_name', 'hrm');
?>