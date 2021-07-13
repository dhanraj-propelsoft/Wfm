<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TradeLeadSource;
use Session;
use Response;
use Validator;
use App\Custom;

class LeadSourceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$sources = TradeLeadSource::where('organization_id', $organization_id)->get();

		return view('trade.lead_source', compact('sources'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('trade.lead_source_create');
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

		$leadsource = new TradeLeadSource();
		$leadsource->name = $request->input('name');
		$leadsource->organization_id = Session::get('organization_id');
		$leadsource->display_name = $request->input('display_name');
		$leadsource->description = $request->input('description');
		$leadsource->save();

		Custom::userby($leadsource, true);

		return response()->json(['status' => 1, 'message' => 'Lead Source'.config('constants.flash.added'), 'data' => ['id' => $leadsource->id, 'name' => $leadsource->name, 'display_name' => $leadsource->display_name, 'description' => ($leadsource->description != null) ? $leadsource->description : ""]]);
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
		 
		$lead_source = TradeLeadSource::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$lead_source) abort(403);

		return view('trade.lead_source_edit',compact('lead_source'));
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

		$leadsource = TradeLeadSource::findOrFail($request->input('id'));
		$leadsource->name = $request->input('name');
		$leadsource->organization_id = Session::get('organization_id');
		$leadsource->display_name = $request->input('display_name');
		$leadsource->description = $request->input('description');
		$leadsource->save();

		Custom::userby($leadsource, false);

		return response()->json(['status' => 1, 'message' => 'Lead Source'.config('constants.flash.updated'), 'data' => ['id' => $leadsource->id, 'name' => $leadsource->name, 'display_name' => $leadsource->display_name, 'description' => ($leadsource->description != null) ? $leadsource->description : "", 'status' => $leadsource->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$lead_source = TradeLeadSource::findOrFail($request->id);
		$lead_source->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Lead Source'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function status(Request $request)
	{
		TradeLeadSource::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$lead_source = explode(',', $request->id);
		$lead_source_list = [];

		foreach ($lead_source as $lead_source_id) {
			$leadsource = TradeLeadSource::findOrFail($lead_source_id);
			$leadsource->delete();
			$lead_source_list[] = $lead_source_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Lead Source'.config('constants.flash.deleted'),'data'=>['list' => $lead_source_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$lead_source = explode(',', $request->id);
		$lead_source_list = [];

		foreach ($lead_source as $lead_source_id) {
			TradeLeadSource::where('id', $lead_source_id)->update(['status' => $request->input('status')]);;
			$lead_source_list[] = $lead_source_id;
		}

		return response()->json(['status'=>1, 'message'=>'Lead Source'.config('constants.flash.updated'),'data'=>['list' => $lead_source_list]]);
	}
}
