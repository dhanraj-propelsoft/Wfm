<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmPermission;
use App\HrmEmployee;
use App\HrmProject;
use App\HrmDepartment;
use Session;
use Response;
use Validator;
use App\Role;
use App\User;
use App\State;
use App\Custom;
use DateTime;
use Auth;
use DB;

class PermissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$user = User::findorFail(Auth::user()->id);

		$permission_approval = array();

		foreach($user->roles()->get() as $roles) {
			$roles = DB::table("permission_role")->select('permissions.name')->leftjoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')->where("permission_role.role_id", $roles->pivot->role_id)->where("permissions.name", "permission-approval")->get();

			foreach($roles as $r) {
				$permission_approval[] =  $r;
			}
		}	

		$employee_id = HrmEmployee::select('hrm_employees.id')               
		->leftjoin('persons','hrm_employees.person_id','=','persons.id')
		->where('hrm_employees.person_id',$user->person_id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		if(count($permission_approval) <= 0)
		{
			if($employee_id == null) {
				return redirect()->back()->withErrors('Kindly add yourself as an employee in employee menu!');
			}
		}

		$query = HrmPermission::select('hrm_permissions.id', 'hrm_permissions.employee_id', 'hrm_permissions.permission_date', 'hrm_permissions.reason','hrm_permissions.total_hours', 'hrm_permissions.approval_status', 'hrm_employees.first_name')
		->leftjoin('hrm_employees','hrm_permissions.employee_id', 'hrm_employees.id')
		->where('hrm_permissions.organization_id', $organization_id);
		
		if(count($permission_approval) <= 0)
		{
			$query->where('hrm_employees.id', $employee_id->id);
		}

		$permissions = $query->paginate(10);

		return view('hrm.permissions', compact('permissions'));
	}

	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$user = User::findorFail(Auth::user()->id);

		$employee_id = HrmEmployee::select('hrm_employees.id')               
		->leftjoin('persons','hrm_employees.person_id','=','persons.id')
		->where('hrm_employees.person_id',$user->person_id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		if($employee_id == null && !$user->can('permission-approval')) {
			return redirect()->back()->withErrors('Kindly add yourself as an employee in employee menu!');
		}

		$departments = HrmDepartment::select('id', 'name')->where('organization_id', $organization_id)->pluck('name', 'id');
			$departments->prepend('Select Department', '');

		return view('hrm.permissions_create',compact('departments'));
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
			'permission_date' => 'required',
			'from_time' => 'required',
			'to_time' => 'required',
		]);

		//return $request->all();

		$permission_date = explode('-',$request->input('permission_date'));
		$organization_id = Session::get('organization_id');
		$from_time = new DateTime($request->input('from_time'));
		$to_time = new DateTime($request->input('to_time'));       
		
		$total_hours = $to_time->diff($from_time);
		
		$user = User::findorFail(Auth::user()->id);

		$employee_id = HrmEmployee::select('hrm_employees.id')
		->leftjoin('persons','hrm_employees.person_id','=','persons.id')
		->where('hrm_employees.person_id',$user->person_id)
		->where('hrm_employees.organization_id',$organization_id)->first();


		$permission = new HrmPermission;

		if($request->input('admin_employee_id') != null )
		{
			$permission->employee_id = $request->input('admin_employee_id');
		}
		else{
			$permission->employee_id = $employee_id->id;
		}     
		$permission->permission_date = $permission_date[2].'-'.$permission_date[1].'-'.$permission_date[0];
		$permission->reason = $request->input('reason');
		$permission->from_time = $from_time->format('H:i:s');
		$permission->to_time= $to_time->format('H:i:s');
		$permission->total_hours = $total_hours->format('%H:%i:%s');
		$permission->organization_id = $organization_id; 
		$permission->save();

		Custom::userby($permission, true);

		$permissions = HrmPermission::select('hrm_permissions.*',DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS employee_name'), 'hrm_permissions.total_hours')
		->leftjoin('hrm_employees','hrm_permissions.employee_id','=','hrm_employees.id')
		->where('hrm_permissions.id',$permission->id)     
		->where('hrm_permissions.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Permission'.config('constants.flash.added'), 'data' => ['id' => $permissions->id, 'employee_name' => $permissions->employee_name,'reason'=>$permissions->reason,'total_hours'=> $permissions->total_hours,'approval_status' => $permissions->approval_status]]);
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

		$permissions = HrmPermission::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$permissions) abort(403);
		$employee   = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name','id'); 

		return view('hrm.permissions_edit',compact('permissions','employee'));
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
			'permission_date' => 'required',
			'from_time' => 'required',
			'to_time' => 'required',            
		]);

		//return $request->all();

		$permission_date = explode('-',$request->input('permission_date'));
		$organization_id = Session::get('organization_id');
		$from_time = new DateTime($request->input('from_time'));
		$to_time = new DateTime($request->input('to_time'));        

		$total_hours = $to_time->diff($from_time);

		$permission = HrmPermission::findorFail($request->input('id'));
		$permission->permission_date = $permission_date[2].'-'.$permission_date[1].'-'.$permission_date[0];
		$permission->reason = $request->input('reason');
		$permission->from_time = $from_time->format('H:i:s');
		$permission->to_time= $to_time->format('H:i:s');
		$permission->total_hours = $total_hours->format('%H:%i:%s');
		$permission->organization_id = $organization_id;
		$permission->save();

		Custom::userby($permission, false);

		$permissions = HrmPermission::select('hrm_permissions.*',DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS employee_name'), 'hrm_permissions.total_hours')
		->leftjoin('hrm_employees','hrm_permissions.employee_id','=','hrm_employees.id')
		->where('hrm_permissions.id',$permission->id)     
		->where('hrm_permissions.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Permission'.config('constants.flash.updated'), 'data' => ['id' => $permissions->id, 'employee_name' => $permissions->employee_name,'reason'=>$permissions->reason,'total_hours'=>$permissions->total_hours,'approval_status' => $permissions->approval_status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$permissions = HrmPermission::findOrFail($request->id);
		$permissions->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Permission'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$permissions = explode(',', $request->id);

		$permission_list = [];

		foreach ($permissions as $permission_id) {
			$permission = HrmPermission::findOrFail($permission_id);
			$permission->delete();
			$permission_list[] = $permission_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Permission'.config('constants.flash.deleted'),'data'=>['list' => $permission_list]]);
	}

	public function multiapprove(Request $request)
	{
		$permissions = explode(',', $request->id);

		$permission_list = [];

		foreach ($permissions as $permission_id) {
			HrmPermission::where('id', $permission_id)->update(['status' => $request->input('status')]);
			$permission_list[] = $permission_id;
		}

		return response()->json(['status'=>1, 'message'=>'Permission'.config('constants.flash.updated'),'data'=>['list' => $permission_list]]);
	}

	public function status(Request $request)
	{
		//return $request->all();

		HrmPermission::where('id', $request->input('id'))->update(['approval_status' => $request->input('approval_status')]);

		return response()->json(['status'=>1, 'message'=>'Permission'.config('constants.flash.updated'),'data'=>[]]);
	}
	
}