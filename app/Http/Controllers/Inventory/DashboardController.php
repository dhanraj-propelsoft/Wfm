<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\AccountVoucher;
use App\InventoryItem;
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
        $person_type_id = AccountPersonType::where('name', 'vendor')->first()->id;
        $transaction_type = AccountVoucher::where('name','purchases')
                            ->where('organization_id', $organization_id)->first()->id;

        $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;

        $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        
        $person_type = [$transaction_type];

        $notifications = [];
        $time = [];
         $today = Carbon::today()->format('Y-m-d');
        $last_six_month = Carbon::today()->submonth(6)->firstofmonth();
        $six_month =  $last_six_month->format('Y-m-d'); 
        //dd($six_month);
        $six_month_view = $last_six_month->format('d-m-Y');
        $today_view = Carbon::today()->format('d-m-Y');

// ********    Calculation for Total Supplier Begins   ********

        $total_supplier = People::select(DB::raw('COUNT(people.id) AS id'))
            ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
            ->leftJoin('persons', 'persons.id','=','people.person_id')
            ->leftJoin('businesses', 'businesses.id','=','people.business_id')
            ->leftJoin('transactions', function($query) use($transaction_type) {
                    $query->on('transactions.people_id','=','people.person_id');
                    $query->whereIn('transactions.transaction_type_id', [$transaction_type]);
            })
            ->leftJoin('transactions AS business', function($query) use($transaction_type) {
                    $query->on('business.people_id','=','people.business_id');
                    $query->whereIn('transactions.transaction_type_id', [$transaction_type]);
            })
            ->where('people.organization_id', $organization_id)
            ->where('people.status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->first()->id;
        //dd(count($total_supplier));

// ********    Calculation for Total Supplier Ends   ********

// ********    Calculation for Low Stock Begins   ********

            $low_stocks = InventoryItem::select('inventory_items.id','inventory_items.name','inventory_items.status', 'inventory_items.low_stock', 'global_item_category_types.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.purchase_price', 'inventory_items.minimum_order_quantity')
            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_models.category_id')
            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
            ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
            ->where('inventory_items.organization_id', $organization_id)
            ->whereRaw('inventory_item_stocks.in_stock <= inventory_items.low_stock')
            ->groupby('inventory_items.id')
            ->get();
            $low_stock_count = count($low_stocks);

            //dd($low_stock_count);

// ********    Calculation for Low Stock Ends   ********

// ********    Calculation for Top Suppliers Begins   ********

        /*$top_supplier = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM(transactions.total) AS total'))
            ->leftJoin('people_person_types', 'people_person_types.person_type_id','=','transactions.people_id')
            ->where('organization_id', $organization_id)
            ->where('approval_status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->groupby('transactions.people_id')
            ->groupby('transactions.user_type')
            ->orderby('total', 'desc')->take(15)->get();*/

        /*$top_suppliers = People::select('people.id AS people_id', DB::raw('SUM(transactions.total) AS total'), 'transactions.id', 'transactions.entry_id', 'transactions.transaction_type_id', 'transactions.name')
            ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
            ->leftJoin('transactions', 'people.id','=','transactions.people_id')            
            ->where('people.organization_id', $organization_id)
            ->where('people.status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->take(15)->get();*/

        $top_suppliers = People::select('people.display_name', 
            DB::raw('IF(SUM(transactions.total) IS NULL, SUM(business.total), SUM(transactions.total)) AS total'),
            DB::raw('CONCAT(COALESCE(people.first_name, ""), " " ,COALESCE(people.last_name, "")) AS contact_person'))
        ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
        ->leftJoin('persons', 'persons.id','=','people.person_id')
        ->leftJoin('transactions', function($query) use($person_type) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', $person_type);
                $query->where('transactions.user_type', '0');
        })
        ->leftJoin('transactions AS business', function($query)  use($person_type){
                $query->on('business.people_id','=','people.business_id');
                $query->whereIn('business.transaction_type_id', $person_type);
                $query->where('business.user_type', '1');
        })
        ->where('people.organization_id', $organization_id)
        ->where('people.status', '1')
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('total', 'desc')
        ->havingRaw('total != 0')
        ->take(15)->get();

            $employee_name = [];
            $employee_total = [];

            foreach ($top_suppliers as $key => $value) {

               $employee_name[] = [$key, $value->display_name];
               $employee_total[] = [$key, $value->total];
            }
            $customers_names = json_encode($employee_name);
            $customers_total_value = json_encode($employee_total);

            //return $top_suppliers->all();
            //return $customers_total_value;

// ********    Calculation for Top Suppliers Ends   ********

        $total_products = DB::table('inventory_items')->where('organization_id', $organization_id)->count();

     /*   $total_purchases = DB::table('transactions')
                           ->where('organization_id', $organization_id)->where('approval_status', 1)
                           ->where('transaction_type_id', $transaction_type)->sum('total');*/
          $total_purchases = DB::table('transactions')
                           ->where('organization_id', $organization_id)->where('approval_status', 1)
                           ->where('transaction_type_id', $transaction_type)
                           ->wherebetween('transactions.date',[$six_month , $today])
                           ->sum('total');

// ********    Calculation for Total Payables Begins   ********

        $total_payable = DB::select("SELECT SUM(balance) AS total FROM (
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
                AND account_entries.date BETWEEN '$six_month' AND  '$today' 
                    AND transactions.approval_status = 1 
                    AND transactions.transaction_type_id = $transaction_type
                    AND transactions.deleted_at IS NULL 
                    GROUP BY transactions.id
                ) AS trans");

            $total_payables = $total_payable[0]->total;
            //dd($total_payables);

