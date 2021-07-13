<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmAttendanceType;
use App\HrmWeekOff;
use Carbon\Carbon;
use App\Weekday;
use App\Custom;
use App\State;
use Response;
use DateTime;
use Session;
use Auth;
use DB;

class WeekOffController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$weekoffs = HrmWeekOff::select('id', 'name',  DB::raw('DATE_FORMAT(effective_date, "%d-%m-%Y") AS effective_date'),'description','status')->where('organization_id',$organization_id)
		->paginate(10);       
		
		return view('hrm.weekoff',compact('weekoffs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$weekdays = Weekday::pluck('display_name','id');
		$weekdays->prepend('Select Week Days','');

		return view('hrm.weekoff_create',compact('weekdays'));
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
			'effective_date'=> 'required'
		]);

		//return $request->all();
		$organization_id = Session::get('organization_id');

		$effective_date = explode('-', $request->input('effective_date'));

		$weekoff = new HrmWeekOff;
		$weekoff->name = $request->input('name');
		$weekoff->effective_date = $effective_date[2]."-".$effective_date[1]."-".$effective_date[0];
		$weekoff->first_week_off = $request->input('first_week_off');
		if($request->input('first_week_off_period') != null){
			$weekoff->first_week_off_period = $request->input('first_week_off_period');
		}        

		if($request->input('first_week_half_day') != null)
		{
			$weekoff->first_week_half_day = $request->input('first_week_half_day');
		} 

		if($request->input('first_half_minimum') != null){
			$first_half_minimum = new DateTime($request->input('first_half_minimum'));
			$weekoff->first_half_minimum = $first_half_minimum->format('H:i:s');
		}        

		if($request->input('first_full_day_rule') != null){
			$weekoff->first_full_day_rule = $request->input('first_full_day_rule');
		}  

		if($request->input('second_week_off') != null)
		{
			$weekoff->second_week_off = $request->input('second_week_off');
		}
		
		if($request->input('second_week_off_period') != null)
		{
			$weekoff->second_week_off_period = $request->input('second_week_off_period');
		}        

		if($request->input('second_week_half_day') != null)
		{
			$weekoff->second_week_half_day = $request->input('second_week_half_day');
		} 

		if($request->input('second_half_minimum') != null)
		{
			$second_half_minimum = new DateTime($request->input('second_half_minimum'));
			$weekoff->second_half_minimum = $second_half_minimum->format('H:i:s');
		}        
		
		if($request->input('second_full_day_rule') != null)
		{
			$weekoff->second_full_day_rule = $request->input('second_full_day_rule');
		}

		if($request->input('paid_status') != null)
		{
			$weekoff->pay_status = $request->input('paid_status');
		}

		$weekoff->description = $request->input('description');
		$weekoff->organization_id = $organization_id;
		$weekoff->save();

		if($weekoff)
		{
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
		}

		Custom::userby($weekoff, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Week-Off'.config('constants.flash.added'), 'data' => ['id' => $weekoff->id, 'name' => $weekoff->name, 'effective_date' => $request->input('effective_date'),'description' => ($weekoff->description != null) ? $weekoff->description : ""]]);
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

		$weekdays = Weekday::pluck('display_name','id');
		$weekdays->prepend('Select Week Days','');

		$weekoffs = HrmWeekOff::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$weekoffs) abort(403);

		return view('hrm.weekoff_edit',compact('weekoffs','weekdays'));
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
			'effective_date'=> 'required'
		]);

		//return $request->all();
		$organization_id = Session::get('organization_id');

		$effective_date = explode('-', $request->input('effective_date'));

		$weekoff =  HrmWeekOff::findOrFail($request->input('id'));     
		$weekoff->name = $request->input('name');
		$weekoff->effective_date = $effective_date[2]."-".$effective_date[1]."-".$effective_date[0];
		$weekoff->first_week_off = $request->input('first_week_off');

		$weekoff->first_week_off_period = null;
		if($request->input('first_week_off_period') != null) $weekoff->first_week_off_period = $request->input('first_week_off_period');
			 
		$weekoff->first_week_half_day = 0;
		if($request->input('first_week_half_day') != null) $weekoff->first_week_half_day = $request->input('first_week_half_day');         

		$weekoff->first_half_minimum = null;
		if($request->input('first_half_minimum') != null){
			$first_half_minimum = new DateTime($request->input('first_half_minimum'));
			$weekoff->first_half_minimum = $first_half_minimum->format('H:i:s');
		} 
			 
		$weekoff->first_full_day_rule = 0;    
		if($request->input('first_full_day_rule') != null) $weekoff->first_full_day_rule = $request->input('first_full_day_rule');
		
		$weekoff->second_week_off = null;
		if($request->input('second_week_off') != null) $weekoff->second_week_off = $request->input('second_week_off');        
		
		$weekoff->second_week_off_period = null;
		if($request->input('second_week_off_period') != null) $weekoff->second_week_off_period = $request->input('second_week_off_period');

		$weekoff->second_week_half_day = 0;
		if($request->input('second_week_half_day') != null) $weekoff->second_week_half_day = $request->input('second_week_half_day');
		
		$weekoff->second_half_minimum = null;
		if($request->input('second_half_minimum') != null){
			$second_half_minimum = new DateTime($request->input('second_half_minimum'));
			$weekoff->second_half_minimum = $second_half_minimum->format('H:i:s');
		} 
			   
		$weekoff->second_full_day_rule = 0;
		if($request->input('second_full_day_rule') != null) $weekoff->second_full_day_rule = $request->input('second_full_day_rule');

		if($request->input('pay_status') != null)
		{
			$weekoff->pay_status = $request->input('pay_status');
		} 

		$weekoff->description = $request->input('description');
		$weekoff->save();

		if($weekoff)
		{
			$attendance_type = HrmAttendanceType::findOrFail($request->input('name'));
			$attendance_type->name = $request->input('name');
			$attendance_type->display_name = $request->input('name');
			$attendance_type->color = $request->input('color');
			$attendance_type->paid_status = $request->input('paid_status');
			$attendance_type->description = $request->input('description');
			$attendance_type->save();

			Custom::userby($attendance_type, false);
		}

		Custom::userby($weekoff, false);

		return response()->json(['status' => 1, 'message' => 'Week-Off'.config('constants.flash.updated'), 'data' => ['id' => $weekoff->id, 'name' => $weekoff->name, 'effective_date' => $request->input('effective_date'),'description' => ($weekoff->description != null) ? $weekoff->description : "",'status' => $weekoff->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$weekoff = HrmWeekOff::findOrFail($request->id);
		$weekoff->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Week-Off'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$week_offs = explode(',', $request->id);

		$weekoff_list = [];

		foreach ($week_offs as $week_off_id) {
			$weekoff = HrmWeekOff::findOrFail($week_off_id);
			$weekoff->delete();
			$weekoff_list[] = $week_off_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Week Off'.config('constants.flash.deleted'),'data'=>['list' => $weekoff_list]]);
	}

	public function multiapprove(Request $request)
	{
		$week_offs = explode(',', $request->id);

		$weekoff_list = [];

		foreach ($week_offs as $week_off_id) {
			HrmWeekOff::where('id', $week_off_id)->update(['status' => $request->input('status')]);;
			$weekoff_list[] = $week_off_id;
		}

		return response()->json(['status'=>1, 'message'=>'Week Off'.config('constants.flash.updated'),'data'=>['list' => $weekoff_list]]);
	}

	public function weekoff_status_approval(Request $request)
	{
		HrmWeekOff::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Week Off'.config('constants.flash.updated'),'data'=>[]]);
	}
}
