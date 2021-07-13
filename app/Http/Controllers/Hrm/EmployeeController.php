<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmployeeAddedEmail;
use Illuminate\Support\Facades\Log;
use App\HrmEmployeeWorkingPeriod;
use App\HrmEmployeeExperience;
use App\HrmEmployeeEducation;
use App\HrmEmployeeAddress;
use App\AccountLedgerType;
use App\HrmEmploymentType;
use App\HrmEmployeeSalary;
use App\HrmEmployeeSkill;
use App\Jobs\SendSms;
use App\Notification\Service\SmsNotificationService;
use App\HrmDesignation;
use App\HrmSalaryScale;
use App\HrmDepartment;
use App\HrmShift;
use App\MaritalStatus;
use App\CustomerGroping;
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
use App\Person;
use App\Gender;
use App\Custom;
use App\State;
use App\City;
use App\Bank;
use App\Term;
use Validator;
use DateTime;
use Response;
use Session;
use Mail;
use Auth;
use DB;

class EmployeeController extends Controller
{
    public function __construct(SmsNotificationService $SmsNotificationService)
    {
        $this->SmsNotificationService = $SmsNotificationService;
    }
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

		$country_id = Country::where('name', 'India')->first()->id;
		$state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
		$state->prepend('Select State', '');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');
		$group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

		$employees = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','genders.display_name as gender','blood_groups.display_name as blood_group')
		->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
		->where('organization_id',$organization_id)
		->whereNull('deleted_at')
		->get();

