<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\HrmPayrollFrequency;
use App\HrmPayHead;
use App\HrmSalaryScale;
use App\HrmEmployeeSalary;
use App\Custom;
use Session;
use Response;
use Validator;
use DB;

class SalaryScaleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$salary_scale = HrmSalaryScale::select('hrm_salary_scales.id','hrm_salary_scales.name','hrm_salary_scales.code','hrm_salary_scales.status')
		->where('hrm_salary_scales.organization_id', $organization_id)->paginate(10);

		return view('hrm.salary_scale',compact('salary_scale'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');


		$payroll_frequency = HrmPayrollFrequency::where('organization_id',$organization_id)->pluck('name', 'id');
		$payroll_frequency->prepend('Select Frequency', '');

		$salary_earnings = HrmPayHead::where('organization_id',$organization_id)->where('payhead_type_id',1)->pluck('name', 'id');
		$salary_earnings->prepend('Select Earnings', '');

		$salary_deduction = HrmPayHead::where('organization_id',$organization_id)->where('payhead_type_id',2)->pluck('name', 'id');
		$salary_deduction->prepend('Select Deduction', '');   

		return view('hrm.salary_scale_create',compact('payroll_frequency','salary_earnings','salary_deduction'));
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
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$earning_id = $request->input('earning_id');
		$earning_value = $request->input('earning_value');
		$deduction_id = $request->input('deduction_id');
		$deduction_value = $request->input('deduction_value');

		$salary_scale = new HrmSalaryScale;
		$salary_scale->name = $request->input('name');
		$salary_scale->code = $request->input('code');
		$salary_scale->frequency_id = $request->input('frequency_id');
		if($request->input('round_off') != null){
			$salary_scale->round_off =  $request->input('round_off');
		}
		if($request->input('round_off_limit') != null){
			$salary_scale->round_off_limit =  $request->input('round_off_limit');
		}		
		$salary_scale->organization_id = $organization_id;
		$salary_scale->save();


		for($i=0;$i<count($earning_id);$i++) {
			if($earning_id[$i] != "" && $earning_value[$i] != "") {
				DB::table('hrm_salary_scale_pay_head')->insert([
					'pay_head_id' => $earning_id[$i], 'salary_scale_id' => $salary_scale->id, 'value' => $earning_value[$i]
				]);
			}
		}

		for($i=0;$i<count($deduction_id);$i++) {
			if($deduction_id[$i] != "" && $deduction_value[$i] != "") {
				DB::table('hrm_salary_scale_pay_head')->insert([
				  'pay_head_id' => $deduction_id[$i], 'salary_scale_id' => $salary_scale->id, 'value' => $deduction_value[$i]
				]);
			}
		}

		Custom::userby($salary_scale, true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Salary Scale'.config('constants.flash.added'), 'data' => ['id' => $salary_scale->id, 'name' => $salary_scale->name,'code'=>$salary_scale->code]]);
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

		$payroll_frequency = HrmPayrollFrequency::where('organization_id',$organization_id)->pluck('name', 'id');
		$payroll_frequency->prepend('Select Frequency', '');

		$salary_earnings = HrmPayHead::where('organization_id',$organization_id)->where('payhead_type_id',1)->pluck('name', 'id');
		$salary_earnings->prepend('Select Earnings', '');

		$salary_deduction = HrmPayHead::where('organization_id',$organization_id)->where('payhead_type_id',2)->pluck('name', 'id');
		$salary_deduction->prepend('Select Deduction', '');


		$salary_scale = HrmSalaryScale::where('organization_id',$organization_id)->where('id',$id)->first();

		$earnings = DB::table('hrm_salary_scale_pay_head')
		->leftjoin('hrm_pay_heads', 'hrm_salary_scale_pay_head.pay_head_id', '=', 'hrm_pay_heads.id')
		->where('hrm_salary_scale_pay_head.salary_scale_id', $salary_scale->id)
		->where('hrm_pay_heads.payhead_type_id', 1)
		->groupby('hrm_salary_scale_pay_head.pay_head_id')->get();

		$deductions = DB::table('hrm_salary_scale_pay_head')
		->leftjoin('hrm_pay_heads', 'hrm_salary_scale_pay_head.pay_head_id', '=', 'hrm_pay_heads.id')
		->where('hrm_salary_scale_pay_head.salary_scale_id', $salary_scale->id)
		->where('hrm_pay_heads.payhead_type_id', 2)
		->groupby('hrm_salary_scale_pay_head.pay_head_id')->get();

		if(!$salary_scale) abort(403);

		return view('hrm.salary_scale_edit',compact('salary_scale','payroll_frequency','salary_earnings','salary_deduction','earnings','deductions'));
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
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$earning_id = $request->input('earning_id');
		$earning_value = $request->input('earning_value');
		$deduction_id = $request->input('deduction_id');
		$deduction_value = $request->input('deduction_value');

		$salary_scale = HrmSalaryScale::findorFail($request->input('id'));
		$salary_scale->name = $request->input('name');
		$salary_scale->code = $request->input('code');
		$salary_scale->frequency_id = $request->input('frequency_id');

		$salary_scale->round_off = null;
		if($request->input('round_off') != null){
			$salary_scale->round_off =  $request->input('round_off');
		}

		$salary_scale->round_off_limit = 0;
		if($request->input('round_off_limit') != null){
			$salary_scale->round_off_limit =  $request->input('round_off_limit');
		}
		$salary_scale->save();

		DB::table('hrm_salary_scale_pay_head')->where('salary_scale_id', $salary_scale->id)->delete();


		for($i=0;$i<count($earning_id);$i++) {
			if($earning_id[$i] != "" && $earning_value[$i] != "") {
				DB::table('hrm_salary_scale_pay_head')->insert([
					'pay_head_id' => $earning_id[$i], 'salary_scale_id' => $salary_scale->id, 'value' => $earning_value[$i]
				]);
			}
		}

		for($i=0;$i<count($deduction_id);$i++) {
			if($deduction_id[$i] != "" && $deduction_value[$i] != "") {
				DB::table('hrm_salary_scale_pay_head')->insert([
				  'pay_head_id' => $deduction_id[$i], 'salary_scale_id' => $salary_scale->id, 'value' => $deduction_value[$i]
				]);
			}
		}

		Custom::userby($salary_scale, false);

		return response()->json(['status' => 1, 'message' => 'Salary Scale'.config('constants.flash.updated'), 'data' => ['id' => $salary_scale->id, 'name' => $salary_scale->name,'code'=>$salary_scale->code,'status'=> $salary_scale->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$salary_scale = HrmSalaryScale::findorFail($request->id);
		$salary_scale->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Salary Scale'.config('constants.flash.deleted'), 'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$salary_scales = explode(',', $request->id);

		$salaryscale_list = [];

		foreach ($salary_scales as $salary_scale_id) {
			$salary_scale = HrmSalaryScale::findOrFail($salary_scale_id);
			$salary_scale->delete();
			$salaryscale_list[] = $salary_scale_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Salary Scale'.config('constants.flash.deleted'),'data'=>['list' => $salaryscale_list]]);
	}

	public function multiapprove(Request $request)
	{
		$salary_scales = explode(',', $request->id);

		$salaryscale_list = [];

		foreach ($salary_scales as $salary_scale_id) {
			HrmSalaryScale::where('id', $salary_scale_id)->update(['status' => $request->input('status')]);;
			$salaryscale_list[] = $salary_scale_id;
		}

		return response()->json(['status'=>1, 'message'=>'Salary Scale'.config('constants.flash.updated'),'data'=>['list' => $salaryscale_list]]);
	}

	public function salaryscale_status_approval(Request $request)
	{
		HrmSalaryScale::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Salary Scale'.config('constants.flash.updated'),'data'=>[]]);
	}
}
