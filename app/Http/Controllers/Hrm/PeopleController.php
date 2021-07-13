<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use App\PeopleTitle;
use App\PeopleAddress;
use App\AccountLedger;
use App\AccountLedgerType;
use App\AccountLedgerCreditInfo;
use App\AccountGroup;
use App\Country;
use App\Organization;
use App\People;
use App\Custom;
use App\State;
use App\City;
use App\Term;
use Validator;
use Session;
use DB;

class PeopleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$peoples = People::select('people.*','people.id','people.display_name','people.mobile_no','people.status')        	
		->where('people.organization_id', $organization_id)
		->paginate(10);

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');


		$terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
		$terms->prepend('Select Term','');

		return view('hrm.people', compact('peoples','state', 'title', 'payment', 'terms'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
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

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$peoples = People::select('people.*', 'billing_address.city_id AS billing_city_id','billing_address.address as billing_address','billing_address.pin as billing_pin','billing_address.google as billing_google','shipping_address.city_id AS shipping_city_id','shipping_address.address as shipping_address','shipping_address.pin as shipping_pin', 'shipping_address.google as shipping_google','billing_address.id AS billing_id', 'shipping_address.id AS shipping_id','cities.name as city_name','cities.state_id','states.name as state_name',DB::raw('IF(account_ledger_credit_infos.max_credit_limit IS NULL, business_ledger_credit_infos.max_credit_limit, account_ledger_credit_infos.max_credit_limit) AS max_credit_limit'),'people.group_id','customer_gropings.display_name as group_name')
			->leftJoin('people_addresses AS billing_address', function($join)
        	{
            	$join->on('billing_address.people_id', '=', 'people.id')
            	->where('billing_address.address_type', '0');
        	})
			->leftJoin('people_addresses AS shipping_address', function($join)
        	{
            	$join->on('shipping_address.people_id', '=', 'people.id')
            	->where('shipping_address.address_type', '1');
        	})

        	->leftJoin('account_ledgers', function($join) use($organization_id)
        	{
            	$join->on('people.person_id', '=', 'account_ledgers.person_id')
            	->where('account_ledgers.organization_id', $organization_id);
        	})

        	->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
        	{
            	$join->on('people.business_id', '=', 'business_ledgers.business_id')
            	->where('business_ledgers.organization_id', $organization_id);
        	})

        	->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id')
        	->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id')
        	->leftjoin('cities','billing_address.city_id','=','cities.id')
	   		->leftjoin('states','cities.state_id','=','states.id')
	   		->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')
        	->where('people.organization_id',$organization_id)
        	->where('people.id',$id)
        	->groupby('people.id')->first();

        	$selected_billing_state = null;
        	$selected_shipping_state = null;
        	$billing_city = [];
        	$shipping_city =[];

	   if(!empty($peoples->billing_city_id)) {

			$selected_billing_city = City::where('id', $peoples->billing_city_id)->first();
			$selected_billing_state = State::select('id')->where('id', $selected_billing_city->state_id)->first()->id;
			
			$billing_city  = City::select('id', 'name')->where('state_id', $selected_billing_state)->get();
		}

		if(!empty($peoples->shipping_city_id)) {	

			$selected_shipping_city = City::where('id', $peoples->shipping_city_id)->first();
			$selected_shipping_state = State::select('id')->where('id', $selected_shipping_city->state_id)->first()->id;
					
			$shipping_city = City::select('id', 'name')->where('state_id', $selected_shipping_state)->get();

		}else{
				$selected_shipping_state = $selected_billing_state;
				$shipping_city = $billing_city;
		}

		return response()->json(['result' => $peoples, 'billing_state' => $selected_billing_state, 'billing_city' => $billing_city , 'shipping_state' => $selected_shipping_state, 'shipping_city' => $shipping_city]);
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
			'first_name' => 'required',
		]);

		

		$people = People::findOrFail($request->input('id'));
		$people->title_id = $request->input('title_id');
		$people->first_name = $request->input('first_name');
		$people->last_name = $request->input('last_name');
		$people->display_name = $request->input('display_name');
		$people->mobile_no = $request->input('mobile_no');
		$people->email_address = $request->input('email');
		$people->payment_mode_id = $request->input('payment_mode_id');
		$people->term_id = $request->input('term_id');
		$people->phone = $request->input('phone');
		$people->pan_no = $request->input('pan_no');
		$people->gst_no = $request->input('gst_no');
		$people->group_id = $request->input('group_id');
		$people->save();
  
		$account_ledgers = AccountLedger::select('account_ledgers.id');

		if($people->person_id != null ) {

			$account_ledgers->where('person_id', $people->person_id);
		} else {
			$account_ledgers->where('business_id', $people->business_id);
		}

		$account_ledgers->where('organization_id', Session::get('organization_id'));
		$account_ledger = $account_ledgers->first();

		if($account_ledger == null) {

			$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
			$organization = Organization::findOrFail(Session::get('organization_id'));

			$ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', Session::get('organization_id'))->first();

			$ledger =  Custom::create_ledger($people->display_name, $organization, $people->display_name, $personal_ledger->id,$people->person_id, $people->business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', '1', '1', Session::get('organization_id'), false);
		} else {
			$ledger = $account_ledger->id;
		}

		//People Ledger
		//$ledger

		$credit_limit = AccountLedgerCreditInfo::findOrFail($ledger);

		$credit_limit->max_credit_limit = $request->input('credit_limit');

		$credit_limit->save();	

		

		$billing = PeopleAddress::where('id',$request->input('billing_id'))->first();
		if($billing != null) {
			$billing_address = $billing;
		} else {
			$billing_address = new PeopleAddress();
			$billing_address->address_type = 0;
			$billing_address->people_id = $people->id;
		}
			
		$billing_address->address = $request->input('billing_address');
		$billing_address->city_id = $request->input('billing_city_id');
		$billing_address->google = $request->input('billing_google');
		$billing_address->pin = $request->input('billing_pin');
		$billing_address->save();


	$shipping = PeopleAddress::where('id',$request->input('shipping_id'))->first();

	if($request->input('shipping_address') && $request->input('shipping_city_id'))
	{
		if($shipping != null) {
			$shipping_address = $shipping;
		} else {
			$shipping_address = new PeopleAddress();
			$shipping_address->address_type = 1;
			$shipping_address->people_id = $people->id;
		}

			$shipping_address->address = $request->input('shipping_address');
			$shipping_address->city_id = $request->input('shipping_city_id');
			$shipping_address->google = $request->input('shipping_google');
			$shipping_address->pin = $request->input('shipping_pin');
			$shipping_address->save();
	}
	else{
		$address_delete = PeopleAddress::where('people_id',$people->id)->where('address_type',1)->first();
		if($address_delete != null)
		{
			$address_delete->delete();
		}
		$shipping_address = $billing_address;
		
	}		

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.updated'), 'data' => ['id' => $people->id, 'first_name' => $people->first_name, 'last_name' => $people->last_name, 'display_name' => $people->display_name,'mobile_no' => $people->mobile_no,'email_address' => $people->email_address,'phone' => $people->phone,'pan_no' => $people->pan_no,'gst_no' => $people->gst_no,'status' =>$people->status,'billing_address' => $billing_address->address, 'address_type' => $billing_address->address_type , 'billing_city_id' => $billing_address->city_id, 'billing_google'=> $billing_address->google,'billing_pin' => $billing_address->pin,'shipping_address'=> $billing_address->address,'address_type' => $shipping_address->address_type,'shipping_city_id' => $shipping_address->city_id,'shipping_google' => $shipping_address->google,'shipping_pin'=>$shipping_address->pin,'billing_id' => $request->input('billing_id') , 'shipping_id' => $request->input('shipping_id'),'credit_limit' => $credit_limit->max_credit_limit ]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$people = People::findOrFail($request->input('id'));
		$people->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$peoples = explode(',', $request->id);

		$people_list = [];

		foreach ($peoples as $people_id) {
			$people = People::findOrFail($people_id);
			$people->delete();
			$people_list[] = $people_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.deleted'),'data'=>['list' => $people_list]]);
	}

	public function multiapprove(Request $request)
	{
		$peoples = explode(',', $request->id);

		$people_list = [];

		foreach ($peoples as $people_id) {
			People::where('id', $people_id)->update(['status' => $request->input('status')]);
			$people_list[] = $people_id;
		}

		return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.updated'),'data'=>['list' => $people_list]]);
	}

	public function people_status_approval(Request $request)
	{
		People::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.updated'),'data'=>[]]);
	}
}
