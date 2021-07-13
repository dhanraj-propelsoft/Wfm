<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\PaymentMethod;
use App\PeopleAddress;
use App\PeopleTitle;
use App\CustomerGroping;
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
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$people = People::where('organization_id', $organization_id)->get();

		$person_type_id = AccountPersonType::where('name', $name)->first()->id;

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

		return view('trade_wms.customers',compact('people','state','payment','terms','title','groyp_name'));
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

		//return $request->all();

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
