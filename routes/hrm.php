<?php

// HRM MODULE STARTS  

		Route::group(['prefix' => 'hrm', 'middleware' => 'modules', 'modules' => 'hrm'], function () {

			Route::get('dashboard', ['as' => 'hrm.dashboard', 'uses' => 'Hrm\DashboardController@index']);

			Route::get('departments', ['as' => 'hrm_departments.index', 'uses' => 'Hrm\DepartmentController@index', 'middleware' => ['permission:department-list']]);

			Route::get('departments/create', ['as' => 'hrm_departments.create', 'uses' => 'Hrm\DepartmentController@create', 'middleware' => ['permission:department-create']]);

		    Route::post('departments', ['as' => 'hrm_departments.store', 'uses' => 'Hrm\DepartmentController@store', 'middleware' => ['permission:department-create']]);

		    Route::get('departments/{id}/edit', ['as' => 'hrm_departments.edit', 'uses' => 'Hrm\DepartmentController@edit', 'middleware' => ['permission:department-edit']]);

		    Route::patch('departments', ['as' => 'hrm_departments.update', 'uses' => 'Hrm\DepartmentController@update', 'middleware' => ['permission:department-edit']]);

		    Route::delete('departments/delete', ['as' => 'hrm_departments.destroy', 'uses' => 'Hrm\DepartmentController@destroy', 'middleware' => ['permission:department-delete']]);

		    Route::delete('departments/multidelete', ['as' => 'hrm_departments.multidestroy', 'uses' => 'Hrm\DepartmentController@multidestroy', 'middleware' => ['permission:department-delete']]);

		    Route::post('departments/multiapprove', ['as' => 'hrm_departments.multiapprove', 'uses' => 'Hrm\DepartmentController@multiapprove', 'middleware' => ['permission:department-edit']]);

		    Route::get('department_status_approval',['as'=>'department_status_approval','uses'=>'Hrm\DepartmentController@department_status_approval', 'middleware' => ['permission:department-edit']]);



		    Route::get('designations', ['as' => 'designations.index', 'uses' => 'Hrm\DesignationController@index', 'middleware' => ['permission:designation-list']]);

			Route::get('designations/create', ['as' => 'designations.create', 'uses' => 'Hrm\DesignationController@create', 'middleware' => ['permission:designation-create']]);

			Route::post('designations', ['as' => 'designations.store', 'uses' => 'Hrm\DesignationController@store', 'middleware' => ['permission:designation-create']]);

			Route::get('designations/{id}/edit', ['as' => 'designations.edit', 'uses' => 'Hrm\DesignationController@edit', 'middleware' => ['permission:department-edit']]);

			Route::patch('designations/update', ['as' => 'designations.update', 'uses' => 'Hrm\DesignationController@update', 'middleware' => ['permission:designation-edit']]);

			Route::delete('designations/delete', ['as' => 'designations.destroy', 'uses' => 'Hrm\DesignationController@destroy', 'middleware' => ['permission:designation-delete']]);

			Route::get('designations_status_approval',['as'=>'designations_status_approval','uses'=>'Hrm\DesignationController@designations_status_approval', 'middleware' => ['permission:designation-edit']]);

			Route::delete('designations/multidelete', ['as' => 'designations.multidestroy', 'uses' => 'Hrm\DesignationController@multidestroy', 'middleware' => ['permission:designation-delete']]);

			Route::post('designations/multiapprove', ['as'=>'designations.multiapprove', 'uses'=>'Hrm\DesignationController@multiapprove', 'middleware' => ['permission:designation-edit']]);

			Route::get('get_designation', ['as' => 'get_designation', 'uses' => 'Hrm\DesignationController@get_designation']);

			Route::get('get_employee', ['as' => 'get_employee', 'uses' => 'Hrm\DesignationController@get_employee']);

			Route::get('get_employee_by_department', ['as' => 'get_employee_by_department', 'uses' => 'Hrm\DepartmentController@get_employee']);


			Route::get('shifts', ['as' => 'shifts.index', 'uses' => 'Hrm\ShiftController@index', 'middleware' => ['permission:work-shift-list']]);

			Route::get('shifts/create', ['as' => 'shifts.create', 'uses' => 'Hrm\ShiftController@create', 'middleware' => ['permission:work-shift-create']]);

			Route::post('shifts', ['as' => 'shifts.store', 'uses' => 'Hrm\ShiftController@store', 'middleware' => ['permission:work-shift-create']]);

			Route::get('shifts/{id}/edit', ['as' => 'shifts.edit', 'uses' => 'Hrm\ShiftController@edit', 'middleware' => ['permission:work-shift-edit']]);

			Route::patch('shifts/update', ['as' => 'shifts.update', 'uses' => 'Hrm\ShiftController@update', 'middleware' => ['permission:work-shift-edit']]);

			Route::delete('shifts/delete', ['as' => 'shifts.destroy', 'uses' => 'Hrm\ShiftController@destroy', 'middleware' => ['permission:work-shift-delete']]);

			Route::delete('shifts/multidelete', ['as' => 'shifts.multidestroy', 'uses' => 'Hrm\ShiftController@multidestroy', 'middleware' => ['permission:work-shift-delete']]);

			Route::post('shifts/multiapprove', ['as' => 'shifts.multiapprove', 'uses' => 'Hrm\ShiftController@multiapprove', 'middleware' => ['permission:work-shift-edit']]);

			Route::get('shift_status_approval',['as'=>'shift_status_approval','uses'=>'Hrm\ShiftController@shift_status_approval', 'middleware' => ['permission:work-shift-edit']]);




			Route::get('person-types', ['as' => 'person_types.index', 'uses' => 'Hrm\PersonTypeController@index', 'middleware' => ['permission:person-types-list']]);

			Route::get('person-types/create', ['as' => 'person_types.create', 'uses' => 'Hrm\PersonTypeController@create', 'middleware' => ['permission:person-types-create']]);

			Route::post('person-types', ['as' => 'person_types.store', 'uses' => 'Hrm\PersonTypeController@store', 'middleware' => ['permission:person-types-create']]);

			Route::get('person-types/{id}/edit', ['as' => 'person_types.edit', 'uses' => 'Hrm\PersonTypeController@edit', 'middleware' => ['permission:person-types-edit']]);

			Route::patch('person-types/update', ['as' => 'person_types.update', 'uses' => 'Hrm\PersonTypeController@update', 'middleware' => ['permission:person-types-edit']]);

			Route::delete('person-types/delete', ['as' => 'person_types.destroy', 'uses' => 'Hrm\PersonTypeController@destroy', 'middleware' => ['permission:person-types-delete']]);

			Route::delete('person-types/multidelete', ['as' => 'person_types.multidestroy', 'uses' => 'Hrm\PersonTypeController@multidestroy', 'middleware' => ['permission:person-types-delete']]);

		    Route::post('person-types/multiapprove', ['as' => 'person_types.multiapprove', 'uses' => 'Hrm\PersonTypeController@multiapprove', 'middleware' => ['permission:person-types-edit']]);

			Route::get('person_type_status_approval',['as'=>'person_type_status_approval','uses'=>'Hrm\PersonTypeController@person_type_status_approval', 'middleware' => ['permission:person-types-edit']]);



			Route::get('breaks', ['as' => 'breaks.index', 'uses' => 'Hrm\BreakController@index','middleware' => ['permission:work-break-list']]);

			Route::get('breaks/create', ['as' => 'breaks.create', 'uses' => 'Hrm\BreakController@create','middleware' => ['permission:work-break-create']]);

			Route::post('breaks', ['as' => 'breaks.store', 'uses' => 'Hrm\BreakController@store','middleware' => ['permission:work-break-create']]);

			Route::get('breaks/{id}/edit', ['as' => 'breaks.edit', 'uses' => 'Hrm\BreakController@edit','middleware' => ['permission:work-break-edit']]);

			Route::patch('breaks/update', ['as' => 'breaks.update', 'uses' => 'Hrm\BreakController@update','middleware' => ['permission:work-break-edit']]);

			Route::delete('breaks/delete', ['as' => 'breaks.destroy', 'uses' => 'Hrm\BreakController@destroy','middleware' => ['permission:work-break-delete']]);

			Route::delete('breaks/multidelete', ['as' => 'breaks.multidestroy', 'uses' => 'Hrm\BreakController@multidestroy','middleware' => ['permission:work-break-delete']]);
			




			Route::get('branches', ['as' => 'branches.index', 'uses' => 'Hrm\BranchController@index', 'middleware' => ['permission:branches-list']]);

			Route::get('branches/create', ['as' => 'branches.create', 'uses' => 'Hrm\BranchController@create', 'middleware' => ['permission:branches-create']]);

			Route::post('branches', ['as' => 'branches.store', 'uses' => 'Hrm\BranchController@store', 'middleware' => ['permission:branches-create']]);

			Route::get('branches/{id}/edit', ['as' => 'branches.edit', 'uses' => 'Hrm\BranchController@edit', 'middleware' => ['permission:branches-edit']]);

			Route::patch('branches/update', ['as' => 'branches.update', 'uses' => 'Hrm\BranchController@update', 'middleware' => ['permission:branches-edit']]);

			Route::delete('branches/delete', ['as' => 'branches.destroy', 'uses' => 'Hrm\BranchController@destroy', 'middleware' => ['permission:branches-delete']]);

			Route::delete('branches/multidelete', ['as' => 'branches.multidestroy', 'uses' => 'Hrm\BranchController@multidestroy', 'middleware' => ['permission:branches-delete']]);

			Route::post('branches/multiapprove', ['as' => 'branches.multiapprove', 'uses' => 'Hrm\BranchController@multiapprove', 'middleware' => ['permission:branches-edit']]);




			

			Route::get('leave-types', ['as' => 'leave_types.index', 'uses' => 'Hrm\LeaveTypeController@index','middleware' => ['permission:leave-types-list']]);

			Route::get('leave-types/create', ['as' => 'leave_types.create', 'uses' => 'Hrm\LeaveTypeController@create','middleware' => ['permission:leave-types-create']]);
			
			Route::post('leave-types', ['as' => 'leave_types.store', 'uses' => 'Hrm\LeaveTypeController@store','middleware' => ['permission:leave-types-create']]);

			Route::get('leave-types/{id}/edit', ['as' => 'leave_types.edit', 'uses' => 'Hrm\LeaveTypeController@edit','middleware' => ['permission:leave-types-edit']]);

			Route::patch('leave-types/update', ['as' => 'leave_types.update', 'uses' => 'Hrm\LeaveTypeController@update','middleware' => ['permission:leave-types-edit']]);

			Route::delete('leave-types/delete', ['as' => 'leave_types.destroy', 'uses' => 'Hrm\LeaveTypeController@destroy','middleware' => ['permission:leave-types-delete']]);

			Route::delete('leave-types/multidelete', ['as' => 'leave_types.multidestroy', 'uses' => 'Hrm\LeaveTypeController@multidestroy','middleware' => ['permission:leave-types-delete']]);

			Route::post('leave-types/multiapprove', ['as' => 'leave_types.multiapprove', 'uses' => 'Hrm\LeaveTypeController@multiapprove','middleware' => ['permission:leave-types-edit']]);

			Route::get('leavetypes_status_approval', ['as' => 'leavetypes_status_approval', 'uses' => 'Hrm\LeaveTypeController@leavetypes_status_approval', 'middleware' => ['permission:branches-edit']]);
			


			Route::get('holiday-types', ['as' => 'holiday_types.index', 'uses' => 'Hrm\HolidayTypeController@index','middleware' => ['permission:holiday-types-list']]);

			Route::get('holiday-types/create', ['as' => 'holiday_types.create', 'uses' => 'Hrm\HolidayTypeController@create','middleware' => ['permission:holiday-types-create']]);
			
			Route::post('holiday-types', ['as' => 'holiday_types.store', 'uses' => 'Hrm\HolidayTypeController@store','middleware' => ['permission:holiday-types-create']]);

			Route::get('holiday-types/{id}/edit', ['as' => 'holiday_types.edit', 'uses' => 'Hrm\HolidayTypeController@edit','middleware' => ['permission:holiday-types-edit']]);

			Route::patch('holiday-types/update', ['as' => 'holiday_types.update', 'uses' => 'Hrm\HolidayTypeController@update','middleware' => ['permission:holiday-types-edit']]);

			Route::delete('holiday-types/delete', ['as' => 'holiday_types.destroy', 'uses' => 'Hrm\HolidayTypeController@destroy','middleware' => ['permission:holiday-types-delete']]);			

			Route::delete('holiday-types/multidelete', ['as' => 'holiday_types.multidestroy', 'uses' => 'Hrm\HolidayTypeController@multidestroy', 'middleware' => ['permission:holiday-types-delete']]);

			Route::post('holiday-types/multiapprove', ['as' => 'holiday_types.multiapprove', 'uses' => 'Hrm\HolidayTypeController@multiapprove', 'middleware' => ['permission:holiday-types-edit']]);

			Route::get('holidaytype_status_approval', ['as' => 'holidaytype_status_approval', 'uses' => 'Hrm\HolidayTypeController@holidaytype_status_approval', 'middleware' => ['permission:holiday-types-edit']]);



			Route::get('attendance/types', ['as' => 'attendance_types.index', 'uses' => 'Hrm\AttendanceTypeController@index', 'middleware' => ['permission:attendance-types-list']]);

			Route::get('attendance/types/create', ['as' => 'attendance_types.create', 'uses' => 'Hrm\AttendanceTypeController@create', 'middleware' => ['permission:attendance-types-create']]);

			Route::post('attendance/types', ['as' => 'attendance_types.store', 'uses' => 'Hrm\AttendanceTypeController@store', 'middleware' => ['permission:attendance-types-create']]);

			Route::get('attendance/types/{id}/edit', ['as' => 'attendance_types.edit', 'uses' => 'Hrm\AttendanceTypeController@edit', 'middleware' => ['permission:department-edit']]);

			Route::patch('attendance/types/update', ['as' => 'attendance_types.update', 'uses' => 'Hrm\AttendanceTypeController@update', 'middleware' => ['permission:attendance-types-edit']]);

			Route::delete('attendance/types/delete', ['as' => 'attendance_types.destroy', 'uses' => 'Hrm\AttendanceTypeController@destroy', 'middleware' => ['permission:attendance-types-delete']]);

			Route::get('attendance_types_status_approval',['as'=>'attendance_types_status_approval','uses'=>'Hrm\AttendanceTypeController@attendance_types_status_approval', 'middleware' => ['permission:attendance-types-edit']]);

			Route::delete('attendance/types/multidelete', ['as' => 'attendance_types.multidestroy', 'uses' => 'Hrm\AttendanceTypeController@multidestroy', 'middleware' => ['permission:attendance-types-delete']]);			

			Route::post('attendance/types/multiapprove', ['as'=>'attendance_types.multiapprove', 'uses'=>'Hrm\AttendanceTypeController@multiapprove', 'middleware' => ['permission:attendance-types-edit']]);



			Route::get('holidays', ['as' => 'holidays.index', 'uses' => 'Hrm\HolidayController@index','middleware' => ['permission:holidays-list']]);

			Route::get('holidays/create', ['as' => 'holidays.create', 'uses' => 'Hrm\HolidayController@create','middleware' => ['permission:holidays-create']]);

			Route::post('holidays', ['as' => 'holidays.store', 'uses' => 'Hrm\HolidayController@store', 'middleware' => ['permission:holidays-create']]);

			Route::get('holidays/{id}/edit', ['as' => 'holidays.edit', 'uses' => 'Hrm\HolidayController@edit', 'middleware' => ['permission:holidays-edit']]);

			Route::patch('holidays/update', ['as' => 'holidays.update', 'uses' => 'Hrm\HolidayController@update', 'middleware' => ['permission:holidays-edit']]);

			Route::delete('holidays/delete', ['as' => 'holidays.destroy', 'uses' => 'Hrm\HolidayController@destroy', 'middleware' => ['permission:holidays-delete']]);

			Route::delete('holidays/multidelete', ['as' => 'holidays.multidestroy', 'uses' => 'Hrm\HolidayController@multidestroy','middleware' => ['permission:holidays-delete']]);

			Route::post('holidays/multiapprove', ['as' => 'holidays.multiapprove', 'uses' => 'Hrm\HolidayController@multiapprove', 'middleware' => ['permission:holidays-edit']]);

			Route::get('holidays_status_approval', ['as' => 'holidays_status_approval', 'uses' => 'Hrm\HolidayController@holidays_status_approval', 'middleware' => ['permission:holidays-edit']]);




			Route::get('week-off', ['as' => 'weekoff.index', 'uses' => 'Hrm\WeekOffController@index', 'middleware' => ['permission:week-off-list']]);

			Route::get('week-off/create', ['as' => 'weekoff.create', 'uses' => 'Hrm\WeekOffController@create','middleware' => ['permission:week-off-create']]);

			Route::post('week-off', ['as' => 'weekoff.store', 'uses' => 'Hrm\WeekOffController@store', 'middleware' => ['permission:week-off-create']]);

			Route::get('week-off/{id}/edit', ['as' => 'weekoff.edit', 'uses' => 'Hrm\WeekOffController@edit', 'middleware' => ['permission:week-off-edit']]);

			Route::patch('week-off/update', ['as' => 'weekoff.update', 'uses' => 'Hrm\WeekOffController@update', 'middleware' => ['permission:week-off-edit']]);

			Route::delete('week-off/delete', ['as' => 'weekoff.destroy', 'uses' => 'Hrm\WeekOffController@destroy', 'middleware' => ['permission:week-off-delete']]);

			Route::delete('week-off/multidelete', ['as' => 'weekoff.multidestroy', 'uses' => 'Hrm\WeekOffController@multidestroy', 'middleware' => ['permission:week-off-delete']]);

			Route::post('week-off/multiapprove', ['as' => 'weekoff.multiapprove', 'uses' => 'Hrm\WeekOffController@multiapprove', 'middleware' => ['permission:week-off-edit']]);

			Route::get('weekoff_status_approval', ['as' => 'weekoff_status_approval', 'uses' => 'Hrm\WeekOffController@weekoff_status_approval', 'middleware' => ['permission:week-off-edit']]);



			Route::get('leaves', ['as' => 'leaves.index', 'uses' => 'Hrm\LeaveController@index']);

			Route::get('leaves/create', ['as' => 'leaves.create', 'uses' => 'Hrm\LeaveController@create']);

			Route::post('leaves', ['as' => 'leaves.store', 'uses' => 'Hrm\LeaveController@store']);

			Route::get('leaves/{id}/edit', ['as' => 'leaves.edit', 'uses' => 'Hrm\LeaveController@edit']);

			Route::patch('leaves/update', ['as' => 'leaves.update', 'uses' => 'Hrm\LeaveController@update']);

			Route::get('leaves_status_approval', ['as' => 'leaves_status_approval', 'uses' => 'Hrm\LeaveController@leaves_status_approval', 'middleware' => ['permission:leaves-edit']]);

			Route::delete('leaves/delete', ['as' => 'leaves.destroy', 'uses' => 'Hrm\LeaveController@destroy', 'middleware' => ['permission:leaves-delete']]);

			Route::delete('leaves/multidelete', ['as' => 'leaves.multidestroy', 'uses' => 'Hrm\LeaveController@multidestroy', 'middleware' => ['permission:leaves-delete']]);

			Route::post('leaves/multiapprove', ['as' => 'leaves.multiapprove', 'uses' => 'Hrm\LeaveController@multiapprove', 'middleware' => ['permission:leaves-edit']]);


			Route::get('permissions', ['as' => 'permissions.index', 'uses' => 'Hrm\PermissionController@index', 'middleware' => ['permission:permissions-list']]);

			Route::get('permissions/create', ['as' => 'permissions.create', 'uses' => 'Hrm\PermissionController@create', 'middleware' => ['permission:permissions-create']]);

			Route::post('permissions', ['as' => 'permissions.store', 'uses' => 'Hrm\PermissionController@store', 'middleware' => ['permission:permissions-create']]);

			Route::get('permissions/status', ['as'=>'permissions.status','uses'=>'Hrm\PermissionController@status', 'middleware' => ['permission:permission-approval']]);

			Route::get('permissions/{id}/edit', ['as' => 'permissions.edit', 'uses' => 'Hrm\PermissionController@edit', 'middleware' => ['permission:permissions-edit']]);

			Route::patch('permissions/update', ['as' => 'permissions.update', 'uses' => 'Hrm\PermissionController@update', 'middleware' => ['permission:permissions-edit']]);

			Route::delete('permissions/delete', ['as' => 'permissions.destroy', 'uses' => 'Hrm\PermissionController@destroy', 'middleware' => ['permission:permissions-delete']]);

			Route::delete('permissions/multidelete', ['as' => 'permissions.multidestroy', 'uses' => 'Hrm\PermissionController@multidestroy', 'middleware' => ['permission:permissions-delete']]);

		    Route::post('permissions/multiapprove', ['as' => 'permissions.multiapprove', 'uses' => 'Hrm\PermissionController@multiapprove', 'middleware' => ['permission:permissions-edit']]);


			Route::get('log-register', ['as' => 'log_registers.index', 'uses' => 'Hrm\LogRegisterController@index', 'middleware' => ['permission:ot-register-list']]);

			Route::get('log-register/create', ['as' => 'log_registers.create', 'uses' => 'Hrm\LogRegisterController@create', 'middleware' => ['permission:ot-register-create']]);

			Route::post('log-register', ['as' => 'log_registers.store', 'uses' => 'Hrm\LogRegisterController@store', 'middleware' => ['permission:ot-register-create']]);

			Route::get('log-register/{id}/edit', ['as' => 'log_registers.edit', 'uses' => 'Hrm\LogRegisterController@edit', 'middleware' => ['permission:ot-register-edit']]);

			Route::patch('log-register/update', ['as' => 'log_registers.update', 'uses' => 'Hrm\LogRegisterController@update', 'middleware' => ['permission:ot-register-edit']]);

			Route::delete('log-register/delete', ['as' => 'log_registers.destroy', 'uses' => 'Hrm\LogRegisterController@destroy', 'middleware' => ['permission:ot-register-delete']]);

			Route::delete('log-register/multidelete', ['as' => 'log_registers.multidestroy', 'uses' => 'Hrm\LogRegisterController@multidestroy', 'middleware' => ['permission:ot-register-delete']]);



			Route::get('payroll-frequency', ['as' => 'payroll_frequency.index', 'uses' => 'Hrm\PayrollFrequencyController@index','middleware' => ['permission:payroll-frequency-list']]);

			Route::get('payroll-frequency/create', ['as' => 'payroll_frequency.create', 'uses' => 'Hrm\PayrollFrequencyController@create','middleware' => ['permission:payroll-frequency-create']]);

			Route::post('payroll-frequency', ['as' => 'payroll_frequency.store', 'uses' => 'Hrm\PayrollFrequencyController@store','middleware' => ['permission:payroll-frequency-create']]);

			Route::get('payroll-frequency/{id}/edit', ['as' => 'payroll_frequency.edit', 'uses' => 'Hrm\PayrollFrequencyController@edit','middleware' => ['permission:payroll-frequency-edit']]);

			Route::patch('payroll-frequency/update', ['as' => 'payroll_frequency.update', 'uses' => 'Hrm\PayrollFrequencyController@update','middleware' => ['permission:payroll-frequency-edit']]);

			Route::delete('payroll-frequency/delete', ['as' => 'payroll_frequency.destroy', 'uses' => 'Hrm\PayrollFrequencyController@destroy','middleware' => ['permission:payroll-frequency-edit']]);

			Route::delete('payroll-frequency/multidelete', ['as' => 'payroll_frequency.multidestroy', 'uses' => 'Hrm\PayrollFrequencyController@multidestroy','middleware' => ['permission:payroll-frequency-delete']]);

			Route::post('payroll-frequency/multiapprove', ['as' => 'payroll_frequency.multiapprove', 'uses' => 'Hrm\PayrollFrequencyController@multiapprove','middleware' => ['permission:payroll-frequency-edit']]);

			Route::get('payrollfrequency_status_approval', ['as' => 'payrollfrequency_status_approval', 'uses' => 'Hrm\PayrollFrequencyController@payrollfrequency_status_approval','middleware' => ['permission:payroll-frequency-edit']]);



			Route::get('pay-head', ['as' => 'pay_head.index', 'uses' => 'Hrm\PayHeadController@index','middleware' => ['permission:pay-head-list']]);

			Route::get('pay-head/create', ['as' => 'pay_head.create', 'uses' => 'Hrm\PayHeadController@create', 'middleware' => ['permission:pay-head-create']]);

			Route::post('pay-head', ['as' => 'pay_head.store', 'uses' => 'Hrm\PayHeadController@store', 'middleware' => ['permission:pay-head-create']]);

			Route::get('pay-head/{id}/edit', ['as' => 'pay_head.edit', 'uses' => 'Hrm\PayHeadController@edit', 'middleware' => ['permission:pay-head-edit']]);

			Route::patch('pay-head', ['as' => 'pay_head.update', 'uses' => 'Hrm\PayHeadController@update','middleware' => ['permission:pay-head-edit']]);

			Route::delete('pay-head/delete', ['as' => 'pay_head.destroy', 'uses' => 'Hrm\PayHeadController@destroy', 'middleware' => ['permission:pay-head-delete']]);

			Route::delete('pay-head/multidelete', ['as' => 'pay_head.multidestroy', 'uses' => 'Hrm\PayHeadController@multidestroy','middleware' => ['permission:pay-head-delete']]);

			Route::post('pay-head/multiapprove', ['as' => 'pay_head.multiapprove', 'uses' => 'Hrm\PayHeadController@multiapprove', 'middleware' => ['permission:pay-head-edit']]);

			Route::get('payhead_status_approval', ['as' => 'payhead_status_approval', 'uses' => 'Hrm\PayHeadController@payhead_status_approval', 'middleware' => ['permission:pay-head-edit']]);





			Route::get('salary-scale', ['as' => 'salary_scale.index', 'uses' => 'Hrm\SalaryScaleController@index','middleware' => ['permission:salary-scale-list']]);

			Route::get('salary-scale/create', ['as' => 'salary_scale.create', 'uses' => 'Hrm\SalaryScaleController@create','middleware' => ['permission:salary-scale-create']]);

			Route::post('salary-scale', ['as' => 'salary_scale.store', 'uses' => 'Hrm\SalaryScaleController@store', 'middleware' => ['permission:salary-scale-create']]);

			Route::get('salary-scale/{id}/edit', ['as' => 'salary_scale.edit', 'uses' => 'Hrm\SalaryScaleController@edit', 'middleware' => ['permission:salary-scale-edit']]);

			Route::patch('salary-scale/update', ['as' => 'salary_scale.update', 'uses' => 'Hrm\SalaryScaleController@update', 'middleware' => ['permission:salary-scale-edit']]);

			Route::delete('salary-scale/delete', ['as' => 'salary_scale.destroy', 'uses' => 'Hrm\SalaryScaleController@destroy', 'middleware' => ['permission:salary-scale-delete']]);

			Route::delete('salary-scale/multidelete', ['as' => 'salary_scale.multidestroy', 'uses' => 'Hrm\SalaryScaleController@multidestroy', 'middleware' => ['permission:salary-scale-delete']]);

			Route::post('salary-scale/multiapprove', ['as' => 'salary_scale.multiapprove', 'uses' => 'Hrm\SalaryScaleController@multiapprove', 'middleware' => ['permission:salary-scale-edit']]);

			Route::get('salaryscale_status_approval', ['as' => 'salaryscale_status_approval', 'uses' => 'Hrm\SalaryScaleController@salaryscale_status_approval', 'middleware' => ['permission:salary-scale-edit']]);


			Route::get('payroll', ['as' => 'payroll.index', 'uses' => 'Hrm\PayrollController@index']);

			Route::get('generate_payroll', ['as' => 'generate_payroll', 'uses' => 'Hrm\PayrollController@payroll']);

			Route::post('payslip', ['as' => 'payslip', 'uses' => 'Hrm\PayrollController@payslip']);

			Route::post('get/salary-scale', ['as' => 'get_salary_scale', 'uses' => 'Hrm\PayrollController@get_salary_scale']);

			Route::post('get/employee-salary', ['as' => 'get_employee_salary', 'uses' => 'Hrm\PayrollController@get_employee_salary']);

			Route::post('employee-salary/multiapprove', ['as' => 'hrm_payroll.multiapprove', 'uses' => 'Hrm\PayrollController@multiapprove']);

			Route::post('employee-salary/multipayment', ['as' => 'hrm_payroll.multipayment', 'uses' => 'Hrm\PayrollController@multipayment']);

			Route::post('employee-salary/multidelete', ['as' => 'hrm_payroll.multidelete', 'uses' => 'Hrm\PayrollController@multidelete']);

		
			Route::get('attendance/settings', ['as' => 'attendance_setting.index', 'uses' => 'Hrm\AttendanceSettingController@index', 'middleware' => ['permission:attendance-setting-list']]);

			Route::get('attendance/settings/create', ['as' => 'attendance_setting.create', 'uses' => 'Hrm\AttendanceSettingController@create', 'middleware' => ['permission:attendance-setting-create']]);

			Route::post('attendance/settings/store', ['as' => 'attendance_setting.store', 'uses' => 'Hrm\AttendanceSettingController@store', 'middleware' => ['permission:attendance-setting-create']]);

			Route::get('attendance/settings/{id}/edit', ['as' => 'attendance_setting.edit', 'uses' => 'Hrm\AttendanceSettingController@edit', 'middleware' => ['permission:attendance-setting-edit']]);

			Route::patch('attendance/settings/update', ['as' => 'attendance_setting.update', 'uses' => 'Hrm\AttendanceSettingController@update', 'middleware' => ['permission:attendance-setting-edit']]);

			Route::delete('attendance/settings/delete', ['as' => 'attendance_setting.destroy', 'uses' => 'Hrm\AttendanceSettingController@destroy', 'middleware' => ['permission:attendance-setting-delete']]);
			



			Route::post('check_employee', ['as' => 'check_employee', 'uses' => 'Hrm\EmployeeController@check_employee']);

			Route::get('employees', ['as' => 'employees.index', 'uses' => 'Hrm\EmployeeController@index', 'middleware' => ['permission:employee-list']]);

	 		Route::get('employees/create', ['as' => 'employees.create', 'uses' => 'Hrm\EmployeeController@create', 'middleware' => ['permission:employee-create']]);

	 		Route::post('employees', ['as' => 'employees.store', 'uses' => 'Hrm\EmployeeController@store']);

	 		Route::get('employees/{id}', ['as' => 'employees.show', 'uses' => 'Hrm\EmployeeController@show']);

	 		Route::patch('employees/official_info_update', ['as' => 'employees.official_info_update', 'uses' => 'Hrm\EmployeeController@official_info_update']);

	 		Route::patch('employees/personal_info_update', ['as' => 'employees.personal_info_update', 'uses' => 'Hrm\EmployeeController@personal_info_update']);

	 		Route::patch('employees/contact_info_update', ['as' => 'employees.contact_info_update', 'uses' => 'Hrm\EmployeeController@contact_info_update']);

	 		Route::patch('employees/education_info_update', ['as' => 'employees.education_info_update', 'uses' => 'Hrm\EmployeeController@education_info_update']);

	 		Route::patch('employees/employee_skills_update', ['as' => 'employees.employee_skills_update', 'uses' => 'Hrm\EmployeeController@employee_skills_update']);

	 		Route::patch('employees/employee_experience_update', ['as' => 'employees.employee_experience_update', 'uses' => 'Hrm\EmployeeController@employee_experience_update']);

	 		Route::patch('employees/salary_info_update', ['as' => 'employees.salary_info_update', 'uses' => 'Hrm\EmployeeController@salary_info_update']);

	 		Route::patch('employees/bank_info_update', ['as' => 'employees.bank_info_update', 'uses' => 'Hrm\EmployeeController@bank_info_update']);

	 		Route::post('employees/image-upload', 'Hrm\EmployeeController@employee_image_upload')->name('employee_image_upload');

	 		Route::post('employees/file-upload', 'Hrm\EmployeeController@employee_file_upload')->name('employee_file_upload');

	 		Route::delete('employees/experience_delete', ['as' => 'employees.experience_delete', 'uses' => 'Hrm\EmployeeController@experience_delete']);

	 		Route::delete('employees/education_delete', ['as' => 'employees.education_delete', 'uses' => 'Hrm\EmployeeController@education_delete']);

	 		Route::delete('employees/skill_delete', ['as' => 'employees.skill_delete', 'uses' => 'Hrm\EmployeeController@skill_delete']);

	 		Route::post('employees/salary_scale', ['as' => 'employees.salary_scale', 'uses' => 'Hrm\EmployeeController@salary_scale']);

	 		Route::get('ifsc_search', ['as'=>'ifsc_search', 'uses'=>'Hrm\EmployeeController@ifsc_search']);



	 		Route::get('team', ['as' => 'team.index', 'uses' => 'Hrm\TeamController@index', 'middleware' => ['permission:team-list']]);

			Route::get('team/create', ['as' => 'team.create', 'uses' => 'Hrm\TeamController@create', 'middleware' => ['permission:team-create']]);

			Route::post('team', ['as' => 'team.store', 'uses' => 'Hrm\TeamController@store', 'middleware' => ['permission:team-create']]);

			Route::post('team/member/get', ['as' => 'team_member_get', 'uses' => 'Hrm\TeamController@team_get', 'middleware' => ['permission:team-create']]);

			Route::get('team/{id}/edit', ['as' => 'team.edit', 'uses' => 'Hrm\TeamController@edit', 'middleware' => ['permission:team-edit']]);

			Route::patch('team', ['as' => 'team.update', 'uses' => 'Hrm\TeamController@update', 'middleware' => ['permission:team-edit']]);

			Route::delete('team', ['as' => 'team.destroy', 'uses' => 'Hrm\TeamController@destroy', 'middleware' => ['permission:team-delete']]);

			Route::delete('team/multidelete', ['as' => 'team.multidestroy', 'uses' => 'Hrm\TeamController@multidestroy', 'middleware' => ['permission:team-delete']]);

			Route::get('team_status_approval', ['as'=>'team_status_approval', 'uses'=>'Hrm\TeamController@team_status_approval', 'middleware' => ['permission:team-edit']]);

			Route::post('team/multiapprove', ['as'=>'team.multiapprove', 'uses'=>'Hrm\TeamController@multiapprove', 'middleware' => ['permission:team-edit']]);

			Route::get('roaster', ['as' => 'roaster.index', 'uses' => 'Hrm\RoasterController@index', 'middleware' => ['permission:branches-list']]);

			Route::get('roaster/create', ['as' => 'roaster.create', 'uses' => 'Hrm\RoasterController@create', 'middleware' => ['permission:branches-create']]);

			Route::post('roaster', ['as' => 'roaster.store', 'uses' => 'Hrm\RoasterController@store', 'middleware' => ['permission:branches-create']]);

			Route::get('roaster/{id}/edit', ['as' => 'roaster.edit', 'uses' => 'Hrm\RoasterController@edit', 'middleware' => ['permission:branches-edit']]);

			Route::patch('roaster/{id}', ['as' => 'roaster.update', 'uses' => 'Hrm\RoasterController@update', 'middleware' => ['permission:branches-edit']]);

			Route::get('roaster/{id}', ['as' => 'roaster.show', 'uses' => 'Hrm\RoasterController@show', 'middleware' => ['permission:branches-list']]);

			Route::delete('roaster', ['as' => 'roaster.destroy', 'uses' => 'Hrm\RoasterController@destroy', 'middleware' => ['permission:branches-delete']]);

			Route::post('roaster/allocation', ['as' => 'roaster_allocation', 'uses' => 'Hrm\RoasterController@roaster_allocation', 'middleware' => ['permission:branches-create']]);


			
		    Route::get('employee-relieve', ['as' => 'employee_relieve.index', 'uses' => 'Hrm\EmployeeRelieveController@index', 'middleware' => ['permission:employee-relieve-list']]);

			Route::get('employee-relieve/create', ['as' => 'employee_relieve.create', 'uses' => 'Hrm\EmployeeRelieveController@create', 'middleware' => ['permission:employee-relieve-create']]);

			Route::post('employee-relieve/store', ['as' => 'employee_relieve.store', 'uses' => 'Hrm\EmployeeRelieveController@store', 'middleware' => ['permission:employee-relieve-create']]);

			Route::get('employee-relieve/{id}/edit', ['as' => 'employee_relieve.edit', 'uses' => 'Hrm\EmployeeRelieveController@edit', 'middleware' => ['permission:employee-relieve-edit']]);

			Route::patch('employee-relieve/update', ['as' => 'employee_relieve.update', 'uses' => 'Hrm\EmployeeRelieveController@update', 'middleware' => ['permission:employee-relieve-edit']]);

			Route::delete('employee-relieve/delete', ['as' => 'employee_relieve.destroy', 'uses' => 'Hrm\EmployeeRelieveController@destroy', 'middleware' => ['permission:employee-relieve-delete']]);

Route::get('attendance', ['as' => 'hrm_attendance.index', 'uses' => 'Hrm\AttendanceController@index', 'middleware' => ['permission:attendance-list']]);
			//not in live 
			// new attendance route
			Route::get('new/attendance', ['as' => 'hrm_attendance_new.index', 'uses' => 'Hrm\AttendanceController@index_new', 'middleware' => ['permission:attendance-list']]);

			Route::get('attendance/create/{date}', ['as' => 'hrm_attendance.create', 'uses' => 'Hrm\AttendanceController@create', 'middleware' => ['permission:attendance-create']]);

			Route::post('attendance/create', ['as' => 'hrm_attendance.store', 'uses' => 'Hrm\AttendanceController@store', 'middleware' => ['permission:attendance-create']]);

		   	Route::delete('attendance/delete', ['as' => 'hrm_attendance.destroy', 'uses' => 'Hrm\AttendanceController@destroy', 'middleware' => ['permission:attendance-delete']]);

		   	Route::post('get/attendance/details', ['as' => 'get_attendance_details', 'uses' => 'Hrm\AttendanceController@get_attendance_details']);

		   	//not in live
		   	//new attendance in hrm
		   	Route::post('get/attendance/details/new', ['as' => 'get_attendance_details_new', 'uses' => 'Hrm\AttendanceController@get_attendance_details_new']);

		   	Route::post('attendance_update',['as'=>'attendance_update','uses'=>'Hrm\AttendanceController@attendance_update', 'middleware' => ['permission:attendance-edit']]);
		   	//not in live
		   	// new attendace update
		   	Route::post('attendance_update_new',['as'=>'attendance_update_new','uses'=>'Hrm\AttendanceController@attendance_update_new', 'middleware' => ['permission:attendance-edit']]);

		   	Route::delete('attendance/multidelete', ['as' => 'attendance.multidestroy', 'uses' => 'Hrm\AttendanceController@multidestroy', 'middleware' => ['permission:attendance-delete']]);

			Route::post('attendance/multitime', ['as'=>'attendance.multitime', 'uses'=>'Hrm\AttendanceController@multitime', 'middleware' => ['permission:attendance-edit']]);

			/*route start for hrm_vacancies*/

			Route::get('vacancy', ['as' => 'vacancy.index', 'uses' => 'Hrm\VacancyController@index']);

			Route::get('vacancy/get_positions', ['as' => 'get_positions', 'uses' => 'Hrm\VacancyController@get_positions']);

			Route::get('vacancy/create', ['as' => 'vacancy.create', 'uses' => 'Hrm\VacancyController@create']);

			Route::post('vacancy/store', ['as' => 'vacancy.store', 'uses' => 'Hrm\VacancyController@store']);

			Route::get('vacancy/{id}/edit', ['as' => 'vacancy.edit', 'uses' => 'Hrm\VacancyController@edit']);

			
			Route::patch('vacancy/update', ['as' => 'vacancy.update', 'uses' => 'Hrm\VacancyController@update']);

			
			Route::post('vacancy/status',['as'=>'vacancy.status','uses'=>'Hrm\VacancyController@vacancy_status']);

			Route::delete('vacancy/delete', ['as' => 'vacancy.destroy', 'uses' => 'Hrm\VacancyController@destroy']);

  			Route::get('vacancy_status_search',['as'=>'vacancy_status_search','uses'=>'Hrm\VacancyController@vacancy_status_search']);
			

			/*route end for hrm_vacancies*/

			/*route start for hrm_candidates*/

			Route::get('candidate', ['as' => 'candidates.index', 'uses' => 'Hrm\CandidateController@index']);
			
			Route::get('candidate/create', ['as' => 'candidate.create', 'uses' => 'Hrm\CandidateController@create']);

			Route::post('candidate/store', ['as' => 'candidate.store', 'uses' => 'Hrm\CandidateController@store']);

			Route::post('candidate/status',['as'=>'candidate.status','uses'=>'Hrm\CandidateController@candidate_status']);

			Route::get('candidate/get_recruitment_status',['as'=>'get_recruitment_status','uses'=>'Hrm\CandidateController@get_recruitment_status']);

			Route::get('candidate/{id}/edit', ['as' => 'candidate.edit', 'uses' => 'Hrm\CandidateController@edit']);

			Route::patch('candidate/update', ['as' => 'candidate.update', 'uses' => 'Hrm\CandidateController@update']);

			Route::delete('candidate/delete', ['as' => 'candidate.destroy', 'uses' => 'Hrm\CandidateController@destroy']);
		
			/*route end for hrm_candidates*/

			/*route start for hrm_documents*/

			Route::get('document', ['as' => 'documents.index', 'uses' => 'Hrm\DocumentController@index']);

			Route::get('document/create', ['as' => 'document.create', 'uses' => 'Hrm\DocumentController@create']);

			Route::post('document/store', ['as' => 'document.store', 'uses' => 'Hrm\DocumentController@store']);

			Route::get('document/{id}/edit', ['as' => 'document.edit', 'uses' => 'Hrm\DocumentController@edit']);
			
			Route::patch('document/update', ['as' => 'document.update', 'uses' => 'Hrm\DocumentController@update']);

			Route::post('document/status',['as'=>'document.status','uses'=>'Hrm\DocumentController@document_status']);

			Route::delete('document/delete', ['as' => 'document.destroy', 'uses' => 'Hrm\DocumentController@destroy']);
			
			Route::get('document_type_search',['as'=>'document_type_search','uses'=>'Hrm\DocumentController@document_type_search']);
	
			/*route end for hrm_documents*/

			/*route start for hrm_appraisal_kpi*/


			Route::get('appraisal', ['as' => 'appraisal_kpi.index', 'uses' => 'Hrm\AppraisalKpiController@index']);

			Route::get('appraisal/create', ['as' => 'appraisal_kpi.create', 'uses' => 'Hrm\AppraisalKpiController@create']);

			Route::post('appraisal/check', ['as' => 'appraisal_weight_check', 'uses' => 'Hrm\AppraisalKpiController@appraisal_weight_check']);

			Route::post('appraisal/store', ['as' => 'appraisal_kpi.store', 'uses' => 'Hrm\AppraisalKpiController@store']);

			Route::get('appraisal/edit', ['as' => 'appraisal_kpi.edit', 'uses' => 'Hrm\AppraisalKpiController@edit']);

			/*route end for hrm_appraisal_kpi*/

			/*route start for hrm_appraisal*/

			Route::get('appraisals', ['as' => 'appraisals.index', 'uses' => 'Hrm\HrmAppraisalController@index']);
			
			Route::get('appraisals/create', ['as' => 'appraisal_initiate.create', 'uses' => 'Hrm\HrmAppraisalController@create']);
			
			Route::post('appraisals/store', ['as' => 'appraisal.store', 'uses' => 'Hrm\HrmAppraisalController@store']);
			
			Route::post('appraisals/status',['as'=>'appraisal.status','uses'=>'Hrm\HrmAppraisalController@appraisal_status']);
			 
			Route::get('appraisals/random/create', ['as' => 'appraisal_random.create', 'uses' => 'Hrm\HrmAppraisalController@appraisal_random']);
			Route::post('hrm_salary_status',['as'=>'hrm_salary_status','uses'=>'Hrm\PayrollController@hrm_salary_status']);

			/*Route::post('appraisals/initiate/store', ['as' => 'appraisal_initiate.store', 'uses' => 'Hrm\AppraisalKpiController@initiate_store']);*/

			/*route end for hrm_appraisal*/


			Route::get('attendance_report_view', ['as' => 'attendance_report_view', 'uses' => 'Hrm\AttendanceController@attendance_report_view']);

			Route::post('get_attedance_report', ['as' => 'get_attedance_report', 'uses' => 'Hrm\AttendanceController@get_attedance_report']);

			 Route::get('attedance_details_view/{id}/{month}/{year}/view', ['as' => 'attendance_reports.view',
			 	'uses'=>'Hrm\AttendanceController@attedance_details_view']);

			 Route::get('get_present_details', ['as' => 'get_present_details', 'uses' => 'Hrm\AttendanceController@get_present_details', 'middleware' => ['permission:department-list']]);





		});