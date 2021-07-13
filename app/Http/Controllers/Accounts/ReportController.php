<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountEntry;
use App\AccountHead;
use App\AccountLedger;
use App\InventoryItemStock;
use App\InventoryItem;
use Carbon\Carbon;
use App\HrmBranch;
use App\TaxGroup;
use App\Custom;
use App\AccountGroup;
use App\Transaction;
use Session;
use DB;
use PDF;
use App\InventoryItemStockLedger;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
	public function trial_balance()
	{
	  $branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;
	  return view('accounts.trial_balance', compact('branch'));
	}

	public function get_trial_balance(Request $request)
	{
	  $accounts =  AccountHead::select('id', 'name')->where('status', '1')->get();	 

	  $organization_id = Session::get('organization_id');

	  $start_date = $request->start_date;
	  $end_date = $request->end_date;

	  $total =  DB::select("SELECT 
  		SUM(IF(closing_balance > 0,closing_balance,'0.00')) AS credit,
  		SUM(IF(closing_balance < 0,closing_balance,'0.00')) AS debit
		FROM
		  (SELECT 
			account_ledgers.id,
			account_ledgers.display_name AS ledger,
			credit_account.credit,
			debit_account.debit,
			account_ledgers.group_id,
			IF(
			  opening_balance_type = 'Debit',
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			) AS closing_balance,
			account_ledgers.display_name,
			opening_balance,
			opening_balance_type,
			IF(
			  IF( opening_balance_type = 'Debit',
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			  ) > 0, 'Cr','Dr' ) AS balance_type 
		  FROM
			account_ledgers 
			LEFT JOIN persons 
			  ON account_ledgers.person_id = persons.id 
			LEFT JOIN businesses 
			  ON account_ledgers.business_id = businesses.id 
			LEFT JOIN account_transactions 
			  ON account_transactions.debit_ledger_id = account_ledgers.id 
			LEFT JOIN account_groups
			  ON account_groups.id = account_ledgers.group_id
			LEFT JOIN account_heads 
			  ON account_groups.account_head = account_heads.id
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
				  WHERE account_entries.status = 1
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
				   WHERE account_entries.status = 1
			  GROUP BY dr) AS debit_account 
			  ON debit_account.dr = account_ledgers.id 
		  WHERE account_ledgers.organization_id = ".$organization_id."  AND (COALESCE(credit_account.cr_date,debit_account.dr_date) BETWEEN '".$start_date."' AND '".$end_date."')
		  GROUP BY account_ledgers.id 
		  ORDER BY account_ledgers.id) AS trial_balance 
		WHERE closing_balance != 0 LIMIT 1");

	  $suspense = $total[0]->credit - abs($total[0]->debit);

	  $ledgers_list = array();

	  foreach($accounts as $account) { 

		$ledgers_list[$account->name] = DB::select("SELECT 
  		id, ledger,group_id, IF(closing_balance > 0,closing_balance,'0.00') AS credit, IF(closing_balance < 0,closing_balance,'0.00') AS debit, closing_balance 
		FROM
		  (SELECT 
			account_ledgers.id,
			account_ledgers.display_name AS ledger,
			credit_account.credit,
			debit_account.debit,
			account_ledgers.group_id,
			IF(
			  opening_balance_type = 'Debit',
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			) AS closing_balance,
			account_ledgers.display_name,
			opening_balance,
			opening_balance_type,
			IF(
			  IF( opening_balance_type = 'Debit',
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			  ) > 0, 'Cr','Dr' ) AS balance_type 
		  FROM
			account_ledgers 
			LEFT JOIN persons 
			  ON account_ledgers.person_id = persons.id 
			LEFT JOIN businesses 
			  ON account_ledgers.business_id = businesses.id 
			LEFT JOIN account_transactions 
			  ON account_transactions.debit_ledger_id = account_ledgers.id 
			LEFT JOIN account_groups
			  ON account_groups.id = account_ledgers.group_id 
			LEFT JOIN account_heads 
			  ON account_groups.account_head = account_heads.id 
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
				   WHERE account_entries.status = 1
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
				   WHERE account_entries.status = 1
			  GROUP BY dr) AS debit_account 
			  ON debit_account.dr = account_ledgers.id 
		  WHERE account_ledgers.organization_id = ".$organization_id." 
			AND account_heads.id = ".$account->id." 
			  AND (COALESCE(credit_account.cr_date,debit_account.dr_date) BETWEEN '".$start_date."' AND '".$end_date."')
		  GROUP BY account_ledgers.id 
		  ORDER BY account_ledgers.id) AS trial_balance 
		WHERE closing_balance != 0");
	  }

	  //dd($ledgers_list);

		return response()->json(array('ledger_list' => $ledgers_list, 'total' => $total[0], 'suspense' => $suspense));
	}


	public function balance_sheet()
	{

	  $branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;

	  return view('accounts.balance_sheet', compact('branch'));
	}

	/* out side link - Balance sheet */

	public function get_balance_sheet(Request $request)
	{
	  	$asset 	= AccountHead::where('name', 'asset')->first();
	  	$expense = AccountHead::where('name', 'expense')->first();
	  	$income = AccountHead::where('name', 'income')->first();
	  	$liability = AccountHead::where('name', 'liability')->first();

	  	$organization_id = Session::get('organization_id');

	  	$start_date = $request->start_date;
	  	$end_date = $request->end_date;


	  	$total_asset = $this->total_account_sp($organization_id, $asset->id, "asset", $start_date, $end_date);

	  	$total_liability = $this->total_account_sp($organization_id, $liability->id, "liability", $start_date, $end_date);

	  	$total_profit = $this->total_account_sp($organization_id, $income->id, "income", $start_date, $end_date);

	  	$total_loss = $this->total_account_sp($organization_id, $expense->id, "expense", $start_date, $end_date);


		$total_trial =  DB::select("SELECT 
  		SUM(IF(closing_balance > 0,closing_balance,'0.00')) AS credit,
  		SUM(IF(closing_balance < 0,closing_balance,'0.00')) AS debit
		FROM
		  (SELECT 
			account_ledgers.id,
			account_ledgers.display_name AS ledger,
			credit_account.credit,
			debit_account.debit,
			IF(
			  opening_balance_type = 'Debit',
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
			  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			) AS closing_balance,
			account_ledgers.display_name,
			opening_balance,
			opening_balance_type,
			IF(
			  IF( opening_balance_type = 'Debit',
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance,
				( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance
			  ) > 0, 'Cr','Dr' ) AS balance_type 
		  FROM
			account_ledgers 
			LEFT JOIN persons 
			  ON account_ledgers.person_id = persons.id 
			LEFT JOIN businesses 
			  ON account_ledgers.business_id = businesses.id 
			LEFT JOIN account_transactions 
			  ON account_transactions.debit_ledger_id = account_ledgers.id 
			LEFT JOIN account_groups
			  ON account_groups.id = account_ledgers.group_id
			LEFT JOIN account_heads 
			  ON account_groups.account_head = account_heads.id
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
				  WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
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
				  WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
				   AND account_entries.status = 1 
			  GROUP BY dr) AS debit_account 
			  ON debit_account.dr = account_ledgers.id 
		  WHERE account_ledgers.organization_id = ".$organization_id." 
		  -- AND (COALESCE(credit_account.cr_date,debit_account.dr_date) BETWEEN '".$start_date."' AND '".$end_date."')
		  GROUP BY account_ledgers.id 
		  ORDER BY account_ledgers.id) AS trial_balance 
		WHERE closing_balance != 0 LIMIT 1");

	  	$suspense = $total_trial[0]->credit - abs($total_trial[0]->debit);


	  	$assets_array = array();

	  	$assets_array = array_merge($this->traverse_group($asset->id, $start_date, $end_date, "parent", "asset", 'NULL', $assets_array), $this->traverse_group($asset->id, $start_date, $end_date, "group", "asset", 'NULL', $assets_array));

	  	//dd($assets_array);

	  	$assets_result = array();
	  	$assets_id = array();  	  	

		  foreach($assets_array as $asset) {

			if (!in_array($asset['id'], $assets_id)) {
			  $assets_result[] = $asset;
			  $assets_id[] = $asset['id'];
			}
		  }


	  	$assets =  Custom::tree($assets_result);

	 	$liabilities_array = array();

	  	$liabilities_array = array_merge($this->traverse_group($liability->id, $start_date, $end_date, "parent", "liability", 'NULL', $liabilities_array), $this->traverse_group($liability->id, $start_date, $end_date, "group", "liability", 'NULL', $liabilities_array));

	  	$liabilities_result = array();
	  	$liabilities_id = array();

	  	foreach($liabilities_array as $liability) {

			if (!in_array($liability['id'], $liabilities_id)) {
			  $liabilities_result[] = $liability;
			  $liabilities_id[] = $liability['id'];
			}
	  	}

	  
	  	$liabilities =  Custom::tree($liabilities_result); 

	  	//dd($liabilities);
	  
	  	$profit = isset($total_profit[0]->closing_balance) ? $total_profit[0]->closing_balance : 0;
	  	$loss = isset($total_loss[0]->closing_balance) ? $total_loss[0]->closing_balance : 0;

	  	$report = null;

	  	if(($profit > $loss) && (abs($profit) - abs($loss)) != 0) {
		  $report = 'profit';
	  	} else if(($profit < $loss) && (abs($profit) - abs($loss)) != 0) {
		  $report = 'loss';
	  	}

	  	$statement = array('report' => $report, 'report_amount' => abs($profit - $loss), 'profit' => $profit, 'loss' => $loss );

	  	return response()->json(array('total_asset' => $total_asset, 'total_liability' => $total_liability, 'statement' => $statement, 'suspense' => $suspense, 'assets' => $assets, 'liabilities' => $liabilities));
	}

	/*End*/

	public function profit_and_loss()
	{
	  	$branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;

	  	return view('accounts.profit_loss', compact('branch'));
	}


	/* outside link - income and expense */

	public function get_profit_and_loss(Request $request)
    {
      	$expense = AccountHead::where('name', 'expense')->first();
      	$income = AccountHead::where('name', 'income')->first();
      	$liability = AccountHead::where('name', 'liability')->first();
      	$asset = AccountHead::where('name', 'asset')->first();

      	$organization_id = Session::get('organization_id');

      	$start_date = $request->start_date;
	  	$end_date = $request->end_date;

      	$pre_entry = AccountEntry::select('account_entries.id',DB::raw('MIN(account_entries.date) as pre_date'))               
        ->where('account_entries.organization_id', $organization_id) 
        ->first();

        /* start_date is changed to $pre_entry->pre_date */

        $total_profit = $this->total_account_sp($organization_id, $income->id, "income", $pre_entry->pre_date, $request->end_date);

      	$total_loss = $this->total_account_sp($organization_id, $expense->id, "expense", $pre_entry->pre_date, $request->end_date);

      	$total_liability = $this->total_account_sp($organization_id, $liability->id, "liability",  $pre_entry->pre_date, $end_date);

      	$total_asset = $this->total_account_sp($organization_id, $asset->id, "asset",  $pre_entry->pre_date, $end_date);

        $pre_balance_income = $this->total_account_sp($organization_id,$income->id, "income", $pre_entry->pre_date, $request->start_date);

        $pre_balance_expense = $this->total_account_sp($organization_id,$income->id, "expense", $pre_entry->pre_date, $request->start_date);

        /*$liabilities_array = array();

	  	$liabilities_array = $this->traverse_group($liability->id, $start_date, $end_date, "parent", "opening_stock", 'NULL', $liabilities_array);	  

	  	$liabilities_result = array();
	  	$liabilities_id = array(); 	 	

	  	foreach($liabilities_array as $liability) {

			if (!in_array($liability['id'], $liabilities_id)) {
			
				if($liability['name'] == 'Capital A/C')
				{
					$liability['name'] ='Opening Stock';
					$liabilities_result[] = $liability;
			  		$liabilities_id[] = $liability['id'];
				}
			}
	  	}*/	  	
	  	
	  	//$liabilities =  Custom::tree($liabilities_result);
	  	//dd($liabilities_result);
	  	//Log::info(' liablity tree?'.json_encode($liabilities));


      	$expenses_array = array();
 
      	$expenses_array = array_merge($this->traverse_group($expense->id, $pre_entry->pre_date, $request->end_date, "parent", "expense", 'NULL', $expenses_array), $this->traverse_group($expense->id, $pre_entry->pre_date, $request->end_date, "group", "expense", 'NULL', $expenses_array));

		//$expenses_array_new = array_merge($liabilities_result,$expenses_array);

		//dd($expenses_array);

      	$expense_result = array();

      	$expense_id = array();
	      foreach($expenses_array as $expense) {

	        if (!in_array($expense['id'], $expense_id)) {
      	 		
	          $expense_result[] = $expense;
	          $expense_id[] = $expense['id'];
	       
	        }
	      }	     

      	$expense =  Custom::tree($expense_result);


      	/*$assets_array = array();

	  	$assets_array = array_merge($this->traverse_group($asset->id, $start_date, $end_date, "parent", "asset", 'NULL', $assets_array), $this->traverse_group($asset->id, $start_date, $end_date, "group", "asset", 'NULL', $assets_array));	  	

	  	$assets_result = array();
	  	$assets_id = array();

		  foreach($assets_array as $asset) {

			if (!in_array($asset['id'], $assets_id)) {

				if($asset['name'] == 'Other Current Asset')
				{
					$asset['name'] ='Closing Stock';
					$asset['parent'] = null;
					$assets_result[] = $asset;
			 	 	$assets_id[] = $asset['id'];
				}
			}
		  }

	  	$assets =  Custom::tree($assets_result);*/

	  	
      	$incomes_array = array();

      	$incomes_array = array_merge($this->traverse_group($income->id, $pre_entry->pre_date, $request->end_date, "parent", "income", 'NULL', $incomes_array), $this->traverse_group($income->id, $pre_entry->pre_date, $request->end_date, "group", "income", 'NULL', $incomes_array));

      	//$incomes_array_new = array_merge($assets_result,$incomes_array);

      	$income_result = array();

      	$income_id = array();

      	$pre_income = isset($pre_balance_income[0]->closing_balance) ? $pre_balance_income[0]->closing_balance : 0;

      	foreach($incomes_array as $income) {
        	if (!in_array($income['id'], $income_id)) {
          		$income_result[] = $income;
          		$income_id[] = $income['id'];
        	}
      	}
      	

      	$income =  Custom::tree($income_result);

      	$profit = isset($total_profit[0]->closing_balance) ? $total_profit[0]->closing_balance : 0;

      	$loss = isset($total_loss[0]->closing_balance) ? $total_loss[0]->closing_balance : 0;

      	$report = null;

      	if(($profit > $loss) && (abs($profit) - abs($loss)) != 0) {
          $report = 'profit';
          //$loss = abs($profit) + abs($loss);
      	} else if(($profit < $loss) && (abs($profit) - abs($loss)) != 0) {
          $report = 'loss';
          //$profit = abs($profit) + abs($loss);
      	}

      	$statement = array("incomes" => $profit, 'expenses' => $loss, 'report' => $report, 'report_amount' => abs($profit - $loss), 'profit' => $profit, 'loss' => $loss );


  		return response()->json(array('total_profit' => $total_profit, 'total_loss' => $total_loss, 'statement' => $statement, 'expense' => $expense, 'income' => $income, 'profit' => $profit, 'loss' => $loss));
    }

    /*End*/

	public function total_account_sp($organization_id, $head, $head_name, $start_date, $end_date) 
	{
		return DB::select("SELECT 
		SUM(closing_balance) AS closing_balance 
		FROM
			(SELECT 
			  account_ledgers.id,
			  account_groups.account_head,
			  credit_account.credit,
			  debit_account.debit,
			  CASE
				WHEN '".$head_name."' = 'asset' OR '".$head_name."' = 'expense' 
				THEN IF(opening_balance_type = 'Debit',
				  (COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)) + opening_balance,
				  (COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)) - opening_balance
				) 
				WHEN '".$head_name."' = 'liability' OR '".$head_name."' = 'income' 
				THEN IF(opening_balance_type = 'Credit',
				  (COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance,
				  (COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance
				) 
			  END AS closing_balance,
			  account_ledgers.display_name,
			  opening_balance,
			  opening_balance_type 
			FROM
			  account_ledgers 
			  LEFT JOIN account_transactions 
				ON account_transactions.debit_ledger_id = account_ledgers.id 
			  LEFT JOIN account_groups 
				ON account_groups.id = account_ledgers.group_id 
			  LEFT JOIN account_heads 
				ON account_heads.id = account_groups.account_head 
			  LEFT JOIN 
			  	-- SUM ALL CREDIT LEDGERS AMOUNT BY LEDGER ID OF PARENT LEDGER
				-- HERE WE CAN GET THE SUM AMOUNT OF PARTICULAR LEDGER 
				(SELECT 
				  account_transactions.credit_ledger_id AS cr,
				  MIN(account_entries.date) AS cr_date,
				  SUM(account_transactions.amount) AS credit 
				FROM
				  account_transactions 
				  LEFT JOIN account_entries 
					ON account_transactions.entry_id = account_entries.id
				   LEFT JOIN account_vouchers
			       ON account_vouchers.id = account_entries.voucher_id	 
					WHERE account_entries.organization_id = ".$organization_id."
					 AND (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."')AND account_entries.organization_id = ".Session::get('organization_id')."
					  AND account_entries.status = 1 
				GROUP BY cr) AS credit_account 
				ON credit_account.cr = account_ledgers.id 
			  LEFT JOIN -- SUM ALL DEBIT LEDGERS AMOUNT BY LEDGER ID OF PARENT LEDGER
				-- HERE WE CAN GET THE SUM AMOUNT OF PARTICULAR LEDGER 
				(SELECT 
				  account_transactions.debit_ledger_id AS dr,
				  MIN(account_entries.date) AS dr_date,
				  SUM(account_transactions.amount) AS debit 
				FROM
				  account_transactions 
				  LEFT JOIN account_entries 
					ON account_transactions.entry_id = account_entries.id 
					LEFT JOIN account_vouchers
			       ON account_vouchers.id = account_entries.voucher_id
					WHERE account_entries.organization_id = ".$organization_id."
					AND (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
					 AND account_entries.status = 1 
				GROUP BY dr) AS debit_account 
				ON debit_account.dr = account_ledgers.id 
			WHERE account_ledgers.organization_id = ".$organization_id."
			  -- AND (COALESCE( credit_account.cr_date, debit_account.dr_date) BETWEEN '".$start_date."' AND '".$end_date."') 
			GROUP BY account_ledgers.id 
			ORDER BY account_ledgers.id) AS total_accounts 
		  WHERE account_head = ".$head." 
		  HAVING SUM(closing_balance) != 0 ");
	}


	public function traverse_group($id, $start_date, $end_date, $account_type, $head_name, $groupid, $group_array) {

	  	$organization_id = Session::get('organization_id');

	 	$ledger_group = $this->account_sp($organization_id, $id, $start_date, $end_date, $account_type, $head_name, $groupid);

	 	//dd($organization_id, $id, $start_date, $end_date, $account_type, $head_name, $groupid);
	 	
	 	//Log::info(' Ledger Group-'.json_encode($ledger_group));


		foreach ($ledger_group as $group) {

		  	$ledger_array = array();

		  	$asset_ledgers = $this->account_sp($organization_id, $id, $start_date, $end_date, "ledger", $head_name, $group->id);

		 	foreach ($asset_ledgers as $ledgers) {

		  		$equity = $ledgers->ledger;	
		  		//if($equity != 'Equity'){
		  		$ledger_array[] = array("id" => $ledgers->id, "name" => $ledgers->ledger, "amount" => $ledgers->closing_balance, "parent" => $ledgers->parent);
		  		//}
			
		  }		


		  $group_array[] = array("id" => $group->id, "name" => $group->ledger, "amount" => $group->closing_balance, "parent" => $group->parent, "ledgers" => $ledger_array);
	  	}



	  	return $group_array;
	}

   /*Total amount in outside links*/
	public function account_sp($organization_id, $head, $start_date, $end_date, $account_type, $head_name, $groupid) 
	{   
		$dpt_id = AccountGroup::select('id')->where('organization_id',$organization_id)->where('name','sundry_debtor')->first();


		//dd($organization_id, $head, $start_date, $end_date, $account_type, $head_name, $groupid);

		$crt_id = AccountGroup::select('id')->where('organization_id',$organization_id)->where('name','sundry_creditor')->first();

		
		
		$sql = "SELECT 
			CASE
		  WHEN '".$account_type."' = 'parent' THEN parent_group_id
		  WHEN '".$account_type."' = 'group' THEN group_id
		  WHEN '".$account_type."' = 'ledger' THEN ledger_id
			END AS id,
			CASE
		  WHEN '".$account_type."' = 'parent' THEN parent_name
		  WHEN '".$account_type."' = 'group' THEN group_name
		  WHEN '".$account_type."' = 'ledger' THEN ledger_name
			END AS ledger,
			CASE
		  WHEN '".$account_type."' = 'parent' THEN group_parent_id
		  WHEN '".$account_type."' = 'group' THEN parent_id
		  WHEN '".$account_type."' = 'ledger' THEN ledger_group_id
			END AS parent,
			SUM(closing_balance) AS closing_balance,
			IF(closing_balance < 0, 'Cr', 'Dr') AS balance_type 
		  FROM
			(SELECT 
			  account_ledgers.id AS ledger_id,
			  account_groups.id AS group_id,
			  IF(parent_groups.id IS NULL, account_groups.id, parent_groups.id) AS parent_group_id,
			  account_groups.account_head,
			  account_groups.display_name AS group_name,
			  IF(parent_groups.display_name  IS NULL, account_groups.display_name, parent_groups.display_name) AS parent_name,
			  account_ledgers.display_name AS ledger_name,
			  account_ledgers.group_id AS ledger_group_id,
			  account_groups.parent_id,
			  parent_groups.parent_id AS group_parent_id,
			  credit_account.credit,
			  debit_account.debit,

			  CASE
				WHEN '".$head_name."' = 'asset' OR '".$head_name."' = 'expense' 
				THEN IF(opening_balance_type = 'Debit',
				  ( COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)) + opening_balance,
				  ( COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)) - opening_balance
				) 
				WHEN '".$head_name."' = 'liability' OR '".$head_name."' = 'income' 
				THEN IF(opening_balance_type = 'Credit',
				  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) + opening_balance,
				  ( COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)) - opening_balance
				) 
				WHEN '".$head_name."' = 'opening_stock' 
				THEN (inventory_item_stock_ledgers.purchase_price)
			  END AS closing_balance,
			  opening_balance_type 
			FROM
			  account_ledgers 
			  LEFT JOIN persons 
				ON account_ledgers.person_id = persons.id 
			  LEFT JOIN businesses 
				ON account_ledgers.business_id = businesses.id 
			  LEFT JOIN account_transactions 
				ON account_transactions.debit_ledger_id = account_ledgers.id 
			  LEFT JOIN account_groups 
				ON account_groups.id = account_ledgers.group_id 
			  LEFT JOIN account_groups AS parent_groups
		  		ON parent_groups.id = account_groups.parent_id
			  LEFT JOIN account_heads 
				ON account_heads.id = account_groups.account_head 
			  LEFT JOIN inventory_item_stock_ledgers
			  	ON 	inventory_item_stock_ledgers.account_entry_id = account_transactions.entry_id	
			  LEFT JOIN 
				/* SUM ALL CREDIT LEDGERS AMOUNT BY LEDGER ID OF PARENT LEDGER
		   		HERE WE CAN GET THE SUM AMOUNT OF PARTICULAR LEDGER */
				(SELECT 
				  account_transactions.credit_ledger_id AS cr,
				  MIN(account_entries.date) AS cr_date,
				  SUM(account_transactions.amount) AS credit 
				FROM
				  account_transactions 
				  LEFT JOIN account_entries 
					ON account_transactions.entry_id = account_entries.id 
				  LEFT JOIN account_vouchers
				   ON account_vouchers.id = account_entries.voucher_id	
					WHERE account_entries.organization_id = ".$organization_id."
					AND (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."')
					AND account_entries.status = 1 
				GROUP BY cr) AS credit_account 
				ON credit_account.cr = account_ledgers.id 
			  LEFT JOIN 
				/* SUM ALL DEBIT LEDGERS AMOUNT BY LEDGER ID OF PARENT LEDGER
		   		HERE WE CAN GET THE SUM AMOUNT OF PARTICULAR LEDGER */
				(SELECT 
				  account_transactions.debit_ledger_id AS dr,
				  MIN(account_entries.date) AS dr_date,
				  SUM(account_transactions.amount) AS debit ,
				  account_vouchers.name AS voucher_name
				FROM
				  account_transactions 
				  LEFT JOIN account_entries 
					ON account_transactions.entry_id = account_entries.id 
					LEFT JOIN account_vouchers
				   ON account_vouchers.id = account_entries.voucher_id
					WHERE account_entries.organization_id = ".$organization_id."
					AND (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."')
					AND account_entries.status = 1 
				GROUP BY dr) AS debit_account 
				ON debit_account.dr = account_ledgers.id 
			WHERE account_ledgers.organization_id = ".$organization_id." 
			-- AND (COALESCE(credit_account.cr_date,debit_account.dr_date) BETWEEN '".$start_date."' AND '".$end_date."')
			   AND CASE
				WHEN '".$account_type."' = 'parent' THEN account_groups.id IS NOT NULL
		  WHEN '".$account_type."' = 'group' THEN account_groups.id IS NOT NULL
		  WHEN '".$account_type."' = 'ledger' THEN account_groups.id = ".$groupid."
			  END
			  
		   
		   GROUP BY account_ledgers.id
			
		 	/*   CASE
		  	WHEN '".$account_type."' = 'parent' THEN account_groups.id
		  	WHEN '".$account_type."' = 'group' THEN account_groups.id
		  	WHEN '".$account_type."' = 'ledger' THEN account_ledgers.id
			END */
		   
			ORDER BY account_groups.id
			
			
			) AS accounts 
		 	 WHERE account_head = ".$head." 
		 	GROUP BY id 
			-- HAVING closing_balance != 0
		  ORDER BY id";
		 
 
		return DB::select($sql);
		
	}


 	/* All Inside link*/

	public function get_ledger_report(Request $request)
	{	  
	   $organization_id = Session::get('organization_id');
	   $start_date 	= $request->start_date;
	   $end_date 	= $request->end_date;
	   $ledger_id 	= $request->id;
	   $group_name 	= $request->group_name;

	   $prev_date = date('Y-m-d', strtotime($start_date .' -1 day'));

	   //dd($start_date);

	   $account_ledger_name = AccountLedger::where('id', $ledger_id)->first()->name;

	   $ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS ledger', 'account_ledgers.opening_balance','account_ledgers.opening_balance_type', 'account_ledgers.updated_at', 'account_ledgers.opening_balance_date')
        ->where('account_ledgers.id', $ledger_id)
        ->first();

        /*get organization first transaction date */

        $pre_entry = AccountEntry::select('account_entries.id',DB::raw('MIN(account_entries.date) as pre_date'))
        ->leftjoin('account_vouchers','account_vouchers.id', '=' ,'account_entries.voucher_id')
        ->leftjoin('account_transactions','account_transactions.entry_id', '=' ,'account_entries.id')
        ->leftjoin('account_ledgers AS debit_ledger','debit_ledger.id', '=' ,'account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_ledger','credit_ledger.id', '=' ,'account_transactions.credit_ledger_id')        
        ->where('account_entries.organization_id', $organization_id)
        ->where('debit_ledger.id',$ledger_id)
        ->orWhere('credit_ledger.id',$ledger_id)
        ->first();

        /*end*/

       	$opening_balance = $this->ledger_closing_sp($ledger_id, Carbon::parse($pre_entry->pre_date)->subDay()->toDateString(), Carbon::parse($pre_entry->pre_date)->subDay()->toDateString(),$group_name);      

       	/*its changed*/
       	$closing_balance = $this->ledger_closing_sp($ledger_id, $pre_entry->pre_date, $end_date,$group_name);
       	/*end*/


       	/* previous opening amount */

		$pre_balance = $this->ledger_closing_sp($ledger_id, $pre_entry->pre_date, $prev_date,$group_name);

		/*end*/
		
		//dd($pre_balance);

        if($account_ledger_name == 'sales' || $account_ledger_name == 'opening_equity' || $group_name == 'Sundry Debtors' || $group_name == 'Sundry creditors' || $group_name =='Duties &amp; Taxes')
		{
			//dd('1');
			$ledger_statement = DB::select("SELECT 
			  account_entries.id,
			  account_transactions.id AS voucher_acc_id,
			  account_entries.voucher_no,
			  account_vouchers.code AS voucher_code,
			  account_vouchers.id AS voucher_master_id,
			  account_vouchers.display_name AS voucher_type,
			  debit_ledger.display_name AS debit_account,
			  credit_ledger.display_name AS credit_account,
			  
			  IF(debit_ledger.id = ".$ledger_id.",sum(account_transactions.amount), '0.00') AS debit,
			  IF(credit_ledger.id = ".$ledger_id.",sum(account_transactions.amount), '0.00') AS credit,
			  account_entries.date,
			  account_vouchers.name AS voucher_master
			FROM
			  account_entries
			  LEFT JOIN account_vouchers 
			    ON account_vouchers.id = account_entries.voucher_id 
			  LEFT JOIN account_transactions 
			    ON account_entries.id = account_transactions.entry_id 
			  LEFT JOIN account_ledgers AS debit_ledger 
			    ON debit_ledger.id = account_transactions.debit_ledger_id 
			  LEFT JOIN account_ledgers AS credit_ledger 
			    ON credit_ledger.id = account_transactions.credit_ledger_id 
			WHERE account_entries.organization_id = ".$organization_id." 
			  AND (debit_ledger.id = ".$ledger_id." OR credit_ledger.id = ".$ledger_id.") AND (DATE BETWEEN '".$start_date."' AND '".$end_date."')
			 AND account_entries.status = 1 AND account_vouchers.name != 'stock_journal'
			 GROUP BY account_entries.id
			  ");
		}else
		{
			//dd('2');
	        $ledger_statement = DB::select("SELECT 
			  account_entries.id,
			  account_transactions.id AS voucher_acc_id,
			  account_entries.voucher_no,
			  account_vouchers.code AS voucher_code,
			  account_vouchers.id AS voucher_master_id,
			  account_vouchers.display_name AS voucher_type,
			  debit_ledger.display_name AS debit_account,
			  credit_ledger.display_name AS credit_account,
			  
			  IF(debit_ledger.id = ".$ledger_id.", sum(account_transactions.amount), '0.00') AS debit,
			  IF(credit_ledger.id = ".$ledger_id.",sum(account_transactions.amount), '0.00') AS credit,
			  account_entries.date,
			  account_vouchers.name AS voucher_master
			FROM
			  account_entries
			  LEFT JOIN account_vouchers 
			    ON account_vouchers.id = account_entries.voucher_id 
			  LEFT JOIN account_transactions 
			    ON account_entries.id = account_transactions.entry_id 
			  LEFT JOIN account_ledgers AS debit_ledger 
			    ON debit_ledger.id = account_transactions.debit_ledger_id 
			  LEFT JOIN account_ledgers AS credit_ledger 
			    ON credit_ledger.id = account_transactions.credit_ledger_id 
			WHERE account_entries.organization_id = ".$organization_id." 
			  AND (debit_ledger.id = ".$ledger_id." OR credit_ledger.id = ".$ledger_id.") AND (DATE BETWEEN '".$start_date."' AND '".$end_date."')
			 AND account_entries.status = 1
			 GROUP BY account_entries.id
			  ");
	    }
	   
        //dd($ledger_statement);

        return response()->json(array('opening_balance' => $opening_balance, 'ledger_statement' => $ledger_statement, 'closing_balance' => $closing_balance, 'opening_date' => $request->start_date, 'closing_date' => $end_date, 'ledger' => $ledger,'account_ledger_name' => $account_ledger_name,'pre_balance' => $pre_balance));
	}

	/* End */

	public function ledger_closing_sp($ledger, $start_date, $end_date, $group_name)
	{
		//dd($start_date);
		$a_ledger = AccountLedger::select('name')->where('id',$ledger)->first();
		$ledger_name = $a_ledger->name;

		if($group_name == 'Sundry Debtors' || $group_name == 'Sundry creditors' || $group_name =='Duties &amp; Taxes')
		{

			$account_ledgers = DB::select("SELECT account_ledgers.id,account_ledgers.display_name AS ledger,account_groups.display_name AS ledger_group_name,
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
		  account_ledgers.status,account_ledgers.approval_status,account_ledgers.delete_status
		  FROM
		  account_ledgers 
		  
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
		      LEFT JOIN account_vouchers
		       ON  account_vouchers.id = account_entries.voucher_id  
		        WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
		        AND account_entries.status = 1 AND account_vouchers.name != 'stock_journal'
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
		      LEFT JOIN account_vouchers
		       ON  account_vouchers.id = account_entries.voucher_id   
		        WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
			 AND account_entries.status = 1 AND account_vouchers.name != 'stock_journal'
		    GROUP BY dr) AS debit_account 
		    ON debit_account.dr = account_ledgers.id 
		    WHERE account_ledgers.organization_id = ".Session::get('organization_id')."
		    AND account_ledgers.id = ".$ledger."
		    GROUP BY account_ledgers.id 
		    ORDER BY account_groups.display_name
		    ");
		}else{
			
			$account_ledgers = DB::select("SELECT 
		  	account_ledgers.id,
		  	account_ledgers.display_name AS ledger,
		  	account_groups.display_name AS ledger_group_name,
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
		  account_ledgers.status,account_ledgers.approval_status,account_ledgers.delete_status
		  FROM
		  account_ledgers 
		  
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
		      LEFT JOIN account_vouchers
		       ON  account_vouchers.id = account_entries.voucher_id  
		        WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
		        AND account_entries.status = 1 AND account_vouchers.name != 'stock_journal'
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
		        WHERE (account_entries.date BETWEEN '".$start_date."' AND '".$end_date."') AND account_entries.organization_id = ".Session::get('organization_id')."
			 AND account_entries.status = 1
		    GROUP BY dr) AS debit_account 
		    ON debit_account.dr = account_ledgers.id 
		    WHERE account_ledgers.organization_id = ".Session::get('organization_id')."
		    AND account_ledgers.id = ".$ledger."
		    GROUP BY account_ledgers.id 
		    ORDER BY account_groups.display_name
		    ");

		    //dd($account_ledgers);
		}

		return $account_ledgers;
	}

	
	public function journal_report(Request $request)
	{
		$branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;
		 
		return view('accounts.journal_report', compact( 'branch'));
	}

	public function get_journal_report(Request $request)
	{
		$organization_id = Session::get('organization_id');
		$start_date = $request->start_date;
		$end_date = $request->end_date;

		  $voucher_no = AccountEntry::where('organization_id', Session::get('organization_id'))->get();
		  $journal_report = array();

		  foreach($voucher_no as $voucher) {


		$total_amount = DB::select("SELECT SUM(debit) AS debit, SUM(credit) AS credit
			FROM (SELECT 
		  account_entries.id,
		  account_transactions.id AS voucher_acc_id,
		  account_entries.voucher_no,
		  account_entries.reference_voucher_id,
		  account_ledgers.display_name AS account,
		  credit_ledger.display_name AS TRANSACTION,
		  account_entries.date,
		  account_vouchers.name AS voucher_master,
		  account_transactions.amount AS debit,
		  '0.00' AS credit 
		FROM
		  account_entries 
		  LEFT JOIN account_vouchers 
			ON account_vouchers.id = account_entries.voucher_id 
		  LEFT JOIN account_transactions 
			ON account_entries.id = account_transactions.entry_id 
		  LEFT JOIN account_ledgers 
			ON account_ledgers.id = account_transactions.debit_ledger_id 
		  LEFT JOIN account_ledgers AS credit_ledger 
			ON credit_ledger.id = account_transactions.credit_ledger_id 
		WHERE account_entries.organization_id = ".$organization_id." 
		  AND account_entries.voucher_no = '".$voucher->voucher_no."'
		  AND account_entries.voucher_id  = '".$voucher->voucher_id."'
		  AND account_entries.status = 1
		  UNION ALL
		  
		  SELECT 
		  account_entries.id,
		  account_transactions.id AS voucher_acc_id,
		  account_entries.voucher_no,
		  account_entries.reference_voucher_id,
		  account_ledgers.display_name AS account,
		  debit_ledger.display_name AS TRANSACTION,
		  account_entries.date,
		  account_vouchers.name AS voucher_master,
		  '0.00' AS debit,
		  account_transactions.amount AS credit
		FROM account_entries
		LEFT JOIN account_vouchers 
		ON account_vouchers.id = account_entries.voucher_id
		LEFT JOIN account_transactions
		ON account_entries.id = account_transactions.entry_id
		LEFT JOIN account_ledgers AS debit_ledger
		ON debit_ledger.id = account_transactions.debit_ledger_id
		LEFT JOIN account_ledgers
		ON account_ledgers.id = account_transactions.credit_ledger_id
		WHERE account_entries.organization_id = ".$organization_id." 
		  AND account_entries.voucher_no = '".$voucher->voucher_no."'
		  AND account_entries.voucher_id  = '".$voucher->voucher_id."'
		  AND account_entries.status = 1
		) AS journal WHERE DATE BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY voucher_acc_id, debit");

		$data = DB::select("SELECT *
			FROM (SELECT 
		  account_entries.id,
		  account_transactions.id AS voucher_acc_id,
		  account_entries.voucher_no,
		  account_entries.reference_voucher_id,
		  account_ledgers.display_name AS account,
		  credit_ledger.display_name AS TRANSACTION,
		  account_entries.date,
		  account_vouchers.name AS voucher_master,
		  SUM(account_transactions.amount) AS debit,
		  '0.00' AS credit 
		FROM
		  account_entries 
		  LEFT JOIN account_vouchers 
			ON account_vouchers.id = account_entries.voucher_id 
		  LEFT JOIN account_transactions 
			ON account_entries.id = account_transactions.entry_id 
		  LEFT JOIN account_ledgers 
			ON account_ledgers.id = account_transactions.debit_ledger_id 
		  LEFT JOIN account_ledgers AS credit_ledger 
			ON credit_ledger.id = account_transactions.credit_ledger_id  
		WHERE account_entries.organization_id = '".$organization_id."' 
		  AND account_entries.voucher_no = '".$voucher->voucher_no."'
		  AND account_entries.voucher_id  = '".$voucher->voucher_id."'
		  AND account_entries.status = 1
		  GROUP BY account_ledgers.id
		  
		  UNION ALL
		  
		  SELECT 
		  account_entries.id,
		  account_transactions.id AS voucher_acc_id,
		  account_entries.voucher_no,
		  account_entries.reference_voucher_id,
		  account_ledgers.display_name AS account,
		  debit_ledger.display_name AS TRANSACTION,
		  account_entries.date,
		  account_vouchers.name AS voucher_master,
		  '0.00' AS debit,
		  SUM(account_transactions.amount) AS credit
		FROM account_entries
		LEFT JOIN account_vouchers 
		ON account_vouchers.id = account_entries.voucher_id
		LEFT JOIN account_transactions
		ON account_entries.id = account_transactions.entry_id
		LEFT JOIN account_ledgers AS debit_ledger
		ON debit_ledger.id = account_transactions.debit_ledger_id
		LEFT JOIN account_ledgers
		ON account_ledgers.id = account_transactions.credit_ledger_id
		WHERE account_entries.organization_id = '".$organization_id."' 
		  AND account_entries.voucher_no = '".$voucher->voucher_no."' 
		  AND account_entries.voucher_id  = '".$voucher->voucher_id."'
		  AND account_entries.status = 1
			GROUP BY account_ledgers.id
		) AS journal WHERE DATE BETWEEN '".$start_date."' AND '".$end_date."' ORDER BY voucher_acc_id, debit");

			$data_array = array_filter($data);

			if(!empty($data_array)) {

			$formatted_data = [];

			/*foreach($data as $key => $value) {
				if (!in_array($asset['id'], $assets_id)) {
					$assets_result[] = $asset;
					$assets_id[] = $asset['id'];
				}
			}*/

			  $journal_report[] = array(
				'voucher_no' => $voucher->voucher_no,
				'date' => $voucher->date,
				'credit' => $total_amount[0]->credit,
				'debit' => $total_amount[0]->debit,
				'data' => $data
				);
			}

		  }

		 return response()->json($journal_report);
	}

	public function ledger_report(Request $request, $id,$parent)
	{	
		$branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;
		
		$ledger = AccountLedger::find($id)->display_name;

		$parent = AccountGroup::find($parent)->display_name;
		 
		return view('accounts.ledger_statement', compact('branch', 'id', 'ledger','parent'));
	}


	public function inventory_report(Request $request, $id)
	{		
		$organization_id = Session::get('organization_id');		

		$inventory_details = InventoryItemStock::where('entry_id', $id)->first();
		// $inventory_details= InventoryItemStock::select('inventory_item_stocks.id','inventory_item_stocks.in_stock','inventory_item_stocks.data')
		// ->leftjoin('transaction_items','transaction_items.item_id','=','inventory_item_stocks.id')
		// ->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id')
		// ->where('transactions.entry_id',$id)
		// ->first();
		
		
		//$account_ledger_name = AccountLedger::where('id', $id)->first()->name;

		$available_quantity = $inventory_details->in_stock;

		$item_details = InventoryItem::where('id',$inventory_details->id)->first();		

		$balance = Custom::two_decimal($available_quantity * $item_details->purchase_price);		

		if(!$inventory_details) abort(403);

		$stock_datas = json_decode($inventory_details->data);		
		 
		return view('accounts.inventory_report', compact('id','stock_datas','available_quantity','item_details','available_quantity','balance'));
	}

	public function other_report(Request $request, $id)
	{		
		$organization_id = Session::get('organization_id');		

		// $inventory_details = InventoryItemStock::where('entry_id', $id)->first();

		$inventory_details= InventoryItemStock::select('inventory_item_stocks.id','inventory_item_stocks.in_stock')
		->leftjoin('transaction_items','transaction_items.item_id','=','inventory_item_stocks.id')
		->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id')
		->where('transactions.entry_id',$id)
		->first();
		
		//dd($inventory_details);
		//$account_ledger_name = AccountLedger::where('id', $id)->first()->name;

		$available_quantity = $inventory_details->in_stock;

		$item_details = InventoryItem::where('id',$inventory_details->id)->first();		

		$balance = Custom::two_decimal($available_quantity * $item_details->purchase_price);		

		if(!$inventory_details) abort(403);

		// $stock_datas = json_decode($inventory_details->data);	
		$stock_datas = InventoryItemStockLedger::where('inventory_item_stock_id',$item_id)->get();	
		 
		return view('accounts.inventory_report', compact('id','stock_datas','available_quantity','item_details','available_quantity','balance'));
	}
	public function purchase_process(Request $request, $id)
	{
		//dd($id);
		$organization_id = Session::get('organization_id');	

		$datas = AccountEntry::select('account_entries.*','account_transactions.debit_ledger_id','account_transactions.credit_ledger_id','account_transactions.description','account_transactions.amount','account_ledgers.name as debit_ledger_name','debit_ledgers.name as credit_ledger_name')
		->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')	
		->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.credit_ledger_id')
		->leftjoin('account_ledgers as debit_ledgers','debit_ledgers.id','=','account_transactions.debit_ledger_id')
		->where('account_entries.id',$id)->get();
		//dd($datas);
		
			
		 
		return view('accounts.purchase_report', compact('datas'));
	}

	public function stock_report(Request $request)
	{
		$branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;		

		//dd($inventory_details);
		 
		return view('accounts.stock_report', compact( 'branch'));
	}

	public function get_stock_report(Request $request)
	{
		$organization_id = Session::get('organization_id');	

		$start_date = $request->start_date;
		$end_date = $request->end_date;

		$inventory_details = InventoryItem::select('inventory_items.id AS item_id','inventory_items.name','inventory_item_stocks.in_stock','inventory_items.purchase_price','inventory_items.base_price','inventory_items.purchase_tax_id','inventory_item_stocks.entry_id')
		->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
		->leftjoin('account_entries','account_entries.id','=','inventory_item_stocks.entry_id')
		->whereBetween('account_entries.date',[$start_date,$end_date])
		->where('inventory_items.organization_id',$organization_id)
		->get();		

		//dd($inventory_details);

		$opening_balance = [];
		$inwards = [];
		$outwards = [];
		$update = [];
		$i = 0;	

		$grand_quantity = 0;
		$grand_value = 0;
		$grand_inwards_quantity = 0;
		$grand_inwards_value = 0;
		$grand_outwards_quantity = 0;
		$grand_outwards_value = 0;
		$grand_closing_quantity = 0;
		$grand_closing_value = 0;

		foreach ($inventory_details as $key => $value) 
		{
			$opening_qty = 0;
			$inwards_qty = 0;
			$outwards_qty = 0;
			$opening_val = 0;
			$inwards_val = 0;
			$outwards_val = 0;
			$sum_inward_quantity = 0;
			$sum_outward_quantity = 0;


			

			$item_id = $inventory_details[$key]->item_id;
			$entry_id = $inventory_details[$key]->entry_id;
			$item_name = $inventory_details[$key]->name;
			$purchase_price = $inventory_details[$key]->purchase_price;
			$sale_price = $inventory_details[$key]->base_price;
			$in_stock = $inventory_details[$key]->in_stock;
			$purchase_tax_id = $inventory_details[$key]->purchase_tax_id;

			if($in_stock == 0){
				$in_stock = 0.00;
			}


			$purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
			->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
			->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
			->where('tax_groups.organization_id', $organization_id)
			->where('tax_groups.id', $purchase_tax_id)
			->groupby('tax_groups.id')->first();

			if($purchase_tax_id != null)
			{
			 	$purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($purchase_price));

			 	$purchase_tax_price = Custom::two_decimal($purchase_price + $purchase_tax_amount);
			}
			else{
			 	$purchase_tax_price = $purchase_price;
			}		 
				
 		$list = InventoryItemStockLedger::where('inventory_item_stock_id',$item_id)->get();
 		
			// $list = json_decode($inventory_details[$key]->data,true);

			if($list != null || $list != ''){
				
				foreach ($list as $key => $value) 
				{
					if($list[$key] != null)
					{

						$opening_balance[$i]['item_name'] = $item_name;

						$opening_balance[$i]['entry_id'] = $entry_id;


						//$opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_price);
						

						if(isset($list[$key]['voucher_type']) && ($list[$key]['voucher_type']== 'Goods Receipt Note' ||  
								$list[$key]['voucher_type'] == 'Purchase') && isset($list[$key]['status']) &&  $list[$key]['status']== 1)
							{


							$sum_inward_quantity = Custom::two_decimal($sum_inward_quantity + $list[$key]['quantity']);

							$opening_balance[$i]['inwards_quantity'] = $sum_inward_quantity;

							$inwards_qty = $opening_balance[$i]['inwards_quantity'];					

							$opening_balance[$i]['inwards_price'] = $list[$key]['purchase_price'];

							$opening_balance[$i]['inwards_value'] = Custom::two_decimal($sum_inward_quantity * $list[$key]['purchase_price']);

							$inwards_val = $opening_balance[$i]['inwards_value'];				
						}

						if( isset($list[$key]['voucher_type']) && ($list[$key]['voucher_type'] == 'Delivery Note' ||  
								$list[$key]['voucher_type'] == 'Job Invoice Credit' 
								|| $list[$key]['voucher_type'] == 'Job Invoice Cash' || $list[$key]['voucher_type'] == 'Invoice' || $list[$key]['voucher_type'] == 'Invoice Cash' ) && isset($list[$key]['status']) && $list[$key]['status']== 1)
							{
							//$opening_balance['opening_balance']['inwards_quantity'] = $list[$key]['quantity'];

							$sum_outward_quantity = Custom::two_decimal($sum_outward_quantity + $list[$key]['quantity']);

							$opening_balance[$i]['outwards_quantity'] = $sum_outward_quantity;

							$outwards_qty = $opening_balance[$i]['outwards_quantity'];						

							$opening_balance[$i]['outwards_price'] = $list[$key]['sale_price'];

							$opening_balance[$i]['outwards_value'] = Custom::two_decimal($sum_outward_quantity * $list[$key]['sale_price']);

							$outwards_val = $opening_balance[$i]['outwards_value'];		
						}


						$opening_quantity = Custom::two_decimal($in_stock - $inwards_qty + $outwards_qty);

						if($opening_quantity > 0){

							$opening_balance[$i]['opening_quantity'] = $opening_quantity;
						}else{
							$opening_balance[$i]['opening_quantity'] = 0.00;
						}						

						$opening_qty = $opening_balance[$i]['opening_quantity'];

						$opening_balance[$i]['opening_price'] = $purchase_tax_price;

						$opening_balance[$i]['opening_value'] = Custom::two_decimal( $opening_qty * $purchase_tax_price);

						$opening_val = $opening_balance[$i]['opening_value'];

						$opening_balance[$i]['opening_quantity'] = (isset($opening_qty)) ? $opening_qty : $in_stock;

						$opening_balance[$i]['opening_value'] = (isset($opening_val)) ? $opening_val : Custom::two_decimal($in_stock * $purchase_tax_price);



						$opening_balance[$i]['inwards_quantity'] = (isset($inwards_qty)) ? $inwards_qty : 0.00;		

						$opening_balance[$i]['inwards_value'] = (isset($inwards_val)) ? $inwards_val : 0.00;

						$opening_balance[$i]['outwards_quantity'] = (isset($outwards_qty))  ? $outwards_qty : 0.00 ;			

						$opening_balance[$i]['outwards_value'] =(isset($outwards_val)) ? $outwards_val : 0.00 ;


						if(isset($opening_balance[$i]['opening_quantity'])) {
							
							$opening_qty = $opening_balance[$i]['opening_quantity'];
							$opening_val = $opening_balance[$i]['opening_value'];
						}
						if(isset($opening_balance[$i]['inwards_quantity'])){
							$inwards_qty = $opening_balance[$i]['inwards_quantity'];
							$inwards_val = $opening_balance[$i]['inwards_value'];
						}
						if(isset($opening_balance[$i]['outwards_quantity'])){
							$outwards_qty = $opening_balance[$i]['outwards_quantity'];
							$outwards_val = $opening_balance[$i]['outwards_value'];
						}

						$opening_balance[$i]['closing_quantity'] = Custom::two_decimal($in_stock );

						$opening_balance[$i]['closing_value'] =Custom::two_decimal($in_stock * $purchase_tax_price);						

					}
					else{

						$opening_balance[$i]['item_name'] = $item_name;
						$opening_balance[$i]['entry_id'] = $entry_id;
						
						$opening_balance[$i]['opening_quantity'] = $in_stock;
						$opening_balance[$i]['opening_price'] = $purchase_tax_price;
						$opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_tax_price);						

						$opening_balance[$i]['inwards_quantity'] = 0.00;
						$opening_balance[$i]['inwards_price'] = 0.00;
						$opening_balance[$i]['inwards_value'] = 0.00;

						$opening_balance[$i]['outwards_quantity'] = 0.00 ;
						$opening_balance[$i]['outwards_price'] = 0.00 ;
						$opening_balance[$i]['outwards_value'] = 0.00 ;

						$opening_balance[$i]['closing_quantity'] = $in_stock ;
						$opening_balance[$i]['closing_value'] = Custom::two_decimal($in_stock * $purchase_tax_price);
					}				
					
				}
				
			}
			else{

				//dd($list);
				$opening_balance[$i]['item_name'] = $item_name;
				
				$opening_balance[$i]['entry_id'] = $entry_id;

				$opening_balance[$i]['opening_quantity'] = $in_stock;

				$opening_balance[$i]['opening_price'] = $purchase_tax_price;

				$opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_tax_price);

				$opening_balance[$i]['inwards_quantity'] = 0.00;
				$opening_balance[$i]['inwards_price'] = 0.00;
				$opening_balance[$i]['inwards_value'] = 0.00;

				$opening_balance[$i]['outwards_quantity'] = 0.00 ;
				$opening_balance[$i]['outwards_price'] = 0.00 ;
				$opening_balance[$i]['outwards_value'] = 0.00 ;

				$opening_balance[$i]['closing_quantity'] = $in_stock ;
				$opening_balance[$i]['closing_value'] = Custom::two_decimal($in_stock * $purchase_tax_price);
			}


			$grand_quantity = Custom::two_decimal($grand_quantity + $opening_balance[$i]['opening_quantity']);

			$grand_value = Custom::two_decimal($grand_value + $opening_balance[$i]['opening_value']);

			$grand_inwards_quantity = Custom::two_decimal($grand_inwards_quantity + $opening_balance[$i]['inwards_quantity']);

			$grand_inwards_value = Custom::two_decimal($grand_inwards_value + $opening_balance[$i]['inwards_value']);

			$grand_outwards_quantity = Custom::two_decimal($grand_outwards_quantity + $opening_balance[$i]['outwards_quantity']);

			$grand_outwards_value = Custom::two_decimal($grand_outwards_value + $opening_balance[$i]['outwards_value']);

			$grand_closing_quantity = Custom::two_decimal($grand_closing_quantity + $opening_balance[$i]['closing_quantity']);

			$grand_closing_value = Custom::two_decimal($grand_closing_value + $opening_balance[$i]['closing_value']);		

		
			$i++;	
		}			
		

		 return response()->json(['result' => $opening_balance,'grand_quantity' =>$grand_quantity , 'grand_value' => $grand_value,'grand_inwards_quantity' => $grand_inwards_quantity, 'grand_inwards_value' => $grand_inwards_value,'grand_outwards_quantity'=> $grand_outwards_quantity, 'grand_outwards_value'=>$grand_outwards_value,'grand_closing_quantity'=> $grand_closing_quantity,'grand_closing_value' =>$grand_closing_value]);
	}


	

}
