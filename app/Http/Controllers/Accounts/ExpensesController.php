<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Expense;
use App\HrmEmployee;
use App\AccountVoucher;
use App\AccountEntry;
use App\AccountLedger;
use App\AccountTransaction;
use App\ExpensesTransaction;
use App\MshipMembers;
use App\PaymentMode;
use Carbon\Carbon;
use App\Custom;
use App\Module;
use Session;
use DB;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_name=Session::get('module_name');
        
        $organization_id = Session::get('organization_id');

        $daily_expense_id = AccountLedger::where('account_ledgers.name','=','Daily Expenses')->where('account_ledgers.organization_id',$organization_id)->first()->id;
       
    
        $expenses_lists = Expense::select('*',DB::raw('(CASE WHEN ledger_id= "0" THEN '.$daily_expense_id.' ELSE ledger_id END) AS expense_ledger_id'))
                ->where('organization_id',$organization_id)
                ->get();
      
        $employee = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name', 'id');
           
        $employee->prepend('Select Employee', '');

        /* $expense_amount = AccountEntry::select('account_entries.date as start','account_entries.date as end','account_transactions.amount as title')->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')->where('account_entries.organization_id','=',$organization_id)->where('account_ledgers.name','=','Daily Expenses')->get();*/
   
      $expense_amount = AccountEntry::select('account_entries.date as start','account_entries.date as end','account_transactions.amount as title')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id')
        ->where('account_entries.organization_id','=',$organization_id)
        ->where('expenses_transactions.expense_type',1)       
        ->groupby('account_entries.date')
        ->get();

      /* $expense_amounts = AccountEntry::select('account_entries.date as start','account_entries.date as end','account_transactions.amount as title');
        $expense_amounts->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $expense_amounts->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
        $expense_amounts->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
        $expense_amounts->where('account_entries.organization_id','=',$organization_id);
        $expense_amounts->where(function($query){
            $query->where('expenses_transactions.expense_type',1)
            ->orWhere('expenses_transactions.expense_type',NULL);

        });
       $expense_amounts->where('expenses_transactions.expense_type','!=',2);
        $expense_amounts->groupby('account_entries.date');
        $expense_amount=$expense_amounts->get();*/
        $expense = json_decode($expense_amount);
         //dd($expense);
       
      

        return view('accounts.expenses',compact('expenses_lists','employee','expense','module_name'));
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
        // dd($request->all());
        $organization_id = Session::get('organization_id');

        $voucher_master = AccountVoucher::where('display_name',$request->input('voucher_type'))->where('organization_id',$organization_id)->first();
        $voucher_id = $voucher_master->id;

        $previous_entry = AccountEntry::where('voucher_id', $voucher_id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();
        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;

        $payment = PaymentMode::where('name',$request->input('payment_mode'))->first();

        $credit_ledger= AccountLedger::where('name',$request->input('credit_ledger'))->where('organization_id',$organization_id)->first();
        $credit_ledger_id = $credit_ledger->id;
        //  $debit_ledger= AccountLedger::where('name',$request->input('debit_ledger'))->where('organization_id',$organization_id)->first();
        // $debit_ledger_id = $debit_ledger->id;
        $expense_id = $request->input('reference_voucher_id');
        $expenses = $request->input('reference_voucher');
        $description = $request->input('notes');
        $employee_id = $request->input('employee');
        $amount = $request->input('amount');
        $debit_ledger=$request->input('debit_ledger');
       
        $total_amount = $request->input('total_amount');
        $description = $request->input('notes');
        $expense_description = $request->input('description');

       /*account_entries Table*/
            $accountentries = new AccountEntry;
            $accountentries->voucher_no = Custom::generate_accounts_number($voucher_master->name,$gen_no, false);
            $accountentries->gen_no = $gen_no;
            $accountentries->voucher_id = $voucher_id;
            $accountentries->payment_mode_id = $payment->id;
            $accountentries->date = $request->input('date');
            $accountentries->organization_id = $organization_id;
            $accountentries->save();
        /*end*/

    $transaction_id=[];
   
    /*account_transactions table*/
    if($accountentries->id){
        $expense_trans1 = array_combine($expense_id,$amount);
        $expense_trans = array_filter($expense_trans1);
        $debit_ledger_id=array_combine($expense_id,$debit_ledger);
        foreach ($expense_trans as $key => $value) 
         {     
            $transaction = new AccountTransaction;
            $transaction->debit_ledger_id=$debit_ledger_id[$key];
            $transaction->credit_ledger_id=$credit_ledger_id;
            $transaction->amount =$value;
            $transaction->description=$description;
            $transaction->entry_id=$accountentries->id;
            $transaction->save();
            Custom::userby($transaction, true);

            array_push($transaction_id,$transaction->id);
        
     

        }
    }
    /*End*/


    /*Expense_transactions table*/
    if($transaction->id){
        $expense_trans_arrary = array_combine($expense_id, $amount);
        $expense_trans = array_filter($expense_trans_arrary);

        $expenses_id_value=array_keys($expense_trans);
        $acc_tran_id=array_combine($expenses_id_value,$transaction_id);

        $des = array_combine($expense_id,$expense_description);
        $employee = array_combine($expense_id, $employee_id);
        foreach ($expense_trans as $key => $value) {
            
        $expenses_transaction = new ExpensesTransaction;
        $expenses_transaction->expense_id = $key;
        $expenses_transaction->employee = $employee[$key];
        $expenses_transaction->amount = $value;
        $expenses_transaction->reference_entry_id=$acc_tran_id[$key];
        $expenses_transaction->description =$des[$key];
        $expenses_transaction->expense_type = 1;
        $expenses_transaction->organization_id = $organization_id;
        $expenses_transaction->save();
        Custom::userby($expenses_transaction, true);

    }
        }
    /*end*/
     
        return response()->json(['status' => 1,'data'=>['date'=>$accountentries->date]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($request->all());
        $organization_id = Session::get('organization_id');
        
       
       
        /*$expense = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','expenses.display_name','expenses_transactions.amount','expenses.id as expense_id','expenses_transactions.employee','hrm_employees.first_name','account_transactions.id as transaction_id','expenses_transactions.id as expense_trans_id','expenses_transactions.description');
        $expense->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $expense->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
        $expense->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
        $expense->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
        $expense->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');
        $expense->where('account_entries.organization_id','=',$organization_id);
        ->where('account_ledgers.name','=','Daily Expenses')
        $expense->where(function($query){
               $query->where('expenses_transactions.expense_type',1)
               ->orWhere('expenses_transactions.expense_type',NULL);

        });
        $expense->where('account_entries.date','=',$request->input('date'));
        $expenses=$expense->get();*/
        //dd($expenses);
      
        $expenses = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','expenses.display_name','expenses_transactions.amount','expenses.id as expense_id','expenses_transactions.employee','hrm_employees.first_name','account_transactions.id as transaction_id','expenses_transactions.id as expense_trans_id','expenses_transactions.description')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id')
        ->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee')
        ->where('account_entries.organization_id','=',$organization_id)
       /* ->where('account_ledgers.name','=','Daily Expenses')*/
       ->where('expenses_transactions.expense_type',1)
      
        ->where('account_entries.date','=',$request->input('date'))
        ->get();
        //dd($expenses);

        
        $employee = HrmEmployee::select('first_name', 'id')->where('organization_id', $organization_id)->get();
    
        $amount = AccountEntry::select('account_transactions.amount')
                ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
                ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
                ->where('account_entries.organization_id','=',$organization_id)
                ->where('account_vouchers.name','=','payment')
                ->where('account_entries.date','=',$request->input('date'))
                ->first();
               
        return response()->json(['expenses' =>$expenses,'employee'=>$employee,'amount'=>$amount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $organization_id = Session::get('organization_id');
        $expenses = Expense::where('id', $id)->where('organization_id', $organization_id)->first();
        $employee = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name', 'id');
        $employee->prepend('Select Employee', '');
        return view('accounts.expenses_edit', compact('expenses','employee'));
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

       
       
        $organization_id = Session::get('organization_id');
        $voucher_master = AccountVoucher::where('display_name',$request->input('voucher_type'))->where('organization_id',$organization_id)->first();
        $voucher_id = $voucher_master->id;
        $previous_entry = AccountEntry::where('voucher_id', $voucher_id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;
        $payment = PaymentMode::where('name',$request->input('payment_mode'))->first();
        $expense_id = $request->input('reference_voucher_id');
        $expenses = $request->input('reference_voucher');
        $description = $request->input('notes');
        $employee_id = $request->input('employee');
        $debit_ledger= AccountLedger::where('name',$request->input('debit_ledger'))->where('organization_id',$organization_id)->first();
        $debit_ledger_id = $debit_ledger->id;
        $credit_ledger= AccountLedger::where('name',$request->input('credit_ledger'))->where('organization_id',$organization_id)->first();
        $credit_ledger_id = $credit_ledger->id;
        $amount = $request->input('amount');
        $total_amount = $request->input('total_amount');
        $entry_id = $request->input('entry_id');
        $transcation_id = $request->input('transcation_id');
        $expense_trans_id = $request->input('expense_trans_id');
        //dd($expense_trans_id);

        $transaction = AccountTransaction::findOrFail($transcation_id);
        $transaction->debit_ledger_id = $debit_ledger_id;
        $transaction->credit_ledger_id = $credit_ledger_id;
        $transaction->amount = $total_amount;
        $transaction->entry_id = $entry_id;
        $transaction->save();
        Custom::userby($transaction, true);

        if($transaction->id)
        {
            $expense = array_combine($expense_id, $amount);
          
            $employee = array_combine($expense_id, $employee_id);
            $expense_trans= array_combine($expense_id, $expense_trans_id);
            foreach ($expense as $key => $value)
            {
                $expenses_transaction = ExpensesTransaction::findOrFail($expense_trans[$key]);
                $expenses_transaction->expense_id = $key;
                $expenses_transaction->employee = $employee[$key];
                $expenses_transaction->amount = $value;
                $expenses_transaction->reference_entry_id = $transaction->id;
                $expenses_transaction->organization_id = $organization_id;
                $expenses_transaction->expense_type = 1;
                
                $expenses_transaction->save();
                Custom::userby($expenses_transaction, true);
            }
        }

        $date=$previous_entry->date;
        return response()->json(['status' => 1,'date'=>$date]);

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
    public function report(Request $request)
    {
       
       
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $organization_id = Session::get('organization_id');

        $total_expenses = AccountEntry::select(DB::raw('sum(expenses_transactions.amount) as total'));
        $total_expenses->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $total_expenses->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
        $total_expenses->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
        $total_expenses->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
       
        $total_expenses->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');   
      
       
        $total_expenses->where('account_entries.organization_id','=',$organization_id);
      /*  $total_expenses->where('account_ledgers.name','=','Daily Expenses');*/
        $total_expenses->where('expenses_transactions.expense_type',1);
        if(!empty($from_date) && !empty($to_date))
       {
        $total_expenses->wherebetween('account_entries.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
       {
        $total_expenses->where('account_entries.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
        $total_expenses->where('account_entries.date','<=',$to_date);
        }
        $total_expense = $total_expenses->first();

       
     $barchart=Expense::select('expenses.name',DB::raw("SUM(CASE WHEN account_entries.date BETWEEN '$from_date' AND '$to_date' THEN expenses_transactions.amount ELSE 0 END)as amount"))
        ->leftjoin('expenses_transactions','expenses.id','=','expenses_transactions.expense_id')
        ->leftjoin('account_transactions','account_transactions.id','=','expenses_transactions.reference_entry_id')
        ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
        ->where('expenses.organization_id','=',$organization_id)
        ->wherebetween('account_entries.date',[$from_date,$to_date])
        ->orwhere('expenses.organization_id','=',$organization_id)
        ->groupBy('expenses_transactions.expense_id')
        ->get(); 
        
      $chart = [];
        foreach ( $barchart as $bar) {
               $chart [] =[$bar->name,(int)$bar->amount];
        }
       $bar= $chart;
       $headers = ["Expenses","Amount"];
       $header = [$headers];
       $mergevalues = array_merge($header, $bar);
       $barchart_value = json_encode($mergevalues);

       
       return response()->json(['status'=>1,'total_expense'=>$total_expense,'barchart'=>$barchart_value]);  
    }
}
