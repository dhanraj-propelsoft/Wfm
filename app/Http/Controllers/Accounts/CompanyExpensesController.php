<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountGroup;
use App\AccountPersonType;
use App\AccountVoucher;
use App\AccountEntry;
use App\PaymentMode;
use App\AccountTransaction;
use App\ExpensesTransaction;
use App\CustomerGroping;
use App\PeopleTitle;
use App\AccountLedger;
use App\AccountLedgerType;
use App\People;
use App\Country;
use App\State;
use App\Term;
use App\City;
use App\TaxGroup;
use Carbon\Carbon;
use App\Custom;
use Session;
use DB;
use App\Organization;

class CompanyExpensesController extends Controller
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
        $from = new Carbon('first day of this month');
        $from_date_to_show = $from->format('d-m-Y');
        $from_date = $from->format('Y-m-d');
        //dd($from->format('Y-m-d'));
        $to =Carbon::today();
        $to_date_to_show = $to->format('d-m-Y');
        $to_date = $to->format('Y-m-d');

        $from_account = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $from_account->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $from_account->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $from_account->where('account_groups.organization_id',$organization_id);
        $from_accounts = $from_account->get(); 

       /* $from_accounts = PaymentMode::select('display_name','id')->get();*/
        $from = array();
       foreach ($from_accounts as $value) {
           $from[] = $value->display_name;
       }
       

        $person_type = AccountPersonType::select('id')->where('name','vendor')->first();

        $expense_ledgers = AccountGroup::select('account_ledgers.id','account_ledgers.display_name AS ledger_name')
        ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
        ->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id')
        ->where('account_heads.name','=',"expense")->where('account_groups.organization_id',$organization_id)
        ->whereNotNull('account_ledgers.id')
        ->get();

       /* $taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name AS name', 'tax_types.display_name as tax_type', DB::raw('SUM(taxes.value) AS value'), 'tax_groups.status', 'taxes.is_percent')
        ->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
        ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
        ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
        ->where('tax_groups.organization_id', $organization_id)
        ->groupby('tax_groups.id')
        ->orderby('tax_groups.name')
        ->get();*/

        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();
        //dd($taxes);

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $people = $people_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');

        $business = $business_list->pluck('name', 'id');
        $business->prepend('Select Business', '');
        

        $peoples = People::select('person_id AS id','display_name', 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $businesses = People::select('business_id AS id', 'display_name', 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $get_people_ledgers = $peoples->get();
        $get_business_ledgers = $businesses->get();

        $people_ledger = array();
        $business_ledger = array();

        foreach ($get_people_ledgers as $value) {
           $people_ledger[] = $value->display_name;
        }

        foreach ($get_business_ledgers as $value) {
           $business_ledger[] = $value->display_name;
        }

        $suppliers = array_merge($people_ledger,$business_ledger);
        $people_ledger1 = array_filter($suppliers);
      
        $title = PeopleTitle::pluck('display_name','id');

        $title->prepend('Title','');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');

        $group_name->prepend('Select Group Name', '');

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');

        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $table_values = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','credit_account.display_name AS from_account','account_ledgers.display_name AS to_account','account_transactions.amount','expenses_transactions.id as expenses_id','expenses_transactions.reference_account_entry_id as entry_id',DB::raw('(SELECT ec_ent.voucher_no AS vou FROM account_entries AS ec_ent
  LEFT JOIN expenses_transactions AS ex_tra ON ex_tra.reference_account_entry_id = ec_ent.id 
  WHERE ex_tra.reference_account_entry_id= expenses_transactions.reference_account_entry_id) AS expense_voucher'))
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
        ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_entries.id')
        ->where('account_entries.organization_id',$organization_id)
        ->where('account_vouchers.name','=','payment')
        ->whereIn('credit_account.name',$from)
        ->whereIn('account_ledgers.name',$people_ledger1)
        ->wherebetween('account_entries.date',[$from_date,$to_date])
        ->get();
        //dd($table_values);
       /* foreach($table_values as $table_value)
        {
            $entry_id[] = $table_value['entry_id'];
        }
            //dd($entry_id);

        $expenses_voucher_name = AccountEntry::select('account_entries.id','account_entries.voucher_no')
        ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_entries.id')
        ->whereIn('expenses_transactions.reference_account_entry_id',$entry_id)
        ->get();*/

        //dd($expenses_voucher_name);

       /* $expenses_voucher_name = AccountEntry::select('account_entries.id','account_entries.voucher_no')
        ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
        ->where('account_vouchers.name','CompanyExpenses')
        ->where('account_entries.organization_id',$organization_id)
        ->whereIn('credit_account.name',$from)
        ->wherebetween('account_entries.date',[$from_date,$to_date])
        ->get();*/
        //dd($expenses_voucher_name);

       
         
        
        return view('accounts.company_expenses',compact('from_accounts','suppliers','expense_ledgers','taxes','people','business','title','group_name','country','state','city','payment','terms','table_values','from_date_to_show','to_date_to_show','expenses_voucher_name','module_name'));
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
        //dd($request->all());
        $organization_id = Session::get('organization_id');
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $amount= $request->input('amount');
        $reference_no = $request->input('reference_no');
        $tax = $request->input('tax');
        $from_account = $request->input('from_account');
        $to_account = $request->input('to_account');
        $people_type = $request->input('people_type'); 
        $organization = Organization::findOrFail($organization_id);
        $amount_after_tax = $request->input('amount_after_tax');


       /* $to_ledger = People::select('people.display_name',DB::raw('IF(people.user_type = 0,person_ledger.id,account_ledgers.id) AS ledger_id'));
        $to_ledger->leftjoin('account_ledgers','account_ledgers.business_id','=','people.business_id');
        $to_ledger->leftjoin('account_ledgers AS person_ledger','person_ledger.person_id','=','people.person_id');
        $to_ledger->where('people.organization_id',$organization_id);
        $to_ledger->where('people.user_type',$people_type);
        $to_ledger->where(function ($query) use($to_account){
                    $query->where('people.person_id', '=',$to_account)
                        ->orWhere('people.business_id', '=',$to_account);
        });

        $to = $to_ledger->first();*/
        $person_list = People::select('user_type', 'display_name','id');
        if($request->user_type == 0) {
            $person_list->where('person_id', $request->input('people_id'));
        } else if($request->user_type == 1) {
            $person_list->where('business_id', $request->input('people_id'));
        }
        $person_list->where('organization_id', $organization_id);
        $persons = $person_list->first();
                    
        //dd($persons);

        $account_ledgers = AccountLedger::select('account_ledgers.id');

       if($persons != null) {
            //dd("work");
            if($request->user_type == 0) {
                $account_ledgers->where('person_id', $request->input('people_id'));
                $account_ledgers->where('organization_id', $organization_id);

                $person_id = $request->input('people_id');
                $business_id = null;
            }
            else if($request->user_type == 1) {
                $account_ledgers->where('business_id', $request->input('people_id'));
                $account_ledgers->where('organization_id', $organization_id);
                $business_id = $request->input('people_id');
                $person_id = null;
            }
        } else {
            //dd("working");

            $account_ledgers->where('business_id', $request->input('people_id'));
            $business_id = $request->input('people_id');
            $person_id = null;
        }       
        
        $account_ledger = $account_ledgers->first();
       //dd($account_ledger);

        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

        if($account_ledger != null){
            $customer_ledger = $account_ledger->id;
        }
        else {
            
                $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();
                //dd($persons->display_name);
                $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);          

        }

        $ledger= $request->input('ledger');
        $tax_percent = $request->input('tax_percent');
        $taxes = TaxGroup::select('taxes.display_name')->leftjoin('group_tax','group_tax.group_id','=','tax_groups.id')->leftjoin('taxes','taxes.id','=','group_tax.tax_id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id',$tax_percent)->get();
        $tax_result = array();
        foreach ($taxes as $value) {
           $tax_result[] = $value->display_name;
        }
        $tax_persents = AccountLedger::select('id')->where('organization_id',$organization_id)->whereIn('name',$tax_result)->get();
    
       
        $is_person = false;
        
        $tax_values = $request->input('tax_val');
        if($tax_values)
        {
            foreach($tax_values as $tax_value)
            {
                
                $val = $tax_value['value'];
                //$include_tax =  Custom::two_decimal($amount*($val+1));
               
                $tax_to = $amount * ( $val/100);

                $tax_new = Custom::two_decimal(($amount)/(($val/100)+1));
                          
                $tax_new_values = ($val/100)*($tax_new);
                $tax_new_val1 = Custom::two_decimal($tax_new_values);
                $tax_new_val = Custom::two_decimal($tax_to);


                //dd($tax_new_val);
            }
        }
        $tax_value1 = $tax/100  * $amount;
        $tax_amount = $amount/($tax+100);
       /* $tax_amount  = $amount + $tax_value;*/
        $voucher_master = AccountVoucher::where('organization_id', $organization_id)->where('name','payment')->first();
        $voucher_master1 = AccountVoucher::where('organization_id', $organization_id)->where('name','CompanyExpenses')->first();
        //dd($voucher_master1);
        $previous_entry = AccountEntry::where('voucher_id', $voucher_master->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();
        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;

        $expense_previous_entry = AccountEntry::where('voucher_id', $voucher_master1->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();
        $expense_gen_no = ($expense_previous_entry != null) ? ($expense_previous_entry->gen_no + 1) : $voucher_master1->starting_value;
        //dd("gen".$gen_no."name".$voucher_master1->name."is_person".$is_person);
         $entries = array();
         //hide for wrong credit ledger
       /*  $entries[] = ['debit_ledger_id' => $ledger, 'credit_ledger_id' => $to->ledger_id, 'amount' => $amount];

        foreach ($tax_persents as $value) {
            $entries[] =  ['debit_ledger_id' => $value->id, 'credit_ledger_id' => $to->ledger_id, 'amount' => $tax_value/2];
        }*/
         $entries[] = ['debit_ledger_id' => $ledger, 'credit_ledger_id' => $customer_ledger, 'amount' => $amount];

        foreach ($tax_persents as $value) {
            $entries[] =  ['debit_ledger_id' => $value->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax_new_val];
        }

       /* $entries[] = ['debit_ledger_id' => $tax_percent, 'credit_ledger_id' => $to->ledger_id, 'amount' => $tax_value];*/
        $entries2 = array();
        //hide for wrong credit ledger
       /* $entries2[] = ['debit_ledger_id' => $to->ledger_id, 'credit_ledger_id' => $from_account, 'amount' => $tax_amount];*/
        $entries2[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $from_account, 'amount' => $amount_after_tax];

        //dd($entries);
       

        $accountentries = new AccountEntry;
        $accountentries->voucher_no = Custom::generate_accounts_number($voucher_master1->name, $expense_gen_no, $is_person, ($is_person) ? $organization_id: null);
        $accountentries->reference_voucher = $reference_no;
        $accountentries->gen_no = $expense_gen_no;
        $accountentries->date = $date;
       /* $accountentries->payment_mode_id = $from_account;*/
        $accountentries->voucher_id = $voucher_master1->id;
        $accountentries->status = 1;
        $accountentries->organization_id = $organization_id;
        Custom::userby($accountentries, true, ($is_person) ? $id: null);
        $accountentries->save();

        $accountentryid = $accountentries->id;

        if($accountentryid){ 
            foreach ($entries as $entry) {              
                $voucheraccount = new AccountTransaction;  
                $voucheraccount->debit_ledger_id = $entry['debit_ledger_id'];
                $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
               /* $voucheraccount->description = $description;*/
                $voucheraccount->amount = $entry['amount'];
                $voucheraccount->entry_id= $accountentryid;
                $voucheraccount->save();
                Custom::userby($voucheraccount, true, ($is_person) ? $id: null);
            } 
        }

        if($voucheraccount){

            $accountentries = new AccountEntry;
            $accountentries->voucher_no = custom::generate_accounts_number($voucher_master->name, $gen_no, $is_person, ($is_person) ? $organization_id: null);
            $accountentries->reference_voucher = $reference_no;
            $accountentries->gen_no = $gen_no;
            $accountentries->date = $date;
            /* $accountentries->payment_mode_id = $from_account;*/
            $accountentries->voucher_id = $voucher_master->id;
            $accountentries->status = 1;
            $accountentries->organization_id = $organization_id;
            Custom::userby($accountentries, true, ($is_person) ? $id: null);
            $accountentries->save();

            $account_id = $accountentries->id;

            $expenses_entries = array();

            $expenses_entries[] = ['tax_id' => $tax_percent, 'reference_ledger_id' => $ledger, 'entry_id' => $account_id,'reference_id'=>$accountentryid,'amount'=>$amount_after_tax];

            if($account_id){ 
                foreach ($entries2 as $entry) {              
                    $voucheraccount = new AccountTransaction;  
                    $voucheraccount->debit_ledger_id = $entry['debit_ledger_id'];
                    $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
                  /* $voucheraccount->description = $description;*/
                    $voucheraccount->amount = $entry['amount'];
                    $voucheraccount->entry_id= $account_id;
                    $voucheraccount->save();
                    Custom::userby($voucheraccount, true, ($is_person) ? $id: null);
                } 

                foreach($expenses_entries as $expenses_entry) { 
                    $expense_transactions = new ExpensesTransaction;
                    $expense_transactions->organization_id = $organization_id; 
                    $expense_transactions->status = 1;
                    $expense_transactions->tax_id = $expenses_entry['tax_id'];
                    $expense_transactions->amount = $expenses_entry['amount'];
                    $expense_transactions->reference_ledger_id = $expenses_entry['reference_ledger_id'];
                    $expense_transactions->reference_account_entry_id = $expenses_entry['reference_id'];
                    $expense_transactions->entry_id = $expenses_entry['entry_id'];
                    $expense_transactions->expense_type = 2;
                    Custom::userby($expense_transactions, true, ($is_person) ? $id: null);
                    $expense_transactions->save();
                }   
            } 
        }

        $voucher_no = AccountEntry::select('voucher_no')->where('organization_id',$organization_id)->where('id',$account_id)->first();

        $debit_ledger = AccountLedger::select('display_name')->where('organization_id',$organization_id)->where('id',$voucheraccount->debit_ledger_id)->first();
        //dd($debit_ledger);

        $credit_ledger = AccountLedger::select('display_name')->where('organization_id',$organization_id)->where('id',$voucheraccount->credit_ledger_id)->first();

        $expenses_id = ExpensesTransaction::select('expenses_transactions.id as expenses_id','expenses_transactions.reference_account_entry_id as entry_id')->where('organization_id',$organization_id)->where('entry_id',$account_id)->first();

       
         return response()->json(['status' => 1, 'message' => 'Expense'.config('constants.flash.added'), 'data' => ['id' => $account_id,'date'=>$accountentries->date,'voucher_no'=>$voucher_no->voucher_no,'from_account'=>$credit_ledger->display_name,'to_account'=>$debit_ledger->display_name,'amount'=>$voucheraccount->amount,'expense_amount' => $amount_after_tax,'expensese_id'=>$expenses_id->expenses_id,'entry_id'=>$expenses_id->entry_id]]);
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
    public function edit(Request $request, $id)
    {
        $organization_id = Session::get('organization_id');

        $to_account = $request->input('to_account');
        $to_ledger = People::select(DB::raw('IF(user_type = 1,business_id,person_id)as id'),'user_type','people_addresses.address','cities.name AS city','states.name AS state')->leftjoin('people_addresses','people_addresses.people_id','=','people.id')->leftjoin('cities','cities.id','=','people_addresses.city_id')->leftjoin('states','states.id','=','cities.state_id')->where('organization_id',$organization_id)->where('display_name',$to_account)->first();
       

        $from_account = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $from_account->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $from_account->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $from_account->where('account_groups.organization_id',$organization_id);
        $from_accounts = $from_account->get(); 

        $from = array();
       foreach ($from_accounts as $value) {
           $from[] = $value->display_name;
       }

       $expense_ledgers = AccountGroup::select('account_ledgers.id','account_ledgers.display_name AS ledger_name')
        ->leftjoin('account_heads','account_heads.id','=','account_groups.account_head')
        ->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id')
        ->where('account_heads.name','=',"expense")->where('account_groups.organization_id',$organization_id)
        ->whereNotNull('account_ledgers.id')
        ->get();

        $expenses = array();
        foreach ($expense_ledgers as $value) {
            $expenses[] = $value->ledger_name;
        }

        $peoples = People::select('person_id AS id','display_name', 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $businesses = People::select('business_id AS id', 'display_name', 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $get_people_ledgers = $peoples->get();
        $get_business_ledgers = $businesses->get();

        $people_ledger = array();
        $business_ledger = array();

        foreach ($get_people_ledgers as $value) {
           $people_ledger[] = $value->display_name;
        }

        foreach ($get_business_ledgers as $value) {
           $business_ledger[] = $value->display_name;
        }

        $suppliers = array_merge($people_ledger,$business_ledger);
        $people_ledger = array_filter($suppliers);

        $edit_values = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','credit_account.id AS from_account','account_ledgers.id AS to_account','account_transactions.amount','account_entries.reference_voucher')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
        ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
        ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
        ->where('account_entries.organization_id',$organization_id)
        ->where('account_vouchers.name','=','payment')
        ->whereIn('credit_account.name',$from)
        ->whereIn('account_ledgers.name',$people_ledger)
        ->where('account_entries.id',$id)
        ->first();

        $expense_ledger = AccountEntry::select('expenses_ledger.id AS expense_ledger','expenses_transactions.amount','expenses_transactions.tax_id','expenses_transactions.entry_id')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_entries.id')
        ->leftjoin('account_ledgers AS expenses_ledger','expenses_ledger.id','=','expenses_transactions.reference_ledger_id')
        ->where('account_entries.organization_id',$organization_id)
        ->where('expenses_transactions.entry_id',$id)->first();

        if($expense_ledger != null){
            $entry_id = $expense_ledger->entry_id;
            $tax_id = $expense_ledger->tax_id;
            $amount = $expense_ledger->amount;
             $tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                ->where('tax_groups.organization_id', $organization_id)
                ->where('tax_groups.id', $expense_ledger->tax_id)
                ->groupby('tax_groups.id')->first();
                           // to exclude tax for showing in amount field 
                // to get amount 
            $tax_new1 = Custom::two_decimal($amount)/(($tax_value->value/100)+1);
            $expense_ledger = $expense_ledger->expense_ledger;
        }else{
            $entry_id = null;
            $tax_id = null;
            $amount = null;
            $expense_ledger = null;
        }
    

        return response()->json(['data' => $edit_values,'to_account'=>$to_ledger->id,'user_type'=>$to_ledger->user_type,'expense_ledger_id'=>$expense_ledger,'amount'=>$amount,
            'tax_id'=>$tax_id,'address'=>$to_ledger->address,'state'=>$to_ledger->state,
            'city'=>$to_ledger->city,'entry_id'=>$entry_id,'before_tax_amount' => $tax_new1]);
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
        //dd($request->all());
        $organization_id = Session::get('organization_id');
        $id = $request->input('id');
        $entry_id = $request->input('entry_id');
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $reference_no = $request->input('reference_no');
        $from_account = $request->input('from_account');
        $to_account = $request->input('to_account');
        $people_type = $request->input('people_type');
        $expense_ledger = $request->input('ledger');
        $tax = $request->input('tax');
        $tax_percent = $request->input('tax_percent');
        $amount = $request->input('amount');
        $amount_after_tax = $request->input('amount_after_tax');


        $voucher_master1 = AccountVoucher::where('organization_id', $organization_id)->where('name','purchases')->first();
        $voucher_master = AccountVoucher::where('organization_id', $organization_id)->where('name','payment')->first();

      /*  $to_ledger = People::select('people.display_name',DB::raw('IF(people.user_type = 0,person_ledger.id,account_ledgers.id) AS ledger_id'));
        $to_ledger->leftjoin('account_ledgers','account_ledgers.business_id','=','people.business_id');
        $to_ledger->leftjoin('account_ledgers AS person_ledger','person_ledger.person_id','=','people.person_id');
        $to_ledger->where('people.organization_id',$organization_id);
        $to_ledger->where('people.user_type',$people_type);
        $to_ledger->where(function ($query) use($to_account){
                    $query->where('people.person_id', '=',$to_account)
                        ->orWhere('people.business_id', '=',$to_account);
        });

        $to = $to_ledger->first();*/
        //dd($to);

        $person_list = People::select('user_type', 'display_name','id');
        if($request->user_type == 0) {
            $person_list->where('person_id', $request->input('people_id'));
        } else if($request->user_type == 1) {
            $person_list->where('business_id', $request->input('people_id'));
        }
        $person_list->where('organization_id', $organization_id);
        $persons = $person_list->first();
                    
        

        $account_ledgers = AccountLedger::select('account_ledgers.id');

       if($persons != null) {
           
            if($request->user_type == 0) {
                $account_ledgers->where('person_id', $request->input('people_id'));
                $account_ledgers->where('organization_id', $organization_id);

                $person_id = $request->input('people_id');
                $business_id = null;
            }
            else if($request->user_type == 1) {
                $account_ledgers->where('business_id', $request->input('people_id'));
                $account_ledgers->where('organization_id', $organization_id);

                $business_id = $request->input('people_id');
                $person_id = null;
            }
        } else {
          

            $account_ledgers->where('business_id', $request->input('people_id'));
            $business_id = $request->input('people_id');
            $person_id = null;
        }       
        
        $account_ledger = $account_ledgers->first();
     

        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

        if($account_ledger != null){
            $customer_ledger = $account_ledger->id;
        }
        else {
            
                $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();
               
                $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);          

        }

        $taxes = TaxGroup::select('taxes.display_name')->leftjoin('group_tax','group_tax.group_id','=','tax_groups.id')->leftjoin('taxes','taxes.id','=','group_tax.tax_id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id',$tax_percent)->get();
        $tax_result = array();
        foreach ($taxes as $value) {
           $tax_result[] = $value->display_name;
        }
        
        $tax_persents = AccountLedger::select('id')->where('organization_id',$organization_id)->whereIn('name',$tax_result)->get();
    
    
       
        $is_person = false;
        $tax_value = $tax/100  * $amount;

        $tax_values = $request->input('tax_val');
        if($tax_values)
        {
            foreach($tax_values as $tax_value)
            {
               
                $val = $tax_value['value'];
               
                $tax_to = $amount * ( $val/100);


                $tax_new = Custom::two_decimal(($amount)/(($val/100)+1));
                
                
                $tax_new_values = ($val/100)*($tax_new);
                $tax_new_val1 = Custom::two_decimal($tax_new_values);
                $tax_new_val = Custom::two_decimal($tax_to);


                
            }
        }
        //$tax_amount  = $amount + $tax_value;
        $entries = array();
       

        $entries[] = ['debit_ledger_id' => $expense_ledger, 'credit_ledger_id' => $customer_ledger, 'amount' => $amount];
       
        foreach ($tax_persents as $value) {
           
            $entries[] =  ['debit_ledger_id' => $value->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax_new_val];
            
        }
       

        $entries2 = array();

        $entries2[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $from_account, 'amount' => $amount_after_tax];
       

        $accountentries = AccountEntry::findOrFail($entry_id);
        $accountentries->reference_voucher = $reference_no;
        $accountentries->date = $date;
       /* $accountentries->payment_mode_id = $from_account;*/
        $accountentries->voucher_id = $voucher_master1->id;
        $accountentries->status = 1;
        $accountentries->organization_id = $organization_id;
        Custom::userby($accountentries, true, ($is_person) ? $id: null);
        $accountentries->save();
       

        $transaction_id = AccountTransaction::select('id')->where('entry_id', $entry_id)->first();
        $account_entry_id = ExpensesTransaction::where('entry_id',$entry_id)->first();
      
        $tra_ids = AccountTransaction::select('id')->where('entry_id',$account_entry_id->reference_account_entry_id)->get();

        //to get expense voucher number from account entries table
        $expenses_voucher_name = AccountEntry::where('id',$account_entry_id->reference_account_entry_id)->first();

      
        $update_id =  $tra_ids->toArray();
      
        $accountentryid = $accountentries->id;
       

        if($accountentryid){ 
           
               foreach ($entries as $key=>$value) {  
                $update_id[$key]['id'];
                $voucheraccount = AccountTransaction::findOrFail($update_id[$key]['id']);
                $voucheraccount->debit_ledger_id = $value['debit_ledger_id'];
                $voucheraccount->credit_ledger_id = $value['credit_ledger_id'];
               /* $voucheraccount->description = $description;*/
                $voucheraccount->amount = $value['amount'];
                $voucheraccount->entry_id= $account_entry_id->reference_account_entry_id;
                $voucheraccount->save();
                Custom::userby($voucheraccount, true, ($is_person) ? $id: null);
            } 
           
           
        }

        if($voucheraccount){

            $accountentries = AccountEntry::findOrFail($id);
            $accountentries->reference_voucher = $reference_no;
            $accountentries->date = $date;
            /* $accountentries->payment_mode_id = $from_account;*/
            $accountentries->voucher_id = $voucher_master->id;
            $accountentries->status = 1;
            $accountentries->organization_id = $organization_id;
            Custom::userby($accountentries, true, ($is_person) ? $id: null);
            $accountentries->save();

            $account_id = $accountentries->id;

            $expenses_entries = array();

            $expenses_entries[] = ['tax_id' => $tax_percent, 'reference_ledger_id' => $expense_ledger, 'entry_id' => $account_id,'reference_id'=>$accountentryid,'amount'=>$amount_after_tax];
          

            $transaction_id = AccountTransaction::select('id')->where('entry_id',$account_id)->first();

            $expense_id = ExpensesTransaction::select('id')->where('organization_id',$organization_id)->where('entry_id',$account_id)->first();

            if($account_id){ 
                foreach ($entries2 as $entry) {              
                    $voucheraccount = AccountTransaction::findOrFail($transaction_id->id);  
                    $voucheraccount->debit_ledger_id = $entry['debit_ledger_id'];
                    $voucheraccount->credit_ledger_id = $entry['credit_ledger_id'];
                    /* $voucheraccount->description = $description;*/
                    $voucheraccount->amount = $entry['amount'];
                    $voucheraccount->entry_id= $account_id;
                    $voucheraccount->save();
                    Custom::userby($voucheraccount, true, ($is_person) ? $id: null);
                } 


                foreach ($expenses_entries as $expenses_entry) { 
                    $expense_transactions = ExpensesTransaction::findOrFail($expense_id->id);
                    $expense_transactions->organization_id = $organization_id; 
                    $expense_transactions->status = 1;
                    $expense_transactions->tax_id = $expenses_entry['tax_id'];
                    $expense_transactions->amount = $expenses_entry['amount'];
                    $expense_transactions->reference_ledger_id = $expenses_entry['reference_ledger_id'];
                    $expense_transactions->entry_id = $expenses_entry['entry_id'];
                    $expense_transactions->expense_type = 2;
                    Custom::userby($expense_transactions, true, ($is_person) ? $id: null);
                    $expense_transactions->save();
                }   
            } 
        }

        $voucher_no = AccountEntry::select('voucher_no')->where('organization_id',$organization_id)->where('id',$account_id)->first();
   

        $debit_ledger = AccountLedger::select('display_name')->where('organization_id',$organization_id)->where('id',$voucheraccount->debit_ledger_id)->first();
    

        $credit_ledger = AccountLedger::select('display_name')->where('organization_id',$organization_id)->where('id',$voucheraccount->credit_ledger_id)->first();

        $expenses_id = ExpensesTransaction::select('expenses_transactions.id as expenses_id','expenses_transactions.reference_account_entry_id as entry_id')->where('organization_id',$organization_id)->where('entry_id',$account_id)->first();
       
        return response()->json(['status' => 1, 'message' => 'Expense'.config('constants.flash.added'), 'data' => ['id' => $account_id,'date'=>$accountentries->date,'voucher_no'=>$voucher_no->voucher_no,'from_account'=>$credit_ledger->display_name,'to_account'=>$debit_ledger->display_name,'amount'=>$voucheraccount->amount,'expensese_id'=>$expenses_id->expenses_id,'entry_id'=>$expenses_id->entry_id,'expense_voucher_no' => $expenses_voucher_name->voucher_no]]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $organization_id = Session::get('organization_id');

        $expense_transactions = ExpensesTransaction::findOrFail($request->input('expensese_id'));
        $expense_transactions->delete();

        $account_entry = AccountEntry::findOrFail($request->input('id'));
        $account_entry->delete();

        $purchases_entry = AccountEntry::findOrFail($request->input('entry_id'));
        $purchases_entry->delete();

       
        return response()->json(['status' => 1, 'message' => 'Transaction'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function get_address(Request $request)
    {
        $organization_id = Session::get('organization_id');

        $person_type = $request->input('type');

        $supplier_id = $request->input('supplier_id');

        $selected_people_detail = People::select('business_id','person_id','user_type','people_addresses.address','cities.name AS city','states.name AS state')
        ->leftjoin('people_addresses','people_addresses.people_id','=','people.id')
        ->leftjoin('cities','cities.id','=','people_addresses.city_id')
        ->leftjoin('states','states.id','=','cities.state_id')
        ->where('people.organization_id',$organization_id)
        ->where('people.user_type',$person_type)
        ->where('people.person_id',$supplier_id)
        ->orWhere('people.business_id',$supplier_id)
        ->first();

        return response()->json(['status' => 1, 'data' => $selected_people_detail]);

    }

    public function search_result(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $from_account = AccountGroup::select('account_ledgers.display_name','account_ledgers.id','account_groups.display_name AS group_name','account_groups.id AS group_id');
        $from_account->leftjoin('account_ledgers','account_ledgers.group_id','=','account_groups.id');
        $from_account->where(function ($query){
                    $query->where('account_groups.name', '=','cash')
                        ->orWhere('account_groups.name', '=','bank_account');
        });
        $from_account->where('account_groups.organization_id',$organization_id);
        $from_accounts = $from_account->get(); 

        $from = array();
        foreach ($from_accounts as $value) {
           $from[] = $value->display_name;
        }

        $peoples = People::select('person_id AS id','display_name', 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $businesses = People::select('business_id AS id', 'display_name', 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $get_people_ledgers = $peoples->get();
        $get_business_ledgers = $businesses->get();

        $people_ledger = array();
        $business_ledger = array();

        foreach ($get_people_ledgers as $value) {
           $people_ledger[] = $value->display_name;
        }

        foreach ($get_business_ledgers as $value) {
           $business_ledger[] = $value->display_name;
        }

        $suppliers = array_merge($people_ledger,$business_ledger);
        $people_ledger = array_filter($suppliers);
        
        $table_value = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','credit_account.display_name AS from_account','account_ledgers.display_name AS to_account','account_transactions.amount','expenses_transactions.id as expenses_id','expenses_transactions.reference_account_entry_id as entry_id',DB::raw('(SELECT ec_ent.voucher_no AS vou FROM account_entries AS ec_ent
  LEFT JOIN expenses_transactions AS ex_tra ON ex_tra.reference_account_entry_id = ec_ent.id 
  WHERE ex_tra.reference_account_entry_id= expenses_transactions.reference_account_entry_id) AS expense_voucher'));
        $table_value->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $table_value->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $table_value->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
        $table_value->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id');
        $table_value->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_entries.id');
        $table_value->where('account_entries.organization_id',$organization_id);
        $table_value->where('account_vouchers.name','=','payment');
        $table_value->whereIn('credit_account.name',$from);
        $table_value->whereIn('account_ledgers.name',$people_ledger);
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
       

        return response()->json(['status' => 1, 'data' => $search_result]);
    }
}
