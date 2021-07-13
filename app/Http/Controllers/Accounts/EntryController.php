<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountTransaction;
use App\AccountVoucherType;
use App\AccountAllocation;
use App\AccountChequeBook;
use App\AccountRecurring;
use App\AccountVoucher;
use App\AccountLedger;
use App\AccountEntry;
use App\PaymentMode;
use App\Transaction;
use Carbon\Carbon;
use App\Weekday;
use App\Custom;
use Session;
use DB;

class EntryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	     $from_date_trade_wms =Carbon::today()->subDays( 30 )->format('Y-m-d');
	    $today_view = Carbon::today()->format('Y-m-d');

		$vouchers = AccountEntry::select('account_entries.id','transactions.entry_id','account_entries.reference_voucher_id', 'account_vouchers.display_name AS voucher_type','account_entries.voucher_no','account_ledgers.name AS ledger_name',

			DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN account_ledgers.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

			DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS date'), 'account_entries.voucher_no',		

			DB::raw('SUM(account_transactions.amount) AS amount'), 'reference.order_no AS reference')
		->leftJoin('transactions as reference','reference.id','=','account_entries.reference_voucher_id')
		->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
		->leftJoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
		->leftjoin('transactions','transactions.entry_id','=','account_entries.id')
		->where('account_entries.organization_id', Session::get('organization_id'))
		->whereBetween('account_entries.date',[$today_view,$today_view])
		
		->groupby('account_entries.id')		
		->get();

		$today = Carbon::today()->format('d-m-Y');
		$firstDay_only_trade_wms =Carbon::today()->subDays( 30 )->format('d-m-Y');
		
		return view('accounts.entries', compact('vouchers','today','firstDay_only_trade_wms'));
	}

	public function get_all_transactions(Request $request)
	{

		$from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');

		$vouchers = AccountEntry::select('account_entries.id','transactions.entry_id','account_entries.reference_voucher_id','account_vouchers.display_name AS voucher_type','account_entries.voucher_no','account_ledgers.name AS ledger_name',

			DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN account_ledgers.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

			DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS date'), 'account_entries.voucher_no',		

			DB::raw('SUM(account_transactions.amount) AS amount'), 'reference.order_no AS reference');
		$vouchers->leftJoin('transactions AS reference','reference.id','=','account_entries.reference_voucher_id');
		$vouchers->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$vouchers->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
		$vouchers->leftJoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
		$vouchers->leftjoin('transactions','transactions.entry_id','=','account_entries.id');
		$vouchers->where('account_entries.organization_id', Session::get('organization_id'));
		if(!empty($from_date) && !empty($to_date))
        {
          $vouchers->wherebetween('account_entries.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
       	{
       	  $vouchers->where('account_entries.date','>=',$from_date);
              
      	}
       	if($request->input('to_date'))
        {
           $vouchers->where('account_entries.date','<=',$to_date);
        }
		$vouchers->groupby('account_entries.id');	
		$get_vouchers = $vouchers->get();		

		
		return response()->json(['status' => 1, 'data' => $get_vouchers]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		//dd(Session::get('module_name'));

		$voucher_list = AccountVoucherType::select('account_vouchers.id', 'account_vouchers.display_name', 'account_voucher_types.name')
		->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
		->where('account_vouchers.organization_id', $organization_id)
		//->where('modules.name', Session::get('module_name'))
		->get();


		$ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$ledgers = $ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$ledgers->prepend('Select Ledger', '');

		//$cheque_books = AccountChequeBook::pluck('book_no','id');
		//$cheque_books->prepend('Select Cheque No','');

		$payment = PaymentMode::pluck('display_name','id');
		$payment->prepend('Select Payment Mode','');

		$weekdays = Weekday::pluck('display_name','id');
		$weekday = Weekday::where('name','monday')->first()->id;

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		return view('accounts.entry_create', compact('voucher_list', 'ledgers', 'weekdays', 'weekday', 'days','payment'));
	}

	public function get_cheque(Request $request)
	{
		$cheques = AccountChequeBook::select('account_cheque_books.id','account_cheque_books.ledger_id','account_cheque_books.status','account_cheque_books.cheque_no_from','account_cheque_books.cheque_no_to','account_cheque_books.no_of_leaves','account_entries.cheque_no','account_entries.cheque_book_id')
		->leftjoin('account_entries','account_cheque_books.id','=','account_entries.cheque_book_id')
		->where('account_cheque_books.ledger_id',$request->input('account_ledger_id'))
		->first();

		$cheque_numbers = [];

		for ($i=$cheques->cheque_no_from; $i <= $cheques->cheque_no_to; $i++)
		{
			$cheque_numbers[] = $i;
		}

		$existing_cheque = AccountEntry::select('account_entries.id','account_entries.cheque_book_id','account_entries.cheque_no');
		$existing_cheque->where('account_entries.cheque_book_id',$cheques->ledger_id);

		if($request->input('cheque_no') != null) {

			$existing_cheque->where('account_entries.cheque_no', $request->input('cheque_no'));
		}

		$existing_cheques = $existing_cheque->get();

		//dd($existing_cheques);

		$existing_cheque_no = [];

		foreach ($existing_cheques as $value)
		{
			$existing_cheque_no[] = $value->cheque_no;
		}

    	$cheque_numbers = array_diff($cheque_numbers, $existing_cheque_no);

		return response()->json(array('result' => $cheque_numbers));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$reference_transaction = Transaction::select('id')->where('transaction_type_id', $request->reference_type)->where('order_no', $request->reference_id)->first();

		$organization_id = Session::get('organization_id');

		$debit_ledger_id = $request->input('debit_ledger');
		$cheque_book_id = $request->input('cheque_book_id');
		$credit_ledger_id = $request->input('credit_ledger');
		$amount = $request->input('amount');
		$notes = $request->input('notes');

		$voucher_master = AccountVoucher::find($request->input('voucher_type'));

		$previous_entry = AccountEntry::where('voucher_id', $request->input('voucher_type'))->where('organization_id', $organization_id)->orderby('id','desc')->first();

		//$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;
		
		$vou_restart_value = AccountVoucher::select('restart')->where('id',$request->voucher_type)->first();
				//dd($vou_restart_value->restart);
	   if($vou_restart_value->restart == 0)
		  {
		      $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
		      Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
		  }
		  else
		  {
		       $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
		  	    Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
		  }
		$accountentries = new AccountEntry;
		$accountentries->voucher_no = Custom::generate_accounts_number($voucher_master->name, $gen_no, false);
		$accountentries->gen_no = $gen_no;
		
		$accountentries->reference_voucher = $request->input('reference_id');
		$accountentries->reference_voucher_id = $request->transaction_id;
		$accountentries->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		/*$accountentries->payment_mode_id = $organization_id;*/
		if($request->input('payment') != null){
			$accountentries->payment_mode_id = $request->input('payment');
		}
		if($request->input('cheque_no') != null){
			$accountentries->cheque_no = $request->input('cheque_no');
		}
		if($request->input('cheque_no') != null){
			$accountentries->cheque_book_id = $cheque_book_id;
		}
		$accountentries->organization_id = $organization_id;
		$accountentries->description = $request->input('notes');
		$accountentries->voucher_id = $request->input('voucher_type');
		$accountentries->reference_transaction_id = $request->transaction_id;
		/*if($reference_transaction != null) {
			$accountentries->reference_transaction_id = $reference_transaction->id;
		}*/

		$accountentries->save();
		Custom::userby($accountentries, true);

		if($accountentries->id)
		{
			$ledger_count = 0;

			if(count($debit_ledger_id) > count($credit_ledger_id)) 
				$ledger_count = count($debit_ledger_id);
			else
				$ledger_count = count($credit_ledger_id);

			for ($i=0; $i < $ledger_count; $i++) {
				$transaction = new AccountTransaction;
				$transaction->debit_ledger_id = isset($debit_ledger_id[$i]) ? $debit_ledger_id[$i] : $debit_ledger_id[0];
				$transaction->credit_ledger_id = isset($credit_ledger_id[$i]) ? $credit_ledger_id[$i] : $credit_ledger_id[0];
				if(count($notes) > 0) {
					$transaction->description = $notes[$i] ? $notes[$i] : null;
				}
				$transaction->amount = $amount[$i];
				$transaction->entry_id = $accountentries->id;
				Custom::userby($transaction, true);
			}

			/*$recurring = new AccountRecurring;
			$recurring->id = $accountentries->id;
			$recurring->interval = $accountentries->id;
			$recurring->week_day_id = $accountentries->id;
			$recurring->day = $accountentries->id;
			$recurring->period = $accountentries->id;
			$recurring->start_date = $accountentries->id;
			$recurring->end_date = $accountentries->id;
			$recurring->end_occurence = $accountentries->id;
			$recurring->save();
			Custom::userby($recurring, true);*/

			/*$allocation = new AccountAllocation;
			$allocation->id = $accountentries->id;
			$allocation->branch_id = $request->input('branch_id');
			$allocation->department_id = $request->input('department_id');
			$allocation->project_id = $request->input('project_id');
			$allocation->save();
			Custom::userby($allocation, true);*/
		}

		$entry = AccountEntry::select('account_entries.id', 'account_entries.voucher_no', 'account_entries.date', 'reference.voucher_no AS reference', 'account_vouchers.display_name AS voucher_type', DB::raw('SUM(account_transactions.amount) AS amount'))
		->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
		->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
		->leftjoin('account_entries AS reference', 'reference.id', '=', 'account_entries.reference_voucher_id')
		->where('account_entries.id', $accountentries->id)->first();
		
		if($transaction && $accountentries)
		{
			if($vou_restart_value->restart == 1)
			{
			  DB::table('account_vouchers')->where('organization_id', $organization_id)->where('id',$request->voucher_type)->update(['restart'=> '0', 'last_restarted' => Carbon::now()]);

			}
		}

		return response()->json(array('status' => 1, 'message' => 'Transaction'.config('constants.flash.added'), 'data' => ['id' => $entry->id, 'order_no' => $entry->voucher_no, 'date' => Carbon::parse($entry->date)->format('d-m-Y'), 'amount' => $entry->amount, 'reference' => ($entry->reference != null) ? $entry->reference : "" , 'type' => $entry->voucher_type]));
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
		//dd($id);
		$organization_id = Session::get('organization_id');

		$voucher_list = AccountVoucher::select('account_vouchers.id', 'account_vouchers.display_name', 'account_voucher_types.name')->where('organization_id', $organization_id)
		->leftJoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')
		->get();

		$ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$ledgers = $ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$ledgers->prepend('Select Ledger', '');

		$payment = PaymentMode::pluck('display_name','id');
		$payment->prepend('Select Payment Mode','');

		$weekdays = Weekday::pluck('display_name','id');
		$weekday = Weekday::where('name','monday')->first()->id;

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		$entry = AccountEntry::select('account_entries.id', 'account_entries.voucher_no','account_entries.reference_voucher_id', 'transactions.entry_id','account_entries.date', 'account_entries.voucher_id','account_vouchers.name AS voucher', 'account_vouchers.display_name AS voucher_name', 'reference.voucher_no AS reference_id','account_entries.description AS notes',
		DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN account_ledgers.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

		DB::raw('SUM(account_transactions.amount) AS total'),'account_entries.cheque_no','account_entries.payment_mode_id','payment_modes.display_name AS payment_mode', 'account_transactions.debit_ledger_id', 'account_transactions.credit_ledger_id')
		->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftJoin('account_entries AS reference','reference.id','=','account_entries.reference_voucher_id')
		->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
		->leftJoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
		->leftJoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id')
		->leftjoin('transactions','transactions.entry_id','=','account_entries.id')
		->where('account_entries.id', $id)->first();

		$cheque = AccountChequeBook::select('account_cheque_books.id','account_cheque_books.ledger_id','account_cheque_books.status','account_cheque_books.cheque_no_from','account_cheque_books.cheque_no_to','account_cheque_books.no_of_leaves','account_entries.cheque_no','account_entries.cheque_book_id');
		$cheque->leftjoin('account_entries','account_cheque_books.id','=','account_entries.cheque_book_id');
		if($entry->voucher == "deposit") {
			$cheque->where('account_cheque_books.ledger_id', $entry->debit_ledger_id);
		} else if($entry->voucher == "withdrawal") {
			$cheque->where('account_cheque_books.ledger_id', $entry->credit_ledger_id);
		}
		
		$cheques = $cheque->first();

		if($cheques != null) {
			$cheque_numbers = ["0" => "Select cheque"];

			for ($i=$cheques->cheque_no_from; $i <= $cheques->cheque_no_to; $i++)
			{
				$cheque_numbers[] = $i;
			}

			for ($i=$cheques->cheque_no_from; $i <= $cheques->cheque_no_to; $i++)
			{
				$cheque_numbers[] = $i;
			}

			$existing_cheque = AccountEntry::select('account_entries.id','account_entries.cheque_book_id','account_entries.cheque_no');
			$existing_cheque->where('account_entries.cheque_book_id',$cheques->ledger_id);

			if($entry->cheque_no != null) {

				$existing_cheque->where('account_entries.cheque_no', $entry->cheque_no);
			}

			$existing_cheques = $existing_cheque->get();

			//dd($existing_cheques);

			$existing_cheque_no = [];

			foreach ($existing_cheques as $value)
			{
				$existing_cheque_no[] = $value->cheque_no;
			}

	    	$cheque_numbers = array_diff($cheque_numbers, $existing_cheque_no);
		} else {
			$cheque_numbers = [];
		}
		


		return view('accounts.entry_edit', compact('voucher_list','ledgers', 'weekdays', 'weekday', 'days', 'entry', 'id','payment', 'cheque_numbers'));
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
		//return $request->all();

		$debit_ledger_id = $request->input('debit_ledger');
		$cheque_book_id = $request->input('cheque_book_id');
		$credit_ledger_id = $request->input('credit_ledger');
		$amount = $request->input('amount');
		$notes  = $request->input('notes');

		$accountentries = AccountEntry::findOrFail($request->id);
		$accountentries->reference_voucher_id = $request->input('reference_id');
		$accountentries->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		if($request->input('payment') != null){
			$accountentries->payment_mode_id = $request->input('payment');
		}
		if($request->input('cheque_no') != null){
			$accountentries->cheque_no = $request->input('cheque_no');
		}
		if($request->input('cheque_book_id') != null){
			$accountentries->cheque_book_id = $cheque_book_id;
		}
		
		$accountentries->description = $request->input('notes');
		$accountentries->save();
		Custom::userby($accountentries, true);

		DB::table('account_transactions')->where('account_transactions.entry_id', $request->id)->delete();


			$ledger_count = 0;

			if(count($debit_ledger_id) > count($credit_ledger_id)) 
				$ledger_count = count($debit_ledger_id);
			else
				$ledger_count = count($credit_ledger_id);

			for($i=0; $i < $ledger_count; $i++) { 
				$transaction = new AccountTransaction;
				$transaction->debit_ledger_id = isset($debit_ledger_id[$i]) ? $debit_ledger_id[$i] : $debit_ledger_id[0];
				$transaction->credit_ledger_id = isset($credit_ledger_id[$i]) ? $credit_ledger_id[$i] : $credit_ledger_id[0];
				if(count($notes) > 0) {
					$transaction->description = $notes[$i] ? $notes[$i] : null;
				}
				$transaction->amount = $amount[$i];
				$transaction->entry_id = $accountentries->id;
				Custom::userby($transaction, true);
			}

			

			/*$recurring = new AccountRecurring;
			$recurring->id = $accountentries->id;
			$recurring->interval = $accountentries->id;
			$recurring->week_day_id = $accountentries->id;
			$recurring->day = $accountentries->id;
			$recurring->period = $accountentries->id;
			$recurring->start_date = $accountentries->id;
			$recurring->end_date = $accountentries->id;
			$recurring->end_occurence = $accountentries->id;
			$recurring->save();
			Custom::userby($recurring, true);*/

			/*$allocation = new AccountAllocation;
			$allocation->id = $accountentries->id;
			$allocation->branch_id = $request->input('branch_id');
			$allocation->department_id = $request->input('department_id');
			$allocation->project_id = $request->input('project_id');
			$allocation->save();
			Custom::userby($allocation, true);*/

		$entry = AccountEntry::select('account_entries.id', 'account_entries.voucher_no', 'account_entries.date', 'reference.voucher_no AS reference', 'account_vouchers.display_name AS voucher_type', DB::raw('SUM(account_transactions.amount) AS amount'))
		->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
		->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
		->leftjoin('account_entries AS reference', 'reference.id', '=', 'account_entries.reference_voucher_id')
		->where('account_entries.id', $accountentries->id)->first();

		return response()->json(array('status' => 1, 'message' => 'Transaction'.config('constants.flash.added'), 'data' => ['id' => $entry->id, 'order_no' => $entry->voucher_no, 'date' => Carbon::parse($entry->date)->format('d-m-Y'), 'amount' => $entry->amount, 'reference' => ($entry->reference != null) ? $entry->reference : "" , 'type' => $entry->voucher_type]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$voucher = AccountEntry::findOrFail($request->id);

		$voucher->delete();

		return response()->json(['status' => 1, 'message' => 'Voucher'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function get_transaction_order(Request $request) {  

		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first()->id;

		$order = AccountEntry::where('voucher_no', $request->reference_id)->where('voucher_id', $transaction_type)->where('organization_id', $organization_id)->first();

		if($order == null) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	public function get_transactions(Request $request) {  

		$transactions = AccountTransaction::where('account_transactions.entry_id', $request->id)->get();

		return response()->json(array('status' => 1, 'data' => $transactions));
	}

	public function get_ledgers(Request $request)
	{
		$organization_id = Session::get('organization_id');
		//this condition used for voucher type change function in the account transaction screen

		$ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group');
		$ledger->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id');

		if($request->input('voucher_type') == "deposit" || $request->input('voucher_type') == "withdrawal") {
			$ledger->whereIn('account_groups.name', ['bank_account', 'unsecured_loan','cash']);
		}

		if($request->input('voucher_type') == "payment" || $request->input('voucher_type') == "receipt") {
			$ledger->whereIn('account_groups.name', ['cash']);
		}

		/*if($request->input('voucher_type') == "journal") {
			$ledger->whereNotIn('account_groups.name', ['cash']);
		}*/

		$ledger->where('account_ledgers.organization_id', Session::get('organization_id'));
		$ledger->where('account_ledgers.approval_status', '1');
		$ledger->where('account_ledgers.status', '1');
		$ledger->orderby('account_ledgers.display_name','asc');
		
		return response()->json(array('result' => $ledger->get()));
	}

	public function get_reference_type(Request $request) {
		$transaction_types = AccountVoucher::select('id', 'display_name AS name');

		if($request->voucher_type == "receipt") {
			$transaction_types->whereIn('name', ['sales']);
		} else if($request->voucher_type == "payment") {
			$transaction_types->whereIn('name', ['purchases']);
		}/* else if($request->voucher_type == "deposit") {
			$transaction_types->whereIn('name', ['purchases']);
		} else if($request->voucher_type == "withdrawal") {
			$transaction_types->whereIn('name', ['sales']);
		}*/ else if($request->voucher_type == "credit_note") {
			$transaction_types->whereIn('name', ['sales']);
		} else if($request->voucher_type == "debit_note") {
			$transaction_types->whereIn('name', ['purchases']);
		}
		
		$transaction_types->where('organization_id', Session::get('organization_id'));
		$transaction_type = $transaction_types->get();

		return response()->json(array('result' => $transaction_type));
	}
	
		public function cash_payment_index($type)
	{
		//dd($type);
		$today = Carbon::today()->format('d-m-Y');

		$from_date =Carbon::today()->subDays( 30 )->format('Y-m-d');
        $to_date = Carbon::today()->format('Y-m-d');

        $firstDay_only_trade_wms =Carbon::today()->subDays( 30 )->format('d-m-Y');
		$voucher = AccountEntry::select('account_entries.id','transactions.entry_id','account_entries.reference_voucher_id','account_vouchers.display_name AS voucher_type','account_entries.voucher_no','debit_account.name AS ledger_name','credit_account.name AS credit_ledger_name',

			DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN debit_account.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

			DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS date'), 'account_entries.voucher_no',		

			DB::raw('SUM(account_transactions.amount) AS amount'), 'reference.order_no AS reference')
		->leftJoin('transactions AS reference','reference.id','=','account_entries.reference_voucher_id')
		->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
		->leftJoin('account_ledgers as debit_account','debit_account.id','=','account_transactions.debit_ledger_id')
		->leftJoin('account_ledgers as credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
		->leftjoin('transactions','transactions.entry_id','=','account_entries.id')
		->where('account_entries.organization_id', Session::get('organization_id'))
		->whereBetween('account_entries.date',[$from_date,$to_date]);
		if($type != "receipt")
		{

			$voucher->where('account_vouchers.name',"=",$type);
		}
		if($type == "receipt")
		{
			$voucher->where(function ($query) use ($type) {
			    $query->where('account_vouchers.name', '=', $type)
			          ->orWhere('account_vouchers.name', '=', "wms_receipt");
			});
		
		}
		$voucher->groupby('account_entries.id');		
	$vouchers=$voucher->get();

		$today = Carbon::today()->format('d-m-Y');

		//dd($vouchers);
		
		return view('accounts.transaction_entries', compact('vouchers','today','type','firstDay_only_trade_wms'));
	}

	public function transaction_entries_create($type)
	{
		//dd($type);
		$organization_id = Session::get('organization_id');

		//dd(Session::get('module_name'));

		$voucher_list = AccountVoucherType::select('account_vouchers.id', 'account_vouchers.display_name', 'account_voucher_types.name')
		->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
		->where('account_vouchers.organization_id', $organization_id)
		//->where('modules.name', Session::get('module_name'))
		->get();

		$datas = AccountVoucherType::select('account_vouchers.id', 'account_vouchers.display_name', 'account_voucher_types.name')
		->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
		->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
		->where('account_vouchers.organization_id', $organization_id)
		->where('account_voucher_types.name',$type)
		->first();


		$ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$ledgers = $ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$ledgers->prepend('Select Ledger', '');

		//$cheque_books = AccountChequeBook::pluck('book_no','id');
		//$cheque_books->prepend('Select Cheque No','');

		$payment = PaymentMode::pluck('display_name','id');
		$payment->prepend('Select Payment Mode','');

		$weekdays = Weekday::pluck('display_name','id');
		$weekday = Weekday::where('name','monday')->first()->id;

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		return view('accounts.transaction_entry_create', compact('voucher_list', 'ledgers', 'weekdays', 'weekday', 'days','payment','type','datas'));
	}

	public function get_all_transactions_datas(Request $request)
	{

		//dd($request->all());

		$from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
                $type = $request->type_name;


		$vouchers = AccountEntry::select('account_entries.id','transactions.entry_id','account_entries.reference_voucher_id','account_vouchers.display_name AS voucher_type','account_entries.voucher_no','debit_account.name AS ledger_name','credit_account.name as credit_ledger_name',

			DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN debit_account.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

			DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS date'), 'account_entries.voucher_no',		

			DB::raw('SUM(account_transactions.amount) AS amount'), 'reference.order_no AS reference');
		$vouchers->leftJoin('transactions AS reference','reference.id','=','account_entries.reference_voucher_id');
		$vouchers->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$vouchers->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
		$vouchers->leftJoin('account_ledgers as debit_account','debit_account.id','=','account_transactions.debit_ledger_id');
		$vouchers->leftJoin('account_ledgers as credit_account','credit_account.id','=','account_transactions.credit_ledger_id');
		$vouchers->leftjoin('transactions','transactions.entry_id','=','account_entries.id');
		$vouchers->where('account_entries.organization_id', Session::get('organization_id'));
		if(!empty($from_date) && !empty($to_date))
        {
          $vouchers->wherebetween('account_entries.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
       	{
       	  $vouchers->where('account_entries.date','>=',$from_date);
              
      	}
       	if($request->input('to_date'))
        {
           $vouchers->where('account_entries.date','<=',$to_date);
        }
         if($request->type_name != "receipt")
        {
           $vouchers->where('account_vouchers.name','=',$request->type_name);

        }
        if($request->type_name == "receipt")
		{
			$vouchers->where(function ($query) use ($type) {
			    $query->where('account_vouchers.name', '=', $type)
			          ->orWhere('account_vouchers.name', '=', "wms_receipt");
			});
			//$voucher->orWhere('account_vouchers.name',"wms_receipt");
		}
		$vouchers->groupby('account_entries.id');	
		$get_vouchers = $vouchers->get();	
		//dd($get_vouchers);

		
		return response()->json(['status' => 1, 'data' => $get_vouchers ,'type' => $request->type_name]);
	}

	public function transaction_entries_edit($id)
	{
		//dd($id);
		$organization_id = Session::get('organization_id');

		$voucher_list = AccountVoucher::select('account_vouchers.id', 'account_vouchers.display_name', 'account_voucher_types.name')->where('organization_id', $organization_id)
		->leftJoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')
		->get();

		$ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name','account_ledgers.opening_balance_date','account_ledgers.delete_status','account_ledgers.approval_status','account_ledgers.delete_status')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$ledgers = $ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$ledgers->prepend('Select Ledger', '');

		$payment = PaymentMode::pluck('display_name','id');
		$payment->prepend('Select Payment Mode','');

		$weekdays = Weekday::pluck('display_name','id');
		$weekday = Weekday::where('name','monday')->first()->id;

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		$entry = AccountEntry::select('account_entries.id', 'account_entries.voucher_no', 'transactions.entry_id','account_entries.reference_voucher_id','account_entries.date', 'account_entries.voucher_id','account_vouchers.name AS voucher', 'account_vouchers.display_name AS voucher_name', 'reference.voucher_no AS reference_id','account_entries.description AS notes',
		DB::raw('sum(account_transactions.amount) - 2*(sum(CASE WHEN account_ledgers.name = "sales_discounts"  THEN account_transactions.amount ELSE 0 END)) as total_amount'),

		DB::raw('SUM(account_transactions.amount) AS total'),'account_entries.cheque_no','account_entries.payment_mode_id','payment_modes.display_name AS payment_mode', 'account_transactions.debit_ledger_id', 'account_transactions.credit_ledger_id')
		->leftJoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftJoin('account_entries AS reference','reference.id','=','account_entries.reference_voucher_id')
		->leftJoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
		->leftJoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
		->leftJoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id')
		->leftjoin('transactions','transactions.entry_id','=','account_entries.id')

		->where('account_entries.id', $id)->first();

      
		$cheque = AccountChequeBook::select('account_cheque_books.id','account_cheque_books.ledger_id','account_cheque_books.status','account_cheque_books.cheque_no_from','account_cheque_books.cheque_no_to','account_cheque_books.no_of_leaves','account_entries.cheque_no','account_entries.cheque_book_id');
		$cheque->leftjoin('account_entries','account_cheque_books.id','=','account_entries.cheque_book_id');
		if($entry->voucher == "deposit") {
			$cheque->where('account_cheque_books.ledger_id', $entry->debit_ledger_id);
		} else if($entry->voucher == "withdrawal") {
			$cheque->where('account_cheque_books.ledger_id', $entry->credit_ledger_id);
		}
		
		$cheques = $cheque->first();

		if($cheques != null) {
			$cheque_numbers = ["0" => "Select cheque"];

			for ($i=$cheques->cheque_no_from; $i <= $cheques->cheque_no_to; $i++)
			{
				$cheque_numbers[] = $i;
			}

			for ($i=$cheques->cheque_no_from; $i <= $cheques->cheque_no_to; $i++)
			{
				$cheque_numbers[] = $i;
			}

			$existing_cheque = AccountEntry::select('account_entries.id','account_entries.cheque_book_id','account_entries.cheque_no');
			$existing_cheque->where('account_entries.cheque_book_id',$cheques->ledger_id);

			if($entry->cheque_no != null) {

				$existing_cheque->where('account_entries.cheque_no', $entry->cheque_no);
			}

			$existing_cheques = $existing_cheque->get();

			//dd($existing_cheques);

			$existing_cheque_no = [];

			foreach ($existing_cheques as $value)
			{
				$existing_cheque_no[] = $value->cheque_no;
			}

	    	$cheque_numbers = array_diff($cheque_numbers, $existing_cheque_no);
		} else {
			$cheque_numbers = [];
		}
		


		return view('accounts.transaction_entry_edit', compact('voucher_list','ledgers', 'weekdays', 'weekday', 'days', 'entry', 'id','payment', 'cheque_numbers'));
	}
	
	public function transactions_order_no_search(Request $request)
	{
		//dd($request->all());
		$module_name =	Session::get('module_name');

		//dd($module_name);

		$keyword = $request->input('term');

		$organization_id = Session::get('organization_id');

		//dd($organization_id);
		if($request->type == "payment" || $request->type == "debit_note")
		{
			$transaction_type_purchase = AccountVoucher::where('name','=',"purchases")->where('organization_id', $organization_id)->first()->id;

		}
		if($request->type == "receipt" || $request->type == "credit_note")
		{
			$transaction_type_sales = AccountVoucher::where('name','=',"sales")->where('organization_id', $organization_id)->first()->id;
			$transaction_type_invoice = AccountVoucher::where('name','=',"job_invoice")->where('organization_id', $organization_id)->first()->id;

		}
		//$result = '';
         $item_array = [];
        // dd($request->type);
		if($request->type == "payment" || $request->type == "debit_note" || $request->type == "receipt" || $request->type == "credit_note")
			{
				//dd("sdf");
		 		$results = Transaction::select('transactions.order_no','transactions.id')
		 		->leftjoin('account_vouchers', 'account_vouchers.id', '=', 
		 			'transactions.transaction_type_id');
		 		if($request->type == "payment" || $request->type == "debit_note")
				{
					$results->where('transactions.transaction_type_id',$transaction_type_purchase);

				}
				if($request->type == "receipt" || $request->type == "credit_note")
				{

					$results->where(function ($query) use ($transaction_type_sales, $transaction_type_invoice) {
				    $query->where('transactions.transaction_type_id', '=', $transaction_type_sales)
				          ->orWhere('transactions.transaction_type_id', '=', $transaction_type_invoice);
					});
					

				}

				
		 			
		 		 $results->where('transactions.order_no','LIKE','%'.$keyword .'%'); 
		 		 $results->where('transactions.organization_id',$organization_id);
		 		 $results->where('transactions.approval_status',1);
		 		 $results->whereNull('transactions.deleted_at');
		 		 $results->take(10);              
                 $result = $results->get();
                 //dd($result);
                
          	$item_array = [];

			foreach ($result as  $value ) {

				$item_array[] = ['id' => $value->id, 'label' => $value->order_no ,'name' =>$value->order_no,'module'=>$value->name];
			}	
       }   

          
		
		return response()->json($item_array);
	}
	
	public function get_recepits_id(Request $request)
	{
		//dd($request->type_id);
		$organization_id = Session::get('organization_id');
		$voucher_type_id = Transaction::select('transaction_type_id')->where('id',$request->type_id)->first();
		//dd($voucher_type_id->transaction_type_id);
		$transaction_job_invoice = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

		$transaction_job_invoice_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

		$transaction_sales = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

		$transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;


		$cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
		$cash_voucher_receipt = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;

		//dd($voucher_type_id->transaction_type_id.$cash_voucher);
		if($voucher_type_id->transaction_type_id == $transaction_job_invoice || $voucher_type_id->transaction_type_id == $transaction_job_invoice_cash)
		{
			//dd("wms_receipt");
			return response()->json(['message' => 'Return Wms Receipt','data' => $cash_voucher]);
		}
		else
		{
			//dd("receipt");

			return response()->json(['message' => 'Return Receipt','data' => $cash_voucher_receipt]);

		}
	}
}
