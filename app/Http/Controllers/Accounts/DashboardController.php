<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Session;
use App\Custom;
use App\AccountLedger;

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
        $expense=DB::select('SELECT account_groups.account_head,MONTHNAME(account_entries.date) as month_name, account_transactions.amount,CASE WHEN(account_heads.id=2) THEN SUM(account_transactions.amount)
            ELSE 0 END AS total_expense
            FROM account_ledgers
            LEFT JOIN account_groups ON account_groups.id = account_ledgers.group_id
            LEFT JOIN account_heads ON account_heads.id = account_groups.account_head 
            LEFT JOIN account_transactions ON account_ledgers.id = account_transactions.debit_ledger_id
            LEFT JOIN account_entries ON account_entries.id = account_transactions.entry_id
            WHERE account_ledgers.organization_id = '.$organization_id.' AND account_heads.id=2
            AND  account_entries.date IS NOT NULL
            GROUP BY MONTH(account_entries.date)'); 

         

           $income = DB::select('SELECT account_groups.account_head,MONTHNAME(account_entries.date) as month_name, account_transactions.amount,CASE WHEN(account_heads.id=3) THEN SUM(account_transactions.amount)
            ELSE 0 END AS total_income
            FROM account_ledgers
            LEFT JOIN account_groups ON account_groups.id = account_ledgers.group_id
            LEFT JOIN account_heads ON account_heads.id = account_groups.account_head 
            LEFT JOIN account_transactions ON account_ledgers.id = account_transactions.credit_ledger_id
            LEFT JOIN account_entries ON account_entries.id = account_transactions.entry_id
            WHERE account_ledgers.organization_id = '.$organization_id.' AND account_heads.id=3
            AND  account_entries.date IS NOT NULL
            GROUP BY MONTH(account_entries.date)
            ');
           $month = [];
           $month1 = [];
           $indexed = [];
           $indexed1 =[];

            foreach ($expense as $obj) {
               $month[] = $obj->month_name;
            }
            foreach($income as $income_obj)
            {
                $month1[] = $income_obj->month_name;
            }
          
            $month_combine= array_merge($month,$month1);
            $value_unique = array_unique($month_combine);
          
            foreach($expense as $object) {
             
              $indexed[$object->month_name]=$object->total_expense;
            }
           
              foreach($income as $val)
            {
              $indexed1[$val->month_name]=$val->total_income;
            
            }
          
            $income1 = '';
            $expense1 = '';
            $arr = [];
            $mer = [];  

            foreach($value_unique as $value_uniq)
            {
                $expense1 = isset($indexed[$value_uniq])?$indexed[$value_uniq]:0;
                $income1 = isset($indexed1[$value_uniq])?$indexed1[$value_uniq]:0;
              
                $arr[] = [$value_uniq,(float)$income1,(float)$expense1];
                

            }
          
            if($arr)
            {
                 foreach($arr as $diff)
                {
                   $difference = [$diff[1]-$diff[2]];
                   $mer[] = array_merge($diff,$difference);
                   //dd($mer);
                }
            }
            $array[]=['Year','Income','Expense','Difference'];
            $df = array_merge($array,$mer);

            $ff = json_encode($df);

        $from_date1 = date('01-m-Y');
        $from_date = date('Y-m-01');
        $to_date = Carbon::today()->format('Y-m-d');
        $to_date1 = Carbon::today()->format('d-m-Y');

        $total_expensess = AccountLedger::select('account_ledgers.group_id','account_ledgers.id','account_groups.name','account_groups.display_name as group_name','account_ledgers.name','account_ledgers.display_name as ledger_name','account_groups.account_head','account_transactions.amount',DB::raw('CASE WHEN account_heads.id=2 THEN SUM(account_transactions.amount) ELSE 0 END AS total_expense'))
       ->leftjoin('account_groups','account_groups.id' ,'=','account_ledgers.group_id')
       ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
       ->leftjoin('account_transactions','account_transactions.debit_ledger_id','=','account_ledgers.id')
       ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
       ->where('account_ledgers.organization_id',$organization_id)
       ->where('account_heads.id',2)
       ->wherebetween('account_entries.date',[$from_date,$to_date])
       ->groupby('account_ledgers.group_id','account_ledgers.id')
       ->orderby('account_ledgers.group_id','account_ledgers.id')
       ->get();
        $total_incomes = AccountLedger::select('account_ledgers.group_id','account_ledgers.id','account_groups.name','account_groups.display_name as group_name','account_ledgers.name','account_ledgers.display_name as ledger_name','account_groups.account_head','account_transactions.amount',DB::raw('CASE WHEN account_heads.id=3 THEN SUM(account_transactions.amount) ELSE 0 END AS total_income'))
       ->leftjoin('account_groups','account_groups.id' ,'=','account_ledgers.group_id')
       ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
       ->leftjoin('account_transactions','account_transactions.credit_ledger_id','=','account_ledgers.id')
       ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
       ->where('account_ledgers.organization_id',$organization_id)
       ->where('account_heads.id',3)
       ->wherebetween('account_entries.date',[$from_date,$to_date])
       ->groupby('account_ledgers.group_id','account_ledgers.id')
       ->orderby('account_ledgers.group_id','account_ledgers.id')
       ->get();

        $sum_of_expense1 =0;
        $sum_of_expense = 0;
         foreach($total_expensess as $total_expenses)
         {
        
          $sum_expense = $total_expenses->total_expense;
          $sum_of_expense1 += $sum_expense;
          $sum_of_expense=Custom::two_decimal($sum_of_expense1);

         
         }
         //dd($sum_of_expense);
        
         $sum_of_income1 = 0;
         $sum_of_income = 0;
         foreach($total_incomes as $total_income)
         {
            $sum_income = $total_income->total_income;
            $sum_of_income1 += $sum_income;
            $sum_of_income=Custom::two_decimal($sum_of_income1);

         }

         
         
         
       return view('accounts.dashboard',compact('array','mer','ff','total_expensess','total_incomes','sum_of_expense','sum_of_income','from_date1','to_date1'));
    }

     public function account_dashboard_search(Request $request)
    {
      //dd($request->all());
      $organization_id = Session::get('organization_id');
      $from_date1 = $request->from_date;
      $to_date1 = $request->to_date;
      $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
      $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
     
      //dd($from_date);

       $total_expensess = AccountLedger::select('account_ledgers.group_id','account_ledgers.id','account_groups.name','account_groups.display_name as group_name','account_ledgers.name','account_ledgers.display_name as ledger_name','account_groups.account_head','account_transactions.amount',DB::raw('CASE WHEN account_heads.id=2 THEN SUM(account_transactions.amount) ELSE 0 END AS total_expense'))
       ->leftjoin('account_groups','account_groups.id' ,'=','account_ledgers.group_id')
       ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
       ->leftjoin('account_transactions','account_transactions.debit_ledger_id','=','account_ledgers.id')
       ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
       ->where('account_ledgers.organization_id',$organization_id)
       ->where('account_heads.id',2)
       ->wherebetween('account_entries.date',[$from_date,$to_date])
       ->groupby('account_ledgers.group_id','account_ledgers.id')
       ->orderby('account_ledgers.group_id','account_ledgers.id')
       ->get();

         $total_incomes = AccountLedger::select('account_ledgers.group_id','account_ledgers.id','account_groups.name','account_groups.display_name as group_name','account_ledgers.name','account_ledgers.display_name as ledger_name','account_groups.account_head','account_transactions.amount',DB::raw('CASE WHEN account_heads.id=3 THEN SUM(account_transactions.amount) ELSE 0 END AS total_income'))
       ->leftjoin('account_groups','account_groups.id' ,'=','account_ledgers.group_id')
       ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
       ->leftjoin('account_transactions','account_transactions.credit_ledger_id','=','account_ledgers.id')
       ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
       ->where('account_ledgers.organization_id',$organization_id)
       ->where('account_heads.id',3)
       ->wherebetween('account_entries.date',[$from_date,$to_date])
       ->groupby('account_ledgers.group_id','account_ledgers.id')
       ->orderby('account_ledgers.group_id','account_ledgers.id')
       ->get();

        $sum_of_expense1 =0;
        $sum_of_expense = 0;
         foreach($total_expensess as $total_expenses)
         {
            //dd($total_income->total_income);
          $sum_expense = $total_expenses->total_expense;
          //dd($sum_expense);
          $sum_of_expense1 += $sum_expense;
          //dd($sum_of_expense);
          $sum_of_expense=Custom::two_decimal($sum_of_expense1);

         }


         //dd($sum_of_expense);

         $sum_of_income1 = 0;
         $sum_of_income = 0;
         foreach($total_incomes as $total_income)
         {
            $sum_income = $total_income->total_income;
            $sum_of_income1 += $sum_income;
            $sum_of_income=Custom::two_decimal($sum_of_income1);

         }

       return response()->json(['total_incomes' =>$total_incomes,'total_expensess'=>$total_expensess,'sum_of_expense' => $sum_of_expense,'sum_of_income'=>$sum_of_income]);
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
