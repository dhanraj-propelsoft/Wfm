<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShipmentMode;
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
class ShipmentModeController extends Controller
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

		$shipment_modes = ShipmentMode::where('organization_id', $organization_id)->paginate(10);
		return view('inventory.shipment_mode', compact('shipment_modes','state','city','title','payment','terms','group_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('inventory.shipment_mode_create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
			'name' => 'required'
			]);
		$organization_id = Session::get('organization_id');

		$shipment = new ShipmentMode;
		$shipment->name = $request->input('name');
		$shipment->description = $request->input('description');       
		$shipment->organization_id = $organization_id;       
		$shipment->save();

		Custom::userby($shipment,true);
		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Shipment Mode'.config('constants.flash.added'), 'data' => ['id' => $shipment->id, 'name' => $shipment->name, 'description' => ($shipment->description != null) ? $shipment->description : ""]]);
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

		$shipment = ShipmentMode::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$shipment) abort(403);

		return view('inventory.shipment_mode_edit',compact('shipment'));
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
		$this->validate($request,[
			'name' => 'required'
		]);
		
		$shipment = ShipmentMode::findorFail($request->input('id'));
		$shipment->name = $request->input('name');
		$shipment->description = $request->input('description');
		$shipment->save();

		Custom::userby($shipment,false);

		return response()->json(['status' => 1, 'message' => 'Shipment Mode'.config('constants.flash.updated'), 'data' => ['id' => $shipment->id, 'name' => $shipment->name, 'description' => ($shipment->description != null) ? $shipment->description : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$shipment = ShipmentMode::findOrFail($request->input('id'));
		$shipment->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Shipment Mode'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function shipment_status_approval(Request $request)
	{
		ShipmentMode::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}
}
