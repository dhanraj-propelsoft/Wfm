<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountFinancialYear;
use App\AccountLedgerType;
use App\PersonalAccount;
use App\AccountLedger;
use App\AccountGroup;
use Carbon\Carbon;
use App\Custom;
use App\User;
use Session;
use Auth;
use DB;


class AccountController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user_id = Auth::user()->id;

		$financialyear = AccountFinancialYear::select('financial_start_year','financial_end_year')->where('user_id', Auth::id())->where('status', '1')->first();

		$personal_accounts = DB::select("SELECT 
  personal_accounts.id,
  personal_accounts.name,
  personal_accounts.account_number,
  IF(
	opening_balance_type = 'Debit',
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) - opening_balance,
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) + opening_balance
  ) AS closing_balance,
  opening_balance,
  opening_balance_type,
  IF(
	IF(
	  opening_balance_type = 'Debit',
	  (
		COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	  ) - opening_balance,
	  (
		COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	  ) + opening_balance
	) > 0,
	'Cr',
	'Dr'
  ) AS balance_type,
  personal_accounts.status,
  personal_accounts.delete_status
FROM
  personal_accounts 
   LEFT JOIN account_ledgers 
    ON personal_accounts.ledger_id = account_ledgers.id
  LEFT JOIN account_transactions 
	ON account_transactions.debit_ledger_id = account_ledgers.id 
  LEFT JOIN account_groups
	ON account_ledgers.group_id = account_groups.id
  LEFT JOIN 
	(SELECT 
	  account_transactions.credit_ledger_id AS cr,
	  MIN(account_entries.date) AS cr_date,
	  SUM(
		account_transactions.amount
	  ) AS credit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.user_id = ".$user_id."
		 AND account_entries.status = 1
	GROUP BY cr) AS credit_account 
	ON credit_account.cr = account_ledgers.id 
  LEFT JOIN 
	(SELECT 
	  account_transactions.debit_ledger_id AS dr,
	  MIN(account_entries.date) AS dr_date,
	  SUM(
		account_transactions.amount
	  ) AS debit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.user_id = ".$user_id."
		AND account_entries.status = 1
	GROUP BY dr) AS debit_account 
	ON debit_account.dr = account_ledgers.id 
	WHERE account_ledgers.user_id = ".$user_id." 
	 AND account_ledgers.status = 1 AND account_ledgers.approval_status = 1
	GROUP BY account_ledgers.id 
	ORDER BY account_groups.display_name
	");

		return view('personal.account', compact('personal_accounts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$user_id = Auth::user()->id;

		return view('personal.account_create');
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
			'name' => 'required'
		]);

		$user_id = Auth::user()->id;

		$user = User::findOrFail($user_id);

		$account = new PersonalAccount;
		$account->name = $request->input('name');
		$account->account_number = $request->input('account_number');
		$account->user_id = $user_id;
		$account->save();

		Custom::userby($account, true);

		if($request->input('type') == "bank") {
			$ledgergroup = AccountGroup::where('name', 'bank_account')->where('user_id', $user_id)->first();
		} else if($request->input('type') == "credit_card") {
			$ledgergroup = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();
		} else {
			$ledgergroup = AccountGroup::where('name', 'cash')->where('user_id', $user_id)->first();
		}
		



		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
		$year = AccountFinancialYear::where('user_id', $user_id)->first();

		$account->ledger_id = Custom::create_ledger($account->name, $user, $account->name, $impersonal_ledger->id, null, null, $ledgergroup->id, $year->books_year, 'debit', $request->input('balance'), '1', '0', $user_id, 'true');
		$account->save();

		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.added'), 'data' => ['id' => $account->id, 'name' => $account->name, 'balance' => ($request->input('balance') != null) ? $request->input('balance') : "0.00", 'account_number' => ($account->account_number != null) ? $account->account_number : ""]]);
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
		$user_id = Auth::user()->id;

		$personal_accounts = PersonalAccount::where('id', $id)->where('user_id', $user_id)->first();
		if(!$personal_accounts) abort(403);

		return view('personal.account_edit', compact('personal_accounts'));
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

		$personal_accounts = PersonalAccount::findOrFail($request->input('id'));
		$personal_accounts->name = $request->input('name');
		$personal_accounts->account_number = $request->input('account_number');
		$personal_accounts->save();

		Custom::userby($personal_accounts, false);

		$account_ledger =  AccountLedger::findOrFail($personal_accounts->ledger_id); 
		$account_ledger->name = $personal_accounts->name;
		$account_ledger->display_name = $personal_accounts->name;
		$account_ledger->save();

		Custom::userby($account_ledger, false);

		$user_id = Auth::user()->id;

		$financialyear = AccountFinancialYear::select('financial_start_year','financial_end_year')->where('user_id', Auth::id())->where('status', '1')->first();

		$balance = DB::select("SELECT 
  IF(
	opening_balance_type = 'Debit',
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) - opening_balance,
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) + opening_balance
  ) AS closing_balance,
  opening_balance
FROM
  personal_accounts 
   LEFT JOIN account_ledgers 
    ON personal_accounts.ledger_id = account_ledgers.id
  LEFT JOIN account_transactions 
	ON account_transactions.debit_ledger_id = account_ledgers.id 
  LEFT JOIN account_groups
	ON account_ledgers.group_id = account_groups.id
  LEFT JOIN 
	(SELECT 
	  account_transactions.credit_ledger_id AS cr,
	  MIN(account_entries.date) AS cr_date,
	  SUM(
		account_transactions.amount
	  ) AS credit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.user_id = ".$user_id."
		 AND account_entries.status = 1
	GROUP BY cr) AS credit_account 
	ON credit_account.cr = account_ledgers.id 
  LEFT JOIN 
	(SELECT 
	  account_transactions.debit_ledger_id AS dr,
	  MIN(account_entries.date) AS dr_date,
	  SUM(
		account_transactions.amount
	  ) AS debit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.user_id = ".$user_id."
		AND account_entries.status = 1
	GROUP BY dr) AS debit_account 
	ON debit_account.dr = account_ledgers.id 
	WHERE account_ledgers.id = ".$account_ledger->id." 
	");


		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.updated'), 'data' => ['id' => $personal_accounts->id, 'name' => $personal_accounts->name, 'balance' => $balance[0]->closing_balance, 'account_number' => ($personal_accounts->account_number != null) ? $personal_accounts->account_number : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$personal_accounts = PersonalAccount::findOrFail($request->input('id'));
		$account_ledger =  AccountLedger::findOrFail($personal_accounts->ledger_id); 
		if($account_ledger->delete()) {
			$personal_accounts->delete();
		}
		
		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function account_status_approval(Request $request)
	{
		PersonalAccount::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}

}
