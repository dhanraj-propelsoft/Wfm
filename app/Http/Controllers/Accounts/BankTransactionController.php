<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountEntry;
use App\AccountGroup;
use App\AccountLedger;
use App\AccountVoucher;
use App\AccountTransaction;
use App\Transaction;
use Carbon\Carbon;
use App\Custom;
use Session;
use DB;

class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');
        $from = new Carbon('first day of this month');
        $from_date_to_show = $from->format('d-m-Y');
        $from_date = $from->format('Y-m-d');
        //dd($from->format('Y-m-d'));
        $to =Carbon::today();
        $to_date_to_show = $to->format('d-m-Y');
        $to_date = $to->format('Y-m-d');
        //dd($to->format('Y-m-d'));

        $box_values = DB::select("SELECT 
                        account_ledgers.id AS ledger_id,
                        account_groups.id AS group_id,
                        IF(
                            parent_groups.id IS NULL,
                            account_groups.id,
                            parent_groups.id
                        ) AS parent_group_id,
                        account_groups.account_head,
                        account_groups.display_name AS group_name,
                        IF(
                            parent_groups.display_name IS NULL,
                            account_groups.display_name,
                            parent_groups.display_name
                        ) AS parent_name,
                        account_ledgers.display_name AS ledger_name,
                        account_ledgers.group_id AS ledger_group_id,
                        account_groups.parent_id,
                        parent_groups.parent_id AS group_parent_id,
                        credit_account.credit,
                        debit_account.debit,
                        IF(
                        opening_balance_type = 'Debit',
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) - opening_balance
                      ) AS closing_balance,
                    IF(
                        opening_balance_type = 'Credit',
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) - opening_balance
                      ) AS closing_balance1,
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
                    LEFT JOIN 
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
                            WHERE  account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY cr) AS credit_account 
                            ON credit_account.cr = account_ledgers.id 
                        LEFT JOIN 
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
                            WHERE account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY dr) AS debit_account 
                            ON debit_account.dr = account_ledgers.id 
                        WHERE account_ledgers.organization_id = '".$organization_id."' 
                        AND (
                                account_groups.name = 'cash'
                                OR account_groups.name = 'bank_account'
                              )
                        GROUP BY  account_ledgers.id");

        
        $select_value = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $select_value->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $select_value->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $select_value->where('account_groups.organization_id',$organization_id);
        $select_values = $select_value->get();

        $ledger = AccountGroup::select('account_groups.display_name AS group_name','account_ledgers.name AS ledger_name');
        $ledger->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $ledger->where('account_ledgers.organization_id','=',$organization_id);
        $ledger->where(function ($query){
          $query->where('account_groups.name', '=','cash')
                ->orWhere('account_groups.name', '=','bank_account');
        });
        $ledgers = $ledger->get();

        $ledger_account = array();

       foreach ($ledgers as $ledger) {
        $ledger_account[] = $ledger->ledger_name;
       }

      /* $table_values = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','account_transactions.amount','account_ledgers.display_name AS to_account','credit_account.display_name AS from_account')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
        ->where('account_entries.organization_id','=',$organization_id)
        ->whereIn('account_ledgers.name',$ledger_account)
        ->whereIn('credit_account.name',$ledger_account)
        ->wherebetween('account_entries.date',[$from_date,$to_date])
        ->get();*/

         $withdraw_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','withdrawal')->first();

        $deposit_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','deposit')->first();

          $table_value = AccountEntry::select('account_entries.id','account_entries.date','debit_ledger.display_name AS to_account','credit_ledger.display_name AS from_account','account_transactions.amount','account_entries.voucher_no','account_transactions.id as transaction_id');
        $table_value->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $table_value->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $table_value->leftjoin('account_ledgers AS debit_ledger','debit_ledger.id','=','account_transactions.debit_ledger_id');
        $table_value->leftjoin('account_ledgers AS credit_ledger','credit_ledger.id','=','account_transactions.credit_ledger_id');
        $table_value->where('account_entries.organization_id','=',$organization_id);
        $table_value->where(function ($query) use($deposit_voucher_id,$withdraw_voucher_id){
                    $query->where('account_vouchers.id', '=',$deposit_voucher_id->id)
                        ->orWhere('account_vouchers.id', '=',$withdraw_voucher_id->id);
        });
       $table_value->whereIn('debit_ledger.name',$ledger_account);
       $table_value->whereIn('credit_ledger.name',$ledger_account);
       $table_value->wherebetween('account_entries.date',[$from_date,$to_date]);
       $table_values = $table_value->get();
       //dd($table_values);


        return view('accounts.bank_transactions',compact('box_values','select_values','table_values','from_date_to_show','to_date_to_show'));
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

    public function get_to_account(Request $request)
    {
        //dd($request->all());
        $organization_id = Session::get('organization_id');
         $select_value = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $select_value->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $select_value->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $select_value->where('account_groups.organization_id',$organization_id);
        $select_value->where('account_ledgers.id','!=',$request->id);
        $select_values = $select_value->get();
        return response()->json(['data' => $select_values]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $voucher_name = $request->input('ledger');
        if($voucher_name == "deposit"){
           $debit_ledger_id = $request->input('to_account');
           $credit_ledger_id = $request->input('from_account');
        }else{
            $credit_ledger_id = $request->input('from_account');
            $debit_ledger_id = $request->input('to_account');
        }
        $amount = $request->input('amount');
        $notes = $request->input('notes');
        $reference_no = $request->input('reference_no');
        $is_person = false;

        $voucher_master = AccountVoucher::where('organization_id', $organization_id)->where('name', $voucher_name)->first();
        $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;
        $g_n = custom::generate_accounts_number($voucher_master->name, $gen_no, $is_person, ($is_person) ? $organization_id: null);
        //dd($g_n);

        $accountentries = new AccountEntry;
        $accountentries->voucher_no = custom::generate_accounts_number($voucher_master->name, $gen_no, $is_person, ($is_person) ? $organization_id: null);
        $accountentries->reference_voucher = $reference_no;
        $accountentries->gen_no = $gen_no;
        $accountentries->date = $date;
        $accountentries->voucher_id = $voucher_master->id;
        $accountentries->description = $notes;
        $accountentries->status = 1;
        $accountentries->organization_id = $organization_id;
        Custom::userby($accountentries, true, ($is_person) ? $id: null);
        $accountentries->save();

        $accountentryid = $accountentries->id;

        if($accountentryid){             
                $voucheraccount = new AccountTransaction;  
                $voucheraccount->debit_ledger_id = $debit_ledger_id;
                $voucheraccount->credit_ledger_id = $credit_ledger_id;
                $voucheraccount->amount = $amount;
                $voucheraccount->entry_id= $accountentryid;
                $voucheraccount->save();
                Custom::userby($voucheraccount, true, ($is_person) ? $id: null);
        }

        $from_account = AccountLedger::where('organization_id', $organization_id)->where('id', $credit_ledger_id)->first();
        $to_account =  AccountLedger::where('organization_id', $organization_id)->where('id', $debit_ledger_id)->first();
        $voucher_no = AccountEntry::select('voucher_no')->where('organization_id', $organization_id)->where('id', $accountentryid)->first();


        return response()->json(['status' => 1, 'message' => 'Transactions'.config('constants.flash.added'), 'data' => ['id' => $accountentries->id, 'date' => $accountentries->date, 'from_account' => $from_account->display_name,'to_account' => $to_account->display_name,'voucher_no' => $voucher_no->voucher_no, 'amount' => $voucheraccount->amount,'transaction_id'=>$voucheraccount->id]]);




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
        $table_value = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','debit_ledger.id AS to_account','credit_ledger.id AS from_account','account_transactions.amount','account_entries.description','account_entries.reference_voucher','account_entries.voucher_id','account_vouchers.name as voucher_name')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
        ->leftjoin('account_ledgers AS debit_ledger','debit_ledger.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_ledger','credit_ledger.id','=','account_transactions.credit_ledger_id')
        ->where('account_entries.organization_id','=',$organization_id)
        ->where('account_entries.id','=',$id)
        ->first();
        

        return response()->json(['data' => $table_value]);
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
        $voucher_name = $request->input('ledger');
        if($voucher_name == "deposit"){
           $debit_ledger_id = $request->input('to_account');
           $credit_ledger_id = $request->input('from_account');
        }else{
            $credit_ledger_id = $request->input('from_account');
            $debit_ledger_id = $request->input('to_account');
        }
        $is_person = false;
        $accountentries = AccountEntry::findOrFail($request->input('id'));
        $accountentries->reference_voucher = $request->input('reference_no');
        $accountentries->date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $accountentries->voucher_id = $request->input('reference_voucher');
        $accountentries->description = $request->input('notes');
        $accountentries->status = 1;
        $accountentries->organization_id = $organization_id;
        $accountentries->save();
        Custom::userby($accountentries, false);

        $id = AccountTransaction::select('id')->where('entry_id', $accountentries->id)->first();
        $accountentryid =  $accountentries->id;

        if($accountentries){
            $voucheraccount = AccountTransaction::findOrFail($id->id);
            $voucheraccount->debit_ledger_id = $debit_ledger_id;
            $voucheraccount->credit_ledger_id = $credit_ledger_id;
            $voucheraccount->amount = $request->input('amount');
            $voucheraccount->entry_id= $accountentryid;
            $voucheraccount->save();
            Custom::userby($voucheraccount, false);
        }

        $from_account = AccountLedger::where('organization_id', $organization_id)->where('id', $credit_ledger_id)->first();
        $to_account =  AccountLedger::where('organization_id', $organization_id)->where('id', $debit_ledger_id)->first();

        return response()->json(['status' => 1, 'message' => 'Transactions'.config('constants.flash.updated'), 'data' => ['id' => $accountentries->id, 'date' => $accountentries->date, 'from_account' => $from_account->display_name,'to_account' => $to_account->display_name,'description' => ($accountentries->description != null) ? $accountentries->description : "", 'amount' => $voucheraccount->amount,'transaction_id'=>$voucheraccount->id]]);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $account_entry = AccountEntry::findOrFail($request->input('id'));
        $account_entry->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Transaction'.config('constants.flash.deleted'), 'data' => []]);

        
    }

    public function search_result(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $withdraw_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','withdrawal')->first();

        $deposit_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','deposit')->first();


         $box_values = DB::select("SELECT 
                        account_ledgers.id AS ledger_id,
                        account_groups.id AS group_id,
                        IF(
                            parent_groups.id IS NULL,
                            account_groups.id,
                            parent_groups.id
                        ) AS parent_group_id,
                        account_groups.account_head,
                        account_groups.display_name AS group_name,
                        IF(
                            parent_groups.display_name IS NULL,
                            account_groups.display_name,
                            parent_groups.display_name
                        ) AS parent_name,
                        account_ledgers.display_name AS ledger_name,
                        account_ledgers.group_id AS ledger_group_id,
                        account_groups.parent_id,
                        parent_groups.parent_id AS group_parent_id,
                        credit_account.credit,
                        debit_account.debit,
                        IF(
                        opening_balance_type = 'Debit',
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) - opening_balance
                      ) AS closing_balance,
                    IF(
                        opening_balance_type = 'Credit',
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) - opening_balance
                      ) AS closing_balance1,
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
                    LEFT JOIN 
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
                            WHERE account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY cr) AS credit_account 
                            ON credit_account.cr = account_ledgers.id 
                        LEFT JOIN 
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
                            WHERE account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY dr) AS debit_account 
                            ON debit_account.dr = account_ledgers.id 
                        WHERE account_ledgers.organization_id = '".$organization_id."' 
                        AND (
                                account_groups.name = 'cash'
                                OR account_groups.name = 'bank_account'
                              )
                        GROUP BY  account_ledgers.id");


       

        $table_value = AccountEntry::select('account_entries.id','account_entries.date','debit_ledger.display_name AS to_account','credit_ledger.display_name AS from_account','account_transactions.amount','account_entries.voucher_no','account_transactions.id as transaction_id');
        $table_value->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $table_value->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $table_value->leftjoin('account_ledgers AS debit_ledger','debit_ledger.id','=','account_transactions.debit_ledger_id');
        $table_value->leftjoin('account_ledgers AS credit_ledger','credit_ledger.id','=','account_transactions.credit_ledger_id');
        $table_value->where('account_entries.organization_id','=',$organization_id);
        $table_value->where(function ($query) use($deposit_voucher_id,$withdraw_voucher_id){
                    $query->where('account_vouchers.id', '=',$deposit_voucher_id->id)
                        ->orWhere('account_vouchers.id', '=',$withdraw_voucher_id->id);
        });
        if(!empty($from_date) && !empty($to_date))
        {
          $table_value->wherebetween('account_entries.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
        {
          $table_value->where('account_entries.date','>=',$from_date);
              
        }
        if($request->input('to_date'))
        {
           $table_value->where('account_entries.date','<=',$to_date);
        }
        $search_result = $table_value->get();


        return response()->json(['status' => 1, 'data' => ['data' => $search_result, 'box_values' => $box_values]]);
        
    }

    public function reset_options(Request $request)
    {
        //dd($request->all());
        $organization_id = Session::get('organization_id');
        $ledger = $request->input('ledger_name');
        $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
        //dd($from_date);
        $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
        //dd($to_date);

        $ledgers = AccountGroup::select('account_groups.display_name AS group_name','account_ledgers.name AS ledger_name');
        $ledgers->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $ledgers->where('account_ledgers.organization_id','=',$organization_id);
        $ledgers->where(function ($query){
          $query->where('account_groups.name', '=','cash')
                ->orWhere('account_groups.name', '=','bank_account');
        });
        $ledger_accounts = $ledgers->get();

        $ledger_names = array();

       foreach ($ledger_accounts as $ledger_account) {
        $ledger_names[] = $ledger_account->ledger_name;
       }
       //dd($ledger_names);


        /* $table_values = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','account_transactions.amount','account_ledgers.display_name AS to_account','credit_account.display_name AS from_account','account_transactions.id as transaction_id','account_ledgers.name')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
        ->where('account_entries.organization_id','=',$organization_id)
        ->where('account_ledgers.display_name',$ledger)
        ->orWhere('credit_account.display_name',$ledger)
        ->whereIn('credit_account.name',$ledger_names)
        ->wherebetween('account_entries.date',[$from_date,$to_date])
        ->get();
*/
          $withdraw_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','withdrawal')->first();

        $deposit_voucher_id = AccountVoucher::select('id')->where('organization_id',$organization_id)->where('name','deposit')->first();

       

        $table_value = AccountEntry::select('account_entries.id','account_entries.date','debit_ledger.display_name AS to_account','credit_ledger.display_name AS from_account','account_transactions.amount','account_entries.voucher_no','account_transactions.id as transaction_id');
        $table_value->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $table_value->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $table_value->leftjoin('account_ledgers AS debit_ledger','debit_ledger.id','=','account_transactions.debit_ledger_id');
        $table_value->leftjoin('account_ledgers AS credit_ledger','credit_ledger.id','=','account_transactions.credit_ledger_id');
        $table_value->where('account_entries.organization_id','=',$organization_id);
        $table_value->where(function ($query) use($deposit_voucher_id,$withdraw_voucher_id){
                    $query->where('account_vouchers.id', '=',$deposit_voucher_id->id)
                        ->orWhere('account_vouchers.id', '=',$withdraw_voucher_id->id);
        });
         $table_value->where(function ($query) use($ledger){
                    $query->where('debit_ledger.display_name', '=',$ledger)
                        ->orWhere('credit_ledger.display_name', '=',$ledger);
        });
        
        $table_value->whereIn('credit_ledger.name',$ledger_names);
        $table_value->wherebetween('account_entries.date',[$from_date,$to_date]);
        $table_values = $table_value->get();
        //dd($table_values);

         $select_value = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $select_value->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $select_value->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $select_value->where('account_groups.organization_id',$organization_id);
        $select_value->where('account_ledgers.id','!=',$request->ledger_id);
        $select_values = $select_value->get();




        return response()->json(['status' => 1, 'data' => $table_values,'select_value' => $select_values]);
        
    }
}
