<?php

namespace App\Http\Controllers\Api\Personal;

use App\Http\Controllers\Controller;
use App\AccountFinancialYear;
use App\PersonalTransaction;
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
	private $successStatus = 200;
	public function index()
	{
		$user_id = request('user_id');

		$financialyear = AccountFinancialYear::select('financial_start_year','financial_end_year')->where('user_id', $user_id)->where('status', '1')->first();

		$personal_accounts = DB::select("SELECT 
  personal_accounts.id,
  personal_accounts.name,
  COALESCE(personal_accounts.account_type, '') AS account_type,
  COALESCE(personal_accounts.account_number, '') AS account_number,
  IF(
	opening_balance_type = 'Debit',
	(
	  COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
	) + opening_balance,
	(
	  COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
	) - opening_balance
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
		
		$message['status'] =  '1';
		$message['accounts'] =  $personal_accounts;

		return response()->json($message, $this->successStatus);
	}

	public function cash()
	{
		$user_id = request('user_id');

		$update_date = date('d M Y'); //current date

		$total = PersonalTransaction::select(DB::raw('SUM(amount) as amount'))
					->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
					->where('personal_transactions.user_id', $user_id)
					->groupby('transaction_type')
					->orderby('personal_transaction_types.name', 'asc')->get();
					//return count($total);
		if(count($total) == 0){
			return response()->json(['status' => 1, 'data' => ['update_date' => $update_date, 
			'total_expense' => '0.00', 'total_income' => '0.00', 'cash' => '0.00' ]], $this->successStatus);
		} else {

			$total_expense = (count($total) > 0 ) ? $total[0]->amount : 0;
			$total_income = (count($total) > 1 ) ? $total[1]->amount : 0;

			return response()->json(['status' => 1, 'data' => ['update_date' => $update_date, 
			'total_expense' => $total_expense, 'total_income' => $total_income,
			'cash' => $total_income - $total_expense ]], $this->successStatus);
		}
	}

	public function store()
	{
		$user_id = request('user_id');

		$user = User::findOrFail($user_id);

		$account = new PersonalAccount;
		$account->name = request('name');
		$account->account_type = request('type');
		$account->account_number = request('account_number');
		$account->user_id = $user_id;
		$account->status = request('status');
		$account->save();

		Custom::userby($account, true, $user_id);

		if(request('type') == "Bank") {
			$ledgergroup = AccountGroup::where('name', 'bank_account')->where('user_id', $user_id)->first();
		} else if(request('type') == "Credit Card") {
			$ledgergroup = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();
		} else {
			$ledgergroup = AccountGroup::where('name', 'cash')->where('user_id', $user_id)->first();
		}
		
		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
		$year = AccountFinancialYear::where('user_id', $user_id)->first();

		$account->ledger_id = Custom::create_ledger($account->name, $user, $account->name, $impersonal_ledger->id, null, null, $ledgergroup->id, $year->books_year, 'debit', request('balance'), '1', '0', $user_id, 'true');
		$account->save();

		$message['status'] =  '1';
		$message['account'] =  $account;

		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.added'), 'data' => ['id' => $account->id, 'name' => $account->name, 'account_type' => ($account->account_type != null) ? $account->account_type : "" , 'account_number' => ($account->account_number != null) ? $account->account_number : "" , 'closing_balance' => (request('balance') != null) ? request('balance') : "0.00", 'status' => $account->status ]]);
	}

	public function update()
	{
		$user_id = request('user_id');

		$account = PersonalAccount::findOrFail(request('id'));
		$account->name = request('name');
		$account->account_type = request('type');
		$account->account_number = request('account_number');
		$account->user_id = $user_id;
		$account->status = request('status');
		$account->save();

		Custom::userby($account, false);

		if(request('type') == "Bank") {
			$ledgergroup = AccountGroup::where('name', 'bank_account')->where('user_id', $user_id)->first();
		} else if(request('type') == "Credit Card") {
			$ledgergroup = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();
		} else {
			$ledgergroup = AccountGroup::where('name', 'cash')->where('user_id', $user_id)->first();
		}

		$account_ledger =  AccountLedger::findOrFail($account->ledger_id); 
		$account_ledger->name = $account->name;
		$account_ledger->display_name = $account->name;
		$account_ledger->group_id = $ledgergroup->id;
		$account_ledger->save();

		Custom::userby($account_ledger, false);


		$financialyear = AccountFinancialYear::select('financial_start_year','financial_end_year')->where('user_id', Auth::id())->where('status', '1')->first();

		$balance = DB::select("SELECT 
  personal_accounts.id,
  personal_accounts.name,
  COALESCE(personal_accounts.account_type, '') AS account_type,
  COALESCE(personal_accounts.account_number, '') AS account_number,
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
	AND account_ledgers.id = ".$account->id." 
	 AND account_ledgers.status = 1 AND account_ledgers.approval_status = 1
	GROUP BY account_ledgers.id 
	ORDER BY account_groups.display_name
	");
		
		$message['status'] =  '1';
		$message['account'] =  $account;

		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.added'), 'data' => ['id' => $account->id, 'name' => $account->name, 'account_type' => ($account->account_type != null) ? $account->account_type : "" , 'account_number' => ($account->account_number != null) ? $account->account_number : "" , 'closing_balance' => (request('balance') != null) ? request('balance') : "0.00", 'status' => $account->status ]]);


	}

	public function destroy()
	{
		$personal_accounts = PersonalAccount::where(request('id'))->where('delete_status', "1")->first();

		if($personal_accounts->ledger_id != null) {
			$account_ledger =  AccountLedger::findOrFail($personal_accounts->ledger_id); 
			if($account_ledger != null) {
				$account_ledger->delete();
			}
		}

		$personal_accounts->delete();

		return response()->json(['status' => 1, 'message' => 'Account'.config('constants.flash.deleted'), 'data' => []]);
	}
}
