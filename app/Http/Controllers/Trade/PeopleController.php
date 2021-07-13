<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\PaymentMethod;
use App\PeopleAddress;
use App\PeopleTitle;
use App\Country;
use App\People;
use App\Custom;
use App\State;
use Validator;
use Response;
use App\City;
use App\Term;
use Session;


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

		if($type == 'wms') $name = 'customer'; else $name = $type;

		$person_type_id = AccountPersonType::where('name', $name)->first()->id;

		    $peoples = People::select('people.id', 'people.person_id', 'people.first_name', 'people.display_name','people.mobile_no','people.status', 'people.email_address', 'persons.crm_code', 'businesses.bcrm_code', 
            DB::raw('IF(SUM(transactions.total) IS NULL, SUM(business.total), SUM(transactions.total)) AS total'),
            DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS crm_id'),
            DB::raw('CONCAT(COALESCE(people.first_name, ""), " " ,COALESCE(people.last_name, "")) AS contact_person'), 
            DB::raw('IF(account_ledger_credit_infos.max_credit_limit IS NULL, business_ledger_credit_infos.max_credit_limit, account_ledger_credit_infos.max_credit_limit) AS max_credit_limit')
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

        ->where('people.organization_id', $organization_id)
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('people.first_name')
        ->get();



		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		return view('trade_wms.customers', compact('peoples','name', 'title', 'state', 'payment', 'terms', 'type'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$state =  State::orderby('name')->orderby('name')->pluck('name', 'id');
		$state->prepend('Select State','');

		$city =  City::orderby('name')->orderby('name')->pluck('name', 'id');
		$city->prepend('Select City','');

		$title = PeopleTitle::pluck('name','id');
		$title->prepend('Select Title','');

		return view('trade.people_create',compact('state','city','title'));
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

		return $request->all();

		$organization_id = Session::get('organization_id');

		$people = new People();
		$people->title = $request->input('title');
		$people->first_name = $request->input('first_name');
		$people->last_name = $request->input('last_name');
		$people->mobile_no = $request->input('mobile_no');
		$people->phone = $request->input('phone');
		$people->email_address = $request->input('email_address');
		$people->web_address = $request->input('web_address');
		$people->pan = $request->input('pan');
		$people->gst = $request->input('gst');
		$people->organization_id = Session::get('organization_id');
		$people->save();

		if($people->id) {
			$people_address = new PeopleAddress();
			$people_address->people_id = $people->id;
			$people_address->address = $request->input('address');
			$people_address->state_id = $request->input('state_id');
			$people_address->city_id = $request->input('city_id');
			$people_address->pin = $request->input('pin');
			$people_address->save();
		}

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.added'), 'data' => [
			'id' => $people->id, 
			'title' => $people->title,
			'first_name' => $people->first_name,
			'last_name' => $people->last_name,
			'mobile_no' => $people->mobile_no,
			'phone' => $people->phone,
			'email_address' => $people->email_address,
			'pan' => $people->pan,
			'gst' => $people->gst,
			'address' => $people_address->address,
			'state_id' => $people_address->state_id,
			'city_id' => $people_address->city_id,
			'pin' => $people_address->pin,
			]]);

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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
