<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\AccountVoucher;
use App\AccountLedger;
use App\Transaction;
use Carbon\Carbon;
use App\Person;
use App\People;
use Session;
use Auth;
use DB;

class DashboardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$person_type_id = AccountPersonType::where('name', 'customer')->first()->id;

		$transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();

		$cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;

		$return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

		$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

		$transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

		$sale_order_id = AccountVoucher::where('name', 'sale_order')->where('organization_id', $organization_id)->first()->id;
		$notifications = [];
		$time = [];
		$today = Carbon::today()->format('Y-m-d');
		$last_six_month = Carbon::now()->submonth(6)->firstofmonth();
		$six_month = $last_six_month->format('Y-m-d');

		$six_month_view = $last_six_month->format('d-m-Y');
        $today_view = Carbon::today()->format('d-m-Y');

		$top_customers = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM(transactions.total) AS total'))
		->where('organization_id', $organization_id)
		->wherebetween('transactions.date',[$six_month, $today])
		->where('approval_status', '1')
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->groupby('transactions.people_id')
		->groupby('transactions.user_type')
		->orderby('total', 'desc')->take(10)->get();

		$employee_name = [];
		$employee_total = [];

		foreach ($top_customers as $key => $value) {
		   $employee_name[] = [$key, $value->name];
		   $employee_total[] = [$key, $value->total];
		}

		$customers_names = json_encode($employee_name);
		$customers_total_value = json_encode($employee_total);

		//return $top_customers->all();
		//return $customers_names;


		$total_customer = People::select(DB::raw('COUNT(people.id) AS id'))
		->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
		->leftJoin('persons', 'persons.id','=','people.person_id')
		->leftJoin('businesses', 'businesses.id','=','people.business_id')
		->leftJoin('transactions', function($query) use($transaction_id, $transaction_cash) {
				$query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->leftJoin('transactions AS business', function($query) use($transaction_id, $transaction_cash) {
				$query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->where('people.organization_id', $organization_id)
		->where('people.status', '1')
		->where('people_person_types.person_type_id', $person_type_id)
		->first()->id;

		/*$total_sales = DB::table('transactions')
						   ->where('organization_id', $organization_id)->where('approval_status', 1)
						   ->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])->sum('total');*/
		$total_sales = DB::table('transactions')
						   ->where('organization_id', $organization_id)->where('approval_status', 1)
						   ->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
						   ->wherebetween('transactions.date',[$six_month, $today])
						   ->sum('total');
		
		// ********   Calculation for Total Receivables Begins   ********

		$total_receivable = DB::select("SELECT SUM(balance) AS total FROM (
	  	SELECT 
	    	transactions.id,
	     	IF(
	      		(SELECT SUM(account_transactions.amount) FROM account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE account_entries.reference_transaction_id = transactions.id 
	        		AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,
	      				SUM(transactions.total),
	      				SUM(transactions.total) - 
	      			(SELECT 
	        			SUM(account_transactions.amount) 
	      			FROM
	        			account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE account_entries.reference_transaction_id = transactions.id 
	        			AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))
	    		) AS balance 
		  	FROM
		    	transactions 
		    	left join account_entries on account_entries.id = transactions.entry_id
			WHERE transactions.organization_id = $organization_id 
			AND account_entries.date BETWEEN '$six_month' 
    AND '$today'
			    AND transactions.approval_status = 1 
			    AND transactions.transaction_type_id = $transaction_id
			    AND transactions.deleted_at IS NULL 
			    GROUP BY transactions.id
		 	) AS trans");

		$total_receivables = $total_receivable[0]->total;

	// ********    Calculation for Total Receivables Ends   ********		

	// ********    Calculation for New Orders Begins   ********

		/*$new_orders = DB::select("SELECT COUNT(id) AS total FROM (SELECT id, transactions.total,
  				( transactions.total - COALESCE(  (  SELECT  SUM(account_transactions.amount) FROM  account_entries  
        	LEFT JOIN account_transactions  ON account_transactions.entry_id = account_entries.id  
        		WHERE account_entries.reference_transaction_id = transactions.id  AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher) ), 0 )) AS balance
			FROM
			  	transactions 
			WHERE organization_id = $organization_id
				AND transactions.transaction_type_id = $sale_order_id  
				-- if we need to calculate total sale order and estimate add the corresponding value/variable on this line (AND transactions.transaction_type_id IN (transaction_id, estimate_id, sale_order_id)
			HAVING balance != 0 ) AS trans");

		$new_order = (COUNT($new_orders)) ? $new_orders[0]->total : 0;*/

		$new_order = Transaction::select('transactions.id')
		->whereIn('transaction_type_id', [$sale_order_id])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->count('id');

// ********    Calculation for New Orders Ends   ********

// ********    Calculation of Graphical data for Sales Begins   ********
		
		$sales = Transaction::select('transactions.id', DB::raw('DATE_FORMAT(transactions.date, "%b-%Y") AS date'), 
			DB::raw('SUM(transactions.total) AS total'), 'transactions.name',  DB::raw('MONTH(transactions.date) AS month'))
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->wherebetween('transactions.date',[$six_month, $today])
		->groupby(\DB::raw('MONTH(transactions.date)'))->get();

		//dd($sales); //DB::raw('DATE_FORMAT(cust.cust_dob, "%d-%b-%Y") as formatted_dob'

		$sales_data = [];
		
		foreach ($sales as $value) {

			$sales_data[] = [$value->date, $value->total];
		}

		$sales_data = json_encode($sales_data);

// ********    Calculation of Graphical data for Sales Ends   ********

		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
		->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
		->leftJoin('account_transactions', 'account_transactions.debit_ledger_id', '=', 'account_ledgers.id')
		->whereIn('account_groups.name', ['cash'])
		->where('account_ledgers.organization_id', $organization_id)
		->where('account_ledgers.approval_status', '1')
		->where('account_ledgers.status', '1')
		->orderby('account_ledgers.id','asc')
		->sum('account_transactions.amount');

		//dd($ledgers);

		
		return view('trade.dashboard', compact('top_customers', 'customers_names', 'customers_total_value', 'total_customer', 'total_sales', 'total_receivables', 'new_order', 'sales_data','six_month_view','today_view'));
	}

	public function search_index(Request $request)
	{
		//dd($request->all());
		$organization_id = Session::get('organization_id');

		$person_type_id = AccountPersonType::where('name', 'customer')->first()->id;

		$transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();

		$cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;

		$return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

		$transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

		$transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

		$sale_order_id = AccountVoucher::where('name', 'sale_order')->where('organization_id', $organization_id)->first()->id;
		$notifications = [];
		$time = [];

		$six_month_view = $request->input('from_date');
		$today_view =$request->input('to_date');
		if($request->input('from_date'))
 		{
 			$from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
 		}
 	

 		if($request->input('to_date'))
 		{
			$to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
		}
		//dd($from_date);

		$top_customers = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM(transactions.total) AS total'))
		->where('organization_id', $organization_id)
		->wherebetween('transactions.date',[$from_date, $to_date])
		->where('approval_status', '1')
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->groupby('transactions.people_id')
		->groupby('transactions.user_type')
		->orderby('total', 'desc')->take(10)->get();

		$employee_name = [];
		$employee_total = [];

		foreach ($top_customers as $key => $value) {
		   $employee_name[] = [$key, $value->name];
		   $employee_total[] = [$key, $value->total];
		}

		$customers_names = json_encode($employee_name);
		$customers_total_value = json_encode($employee_total);

		//return $top_customers->all();
		//return $customers_names;


		$total_customer = People::select(DB::raw('COUNT(people.id) AS id'))
		->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
		->leftJoin('persons', 'persons.id','=','people.person_id')
		->leftJoin('businesses', 'businesses.id','=','people.business_id')
		->leftJoin('transactions', function($query) use($transaction_id, $transaction_cash) {
				$query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->leftJoin('transactions AS business', function($query) use($transaction_id, $transaction_cash) {
				$query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->where('people.organization_id', $organization_id)
		->where('people.status', '1')
		->where('people_person_types.person_type_id', $person_type_id)
		->first()->id;

		$total_sales = DB::table('transactions')
						   ->where('organization_id', $organization_id)->where('approval_status', 1)
						   ->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
						   ->wherebetween('transactions.date',[$from_date, $to_date])
						   ->sum('total');
		
		// ********   Calculation for Total Receivables Begins   ********

		$total_receivable = DB::select("SELECT SUM(balance) AS total FROM (
	  	SELECT 
	    	transactions.id,
	     	IF(
	      		(SELECT SUM(account_transactions.amount) FROM account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE  account_entries.reference_transaction_id = transactions.id 
	        		AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,
	      				SUM(transactions.total),
	      				SUM(transactions.total) - 
	      			(SELECT 
	        			SUM(account_transactions.amount) 
	      			FROM
	        			account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE  account_entries.reference_transaction_id = transactions.id 
	        			AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))
	    		) AS balance 
		  	FROM
		    	transactions
		    	left join account_entries on account_entries.id = transactions.entry_id 
			WHERE transactions.organization_id = $organization_id 
			  AND account_entries.date BETWEEN '$from_date'  AND '$to_date'
			    AND transactions.approval_status = 1 
			    AND transactions.transaction_type_id = $transaction_id
			    AND transactions.deleted_at IS NULL 
			    GROUP BY transactions.id
		 	) AS trans");


		//dd($total_receivable);

		$total_receivables = $total_receivable[0]->total;

		//$total_receivables ="90000";


	// ********    Calculation for Total Receivables Ends   ********		

	// ********    Calculation for New Orders Begins   ********

		

		$new_order = Transaction::select('transactions.id')
		->whereIn('transaction_type_id', [$sale_order_id])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->count('id');

// ********    Calculation for New Orders Ends   ********

// ********    Calculation of Graphical data for Sales Begins   ********
		
		$sales = Transaction::select('transactions.id', DB::raw('DATE_FORMAT(transactions.date, "%b-%Y") AS date'), 
			DB::raw('SUM(transactions.total) AS total'), 'transactions.name',  DB::raw('MONTH(transactions.date) AS month'))
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->wherebetween('transactions.date',[$from_date, $to_date])
		->groupby(\DB::raw('MONTH(transactions.date)'))->get();

		//dd($sales); //DB::raw('DATE_FORMAT(cust.cust_dob, "%d-%b-%Y") as formatted_dob'

		$sales_data = [];
		
		foreach ($sales as $value) {

			$sales_data[] = [$value->date, $value->total];
		}

		$sales_data = json_encode($sales_data);

// ********    Calculation of Graphical data for Sales Ends   ********

		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
		->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
		->leftJoin('account_transactions', 'account_transactions.debit_ledger_id', '=', 'account_ledgers.id')
		->whereIn('account_groups.name', ['cash'])
		->where('account_ledgers.organization_id', $organization_id)
		->where('account_ledgers.approval_status', '1')
		->where('account_ledgers.status', '1')
		->orderby('account_ledgers.id','asc')
		->sum('account_transactions.amount');

		//dd($ledgers);

		
		return view('trade.dashboard_search', compact('top_customers', 'customers_names', 'customers_total_value', 'total_customer', 'total_sales', 'total_receivables', 'new_order', 'sales_data','six_month_view','today_view'))->render();
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