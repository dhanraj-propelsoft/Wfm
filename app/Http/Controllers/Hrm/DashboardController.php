<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmDesignation;
use App\HrmPermission;
use App\HrmLeaveType;
use App\HrmEmployee;
use Carbon\Carbon;
use App\HrmLeave;
use App\HrmTeam;
use Response;
use Session;
use DB;

class DashboardController extends Controller
{
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$notifications = [];
		$time = [];

		$designations = HrmDesignation::select('hrm_designations.*', 'hrm_departments.name AS department', DB::raw('COUNT(hrm_employee_designation.employee_id) as filledposition'))
		->leftjoin('hrm_departments', 'hrm_departments.id', '=', 'hrm_designations.department_id')
		->leftjoin('hrm_employee_designation', 'hrm_designations.id', '=', 'hrm_employee_designation.designation_id')
		->where('hrm_designations.organization_id', $organization_id)
		->where('hrm_designations.status', 1)
		->groupby('hrm_designations.id')
		->orderby('hrm_designations.name')
		->get();

		$designations_chart = HrmDesignation::select(
			DB::raw('COUNT(hrm_designations.id) AS designations'), 
			DB::raw('COUNT(hrm_designations.department_id) AS departments'),
			DB::raw('SUM(hrm_designations.positions) AS openings'),			
			DB::raw('COUNT(hrm_employee_designation.employee_id) as filledposition'))
		->leftjoin('hrm_departments', 'hrm_departments.id', '=', 'hrm_designations.department_id')
		->leftjoin('hrm_employee_designation', 'hrm_designations.id', '=', 'hrm_employee_designation.designation_id')
		->where('hrm_designations.organization_id', $organization_id)
		->where('hrm_designations.status', 1)

		->orderby('hrm_designations.name')
		->first();


		$teams = HrmTeam::select('hrm_teams.id','hrm_teams.name as team_name',DB::raw('COUNT(hrm_employee_team.employee_id) AS team_employees'))
		->leftjoin('hrm_employee_team','hrm_teams.id','=','hrm_employee_team.team_id')
		->where('hrm_teams.organization_id',$organization_id)
		->groupby('hrm_teams.id')
		->where('hrm_teams.status', 1)->get();

		$team_data = [];

		foreach ($teams as  $value) {
			$team_data[] = ["label" => $value->team_name, "data" => $value->team_employees];
		}

		$teams = json_encode($team_data);

		$start = new Carbon('first day of this month');
		$last = new Carbon('last day of this month');

		$leaves = HrmLeave::select('hrm_leaves.id',DB::raw('SUM(hrm_leaves.leave_days) AS leave_days'),'hrm_leaves.employee_id','hrm_leaves.leave_type_id','hrm_employees.first_name','hrm_leave_types.display_name as leave_type_name')
		->leftjoin('hrm_employees','hrm_leaves.employee_id','=','hrm_employees.id')
		->leftjoin('hrm_leave_types','hrm_leaves.leave_type_id','=','hrm_leave_types.id')		
		->where('hrm_leaves.organization_id',$organization_id)
		->where('hrm_leaves.approval_status', 1)
		->whereBetween('hrm_leaves.from_date', array($start->format('Y-m-d'), $last->format('Y-m-d')))
		->whereBetween('hrm_leaves.to_date', array($start->format('Y-m-d'), $last->format('Y-m-d')))
		->groupby('hrm_leaves.employee_id')
		->orderby('hrm_employees.first_name')->take(10)->get();

		$leave_data_array = [];
		$employee_data_array = [];

		foreach ($leaves as $key => $value) {
		   $leave_data_array[] = [$key, $value->leave_days];
		   $employee_data_array[] = [$key, $value->first_name];
		}

		$employees_leave_data = json_encode($leave_data_array);
		$employees_data 	 = json_encode($employee_data_array);


		$employees_leaves = HrmLeave::select('hrm_leaves.id', 'hrm_leaves.leave_days', 'hrm_employees.first_name AS employee','hrm_leave_types.display_name as leave_type_name')
		->leftjoin('hrm_employees','hrm_leaves.employee_id','=','hrm_employees.id')
		->leftjoin('hrm_leave_types','hrm_leaves.leave_type_id','=','hrm_leave_types.id')		
		->where('hrm_leaves.organization_id',$organization_id)
		->where('hrm_leaves.approval_status', 0)
		->get();

		foreach ($employees_leaves as $employees_leave) {
			$notifications[] = ["id" => $employees_leave->id, "type" => "hrm", "category" => "leaves", "message" => $employees_leave->employee. " applied for ".$employees_leave->leave_type_name, "time" => Carbon::parse($employees_leave->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($employees_leave->updated_at)->format('Y-m-d H:m:s')];
		}

		$employees_permissions = HrmPermission::select('hrm_permissions.id', 'hrm_employees.first_name AS employee')
		->leftjoin('hrm_employees','hrm_permissions.employee_id','=','hrm_employees.id')	
		->where('hrm_permissions.organization_id',$organization_id)
		->where('hrm_permissions.approval_status', 0)
		->get();

		foreach ($employees_permissions as $employees_permission) {
			$notifications[] = ["id" => $employees_permission->id, "type" => "hrm", "category" => "permissions", "message" => $employees_permission->employee. " applied for permission", "time" => Carbon::parse($employees_permission->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($employees_permission->updated_at)->format('Y-m-d H:m:s')];
		}

		return view('hrm.dashboard', compact('designations', 'leaves', 'employees_leave_data', 'employees_data', 'designations_chart', 'teams', 'notifications'));
	}
}
