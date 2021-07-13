<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use App\BusinessProfessionalism;
use App\HrmDesignation;
use App\BusinessNature;
use App\PaymentMethod;
use App\CustomerGroping;
use App\HrmDepartment;
use App\Organization;
use App\HrmEmployee;
use App\PeopleTitle;
use App\Business;
use App\Country;
use App\Custom;
use App\People;
use App\State;
use Validator;
use App\Term;
use App\City;
use Session;
use DB;


class BussinessProfileController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');
		$group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

		$businesses = Business::select('businesses.id','businesses.business_name')
		->leftjoin('organizations','businesses.id','=','organizations.business_id')
		->where('organizations.id', $organization_id)
		->paginate(10);

		return view('settings.business_profile',compact('businesses','title','payment','terms','group_name'));
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
	public function show($id, $type = null)
	{
		

		$organization_id = Session::get('organization_id');
		$organization = Organization::find($organization_id);
		
		if($organization->business_id != $id && !(Organization::checkModuleExists('super_admin', Session::get('organization_id')))) {
			abort(404);
		}


		$businessnature =  BusinessNature::pluck('display_name', 'id');
		$businessnature->prepend('Select Nature Of Business', '');

		$businessprofessionalism = BusinessProfessionalism::pluck('display_name', 'id');
		$businessprofessionalism->prepend('Select Business Profession', '');

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id')->prepend('Select Department', '');

		$designation  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation->prepend('Select Designation', '');       

		/*$ownerships = HrmEmployee::select('hrm_employeesa.*','hrm_designations.id as designation_id','hrm_designations.name as designation_name','hrm_departments.id as department_id','hrm_departments.name as department_name',(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS employee_name')),'hrm_staff_types.display_name as staff_type')
		->leftjoin('hrm_employee_designation','hrm_employees.id','=','hrm_employee_designation.employee_id')
		->leftjoin('hrm_designations','hrm_employee_designation.designation_id','=','hrm_designations.id')
		->leftjoin('hrm_departments','hrm_designations.department_id','=','hrm_departments.id')
		->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
		->where('hrm_staff_types.display_name','Ownership')
		->where('hrm_employees.id',$id)->get();
		->where('hrm_employees.organization_id', $organization_id)->get();*/


		

		$employees = HrmEmployee::select(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'), 'id')->pluck("name", "id");
		$employees->prepend('Select Employee', '');

		$business = Business::select('businesses.*','business_natures.display_name as business_nature','business_professionalisms.display_name as business_professionalism')->leftjoin('organizations','businesses.id','=','organizations.business_id')->leftjoin('business_natures','businesses.business_nature_id','=','business_natures.id')->leftjoin('business_professionalisms','businesses.business_professionalism_id','=','business_professionalisms.id')->where('businesses.id',$id)->first();

		$businesscommuincation = BusinessCommunicationAddress::select('business_communication_addresses.id','business_communication_addresses.mobile_no','persons.first_name as persons_id','cities.name as city_name','states.name as state_name','business_communication_addresses.address','business_communication_addresses.landmark','business_communication_addresses.google','business_communication_addresses.email_address','business_communication_addresses.web_address','business_communication_addresses.pin','business_communication_addresses.city_id','cities.state_id','business_communication_addresses.placename')
		->leftJoin('persons', 'business_communication_addresses.contact_person_id', '=', 'persons.id')
		->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
		->leftjoin('states','cities.state_id','=','states.id')		
		->where('business_communication_addresses.business_id', $id)->first();

		$ownerships = HrmEmployee::select('hrm_employees.*',(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS employee_name')),'hrm_staff_types.display_name as staff_type')
		->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
		->where('hrm_staff_types.name','ownership')
		->where('hrm_employees.organization_id', $organization_id)->get();

		$managements = HrmEmployee::select('hrm_employees.*',(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS employee_name')),'hrm_staff_types.display_name as staff_type')
		->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
		->where('hrm_staff_types.name','management')
		->where('hrm_employees.organization_id', $organization_id)->get();

		$staffs = HrmEmployee::select('hrm_employees.*',(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS employee_name')),'hrm_staff_types.display_name as staff_type')
		->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
		->where('hrm_staff_types.name','staff')
		->where('hrm_employees.organization_id', $organization_id)->get();

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		

		$city = [];

		$address = BusinessCommunicationAddress::select('business_communication_addresses.id as address_id','business_communication_addresses.city_id','cities.name as city_name','cities.state_id','states.name as state_name')
	   ->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')	  
	   ->where('business_communication_addresses.business_id',$id)->first();

		if(!empty($address->city_id)) {
			$selected_city = City::where('id', $address->city_id)->first();

			$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

			$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
			$city->prepend('Select City', '');
		}

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$people->prepend('Select Person', '');

		return view('settings.business_profile_show',compact('id','business','businessnature','businessprofessionalism','businesscommuincation','state','city','ownerships','managements','staffs','people','title','payment','terms','department','designation','employees', 'organization', 'type','group_name'));
	}

	public function business_contact_update(Request $request)
	{
		//return $request->all();

		$organization_id = Session::get('organization_id');

		$business =  Business::where('id',$request->input('id'))->first();

		$business->business_name = $request->input('business_name');
		$business->alias = $request->input('alias');        
		if($request->input('business_nature_id') != null){
			$business->business_nature_id = $request->input('business_nature_id');
		}
		if($request->input('business_professionalism_id') != null){
			$business->business_professionalism_id = $request->input('business_professionalism_id');
		}		
		$business->gst = $request->input('gst');
		$business->pan = $request->input('pan');
		$business->tin = $request->input('tin');
		$business->save();

		Custom::userby($business, false);      

		return response()->json(['status' => 1, 'message' => 'Business'.config('constants.flash.updated'), 'data' => []]);

	}

	public function communication_update(Request $request)
	{
		//return $request->all();

		$organization_id = Session::get('organization_id');

		$commuincation =  BusinessCommunicationAddress::where('business_id',$request->input('id'))->first();

	 	$commuincation->address = $request->input('address');  	
		$commuincation->mobile_no = $request->input('mobile_no');
		$commuincation->placename = $request->input('placename');
		$commuincation->email_address = $request->input('email_id');
		$commuincation->web_address = $request->input('web_address');
		$commuincation->city_id = $request->input('city_id');
		$commuincation->pin = $request->input('pin');
		$commuincation->google = $request->input('google');
		$commuincation->save();

		Custom::userby($commuincation, false);      

		return response()->json(['status' => 1, 'message' => 'Communication'.config('constants.flash.updated'), 'data' => []]);

	}

	public function ownership_update(Request $request)
	{
		//return $request->all();

		$ownerships =  HrmEmployee::where('id',$request->input('employee_id'))->first();

		//dd($ownerships);
		
		if($ownerships != null)
		{
			$ownership = $ownerships;
		}
		else
		{
			$ownership = new HrmEmployee;
		}     
		
		$ownership->employee_code = $request->input('employee_code');
		
		if(($ownerships->person_id) != ''){
			$ownership->person_id = $ownerships->person_id;
		}
		
		$ownership->phone_no = $request->input('phone_no');
		$ownership->email = $request->input('email');
		$ownership->save();
				
		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);
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
