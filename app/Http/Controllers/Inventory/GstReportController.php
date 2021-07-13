<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Custom;
use App\Unit;
use App\AccountVoucher;
use Session;
use URL;
use DB;

class GstReportController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		//$transaction_type = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

		$transactions = Transaction::select('transactions.id','transactions.transaction_type_id',DB::raw('SUM(transactions.total) AS total'),
			DB::raw('IF(account_vouchers.name = "sales" OR account_vouchers.name = "sales_cash", "Sales Invoice", account_vouchers.display_name ) AS transaction_type')	)

		->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
		->where('transactions.organization_id',$organization_id)
		->whereIn('account_vouchers.name', ['sales', 'sales_cash','purchases','credit_note','debit_note'])
		->groupby('transactions.transaction_type_id')
		->get();

		$total = Transaction::select('transactions.id','transactions.transaction_type_id',DB::raw('SUM(transactions.total) AS total'))
		->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
		->where('transactions.organization_id',$organization_id)
		->whereIn('account_vouchers.name', ['sales', 'sales_cash','purchases','credit_note','debit_note'])
		->first();


		return view('inventory.gst_report',compact('transactions', 'total'));
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
}
