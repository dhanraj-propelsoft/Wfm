<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DiscountType;
use App\AccountFinancialYear;
use App\AccountLedgerType;
use App\Discount;
use App\Organization;
use App\AccountGroup;
use App\AccountLedger;
use App\Custom;
use Session;
use Response;
use Validator;
use DB;

class DiscountController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$discounts = Discount::select('discounts.id','discounts.name','discounts.value','discounts.status')     
		->where('discounts.organization_id', $organization_id)->get();

		return view('trade.discount', compact('discounts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$indirect_expense = AccountGroup::where('name', 'indirect_expense')->where('organization_id', $organization_id)->first()->id;
		$indirect_income = AccountGroup::where('name', 'indirect_income')->where('organization_id', $organization_id)->first()->id;

		$sales_ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where('group_id', $indirect_expense)
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$sales_ledgers = $sales_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$sales_ledgers->prepend('Select Sales Ledger', '');

		$purchase_ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where('group_id', $indirect_income)
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$purchase_ledgers = $purchase_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$purchase_ledgers->prepend('Select Purchase Ledger', '');

		return view('trade.discount_create', compact('sales_ledgers','purchase_ledgers'));
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
			'name' => 'required',
			'value' => 'required'
			]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$discount = new Discount;
		$discount->name = $request->input('name');
		$discount->display_name = $request->input('name');
		$discount->value = $request->input('value');

		if($request->input('is_percent') != null){
			$discount->is_percent = $request->input('is_percent');
		}
		if($request->input('is_sales') != null){
			$discount->is_sales = $request->input('is_sales');
		}
		if($request->input('is_purchase') !=null){
			$discount->is_purchase = $request->input('is_purchase');
		}

		$discount->description = $request->input('description');        
		$discount->sales_ledger_id    = $request->input('sales_ledger_id');
		$discount->purchase_ledger_id = $request->input('purchase_ledger_id');
		$discount->organization_id = $organization_id;

		$discount->save();

		Custom::userby($discount, true);
		Custom::add_addon('records');
		

		return response()->json(['status' => 1, 'message' => 'Discount'.config('constants.flash.added'), 'data' => ['id' => $discount->id, 'name' => $discount->name, 'value' => $discount->value,'status' => $discount->status]]);
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

		$indirect_expense = AccountGroup::where('name', 'indirect_expense')->where('organization_id', $organization_id)->first()->id;
		$indirect_income = AccountGroup::where('name', 'indirect_income')->where('organization_id', $organization_id)->first()->id;

		$sales_ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where('group_id', $indirect_expense)
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$sales_ledgers = $sales_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$sales_ledgers->prepend('Select Sales Ledger', '');

		$purchase_ledgers = AccountLedger::select('account_ledgers.id',
		 'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where('group_id', $indirect_income)
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$purchase_ledgers = $purchase_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$purchase_ledgers->prepend('Select Purchase Ledger', '');
		 
		$discount = Discount::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$discount) abort(403);

		return view('trade.discount_edit', compact('discount','sales_ledgers','purchase_ledgers'));
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
		$this->validate($request,[
			'name' => 'required',            
			'value' => 'required'
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$discount =  Discount::findOrFail($request->input('id'));
		$discount->name = $request->input('name');
		$discount->display_name = $request->input('name');
		$discount->value = $request->input('value');

		$discount->is_percent = 0;
		if($request->input('is_percent') != null){
			$discount->is_percent = $request->input('is_percent');
		}

		$discount->is_sales = 0;
		if($request->input('is_sales') != null){
			$discount->is_sales = $request->input('is_sales');
		}

		$discount->is_purchase = 0;
		if($request->input('is_purchase') !=null){
			$discount->is_purchase = $request->input('is_purchase');
		}

		$discount->description = $request->input('description');        
		$discount->sales_ledger_id    = $request->input('sales_ledger_id');
		$discount->purchase_ledger_id = $request->input('purchase_ledger_id');
		$discount->organization_id = $organization_id;

		$discount->save();

		Custom::userby($discount,true);
		Custom::add_addon('records');        

		return response()->json(['status' => 1, 'message' => 'Discount'.config('constants.flash.updated'), 'data' => ['id' => $discount->id, 'name' => $discount->name, 'value' => $discount->value,'status' => $discount->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$discount = Discount::findOrFail($request->id);

		$discount->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Discount'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function status(Request $request)
	{
		Discount::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$discounts = explode(',', $request->id);
		$discount_list = [];

		foreach ($discounts as $discount_id) {
			$discount = Discount::findOrFail($discount_id);
			$discount->delete();
			$discount_list[] = $discount_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Discount'.config('constants.flash.deleted'),'data'=>['list' => $discount_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$discounts = explode(',', $request->id);
		$discount_list = [];

		foreach ($discounts as $discount_id) {
			Discount::where('id', $discount_id)->update(['status' => $request->input('status')]);;
			$discount_list[] = $discount_id;
		}

		return response()->json(['status'=>1, 'message'=>'Discount'.config('constants.flash.updated'),'data'=>['list' => $discount_list]]);
	}
}
