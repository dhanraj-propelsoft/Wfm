<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;
use Session;
use Validator;
use App\HrmEmployee;
use App\HrmBreak;
use App\BusinessNature;
use App\BusinessProfessionalism;
use App\State;
use App\Custom;
use DateTime;
use DB;


class BreakController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$breaks = HrmBreak::select('hrm_breaks.id', 'hrm_breaks.name', DB::raw('DATE_FORMAT(hrm_breaks.start_time,"%h:%i %p") AS start_time'), DB::raw('DATE_FORMAT(hrm_breaks.end_time,"%h:%i %p") AS end_time'))->where('hrm_breaks.organization_id', $organization_id)->paginate(10);

		return view('hrm.breaks', compact('breaks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('hrm.breaks_create');
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
			'start_time' => 'required',
			'end_time' => 'required'   
		]);

		//return $request->all();
		
		$organization_id = Session::get('organization_id');

		$start_time = new DateTime($request->input('start_time'));
		$end_time = new DateTime($request->input('end_time'));

		$breaks = new HrmBreak;
		$breaks->name = $request->input('name');        
		$breaks->start_time = $start_time->format('H:i:s');
		$breaks->end_time= $end_time->format('H:i:s');    
		$breaks->organization_id = $organization_id;
		$breaks->save();
		Custom::userby($breaks, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Breaks'.config('constants.flash.added'), 'data' => ['id' => $breaks->id, 'name' => $breaks->name, 'start_time' => $start_time->format('h:i A'), 'end_time' => $end_time->format('h:i A')]]);
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

		$breaks = HrmBreak::where('organization_id',$organization_id)->where('id',$id)
		->first();

		if(!$breaks) abort(403);

		return view('hrm.breaks_edit',compact('breaks'));
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
			'start_time' => 'required',
			'end_time' => 'required'   
		]);

		//return $request->all();       
		
		$organization_id = Session::get('organization_id');
		$start_time = new DateTime($request->input('start_time'));
		$end_time = new DateTime($request->input('end_time'));

		$breaks =  HrmBreak::findOrFail($request->input('id'));
		$breaks->name = $request->input('name');        
		$breaks->start_time = $start_time->format('H:i:s');
		$breaks->end_time= $end_time->format('H:i:s');     
		$breaks->save();

		Custom::userby($breaks, false);
		

		return response()->json(['status' => 1, 'message' => 'Breaks'.config('constants.flash.added'), 'data' => ['id' => $breaks->id, 'name' => $breaks->name, 'start_time' => $start_time->format('h:i A'), 'end_time' => $end_time->format('h:i A')]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$breaks = HrmBreak::findOrFail($request->id);
		$breaks->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Breaks'.config('constants.flash.deleted'),'data'=>[]]);
	}
	public function multidestroy(Request $request)
	{
		$breaks = explode(',', $request->id);

		$break_list = [];

		foreach ($breaks as $break_id) {
			$break = HrmBreak::findOrFail($break_id);
			$break->delete();
			$break_list[] = $break_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Break'.config('constants.flash.deleted'),'data'=>['list' => $break_list]]);
	}   
}
