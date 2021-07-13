<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountFinancialYear;
use App\InventoryAdjustment;
use App\AccountLedgerType;
use App\TransactionItem;
use App\AccountVoucher;
use App\AccountLedger;
use App\AccountEntry;
use App\InventoryItem;
use App\PaymentMode;
use App\Organization;
use App\AccountGroup;
use App\ShipmentMode;
use App\PeopleTitle;
use App\HrmEmployee;
use App\Transaction;
use Carbon\Carbon;
use App\Discount;
use App\TaxGroup;
use App\Weekday;
use App\TaxType;
use App\People;
use App\Custom;
use App\State;
use App\Term;
use App\Tax;
use Session;
use Auth;
use DB;

class CashTransactionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($type)
	{

		//dd($type);
		$user_id = Auth::id();

		$transaction_type = AccountVoucher::where('name', $type)->where('user_id', $user_id)->first();

		//dd($transaction_type);

		if($transaction_type == null) {
			abort(404);
		}

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('user_id', $user_id)->first()->id;

		if($type == "payment") {
			$transaction_id = AccountVoucher::where('name', 'purchases')->where('user_id', $user_id)->first();
			$user = "Vendor";
			$account_type = "Bill";
			$title = "Payables";
			$cash_voucher = 'receipt';
			$return_voucher = 'credit_note';
			$voucher_type = "sales";
		} else if($type == "receipt") {
			$transaction_id = AccountVoucher::where('name', 'sales')->where('user_id', $user_id)->first();
			$user = "Customer";
			$account_type = "Invoice";
			$title = "Receivables";
			$cash_voucher = 'payment';
			$return_voucher = 'debit_note';
			$voucher_type = "purchases";
		}

		$payment = PaymentMode::pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$transaction = AccountEntry::select('account_entries.id',
		DB::raw('COALESCE(businesses.alias, "") AS category'),
		DB::raw("'' as category_id"),
		DB::raw('IF(payment_modes.name = "credit_card", "Credit Card", businesses.alias) AS business'),
		'account_entries.voucher_no AS order_no',
		DB::raw('IF(personal_transactions.reference_id IS NULL, 0, 1) AS notification_status'),
		'account_entries.date',
		'account_entries.voucher_id',
		'transactions.due_date',
		'payment_modes.name AS payment_mode',
		'account_entries.payment_mode_id',
		'account_entries.reference_transaction_id',
		'account_vouchers.name AS voucher_name',
		DB::raw('SUM(account_transactions.amount) AS total'),
		'account_entries.updated_at');

		$transaction->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$transaction->leftjoin('account_transactions','account_entries.id','=','account_transactions.entry_id');

		$transaction->leftjoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id');

		$transaction->leftjoin('transactions','transactions.id','=','account_entries.reference_transaction_id');

		$transaction->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$transaction->leftjoin('businesses','businesses.id','=','organizations.business_id');

		$transaction->leftJoin('personal_transactions', 'personal_transactions.reference_id','=', 'transactions.id');

		if($type == "receipt") {
			$transaction->whereIn('account_vouchers.name', ['payment']);
		}

		else if($type == "payment") {
			$transaction->whereIn('account_vouchers.name', ['receipt']);
		}		
		
		$transaction->where('transactions.people_id', Auth::user()->person_id);
		
		$transaction->where('transactions.user_type', 0);
		$transaction->groupby('account_entries.id');
		$transactions = $transaction->get();

		$customer_label = null;
		$ledger_label = null;
		$amount = null;
		$date = null;
		$payment_method = null;
		$reference_type = null;

		switch($type) {
			case 'receipt':
				$customer_label = "Customer";
				$ledger_label = "Deposit To";
				$amount = "Received Amount";
				$date = "Date";
				$payment_method = "Mode of Payment";
				$reference_type = "DN";
			break;
			case 'payment':
				$customer_label = "Vendor";
				$ledger_label = "Pay From";
				$amount = "Payment";
				$date = "Payment Date";
				$payment_method = "Payment Method";
				$reference_type = "GRN";
			break;
		}
		
		
		return view('personal.cash_transaction', compact('transaction_type', 'payment', 'type', 'user', 'account_type', 'title', 'customer_label', 'ledger_label', 'amount', 'date', 'payment_method', 'transaction_id', 'reference_type', 'transactions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($type)
	{
		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

		$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
		->where('user_type', 0)
		->where('organization_id', $organization_id)
		->pluck('name', 'id');
		$people->prepend('Select People', '');

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
		->where('user_type', 1)
		->where('organization_id', $organization_id)
		->pluck('name', 'id');
		$business->prepend('Select Business', '');

		$ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group');
		$ledger->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id');
		$ledger->whereIn('account_groups.name', ['cash']);
		$ledger->where('account_ledgers.organization_id', $organization_id);
		$ledger->where('account_ledgers.approval_status', '1');
		$ledger->where('account_ledgers.status', '1');
		$ledger->orderby('account_ledgers.id','asc');

		$ledgers = $ledger->pluck('name', 'id');

		$customer_label = null;
		$ledger_label = null;
		$amount = null;
		$date = null;
		$payment_method = null;
		$reference_type = null;

		switch($type) {
			case 'receipt':
				$customer_label = "Customer";
				$ledger_label = "Deposit To";
				$date = "Date";
				$payment_method = "Mode of Payment";
				$reference_type = 'DN';
			break;
			case 'payment':
				$customer_label = "Vendor";
				$ledger_label = "Pay From";
				$date = "Payment Date";
				$payment_method = "Payment Method";
				$reference_type = "GRN";
			break;
		}

		return view('inventory.cash_transaction_create', compact('type', 'payment', 'people', 'business','ledgers','transaction_type', 'customer_label', 'ledger_label', 'date', 'payment_method', 'reference_type'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrFail($organization_id);

		$entry = [];

		$transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();

		if($transaction_type->name == "payment") {
			$transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;
		} else if($transaction_type->name == "receipt") {
			$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
		}

		$previous_entry = AccountEntry::where('voucher_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$person_list = People::select('user_type', 'display_name');
		if($request->user_type == 0) {
			$person_list->where('person_id', $request->input('people_id'));
		} else if($request->user_type == 1) {
			$person_list->where('business_id', $request->input('people_id'));
		}
		$person_list->where('organization_id', $organization_id);
		$persons = $person_list->first();

		$account_ledgers = AccountLedger::select('account_ledgers.id');

		if($persons->user_type == 0) {
		  $account_ledgers->where('person_id', $request->input('people_id'));
		  $person_id = $request->input('people_id');
		  $business_id = null;
		}
		else if($persons->user_type == 1) {
		  $account_ledgers->where('business_id', $request->input('people_id'));
		  $business_id = $request->input('people_id');
		  $person_id = null;
		}
		
		$account_ledger = $account_ledgers->first();

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

		if($account_ledger != null){
			$customer_ledger = $account_ledger->id;
		}
		else {
			if($transaction_type->name == "payment") {
				$ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();
				$customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
			} else if($transaction_type->name == "receipt") {
				$ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();
				$customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
			}

		}

		$invoice_no = $request->input('order_id');
		$reference_id = $request->input('reference_id');

		$amount = $request->input('amount');
		$grn_no = $request->input('grn_no');

		for($i=0; $i < count($amount); $i++) {

			if($amount[$i] != "" && $amount[$i] != 0) {
				$entry = [];

				if($transaction_type->name == "payment") {

					$entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $request->input('ledger_id'), 'amount' => $amount[$i]];

				} else if($transaction_type->name == "receipt") {

					$entry[] = ['debit_ledger_id' => $request->input('ledger_id'), 'credit_ledger_id' => $customer_ledger, 'amount' => $amount[$i]];

				}

				Custom::add_entry(($request->input('invoice_date') != null) ? Carbon::parse($request->input('invoice_date'))->format('Y-m-d') : date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $reference_id[$i], $grn_no[$i], ($request->input('description') != null) ? $request->input('description') : null );								
			}
				
		}

		return response()->json(array('status' => 1, 'message' => 'Transaction'.config('constants.flash.added')));		
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

	public function get_payment(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', $request->input('transaction_type'))->where('organization_id', $organization_id)->first();

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

		if($request->input('transaction_type') == "payment") {
			$transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;
			$cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
			$return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
			$user = "Vendor";
		} else if($request->input('transaction_type') == "receipt") {
			$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
			$cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
			$return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
			$user = "Customer";
		}

		if($request->input('type') == "people") {

			$transactions = People::select('people.display_name AS customer',
				DB::raw('IF(transactions.id IS NULL, COUNT(transactions.id), COUNT(business.id)) AS invoices'),
				DB::raw("IF(

					(SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id 

					WHERE account_entries.reference_transaction_id IN (     IF(  CONVERT((SELECT GROUP_CONCAT(business.id)), CHAR) IS NULL,   CONVERT((SELECT GROUP_CONCAT(transactions.id)), CHAR),   CONVERT((SELECT GROUP_CONCAT(business.id)), CHAR))                )


					 AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))

					 IS NULL, 

					IF(SUM(transactions.total) IS NUll, SUM(business.total), SUM(transactions.total)), 


					IF(SUM(transactions.total) IS NUll, SUM(business.total), SUM(transactions.total)) -  
						
					(SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id

					WHERE account_entries.reference_transaction_id IN (     IF(  CONVERT((SELECT GROUP_CONCAT(business.id)), CHAR) IS NULL,   CONVERT((SELECT GROUP_CONCAT(transactions.id)), CHAR),   CONVERT((SELECT GROUP_CONCAT(business.id)), CHAR))                )

					
					AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))

				
					) AS total"),

				DB::raw('IF(people.person_id IS NULL, people.business_id, people.person_id) AS people_id'),
				'people.user_type')
			->leftjoin('transactions', function($query){
					$query->on('transactions.people_id','=','people.person_id');
					$query->where('transactions.user_type','=','0');
			})
			->leftjoin('transactions AS business', function($query){
					$query->on('business.people_id','=','people.business_id');
					$query->where('business.user_type','=','1');
			})

			->where(function($query) use($transaction_id) {
					$query->where('transactions.transaction_type_id','=', $transaction_id);
					$query->orWhere('business.transaction_type_id','=', $transaction_id);
			})
			->where('people.organization_id', $organization_id)
			->where(function($query) use($organization_id) {
					$query->where('transactions.organization_id','=', $organization_id);
					$query->orWhere('business.organization_id','=', $organization_id);
			})
			->where(function($query) use($transaction_id) {
					$query->where('transactions.approval_status','=', 1);
					$query->orWhere('business.approval_status','=', 1);
			})

			->where(function($query) use($transaction_id) {
					$query->where('transactions.notification_status','=', 2);
					$query->orWhere('business.notification_status','=', 2);
			})

			->groupBy('people.id')
			->havingRaw('total > 0')
			->get();

			//$transactions = $transaction->groupBy('transactions.people_id')->get();

		} else {

			$transaction = Transaction::select('transactions.id', 'transactions.order_no', 
				DB::raw('COALESCE(DATE_FORMAT(transactions.created_at, "%d-%m-%Y"), "") as created_on'), 
				DB::raw('COALESCE(DATE_FORMAT(transactions.date, "%d-%m-%Y"), "") as date'), 
				DB::raw('COALESCE(DATEDIFF(NOW(), transactions.due_date), "") as overdue'), 
				DB::raw('COALESCE(DATE_FORMAT(transactions.due_date, "%d-%m-%Y"), "") as due_date'), 
				DB::raw('IF(transactions.transaction_type_id = '.$transaction_type->id.', "1", 0) AS cash_transaction'),
			'transactions.total', 


			DB::raw('IF(people.id IS NULL, business.display_name, people.display_name ) AS customer'),


			'transactions.due_date as original_due_date',

				DB::raw("IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL, 

					transactions.total, 

					transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) AS balance"), 

				DB::raw("CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1  
			WHEN transactions.due_date < CURDATE()  THEN 3 
			WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2
			ELSE 0 
			END AS status"), 


				'transactions.approval_status', 'transactions.user_type', 'transactions.people_id');

			$transaction->leftJoin('people', function($query) {
				$query->on('transactions.people_id','=','people.person_id');
				$query->where('transactions.user_type','=','0');
			});

			$transaction->leftJoin('people AS business', function($query) {
				$query->on('transactions.people_id','=','business.business_id');
				$query->where('transactions.user_type','=','1');
			});

			$transaction->where('transactions.organization_id', $organization_id);
			$transaction->where('transactions.approval_status', 1);
			$transaction->where('transactions.transaction_type_id', $transaction_id);
			$transaction->groupby('transactions.id');

			$transaction->havingRaw('status != 1');

			$transaction->havingRaw('balance > 0');

			$transactions = $transaction->get();

		}

			return response()->json(array('status' => 1, 'data' => $transactions ));
	}

	public function get_invoice(Request $request)
	{
			$organization_id = Session::get('organization_id');
			
			$transaction_type = AccountVoucher::where('name', $request->transaction_type)->where('organization_id', $organization_id)->first()->id;

			$type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first()->id;

			$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

			if($request->transaction_type == "purchases") {
				$cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
				$return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
			} else if($request->transaction_type == "sales") {
				$cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
				$return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
			}

			$transactions = Transaction::select('transactions.id', 'transactions.order_no', 'transactions.total', DB::raw('COALESCE(DATE_FORMAT(transactions.due_date, "%d-%m-%Y"), "") as due_date'), 

				DB::raw("IF((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL, 
					transactions.total, transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) AS balance"))

			->where('transactions.people_id', $request->people_id)
			->where('transactions.user_type', $request->user_type)
			->where('transactions.transaction_type_id', $transaction_type)
			->where('transactions.organization_id', $organization_id)
			->where('transactions.approval_status', 1)
			->groupby('transactions.id')
			->havingRaw('balance > 0')
			->get();

		return response()->json($transactions);
	}

}