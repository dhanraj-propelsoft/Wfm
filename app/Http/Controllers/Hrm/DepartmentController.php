<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmDepartment;
use App\HrmEmployee;
use App\Custom;
use Validator;
use Session;
use DB;

class DepartmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$parent_department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$parent_department->prepend('Select Parent Department', '');

		$department = HrmDepartment::select('hrm_departments.id', 'hrm_departments.name', 'hrm_departments.parent_id', 'hrm_departments.description', 'hrm_departments.status', 'parent.name as parent_name');
		$department->leftJoin('hrm_departments as parent', 'parent.id', '=', 'hrm_departments.parent_id');
		$department->where('hrm_departments.organization_id', $organization_id);
		$department->orderby('hrm_departments.name');
		$departments = $department->paginate(10);

		/** Search drop down strt here **/
		$department_name   = HrmDepartment::where('organization_id',$organization_id)->pluck('name','id');
		$department_name->prepend('Select Department', '');
		/** Search drop down end here **/

		return view('hrm.departments', compact('departments', 'parent_department','department_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$parent_dept = HrmDepartment::where('organization_id',$organization_id)->pluck('name', 'id');
		$parent_dept->prepend('Select Parent Department', '');

		return view('hrm.departments_create', compact('parent_dept'));
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
		]);
		
		$organization_id = Session::get('organization_id');

		$department = new HrmDepartment;
		$department->name = $request->input('name');
		if($request->input('parent_department') != "") {
			$department->parent_id = $request->input('parent_department');
		}
		if($request->input('description') != "") {
			$department->description = $request->input('description');
		}
		$department->organization_id = $organization_id;
		$department->save();

		$parent_name = ($request->input('parent_department') != null) ? HrmDepartment::findorFail($department->parent_id)->name : "";

		Custom::userby($department, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Department'.config('constants.flash.added'), 'data' => ['id' => $department->id, 'name' => $department->name, 'parent_department' => $parent_name, 'description' => ($department->description != null) ? $department->description : ""]]);
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

		$department = HrmDepartment::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$department) abort(403);

		/*$department = HrmDepartment::select('hrm_departments.id', 'hrm_departments.name', 'hrm_departments.parent_id', 'hrm_departments.description', 'parent.name as parent_name');
		$department->leftJoin('hrm_departments as parent', 'parent.id', '=', 'hrm_departments.parent_id');
		$department->where('hrm_departments.organization_id', $organization_id);
		$department->orderby('hrm_departments.name')->first();*/

		$parent_department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$parent_department->prepend('Select Parent Department', '');

		return view('hrm.departments_edit', compact('department', 'parent_department'));
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
		]);

		$department = HrmDepartment::findOrFail($request->input('id'));
		$department->name = $request->input('name');
		$department->parent_id = $request->input('parent_department');        
		$department->description = $request->input('description'); 
		$department->save();

		$parent_name = ($request->input('parent_department') != null) ? HrmDepartment::findorFail($department->parent_id)->name : "";

		Custom::userby($department, false);

		return response()->json(['status' => 1, 'message' => 'Department'.config('constants.flash.updated'), 'data' => ['id' => $department->id, 'name' => $department->name, 'parent_department' => $parent_name, 'description' => ($department->description != null) ? $department->description : "",'status' =>$department->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$department = HrmDepartment::findOrFail($request->input('id'));
		$department->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Department'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$departments = explode(',', $request->id);

		$department_list = [];

		foreach ($departments as $department_id) {
			$department = HrmDepartment::findOrFail($department_id);
			$department->delete();
			$department_list[] = $department_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Department'.config('constants.flash.deleted'),'data'=>['list' => $department_list]]);
	}

	public function multiapprove(Request $request)
	{
		$departments = explode(',', $request->id);

		$department_list = [];

		foreach ($departments as $department_id) {
			HrmDepartment::where('id', $department_id)->update(['status' => $request->input('status')]);
			$department_list[] = $department_id;
		}

		return response()->json(['status'=>1, 'message'=>'Department'.config('constants.flash.updated'),'data'=>['list' => $department_list]]);
	}

	public function department_status_approval(Request $request)
	{
		HrmDepartment::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Department'.config('constants.flash.updated'),'data'=>[]]);
	}

	public function get_employee(Request $request)
	{
		$this->validate($request, [
			  'department_id'  => 'required'
		]);

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS name'))
		->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_designations', 'hrm_designations.id', '=', 'hrm_employee_designation.designation_id')
		->leftjoin('hrm_departments', 'hrm_designations.department_id', '=', 'hrm_departments.id')
		->where('hrm_employees.organization_id',Session::get('organization_id'))
		->whereNull('hrm_employees.deleted_at')
		->where('hrm_departments.id', $request->department_id)
		->groupBy('hrm_employees.id')
		->orderBy('hrm_employees.first_name')
		->get();

		return response()->json(array('result' => $employees));
	}

	
}