		return view('hrm.employees',compact('employees', 'title', 'state', 'payment', 'terms','group_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		/*$person_id = Auth::user()->person_id;

		$employee = HrmEmployee::select('hrm_employees.id')
		->where('hrm_employees.organization_id', $organization_id)
		->where('hrm_employees.person_id', $person_id)
		->first();

		$selected_employee = ($employee != null) ? $employee->id : null;*/

		$own_branch = HrmBranch::select('hrm_branches.id')
		->where('hrm_branches.organization_id', $organization_id)		
		->first();
		$selected_branch = ($own_branch != null) ? $own_branch->id : null;

		$own_department = HrmDepartment::select('hrm_departments.id')
		->where('hrm_departments.organization_id', $organization_id)		
		->first();
		$selected_department = ($own_department != null) ? $own_department->id : null;

		$own_designation = HrmDesignation::select('hrm_designations.id')
		->where('hrm_designations.organization_id', $organization_id)		
		->first();
		$selected_designation = ($own_designation != null) ? $own_designation->id : null;

		$own_shift = HrmShift::select('hrm_shifts.id')
		->where('hrm_shifts.organization_id', $organization_id)		
		->first();
		$selected_shift = ($own_shift != null) ? $own_shift->id : null;

		$own_employment = HrmEmploymentType::select('hrm_employment_types.id')
		->where('hrm_employment_types.organization_id', $organization_id)		
		->first();
		$selected_employment = ($own_employment != null) ? $own_employment->id : null;

		$selected_staff = HrmStaffType::where('name', 'staff')->first()->id;


		$staff_type = HrmStaffType::pluck('display_name','id');
		$staff_type->prepend('Select Staff','');

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Select Department', '');

		$designation  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation->prepend('Select Designation', '');

		$shift  = HrmShift::where('organization_id',$organization_id)->pluck('name','id');
		$shift->prepend('Select Shift', '');



		$branch = HrmBranch::where('organization_id', $organization_id)->pluck('branch_name', 'id');
		$branch->prepend('Select Branch', '');

		$country_id = Country::where('name', 'India')->first()->id;
		$state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
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

		$ifsc_code = Bank::pluck('ifsc', 'id');
		$ifsc_code->prepend('Select IFSC', '');


		
		return view('hrm.employees_create', compact('people', 'state', 'title', 'payment', 'terms','blood_groups','department','designation','branch','genders','marital_status','license_type','job_type','bank','employee_salary_scale','staff_type','ifsc_code','selected_branch','selected_department','selected_designation','selected_staff','selected_shift','shift','selected_employment'));
	}

	public function ifsc_search(Request $request)
	{
		//dd($request->all());
		$organization_id = Session::get('organization_id');

		$keyword = $request->input('term');

		$query = Bank::select('banks.*');

		$query->where("banks.ifsc", 'LIKE', $keyword.'%');

		$ifsc_search = $query->take(10)->get();

		$ifsc_array = [];

		foreach ($ifsc_search as  $value ) {

			$ifsc_array[] = ['id' => $value->id, 'label' => $value->ifsc, 'value' => $value->ifsc,'bank_name' => $value->bank,'state_name'=>$value->state,'city_name'=>$value->city, 'branch_name'=> $value->branch,'micr_code' => $value->micr];			
		}		

		return response()->json($ifsc_array);

	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//dd($request->all());
	    Log::info('EmployeeController->Store:- Inside');
		$validator = Validator::make($request->all(), [
			'person_id'=>'required',
			'employee_code'=> 'required',
			'first_name'=>'required',
			'email'=>'required',
			'phone_no'=>'required',
			'gender_id'=>'required',
			'staff_type_id' => 'required',
			'employment_type_id' => 'required',
			'joined_date'=> 'required',		
			'branch_id'=> 'required',
			'department_id' => 'required',
			'designation_id'=> 'required',
			'shift_id'=> 'required',	
			'salary_scale_id'=> 'required',
			'payment_method_id'=> 'required',
		]);

		

		if ($validator->fails()) {    
		    return response()->json(['status' => 0, 'message' => $validator->messages()], 200);
		}
		
		
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

		$person = Person::find($request->input('person_id'));

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
		if($request->input('shift_id') != null){
			$hrm_employee->shift_id = $request->input('shift_id');
		}
		if($request->input('marital_status') != null){
			$hrm_employee->marital_status = $request->input('marital_status');
		}
		$hrm_employee->pan_no = $request->input('pan_no');
		$hrm_employee->aadhar_no = $request->input('aadhar_no');
		$hrm_employee->passport_no = $request->input('passport_no');
		if($request->input('license_type_id') != null){
			$hrm_employee->license_type_id = $request->input('license_type_id');
		}
		$hrm_employee->license_no = $request->input('license_no');
		
		$hrm_employee->organization_id = $organization_id;
		$hrm_employee->save();

		$ledgergroup = AccountGroup::where('name', 'employees')->where('organization_id', $organization_id)->first();
		$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
		$organization = Organization::findOrFail($organization_id);

		$hrm_employee->ledger_id = Custom::create_ledger($hrm_employee->first_name."_".$hrm_employee->last_name."_".$hrm_employee->employee_code, $organization, $hrm_employee->first_name." ".$hrm_employee->last_name." ".$hrm_employee->employee_code, $personal_ledger->id, $hrm_employee->person_id, null, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
		$hrm_employee->save();

		$employee_id = $hrm_employee->id;
		

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;
		
		$mobile = $hrm_employee->phone_no;
		$subject = " Add New User";
		$name = $hrm_employee->first_name."".$hrm_employee->last_name;
		$message = "Dear ".$hrm_employee->first_name.",". "\n\n" ."You have been added to ".$business_name. ". You can create your account with the Propel ID: ".$person->crm_code. " using the url ". route('propel_register_user');

		//$this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $hrm_employee->phone_no, $message));

		Custom::add_addon('sms');

		//$this->dispatch(new SendEmployeeAddedEmail(['name' => $hrm_employee->first_name, 'business' => $business_name, 'person_id' => $person->crm_code, 'url' => route('propel_register_user')], $hrm_employee->email, $business_name));
		$msg = $this->SmsNotificationService->save($mobile, $subject, $name, $message," ", "TRANSACTION");
		
		//Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $hrm_employee->phone_no, $message);

		$to_email = $hrm_employee->email;
        $to_name = $hrm_employee->first_name;
        $business = $business_name;
		$data = ['name' => $hrm_employee->first_name, 'business' => $business_name, 'person_id' => $person->crm_code, 'url' => route('propel_register_user')];
		Log::info('EmployeeController->Store:- Line No 351');
		Mail::send('emails.employee_added_mail', $data, function ($message) use ($to_email, $to_name, $business) {
            $message->from('support@propelsoft.in', 'PropelERP');
            $message->to($to_email, $to_name);
            $message->subject($business."added you as an Employee!");
        });


		$organization_person = DB::table('organization_person')->where('person_id', $hrm_employee->person_id)->where('organization_id', $organization_id)->first();

      	if($organization_person == null) {
        	DB::table('organization_person')->insert(['person_id' => $hrm_employee->person_id, 'organization_id' => $organization_id]);
      	}


		$work_periods = new HrmEmployeeWorkingPeriod;

		$work_periods->employee_id = $employee_id;

		if($request->input('joined_date') != null){

			$work_periods->joined_date = ($request->input('joined_date')!=null) ? Carbon::parse($request->input('joined_date'))->format('Y-m-d') : null;
		}
		$work_periods->employment_type_id = $request->input('employment_type_id');
		$work_periods->confirmation_period = $request->input('confirmation_period');
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
			$employee_address->person = $request->input('contact_person');
			$employee_address->address = $request->input('address');
			$employee_address->city_id = $request->input('city_id');
			$employee_address->pin = $request->input('pin');
			$employee_address->landmark = $request->input('land_mark');
			$employee_address->address_type = 0;
			$employee_address->save();
		}
		
		if($request->input('permanent_address') != null)
		{
			$employee_address = new HrmEmployeeAddress;
			$employee_address->employee_id = $employee_id;
			$employee_address->person = $request->input('permanent_contact_person');
			$employee_address->address = $request->input('permanent_address');
			$employee_address->city_id = $request->input('permanent_city_id');
			$employee_address->pin = $request->input('permanent_pin');
			$employee_address->landmark = $request->input('permanent_land_mark');
			$employee_address->address_type = 1;
			$employee_address->save();
		}		

		$employee_gender_id = $hrm_employee->gender_id;
		$employee_employment_type = $work_periods->employment_type_id;
		$employee_designation = HrmDesignation::find($request->input('designation_id'));
		$employee_designation_id = HrmDesignation::find($request->input('designation_id'))->id;
		$employee_department = HrmDepartment::find($employee_designation->department_id)->id;

		$leave_types = HrmLeaveType::where('organization_id', $organization_id)
		->where(function($query) use ($employee_gender_id) { return $query->where('applicable_gender', '=', $employee_gender_id)->orWhereNull('applicable_gender'); })

		->where(function($query) use ($employee_employment_type) { return $query->where('applicable_employment_type', '=', $employee_employment_type)->orWhereNull('applicable_employment_type'); })

		->where(function($query) use ($employee_department) { return $query->where('applicable_department', '=', $employee_department) ->orWhereNull('applicable_department'); })

		->where(function($query) use ($employee_designation_id) { return $query->where('applicable_designation', '=', $employee_designation_id) ->orWhereNull('applicable_designation'); })

		->where('status', 1)->get();

		foreach ($leave_types as $leave_type) {
			if($leave_type->effective_from == 0 || $leave_type->effective_from == null) {
				DB::table('hrm_employee_leaves')->insert([
					'employee_id' => $employee_id,
					'leave_type_id' => $leave_type->id,
					'remaining_leaves' => $leave_type->yearly_limit,
					'updated_date' => Carbon::now()->format('Y-m-d H:i:s')
				]);
			}
		}
		

		$qualification 	= $request->input('qualification');
		$institution 	= $request->input('institution');
		$city_id 		= $request->input('education_city_id');
		$year 			= $request->input('year');
		$percentage 	= $request->input('percentage');

		if($qualification != null && $institution != null)
		{
			if(count($qualification) > 0)
			{
				for($i=0;$i<count($qualification);$i++) {
					if($qualification[$i] != "") {
						DB::table('hrm_employee_educations')->insert([
							'qualification' => $qualification[$i],
							'institution' => $institution[$i],
							'city_id' => $city_id[$i],
							'year' => $year[$i],
							'percentage' => $percentage[$i],
							'employee_id'=> $employee_id
						]);
					}
				}
			}
		}

		$skill 			= $request->input('skill');
		$skill_level 	= $request->input('skill_level');
		$experience 	= $request->input('experience');		

		if($skill != null )
		{
			if(count($skill) > 0)
			{
				for($i=0;$i<count($skill);$i++) {
					if($skill[$i] != "") {
						DB::table('hrm_employee_skills')->insert([
							'skill' => $skill[$i],
							'skill_level' => $skill_level[$i],
							'experience' => $experience[$i],						
							'employee_id'=> $employee_id
						]);
					}
				}
			}
		}
		
		$previous_joined_date 	= $request->input('previous_joined_date');
		$previous_relieved_date = $request->input('previous_relieved_date');
		$organization_name 		= $request->input('organization_name');

		if($organization_name != null && $previous_joined_date != null)
		{
			if(count($organization_name) > 0)
			{
				for($i=0;$i<count($organization_name);$i++) {
					if($organization_name[$i] != "") {
						$pre_joined_date =  ($previous_joined_date[$i] !=null) ? Carbon::parse($previous_joined_date[$i])->format('Y-m-d') : null;
						$pre_relieved_date =  ($previous_relieved_date[$i] !=null) ? Carbon::parse($previous_relieved_date[$i])->format('Y-m-d') : null;

						DB::table('hrm_employee_experiences')->insert([
							'organization_name' => $organization_name[$i],
							'joined_date' => $pre_joined_date,
							'relieved_date' => $pre_relieved_date,
							'employee_id'=> $employee_id
						]);
					}
				}
			}
		}	

		if($request->input('salary_scale_id') != null)
		{
			$employee_salary = DB::table('hrm_employee_salary')->insert([
				'employee_id' => $employee_id,
				'salary_scale_id'=>  $request->input('salary_scale_id'),
				'payment_method_id' => $request->input('payment_method_id'),
				'ot_wage' => $request->input('ot_wage'),
				'organization_id' => $organization_id
			]);
		}

		$pay_head_id      = $request->input('pay_head_id');
		$payhead_value 	  = $request->input('payhead_value');

		if(count($pay_head_id) >0)
		{
			for($i=0; $i<count($pay_head_id); $i++)
			{
				$employee_payhead = DB::table('hrm_employee_pay_head')->insert([
					'employee_id'=>  $employee_id,
					'pay_head_id'=>  $pay_head_id[$i],
					'value' => $payhead_value[$i]
					]);
			}
		}

		if($request->input('account_no') != null && $request->input('ifsc') != null)
		{
			$employee_bank = DB::table('hrm_employee_bank')->insert([
				'employee_id' => $employee_id,
				'bank_id' => $request->input('bank_id'),
				'account_no' => $request->input('account_no'),
				'bank_name' => $request->input('bank_name'),
				'bank_branch' => $request->input('bank_branch'),
				'ifsc' => $request->input('ifsc'),
				'micr' => $request->input('micr'),
				'organization_id' => $organization_id
			]);
		}

		Custom::userby($hrm_employee, true);

		$employees = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name','hrm_employees.employee_code','hrm_employees.phone_no','hrm_employees.email','genders.display_name as gender','blood_groups.display_name as blood_group')
		->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
	   	->where('hrm_employees.id',$hrm_employee->id)
		->where('hrm_employees.organization_id',$organization_id)->first();
		Log::info('EmployeeController->Store:- Return');
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
		
		$organization_id = Session::get('organization_id');

		$staff_type = HrmStaffType::pluck('display_name','id');
		$staff_type->prepend('Select Staff','');

		$department = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$department->prepend('Select Department', '');

		$designation  = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designation->prepend('Select Designation', '');

		$shift  = HrmShift::where('organization_id',$organization_id)->pluck('name','id');
		$shift->prepend('Select Shift', '');

		$branch = HrmBranch::where('organization_id', $organization_id)->pluck('branch_name', 'id');
		$branch->prepend('Select Branch', '');

		
		$country_id = Country::where('name', 'India')->first()->id;
		
		$state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
		$state->prepend('Select State', '');

		//$cities  = City::pluck('name','id');
		//$cities->prepend('Select City', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$employee_salary_scale = HrmSalaryScale::where('organization_id', $organization_id)->where('status', 1)->pluck('name', 'id');
		$employee_salary_scale->prepend('Select Salary Scale', '');

		$blood_groups = BloodGroup::pluck('display_name','id');
		$blood_groups->prepend('Select Blood Groups','');

		$marital_status = MaritalStatus::pluck('display_name','id');
		$marital_status->prepend('Select Marital Status','');

		$license_type = LicenseType::pluck('display_name','id');
		$license_type->prepend('Select License Type','');

		$job_type = HrmEmploymentType::where('organization_id', $organization_id)->pluck('name', 'id');
		$job_type->prepend('Select Job Type', '');

		/*$genders = Gender::select('genders.display_name as gender','genders.id')->get();
		$gender_array = array();*/

		$genders = Gender::pluck('display_name','id');
		$genders->prepend('Select Gender','');

		$bank   = Bank::distinct()->get(['bank'])->pluck('bank', 'bank');
		$bank->prepend('Select Bank', '');


		$designations = HrmEmployee::select('hrm_employees.id','hrm_designations.id as designations_id','hrm_designations.name as designation_name','hrm_departments.id as departments_id','hrm_departments.name as department_name')
		->leftjoin('hrm_employee_designation','hrm_employees.id','=','hrm_employee_designation.employee_id')
		->leftjoin('hrm_designations','hrm_employee_designation.designation_id','=','hrm_designations.id')
		->leftjoin('hrm_departments','hrm_designations.department_id','=','hrm_departments.id')
		->where('hrm_employees.id',$id)->get();

	   
	   $official = HrmEmployee::select('hrm_employees.id',(DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS employee_name')),'hrm_employees.first_name','hrm_employees.last_name','hrm_employees.employee_code','hrm_employees.pan_no','hrm_employees.aadhar_no','hrm_employees.passport_no','hrm_employees.license_no','hrm_employees.license_type_id','hrm_employees.gender_id','hrm_employees.marital_status','hrm_employees.blood_group_id','hrm_employees.staff_type_id','license_types.display_name as license_type','hrm_staff_types.display_name as staff_type','genders.display_name as gender_name','blood_groups.display_name as blood_group','marital_statuses.display_name as marital_status_name','hrm_employees.email','hrm_employees.phone_no','hrm_shifts.name as shift_name','hrm_employees.shift_id')
	    ->leftjoin('license_types','hrm_employees.license_type_id','=','license_types.id')
	    ->leftjoin('hrm_staff_types','hrm_employees.staff_type_id','=','hrm_staff_types.id')
	    ->leftjoin('genders','hrm_employees.gender_id','=','genders.id')
	   	->leftjoin('blood_groups','hrm_employees.blood_group_id','=','blood_groups.id')
	   	 ->leftjoin('hrm_shifts','hrm_employees.shift_id','=','hrm_shifts.id')
	   	->leftjoin('marital_statuses','hrm_employees.marital_status','=','marital_statuses.id')
	   ->where('hrm_employees.id',$id)->first();


	   $work_periods = HrmEmployeeWorkingPeriod::select('hrm_employee_working_periods.joined_date','hrm_employee_working_periods.branch_id','hrm_employee_working_periods.employment_type_id','hrm_employee_working_periods.confirmation_period','hrm_employment_types.id as job_type_id','hrm_employment_types.name as job_type','hrm_branches.branch_name')
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


	   $emp_bank = DB::table('hrm_employee_bank')
	   ->select('hrm_employee_bank.id','hrm_employee_bank.bank_id','hrm_employee_bank.ifsc','hrm_employee_bank.bank_branch','hrm_employee_bank.micr','hrm_employee_bank.bank_name','hrm_employee_bank.account_type','hrm_employee_bank.account_no','banks.state','banks.city')
	   ->leftjoin('banks','banks.id','=','hrm_employee_bank.bank_id')
	   ->where('hrm_employee_bank.employee_id',$id)->first();

	   

	   $emp_skills = DB::table('hrm_employee_skills')->where('employee_id',$id)->get();
	   

	   	$city = [];

	   if(!empty($emp_address->city_id)) {
	   		$selected_city = City::where('id', $emp_address->city_id)->first();
		   	$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

			$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
			$city->prepend('Select City', '');
	   }

	   $employee_address = HrmEmployeeAddress::where('employee_id',$id)->orderby('address_type')->get();

	   $employee_educations = HrmEmployeeEducation::select('hrm_employee_educations.*','cities.name as city_name','cities.state_id','states.name as state_name')
	   ->leftjoin('cities','hrm_employee_educations.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
	   ->where('employee_id',$id)->get();

	   $employee_experiences = HrmEmployeeExperience::where('employee_id',$id)->get();

	   $employee_salary = DB::table('hrm_employee_salary')->select('hrm_employee_salary.*','hrm_salary_scales.name as salary_scale','payment_methods.name as payment_method','hrm_salary_scale_pay_head.value','hrm_pay_heads.name')
	   ->leftjoin('hrm_salary_scales','hrm_employee_salary.salary_scale_id','=','hrm_salary_scales.id')
	   ->leftjoin('payment_methods','hrm_employee_salary.payment_method_id','=','payment_methods.id')
	   ->leftjoin('hrm_salary_scale_pay_head','hrm_salary_scales.id','=','hrm_salary_scale_pay_head.salary_scale_id')
	   ->leftjoin('hrm_pay_heads','hrm_salary_scale_pay_head.pay_head_id','=','hrm_pay_heads.id')
	   ->where('employee_id',$id)->first();

	   $pay_heads = DB::table('hrm_employee_salary')->select('hrm_employee_salary.*','hrm_salary_scales.name as salary_scale','hrm_employee_pay_head.value','hrm_pay_heads.id as payhead_id','hrm_pay_heads.name as pay_head')
	   ->leftjoin('hrm_salary_scales','hrm_employee_salary.salary_scale_id','=','hrm_salary_scales.id')	   
	   ->leftjoin('hrm_employee_pay_head','hrm_employee_salary.employee_id','=','hrm_employee_pay_head.employee_id')
	   ->leftjoin('hrm_pay_heads','hrm_employee_pay_head.pay_head_id','=','hrm_pay_heads.id')
	   ->where('hrm_employee_salary.employee_id',$id)->get();	   


		return view('hrm.employee_view',compact('id','department','designation','branch','job_type','license_type','genders','blood_groups','marital_status','state','employee_educations','employee_experiences','employee_salary','payment','employee_salary_scale','designations','pay_heads','city','staff_type','official','work_periods','job','employee_address','emp_address','emp_bank','shift','emp_skills'));
	}

	public function salary_scale(Request $request)
	{
		//return $request->all();
		$salary_scales = DB::table('hrm_salary_scale_pay_head')->select('hrm_salary_scale_pay_head.value','hrm_salary_scale_pay_head.pay_head_id','hrm_pay_heads.name as pay_head')	   
	   ->leftjoin('hrm_pay_heads','hrm_salary_scale_pay_head.pay_head_id','=','hrm_pay_heads.id')
	   ->where('hrm_salary_scale_pay_head.salary_scale_id', $request->salary_scale_id)->get();

	   return response()->json(['status' => 1, 'message' => 'Employees'.config('constants.flash.added'), 'data' => $salary_scales]);
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

	public function official_info_update(Request $request)
	{
		//return $request->all();

		$organization_id = Session::get('organization_id');	

		$hrm_employee =  HrmEmployee::where('id',$request->input('id'))->first();

		$hrm_employee->first_name = $request->input('first_name');
		$hrm_employee->last_name = $request->input('last_name');
		$hrm_employee->employee_code = $request->input('employee_code');
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

		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);
	}

	public function personal_info_update(Request $request)
	{
		//return $request->all();

		$organization_id = Session::get('organization_id');

		$work_periods =  HrmEmployeeWorkingPeriod::where('employee_id',$request->input('employee_id'))->first();

		if($request->input('joined_date') != null){
			$work_periods->joined_date = ($request->input('joined_date')!=null) ? Carbon::parse($request->input('joined_date'))->format('Y-m-d') : null;
		}
		
		$work_periods->employment_type_id = $request->input('employment_type_id');
		$work_periods->confirmation_period = $request->input('confirmation_period');
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

		$hrm_employee =  HrmEmployee::where('id',$request->input('employee_id'))->first();

		
		$hrm_employee->pan_no = $request->input('pan_no');
		$hrm_employee->aadhar_no = $request->input('aadhar_no');
		$hrm_employee->passport_no = $request->input('passport_no');
		if($request->input('license_type_id') != null){
			$hrm_employee->license_type_id = $request->input('license_type_id');
		}
		$hrm_employee->license_no = $request->input('license_no');
		$hrm_employee->shift_id = $request->input('shift_id');
		$hrm_employee->staff_type_id = $request->input('staff_type_id');
		
		$hrm_employee->save();

		Custom::userby($hrm_employee, false);

		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);
	}

	public function contact_info_update(Request $request)
	{
		//return $request->all();

		$organization_id = Session::get('organization_id');		

					

		$present_address =  HrmEmployeeAddress::where('id', $request->input('present_id'))->first();
		$permanent_address = HrmEmployeeAddress::where('id', $request->input('permanent_id'))->first();



		if($present_address != null){
			$present_address->person = $request->input('contact_person');
			$present_address->address = $request->input('address');
			$present_address->city_id = $request->input('city_id');
			$present_address->pin = $request->input('pin');
			$present_address->landmark = $request->input('landmark');
			$present_address->address_type = 0;
			$present_address->save();
		} 
		else
		{
			if($request->input('address') != null)
			{
				$employee_address = new HrmEmployeeAddress;
				$employee_address->employee_id = $request->input('employee_id');
				$employee_address->person 	= $request->input('contact_person');
				$employee_address->address 	= $request->input('address');
				$employee_address->city_id 	= $request->input('city_id');
				$employee_address->pin 		= $request->input('pin');
				$employee_address->landmark 	= $request->input('landmark');
				$employee_address->address_type = 0;
				$employee_address->save();
				Custom::userby($employee_address, false);
			}
		}
		
		if($permanent_address != null)
		{
			$permanent_address->person  = $request->input('permanent_contact_person');
			$permanent_address->address = $request->input('permanent_address');
			$permanent_address->city_id = $request->input('permanent_city_id');
			$permanent_address->pin  	= $request->input('permanent_pin');
			$permanent_address->landmark  = $request->input('permanent_landmark');
			$permanent_address->address_type = 1;
			$permanent_address->save();
		}
		else
		{
			if($request->input('permanent_address') !=null)
			{
				$permanent = new HrmEmployeeAddress;
				$permanent->employee_id = $request->input('employee_id');
				$permanent->person 		= $request->input('permanent_contact_person');
				$permanent->address 	= $request->input('permanent_address');
				$permanent->city_id 	= $request->input('permanent_city_id');
				$permanent->pin 		= $request->input('permanent_pin');
				$permanent->landmark 		= $request->input('permanent_landmark');
				$permanent->address_type = 1;
				$permanent->save();
			}
		}

		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);
	}



	public function education_info_update(Request $request)
	{
		$employee_educations = HrmEmployeeEducation::find($request->input('id'));

		//dd($employee_educations);

		if($employee_educations != null )
		{
			$employee_education =  $employee_educations;
		}
		else{
			$employee_education =  new HrmEmployeeEducation;
		}

		$employee_education->employee_id = $request->input('employee_id');
		$employee_education->qualification = $request->input('qualification');
		$employee_education->institution = $request->input('institution');
		$employee_education->city_id = $request->input('education_city_id');
		$employee_education->year = $request->input('year');
		$employee_education->percentage = $request->input('percentage');
		$employee_education->save();
		
		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => ['id' => $employee_education->id]]);
	}

