<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TradeLeadStatus;
use Session;
use Response;
use Validator;
use App\Custom;

class LeadStatusController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$leads = TradeLeadStatus::where('organization_id', $organization_id)->get();

		return view('trade.lead_status', compact('leads'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('trade.lead_status_create');
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
			'display_name' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$leadstatus = new TradeLeadStatus();
		$leadstatus->name = $request->input('name');
		$leadstatus->organization_id = Session::get('organization_id');
		$leadstatus->display_name = $request->input('display_name');
		$leadstatus->description = $request->input('description');
		$leadstatus->save();

		Custom::userby($leadstatus, true);

		return response()->json(['status' => 1, 'message' => 'Lead Status'.config('constants.flash.added'), 'data' => ['id' => $leadstatus->id, 'name' => $leadstatus->name, 'display_name' => $leadstatus->display_name, 'description' => ($leadstatus->description != null) ? $leadstatus->description : ""]]);
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
		 
		$lead_status = TradeLeadStatus::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$lead_status) abort(403);

		return view('trade.lead_status_edit',compact('lead_status'));
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
			'display_name' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$leadstatus = TradeLeadStatus::findOrFail($request->input('id'));
		$leadstatus->name = $request->input('name');
		$leadstatus->organization_id = Session::get('organization_id');
		$leadstatus->display_name = $request->input('display_name');
		$leadstatus->description = $request->input('description');
		$leadstatus->save();

		Custom::userby($leadstatus, false);

		return response()->json(['status' => 1, 'message' => 'Lead Status'.config('constants.flash.updated'), 'data' => ['id' => $leadstatus->id, 'name' => $leadstatus->name, 'display_name' => $leadstatus->display_name, 'description' => ($leadstatus->description != null) ? $leadstatus->description : "", 'status' => $leadstatus->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$lead_status = TradeLeadStatus::findOrFail($request->id);

		$lead_status->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Lead Status'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function status(Request $request)
	{
		TradeLeadStatus::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$lead_status = explode(',', $request->id);
		$lead_status_list = [];

		foreach ($lead_status as $lead_status_id) {
			$leadstatus = TradeLeadStatus::findOrFail($lead_status_id);
			$leadstatus->delete();
			$lead_status_list[] = $lead_status_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Lead Status'.config('constants.flash.deleted'),'data'=>['list' => $lead_status_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$lead_status = explode(',', $request->id);
		$lead_status_list = [];

		foreach ($lead_status as $lead_status_id) {
			TradeLeadStatus::where('id', $lead_status_id)->update(['status' => $request->input('status')]);;
			$lead_status_list[] = $lead_status_id;
		}

		return response()->json(['status'=>1, 'message'=>'Lead Status'.config('constants.flash.updated'),'data'=>['list' => $lead_status_list]]);
	}
}
