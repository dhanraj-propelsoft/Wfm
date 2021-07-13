<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmAttendanceType;
use App\HrmHolidayType;
use Carbon\Carbon;
use App\Custom;
use App\State;
use Response;
use Session;
use Auth;
use DB;

class HolidayTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$holiday_types = HrmHolidayType::where('organization_id',$organization_id)
		->paginate(10);

		return view('hrm.holiday_types',compact('holiday_types'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('hrm.holiday_types_create');
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
			'code'=> 'required'
		]);

		//return $request->all();
		$organization_id = Session::get('organization_id');

		$holiday_types = new HrmHolidayType;       
		$holiday_types->name = $request->input('name');
		$holiday_types->code = $request->input('code');
		$holiday_types->pay_status = $request->input('pay_status');
		$holiday_types->description = $request->input('description');
		$holiday_types->organization_id = $organization_id;
		$holiday_types->save();

		if($holiday_types)
		{
			$attendance_type = new HrmAttendanceType;
			$attendance_type->name = $request->input('name');
			$attendance_type->display_name = $request->input('name');
			$attendance_type->color = $request->input('color');
			$attendance_type->paid_status = $request->input('pay_status');
			$attendance_type->description = $request->input('description');
			$attendance_type->delete_status = 1;
			$attendance_type->organization_id = $organization_id;
			$attendance_type->save();

			Custom::userby($attendance_type, true);
			Custom::add_addon('records');
		}

		Custom::userby($holiday_types, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Holiday Types'.config('constants.flash.added'), 'data' => ['id' => $holiday_types->id, 'name' => $holiday_types->name, 'code' => $holiday_types->code, 'status'=> $holiday_types->status]]);
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

		$holiday_types = HrmHolidayType::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$holiday_types) abort(403);

		return view('hrm.holiday_types_edit',compact('holiday_types'));
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
			'code'=> 'required'
		]);

		//return $request->all();
		$organization_id = Session::get('organization_id');

		$holiday_types =  HrmHolidayType::findOrFail($request->input('id'));  
		$holiday_types->name = $request->input('name');
		$holiday_types->code = $request->input('code');
		$holiday_types->pay_status = $request->input('pay_status');
		$holiday_types->description = $request->input('description');  
		$holiday_types->save();

		Custom::userby($holiday_types, false);

		return response()->json(['status' => 1, 'message' => 'Holiday Types'.config('constants.flash.updated'), 'data' => ['id' => $holiday_types->id, 'name' => $holiday_types->name, 'code' => $holiday_types->code,'status'=> $holiday_types->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$holiday_types = HrmHolidayType::findOrFail($request->id);
		$holiday_types->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Holiday Types'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$holiday_types = explode(',', $request->id);

		$holidaytype_list = [];

		foreach ($holiday_types as $holiday_type_id) {
			$holiday_type = HrmHolidayType::findOrFail($holiday_type_id);
			$holiday_type->delete();
			$holidaytype_list[] = $holiday_type_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Holiday Type'.config('constants.flash.deleted'),'data'=>['list' => $holidaytype_list]]);
	}

	public function multiapprove(Request $request)
	{
		$holiday_types = explode(',', $request->id);

		$holidaytype_list = [];

		foreach ($holiday_types as $holiday_type_id) {
			HrmHolidayType::where('id', $holiday_type_id)->update(['status' => $request->input('status')]);;
			$holidaytype_list[] = $holiday_type_id;
		}

		return response()->json(['status'=>1, 'message'=>'Holiday Type'.config('constants.flash.updated'),'data'=>['list' => $holidaytype_list]]);
	}

	public function holidaytype_status_approval(Request $request)
	{
		HrmHolidayType::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Holiday Type'.config('constants.flash.updated'),'data'=>[]]);
	}
}
