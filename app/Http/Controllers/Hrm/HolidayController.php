<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmHolidayType;
use App\HrmHoliday;
use Carbon\Carbon;
use App\Custom;
use App\State;
use Response;
use Session;
use Auth;
use DB;

class HolidayController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$holidays = HrmHoliday::select('id', 'name',  DB::raw('DATE_FORMAT(holiday_date, "%d-%m-%Y") AS holiday_date'),'description','status')->where('organization_id',$organization_id)
		->get();
		
		return view('hrm.holidays',compact('holidays'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$holiday_types = HrmHolidayType::where('organization_id',$organization_id)->pluck('name','id');
		$holiday_types->prepend('Select Holiday Type','');

		return view('hrm.holidays_create',compact('holiday_types'));
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
			'holiday_date'=> 'required'
		]);

		$organization_id = Session::get('organization_id');	

		$holiday = new HrmHoliday;       
		$holiday->name = $request->input('name');

		$holiday->holiday_date = ($request->input('holiday_date')!=null) ? Carbon::parse($request->input('holiday_date'))->format('Y-m-d') : null;
		$holiday->display_name = $request->input('name');
		if($request->input('continue_status') != null)
		{
			$holiday->continue_status = $request->input('continue_status');
		}        
		$holiday->holiday_type_id = $request->input('holiday_type_id');
		$holiday->description = $request->input('description');
		$holiday->organization_id = $organization_id;
		$holiday->save();

		Custom::userby($holiday, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Holiday'.config('constants.flash.added'), 'data' => ['id' => $holiday->id, 'name' => $holiday->name, 'holiday_date' => $request->input('holiday_date'),'status' => $holiday->status,'description' => ($holiday->description != null) ? $holiday->description : ""]]);
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

		$holiday_types = HrmHolidayType::where('organization_id',$organization_id)->pluck('name','id');
		$holiday_types->prepend('Select Holiday Type','');

	   $holidays = HrmHoliday::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$holidays) abort(403);

		return view('hrm.holidays_edit',compact('holidays','holiday_types'));
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
			'holiday_date'=> 'required'
		]);

		//return $request->all();
		$organization_id = Session::get('organization_id');
		

		$holiday =  HrmHoliday::findOrFail($request->input('id'));

		$holiday->name = $request->input('name');
		$holiday->holiday_date = ($request->input('holiday_date')!=null) ? Carbon::parse($request->input('holiday_date'))->format('Y-m-d') : null;
		$holiday->display_name = $request->input('name');

		if($request->input('continue_status') != null)
		{
			$holiday->continue_status = $request->input('continue_status');
		}else{
			$holiday->continue_status = 0;
		}       
		$holiday->holiday_type_id = $request->input('holiday_type_id');
		$holiday->description = $request->input('description');
		$holiday->organization_id = $organization_id;
		$holiday->save();

		Custom::userby($holiday, false);
	   

		return response()->json(['status' => 1, 'message' => 'Holiday'.config('constants.flash.updated'), 'data' => ['id' => $holiday->id, 'name' => $holiday->name, 'holiday_date' => $request->input('holiday_date'),'status' => $holiday->status, 'description' => ($holiday->description != null) ? $holiday->description : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$holidays = HrmHoliday::findOrFail($request->id);
		$holidays->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Holidays'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
{
	$holidays = explode(',', $request->id);

	$holiday_list = [];

	foreach ($holidays as $holiday_id) {
		$holiday = HrmHoliday::findOrFail($holiday_id);
		$holiday->delete();
		$holiday_list[] = $holiday_id;
		Custom::delete_addon('records');
	}

	return response()->json(['status'=>1, 'message'=>'Holiday'.config('constants.flash.deleted'),'data'=>['list' => $holiday_list]]);
}

	public function multiapprove(Request $request)
	{
		$holidays = explode(',', $request->id);

		$holiday_list = [];

		foreach ($holidays as $holiday_id) {
			HrmHoliday::where('id', $holiday_id)->update(['status' => $request->input('status')]);;
			$holiday_list[] = $holiday_id;
		}

		return response()->json(['status'=>1, 'message'=>'Holiday'.config('constants.flash.updated'),'data'=>['list' => $holiday_list]]);
	}

	public function holidays_status_approval(Request $request)
	{
		HrmHoliday::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Holiday'.config('constants.flash.updated'),'data'=>[]]);
	}
}
