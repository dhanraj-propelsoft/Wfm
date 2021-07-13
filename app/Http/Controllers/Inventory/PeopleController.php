<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\CustomerGroping;
use App\AccountVoucher;
use App\PaymentMethod;
use App\PeopleTitle;
use App\Transaction;
use App\Country;
use App\Person;
use App\People;
use App\State;
use App\City;
use App\Term;
use Validator;
use Response;
use Session;
use App\Custom;
use App\Business;
use App\BusinessCommunicationAddress;
use App\PersonCommunicationAddress;
use App\BusinessAddressType;
use App\PeopleAddress;
use App\PersonAddressType;
use App\AccountLedger;
use App\AccountLedgerType;
use App\AccountGroup;
use App\AccountLedgerCreditInfo;
use App\Organization;
use DB;
use Illuminate\Support\Facades\Log;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {

        $organization_id = Session::get('organization_id');       

        if($type != 'vendor' && $type != 'customer' && $type != 'wms-customer') abort(403);

        if($type == 'wms-customer') $name = 'customer'; else $name = $type;

        $person_type_id = AccountPersonType::where('name', $name)->first()->id;

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        if($name == 'vendor') {

            $purchases = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;

            $transaction_type = [$purchases];
        } 
        else if($name == 'customer') 
        {            
            $sale = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

            $sale_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

            $transaction_type = [$sale, $sale_cash];
        }

        $sale = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

        $sale_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

        
        $peoples = People::select('people.id', 'people.person_id', 'people.first_name', 'people.display_name','people.mobile_no','people.status', 'people.email_address', 'persons.crm_code', 'businesses.bcrm_code', 
            DB::raw('IF(SUM(transactions.total) IS NULL, SUM(business.total), SUM(transactions.total)) AS total'),
            DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS crm_id'),
            DB::raw('CONCAT(COALESCE(people.first_name, ""), " " ,COALESCE(people.last_name, "")) AS contact_person'), 
            DB::raw('IF(account_ledger_credit_infos.max_credit_limit IS NULL, business_ledger_credit_infos.max_credit_limit, account_ledger_credit_infos.max_credit_limit) AS max_credit_limit'),
            'people.group_id','customer_gropings.display_name as group_name'
        )
        ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
        ->leftJoin('persons', 'persons.id','=','people.person_id')
        ->leftJoin('businesses', 'businesses.id','=','people.business_id')

        ->leftJoin('transactions', function($query) use($transaction_type) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })
        ->leftJoin('transactions AS business', function($query)  use($transaction_type){
                $query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })

        ->leftJoin('account_ledgers', function($join) use($organization_id)
            {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id')

        ->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
            {
                $join->on('people.business_id', '=', 'business_ledgers.business_id')
                ->where('business_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id')
        ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')

        ->where('people.organization_id', $organization_id)
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('people.first_name')
        ->get();
       

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

        return view('inventory.contact', compact('peoples','name', 'title', 'state', 'payment', 'terms', 'type','group_name'));
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

		//dd($organization_id);

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
		Log::info("PeopleController->update :- Inside ".json_encode($request->all()));
	
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

		if($people->person_id != null ) {

			$persons = Person::select('persons.id');
			$persons->where('persons.id', $people->person_id);
			$person = $persons->first();

			$update_person = Person::findOrFail($person->id);
			$update_person->first_name = $people->first_name;
			$update_person->save();
		} 

		if($people->business_id != null ) {

			$businesses = Business::select('businesses.id');
			$businesses->where('businesses.id', $people->business_id);
			$business = $businesses->first();

			$update_business = Business::findOrFail($business->id);
			$update_business->business_name = $people->first_name;
			$update_business->gst = ($people->gst_no != null) ? $people->gst_no : null;
			$update_business->save();
		}

        
		$account_ledgers = AccountLedger::select('account_ledgers.id');	

		if($people->person_id != null ) {

			$account_ledgers->where('person_id', $people->person_id);
		} else {
			$account_ledgers->where('business_id', $people->business_id);
		}

		$account_ledgers->where('organization_id', Session::get('organization_id'));
		$account_ledger = $account_ledgers->first();		
        Log::info("PeopleController->update :- account_ledger ".json_encode($account_ledger));

		if($account_ledger == null) {

			$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
			$organization = Organization::findOrFail(Session::get('organization_id'));

			$ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', Session::get('organization_id'))->first();

			$ledger =  Custom::create_ledger($people->first_name, $organization, $people->display_name, $personal_ledger->id,$people->person_id, $people->business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', '1', '1', Session::get('organization_id'), false);
		} else {

			$update_ledger = AccountLedger::findOrFail($account_ledger->id);

			$update_ledger->name = $people->first_name;
			$update_ledger->display_name = $people->display_name;
			$update_ledger->person_id = $people->person_id;
			$update_ledger->business_id = $people->business_id;
			$update_ledger->save();

			$ledger = $account_ledger->id;
		}

		//People Ledger
		//$ledger
		Log::info("PeopleController->update :- after line 338 ledger_id ".$ledger);

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
		Log::info("PeopleController->update :- return ");

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
        //dd($request->id);
        $people = People::findOrFail($request->id);
        $people->delete();

        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function contact_status_approval(Request $request)
    {
        People::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.updated'),'data'=>[]]);
    }

    public function multidestroy(Request $request)
    {
        $peoples = explode(',', $request->id);

        $contact_list = [];

        foreach ($peoples as $contact_id) {
            $contact = People::findOrFail($contact_id);
            $contact->delete();
            $contact_list[] = $contact_id;
            
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.deleted'),'data'=>['list' => $contact_list]]);
    }

    public function multiapprove(Request $request)
    {
        $peoples = explode(',', $request->id);

        $contact_list = [];

        foreach ($peoples as $contact_id) {
            People::where('id', $contact_id)->update(['status' => $request->input('status')]);
            $contact_list[] = $contact_id;
        }

        return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.updated'),'data'=>['list' => $contact_list]]);
    }

    public function add_group(Request $request){
       
    }

     public function new_customer_data_create($name)
    {
        //dd($name);
        $type= $name;
        $organization_id = Session::get('organization_id');
        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

         $cities = City::pluck('name', 'id');
        $cities->prepend('Select City', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');
        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

        return view('inventory.add_customer_data',compact('country','state','title','payment','terms','group_name','cities','organization_id','type'));
    }

    public function new_customer_data_store(Request $request)
    {
      $inputs=$request->all();
      //dd($inputs);
        $response=self::comman_add_customer($inputs);

     //dd($response);
        $mobile_number = $response['mobile_number'];
        $name = $response['name'];
        $id = $response['id'];
        $credit_limit_value = $response['credit_limit_value'];
        $code = $response['code'];
        //dd($mobile_number);

          return response()->json(['status' => '1' ,'message' => 'Data stored' ,'mobile_no' => $mobile_number,'name' => $name , 'code' => $code ,'credit_limit_value' => $credit_limit_value ,'id' => $id]);
      
      
    }

    public function comman_add_customer($array)
    {
      //dd($array);
      $organization_id = session::get('organization_id');
      $code = '';
      $id ='';
      $mobile_number = $array['mobile_number'];
      $name = $array['first_name'];
      $credit_limit_value = $array['max_credit_limit'];

      $mobile = People::select('people.id','people.person_id','people.business_id')
      ->leftjoin('persons','persons.id','=','people.person_id')
      ->leftjoin('person_communication_addresses','person_communication_addresses.person_id','=','persons.id')
      ->leftJoin('businesses','businesses.id','=','people.business_id')
      ->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id')
      ->where('person_communication_addresses.mobile_no',$mobile_number)
      ->orWhere('business_communication_addresses.mobile_no',$mobile_number)
      ->first();
      //dd($mobile);
      if($mobile != null)
      {
              $people = new People();
              $people->user_type = $array['type'];
              if($mobile->person_id != null)
              {
              $people->person_id = $mobile->person_id;
              $address_type = BusinessAddressType::where('name', 'business')->first();


              }
              if($mobile->business_id != null)
              {
              $people->business_id = $mobile->business_id;
              $address_type = PersonAddressType::where('name', 'residential')->first();

              }
              $people->title_id = ($array['title_id'] != null) ? $array['title_id'] : null ;
              $people->first_name = $array['first_name'];
              $people->last_name = $array['last_name'];
              $people->company = $array['display_name'];
              $people->display_name = $array['display_name'];
              $people->mobile_no = $array['mobile_number'];
              $people->email_address = $array['email_address'];
              $people->phone = $array['phone'];
              $people->gst_no = $array['gst_no'];
              $people->pan_no = $array['pan_no'];
              $people->payment_mode_id = $array['payment_mode_id'];
              $people->term_id = $array['term_id'];
              $people->group_id = $array['group_name'];
              $people->organization_id = Session::get('organization_id');
              $people->save();
      }
      else
      {
        if($array['type'] == 1)
        {
          $city = City::select('name')->where('id', $array['billing_city_id'])->first()->name;

          $bcrm_code = Custom::business_crm($city, $array['mobile_number'], $array['first_name']);
          $code = $bcrm_code;
         
          $business = new Business;
          $business->bcrm_code = $bcrm_code;
          $business->business_name = $array['first_name'];
          $business->alias = $array['first_name'];
          $business->pan = $array['pan_no'];
          $business->gst = $array['gst_no'];
          $business->save();

          $address_type = BusinessAddressType::where('name', 'business')->first();


          if($business->id)
          {

              $business_address = new BusinessCommunicationAddress;
              $business_address->address_type = $address_type->id;
              $business_address->placename = $array['first_name'];
              $business_address->address = $array['billing_address']; 
              $business_address->address_prev = $array['billing_address']; 
              $business_address->city_id = $array['billing_city_id'];
              $business_address->pin = $array['billing_pin'];
              $business_address->google = $array['billing_google'];
              $business_address->mobile_no = $array['mobile_number'];
              $business_address->mobile_no_prev = $array['mobile_number'];
              $business_address->phone = $array['phone'];
              $business_address->phone_prev = $array['phone'];
              $business_address->email_address = $array['email_address'];
              $business_address->email_address_prev = $array['email_address'];
              $business_address->business_id = $business->id;
              $business_address->save();

              $people = new People();
              $people->user_type = $array['type'];
              $people->business_id = $business->id;
              $people->title_id = ($array['title_id'] != null) ? $array['title_id'] : null ;
              $people->first_name = $array['first_name'];
              $people->last_name = $array['last_name'];
              $people->company = $array['display_name'];
              $people->display_name = $array['display_name'];
              $people->mobile_no = $array['mobile_number'];
              $people->email_address = $array['email_address'];
              $people->phone = $array['phone'];
              $people->gst_no = $array['gst_no'];
              $people->pan_no = $array['pan_no'];
              $people->payment_mode_id = $array['payment_mode_id'];
              $people->term_id = $array['term_id'];
              $people->group_id = $array['group_name'];
              $people->organization_id = Session::get('organization_id');
              $people->save();

              $id = $people->id;

             


          }
        }

        if($array['type'] == 0)
        {
          //dd($request->all());
          $city = City::select('name')->where('id', $array['billing_city_id'])->first()->name;

          $crm_code = Custom::personal_crm($city, $array['mobile_number'], $array['first_name']);
          $code =  $crm_code;

          $person=new Person;
          $person->crm_code = $crm_code;
          $person->salutation = $array['title_id'];
          $person->first_name = $array['first_name'];
          $person->last_name = $array['last_name'];
          $person->gst_no = $array['gst_no'];
          $person->pan_no = $array['pan_no'];
          $person->save();
          $address_type = PersonAddressType::where('name', 'residential')->first();
            if($person->id)
            {
              $person_address = new PersonCommunicationAddress;
              $person_address->person_id = $person->id;
              $person_address->address_type = $address_type->id;
              $person_address->city_id = $array['billing_city_id'];
              $person_address->pin = $array['billing_pin'];
              $person_address->google = $array['billing_google'];
              $person_address->mobile_no = $array['mobile_number'];
              $person_address->mobile_no_prev = $array['mobile_number'];
              $person_address->phone = $array['phone'];
              $person_address->phone_prev = $array['phone'];
              $person_address->email_address = $array['email_address'];
              $person_address->email_address_prev = $array['email_address'];
             
              $person_address->address = $array['billing_address'];
              $person_address->address_prev = $array['billing_address'];
              $person_address->save();

              $people_exist = People::where('person_id', $person->id)->where('organization_id', Session::get('organization_id'))->first();

              if($people_exist == null) {
                $people = new People();
                $people->person_id = $person->id;
                $people->title_id = ($array['title_id'] != null) ? $array['title_id'] : null;
                $people->first_name = $array['first_name'];
                $people->last_name = $array['last_name'];
                $people->display_name = $array['display_name'];
                $people->mobile_no = $array['mobile_number'];
                $people->email_address = $array['email_address'];
                $people->phone = $array['phone'];
                $people->gst_no = $array['gst_no'];
                $people->pan_no = $array['pan_no'];
                $people->payment_mode_id = $array['payment_mode_id'];
                $people->term_id = $array['term_id'];
                $people->organization_id = Session::get('organization_id');
                $people->save();

                Custom::add_addon('records');
              } else {
                $people = $people_exist;
              }
              $id = $people->id;

            }

        }
      }
      

      $person_type_name = $array['person_type'];
      if($person_type_name != null) {

        $person_type_id = AccountPersonType::where('name', $person_type_name)->first()->id;

        $person_type = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id)->first();

        if($person_type == null) {
          DB::table('people_person_types')->insert(['people_id' => $people->id, 'person_type_id' => $person_type_id]);
        }
      }

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

    
      $credit_limit = AccountLedgerCreditInfo::findOrFail($ledger);

      $credit_limit->max_credit_limit = $array['max_credit_limit'];

      $credit_limit->save();  

       if($people->id) {
              $billing_address = new PeopleAddress();
              $billing_address->people_id = $people->id;
              $billing_address->address_type = 0;
              $billing_address->address = $array['billing_address'];
              $billing_address->city_id = $array['billing_city_id'];
              $billing_address->google = $array['billing_google'];
              $billing_address->pin = $array['billing_pin'];
              $billing_address->save();

              if($array['same_billing_address'] != null) {
                $shipping_address = new PeopleAddress();
                $shipping_address->people_id = $people->id;
                $shipping_address->address_type = $address_type->id;
                $shipping_address->address = $array['shipping_address'];
                $shipping_address->city_id = $array['shipping_city_id'];
                $shipping_address->google = $array['shipping_google'];
                $shipping_address->pin = $array['shipping_pin'];
                $shipping_address->save();
              } else {
                $shipping_address = new PeopleAddress();
                $shipping_address->people_id = $people->id;
                $shipping_address->address_type = $address_type->id;
                $shipping_address->address = $array['billing_address'];
                $shipping_address->city_id = $array['billing_city_id'];
                $shipping_address->google = $array['billing_google'];
                $shipping_address->pin = $array['billing_pin'];
                $shipping_address->save();
              }
            
            }

            
           
       
       return ['mobile_number' => $mobile_number , 'id' => $id ,'name' => $name ,'credit_limit_value' => $credit_limit_value,'code' => $code];
    }
    public function get_data_from_gst_number(Request $request)
    {
      
      $organization_id = Session::get('organization_id');
      $gst = $request->input('data');
      
         if($request->input('user_type') == 0)
        {
            $check_gst = Person::leftjoin('people','people.person_id','=','persons.id')
            ->where('people.organization_id',$organization_id)
            ->where('persons.status','1')
            ->where('persons.gst_no', $gst)
            ->first();
            
            if($check_gst != null)
            {
               return response()->json(['status' => 0 ,'message' => 'This GST Number already exists in your organization!']);
            }
            else
            {
              $check_gst = Person::select('persons.*','person_communication_addresses.address_type','person_communication_addresses.address','person_communication_addresses.city_id','person_communication_addresses.pin','person_communication_addresses.mobile_no','person_communication_addresses.email_address')
              ->leftjoin('person_communication_addresses','person_communication_addresses.person_id','=','persons.id')
              ->where('persons.gst_no', $gst)->where('persons.status','1')->first();
              
             if($check_gst)
              {

                $city_id = City::where('id',$check_gst->city_id)->first();              
                $state_id = $city_id->id;
                return response()->json(['status' => 1 ,'data' => $check_gst, 'state_id' => $state_id,'message' =>'This information already exists.Do you want to add this information in your organization?']);
              }
              else{
                 return response()->json(['status' => 2,'data' => $gst]);
              }
              }

        }
        else
        {
          $check_gst = Business::leftjoin('people','people.business_id','=','businesses.id')
          ->where('people.organization_id',$organization_id)
          ->where('businesses.status' , '1')
          ->where('businesses.gst', $gst)
          ->first();
          

          if($check_gst != null)
          {
             return response()->json(['status' => 0 , 'message' => 'This GST Number is already exists in your organization!']);
          }
          else
          {
             $check_gst = Business::select('businesses.*','business_communication_addresses.address_type','business_communication_addresses.address','business_communication_addresses.city_id','business_communication_addresses.mobile_no','business_communication_addresses.email_address')
             ->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id')
             ->where('businesses.gst', $gst)
             ->where('businesses.status' , '1')->first();
          if($check_gst)
              {

                $city_id = City::where('id',$check_gst->city_id)->first();              
                $state_id = $city_id->id;
                return response()->json(['status' => 1 ,'data' => $check_gst, 'state_id' => $state_id,'message' =>'This information already exists.Do you want to add this information in your organization?']);
              }
              else{
                 return response()->json(['status' => 2,'data' => $gst]);
              }
              
          }
        }

    }
   
    public function get_data_from_mobile_number(Request $request)
    {
      //dd($request->all());
      $organization_id = Session::get('organization_id');
      $mobile_number = $request->input('data');
      
         if($request->input('user_type') == 0)
        {
            $check_mob = PersonCommunicationAddress::leftjoin('persons','persons.id','=','person_communication_addresses.person_id')
            ->leftjoin('people','people.person_id','=','persons.id')
            ->where('person_communication_addresses.mobile_no', $mobile_number)
            ->where('people.organization_id',$organization_id)
            ->where('person_communication_addresses.status','1')
            ->first();
            //dd($check_mob);
            if($check_mob != null)
            {
               return response()->json(['status' => 0 ,'message' => 'This Mobile Number already exists in your organization!']);
            }
            else
            {
              $check_mob = PersonCommunicationAddress::select('persons.*','person_communication_addresses.address_type','person_communication_addresses.address','person_communication_addresses.city_id','person_communication_addresses.pin','person_communication_addresses.mobile_no','person_communication_addresses.email_address','people.user_type')
              ->leftjoin('persons','persons.id','=','person_communication_addresses.person_id')
              ->leftjoin('people','people.person_id','=','persons.id')
              ->where('person_communication_addresses.mobile_no', $mobile_number)->where('person_communication_addresses.status','1')->first();
              //dd($check_mob);
              $state_id = '';
             if($check_mob != null)
             {
               $city_id = City::where('id',$check_mob->city_id)->first();
              //dd($city_id);
              $state_id = $city_id->state_id;
             }
             

              return response()->json(['status' => 1 ,'data' => $check_mob,'state_id' => $state_id,'message' =>'This information already exists.Do you want to add this information in your organization?']);
            }

        }
        else
        {
          $check_mob = BusinessCommunicationAddress::leftjoin('businesses','businesses.id','=','business_communication_addresses.business_id')
          ->leftjoin('people','people.business_id','=','businesses.id')
          ->where('business_communication_addresses.mobile_no', $mobile_number)
          ->where('people.organization_id',$organization_id)
          ->where('business_communication_addresses.status' , '1')
          ->first();
          //dd($check_mob);

          if($check_mob != null)
          {
             return response()->json(['status' => 0 , 'message' => 'This mobile number is already exists in your organization!']);
          }
          else
          {
             $check_mob = BusinessCommunicationAddress::select('businesses.*','business_communication_addresses.address_type','business_communication_addresses.address','business_communication_addresses.city_id','business_communication_addresses.mobile_no','business_communication_addresses.email_address','people.user_type')
             ->leftjoin('businesses','businesses.id','=','business_communication_addresses.business_id')
             ->leftjoin('people','people.business_id','=','businesses.id')
             ->where('business_communication_addresses.mobile_no', $mobile_number)
             ->where('business_communication_addresses.status' , '1')->first();
             //dd($check_mob);
             $state_id = '';
             if($check_mob != null)
             {
              $city_id = City::where('id',$check_mob->city_id)->first();
               //dd($city_id);
               $state_id = $city_id->state_id;
             }
             
            return response()->json(['status' => 1 , 'data' => $check_mob ,'state_id' => $state_id,'message' => 'This information already exists.Do you want to add this information in your organization?']);
          }
        }

    }


}
