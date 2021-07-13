<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\HrmAttendanceSetting;
use App\Http\Requests;
use App\HrmBreak;
use App\HrmShift;
use Carbon\Carbon;
use App\Custom;
use Validator;
use Session;
use DB;


class ShiftController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$work_shift = HrmShift::select('hrm_shifts.id','hrm_shifts.name',
		 DB::raw('GROUP_CONCAT(hrm_breaks.name) AS break_name'), 
		 DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.start_time,"%h:%i %p")) AS start_time'),
		 DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.end_time,"%h:%i %p")) AS end_time'), 
		 DB::raw('DATE_FORMAT(hrm_shifts.from_time,"%h:%i %p") AS from_time'), 
		 DB::raw('DATE_FORMAT(hrm_shifts.to_time,"%h:%i %p") AS to_time'), 
		 'hrm_shifts.total_hours','hrm_shifts.status', 'hrm_attendance_settings.name AS attendance_name')
		->leftJoin('hrm_break_shift', 'hrm_shifts.id', '=', 'hrm_break_shift.shift_id')
		->leftJoin('hrm_breaks', 'hrm_breaks.id', '=', 'hrm_break_shift.break_id')
		->leftJoin('hrm_attendance_settings', 'hrm_attendance_settings.id', '=', 'hrm_shifts.attendance_settings_id')
		->where('hrm_shifts.organization_id',$organization_id)
		->groupby('hrm_shifts.id');
		//return $work_shift->toSql();
		$work_shifts = $work_shift->paginate(10);        

		return view('hrm.shifts', compact('work_shifts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$attendance_setting = HrmAttendanceSetting::where('organization_id',$organization_id)->pluck('name','id');
		$attendance_setting->prepend('Select Attendance Setting', ''); 

		$work_breaks = HrmBreak::select('hrm_breaks.name AS break_name','hrm_breaks.id AS break_id')         
		->where('hrm_breaks.organization_id',$organization_id);
		$work_break = $work_breaks->get();

		return view('hrm.shifts_create',compact('work_break', 'attendance_setting'));
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
			'from_time' => 'required',
			'to_time' => 'required'
		]); 
		//return $request->all();     
		$organization_id = Session::get('organization_id');

		$from_time = Carbon::parse($request->input('from_time'));
		$to_time = Carbon::parse($request->input('to_time'));

		$attendance_setting = HrmAttendanceSetting::where('organization_id',$organization_id)->first()->id;
		
		$workshift = new HrmShift;
		$workshift->name = $request->input('name');        
		$workshift->from_time = $from_time->format('H:i:s');
		$workshift->to_time = $to_time->format('H:i:s');
		$workshift->total_hours = $to_time->diffInMinutes($from_time) / 60;
		$workshift->attendance_settings_id = $attendance_setting;
		$workshift->organization_id = $organization_id;
		$workshift->save();

		Custom::userby($workshift, true);
		Custom::add_addon('records');

		$attendance_settings_name = HrmAttendanceSetting::findorFail($workshift->attendance_settings_id)->name;
		
		$break_times = [];
		$break_id = $request->input('break_id');
		$workshift->breaks()->detach();
		
		if($break_id != null) {
			$break = array_filter($break_id);
			if(!empty($break)) {
				foreach ($request->input('break_id') as $key => $value) {
					$workshift->breaks()->attach($value);
				}

				$break_times = HrmBreak::select('hrm_breaks.name AS break_name', DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.start_time,"%h:%i %p")) AS start_time'),
					DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.end_time,"%h:%i %p")) AS end_time'))->whereIn('hrm_breaks.id', $break_id )->groupby('hrm_breaks.id')->get();
			}
		}

		return response()->json(['status' => 1, 'message' => 'Shift'.config('constants.flash.added'), 'data' => ['id' => $workshift->id, 'name' => $workshift->name, 'from_time' => $workshift->from_time, 'to_time' => $workshift->to_time, 'total_hours' => $workshift->total_hours, 'attendance_settings_id' => $attendance_settings_name, 'breaks' => $break_times]]);
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

		$attendance_setting = HrmAttendanceSetting::where('organization_id',$organization_id)->pluck('name','id');
		$attendance_setting->prepend('Select Attendance Setting', '');      

		$work_shift = HrmShift::select('hrm_shifts.id','hrm_shifts.name',DB::raw('DATE_FORMAT(hrm_shifts.from_time,"%h:%i %p") AS from_time'), DB::raw('DATE_FORMAT(hrm_shifts.to_time,"%h:%i %p") AS to_time'), DB::raw('hrm_shifts.total_hours'), 'hrm_shifts.attendance_settings_id')
		->where('hrm_shifts.id', $id)->where('hrm_shifts.organization_id', $organization_id)->first();

		if(!$work_shift) abort(403);

		$work_breaks = HrmBreak::select('hrm_breaks.name AS break_name','hrm_breaks.id AS break_id')         
		  ->where('hrm_breaks.organization_id',$organization_id)->get();   

		$shift_break = DB::table('hrm_break_shift')->select('break_id')->where('shift_id', $work_shift->id)->get();

		$shift_breaks = [];

		foreach ($shift_break as $break) {
		   $shift_breaks[] = $break->break_id;
		}

		return view('hrm.shifts_edit', compact('work_shift','work_breaks', 'shift_breaks', 'attendance_setting'));
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
			'from_time'=>'required',
			'to_time'=>'required'                         
		]);

		$from_time = Carbon::parse($request->input('from_time'));
		$to_time = Carbon::parse($request->input('to_time'));


		$workshift = HrmShift::findOrFail($request->input('id'));
		$workshift->name = $request->input('name');        
		$workshift->from_time = $from_time->format('H:i:s');
		$workshift->to_time = $to_time->format('H:i:s');
		$workshift->total_hours = $to_time->diffInMinutes($from_time) / 60;
		/*$workshift->attendance_settings_id = $attendance_setting; */     
		$workshift->save();

		Custom::userby($workshift, false);
		$attendance_settings_name = HrmAttendanceSetting::findorFail($workshift->attendance_settings_id)->name;
		
		$break_times = [];
		$break_id = $request->input('break_id');
		$workshift->breaks()->detach();

		if($break_id != null) {
			$break = array_filter($break_id);
			if(!empty($break)) {
				foreach ($request->input('break_id') as $key => $value) {
					$workshift->breaks()->attach($value);
				}

				$break_times = HrmBreak::select('hrm_breaks.name AS break_name', DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.start_time,"%h:%i %p")) AS start_time'),
					DB::raw('GROUP_CONCAT(DATE_FORMAT(hrm_breaks.end_time,"%h:%i %p")) AS end_time'))->whereIn('hrm_breaks.id', $break_id )->groupby('hrm_breaks.id')->get();
			}
		}        

		return response()->json(['status' => 1, 'message' => 'Shift'.config('constants.flash.updated'), 'data' => ['id' => $workshift->id, 'name' => $workshift->name, 'from_time' => $workshift->from_time, 'to_time' => $workshift->to_time, 'total_hours' => $workshift->total_hours, 'break_id' => $break_times, 'attendance_settings_id' => $attendance_settings_name,'status' => $workshift->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$workshift = HrmShift::findOrFail($request->input('id'));
		$workshift->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Shift'.config('constants.flash.deleted'), 'data' => []]);
	}
	public function multidestroy(Request $request)
	{
		$shifts = explode(',', $request->id);

		$shift_list = [];

		foreach ($shifts as $shift_id) {
			$shift = HrmShift::findOrFail($shift_id);
			$shift->delete();
			$shift_list[] = $shift_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Shift'.config('constants.flash.deleted'),'data'=>['list' => $shift_list]]);
	}

	public function multiapprove(Request $request)
	{
		$shifts = explode(',', $request->id);

		$shift_list = [];

		foreach ($shifts as $shift_id) {
			HrmShift::where('id', $shift_id)->update(['status' => $request->input('status')]);;
			$shift_list[] = $shift_id;
		}

		return response()->json(['status'=>1, 'message'=>'Shift'.config('constants.flash.updated'),'data'=>['list' => $shift_list]]);
	}

	public function shift_status_approval(Request $request)
	{
		HrmShift::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		 return response()->json(['status'=>1, 'message'=>'Shift'.config('constants.flash.updated'),'data'=>[]]);
	}
}
