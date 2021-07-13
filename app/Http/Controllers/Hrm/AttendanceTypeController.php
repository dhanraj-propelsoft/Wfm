<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmAttendanceType;
use App\Custom;
use Validator;
use Session;

class AttendanceTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$attendance_types = HrmAttendanceType::select('hrm_attendance_types.id', 'hrm_attendance_types.display_name AS name', 'hrm_attendance_types.color', 'hrm_attendance_types.status', 'hrm_attendance_types.description')->where('organization_id', $organization_id)->get();

		return view('hrm.attendance_type', compact('attendance_types'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		return view('hrm.attendance_type_create');
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
			'color' => 'required'
		]);
		
		$organization_id = Session::get('organization_id');
	   // dd($request->all());
		$attendance_type = new HrmAttendanceType;
		$attendance_type->name = $request->input('name');
		$attendance_type->display_name = $request->input('name');
		$attendance_type->color = $request->input('color');
		$attendance_type->paid_status = $request->input('paid_status');
		$attendance_type->description = $request->input('description');
		$attendance_type->delete_status = 1;
		$attendance_type->organization_id = $organization_id;
		$attendance_type->save();

		Custom::userby($attendance_type, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Attendance Type'.config('constants.flash.added'), 'data' => ['id' => $attendance_type->id, 'name' => $attendance_type->display_name, 'color' => $attendance_type->color, 'description' => ($attendance_type->description != null) ? $attendance_type->description : "", 'status' => $attendance_type->status ]]);
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

		$attendance_type = HrmAttendanceType::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$attendance_type) abort(403);

		return view('hrm.attendance_type_edit', compact('attendance_type'));
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
			'color' => 'required'
		]); 

		//dd($request->id);
		$attendance_type = HrmAttendanceType::findOrFail($request->input('id'));
		if($attendance_type->delete_status == 1) {
			$attendance_type->name = $request->input('name');
		}
		$attendance_type->display_name = $request->input('name');
		$attendance_type->color = $request->input('color');
		$attendance_type->paid_status = $request->input('paid_status');
		$attendance_type->description = $request->input('description');
		$attendance_type->save();

		Custom::userby($attendance_type, false);

		return response()->json(['status' => 1, 'message' => 'Attendance Type'.config('constants.flash.updated'), 'data' => ['id' => $attendance_type->id, 'name' => $attendance_type->display_name, 'color' => $attendance_type->color, 'description' => ($attendance_type->description != null) ? $attendance_type->description : "", 'status' => $attendance_type->status ]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$attendance_type = HrmAttendanceType::findOrFail($request->input('id'));
		$attendance_type->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Attendance Type'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function attendance_types_status_approval(Request $request)
	{
		HrmAttendanceType::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}

	public function multidestroy(Request $request)
	{
		$attendance_types = explode(',', $request->id);
		$attendance_type_list = [];

		foreach ($attendance_types as $attendance_type_id) {
			$attendance_type_delete = HrmAttendanceType::findOrFail($attendance_type_id);
			$attendance_type_delete->delete();
			$attendance_type_list[] = $attendance_type_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Attendance Type'.config('constants.flash.deleted'),'data'=>['list' => $attendance_type_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$attendance_types = explode(',', $request->id);
		$attendance_type_list = [];

		foreach ($attendance_types as $attendance_type_id) {
			HrmAttendanceType::where('id', $attendance_type_id)->update(['status' => $request->input('status')]);;
			$attendance_type_list[] = $attendance_type_id;
		}

		return response()->json(['status'=>1, 'message'=>'Attendance Type'.config('constants.flash.updated'),'data'=>['list' => $attendance_type_list]]);
	}
}
