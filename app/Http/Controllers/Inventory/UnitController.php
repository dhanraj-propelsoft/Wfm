<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Unit;
use App\Custom;
use Validator;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;

class UnitController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');
		$units = Unit::where('organization_id', $organization_id)->paginate(10);

		return view('inventory.unit', compact('units','state','city','title','payment','terms','group_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('inventory.unit_create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function check_unit_name(Request $request) {
		//dd($request->all());
		$organization_id = Session::get('organization_id');		
		$unit = Unit::where('display_name', $request->display_name)->where('organization_id', $organization_id)
				->where('id','!=', $request->id)->first();
		if(!empty($unit->id)) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'display_name' => 'required',        
		]);

		$organization_id = Session::get('organization_id');

		$unit = new Unit;
		$unit->name = $request->input('name');
		$unit->display_name = $request->input('display_name');
		$unit->description = $request->input('description');
		$unit->organization_id = $organization_id;
		$unit->save();

		Custom::userby($unit, true);
		Custom::add_addon('records');
	   
		return response()->json(['status' => 1, 'message' => 'Unit'.config('constants.flash.added'), 'data' => ['id' => $unit->id, 'name' => $unit->name, 'display_name' => $unit->display_name, 'description' => ($unit->description != null) ? $unit->description : ""]]);
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

		$unit = Unit::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$unit) abort(403);

		return view('inventory.unit_edit', compact('unit'));
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

		$unit = Unit::findOrFail($request->input('id'));
		$unit->name = $request->input('name');
		$unit->display_name = $request->input('display_name');
		$unit->description = $request->input('description');        
		$unit->save();

		Custom::userby($unit, false);
	   
		return response()->json(['status' => 1, 'message' => 'Unit'.config('constants.flash.updated'), 'data' => ['id' => $unit->id, 'name' => $unit->name, 'display_name' => $unit->display_name, 'description' => ($unit->description != null) ? $unit->description : "", 'status' => $unit->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$unit = Unit::findOrFail($request->input('id'));
		$unit->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Unit'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function unit_status_approval(Request $request)
	{
		Unit::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}

	public function multidestroy(Request $request)
	{
		$units = explode(',', $request->id);
		$unit_list = [];

		foreach ($units as $unit_id) {
			$unit_delete = Unit::findOrFail($unit_id);
			$unit_delete->delete();
			$unit_list[] = $unit_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Units'.config('constants.flash.deleted'),'data'=>['list' => $unit_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$units = explode(',', $request->id);
		$unit_list = [];

		foreach ($units as $unit_id) {
			Unit::where('id', $unit_id)->update(['status' => $request->input('status')]);;
			$unit_list[] = $unit_id;
		}

		return response()->json(['status'=>1, 'message'=>'Units'.config('constants.flash.updated'),'data'=>['list' => $unit_list]]);
	}
}
