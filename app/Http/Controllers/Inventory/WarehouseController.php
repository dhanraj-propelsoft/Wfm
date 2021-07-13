<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use App\BusinessAddressType;
use App\InventoryStore;
use App\PaymentMethod;
use App\InventoryRack;
use App\Organization;
use App\PeopleTitle;
use App\HrmEmployee;
use App\Country;
use App\Custom;
use App\People;
use App\State;
use App\Term;
use App\City;
use Validator;
use Response;
use Session;
use DB;

class WarehouseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);		
		$organizations = $organization->first();
		
		$id = $organizations->business_id;		  

		$address_type_name = BusinessAddressType::where('name', 'warehouse')->first()->id;               

		$warehouses = BusinessCommunicationAddress::select('business_communication_addresses.id','business_communication_addresses.email_address','business_communication_addresses.mobile_no','business_communication_addresses.placename','business_communication_addresses.address','hrm_employees.first_name as contact_person_name','business_address_types.display_name as address_type', 'business_communication_addresses.status')
		->leftJoin('hrm_employees', 'business_communication_addresses.contact_person_id','=', 'hrm_employees.person_id')
		->leftJoin('business_address_types', 'business_communication_addresses.address_type','=', 'business_address_types.id')
		->where('business_communication_addresses.business_id', $id)
		->where('business_communication_addresses.address_type', $address_type_name)
		->groupby('business_communication_addresses.id')
		->get();
		//dd($warehouses);

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');  

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');  

		return view('inventory.warehouse', compact('warehouses', 'id', 'title', 'state', 'payment', 'terms'));
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
		$id = $organizations->business_id;      

		$businessaddresstype = BusinessAddressType::pluck('name', 'id');
		$businessaddresstype->prepend('Select Address Type', '');

		$country_id = Country::where('name', 'India')->first()->id;

        $state =  State::where('country_id', $country_id)->orderBy('name')->pluck('name','id');
		$state->prepend('Select State', '');		

		$city =  City::pluck('name', 'id');
		$city->prepend('Select City', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
		$employees->prepend('Select Employee', '');

		return view('inventory.warehouse_create', compact('businessaddresstype','state','id','add', 'title', 'employees'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function check_warehouse_name(Request $request) {
		//dd($request->all());
		$warehouse = BusinessCommunicationAddress::where('placename', $request->placename)
					->where('business_id', $request->business_id)
					->where('id','!=', $request->id)->first();
		if(!empty($warehouse->id)) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			  'placename'  => 'required',
		]);

		//return $request->all();

		$contact_person_id = HrmEmployee::where('id', $request->input('employee_id'))->first();

		$address_type_name = BusinessAddressType::where('name', 'warehouse')->first();
		//dd($address_type_name);

		$businesscommunicationaddresss = new BusinessCommunicationAddress;
		$businesscommunicationaddresss->address_type = $address_type_name->id;
		$businesscommunicationaddresss->placename = $request->input('placename');     
		$businesscommunicationaddresss->pin = $request->input('pin');
		$businesscommunicationaddresss->landmark = $request->input('landmark');
		$businesscommunicationaddresss->google = $request->input('google');
		$businesscommunicationaddresss->mobile_no = $request->input('mobile_no');
		$businesscommunicationaddresss->mobile_no_prev = $request->input('mobile_no');
		$businesscommunicationaddresss->contact_person_id = ($contact_person_id != null) ? $contact_person_id->person_id : null;
		$businesscommunicationaddresss->phone = $request->input('phone');
		$businesscommunicationaddresss->phone_prev = $request->input('phone');
		$businesscommunicationaddresss->email_address = $request->input('email_address');
		$businesscommunicationaddresss->email_address_prev = $request->input('email_address');
		$businesscommunicationaddresss->web_address = $request->input('web_address');
		if($request->input('city_id') != null){
			$businesscommunicationaddresss->city_id = $request->input('city_id');
		}		
		$businesscommunicationaddresss->web_address_prev = $request->input('web_address');
		$businesscommunicationaddresss->address = $request->input('address');
		$businesscommunicationaddresss->address_prev = $request->input('address');
		$businesscommunicationaddresss->business_id = $request->input('business_id');
		$businesscommunicationaddresss->save();
		Custom::userby($businesscommunicationaddresss, true);
		Custom::add_addon('records');

		$warehouses = BusinessCommunicationAddress::select('business_communication_addresses.id','business_communication_addresses.placename','business_communication_addresses.email_address','business_communication_addresses.mobile_no','business_communication_addresses.address', 'hrm_employees.first_name as contact_person_name','business_address_types.display_name as address_type', 'business_communication_addresses.status')
		->leftJoin('hrm_employees', 'business_communication_addresses.contact_person_id','=', 'hrm_employees.person_id')
		->leftJoin('business_address_types', 'business_communication_addresses.address_type','=', 'business_address_types.id')
		->where('business_communication_addresses.id', $businesscommunicationaddresss->id)
		->first();  


		return response()->json(['status' => 1, 'message' => 'Warehouse'.config('constants.flash.added'), 'data' => ['id' => $warehouses->id,'person'=> ($warehouses->contact_person_name != null) ? $warehouses->contact_person_name : "" , 'placename'=>($warehouses->placename != null) ? $warehouses->placename : "",'mobile_no'=>$warehouses->mobile_no,  'email_address'=> ($warehouses->email_address != null) ? $warehouses->email_address : "", 'address'=> ($warehouses->address != null) ? $warehouses->address : "", 'status' => $warehouses->status ]]);
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
		$organization = Organization::select('id','name','business_id')->where('id', $organization_id);
		$organizations = $organization->first();
		$business_id = $organizations->business_id;

		$businessaddresstype = BusinessAddressType::pluck('name', 'id');
		$businessaddresstype->prepend('Select Address Type', '');

		$country_id = Country::where('name', 'India')->first()->id;

        $state =  State::where('country_id', $country_id)->orderBy('name')->pluck('name','id');
		$state->prepend('Select State', '');

		

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
		$employees->prepend('Select Employee', '');

		$warehouse = BusinessCommunicationAddress::where('id',$id)->first();
	if($warehouse->city_id != null){
		$selected_state =  City::findOrFail($warehouse->city_id)->state_id;
		$city =  City::where('state_id', $selected_state)->pluck('name', 'id');
		$city->prepend('Select City', '');
	}else{
		$selected_state = null;
		$city =  [];
	}
		
		$employee = HrmEmployee::where('person_id', $warehouse->contact_person_id)->first();

		if($employee != null) {
			$selected_employee = $employee->id;
		} else {
			$selected_employee = null;
		}
		

		return view('inventory.warehouse_edit', compact('businessaddresstype','state','city','add', 'title', 'employees','warehouse','business_id','selected_state', 'selected_employee'));
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
			  'placename'  => 'required',
		]);

		//return $request->all();

		$address_type_name = BusinessAddressType::where('name', 'warehouse')->first();
		//dd($address_type_name);

		$contact_person_id = HrmEmployee::where('id', $request->input('employee_id'))->first();

		$businesscommunicationaddresss =  BusinessCommunicationAddress::findorFail($request->input('id'));
		$businesscommunicationaddresss->address_type = $address_type_name->id;
		$businesscommunicationaddresss->placename = $request->input('placename');     
		$businesscommunicationaddresss->pin = $request->input('pin');
		$businesscommunicationaddresss->landmark = $request->input('landmark');
		$businesscommunicationaddresss->google = $request->input('google');
		$businesscommunicationaddresss->mobile_no = $request->input('mobile_no');
		$businesscommunicationaddresss->mobile_no_prev = $request->input('mobile_no');
		$businesscommunicationaddresss->contact_person_id = ($contact_person_id != null) ? $contact_person_id->person_id : null;
		$businesscommunicationaddresss->phone = $request->input('phone');
		$businesscommunicationaddresss->phone_prev = $request->input('phone');
		$businesscommunicationaddresss->email_address = $request->input('email_address');
		$businesscommunicationaddresss->email_address_prev = $request->input('email_address');
		$businesscommunicationaddresss->web_address = $request->input('web_address');
		if($request->input('city_id') != null){
			$businesscommunicationaddresss->city_id = $request->input('city_id');
		}
		$businesscommunicationaddresss->web_address_prev = $request->input('web_address');
		$businesscommunicationaddresss->address = $request->input('address');
		$businesscommunicationaddresss->address_prev = $request->input('address');
		$businesscommunicationaddresss->business_id = $request->input('business_id');
		$businesscommunicationaddresss->save();
		Custom::userby($businesscommunicationaddresss, true);

		$warehouses = BusinessCommunicationAddress::select('business_communication_addresses.id','business_communication_addresses.email_address','business_communication_addresses.placename','business_communication_addresses.mobile_no','business_communication_addresses.address', 'hrm_employees.first_name as contact_person_name','business_address_types.display_name as address_type', 'business_communication_addresses.status')
		->leftJoin('hrm_employees', 'business_communication_addresses.contact_person_id','=', 'hrm_employees.person_id')
		->leftJoin('business_address_types', 'business_communication_addresses.address_type','=', 'business_address_types.id')
		->where('business_communication_addresses.id', $businesscommunicationaddresss->id)
		->first();

		return response()->json(['status' => 1, 'message' => 'Warehouse'.config('constants.flash.updated'), 'data' => ['id' => $warehouses->id, 'person'=> ($warehouses->contact_person_name != null) ? $warehouses->contact_person_name : "", 'placename'=>($warehouses->placename != null) ? $warehouses->placename : "",'mobile_no'=>$warehouses->mobile_no, 'email_address'=> ($warehouses->email_address != null) ? $warehouses->email_address : "", 'address'=> ($warehouses->address != null) ? $warehouses->address : "", 'status' => $warehouses->status ]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		//dd($request->id);
		$store_id = InventoryStore::where('warehouse_id', $request->id)->first();
		$rack_id = InventoryRack::where('warehouse_id', $request->id)->first();
		//dd($store_id);
		if($store_id != null)
		{
			return response()->json(['status' => 0, 'message' => 'This Warehouse is Used on Stores.', 'data' => []]);
		}
		else if($rack_id != null)
		{
			return response()->json(['status' => 0, 'message' => 'This Warehouse is Used on Racks.', 'data' => []]);
		}
		else
		{

			$warehouse = BusinessCommunicationAddress::findOrFail($request->id);

			$warehouse->delete();

			Custom::delete_addon('records');

			return response()->json(['status' => 1, 'message' => 'Warehouse'.config('constants.flash.deleted'), 'data' => []]);
		}
		
	}

	public function status(Request $request)
    {
        BusinessCommunicationAddress::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

        return response()->json(array('result' => "success"));
    }
}
