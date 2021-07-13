<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryStore;
use App\InventoryRack;
use App\BusinessAddressType;
use App\BusinessCommunicationAddress;
use App\Organization;
use App\Custom;
use Validator;
use App\Role;
use Session;


class RackController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		
		$racks = InventoryRack::select('inventory_racks.*','inventory_stores.name AS store_name','business_communication_addresses.placename AS warehouse_name')
		->leftjoin('business_communication_addresses', 'business_communication_addresses.id','=','inventory_racks.warehouse_id')
		->leftJoin('inventory_stores','inventory_racks.store_id','=','inventory_stores.id')
		->where('inventory_racks.organization_id',$organization_id)->paginate(10);

		return view('inventory.rack', compact('racks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$store_name = InventoryStore::where('organization_id', $organization_id)->where('status', '1')->pluck('name', 'id');
		$store_name->prepend('Choose Store Name', '');


		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);
		$organizations = $organization->first();
		$b_id = $organizations->business_id;
		
		$address_type= BusinessAddressType::where('name', 'warehouse')->first()->id;
		
		$warehouse_name = BusinessCommunicationAddress::where('address_type', $address_type)->where('business_id', $b_id)
			->where('status', '1')->pluck('placename', 'id');
		$warehouse_name->prepend('Choose Warehouse Name', '');

		return view('inventory.rack_create', compact('store_name','warehouse_name'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function check_rack_name(Request $request) {
		//dd($request->all());
		$organization_id = Session::get('organization_id');
		$rack = InventoryRack::where('name', $request->name)
				->where('organization_id', $organization_id)
				->where('id','!=', $request->id)->first();
		if(!empty($rack->id)) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	public function store(Request $request)
	{
		//return $request->all();

		$this->validate($request,[
			'name' => 'required',			
		]);

		$organization_id = Session::get('organization_id');

		$warehouse_id = null;
		$store_id = null;

		if($request->input('store_id') != null){
			$store = InventoryStore::find($request->input('store_id'));
			$store_id = $store->id;
				if ($store->warehouse_id != null) {
					$warehouse_id = $store->warehouse_id;
				}			
		}
		
		if($request->input('warehouse_id') != null){
			$warehouse_id = $request->input('warehouse_id');
		}

		$rack = new InventoryRack;	
		$rack->name = $request->input('name');
		$rack->warehouse_id = $warehouse_id;
		$rack->store_id = $store_id;
		$rack->description = $request->input('description');
		$rack->organization_id = $organization_id;
		$rack->save();

		$racks = InventoryRack::select('inventory_racks.*', 'inventory_stores.name AS store_name','business_communication_addresses.placename AS warehouse_name')
		->leftjoin('business_communication_addresses', 'business_communication_addresses.id','=','inventory_racks.warehouse_id')
		->leftJoin('inventory_stores','inventory_racks.store_id','=','inventory_stores.id')
		->where('inventory_racks.id', $rack->id)
		->where('inventory_racks.organization_id',$organization_id)
		->first();


		Custom::userby($rack, true);
		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Rack'.config('constants.flash.added'), 'data' => ['id' => $racks->id, 'name' => $racks->name,'warehouse' => ($racks->warehouse_name != null) ? $racks->warehouse_name : "" , 'store' => ($racks->store_name != null) ? $racks->store_name : "", 'description' => ($racks->description != null) ? $racks->description : ""]]);
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

		//$rack = InventoryRack::where('id', $id)->first();

		$racks = InventoryRack::where('id', $id)->first();
		
		if(!$racks) abort(403);

		$store_name = InventoryStore::where('organization_id', $organization_id)->where('status', '1')->pluck('name', 'id');
		$store_name->prepend('Choose Store Name', '');

		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);
		$organizations = $organization->first();
		$b_id = $organizations->business_id;
		
		$address_type= BusinessAddressType::where('name', 'warehouse')->first()->id;
		
		$warehouse_name = BusinessCommunicationAddress::where('address_type', $address_type)->where('business_id', $b_id)
							->where('status', '1')->pluck('placename', 'id');
		$warehouse_name->prepend('Choose Warehouse Name', '');
		
		return view('inventory.rack_edit',compact('racks', 'store_name','warehouse_name'));
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
		$organization_id = Session::get('organization_id');

		$this->validate($request,[
			'name' => 'required',
		]);

		$warehouse_id = null;
		$store_id = null;

		if($request->input('store_id') != null){
			$store = InventoryStore::find($request->input('store_id'));
			$store_id = $store->id;
			if ($store->warehouse_id != null) {
				$warehouse_id = $store->warehouse_id;
			}
		}
		
		if($request->input('warehouse_id') != null){
			$warehouse_id = $request->input('warehouse_id');
		}
		
		$rack = InventoryRack::findorFail($request->input('id'));
		$rack->name = $request->input('name');
		$rack->warehouse_id = $warehouse_id;
		$rack->store_id = $store_id;
		$rack->description = $request->input('description');
		$rack->save();

		$racks = InventoryRack::select('inventory_racks.*', 'inventory_stores.name AS store_name','business_communication_addresses.placename AS warehouse_name')
		->leftjoin('business_communication_addresses', 'business_communication_addresses.id','=','inventory_racks.warehouse_id')
		->leftJoin('inventory_stores','inventory_racks.store_id','=','inventory_stores.id')
		->where('inventory_racks.id', $rack->id)
		->where('inventory_racks.organization_id',$organization_id)
		->first();

		Custom::userby($rack, false);

		return response()->json(['status' => 1, 'message' => 'Rack'.config('constants.flash.updated'), 'data' => ['id' => $racks->id, 'name' => $racks->name,'warehouse' => ($racks->warehouse_name != null) ? $racks->warehouse_name : "" , 'store' => ($racks->store_name != null) ? $racks->store_name : "", 'description' => ($racks->description != null) ? $racks->description : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$rack = InventoryRack::findOrFail($request->input('id'));
		$rack->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Rack'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$racks = explode(',', $request->id);

		$rack_list = [];

		foreach ($racks as $rack_id) {
			$rack = InventoryRack::findOrFail($rack_id);
			$rack->delete();
			$rack_list[] = $rack_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Rack'.config('constants.flash.deleted'),'data'=>['list' => $rack_list]]);
	}

	public function rack_status_approval(Request $request)
	{	

		InventoryRack::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Rack'.config('constants.flash.updated'),'data'=>[]]);
	}
	public function multiapprove(Request $request)
	{
		$racks = explode(',', $request->id);
		$rack_list = [];

		foreach ($racks as $rack_id) {
			InventoryRack::where('id', $rack_id)->update(['status' => $request->input('status')]);
			$rack_list[] = $rack_id;
		}

		return response()->json(['status'=>1, 'message'=>'Rack'.config('constants.flash.updated'),'data'=>['list' => $rack_list]]);
	}
}