// ********    Calculation for Total Payables Ends   ********

        return view('inventory.dashboard', compact('total_supplier', 'customers_names', 'customers_total_value', 'total_products', 'total_purchases', 'total_payables', 'low_stocks', 'low_stock_count','six_month_view' ,'today_view'));
    }
     public function search_index(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $person_type_id = AccountPersonType::where('name', 'vendor')->first()->id;
        $transaction_type = AccountVoucher::where('name','purchases')
                            ->where('organization_id', $organization_id)->first()->id;

        $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;

        $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        
        $person_type = [$transaction_type];

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
// ********    Calculation for Total Supplier Begins   ********

        $total_supplier = People::select(DB::raw('COUNT(people.id) AS id'))
            ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
            ->leftJoin('persons', 'persons.id','=','people.person_id')
            ->leftJoin('businesses', 'businesses.id','=','people.business_id')
            ->leftJoin('transactions', function($query) use($transaction_type) {
                    $query->on('transactions.people_id','=','people.person_id');
                    $query->whereIn('transactions.transaction_type_id', [$transaction_type]);
            })
            ->leftJoin('transactions AS business', function($query) use($transaction_type) {
                    $query->on('business.people_id','=','people.business_id');
                    $query->whereIn('transactions.transaction_type_id', [$transaction_type]);
            })
            ->where('people.organization_id', $organization_id)
            ->where('people.status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->first()->id;
        //dd(count($total_supplier));

// ********    Calculation for Total Supplier Ends   ********

// ********    Calculation for Low Stock Begins   ********

            $low_stocks = InventoryItem::select('inventory_items.id','inventory_items.name','inventory_items.status', 'inventory_items.low_stock', 'global_item_category_types.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.purchase_price', 'inventory_items.minimum_order_quantity')
            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_models.category_id')
            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
            ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
            ->where('inventory_items.organization_id', $organization_id)
            ->whereRaw('inventory_item_stocks.in_stock <= inventory_items.low_stock')
            ->groupby('inventory_items.id')
            ->get();
            $low_stock_count = count($low_stocks);

            //dd($low_stock_count);

// ********    Calculation for Low Stock Ends   ********

// ********    Calculation for Top Suppliers Begins   ********

        /*$top_supplier = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM(transactions.total) AS total'))
            ->leftJoin('people_person_types', 'people_person_types.person_type_id','=','transactions.people_id')
            ->where('organization_id', $organization_id)
            ->where('approval_status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->groupby('transactions.people_id')
            ->groupby('transactions.user_type')
            ->orderby('total', 'desc')->take(15)->get();*/

        /*$top_suppliers = People::select('people.id AS people_id', DB::raw('SUM(transactions.total) AS total'), 'transactions.id', 'transactions.entry_id', 'transactions.transaction_type_id', 'transactions.name')
            ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
            ->leftJoin('transactions', 'people.id','=','transactions.people_id')            
            ->where('people.organization_id', $organization_id)
            ->where('people.status', '1')
            ->where('people_person_types.person_type_id', $person_type_id)
            ->take(15)->get();*/

        $top_suppliers = People::select('people.display_name', 
            DB::raw('IF(SUM(transactions.total) IS NULL, SUM(business.total), SUM(transactions.total)) AS total'),
            DB::raw('CONCAT(COALESCE(people.first_name, ""), " " ,COALESCE(people.last_name, "")) AS contact_person'))
        ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
        ->leftJoin('persons', 'persons.id','=','people.person_id')
        ->leftJoin('transactions', function($query) use($person_type) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', $person_type);
                $query->where('transactions.user_type', '0');
        })
        ->leftJoin('transactions AS business', function($query)  use($person_type){
                $query->on('business.people_id','=','people.business_id');
                $query->whereIn('business.transaction_type_id', $person_type);
                $query->where('business.user_type', '1');
        })
        ->where('people.organization_id', $organization_id)
        ->where('people.status', '1')
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('total', 'desc')
        ->havingRaw('total != 0')
        ->take(15)->get();

            $employee_name = [];
            $employee_total = [];

            foreach ($top_suppliers as $key => $value) {

               $employee_name[] = [$key, $value->display_name];
               $employee_total[] = [$key, $value->total];
            }
            $customers_names = json_encode($employee_name);
            $customers_total_value = json_encode($employee_total);

            //return $top_suppliers->all();
            //return $customers_total_value;

// ********    Calculation for Top Suppliers Ends   ********

        $total_products = DB::table('inventory_items')->where('organization_id', $organization_id)->count();

        $total_purchases = DB::table('transactions')
                           ->where('organization_id', $organization_id)->where('approval_status', 1)
                           ->where('transaction_type_id', $transaction_type)
                           ->wherebetween('transactions.date',[$from_date , $to_date])
                           ->sum('total');
        //dd($total_purchases);

// ********    Calculation for Total Payables Begins   ********

        $total_payable = DB::select("SELECT SUM(balance) AS total FROM (
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
                        WHERE account_entries.reference_transaction_id = transactions.id 
                            AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))
                    ) AS balance 
                FROM
                    transactions
                    left join account_entries on account_entries.id = transactions.entry_id
                WHERE transactions.organization_id = $organization_id 
               AND account_entries.date BETWEEN '$from_date' AND '$to_date'  
                    AND transactions.approval_status = 1 
                    AND transactions.transaction_type_id = $transaction_type
                    AND transactions.deleted_at IS NULL 
                    GROUP BY transactions.id
                ) AS trans");

            $total_payables = $total_payable[0]->total;
            //dd($total_payables);

// ********    Calculation for Total Payables Ends   ********

        return view('inventory.dashboard_search', compact('total_supplier', 'customers_names', 'customers_total_value', 'total_products', 'total_purchases', 'total_payables', 'low_stocks', 'low_stock_count','six_month_view' ,'today_view'));
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
