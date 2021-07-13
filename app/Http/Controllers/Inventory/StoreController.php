<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use App\BusinessAddressType;
use App\InventoryStore;
use App\InventoryRack;
use App\Organization;
use App\Custom;
use Validator;
use Session;

class StoreController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');		    
			
		$store = InventoryStore::select('inventory_stores.id', 'inventory_stores.name', 'inventory_stores.warehouse_id', 'inventory_stores.description', 'inventory_stores.status', 'business_communication_addresses.placename');
		$store->leftjoin('business_communication_addresses', 'business_communication_addresses.id','=','inventory_stores.warehouse_id');
		//$store->where('business_communication_addresses.address_type', $address_type);
		//$store->where('business_communication_addresses.business_id', $b_id);
		$store->where('inventory_stores.organization_id', $organization_id);

		$stores = $store->paginate(10);
		return view('inventory.stores', compact('stores'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);
		$organizations = $organization->first();
		$b_id = $organizations->business_id;
		
		$address_type= BusinessAddressType::where('name', 'warehouse')->first()->id;
		
		$store_name = BusinessCommunicationAddress::where('address_type', $address_type)->where('business_id', $b_id)
			->where('status', '1')->pluck('placename', 'id');
		$store_name->prepend('Choose Warehouse Name', '');
		//dd($store_name);
		return view('inventory.store_create', compact('store_name'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function check_store_name(Request $request) {
		//dd($request->all());
		$organization_id = Session::get('organization_id');
		$store = InventoryStore::where('name', $request->name)->where('organization_id', $organization_id)
				->where('id','!=', $request->id)->first();
		if(!empty($store->id)) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	public function store(Request $request)
	{
		$this->validate($request,[
			'name' => 'required',
			]);
		$organization_id = Session::get('organization_id');

		$store = new InventoryStore;
		$store->name = $request->input('name');
		if($request->input('warehouse_id')){
			$store->warehouse_id = $request->input('warehouse_id');
		}
		$store->description = $request->input('description');
		$store->organization_id = $organization_id;    
		$store->save();		

		if($store->warehouse_id != null){
			$warehouse_name = BusinessCommunicationAddress::findorFail($store->warehouse_id)->placename;
		}else{
			$warehouse_name = "";
		}

		Custom::userby($store,true);
		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Store'.config('constants.flash.added'), 'data' => ['id' => $store->id, 'name' => $store->name, 'warehouse_id' => $warehouse_name, 'description' => ($store->description != null) ? $store->description : ""]]);
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

		$store = InventoryStore::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$store) abort(403);
		
		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);
		$organizations = $organization->first();
		$b_id = $organizations->business_id;
		
		$address_type= BusinessAddressType::where('name', 'warehouse')->first()->id;

		$warehouse_name = BusinessCommunicationAddress::where('address_type', $address_type)
		->where('business_id', $b_id)->where('status', '1')
		->pluck('placename', 'id');

		$warehouse_name->prepend('Choose Warehouse Name', '');

		return view('inventory.store_edit', compact('store', 'warehouse_name'));
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
			'name' => 'required',
			
			]);
		
		$store = InventoryStore::findorFail($request->input('id'));
		$store->name = $request->input('name');
		$store->warehouse_id = $request->input('warehouse_id');
		$store->description = $request->input('description'); 
		$store->save();
				
		if($store->warehouse_id != null){
			$warehouse_name = BusinessCommunicationAddress::findorFail($store->warehouse_id)->placename;
		}else{
			$warehouse_name = "";
		}

		Custom::userby($store, false);

		return response()->json(['status' => 1, 'message' => 'Store'.config('constants.flash.updated'), 'data' => ['id' => $store->id, 'name' => $store->name, 'warehouse_id' => $warehouse_name, 'description' => $store->description]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$rack_id = InventoryRack::where('store_id', $request->id)->first();
		//dd($rack_id);
		if($rack_id != null)
		{
			return response()->json(['status' => 0, 'message' => 'This Store is Used on Racks.', 'data' => []]);
		}
		else {
			$store = InventoryStore::findOrFail($request->input('id'));
			$store->delete();
			Custom::delete_addon('records');

			return response()->json(['status' => 1, 'message' => 'Store'.config('constants.flash.deleted'), 'data' => []]);
		}
	}

	public function store_status_approval(Request $request)
	{
		InventoryStore::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}
}
