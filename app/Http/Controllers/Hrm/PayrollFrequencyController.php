<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmPayrollFrequencyType;
use App\HrmPayrollFrequency;
use App\Weekday;
use App\Custom;
use Session;
use DB;

class PayrollFrequencyController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$frequencies = HrmPayrollFrequency::select('hrm_payroll_frequencies.id', 'hrm_payroll_frequencies.name', 'hrm_payroll_frequencies.code','hrm_payroll_frequencies.status', DB::Raw('IF(hrm_payroll_frequencies.week_day_id IS NULL, hrm_payroll_frequencies.salary_day , weekdays.display_name) AS day'), DB::Raw('CASE
			WHEN `hrm_payroll_frequencies`.frequency_type = 0 THEN "Daily"
			WHEN `hrm_payroll_frequencies`.frequency_type = 1 THEN "Weekly"
			WHEN `hrm_payroll_frequencies`.frequency_type = 2 THEN "Monthly"
		  END AS frequency_type'))
				->leftjoin('weekdays','weekdays.id', '=', 'hrm_payroll_frequencies.week_day_id')
		->where('hrm_payroll_frequencies.organization_id',$organization_id)->get();

		return view('hrm.payroll_frequency', compact('frequencies'));
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$weekdays = Weekday::pluck('display_name','id');
		$weekdays->prepend('Select Week Days','');

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		return view('hrm.payroll_frequency_create',compact('weekdays','days'));
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
			'code'=> 'required'
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$frequency = new HrmPayrollFrequency;
		$frequency->name = $request->input('name');
		$frequency->code = $request->input('code');
		$frequency->frequency_type = $request->input('frequency_type');

		if($request->input('week_day_id') != null){
			$frequency->week_day_id = $request->input('week_day_id');
		}
		
		if($request->input('salary_day') != null && $request->input('frequency_type') == 2 && $request->input('week_day_id') == null ){
			$frequency->salary_day = $request->input('salary_day');
		}
		
		if($request->input('salary_period') != null){
			$frequency->salary_period = $request->input('salary_period');
		}        
		$frequency->organization_id = $organization_id;
		$frequency->save();

		Custom::userby($frequency, true);

		Custom::add_addon('records');

		$frequencies = HrmPayrollFrequency::select('hrm_payroll_frequencies.id', 'hrm_payroll_frequencies.name', 'hrm_payroll_frequencies.code','hrm_payroll_frequencies.status', DB::Raw('IF(hrm_payroll_frequencies.week_day_id IS NULL, hrm_payroll_frequencies.salary_day , weekdays.display_name) AS day'), DB::Raw('CASE
	WHEN `hrm_payroll_frequencies`.frequency_type = 0 THEN "Daily"
	WHEN `hrm_payroll_frequencies`.frequency_type = 1 THEN "Weekly"
	WHEN `hrm_payroll_frequencies`.frequency_type = 2 THEN "Monthly"
  END AS frequency_type'))
		->leftjoin('weekdays','weekdays.id', '=', 'hrm_payroll_frequencies.week_day_id')
		->where('hrm_payroll_frequencies.id',$frequency->id)
		->where('hrm_payroll_frequencies.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Payroll Frequency'.config('constants.flash.added'), 'data' => ['id' => $frequencies->id, 'name' => $frequencies->name, 'code' => $frequencies->code, 'frequency'=> $frequencies->frequency_type,'salary_period' => ($frequencies->day != null) ? $frequencies->day : "",'status' => $frequencies->status ]]);
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

		$weekdays = Weekday::pluck('display_name','id');
		$weekdays->prepend('Select Week Days','');

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		$payroll_frequency = HrmPayrollFrequency::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$payroll_frequency) abort(403);

		return view('hrm.payroll_frequency_edit',compact('payroll_frequency','weekdays','days'));
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
			'name' => 'required',
			'code'=> 'required'
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$frequency =  HrmPayrollFrequency::findOrFail($request->input('id'));
		$frequency->name = $request->input('name');
		$frequency->code = $request->input('code');
		$frequency->frequency_type = $request->input('frequency_type');

		$frequency->week_day_id = null;
		if($request->input('week_day_id') != null){
			$frequency->week_day_id = $request->input('week_day_id');
		}
		
		$frequency->salary_day = null;
		if($request->input('salary_day') != null && $request->input('frequency_type') == 2 && $request->input('week_day_id') == null ){
			$frequency->salary_day = $request->input('salary_day');
		}
		
		$frequency->salary_period = null;
		if($request->input('salary_period') != null){
			$frequency->salary_period = $request->input('salary_period');
		}

		$frequency->organization_id = $organization_id;
		$frequency->save();

		Custom::userby($frequency, false);		

		$frequencies = HrmPayrollFrequency::select('hrm_payroll_frequencies.id', 'hrm_payroll_frequencies.name', 'hrm_payroll_frequencies.code','hrm_payroll_frequencies.status', DB::Raw('IF(hrm_payroll_frequencies.week_day_id IS NULL, hrm_payroll_frequencies.salary_day , weekdays.display_name) AS day'), DB::Raw('CASE
	WHEN `hrm_payroll_frequencies`.frequency_type = 0 THEN "Daily"
	WHEN `hrm_payroll_frequencies`.frequency_type = 1 THEN "Weekly"
	WHEN `hrm_payroll_frequencies`.frequency_type = 2 THEN "Monthly"
  END AS frequency_type'))
		->leftjoin('weekdays','weekdays.id', '=', 'hrm_payroll_frequencies.week_day_id')
		->where('hrm_payroll_frequencies.id',$frequency->id)
		->where('hrm_payroll_frequencies.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Payroll Frequency'.config('constants.flash.updated'), 'data' => ['id' => $frequencies->id, 'name' => $frequencies->name, 'code' => $frequencies->code, 'frequency'=> $frequencies->frequency_type,'salary_period' => ($frequencies->day != null) ? $frequencies->day : "",'status' =>$frequencies->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$payroll_frequency = HrmPayrollFrequency::findorFail($request->id);
		$payroll_frequency->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Payroll Frequency'.config('constants.flash.deleted'), 'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$payroll_frequencies = explode(',', $request->id);

		$leavetype_list = [];

		foreach ($payroll_frequencies as $payroll_frequency_id) {
			$payroll_frequency = HrmPayrollFrequency::findOrFail($payroll_frequency_id);
			$payroll_frequency->delete();
			$payrollfrequency_list[] = $payroll_frequency_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Payroll Frequency'.config('constants.flash.deleted'),'data'=>['list' => $payrollfrequency_list]]);
	}

	public function multiapprove(Request $request)
	{
		$payroll_frequencies = explode(',', $request->id);

		$payrollfrequency_list = [];

		foreach ($payroll_frequencies as $payroll_frequency_id) {
			HrmPayrollFrequency::where('id', $payroll_frequency_id)->update(['status' => $request->input('status')]);;
			$payrollfrequency_list[] = $payroll_frequency_id;
		}

		return response()->json(['status'=>1, 'message'=>'Payroll Frequency'.config('constants.flash.updated'),'data'=>['list' => $payrollfrequency_list]]);
	}

	public function payrollfrequency_status_approval(Request $request)
	{
		HrmPayrollFrequency::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Payroll Frequency'.config('constants.flash.updated'),'data'=>[]]);
	}
}
