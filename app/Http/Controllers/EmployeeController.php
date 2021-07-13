<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployeeWorkingPeriod;
use App\HrmEmployeeExperience;
use App\HrmEmployeeEducation;
use App\HrmEmployeeAddress;
use App\AccountLedgerType;
use App\HrmEmploymentType;
use App\HrmEmployeeSalary;
use App\AccountPersonType;
use App\HrmDesignation;
use App\HrmSalaryScale;
use App\HrmDepartment;
use App\MaritalStatus;
use App\PaymentMethod;
use App\Organization;
use App\HrmLeaveType;
use App\AccountGroup;
use App\HrmStaffType;
use App\LicenseType;
use App\HrmEmployee;
use App\PeopleTitle;
use App\BloodGroup;
use App\HrmBranch;
use Carbon\Carbon;
use App\Business;
use App\Country;
use App\People;
use App\Gender;
use App\Custom;
use App\State;
use App\City;
use App\Bank;
use App\Term;
use DateTime;
use Response;
use Session;
use DB;

class EmployeeController extends Controller
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

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$employees = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','genders.display_name as gender','blood_groups.display_name as blood_group')
		->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
		->where('organization_id',$organization_id)
		->whereNull('deleted_at')
		->get();

		return view('employees',compact('employees','title', 'state', 'payment', 'terms'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$staff_type = HrmStaffType::pluck('display_name','id');
		$staff_type->prepend('Select Staff','');

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Select Department', '');

		$designation  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation->prepend('Select Designation', '');

		$branch = HrmBranch::where('organization_id', $organization_id)->pluck('branch_name', 'id');
		$branch->prepend('Select Branch', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$blood_groups = BloodGroup::pluck('display_name','id');
		$blood_groups->prepend('Select Blood Groups','');

		$marital_status = MaritalStatus::pluck('display_name','id');
		$marital_status->prepend('Select Marital Status','');

		$license_type = LicenseType::pluck('display_name','id');
		$license_type->prepend('Select License Type','');

		$job_type = HrmEmploymentType::where('organization_id', $organization_id)->pluck('name', 'id');
		$job_type->prepend('Select Job Type', '');

		$genders = Gender::select('genders.id','genders.display_name as name')->get();  
		$gender_array = array();

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$employee_salary_scale = HrmSalaryScale::where('organization_id', $organization_id)->where('status', 1)->pluck('name', 'id');
		$employee_salary_scale->prepend('Select Salary Scale', '');

		$bank   = Bank::distinct()->get(['bank'])->pluck('bank', 'bank');
		$bank->prepend('Select Bank', '');

		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$people->prepend('Select Person', '');      
		
		return view('employees_create', compact('people', 'state', 'title', 'payment', 'terms','blood_groups','department','designation','branch','genders','marital_status','license_type','job_type','bank','employee_salary_scale','staff_type'));
		  
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
			'first_name'=>'required',
			'employee_code'=> 'required'
		]);

		//return $request->all();
		
		$organization_id = Session::get('organization_id');

		$employee = HrmEmployee::select('hrm_employees.*')
		->leftJoin('organization_person', function($q) use ($organization_id)
		{
			$q->on('organization_person.person_id', '=', 'hrm_employees.person_id')
				->where('organization_person.organization_id', '=', "$organization_id");
		})
		->where('hrm_employees.person_id', $request->input('person_id'))
		->where('hrm_employees.organization_id', $organization_id)
		->where('hrm_employees.status', '1')
		->whereNotNull('organization_person.person_id')
		->first();

		if($employee != null) {
			return response()->json(['status' => 0, 'message' => 'Employees'.config('constants.flash.exist'), 'data' => ['id' => $employee->id, 'name' => $employee->first_name,'code'=>$employee->employee_code,'phone_no'=>$employee->phone_no, 'email'=> $employee->email, 'blood_group'=> $employee->blood_group,'gender' => $employee->gender]]);
		}       

		$hrm_employee = new HrmEmployee;

		if($request->input('staff_type_id') != null){
			$hrm_employee->staff_type_id = $request->input('staff_type_id');
		}
		$hrm_employee->person_id = $request->input('person_id');
		if($request->input('title_id') != null){
			$hrm_employee->title_id = $request->input('title_id');
		}
		$hrm_employee->employee_code = $request->input('employee_code');
		$hrm_employee->first_name = $request->input('first_name');
		$hrm_employee->last_name = $request->input('last_name');
		$hrm_employee->email = $request->input('email');
		$hrm_employee->phone_no = $request->input('phone_no');

		if($request->input('gender_id') != null){
			$hrm_employee->gender_id = $request->input('gender_id');
		}
		if($request->input('blood_group_id') != null){
			$hrm_employee->blood_group_id = $request->input('blood_group_id');
		}
		if($request->input('marital_status') != null){
			$hrm_employee->marital_status = $request->input('marital_status');
		}	   
		$hrm_employee->organization_id = $organization_id;	

		$hrm_employee->save();

		$ledgergroup = AccountGroup::where('name', 'employees')->where('organization_id', $organization_id)->first();
		$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
		$organization = Organization::findOrFail($organization_id);

		$hrm_employee->ledger_id = Custom::create_ledger($hrm_employee->first_name."_".$hrm_employee->last_name."_".$hrm_employee->employee_code, $organization, $hrm_employee->first_name." ".$hrm_employee->last_name." ".$hrm_employee->employee_code, $personal_ledger->id, $hrm_employee->person_id, null, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
		$hrm_employee->save();

		$employee_id = $hrm_employee->id;

		$organization_person = DB::table('organization_person')->where('person_id', $hrm_employee->person_id)->where('organization_id', $organization_id)->first();

      	if($organization_person == null) {
        	DB::table('organization_person')->insert(['person_id' => $hrm_employee->person_id, 'organization_id' => $organization_id]);
      	}



		$work_periods = new HrmEmployeeWorkingPeriod;
		$work_periods->employee_id = $employee_id;
		if($request->input('joined_date') != null){
			$work_periods->joined_date = ($request->input('joined_date')!=null) ? Carbon::parse($request->input('joined_date'))->format('Y-m-d') : null;
		}		
		if($request->input('branch_id') != null){
			$work_periods->branch_id = $request->input('branch_id');
		}
		$work_periods->save();


		$designation = DB::table('hrm_employee_designation')->insert([
			'employee_id' => $employee_id, 
			'designation_id' =>($request->input('designation_id') != null) ? $request->input('designation_id') : null
		]);

		if($request->input('address') != null)
		{
			$employee_address = new HrmEmployeeAddress;
			$employee_address->employee_id = $employee_id;
			$employee_address->person = $request->input('first_name');
			$employee_address->address = $request->input('address');
			$employee_address->city_id = $request->input('city_id');
			$employee_address->pin = $request->input('pin');
			$employee_address->google = $request->input('google');
			$employee_address->address_type = 0;
			$employee_address->save();
		}
		if($request->input('address') != null)
		{
			$employee_address = new HrmEmployeeAddress;
			$employee_address->employee_id = $employee_id;
			$employee_address->person = $request->input('first_name');
			$employee_address->address = $request->input('address');
			$employee_address->city_id = $request->input('city_id');
			$employee_address->pin = $request->input('pin');
			$employee_address->google = $request->input('google');
			$employee_address->address_type = 1;
			$employee_address->save();
		}

		Custom::userby($hrm_employee, true);

		$employees = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','genders.display_name as gender','blood_groups.display_name as blood_group')
		->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
	   	->where('hrm_employees.id',$hrm_employee->id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Employees'.config('constants.flash.added'), 'data' => ['id' => $employees->id, 'name' => $employees->first_name,'code'=>$employees->employee_code,'phone_no'=>$employees->phone_no, 'email'=> $employees->email, 'blood_group'=> $employees->blood_group,'gender' => $employees->gender]]);
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

		$staff_type = HrmStaffType::pluck('display_name','id');
		$staff_type->prepend('Select Staff','');

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Select Department', '');

		$designation  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation->prepend('Select Designation', '');

		$branch = HrmBranch::where('organization_id', $organization_id)->pluck('branch_name', 'id');
		$branch->prepend('Select Branch', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$blood_groups = BloodGroup::pluck('display_name','id');
		$blood_groups->prepend('Select Blood Groups','');

		$marital_status = MaritalStatus::pluck('display_name','id');
		$marital_status->prepend('Select Marital Status','');

		$license_type = LicenseType::pluck('display_name','id');
		$license_type->prepend('Select License Type','');

		$job_type = HrmEmploymentType::where('organization_id', $organization_id)->pluck('name', 'id');
		$job_type->prepend('Select Job Type', '');

		$genders = Gender::select('genders.id','genders.display_name as name')->get();  
		$gender_array = array();

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');		


		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$people->prepend('Select Person', '');


		$official = HrmEmployee::select('hrm_employees.id','hrm_employees.person_id','hrm_employees.first_name','hrm_employees.last_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','hrm_employees.gender_id','hrm_employees.marital_status','hrm_employees.blood_group_id','hrm_employees.staff_type_id','hrm_staff_types.display_name as staff_type','genders.display_name as gender_name','blood_groups.display_name as blood_group','marital_statuses.display_name as marital_status_name','hrm_employees.title_id','people_titles.display_name AS title')    
	    ->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
	    ->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
	   	->leftjoin('marital_statuses','hrm_employees.marital_status','=','marital_statuses.id')
	   	->leftjoin('people_titles','hrm_employees.title_id','=','people_titles.id')
	   ->where('hrm_employees.id',$id)->first();

	   $work_periods = HrmEmployeeWorkingPeriod::select('hrm_employee_working_periods.joined_date','hrm_employee_working_periods.branch_id','hrm_employee_working_periods.employment_type_id','hrm_employment_types.id as job_type_id','hrm_employment_types.name as job_type','hrm_branches.branch_name')
	   ->leftjoin('hrm_employment_types','hrm_employee_working_periods.employment_type_id','=','hrm_employment_types.id')
	   ->leftjoin('hrm_branches','hrm_employee_working_periods.branch_id','=','hrm_branches.id')
	   ->where('hrm_employee_working_periods.employee_id',$id)->first();


	   $job = DB::table('hrm_employee_designation')
		->select('hrm_employee_designation.employee_id','hrm_employee_designation.designation_id','hrm_designations.name as designation_name','hrm_designations.department_id','hrm_departments.name as department_name')
	   ->leftjoin('hrm_designations','hrm_employee_designation.designation_id','=','hrm_designations.id')
	   ->leftjoin('hrm_departments','hrm_designations.department_id','=','hrm_departments.id')
	   ->where('hrm_employee_designation.employee_id',$id)->first();


	    $emp_address = HrmEmployeeAddress::select('hrm_employee_addresses.id as address_id','hrm_employee_addresses.person','hrm_employee_addresses.address','hrm_employee_addresses.city_id','hrm_employee_addresses.pin','hrm_employee_addresses.landmark','hrm_employee_addresses.google','cities.name as city_name','cities.state_id','states.name as state_name')    
	   ->leftjoin('cities','hrm_employee_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
	   ->where('hrm_employee_addresses.employee_id',$id)->first();

	   $city = [];

	   if(!empty($emp_address->city_id)) {
	   		$selected_city = City::where('id', $emp_address->city_id)->first();
		   	$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

			$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
			$city->prepend('Select City', '');
	   }

		
		return view('employees_edit', compact('id','people', 'state', 'title', 'payment', 'terms','blood_groups','department','designation','branch','genders','marital_status','license_type','job_type','bank','employee_salary_scale','staff_type','official','work_periods','job','emp_address','city'));
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
		//return $request->all();
		$organization_id = Session::get('organization_id');

		$hrm_employee =  HrmEmployee::where('id',$request->input('id'))->first();
		
		if($request->input('staff_type_id') != null){
			$hrm_employee->staff_type_id = $request->input('staff_type_id');
		}
		$hrm_employee->person_id = $request->input('person_id');

		if($request->input('title_id') != null){
			$hrm_employee->title_id = $request->input('title_id');
		}
		$hrm_employee->employee_code = $request->input('employee_code');
		$hrm_employee->first_name = $request->input('first_name');
		$hrm_employee->last_name = $request->input('last_name');
		$hrm_employee->email = $request->input('email');
		$hrm_employee->phone_no = $request->input('phone_no');
		
		if($request->input('gender_id') != null){
			$hrm_employee->gender_id = $request->input('gender_id');
		}
		
		if($request->input('blood_group_id') != null){
			$hrm_employee->blood_group_id = $request->input('blood_group_id');
		}
		
		if($request->input('marital_status') != null){
			$hrm_employee->marital_status = $request->input('marital_status');
		}
		$hrm_employee->save();
		Custom::userby($hrm_employee, false);

		$work_periods =  HrmEmployeeWorkingPeriod::where('employee_id',$request->input('id'))->first();

		if($request->input('joined_date') != null){
			$work_periods->joined_date = ($request->input('joined_date')!=null) ? Carbon::parse($request->input('joined_date'))->format('Y-m-d') : null;
		}
		
		if($request->input('branch_id') != null)
		{
			$work_periods->branch_id = $request->input('branch_id');
		}
		$work_periods->save();

		Custom::userby($work_periods, false);

		$designation = DB::table('hrm_employee_designation')
		->where('employee_id',$request->input('id'))
		->update(['designation_id' =>($request->input('designation_id') != null) ? $request->input('designation_id') : null
		]);

		if($request->input('address') != null)
		{
			$employee_address = HrmEmployeeAddress::where('employee_id', $request->input('id'))->first();
			$employee_address->employee_id = $request->input('id');
			$employee_address->person = $request->input('first_name');
			$employee_address->address = $request->input('address');
			$employee_address->city_id = $request->input('city_id');
			$employee_address->pin = $request->input('pin');
			$employee_address->google = $request->input('google');
			$employee_address->address_type = 0;
			$employee_address->save();

			Custom::userby($employee_address, false);
		}

		if($request->input('address') != null)
		{
			$employee_address = HrmEmployeeAddress::where('employee_id', $request->input('id'))->first();
			$employee_address->employee_id = $request->input('id');
			$employee_address->person = $request->input('first_name');
			$employee_address->address = $request->input('address');
			$employee_address->city_id = $request->input('city_id');
			$employee_address->pin = $request->input('pin');
			$employee_address->google = $request->input('google');
			$employee_address->address_type = 1;
			$employee_address->save();

			Custom::userby($employee_address, false);
		}

		$employees = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','genders.display_name as gender','blood_groups.display_name as blood_group')
		->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
	   	->where('hrm_employees.id',$hrm_employee->id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Employees'.config('constants.flash.added'), 'data' => ['id' => $employees->id, 'name' => $employees->first_name,'code'=>$employees->employee_code,'phone_no'=>$employees->phone_no, 'email'=> $employees->email, 'blood_group'=> $employees->blood_group,'gender' => $employees->gender]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$employee = HrmEmployee::findOrFail($request->input('id'));

		$person = DB::table('organization_person')->where('person_id', $employee->person_id)->where('organization_id',$organization_id);

		//dd($person);
		
		$person->delete();

		$people = People::where('person_id', $employee->person_id)->where('user_type', '0')->first();

		if($people != null) {
			$person_type_id = AccountPersonType::where('name', 'employee')->first()->id;
			$people_person = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id);

			if($people_person != null) {
				$people_person->delete();
			}
		}
		

		$employee->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$employees = explode(',', $request->id);

		$employee_list = [];

		foreach ($employees as $employee_id) {
			$employee_list[] = $employee_id;
			$employee = HrmEmployee::findOrFail($employee_id);

			$person = DB::table('organization_person')->where('person_id', $employee->person_id)->where('organization_id',$organization_id);

			//dd($person);
			
			$person->delete();

			$people = People::where('person_id', $employee->person_id)->where('user_type', '0')->first();

			if($people != null) {
				$person_type_id = AccountPersonType::where('name', 'employee')->first()->id;
				$people_person = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id);

				if($people_person != null) {
					$people_person->delete();
				}
			}
			
			$employee->status = 0;
			$employee->save();

			
			$employee->delete();
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Employee'.config('constants.flash.deleted'),'data'=>['list' => $employee_list]]);
	}
}
