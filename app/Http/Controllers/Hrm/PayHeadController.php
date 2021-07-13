<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmSalaryCalculationType;
use App\AccountFinancialYear;
use App\AccountLedgerType;
use App\HrmPayHeadType;
use App\AccountLedger;
use App\Organization;
use App\AccountGroup;
use App\HrmWageType;
use App\HrmPayHead;
use App\Custom;
use Validator;
use Response;
use Session;
use DB;

class PayHeadController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$payheads = HrmPayHead::select('hrm_pay_heads.id', 'hrm_pay_heads.name', 'hrm_pay_heads.code','hrm_pay_heads.status', DB::Raw('CASE
	WHEN `hrm_pay_heads`.wage_type = 0 THEN "Hour Based"
	WHEN `hrm_pay_heads`.wage_type = 1 THEN "Day Based"
	WHEN `hrm_pay_heads`.wage_type = 2 THEN "Month Based"
  END AS wage_types'))
		->where('hrm_pay_heads.organization_id', $organization_id)->paginate(10);

		return view('hrm.pay_head',compact('payheads'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$payhead_types = HrmPayHeadType::pluck('display_name', 'id');
		$payhead_types->prepend('Choose PayHead Type');

		$pay_head = HrmPayHead::where('organization_id',$organization_id)->pluck('name', 'id');

		$direct_expense = AccountGroup::where('name', 'direct_expense')->where('organization_id', $organization_id)->first()->id;

		$indirect_expense = AccountGroup::where('name', 'indirect_expense')->where('organization_id', $organization_id)->first()->id;

		$groups = AccountGroup::select('account_groups.id',
		  'account_groups.display_name AS name')
		  ->where([
					['account_groups.organization_id', $organization_id],
					['account_groups.approval_status', '1'],
					['account_groups.status', '1']
				])
		  ->where(function ($query) {
		  	return $query->where('account_groups.name', '=', 'direct_expense')->orWhere('account_groups.name', '=', 'indirect_expense'); 
		  })
		  ->orderby('name','asc');

		$groups = $groups->pluck('account_ledgers.name', 'account_ledgers.id');
		//$groups->prepend('Select Ledger', '');

		return view('hrm.pay_head_create',compact('payhead_types','pay_head','groups'));

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
			'payhead_type_id' => 'required',
			'name' => 'required',           
		]);

		$pay_head_id = $request->input('pay_head_id');  

		$payhead_type = HrmPayHeadType::find($request->input('payhead_type_id'))->name;

		$organization_id = Session::get('organization_id');
		$organization = Organization::find($organization_id);

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

		if($payhead_type == "earnings") {
			$payhead_ledger = AccountGroup::where('name', 'salary')->where('organization_id', $organization_id)->first()->id;
		} else if($payhead_type == "deductions") {
			$payhead_ledger = AccountGroup::where('name', 'current_liability')->where('organization_id', $organization_id)->first()->id;
		}
		

		$pay_head = new HrmPayHead;
		$pay_head->payhead_type_id = $request->input('payhead_type_id');
		$pay_head->code = $request->input('code');
		$pay_head->name = $request->input('name');
		$pay_head->display_name = $request->input('display_name');
		$pay_head->calculation_type = $request->input('calculation_type');

		if($request->input('formula') != null){
			$pay_head->formula = $request->input('formula');
		}
		
		$pay_head->wage_type = $request->input('wage_type');

		if($request->input('fixed_month') != null){
			$pay_head->fixed_month = $request->input('fixed_month');
		}
		if($request->input('fixed_days') != null){
			$pay_head->fixed_days = $request->input('fixed_days');
		}

		if($request->input('minimum_attendance') != null){
			$pay_head->minimum_attendance = $request->input('minimum_attendance');
		}

		if($request->input('ledger_id') != null) {
			$pay_head->ledger_id = $request->input('ledger_id');
		} else {
			$pay_head->ledger_id = Custom::create_ledger($pay_head->name, $organization, $pay_head->display_name, $impersonal_ledger->id, null, null, $payhead_ledger, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
		}
		

		$pay_head->description = $request->input('description');
		$pay_head->organization_id = $organization_id;
		$pay_head->save();

		
		
		$hrm_pay_heads = $pay_head->id;

		for($i=0;$i<count($pay_head_id);$i++)
		{
			DB::table('hrm_pay_head_parent')->insert(
			  ['pay_head_id' => $pay_head_id[$i], 'pay_head_parent_id' => $hrm_pay_heads]
			);
		}       

		Custom::userby($pay_head, true);

		$payheads = HrmPayHead::select('hrm_pay_heads.id', 'hrm_pay_heads.name', 'hrm_pay_heads.code','hrm_pay_heads.status', DB::Raw('CASE
			WHEN `hrm_pay_heads`.wage_type = 0 THEN "Hour Based"
			WHEN `hrm_pay_heads`.wage_type = 1 THEN "Day Based"
			WHEN `hrm_pay_heads`.wage_type = 2 THEN "Month Based"
		  END AS wage_types'))
		->where('hrm_pay_heads.id',$pay_head->id)
		->where('hrm_pay_heads.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Pay Head'.config('constants.flash.updated'), 'data' => ['id' => $payheads->id, 'name' => $payheads->name, 'code' => $payheads->code,'wage_type'=>$payheads->wage_types]]);
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

		$payhead_types = HrmPayHeadType::pluck('display_name', 'id');
		$payhead_types->prepend('Choose PayHead Type');

		$pay_heads = HrmPayHead::where('organization_id',$organization_id)->where('id', '!=', $id)->get();        

		$hrm_payhead = DB::table('hrm_pay_head_parent')->where('pay_head_parent_id',$id)->get();

		$selected_pay_heads= [];
		
		foreach ($hrm_payhead as $value) {
		   $selected_pay_heads[] = $value->pay_head_id;
		}

		$direct_expense = AccountGroup::where('name', 'direct_expense')->where('organization_id', $organization_id)->first()->id;

		$indirect_expense = AccountGroup::where('name', 'indirect_expense')->where('organization_id', $organization_id)->first()->id;

		$groups = AccountGroup::select('account_groups.id',
		  'account_groups.display_name AS name')
		  ->where([
					['account_groups.organization_id', $organization_id],
					['account_groups.approval_status', '1'],
					['account_groups.status', '1']
				])
		  ->where(function ($query) {
		  	return $query->where('account_groups.name', '=', 'direct_expense')->orWhere('account_groups.name', '=', 'indirect_expense'); 
		  })
		  ->orderby('name','asc');

		$groups = $groups->pluck('account_ledgers.name', 'account_ledgers.id');

		$payhead_edit = HrmPayHead::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$payhead_edit) abort(403);

		return view('hrm.pay_head_edit',compact('payhead_types','payhead_edit','groups','selected_pay_heads','hrm_payhead','pay_heads'));
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

		$pay_head_id = $request->input('pay_head_id');  

		$organization_id = Session::get('organization_id');

		$pay_head =  HrmPayHead::findorFail($request->input('id'));
		//$pay_head->payhead_type_id = $request->input('payhead_type_id');
		$pay_head->code = $request->input('code');
		$pay_head->name = $request->input('name');
		$pay_head->display_name = $request->input('display_name');
		$pay_head->calculation_type = $request->input('calculation_type');

		$pay_head->formula = null;
		if($request->input('formula') != null){
			$pay_head->formula = $request->input('formula');
		}
		
		$pay_head->wage_type = $request->input('wage_type');

		$pay_head->fixed_month = null;
		if($request->input('fixed_month') != null){
			$pay_head->fixed_month = $request->input('fixed_month');
		}

		$pay_head->fixed_days = null;
		if($request->input('fixed_days') != null){
			$pay_head->fixed_days = $request->input('fixed_days');
		}
		
		$pay_head->minimum_attendance = 0;
		if($request->input('minimum_attendance') != null){
			$pay_head->minimum_attendance = $request->input('minimum_attendance');
		}       

		//$pay_head->ledger_id = $request->input('ledger_id');
		$pay_head->description = $request->input('description');
	   
		$pay_head->save();
		
		$hrm_pay_heads = $pay_head->id;

		DB::table('hrm_pay_head_parent')->where('pay_head_parent_id', $hrm_pay_heads)->delete();

		for($i=0;$i<count($pay_head_id);$i++)
		{
			DB::table('hrm_pay_head_parent')->insert(
			  ['pay_head_id' => $pay_head_id[$i], 'pay_head_parent_id' => $hrm_pay_heads]
			);
		}

		Custom::userby($pay_head, false);

		$payheads = HrmPayHead::select('hrm_pay_heads.id', 'hrm_pay_heads.name', 'hrm_pay_heads.code','hrm_pay_heads.status', DB::Raw('CASE
	WHEN `hrm_pay_heads`.wage_type = 0 THEN "Hour Based"
	WHEN `hrm_pay_heads`.wage_type = 1 THEN "Day Based"
	WHEN `hrm_pay_heads`.wage_type = 2 THEN "Month Based"
  END AS wage_types'))
		->where('hrm_pay_heads.id',$pay_head->id)
		->where('hrm_pay_heads.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Pay Head'.config('constants.flash.updated'), 'data' => ['id' => $payheads->id, 'name' => $payheads->name, 'code' => $payheads->code,'wage_type'=>$payheads->wage_types,'status' => $payheads->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$payhead = HrmPayHead::findorFail($request->id);
		$payhead->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Pay Head'.config('constants.flash.deleted'), 'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$pay_heads = explode(',', $request->id);

		$payhead_list = [];

		foreach ($pay_heads as $pay_head_id) {
			$pay_head = HrmPayHead::findOrFail($pay_head_id);
			$pay_head->delete();
			$payhead_list[] = $pay_head_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Pay Head'.config('constants.flash.deleted'),'data'=>['list' => $payhead_list]]);
	}

	public function multiapprove(Request $request)
	{
		$pay_heads = explode(',', $request->id);

		$payhead_list = [];

		foreach ($pay_heads as $pay_head_id) {
			HrmPayHead::where('id', $pay_head_id)->update(['status' => $request->input('status')]);;
			$payhead_list[] = $pay_head_id;
		}

		return response()->json(['status'=>1, 'message'=>'Pay Head'.config('constants.flash.updated'),'data'=>['list' => $payhead_list]]);
	}

	public function payhead_status_approval(Request $request)
	{
		HrmPayHead::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(['status'=>1, 'message'=>'Pay Head'.config('constants.flash.updated'),'data'=>[]]);
	}
}
