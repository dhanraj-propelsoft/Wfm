<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountVoucherType;
use App\AccountVoucher;
use App\ReferenceVoucher;
use App\Transaction;
use App\AccountLedger;
use App\CustomerGroping;
use App\VehicleJobcardStatus;
use Carbon\Carbon;
use App\PeopleTitle;
use App\PaymentMode;
use App\People;
use App\Country;
use App\State;
use App\Term;
use DB;

use Session;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');

       /* $today = Carbon::today()->format('d-m-Y');
        $firstDay = new Carbon('first day of last month'); 
        $firstDay_only=$firstDay->format('d-m-Y');
        $from_date = $firstDay->format('Y-m-d');
        $to_date = Carbon::today()->format('Y-m-d');*/

        $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');
        $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
        $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
        $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
        $transaction_types->where('account_vouchers.organization_id', $organization_id);
        if(Session::get('module_name') != null) {
            $transaction_types->where('modules.name', Session::get('module_name'));
        }
        
        $transaction_types->where('account_vouchers.name', 'job_card');

        $transaction_type = $transaction_types->first();

        $estimation_type = AccountVoucher::where('name','job_request')->where('organization_id', $organization_id)->first();

        $reference_id = $estimation_type->id;

        if($transaction_type == null) abort(404);

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

        $cash_voucher = 0;

        $return_voucher = 0;

        $reference_type = ReferenceVoucher::where('name', 'purchases')->first()->id;

        if($transaction_type->module == "trade_wms")
        {
        $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

        }

        $transaction = Transaction::select('transactions.id', 'transactions.order_no','transactions.reference_id','transactions.approved_on','vehicle_register_details.id as vehicle_id',DB::raw('sum( (CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as jobcard_total'),
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"),'transactions.date as original_date', 'transactions.due_date as original_due_date','transactions.total', 
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance"),  
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"),  'transactions.approval_status', 'transactions.transaction_type_id',  
            DB::raw("IF(people.display_name IS NULL, SUBSTRING(business.display_name,1,8), SUBSTRING(people.display_name,1,11)) as customer"),
            DB::raw("IF(people.display_name IS NULL, business.display_name, CONCAT(people.first_name, ' ', COALESCE(people.last_name))) as customer_contact"),
            DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"),
             DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"),
            DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.name as jobcard_status','vehicle_jobcard_statuses.id as jobcard_status_id','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.advance_amount','reference_estimation.order_no as estimation','reference_estimation.id as estimation_id');
        

        $transaction->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
        $transaction->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });
        

        $transaction->leftjoin('transactions AS reference_transactions','transactions.reference_id','=','reference_transactions.id');


        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        $transaction->leftJoin('transactions as reference_estimation', function($join) use($reference_id) 
            {
                $join->on('reference_estimation.reference_id','=', 'transactions.id')
                ->where('reference_estimation.transaction_type_id', $reference_id);
            });

        $transaction->where('transactions.organization_id', $organization_id);
        $transaction->where('transactions.transaction_type_id', $transaction_type->id);
        $transaction->whereNull('transactions.deleted_at');
        $transaction->where('transactions.notification_status','!=',2);
        $transaction->where('vehicle_jobcard_statuses.id','!=',8);
        $transaction->groupby('transactions.id');
        $transaction->orderBy('transactions.updated_at','desc');
        $transactions = $transaction->paginate(20);

        

        $job_card_status = VehicleJobcardStatus::where('status', '1')->select('name', 'id')->get();

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');
        
        
         $ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group');
        $ledger->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id');
        $ledger->whereIn('account_groups.name', ['cash']);
        $ledger->where('account_ledgers.organization_id', $organization_id);
        $ledger->where('account_ledgers.approval_status', '1');
        $ledger->where('account_ledgers.status', '1');
        $ledger->orderby('account_ledgers.id','asc');

        $ledgers = $ledger->pluck('name', 'id');


        return view('trade_wms.home_page',compact('transactions','job_card_status','title','group_name','state','terms','payment','ledgers'));
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

    public function advace_amount(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $type = $request->input('type');
        $transaction_id = $request->input('id');
        $name= $request->input('name');
         
        $transaction_type = AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        $jobcard_id = AccountVoucher::where('name', 'job_card')->where('organization_id', $organization_id)->first()->id;

        //dd($jobcard_id);
        $job_card = Transaction::leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->where('transaction_type_id', $jobcard_id)->where('transactions.organization_id', $organization_id)->orderby('id','DESC')->pluck('transactions.order_no', 'transactions.id');
        $job_card->prepend('Select Job Card', ''); 

        $selected_job_card =  Transaction::select('transactions.order_no', 'transactions.id')->where('transactions.organization_id', $organization_id)->where('transactions.id', $transaction_id)->first();


        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');
       

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

        if($transaction_type == null) {
            abort(404);
        }

        if($type == "payment") {
            $transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first();
            $user = "Vendor";
            $account_type = "Bill";
            $title1 = "Payables";
        } else if($type == "receipt") {
            $transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first();
            //dd($transaction_id);
            $user = "Customer";
            $account_type = "Invoice";
            $title1 = "Receivables";
        }
        else if($type == "wms_receipt") {
            $transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first();
            //dd($transaction_id);
            $user = "WMS Customer";
            $account_type = "WMS Invoice";
            $title1 = "WMS Receivables";
        }
        
        
        $payment = PaymentMode::where('status', 1)->select('display_name','id')->get();
        

        

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

        $ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
        ->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
        ->whereIn('account_groups.name', ['cash'])
        ->where('account_ledgers.organization_id', $organization_id)
        ->where('account_ledgers.approval_status', '1')
        ->where('account_ledgers.status', '1')
        ->orderby('account_ledgers.id','asc')
        ->get();

        $people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
        ->where('user_type', 0)
        ->where('organization_id', $organization_id)->get();

        $business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
        ->where('user_type', 1)
        ->where('organization_id', $organization_id)->get();

        $customer_label = null;
        $ledger_label = null;
        $amount = null;
        $date = null;
        $payment_method = null;
        $reference_type = null;

        switch($type) {
            case 'receipt':
                $customer_label = "Customer";
                $ledger_label = "Deposit To";
                $amount = "Received Amount";
                $date = "Date";
                $payment_method = "Mode of Payment";
                $reference_type = "DN";
            break;
            case 'payment':
                $customer_label = "Vendor";
                $ledger_label = "Pay From";
                $amount = "Payment";
                $date = "Payment Date";
                $payment_method = "Payment Method";
                $reference_type = "GRN";
            break;
            case 'wms_receipt':
                $customer_label = "WMS Customer";
                $ledger_label = "Deposit To";
                $amount = "Received Amount";
                $date = "Date";
                $payment_method = "Mode of WMS Payment";
                $reference_type = "DN";
            break;
        }

        $job_id = $request->id;
        //dd($$job_id);
        $job_name = Transaction::select('transactions.id','order_no','user_type','total')->where('id',$job_id)->first();
        //dd($job_name);
        $organization_id = session::get('organization_id');
        if($job_name->user_type == 0)
        {
            $cus_name = Transaction::select('transactions.id','transactions.total','people.display_name','people.person_id','transactions.user_type')
            ->leftjoin('people','people.person_id','=','transactions.people_id')
            ->where('transactions.order_no',$job_name->order_no)
            ->where('transactions.organization_id',$organization_id)
            ->whereNull('transactions.deleted_at')->first();
            //dd($query);
        }
        if($job_name->user_type == 1)
        {
            $cus_name = Transaction::select('transactions.id','transactions.total','people.display_name','people.business_id','transactions.user_type')
            ->leftjoin('people','people.business_id','=','transactions.people_id')
            ->where('transactions.order_no',$job_name->order_no)
            ->where('transactions.organization_id',$organization_id)
            ->whereNull('transactions.deleted_at')->first();
            //dd($query);
        }


        return response()->json(['transaction_type' =>$transaction_type, 'payment'=> $payment, 'type'=>$type, 'user'=>$user, 'account_type'=>$account_type, 'title1'=>$title1, 'ledgers'=>$ledgers, 'people'=>$people, 'business'=>$business, 'customer_label'=>$customer_label, 'ledger_label'=>$ledger_label, 'amount'=>$amount, 'date'=>$date, 'payment_method'=>$payment_method, 'transaction_id'=>$transaction_id, 'reference_type'=>$reference_type,'title'=>$title, 'state'=> $state, 'terms'=>$terms ,'job_card' => $job_card,'group_name'=> $group_name, 'selected_job_card'=>$selected_job_card,'name'=>$cus_name,'type'=>$name]);
        
    }
}
