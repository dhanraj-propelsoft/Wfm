<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonCommunicationAddress;
use App\PersonAddressType;
use App\Organization;
use App\BloodGroup;
use App\Country;
use App\Gender;
use App\Person;
use App\State;
use App\User;
use Hash;
use Auth;
use DB;
use Session;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public $successStatus = 200;

	public function index()
	{
		$person_id = Auth::user(request('user_id'))->person_id;

		$users = Person::select('persons.id', 'persons.crm_code', 'persons.first_name', DB::raw('COALESCE(persons.last_name, "") AS last_name'),
		 DB::raw('COALESCE(persons.pan_no, "") AS pan_no'), 
		 DB::raw('COALESCE(persons.aadhar_no, "") AS aadhar_no'), 
		 DB::raw('COALESCE(persons.passport_no, "") AS passport_no'), 
		 DB::raw('COALESCE(DATE_FORMAT(persons.dob, "%d-%m-%Y"), "") AS dob'), 
		 DB::raw('COALESCE(genders.display_name, "") AS gender'), 
		 DB::raw('COALESCE(blood_groups.display_name, "") AS blood_group'))
		->leftjoin('genders', 'genders.id', '=', 'persons.gender_id')
		->leftjoin('blood_groups', 'blood_groups.id', '=', 'persons.blood_group_id')
		->where('persons.id', $person_id)->first();

		$address = PersonCommunicationAddress::select( 
			'person_address_types.display_name AS address_type', 
			DB::raw('COALESCE(person_communication_addresses.address, "") AS address'), 
			DB::raw('COALESCE(cities.name, "") AS city'),
			DB::raw('COALESCE(states.name, "") AS state'), 
			DB::raw('COALESCE(person_communication_addresses.pin, "") AS pin'), 
			DB::raw('COALESCE(person_communication_addresses.google, "") AS google'), 
			DB::raw('COALESCE(person_communication_addresses.mobile_no, "") AS mobile_no'), 
			DB::raw('COALESCE(person_communication_addresses.email_address, "") AS email_address'))
		->leftjoin('person_address_types', 'person_address_types.id', '=', 'person_communication_addresses.address_type')
		->leftjoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id')
		->leftjoin('states', 'states.id', '=', 'cities.state_id')
		->where('person_communication_addresses.person_id', $person_id)->get();

		$message['status'] =  1;
		$message['users'] =  $users;
		$message['address'] =  $address;

		return response()->json($message, $this->successStatus);
	}

	public function edit($id)
	{
		$person_id = Auth::user($id)->person_id;

		$users = Person::select('persons.id', 'persons.crm_code', 'persons.first_name', DB::raw('COALESCE(persons.last_name, "") AS last_name'),
		 DB::raw('COALESCE(persons.pan_no, "") AS pan_no'), 
		 DB::raw('COALESCE(persons.aadhar_no, "") AS aadhar_no'), 
		 DB::raw('COALESCE(persons.passport_no, "") AS passport_no'), 
		 DB::raw('COALESCE(DATE_FORMAT(persons.dob, "%d-%m-%Y"), "") AS dob'), 
		 DB::raw('COALESCE(persons.gender_id, "") AS gender'), 
		 DB::raw('COALESCE(blood_groups.display_name, "") AS blood_group'))
		->leftjoin('blood_groups', 'blood_groups.id', '=', 'persons.blood_group_id')
		->where('persons.id', $person_id)->first();

		$address_type = PersonAddressType::where('name', 'residential')->first()->id;

		$address = PersonCommunicationAddress::select( 
			'person_communication_addresses.id',
			'person_communication_addresses.address_type AS address_type_id', 
			'person_address_types.display_name AS address_type', 
			DB::raw('COALESCE(person_communication_addresses.address, "") AS address'), 
			DB::raw('COALESCE(person_communication_addresses.city_id, "") AS city'),
			DB::raw('COALESCE(states.name, "") AS state'),
			DB::raw('COALESCE(cities.name, "") AS city'),
			DB::raw('COALESCE(cities.state_id, "") AS state_id'), 
			DB::raw('COALESCE(person_communication_addresses.pin, "") AS pin'), 
			DB::raw('COALESCE(person_communication_addresses.google, "") AS google'), 
			DB::raw('COALESCE(person_communication_addresses.mobile_no, "") AS mobile_no'), 
			DB::raw('COALESCE(person_communication_addresses.email_address, "") AS email_address'))
		->leftjoin('person_address_types', 'person_address_types.id', '=', 'person_communication_addresses.address_type')
		->leftjoin('cities', 'cities.id', '=', 'person_communication_addresses.city_id')
		->leftjoin('states', 'states.id', '=', 'cities.state_id')
		->where('person_communication_addresses.person_id', $person_id)
		->where('person_address_types.id', $address_type)
		->first();


		$blood_group = BloodGroup::select('id', 'display_name AS name')->where('status', 1)->get();

		$gender = Gender::select('id', 'display_name AS name')->where('status', 1)->get();

		$country = Country::select('id', 'name')->where('name', "India")->first();

		$state = State::select('id', 'name')->where('country_id', $country->id)->where('status', 1)->get();

		$message['status'] =  1;
		$message['users'] =  $users;
		$message['address'] =  $address;
		$message['blood_group'] =  $blood_group;
		$message['gender'] =  $gender;
		$message['state'] =  $state;

		return response()->json($message, $this->successStatus);
	}

	public function update()
	{
		$person_id = Auth::user(request('user_id'))->person_id;

		$dob_text = explode('-', request('dob'));

		$user = Person::find($person_id);
		$user->first_name = request('first_name');
		$user->last_name = request('last_name');
		$user->dob = $dob_text[2]."-".$dob_text[1]."-".$dob_text[0];
		$user->pan_no = request('pan_no');
		$user->aadhar_no = request('aadhar_no');
		$user->passport_no = request('passport_no');
		$user->pan_no = request('pan_no');
		if(request('gender_id') != null) {
			$user->gender_id = request('gender_id');
		}

		if(request('blood_group_id') != null) {
			$user->blood_group_id = request('blood_group_id');
		}
		
		
		$user->save();

		$address_type = PersonAddressType::where('name', 'residential')->first()->id;

		$address = PersonCommunicationAddress::where('person_id', $person_id)->where('person_communication_addresses.address_type', $address_type)->first();
		$address->address = request('address');

		if(request('city_id') != null) {
			$address->city_id = request('city_id');
		}
		
		$address->pin = request('pin');
		$address->google = request('google');
		$address->mobile_no = request('mobile_no');
		$address->email_address = request('email_address');
		$address->save();

		return response()->json(['status' => 1, 'message' => 'Profile'.config('constants.flash.updated'), 'data' => []]);
	}

	public function password()
	{
		$user = User::find(request('user_id'));

		if (Hash::check(request('old_password'), $user->password)) {
			$user->password = Hash::make(request('password'));
			$user->save();

			return response()->json(['status' => 1, 'message' => 'Password Successfully Changed!', 'data' => []], $this->successStatus);
		} else {
			return response()->json(['status' => 0, 'message' => 'Incorrect old Password!', 'data' => []], $this->successStatus);
		}
	}

	  public function UserCompanies($person_id)
	  {
			Log::info('API_UserController->UserCompanies :- Inside: ');
			Log::info('API_UserController->UserCompanies :- Inside: param person_id '.$person_id);
			
			

			$organization = Organization::select('organizations.*');
			$organization->leftJoin('organization_person', 'organizations.id', '=', 'organization_person.organization_id');
			$organization->leftJoin('persons', 'persons.id', '=', 'organization_person.person_id');
			$organization->leftJoin('module_organization', 'module_organization.organization_id', '=', 'organizations.id');
			$organization->leftJoin('modules', 'module_organization.module_id', '=', 'modules.id');
			$organization->where('persons.id', $person_id);
			$organization->where('modules.name', "wfm");
			$organizations = $organization->groupBy('organizations.id')->get();
			
			

			return response()->json(['status' => 1, 'message' => 'Business Accounts Data has been get Successfully!', 'data' =>$organizations], $this->successStatus);
	


	  }
}
