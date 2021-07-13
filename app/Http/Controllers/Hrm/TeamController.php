<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployee;
use App\HrmTeam;
use App\Custom;
use Session;
use DB;

class TeamController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$teams = HrmTeam::select('hrm_teams.id', 'hrm_teams.name', 'hrm_teams.description', DB::raw('GROUP_CONCAT(hrm_employees.id) AS employee_id'), DB::raw('GROUP_CONCAT(COALESCE(hrm_employees.first_name, "")) AS first_name'), DB::raw('GROUP_CONCAT(COALESCE(hrm_employees.last_name, "")) AS last_name'), DB::raw('GROUP_CONCAT(COALESCE(hrm_employees.employee_code, "")) AS employee_code'), DB::raw('GROUP_CONCAT(COALESCE(hrm_designations.name, "")) AS designation'), DB::raw('GROUP_CONCAT(COALESCE(hrm_departments.name, "")) AS department'), 'hrm_teams.status')
		->leftjoin('hrm_employee_team', 'hrm_employee_team.team_id', '=', 'hrm_teams.id')
		->leftjoin('hrm_employees', 'hrm_employee_team.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_designations', 'hrm_employee_designation.designation_id', '=', 'hrm_designations.id')
		->leftjoin('hrm_departments', 'hrm_designations.department_id', '=', 'hrm_departments.id')
		->where('hrm_teams.organization_id', $organization_id)->groupby('hrm_teams.id')->get();

		return view('hrm.team', compact('teams'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$employees = HrmEmployee::select('hrm_employees.id', 'hrm_employees.first_name', 'hrm_employees.last_name', 'hrm_employees.employee_code', 'hrm_designations.name AS designation', 'hrm_departments.name AS department')
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_designations', 'hrm_employee_designation.designation_id', '=', 'hrm_designations.id')
		->leftjoin('hrm_departments', 'hrm_designations.department_id', '=', 'hrm_departments.id')
		->where('hrm_employees.organization_id', $organization_id)
		->whereNotNull('hrm_designations.id')
		->get();

		return view('hrm.team_create', compact('employees'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'employees' => 'required'
		]);

		$organization_id = Session::get('organization_id');
		$employees = $request->input('employees');

		$team = new HrmTeam;
		$team->name = $request->input('name');
		$team->description = $request->input('description');
		$team->organization_id = $organization_id;
		$team->save();
		Custom::userby($team, true);

		$team_members = [];

		if($team->id) {
			if(!empty(array_filter($employees))) {
				foreach ($employees as $employee) {
					DB::table('hrm_employee_team')->insert(['employee_id' => $employee, 'team_id' => $team->id]);
				} 

				$team_members = DB::table('hrm_employee_team')->select('hrm_employees.id', DB::raw('COALESCE(hrm_employees.first_name, "") AS first_name'), DB::raw('COALESCE(hrm_employees.last_name, "") AS last_name'), DB::raw('COALESCE(hrm_employees.employee_code, "") AS employee_code'))
		->leftjoin('hrm_teams', 'hrm_employee_team.team_id', '=', 'hrm_teams.id')
		->leftjoin('hrm_employees', 'hrm_employee_team.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->where('hrm_teams.organization_id', $organization_id)
		->where('hrm_teams.id', $team->id)->get();
			}
		}

		Custom::add_addon('records');
		return response()->json(['status' => 1, 'message' => 'Team'.config('constants.flash.added'), 'data' => ['id' => $team->id, 'name' => $team->name, 'team_members' => $team_members, 'description' => $team->description ,'status' => $team->status ]]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$organization_id = Session::get('organization_id');

		$team = HrmTeam::findOrFail($id);

		$selected_employees = DB::table('hrm_employee_team')->select('hrm_employee_team.employee_id')->where('team_id', $id)->get();

		$selected = array();

		foreach ($selected_employees as $value) {
			$selected[] = $value->employee_id;
		}

		$employees = HrmEmployee::select('hrm_employees.id', 'hrm_employees.first_name', 'hrm_employees.last_name', 'hrm_employees.employee_code', 'hrm_designations.name AS designation', 'hrm_departments.name AS department')
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_designations', 'hrm_employee_designation.designation_id', '=', 'hrm_designations.id')
		->leftjoin('hrm_departments', 'hrm_designations.department_id', '=', 'hrm_departments.id')
		->where('hrm_employees.organization_id', $organization_id)
		->whereNotNull('hrm_designations.id')
		->get();
		return view('hrm.team_edit', compact('team', 'employees', 'selected'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'employees' => 'required'
		]);

		$organization_id = Session::get('organization_id');
		$employees = $request->input('employees');

		$team = HrmTeam::findOrFail($request->input('id'));
		$team->name = $request->input('name');
		$team->description = $request->input('description');
		$team->save();
		Custom::userby($team, false);

		DB::table('hrm_employee_team')->where('team_id', $team->id)->delete();

		$team_members = [];

		if($team->id) {
			if(!empty(array_filter($employees))) {
				foreach ($employees as $employee) {
					DB::table('hrm_employee_team')->insert(['employee_id' => $employee, 'team_id' => $team->id]);
				} 

				$team_members = DB::table('hrm_employee_team')->select('hrm_employees.id', DB::raw('COALESCE(hrm_employees.first_name, "") AS first_name'), DB::raw('COALESCE(hrm_employees.last_name, "") AS last_name'), DB::raw('COALESCE(hrm_employees.employee_code, "") AS employee_code'))
		->leftjoin('hrm_teams', 'hrm_employee_team.team_id', '=', 'hrm_teams.id')
		->leftjoin('hrm_employees', 'hrm_employee_team.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->where('hrm_teams.organization_id', $organization_id)
		->where('hrm_teams.id', $team->id)->get();
			}
		}

		Custom::add_addon('records');
		return response()->json(['status' => 1, 'message' => 'Team'.config('constants.flash.updated'), 'data' => ['id' => $team->id, 'name' => $team->name, 'team_members' => $team_members, 'description' => $team->description ,'status' => $team->status ]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$team = HrmTeam::findOrFail($request->id);

		$team->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Team'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$teams = explode(',', $request->id);

		$team_list = [];

		foreach ($teams as $team_id) {
			$team = HrmTeam::findOrFail($team_id);
			$team->delete();
			$team_list[] = $team_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Team'.config('constants.flash.deleted'),'data'=>['list' => $team_list]]);
	}

	public function team_status_approval(Request $request)
	{
		HrmTeam::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Team'.config('constants.flash.updated'),'data'=>[]]);
	}

	public function multiapprove(Request $request)
	{
		$teams = explode(',', $request->id);

		$team_list = [];

		foreach ($teams as $team_id) {
			HrmTeam::where('id', $team_id)->update(['status' => $request->input('status')]);;
			$team_list[] = $team_id;
		}

		return response()->json(['status'=>1, 'message'=>'Team'.config('constants.flash.updated'),'data'=>['list' => $team_list]]);
	}
}
