<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmAttendanceSetting;
use App\Custom;
use Validator;
use Session;
use DB;

class AttendanceSettingController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$attendance_settings = HrmAttendanceSetting::select('hrm_attendance_settings.*')
			->where('organization_id',$organization_id)
			->paginate(10);

		return view('hrm.attendance_setting', compact('attendance_settings'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('hrm.attendance_setting_create');
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

		$attendance = new HrmAttendanceSetting;
		$attendance->name = $request->input('name');        
		$attendance->standard_working_hours = Carbon::parse($request->input('standard_working_hours'))->format('H:i:s');
		$attendance->min_hours_for_full_day = Carbon::parse($request->input('min_hours_for_full_day'))->format('H:i:s');
		$attendance->min_hours_for_half_day = Carbon::parse($request->input('min_hours_for_half_day'))->format('H:i:s');
		$attendance->min_hours_for_official_half_day = Carbon::parse($request->input('min_hours_for_official_half_day'))->format('H:i:s');
		$attendance->grace_time = Carbon::parse($request->input('grace_time'))->format('Y-m-d H:i:s');
		$attendance->deduction_days = $request->input('deduction_days');
		if($request->input('cancel_deduction') != ""){
			$attendance->cancel_deduction = $request->input('cancel_deduction');
		} else {
			$attendance->cancel_deduction = '0';
		}
		$attendance->deduct_from = $request->input('deduct_from');
		$attendance->organization_id = $organization_id;
		$attendance->save();

		Custom::userby($attendance, true);
		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Attendance Setting'.config('constants.flash.added'), 'data' => [
			'id' => $attendance->id,
			'name' => $attendance->name,
			'standard_working_hours' => $attendance->standard_working_hours,
			'min_hours_for_full_day' => $attendance->min_hours_for_full_day,
			'min_hours_for_half_day' => $attendance->min_hours_for_half_day,
			'min_hours_for_official_half_day' => $attendance->min_hours_for_official_half_day,
			'grace_time' => $attendance->grace_time,
			'deduction_days' => ($attendance->deduction_days != '') ? $attendance->deduction_days : "",
			'cancel_deduction' => ($attendance->cancel_deduction == '1') ? "Yes" : "No",
			'deduct_from' => ($attendance->deduct_from == '1') ? "LOP" : "CL",
		]]);
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

		$attendance = HrmAttendanceSetting::select('hrm_attendance_settings.id','hrm_attendance_settings.name',
			DB::raw('DATE_FORMAT(hrm_attendance_settings.standard_working_hours,"%h:%i %p") AS standard_working_hours'),
			DB::raw('DATE_FORMAT(hrm_attendance_settings.min_hours_for_full_day,"%h:%i %p") AS min_hours_for_full_day'),
			DB::raw('DATE_FORMAT(hrm_attendance_settings.min_hours_for_half_day,"%h:%i %p") AS min_hours_for_half_day'),
			DB::raw('DATE_FORMAT(hrm_attendance_settings.min_hours_for_official_half_day,"%h:%i %p") AS min_hours_for_official_half_day'),
			DB::raw('DATE_FORMAT(hrm_attendance_settings.grace_time,"%h:%i %p") AS grace_time'),
			'hrm_attendance_settings.deduction_days', 'hrm_attendance_settings.cancel_deduction', 'hrm_attendance_settings.deduct_from')
		->where('id', $id)->where('organization_id', $organization_id)->first();

		if(!$attendance) abort(403);

		return view('hrm.attendance_setting_edit', compact('attendance'));
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
		//return $request->all(); 

		$attendance = HrmAttendanceSetting::findOrFail($request->input('id'));
		$attendance->name = $request->input('name');        
		$attendance->standard_working_hours = Carbon::parse($request->input('standard_working_hours'))->format('H:i:s');
		$attendance->min_hours_for_full_day = Carbon::parse($request->input('min_hours_for_full_day'))->format('H:i:s');
		$attendance->min_hours_for_half_day = Carbon::parse($request->input('min_hours_for_half_day'))->format('H:i:s');
		$attendance->min_hours_for_official_half_day = Carbon::parse($request->input('min_hours_for_official_half_day'))->format('H:i:s');
		$attendance->grace_time = Carbon::parse($request->input('grace_time'))->format('Y-m-d H:i:s');
		$attendance->deduction_days = $request->input('deduction_days');
		$attendance->cancel_deduction = $request->input('cancel_deduction');
		$attendance->deduct_from = $request->input('deduct_from');
		$attendance->save();

		Custom::userby($attendance, false);

		return response()->json(['status' => 1, 'message' => 'Attendance Setting'.config('constants.flash.updated'), 'data' => [
			'id' => $attendance->id,
			'name' => $attendance->name,
			'standard_working_hours' => $attendance->standard_working_hours,
			'min_hours_for_full_day' => $attendance->min_hours_for_full_day,
			'min_hours_for_half_day' => $attendance->min_hours_for_half_day,
			'min_hours_for_official_half_day' => $attendance->min_hours_for_official_half_day,
			'grace_time' => $attendance->grace_time,
			'deduction_days' => ($attendance->deduction_days != '') ? $attendance->deduction_days : "",
			'cancel_deduction' => ($attendance->cancel_deduction == '1') ? "Yes" : "No",
			'deduct_from' => ($attendance->deduct_from == '1') ? "LOP" : "CL",
		]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$attendance = HrmAttendanceSetting::findOrFail($request->input('id'));
		$attendance->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Attendance Setting'.config('constants.flash.deleted'), 'data' => []]);
	}
}
