<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;
use App\PersonCommunicationAddress;
use App\PersonAddressType;
use App\Gender;
use App\BloodGroup;
use App\MaritalStatus;
use App\Country;
use App\State;
use App\City;
use App\Custom;
use App\User;
use Carbon\Carbon;
use DateTime;
use Validator;
use Session;
use Auth;
use DB;
use URL;


class PersonProfileController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$person_id = Auth::user()->person_id;

		$persons = Person::select('persons.id','persons.first_name')        
		->where('persons.id', $person_id)
		->paginate(10);

		return view('settings.person_profile',compact('persons'));
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
		$blood_groups = BloodGroup::pluck('display_name','id');
		$blood_groups->prepend('Select Blood Groups','');

		$marital_status = MaritalStatus::pluck('display_name','id');
		$marital_status->prepend('Select Marital Status','');

		$genders = Gender::select('genders.display_name as gender','genders.id')->get(); 
		$gender_array = array();

		$person = Person::select('persons.id','persons.first_name','persons.dob','persons.alias','persons.pan_no','persons.aadhar_no','persons.passport_no','persons.license_no','persons.gender_id','persons.marital_status_id','persons.image as profile_image','persons.blood_group_id','genders.display_name as gender_name','blood_groups.display_name as blood_group','marital_statuses.display_name as marital_status_name')
		->leftjoin('genders','persons.gender_id','=','genders.id')
		->leftjoin('blood_groups','persons.blood_group_id','=','blood_groups.id')
		->leftjoin('marital_statuses','persons.marital_status_id','=','marital_statuses.id')
	   	->where('persons.id',$id)
	   	->first();
		$image=$person->profile_image;

	   /*$emp_address = PersonCommunicationAddress::select('person_communication_addresses.*','persons.first_name as person_name','cities.id as city_id','cities.name as city_name','states.name as state_name','cities.state_id')
		->leftJoin('persons', 'person_communication_addresses.person_id', '=', 'persons.id')
		->leftjoin('cities','person_communication_addresses.city_id','=','cities.id')
		->leftjoin('states','cities.state_id','=','states.id')
		->where('person_communication_addresses.person_id', $id)->first();*/

		$communication = PersonCommunicationAddress::select('person_communication_addresses.id as address_id','person_communication_addresses.address_type','person_communication_addresses.address','person_communication_addresses.city_id','person_communication_addresses.pin','person_communication_addresses.google','cities.name as city_name','cities.state_id','states.name as state_name','person_address_types.display_name as address_type_name','person_communication_addresses.mobile_no','person_communication_addresses.email_address')
	   ->leftjoin('cities','person_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
	   ->leftjoin('person_address_types','person_communication_addresses.address_type','=','person_address_types.id')
	   ->where('person_communication_addresses.person_id',$id)
	   ->where('person_communication_addresses.address_type', '=', 1)->first();
	  /* $communication->where(function ($query)  {
			    $query->where('person_communication_addresses.address_type', '=', 1)
			          ->orWhere('person_communication_addresses.address_type', '=', 2);
		});*/
     // $communications = $communication->get();

		

		$official_communication = PersonCommunicationAddress::select('person_communication_addresses.id as address_id','person_communication_addresses.address_type','person_communication_addresses.address','person_communication_addresses.city_id','person_communication_addresses.pin','person_communication_addresses.google','cities.name as city_name','cities.state_id','states.name as state_name','person_address_types.display_name as address_type_name','person_communication_addresses.mobile_no','person_communication_addresses.email_address')
	   ->leftjoin('cities','person_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
	   ->leftjoin('person_address_types','person_communication_addresses.address_type','=','person_address_types.id')
	   ->where('person_communication_addresses.person_id',$id)
	   ->where('person_communication_addresses.address_type', '=', 2)->first();

		$person_communications = PersonCommunicationAddress::where('person_id',$id)->orderby('address_type')->get();

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$address_type = PersonAddressType::pluck('display_name', 'id');
		$address_type->prepend('Select Address Type', '');

		$city = [];

		$address = PersonCommunicationAddress::select('person_communication_addresses.id as address_id','person_communication_addresses.city_id','cities.name as city_name','cities.state_id','states.name as state_name')
	   ->leftjoin('cities','person_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')	  
	   ->where('person_communication_addresses.person_id',$id)->first();

	if(!empty($address->city_id)) {
		$selected_city = City::where('id', $address->city_id)->first();

		$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

		$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
		$city->prepend('Select City', '');
	}
		$path=URL::to('/').'/public/users/images/'.$image;

		return view('settings.person_profile_show',compact('id','person','blood_groups','marital_status','genders','communication','state','city','person_communications','address_type','official_communication','path'));
	}

	public function personal_details_update(Request $request)
	{		

		$organization_id = Session::get('organization_id');

		$personal_details =  Person::where('id',$request->input('id'))->first();

		$personal_details->first_name = $request->input('first_name');
		$personal_details->alias = $request->input('alias'); 

		if($request->input('dob') != null){
			$personal_details->dob = ($request->input('dob')!=null) ? Carbon::parse($request->input('dob'))->format('Y-m-d') : null;
		}
		
		if($request->input('gender_id') != null){
			$personal_details->gender_id = $request->input('gender_id');
		}       
		if($request->input('blood_group_id') != null){
			$personal_details->blood_group_id = $request->input('blood_group_id');
		}
		if($request->input('marital_status_id') != null){
			$personal_details->marital_status_id = $request->input('marital_status_id');
		}
		$personal_details->pan_no = $request->input('pan_no');
		$personal_details->aadhar_no = $request->input('aadhar_no');
		$personal_details->passport_no = $request->input('passport_no');
		$personal_details->save();

		Custom::userby($personal_details, false);      

		return response()->json(['status' => 1, 'message' => 'Person'.config('constants.flash.updated'), 'data' => []]);

	}
	public function profile_image_upload(Request $request)
	{	

		$organization_id = Session::get('organization_id');
		$id=$request->id;

		$file = $request->file('file');
		if($request->hasFile('file')){
			$imgname=$request->file('file')->getClientOriginalName();
			$format=explode(".", $imgname);
			
		}
		
		$personal_details =  Person::where('id',$request->input('id'))->first();


		$path_array = explode('/', '/users/images/');

		$public_path = '';
		
		foreach ($path_array as $p) {
			$public_path .= $p."/";
			if (!file_exists(public_path($public_path))) {
				mkdir(public_path($public_path), 0777, true);
			}
		}

		$name = "user_".$id.".".$format[1];
		
		$personal_details= Person::findorFail($id);
		$personal_details->image=$name;
		$personal_details->save();

		 $img=Custom::image_resize($file,200,$name,$public_path);
		//$request->file('file')->move(public_path($public_path), $name);

		return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.updated'),'data'=>['id' => $id, 'path' => URL::to('/').'/public/users/images/'.$name]]);
	}

	
	public function communication_update(Request $request)
	{
		//dd($request->all());

		$organization_id = Session::get('organization_id');
		
		$communications = PersonCommunicationAddress::find($request->input('person_id'));

		if($communications != null )
		{
			$communication =  $communications;
		}
		else{
			$communication =  new PersonCommunicationAddress;
		}
		
		$communication->person_id = $request->input('person_id');
		$communication->address = $request->input('residential_address');
		$communication->city_id = $request->input('residential_city_id');
		$communication->pin = $request->input('residential_pin');
		$communication->google = $request->input('residential_google');
		$communication->address_type = $request->input('address_type');
		$communication->save();

		if($request->address_type2 != null){
			$communication = PersonCommunicationAddress::updateOrCreate(
          [
            'person_id'=>$request->input('person_id'),
            'address_type'=> $request->input('address_type2'),
          ],[
            'person_id'=> $request->input('person_id'),
            'address'=>$request->input('address'),
            'city_id'=>$request->input('city_id'),
            'pin'=>$request->input('pin'),
            'google'=>$request->input('google'),
            'address_type'=>$request->input('address_type2'),
          ]);
		}

		if($request->mobile_no != null){

             DB::table('person_communication_addresses')->where('person_id',$request->input('person_id'))->where('id',$request->input('id'))->update(['mobile_no'=> $request->input('mobile_no')]);
              DB::table('users')->where('person_id',$request->input('person_id'))->update(['mobile'=> $request->input('mobile_no')]);
		}

		if($request->email_id != null){
			DB::table('person_communication_addresses')->where('person_id',$request->input('person_id'))->where('id',$request->input('id'))->update(['email_address'=> $request->input('email_id')]);

			DB::table('users')->where('person_id',$request->input('person_id'))->update(['email'=> $request->input('email_id')]);
		}
		
		return response()->json(['status' => 1, 'message' => 'Communication'.config('constants.flash.updated'), 'data' => ['id' => $communication->id]]);
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

	public function get_mobile_no(Request $request)
	{
		$communication = PersonCommunicationAddress::where('mobile_no', $request->phone)
                ->where('id','!=', $request->id)->first();
        if(!empty($communication->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
	}

}
