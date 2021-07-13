<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmSalaryPayhead;
use App\HrmSalaryScale;
use App\HrmAttendance;
use App\HrmOtRegister;
use App\AccountLedger;
use App\AccountEntry;
use App\HrmEmployee;
use App\HrmWeekoff;
use App\HrmHoliday;
use App\HrmSalary;
use App\HrmLeave;
use App\AccountVoucher;
use App\User;
use Carbon\Carbon;
use App\Custom;
use Session;
use Auth;
use DB;

class PayrollController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
public function index()
	{
		$organization_id = Session::get('organization_id');

		$user = User::find(Auth::user()->id);

		$person_id = Auth::user()->person_id;

		//dd($person_id);

		$employee_id = HrmEmployee::where('organization_id', $organization_id)
		->where('person_id',$person_id)->first()->id;

		$query = HrmSalary::select('hrm_salaries.id','hrm_salaries.employee_id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee'), 'hrm_salaries.status','hrm_salaries.gross_salary', 'hrm_salaries.batch');

		$query->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
		$query->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id');
		$query->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id');
		$query->where(function ($query) {
				$query->where('hrm_salaries.status', 1)
				->orWhere('hrm_salaries.status', 0);
		});
		$query->whereBetween('hrm_salaries.salary_date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()]);
		$query->where('hrm_salaries.organization_id', $organization_id);
		// $query->groupby('hrm_salaries.batch');
		$query->orderby('hrm_salaries.salary_date');

		if(!$user->can('payroll-approval')){
			$query->where('hrm_salaries.employee_id',$employee_id);
			$query->where('hrm_salaries.status', 1);
		}

		$salaries = $query->get();


		//dd($employee_id);


		// $query = HrmSalary::select('hrm_salaries.id','hrm_salaries.employee_id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee'), 'hrm_salaries.status',DB::Raw('ROUND(SUM(hrm_salaries.gross_salary), 2) AS gross_salary'), 'hrm_salaries.batch');

		// $query->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
		// $query->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id');
		// $query->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id');
		// $query->where(function ($query) {
		// 		$query->where('hrm_salaries.status', 1)
		// 		->orWhere('hrm_salaries.status', 0);
		// });
		// $query->whereBetween('hrm_salaries.salary_date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()]);
		// $query->where('hrm_salaries.organization_id', $organization_id);
		// $query->groupby('hrm_salaries.batch');

		// if(!$user->can('payroll-approval')){
		// 	$query->where('hrm_salaries.employee_id',$employee_id);
		// 	$query->where('hrm_salaries.status', 1);
		// }

		// $salaries = $query->get();

		return view('hrm.payroll', compact('salaries'));
	}
	public function hrm_salary_status(Request $request){
		
		$salary_date = Carbon::parse($request->salary_date)->format('Y-m-d');

		$salary_month = Carbon::parse($request->salary_date)->format('m');	
		
		$this_month=date('m');

		
		$organization_id = Session::get('organization_id');
		$user = User::find(Auth::user()->id);          
		$person_id = Auth::user()->person_id;
		$employee_id = HrmEmployee::where('organization_id', $organization_id)
		->where('person_id',$person_id)->first()->id;
		if($request->salary_date!=null&$request->type_status==null)
		{
		

		 $query = HrmSalary::select('hrm_salaries.id','hrm_salaries.employee_id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee'), 'hrm_salaries.status','hrm_salaries.gross_salary','hrm_salaries.batch');

				$query->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
				$query->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id');
				$query->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id');
				
				$query->where(function ($query) {
						$query->where('hrm_salaries.status', 1)
						->orWhere('hrm_salaries.status', 0);
					});
			

				$query->whereMonth('hrm_salaries.salary_date', '=', $salary_month);
				// $query->whereBetween('hrm_salaries.salary_date', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()]);
				$query->where('hrm_salaries.organization_id', $organization_id);
			

				if(!$user->can('payroll-approval'))
				{
					$query->where('hrm_salaries.employee_id',$employee_id);
					$query->where('hrm_salaries.status', 1);
				}
				
				$salaries = $query->get();
			
				 return response()->json(['status' => 1 ,'data' => $salaries]);
		}
		else if($request->salary_date==null&$request->type_status!=null){
		
				$query = HrmSalary::select('hrm_salaries.id','hrm_salaries.employee_id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee'), 'hrm_salaries.status','hrm_salaries.gross_salary','hrm_salaries.batch');

				$query->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
				$query->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id');
				$query->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id');
				if($request->type_status ==1)
				{		
					$query->where('hrm_salaries.status', 1);
						
				}
				else if($request->type_status ==2){
				
					$query->where('hrm_salaries.status', 0);
				}
				else
				{
					$query->where(function ($query) {
						$query->where('hrm_salaries.status', 1)
						->orWhere('hrm_salaries.status', 0);
					});
				}
				
				$query->whereMonth('hrm_salaries.salary_date', '=', $this_month);
			
				
				$query->where('hrm_salaries.organization_id', $organization_id);
				

				if(!$user->can('payroll-approval'))
				{
					$query->where('hrm_salaries.employee_id',$employee_id);
					$query->where('hrm_salaries.status', 1);
				}
			
				$salaries = $query->get();

			
				 return response()->json(['status' => 1 ,'data' => $salaries]);

		}
		else
		{
		
			$query = HrmSalary::select('hrm_salaries.id','hrm_salaries.employee_id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee'), 'hrm_salaries.status', 'hrm_salaries.gross_salary', 'hrm_salaries.batch');

			$query->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
			$query->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id');
			$query->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id');
			if($request->type_status ==1)
			{		
				$query->where('hrm_salaries.status', 1);
					
			}
			else if($request->type_status ==2){
			
				$query->where('hrm_salaries.status', 0);
			}
			else
			{
				$query->where(function ($query) {
					$query->where('hrm_salaries.status', 1)
					->orWhere('hrm_salaries.status', 0);
				});
			}
			
			$query->whereMonth('hrm_salaries.salary_date', '=', $salary_month);
		
			
			$query->where('hrm_salaries.organization_id', $organization_id);
			

			if(!$user->can('payroll-approval'))
			{
				$query->where('hrm_salaries.employee_id',$employee_id);
				$query->where('hrm_salaries.status', 1);
			}
		
			$salaries = $query->get();
		
			 return response()->json(['status' => 1 ,'data' => $salaries]);

	    }
    }

	//Generate Payroll
	public function payroll()
	{
		$organization_id = Session::get('organization_id');

		$salary_scale = HrmSalaryScale::where('status', 1)
		->where('organization_id', $organization_id)
		->pluck('name', 'id');

		$salary_scale->prepend("Select Scale", "");

		$salary = HrmSalary::select('hrm_salaries.id', 'hrm_salaries.salary_date', 'hrm_salaries.over_time_hours', 'payment_methods.display_name AS payment_method', 'hrm_salary_scales.name AS salary_scale', DB::Raw('CONCAT(hrm_employees.first_name, " " ,hrm_employees.last_name) AS employee'), 'hrm_salaries.status')
		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
		->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id')
		->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id')
		->where('hrm_salaries.status', 1)->where('hrm_salaries.organization_id', $organization_id)
		->groupby('hrm_salaries.id')->get();

		return view('hrm.payroll_create', compact('salary_scale',  'salary'));
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

	//get all salary scale fields
	public function get_salary_scale(Request $request)
	{
		 return HrmSalaryScale::select('hrm_pay_heads.display_name AS name')
		->leftjoin('hrm_salary_scale_pay_head', 'hrm_salary_scale_pay_head.salary_scale_id', '=', 'hrm_salary_scales.id')
		->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_salary_scale_pay_head.pay_head_id')
		->where('hrm_salary_scales.id', $request->id)
		->where('hrm_pay_heads.status', 1)
		->groupby('hrm_pay_heads.id')
		->get();
	}

	//get all employee salary values
	public function get_employee_salary(Request $request)
	{

		$organization_id = Session::get('organization_id');

		$date = Carbon::createFromFormat('d-m-Y', $request->date);

		$salaries = [];

		$employees = HrmEmployee::select('hrm_employees.id', 'hrm_employees.first_name', 'hrm_employee_salary.salary_scale_id', 'hrm_employees.ledger_id')
		->leftjoin('hrm_employee_salary', 'hrm_employee_salary.employee_id', '=', 'hrm_employees.id')
		->leftjoin('hrm_salary_scales', 'hrm_employee_salary.salary_scale_id', '=', 'hrm_salary_scales.id')
		->where('hrm_salary_scales.id', $request->id)
		->get();

		$frequency = HrmSalaryScale::select('hrm_payroll_frequencies.frequency_type')
		->leftjoin('hrm_payroll_frequencies', 'hrm_payroll_frequencies.id', '=', 'hrm_salary_scales.frequency_id')
		->where('hrm_salary_scales.id', $request->id)
		->first()->frequency_type;

		foreach ($employees as $employee) {

				$existing_salaries = HrmSalaryScale::select('hrm_salary_scales.id', 'hrm_pay_heads.id AS pay_head', 'hrm_salary_payheads.value', 'hrm_pay_heads.display_name', 'hrm_pay_heads.name AS  pay_head_name','hrm_pay_heads.formula','hrm_pay_heads.wage_type','hrm_pay_heads.fixed_month','hrm_pay_heads.ledger_id','hrm_pay_heads.fixed_days','hrm_pay_heads.minimum_attendance', 'hrm_pay_heads.calculation_type', 'hrm_pay_head_types.name AS pay_head_type', 'hrm_salaries.status', 'hrm_employee_pay_head.pay_head_id', 'hrm_employee_salary.payment_method_id', DB::Raw('COALESCE(ot_wage, 0) AS ot'));
				$existing_salaries->leftjoin('hrm_salaries', 'hrm_salaries.salary_scale_id', '=', 'hrm_salary_scales.id');
				$existing_salaries->leftjoin('hrm_employee_salary', 'hrm_employee_salary.salary_scale_id', '=', 'hrm_salary_scales.id');
				$existing_salaries->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id');
				$existing_salaries->leftjoin('hrm_employee_pay_head', 'hrm_employee_pay_head.employee_id', '=', 'hrm_employee_salary.employee_id');
				$existing_salaries->leftjoin('hrm_salary_payheads', 'hrm_salary_payheads.pay_head_id', '=', 'hrm_employee_pay_head.pay_head_id');
				$existing_salaries->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_employee_pay_head.pay_head_id');
				$existing_salaries->leftjoin('hrm_pay_head_types', 'hrm_pay_head_types.id', '=', 'hrm_pay_heads.payhead_type_id');
				$existing_salaries->where('hrm_salary_scales.id', $request->id);
				$existing_salaries->where(function ($query) {
								    $query->where('hrm_salaries.status', 1)
								          ->orWhere('hrm_salaries.status', 0);
								});
				$existing_salaries->where('hrm_employees.id', $employee->id);

				if($frequency == 0) { // Daily
					$existing_salaries->whereBetween('hrm_salaries.salary_date', [$date->toDateString(), $date->toDateString()]);
				}
				else if($frequency == 1) { // Weekly
					$existing_salaries->whereBetween('hrm_salaries.salary_date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);
				}
				else if($frequency == 2) { // Monthly
					$existing_salaries->whereBetween('hrm_salaries.salary_date', [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()]);
				}


				$existing_salaries->groupby('hrm_pay_heads.id');
				$existing_salary = $existing_salaries->get();

				if(count($existing_salary) > 0) {
				   $salary_scale = [];//$existing_salary;
				} else {
					$salary_scale = HrmSalaryScale::select('hrm_salary_scales.id', 'hrm_pay_heads.id AS pay_head', 'hrm_employee_pay_head.value', 'hrm_pay_heads.display_name', 'hrm_pay_heads.name AS  pay_head_name','hrm_pay_heads.formula','hrm_pay_heads.wage_type','hrm_pay_heads.fixed_month','hrm_pay_heads.ledger_id','hrm_pay_heads.fixed_days','hrm_pay_heads.minimum_attendance', 'hrm_pay_heads.calculation_type', 'hrm_pay_head_types.name AS pay_head_type', DB::Raw('0 AS status'), 'hrm_employee_pay_head.pay_head_id', 'hrm_employee_salary.payment_method_id', DB::Raw('COALESCE(ot_wage, 0) AS ot'))
					->leftjoin('hrm_employee_salary', 'hrm_employee_salary.salary_scale_id', '=', 'hrm_salary_scales.id')
					->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_employee_salary.employee_id')
					->leftjoin('hrm_employee_pay_head', 'hrm_employee_pay_head.employee_id', '=', 'hrm_employee_salary.employee_id')
					->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_employee_pay_head.pay_head_id')
					->leftjoin('hrm_pay_head_types', 'hrm_pay_head_types.id', '=', 'hrm_pay_heads.payhead_type_id')
					->where('hrm_salary_scales.id', $request->id)
					->where('hrm_pay_heads.status', 1)
					->where('hrm_employee_salary.employee_id', $employee->id)
					->groupby('hrm_pay_heads.id')
					->get();
				}


				
				$status = null;
				$overtime = 0;

				if(count($salary_scale) > 0) { 
					$payment_method_id = $salary_scale[0]->payment_method_id;

					//Formatted salary data
					$data = $this->get_salary_amount($salary_scale, $date, $employee->id, $frequency);

					$salaries[] = ['employee_id' => $employee->id, 'ledger_id' => $employee->ledger_id, 'employee' => $employee->first_name, 'salary' => $data['salary'], 'total' => $data['total'], 'overtime' => $overtime, 'payment_method' => $payment_method_id, 'status' => $status, 'frequency' => $frequency];
				}

				

				
				
		}

		return response()->json($salaries);
	}

	//Recursive fubnction to get parent salary
	public function get_parent_salary($employee, $pay_head, $amount) {

		$pays = DB::table('hrm_employee_pay_head')->select('hrm_employee_pay_head.pay_head_id AS id','hrm_pay_head_parent.pay_head_id AS parent','hrm_employee_pay_head.employee_id','hrm_pay_heads.calculation_type','hrm_pay_heads.formula','hrm_employee_pay_head.value')
		->leftjoin('hrm_pay_head_parent', 'hrm_pay_head_parent.pay_head_parent_id', '=', 'hrm_employee_pay_head.pay_head_id')
		->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_employee_pay_head.pay_head_id')
		->where('hrm_employee_pay_head.pay_head_id', $pay_head)
		->where('hrm_employee_pay_head.employee_id', $employee)
		->get();

		$value = 0;

		foreach ($pays as $pay) {
			if($pay->parent != null) {
				if($pay->calculation_type != 0) {
					$value += $this->get_parent_salary($employee, $pay->parent, $pay->value) * ($amount / 100);
				}
			} else {
				$value = $pay->value;
			}
 
		}
		return $value;
	}

	//get formatted salary
	public function get_salary_amount($salary_scale, $date, $employee_id, $frequency, $employee_ledger = null) {

		$subtotal = 0;
		$earnings = 0;
		$deductions = 0;
		$overtime = 0;
		$subtotal_array = [];
		$earning_array = [];
		$deduction_array = [];
		$data = [];
		$salary_detail = [];

		foreach ($salary_scale as $salary) {

					$total_working_days = 0;
					$total_working_hours = 0;
					$full_days = 0;

					//Calendar Month
					if($salary->fixed_month == 1) {
						$total_days = $date->daysInMonth;//$salary->fixed_days;
					} else {
						$total_days = $salary->fixed_days;
					}

					$ot_registers = HrmOtRegister::select(DB::raw('COALESCE(SUM(hrm_ot_registers.over_time_hours), 0) AS over_time_hours'));

					if($frequency == 0) { // Daily
						$ot_registers->whereBetween('hrm_ot_registers.attended_date', [$date->toDateString(), $date->toDateString()]);
					}
					else if($frequency == 1) { // Weekly
						$ot_registers->whereBetween('hrm_ot_registers.attended_date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);
					}
					else if($frequency == 2) { // Monthly
						$ot_registers->whereBetween('hrm_ot_registers.attended_date', [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()]);
					}

					


					$ot_registers->where('hrm_ot_registers.employee_id', $employee_id);
					$ot_registers->where('hrm_ot_registers.status', 1);

					//Total worked hours
					$over_time = $ot_registers->first()->over_time_hours;

					//Amount = Total worked hours * Ot Wages
					$overtime = $over_time * $salary->ot;


					if($salary->wage_type == 0) { // Hour Based
						$total_attendance = HrmAttendance::select( 'hrm_attendance_types.paid_status', 'hrm_attendances.in_time', 'hrm_attendances.out_time','hrm_attendance_types.name');
					}
					else if( $salary->wage_type == 1 || $salary->wage_type == 2) { // Date and Month Based*/
						$total_attendance = HrmAttendance::select( 'hrm_attendance_types.paid_status', 'hrm_attendances.in_time', 'hrm_attendances.out_time', DB::raw('COUNT(hrm_attendance_types.id) AS attendance'), 'hrm_attendance_types.name');
					}
					

					$total_attendance->leftjoin('hrm_attendance_types', 'hrm_attendance_types.id', '=', 'hrm_attendances.attendance_type_id');

					if($frequency == 0) { // Daily
						$total_attendance->whereBetween('hrm_attendances.attended_date', [$date->toDateString(), $date->toDateString()]);
					}
					else if($frequency == 1) { // Weekly
						$total_attendance->whereBetween('hrm_attendances.attended_date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);
					}
					else if($frequency == 2) { // Monthly
						$total_attendance->whereBetween('hrm_attendances.attended_date', [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()]);
					}


					$total_attendance->where('hrm_attendances.employee_id', $employee_id);
					$total_attendance->where('hrm_attendances.status', 1);

					if( $salary->wage_type == 1 || $salary->wage_type == 2) { // Date and Month Based
						$total_attendance->groupby('hrm_attendances.attendance_type_id');
					}

					$total_attendances = $total_attendance->get();

					foreach ($total_attendances as $attendance) { 
						$full_days += $attendance->attendance; // Total days in the frequent period.
						if($attendance->paid_status == 1) {

							$total_working_days += $attendance->attendance; // Total No of working days in the frequent period.

							if( $attendance->in_time != null && $attendance->out_time != null ) {
								$in_time = Carbon::parse($attendance->in_time);
								$out_time = Carbon::parse($attendance->out_time);
								$total_working_hours += $out_time->diffInMinutes($in_time) / 60;
							}
						}
					}
					$value = 0;

					/* PAY HEAD CALCULATION STARTS */

					if($salary->calculation_type == 0) { //Flat
						$value = $salary->value;
					} else if($salary->calculation_type == 1) { //Percent
						if($salary->formula == 0) { // From Sub Total
							$subtotal_array[$salary->pay_head] = $salary->value;
							$value = "0000".$salary->pay_head.".00";
						} else if($salary->formula == 1) { // From Pay Head Group

							if($salary->calculation_type == 1) { // Percent
								//Recursive function
								$pay_group_value = $this->get_parent_salary($employee_id, $salary->pay_head_id, $salary->value);
							} else { // Flat
								$pay_group_value = $salary->value;
							}

							$value = $pay_group_value;
						} else if($salary->formula == 2) { // From Earnings
							$earning_array[$salary->pay_head] = $salary->value;
							$value = "0000".$salary->pay_head.".00";
						} else if($salary->formula == 3) { // From Deductions
							$deduction_array[$salary->pay_head] = $salary->value;
							$value = "0000".$salary->pay_head.".00";
						}
						
					}

					/* PAY HEAD CALCULATION ENDS */


					/* ATTENDANCE WAGE BASED CALCULATION STARTS */

					if(($salary->calculation_type == 0 && $salary->formula == null) || ($salary->calculation_type == 1 && $salary->formula == 1 )) {
						if($salary->wage_type == 0) { //Hour Based
							$value = $value * $total_working_hours;
						} else if($salary->wage_type == 1) { //Day Based
							$value = $value * $total_working_days;
						} else if($salary->wage_type == 2) { //Month Based
							if($salary->fixed_month == 0) { //Fixed Days
								$value = $value;
							} else if($salary->fixed_month == 1) {  //Calendar Month
								if($salary->minimum_attendance != '' && $salary->minimum_attendance != 0) {
									$value = ($total_days <= $full_days + $salary->minimum_attendance) ? $value : 0.00;
								} else {
									$value = $value;
								}
							}
						}
					}

					/* ATTENDANCE WAGE BASED CALCULATION ENDS */

					if($salary->pay_head_type == "earnings") {
						$data[$salary->pay_head_name] = $value;
						$salary_detail[] = ['pay_head_id' => $salary->pay_head, 'debit_ledger_id' => $salary->ledger_id, 'credit_ledger_id' => $employee_ledger, 'amount' => $value, 'pay_head' => $salary->pay_head_name];
						if(($salary->calculation_type == 0 && $salary->formula == null) || ($salary->calculation_type == 1 && $salary->formula == 1 )) {
							$earnings += $value;
						}
					} else if($salary->pay_head_type == "deductions")  {
						$data[$salary->pay_head_name] = "-".$value;
						$salary_detail[] = ['pay_head_id' => $salary->pay_head, 'debit_ledger_id' => $employee_ledger, 'credit_ledger_id' => $salary->ledger_id, 'amount' => $value, 'pay_head' => $salary->pay_head_name];
						if(($salary->calculation_type == 0 && $salary->formula == null) || ($salary->calculation_type == 1 && $salary->formula == 1 )) {
							$deductions += $value;
						}
					}
					
					$status = $salary->status;
					$payment_method_id = $salary->payment_method_id;

				}


				//Add Overtime Wages
				$subtotal += $overtime;

				if(count($earning_array) > 0) {
					foreach ($earning_array as $key => $earn) {
						$earning_percent = $earnings * ($earn / 100);
						if(array_search('0000'.$key.'.00', $data)) {
							$data[array_search('0000'.$key.'.00', $data)] = $earning_percent;
						}
						$earnings += $earning_percent;
					}
				}
				if(count($deduction_array) > 0) {
					foreach ($deduction_array as $key => $deduct) {
						$deduction_percent = $deductions * ($deduct / 100);
						if(array_search('-0000'.$key.'.00', $data)) {
							$data[array_search('-0000'.$key.'.00', $data)] = $deduction_percent;
						}
						$deductions -= $deduction_percent;
					}
				}


				$subtotal += $earnings;
				$subtotal -= $deductions;

				if(count($subtotal_array) > 0) {
					foreach ($subtotal_array as $key => $sub) {
						$subtotal_percent = $subtotal * ($sub / 100);
						if(array_search('0000'.$key.'.00', $data)) {
							$data[array_search('0000'.$key.'.00', $data)] = $subtotal_percent;
						}
						$subtotal += $subtotal_percent;
					}
				}

				$total = $subtotal;

				return ['salary' => $data, 'salary_detail' => $salary_detail, 'total' => $total];
	} 

	//Generate Payslip
	public function multiapprove(Request $request) {
	

		$organization_id = Session::get('organization_id');

		$date = Carbon::createFromFormat('d-m-Y', $request->date);
		$payment_method = explode(',', $request->payment_method_id);
		$employee = explode(',', $request->employee_id);
		$overtime = explode(',', $request->over_time);
		$frequency = explode(',', $request->frequency);
		$ledger_id = explode(',', $request->ledger_id);

		$salary_payable = AccountLedger::where('name', 'Salary Payable')->where('organization_id', $organization_id)->first()->id;

		$employee_list = [];

		$salary_date = Carbon::parse($request->date)->format('Y-m-d');

		$batch = 1;

		$existing_salary = HrmSalary::where('organization_id', $organization_id)->orderby('batch', 'desc')->first();

		if($existing_salary != null) {
			$batch = $existing_salary->batch + 1;
		}

		for($i = 0; $i < count($employee); $i++) {


			$salary_scales = HrmSalaryScale::select('hrm_pay_heads.id', 'hrm_pay_heads.id AS pay_head', 'hrm_employee_pay_head.value', 'hrm_pay_heads.display_name', 'hrm_pay_heads.name AS  pay_head_name','hrm_pay_heads.formula','hrm_pay_heads.wage_type','hrm_pay_heads.fixed_month','hrm_pay_heads.ledger_id','hrm_pay_heads.fixed_days','hrm_pay_heads.minimum_attendance', 'hrm_pay_heads.calculation_type', 'hrm_pay_head_types.name AS pay_head_type', DB::Raw('0 AS status'), 'hrm_employee_pay_head.pay_head_id')
				->leftjoin('hrm_employee_salary', 'hrm_employee_salary.salary_scale_id', '=', 'hrm_salary_scales.id')
				->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_employee_salary.employee_id')
				->leftjoin('hrm_employee_pay_head', 'hrm_employee_pay_head.employee_id', '=', 'hrm_employee_salary.employee_id')
				->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_employee_pay_head.pay_head_id')
				->leftjoin('hrm_pay_head_types', 'hrm_pay_head_types.id', '=', 'hrm_pay_heads.payhead_type_id')
				->where('hrm_salary_scales.id', $request->salary_scale_id)
				->where('hrm_pay_heads.status', 1)
				->where('hrm_employee_salary.employee_id', $employee[$i])
				->groupby('hrm_pay_heads.id')
				->get();

			$subtotal = 0;
			$earnings = 0;
			$deductions = 0;
			$subtotal_array = [];
			$earning_array = [];
			$deduction_array = [];
			$data = [];



			$salaries = $this->get_salary_amount($salary_scales, $date, $employee[$i], $frequency[$i], $ledger_id[$i]);

			

			$entry = [];
			

			foreach ($salaries['salary_detail'] as $salary_detail) {

				//$salary_detail['credit_ledger_id'] ----> Employee Ledger  [Credit Ledger ID]

				$entry[] = ['debit_ledger_id' => $salary_detail['debit_ledger_id'], 'credit_ledger_id' => $salary_payable, 'amount' => $salary_detail['amount']];

				if($salary_detail['pay_head'] == 'PF Employee Contribution' ) {

					$employer_contribution = AccountLedger::where('name', 'PF Employer Contribution')->where('organization_id', $organization_id)->first()->id;

					$employer_payable = AccountLedger::where('name', 'PF Employer Contribution Payable')->where('organization_id', $organization_id)->first()->id;

					$entry[] = ['debit_ledger_id' => $employer_contribution, 'credit_ledger_id' => $employer_payable, 'amount' => $salary_detail['amount']];
				}
			}

			$entry_id = Custom::add_entry($salary_date, $entry, null, 'payroll', $organization_id, 1, false);

			

			$salary = new HrmSalary;
			$salary->salary_date = $salary_date;
			$salary->entry_id = $entry_id;
			$salary->over_time_hours = $overtime[$i];
			$salary->payment_method_id = $payment_method[$i];
			$salary->salary_scale_id = $request->salary_scale_id;
			$salary->employee_id = $employee[$i];
			$salary->gross_salary = $salaries['total'];
			$salary->organization_id = $organization_id;
			$salary->batch = $batch;
			$salary->status = 0;
			$salary->save();

			Custom::userby($salary, true);

			if($salary->id) {
				foreach ($salaries['salary_detail'] as $salary_head) {
					$salaryhead = new HrmSalaryPayhead;
					$salaryhead->salary_id = $salary->id;
					$salaryhead->pay_head_id = $salary_head['pay_head_id'];
					$salaryhead->value = $salary_head['amount'];
					$salaryhead->save();
				}

				//Salary Payment
				/*$cash = AccountLedger::where('name', 'Cash')->where('organization_id', $organization_id)->first()->id;

				$salary_payment[] = ['debit_ledger_id' => $ledger_id[$i], 'credit_ledger_id' => $cash, 'amount' => $salary->gross_salary];

				Custom::add_entry($salary_date, $salary_payment, null, 'payment', $organization_id, 1, false, null, null, $salary->entry_id);*/
				
			}

			

			$employee_list[] = $employee[$i];
		}

		return response()->json(['status'=>1, 'message'=>'Payroll'.config('constants.flash.added'),'data'=>['list' => $employee_list]]);
	}

public function multidelete(Request $request) {

		$organization_id = Session::get('organization_id');

		$ids = explode(',', $request->pay_ids);



		$id_list = [];

		foreach ($ids as $key=>$id) {
			$data=HrmSalary::select('batch')->where('id',$ids[$key])->first();


			$salaries = HrmSalary::where('batch', $data->batch)->where('organization_id', $organization_id)->get();
			$current_batch = null;
			foreach ($salaries as $salary) {
				$hrm_salary = HrmSalary::select('hrm_salaries.id', 'hrm_salaries.batch', 'hrm_salaries.employee_id', 'hrm_salaries.entry_id', 'hrm_salaries.gross_salary', 'payment_methods.name AS payment_method')
				->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id')
				->where('hrm_salaries.id', $salary->id)->first();

				if(!empty($hrm_salary->entry_id)) {
					$entry = AccountEntry::where('account_entries.id', $hrm_salary->entry_id)->first();

					if($hrm_salary->payment_method == "Cash") {
						$credit_ledger_id = AccountLedger::where('name', 'Cash')->where('organization_id', $organization_id)->first()->id;

						$debit_ledger_id = HrmEmployee::find($hrm_salary->employee_id)->ledger_id;

						$salary_payment[] = ['debit_ledger_id' => $debit_ledger_id, 'credit_ledger_id' => $credit_ledger_id, 'amount' => $hrm_salary->gross_salary];

						$payment_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;

						$cash_entry = AccountEntry::where('voucher_id', $payment_voucher)->where('reference_transaction_id', $hrm_salary->id)->first();

						if($cash_entry != null) {
							$cash_entry->delete();
						}

					}

					$entry->delete();
				}

				
				if($current_batch != $salary->id) {
					$current_batch = $salary->id;
					$batch_list[] = $current_batch;
				}

				$hrm_salary->delete();
			}
		}

		

		return response()->json(['status' => 1, 'message' => 'Salary'.config('constants.flash.deleted'), 'data' =>['list' => $batch_list]]);
	}

	//Generate Payment
	public function multipayment(Request $request) {

	 

		$organization_id = Session::get('organization_id');

		$datas = explode(',', $request->id);


		$data_list = [];
		if($request->gen_mode=="batch"){

			
			
			

		foreach ($datas as $batch) {
			$batch_id=HrmSalary::select('batch')->where('id',$request->id)->first();
			$salaries = HrmSalary::select('hrm_salaries.id', 'hrm_salaries.batch')
			->where('hrm_salaries.batch', $batch_id->batch)
			->where('hrm_salaries.organization_id', $organization_id)
			->get();

			$current_batch = null;
			foreach ($salaries as $salary) {
				$hrm_salary = HrmSalary::select('hrm_salaries.id', 'hrm_salaries.batch', 'hrm_salaries.employee_id', 'hrm_salaries.entry_id', 'hrm_salaries.gross_salary', 'payment_methods.name AS payment_method')
				->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id')
				->where('hrm_salaries.id', $salary->id)->first();

				//To clear previous record
				$salary_payment = [];

				if(!empty($hrm_salary->entry_id)) {
 
 					if($request->status == 1) {

						if($hrm_salary->payment_method == "Cash") {
							$credit_ledger_id = AccountLedger::where('name', 'Cash')->where('organization_id', $organization_id)->first()->id;

							$debit_ledger_id = HrmEmployee::find($hrm_salary->employee_id)->ledger_id;

							$salary_payment[] = ['debit_ledger_id' => $debit_ledger_id, 'credit_ledger_id' => $credit_ledger_id, 'amount' => $hrm_salary->gross_salary];

							Custom::add_entry(date('Y-m-d'), $salary_payment, null, 'payment', $organization_id, 1, false, null, null, $hrm_salary->entry_id);

						}
					} else if($request->status == 0) {
						AccountEntry::where('reference_voucher_id', $hrm_salary->entry_id)->delete();
					}
				}

				if($current_batch != $salary->batch) {
					$current_batch = $salary->id;
					$data_list[] = $current_batch;
				}

				$hrm_salary->status = $request->status;
				$hrm_salary->save();

				
			}
			
		}
		return response()->json(['status' => 1, 'message' => 'Salary'.config('constants.flash.updated'), 'data' =>['list' => $data_list]]);
	    }
	    else{
	    	foreach ($datas as $key=>$batch) {

			$current_id = null;
			
			$hrm_salary = HrmSalary::select('hrm_salaries.id', 'hrm_salaries.batch', 'hrm_salaries.employee_id', 'hrm_salaries.entry_id', 'hrm_salaries.gross_salary', 'payment_methods.name AS payment_method')
				->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id')
				->where('hrm_salaries.id', $datas[$key])->first();

				//To clear previous record
				$salary_payment = [];

				if(!empty($hrm_salary->entry_id)) {
 
 					if($request->status == 1) {

						if($hrm_salary->payment_method == "Cash") {
							$credit_ledger_id = AccountLedger::where('name', 'Cash')->where('organization_id', $organization_id)->first()->id;

							$debit_ledger_id = HrmEmployee::find($hrm_salary->employee_id)->ledger_id;

							$salary_payment[] = ['debit_ledger_id' => $debit_ledger_id, 'credit_ledger_id' => $credit_ledger_id, 'amount' => $hrm_salary->gross_salary];

							Custom::add_entry(date('Y-m-d'), $salary_payment, null, 'payment', $organization_id, 1, false, null, null, $hrm_salary->entry_id);

						}
					} else if($request->status == 0) {
						AccountEntry::where('reference_voucher_id', $hrm_salary->entry_id)->delete();
					}
				}

				if($current_id != $hrm_salary->batch) {
					$current_id = $hrm_salary->id;
					$data_list[] = $current_id;
				}

				$hrm_salary->status = $request->status;
				$hrm_salary->save();
			}

				return response()->json(['status' => 1, 'message' => 'Salary'.config('constants.flash.updated'), 'data' =>['list' => $data_list]]);
			}  

		
	}
	//Generate Payslip
	public function payslip(Request $request) {

		$organization_id = Session::get('organization_id');

		$salary = AccountEntry::select('hrm_salaries.id', 'hrm_salaries.salary_date', 'payment_methods.display_name AS payment_method', 'gross_salary', 'print_templates.data', DB::Raw('CONCAT(hrm_employees.first_name, " " ,COALESCE(hrm_employees.last_name, "")) AS employee_name'), 'hrm_designations.name AS designation', 'hrm_employees.employee_code')
				->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
				->leftjoin('print_templates','print_templates.id','=','account_vouchers.print_id')
				->leftjoin('hrm_salaries','hrm_salaries.entry_id','=','account_entries.id')
				->leftjoin('hrm_salary_scales', 'hrm_salary_scales.id', '=', 'hrm_salaries.salary_scale_id')
				->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
				->leftjoin('hrm_employee_designation', 'hrm_employees.id', '=', 'hrm_employee_designation.employee_id')
				->leftjoin('hrm_designations', 'hrm_designations.id', '=', 'hrm_employee_designation.designation_id')
				->leftjoin('payment_methods', 'payment_methods.id', '=', 'hrm_salaries.payment_method_id')
				->where('hrm_salaries.id', $request->id)
				->where('hrm_salaries.organization_id', $organization_id)
				->first();


		$earning = HrmSalaryPayhead::select('hrm_salary_payheads.value', 'hrm_pay_heads.display_name AS pay_head');
		$earning->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_salary_payheads.pay_head_id');
		$earning->leftjoin('hrm_pay_head_types', 'hrm_pay_head_types.id', '=', 'hrm_pay_heads.payhead_type_id');
		$earning->where('hrm_pay_head_types.name', 'earnings');
		$earning->where('hrm_salary_payheads.salary_id', $salary->id);
		$earnings = $earning->get();

		$earning_total = $earning->sum('value');

		$deduction = HrmSalaryPayhead::select('hrm_salary_payheads.value', 'hrm_pay_heads.display_name AS pay_head');
		$deduction->leftjoin('hrm_pay_heads', 'hrm_pay_heads.id', '=', 'hrm_salary_payheads.pay_head_id');
		$deduction->leftjoin('hrm_pay_head_types', 'hrm_pay_head_types.id', '=', 'hrm_pay_heads.payhead_type_id');
		$deduction->where('hrm_pay_head_types.name', 'deductions');
		$deduction->where('hrm_salary_payheads.salary_id', $salary->id);
		$deductions = $deduction->get();

		$deduction_total = $deduction->sum('value');

		$date = Carbon::parse($salary->salary_date)->format('F Y');

		return ['salary_data' => $salary->data, 'employee_name' => $salary->employee_name, 'employee_designation' => $salary->designation, 'employee_code' => $salary->employee_code, 'payslip_date' => $date, 'earnings' => $earnings, 'deductions' => $deductions, 'earning_total' => $earning_total, 'deduction_total' => $deduction_total, 'gross_total' => Custom::two_decimal($salary->gross_salary), 'net_total' => Custom::two_decimal($salary->gross_salary), 'gross_amount_words' => ucwords(Custom::amountInIndianWords($salary->gross_salary)."only"), 'net_amount_words' => ucwords(Custom::amountInIndianWords($salary->gross_salary)."only"), 'date' => $salary->salary_date, 'salary_month_year' => $date];

		/*



<div style="float:left; width:100%; margin-top:150px;">
  <table style="border-spacing:30px" align="center" width="800">
    <tr>
      <td width="42">&nbsp;</td>
      <td width="600"><table class="border" width="100%">
          <tr>
            <td class="border_bottom padding" width="50%"><b>PropelSoft</b><br>
              Gitanjali Apartments, Shastri Rd, Thillai Nagar, Tiruchirappalli - 620 008, Tamil Nadu.</td>
            <td class="border_bottom" align="right" width="50%"><img width="150" src="" /></td>
          </tr>
          <tr>
            <td style="padding:5px" class="border" align="center" colspan="2"><b>PAY SLIP FOR THE MONTH OF <span class="payslip_date" style="text-transform:uppercase;"><span><b></td>
          </tr>
          <tr>
            <td class="padding" align="center" colspan="2"><table width="100%">
                <tr>
                  <td width="28%">Employee Name</td>
                  <td width="72%"><span class="employee_name"></span></td>
                </tr>
                <tr>
                  <td>Employee Designation</td>
                  <td><span class="employee_designation"></span></td>
                </tr>
                <tr>
                  <td>Employee ID</td>
                  <td><span class="employee_code"></span></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td style="padding:10px" colspan="2"><span class="earnings"></span></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%">
                <tr>
                  <td width="35%" height="42" valign="bottom" style="padding: 2px 15px; font-weight: bold;">Gross Pay</td>
                  <td style="padding: 2px 15px" valign="bottom" align="right" width="65%"><span style="font-weight: bold;" class="gross_total">Rupees Ten thousand only</span></td>
                </tr>
                <tr>
                  <td width="35%" colspan="2" style="padding: 2px 15px"><span class="gross_total_words">Rupees Ten thousand only</span></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td height="62" colspan="2" align="center">"This is computer generated Payslip. Signature not required!"</td>
          </tr>
        </table></td>
      <td width="42">&nbsp;</td>
    </tr>
  </table>
</div>



		*/

	}
}
