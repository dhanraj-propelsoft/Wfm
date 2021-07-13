<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Custom;
use App\Role;
use App\Module;
use Session;
use DB;

class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$roles = Role::where('organization_id', Session::get('organization_id'))->get();		

		return view('settings.role',compact('roles'));

	}

	public function get_copy_role($id)
	{
		//dd($request->all());
		//$role_permissions = DB::table('role_permissions')->where('role_id',$request->id)->get();
		$role = Role::find($id);

		$role_name = 'Copy_'.$role->name;
		$display_name = 'Copy_'.$role->display_name;
		$permission = Permission::where('status', 1)->orderBy('module')->get();
		
		$roles = DB::table("permission_role")->where("permission_role.role_id",$id)->pluck('permission_role.permission_id','permission_role.permission_id');
		//dd($roles);

		 $rolePermissions = array();

		foreach($roles as $r) {
			$rolePermissions[] =  $r;
		}

		return view('settings.roles_copy',compact('role','permission','rolePermissions','role_name','display_name'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function create() {

		$organization_id = Session::get('organization_id');

		$permission = Permission::where('status', 1)->orderBy('module')->get();
		
		$module_org = DB::table('module_organization')->where('organization_id',$organization_id)->get();

		foreach ($module_org as $key => $value) {

			$module_id = $module_org[$key]->module_id;
			$module_name[] = Module::where('id', $module_id)->first()->name;
		}

		return view('settings.roles_create',compact('permission','module_name'));

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function store(Request $request) {

		$this->validate($request, [
			'name' => 'required|unique:roles,name',
			'display_name' => 'required',
			'permission' => 'required',
		]);

		$role = new Role();
		$role->name = $request->input('name');
		$role->organization_id = Session::get('organization_id');
		$role->display_name = $request->input('display_name');
		$role->description = $request->input('description');
		$role->save();

		Custom::userby($role, true);

		foreach ($request->input('permission') as $key => $value) {
			$role->attachPermission($value);
		}

		return response()->json(['status' => 1, 'message' => 'Role'.config('constants.flash.added'), 'data' => ['id' => $role->id, 'name' => $role->name, 'display_name' => $role->display_name, 'description' => $role->description]]);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function show($id) {

		$role = Role::find($id);

		$rolePermissions = Permission::join("permission_role","permission_role.permission_id","=","permissions.id")->where("permission_role.role_id",$id)->where('permissions.status', 1)->get();

		return view('settings.roles_show',compact('role','rolePermissions'));

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function edit($id) {

		$organization_id = Session::get('organization_id');	

		$role = Role::find($id);

		//dd($role);

		$permission = Permission::where('status', 1)->orderBy('module')->get();

		$roles = DB::table("permission_role")->where("permission_role.role_id",$id)->pluck('permission_role.permission_id','permission_role.permission_id');

		 $rolePermissions = array();

		foreach($roles as $r) {
			$rolePermissions[] =  $r;
		}		

		$module_org = DB::table('module_organization')->where('organization_id',$organization_id)->get();

		foreach ($module_org as $key => $value) {

			$module_id = $module_org[$key]->module_id;
			$module_name[] = Module::where('id', $module_id)->first()->name;
		}

		return view('settings.roles_edit',compact('role','permission','rolePermissions','module_name'));

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function update(Request $request) {

		$this->validate($request, [
			'display_name' => 'required',
			'permission' => 'required',

		]);

		//dd($request->all());

		$role = Role::find($request->input('id'));
		$role->name = $request->input('name');
		$role->display_name = $request->input('display_name');
		$role->description = $request->input('description');
		$role->save();
		Custom::userby($role, false);

		DB::table("permission_role")->where("permission_role.role_id",$request->input('id'))->delete();

		foreach ($request->input('permission') as $key => $value) {

			$role->attachPermission($value);
		}

		return response()->json(['status' => 1, 'message' => 'Role'.config('constants.flash.updated'), 'data' => ['id' => $role->id, 'name' => $role->name, 'display_name' => $role->display_name, 'description' => $role->description]]);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function destroy(Request $request) {

		DB::table("roles")->where('id',$request->input('id'))->delete($request->input('id'));
		Session::flash('flash_message', 'Role successfully deleted!');

		return response()->json(['status' => 1, 'message' => 'Role'.config('constants.flash.deleted'), 'data' => []]);
	}
	public function role_check(Request $request) {
		$organization_id = Session::get('organization_id');

		$role_name = role::where('id',$request->input('role_id'))
		->first()->name;
		$role_name ='Copy_'.$role_name; 
		
		$check=role::where('organization_id',$organization_id)
		->where('name',$role_name)
		->exists();

		return response()->json(['status' =>$check]);
	}
}
