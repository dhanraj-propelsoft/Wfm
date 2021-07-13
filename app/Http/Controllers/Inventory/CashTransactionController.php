<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountFinancialYear;
use App\InventoryAdjustment;
use App\AccountLedgerType;
use App\TransactionItem;
use App\AccountVoucher;
use App\AccountLedger;
use App\InventoryItem;
use App\CustomerGroping;
use App\PaymentMode;
use App\AccountEntry;
use App\Organization;
use App\AccountGroup;
use App\ShipmentMode;
use App\PeopleTitle;
use App\HrmEmployee;
use App\Transaction;
use App\PaymentMethod;
use Carbon\Carbon;
use App\Discount;
use App\TaxGroup;
use App\Weekday;
use App\TaxType;
use App\People;
use App\Custom;
use App\Country;
use App\State;
use App\Term;
use App\Tax;
use Session;
use DB;
use Illuminate\Support\Facades\Log;

class CashTransactionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($type)
	{
		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

		$jobcard_id = AccountVoucher::where('name', 'job_card')->where('organization_id', $organization_id)->first()->id;

		//dd($jobcard_id);
		$job_card = Transaction::leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->where('transaction_type_id', $jobcard_id)->where('transactions.organization_id', $organization_id)->where('wms_transactions.jobcard_status_id' ,'!=',8)->orderby('id','DESC')->pluck('transactions.order_no', 'transactions.id');
        $job_card->prepend('Select Job Card', ''); 

        //dd($job_card);

		$title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');
       

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

		if($transaction_type == null) {
			abort(404);
		}

		if($type == "payment") {
			$transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first();
			$user = "Vendor";
			$account_type = "Bill";
			$title1 = "Payables";
		} else if($type == "receipt") {
			$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first();
			//dd($transaction_id);
			$user = "Customer";
			$account_type = "Invoice";
			$title1 = "Receivables";
		}
		else if($type == "wms_receipt") {
			$transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first();
			//dd($transaction_id);
			$user = "WMS Customer";
			$account_type = "WMS Invoice";
			$title1 = "WMS Receivables";
		}
		
		
		$payment = PaymentMode::where('status', 1)->pluck('display_name','id');
		$payment->prepend('Cash','1');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
		->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
		->whereIn('account_groups.name', ['cash','bank_account'])
		->where('account_ledgers.organization_id', $organization_id)
		->where('account_ledgers.approval_status', '1')
		->where('account_ledgers.status', '1')
		->orderby('account_ledgers.id','asc')
		->pluck('name', 'id');

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
			case 'wms_receipt':
				$customer_label = "WMS Customer";
				$ledger_label = "Deposit To";
				$amount = "Received Amount";
				$date = "Date";
				$payment_method = "Mode of WMS Payment";
				$reference_type = "DN";
			break;
		}



		return view('inventory.cash_transaction', compact('transaction_type', 'payment', 'type', 'user', 'account_type', 'title1', 'ledgers', 'people', 'business', 'customer_label', 'ledger_label', 'amount', 'date', 'payment_method', 'transaction_id', 'reference_type','title', 'state', 'payment', 'terms','job_card','group_name'));
	}


	public function get_job_card_customer_name(Request $request)
	{
		//dd($request->all());
		$job_id = $request->id;
		//dd($$job_id);
		$job_name = Transaction::select('transactions.id','order_no','user_type','total')->where('id',$job_id)->first();
		//dd($job_name);
		$organization_id = session::get('organization_id');
		if($job_name->user_type == 0)
		{
			$cus_name = Transaction::select('transactions.id','transactions.total','people.display_name','people.person_id','transactions.user_type')
			->leftjoin('people','people.person_id','=','transactions.people_id')
			->where('transactions.order_no',$job_name->order_no)
			->where('transactions.organization_id',$organization_id)
			->whereNull('transactions.deleted_at')->first();
			//dd($query);
		}
		if($job_name->user_type == 1)
		{
			$cus_name = Transaction::select('transactions.id','transactions.total','people.display_name','people.business_id','transactions.user_type')
			->leftjoin('people','people.business_id','=','transactions.people_id')
			->where('transactions.order_no',$job_name->order_no)
			->where('transactions.organization_id',$organization_id)
			->whereNull('transactions.deleted_at')->first();
			//dd($query);
		}
		return response()->json(['message' => 'true','name'=>$cus_name]);
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

		$jobcard_id = AccountVoucher::where('name', 'job_card')->where('organization_id', $organization_id)->first()->id;

		//dd($jobcard_id);
		$job_card = Transaction::where('transaction_type_id', $jobcard_id)->where('organization_id', $organization_id)->orderby('order_no')->pluck('order_no', 'id');
        $job_card->prepend('Select Job Card', ''); 



		$payment = PaymentMode::where('status', 1)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');        

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

		/*$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
		->where('user_type', 0)
		->where('organization_id', $organization_id)
		->pluck('name', 'id');
		$people->prepend('Select People', '');

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
		->where('user_type', 1)
		->where('organization_id', $organization_id)
		->pluck('name', 'id');
		$business->prepend('Select Business', '');*/

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

		$business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
        ->where('user_type', 1)
        ->where('organization_id', Session::get('organization_id'));

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
        ->where('user_type', 0)
        ->where('organization_id', Session::get('organization_id'));

		$customer_type_label = 'Customer Type';
        $customer_label = 'Customer';
        $person_type = "customer";
        $people = $people_list->pluck('name', 'id');
        $business = $business_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');
        $business->prepend('Select Business', '');

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
			case 'wms_receipt':
				$customer_label = "Customer";
				$ledger_label = "Deposit To";
				$date = "Date";
				$payment_method = "Mode of Payment";
				$reference_type = 'DN';
			break;
		}

		return view('inventory.cash_transaction_create', compact('type', 'payment', 'people', 'business','ledgers','transaction_type', 'customer_label', 'ledger_label', 'date', 'payment_method', 'reference_type','title', 'state', 'payment', 'terms','customer_type_label','customer_label','person_type','job_card'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		Log::info("CashTransactionController->store :-request ".json_encode($request->all()));
		
		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrFail($organization_id);

		$entry = [];

		$transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();

		Log::info("CashTransactionController->store :- transaction_type ".json_encode($transaction_type));
		//dd($transaction_type);

		if($transaction_type->name == "payment") {
			$transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;
		} else if($transaction_type->name == "receipt") {
			$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
		}
		else if($transaction_type->name == "wms_receipt") {
			$transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;
		}
		

		$previous_entry = AccountEntry::where('voucher_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$person_list = People::select('user_type', 'display_name','id');
		if($request->user_type == 0) {
			$person_list->where('person_id', $request->input('people_id'));
		} else if($request->user_type == 1) {
			$person_list->where('business_id', $request->input('people_id'));
		}
		$person_list->where('organization_id', $organization_id);
		$persons = $person_list->first();
                    
		//dd($persons);

		$account_ledgers = AccountLedger::select('account_ledgers.id');

		if($persons != null) {
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
		} else {
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
			else if($transaction_type->name == "wms_receipt") {
				$ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();
				$customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
			}

		}

		$invoice_no = $request->input('order_id');
		$reference_id = $request->input('reference_id');
		$reference_voucher = $request->input('reference_voucher');
		$payment_mode = $request->input('payment_method_id');
		$description = $request->input('description');
		$amount = $request->input('amount');
		$grn_no = $request->input('grn_no');
        $checked_value = $request->input('checked_value');
		$due_amount = $request->input('due_amount');
		$entry_id = '';
   		$total_amount = 0;
   		$discount_amount = 0;  
		Log::info("CashTransactionController->store :- get reference id:-".$reference_id);

		Log::info("CashTransactionController->store :- amount before for loop");

		for($i=0; $i < count($amount); $i++) {

			if($amount[$i] != "" && $amount[$i] != 0) {
				$entry = [];

				if($checked_value == 'yes'){

				$transcation_amount = Transaction::select('transactions.total','transactions.entry_id')->where('transactions.id',$reference_id[0])->first();

				Log::info("CashTransactionController->FindTransactionAmount :-".json_encode($transcation_amount));

				$entry_id = $transcation_amount->entry_id;

				$paid_amounts = AccountEntry::select('account_transactions.amount')->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')->where('account_entries.reference_voucher_id',$reference_id)->get();

				$paid_amount = 0;
				foreach ($paid_amounts as  $value) {
		  	  	$paid_amount = $paid_amount + $value->amount;
				}

				if($paid_amount == 0){
				$discount_amount = $transcation_amount->total - $amount[$i];
				}else{
					$discount_amount = $due_amount - $amount[$i];
				}

				if($transaction_type->name == "payment") {

					$entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $request->input('ledger_id'), 'amount' => $due_amount,'discount_amount'=>$discount_amount];

				} else if($transaction_type->name == "receipt") {

					$entry[] = ['debit_ledger_id' => $request->input('ledger_id'), 'credit_ledger_id' => $customer_ledger, 'amount' => $due_amount,'discount_amount'=>$discount_amount];

				}
				else if($transaction_type->name == "wms_receipt") {

					$entry[] = ['debit_ledger_id' => $request->input('ledger_id'), 'credit_ledger_id' => $customer_ledger, 'amount' => $due_amount,'discount_amount'=>$discount_amount];

				}

			}

			if($checked_value == 'no'){	

				if($transaction_type->name == "payment") {

					$entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $request->input('ledger_id'), 'amount' => $amount[$i]];

				} else if($transaction_type->name == "receipt") {

					$entry[] = ['debit_ledger_id' => $request->input('ledger_id'), 'credit_ledger_id' => $customer_ledger, 'amount' => $amount[$i]];

				}
				else if($transaction_type->name == "wms_receipt") {

					$entry[] = ['debit_ledger_id' => $request->input('ledger_id'), 'credit_ledger_id' => $customer_ledger, 'amount' => $amount[$i]];

				}

            }           
                     
				$account_entryid=Custom::add_entry(($request->input('invoice_date') != null) ? Carbon::parse($request->input('invoice_date'))->format('Y-m-d') : date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $reference_id[$i], $reference_voucher,$payment_mode, ($description != null) ? $description  : null,$checked_value,$entry_id);


			    $voucher_name = AccountEntry::select('voucher_no')->where('id',$account_entryid)->first();

        		Log::info("CashTransactionController->store :- return checked_value - no ".json_encode($request));
			    return response()->json(array('status' => 1,'acount_entryid' => $account_entryid , 'message' => 'Transaction'.config('constants.flash.added'),'voucher_name' => $voucher_name));

			}
			else

			{
		        Log::info("CashTransactionController->store :- return else - ".json_encode($request));
				return response()->json(array('status' =>0, 'message' => 'Amount should be there.'));
			}
				
		}

			
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

		//dd($request->input('voucher_name'));

		$transaction_type = AccountVoucher::where('name', $request->input('transaction_type'))->where('organization_id', $organization_id)->first();

		//dd($transaction_type); 

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
		else if($request->input('transaction_type') == "wms_receipt") {
			$transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;
			$cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
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

			


			->groupBy('people.id')
			->havingRaw('total > 0')
			->get();

			//$transactions = $transaction->groupBy('transactions.people_id')->get();

		} 
		else {

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


				'transactions.approval_status', 'transactions.user_type', 'transactions.people_id','wms_transactions.advance_amount');

			$transaction->leftJoin('people', function($query) {
				$query->on('transactions.people_id','=','people.person_id');
				$query->where('transactions.user_type','=','0');
			});

			$transaction->leftJoin('people AS business', function($query) {
				$query->on('transactions.people_id','=','business.business_id');
				$query->where('transactions.user_type','=','1');
			});
             $transaction->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
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
		else if($request->transaction_type == "job_invoice") {
			$cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
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

	/*public function get_wms_invoice(Request $request)
	{
		$organization_id = Session::get('organization_id');
		
		$transaction_type = AccountVoucher::where('name', $request->transaction_type)->where('organization_id', $organization_id)->first()->id;

		$type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first()->id;

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

		if($request->transaction_type == "purchases") {
			$cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
			$return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
		} 
		else if($request->transaction_type == "job_invoice") {
			$cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
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
	}*/
	/*public function get_receipt(Request $request)	{
	
	$receipt_id = AccountEntry::select('id')->where('id','=', DB::raw(SELECT MAX(id) FROM account_entries));

}*/
}
