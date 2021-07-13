<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\HrmEmployee;
use App\Role;
use App\User;
use Session;
use DB;

class UserPrivilegeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		
		$organization_id = Session::get('organization_id');
		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS name'), 'users.id AS user_id')
		->leftjoin('users', 'users.person_id', '=', 'hrm_employees.person_id')
		->where('hrm_employees.organization_id', Session::get('organization_id'))
		->whereNotNull('users.id')
		->get();

		return view('settings.previlege', compact('employees'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$employee = HrmEmployee::findOrFail($id);
		$employee_name = $employee->first_name." ".$employee->last_name;
		$user = User::where('person_id', $employee->person_id)->first();


		$organizations = Organization::pluck('name', 'id');
		$roles = Role::where('organization_id', Session::get('organization_id'))->get();

		$assigned_roles = $user->roles;

		return view('settings.previlege_edit', compact('employee', 'employee_name', 'organizations', 'roles', 'assigned_roles', 'user'));
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
			'roles' => 'required'
		]);

		$organization_id = Session::get('organization_id');

		$employee = HrmEmployee::findOrFail($request->input('id'));
		$employee_name = $employee->first_name." ".$employee->last_name;

		$user = User::where('person_id', $employee->person_id)->first();

		DB::table('role_user')->where('user_id', $user->id)->where('organization_id', $organization_id)->delete();

		foreach ($request->input('roles') as $key => $value) {
			$user->roles()->attach($value, ['organization_id' => $organization_id]);
		}

		$assigned_roles = $user->roles;
		$roles = [];

		foreach ($assigned_roles as $role) {
			$roles[] = $role["name"];
		}

		return response()->json(['status' => 1, 'message' => 'User Previlege'.config('constants.flash.updated'), 'data' => ['id' => $employee->id, 'name' => $employee_name, 'roles' => $roles]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$employee = HrmEmployee::findOrFail($request->input('id'));

		$user = User::where('person_id', $employee->person_id)->first();

		DB::table('role_user')->where('user_id', $user->id)->where('organization_id', $organization_id)->delete();

		return response()->json(['status' => 1, 'message' => 'User Previlege'.config('constants.flash.deleted'), 'data' => []]);
	}
}
