<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmDesignation;
use App\HrmDepartment;
use App\HrmEmployee;
use App\Custom;
use Validator;
use Session;
use DB;

class DesignationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$designations = HrmDesignation::select('hrm_designations.*', 'hrm_departments.name AS department')->where('hrm_designations.organization_id', $organization_id)->leftjoin('hrm_departments', 'hrm_departments.id', '=', 'hrm_designations.department_id' )->orderby('hrm_designations.name')->get();

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Choose Department', '');

		/** Searh Strt Here **/
		$designation_name  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation_name->prepend('Select Designation Name', '');
		/** Searh End Here **/

		return view('hrm.designations', compact('designations', 'department','designation_name'));
	}

	public function get_designation(Request $request)
    {
        $this->validate($request, [
              'department_id'  => 'required'
        ]);

        $department = HrmDesignation::select('id', 'name')->where('department_id', $request->input('department_id'))->where('organization_id',Session::get('organization_id'))->get();
        return response()->json(array('result' => $department));
    }

    public function get_employee(Request $request)
    {
        $this->validate($request, [
              'designation_id'  => 'required'
        ]);

        $employees = HrmEmployee::select('id', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS name'))
        ->leftjoin('hrm_employee_designation', 'hrm_employee_designation.employee_id', '=', 'hrm_employees.id')
        ->where('hrm_employees.organization_id',Session::get('organization_id'))
        ->where('hrm_employees.status', 1)
        ->where('hrm_employee_designation.designation_id', $request->designation_id)
        ->groupBy('hrm_employees.id')
        ->orderBy('hrm_employees.first_name')->get();

        return response()->json(array('result' => $employees));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Choose Department Name', '');
		return view('hrm.designations_create', compact('department'));
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
			'department_id' => 'required'
		]);
		
		$organization_id = Session::get('organization_id');
	   // dd($request->all());
		$designation = new HrmDesignation;
		$designation->name = $request->input('name');
		$designation->department_id = $request->input('department_id');
		$designation->description = $request->input('description');
		$designation->positions = $request->input('positions');
		$designation->organization_id = $organization_id;
		$designation->save();

		$parent_name = HrmDepartment::findorFail($designation->department_id)->name;

		Custom::userby($designation, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Designations'.config('constants.flash.added'), 'data' => ['id' => $designation->id, 'name' => $designation->name, 'department_id' => $parent_name, 'description' => ($designation->description != null) ? $designation->description : "", 'positions' => ($designation->positions != null) ? $designation->positions : ""]]);
		
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

		$designation = HrmDesignation::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$designation) abort(403);

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Choose Department', '');

		return view('hrm.designations_edit', compact('designation', 'department'));
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
			'department_id' => 'required'
		]); 

		//dd($request->id);
		$designation = HrmDesignation::findOrFail($request->input('id'));
		$designation->name = $request->input('name');        
		$designation->department_id = $request->input('department_id');
		$designation->description = $request->input('description');
		$designation->positions = $request->input('positions');      
		$designation->save();

		$parent_name = HrmDepartment::findorFail($designation->department_id)->name;

		Custom::userby($designation, false);

		return response()->json(['status' => 1, 'message' => 'Designations'.config('constants.flash.updated'), 'data' => ['id' => $designation->id, 'name' => $designation->name, 'department_id' => $parent_name, 'description' => ($designation->description != null) ? $designation->description : "", 'positions' => ($designation->positions != null) ? $designation->positions : "",'status' => $designation->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$designation = HrmDesignation::findOrFail($request->input('id'));
		$designation->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Designation'.config('constants.flash.deleted'), 'data' => []]);
	}

	

	public function multidestroy(Request $request)
	{
		$designations = explode(',', $request->id);

		$designation_list = [];

		foreach ($designations as $designation_id) {
			$designation = HrmDesignation::findOrFail($designation_id);
			$designation->delete();
			$designation_list[] = $designation_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Designation'.config('constants.flash.deleted'),'data'=>['list' => $designation_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$designations = explode(',', $request->id);

		$designation_list = [];

		foreach ($designations as $designation_id) {
			HrmDesignation::where('id', $designation_id)->update(['status' => $request->input('status')]);;
			$designation_list[] = $designation_id;
		}

		return response()->json(['status'=>1, 'message'=>'Designation'.config('constants.flash.updated'),'data'=>['list' => $designation_list]]);
	}

	public function designations_status_approval(Request $request)
	{
		HrmDesignation::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Designation'.config('constants.flash.updated'),'data'=>[]]);
	}
}
