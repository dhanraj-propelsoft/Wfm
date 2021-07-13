<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Custom;
use App\Unit;
use App\AccountVoucher;
use Carbon\Carbon;
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

		$date = date('m-Y');



		return view('trade.gst_report',compact('date'));
	}

	public function get_gst_report(Request $request) {

		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

		$start_date = Carbon::parse('01'.'-'.$request->input('date'))->format('Y-m-d');
		$end_date = Carbon::parse($start_date)->endOfMonth()->format('Y-m-d');

		$select_type = $request->input('select_type');		

		$query = Transaction::select('transactions.*','transactions.transaction_type_id',DB::raw('SUM(transactions.total) AS total'),
			DB::raw('IF(account_vouchers.name = "sales" OR account_vouchers.name = "sales_cash", "Sales Invoice", account_vouchers.display_name ) AS transaction_type'),'transaction_items.item_id as item_no' ,'transaction_items.quantity','transaction_items.rate','transaction_items.amount','transaction_items.discount','transaction_items.is_discount_percent','transaction_items.description','units.display_name as unit','transaction_items.tax','inventory_items.hsn','global_item_category_types.display_name as category_type','transaction_items.discount','transactions.billing_address','transactions.shipping_date','transactions.name as customer_name','transactions.pin');
		
		$query->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
		$query->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id');
		$query->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id');
		$query->leftjoin('units','inventory_items.unit_id','=','units.id');
		$query->leftjoin('global_item_models','inventory_items.global_item_model_id','=','global_item_models.id');
		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$query->leftjoin('global_item_category_types','global_item_main_categories.category_type_id','=','global_item_category_types.id');
				
		$query->where('transactions.organization_id',$organization_id);	

		if($select_type == 'sales'){
			$query->whereIn('account_vouchers.name', ['sales', 'sales_cash']);
		} else if ($select_type == 'purchases') {
			$query->whereIn('account_vouchers.name', ['purchases']);
		}else  {
			$query->whereIn('account_vouchers.name', ['sales', 'sales_cash','purchases']);
		}
		
		$query->whereBetween('transactions.date', [$start_date, $end_date]);
		$query->groupby('transactions.transaction_type_id');
		$query->groupby('transaction_items.transaction_id');
		$query->groupby('transaction_items.item_id');

		$results = $query->get();


		$transactions = [];

		foreach ($results as $transaction) {

			$discounts = json_decode($transaction->discount);
			
			$discount = "";
			$discount_amount = "";

			if(count($discounts) > 0 && $discounts != null)
			{
				$discount_amount = $discounts->amount;
			}
		}

		foreach ($results as $transaction) {

			$billing_addresses = $transaction->billing_address;
			
			
			//$discount = "";
			$billing_address = "";

			if(count($billing_addresses) > 0 && $billing_addresses != null)
			{
				$billing_address = $billing_addresses;
			}
		}

		
		foreach ($results as $transaction) {
			
			$taxes = json_decode($transaction->tax, true);
			
			$cgst = "";
			$sgst = "";
			$igst = "";
			$cgst_amount = "";
			$sgst_amount = "";
			$igst_amount = "";
			$gst = "";

			if(count($taxes) > 0)
			{
				foreach ($taxes as $tax) {
				
					if(strpos($tax['name'], 'CGST') !== false) {
						$cgst = $tax['value'];
						$cgst_amount = $tax['amount'];

					} else if(strpos($tax['name'], 'SGST') !== false) {
						$sgst = $tax['value'];
						$sgst_amount = $tax['amount'];
					} else if(strpos($tax['name'], 'IGST') !== false) {
						$igst = $tax['value'];
						$igst_amount = $tax['amount'];
					}
				}

				if($cgst != "" && $sgst != "")
				{
					$igst = $cgst + $sgst;
					$igst_amount = $cgst_amount + $sgst_amount;
				}
				
				$gst = $cgst + $sgst;
			}


			$gst_report = new \stdClass;
			$gst_report->gst = $gst;
			$gst_report->cgst = $cgst;
			$gst_report->cgst_amount = Custom::two_decimal($cgst_amount);
			$gst_report->sgst = $sgst;
			$gst_report->sgst_amount = Custom::two_decimal($sgst_amount);
			$gst_report->igst = $igst;
			$gst_report->igst_amount = Custom::two_decimal($igst_amount);
			$gst_report->id = $transaction->id;
			$gst_report->transaction_type = $transaction->transaction_type;
			$gst_report->date = $transaction->date;
			$gst_report->billing_name = $transaction->billing_name;
			$gst_report->reference_no = $transaction->reference_no;
			$gst_report->item_no = $transaction->item_no;
			$gst_report->quantity = $transaction->quantity;
			$gst_report->rate = $transaction->rate;
			$gst_report->amount = $transaction->amount;
			$gst_report->is_discount_percent = $transaction->is_discount_percent;
			$gst_report->discount = $transaction->discount;
			$gst_report->total = $transaction->total;
			$gst_report->description = $transaction->description;
			$gst_report->unit = $transaction->unit;
			$gst_report->tax = $transaction->tax;
			$gst_report->hsn = $transaction->hsn;
			$gst_report->category_type = $transaction->category_type;
			$gst_report->discount_amount = $transaction->discount_amount;
			$gst_report->billing_address = $transaction->billing_address;
			$gst_report->shipping_date = $transaction->shipping_date;
			$gst_report->customer_name = $transaction->customer_name;
			$gst_report->pin = $transaction->pin;

			$transactions[] = $gst_report;

		}

		//dd($transactions);

		$total = Transaction::select('transactions.id','transactions.transaction_type_id',DB::raw('SUM(transactions.total) AS total'))
		->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
		->where('transactions.organization_id',$organization_id)
		->whereIn('account_vouchers.name', ['sales', 'sales_cash','purchases','credit_note','debit_note'])
		->first();	


		return response()->json(array('transactions' => $transactions,'total' => $total));
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
