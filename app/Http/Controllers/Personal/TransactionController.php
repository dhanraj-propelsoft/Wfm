<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalTransactionType;
use App\PersonalTransaction;
use App\PersonalCategory;
use App\PersonalAccount;
use App\PersonalContact;
use App\PersonalPeople;
use App\Transaction;
use App\AccountEntry;
use Carbon\Carbon;
use App\Custom;
use Auth;
use DB;


class TransactionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$transactions = PersonalTransaction::select('personal_transactions.id', 'personal_categories.name AS category', DB::raw('DATE_FORMAT(personal_transactions.date, "%d %b, %Y") AS date'), 'personal_transactions.amount', 'personal_transaction_types.name AS transaction_type')
		->leftjoin('personal_categories', 'personal_categories.id', '=', 'personal_transactions.category_id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.user_id', Auth::id())
		->groupby('personal_transactions.id')
		->get();

		return view('personal.transaction', compact('transactions'));
	}

	public function transaction_category(Request $request) {
		$category = PersonalCategory::select('personal_categories.id', 'personal_categories.name')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_categories.transaction_type')
		->where('personal_transaction_types.name', $request->type)
		->where('personal_categories.user_id', Auth::id())
		->where('personal_categories.status', 1)
		->get();

		return response()->json($category);
	}

	public function transaction_account(Request $request) {
		$account = PersonalAccount::select('personal_accounts.id', 'personal_accounts.name')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_categories.transaction_type')
		->where('personal_transaction_types.name', $request->type)
		->where('personal_categories.user_id', Auth::id())
		->where('personal_categories.status', 1)
		->get();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($reference = null, $type = null)
	{

		$accounts = PersonalAccount::where('personal_accounts.user_id', Auth::id())->where('personal_accounts.status', 1)->pluck('name', 'id');
		$accounts->prepend('Select Account', '');

		return view('personal.transaction_create', compact('type', 'accounts', 'reference', 'type'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		/*$this->validate($request, [
			'name' => 'required'
		]);*/

		//dd($request->all());

		$transaction_type = PersonalTransactionType::where('name', $request->input('transaction_type'))->first();
		$category = PersonalCategory::find($request->input('transaction_category'));
		$account = PersonalAccount::find($request->input('account_id'));

		$transaction = new PersonalTransaction();
		$transaction->source = $request->input('source');
		$transaction->type = ($request->input('type') != null) ? $request->input('type') : null;
		$transaction->reference_id = ($request->input('reference_id') != null) ? $request->input('reference_id') : null;
		$transaction->category_id = $request->input('transaction_category');
		$transaction->transaction_type = $transaction_type->id;
		$transaction->date = Carbon::parse($request->input('date'))->format('Y-m-d');
		$transaction->due_date = Carbon::parse($request->input('date'))->format('Y-m-d');
		$transaction->amount = $request->input('amount');
		$transaction->account_id = $request->input('account_id');
		$transaction->description = $request->input('description');

		$transaction->user_id = Auth::id();        
		$transaction->save();
		Custom::userby($transaction, true);

		if($request->input('transaction_type') == "income") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'receipt';
		} if($request->input('transaction_type') == "expense") {
			$debit_ledger = $category->ledger_id;
			$credit_ledger = $account->ledger_id;
			$voucher_type = 'payment';
		} if($request->input('transaction_type') == "liability") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'payment';
		}

		$entry[] = array('debit_ledger_id' => $debit_ledger, 'credit_ledger_id' => $credit_ledger, 'amount' => $transaction->amount);

        $transaction->entry_id = Custom::add_entry($transaction->date, $entry, null, $voucher_type, $transaction->user_id, 1, true);
        $transaction->save();

        if($request->input('reference_id') != null) {
        	$remote = Transaction::find($request->input('reference_id'));
        	if($remote != null) {
        		$remote->notification_status = 2;
        		$remote->save();
        	}
        	
        }

        return response()->json(['status' => 1, 'message' => 'Transaction'.config('constants.flash.added'), 'data' => ['id' => $transaction->id, 'name' => $transaction->name, 'amount' => $transaction->amount]]);  
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
		$accounts = PersonalAccount::where('personal_accounts.user_id', Auth::id())->where('personal_accounts.status', 1)->pluck('name', 'id');
		$accounts->prepend('Select Account', '');

		$transaction = PersonalTransaction::select('personal_transactions.id', 'personal_transactions.category_id', 'personal_transactions.transaction_type AS transaction_type_id', 'personal_transaction_types.name AS transaction_type', DB::raw('DATE_FORMAT(personal_transactions.date, "%d-%m-%Y") AS date'), 'personal_transactions.amount', 'personal_transactions.account_id', 'personal_transactions.description', 'personal_transactions.source')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.id', $id)
		->where('personal_transactions.user_id', Auth::id())->first();
		if(!$transaction) abort(403);

		$category = PersonalCategory::select('personal_categories.id', 'personal_categories.name')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_categories.transaction_type')
		->where('personal_transaction_types.id', $transaction->transaction_type_id)
		->where('personal_categories.user_id', Auth::id())
		->where('personal_categories.status', 1)
		->pluck('name', 'id');
		$category->prepend('Select Category', '');

		return view('personal.transaction_edit', compact('accounts', 'transaction', 'category'));
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
		$transaction_type = PersonalTransactionType::where('name', $request->input('transaction_type'))->first();
		$category = PersonalCategory::find($request->input('transaction_category'));
		$account = PersonalAccount::find($request->input('account_id'));

		$transaction = PersonalTransaction::find($request->input('id'));
		$transaction->source = $request->input('source');
		$transaction->category_id = $request->input('transaction_category');
		$transaction->transaction_type = $transaction_type->id;
		$transaction->date = Carbon::parse($request->input('date'))->format('Y-m-d');
		$transaction->due_date = Carbon::parse($request->input('date'))->format('Y-m-d');
		$transaction->amount = $request->input('amount');
		$transaction->account_id = $request->input('account_id');
		$transaction->description = $request->input('description');        
		$transaction->save();
		Custom::userby($transaction, false);

		if($request->input('transaction_type') == "income") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'receipt';
		} if($request->input('transaction_type') == "expense") {
			$debit_ledger = $category->ledger_id;
			$credit_ledger = $account->ledger_id;
			$voucher_type = 'payment';
		}

		$entry[] = array('debit_ledger_id' => $debit_ledger, 'credit_ledger_id' => $credit_ledger, 'amount' => $transaction->amount);

        $transaction->entry_id = Custom::add_entry($transaction->date, $entry, $transaction->entry_id, $voucher_type, $transaction->user_id, 1, true);
        $transaction->save();

        return response()->json(['status' => 1, 'message' => 'Transaction'.config('constants.flash.added'), 'data' => ['id' => $transaction->id, 'name' => $transaction->name, 'amount' => $transaction->amount]]); 
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$personal_transaction = PersonalTransaction::findOrFail($request->input('id'));

		if($personal_transaction->entry_id != null) {
			$account_entry =  AccountEntry::findOrFail($personal_transaction->entry_id);

			if($account_entry != null) {
				$account_entry->delete();
			}
		}
		 
		$personal_transaction->delete();

		return response()->json(['status' => 1, 'message' => 'transaction'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$transactions = explode(',', $request->id);

		$transaction_list = [];

		foreach ($transactions as $id) {
			$personal_transaction = PersonalTransaction::findOrFail($id);

			if($personal_transaction->entry_id != null) {
				$account_entry =  AccountEntry::findOrFail($personal_transaction->entry_id); 
				if($account_entry != null) {
					$account_entry->delete();
				}
			}
			$transaction_list[] = $id;
			$personal_transaction->delete();
			
		}

		return response()->json(['status'=>1, 'message'=>'Transaction'.config('constants.flash.deleted'),'data'=>['list' => $transaction_list]]);
	}
}