	public function employee_skills_update(Request $request)
	{
		$employee_skills = HrmEmployeeSkill::find($request->input('id'));

		if($employee_skills != null)
		{
			$employee_skill = $employee_skills;
		}
		else
		{
			$employee_skill = new HrmEmployeeSkill;
		}
			
		$employee_skill->employee_id = $request->input('employee_id');
		$employee_skill->skill = $request->input('skill');
		$employee_skill->skill_level = $request->input('skill_level');
		$employee_skill->experience = $request->input('experience');
		$employee_skill->save();
				
		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => ['id' => $employee_skill->id]]);
	}

	public function employee_experience_update(Request $request)
	{
		$employee_experiences = HrmEmployeeExperience::find($request->input('id'));

		if($employee_experiences != null)
		{
			$employee_experience = $employee_experiences;
		}
		else
		{
			$employee_experience = new HrmEmployeeExperience;
		}
			
		$employee_experience->employee_id = $request->input('employee_id');
		$employee_experience->organization_name = $request->input('organization_name');
		$employee_experience->joined_date = ($request->input('previous_joined_date')!=null) ? Carbon::parse($request->input('previous_joined_date'))->format('Y-m-d') : null;
		$employee_experience->relieved_date = ($request->input('previous_relieved_date')!=null) ? Carbon::parse($request->input('previous_relieved_date'))->format('Y-m-d') : null;
		$employee_experience->save();
				
		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => ['id' => $employee_experience->id]]);
	}

	public function salary_info_update(Request $request)
	{
		//return $request->all();
		$organization_id = Session::get('organization_id');

		$emp_salary = DB::table('hrm_employee_salary')->where('employee_id',$request->input('id'))->first();

		if($emp_salary != null)
		{
			$employee_salary = DB::table('hrm_employee_salary')->where('employee_id',$request->input('id'))->update([
			'salary_scale_id'=>  $request->input('salary_scale_id'),
			'payment_method_id' => $request->input('payment_method_id'),
			'ot_wage' => $request->input('ot_wage')
			]);
		}
		else{
			
			$employee_salary = DB::table('hrm_employee_salary')->insert([
			'salary_scale_id'=>  $request->input('salary_scale_id'),
			'payment_method_id' => $request->input('payment_method_id'),
			'ot_wage' => $request->input('ot_wage'),
			'employee_id' => $request->input('id'),
			'organization_id' => $organization_id
			]);
		}

		$pay_head_id      = $request->input('pay_head_id');
		$payhead_value 	  = $request->input('payhead_value');


		if(count($pay_head_id) >0)
		{
			DB::table('hrm_employee_pay_head')->where('employee_id', $request->input('id'))->delete();
			for($i=0; $i<count($pay_head_id); $i++)
			{
				$employee_payhead = DB::table('hrm_employee_pay_head')->insert([
					'employee_id'=>  $request->input('id'),
					'pay_head_id'=>  $pay_head_id[$i],
					'value' => $payhead_value[$i]
					]);
			}
		}

		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);	
	}

	public function bank_info_update(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$emp_bank = DB::table('hrm_employee_bank')->where('employee_id',$request->input('id'))->first();

		if($emp_bank != null)
		{
			$employee_bank = DB::table('hrm_employee_bank')->where('employee_id',$request->input('id'))->update([
				'account_no' => $request->input('account_no'),
				'bank_name' => $request->input('bank_name'),
				'bank_branch' => $request->input('bank_branch'),
				'bank_id' => $request->input('bank_id'),
				'ifsc' => $request->input('ifsc'),
				'micr' => $request->input('micr')
			]);
		}
		else{
			if($request->input('account_no') != null)
			{
			$employee_bank = DB::table('hrm_employee_bank')->insert([
				'account_no' => $request->input('account_no'),
				'bank_name' => $request->input('bank_name'),
				'bank_branch' => $request->input('bank_branch'),
				'bank_id' => $request->input('bank_id'),
				'ifsc' => $request->input('ifsc'),
				'micr' => $request->input('micr'),
				'employee_id' => $request->input('id'),
				'organization_id' => $organization_id
			]);
			}
		}


		return response()->json(['status' => 1, 'message' => 'Employee'.config('constants.flash.updated'), 'data' => []]);
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

	public function experience_delete(Request $request)
	{
		//return $request->all();
		$experience = HrmEmployeeExperience::findOrFail($request->input('id'));
		$experience->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Employee Experience'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function education_delete(Request $request)
	{
		//return $request->all();
		$education = HrmEmployeeEducation::findOrFail($request->input('id'));
		$education->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Employee Education'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function skill_delete(Request $request)
	{
		//return $request->all();
		$skill = HrmEmployeeSkill::findOrFail($request->input('id'));
		$skill->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Employee Skill'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function employee_image_upload(Request $request) {

		$file = $request->file('file');
		$id = $request->input('id');

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$path_array = explode('/', 'organizations/'.$business_name.'/employees');

		$public_path = '';

		foreach ($path_array as $p) {
			$public_path .= $p."/";
			if (!file_exists(public_path($public_path))) {
				mkdir(public_path($public_path), 0777, true);
			}
		}


		$name = $id.".".$file->getClientOriginalName();

		return $request->file('file')->move(public_path($public_path), $name);

	}

	public function employee_file_upload(Request $request) {

		$file = $request->file('file');
		$id = $request->input('id');

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$path_array = explode('/', 'organizations/'.$business_name.'/employees');

		$public_path = '';

		foreach ($path_array as $p) {

			$public_path .= $p."/";

			if (!file_exists(public_path($public_path))) {
				mkdir(public_path($public_path), 0777, true);
			}
		}		


		$dt = new DateTime();

		$name = "_".$id."_".$dt->format('Y-m-d-H-i-s').".".$file->getClientOriginalName();		

		return $request->file('file')->move(public_path($public_path), $name);

	}

	public function check_employee(Request $request) {

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
			echo 'false';
		} else {
			echo 'true';
		}

	}
}
