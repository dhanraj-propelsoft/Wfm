<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\BusinessCommunicationAddress;
use App\WmsTransactionComplaintService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Notification\Service\SmsLedgerService;
use App\VehicleMaintenanceReading;
use App\Jobs\SendTransactionEmail;
use App\GlobalItemCategoryType;
use App\WmsTransactionReading;
use App\VehicleRegisterDetail;
use App\TransactionFieldValue;
use App\TransactionRecurring;
use App\AccountFinancialYear;
use App\BusinessAddressType;
use App\InventoryAdjustment;
use App\InventoryItemGroup;
use App\InventoryItemStock;
use App\AccountVoucherType;
use App\GlobalItemCategory;
use App\AccountLedgerType;
use App\VehicleDrivetrain;
use App\InventoryCategory;
use App\WmsReadingFactor;
use App\TransactionField;
use App\ReferenceVoucher;
use App\VehicleFuelType;
use App\TransactionItem;
use App\GlobalItemModel;
use App\VehicleTyreSize;
use App\VehicleTyreType;
use App\VehicleBodyType;
use App\VehicleSpecification;
use App\VehicleSpecificationDetails;
use App\RegisteredVehicleSpec;
use App\VehicleCategory;
use App\VehicleVariant;
use App\AccountVoucher;
use App\VehicleRimType;
use App\WmsTransaction;
use App\WmsAttachment;
use App\InventoryItem;
use App\AccountLedger;
use App\PeopleAddress;
use App\AccountEntry;
use App\Organization;
use App\AccountGroup;
use App\ShipmentMode;
use App\Jobs\SendSms;
use App\VehicleModel;
use App\VehicleWheel;
use App\VehicleUsage;
use App\PeopleTitle;
use App\FieldFormat;
use App\HrmEmployee;
use App\Transaction;
use App\VehicleMake;
use App\PaymentMode;
use App\MultiTemplate;
use App\HrmShift;
use App\ServiceType;
use App\VehicleJobcardStatus;
use App\FieldType;
use App\VehicleServiceType;
use App\VehicleChecklist;
use App\WmsChecklist;
use App\VehicleJobItemStatus;
use App\PaymentTerm;
use App\VehicleSpecMaster;
use App\VehicleSegmentDetail;
use App\CustomerGroping;
use Carbon\Carbon;
use App\Discount;
use App\TaxGroup;
use App\Business;
use App\Weekday;
use App\TaxType;
use App\JobType;
use App\Country;
use App\People;
use App\Person;
use App\Custom;
use App\State;
use App\Term;
use App\Unit;
use App\City;
use App\Tax;
use App\FsmPump;
use App\FsmShiftCashManage;
use App\WmsPriceList;
use App\AccountPersonType;
use App\InventoryItemBatch;
use Session;
use App\SmsTemplate;
use App\PrintTemplate;
use Mail;
use Auth;
use DB;
use PDF;
use DateTime;
use Illuminate\Support\Str;
use File;
use Validator;
use Storage;
use Illuminate\Support\Facades\Log;
use App\InventoryItemStockLedger;
use App\OrgCustomValue;
use App\PaymentMethod;



class TransactionController extends Controller
{
    public function __construct(SmsLedgerService $smsLedgerService)
    {
        
        $this->smsLedgerService = $smsLedgerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type,Request $request)
    {
        Log::info("TransactionController->index :- Inside  type - ".$type);

        $session_put_type = $request->session()->put('transaction-type', $type);
        $session_get_type = $request->session()->get('transaction-type');

        $organization_id = Session::get('organization_id');
        $module_name =  Session::get('module_name');

        if($session_get_type){

            $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');
            $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
            $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
            $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
            $transaction_types->where('account_vouchers.organization_id', $organization_id);
            /*if(Session::get('module_name') != null) {
                $transaction_types->where('modules.name', Session::get('module_name'));
            }*/     
            $transaction_types->where('account_vouchers.name', $type);
            $transaction_type = $transaction_types->first();
        }

        if ($transaction_type == null)
            abort(404);

        //AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        $reference_type = ReferenceVoucher::where('name', 'purchases')->first()->id;

        
        if($transaction_type->module == "trade" || $transaction_type->module == "inventory" )
        {
        $transaction_sales = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

        }

        if($transaction_type->module == "trade_wms" || $transaction_type->module == "fuel_station")
        {
        $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

        }   


        if($type == "purchases") {
            $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
        } 
        else if($type == "sales" || $type == "sales_cash") {
            $cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        else if($type == "job_invoice" || $type == "job_invoice_cash") {
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        
        

        $payment_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;

        if($transaction_type->module != "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
        }

        if($transaction_type->module == "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
        }

        $today = Carbon::today()->format('d-m-Y');
        $firstDay = new Carbon('first day of last month'); 
        $firstDay_only=$firstDay->format('d-m-Y');
        //dd($firstDay_only);
        $from_date = $firstDay->format('Y-m-d');
        $to_date = Carbon::today()->format('Y-m-d');
        
        $from_date_trade_wms =Carbon::today()->subDays( 30 )->format('Y-m-d');
        $to_date_trade_wms = Carbon::today()->format('Y-m-d');

        $firstDay_only_trade_wms =Carbon::today()->subDays( 30 )->format('d-m-Y');
		//get originated_from_id value from transaction table to navigate to jobcard edit from estimation list page
        $transaction = Transaction::select(DB::raw('COUNT(transactions.id)'),'transactions.id','transactions.originated_from_id','referenced_in.order_no as jc_order_no','transactions.order_no','transactions.reference_id','transactions.approved_on','vehicle_register_details.id as vehicle_id',DB::raw('sum( (CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as jobcard_total'),
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"),'transactions.date as original_date', 'transactions.due_date as original_due_date','transactions.total', 
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance"),  
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"),  'transactions.approval_status', 'transactions.transaction_type_id',  
            DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),
            DB::raw("IF(people.display_name IS NULL, business.display_name, CONCAT(people.first_name, ' ', COALESCE(people.last_name))) as customer_contact"),
            DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"),
			DB::raw("CASE WHEN transactions.originated_from_id THEN '' ELSE COALESCE(transactions.reference_no, '')  END AS estimate_reference_no"),
             DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"),
            DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.id as job_card_status_id','vehicle_jobcard_statuses.name as jobcard_status','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.advance_amount');
        

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

		$transaction->leftjoin('job_cards AS referenced_in', 'transactions.originated_from_id', '=', 'referenced_in.id');
		
        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        $transaction->where('transactions.organization_id', $organization_id);

        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {
            
            $transaction->where(function ($query) use ($transaction_sales, $transaction_cash) {
                $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                      ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
        });

        } 
        else {
            $transaction->where('transactions.transaction_type_id', $transaction_type->id);
        }

        $transaction->whereNull('transactions.deleted_at');
        $transaction->where('transactions.notification_status','!=',2);
        
        if($transaction_type->module == "trade_wms")
        {
            if(!empty($from_date_trade_wms) && !empty($to_date_trade_wms))
            {
            $transaction->whereBetween('wms_transactions.job_date',[$from_date_trade_wms,$to_date_trade_wms]);
            }
        }
        if($transaction_type->module == "inventory" || $transaction_type->module == "trade")
        {
        
            if(!empty($from_date) && !empty($to_date))
            {
            $transaction->whereBetween('transactions.date',[$from_date,$to_date]);
            }
        
        }
        $transaction->groupby('transactions.id');
        $transaction->orderBy('transactions.updated_at','desc');
        $transactions = $transaction->get();
        //dd($transactions);

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

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
        $ledger->whereIn('account_groups.name', ['cash','bank_account']);
        $ledger->where('account_ledgers.organization_id', $organization_id);
        $ledger->where('account_ledgers.approval_status', '1');
        $ledger->where('account_ledgers.status', '1');
        $ledger->orderby('account_ledgers.id','asc');

        
        $ledgers = $ledger->pluck('name', 'id'); 


             
        Log::info("TransactionController->index :- return  $type - ".$type);
 
        return view('inventory.transaction', compact('transactions', 'transaction_type', 'type', 'state', 'title', 'payment', 'terms','group_name','firstDay_only','today','city','ledgers','firstDay_only_trade_wms','from_date_trade_wms','to_date'));
    }

    public function transaction_limitation(Request $request)
    {
        Log::info("TransactionController->transaction_limitation :- Inside");

        $organization_id = Session::get('organization_id');     

        $transaction_limitation = Custom::remaining_transaction();

        $transaction_revenue = Custom::remaining_revenue();

        $plan_limitation = Custom::plan_limitation();

        /* transaction sum of amount - check total revenue 1000000 */ 

        /*$account_voucher_type = AccountVoucher::whereIn('name', ['sales','sales_cash','job_invoice','job_invoice_cash'])
        ->where('organization_id', $organization_id)
        ->orderby('id')->pluck('id');
        
        $total_limit = Transaction::select(DB::raw('SUM(transactions.total) AS sum_total'))
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
        
        ->whereNull('transactions.deleted_at')
        ->where('transactions.approval_status','=',1)
        ->where('transactions.organization_id',$organization_id)
        ->whereIn('transactions.transaction_type_id',$account_voucher_type)
        ->first();*/    

        /*End*/ 
        Log::info("TransactionController->transaction_limitation :- Return");

        return response()->json(['transaction_limitation' => $transaction_limitation, 'transaction_revenue' => $transaction_revenue,'plan_limitation' => $plan_limitation]);
    }
    public function sms_limitation()
    {   
        Log::info("TransactionController->sms_limitation :- Inside");
        $smsLedger = $this->smsLedgerService->findSmsLedgerRemaining();
       
        $sms_limitation = Custom::remaining_sms();
        Log::info("TransactionController->sms_limitation :- Return");

        return response()->json(['sms_limitation' => $sms_limitation,'smsLedger'=>$smsLedger]);
    }

    public function promotion_sms_limitation()
    {   
        Log::info("TransactionController->promotion_sms_limitation :- Inside");
        $promotion_sms_limitation = Custom::remaining_promotion_sms();
        Log::info("TransactionController->promotion_sms_limitation :- Return");
        
        return response()->json(['promotion_sms_limitation' => $promotion_sms_limitation]);
    }

    public function ledger_limitation()
    {   
        Log::info("TransactionController->ledger_limitation :- Inside");
        $ledger_limitation = Custom::remaining_ledger();        
        Log::info("TransactionController->ledger_limitation :- Return");

        return response()->json(['ledger_limitation' => $ledger_limitation]);
    }

    public function recurring_transaction($type)
    {
        Log::info("TransactionController->recurring_transaction :- Inside type ".$type);
        if($type != "sales") abort(404);

        $transactions = [];

        $organization_id = Session::get('organization_id');

        $transaction_type = AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        $recurrences = TransactionRecurring::select('transaction_recurrings.*', 'weekdays.name as week_day')
        ->leftjoin('transactions', 'transactions.id', '=', 'transaction_recurrings.id')
        ->leftjoin('weekdays', 'weekdays.id', '=', 'transaction_recurrings.week_day_id')
        ->get();
        $repeating_number = 0;
        $week_day = null;

        
        foreach($recurrences as $recurrence) {

            switch ($recurrence->week_day) {
            case 'sunday':
                $week_day = Carbon::SUNDAY;
                break;
            case 'monday':
                $week_day = Carbon::MONDAY;
                break;
            case 'tuesday':
                $week_day = Carbon::TUESDAY;
                break;
            case 'wednesday':
                $week_day = Carbon::WEDNESDAY;
                break;
            case 'thursday':
                $week_day = Carbon::THURSDAY;
                break;
            case 'friday':
                $week_day = Carbon::FRIDAY;
                break;
            case 'saturday':
                $week_day = Carbon::SATURDAY;
                break;
        }



            $start_date = $recurrence->start_date;

            if($recurrence->end_date == null && $recurrence->end_occurrence == null) {

                $end_date = date('Y-m-d');

            } else if($recurrence->end_date != null) {

                $end_date = $recurrence->end_date;

            } else if($recurrence->end_occurrence != null) {
                $end_occurrence = $recurrence->end_occurrence;

                if($recurrence->interval == 0) {
                    $end_date = Carbon::parse($start_date)->addDays($recurrence->end_occurrence*$recurrence->frequency);



                } else if($recurrence->interval == 1) {
                    $end_date = Carbon::parse($start_date)->addWeeks(($recurrence->end_occurrence*$recurrence->frequency) - 1);

                    
                } else if($recurrence->interval == 2) {
                    if($recurrence->day != 0) {
                        $startDate = Carbon::parse(Carbon::parse($start_date)->format('Y-m-'.$recurrence->day));
                        $end_date = $startDate->addMonths(($recurrence->end_occurrence*$recurrence->frequency) - 1);

                        
                    } else {
                        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
                        $end_date = $startDate->addMonths(($recurrence->end_occurrence*$recurrence->frequency) - 1);

                    }
                }
                    
            }

                if($recurrence->interval == 0) {
                    $difference = Custom::time_difference($end_date, $start_date, 'd');

                    $repeating_number = ($difference>0) ? (($difference)/$recurrence->frequency) + 1 : 0;
                }
                else if($recurrence->interval == 1) {
                    $days = [];
                    $startDate = Carbon::parse($start_date)->subDays(1)->next($week_day);
                    $endDate = Carbon::parse($end_date);

                    for ($date = $startDate; $date->lte($endDate); $date->addWeeks($recurrence->frequency)) {
                        $days[] = $date->format('Y-m-d');
                    }
                    $repeating_number = (int)floor(count($days)/$recurrence->frequency);

                   
                }

                else if($recurrence->interval == 2) {
                    if($recurrence->period == '') {
                        $endDate = Carbon::parse($end_date);
                        $days = [];
                        if($recurrence->day != 0) {
                            $startDate = Carbon::parse(Carbon::parse($start_date)->format('Y-m-'.$recurrence->day));
                                for ($date = $startDate; $date->lte($endDate); $date->addMonths($recurrence->frequency)) {
                                $days[] = $date->format('Y-m-d');
                                }
                        } 
                        else {
                            $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
                                for ($date = $startDate; $date->lte($endDate); $date->addMonths($recurrence->frequency)) {
                                    $d = clone $date;
                                    $days[] = $d->endOfMonth()->format('Y-m-d');
                                }
                        }

                        $repeating_number = (int)floor(count($days)/$recurrence->frequency);
                    }

                    else if($recurrence->period != '' && $recurrence->period != 0) {
                        $days = [];
                        $startDate = Carbon::parse($start_date)->subDays(1)->next($week_day);
                        $endDate = Carbon::parse($end_date);

                        for ($date = $startDate; $date->lte($endDate); $date->addMonths($recurrence->frequency)) {
                            $days[] = Carbon::createFromFormat('Y-m-d', $date->format('Y-m-d'))->nthOfMonth($recurrence->period, $week_day)->format('Y-m-d');
                        }

                        $repeating_number = (int)floor(count($days)/$recurrence->frequency);
                    } else if($recurrence->period == 0) {
                        $days = [];
                        $startDate = Carbon::parse($start_date)->subDays(1)->next($week_day);
                        $endDate = Carbon::parse($end_date);

                        for ($date = $startDate; $date->lte($endDate); $date->addMonths($recurrence->frequency)) {
                            $days[] = $date->endOfMonth()->format('Y-m-d');
                        }

                        $repeating_number = (int)floor(count($days)/$recurrence->frequency);
                    }


                }

                
                $transaction_values = DB::select("SELECT transactions.id, transactions.order_no, transactions.date, 
                    transactions.due_date, terms.days,
            transactions.total,
            IF((transactions.total - SUM(cash_transactions.total)) = 0, '', transactions.total - SUM(cash_transactions.total))  AS balance,
              CASE 
              WHEN (transactions.total - cash_transactions.total) = 0  THEN 1  
              WHEN transactions.due_date < CURDATE()  THEN 3 
              WHEN (transactions.total - cash_transactions.total) > 0  THEN 2
              ELSE 0 
              END AS status, 
              transactions.approval_status,
            transactions.transaction_type_id, IF(people.display_name IS NULL, business.display_name, people.display_name) as customer from transactions 
            left join terms on terms.id = transactions.term_id
            left join people on (people.person_id = transactions.people_id and transactions.user_type = 0)
            left join people as business on (business.business_id = transactions.people_id and transactions.user_type = 1)
            LEFT JOIN transactions AS cash_transactions ON cash_transactions.reference_id = transactions.id
            where transactions.organization_id = $organization_id and transactions.transaction_type_id = $transaction_type->id and transactions.id = $recurrence->id group by transactions.id")[0];

            for($i=0; $i<$repeating_number; $i++) {

                $transactions[] = ["id" => $transaction_values->id,
                                "order_no" => $transaction_values->order_no,
                                "date" => $days[$i],
                                "due_date" => Carbon::parse($days[$i])->addDays($transaction_values->days)->format('Y-m-d'),
                                "original_date" => $days[$i],
                                "original_due_date" => Carbon::parse($days[$i])->addDays($transaction_values->days)->format('Y-m-d'),
                                "customer" => $transaction_values->customer,
                                "total" => $transaction_values->total,
                                "status" => $transaction_values->status,
                                "type" => $type,
                                 ];

            }

        }


        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');
        $group_name->prepend('Select Group Name', '');

        Log::info("TransactionController->recurring_transaction :- return ".$type);

        //return $transactions;
        return view('inventory.recurring_transaction', compact('transactions', 'transaction_type', 'type', 'state', 'title', 'payment', 'terms','group_name'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        Log::info("TransactionController->create :- Inside ".$type);
        //  (string) Str::uuid();       
        // Unoversal Id With * digit for attachment update
        //  dd(Session::get('module_name'));
        
        $uuid=Custom::GUID();
        
        $organization_id = Session::get('organization_id');

        $now = Carbon::now();
        $current_date =  $now->format('Y-m-d H:i:s');
        $add_date = date("Y-m-d H:i:s", strtotime("+1 hours"));

        $job_item_status = 1;

        $tomorrow = Carbon::tomorrow();
        $tomorrow_date =  $tomorrow->format('d-m-Y');

        //dd($tomorrow_date);


        //$transaction = Transaction::findOrFail($request->input('id'));

        //$approvel_status =$transaction->approval_status;
        //$approved_date = $transaction->approved_on;
        
        //dd($current_date);

        $job_item_status = VehicleJobItemStatus::where('status', '1')->pluck('name', 'id');
        $job_item_status->prepend('Select Status', '');

        $item_status = VehicleJobItemStatus::where('name','Open')->first()->id;

        $vehicle_sevice_type = ServiceType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_sevice_type->prepend('Select Service type', '');

        $sevice_type = ServiceType::where('name','Paid Service')->first()->id;

        $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
        $job_card_status->prepend('Select Jobcard Status', '');

        $job_status = VehicleJobcardStatus::where('name', 'New')->first()->id;

        $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');

        $vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');
        $vehicle_model_id->prepend('Select Vehicle Model', '');

        $vehicle_tyre_size = VehicleTyreSize::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_tyre_size->prepend('Select Tyre Size', '');

        $vehicle_tyre_type = VehicleTyreType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_tyre_type->prepend('Select Tyre Type', '');

        $vehicle_variant = VehicleVariant::orderBy('name')->pluck('name', 'id');
        $vehicle_variant->prepend('Select Vehicle Variant', '');

        $vehicle_wheel = VehicleWheel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_wheel->prepend('Select Vehicle Wheel', '');

        $fuel_type = VehicleFuelType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $fuel_type->prepend('Select Fuel Type', '');

        $rim_type = VehicleRimType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $rim_type->prepend('Select Rim Type', '');

        $body_type = VehicleBodyType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $body_type->prepend('Select Body Type', '');

        $vehicle_category = VehicleCategory::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $vehicle_category->prepend('Select Vehicle Category', '');

        $vehicle_drivetrain = VehicleDrivetrain::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_drivetrain->prepend('Select Vehicle Drivetrain', '');

        $service_type = ServiceType::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $service_type->prepend('Select Service Type', '');

        $vehicle_usage = VehicleUsage::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_usage->prepend('Select Vehicle Usage', '');

        $maintanance_reading = VehicleMaintenanceReading::where('status', '1')->pluck('name', 'id');
        $maintanance_reading->prepend('Select Maintenance Reading', '');

       /* $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');*/

        $vehicles_register = VehicleRegisterDetail::leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id')
         ->where('wms_vehicle_organizations.organization_id', $organization_id)->pluck('registration_no', 'vehicle_register_details.id');
        $vehicles_register->prepend('Select Vehicle', '');        

        $vehicle_check_list=VehicleChecklist::select('name','display_name','id')->where('status', '1')->get();

        $reading_factor = WmsReadingFactor::select('wms_reading_factors.id AS reading_factor_id', 'wms_reading_factors.name AS reading_factor_name', 'wms_applicable_divisions.id AS wms_division_id', 'wms_applicable_divisions.division_name')
        ->leftJoin('wms_applicable_divisions', 'wms_applicable_divisions.id','=','wms_reading_factors.wms_division_id')
            ->where('wms_reading_factors.organization_id', $organization_id)->get();

        //$reference_vouchers = ReferenceVoucher::select('display_name', 'name');

        $person_id = Auth::user()->person_id;

        $employee = HrmEmployee::select('hrm_employees.id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->where('hrm_employees.person_id', $person_id)
        ->first();

        $selected_employee = ($employee != null) ? $employee->id : null;
        

        $purchase_employee = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;

        //$selected_reference_voucher = null;
       
        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $busi=People::select(DB::raw('(CASE WHEN person_id is NULL THEN business_id ELSE person_id END) AS id'), DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
               ->where('organization_id', $organization_id)
               ->where(function($query)
               {
                       $query->where('user_type', 0)
                       ->orWhere('user_type', 1);
               })
               ->orderByRaw('name');
        $bus = $busi->get();

        $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        ->where('account_vouchers.organization_id', $organization_id)
        ->where('modules.name', Session::get('module_name'))
        ->where('account_vouchers.name', $type)
        ->first();
        
        //Session::get('module_name')

        //AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        //dd($transaction_type);

        if($transaction_type == null) {
            return null;
        }

Log::info("TransactionController->create :- Custom::getLastGenNumber - ".$transaction_type->id. ' -- '.$organization_id);
        $getGen_no=Custom::getLastGenNumber( $transaction_type->id, $organization_id );
Log::info("TransactionController->create :- after Custom::getLastGenNumber - ".$getGen_no);
        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
        Log::info("TransactionController->create :- after Custom::getLastGenNumber - ".$vou_restart_value);
        
        
          //dd($vou_restart_value);
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }
         


        //$previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();       
        
        /*$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;
        if($previous_entry!=null)
        {
            if($previous_entry->gen_no){

                $gen_no= $previous_entry->gen_no + 1;

            }else{
            if($previous_entry->order_no){
            
                $order_no=$previous_entry->order_no;
                
                $dum_gen_no='~';
                
                $dum_order_no=Custom::generate_accounts_number($transaction_type->name, $dum_gen_no, false);
                
                $ex_gen_no=Custom::get_string_diff($order_no,$dum_order_no);
            

                DB::table('transactions')->where('id',$previous_entry->id)->update(['gen_no'=> $ex_gen_no]);
            
                $gen_no=$ex_gen_no+1;
            }

            }
            
        }else{
            $gen_no=$transaction_type->starting_value;
        }*/ 

        $voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);
    
        $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

        $account_ledgers = AccountLedger::where('group_id', $sale_account)->where('organization_id', $organization_id)->pluck('name', 'id');
        $account_ledgers->prepend('Select Account', '');

        $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
        $employees->prepend('Select Employee', '');


        $shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipment_mode->prepend('Select Shipment Mode', '');

        $delivery_method = ShipmentMode::where('name','General Shipment')->first()->id;


        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')

        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')   
        
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', 1)
        ->orderby('global_item_categories.display_name')
        ->get();


        /*$items = InventoryItem::where('status', '1')->pluck('name','id');
        $items->prepend('Select Item ','');*/

    
        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();

        //dd($taxes);

        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('status', 1)->where('organization_id', $organization_id);

        $discounts = $discount->get();

        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }
        $days[0] = "Last";

        if($transaction_type == null) abort(404);

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $payment_method = PaymentMode::where('name', 'Cash')->first()->id;

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;


        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $selected_term = Term::where('organization_id', $organization_id)->where('name', 'on_receipt')->first();

        $make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $make->prepend('Select Make', '');

        //$job_type = JobType::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');

        $address_type = BusinessAddressType::where('name', 'business')->first();

        $business_id = Organization::find($organization_id)->business_id;

        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        ->leftjoin('states', 'cities.state_id', '=', 'states.id')
        ->where('address_type', $address_type->id)
        ->where('business_id', $business_id)
        ->first();
        

        $date_label = null;
        $due_date_label = null;
        $term_label = null;
        $order_type = null;
        $address_label = null;
        $order_type_value = [];
        $order_label = null;
        $payment_label = null;
        $sales_person_label = null;
        $include_tax_label = null;
        $customer_type_label = null;
        $customer_label = null;
        $discount_option = false;
        $person_type = null;
        $due_date = null;
        $transaction_address_type = null;
        $company_label = false;
        $company_name = null;
        $company_email = null;
        $company_mobile = null;
        $company_address = null;
        $service_type_label = null;

        if($business_communication_address != "") {
            $business_company_address = $business_communication_address->address;
        
            if($business_communication_address->address != "" && $business_communication_address->city != "") {
                $business_company_address .= "\n";
            }
    
            $business_company_address .= $business_communication_address->city;
    
            if($business_communication_address->city != "" && $business_communication_address->state != "") {
                $business_company_address .= "\n";
            }
    
            $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;
        }   

        switch($type) {
            case 'estimation':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();

                //$order_type_value = AccountVoucher::whereIn('name', array('Direct'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                //$order_type_value->prepend('Direct', '');
                $payment_label = 'Payment Method';
                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sale_order':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sales':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sales_cash':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'delivery_note':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sale_order', 'sales','sales_cash'))->where('status', 1)->orderby('name', 'desc')->get();
                $order_label = 'Order#';

                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'sales', 'sales_cash','job_invoice','job_invoice_cash'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'receipt':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'payment':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                
                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_label = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'credit_note':
                $address_label = 'Customer Address';
                $order_type = "Order Type";
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales', 'delivery_note'))->where('status', 1)->orderby('id')->get();

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales', 'sales_cash', 'delivery_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'purchase_order':
                $address_label = 'Supplier Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Supplier Type';
                $customer_label = 'Supplier';
                $person_type = "Vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Supplier', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'purchases':
                $address_label = 'Supplier Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'purchase_order'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Supplier Type';
                $customer_label = 'Supplier';
                $person_type = "Vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Supplier', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'debit_note':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases', 'goods_receipt_note'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases', 'goods_receipt_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'goods_receipt_note':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchase_order', 'purchases'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'purchases'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            
            case 'job_card':
                $service_type_label = 'Service Type';
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                /*$order_type = "Order Type";
                $order_label = 'Order#';
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');*/
                $order_type_value = AccountVoucher::whereIn('name', array('Direct'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';

                $sales_person_label = 'Assigned To';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;

            case 'job_request':
                
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $term_label = 'Terms';
                
                $service_type_label = 'Service Type';
                $address_label = 'Customer Address';
                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Attended By';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'job_invoice':
                $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'job_invoice_cash':
                $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
        }           
        

        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')
        ->get();

        $transaction_fields = TransactionField::select('transaction_fields.id', 'transaction_fields.name', 'field_formats.name as field_format', 'field_types.name as field_type', 'transaction_fields.field_format_id', 'transaction_fields.field_type_id', DB::Raw('GROUP_CONCAT(group_fields.name SEPARATOR "`")as group_name'), 'transaction_fields.sub_heading')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'transaction_fields.field_format_id')
        ->leftjoin('field_types', 'field_types.id', '=', 'transaction_fields.field_type_id')
        ->leftjoin('transaction_fields as group_fields', 'group_fields.group_id', '=', 'transaction_fields.id')
        ->where('transaction_fields.transaction_type_id', $transaction_type->id)
        ->where('transaction_fields.status', 1)
        ->groupby('transaction_fields.id')
        ->orderby('transaction_fields.sub_heading')
        ->get();

        
        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

        $selected_make = null;

        $model = ['' => 'Select Model'];


        $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name','registered_vehicle_specs.spec_value')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')
        ->where('registered_vehicle_specs.organization_id',$organization_id)->get();

        $date = now()->format("Y-m-d "); 

        $shift = HrmShift::where('status',1)->where('organization_id', $organization_id)->pluck('name','id');
      
        $shifttime = FsmShiftCashManage::select('shift_id')->where('date',$date)->where('end_time','=',null)->first();
         
            if($shifttime!=null)
            {
                $shift_id=$shifttime->shift_id;
            }
            else
            {
                $shift_id='';
            }
             

        $pump_name = FsmPump::where('fsm_pumps.organization_id',$organization_id)->pluck('fsm_pumps.name','fsm_pumps.id');

        Log::info("TransactionController->create :- return ".$type);

        return view('inventory.transaction_create', compact('people', 'business', 'person_id', 'selected_employee', 'voucher_no', 'account_ledgers', 'employees', 'shipment_mode', 'items', 'taxes', 'discounts', 'transaction_type', 'state', 'title', 'payment', 'terms', 'voucher_terms', 'weekdays', 'days', 'weekday', 'type', 'due_date_label', 'term_label', 'order_label', 'payment_label', 'sales_person_label', 'include_tax_label', 'date_label', 'customer_type_label', 'customer_label', 'person_type', 'field_types', 'transaction_fields', 'make', 'selected_make', 'model', 'sub_heading', 'discount_option', 'due_date', 'order_type', 'order_type_value', 'address_label', 'transaction_address_type', 'company_name', 'company_email', 'company_mobile', 'company_address', 'company_label', 'service_type_label', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain', 'service_type', 'vehicle_usage', 'maintanance_reading', 'vehicles_register', 'reading_factor','vehicle_sevice_type','job_card_status','job_status','delivery_method','current_date','add_date','vehicle_check_list','uuid','job_item_status','item_status','payment_method','payment_term','payment_terms','sevice_type','tomorrow_date','spec_values','shift','pump_name','shift_id','bus','selected_term'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function save_field(Request $request) {

        Log::info("TransactionController->save_field :- Inside ");
        $organization_id = Session::get('organization_id');
        $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();
        
        $field_item = $request->input('field_item');

        $field = new TransactionField;
        $field->name = $request->input('field_name');
        $field->field_type_id = $request->input('field_type');
        $field->field_format_id = $request->input('field_format');
        $field->transaction_type_id = $transaction_type->id;
        $field->status = $request->input('check_type');
        $field->required_status = $request->input('required_status');
        $field->sub_heading = $request->input('new_group');
        $field->save();

        if($field->id != null) {
            for($i=0; $i<count($field_item); $i++) {
                if($field_item[$i] != null) {
                    $group = new TransactionField;      
                    $group->name = $field_item[$i];                     
                    $group->transaction_type_id = $transaction_type->id;
                    $group->group_id = $field->id;
                    $group->save();
                }
            }

        }
        Log::info("TransactionController->save_field :- Return ");

        return response()->json(array('status' => 1, 'message' => 'Transaction Field'.config('constants.flash.added'), 'data' => ['id' => $field->id, 'name' => $field->name, 'field_type_id' => $field->field_type_id, 'field_format_id' => $field->field_format_id, 'transaction_type_id' => $field->transaction_type_id, 'status' => $field->status, 'required_status' => $field->required_status, 'sub_heading' => $field->sub_heading]));
    }


    public function store(Request $request) {
        Log::info("TransactionController->store :- Inside ");
        return $this->store_transaction($request, "store");
    }

    //Takes data to corresponding page
    
    public function add_to_account(Request $request) {
        Log::info("TransactionController->add_to_account :- Inside Datas are".json_encode($request->all()));
    
        //dd($request->all());

        $module_name = Session::get('module_name');

        $type = $request->type;
        
        Log::info("TransactionController->add_to_account :- Transaction Type Datas are".$type);

        $from = $request->from;

        $notification_type = $request->notification_type;

        $organization_id = Session::get('organization_id');

        $person_id = Auth::user()->person_id;

        $selected_employee = HrmEmployee::select('hrm_employees.id')

        ->where('hrm_employees.organization_id', $organization_id)

        ->where('hrm_employees.person_id', $person_id)

        ->first()->id;

        $tomorrow = Carbon::tomorrow();

        $tomorrow_date =  $tomorrow->format('d-m-Y');

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');

        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');

        $title->prepend('Title','');



        /* WMS - Records */

            $vehicle_sevice_type = ServiceType::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_sevice_type->prepend('Select Service type', '');

            $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
            $job_card_status->prepend('Select Jobcard Status', '');

            $vehicle_make_id = VehicleMake::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_make_id->prepend('Select Vehicle Make', '');

            $vehicle_model_id = VehicleModel::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_model_id->prepend('Select Vehicle Model', '');

            $vehicle_tyre_size = VehicleTyreSize::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_tyre_size->prepend('Select Tyre Size', '');

            $vehicle_tyre_type = VehicleTyreType::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_tyre_type->prepend('Select Tyre Type', '');

            //$vehicle_variant = VehicleVariant::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');

            //$vehicle_variant->prepend('Select Vehicle Variant', '');

            $vehicle_variant = VehicleVariant::orderBy('name')->pluck('name', 'id');
            $vehicle_variant->prepend('Select Vehicle Variant', '');

            $vehicle_wheel = VehicleWheel::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_wheel->prepend('Select Vehicle Wheel', '');

            $fuel_type = VehicleFuelType::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $fuel_type->prepend('Select Fuel Type', '');

            $rim_type = VehicleRimType::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $rim_type->prepend('Select Rim Type', '');

            $body_type = VehicleBodyType::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $body_type->prepend('Select Body Type', '');

            $vehicle_category = VehicleCategory::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
            $vehicle_category->prepend('Select Vehicle Category', '');

            $vehicle_drivetrain = VehicleDrivetrain::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_drivetrain->prepend('Select Vehicle Drivetrain', '');

            $service_type = ServiceType::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
            $service_type->prepend('Select Service Type', '');


            $vehicle_usage = VehicleUsage::where('status', '1')->orderBy('name')->pluck('name', 'id');
            $vehicle_usage->prepend('Select Vehicle Usage', '');



            $maintanance_reading = VehicleMaintenanceReading::where('status', '1')->pluck('name', 'id');
            $maintanance_reading->prepend('Select Maintenance Reading', '');



            /*$vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');

            $vehicles_register->prepend('Select Vehicle', '');*/

             $vehicles_register = VehicleRegisterDetail::leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id')->where('wms_vehicle_organizations.organization_id', $organization_id)->pluck('registration_no', 'vehicle_register_details.id');
            $vehicles_register->prepend('Select Vehicle', '');

            $reading_factor = WmsReadingFactor::select('wms_reading_factors.id AS reading_factor_id', 'wms_reading_factors.name AS reading_factor_name', 'wms_applicable_divisions.id AS wms_division_id', 'wms_applicable_divisions.division_name')

            ->leftJoin('wms_applicable_divisions', 'wms_applicable_divisions.id','=','wms_reading_factors.wms_division_id')

            ->where('wms_reading_factors.organization_id', $organization_id)->get();


        /* END Records */



        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $selected_term = Term::where('organization_id', $organization_id)->where('name', 'on_receipt')->first();

        $make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $make->prepend('Select Make', '');

        $job_type = JobType::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;

        $shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipment_mode->prepend('Select Shipment Mode', '');

        //$selected_payment_method = ReferenceVoucher::where('name', $type)->first()->id;

        $transaction = Transaction::find($request->id);
         Log::info("TransactionController->add_to_account :- get transaction data add_to_account::add_to_account - ".json_encode($transaction));
        $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();


        $cus_name = ($transaction->name.'-'.$transaction->mobile);


        //$transaction = Transaction::find($request->id)->where('organization_id',$organization_id)->first();

        //dd($transaction);

        $wms_transaction = WmsTransaction::select('wms_transactions.id','wms_transactions.registration_id','wms_transactions.vehicle_mileage','wms_transactions.service_type','wms_transactions.jobcard_status_id','wms_transactions.purchase_date','vehicle_register_details.*','wms_transactions.next_visit_mileage','wms_transactions.vehicle_next_visit','wms_transactions.vehicle_next_visit_reason','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.vehicle_note','wms_transactions.advance_amount','vehicle_variants.vehicle_configuration','wms_transactions.vehicle_complaints','wms_transactions.driver','wms_transactions.driver_contact')

        ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id')  

        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')    

        ->where('wms_transactions.organization_id', $organization_id)
        ->where('wms_transactions.transaction_id', $transaction->id)

        ->first();

        if($wms_transaction != null){

             $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',

            'registered_vehicle_specs.spec_value')

        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')

        ->where('registered_vehicle_specs.organization_id',$organization_id)

        ->where('registered_vehicle_specs.registered_vehicle_id',$wms_transaction->registration_id)

        ->get();

        }else{

            $spec_values = null;

        }

        /*$module_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')

        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')

        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')

        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')

        ->where('account_vouchers.organization_id', $organization_id)

        ->where('account_vouchers.id', $transaction->transaction_type_id)

        ->first();*/



        /*$transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')

        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')

        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')

        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')

        ->where('account_vouchers.organization_id', $organization_id)

        ->where('modules.name', Session::get('module_name'))

        ->where('account_vouchers.id', $transaction->transaction_type_id)

        ->first();*/    



        //$module_type = Session::get('module_name');
        //dd($transaction_type);

        /*if($module_type == null) {

            return null;

        }*/


        $selected_payment = null;

        if($transaction->payment_mode_id != null) {

            $selected_payment = PaymentMode::find($transaction->payment_mode_id)->id;

        }

        $selected_shipment = null;

        if($transaction->shipment_mode_id != '')
        {
            $selected_shipment = ShipmentMode::find($transaction->shipment_mode_id)->id;
        }

        $transaction_type = AccountVoucher::find($transaction->transaction_type_id);

        $reference_voucher = ReferenceVoucher::select('name', 'display_name', 'id')->where('name', $transaction_type->name)->where('status', 1)->get(); 

        $remote_reference_no = $transaction->order_no;
        $remote_order_id = $transaction->id;
        //$remote_item_voucher = null;  

        //Transaction from same company (Copying)       

        if($request->notification_type == "copy") {

            $remote_item_voucher = null;            

            if($transaction->user_type == 0)
            {       
                $reference_business = Person::find($transaction->people_id)->id;

                $reference_business_name = Person::find($transaction->people_id)->first_name;

            } else {    

                $reference_business_data = Business::find($transaction->people_id);
                $reference_business = $reference_business_data->id;
                $reference_business_name = $reference_business_data->alias;         
            }

            $customer_name = $transaction->name;
            $customer_mobile = $transaction->mobile;
            $customer_email = $transaction->email;
            $customer_gst = $transaction->gst;

            $customer_address = $transaction->address;

        } 

        //Transaction from another company (Notification)

        else if($request->notification_type == "remote") 
        {
            /*Usually whatever company made the transaction it will be considered as vendor or customer in the current company.

            e.g.  If it is a purchase order it would turns into sale order
                  The company which made the PO is the vendor
                  and vice versa If it is a invoice
                  The company which made the Invoice is the customer
            */
                

            /* get remote item voucher type */                        

            $query = TransactionItem::select('inventory_items.id', 'global_item_models.id AS global_id', 'global_item_models.name AS global_name', 'transaction_items.description', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.id AS tax_id',  'transaction_items.discount', 'transaction_items.discount_id', DB::raw('COALESCE(transaction_items.discount_value, "") AS discount_value'),'transaction_items.start_time','transaction_items.end_time','transaction_items.assigned_employee_id','transaction_items.job_item_status','inventory_item_stocks.in_stock','inventory_items.base_price','inventory_items.purchase_price','inventory_items.sale_price_data','transaction_items.item_id','global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name','account_vouchers.name AS voucher_type');

            $query->leftjoin('inventory_items AS remote_item', 'remote_item.id', '=', 'transaction_items.item_id');
            $query->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'remote_item.id' );

            $query->leftjoin('inventory_items', function($query) use ($organization_id) {

                $query->on('inventory_items.global_item_model_id', '=', 'remote_item.global_item_model_id');

                $query->where('inventory_items.organization_id', '=', $organization_id);

            });
            

            $query->leftjoin('global_item_models', 'global_item_models.id', '=', 'remote_item.global_item_model_id');

            $query->leftjoin('tax_groups AS transaction_item_tax', 'transaction_item_tax.id', '=', 'transaction_items.tax_id');

            $query->leftjoin('tax_groups', function($query1) use ($organization_id) {

                $query1->on('tax_groups.name', '=', 'transaction_item_tax.name');

                $query1->where('tax_groups.organization_id', '=', $organization_id);

            }); 


            $query->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id');

            $query->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id');

            $query->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id');

            $query->leftjoin('transactions', 'transactions.id', '=', 'transaction_items.transaction_id');

            $query->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id');

            $query->where('transaction_items.transaction_id', $request->id);

            $transaction_items = $query->get();

            foreach ($transaction_items as $key => $value) {

                $remote_item_voucher = $transaction_items[$key]->voucher_type;
            }

            //dd($remote_item_voucher);     

            if($transaction->user_type == 0)
            {
                $reference_business = Person::find($transaction->people_id)->id;

                $reference_business_name = Person::find($transaction->people_id)->first_name;

            } else { 

                //The organization which made the transaction

                $organization = Organization::find($transaction->organization_id);

                $reference_business_data = Business::select('businesses.id', 'businesses.alias', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address')

                ->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')

                ->where('businesses.id', $organization->business_id)->first();

                $reference_business = $reference_business_data->id;
                $reference_business_name = $reference_business_data->alias;

            }

            if($type == "purchase_order" || $type == "purchases" || $type == "goods_receipt_note" || $type == "debit_note") {

                $customer_name = $reference_business_data->alias;
                $customer_mobile = $reference_business_data->mobile_no;
                $customer_email = $reference_business_data->email_address;
                $customer_address = $reference_business_data->address;

            } else {

                //$customer_name = $transaction->billing_name;
                //$customer_mobile = $transaction->billing_mobile;
                //$customer_email = $transaction->billing_email;
                //$customer_address = $transaction->billing_address;

                $customer_name = $reference_business_data->alias;
                $customer_mobile = $reference_business_data->mobile_no;
                $customer_email = $reference_business_data->email_address;
                $customer_address = $reference_business_data->address;

            }

        }
        

        $reference_user_type = 1;   

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $transaction_type = AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();
        Log::info("TransactionController->add_to_account :-get transaction_type LineNo 1852 - ".json_encode($transaction_type));
        //dd($transaction_type);

        // $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

        // if($request->gen_no){
        //  $gen_no = $request->gen_no;
        // }else{
        // $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;
         // }

        $getGen_no=Custom::getLastGenNumber( $transaction_type->id, $organization_id );

        //$gen_no=($getGen_no) ? $getGen_no : $transaction_type->starting_value;
        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
          
          
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }
          
          


        
        $voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

        $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

        $account_ledgers = AccountLedger::where('group_id', $sale_account)->where('organization_id', $organization_id)->pluck('name', 'id');

        $account_ledgers->prepend('Select Account', '');

        $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');

        $employees->prepend('Select Sales Person', '');

        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')

        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')  

        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')       

        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', 1)


        ->orderby('global_item_categories.display_name')

        ->get();

        /*$tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');

        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');

        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');

        $tax->where('tax_groups.organization_id', $organization_id);*/



        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);
        $tax->groupby('tax_groups.id');

        $taxes = $tax->get();


        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('status', 1)->where('organization_id', $organization_id);
        $discounts = $discount->get();

        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;



        $days = [];

        for ($i=1; $i <= 28; $i++) { 

            $days[$i] = $i;

        }

        $days[0] = "Last";



        if($transaction_type == null) abort(404);

        $address_type = BusinessAddressType::where('name', 'business')->first();

        $business_id = Organization::find($organization_id)->business_id;


        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')

        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')

        ->leftjoin('states', 'cities.state_id', '=', 'states.id')

        ->where('address_type', $address_type->id)

        ->where('business_id', $business_id)

        ->first();



        $date_label = null;
        $due_date_label = null;
        $term_label = null;
        $order_type = null;
        $address_label = null;
        $order_type_value = [];
        $order_label = null;
        $payment_label = null;
        $sales_person_label = null;
        $include_tax_label = null;
        $customer_type_label = null;
        $customer_label = null;
        $discount_option = false;
        $person_type = null;
        $due_date = null;
        $transaction_address_type = null;
        $company_label = false;
        $company_name = null;
        $company_email = null;
        $company_mobile = null;
        $company_address = null;
        $service_type_label = null;



        $business_company_address = $business_communication_address->address;

        if($business_communication_address->address != "" && $business_communication_address->city != "") {

            $business_company_address .= "\n";

        }

        $business_company_address .= $business_communication_address->city;

        if($business_communication_address->city != "" && $business_communication_address->state != "") {

            $business_company_address .= "\n";

        }


        $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;

        switch($type) {

            case 'estimation':

                $address_label = 'Customer Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();



                //$order_type_value = AccountVoucher::whereIn('name', array('Direct'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                //$order_type_value->prepend('Direct', '');



                $due_date_label = 'Expiry Date';

                $sales_person_label = 'Sales Person';

                $date_label = 'Date';

                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');

                $customer_type_label = 'Customer Type';

                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

                $discount_option = true;

            break;

            case 'sale_order':

                $address_label = 'Customer Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();

                

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');



                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;

            break;

            case 'sales':

                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Sales Person';

                $date_label = 'Invoice Date';

                $customer_type_label = 'Customer Type';

                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

                $discount_option = true;

            break;

            case 'sales_cash':

                $address_label = 'Customer Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";



                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');



                $order_type_value->prepend('Direct', '');



                $due_date_label = 'Due Date';

                $term_label = 'Terms';

                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Sales Person';

                $date_label = 'Invoice Date';

                $customer_type_label = 'Customer Type';

                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

                $discount_option = true;

            break;

            case 'job_card':

                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                //$order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;

            break;

            case 'job_request':

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $term_label = 'Terms';
                $service_type_label = 'Service Type';
                $address_label = 'Customer Address';
                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Attended By';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;

            break;

            case 'job_invoice':

                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $service_type_label = 'Service Type';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;

            break;

            case 'job_invoice_cash':

                $address_label = 'Customer Address';

                $service_type_label = 'Service Type';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();



                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

                $discount_option = true;

            break;

            case 'delivery_note':

                $address_label = 'Customer Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('sale_order', 'sales','sales_cash'))->where('status', 1)->orderby('name', 'desc')->get();

                $order_label = 'Order#';
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'sales', 'sales_cash','job_invoice','job_invoice_cash'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');
                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');

                $discount_option = true;

            break;

            case 'receipt':

                $address_label = 'Customer Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');



                $order_type_value->prepend('Direct', '');



                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Sales Person';

                $date_label = 'Date';

                $customer_type_label = 'Customer Type';

                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

            break;

            case 'payment':

                $address_label = 'Vendor Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                

                $order_type_value->prepend('Direct', '');



                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Created By';

                $date_label = 'Date';

                $customer_type_label = 'Vendor Type';

                $customer_label = 'Vendor';

                $person_type = "vendor";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Vendor', '');

                $tax->where('tax_groups.is_purchase', '1');

                $discount->where('is_purchase', '1');

                $company_label = true;

                $company_name = $business_communication_address->placename;

                $company_email = $business_communication_address->email_address;

                $company_mobile = $business_communication_address->mobile_no;

                $company_address = $business_company_address;

            break;

            case 'credit_note':

                $address_label = 'Customer Address';

                $order_type = "Order Type";

                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales', 'delivery_note'))->where('status', 1)->orderby('id')->get();



                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales', 'delivery_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Sales Person';

                $date_label = 'Date';

                $customer_type_label = 'Customer Type';

                $customer_label = 'Customer';

                $person_type = "customer";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Customer', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_sales', '1');

                $discount->where('is_sales', '1');

            break;

            case 'purchase_order':

                $address_label = 'Supplier Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();

                $payment_label = 'Payment Method';

                $sales_person_label = 'Created By';

                $date_label = 'Date';

                $customer_type_label = 'Supplier Type';

                $customer_label = 'Supplier';

                $person_type = "Vendor";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Supplier', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_purchase', '1');

                $discount->where('is_purchase', '1');

                $company_name = $business_communication_address->placename;

                $company_email = $business_communication_address->email_address;

                $company_mobile = $business_communication_address->mobile_no;

                $company_address = $business_company_address;

            break;

            case 'purchases':

                $address_label = 'Supplier Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'purchase_order'))->where('status', 1)->orderby('id')->get();

                $due_date_label = 'Due Date';

                $term_label = 'Terms';

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Created By';

                $date_label = 'Date';

                $customer_type_label = 'Supplier Type';

                $customer_label = 'Supplier';

                $person_type = "Vendor";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Supplier', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_purchase', '1');

                $discount->where('is_purchase', '1');

                $discount_option = true;

                $company_name = $business_communication_address->placename;

                $company_email = $business_communication_address->email_address;

                $company_mobile = $business_communication_address->mobile_no;

                $company_address = $business_company_address;

            break;

            case 'debit_note':

                $address_label = 'Vendor Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases', 'goods_receipt_note'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases', 'goods_receipt_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $sales_person_label = 'Created By';

                $date_label = 'Date';

                $customer_type_label = 'Vendor Type';

                $customer_label = 'Vendor';

                $person_type = "vendor";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Vendor', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_purchase', '1');

                $discount->where('is_purchase', '1');

                $company_name = $business_communication_address->placename;

                $company_email = $business_communication_address->email_address;

                $company_mobile = $business_communication_address->mobile_no;

                $company_address = $business_company_address;

            break;

            case 'goods_receipt_note':

                $address_label = 'Vendor Address';

                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchase_order', 'purchases'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'purchases'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_label = 'Order#';

                $payment_label = 'Payment Method';

                $date_label = 'Date';

                $customer_type_label = 'Vendor Type';

                $customer_label = 'Vendor';

                $person_type = "vendor";

                $people = $people_list->pluck('name', 'id');

                $business = $business_list->pluck('name', 'id');

                $people->prepend('Select Vendor', '');

                $business->prepend('Select Business', '');

                $tax->where('tax_groups.is_purchase', '1');

                $discount->where('is_purchase', '1');

                $discount_option = true;

                $company_name = $business_communication_address->placename;

                $company_email = $business_communication_address->email_address;

                $company_mobile = $business_communication_address->mobile_no;

                $company_address = $business_company_address;

            break;

        }



        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')

        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')

        ->get();

        $transaction_fields = TransactionField::select('transaction_fields.id', 'transaction_fields.name', 'field_formats.name as field_format', 'field_types.name as field_type', 'transaction_fields.field_format_id', 'transaction_fields.field_type_id', DB::Raw('GROUP_CONCAT(group_fields.name SEPARATOR "`")as group_name'), 'transaction_fields.sub_heading')

        ->leftjoin('field_formats', 'field_formats.id', '=', 'transaction_fields.field_format_id')

        ->leftjoin('field_types', 'field_types.id', '=', 'transaction_fields.field_type_id')

        ->leftjoin('transaction_fields as group_fields', 'group_fields.group_id', '=', 'transaction_fields.id')

        ->where('transaction_fields.transaction_type_id', $transaction_type->id)

        ->where('transaction_fields.status', 1)

        ->groupby('transaction_fields.id')

        ->orderby('transaction_fields.sub_heading')

        ->get();

        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();



        $selected_make = null;
        $model = ['' => 'Select Model'];
        $reference_transaction_type = null;

        $reference_transaction = Transaction::find($transaction->reference_id);

        if($reference_transaction != null) {

            $reference_transaction_account = AccountVoucher::find($reference_transaction->transaction_type_id);

            if($reference_transaction_account != null) {

                $reference_transaction_type = $reference_transaction_account->name;
            }
        }
        // exit;

         $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('name', 'id');

        $group_name->prepend('Select Group Name', '');

        Log::info("TransactionController->add_to_account :- Return ");
        return view('inventory.transaction_add_account', compact('people', 'business', 'voucher_no','gen_no','account_ledgers', 'employees', 'shipment_mode', 'items', 'taxes', 'discounts', 'transaction_type', 'state', 'title', 'payment', 'terms', 'voucher_terms', 'weekdays', 'days', 'weekday', 'type', 'due_date_label', 'term_label', 'order_label', 'payment_label', 'sales_person_label', 'include_tax_label', 'date_label', 'customer_type_label', 'customer_label', 'person_type', 'field_types', 'transaction_fields', 'make', 'selected_make', 'model', 'job_type', 'sub_heading', 'discount_option', 'due_date', 'order_type', 'order_type_value', 'address_label', 'transaction_address_type', 'company_name', 'company_email', 'company_mobile', 'company_address', 'company_label', 'reference_voucher', 'transaction', 'selected_payment', 'reference_business', 'reference_user_type', 'reference_business_name', 'customer_name', 'customer_mobile', 'customer_email','customer_gst', 'customer_address', 'remote_reference_no', 'remote_order_id','selected_employee','selected_shipment', 'notification_type','service_type_label','vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain','service_type', 'vehicle_usage', 'maintanance_reading', 'vehicles_register','reading_factor','wms_transaction','vehicle_sevice_type','job_card_status','payment_terms','tomorrow_date','reference_transaction_type','remote_item_voucher','group_name','spec_values','module_name','from','city','cus_name','stock_item_update','selected_term','payment_term'));


    }

    //Takes data to corresponding page's save method
    public function add_to_store(Request $request)
    {
        Log::info("TransactionController->add_to_store :- Inside ");
        return $this->store_transaction($request, "remote");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        Log::info("TransactionController->update :- Inside ");
        return $this->store_transaction($request, "update");
    }

    public function lowstock_store(Request $request) {
        Log::info("TransactionController->lowstock_store :- Inside ");
        return $this->store_transaction($request, "lowstock");
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
    public function edit($id,$module_name=false)
    {
        Log::info("TransactionController->edit :- Inside id ".$id);
        $now = Carbon::now();
        $current_date =  $now->format('Y-m-d H:i:s');
        $add_date = date("Y-m-d H:i:s", strtotime("+1 hours"));

        $organization_id = Session::get('organization_id');

         if($module_name){
        $module_name=$module_name;
     
       }else{
        $module_name = Session::get('module_name');


       }

        //dd($module_name);

        $item_status = VehicleJobItemStatus::where('name','Open')->first()->id;

        $vehicle_sevice_type = ServiceType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_sevice_type->prepend('Select Service type', '');

        $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
        $job_card_status->prepend('Select Jobcard Status', '');

        $vehicle_make_id = VehicleMake::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');

        $vehicle_model_id = VehicleModel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_model_id->prepend('Select Vehicle Model', '');

        $vehicle_tyre_size = VehicleTyreSize::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_tyre_size->prepend('Select Tyre Size', '');

        $vehicle_tyre_type = VehicleTyreType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_tyre_type->prepend('Select Tyre Type', '');

        $vehicle_variant = VehicleVariant::orderBy('name')->pluck('name', 'id');
        $vehicle_variant->prepend('Select Vehicle Variant', '');


        $vehicle_wheel = VehicleWheel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_wheel->prepend('Select Vehicle Wheel', '');

        $fuel_type = VehicleFuelType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $fuel_type->prepend('Select Fuel Type', '');

        $rim_type = VehicleRimType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $rim_type->prepend('Select Rim Type', '');

        $body_type = VehicleBodyType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $body_type->prepend('Select Body Type', '');

        $vehicle_category = VehicleCategory::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $vehicle_category->prepend('Select Vehicle Category', '');

        $vehicle_drivetrain = VehicleDrivetrain::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_drivetrain->prepend('Select Vehicle Drivetrain', '');

        $service_type = ServiceType::where('organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $service_type->prepend('Select Service Type', '');

        $vehicle_usage = VehicleUsage::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $vehicle_usage->prepend('Select Vehicle Usage', '');

        $maintanance_reading = VehicleMaintenanceReading::where('status', '1')->pluck('name', 'id');
        $maintanance_reading->prepend('Select Maintenance Reading', '');

        /*$vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');*/

        $vehicles_register = VehicleRegisterDetail::leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id')->where('wms_vehicle_organizations.organization_id', $organization_id)->pluck('registration_no', 'vehicle_register_details.id');
        $vehicles_register->prepend('Select Vehicle', '');

        $reading_factor = WmsReadingFactor::select('wms_reading_factors.id AS reading_factor_id', 'wms_reading_factors.name AS reading_factor_name', 'wms_applicable_divisions.id AS wms_division_id', 'wms_applicable_divisions.division_name')
        ->leftJoin('wms_applicable_divisions', 'wms_applicable_divisions.id','=','wms_reading_factors.wms_division_id')
            ->where('wms_reading_factors.organization_id', $organization_id)->get();


        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));


        $transactions = Transaction::where('id',$id)->where('organization_id',$organization_id)->first();

        $stock_item_update = TransactionItem::where('transaction_id', $transactions->id)->first();

        $cus_name = ($transactions->name.'-'.$transactions->mobile);

        $print_templates = MultiTemplate::select('multi_templates.id','multi_templates.voucher_id','account_voucher_types.display_name','multi_templates.print_temp_id','print_templates.display_name','print_templates.data') ->leftjoin('account_voucher_types','account_voucher_types.id','=','multi_templates.voucher_id')
             ->leftjoin('print_templates','print_templates.id','=','multi_templates.print_temp_id')
             ->where('multi_templates.organization_id',$organization_id)
             ->where('voucher_id',22)
             ->groupby('multi_templates.print_temp_id')
             ->get();
          

        $estimation_print_templates = MultiTemplate::select('multi_templates.id','multi_templates.voucher_id','account_voucher_types.display_name','multi_templates.print_temp_id','print_templates.display_name','print_templates.data') ->leftjoin('account_voucher_types','account_voucher_types.id','=','multi_templates.voucher_id')
             ->leftjoin('print_templates','print_templates.id','=','multi_templates.print_temp_id')
             ->where('multi_templates.organization_id',$organization_id)
             ->where('voucher_id',21)
             ->groupby('multi_templates.print_temp_id')
             ->get();
             

        $wms_transaction_readings = WmsReadingFactor::select('wms_reading_factors.name As reading_factor_name','wms_reading_factors.id AS reading_factor_id', 'wms_transaction_readings.reading_values','wms_transaction_readings.reading_notes','wms_transaction_readings.id As id')->LeftJoin('wms_transaction_readings', function($join)  use ($id) {
            $join->on('wms_transaction_readings.reading_factor_id', '=', 'wms_reading_factors.id') ;
            $join->where('wms_transaction_readings.transaction_id', '=',$id) ;})
        ->where('wms_reading_factors.organization_id', $organization_id)->get();

        $wms_attachments_before=WmsAttachment::select('id','organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 1)->where('organization_id',$organization_id)->get();
        
        $wms_attachments_progress=WmsAttachment::select('id','organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 2)->where('organization_id',$organization_id)->get();
        
        $wms_attachments_after=WmsAttachment::select('id','organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 3)->where('organization_id',$organization_id)->get();

        
        $wms_checklist_query=VehicleChecklist::select('vehicle_checklists.name','vehicle_checklists.id as checklist_id','wms_checklists.transaction_id','wms_checklists.checklist_status','wms_checklists.checklist_notes','wms_checklists.id as id')
            ->LeftJoin('wms_checklists', function($join)  use ($id) {
                $join->on('wms_checklists.checklist_id', '=', 'vehicle_checklists.id') ;
                $join->where('wms_checklists.transaction_id', '=',$id);
            })
            ->orderBy('vehicle_checklists.id','ASc');
        
        $wms_checklist=$wms_checklist_query->get();


        $reference_transaction_type = null;

        $reference_transaction = Transaction::find($transactions->reference_id);        

        $wms_transaction = WmsTransaction::select('wms_transactions.*','wms_transactions.service_type','wms_transactions.jobcard_status_id','wms_transactions.purchase_date','vehicle_register_details.*','wms_transactions.next_visit_mileage','wms_transactions.vehicle_next_visit','wms_transactions.vehicle_next_visit_reason','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.registration_id','wms_transactions.vehicle_note','wms_transactions.vehicle_complaints','vehicle_variants.vehicle_configuration','wms_transactions.driver','wms_transactions.driver_contact','wms_transactions.shift_id','wms_transactions.pump_id')
        ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id')
        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')
        ->where('wms_transactions.organization_id', $organization_id)       
        ->where('wms_transactions.transaction_id', $transactions->id)
        ->first();
            
            Log::info("TransactionController->Edit :- getLastjob card Number with transaction Id after line 2905 - ".json_encode($transactions->id));
            Log::info("TransactionController->Edit :- getLastjob card Number with after line 2906 - ".json_encode($wms_transaction));

        if($module_name == "trade_wms")
        {
            $last_job_card_id = '';

            $copy_job = Transaction::where('order_no',$wms_transaction->vehicle_last_job)->exists();        

            if($copy_job == true)
            {               
                $last_job_card_id = Transaction::where('order_no',$wms_transaction->vehicle_last_job)
                ->where('organization_id',$organization_id)->where('deleted_at',NULL)->first();
                Log::info("TransactionController->Edit :- getLastjob card Number with after line 2894  - ".$last_job_card_id);
                if($last_job_card_id)
                {
                    $last_job_card_id = $last_job_card_id->id;
                }
                else
                {
                    $last_job_card_id = "";
                }
                Log::info("TransactionController->Edit :- getLastjob card Number with after line 2903 - ".$last_job_card_id);
                
            }
        }
        

        if($reference_transaction != null) {
            $reference_transaction_account = AccountVoucher::find($reference_transaction->transaction_type_id);

            if($reference_transaction_account != null) {
                $reference_transaction_type = $reference_transaction_account->name;
            }
        }

        $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        ->where('account_vouchers.organization_id', $organization_id)
        ->where('modules.name', $module_name)
        ->where('account_vouchers.id', $transactions->transaction_type_id)
        ->first();


        //AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        //dd($transaction_type);

        if($transaction_type == null) {
            return null;
        }       

        
        /*$transaction_type = AccountVoucher::find($transactions->transaction_type_id);*/       

        $type = $transaction_type->name;

            // $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

            // $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;
Log::info("TransactionController->Edit :- Custom::getLastGenNumber - ".$transaction_type->id. ' -- '.$organization_id);
        $getGen_no=Custom::getLastGenNumber( $transaction_type->id, $organization_id );
Log::info("TransactionController->Edit :- after Custom::getLastGenNumber - ".$getGen_no);

        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
          
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }


        $voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

        $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

        $account_ledgers = AccountLedger::where('group_id', $sale_account)->where('organization_id', $organization_id)->pluck('name', 'id');
        $account_ledgers->prepend('Select Account', '');

        $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
        $employees->prepend('Select Sales Person', '');

        $person_id = Auth::user()->person_id;

        $employee = HrmEmployee::select('hrm_employees.id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->where('hrm_employees.person_id', $person_id)
        ->first();

        $selected_employee = ($employee != null) ? $employee->id : null;


        $shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipment_mode->prepend('Select Shipment Mode', '');


        $job_item_status = VehicleJobItemStatus::where('status', '1')->pluck('name', 'id');
        $job_item_status->prepend('Select Status', '');

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;


        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')

        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
        
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', 1)
        ->orderby('global_item_categories.display_name')
        ->get();

        /*$items = InventoryItem::where('status', '1')->pluck('name','id');
        $items->prepend('Select Item ','');*/   


        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();       


        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('organization_id', $organization_id);
        $discounts = $discount->get();

        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }
        $days[0] = "Last";

        if($transaction_type == null) abort(404);

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $selected_term = Term::where('organization_id', $organization_id)->where('name', 'on_receipt')->first();

        $make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $make->prepend('Select Make', '');

        $job_type = JobType::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');

        $address_type = BusinessAddressType::where('name','business')->first();

        $business_id = Organization::find($organization_id)->business_id;

        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        ->leftjoin('states', 'cities.state_id', '=', 'states.id')
        ->where('address_type', $address_type->id)
        ->where('business_id', $business_id)
        ->first();

        $date_label = null;
        $due_date_label = null;
        $term_label = null;
        $order_type = null;
        $address_label = null;
        $order_type_value = [];
        $order_label = null;
        $payment_label = null;
        $sales_person_label = null;
        $include_tax_label = null;
        $customer_type_label = null;
        $customer_label = null;
        $discount_option = false;
        $person_type = null;
        $due_date = null;
        $shipping_date = null;
        $transaction_address_type = null;
        $company_label = false;
        $company_name = null;
        $company_email = null;
        $company_mobile = null;
        $company_address = null;
        $service_type_label = null;

        $business_company_address = $business_communication_address->address;

        if($business_communication_address->address != "" && $business_communication_address->city != "") {
            $business_company_address .= "\n";
        }

        $business_company_address .= $business_communication_address->city;

        if($business_communication_address->city != "" && $business_communication_address->state != "") {
            $business_company_address .= "\n";
        }

        $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;
    

        switch($type) {
            case 'estimation':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();

                //$order_type_value = AccountVoucher::whereIn('name', array('Direct'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                //$order_type_value->prepend('Direct', '');

                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sale_order':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sales':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sales_cash':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;

            case 'job_card':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                //$order_type = "Order Type";

                /*$order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');*/
                $service_type_label = 'Service Type';
                $sales_person_label = 'Assigned To';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
                
            break;
            case 'job_request':
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $term_label = 'Terms';

                $service_type_label = 'Service Type';
                $service_type_label = 'Service Type';
                $address_label = 'Customer Address';
                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Attended By';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'job_invoice':
                $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;

            case 'job_invoice_cash':
                $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'delivery_note':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sale_order', 'sales','sales_cash'))->where('status', 1)->orderby('name', 'desc')->get();
                $order_label = 'Order#';

                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'sales', 'sales_cash','job_invoice','job_invoice_cash'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'receipt':
                $address_label = 'Customer Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');

                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'payment':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                
                $order_type_value->prepend('Direct', '');

                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_label = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'credit_note':
                $address_label = 'Customer Address';
                $order_type = "Order Type";
                //$reference_voucher = $reference_vouchers->whereIn('name', array('sales', 'delivery_note'))->where('status', 1)->orderby('id')->get();

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales', 'delivery_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'purchase_order':
                $address_label = 'Supplier Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Supplier Type';
                $customer_label = 'Supplier';
                $person_type = "Vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Supplier', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'purchases':
                $address_label = 'Supplier Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'purchase_order'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Supplier Type';
                $customer_label = 'Supplier';
                $person_type = "Vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Supplier', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'debit_note':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchases', 'goods_receipt_note'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases', 'goods_receipt_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'goods_receipt_note':
                $address_label = 'Vendor Address';
                //$reference_voucher = $reference_vouchers->whereIn('name', array('purchase_order', 'purchases'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'purchases'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
        }   
        
        

        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')
        ->get();

        $transaction_fields = TransactionField::select('transaction_fields.id', 'transaction_fields.name', 'field_formats.name as field_format', 'field_types.name as field_type', 'transaction_fields.field_format_id', 'transaction_fields.field_type_id', DB::Raw('GROUP_CONCAT(group_fields.name SEPARATOR "`")as group_name'), 'transaction_fields.sub_heading')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'transaction_fields.field_format_id')
        ->leftjoin('field_types', 'field_types.id', '=', 'transaction_fields.field_type_id')
        ->leftjoin('transaction_fields as group_fields', 'group_fields.group_id', '=', 'transaction_fields.id')
        ->where('transaction_fields.transaction_type_id', $transaction_type->id)
        ->where('transaction_fields.status', 1)
        ->groupby('transaction_fields.id')
        ->orderby('transaction_fields.sub_heading')
        ->get();

        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

        $selected_make = null;

        $model = ['' => 'Select Model'];


        $approvel_status =$transactions->approval_status;
        $approved_date = $transactions->approved_on;

       
        if($module_name == "trade_wms"){

        $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',
            'registered_vehicle_specs.spec_value')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')
        ->where('registered_vehicle_specs.organization_id',$organization_id)
        ->where('registered_vehicle_specs.registered_vehicle_id',$wms_transaction->registration_id)
        ->get();

        }

        $shift=HrmShift::where('hrm_shifts.organization_id',$organization_id)
            ->pluck('hrm_shifts.name','hrm_shifts.id');
                                    

        $pump_name=FsmPump::where('fsm_pumps.organization_id',$organization_id)->pluck('fsm_pumps.name','fsm_pumps.id');
          
        $org_id = Session::get('organization_id');

        $complaints_completed_status = WmsTransactionComplaintService::select(DB::raw('COUNT(
        wms_transaction_complaint_services.service_group_name_type)as total_complints'),
        DB::raw('COUNT(IF(wms_transaction_complaint_services.service_status = 1,wms_transaction_complaint_services.service_status,NULL))as completed_count'))
        ->where('transaction_id',$id)
        ->where('organization_id',$organization_id)
        ->first();

        $total_complaints = $complaints_completed_status->total_complints;

        $total_completed = $complaints_completed_status->completed_count;
        
        Log::info("TransactionController->edit :- return id ".$id);

		return view('inventory.transaction_edit', compact('people', 'business', 'voucher_no', 'account_ledgers', 'employees', 'shipment_mode', 'items', 'taxes', 'discounts', 'transaction_type', 'state', 'title', 'payment', 'terms', 'voucher_terms', 'weekdays', 'days', 'weekday', 'type', 'due_date_label', 'term_label', 'order_label', 'payment_label', 'sales_person_label', 'include_tax_label', 'date_label', 'customer_type_label', 'customer_label', 'person_type', 'field_types', 'transaction_fields', 'make', 'selected_make', 'model', 'job_type', 'sub_heading', 'discount_option', 'due_date', 'order_type', 'order_type_value', 'address_label', 'transaction_address_type', 'company_name', 'company_email', 'company_mobile', 'company_address', 'company_label' , 'transactions', 'id','shipping_date', 'reference_transaction_type','service_type_label','vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain','service_type', 'vehicle_usage', 'maintanance_reading', 'vehicles_register','reading_factor','wms_transaction','vehicle_sevice_type','job_card_status','wms_transaction_readings','wms_attachments_before','wms_attachments_progress','wms_attachments_after','wms_checklist','approvel_status','approved_date','job_item_status','payment_terms','spec_values','org_id','print_templates','shift','pump_name','cus_name','total_complaints','total_completed','estimation_print_templates','last_job_card_id','selected_employee','current_date','add_date','item_status','stock_item_update','selected_term','payment_term'));
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

        $transaction = Transaction::findOrFail($request->id);

        Log::info("TransactionController->destroy :- Inside id ".$transaction->id);

        $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();

        if($transaction->reference_id != null){
            $reference_status = Transaction::find($transaction->reference_id);
        }
        
        $transaction_type = AccountVoucher::find($transaction->transaction_type_id);

        $transaction_id = TransactionFieldValue::where('transaction_id', $transaction->id)->get();

        if(count($transaction_id) > 0) {
            foreach($transaction_id as $id) {
                TransactionFieldValue::where('id', $id->id)->first()->delete();
            }
        }

        if($transaction->approval_status == 1)
        {
            if($transaction_type->name == "credit_note" || $transaction_type->name == "purchases") {

                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 1){

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::where('transaction_id',$item->transaction_id)->first();

                        //dd($inventory_item_batch);
                        

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();

                        if($stock != null) {

                            //$inventory_stock = $stock->in_stock - $item->quantity;

                            if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }
                            
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);                            

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                            $stock->save();

                        /* Item Batch Update */

                        if($inventory_item_batch != null)
                        {
                            /*if($inventory_item_batch->quantity <= $item->quantity)
                            {
                                $inventory_item_batch->quantity= 0.00;
                            }
                            else{
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                            }

                            $inventory_item_batch->save();*/

                            InventoryItemBatch::where('id', $inventory_item_batch->id)->first()->delete();
                        }   

                        /* End */
                        }
                        
                    }
                }
            }

            if($transaction_type->name == "goods_receipt_note") {

                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }                   

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 0 && $reference_status->approval_status == 1){

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::where('transaction_id',$item->transaction_id)->first();

                        //dd($inventory_item_batch);
                        

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();

                        if($stock != null) {

                            //$inventory_stock = $stock->in_stock - $item->quantity;

                            if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }
                            
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);                            

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                            $stock->save();

                        /* Item Batch Update */

                        if($inventory_item_batch != null)
                        {
                            /*if($inventory_item_batch->quantity <= $item->quantity)
                            {
                                $inventory_item_batch->quantity= 0.00;
                            }
                            else{
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                            }

                            $inventory_item_batch->save();*/

                            InventoryItemBatch::where('id', $inventory_item_batch->id)->first()->delete();
                        }   

                        /* End */
                        }
                        
                    }
                }

            }

            if( $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

            

                foreach ($items as $item)
                {
                    $stock = InventoryItemStock::find($item->item_id);

                    $inventory_item = InventoryItem::find($item->item_id);

                    $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                    //dd($inventory_item_batch);

                    //$inventory_item_batch = InventoryItemBatch::where('transaction_id',$item->transaction_id)->first();

                    /*$data = Custom::get_least_closest_date(json_decode($stock->data, true));

                    $qty = $data['quantity'];*/ 

                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                    if($inventory_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $inventory_item->purchase_price;
                    }

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)
                    ->first();

                    if($stock != null) {

                        $inventory_stock = $stock->in_stock + $item->quantity;
                         
                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);
                        

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                        $stock->data = json_encode($data);


                        $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                        $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                        $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                        $stock->save();


                        /* Item Batch Update */

                        if($inventory_item_batch != null)
                        {
                            $inventory_item_batch->quantity = $inventory_item_batch->quantity + $item->quantity;

                            $inventory_item_batch->save();
                        }   

                        /* End */
                    }   
                }
                    
            }


            if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") {

                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 1){

                    foreach ($items as $item) 
                    {
                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        //dd($inventory_item_batch);

                        //$inventory_item_batch = InventoryItemBatch::where('transaction_id',$item->transaction_id)->first();

                        /*$data = Custom::get_least_closest_date(json_decode($stock->data, true));

                        $qty = $data['quantity'];*/ 

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();

                        if($stock != null) {

                            $inventory_stock = $stock->in_stock + $item->quantity;
                             
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);
                            

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            
                            $stock->save();


                            /* Item Batch Update */

                            if($inventory_item_batch != null)
                            {
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity + $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */
                        }   
                    }
                }
            }


            if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
            {
                Custom::delete_revenue('total_revenue',$transaction->total);
            }   

        }

        $transaction->delete();

        if(!empty($transaction->entry_id)) {
            AccountEntry::where('account_entries.id', $transaction->entry_id)->first()->delete();
        }

        /*over all addon transaction delete*/

            Custom::delete_addon('transaction');

        /* end */
        

        /* If we need separately addon delete use this */

            /*if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
            {
                Custom::delete_addon('invoice');
            } 
            if($transaction_type->name == "purchases"){

                Custom::delete_addon('purchase');
            }
            if($transaction_type->name == "goods_receipt_note"){

                Custom::delete_addon('grn');
            }
            if($transaction_type->name == "job_card"){
                Custom::delete_addon('job_card');
            }*/

        /*end*/
        Log::info("TransactionController->destroy :- return id ".$transaction->id);
        
        return response()->json(['status' => 1, 'message' => 'Transaction'.config('constants.flash.deleted'), 'data' =>['gen_no'=>$transaction->gen_no]]);
    }

    public function multidestroy(Request $request)
    {
         Log::info("TransactionController->multidestroy :- Inside ");
        $transactions = explode(',', $request->id);

        $organization_id = Session::get('organization_id');

        $transaction_list = [];

        foreach ($transactions as $transaction_id) {

            $transaction = Transaction::findOrFail($transaction_id);

            $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();

            if($transaction->reference_id != null){
                $reference_status = Transaction::find($transaction->reference_id);
            }

            $transaction_field = TransactionFieldValue::where('transaction_id', $transaction->id)->get();

            if(count($transaction_field) > 0) {
                foreach($transaction_field as $id) {
                    //TransactionFieldValue::where('id', $id->id)->first()->delete();
                }
            }

            $transaction_type = AccountVoucher::find($transaction->transaction_type_id);
            
            if($transaction->approval_status == 1) 
            {
                if($transaction_type->name == "credit_note" || $transaction_type->name == "goods_receipt_note") {

                    if($transaction->transaction_type_id != null)
                    {
                        $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                        ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                        ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                        ->first();
                    }
                    

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::where('transaction_id',$item->transaction_id)->first();

                        //$inventory_item_batch = InventoryItemBatch::find($item->batch_id);
                        

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();

                        if($stock != null) {

                            //$inventory_stock = $stock->in_stock - $item->quantity;

                            if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }
                            
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);                            

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                            $stock->save();

                        /* Item Batch Update */

                        if($inventory_item_batch != null)
                        {
                            /*if($inventory_item_batch->quantity <= $item->quantity)
                            {
                                $inventory_item_batch->quantity= 0.00;
                            }
                            else{
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                            }

                            $inventory_item_batch->save();*/

                            InventoryItemBatch::where('id', $inventory_item_batch->id)->first()->delete();
                        }   

                        /* End */
                        }
                        
                    }
                }


                if( $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" ) {

                    if($transaction->transaction_type_id != null)
                    {
                        $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                        ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                        ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                        ->first();
                    }               

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    foreach ($items as $item) 
                    {
                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        //dd($inventory_item_batch);

                        /*$data = Custom::get_least_closest_date(json_decode($stock->data, true));

                        $qty = $data['quantity'];*/ 

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();

                        if($stock != null) {

                            $inventory_stock = $stock->in_stock + $item->quantity;
                             
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);
                            

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            
                            $stock->save();


                            /* Item Batch Update */

                            if($inventory_item_batch != null)
                            {
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity + $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */
                        }   
                    }
                }

                if($transaction_type->name == "delivery_note" ) {

                    if($transaction->transaction_type_id != null)
                    {
                        $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                        ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                        ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                        ->first();
                    }

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    if($stock_item_update->stock_update == 0 && $reference_status->approval_status == 1){

                        foreach ($items as $item) 
                        {
                            $stock = InventoryItemStock::find($item->item_id);

                            $inventory_item = InventoryItem::find($item->item_id);

                            $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                            //dd($inventory_item_batch);

                            /*$data = Custom::get_least_closest_date(json_decode($stock->data, true));

                            $qty = $data['quantity'];*/ 

                            $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                            if($inventory_item->purchase_tax_id != null)
                            {
                                $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                                $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                            }
                            else{
                                $purchase_tax_price = $inventory_item->purchase_price;
                            }

                            $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                            ->where('transaction_items.transaction_id', $transaction->id)
                            ->where('transaction_items.item_id', $item->item_id)
                            ->first();

                            if($stock != null) {

                                $inventory_stock = $stock->in_stock + $item->quantity;
                                 
                                $stock->in_stock = $inventory_stock;
                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);
                                

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 0];

                                $stock->data = json_encode($data);


                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                                
                                $stock->save();


                                /* Item Batch Update */

                                if($inventory_item_batch != null)
                                {
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity + $item->quantity;

                                    $inventory_item_batch->save();
                                }   

                                /* End */
                            }   
                        }

                    }
                }



                if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
                {
                    Custom::delete_revenue('total_revenue',$transaction->total);
                }
            }


            $transaction_list[] = $transaction_id;
            $transaction->delete();

            if(!empty($transaction->entry_id)) {
                AccountEntry::where('account_entries.id', $transaction->entry_id)->first()->delete();
            }

            /*over all addon transaction delete*/

                Custom::delete_addon('transaction');

                //Custom::delete_revenue('transaction');

            /* end */

            /* If we need separately addon delete use this */

                /*if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
                {
                    Custom::delete_addon('invoice');
                } 
                if($transaction_type->name == "purchases"){

                    Custom::delete_addon('purchase');
                }
                if($transaction_type->name == "goods_receipt_note"){

                    Custom::delete_addon('grn');
                }
                if($transaction_type->name == "job_card"){
                    Custom::delete_addon('job_card');
                }*/

            /*end*/
            
        }
         Log::info("TransactionController->multidestroy :- Return ");

        return response()->json(['status'=>1, 'message'=>'Transaction'.config('constants.flash.deleted'),'data'=>['list' => $transaction_list]]);
    }

    public function multiapprove(Request $request)
    {       
        Log::info("TransactionController->multiapprove :- Inside ");
        $transactions = explode(',', $request->id);
        $datas = Transaction::find($request->id);
        $organization_id =session::get('organization_id');      

        $sms_date =Carbon::now();
        $current_date =  $sms_date->format('d-m-Y');

        if($organization_id){
            $org_id = $organization_id;
        }else{
            $org_id = session::get('organization_id');
        }  
            
        
        $id=$request->id;

        $sms_content_requerment=Transaction::select('vehicle_register_details.registration_no as vehicle_no','transactions.name','transactions.mobile')->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')->where('transactions.id',$id)->get();
        
        foreach ($sms_content_requerment as $key => $value) {
            $vehicle=$value->vehicle_no;
            $mobile_no=$value->mobile;
            $customer_name=$value->name;
        }   
        
        $transaction_list = [];

        $organization_id = Session::get('organization_id');
        $organization = Organization::findOrFail($organization_id);

        foreach ($transactions as $transaction_id) {
            $find_status = Transaction::findOrFail($request->input('id'));

        if($find_status->approval_status == 1) {
            return response()->json(array('status' => 1, 'message' => 'Approved transactions cannot be updated.', 'data' => []));
        }
        else
        {

            $transaction_last = Transaction::select('transactions.*', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no',  
             DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code')
             ,DB::raw('transactions.total + wms_transactions.advance_amount as total_amount'),'transactions.approval_status','transactions.reference_id');

            $transaction_last->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $transaction_last->leftJoin('people', function($join) use($organization_id)
                {
                    $join->on('people.person_id','=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
                });
            $transaction_last->leftJoin('people AS business', function($join) use($organization_id)
                {
                    $join->on('business.business_id','=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->where('transactions.user_type', '1');
                });

            $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
            $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');        
           
            $transaction_last->where('transactions.id', $transaction_id);

            $transaction = $transaction_last->first();

            //dd($transaction);
            //dd($transaction->id);

            $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();

            

            if($transaction->reference_id != null){
                $reference_status = Transaction::find($transaction->reference_id);
            }       


            $vehicle_note = $transaction->vehicle_note;

            if($vehicle_note == null){
                $vehicle_note = "No Specific Notes";
            }else{
                $vehicle_note = $transaction->vehicle_note;
            }

            $business = Organization::select('businesses.alias AS business')
            ->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
            ->where('organizations.id', $organization_id)->first()->business;

            $transaction_type = AccountVoucher::find($transaction->transaction_type_id);
            
            
            /* Sale return is credit note , item increase to inventory*/

            if($transaction_type->name == "credit_note" || $transaction_type->name == "purchases" )
            {
                
                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

            
    
                if($stock_item_update->stock_update == 1 && $transaction->approval_status == 0){

                    foreach ($items as $item) 
                    {

                        //if($item->tax_id != null){

                        $selected_item = InventoryItem::find($item->item_id);

                        $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                        ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                        ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                        ->where('tax_groups.organization_id', $organization_id)
                        ->where('tax_groups.id', $item->tax_id)
                        ->groupby('tax_groups.id')
                        ->first();
                        
                        
                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                         ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                         ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                         ->where('tax_groups.organization_id', $organization_id)
                         ->where('tax_groups.id', $item->tax_id)
                         ->groupby('tax_groups.id')->first();   
                         
                        

                        $sale_price_array = json_decode($selected_item->sale_price_data, true);

                        $sale_price = Custom::two_decimal($item->rate);

                        $new_selling_price = $item->new_selling_price;

                        if($new_selling_price != $sale_price){
                            $new_selling_price = $item->new_selling_price;

                        }else{
                            $new_selling_price = $sale_price;
                        }
                        
                        if($sales_tax_value != null){

                            //$update_price =  Custom::two_decimal( $new_selling_price / (($sales_tax_value->value/100) + 1));

                            $tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($new_selling_price));

                            $update_price = Custom::two_decimal($new_selling_price + $tax_amount);
                        }else{
                            $update_price =  Custom::two_decimal( $new_selling_price);
                        }
                        

                        foreach ($sale_price_array as $key => $value) {
                            if($value['on_date'] == $transaction->date) {
                                unset($sale_price_array[$key]);
                            }
                        }


                        $sale_price_data = array_values($sale_price_array);

                        $sale_price_data[] = ["list_price" => $new_selling_price, "discount" => 0, "discount_amount" => 0.00,  "sale_price" => $update_price, "on_date" => $transaction->date];
                                              

                        $selected_item->purchase_price = $item->rate;
                        $selected_item->selling_price = $new_selling_price;
                        $selected_item->base_price = $update_price;
                        $selected_item->purchase_tax_id = $item->tax_id;
                        $selected_item->sale_price_data = json_encode($sale_price_data);
                        $selected_item->save();
                        

    
                        /* Inventory Stock Update */

                        $stock = InventoryItemStock::find($item->item_id);
                        Log::info("TransactionController->multiapprove :- after Line No 4565 ".$stock);
                        if(!$stock) {
                            $stock = new InventoryItemStock();
                            $stock->in_stock = 0;
                        }
                        
                        if($selected_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($selected_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($selected_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $selected_item->purchase_price;
                        }

                        //$inventory_item = InventoryItem::find($item->item_id);

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)->first();

                        if($stock != null) {

                            $inventory_stock = $stock->in_stock + $item->quantity;
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);

                            //$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $selected_item->base_price,'status' => 1];

                            $stock->data = json_encode($data);
                            $stock->save();

                            


                            /* Account Transaction */

                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $data_entry[] = ['debit_ledger_id' => $selected_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            /* End */

                            //$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);


                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, $transaction_type_name->voucher_type, $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);

                        
                            
                            $stock->save();


                        

                        }
                        Log::info("TransactionController->multiapprove :- after Line No 4620 ".$stock);

                        /* Inventory Stock Update - End */

                        //$voucher = AccountEntry::where('id',$stock->entry_id)->first();
                                
                        $voucher_reference = Transaction::find($transaction->reference_id); 

                        $batch_date = str_replace('-', '', $transaction->date);

                        $inventory_item_batch = new InventoryItemBatch;

                        $inventory_item_batch->item_id = $item->item_id;
                        $inventory_item_batch->global_item_model_id = $selected_item->global_item_model_id;

                        $inventory_item_batch->batch_number = $batch_date.'/'.$selected_item->id.'/'.$transaction->order_no;

                        $inventory_item_batch->purchase_price = $selected_item->purchase_price;
                        $inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
                        $inventory_item_batch->selling_price = $selected_item->selling_price;
                        $inventory_item_batch->selling_plus_tax_price = $selected_item->base_price;

                        $inventory_item_batch->purchase_tax_id = $selected_item->purchase_tax_id;

                        $inventory_item_batch->sales_tax_id = $selected_item->tax_id;

                        $inventory_item_batch->quantity = $item->quantity;
                        $inventory_item_batch->unit_id = $selected_item->unit_id;
                        $inventory_item_batch->transaction_id = $transaction->id;
                        $inventory_item_batch->user_type = $transaction->user_type;
                        $inventory_item_batch->people_id = $transaction->people_id;
                        $inventory_item_batch->organization_id = $organization_id;

                        $inventory_item_batch->save();
                        Custom::userby($inventory_item_batch, true);


                        /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        
                        Log::info("TransactionController->multiapprove :- after Line No 4663 ".json_encode($stock));
                        Log::info("TransactionController->multiapprove :- after Line No 4664 ".$stock->id);
                        $model->inventory_item_stock_id = $stock->id;
                    

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $selected_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/
                    }
                }
            }

            if($transaction_type->name == "goods_receipt_note")
            {
                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 0 && $transaction->approval_status == 0){

                    foreach ($items as $item) 
                    {
                        //if($item->tax_id != null){

                        $selected_item = InventoryItem::find($item->item_id);

                        $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                        ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                        ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                        ->where('tax_groups.organization_id', $organization_id)
                        ->where('tax_groups.id', $item->tax_id)
                        ->groupby('tax_groups.id')
                        ->first();
                        

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                         ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                         ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                         ->where('tax_groups.organization_id', $organization_id)
                         ->where('tax_groups.id', $selected_item->purchase_tax_id)
                         ->groupby('tax_groups.id')->first();                   

                        $sale_price_array = json_decode($selected_item->sale_price_data, true);

                        $sale_price = Custom::two_decimal($item->rate);

                        $new_selling_price = $item->new_selling_price;

                        if($new_selling_price != $sale_price){
                            $new_selling_price = $item->new_selling_price;

                        }else{
                            $new_selling_price = $sale_price;
                        }
                        
                        if($sales_tax_value != null){

                            //$update_price =  Custom::two_decimal( $new_selling_price / (($sales_tax_value->value/100) + 1));

                            $tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($new_selling_price));

                            $update_price = Custom::two_decimal($new_selling_price + $tax_amount);
                        }else{
                            $update_price =  Custom::two_decimal( $new_selling_price);
                        }
                        

                        foreach ($sale_price_array as $key => $value) {
                            if($value['on_date'] == $transaction->date) {
                                unset($sale_price_array[$key]);
                            }
                        }


                        $sale_price_data = array_values($sale_price_array);

                        $sale_price_data[] = ["list_price" => $new_selling_price, "discount" => 0, "discount_amount" => 0.00,  "sale_price" => $update_price, "on_date" => $transaction->date];
                                              

                        $selected_item->purchase_price = $item->rate;
                        $selected_item->selling_price = $new_selling_price;
                        $selected_item->base_price = $update_price;
                        $selected_item->purchase_tax_id = $item->tax_id;
                        $selected_item->sale_price_data = json_encode($sale_price_data);

                        $selected_item->save();


                        /* Inventory Stock Update */

                        $stock= InventoryItemStock::find($item->item_id);

                        if($selected_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($selected_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($selected_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $selected_item->purchase_price;
                        }

                        //$inventory_item = InventoryItem::find($item->item_id);

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)->first();

                        if($stock != null) {

                            $inventory_stock = $stock->in_stock + $item->quantity;
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);

                            //$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $selected_item->base_price,'status' => 1];

                            $stock->data = json_encode($data);
                            $stock->save();


                            /* Account Transaction */

                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $data_entry[] = ['debit_ledger_id' => $selected_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            /* End */

                            //$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            
                            $stock->save();

                        

                        }

                        /* Inventory Stock Update - End */

                        //$voucher = AccountEntry::where('id',$stock->entry_id)->first();
                                
                        $voucher_reference = Transaction::find($transaction->reference_id); 

                        $batch_date = str_replace('-', '', $transaction->date);

                        $inventory_item_batch = new InventoryItemBatch;

                        $inventory_item_batch->item_id = $item->item_id;
                        $inventory_item_batch->global_item_model_id = $selected_item->global_item_model_id;

                        $inventory_item_batch->batch_number = $batch_date.'/'.$selected_item->id.'/'.$transaction->order_no;

                        $inventory_item_batch->purchase_price = $selected_item->purchase_price;
                        $inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
                        $inventory_item_batch->selling_price = $selected_item->selling_price;
                        $inventory_item_batch->selling_plus_tax_price = $selected_item->base_price;

                        $inventory_item_batch->purchase_tax_id = $selected_item->purchase_tax_id;

                        $inventory_item_batch->sales_tax_id = $selected_item->tax_id;

                        $inventory_item_batch->quantity = $item->quantity;
                        $inventory_item_batch->unit_id = $selected_item->unit_id;
                        $inventory_item_batch->transaction_id = $transaction->id;
                        $inventory_item_batch->user_type = $transaction->user_type;
                        $inventory_item_batch->people_id = $transaction->people_id;
                        $inventory_item_batch->organization_id = $organization_id;

                        $inventory_item_batch->save();
                        Custom::userby($inventory_item_batch, true);


                        /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $selected_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/
                    }
                }
            }
            

            /* Purchase return is debit note , item reduse to inventory */

            if($transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" )
            {

                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }
                

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();
                
                    

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);                      

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();


                        if($request->gen_no){
                            $gen_no = $request->gen_no;
                        }
                        else{
                            $gen_no = null;
                        }

                        /*$getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transactions->id );
                
                        $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;*/

                        if($stock != null) {

                            if($request->status == "1") {

                                $inventory_stock = $stock->in_stock - $item->quantity;

                                /*if($stock->in_stock <= $item->quantity)
                                {
                                    $inventory_stock = 0.00;
                                }else{
                                    $inventory_stock = $stock->in_stock - $item->quantity;
                                }*/

                                

                                $stock->in_stock = $inventory_stock;
                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);

                            } 
                            else if($request->status == "0") {

                                $inventory_stock = $stock->in_stock + $item->quantity;

                                $stock->in_stock = $inventory_stock;

                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            }                       

                            $stock->save();


                        /* Inventory Item Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                    $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;
                        
                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/


                            /* Item Batch Update */

                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity= 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }
                                    */
                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */

                        }                       
                    }
            }

            if($transaction_type->name == "sales" )
            {
                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 1 && $transaction->approval_status == 0){

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();


                        if($request->gen_no){
                            $gen_no = $request->gen_no;
                        }
                        else{
                            $gen_no = null;
                        }

                        /*$getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transactions->id );
                
                        $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;*/

                        if($stock != null) {

                            if($request->status == "1") {

                                $inventory_stock = $stock->in_stock - $item->quantity;

                                /*if($stock->in_stock <= $item->quantity)
                                {
                                    $inventory_stock = 0.00;
                                }else{
                                    $inventory_stock = $stock->in_stock - $item->quantity;
                                }*/                             

                                $stock->in_stock = $inventory_stock;
                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);

                            } 
                            else if($request->status == "0") {

                                $inventory_stock = $stock->in_stock + $item->quantity;

                                $stock->in_stock = $inventory_stock;

                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            }

                            $stock->save();


                        /* Inventory Item Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/


                            /* Item Batch Update */

                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity= 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }*/

                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */

                        }
                    }

                }   
            }

            if($transaction_type->name == "delivery_note")
            {
                if($transaction->transaction_type_id != null)
                {
                    $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                    ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                    ->first();
                }

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                if($stock_item_update->stock_update == 0 && $reference_status->approval_status == 1 && $transaction->approval_status == 0){

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);                      

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)
                        ->first();


                        if($request->gen_no){
                            $gen_no = $request->gen_no;
                        }
                        else{
                            $gen_no = null;
                        }

                        /*$getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transactions->id );
                
                        $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;*/

                        if($stock != null) {

                            if($request->status == "1") {

                                $inventory_stock = $stock->in_stock - $item->quantity;

                                /*if($stock->in_stock <= $item->quantity)
                                {
                                    $inventory_stock = 0.00;
                                }else{
                                    $inventory_stock = $stock->in_stock - $item->quantity;
                                }*/                             

                                $stock->in_stock = $inventory_stock;
                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);

                            } 
                            else if($request->status == "0") {

                                $inventory_stock = $stock->in_stock + $item->quantity;

                                $stock->in_stock = $inventory_stock;

                                $stock->date = $transaction->date;
                                $data = json_decode($stock->data, true);

                                $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                                /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                                $stock->data = json_encode($data);

                                $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                                $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                                /*$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);*/

                                $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                            }                       

                            $stock->save();


                            /* Inventory Item Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/


                            /* Item Batch Update */

                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity= 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }*/

                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */

                        }                       
                    }
                }   
            }
        }


            
        $transaction->approval_status = $request->status;
        $transaction->save();





        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" ) {

            if($transaction->approval_status == 1) 
            {
                Custom::add_revenue('total_revenue', $transaction->total);
            }

        }


    if($transaction_type->name == 'purchases')  
        {
            if($transaction->approval_status == 1) 
            {
                if($transaction->term_id != null)
                {

                    $term = Term::select('name')->where('id',$transaction->term_id)->where('organization_id',Session::get('organization_id'))->first();

                    $term_name = $term->name;
                }
                else
                {
                    $term_name = 'null';
                }

                if($term_name == 'on_receipt')
                {
                    $voucher_type = "payment";
                    $reference_voucher_id = $transaction->id;
                    $payment_mode_id = $transaction->payment_mode_id;
                    
                    $credit_ledger = AccountLedger::select('id')->where('name','=','Cash')->where('organization_id',$organization_id)->first();

                    $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();

                        $business_id = '';
                        $person_id = '';
                        $name = '';

                        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

                        $people = People::find($transaction->people_id);

                        /*if($transaction->user_type == 1){
                            $business = People::select('display_name','id')->where('business_id',$transaction->people_id)->where('organization_id',$organization_id)->first();
                            $business_id = $business->id;
                            $name = $business->display_name;
                        }
                        elseif($transaction->user_type == 0){
                            $people = People::select('display_name','id')->where('people_id',$transaction->people_id)->where('organization_id',$organization_id)->first();
                            
                            $person_id = $people->id;
                            $name = $people->display_name;
                        }*/

                        $account_ledgers = AccountLedger::select('account_ledgers.id');
                        if($transaction->user_type == 0) {
                            $account_ledgers->where('person_id', $transaction->people_id);
                            $person_id = $transaction->people_id;
                            $business_id = null;
                        } else if($transaction->user_type == 1) {
                            $account_ledgers->where('business_id', $transaction->people_id);
                            $person_id = null;
                            $business_id = $transaction->people_id;
                        }

                        $account_ledger = $account_ledgers->first();


                    $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                
                    $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
                    
                     if($vou_restart_value->restart == 0)
                      {
                          $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                          Log::info("TransactionController->create :- after  line no 5487 if Custom::gen_no - ".$gen_no);
                      }
                      else
                      {
                           $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                            Log::info("TransactionController->create :- after line no  5492 Custom::gen_no - ".$gen_no);
                      }

                    if($account_ledger != null) {
                        $customer_ledger = $account_ledger->id;
                    }
                    else 
                    {
                        $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
                        
                        
                    }

                    $cash_payment = ['voucher_type'=>$voucher_type,'reference_voucher_id'=>$reference_voucher_id,'payment_mode'=>$payment_mode_id,'debit_ledger_id'=>$customer_ledger,'credit_ledger_id'=>$credit_ledger->id,'amount'=>$transaction->total];    

                    $entry_data[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $credit_ledger->id, 'amount' => $transaction->total];

                                
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry_data,$transaction->entry_id, $transaction_type->name, $organization_id, 1, false, null,$gen_no, null, $transaction->id, null, null, $transaction->payment_mode_id,null,null,$cash_payment);                
                            

                }
            }
        }
        
        
        
        if($transaction_type->name == "sales" && $transaction->approval_status == 1) {
            
            $pay_method_id = Term::select('name')->where('id',$transaction->term_id)->where('organization_id',$organization_id)->first();

            $custom_values = OrgCustomValue::select('data1 as data1')
                         ->where('screen','invoice')
                         ->where('organization_id',$organization_id)
                         ->first();

            if($pay_method_id != null  && $custom_values != null)
            {
                if($pay_method_id->name == "on_receipt" && $transaction->payment_mode_id == 1 && $custom_values->data1 == 1)
                {
                    

                    $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
                    $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;
                    $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                    $person = People::select('id as people_org_id','user_type', 'display_name');

                    if($transaction->user_type == 0) {
                        $person->where('person_id', $transaction->people_id);
                    } 
                    else if($transaction->user_type == 1) {
                        $person->where('business_id', $transaction->people_id);
                    }
                    
                    $person->where('organization_id', $organization_id);

                    $persons = $person->first();

                    $business_id = '';
                    $person_id = '';
                    $name = '';

                    $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

                    $account_ledgers = AccountLedger::select('account_ledgers.id');

                    if($transaction->user_type == 0) {
                      $account_ledgers->where('person_id',$transaction->people_id);
                      $person_id = $transaction->people_id;
                      $business_id = null;
                    }
                    else if($transaction->user_type == 1) {
                      $account_ledgers->where('business_id',$transaction->people_id);
                      $business_id = $transaction->people_id;
                      $person_id = null;
                    }
        
                    $account_ledgers->where('organization_id', $organization_id);

                    $account_ledger = $account_ledgers->first();        

        
                    if($account_ledger != null){
                        $customer_ledger = $account_ledger->id;
                    }
                    else
                    {
                        $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
                    }



                            


                    $cash_entry = [];

                    $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

                    $cash_transaction_id = null;
                            $method= '';
                    

                     if($method == "update") {
                        $cash_transaction = AccountEntry::select('account_entries.id')
                        ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                        ->where('account_entries.reference_transaction_id', $transaction->id)
                        ->where('account_entries.voucher_id', $transaction_type->id)
                        ->first();

                        if($cash_transaction != null) {
                            $cash_transaction_id = $cash_transaction->id;
                        }

                    }

                    Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);
                }
            }


            


        }
        

        if($transaction_type->name == "job_invoice" && $transaction->approval_status == 1) 
        {
            //dd($transaction_type->name);
            $pay_terms_id = WmsTransaction::select('wms_transactions.payment_terms')->where('transaction_id',$transaction->id)->first();
            $custom_values = OrgCustomValue::select('data1 as data1')
                     ->where('screen','job_invoice')
                     ->where('organization_id',$organization_id)
                     ->first();
            //dd($pay_terms_id);
            if($pay_terms_id != null && $custom_values != null)
            {
                if($pay_terms_id->payment_terms == 1 && $transaction->payment_mode_id == 1)
                {

                              
                        if($custom_values->data1 == 1)
                        {
                            //dd($custom_values);

                            $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
                    
                            $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                            $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                    $person = People::select('id as people_org_id','user_type', 'display_name');

                    if($transaction->user_type == 0) {
                        $person->where('person_id',$transaction->people_id);
                    } 
                    else if($transaction->user_type == 1) {
                        $person->where('business_id',$transaction->people_id);
                    }
                    
                    $person->where('organization_id', $organization_id);

                    $persons = $person->first();

                    $business_id = '';
                    $person_id = '';
                    $name = '';

                    $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

                    $account_ledgers = AccountLedger::select('account_ledgers.id');

                    if($transaction->user_type == 0) {
                      $account_ledgers->where('person_id',$transaction->people_id);
                      $person_id = $transaction->people_id;
                      $business_id = null;
                    }
                    else if($transaction->user_type == 1) {
                      $account_ledgers->where('business_id',$transaction->people_id);
                      $business_id = $transaction->people_id;
                      $person_id = null;
                    }
        
                    $account_ledgers->where('organization_id', $organization_id);

                    $account_ledger = $account_ledgers->first();        

        
                    if($account_ledger != null){
                        $customer_ledger = $account_ledger->id;
                    }
                    else
                    {
                        $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
                    }

                            $cash_entry = [];

                            $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

                            $cash_transaction_id = null;
                            $method= '';
                             if($method == "update") {
                                $cash_transaction = AccountEntry::select('account_entries.id')
                                ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                                ->where('account_entries.reference_transaction_id', $transaction->id)
                                ->where('account_entries.voucher_id', $transaction_type->id)
                                ->first();

                                if($cash_transaction != null) {
                                    $cash_transaction_id = $cash_transaction->id;
                                }

                            }

                            Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'wms_receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);
                        }
                                
                    
                }
            }
                    


            

        }

        /*if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "job_invoice") */

        /*  if($transaction_type->name == "purchases")
            {               
                $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                
                //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
          $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;


                $entry_data[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $credit_ledger->id, 'amount' => $transaction->total];

                if($transaction_type->name == "purchases")
                {               
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry_data,$transaction->entry_id, $transaction_type->name, $organization_id, 1, false, null,$gen_no, null, $transaction->id, null, null, $transaction->payment_mode_id,null,null,$cash_payment);                
                }else
                {
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry_data, null, $transaction_type->name, $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id,null,null,null);
                }
            
            
            }   */  


            $reff_id = $datas->reference_id;
            $data = WmsTransaction::where('transaction_id',$reff_id)->first();
            if($data)
            {
                
                if($transaction_type->name == 'job_invoice_cash' || $transaction_type->name == 'job_invoice')
                {
                    //dd($data->transaction_id);
                    DB::table('wms_transactions')->where('jobcard_status_id','!=', null)->where('transaction_id',$data->transaction_id)->update(['jobcard_status_id'=> "8"]);
                }
            }   

            
            $business_name = Session::get('business');

            if($transaction->approval_status == 1)
            {
                if($transaction_type->name == "purchases"|| $transaction_type->name == "receipt" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "job_request" || $transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "credit_note" || $transaction_type->name == "debit_note") 
                {

                    switch ($transaction_type->name) 
                    {
                        case 'receipt':                     

                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Payment of Rs. ".$transaction->total." for Invoice:".$transaction->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Receipt";
                            break;

                        case 'purchase_order':
                            $sms_content = "You have a new purchase order from ".$business_name." for Rs. ".$transaction->total. "\n\n" ."Your Propel ID: ".$transaction->code;
                            $mge ="Purchase Order";
                            break;

                        case 'purchases':
                            $sms_content = "You have a new purchase from ".$business_name." for Rs. ".$transaction->total. "\n\n" ."Your Propel ID: ".$transaction->code;
                            $mge ="Purchase";
                            break;  

                        case 'sale_order':
                            
                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Sale Order of Rs. ".$transaction->total." for Sale Order Number:".$transaction->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Sale Order";
                            break;

                            case 'estimation':
                            

                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Estimation of Rs. ".$transaction->total." for Estimation Number:".$transaction->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Estimation";
                            break;

                        case 'sales':
                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transaction->total." for Invoice Number:".$transaction->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Credit Sale";
                            break;

                        case 'sales_cash':
                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transaction->total." for Invoice Number:".$transaction->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Cash Sale";
                            break;

                        case 'delivery_note':
                            $sms_content = "Dear ".$transaction->customer.",". "\n\n" ."Your order for ".$transaction->reference_no. " of Rs. ".$transaction->total." has been delivered. Ref: ".$transaction->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                            $mge ="Delivery Note";
                            break;

                        case 'job_card':
                            /*$sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Your Jobcard Number:".$transactions->order_no." "."for vehicle"." "..$vehicle." "."Created on"." ".$current_date." "."."."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;*/
                            $url=url('jc_acknowladge/');
                            $sms_content ="Please note the Jobcard"." ".$transaction->order_no." "."for Vehicle ".$vehicle." "."dated ".$current_date."."."\n\n"."Vehicle Note: ".$vehicle_note."\n\n"."Visit below link for the Status of Job. " . $url . '/' . $id. '/'.$org_id;
                            $mge ="Job Card";
                            break;

                        case 'job_request':
                            $url=url('viewlist/');
                            $sms_content="Click  this link to approve estimation  for your vehicle : ".$vehicle." ". $url . '/' . $id. '/'.$org_id."\r\n".$customer_name;
                            $mge ="Estimation link ";
                            break;

                        case 'job_invoice':
                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transaction->total." for Invoice Number:".$transaction->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            
                            $mge = "Invoice";
                            break;

                        case 'job_invoice_cash':
                            $sms_content =  "Dear ".$transaction->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transaction->total." for Invoice Number:".$transaction->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transaction->code;
                            $mge ="Invoice";

                            break;
                    }

                    if($transaction->mobile != "")
                    {
                        /*$msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);*/

                        Custom::add_addon('sms');
                    }

                    
                }
            }

            if($transaction->entry_id != null) {
                $entry = AccountEntry::find($transaction->entry_id);
                $entry->status = $request->status;
                $entry->save();
            }

            $transaction_list[] = $transaction_id;          
        }
        
                 Log::info("TransactionController->multiapprove :- return ");
    
        return response()->json(['status' => 0, 'message' => 'Transaction Approved & SMS Sent' , 'data'=>['list' => $transaction_list]], 200);

        //return response()->json(['status'=>1, 'message'=> $transaction_message,'data'=>['list' => $transaction_list]], 200);
    }

    public function get_item_rate(Request $request) {       

         Log::info("TransactionController->get_item_rate :- Inside ");
        $organization_id = Session::get('organization_id');
        $transaction_module = Session::get('module_name');

        
        if($transaction_module == 'trade_wms')
        {
            /*Segment*/

            $vehicle_variant_id = VehicleRegisterDetail::findOrFail($request->vehicle_id)->vehicle_variant_id;

            //dd($vehicle_variant_id);

            $variant_name = VehicleVariant::findOrFail($vehicle_variant_id)->name;

            $segments = VehicleSegmentDetail::where('vehicle_variant_name',$variant_name)->where('vehicle_variant_id',$vehicle_variant_id)->first();

            if($segments != null)
            {
                $segment_id = VehicleSegmentDetail::where('vehicle_variant_name',$variant_name)->where('vehicle_variant_id',$vehicle_variant_id)->first()->vehicle_segment_id;
            }

            $item_id = WmsPriceList::where('inventory_item_id',$request->id)->first();

            /*End Segment*/


            /*Spec*/

            $vehicle_spec = RegisteredVehicleSpec::where('registered_vehicle_id',$request->vehicle_id)->get();
            

            $spec_id = [];
            $spec_value = [];

            foreach ($vehicle_spec as $key => $value) {
                //$spec_id = $vehicle_spec[$key]->spec_id;
                array_push($spec_id, $vehicle_spec[$key]->spec_id);
                array_push($spec_value, $vehicle_spec[$key]->spec_value);
            }

            
            $VechicleSpec=VehicleSpecification::whereIn('vehicle_spec_id',$spec_id)->get();

            if($spec_value != null) {

            $VechicleSpecName = VehicleSpecificationDetails::select('vehicle_specification_details.id AS value_id','vehicle_specification_details.vehicle_specifications_id','vehicle_specification_details.name','vehicle_specifications.used','vehicle_specifications.pricing')
            ->leftjoin('vehicle_specifications','vehicle_specifications.vehicle_spec_id','=','vehicle_specification_details.vehicle_specifications_id')
            ->where('vehicle_specifications.used','=',1)
            ->where('vehicle_specifications.pricing','=',1)
            ->whereIn('name',$spec_value)
            ->get();

            //dd($VechicleSpecName);

            $value_id = [];
            $spec_array=[];
            $spec_key=1;

            foreach ($VechicleSpecName as $key => $value) { 

                $spec_array["wms_price_lists.spec_value".$spec_key]=$VechicleSpecName[$key]->value_id;
                $spec_key++;
                //array_push($value_id, $VechicleSpecName[$key]->value_id);
            }

            }

            /*End Spec*/

            //dd($spec_array);
        }   
        

        $category_type = InventoryItem::select('inventory_items.id AS item_id','inventory_items.category_id','inventory_categories.category_type_id')
        ->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.id', $request->id)
        ->first();
        

        if($transaction_module != 'trade_wms')
        {
            $item_batch = InventoryItemBatch::where('item_id',$request->id)->where('quantity','>', 0)->get();

            $service_batches = WmsPriceList::where('inventory_item_id',$request->id)->where('organization_id', $organization_id)->get();

            /*$item_batch =  DB::select("SELECT 
              inventory_item_batches.id,
              inventory_item_batches.item_id,
             b.batch_quantity
            FROM
              inventory_item_batches CROSS JOIN (SELECT SUM(
                inventory_item_batches.quantity
              ) AS batch_quantity 
              FROM inventory_item_batches 
              WHERE item_id = '".$request->id."') b
              WHERE inventory_item_batches.quantity > 0 AND
              inventory_item_batches.item_id = '".$request->id."' ");*/ 

            /* Just using for + sign batch item*/

                if(count($item_batch) > 1){
                    foreach ($item_batch as $batch) {
                        $batch_id = $batch->id;
                        $single_batch_id = null;
                        //$batch_quantity = $batch->batch_quantity;
                    }
                }
                elseif (count($item_batch) == 1) {
                    foreach ($item_batch as $batch) {
                        $single_batch_id = $batch->id;
                        $batch_id = null;
                        //$batch_quantity = $batch->batch_quantity;
                    }
                }
                else{
                    $batch_id = null;
                    $single_batch_id = null;
                    //$batch_quantity = null;
                }

            /*end*/


            if(count($service_batches) > 0){                    
                foreach ($service_batches as $service_batch) {
                    $service_batch_id = $service_batch->id;         
                }
            }else{
                $service_batch_id = null;
            }


            /*if($item_batch == null){*/

            $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id','inventory_item_batches.quantity AS batch_stock')

            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

            ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

            ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')

            //->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')

            ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')

            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )

            ->leftjoin('inventory_item_batches', 'inventory_items.id', '=', 'inventory_item_batches.item_id')

            ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )

            ->where('inventory_items.organization_id', $organization_id)
            ->where('inventory_items.id', $request->id)         
            ->first();
            /*}
            else{
                $item = [];
            }*/     

        }
        

        if($transaction_module == 'trade_wms')
        {
            
            $item_batch = InventoryItemBatch::where('item_id',$request->id)->where('quantity','>', 0)->get();

            $service_batches = WmsPriceList::where('inventory_item_id',$request->id)->where('organization_id', $organization_id)->get();


            //dd($service_batches);

            /*$item_batch = DB::select("SELECT 
              inventory_item_batches.id,
              inventory_item_batches.item_id,
             b.batch_quantity
            FROM
              inventory_item_batches CROSS JOIN (SELECT SUM(
                inventory_item_batches.quantity
              ) AS batch_quantity 
              FROM inventory_item_batches 
              WHERE item_id = '".$request->id."') b 
              WHERE inventory_item_batches.item_id = '".$request->id."' ");*/
            

            /* Just using for + sign batch item */

                if(count($item_batch) > 1){
                    foreach ($item_batch as $batch) {
                        $batch_id = $batch->id;                     
                        $single_batch_id = null;
                        //$batch_quantity = $batch->batch_quantity;
                    }   

                }
                elseif (count($item_batch) == 1) {
                    foreach ($item_batch as $batch) {
                        $single_batch_id = $batch->id;
                        $batch_id = null;
                        //$batch_quantity = $batch->batch_quantity;
                    }
                }
                else{
                    $batch_id = null;
                    $single_batch_id = null;
                    //$batch_quantity = null;
                }


                if(count($service_batches) > 0){                    
                    foreach ($service_batches as $service_batch) {
                        $service_batch_id = $service_batch->id;         
                    }
                }else{
                    $service_batch_id = null;
                }           
                

            /*end*/

            
            /*if($item_id != null && $segments != null && $segment_id !=null && $spec_array != null)
            {
                $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id','wms_price_lists.price as segment_price')

                ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

                ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

                ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

                ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')      

                ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')           

                ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
                ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
                ->where('inventory_items.organization_id', $organization_id)            
                ->where('inventory_items.id', $request->id)
                ->Where('wms_price_lists.vehicle_segments_id', $segment_id)
                ->Where($spec_array)
                ->first();              
            }*/

            if($item_id != null && $segments != null && $segment_id !=null)
            {
                $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id','wms_price_lists.price as segment_price','inventory_item_batches.quantity AS batch_stock')

                ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

                ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

                ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

                ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')      

                ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')           

                ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )

                ->leftjoin('inventory_item_batches', 'inventory_items.id', '=', 'inventory_item_batches.item_id')

                ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
                ->where('inventory_items.organization_id', $organization_id)
                ->where('inventory_items.id', $request->id)
                //->Where('wms_price_lists.vehicle_segments_id', $segment_id)
                //->Where($spec_array)
                ->first();
            }

            /*if($item_id != null && $spec_array != null && $segment_id ==null)
            {
                $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id','wms_price_lists.price as segment_price')

                ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

                ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

                ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

                ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')      

                ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')           

                ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
                ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
                ->where('inventory_items.organization_id', $organization_id)            
                ->where('inventory_items.id', $request->id)
                //->Where('wms_price_lists.vehicle_segments_id', $segment_id)
                ->Where($spec_array)
                ->first();              
            }*/

            else
            {
                $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id','inventory_item_batches.quantity AS batch_stock')

                ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

                ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

                ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

                ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')

                ->leftjoin('inventory_item_batches', 'inventory_items.id', '=', 'inventory_item_batches.item_id')

                //->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')

                ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')           

                ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
                ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
                ->where('inventory_items.organization_id', $organization_id)
                ->where('inventory_items.id', $request->id)         
                ->first();
            }
        }   

        
        //if(!$item) abort(404);

        //$sale_price = Custom::get_least_closest_date(json_decode($item->sale_price_data, true), Carbon::parse($request->date)->format('d-m-Y'));

        /*if(count($item)>0 && $batch_id == null){*/
            $sale_price = Custom::get_least_closest_date(json_decode($item->sale_price_data, true));


        $return_data = ['price' => $sale_price['price'],'base_price' => $sale_price['list_price'],'moq' => $item->minimum_order_quantity, 'tax_id' => $item->tax_id,'in_stock' => $item->in_stock,'segment_price' => ($item->segment_price != null) ? $item->segment_price : $sale_price['list_price'],'modules' => $transaction_module,'purchase_price' => ($item->purchase_price != null) ? $item->purchase_price : $sale_price['list_price'],'main_category_type' => $item->category_name, 'main_category_id' => $item->main_category_id,'item_batch_id' =>  $batch_id,'item_id' => $item->id, 'single_batch_id' => $single_batch_id,'service_batch_id' => $service_batch_id,'batch_stock' => $item->batch_stock];

        /*}else{
            $sale_price =[];

            $return_data = ['price' => '','base_price' => '','moq' => '', 'tax_id' => '','is_group' => '', 'group' => '','in_stock' => '','segment_price' => '','modules' => '','purchase_price' => '','main_category_type' => '', 'main_category_id' => '', 'item_batch_id' =>  $batch_id];
        }*/

         Log::info("TransactionController->get_item_rate :- Return ");

        return response()->json($return_data);
    }

    public function get_item_details(Request $request) {

         Log::info("TransactionController->get_item_details :- Inside ");
        $organization_id = Session::get('organization_id');

        $query = InventoryItemGroup::select('inventory_items.name',DB::raw('COALESCE(inventory_item_groups.price, "") as price'),'inventory_item_groups.quantity');

        $query->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_item_groups.item_id');        
        $query->where('inventory_item_groups.item_group_id', $request->id);     

        $item = $query->get();

        if(!$item) abort(404);
         Log::info("TransactionController->get_item_details :- return ");

        return response()->json($item);
    }

    public function get_customer_preference(Request $request) {

         Log::info("TransactionController->get_customer_preference :- Inside ");
        //dd($request->all());

        $this->validate($request, [
            'id'  => 'required',
            'type'  => 'required'
        ]);

        $people = People::select('payment_mode_id', 'term_id')->where('person_id', $request->input('id'))->where('user_type', $request->input('type'))->first();
         Log::info("TransactionController->get_customer_preference :- Return ");

        return response()->json($people, 200);
    }

    
    public function get_order_details(Request $request) {
    
         Log::info("TransactionController->get_order_details :- Inside ");
        if(Session::get('organization_id') == "") {
            $organization_id = $request->organization_id;
        } else {
            $organization_id = Session::get('organization_id');
        }

        $transaction_type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first()->id;

        //$job_item_status = VehicleJobItemStatus::where('status', '1')->pluck('name', 'id');
       // $job_item_status->prepend('Select Status', '');
        
        //dd($request->order_id);

		$transactions = Transaction::select('transactions.*', DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") as date'), DB::raw('DATE_FORMAT(transactions.due_date, "%d-%m-%Y") as due_date'),'wms_transactions.registration_id','wms_transactions.vehicle_mileage','wms_transactions.service_type','wms_transactions.jobcard_status_id','wms_transactions.purchase_date','wms_transactions.next_visit_mileage','wms_transactions.vehicle_next_visit','wms_transactions.vehicle_next_visit_reason','vehicle_register_details.engine_no','vehicle_register_details.chassis_no','vehicle_register_details.vehicle_make_id','vehicle_register_details.vehicle_model_id','vehicle_register_details.vehicle_category_id','vehicle_register_details.vehicle_variant_id','wms_transactions.vehicle_note','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.name as name_of_job','wms_transactions.advance_amount','wms_transactions.vehicle_complaints','transactions.reference_no','wms_transactions.job_date','vehicle_register_details.registration_no');

        $transactions->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');

        $transactions->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transactions->where('transactions.id', $request->order_id);
        $transactions->where('transactions.transaction_type_id', $transaction_type);
        $transactions->where('transactions.organization_id',$organization_id);
           
        if($request->type != "job_card"){

            if($request->status != null) {
                $transactions->where('approval_status', $request->status);
            }

        }   

        $transaction = $transactions->first();
        
        if($transaction != null) {

            $items_query = TransactionItem::select('transaction_items.*','vehicle_job_item_statuses.id as item_status','inventory_item_stocks.in_stock','inventory_items.sale_price_data','transaction_items.new_selling_price','inventory_items.name AS item_name','inventory_item_batches.quantity AS batch_stock')

            ->leftjoin('vehicle_job_item_statuses', 'vehicle_job_item_statuses.id', '=', 'transaction_items.job_item_status','transaction_items.new_selling_price')

            ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

            ->leftjoin('inventory_item_batches', 'inventory_item_batches.id', '=', 'transaction_items.batch_id')

            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )

            ->where('transaction_items.transaction_id', $transaction->id);
            
          $result_query = clone $items_query;

          $transaction_items = $result_query->get();

          //dd($transaction_items);

        } else {
            $transaction_items = [];
        }


        if(count($transaction_items) > 0 )
        {
            $condition_query =  clone $items_query;

            $condition_result = $condition_query->pluck('transaction_items.item_id');           

            $item_batch = InventoryItemBatch::whereIn('item_id',$condition_result)->where('quantity','>', 0)->groupBy('item_id')->havingRaw('COUNT(item_id) > 1')->get();

            $service_batch = WmsPriceList::whereIn('inventory_item_id',$condition_result)->groupBy('inventory_item_id')->havingRaw('COUNT(inventory_item_id) > 0')->get();

            //$item_batch = InventoryItemBatch::where('item_id',$condition_result)->where('quantity','>', 0)->get();                        
        }   
        
        

        $list_price = [];

        $new_selling_price=[];

        if(count($transaction_items) > 0 )
        {
            foreach ($transaction_items as $key => $value) {

                if($transaction_items[$key]->item_id == null )
                {
                    $list_price['list_price'][] = '';
                    $list_price['price'][] = '';
                }

                else if($transaction_items[$key]->sale_price_data == null)
                {
                    $list_price['list_price'][] = '';
                    $list_price['price'][] = '';
                }

                else{
                    $list = Custom::get_least_closest_date(json_decode($transaction_items[$key]->sale_price_data,true));    

                    $list_price['list_price'][] = $list['list_price'];
                    $list_price['price'][] = $list['price'];            
                }

                if($transaction_items[$key]->new_selling_price == null){

                    $new_selling_price['new_selling_price'][] = $list_price['list_price'];

                }else{

                    $new_selling_price['new_selling_price'][] = $transaction_items[$key]->new_selling_price;
                }
            }
        }
        else{ // if transaction item has empty
            
            $list_price['list_price'][] = '';
            $list_price['price'][] = '';
            $new_selling_price['new_selling_price'][] = '';
            $transaction_items[] = '';
            $item_batch[] = '';
            $service_batch[] = '';
        }           
         Log::info("TransactionController->get_order_details :- Return ");

        return response()->json(['response' => $transaction, 'data' => $transaction_items,'selling_price' => $list_price['list_price'],'base_price' =>  $list_price['price'],'new_selling_price' => $new_selling_price['new_selling_price'],'item_batch' => $item_batch,'service_batch' => $service_batch]);
    }

    public function get_tax(Request $request) {  

                 Log::info("TransactionController->get_tax :- Inside ");

        //dd($request->id)

        $tax = Tax::select('taxes.id', 'taxes.display_name AS name', 'taxes.value');
        $tax->leftjoin('group_tax', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->leftjoin('tax_groups', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->where('group_tax.group_id', $request->id);
        $tax->where('tax_groups.status', 1);
        $taxes = $tax->get();

                 Log::info("TransactionController->get_tax :- Return ");
        return response()->json($taxes);
    }

    public function get_field_formats(Request $request) {

                 Log::info("TransactionController->get_field_formats :- Inside ");
        $field_formats = TransactionFieldFormat::select('field_formats.id', 'field_formats.name')
        ->where('field_formats.field_type_id', $request->id)->get();  

                 Log::info("TransactionController->get_field_formats :- return ");
        return response()->json($field_formats);

    }

    public function get_transaction_order(Request $request) {  

                 Log::info("TransactionController->get_transaction_order :- Inside ");
            $organization_id = Session::get('organization_id');

            $transaction_type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first();

            if($transaction_type != null) {
                $order = Transaction::where('order_no', $request->order_id)->where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->first();

                if($order == null) {
                    echo 'false';
                } else {
                    echo 'true';
                }
            } else {
                echo 'true';
            }
                             Log::info("TransactionController->get_transaction_order :- Return ");

    }

    public function get_credit_limit(Request $request) {

                 Log::info("TransactionController->get_credit_limit :- Inside ");
        $organization_id = Session::get('organization_id');

        $selected_people = $request->input('selected_people');
        $selected_type = $request->input('selected_type');

        $limit_query = People::select('people.id', 'people.person_id', 'people.first_name', 'people.display_name','people.business_id',
            DB::raw('IF(account_ledger_credit_infos.max_credit_limit IS NULL, business_ledger_credit_infos.max_credit_limit, account_ledger_credit_infos.max_credit_limit) AS max_credit_limit')
        );
        
        $limit_query->leftJoin('persons', 'persons.id','=','people.person_id');
        $limit_query->leftJoin('businesses', 'businesses.id','=','people.business_id');
           

        $limit_query->leftJoin('account_ledgers', function($join) use($organization_id)
            {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
            });

        $limit_query->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id');

        $limit_query->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
            {
                $join->on('people.business_id', '=', 'business_ledgers.business_id')
                ->where('business_ledgers.organization_id', $organization_id);
            });

        $limit_query->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id');



        $limit_query->where('people.organization_id', $organization_id);
       
        if($selected_type == 1)  
        {
             $limit_query->where('people.business_id',$selected_people);
            }            
                else if($selected_type == 0) {
                $limit_query->where('people.person_id',$selected_people);
            }  
        
        $credit_limit = $limit_query->first();
        


        $transaction_type = AccountVoucher::where('name', $request->input('transaction_type'))->where('organization_id', $organization_id)->first();

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

         
        $transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
        $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;            


        /*$transaction = Transaction::select('transactions.id', 'transactions.order_no',
            'transactions.total', 'people.person_id' ,'people.business_id',

            'people.display_name AS customer',

                DB::raw("IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL, 

                    (account_ledger_credit_infos.max_credit_limit  - transactions.total),

                    account_ledger_credit_infos.max_credit_limit  - (transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))  ) ) AS max_credit_limit"),

                'transactions.approval_status', 'transactions.user_type', 'transactions.people_id');

            if($selected_type == 1)  {
                 $transaction->leftJoin('people', function($query) {
                    $query->on('transactions.people_id','=','people.business_id');
                });

                $transaction->leftJoin('account_ledgers', function($join) use($organization_id) {
                    $join->on('people.business_id', '=', 'account_ledgers.business_id')
                    ->where('account_ledgers.organization_id', $organization_id);
                });

            } else if($selected_type == 0) {
                    $transaction->leftJoin('people', function($query) {
                    $query->on('transactions.people_id','=','people.person_id');
                });

                $transaction->leftJoin('account_ledgers', function($join) use($organization_id) {
                    $join->on('people.person_id', '=', 'account_ledgers.person_id')
                    ->where('account_ledgers.organization_id', $organization_id);
                });


            }  

            $transaction->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id');
             

            $transaction->where('transactions.organization_id', $organization_id);
            $transaction->where('transactions.approval_status', 1);
            $transaction->where('transactions.transaction_type_id', $transaction_id);
            $transaction->where('transactions.people_id', $selected_people);
            $transaction->groupby('transactions.id');

            $transactions = $transaction->first();
        
*/
                 Log::info("TransactionController->get_credit_limit :- Return ");
        return response()->json($credit_limit);

        
    }

    public function remote_transaction($id) {
                 Log::info("TransactionController->remote_transaction :- Inside ");

        $transaction = Transaction::select('transaction_type_id')->where('id', $id)->first();

        $transaction_type = AccountVoucher::where('id', $transaction->transaction_type_id)->first()->name;

        $type = "Transaction";

        if($transaction_type == "purchase_order") {
            $type = "Sale Order";
        } else if($transaction_type == "purchases") {
            $type = "Invoice";
        } else if($transaction_type == "goods_receipt_note") {
            $type = "Delivery Note";
        } else if($transaction_type == "sale_order") {
            $type = "Purchase Order";
        } else if($transaction_type == "sales" || $transaction_type == "job_invoice") {
            $type = "Purchase";
        } else if($transaction_type == "delivery_note") {
            $type = "Goods Receipt Note";
        }
        
                 Log::info("TransactionController->remote_transaction :- Return ");
        return view('inventory.transaction_notification', compact('id', 'type', 'transaction_type'));

    }

    //Inline add to account
    public function add_to_transaction(Request $request) {

                 Log::info("TransactionController->add_to_transaction :- Inside ");
        $organization_id = Session::get('organization_id');
        $organization = Organization::findOrFail($organization_id);

        //dd($organization);

        $entry = [];

        /*$existing_transaction = Transaction::find($request->id);

        $transaction_type = AccountVoucher::find($existing_transaction->transaction_type_id);

        $reference_voucher = ReferenceVoucher::select('name', 'display_name', 'id')->where('name', $transaction_type->name)->where('status', 1)->get();

        $remote_reference_no = $existing_transaction->order_no;
        $remote_order_id = $existing_transaction->id;
        */

        $transaction_type = AccountVoucher::where('name', $request->transaction_name)->where('organization_id', $organization_id)->first();


        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization)->orderby('id', 'desc')->first();
        
        $existing_transaction = Transaction::where('id', $request->id)
        ->first();

        /* Update for status colour */

        DB::table('transactions')->where('id',$existing_transaction->id)->update(['notification_status'=> "2"]);

        /* end */       

        $remote_order = Organization::select('organizations.id','organizations.business_id','businesses.business_name','business_communication_addresses.mobile_no','business_communication_addresses.email_address','business_communication_addresses.address')
        ->leftjoin('businesses','businesses.id','=','organizations.business_id')
        ->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id')
        ->where('organizations.id', $existing_transaction->organization_id)
        ->first();
        
        $existing_transaction_items = TransactionItem::where('transaction_id', $existing_transaction->id)->get();           
        
        $transaction = new Transaction;
        $transaction->user_type = $existing_transaction->user_type;
        $transaction->reference_no = $existing_transaction->order_no;
        $transaction->reference_id = $existing_transaction->id;
        $transaction->people_id = $remote_order->business_id;
        $transaction->ledger_id = $existing_transaction->ledger_id;
        $transaction->date = $existing_transaction->date;
        $transaction->due_date = $existing_transaction->due_date;
        $transaction->transaction_type_id = $transaction_type->id;
        $transaction->payment_mode_id = $existing_transaction->payment_mode_id ;
        $transaction->tax_type = $existing_transaction->tax_type;
        $transaction->term_id = $existing_transaction->term_id;
        $transaction->pin = Custom::otp(4);

        $transaction->name = $remote_order->business_name;
        $transaction->mobile = $remote_order->mobile_no;
        $transaction->email = $remote_order->email_address;
        $transaction->address = $remote_order->address;

        $transaction->billing_name = $existing_transaction->billing_name;
        $transaction->billing_mobile = $existing_transaction->billing_mobile;
        $transaction->billing_email = $existing_transaction->billing_email;
        $transaction->billing_address = $existing_transaction->billing_address;

        $transaction->shipping_name = $existing_transaction->shipping_name;
        $transaction->shipping_mobile = $existing_transaction->shipping_mobile;
        $transaction->shipping_email = $existing_transaction->shipping_email;
        $transaction->shipping_address = $existing_transaction->shipping_address;

        $transaction->shipment_mode_id = $existing_transaction->shipment_mode_id;
        $transaction->shipping_date = $existing_transaction->shipping_date;
        $transaction->discount_is_percent = $existing_transaction->discount_is_percent;
        $transaction->discount = $existing_transaction->discount;
        $transaction->organization_id = $organization_id;
        $transaction->sub_total = $existing_transaction->sub_total;
        $transaction->total = $existing_transaction->total;
        $transaction->notification_status = 2;
        $transaction->approval_status = 1;
        /*if($transaction_type->name == "purchases" || $transaction_type->name == "goods_receipt_note") {
            $transaction->notification_status = 1;
        }*/
        $transaction->save();

        Custom::userby($transaction, true);

        $account_ledgers = AccountLedger::select('account_ledgers.id');

        if($transaction->user_type == 0) {
            $account_ledgers->where('person_id', $transaction->people_id);
            $person_id = $transaction->people_id;
            $business_id = null;
        } else if($transaction->user_type == 1) {
            $account_ledgers->where('business_id', $transaction->people_id);
            $person_id = null;
            $business_id = $transaction->people_id;
        }

        $account_ledger = $account_ledgers->first();

        if($account_ledger != null) {
            $customer_ledger = $account_ledger->id;
        }
        else 
        {
            $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

            $people = People::find($transaction->people_id);

            if($transaction_type->name == "purchases") {

                $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();

                $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
            } 
            else if($transaction_type->name == "sales") {

                $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
            }
            else if($transaction_type->name == "job_invoice") {

                $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
            }
        }

    
        if($transaction->id != null) {

            foreach ($existing_transaction_items as $transaction_item)
            {
                $item = new TransactionItem;
                $item->transaction_id = $transaction->id;
                $item->item_id = $transaction_item->item_id;
                $item->quantity = $transaction_item->quantity;
                $item->rate = $transaction_item->rate;
                $item->amount = $transaction_item->amount;
                $item->tax = $transaction_item->tax;
                $item->tax_id = $transaction_item->tax_id;
                $item->is_tax_percent = $transaction_item->is_tax_percent;
                $item->discount = $transaction_item->discount;
                $item->discount_id = $transaction_item->discount_id;
                $item->is_discount_percent = $transaction_item->is_discount_percent;
                $item->save();

                $item_account = InventoryItem::where('id', $item->item_id)->where('organization_id', $organization_id)->first();

                //dd($item_account);

                if($item->tax_id != null) {

                    $tax_amount = json_decode($item->tax, true);

                    foreach ($tax_amount as $tax) {

                        $tax_value = Tax::find($tax["id"]);

                        if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {
                            //Sales Tax is expense, All expenses are debit
                            //Vendor (Payables) gives the item, Credit the giver
                            $entry[] = ['debit_ledger_id' => $tax_value->purchase_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax["amount"]];
                        } else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" ||  $transaction_type->name == "job_invoice" ||  $transaction_type->name == "job_invoice_cash") {
                            //Sales Tax is liability, Liabilities are credit
                            //Customer (Receivables) pays the tax, Debit the receiver
                            $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $tax_value->sales_ledger_id, 'amount' => $tax["amount"]];
                        }
                    }
                }


                if($transaction_type->name == "purchases") {

                    //Item is expense, All expenses are debit
                    //Vendor gives the item, Credit the giver

                    if($item_account != null)
                    {
                        $entry[] = ['debit_ledger_id' => $item_account->expense_account , 'credit_ledger_id' => $customer_ledger, 'amount' => $item->amount];
                    }else{

                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $customer_ledger, 'amount' => $item->amount];

                    }
                    

                    if($item->discount_id != null) {

                        $discount_amount = json_decode($item->discount);

                        $discount_ledger_id = Discount::findOrFail($item->discount_id)->purchase_ledger_id;
                        //Discount is income, All incomes are credit
                        //Vendor loses amount on discount, All expenses are debit
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $discount_amount["amount"]];
                    }

                } 
                else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") 
                {

                    //Item sale is income, All incomes are credit
                    //Customer gets the item, Debit the receiver
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' =>  $item_account->income_account, 'amount' => $item->amount];

                    if($item->discount_id != null) {

                        $discount_amount = json_decode($item->discount);

                        $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                        //Discount is expense, All expenses are debit
                        //Customer gets discount, All incomes are credit
                        $entry[] = ['debit_ledger_id' => $discount_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $discount_amount["amount"]];
                    }
                }

                else if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

                    //Item sale is income, All incomes are credit
                    //Customer gets the item, Debit the receiver
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' =>  $customer_ledger, 'amount' => $item->amount];

                    if($item->discount_id != null) {

                        $discount_amount = json_decode($item->discount);

                        $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                        //Discount is expense, All expenses are debit
                        //Customer gets discount, All incomes are credit
                        $entry[] = ['debit_ledger_id' => $discount_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $discount_amount["amount"]];
                        }
                }

                else if($transaction_type->name == "credit_note") {

                    $sale_return =  AccountLedger::where('name', 'sale_return')->where('organization_id', $organization_id)->first()->id;
                    //Sales Return Account Debit
                    //Debtor or Customer Account Credit
                    $entry[] = ['debit_ledger_id' => $sale_return, 'credit_ledger_id' => $customer_ledger, 'amount' => $item->amount];


                    if($item_discount_amount != 0) {

                        $discount_ledger = Discount::find($item->discount_id);

                        if($discount_ledger == null) {
                            $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                        } else {
                            $discount_ledger_id = $discount_ledger->purchase_ledger_id;
                        }
                    //Accounts Receivable credit
                    //Discount Allowed debit
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $item_discount_amount];
                    }
                } 
                else if($transaction_type->name == "debit_note") 
                {
                    $purchase_return = AccountLedger::where('name', 'purchase_return')->where('organization_id', $organization_id)->first()->id;

                    //Purchase Return Account Credit
                    //Creditor Account Debit
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $purchase_return, 'amount' => $item->amount];

                    if($item_discount_amount != 0) {

                        $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                        if($discount_ledger_id == null) {
                            $discount_ledger_id = AccountLedger::where('name', 'purchase_discounts')->where('organization_id',$organization_id)->first()->id;
                        }

                        //Accounts Payable debit
                        //Discount credit
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $item_discount_amount];
                    }
                }
            }

            if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {

                if($transaction->discount != null && $transaction->discount != 0) {
                    $discount_ledger = AccountLedger::where('name', 'purchase_discounts')->where('organization_id',$organization_id)->first();
                    //Discount is income, All incomes are credit
                    //Vendor loses amount on discount, All expenses are debit
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger->id, 'amount' => $transaction->discount];
                        
                }


            } 
            else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note") 
            {

                if($transaction->discount != null && $transaction->discount != 0) {

                    $discount_ledger = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first();
                    //Discount is expense, All expenses are debit
                    //Customer gets discount, All incomes are credit
                    $entry[] = ['debit_ledger_id' => $discount_ledger->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->discount];

                }

            }

            else if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "debit_note") {

                if($transaction->discount != null && $transaction->discount != 0) {

                    $discount_ledger = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first();
                    //Discount is expense, All expenses are debit
                    //Customer gets discount, All incomes are credit
                    $entry[] = ['debit_ledger_id' => $discount_ledger->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->discount];

                }

            }


            usort($entry, function ($item1, $item2) {
                return $item1['debit_ledger_id'] - $item2['debit_ledger_id'];
            });

            $transaction->entry_id = Custom::add_entry($transaction->date, $entry, null, $transaction_type->name, $organization_id, 0, false);
            $transaction->save();
            

            if($transaction->entry_id != null) {

                $account_entry = AccountEntry::find($transaction->entry_id);

                $transaction->order_no = $account_entry->voucher_no;
                $transaction->gen_no = $account_entry->gen_no;
                $transaction->save();

            } else {

            //  $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;
                $getGen_no=Custom::getLastGenNumber( $transaction_type->id, $organization_id );

                //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
        
          
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }

                
                $transaction->order_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);
                $transaction->gen_no = $gen_no;
                $transaction->save();
            }

        }
    

                 Log::info("TransactionController->add_to_transaction :- Return ");


        return response()->json(['status' => 1, 'message' => $transaction_type->display_name.config('constants.flash.added'), 'data' => []]);

    }

    public function transaction_item_list(Request $request) {
        
                 Log::info("TransactionController->transaction_item_list :- Inside ");
        $organization_id = Session::get('organization_id');

        if($request->notification_type == "remote") {

            $items_query = TransactionItem::select('inventory_items.id', 'global_item_models.id AS global_id', 'global_item_models.name AS global_name', 'transaction_items.description', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.id AS tax_id',  'transaction_items.discount', 'transaction_items.discount_id', DB::raw('COALESCE(transaction_items.discount_value, "") AS discount_value'),'transaction_items.start_time','transaction_items.end_time','transaction_items.assigned_employee_id','transaction_items.job_item_status','inventory_item_stocks.in_stock','inventory_items.base_price','inventory_items.purchase_price','inventory_items.sale_price_data','transaction_items.item_id','global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name','account_vouchers.name AS voucher_type','transaction_items.percentage');

            $items_query->leftjoin('inventory_items AS remote_item', 'remote_item.id', '=', 'transaction_items.item_id');

            $items_query->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'remote_item.id' );

            $items_query->leftjoin('inventory_items', function($query) use ($organization_id) {
                $query->on('inventory_items.global_item_model_id', '=', 'remote_item.global_item_model_id');

                $query->where('inventory_items.organization_id', '=', $organization_id);

            });
            

            $items_query->leftjoin('global_item_models', 'global_item_models.id', '=', 'remote_item.global_item_model_id');

            $items_query->leftjoin('tax_groups AS transaction_item_tax', 'transaction_item_tax.id', '=', 'transaction_items.tax_id');

            $items_query->leftjoin('tax_groups', function($query1) use ($organization_id) {
                $query1->on('tax_groups.name', '=', 'transaction_item_tax.name');

                $query1->where('tax_groups.organization_id', '=', $organization_id);

            });

            $items_query->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id');

            $items_query->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id');

            $items_query->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id');

            $items_query->leftjoin('transactions', 'transactions.id', '=', 'transaction_items.transaction_id');

            $items_query->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id');

            $items_query->where('transaction_items.transaction_id', $request->order_id);

            $result_query = clone $items_query;
            $transaction_items = $result_query->get();
        } 

        else {

            $items_query = TransactionItem::select('inventory_items.id', 'inventory_items.id AS global_id', 'inventory_items.name AS global_name', 'transaction_items.description', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.id AS tax_id',  'transaction_items.discount', 'transaction_items.discount_id', DB::raw('COALESCE(transaction_items.discount_value, "") AS discount_value'),'transaction_items.start_time','transaction_items.end_time','transaction_items.assigned_employee_id','transaction_items.job_item_status','inventory_item_stocks.in_stock','inventory_items.purchase_price','inventory_items.sale_price_data','transaction_items.item_id', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name','account_vouchers.name AS voucher_type','transaction_items.new_selling_price','transaction_items.batch_id','transaction_items.duration','transaction_items.stock_update','transactions.approval_status','inventory_item_batches.quantity AS batch_stock','transaction_items.percentage')

            ->leftjoin('inventory_items', function($query) use ($organization_id) {

                $query->on('inventory_items.id', '=', 'transaction_items.item_id');

                $query->where('inventory_items.organization_id', '=', $organization_id);
            })

            ->leftjoin('tax_groups AS transaction_item_tax', 'transaction_item_tax.id', '=', 'transaction_items.tax_id')

            ->leftjoin('tax_groups', function($query) use ($organization_id) {

                $query->on('tax_groups.name', '=', 'transaction_item_tax.name');

                $query->where('tax_groups.organization_id', '=', $organization_id);

            })

            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')

            ->leftjoin('inventory_item_batches', 'inventory_item_batches.id', '=', 'transaction_items.batch_id')

            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

            ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

            ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')

            ->leftjoin('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')

            ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')

            ->where('transaction_items.transaction_id',$request->order_id);

            $result_query = clone $items_query;

            $transaction_items = $result_query->get();
        }



        if(count($transaction_items) > 0 )
        {
            $condition_query =  clone $items_query;

            $condition_result = $condition_query->pluck('transaction_items.item_id');

            $item_batch = InventoryItemBatch::whereIn('item_id',$condition_result)->where('quantity','>', 0)->groupBy('item_id')->havingRaw('COUNT(item_id) > 1')->get();

            $service_batch = WmsPriceList::whereIn('inventory_item_id',$condition_result)->groupBy('inventory_item_id')->havingRaw('COUNT(inventory_item_id) > 0')->get();
        }

        /*echo "<pre>";
        print_r($item_batch);
        echo "</pre>";

        dd();*/


        $list_price = [];
        $new_selling_price = '';

        if(count($transaction_items) > 0 )
        {

            foreach ($transaction_items as $key => $value) {

                //dd($transaction_items[$key]->sale_price_data);

                if($transaction_items[$key]->item_id == null)
                {
                    $list_price['list_price'][] = '';
                    $list_price['price'][] = '';
                }

                else if($transaction_items[$key]->sale_price_data == null )
                {
                    $list_price['list_price'][] = '';
                    $list_price['price'][] = '';
                }

                else{

                    $list = Custom::get_least_closest_date(json_decode($transaction_items[$key]->sale_price_data,true));

                    $list_price['list_price'][] = $list['list_price'];

                    $list_price['price'][] = $list['price'];            
                }


                if($transaction_items[$key]->new_selling_price == null){

                    $new_selling_price = $list_price['list_price'];

                }else{

                    $new_selling_price = $transaction_items[$key]->new_selling_price;

                }
            }
        }
        else{ // if transaction item has empty
            
            $list_price['list_price'][] = '';
            $list_price['price'][] = '';
            $new_selling_price = '';
            $transaction_items[] = '';
            $item_batch[] = '';
            $service_batch[] = '';
        }

                 Log::info("TransactionController->transaction_item_list :- Return ");


        //return response()->json([$transaction_items,'is_group'=>$transaction_items->is_group,'group' => $item_group]);

        return response()->json(['transaction_items' => $transaction_items,'notification_type'=>$request->notification_type,'selling_price' => $list_price['list_price'], 'base_price' => $list_price['price'],'new_selling_price' => $new_selling_price,'item_batch' => $item_batch,'service_batch' => $service_batch]);

    }


    //Add remote transaction as expense
    public function add_to_expense(Request $request) {

                 Log::info("TransactionController->add_to_expense :- Inside ");
        //dd($request->all());

        $organization_id = Session::get('organization_id');
        $organization = Organization::findOrFail($organization_id);

        $entry = [];
        
        $other_transaction = Transaction::where('id', $request->id)->first();

        //dd($other_transaction);

        //return false;


        $transaction_type_name = AccountVoucher::where('id', $other_transaction->transaction_type_id)->first()->name;

        $type = null;
        $type_name = "Transaction";

        if($transaction_type_name == "purchase_order") {
            $type = "sale_order";
            $type_name = "Sale Order";
        } else if($transaction_type_name == "purchases") {
            $type = "sales";
            $type_name = "Invoice";
        } else if($transaction_type_name == "goods_receipt_note") {
            $type = "delivery_note";
            $type_name = "Delivery Note";
        } else if($transaction_type_name == "sale_order") {
            $type = "purchase_order";
            $type_name = "Purchase Order";
        } else if($transaction_type_name == "sales" || $transaction_type_name == "job_invoice" || $transaction_type_name == "job_invoice_cash") {
            $type = "purchases";
            $type_name = "Purchases";
        } else if($transaction_type_name == "delivery_note") {
            $type = "goods_receipt_note";
            $type_name = "Goods Receipt Note";
        }

        if($type != null) {

            $transaction_type = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first();

            $other_organization = Organization::findOrFail($other_transaction->organization_id);

            $people_exist = People::where('business_id', $other_organization->business_id)
            ->where('organization_id', $organization_id)
            ->first();

            if($people_exist == null) {

                $business = Business::find($other_organization->business_id);

                $business_address_type = BusinessAddressType::find($business->address_type)->id;

                $business_communication_address = BusinessCommunicationAddress::select('mobile_no', 'address', 'city_id', 'pin');

                $people = new People();
                $people->user_type = 1;
                $people->business_id = $person->id;
                $people->company = $business->alias;
                $people->display_name = $business->alias;
                $people->mobile_no = $business_communication_address->mobile_no;
                $people->organization_id = $organization_id;
                $people->save();

                Custom::add_addon('records');
                Custom::add_addon('customer');

                if($people->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $people->id;
                    $people_address->address_type = 0;
                    $people_address->address = $business_communication_address->address;
                    $people_address->city_id = $business_communication_address->city_id;
                    $people_address->pin = $business_communication_address->pin;
                    $people_address->save();
                }
            } else {
                $people = $people_exist;
            }

            $account_ledgers = AccountLedger::select('account_ledgers.id');
            $account_ledgers->where('business_id', $people->business_id);
            $business_id = $people->business_id;
            $person_id = null;

            $account_ledger = $account_ledgers->first();

            $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

            if($account_ledger != null){
                $customer_ledger = $account_ledger->id;
            }
            else {
                if($transaction_type->name == "purchases") {
                    $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();
                    $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
                } 
                else if($transaction_type->name == "sales" || $transaction_type->name == "job_invoice" || $transaction_type_name == "job_invoice") {

                    $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                    $customer_ledger = Custom::create_ledger($people->display_name, $organization, $people->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
                }

            }


            $purchase_account = AccountLedger::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;

            //Item is expense, All expenses are debit
            //Vendor gives the item, Credit the giver
            $entry[] = ['debit_ledger_id' => $purchase_account, 'credit_ledger_id' => $customer_ledger, 'amount' => $other_transaction->total];

            usort($entry, function ($item1, $item2) {
                return $item1['debit_ledger_id'] - $item2['debit_ledger_id'];
            });

            Custom::add_entry(date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 0, false,null, null, null,$other_transaction->id);

            //$transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);   

        }

        $other_transaction->notification_status = 2;
        $other_transaction->save();
                         Log::info("TransactionController->add_to_expense :- return ");


        return response()->json(['status' => 1, 'message' => $transaction_type->display_name.config('constants.flash.added'), 'data' => ['url' => route('transaction.index', [$type])]]);

    }
    
    //Change approval status and if it is GRN or DN or Credit Note or Debit Note, it would affect inventory
    public function transaction_status(Request $request) {

                 Log::info("TransactionController->transaction_status :- Inside ");
        $organization_id = Session::get('organization_id');

        $transaction = Transaction::select('transactions.*','transactions.transaction_type_id', 'transactions.mobile', 'transactions.id', 'transactions.date', 'transactions.total', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no', 'people.display_name', DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code'))
        ->leftjoin('people', function($query){
                $query->on('transactions.people_id','=','people.person_id');
                $query->orWhere('transactions.people_id','=','people.business_id');
        })
        ->leftjoin('persons', 'people.person_id', '=', 'persons.id')
        ->leftjoin('businesses', 'people.person_id', '=', 'businesses.id')
        ->where('transactions.id', $request->id)
        ->first();

        $business = Organization::select('businesses.alias AS business')
        ->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
        ->where('organizations.id', $organization_id)->first()->business;

        $transaction_type = AccountVoucher::find($transaction->transaction_type_id);

        if($transaction_type->name == "goods_receipt_note" || $transaction_type->name == "credit_note") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            $items = TransactionItem::where('transaction_id', $transaction->id)->get();

            foreach ($items as $item) {
                $stock = InventoryItemStock::find($item->item_id);

                $inventory_item = InventoryItem::find($item->item_id);

                $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                ->where('tax_groups.organization_id', $organization_id)
                ->where('tax_groups.id', $inventory_item->purchase_tax_id)
                ->groupby('tax_groups.id')->first();


                    if($inventory_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $inventory_item->purchase_price;
                    }

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                if($stock != null) {
                    if($request->status == "1") {

                        $inventory_stock = $stock->in_stock + $item->quantity;

                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);

                    } else if($request->status == "0") {

                        //$inventory_stock = $stock->in_stock - $item->quantity;

                        if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }

                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;

                        $data = json_decode($stock->data, true);

                        /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);
                    } 
                    
                    $stock->save();
                }
                
            }
            

        } else if($transaction_type->name == "delivery_note" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice_cash") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            $items = TransactionItem::where('transaction_id', $transaction->id)->get();

            foreach ($items as $item) {
                $stock = InventoryItemStock::find($item->id);

                $inventory_item = InventoryItem::find($item->item_id);

                $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                    if($inventory_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $inventory_item->purchase_price;
                    }

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                if($stock != null) {
                    if($request->status == "1") {

                        //$inventory_stock = $stock->in_stock - $item->quantity;

                        if($stock->in_stock <= $item->quantity)
                        {
                            $inventory_stock = 0.00;
                        }else{
                            $inventory_stock = $stock->in_stock - $item->quantity;
                        }

                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);

                    } 
                    else if($request->status == "0") {

                        $inventory_stock = $stock->in_stock + $item->quantity;

                        $stock->in_stock = $inventory_stock ;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        $data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $inventory_item->purchase_price,'sale_price' => $inventory_item->base_price,'status' => 1];


                        $stock->data = json_encode($data);
                    }
                    $stock->save();
                }   
            }
        }

        
        $transaction->approval_status = $request->status;
        $transaction->save();

        $business_name = Session::get('business');

        if($transaction->approval_status == 1) {

            if($transaction_type->name == "receipt" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

                switch ($transaction_type->name) {
                    case 'receipt':
                        $message = "Dear ".$transaction->display_name.",". "\n\n" ."Payment of Rs. ".$transaction->total." on 11-May-18 for the Invoice ".$transaction->order_no." has been received.". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'purchase_order':
                        $message = "You have a new order from My Company for Rs. ".$transaction->total. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'sale_order':
                        $message = "Dear ".$transaction->display_name.",". "\n\n" ."your purchase order has been confirmed. Order ref:".$transaction->order_no." Amount: Rs.".$transaction->total. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'sales':
                        $message =  "Dear ".$transaction->display_name.",". "\n\n" ."Thanks for choosing PropelSoft. 
                        Invoice with Ref:1236 for Rs. ".$transaction->total." has been created on 11-May-18.". "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'sales_cash':
                        $message = "Dear ".$transaction->display_name.",". "\n\n" ."Your Payment of Rs. ".$transaction->total." has been received for the Invoice ".$transaction->order_no."". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                        case 'job_invoice':
                        $message =  "Dear ".$transaction->display_name.",". "\n\n" ."Thanks for choosing PropelSoft. 
                        Invoice for Rs. ".$transaction->total." has been created on Today.". "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'job_invoice_cash':
                        $message = "Dear ".$transaction->display_name.",". "\n\n" ."Your Payment of Rs. ".$transaction->total." has been received for the Invoice ".$transaction->order_no."". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;

                    case 'delivery_note':
                        $message = "Dear ".$transaction->display_name.",". "\n\n" ."Your order for ".$transaction->reference_no. " of Rs. ".$transaction->total." has been delivered. Ref: ".$transaction->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transaction->code;
                        break;
                }

                if($transaction->mobile != "") {

                    //$this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $transaction->mobile, $message));
                    //$this->dispatch(new SendTransactionEmail());

                    //Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $transaction->mobile, $message);
                }
            }
        }
        

        if($transaction->entry_id != null) {
            $entry = AccountEntry::find($transaction->entry_id);
            $entry->status = $request->status;
            $entry->save();
        }
                 Log::info("TransactionController->transaction_status :- Return ");

        return response()->json(array('result' => 'Success'));
    }
    
    public function print(Request $request, $remote = null) {

    
                 Log::info("TransactionController->print :- Inside ");
        $organization_id = Session::get('organization_id');



        $transactions = Transaction::select('transactions.id','transactions.order_no','transactions.date','transactions.due_date','transactions.email as email_id','transactions.sub_total','transactions.billing_name','transactions.billing_address','transactions.shipping_address','transactions.total','account_vouchers.display_name AS transaction_type','payment_modes.display_name AS payment_method','print_templates.data','transactions.name','transactions.address','vehicle_register_details.registration_no',DB::raw('CONCAT(vehicle_makes.name, " - ",vehicle_models.name," - ",vehicle_variants.name) AS make_model_variant'),'organizations.name as organization_name','business_communication_addresses.mobile_no as company_phone','business_communication_addresses.address as company_address','account_transactions.amount','businesses.gst as company_gst','transactions.gst as customer_communication_gst','transactions.billing_gst as billing_communication_gst',DB::raw('CASE WHEN(transactions.user_type=0) THEN people.`gst_no` ELSE business.gst_no END AS customer_gst'),'people.gst_no as customer_gst1','wms_transactions.vehicle_mileage as warranty_km','hrm_employees.first_name as assigned_to','transactions.mobile as customer_mobile',DB::raw('if(vehicle_register_details.driver is null,transactions.name,vehicle_register_details.driver)as driver'),DB::raw('if(vehicle_register_details.driver_mobile_no is null,transactions.mobile,vehicle_register_details.driver_mobile_no)as driver_mobile_no'),'vehicle_register_details.warranty_km as warranty',"vehicle_register_details.insurance","vehicle_register_details.engine_no","vehicle_register_details.chassis_no",DB::raw("(GROUP_CONCAT(DISTINCT vehicle_spec_masters.name,':',vehicle_specification_details.name)) as spec"),'wms_transactions.job_due_date as job_due_on','wms_transactions.vehicle_last_visit as last_visit_on','wms_transactions.vehicle_next_visit as next_visit_on','wms_transactions.vehicle_complaints');

                     

        $transactions->leftjoin('wms_transactions','transactions.id','=','wms_transactions.transaction_id');

        $transactions->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id');

        $transactions->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

        $transactions->leftJoin('vehicle_models', 'vehicle_models.id', '=','vehicle_variants.vehicle_model_id');

        $transactions->leftJoin('vehicle_makes','vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');

        $transactions->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id');

        $transactions->leftjoin('payment_modes', 'payment_modes.id', '=', 'transactions.payment_mode_id');

        $transactions->leftjoin('print_templates', 'print_templates.id', '=', 'account_vouchers.print_id');

        $transactions->leftjoin('organizations','organizations.id','=','transactions.organization_id');

        $transactions->leftjoin('businesses','businesses.id','=','organizations.business_id');


        $transactions->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id');

        $transactions->leftJoin('people', function($join) use($organization_id)

            {

                $join->on('people.person_id','=', 'transactions.people_id')

                ->where('people.organization_id',$organization_id)

                ->where('transactions.user_type', '0');

            });

        $transactions->leftJoin('people AS business', function($join) use($organization_id)

            {

                $join->on('business.business_id','=', 'transactions.people_id')

                ->where('business.organization_id',$organization_id)

                ->where('transactions.user_type', '1');

            });
    


        $transactions->leftjoin('account_entries','account_entries.reference_transaction_id','=','transactions.id');

        $transactions->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');

        $transactions->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id');

        $transactions->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id');
        
        $transactions->leftjoin('registered_vehicle_specs','registered_vehicle_specs.registered_vehicle_id','=','vehicle_register_details.id');
        $transactions->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id');
        $transactions->leftjoin('vehicle_specification_details','vehicle_specification_details.id','=','registered_vehicle_specs.spec_value_id');

        if($remote == null) {

            $transactions->where('transactions.organization_id', $organization_id);

        }

        $transactions->where('transactions.id', $request->id);

                  

        $transaction = $transactions->first();
        //dd($transaction);


               

        $exact_address = $transaction->address;

        $address = str_replace("<br>"," ",$exact_address);

        $exact_billing_address = $transaction->billing_address;
        $exact_shipping_address = $transaction->shipping_address;


        $billing_address = str_replace("<br>"," ",$exact_billing_address);

        $shipping_address = str_replace("<br>"," ",$exact_shipping_address);




        $last_updated_datas = Transaction::select('transactions.id','vehicle_register_details.registration_no','wms_transactions.job_date','transactions.reference_no');
        $last_updated_datas->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        $last_updated_datas->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id');
        $last_updated_datas->where('vehicle_register_details.registration_no',$transaction->registration_no);
        $last_updated_datas->where(function ($query) {
                $query->where('wms_transactions.jobcard_status_id', '!=',"8")
                      ->orWhere('wms_transactions.jobcard_status_id', '=',null);
        });
        $last_updated_datas->where('transactions.organization_id',$organization_id);
        $last_updated_datas->orderBy('transactions.id',"DESC");
        $last_updated_data = $last_updated_datas->first();

        $job_card_transaction_id = $request->id; 

       $checklist = VehicleChecklist::select('vehicle_checklists.name as checklist','vehicle_checklists.id AS checklist_id','wms_checklists.transaction_id','wms_checklists.checklist_status','wms_checklists.checklist_notes as notes','wms_checklists.id AS id');
        $checklist  ->LeftJoin('wms_checklists', function($join) use($job_card_transaction_id)  {

            $join->on('wms_checklists.checklist_id', '=', 'vehicle_checklists.id') ;

            $join->where('wms_checklists.transaction_id', '=',$job_card_transaction_id) ;
           });

        $checklist->orderby('vehicle_checklists.name','asc')->get();
        //dd($checklist);
        $job_card_checklist = $checklist->skip(5)->take(13)->get();

        $first_checklists = $checklist->skip(1)->take(5)->get();

        $checklist_fuellevel = $checklist->skip(0)->take(1)->get();


        $transaction_items = TransactionItem::select('transaction_items.id',DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'),'inventory_items.hsn','tax_groups.display_name AS gst', 'discounts.value AS discount', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent')

        ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

        ->leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')

        ->leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')

        ->where('transaction_items.transaction_id', $transaction->id)->get();
        //dd($transaction_items);

        $job_card_items = TransactionItem::select('transaction_items.amount as amt','transaction_items.quantity as qty','inventory_items.name as item_name');
        $job_card_items->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
        $job_card_items->where('transaction_items.transaction_id',$request->id);
        $jc_items = $job_card_items->get();

    
            

        $discount_result = TransactionItem::select(DB::raw('GROUP_CONCAT(discount) AS discount'), DB::raw('SUM(amount) AS amount'))->where('transaction_id', $transaction->id)->groupby('discount_id')->get();



        $tax_result = TransactionItem::select('tax', DB::raw('SUM(amount) AS amount'))->where('transaction_id', $transaction->id)->groupby('tax_id')->get();

          

        $discount_array = [];



        $tax_array = [];



        $discount = [];



        $tax = [];



        if($discount_result != null) {

            for ($i=0; $i < count($discount_result); $i++) { 

                $discount_array[] = json_decode(

                    str_replace('}', ', "total-amount": '.$discount_result[$i]->amount.' }', 

                    str_replace('],[', ',', $discount_result[$i]->discount)

                    )

                    , true);

            }

            

        }



        asort($discount_array);



        if($tax_result != null) {

            for ($i=0; $i < count($tax_result); $i++) { 





                $tax_array[] = json_decode(

                    str_replace('}', ', "total-amount": '.$tax_result[$i]->amount.' }', 

                    str_replace('],[', ',', $tax_result[$i]->tax)

                    )

                    , true);

            }

            

        }

        asort($tax_array);



        $discount_id = null;

        $discount_val = null;

        foreach ($discount_array as $value) {



                if($discount_id != $value["id"]) {

                    $discount_id = $value["id"];

                    $discount_val = $value["amount"];

                } else {



                    foreach ($discount as $key => $t) {

                        if($t["id"] == $value["id"]) {

                            unset($discount[$key]);

                        }

                    }



                    $discount_val += $value["amount"];

                }



                if($value["id"] != null) {

                    $discount[] = ["id" => $value["id"], "key" => $value["name"]." @". $value["value"] ."% on ".$value['total-amount'], "value" => "- " .Custom::two_decimal($discount_val)];

                }

            

        }





        $tax_id = null;

        $tax_val = null;



        if(count(array_filter($tax_array)) > 0) {

            foreach (array_filter($tax_array) as $tax_arr) {

                foreach ($tax_arr as $value) {



                    if($tax_id != $value["id"]) {

                        $tax_id = $value["id"];

                        $tax_val = $value["amount"];

                    } else {



                        foreach ($tax as $key => $t) {

                            if($t["id"] == $value["id"]) {

                                unset($tax[$key]);

                            }

                        }



                        $tax_val += $value["amount"];

                    }



                    $tax[] = ["id" => $value["id"], "key" => $value["name"]." @". $value["value"] ."% on ".$value['total-amount'], "value" => Custom::two_decimal($tax_val)];

                

                }



            }

        }



        $no_tax_values = TransactionItem::select('transaction_items.id',DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'),'transaction_items.quantity','transaction_items.rate',DB::raw('transaction_items.discount_value/100 AS discount'),DB::raw('((transaction_items.amount) - (`transaction_items`.`amount`) *( CASE WHEN `transaction_items`.`discount_value` IS NULL  THEN 0 ELSE `transaction_items`.`discount_value`/100 END)) AS amount'),DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'),DB::raw('SUM(((transaction_items.rate) -(transaction_items.rate) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_rate'))

           ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')

           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')

           ->where('transaction_items.transaction_id', $transaction->id)

           ->groupby('transaction_items.id')->get();

           //dd($no_tax_values);

          



        $invoice_items = TransactionItem::select('transaction_items.id',DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'),'inventory_items.hsn','tax_groups.display_name AS gst','transaction_items.discount AS discount', 'transaction_items.quantity','transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent',DB::raw('((transaction_items.amount)-(CASE WHEN discounts.value is null THEN 0 ELSE discounts.value END))as t_amount'))

        ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

        ->leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')

        ->leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')

        ->where('transaction_items.transaction_id', $transaction->id)->get();
        //dd($invoice_items);

        

      

         $total_qty = TransactionItem::select('transaction_items.id',DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'),'inventory_items.hsn','tax_groups.display_name AS gst', 'discounts.value AS discount', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent',DB::raw('sum(transaction_items.quantity) AS total_qty'))

        ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

        ->leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')

        ->leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')

        ->where('transaction_items.transaction_id', $transaction->id)->first();



        $total_amount = TransactionItem::select('transaction_items.id',DB::raw('sum(discounts.value)AS total_discount'),DB::raw('sum((transaction_items.amount)-(CASE WHEN discounts.value is null THEN 0 ELSE discounts.value END))as total_amount'))

        ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')

        ->leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')

        ->leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')

        ->where('transaction_items.transaction_id', $transaction->id)->first();



         $unique_tax = TransactionItem::select('transaction_items.id AS item_id','inventory_items.name AS item',

                                           'tax_groups.display_name AS tax','tax_types.id AS tax_type','transaction_items.quantity AS qty',

                                           'transaction_items.rate AS rate','transaction_items.amount AS amount','transaction_items.discount_value AS discount',DB::raw('transaction_items.amount * transaction_items.discount_value/100 as discount_amount'),DB::raw('SUM((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) AS taxable'),DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS Tax_amount'),'taxes.display_name',

                                              'taxes.value AS tax_value','tax_groups.name')

                 ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')

                 ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')

                 ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')

                 ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')

                 ->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id')

                 ->where('transaction_items.transaction_id', $transaction->id)

                 ->groupby('taxes.Name')->orderby('taxes.Name','taxable')->get();



        $invoice_tax = $unique_tax->unique('name');

         

      

        $hsn_b2b_tax = TransactionItem::select('transaction_items.id AS item_id',

                      'inventory_items.name AS item','inventory_items.hsn','tax_groups.display_name AS tax',

                      'tax_types.id AS tax_type','transaction_items.quantity AS qty',

                      'transaction_items.rate AS rate','transaction_items.amount AS amount','transaction_items.discount_value AS discount',DB::raw('transaction_items.amount * transaction_items.discount_value/100 as discount_amount'),DB::raw('SUM((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) AS taxable'),DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS Tax_amount'),'taxes.display_name',

                      'taxes.value AS tax_value','tax_groups.name')

                 ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')

                 ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')

                 ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')

                 ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')

                 ->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id')

                 ->where('transaction_items.transaction_id', $transaction->id)

                 ->groupby('taxes.Name','inventory_items.hsn')->orderby('inventory_items.hsn','DESC')->get();

                

        $hsn_based_invoice_tax = $hsn_b2b_tax->unique('item_id');



              

        $data = [];

         if($request->data != null){

                $data['transaction_data'] = $request->data;

              }else{

                $data['transaction_data'] = $transaction->data;

              } 
             

        $data['transaction_type'] = $transaction->transaction_type;

        $data['estimate_no'] = $transaction->order_no;
        //dd($data['estimate_no']);

        $data['po_no'] = $transaction->order_no;

        $data['grn_no'] = $transaction->order_no;

        $data['purchase_no'] = $transaction->order_no;

        $data['debit_note_no'] = $transaction->order_no;

        $data['so_no'] = $transaction->order_no;

        $data['invoice_no'] = $transaction->order_no;

        $data['dn_no'] = $transaction->order_no;

        $data['credit_note_no'] = $transaction->order_no;

        $data['customer_vendor'] = $transaction->name;

        $data['customer_mobile'] = $transaction->customer_mobile;

        $data['payment_mode'] = $transaction->order_no;

        $data['resource_person'] = $transaction->order_no;

        $data['company_name'] = $transaction->organization_name;

        $data['company_phone'] = $transaction->company_phone;

        $data['company_address'] = $transaction->company_address;

        $data['company_gst'] = $transaction->company_gst;
        $data['customer_communication_gst'] = $transaction->customer_communication_gst;
        $data['billing_communication_gst'] = $transaction->billing_communication_gst;

        

        $data['customer_gst'] = $transaction->customer_gst;

        $data['email_id'] = $transaction->email_id;

        $data['amount']= $transaction->amount;

        $data['payment_method'] = $transaction->payment_method;

        $data['date'] = Carbon::parse($transaction->date)->format('M d, Y');

        $data['due_date'] = Carbon::parse($transaction->due_date)->format('M d, Y');

        $data['billing_name'] = $transaction->billing_name;

        
        $data['billing_email'] = $transaction->billing_email;

        $data['customer_address'] = $address;

        $data['billing_address'] = $billing_address;

        $data['shipping_name'] = $transaction->shipping_name;

        $data['shipping_email'] = $transaction->shipping_email;

        $data['shipping_address'] = $shipping_address;

        $data['vehicle_number'] = $transaction->registration_no;

        $data['sub_total'] = $transaction->sub_total;

        $data['items'] = $transaction_items;

        $data['job_card_items'] = $jc_items;

        $data['invoice_items'] = $invoice_items;

        $data['taxes'] = array_values($tax);

        $data['discounts'] = array_values($discount);

        $data['total'] = $transaction->total;

        $data['total_qty'] = $total_qty->total_qty;

        $data['total_amount'] = $total_amount->total_amount;

        $data['total_discount'] = $total_amount->total_discount;

        $data['invoice_tax'] = $invoice_tax;

        $data['hsn_based_invoice_tax'] = $hsn_based_invoice_tax;

        $data['make_model_variant'] = $transaction->make_model_variant;

        $data['no_tax_sale'] =  $no_tax_values;

        $data['no_tax_estimation'] = $no_tax_values;

        $data['km'] = $transaction->warranty_km;

        $data['assigned_to'] = $transaction->assigned_to;

        $data['driver'] = $transaction->driver;

        $data['driver_mobile_no'] = $transaction->driver_mobile_no;

        $data['warranty'] = $transaction->warranty;

        $data["insurance"] = $transaction->insurance;

        $data["mileage"] = $transaction->warranty_km;

        $data["engine_no"] = $transaction->engine_no;

        $data["chassis_no"] = $transaction->chassis_no;

        $data["specification"] = $transaction->spec;

        $data["job_due_on"] = $transaction->job_due_on;

        $data["last_visit_on"] = $transaction->last_visit_on;

        $data["next_visit_on"] = $transaction->next_visit_on;

        $data["service_on"] = $last_updated_data->job_date;

        $data["last_visit_jc"] = $last_updated_data->reference_no;

        $data['checklist_details'] = $job_card_checklist;

        $data['first_checklists'] = $first_checklists;

        $data['complaints'] = $transaction->vehicle_complaints;

        $data['fuel_level'] = $checklist_fuellevel;
                 Log::info("TransactionController->print :- return ");

        return $data;

    }

    public static function convert_number_to_words($number) {
        // dd($number);
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' only ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
               // dd($string); 
            //$string .= implode(' ', $words);
        }
                
        return $string;
    }

    public function receipt(Request $request, $remote = null) {
        
                     Log::info("TransactionController->receipt :- Inside ");
$organization_id = Session::get('organization_id');

      $transactions = Transaction::select('transactions.id','transactions.order_no','transactions.date','organizations.name AS company_name','business_communication_addresses.address AS company_address','cities.name as city','business_communication_addresses.pin as company_pincode','business_communication_addresses.email_address AS company_email_id','business_communication_addresses.mobile_no AS company_phone','transactions.address as customer_address','transactions.name as customer_name','transactions.email as customer_email','transactions.mobile as customer_mobile','print_templates.data','transactions.name as received_from');

        $transactions->leftjoin('account_entries','account_entries.reference_transaction_id','=','transactions.id');
        $transactions->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $transactions->leftjoin('print_templates','print_templates.id','=','account_vouchers.print_id');
        $transactions->leftjoin('organizations','organizations.id','=','transactions.organization_id');
        $transactions->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=',
        'transactions.organization_id');
        $transactions->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
        $transactions->leftjoin('cities','cities.id','=','business_communication_addresses.city_id');
            if($remote == null) {
                $transactions->where('transactions.organization_id', $organization_id);
            }
            $transactions->where('transactions.id',$request->id);
                      
            $transaction = $transactions->first();
                  
                $receipt_items = AccountEntry::select('account_entries.voucher_no as voucher','account_entries.date as on_date','account_ledgers.display_name as mode','account_transactions.amount as amount')->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')->where('account_entries.id',$request->entry_id)->first();
            
            $number = $receipt_items->amount;
             $amount = $this->convert_number_to_words($number);
         $wording_amount = ucfirst($amount);
                
            
            $data = [];
            $data['transaction_data'] = $transaction->data;
            $data['transaction_type'] = $transaction->transaction_type;
            $data['date'] = Carbon::parse($transaction->date)->format('M d, Y');
            $data['receipt_no'] = $receipt_items->voucher;
            $data['mode'] = $receipt_items->mode;
            $data['amount'] = $receipt_items->amount;
            $data['wording_amount'] = $wording_amount;
            $data['on_date'] =  Carbon::parse($receipt_items->on_date)->format('M d, Y');
            $data['received_from'] = $transaction->received_from;

            $data['company_name'] = $transaction->company_name;
            $data['company_address'] = $transaction->company_address;
            $data['city'] = $transaction->city;
            $data['pin'] = $transaction->company_pincode;
            $data['mobile_no'] = $transaction->company_phone;
            $data['company_email_id'] = $transaction->company_email_id;
            $data['customer_name'] = $transaction->customer_name;
            $data['customer_address'] = $transaction->customer_address;
            $data['customer_mobile_no'] = $transaction->customer_mobile;
            $data['customer_email'] = $transaction->customer_email;
            $data['jc_no'] = $transaction->order_no;
                     Log::info("TransactionController->receipt :- return ");
            
            return $data;


    }

   public function print_receipt(Request $request, $remote = null) {

                     Log::info("TransactionController->print_receipt :- Inside ");
       $organization_id = Session::get('organization_id');
        $person_list = People::select('user_type','id','person_id','business_id');
        if($request->user_type == 0) {
            $person_list->where('person_id', $request->input('people_id'));
        } else if($request->user_type == 1) {
            $person_list->where('business_id', $request->input('people_id'));
        }
        $person_list->where('organization_id', $organization_id);
        $persons = $person_list->first();

                    if($persons != null) {
            if($persons->user_type == 0) {
                $address = PeopleAddress::select('address')->where('person_id', $request->input('people_id'))->first();
            }
            else if($persons->user_type == 1) {
                $address = BusinessCommunicationAddress::select('address')->where('business_id', $request->input('people_id'))->first();

            }
        } 
           $company = Organization::select('organizations.name',
        'business_communication_addresses.address')->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','organizations.business_id')->where('organizations.id',$organization_id)->first();
          // dd($company);
            $receipt_items = AccountEntry::select('account_entries.voucher_no as voucher','account_entries.date as on_date','a.display_name AS mode','b.display_name AS received_from','account_transactions.amount as amount','print_templates.data','transactions.order_no')
            ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
            ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
            ->leftjoin('account_ledgers as a','a.id','=','account_transactions.debit_ledger_id')
            ->leftjoin('account_ledgers as b','b.id','=','account_transactions.credit_ledger_id')
            ->leftjoin('print_templates','print_templates.id','=','account_vouchers.print_id')
            ->leftjoin('transactions','transactions.id','=','account_entries.grn_no')
            ->where('account_entries.id',$request->id)->first();
            //dd($$receipt_items);
             
        $number = $receipt_items->amount;
         $amount = $this->convert_number_to_words($number);
         $wording_amount = ucfirst($amount);

               
        
        $data = [];
        $data['transaction_data'] = $receipt_items->data;
        //$data['transaction_type'] = $transaction->transaction_type;
        $data['received_from'] = $receipt_items->received_from;
        $data['date'] = Carbon::now()->format('M d, Y');
        $data['receipt_no'] = $receipt_items->voucher;
        $data['mode'] = $receipt_items->mode;
        $data['amount'] = $receipt_items->amount;
        $data['wording_amount'] = $wording_amount;
        $data['on_date'] =  Carbon::parse($receipt_items->on_date)->format('M d, Y');
        $data['customer_name'] = $receipt_items->received_from;
        $data['company_address'] = $company->address;
        $data['company_name'] = $company->name;
        $data['jc_no'] = $receipt_items->order_no;
                     Log::info("TransactionController->print_receipt :- Return ");
        
        return $data;


    }
    public function send_all(Request $request) {

                     Log::info("TransactionController->send_all :- Inside ");
        $transaction = Transaction::findOrFail($request->input('id'));

        //dd($transaction);
        $transaction->notification_status = 0;
        $transaction->save();
                     Log::info("TransactionController->send_all :- Return ");

        return response()->json(array('message' => 'Send Successfully..!'));    

    }

    public function sms_send(Request $request) {

                     Log::info("TransactionController->sms_send :- Inside ");
        $sms_date =Carbon::now();
        $current_date =  $sms_date->format('d-m-Y');
        
        //dd($current_date);

        $organization_id = Session::get('organization_id');

        $transaction_last = Transaction::select('transactions.transaction_type_id', 'transactions.mobile', 'transactions.id', 'transactions.date', 'transactions.total', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no',  
         DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code'));
        $transaction_last->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
        $transaction_last->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });

        $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
        $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');        
       
        $transaction_last->where('transactions.id', $request->id);
        $transactions = $transaction_last->first();     

        //$transactions = Transaction::findOrFail($request->input('id'));

        //dd($transactions);


        $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();

        $business_name = Session::get('business');

        
            if($transaction_type->name == "receipt" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "job_request" || $transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {                

                switch ($transaction_type->name) {
                    case 'receipt':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."Payment of Rs. ".$transactions->total." on 11-May-18 for the Invoice ".$transactions->order_no." has been received.". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'purchase_order':
                        $message = "You have a new order from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'sale_order':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."your purchase order has been confirmed. Order ref:".$transactions->order_no." Amount: Rs.".$transactions->total. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                        case 'estimation':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."your estimation has been created. Order ref:".$transactions->order_no." Amount: Rs.".$transactions->total. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'sales':
                        $message =  "Dear ".$transactions->customer.",". "\n\n" ."Thanks for choosing PropelSoft. 
                        Invoice with Ref:1236 for Rs. ".$transactions->total." has been created on 11-May-18.". "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'sales_cash':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."Your Payment of Rs. ".$transactions->total." has been received for the Invoice ".$transactions->order_no."". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'delivery_note':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."Your order for ".$transactions->reference_no. " of Rs. ".$transactions->total." has been delivered. Ref: ".$transactions->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'job_request':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."your estimation has been created. Order ref:".$transactions->order_no." Amount: Rs.".$transactions->total. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;

                    case 'job_card':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."your Jobcard has been created. Order ref:".$transactions->order_no." Amount: Rs.".$transactions->total. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;  

                    case 'job_invoice':
                        $message =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." "."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        break;

                    case 'job_invoice_cash':
                        $message = "Dear ".$transactions->customer.",". "\n\n" ."Your Payment of Rs. ".$transactions->total." has been received for the Invoice ".$transactions->order_no."". "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        break;
                }

                if($transactions->mobile != "") {

                    //$this->dispatch(new SendSms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $transactions->mobile, $message));

                    //Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $transactions->mobile, $message);
                }
            }
        
                     Log::info("TransactionController->sms_send :- Return ");

        return response()->json(array('message' => 'Send Successfully..!'));

    }


    public function estimation_sms(Request $request)
    {
       // dd($request->all());
        Log::info("TransactionController->estimation_sms :- Inside ");
        $sms_date =Carbon::now();
        $current_date =  $sms_date->format('d-m-Y');

        /*if($organization_id){
            $org_id = $organization_id;
        }else{
            $org_id = session::get('organization_id');
        }*/

        $organization_id = session::get('organization_id');
        $id = $request->id;
        Log::info("TransactionController->estimation_sms ->Get Id:- ".json_encode($request->id));
        
        //dd($organization_id);

        $sms_content_requerment=Transaction::select('vehicle_register_details.registration_no as vehicle_no','transactions.name','transactions.mobile')->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')->where('transactions.id',$id)->get();
        //dd($sms_content_requerment);
        foreach ($sms_content_requerment as $key => $value) {
            $vehicle=$value->vehicle_no;
            $mobile_no=$value->mobile;
            $customer_name=$value->name;
        }

        $transaction_last = Transaction::select('transactions.transaction_type_id', 'transactions.mobile', 'transactions.id','wms_transactions.vehicle_note', 'transactions.date', 'transactions.total', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no',  
             DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code')
             ,DB::raw('transactions.total + wms_transactions.advance_amount as total_amount'));

            $transaction_last->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $transaction_last->leftJoin('people', function($join) use($organization_id)
                {
                    $join->on('people.person_id','=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
                });
            $transaction_last->leftJoin('people AS business', function($join) use($organization_id)
                {
                    $join->on('business.business_id','=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->where('transactions.user_type', '1');
                });

            $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
            $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');        
           
            $transaction_last->where('transactions.id', $request->id);
            $transactions = $transaction_last->first(); 
            
            Log::info("TransactionController->estimation_sms With line No 8517:- ".json_encode($transactions));
        
        

            $module_name = Session::get('module_name');

            if($module_name == "trade_wms"){
                $vehicle_note = ($transactions->vehicle_note)?$transactions->vehicle_note:null;
                

                if($vehicle_note == null){
                    $vehicle_note = "No Specific Notes";
                }else{
                    $vehicle_note = $transactions->vehicle_note;
                }
                Log::info("TransactionController->estimation_sms :- ".$vehicle_note);
            }           


        $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();

        $business_name = Session::get('business');

        //dd($transaction_type->name);
        
            if($transaction_type->name == "purchases"|| $transaction_type->name == "receipt" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "job_request" || $transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "credit_note" || $transaction_type->name == "debit_note" || $transaction_type->name == "goods_receipt_note") 
            {
                switch ($transaction_type->name) 
                {
                    case 'receipt':

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Payment of Rs. ".$transactions->total." for Invoice:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Receipt";
                        break;

                    case 'purchase_order':
                        $sms_content = "You have a new purchase order from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase Order";
                        break;

                    case 'purchases':
                        $sms_content = "You have a new purchase from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase";
                        break;  

                    case 'sale_order':
                        
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Sale Order of Rs. ".$transactions->total." for Sale Order Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Sale Order";
                        break;

                        case 'estimation':                      

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Estimation of Rs. ".$transactions->total." for Estimation Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Estimation";
                        break;
                    case 'credit_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Sale Return of Rs. ".$transactions->total." for credit note:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Sale Return";
                    break;
                    case 'debit_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Purchase Return of Rs. ".$transactions->total." for debit note:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Purchase Return";
                    break;
                    case 'goods_receipt_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Receipt Note of Rs. ".$transactions->total." for purchase:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Good Receipt Note";
                    break;

                    case 'sales':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Credit Sale";
                        break;

                    case 'sales_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Cash Sale";
                        break;

                    case 'delivery_note':
                        $sms_content = "Dear ".$transactions->customer.",". "\n\n" ."Your order for ".$transactions->reference_no. " of Rs. ".$transactions->total." has been delivered. Ref: ".$transactions->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Delivery Note";
                        break;

                    case 'job_card':
                        /*$sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Your Jobcard Number:".$transactions->order_no." "."for vehicle"." "..$vehicle." "."Created on"." ".$current_date." "."."."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;*/
                        $url=url('jc_acknowladge/');
                        $sms_content ="Please note the Jobcard"." ".$transactions->order_no." "."for Vehicle ".$vehicle." "."dated ".$current_date."."."\n\n"."Vehicle Note: ".$vehicle_note."\n\n"."Visit below link for the Status of Job. " . $url . '/' . $transactions->id. '/'.$organization_id;
                        $mge ="Job Card";
                        break;

                    case 'job_request':
                        $url=url('viewlist/');
                        $sms_content="Click  this link to approve estimation  for your vehicle : ".$vehicle." ". $url . '/' . $transactions->id. '/'.$organization_id."\r\n".$customer_name;
                        $mge ="Estimation link ";
                        break;

                    case 'job_invoice':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        
                        $mge = "Invoice";
                        break;

                    case 'job_invoice_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;

                        $mge ="Invoice";

                        break;
                }

                if($transactions->mobile != "") {

                    $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);

                    Custom::add_addon('sms');
                }

            }
        

        /*$url=url('viewlist/');;
        $sms_content="Click  this link to approve estimation  for your vehicle : ".$vehicle." ". $url . '/' . $id."\r\n".$customer_name;
        // dd($sms_content);
         $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);*/
                     Log::info("TransactionController->estimation_sms :- Return ");

       return response()->json(['status' => 1, 'message' =>$mge."  "."sent to ".$mobile_no." for approval", 'data' =>[]]); 
    }

    public function update_inventory(Request $request) {
        
                     Log::info("TransactionController->update_inventory :- Inside ");
        $organization_id = Session::get('organization_id');
        
        $transaction_type = $request->input('type');

        $transaction = Transaction::findOrFail($request->input('id'));

        //dd($transaction);

        $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();

        if($transaction->reference_id != null){
            $reference_status = Transaction::find($transaction->reference_id);
        }
        

        if($transaction_type == "goods_receipt_note") 
        {
            $items = TransactionItem::where('transaction_id', $transaction->id)->get();


            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_item_update->stock_update == 0 && $reference_status->approval_status == 1 && $transaction->approval_status == 1)
            {
            
                foreach ($items as $item) 
                {
                    //if($item->tax_id != null){

                    $selected_item = InventoryItem::find($item->item_id);

                    $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                    ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                    ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                    ->where('tax_groups.organization_id', $organization_id)
                    ->where('tax_groups.id', $item->tax_id)
                    ->groupby('tax_groups.id')
                    ->first();
                    

                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                     ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                     ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                     ->where('tax_groups.organization_id', $organization_id)
                     ->where('tax_groups.id', $selected_item->purchase_tax_id)
                     ->groupby('tax_groups.id')->first();                   

                    $sale_price_array = json_decode($selected_item->sale_price_data, true);

                    $sale_price = Custom::two_decimal($item->rate);

                    $new_selling_price = $item->new_selling_price;

                    if($new_selling_price != $sale_price){
                        $new_selling_price = $item->new_selling_price;

                    }else{
                        $new_selling_price = $sale_price;
                    }
                    
                    if($sales_tax_value != null){

                        //$update_price =  Custom::two_decimal( $new_selling_price / (($sales_tax_value->value/100) + 1));

                        $tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($new_selling_price));

                        $update_price = Custom::two_decimal($new_selling_price + $tax_amount);
                    }else{
                        $update_price =  Custom::two_decimal( $new_selling_price);
                    }
                    

                    foreach ($sale_price_array as $key => $value) {
                        if($value['on_date'] == $transaction->date) {
                            unset($sale_price_array[$key]);
                        }
                    }


                    $sale_price_data = array_values($sale_price_array);

                    $sale_price_data[] = ["list_price" => $new_selling_price, "discount" => 0, "discount_amount" => 0.00,  "sale_price" => $update_price, "on_date" => $transaction->date];
                                          

                    $selected_item->purchase_price = $item->rate;
                    $selected_item->selling_price = $new_selling_price;
                    $selected_item->base_price = $update_price;
                    $selected_item->purchase_tax_id = $item->tax_id;
                    $selected_item->sale_price_data = json_encode($sale_price_data);

                    $selected_item->save();


                    /* Inventory Stock Update */

                    $stock= InventoryItemStock::find($item->item_id);

                    if($selected_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($selected_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($selected_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $selected_item->purchase_price;
                    }

                    //$inventory_item = InventoryItem::find($item->item_id);

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                    if($stock != null) {

                        $inventory_stock = $stock->in_stock + $item->quantity;
                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        //$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $selected_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);
                        $stock->save();


                        /* Account Transaction */

                        $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                        $data_entry[] = ['debit_ledger_id' => $selected_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                        /* End */

                        //$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);

                        $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                        $stock->save();


                        

                    }

                    /* Inventory Stock Update - End */

                    //$voucher = AccountEntry::where('id',$stock->entry_id)->first();
                            
                    $voucher_reference = Transaction::find($transaction->reference_id); 

                    $batch_date = str_replace('-', '', $transaction->date);

                    $inventory_item_batch = new InventoryItemBatch;

                    $inventory_item_batch->item_id = $item->item_id;
                    $inventory_item_batch->global_item_model_id = $selected_item->global_item_model_id;

                    $inventory_item_batch->batch_number = $batch_date.'/'.$selected_item->id.'/'.$voucher_reference->order_no;

                    $inventory_item_batch->purchase_price = $selected_item->purchase_price;
                    $inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
                    $inventory_item_batch->selling_price = $selected_item->selling_price;
                    $inventory_item_batch->selling_plus_tax_price = $selected_item->base_price;

                    $inventory_item_batch->purchase_tax_id = $selected_item->purchase_tax_id;

                    $inventory_item_batch->sales_tax_id = $selected_item->tax_id;

                    $inventory_item_batch->quantity = $item->quantity;
                    $inventory_item_batch->unit_id = $selected_item->unit_id;
                    $inventory_item_batch->transaction_id = $transaction->id;
                    $inventory_item_batch->user_type = $transaction->user_type;
                    $inventory_item_batch->people_id = $transaction->people_id;                 
                    $inventory_item_batch->organization_id = $organization_id;


                    $inventory_item_batch->save();
                    Custom::userby($inventory_item_batch, true);

                    /* Inventory Item Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                    /*End*/
                }

            }   
        }
            

        $transaction->item_update_status = 1;
        $transaction->save();

                             Log::info("TransactionController->update_inventory :- return ");

        return response()->json(array('message' => 'Updated Successfully..!'));
    }   

    //Reference for Credit and Debit Note
    //Reference---  http://www.svtuition.org/2012/08/journal-entries-of-credit-note.html
    //Reference---  https://www.double-entry-bookkeeping.com/accounts-receivable/credit-note-journal-entries/

    public function store_transaction(Request $request, $method)
    {
                     Log::info("TransactionController->store_transaction :- Inside ");
        //dd($request->all());
        $raw_post = file_get_contents('php://input');
        Log::info("TransactionController->store_transaction :-request post ".json_encode($_POST));
        Log::info("TransactionController->store_transaction :-request raw post ".json_encode($raw_post));


try{
    
    

        $result = DB::transaction(function () use ($request, $method)
        {
        
        $modulename = Session::get('module_name');  
        $organization_id = Session::get('organization_id');

        $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();
        
        $vou_restart_values = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
        

        /* Validation for module wise */

        if($modulename == "trade_wms"){
            $validator = Validator::make($request->all(), [
                'registration_no' => 'required',
                'people_id' => 'required'
            ]);
        }

        if($modulename == "trade" ){
            $validator = Validator::make($request->all(), [
                'people_id' => 'required'
            ]);     
        }

        if($modulename == "inventory" ){
            $validator = Validator::make($request->all(), [
                'people_id' => 'required'
            ]);         
        }   


        if($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->messages()->first()]);
        }
        

        if($transaction_type->name == 'job_card' || $transaction_type->name =='job_request')
        {
            if($request->input('item_id') == null || $request->input('item_id') == '') {
                
                if($method == "remote") {                   
                    return response()->json(array('status' => 0, 'message' => 'Atleast one item should be selected.'));
                }                           
            }
        }
        elseif ($transaction_type->name == 'job_invoice' || $transaction_type->name =='job_invoice_cash') {

            if($request->input('item_id') == null || $request->input('item_id') == '') {                
                                    
                return response()->json(array('status' => 0, 'message' => 'Atleast one item should be selected.')); 
            }           
        }
        else{           

            if(count(array_filter($request->input('item_id'))) == 0 )
            {                               
                return response()->json(array('status' => 0, 'message' => 'Atleast one item should be selected.'));
            }           
        }

        /*End*/     
        
        $organization = Organization::findOrFail($organization_id);
        $uuid= $request->input('attachment_uid');
        $complaint = $request->input('complaints');

        /*Item - data */

        $item_id =  $request->input('item_id');
        $batch_id =  $request->input('batch_id');       
        $parent_item_id =  $request->input('parent_item_id');
        $description = $request->input('description');
        $quantity = $request->input('quantity');
        $rate = $request->input('rate');
        $discount = $request->input('discount');
        $tax_id = $request->input('tax_id');
        $new_selling_price = $request->input('new_selling_price');
        $discount_id = $request->input('discount_id');
        $discount_value = $request->input('discount_value');
        $advance_amount = $request->input('advance_amount');
        $assigned_employee_id = $request->input('assigned_employee_id');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $job_item_status = $request->input('job_item_status');

        $duration = $request->input('duration');
        $stock_update = $request->input('stock_update');
        $invoice_approval = $request->input('invoice_approval');

        $selling_price = $request->input('selling_price');
        $over_all_discount = $request->input('over_all_discount');

        /* End */


        if($method == "update") {
            $transaction = Transaction::findOrFail($request->input('id'));

            if($transaction->approval_status == 1) {
                return response()->json(array('status' => 0, 'message' => 'Approved transactions cannot be updated.', 'data' => []));
            }
        }
        

        $entry = [];
        
        $cash_payment = '';

        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();
        
        if($method == "store" || $method == "update" || $method == "lowstock")
        {
            $person = People::select('id as people_org_id','user_type', 'display_name');

            if($request->input('people_type') == 0) {
                $person->where('person_id', $request->input('people_id'));
            } 
            else if($request->input('people_type') == 1) {
                $person->where('business_id', $request->input('people_id'));
            }
            
            $person->where('organization_id', $organization_id);

            $persons = $person->first();
            

            $account_ledgers = AccountLedger::select('account_ledgers.id');

            if($persons->user_type == 0) {
              $account_ledgers->where('person_id', $request->input('people_id'));
              $person_id = $request->input('people_id');
              $business_id = null;
            }
            else if($persons->user_type == 1) {
              $account_ledgers->where('business_id', $request->input('people_id'));
              $business_id = $request->input('people_id');
              $person_id = null;
            }


            if($request->input('person_type') != null) {

                $person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;

                $person_type = DB::table('people_person_types')->where('people_id', $persons->people_org_id)->where('person_type_id', $person_type_id)->first();

                if($person_type == null) {
                    DB::table('people_person_types')->insert(['people_id' => $persons->people_org_id, 'person_type_id' => $person_type_id]);
                }
            }                   

        } 

        else if($method == "remote") {

            $existing_person = People::select('*');

            if($request->input('user_type') == 0) {
                $existing_person->where('person_id', $request->input('people_id'));
            } else if($request->input('user_type') == 1) {
                $existing_person->where('business_id', $request->input('people_id'));
            }
            
            $existing_person->where('organization_id', $organization_id);

            $existing_persons = $existing_person->first();

            if($existing_persons == null) {
                $selected_people = People::select('people.id', 'people.company', 'people.first_name', 'people.middle_name', 'people.last_name', 'people.display_name', 'people.mobile_no', 'people.email_address', 'people.phone', 'people.gst_no')->where('people.id')->first();

                $persons = new People();            

                if($request->input('people_id') != null) {

                    if($request->input('user_type') == 0) {
                    $persons->person_id = $request->input('people_id');
                    } else if($request->input('reference_user_type') == 1) {
                        $persons->business_id = $request->input('people_id');
                    }
                }
                
                $persons->user_type = $request->input('reference_user_type');
                $persons->first_name = $request->input('name');
                $persons->display_name = $request->input('reference_customer_name');
                $persons->mobile_no = $request->input('mobile');
                $persons->email_address = $request->input('email');
                $persons->organization_id = Session::get('organization_id');                
                $persons->save();               

                if($persons->id) {
                    $people_address = new PeopleAddress();
                    $people_address->people_id = $persons->id;
                    $people_address->address_type = 0;
                    $people_address->address = $request->input('address');
                    /*$people_address->city_id = $request->input('city_id');
                    $people_address->pin = $request->input('pin');*/
                    $people_address->save();
                }
            } else {
                $persons = $existing_persons;
            }

            $account_ledgers = AccountLedger::select('account_ledgers.id');
            $account_ledgers->where('person_id', $request->input('people_id'));
            
            $person_id = $persons->person_id;
            $business_id = $persons->business_id;
        }

        $account_ledgers->where('organization_id', $organization_id);

        $account_ledger = $account_ledgers->first();        

        $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

        if($account_ledger != null){
            $customer_ledger = $account_ledger->id;
        }
        else {
            if($transaction_type->name == "purchases") {

                $ledgergroup = AccountGroup::where('name', 'sundry_creditor')->where('organization_id', $organization_id)->first();

                $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'credit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

            } else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") {

                $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();
                
                $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
            }
            else if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

                $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();

                $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);
            }

        }       
        
        /*$field_name = $request->input('field_name');
        $field_type = $request->input('field_type');
        $field_format = $request->input('field_format');
        $type =  $request->input('check_type');
        $field_value =  $request->input('field_value');
    

        */

        $reference_transaction_type = null;

        $discount_percent = null;

        if($discount_value != null) {

            if($request->input('discount_is_percent') != null) {
                $discount_percent = 1;
            } else {
                $discount_percent = 0;
            }
        }

        $tax_type =  $request->tax_type;


        if($method == "store" || $method == "remote" || $method == "lowstock") 
        {
            $transaction = new Transaction;

        } else if($method == "update") {
            $transaction = Transaction::findOrFail($request->input('id'));
        }



        $transaction->user_type = $request->input('people_type');

        if($method == "remote") {
            $transaction->reference_no = $request->remote_reference_no;

        }elseif($method == "store" || $method == "lowstock") {
            $transaction->reference_no = $request->order_id;
        }
        

        if($method == "store" || $method == "update" || $method == "lowstock" || $method == "remote") 
        {
        

            if($request->input('reference_id') != null) {
                //GET Transaction TYPE AND ADD IT IN WHERE CONDITION
                $transaction->reference_id =$request->input('reference_id');

                $reference_transaction_type = Transaction::select('account_vouchers.name')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.id',$request->input('reference_id'))
                ->first();
            }
        }

        if($method == "remote") {
            $transaction->remote_reference_no = $request->input('remote_reference_no');
            $transaction->notification_status = 3;
            $transaction->reference_id = $request->input('order_id');
        }

        $transaction->people_id = $request->input('people_id');
        $transaction->ledger_id = $request->input('ledger_id'); 

        if($transaction_type->name == 'job_card' || $transaction_type->name =='job_request' || $transaction_type->name =='job_invoice' || $transaction_type->name =='job_invoice_cash')
        {
            $transaction->date = ($request->input('job_date')!=null) ? Carbon::parse($request->input('job_date'))->format('Y-m-d') : null;

        }else{
            $transaction->date = ($request->input('invoice_date') != null) ? Carbon::parse($request->input('invoice_date'))->format('Y-m-d') : null;
        }

        $transaction->due_date = ($request->input('due_date') != null) ? Carbon::parse($request->input('due_date'))->format('Y-m-d') : null;

        $transaction->transaction_type_id = $transaction_type->id;
        $transaction->payment_mode_id = $request->input('payment_method_id');
        $transaction->term_id = $request->input('term_id');
        $transaction->employee_id = $request->input('employee_id');
        $transaction->name = $request->input('name');
        $transaction->mobile = $request->input('mobile');
        $transaction->email = $request->input('email');
        $transaction->gst = $request->input('gst');
        $transaction->notification_status = 1;
        $transaction->address = $request->input('address');

        $address_type = BusinessAddressType::where('name','business')->first()->id;

        $business = Business::find($organization->business_id);

        $business_communication_address = BusinessCommunicationAddress::where('business_id', $organization->business_id)->where('address_type', $address_type)->first();

        $city = City::select('cities.name','states.name AS state')->leftjoin('states', 'states.id', '=', 'cities.state_id')->where('cities.id', $business_communication_address->city_id)->first();

        
            $transaction->billing_name = $request->input('billing_name');
            $transaction->billing_mobile = $request->input('billing_mobile');
            $transaction->billing_email = $request->input('billing_email');
            $transaction->billing_gst = $request->input('billing_gst');
            $transaction->billing_address = $request->input('billing_address');

            $transaction->shipping_name = $request->input('shipping_name');
            $transaction->shipping_mobile = $request->input('shipping_mobile');
            $transaction->shipping_email = $request->input('shipping_email');
            $transaction->shipping_address = $request->input('shipping_address');   


        $transaction->shipment_mode_id = $request->input('shipment_mode_id');

        $transaction->shipping_date = ($request->input('shipping_date') != null) ? Carbon::parse($request->input('shipping_date'))->format('Y-m-d') : null;

        $transaction->discount_is_percent = $discount_percent;
        //$transaction->discount = $request->input('discount');
        $transaction->organization_id = $organization_id;
        $transaction->pin = Custom::otp(4);
        $transaction->tax_type = $tax_type;

        if($transaction_type->name == "job_invoice_cash") {
            $transaction->approval_status = 1;
        }

        if($method == "remote") {
            $transaction->notification_status = 3;
        }

        if($request->input('send_po') == 1) {
            $transaction->notification_status = 0;
        }           

        $transaction->save();

        /* Using Packgage metering on over all addon transacion*/

            Custom::add_addon('transaction');

        /* end */


        /* Update for status colour */

        if($method == "remote") {           

            DB::table('transactions')->where('id',$transaction->reference_id)->update(['notification_status'=> "3"]);
        }

        /* Update or Store */

        /*if($method == "store" || $method == "update") {   

            if($request->input('people_type') == 0) {
                DB::table('people')->where('person_id', $transaction->people_id)->update(['mobile_no'=> $transaction->mobile,'email_address' => $transaction->email ]);
            } else if($request->input('people_type') == 1) {
                DB::table('people')->where('business_id', $transaction->people_id)->update(['mobile_no'=> $transaction->mobile,'email_address' => $transaction->email ]);
            }
        }*/

        /* end */

        /* ========== Trade-WMS Store Function Begins ==========  */

            if($method == "store" || $method == "remote" || $method == "lowstock") 
            {           
                $wms_transaction = new WmsTransaction;

            } else if($method == "update") {

                $wms_transaction = WmsTransaction::where('transaction_id',$request->input('id'))->first();
                
            }           
            
            if($transaction_type->name == 'job_card' || $transaction_type->name =='job_request' || $transaction_type->name =='job_invoice' || $transaction_type->name =='job_invoice_cash')
            {
                
                $wms_transaction->transaction_id = $transaction->id;
            
                $wms_transaction->registration_id = $request->input('registration_no');
                $wms_transaction->engine_no = $request->input('engine_no');
                $wms_transaction->chasis_no = $request->input('chasis_no');
            
                //$wms_transaction->vehicle_usage_id = $request->input('vehicle_usage_id');

                $wms_transaction->jobcard_status_id = $request->input('jobcard_status_id');

                $wms_transaction->service_type = $request->input('service_type');
                $wms_transaction->assigned_to = $request->input('employee_id');
                
                $wms_transaction->payment_terms = $request->input('payment_terms');
                $wms_transaction->payment_details = $request->input('payment_details');
                $wms_transaction->delivery_by = $request->input('delivery_by');
                $wms_transaction->delivery_details = $request->input('delivery_details');
                $wms_transaction->vehicle_last_visit = $request->input('vehicle_last_visit');
                $wms_transaction->vehicle_last_job = $request->input('vehicle_last_job');
                $wms_transaction->vehicle_mileage = $request->input('vehicle_mileage');

                $wms_transaction->advance_amount = $request->input('advance_amount');

                $wms_transaction->next_visit_mileage = $request->input('next_visit_mileage');

                $wms_transaction->job_date = ($request->input('job_date')!=null) ? Carbon::parse($request->input('job_date'))->format('Y-m-d') : null;

                $wms_transaction->job_due_date = ($request->input('job_due_date')!=null) ? Carbon::parse($request->input('job_due_date'))->format('Y-m-d') : null;

                $wms_transaction->job_completed_date = ($request->input('job_completed_date')!=null) ? Carbon::parse($request->input('job_completed_date'))->format('Y-m-d') : null;

                $wms_transaction->vehicle_next_visit = ($request->input('vehicle_next_visit')!=null) ? Carbon::parse($request->input('vehicle_next_visit'))->format('Y-m-d') : null;

                $wms_transaction->vehicle_next_visit_reason = $request->input('vehicle_next_visit_reason');
                $wms_transaction->vehicle_note = $request->input('vehicle_note');
                $wms_transaction->vehicle_complaints = $complaint;
                $wms_transaction->driver = $request->input('driver');
                $wms_transaction->driver_contact = $request->input('driver_contact');
            
                $wms_transaction->organization_id = $organization_id;
                $wms_transaction->shift_id = $request->input('shift_id');
                $wms_transaction->pump_id = $request->input('pump_id');
                $wms_transaction->save();

                Custom::userby($wms_transaction, true);

                if($wms_transaction)
                {
                    if($transaction_type->name == 'job_invoice_cash' || $transaction_type->name == 'job_invoice')
                    {
                        
                        $va = DB::table('wms_transactions')->where('jobcard_status_id','!=',null)->where('transaction_id',$request->input('order_id'))->update(['jobcard_status_id'=> "8"]);            
                    }
                    
                }
                



                if($transaction_type->name == 'job_card')
                {

                    /*if( $request->input('attachment_uid')!=""){

                        $If_Existed=WmsAttachment::where('uuid',$request->input('attachment_uid'));

                        if($If_Existed)
                        {
                            //$If_Existed->transaction_id=$transaction->id;
                            $If_Existed->update(array(
                                 'transaction_id'=>$transaction->id));
                        
                                    $files=$If_Existed->get();
                                    $path_array_temp = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/temp");
                
                                    $temp_path = '';

                                    foreach ($path_array_temp as $p) {
                                            $temp_path .= $p."/";
                                            if (!file_exists(public_path($temp_path))) {
                                                    mkdir(public_path($temp_path), 0777, true);

                                                }
                                        }
                            
                                    
                                    $path_array_origional = explode('/', 'wms_attachments/org_'.Session::get('organization_id').'/jobcard_'.$transaction->id);
                
                                    $origional_path = '';
                                    $thumbnail_image_path = '';

                                    foreach ($path_array_origional as $p) {
                                            $origional_path .= $p."/";
                                            if (!file_exists(public_path($origional_path))) {
                                                    mkdir(public_path($origional_path), 0777, true);

                                                }
                                    }
                                    //$thumbnail_image_path = $p."/thumbnails/";
                                    $thumbnail_image_path_array = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/jobcard_".$transaction->id.'/thumbnails');

                                    $thumbnail_image_path='';
                                    
                                    foreach ($thumbnail_image_path_array as $p) {
                                            $thumbnail_image_path .= $p."/";
                                        //$thumbnail_image_path = $p."/thumbnails/";
                                            if (!file_exists(public_path($thumbnail_image_path))) {
                                                mkdir(public_path($thumbnail_image_path), 0777, true);

                                            }

                                        }

                                foreach ($files as  $value) {
                                    $thumbnail_file=$value->thumbnail_file;
                                    $origional_file=$value->origional_file;
                                    
                                    //  Storage::move(asset("public/".$temp_path)."/".$origional_file,asset("public/".$origional_path)."/".$origional_file);
                                    //Storage::move(asset("public/".$temp_path)."/".$thumbnail_file,asset("public/".$thumbnail_image_path)."/".$thumbnail_file);
                                        copy (public_path($temp_path) . '/' . $origional_file,  public_path($origional_path)  . '/' . $origional_file); 
                                        copy (public_path($temp_path) . '/' . $thumbnail_file,  public_path($thumbnail_image_path)  . '/' . $thumbnail_file); 
                                }

                                    # code...
                        

                        }
                    }*/
                    
                    
                    if($wms_transaction != "")
                    {
                        $transaction_id = $transaction->id;         
                        
                        //$getReadingIdCount=count(array_filter($reading_id));
                    
                        
                        if($request->has('wms_reading_factor_id'))
                        {
                            $reading_id = $request->input('wms_reading_id');                
                            $reading_factor_id = $request->input('wms_reading_factor_id');                  
                            $reading_values = $request->input('reading_values');
                            /*  $trasaction_reading_id=$request->input('');*/
                            
                            $reading_notes = $request->input('reading_notes');

                            for($i=0; $i<count($reading_factor_id); $i++){              
                                    
                                /*if($reading_values[$i]!=null)
                                    {*/
                                $ExistValues=WmsTransactionReading::where(["transaction_id" => $transaction->id,"reading_factor_id" => (int)$reading_factor_id[$i]])->exists();


                                    if($ExistValues)
                                    {
                                          $Data=["reading_values" => $reading_values[$i],"reading_notes" => $reading_notes[$i]];
                                         WmsTransactionReading::updateOrCreate(["transaction_id" => $transaction->id,"reading_factor_id" => (int)$reading_factor_id[$i]],$Data);
                                    }elseif($reading_values[$i]!="")
                                    {
                                          $Data=["transaction_id" => $transaction->id,"reading_factor_id" => (int)$reading_factor_id[$i],"reading_values" => $reading_values[$i],"reading_notes" => $reading_notes[$i]];
                                        //  dd($Data);
                                     WmsTransactionReading::updateOrCreate(["id"=>$reading_id[$i]],$Data);
                                    }elseif ($reading_id[$i]) {
                                        
                                            $IsExisted=WmsTransactionReading::findOrFail($reading_id[$i]);
                                        if($IsExisted)
                                        {
                                           WmsTransactionReading::destroy('id',$reading_id[$i]);
                                        }
                                    }else{


                                    }
                                   
                                    /*
                                    }*/
                            }
                        }
                        /*  *** WFM Checklist   **   */
                        

                        if($request->has('checklist_status'))
                        {
                            $wms_checklist_id = $request->input('wms_checklist_id');

                            $checklist_id = $request->input('checklist_id');
                            //dd($checklist_id);
                            $wms_checklist_status = $request->input('checklist_status');
                            /*  $trasaction_reading_id=$request->input('');*/
                            
                                $wms_checklist_notes = $request->input('checklist_notes');
                                for($i=0; $i<count($wms_checklist_status); $i++){

                                    if($wms_checklist_status[$i]==0 && $wms_checklist_id[$i]!="")
                                    {
                                        $IsExisted=WmsChecklist::findOrFail($wms_checklist_id[$i]);
                                        if($IsExisted)
                                        {
                                           WmsChecklist::destroy('id',$wms_checklist_id[$i]);
                                        }
                                    }


                                    if($wms_checklist_status[$i]==1)
                                    {

                                        $ExistValues=WmsChecklist::where(["transaction_id" => $transaction->id,"checklist_id" => $checklist_id[$i]])->exists();

                                    
                                        if($ExistValues)
                                        {
                                              $Data=["checklist_status" => $wms_checklist_status[$i],"checklist_notes" => $wms_checklist_notes[$i]];
                                             WmsChecklist::updateOrCreate(["transaction_id" => $transaction->id,"checklist_id" => $checklist_id[$i]],$Data);
                                        }else{


                                         $Data=["transaction_id" => $transaction->id,"checklist_id" => $checklist_id[$i],"checklist_status" => $wms_checklist_status[$i],"checklist_notes" => $wms_checklist_notes[$i]];
                                       
                                        $WmsChecklist =WmsChecklist::updateOrCreate(["id"=>$wms_checklist_id[$i],"checklist_status"=>$wms_checklist_status[$i]],$Data);
                                        //  Custom::userby($WmsChecklist, true);
                                        }
                                    }
                                    
                                }
                        }
                            //  Custom::userby($WmsChecklist, true);
                            /*}*/
                        /*else{

                            for($i=0; $i<count($wms_checklist_status); $i++){
                            //  dd($wms_checklist_status);
                                $WmsChecklist = new WmsChecklist;
                                $WmsChecklist->transaction_id = $transaction->id;;
                                $WmsChecklist->checklist_id = $checklist_id[$i];
                                $WmsChecklist->checklist_status = $wms_checklist_status[$i];
                                $WmsChecklist->checklist_notes = $wms_checklist_notes[$i];
                                $WmsChecklist->save();

                                Custom::userby($WmsChecklist, true);

                            }
                        }*/

                    }
                    else{

                    }
                }

            
            }

        /* ========= Trade-WMS Store Function Ends  ========  */




        if($method == "store" || $method == "remote" || $method == "lowstock") {
            Custom::userby($transaction, true);
        } else if($method == "update") {
            Custom::userby($transaction, false);
        }
        

        if($transaction->id != null ) { 

            $total_tax = 0;
            $discount_total_amount = 0.0;
            $tax_amount = 0;
            $discount_amount = 0;


            if($method == "update") {
                $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id', $request->input('id'))->delete();
            }

            

            for($i=0; $i<count($item_id); $i++) 
            {
                $item_discount_amount = 0.0;


                if($method == "remote") {

                    if(strpos($item_id[$i], 'g_') !== false)
                    {
                        $global_item_model_id = str_replace('g_', '', $item_id[$i] );
                        $global_item = GlobalItemModel::where('id', $global_item_model_id )->first();

                        $global_item_category = GlobalItemCategory::select('global_item_categories.id','global_item_categories.name','global_item_categories.display_name','global_item_category_types.id AS category_type_id')
                         ->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')
                          ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')
                         ->where('global_item_categories.id',$global_item->category_id)
                         ->first();

                         $item_category_exist =  InventoryCategory::where('name', $global_item_category->name)->where('organization_id', $organization_id)->first();

                         if($item_category_exist != null) {
                            $category_id = $item_category_exist->id;
                         } else {
                            $category = new InventoryCategory;
                            $category->name = $global_item_category->name;
                            $category->display_name = $global_item_category->display_name;
                            $category->category_type_id = $global_item_category->category_type_id;
                            $category->status = 1;
                            $category->organization_id = $organization_id;
                            $category->save();
                            $category_id = $category->id;
                         }

                        $inventory_item = new InventoryItem;
                        $inventory_item->name = $global_item->display_name;
                        $inventory_item->global_item_model_id = $global_item_model_id;
                        $inventory_item->category_id = $category_id;
                        $inventory_item->sku = $request->input('sku');
                        $inventory_item->hsn = $global_item->hsn;
                        $inventory_item->mpn = $global_item->mpn;
                        $inventory_item->income_account = AccountLedger::where('name', 'sales')->where('organization_id',$organization_id)->first()->id;
                        $inventory_item->expense_account = AccountLedger::where('name', 'purchases')->where('organization_id',$organization_id)->first()->id;
                        $inventory_item->inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id',$organization_id)->first()->id;
                        //$inventory_item->unit_id = ($request->input('unit_id') != null)  ? $request->input('unit_id') : null;
                        //$inventory_item->minimum_order_quantity = ($request->input('minimum_order_quantity') != null)  ? $request->input('minimum_order_quantity') : null;
                        $inventory_item->tax_id = $tax_id[$i];

                        $rate_price = $rate[$i];

                        $inventory_item->purchase_price = Custom::two_decimal($rate_price);

                        $list_price = Custom::two_decimal($rate_price);

                        $inventory_item->sale_price_data = json_encode([["list_price" => $list_price, "discount" => "0", "discount_amount" => "0",  "sale_price" => $list_price, "on_date" => $transaction->date]]);

                        $inventory_item->purchase_tax_id = $tax_id[$i];
                        $inventory_item->organization_id = $organization_id;
                        $inventory_item->save();

                        Custom::userby($inventory_item, true);
                        Custom::add_addon('records');

                        $itemId = $inventory_item->id;
                    }
                    else {
                        $itemId = $item_id[$i];
                    }
                }

                $item = new TransactionItem;

                if($method == "remote") {
                    $item->item_id = $itemId;
                } else if($method == "store" || $method == "update" || $method == "lowstock") {

                    if($item_id[$i] != null){
                        $item->item_id = $item_id[$i];
                    }
                }

                

                if($item_id[$i] != null){

                    //$item->item_id = $item_id[$i];                
                    
                    //$item->parent_item_id = ($parent_item_id[$i]) ? $parent_item_id[$i] : null;

                    $item->batch_id = ($batch_id[$i]) ? $batch_id[$i] : null;
                    $item->stock_update = ($stock_update) ? $stock_update : 0;
                    $item->description = ($description[$i]) ? $description[$i] : null;
                    $item->duration = ($duration[$i]) ? $duration[$i] : null;
                    $item->quantity = ($quantity[$i]) ? $quantity[$i] : 0.00;
                    $item->rate = ($rate[$i]) ? $rate[$i] : 0.00;
                    $item->amount = ($quantity[$i] && $rate[$i]) ? $quantity[$i]*$rate[$i] : 0.00;

                    if($transaction_type->name == "purchase_order"){
                        $item->new_selling_price = ($selling_price[$i]) ? $selling_price[$i] : 0.00;
                    }else{
                        $item->new_selling_price = ($new_selling_price[$i]) ? $new_selling_price[$i] : 0.00;
                    }

                    $item->start_time = ($start_time[$i]) ? $start_time[$i] : null;

                    $item->end_time=($end_time[$i]) ? $end_time[$i] : null;

                    $item->job_item_status = ($job_item_status[$i]) ? $job_item_status[$i] : null;

                    $item->assigned_employee_id = ($assigned_employee_id[$i]) ? $assigned_employee_id[$i] : null;               

                    $item->tax_id = ($tax_id[$i]) ? $tax_id[$i] : null;

                    $item->transaction_id = $transaction->id;

                    $item->percentage = ($over_all_discount) ? $over_all_discount : null;

                    $item->save();
                
                    
                    $tax_array_text = [];

                    $tax_group = TaxGroup::where('id', $tax_id[$i])->first();   
                    
                    if($discount_id[$i] != null)
                    {
                        $discount_id_value = Discount::where('id', $discount_id[$i])->first();                      
                    }                                   

                    if($tax_group != null) {

                        $taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

                        $original_rate = 0;

                        // Include Tax

                        if($tax_type == 1) { 

                            $total_tax_value = 0;

                            foreach ($taxgroups as $t) {
                                $taxvalue = Tax::where('id', $t->tax_id)->first();
                                $total_tax_value += $taxvalue->value;
                            }
                            //Inclusive Tax = Rate * Tax / Tax + 100;
                            //Original Amount = Rate - Inclusive Tax ;

                            $original_rate = $rate[$i] - ($rate[$i] * $total_tax_value / ($total_tax_value + 100) );

                            
                        } 

                        // Exclude Tax
                        
                        else if($tax_type == 2) 
                        { 
                            //$original_rate = $rate[$i];
                            $discount_amount=0;

                            if($discount_value[$i]!=null)
                            {
                                $discount_amount =  ($discount_value[$i]/100)*($quantity[$i]*$rate[$i]);
                            }
                            elseif($discount_id[$i] != null)
                            {
                                $discount_id_value = Discount::where('id', $discount_id[$i])->first();

                                $discount_amount =  ($discount_id_value[$i]/100)*($quantity[$i]*$rate[$i]);                         
                            }           

                            $original_rate = ($rate[$i] - $discount_amount );
                        }                   

                        foreach ($taxgroups as $taxgroup) {
                            
                            $tax_value =Tax::where('id', $taxgroup->tax_id)->first();

                            if($tax_value->is_percent == 1) {

                                $tax_amount = ($tax_value->value/100)*($quantity[$i]*$original_rate);
                                

                            } else if($tax_value->is_percent == 0) {

                                $tax_amount = $tax_value->value;
                                
                            }

                            //https://www.accountingtools.com/articles/2017/5/15/accounting-for-sales-taxes
                            //https://cleartax.in/s/accounting-entries-under-gst

                            if($tax_amount != 0) {
                                if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {
                                    //Sales Tax is expense, All expenses are debit
                                    //Vendor (Payables) gives the item, Credit the giver
                                    $entry[] = ['debit_ledger_id' => $tax_value->purchase_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax_amount];
                                } 
                                else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" ) 
                                {
                                    //Sales Tax is liability, Liabilities are credit
                                    //Customer (Receivables) gets the item, Debit the receiver
                                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $tax_value->sales_ledger_id, 'amount' => $tax_amount];
                                }
                            }

                             $total_tax += $tax_amount;                     

                             $tax_array_text[] = ["id" => $tax_value->id, "name" => $tax_value->display_name, "value" => $tax_value->value, "is_percent" => $tax_value->is_percent, "amount" => $tax_amount];                       
                        }

                        //dd($total_tax);
                        
                        $item->is_tax_percent = ($tax_value != null) ? $tax_value->is_percent : null;

                        if(count($tax_array_text) > 0) {
                            $item->tax = json_encode($tax_array_text);
                        }

                        $item->save();
                        
                    }

                    $discount_json = null;

                    if($discount_id[$i] != null){

                        if($discount_id_value != null) {

                            if($discount_id_value->is_percent == 1) {
                                $discount_amount = ($discount_value[$i]/100)*($quantity[$i]*$rate[$i]);
                            } else if($discount_id_value->is_percent == 0 && $discount_id_value != null) {
                                $discount_amount = $discount_value[$i];
                            }


                            $discount_total_amount += $discount_amount;

                            $discount_json = json_encode(["id" => $discount_id_value->id, "name" => $discount_id_value->display_name, "value" => $discount_value[$i], "is_percent" => $discount_id_value->is_percent, "amount" => $discount_amount]);

                            $item_discount_amount = $discount_amount;

                        }
                    }

                    else {
                        $discount_amount =  ($discount_value[$i]/100)*($quantity[$i]*$rate[$i]);

                        $discount_total_amount += $discount_amount;

                        $discount_json = json_encode(["id" => null, "name" => "", "value" => $discount_value[$i], "is_percent" => null, "amount" => $discount_amount]);

                        $item_discount_amount = $discount_amount;
                    }
                    
                    $item->discount_id = ($discount_id[$i] != null) ? $discount_id[$i] : null;

                    $item->discount_value = ($discount_value[$i] != null) ? $discount_value[$i] : null;

                    if($discount_id[$i] != null)
                    {
                        $item->is_discount_percent = ($discount_id_value != null) ? $discount_id_value->is_percent : null;
                    }

                    $item->discount = $discount_json;
                    $item->save();
                }



                /* Need Account transaction table - each item amount
                (with tax )*/

                $item_txn_amount = Custom::two_decimal($item->amount + (($tax_type == 1) ? 0 :($total_tax) - ($item_discount_amount)));

                /*end*/
                

                if($method == "remote") {

                    if($item_id[$i] != null){
                        $current_item = InventoryItem::find($itemId);
                    }
                    

                } else if($method == "store" || $method == "update" || $method == "lowstock") {

                    if($item_id[$i] != null){
                        $current_item = InventoryItem::find($item_id[$i]);
                    }
                }           

                

                if($transaction_type->name == "purchases") {

                    $expense_ledger = AccountLedger::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;

                    $expense_account = ($current_item->expense_account != null) ? $current_item->expense_account : $expense_ledger;

                    //Item is expense, All expenses are debit
                    //Vendor gives the item, Credit the giver
                    if($item->amount != null) {
                        $entry[] = ['debit_ledger_id' => $expense_account, 'credit_ledger_id' => $customer_ledger, 'amount' => $item->amount];
                    }               


                    if($item_discount_amount != 0) {

                        $discount_ledger = Discount::find($item->discount_id);

                        if($discount_ledger == null) {
                            $discount_ledger_id = AccountLedger::where('name', 'purchase_discounts')->where('organization_id',$organization_id)->first()->id;
                        } else {
                            $discount_ledger_id = $discount_ledger->purchase_ledger_id;
                        }
                        //Discount is income, All incomes are credit
                        //Vendor loses amount on discount, All expenses are debit
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $item_discount_amount];
                    }
                }  

                else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") {

                    $sale_ledger = AccountLedger::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
                    

                    $income_account = ($current_item->income_account != null) ? $current_item->income_account : $sale_ledger;

                    //dd($item->amount);

                    //Item sale is income, All incomes are credit
                    //Customer gets the item, Debit the receiver
                    if($item->amount != null) {
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $income_account, 'amount' => $item->amount];
                    }

                    if($item_discount_amount != 0) {

                        if($item->discount_id != null){

                            $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                            if($discount_ledger_id == null) {

                                $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                            }
                        }

                        if($item->discount_value != null)
                        {
                            $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                        }

                        //Discount is expense, All expenses are debit
                        //Customer gets discount, All incomes are credit
                        $entry[] = ['debit_ledger_id' => $discount_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $item_discount_amount];
                    }
                }

                else if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

                    if($item->id != null){

                    $sale_ledger = AccountLedger::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

                    /* Account Transaction table amount now stored without tax , if need with using $item_txn_amount */

                    $income_account = ($current_item->income_account != null) ? $current_item->income_account : $sale_ledger;

                    //Item sale is income, All incomes are credit
                    //Customer gets the item, Debit the receiver

                    /* Account Transaction table amount now stored without tax , if need with tax amount using $item_txn_amount */
                    

                    if($item->amount != null) {
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $income_account, 'amount' => $item->amount];
                    }

                    if($item_discount_amount != 0) {

                        if($item->discount_id != null){

                            $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                            if($discount_ledger_id == null) {

                                $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                            }
                        }

                        if($item->discount_value != null)
                        {
                            $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                        }

                        //Discount is expense, All expenses are debit
                        //Customer gets discount, All incomes are credit

                        $entry[] = ['debit_ledger_id' => $discount_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $item_discount_amount];
                    }

                    }
                }

                else if($transaction_type->name == "credit_note") {

                    $sale_return =  AccountLedger::where('name', 'sale_return')->where('organization_id', $organization_id)->first()->id;
                    //Sales Return Account Debit
                    //Debtor or Customer Account Credit
                    if($item->amount != null) {
                        $entry[] = ['debit_ledger_id' => $sale_return, 'credit_ledger_id' => $customer_ledger, 'amount' => $item->amount];
                    }


                    if($item_discount_amount != 0) {

                        if($item->discount_id != null){

                        $discount_ledger = Discount::find($item->discount_id);

                        if($discount_ledger == null) {
                            $discount_ledger_id = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first()->id;
                        } else {
                            $discount_ledger_id = $discount_ledger->purchase_ledger_id;
                        }

                    

                    
                    //Accounts Receivable credit
                    //Discount Allowed debit
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $item_discount_amount];

                    }

                    }
                }


                else if($transaction_type->name == "debit_note") {

                    $purchase_return = AccountLedger::where('name', 'purchase_return')->where('organization_id', $organization_id)->first()->id;

                    //Purchase Return Account Credit
                    //Creditor Account Debit
                    if($item->amount != null) {
                        $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $purchase_return, 'amount' => $item->amount];
                    }

                    if($item_discount_amount != 0) {

                        if($item->discount_id != null)
                        {

                        $discount_ledger_id = Discount::findOrFail($item->discount_id)->sales_ledger_id;

                        if($discount_ledger_id == null) {
                            $discount_ledger_id = AccountLedger::where('name', 'purchase_discounts')->where('organization_id',$organization_id)->first()->id;
                        }               
                    

                    //Accounts Payable debit
                    //Discount credit
                    $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger_id, 'amount' => $item_discount_amount];

                    }

                    }
                }               

            }
        }
        

        /*$transaction->sub_total = $sub_total->sub_total;

        if($transaction->discount_is_percent == 1) {
                $discount_transaction = ($transaction->discount/100)*($sub_total->sub_total);
        }
        else if($transaction->discount_is_percent == 0) {
            $discount_transaction = $transaction->discount;
        }

        $transaction->discount = $discount_transaction;

        $transaction->total = ($sub_total->sub_total - ($discount_transaction + $discount_total_amount)) + (($tax_type == 1) ? 0 :($total_tax) - ($advance_amount));*/
    
        

            $sub_total = DB::table('transaction_items')->select(DB::raw('COALESCE(SUM(amount), 0)+COALESCE(SUM(tax), 0)+COALESCE(SUM(discount), 0) AS sub_total'))->where('transaction_id', $transaction->id)->first();

            $sub_total = $sub_total->sub_total;

            $transaction->sub_total = $sub_total;       

            if($request->input('item_id') != null){
                $discount_transaction = ($item->discount_value/100)*($sub_total);
            }
            else{
                $discount_transaction = 0.00;
            }
                

            $final_amount = $sub_total - $discount_total_amount;        
            

            if($transaction->discount_is_percent == 1) {

                $discount_transaction = ($transaction->discount/100)*($sub_total->sub_total);
            }
            else if($transaction->discount_is_percent == 0) {
                //$discount_transaction = $transaction->discount;
                $transaction->discount = $discount_transaction;
            }

            $transaction->discount = $discount_transaction;

            $transaction->total = Custom::two_decimal($final_amount + (($tax_type == 1) ? 0 :($total_tax) - ($advance_amount)));

            //$transaction->total = Custom::two_decimal(($final_amount + $total_tax) - $advance_amount);

            

            if($transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice_cash" ) {
                $transaction->approval_status = 1;
            }
            else{
                $transaction->approval_status = $request->input('approve');
            }

            $transaction->save();

            /* Add on Revenue total */

            if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" ) {

                if($transaction->approval_status == 1) 
                {
                    Custom::add_revenue('total_revenue', $transaction->total);
                }
            }

            /*End*/
            
    

        if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {

            if($transaction->discount != null && $transaction->discount != 0) {

                $discount_ledger = AccountLedger::where('name', 'purchase_discounts')->where('organization_id',$organization_id)->first();
                //Discount is income, All incomes are credit
                //Vendor loses amount on discount, All expenses are debit
                $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $discount_ledger->id, 'amount' => $transaction->discount];
            }

            
        } else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note") {

            /*if($transaction->discount != null && $transaction->discount != 0) {

                $discount_ledger = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first();
                //Discount is expense, All expenses are debit
                //Customer gets discount, All incomes are credit
                $entry[] = ['debit_ledger_id' => $discount_ledger->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->discount];

            }*/
        }


        else if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "debit_note") {

            /*if($transaction->discount != null && $transaction->discount != 0) {

                $discount_ledger = AccountLedger::where('name', 'sales_discounts')->where('organization_id',$organization_id)->first();
                //Discount is expense, All expenses are debit
                //Customer gets discount, All incomes are credit
                $entry[] = ['debit_ledger_id' => $discount_ledger->id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->discount];

            }*/
        }


        if($transaction_type->name == 'purchases')  
        {
            if($transaction->approval_status == 1) 
            {
                if($transaction->term_id != null)
                {

                    $term = Term::select('name')->where('id',$transaction->term_id)->where('organization_id',Session::get('organization_id'))->first();

                    $term_name = $term->name;
                }
                else
                {
                    $term_name = 'null';
                }

                if($term_name == 'on_receipt')
                {
                    $voucher_type = "payment";
                    $reference_voucher_id = $transaction->id;
                    $payment_mode_id = $transaction->payment_mode_id;
                    
                    $credit_ledger = AccountLedger::select('id')->where('name','=','Cash')->where('organization_id',$organization_id)->first();

                    $cash_payment = ['voucher_type'=>$voucher_type,'reference_voucher_id'=>$reference_voucher_id,'payment_mode'=>$payment_mode_id,'debit_ledger_id'=>$customer_ledger,'credit_ledger_id'=>$credit_ledger->id,'amount'=>$transaction->total];

                }
            }
        }

        

        usort($entry, function ($item1, $item2) {
              return $item1['debit_ledger_id'] - $item2['debit_ledger_id'];
        });

        if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "job_invoice") 
            {
                
                $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                
                //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                
                $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
    
          
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }

                
            if($method == "store" || $method == "remote" || $method == "lowstock") {

                if($transaction_type->name == "purchases")
                {
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id,null,null,$cash_payment);
                }else
                {
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, null, $transaction_type->name, $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id,null,null,null);
                }
                
                
            
            }
             else if($method == "update") {
                if($transaction_type->name == "purchases"){
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $transaction->entry_id, $transaction_type->name, $organization_id, 1, false, null, $gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id,null,null,$cash_payment);
                }else{
                    $transaction->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, $transaction->entry_id, $transaction_type->name, $organization_id, 1, false, null, $gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id,null,null,null);
                }

                    
            }
        }

        if($request->input('approve') == 1) {
            $transaction->approval_status = 1;
        }
        
        $transaction->save();


        if($transaction->entry_id != null && $transaction->order_no != null) {

            $account_entry = AccountEntry::find($transaction->entry_id);
            $transaction->order_no = $account_entry->voucher_no;
            $transaction->gen_no = $account_entry->gen_no;
            $transaction->save();

        } else if($transaction->order_no == null) {
            
            $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );

            //$gen_no = ($getGen_no)?$getGen_no:$transaction_type->starting_value;
            
            $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
    
         
         if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }

     
            $transaction->order_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

            $transaction->gen_no = $gen_no;

            $transaction->save();
        }


        /* update inventory stock  - Save and Approved */

        $stock_item_update = TransactionItem::where('transaction_id', $transaction->id)->first();

        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_item_update->stock_update == 1 && $transaction->approval_status == 1){

                if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)->first();
                        

                        $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                    
                        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                        
                        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
          
          
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }


                        if($stock != null) {

                            $inventory_stock = $stock->in_stock - $item->quantity;

                            /*if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }*/
                            
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            
                            $data = json_decode($stock->data, true);

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                            
                            $stock->data = json_encode($data);

                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);

                            $stock->save();


                        /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;
                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;
                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/


                            /* Item batch */

                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity = 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }*/

                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */
                        }
                        
                    }
                }
            }
        }

        if($transaction_type->name == "delivery_note") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_update == 0 && $invoice_approval == 1 && $transaction->approval_status == 1){

                if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)->first();
                        

                        $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                    
                        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                        
                        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
         if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }


                        if($stock != null) {

                            $inventory_stock =$stock->in_stock - $item->quantity;

                            /*if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }*/
                            
                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            
                            $data = json_decode($stock->data, true);

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                            
                            $stock->data = json_encode($data);

                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);                

                            $stock->save();


                            /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/


                            /* Item batch */

                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity = 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }*/

                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */
                        }
                        
                    }
                }
            }
        }

        if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" ||  $transaction_type->name == "debit_note"  ) {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($transaction->approval_status == 1){

                if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                    $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                    foreach ($items as $item) {

                        $stock = InventoryItemStock::find($item->item_id);

                        $inventory_item = InventoryItem::find($item->item_id);

                        $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                        $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                        if($inventory_item->purchase_tax_id != null)
                        {
                            $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                            $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                        }
                        else{
                            $purchase_tax_price = $inventory_item->purchase_price;
                        }

                        $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                        ->where('transaction_items.transaction_id', $transaction->id)
                        ->where('transaction_items.item_id', $item->item_id)->first();

                        /*if($request->gen_no){
                            $gen_no = $request->gen_no;
                        }
                        else{
                            $gen_no = null;
                        }*/

                        $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                    
                        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
        if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }


                        if($stock != null) {

                            $inventory_stock = $stock->in_stock - $item->quantity;

                            /*if($stock->in_stock <= $item->quantity)
                            {
                                $inventory_stock = 0.00;
                            }else{
                                $inventory_stock = $stock->in_stock - $item->quantity;
                            }*/

                            $stock->in_stock = $inventory_stock;
                            $stock->date = $transaction->date;
                            $data = json_decode($stock->data, true);

                            /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                            $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                            $stock->data = json_encode($data);


                            $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                            $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                            $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                            $stock->save();


                        /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =(isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null ;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/

                            /* Item batch */
                            
                            if($inventory_item_batch != null)
                            {
                                /*if($inventory_item_batch->quantity <= $item->quantity)
                                {
                                    $inventory_item_batch->quantity= 0.00;
                                }
                                else{
                                    $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;
                                }*/

                                $inventory_item_batch->quantity = $inventory_item_batch->quantity - $item->quantity;

                                $inventory_item_batch->save();
                            }   

                            /* End */
                        }   
                    }
                }
            }
        }


        if($transaction_type->name == "purchases") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_item_update->stock_update == 1 && $transaction->approval_status == 1){

            if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                

                foreach ($items as $item) 
                {
                    //if($item->tax_id != null){
                    //Log::info("TransactionController->CashPurchase :-Iterate".json_encode($item));

                    $selected_item = InventoryItem::find($item->item_id);

                    $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                    ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                    ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                    ->where('tax_groups.organization_id', $organization_id)
                    ->where('tax_groups.id', $item->tax_id)
                    ->groupby('tax_groups.id')
                    ->first();
                    

                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                     ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                     ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                     ->where('tax_groups.organization_id', $organization_id)
                     ->where('tax_groups.id', $item->tax_id)
                     ->groupby('tax_groups.id')->first();                   

                    $sale_price_array = json_decode($selected_item->sale_price_data, true);

                    $sale_price = Custom::two_decimal($item->rate);

                    $new_selling_price = $item->new_selling_price;

                    if($new_selling_price != $sale_price){
                        $new_selling_price = $item->new_selling_price;

                    }else{
                        $new_selling_price = $sale_price;
                    }
                    
                    if($sales_tax_value != null){

                        //$update_price =  Custom::two_decimal( $new_selling_price / (($sales_tax_value->value/100) + 1));

                        $tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($new_selling_price));

                        $update_price = Custom::two_decimal($new_selling_price + $tax_amount);
                    }else{
                        $update_price =  Custom::two_decimal( $new_selling_price);
                    }
                    

                    foreach ($sale_price_array as $key => $value) {
                        if($value['on_date'] == $transaction->date) {
                            unset($sale_price_array[$key]);
                        }
                    }


                    $sale_price_data = array_values($sale_price_array);

                    $sale_price_data[] = ["list_price" => $new_selling_price, "discount" => 0, "discount_amount" => 0.00,  "sale_price" => $update_price, "on_date" => $transaction->date];
                                          

                    $selected_item->purchase_price = $item->rate;
                    $selected_item->selling_price = $new_selling_price;
                    $selected_item->base_price = $update_price;
                    $selected_item->purchase_tax_id = $item->tax_id;
                    $selected_item->sale_price_data = json_encode($sale_price_data);

                    $selected_item->save();


                    /* Inventory Stock Update */

                    $stock = InventoryItemStock::find($item->item_id);
                    //dd($stock);

                    if($selected_item->purchase_tax_id != null)
                    {
                        //dd($selected_item->purchase_tax_id);
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($selected_item->purchase_price));
                        //dd($purchase_tax_value->value/100);

                        $purchase_tax_price = Custom::two_decimal($selected_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price =$selected_item->purchase_price;
                    }

                    //$inventory_item = InventoryItem::find($item->item_id);

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                    if($stock != null) {

                        $inventory_stock = $stock->in_stock + $item->quantity;
                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        //$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $selected_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);
                        $stock->save();                 


                        /* Account Transaction */

                        $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();
                            

                        $data_entry[] = ['debit_ledger_id' => $selected_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];  

                        $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, $transaction_type_name->voucher_type, $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);                      

                        /* End */

                        //$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);
                        
                        $stock->save();                 

                    }

                    /* Inventory Stock Update - End */

                    //$voucher = AccountEntry::where('id',$stock->entry_id)->first();
                            
                    $voucher_reference = Transaction::find($transaction->reference_id); 

                    $batch_date = str_replace('-', '', $transaction->date);

                    $inventory_item_batch = new InventoryItemBatch;

                    $inventory_item_batch->item_id = $item->item_id;
                    $inventory_item_batch->global_item_model_id = $selected_item->global_item_model_id;

                    $inventory_item_batch->batch_number = $batch_date.'/'.$selected_item->id.'/'.$transaction->order_no;

                    $inventory_item_batch->purchase_price = $selected_item->purchase_price;
                    $inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
                    $inventory_item_batch->selling_price = $selected_item->selling_price;
                    $inventory_item_batch->selling_plus_tax_price = $selected_item->base_price;

                    $inventory_item_batch->purchase_tax_id = $selected_item->purchase_tax_id;

                    $inventory_item_batch->sales_tax_id = $selected_item->tax_id;

                    $inventory_item_batch->quantity = $item->quantity;
                    $inventory_item_batch->unit_id = $selected_item->unit_id;
                    $inventory_item_batch->transaction_id = $transaction->id;
                    $inventory_item_batch->user_type = $transaction->user_type;
                    $inventory_item_batch->people_id = $transaction->people_id;
                    $inventory_item_batch->organization_id = $organization_id;

                    $inventory_item_batch->save();
                    Custom::userby($inventory_item_batch, true);


                    /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = (isset($stock->id)) ? $stock->id : null;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $selected_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/
                }
                
            }

            }
        }

        if($transaction_type->name == "goods_receipt_note") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_update == 0 && $invoice_approval == 1 && $transaction->approval_status == 1){

            if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                foreach ($items as $item) 
                {
                    //if($item->tax_id != null){

                    $selected_item = InventoryItem::find($item->item_id);

                    $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                    ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                    ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                    ->where('tax_groups.organization_id', $organization_id)
                    ->where('tax_groups.id', $item->tax_id)
                    ->groupby('tax_groups.id')
                    ->first();
                    

                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                     ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                     ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                     ->where('tax_groups.organization_id', $organization_id)
                     ->where('tax_groups.id', $selected_item->purchase_tax_id)
                     ->groupby('tax_groups.id')->first();                   

                    $sale_price_array = json_decode($selected_item->sale_price_data, true);

                    $sale_price = Custom::two_decimal($item->rate);

                    $new_selling_price = $item->new_selling_price;

                    if($new_selling_price != $sale_price){
                        $new_selling_price = $item->new_selling_price;

                    }else{
                        $new_selling_price = $sale_price;
                    }
                    
                    if($sales_tax_value != null){

                        //$update_price =  Custom::two_decimal( $new_selling_price / (($sales_tax_value->value/100) + 1));

                        $tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($new_selling_price));

                        $update_price = Custom::two_decimal($new_selling_price + $tax_amount);
                    }else{
                        $update_price =  Custom::two_decimal( $new_selling_price);
                    }
                    

                    foreach ($sale_price_array as $key => $value) {
                        if($value['on_date'] == $transaction->date) {
                            unset($sale_price_array[$key]);
                        }
                    }


                    $sale_price_data = array_values($sale_price_array);

                    $sale_price_data[] = ["list_price" => $new_selling_price, "discount" => 0, "discount_amount" => 0.00,  "sale_price" => $update_price, "on_date" => $transaction->date];
                                          

                    $selected_item->purchase_price = $item->rate;
                    $selected_item->selling_price = $new_selling_price;
                    $selected_item->base_price = $update_price;
                    $selected_item->purchase_tax_id = $item->tax_id;
                    $selected_item->sale_price_data = json_encode($sale_price_data);

                    $selected_item->save();


                    /* Inventory Stock Update */

                    $stock= InventoryItemStock::find($item->item_id);

                    if($selected_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($selected_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($selected_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $selected_item->purchase_price;
                    }

                    //$inventory_item = InventoryItem::find($item->item_id);

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                    if($stock != null) {

                        $inventory_stock = $stock->in_stock + $item->quantity;
                        $stock->in_stock = $inventory_stock;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        //$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $selected_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);
                        $stock->save();


                        /* Account Transaction */

                        $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                        $data_entry[] = ['debit_ledger_id' => $selected_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                        /* End */

                        //$stock->entry_id = Custom::add_entry($stock->date, $entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);

                        $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$transaction->gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                        
                        $stock->save();


                        

                    }

                    /* Inventory Stock Update - End */

                    //$voucher = AccountEntry::where('id',$stock->entry_id)->first();
                            
                    $voucher_reference = Transaction::find($transaction->reference_id); 

                    $batch_date = str_replace('-', '', $transaction->date);

                    $inventory_item_batch = new InventoryItemBatch;

                    $inventory_item_batch->item_id = $item->item_id;
                    $inventory_item_batch->global_item_model_id = $selected_item->global_item_model_id;

                    $inventory_item_batch->batch_number = $batch_date.'/'.$selected_item->id.'/'.$transaction->order_no;

                    $inventory_item_batch->purchase_price = $selected_item->purchase_price;
                    $inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
                    $inventory_item_batch->selling_price = $selected_item->selling_price;
                    $inventory_item_batch->selling_plus_tax_price = $selected_item->base_price;

                    $inventory_item_batch->purchase_tax_id = $selected_item->purchase_tax_id;

                    $inventory_item_batch->sales_tax_id = $selected_item->tax_id;

                    $inventory_item_batch->quantity = $item->quantity;
                    $inventory_item_batch->unit_id = $selected_item->unit_id;
                    $inventory_item_batch->transaction_id = $transaction->id;
                    $inventory_item_batch->user_type = $transaction->user_type;
                    $inventory_item_batch->people_id = $transaction->people_id;
                    $inventory_item_batch->organization_id = $organization_id;

                    $inventory_item_batch->save();
                    Custom::userby($inventory_item_batch, true);


                    /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id =  (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;

                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $selected_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/
                }

                
            }

            }
        }       

        /* trade - sale return is credit note*/
        if($transaction_type->name == "credit_note") {

            if($transaction->transaction_type_id != null)
            {
                $transaction_type_name = Transaction::select('transactions.id','transactions.transaction_type_id','account_vouchers.display_name AS voucher_type')
                ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
                ->where('transactions.transaction_type_id',$transaction->transaction_type_id)
                ->first();
            }

            if($stock_update == 0 && $invoice_approval == 1 && $transaction->approval_status == 1){

            if($method == "store" || $method == "remote" || $method == "lowstock" || $method == "update") {

                $items = TransactionItem::where('transaction_id', $transaction->id)->get();

                

                foreach ($items as $item) {

                    $stock = InventoryItemStock::find($item->item_id);

                    $inventory_item = InventoryItem::find($item->item_id);

                    $inventory_item_batch = InventoryItemBatch::find($item->batch_id);

                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $inventory_item->purchase_tax_id)->groupby('tax_groups.id')->first();


                    if($inventory_item->purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

                        $purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $inventory_item->purchase_price;
                    }

                    $t_items = TransactionItem::select('transaction_items.*',DB::raw('SUM(transaction_items.quantity)'))
                    ->where('transaction_items.transaction_id', $transaction->id)
                    ->where('transaction_items.item_id', $item->item_id)->first();

                    /*if($request->gen_no){
                        $gen_no = $request->gen_no;
                    }
                    else{
                        $gen_no = null;
                    }*/

                    $getGen_no = Custom::getLastGenNumber( $transaction_type->id, $organization_id,$transaction->id );
                
                    //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
                    
                    $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
          if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }


                    if($stock != null) {

                        $inventory_stock = $stock->in_stock + $item->quantity;

                        /*if($stock->in_stock <= $item->quantity)
                        {
                            $inventory_stock = 0.00;
                        }else{
                            $inventory_stock = $stock->in_stock + $item->quantity;
                        }*/
                        
                        $stock->in_stock = $inventory_stock ;
                        $stock->date = $transaction->date;
                        $data = json_decode($stock->data, true);

                        /*$data[] = ["date" => $transaction->date, "in_stock" => $inventory_stock];*/

                        $data[] = ["transaction_id" => $transaction->id,"entry_id" => $transaction->entry_id,"voucher_type" => $transaction_type_name->voucher_type,"order_no" => $transaction->order_no,"quantity" => $t_items->quantity,"date" => $transaction->date, "in_stock" => $inventory_stock,'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

                        $stock->data = json_encode($data);                      

                        $credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

                        $data_entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock) ];

                        $stock->entry_id = Custom::add_entry(($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d') : date('Y-m-d'), $data_entry, $stock->entry_id, 'stock_journal', $organization_id, 1, false, null,$gen_no, null, $request->input('reference_id'), null, null, $transaction->payment_mode_id);
                    
                        $stock->save();


                        /* Inventory Stock ledger*/

                        $model = new InventoryItemStockLedger();
                        $model->inventory_item_stock_id = $stock->id;

                        $model->inventory_item_batch_id = (isset($inventory_item_batch->id)) ? $inventory_item_batch->id : null;
                        $model->transaction_id = (isset($transaction->id)) ? $transaction->id : null;

                        $model->account_entry_id = (isset($transaction->entry_id)) ? $transaction->entry_id : null; 
                        $model->voucher_type = $transaction_type_name->voucher_type; 

                        $model->order_no = (isset($transaction->order_no)) ? $transaction->order_no : null;

                        $model->quantity = (isset($t_items->quantity)) ? $t_items->quantity : 0.00;

                        $model->date = (isset($stock->date)) ? $stock->date : date('Y-m-d H:i:s');

                        $model->in_stock = (isset($stock->in_stock)) ? $stock->in_stock : 0.00;

                        $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;
                        $model->sale_price = (isset($selected_item->base_price)) ? $inventory_item->base_price : 0.00;
                        $model->status = 1 ;

                        $model->created_at = (Carbon::now());

                        $model->save();

                        /*End*/

                        /* Item batch */
                        
                        if($inventory_item_batch != null)
                        {                           
                            $inventory_item_batch->quantity = $inventory_item_batch->quantity + $item->quantity;

                            $inventory_item_batch->save();
                        }   

                        /* End */
                    }
                }
            }

            }
        }

        /* inventory stock update - end */      


        if($transaction_type->name == "sales_cash") {

            $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
            $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

            $cash_entry = [];

            $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

            $cash_transaction_id = null;

             if($method == "update") {
                $cash_transaction = AccountEntry::select('account_entries.id')
                ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                ->where('account_entries.reference_transaction_id', $transaction->id)
                ->where('account_entries.voucher_id', $transaction_type->id)
                ->first();

                if($cash_transaction != null) {
                    $cash_transaction_id = $cash_transaction->id;
                }

            }

            Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);

        }
        
        if($transaction_type->name == "sales" && $transaction->approval_status == 1) {
            
            $pay_method_id = Term::select('name')->where('id',$request->term_id)->where('organization_id',$organization_id)->first();
            $custom_values = OrgCustomValue::select('data1 as data1')
                         ->where('screen','invoice')
                         ->where('organization_id',$organization_id)
                         ->first();
            if($pay_method_id != null  && $custom_values != null)
            {
                if($pay_method_id->name == "on_receipt" && $request->payment_method_id == 1 && $custom_values->data1 == 1)
                {
                    

                    $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
                    $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                    $cash_entry = [];

                    $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

                    $cash_transaction_id = null;

                     if($method == "update") {
                        $cash_transaction = AccountEntry::select('account_entries.id')
                        ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                        ->where('account_entries.reference_transaction_id', $transaction->id)
                        ->where('account_entries.voucher_id', $transaction_type->id)
                        ->first();

                        if($cash_transaction != null) {
                            $cash_transaction_id = $cash_transaction->id;
                        }

                    }

                    Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);
                }
            }


            


        }

        if($reference_transaction_type != null && $reference_transaction_type == "sales_cash") 
        {

            if($transaction_type->name == "debit_note") {

                $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
                $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                $cash_entry = [];

                //Accounts payable credit
                //Cash debit
                $cash_entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $cash_ledger_id, 'amount' => $transaction->total];

                if($method == "update") {

                     AccountEntry::select('account_entries.id')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
                    ->where('account_vouchers.name', $transaction_type->name)
                    ->where('account_entries.reference_voucher_id', $transaction->entry_id)
                    ->delete();

                }

                Custom::add_entry($transaction->date, $cash_entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $transaction->entry_id);
            }

            if($transaction_type->name == "credit_note") {

                $cash_transaction_type = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first();
                $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                $cash_entry = [];

                //Accounts receivable debit
                //Cash credit
                $cash_entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $cash_ledger_id, 'amount' => $transaction->total];

                if($method == "update") {

                    AccountEntry::select('account_entries.id')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
                    ->where('account_vouchers.name', $transaction_type->name)
                    ->where('account_entries.reference_voucher_id', $transaction->entry_id)
                    ->delete();

                }

                Custom::add_entry($transaction->date, $cash_entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $transaction->entry_id);
                
            }

        }

        if($transaction_type->name == "job_invoice_cash") {

            $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
            
            $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

            $cash_entry = [];

            $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

            $cash_transaction_id = null;

             if($method == "update") {
                $cash_transaction = AccountEntry::select('account_entries.id')
                ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                ->where('account_entries.reference_transaction_id', $transaction->id)
                ->where('account_entries.voucher_id', $transaction_type->id)
                ->first();

                if($cash_transaction != null) {
                    $cash_transaction_id = $cash_transaction->id;
                }

            }

            Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);

        }
        
        if($transaction_type->name == "job_invoice" && $transaction->approval_status == 1) 
        {
            //dd($transaction_type->name);
            $pay_method_id = PaymentMode::select('name')->where('id',$request->payment_method_id)->first();
            //dd($pay_method_id);
            if($pay_method_id != null)
            {
            //dd($pay_method_id->name.$request->payment_terms);
                $custom_values = OrgCustomValue::select('data1 as data1')
                     ->where('screen','job_invoice')
                     ->where('organization_id',$organization_id)
                     ->first();
                     //dd($custom_values);

                if($request->payment_terms == 1 && $pay_method_id->name == "cash")
                {


                    
                    if($custom_values != null)
                    {
                        if($custom_values->data1 == 1)
                        {
                            //dd($custom_values);

                            $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();
                    
                            $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                            $cash_entry = [];

                            $cash_entry[] = ['debit_ledger_id' => $cash_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $transaction->total];

                            $cash_transaction_id = null;

                             if($method == "update") {
                                $cash_transaction = AccountEntry::select('account_entries.id')
                                ->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
                                ->where('account_entries.reference_transaction_id', $transaction->id)
                                ->where('account_entries.voucher_id', $transaction_type->id)
                                ->first();

                                if($cash_transaction != null) {
                                    $cash_transaction_id = $cash_transaction->id;
                                }

                            }

                            Custom::add_entry($transaction->date, $cash_entry, $cash_transaction_id, 'wms_receipt', $organization_id, 1, false, null, null, null, $transaction->id, null, null, $transaction->payment_mode_id);
                        }
                                
                    }
                }
            }
                    


            

        }
        

        if($reference_transaction_type != null && $reference_transaction_type == "job_invoice_cash") {

            if($transaction_type->name == "debit_note") {

                $cash_transaction_type = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first();

                $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                $cash_entry = [];

                //Accounts payable credit
                //Cash debit
                $cash_entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $cash_ledger_id, 'amount' => $transaction->total];

                if($method == "update") {

                     AccountEntry::select('account_entries.id')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
                    ->where('account_vouchers.name', $transaction_type->name)
                    ->where('account_entries.reference_voucher_id', $transaction->entry_id)
                    ->delete();

                }


                Custom::add_entry($transaction->date, $cash_entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $transaction->entry_id);
            }

            if($transaction_type->name == "credit_note") {

                $cash_transaction_type = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first();
                
                $cash_ledger_id = AccountLedger::where('name', 'cash')->where('organization_id', $organization_id)->first()->id;

                $cash_entry = [];

                //Accounts receivable debit
                //Cash credit
                $cash_entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $cash_ledger_id, 'amount' => $transaction->total];

                if($method == "update") {

                    AccountEntry::select('account_entries.id')
                    ->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
                    ->where('account_vouchers.name', $transaction_type->name)
                    ->where('account_entries.reference_voucher_id', $transaction->entry_id)
                    ->delete();

                }

                Custom::add_entry($transaction->date, $cash_entry, null, $transaction_type->name, $organization_id, 1, false, null, null, null, $transaction->entry_id);
                
            }

        }


    
        
        
        if($transaction && $vou_restart_values)
        {
            if($vou_restart_values->restart == 1)
            {
              DB::table('account_vouchers')->where('id',$transaction_type->id)->update(['restart'=> 0, 'last_restarted' => Carbon::now()]);

            }
        }
        

		$transaction_last = Transaction::select('transactions.id', 'transactions.order_no','transactions.originated_from_id','referenced_in.order_no as jc_order_no',
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"), 
            'transactions.date as original_date', 
            'transactions.due_date as original_due_date', 'account_vouchers.name AS transaction_type','transactions.total',
            DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"),
            DB::raw("IF( (transactions.total - SUM(cash_transactions.total)) = 0, '', IF(cash_transactions.id IS NULL, transactions.total, '') )  AS balance"), 
            DB::raw("CASE 
              WHEN (transactions.total - SUM(cash_transactions.total)) = 0  THEN 1  
              WHEN transactions.due_date < CURDATE()  THEN 3 
              WHEN (transactions.total - SUM(cash_transactions.total)) > 0  THEN 2
              ELSE 0 
              END AS status"), 
            'transactions.approval_status', 
            'transactions.transaction_type_id',
               DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),
               DB::raw("IF(people.first_name IS NULL, COALESCE(business.display_name, ''), CONCAT(people.first_name, '', COALESCE(people.last_name, ''))) as customer_contact"),
               DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"),
               DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code'),
               DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'wms_transactions.registration_id','vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.name as jobcard_status','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.advance_amount','transactions.mobile');

        $transaction_last->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
        $transaction_last->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });

        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

            $receipt_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;

            $transaction_last->leftJoin('transactions AS cash_transactions', function($join) use($organization_id, $receipt_voucher)
                {
                    $join->on('cash_transactions.reference_id','=', 'transactions.id')
                    ->where('cash_transactions.transaction_type_id', $receipt_voucher);
                });
        }
        else if($transaction_type->name == "purchases") {

            $payment_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;

            $transaction_last->leftJoin('transactions AS cash_transactions', function($join) use($organization_id, $payment_voucher)
                {
                    $join->on('cash_transactions.reference_id','=', 'transactions.id')
                    ->where('cash_transactions.transaction_type_id', $payment_voucher);
                });
        } 
        else {
            $transaction_last->leftJoin('transactions AS cash_transactions', 'cash_transactions.reference_id','=', 'transactions.id');
        }
		$transaction_last->leftjoin('job_cards AS referenced_in', 'transactions.originated_from_id', '=', 'referenced_in.id');
        $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
        $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');
        
        $transaction_last->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id');
        $transaction_last->leftjoin('transactions AS reference_transactions','transactions.reference_id','=','reference_transactions.id');
        $transaction_last->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');
        $transaction_last->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');
        $transaction_last->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');
        $transaction_last->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');
        $transaction_last->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        $transaction_last->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        $transaction_last->where('transactions.organization_id', $organization_id);

        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash") {

            $transaction_sales = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

            $transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;           

            $transaction_last->where(function ($query) use ($transaction_sales, $transaction_cash) {
                $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                      ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
            });

        } else {
            $transaction_last->where('transactions.transaction_type_id', $transaction_type->id);
        }

        if($transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {

            $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

            //$transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

            //$transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

            $transaction_last->where(function ($query) use ($transaction_sales, $transaction_cash) {
                $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                      ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
            });

        } else {
            $transaction_last->where('transactions.transaction_type_id', $transaction_type->id);
        }

        $transaction_last->where('transactions.id', $transaction->id);
        
        $transactions = $transaction_last->first();


        $due = Custom::time_difference(Carbon::now()->format('Y-m-d H:i:s'), Carbon::parse($transactions->due_date)->format('Y-m-d H:i:s'), 'd');

        $business_name = Session::get('business');      

        if($modulename == "trade_wms"){
            $vehicle_note = $transactions->vehicle_note;

            if($vehicle_note == null){
                $vehicle_note = "No Specific Notes";
            }else{
                $vehicle_note = $transactions->vehicle_note;
            }
        }


        /* Approved transacation - Send SMS*/

        if($transaction->approval_status == 1)
        {
            $sms_date =Carbon::now();
            $current_date =  $sms_date->format('d-m-Y');  
            $organization_id = session::get('organization_id');

            //$id = $request->id;       

            $sms_content_requerment = Transaction::select('vehicle_register_details.registration_no as vehicle_no','transactions.name','transactions.mobile')
            ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
            ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
            ->where('transactions.id',$transaction->id)->get();

        
            foreach ($sms_content_requerment as $key => $value) {
                $vehicle=$value->vehicle_no;
                $mobile_no=$value->mobile;
                $customer_name=$value->name;
            }

            if($transaction_type->name == "receipt" || $transaction_type->name == "purchases" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "job_request" || $transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash")
            {

                switch ($transaction_type->name) 
                {
                    case 'receipt':                     

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Payment of Rs. ".$transactions->total." for Invoice:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Receipt";
                        break;

                    case 'purchase_order':
                        $sms_content = "You have a new purchase order from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase Order";
                        break;

                    case 'purchases':
                        $sms_content = "You have a new purchase from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase";
                        break;  

                    case 'sale_order':
                        
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Sale Order of Rs. ".$transactions->total." for Sale Order Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Sale Order";
                        break;

                        case 'estimation':                      

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Estimation of Rs. ".$transactions->total." for Estimation Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Estimation";
                        break;

                    case 'sales':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Credit Sale";
                        break;

                    case 'sales_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Cash Sale";
                        break;

                    case 'delivery_note':
                        $sms_content = "Dear ".$transactions->customer.",". "\n\n" ."Your order for ".$transactions->reference_no. " of Rs. ".$transactions->total." has been delivered. Ref: ".$transactions->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Delivery Note";
                        break;

                    case 'job_card':
                        /*$sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Your Jobcard Number:".$transactions->order_no." "."for vehicle"." "..$vehicle." "."Created on"." ".$current_date." "."."."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;*/
                        $url=url('jc_acknowladge/');
                        $sms_content ="Please note the Jobcard"." ".$transactions->order_no." "."for Vehicle ".$vehicle." "."dated ".$current_date."."."\n\n"."Vehicle Note: ".$vehicle_note."\n\n"."Visit below link for the Status of Job. " . $url . '/' . $transaction->id. '/'.$organization_id;
                        $mge ="Job Card";
                        break;

                    case 'job_request':
                        $url=url('viewlist/');
                        $sms_content="Click  this link to approve estimation  for your vehicle : ".$vehicle." ". $url . '/' . $transaction->id. '/'.$organization_id."\r\n".$customer_name;
                        $mge ="Estimation link ";
                        break;

                    case 'job_invoice':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        
                        $mge = "Invoice";
                        break;

                    case 'job_invoice_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;

                        $mge ="Invoice";

                        break;
                }

                if($transactions->mobile != "") {

                    //$msg = Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);

                    Custom::add_addon('sms');
                }
            }
        }


        if($transaction->approval_status == 1)
        {
            $transaction_message = 'Transaction'.config('constants.flash.added_approved');
        }else{

            $transaction_message = 'Transaction'.config('constants.flash.added');
        }

        //$transaction_message = 'Transaction'.config('constants.flash.added');

        /* End Send SMS */

        /*if($method == "remote") {
            return redirect()->route('transaction.index', $transaction_type->name);
        } else {

            $transaction_message = 'Transaction'.config('constants.flash.added');

            if($request->input('sms')) {
                $transaction_message = 'Transaction SMS has been sent!';
            } else if($request->input('print')) {
                $transaction_message = "";
            } else if($request->input('approve')) {
                $transaction_message = 'Transaction approved!';
            } else if($request->input('send_po')) {
                $transaction_message = 'Transaction has been sent!';
            }

            return response()->json(array('status' => 1, 'message' => $transaction_message, 'data' => ['id' => $transactions->id, 'order_no' => $transactions->order_no, 'date' => $transactions->date, 'people' => $transactions->customer, 'people_contact' => $transactions->customer_contact, 'due_date' => ($transactions->due_date != null) ? $transactions->due_date : "", 'transaction_type' => $transactions->transaction_type, 'total' => $transactions->total, 'balance' => $transactions->balance, 'status' => $transactions->status, 'approval_status' => $transactions->approval_status, 'due' => $due]));
        }*/

        // Attachments

        if($uuid)
        {
            $IsExisted=WmsAttachment::where('uuid',$uuid)->exists();

            $is_exist_in_complaints = WmsTransactionComplaintService::where('uuid',$uuid)->exists();

            if($IsExisted)
            {
                DB::table('wms_attachments')->where('uuid',$uuid)->update(['transaction_id'=>$transaction->id]);
            }

            if($is_exist_in_complaints)
            {
                DB::table('wms_transaction_complaint_services')->where('uuid',$uuid)->update(['transaction_id'=>$transaction->id]);
            }

        }

        // Attachments

        

        if($request->input('sms')) {
            $transaction_message = 'Transaction SMS is successfully sent!';
        } else if($request->input('print')) {
            $transaction_message = "";
        } else if($request->input('approve')) {
            $transaction_message = 'Transaction approved! & SMS Sent';
        } else if($request->input('send_po') == 1) {
            $transaction_message = 'Transaction is successfully sent!';
        } else if($request->input('update_goods')) {
            $transaction_message = 'Items has been updated!';
        }

        if($request->input('update_customer_info') != null)
        {
            if($request->input('update_customer_info') == 1)
            {
                
                if($request->people_type == 0)
                {
                    $stored_date = DB::table('people')->where('user_type',0)->where('organization_id',$organization_id)->where('person_id',$request->people_id)->update(['first_name' => $request->name,'display_name' => $request->name , 'mobile_no' => $request->mobile ,'email_address' => $request->email]);
                    $people_id = People::where('organization_id',$organization_id)->where('user_type',0)->where('person_id',$request->people_id)->first()->id;

                    if($stored_date)
                    {
                        DB::table('people_addresses')->where('people_id',$people_id)->update(['address' => $request->address]);
                    }
                }

                if($request->people_type == 1)
                {
                    $stored_date = DB::table('people')->where('user_type',1)->where('organization_id',$organization_id)->where('business_id',$request->people_id)->update(['company' => $request->name,'display_name' => $request->name ,'mobile_no' => $request->mobile ,'email_address' => $request->email ]);

                        $people_id = People::where('organization_id',$organization_id)->where('user_type',1)->where('business_id',$request->people_id)->first()->id;

                    if($stored_date)
                    {
                        DB::table('people_addresses')->where('people_id',$people_id)->update(['address' =>$request->address ]);
                    }
                }
            }           
        }

        
                     Log::info("TransactionController->store_transaction :- return TRY");

        return response()->json(array('status' => 1, 'message' => $transaction_message, 'data' => ['id' => $transactions->id, 'order_no' => $transactions->order_no, 'date' => $transactions->date, 'people' => $transactions->customer, 'people_contact' => $transactions->customer_contact, 'due_date' => ($transactions->due_date != null) ? $transactions->due_date : "", 'transaction_type' => $transactions->transaction_type, 'total' => $transactions->total, 'balance' => $transactions->balance, 'status' => $transactions->status, 'approval_status' => $transactions->approval_status, 'due' => $due,'reference_no'=> $transactions->reference_no,'reference_type' => $transactions->reference_type,'shipping_date' => $transactions->shipping_date,'assigned_to' => $transactions->assigned_to,'registration_id' => $transactions->registration_no,'service_type' => $transactions->service_type,'jobcard_status'=> $transactions->jobcard_status,
            'name_of_job'=> $transactions->name_of_job, 
            'job_date' => $transactions->job_date, 
            'job_due_date' => $transactions->job_due_date, 
			'jc_order_no' => $transactions->jc_order_no, 
            'job_completed_date' => $transactions->job_completed_date,'advance_amount' => ($transactions->advance_amount != null) ? $transactions->advance_amount : "" ])); 

        
        });

                     Log::info("TransactionController->store_transaction :- return TRY OUT");
        return $result;

        }

        catch (\Exception $e) {
                     Log::info("TransactionController->store_transaction :- return Catch");
                     $error = sprintf('[%s],[%d] ERROR:[%s]', __METHOD__, __LINE__, json_encode($e->getMessage(), true));
       
                     Log::info("TransactionController->store_transaction :- return Catch ".$error);
         return response()->json(['status' => 2, 'error' =>  $e->getMessage()]);
        }
    }

    public function generte_pdf(Request $request){
        /*$data=$request->pdf;
        //dd($data);
        view()->share('data',$data);
         
       PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            // pass view file
            $pdf = PDF::loadHTML('<h1>hiii</h1>');
            // download pdf
            return $pdf->download('pdfview.pdf');*/
    }

        
    // public function wms_attachments(Request $request)
    // {
    //  $inputs=$request->all();
    //  //dd($inputs['transaction_id']);
    //  $uuid=$inputs['uuid'];
    //  $attachment_uid=$inputs['attachment_uid'];

    //  /*FILE PATH*/       
        
    //  /*  if (!file_exists(public_path($thumbnail_image_path))) {
    //              mkdir(public_path($thumbnail_image_path), 0777, true);

    //          }*/

    //      $i=0;
    //      foreach ($uuid as $value) {
    //      # code...
    //          $file_name=$inputs['image_name_'.$value];
    //          $file=$request->file('image_'.$value);
    //          $field_name='image_'.$value;
                
    //          /*
    //          *Modify the origional file name based on storing folder add suffix for "origional" image
    //          *
    //          thumbnail file name add suffix for "thumbnail" image

    //          *
    //          */
                
    //          $file_name_array=explode(".", $file->getClientOriginalName());
    //          $file_name_array[0]=$file_name_array[0]."_origional";
    //          $Modify_filename_origional=implode(".",$file_name_array);
                
                
    //          $file_name_array=explode(".", $file->getClientOriginalName());
    //          $file_name_array[0]=$file_name_array[0]."_thumbnail";
    //          $Modify_filename_thumnail=implode(".",$file_name_array);
    //      //          dd($thumbnail_image_path);
    //          /*Move to attachment folder*/
    //          $dt= new DateTime();
    //          /*
    //          *Generate Date
    //          */
    //          $wms_attachment_path1="";
    //          $wms_attachment_path2="";
    //          $name_origional = "T_"."1"."_".$dt->format('Y-m-d-H-i-s')."_".$Modify_filename_origional;    
    //          $name_thumbnail = "T_"."1_".$dt->format('Y-m-d-H-i-s')."_".$Modify_filename_thumnail;

    //          if($attachment_uid)
    //          {
    //              $path_array = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/temp");
        
    //              $wms_attachment_path = '';

    //                  foreach ($path_array as $p) {
    //                      $wms_attachment_path .= $p."/";
    //                      //$thumbnail_image_path = $p."/thumbnails/";
    //                      if (!file_exists(public_path($wms_attachment_path))) {
    //                              mkdir(public_path($wms_attachment_path), 0777, true);

    //                          }

    //                      }
                            
    //              $wms_attachment_path1=$wms_attachment_path;
    //              $wms_attachment_path2=$wms_attachment_path;
                            
    //          }else{

    //                  $path_array = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/jobcard_".$inputs['transaction_id']);
    //                  $thumbnail_image_path_array = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/jobcard_".$inputs['transaction_id'].'/thumbnails');

    //                  $wms_attachment_path = '';

    //                  foreach ($path_array as $p) {
    //                          $wms_attachment_path .= $p."/";
    //                  //$thumbnail_image_path = $p."/thumbnails/";
    //                          if (!file_exists(public_path($wms_attachment_path))) {
    //                                  mkdir(public_path($wms_attachment_path), 0777, true);

    //                              }

    //                      }
                        
    //              //  Create path on thumbnail image
                        
    //                  $thumbnail_image_path='';
    //                  foreach ($thumbnail_image_path_array as $p) {
    //                          $thumbnail_image_path .= $p."/";
    //                  //$thumbnail_image_path = $p."/thumbnails/";
    //                              if (!file_exists(public_path($thumbnail_image_path))) {
    //                              mkdir(public_path($thumbnail_image_path), 0777, true);

    //                              }

    //      //  $file->move(public_path($thumbnail_image_path), $name_thumbnail);
    
    //                  }
    //                  $wms_attachment_path1=$wms_attachment_path;
    //                  $wms_attachment_path2=$thumbnail_image_path;
    //          }

    //          $file->move(public_path($wms_attachment_path1), $name_origional);
                
    //          copy (public_path($wms_attachment_path1) . '/' . $name_origional,  public_path($wms_attachment_path2)  . '/' . $name_thumbnail); 
    //          Custom::generate_image_thumbnail(public_path($wms_attachment_path1) . '/' . $name_origional,  public_path($wms_attachment_path2)  . '/' . $name_thumbnail);
    //          $WmsAttachment = new WmsAttachment;
    //          $WmsAttachment->transaction_id=$inputs['transaction_id'];
    //          $WmsAttachment->image_name=$file_name;
    //          $WmsAttachment->image_category=$inputs['image_category'];
    //          $WmsAttachment->image_origional_name=$file->getClientOriginalName();
    //          $WmsAttachment->thumbnail_file=$name_thumbnail;
    //          $WmsAttachment->origional_file=$name_origional;
    //          $WmsAttachment->uuid=$attachment_uid;
    //          $WmsAttachment->organization_id=Session::get('organization_id');

    //          $WmsAttachment->save();
    //          /*Generate Upload Files Array*/
    //          $UploadFilesArray=[];
    //          $UploadFilesArray[$i]['image_thumbnail']=asset("public/".$wms_attachment_path1)  . '/' . $name_thumbnail;
    //          $UploadFilesArray[$i]['image_origional']=asset("public/".$wms_attachment_path2)  . '/' . $name_origional;
    //          $UploadFilesArray[$i]['image_name']=$file_name;
    //          $i++;

    //      }
    //      return response()->json(['status'=>1,'uploaded_files'=>$UploadFilesArray]);
    // }

    
    public function wms_attachment(Request $request)
    {
                     Log::info("TransactionController->wms_attachment :- Inside");
        //dd($request->all());
        $inputs=$request->all();
        $transaction_id = $request->input('transaction_id');
        //dd($transaction_id);
        $organization_id= session::get('organization_id');
        $attachment_uid = $inputs['attachment_uid'];
        $image_category = $inputs['image_category'];
        //dd($attachment_uid);
         $files= $request->file;
         foreach($files as $file)

            {
            $var=$file->getClientOriginalName();
               /* if (!file_exists(public_path('avatars'))) 
                {
                    mkdir(public_path('avatars'), 0777, true);
                }*/

            $dt= new DateTime();

            $file_name_array=explode(".", $file->getClientOriginalName());
            $file_name_array[0]= "T_"."1"."_".$dt->format('Y-m-d-H-i-s')."_".$file_name_array[0]."_origional";
            $Modify_filename_origional=implode(".",$file_name_array);

            $path= 'wms_attachments/org_'.Session::get('organization_id').'/temp';
            $img=Custom::image_resize($file,800,$Modify_filename_origional,$path);
           // dd($Modify_filename_origional);
            
            //$name_origional = "T_"."1"."_".$dt->format('Y-m-d-H-i-s')."_".$Modify_filename_origional;   

                
                        /*$path_array = explode('/', 'wms_attachments/org_'.Session::get('organization_id')."/temp");
            
                        $wms_attachment_path = '';

                            foreach ($path_array as $p) {
                                $wms_attachment_path .= $p."/";
                                //$thumbnail_image_path = $p."/thumbnails/";
                                if (!file_exists(public_path($wms_attachment_path))) {
                                        mkdir(public_path($wms_attachment_path), 0777, true);

                                    }

                        }*/
                            
                            
            
            //  dd($name_origional);    
            //$file->move(public_path($wms_attachment_path), $Modify_filename_origional);

            //dd($Modify_filename_origional);   
                    
            /*$file_name_array=explode(".", $file->getClientOriginalName());
            $file_name_array[0]=$file_name_array[0]."_thumbnail";
            $Modify_filename_thumnail=implode(".",$file_name_array);*/
            //dd($Modify_filename_thumnail);
             
            /*$name_thumbnail = "T_"."1_".$dt->format('Y-m-d-H-i-s')."_".$Modify_filename_thumnail;*/
            //$file->move(public_path('avatars'), $name_origional);
             if($img)
            {
            $WmsAttachment = new WmsAttachment;
            $WmsAttachment->transaction_id=$transaction_id;
            $WmsAttachment->image_name=$var;
            $WmsAttachment->image_category=$image_category;
            $WmsAttachment->image_origional_name=$file->getClientOriginalName();
            /*$WmsAttachment->thumbnail_file=$name_thumbnail;*/
            $WmsAttachment->origional_file=$Modify_filename_origional;
            $WmsAttachment->uuid=$attachment_uid;
            $WmsAttachment->organization_id=Session::get('organization_id');
            $WmsAttachment->save();

            }
        }


                                 Log::info("TransactionController->wms_attachment :- return");

            return response()->json(['message'=>"Image Uploaded."]);
    }
    

    public function get_job_card_data(Request $request)
    {
                     Log::info("TransactionController->get_job_card_data :- Inside");
        //dd($request->all());
        $type= $request->input('type');

        //dd($type);
        $from_date = '';
        $to_date = '';
        $organization_id = session::get('organization_id');


        /*$from_date = '';
        $to_date = '';
        if($request->input('from_date'))
        {
            $from_date=date_string($request->input('from_date'));
        }
        if($request->input('to_date'))
        {
            $to_date = date_string($request->input('to_string'));
        }*/
        
        if($request->input('from_date'))
        {
            $from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
        }
    

        if($request->input('to_date'))
        {
            $to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
        }
        /*else
        {
            $to=Carbon::parse($request->input('from_date'))->format('Y-m-d');
            $to_date = WmsTransaction::select('wms_transactions.job_date')
            ->leftjoin('transactions','wms_transactions.transaction_id','=','transactions.id')
            ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
            ->Where('wms_transactions.job_date','>=',$to)
            ->where('wms_transactions.organization_id',$organization_id)
            ->where('account_vouchers.name',$type)
            ->get();
    
        }*/
        //dd($to_date);
        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        $reference_type = ReferenceVoucher::where('name', 'purchases')->first()->id;

        //dd($from_date);
        

        $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');

        $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
        $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
        $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
        $transaction_types->where('account_vouchers.organization_id', $organization_id);

        if(Session::get('module_name') != null) {
            $transaction_types->where('modules.name', Session::get('module_name'));
        }
        
        $transaction_types->where('account_vouchers.name', $type);

        $transaction_type = $transaction_types->first();

        //dd($transaction_type->module);

        if($transaction_type == null) abort(404);

        if($type == "purchases") {
            $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
        } 
        else if($type == "sales" || $type == "sales_cash") {
            $cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        else if($type == "job_invoice" || $type == "job_invoice_cash") {
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        
        

        $payment_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;   

        if($transaction_type->module != "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
        }

        if($transaction_type->module == "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
        }

        if($transaction_type->module == "trade" || $transaction_type->module == "inventory")
        {
        $transaction_sales = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

        }


        if($transaction_type->module == "trade_wms")
        {
            $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

        }
    
 		$transaction = Transaction::select(DB::raw('COUNT(transactions.id)'),'transactions.id', 'transactions.originated_from_id',DB::raw('(CASE WHEN referenced_in.order_no is NULL THEN " " ELSE referenced_in.order_no END) AS jc_order_no'),'transactions.order_no','transactions.reference_id','transactions.approved_on','vehicle_register_details.id as vehicle_id',DB::raw('sum( (CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as jobcard_total'), 
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"),'transactions.date as original_date', 'transactions.due_date as original_due_date','transactions.total', 
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance"),  
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"),  'transactions.approval_status', 'transactions.transaction_type_id',  
            DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),
            DB::raw("IF(people.display_name IS NULL, business.display_name, CONCAT(people.first_name, ' ', COALESCE(people.last_name))) as customer_contact"),
            DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"),
             DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"),
            DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.name as jobcard_status','vehicle_jobcard_statuses.id as jobcard_status_id','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.advance_amount');

        

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
		$transaction->leftjoin('job_cards AS referenced_in', 'transactions.originated_from_id', '=', 'referenced_in.id');


        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');


        $transaction->where('transactions.organization_id', $organization_id);

        if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {
            
            $transaction->where(function ($query) use ($transaction_sales, $transaction_cash) {
                $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                      ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
        });

        } 
        else {
            $transaction->where('transactions.transaction_type_id', $transaction_type->id);
        }



        $transaction->whereNull('transactions.deleted_at');
        $transaction->groupby('transactions.id');
        $transaction->orderBy('transactions.updated_at','desc');
        if($transaction_type->module == "inventory" || $transaction_type->module == "trade")
        {
        
            if(!empty($request->input('from_date')) && !empty($request->input('to_date')))
            {
            $transaction->whereBetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
                $transaction->where('transactions.date','>=',$from_date);
            }
            if($request->input('to_date'))
            {
                $transaction->where('transactions.date','<=',$to_date);
            }
        
        }
        if($transaction_type->module == "trade_wms")
        {
            if(!empty($request->input('from_date')) && !empty($request->input('to_date')))
            {
            $transaction->whereBetween('wms_transactions.job_date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
                $transaction->where('wms_transactions.job_date','>=',$from_date);
            }
            if($request->input('to_date'))
            {
                $transaction->where('wms_transactions.job_date','<=',$to_date);
            }
        }
        $transactions = $transaction->get();
        //dd($transactions);

        foreach($transactions as $value)
        {
            $original_due_date = $value->original_due_date;
            $get_due_date = Custom::time_difference(Carbon::now()->format('Y-m-d H:i:s'),Carbon::parse($original_due_date)->format('Y-m-d'), 'd');
            $value['get_date'] = $get_due_date;
            //dd($value['get_date']);

        }

                     Log::info("TransactionController->get_job_card_data :- return");

        if(count($transactions) > 0)
        {
        return response()->json(['status' => 1, 'data' => $transactions ,'type' => $type]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => "No available datas."]);
        }
    
    }
    public function save_job_card_status(Request $request)
    {
                     Log::info("TransactionController->save_job_card_status :- Inside");
        //dd($request->all());
        $transaction_id = $request->input('id');
        $jobcard_status_id = $request->input('jobcard_status_id');
        DB::table('wms_transactions')->where('transaction_id',$transaction_id )->update(['jobcard_status_id' => $jobcard_status_id]);
                     Log::info("TransactionController->save_job_card_status :- return");
        return response()->json(['data' => 'updated']);
    }

    public function find_reference_id(Request $request)
    {
                     Log::info("TransactionController->find_reference_id :- Inside");
        //dd($request->all());
        $reference_id = $request->id;
        $data = Transaction::select('order_no')->where('id',$reference_id)->first();
        $order_no = $data->order_no;
        $reference = $request->reference;
        if($reference == 'job_invoice_cash'){
           $transaction_type = 'Job Invoice';
        }else if($reference == 'job_invoice'){
             $transaction_type = 'Job Invoice';
        }else if($reference == 'job_request'){
             $transaction_type = 'Job Estimation';
        }else if($reference == 'goods_receipt_note'){
            $transaction_type = 'Goods Receipt Note';
        }
        
        $reference_no = Transaction::select('transactions.id','transactions.reference_id','transactions.reference_no','transactions.transaction_type_id','transactions.order_no','account_vouchers.display_name','account_voucher_types.display_name as voucher_type')
                       ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
                       ->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')
                       ->where('transactions.reference_id',$reference_id)
                       ->where('transactions.reference_no',$order_no)
                       ->where('account_voucher_types.display_name',$transaction_type)
                       ->first();
                      
                     Log::info("TransactionController->find_reference_id :- return");
                       
      
         return response()->json(['data' => $reference_no]);
     }
    public function jobcard_additem(){

                     Log::info("TransactionController->jobcard_additem :- Inside");
        $organization_id = session::get('organization_id');

        $items = InventoryItem::where('inventory_items.organization_id', $organization_id)->pluck('inventory_items.name', 'inventory_items.id');

        $global_items = GlobalItemCategory::pluck('global_item_categories.name', 'global_item_categories.id');
        //dd($items);       
                     Log::info("TransactionController->jobcard_additem :- return");

        return view('inventory.jobcard_additem',compact('items','global_items'));

    }

public function delete_confirmation(Request $request)
    {
        
                     Log::info("TransactionController->delete_confirmation :- Inside this has multiple returns so not loging return");
        $organization_id = session::get('organization_id');
        $id = $request->input('id');
        //dd($id);
        $type = $request->input('type');
        //dd($type);
        $date30DaysBack= Carbon::now()->subDays(30)->format('Y-m-d'); 
        $today = Carbon::now()->format('Y-m-d');
        
        if($type == 'job_card' || $type == 'purchase_order' || $type == 'estimation')
        {

            $data = Transaction::select('transactions.order_no','account_vouchers.display_name')->leftJoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')->where('reference_id',$id)->where('transactions.organization_id',$organization_id)->first();


            if($data != null){
                return response()->json(['data' => $data,'type' =>$type]);
            }else{
                return response()->json(['data' => 'null','type' =>$type]);
            }       
        }else if($type == 'job_request' || $type == 'sale_order' || $type == 'purchases'){
            
            $data = Transaction::select('reference_id','reference_no','approval_status')->where('id',$id)->/*where('organization_id',$organization_id)->whereNotBetween('transactions.date',[$date30DaysBack,$today])->*/first();
        
            if($data != null){
                $reference_no = Transaction::select('id','order_no','transaction_type_id')->where('reference_id',$id)->where('organization_id',$organization_id)->first();
                $reference = Transaction::select('id','order_no','transaction_type_id')->where('id',$data->reference_id)->where('organization_id',$organization_id)->first();
                if($reference_no != null){
                    $transaction_type = Transaction::select('account_vouchers.display_name')->leftJoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')->where('transactions.id',$reference_no->id)->first();

                return response()->json(['data' => $data,'transaction_type'=>$transaction_type,'type' =>$type,'status'=>$data->approval_status,'reference_no'=>$reference_no->order_no]);
                }else if($reference != null){
                    $transaction_type = Transaction::select('transactions.order_no','account_vouchers.display_name')->leftJoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')->where('transactions.id',$reference->id)->first();
                //dd($transaction_type);
                    return response()->json(['data' => $data,'transaction_type'=>$transaction_type ,'type' =>$type,'status'=>$data->approval_status,'reference_no'=>$transaction_type->order_no]);
                }else{
                    return response()->json(['data' => $data,'transaction_type'=>null ,'type' =>$type,'status'=>$data->approval_status,'reference_no'=>null]);
                }
            
            }else{
                return response()->json(['data' => $data,'type' =>$type,'status'=> 'null']);
            }

                
        }else if($type == 'job_invoice'   || $type == 'sales'  || $type == 'goods_receipt_note'){
            $data = Transaction::select('reference_no','approval_status')
            ->where('id',$id)
            ->where('organization_id',$organization_id)
        /*  ->whereNotBetween('transactions.date',[$date30DaysBack,$today])*/
            ->first(); 
            if($data == null){
                return response()->json(['data' => $data,'type' =>$type,'action'=>0]);
            }else{
                return response()->json(['data' => $data,'type' =>$type,'action'=>1]);
            }
        }else if($type == 'debit_note' || $type == 'credit_note'||  $type == 'delivery_note'){
            $data = Transaction::select('reference_no','approval_status')
            ->where('id',$id)
            ->where('organization_id',$organization_id)
            ->first(); 
            //dd($data);
            if($data != null){
                return response()->json(['data' => $data,'type' =>$type,'action'=>$data->approval_status]);
            }
        }

        
     }
 public function transaction_un_approve(Request $request)
 {
                     Log::info("TransactionController->transaction_un_approve :- Inside ");
    //dd($request->all());
    $data = DB::table('transactions')->where('transactions.id',$request->id)->where('approval_status',1)->update(['approval_status'=>0]);
    //dd($data);
                     Log::info("TransactionController->transaction_un_approve :- return ");
    
        return response()->json(['data' => 1,'message' => 'Transaction Unapproved','status' => $request]);
 }
 public function show_print_popup(Request $request)
 {
    return view('modals.print_popup_modal');
 }


  public function job_card_status_update(Request $request)
 {
                     Log::info("TransactionController->job_card_status_update :- Inside ");
    //dd($request->all());
      $candidate_status=WmsTransaction::where('transaction_id',$request->input('id'))
        ->update(['jobcard_status_id'=>$request->input('status')]);
        //dd($candidate_status);
                     Log::info("TransactionController->job_card_status_update :- return ");
        return response()->json(['status'=>$request->input('status')]);
 }




  public function get_estimations_view($id,$name)
 {
                     Log::info("TransactionController->get_estimations_view :- Inside ");
    //dd($identity_name);
    $organization_id = Session::get('organization_id');
    $name = $name;
    $id = $id;
    
    
    $job_request_id = AccountVoucher::select('id')->where('name','job_request')->where('organization_id',$organization_id)->first(); 

    $job_invoice_id = AccountVoucher::select('id')->where('name','job_invoice')
    ->where('organization_id',$organization_id)->first(); 

    $job_invoice_cash_id = AccountVoucher::select('id')->where('name','job_invoice_cash')
    ->where('organization_id',$organization_id)->first();

    $delivery_id = AccountVoucher::select('id')->where('name','delivery_note')
    ->where('organization_id',$organization_id)->first();

    $grn_id = AccountVoucher::select('id')->where('name','goods_receipt_note')
    ->where('organization_id',$organization_id)->first();
    //dd($job_invoice_id->id);
    
    
    //dd($voucher_name->id);
    $view_estimations = Transaction::select('transactions.id','transactions.order_no','transactions.approval_status');
    $view_estimations->where('reference_id',$id);
    if($name == "job_request")
    {
        $view_estimations->where('transaction_type_id',$job_request_id->id);
    }
    if($name == "job_invoice")
    {
        $view_estimations->where(function($query) use($job_invoice_id,$job_invoice_cash_id)
        {
            $query->where('transaction_type_id',$job_invoice_id->id)
        ->orWhere('transaction_type_id',$job_invoice_cash_id->id);
        });
        
        
    }
    if($name == "delivery_note")
    {
        $view_estimations->where('transaction_type_id',$delivery_id->id);
    }
    if($name == "goods_receipt_note")
    {
        $view_estimations->where('transaction_type_id',$grn_id->id);
    }

    $view_estimation = $view_estimations->get();

    //dd($view_estimation);
    $status = '';
                         Log::info("TransactionController->get_estimations_view :- return ");

    if(count($view_estimation) == 0 )
    {
        
        $status = 0;

        return view('inventory.view_estimations',compact('view_estimation','id','status' ,'name'));
        

    }
    else
    {
        $status = 1;
        //dd($status);

        return view('inventory.view_estimations',compact('view_estimation','id','status'));
        
    }
 }
 //this function using for payment for purchase,,invoice in ACtion button

  public function get_estimations_views($id,$name,$identity_name)
 {
                     Log::info("TransactionController->get_estimations_views :- Inside ");
    //dd($identity_name);
    $organization_id = Session::get('organization_id');
    $name = $name;
    $id = $id;
    $identity_name = $identity_name;
    
    /*$job_request_id = AccountVoucher::select('id')->where('name','job_request')->where('organization_id',$organization_id)->first(); 

    $job_invoice_id = AccountVoucher::select('id')->where('name','job_invoice')
    ->where('organization_id',$organization_id)->first(); 

    $job_invoice_cash_id = AccountVoucher::select('id')->where('name','job_invoice_cash')
    ->where('organization_id',$organization_id)->first();

    $delivery_id = AccountVoucher::select('id')->where('name','delivery_note')
    ->where('organization_id',$organization_id)->first();

    $grn_id = AccountVoucher::select('id')->where('name','goods_receipt_note')
    ->where('organization_id',$organization_id)->first();*/
    //dd($job_invoice_id->id);
    
    $status = '';
 
  $transaction_type = AccountVoucher::where('name', $name)->where('organization_id', $organization_id)->first();

    //dd($transaction_type); 
  $payment = PaymentMode::where('status', 1)->pluck('display_name','id');
        $payment->prepend('Cash','1');

    $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

    if($name == "payment") {
        $transaction_id = AccountVoucher::where('name', 'purchases')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
        $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
        $user = "Vendor";
    } else if($name == "receipt") {
        $transaction_id = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
        $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        $user = "Customer";
    }
    else if($name == "wms_receipt") {
        $transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
        //dd($cash_voucher);

        $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        $user = "Customer";
    }

    $view_estimations = Transaction::select('transactions.id', 'transactions.order_no',
                DB::raw('COALESCE(DATE_FORMAT(transactions.created_at, "%d-%m-%Y"), "") as created_on'), 
                DB::raw('COALESCE(DATE_FORMAT(transactions.date, "%d-%m-%Y"), "") as date'), 
                DB::raw('COALESCE(DATEDIFF(NOW(), transactions.due_date), "") as overdue'), 
                DB::raw('COALESCE(DATE_FORMAT(transactions.due_date, "%d-%m-%Y"), "") as due_date'), 
                DB::raw('IF(transactions.transaction_type_id = '.$transaction_type->id.', "1", 0) AS cash_transaction'),
            'transactions.total', 
            DB::raw('IF(people.id IS NULL, business.display_name, people.display_name ) AS customer'),
            'transactions.due_date as original_due_date',
                DB::raw("IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL, 

                    transactions.total, 

                    transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) AS balance"), 

                DB::raw("CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1  
            WHEN transactions.due_date < CURDATE()  THEN 3 
            WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2
            ELSE 0 
            END AS status"), 


                'transactions.approval_status', 'transactions.user_type', 'transactions.people_id');

            $view_estimations->leftJoin('people', function($query) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->where('transactions.user_type','=','0');
            });

            $view_estimations->leftJoin('people AS business', function($query) {
                $query->on('transactions.people_id','=','business.business_id');
                $query->where('transactions.user_type','=','1');
            });

            $view_estimations->where('transactions.organization_id', $organization_id);
            if($identity_name == "job_invoice_payment")
            {
            $view_estimations->where('reference_id',$id);
            }
            if($identity_name == "payment")
            {
            $view_estimations->where('transactions.id',$id);
            }
            $view_estimations->where('transaction_type_id',$transaction_id);

            /*if($name == "job_request")
            {
                $view_estimations->where('transaction_type_id',$job_request_id->id);
            }
            if($name == "job_invoice")
            {
                $view_estimations->where(function($query) use($job_invoice_id,$job_invoice_cash_id)
                {
                    $query->where('transaction_type_id',$job_invoice_id->id)
                ->orWhere('transaction_type_id',$job_invoice_cash_id->id);
                });
                
                
            }
            if($name == "delivery_note")
            {
                $view_estimations->where('transaction_type_id',$delivery_id->id);
            }
            if($name == "goods_receipt_note")
            {
                $view_estimations->where('transaction_type_id',$grn_id->id);
            }
*/
            $view_estimations->groupby('transactions.id');

            $view_estimations->havingRaw('status != 1');

            $view_estimations->havingRaw('balance > 0');

            $view_estimation = $view_estimations->get();
            //dd($view_estimation);
            $ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
        ->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
        ->whereIn('account_groups.name', ['cash','bank_account'])
        ->where('account_ledgers.organization_id', $organization_id)
        ->where('account_ledgers.approval_status', '1')
        ->where('account_ledgers.status', '1')
        ->orderby('account_ledgers.id','asc')
        ->pluck('name', 'id');
                     Log::info("TransactionController->get_estimations_views :- return ");

    if(count($view_estimation) == 0 )
    {
        
        $status = 0;

        return view('inventory.pay_bill',compact('view_estimation','id','status','name','payment','ledgers'));
        

    }
    else
    {
        $status = 1;
        //dd($status);

        return view('inventory.pay_bill',compact('view_estimation','id','status','name','payment','ledgers'));
        
    }
 }





 public function get_prints_view($id,$name)
 {
                     Log::info("TransactionController->get_prints_view :- Inside ");
    //dd($id);
    $id = $id;
    $name = $name;
    $organization_id = Session::get('organization_id');
    $show_print_templates = MultiTemplate::select('multi_templates.id','multi_templates.voucher_id','account_voucher_types.display_name','multi_templates.print_temp_id','print_templates.display_name','print_templates.data') ->leftjoin('account_voucher_types','account_voucher_types.id','=','multi_templates.voucher_id')
     ->leftjoin('print_templates','print_templates.id','=','multi_templates.print_temp_id')
     ->where('multi_templates.organization_id',$organization_id)
     ->where('voucher_id',22)
     ->groupby('multi_templates.print_temp_id')
     ->get();
             //dd($show_print_templates);

      $estimation_print_templates = MultiTemplate::select('multi_templates.id','multi_templates.voucher_id','account_voucher_types.display_name','multi_templates.print_temp_id','print_templates.display_name','print_templates.data') ->leftjoin('account_voucher_types','account_voucher_types.id','=','multi_templates.voucher_id')
     ->leftjoin('print_templates','print_templates.id','=','multi_templates.print_temp_id')
     ->where('multi_templates.organization_id',$organization_id)
     ->where('voucher_id',21)
     ->groupby('multi_templates.print_temp_id')
     ->get();
                     Log::info("TransactionController->get_prints_view :- return ");
     
    return view('inventory.print_views',compact('show_print_templates','estimation_print_templates','id','name'));
 }

public function show_sms_popup(Request $request)
 {

                     Log::info("TransactionController->show_sms_popup :- Inside ");
        $sms_summary=SmsTemplate::where('status',1)
                    ->pluck('sms_type','sms_content')
                    ->prepend('Select the SMS','');
        $sms_date =Carbon::now();
        $current_date =  $sms_date->format('d-m-Y');


        /*if($organization_id){
            $org_id = $organization_id;
        }else{
            $org_id = session::get('organization_id');
        }*/

        $organization_id = session::get('organization_id');
        $id = $request->id;

        //dd($organization_id);

        $sms_content_requerment=Transaction::select('vehicle_register_details.registration_no as vehicle_no','transactions.name','transactions.mobile')->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')->where('transactions.id',$id)->get();
        //dd($sms_content_requerment);
        foreach ($sms_content_requerment as $key => $value) {
            $vehicle=$value->vehicle_no;
            $mobile_no=$value->mobile;
            $customer_name=$value->name;
        }

        $transaction_last = Transaction::select('transactions.transaction_type_id', 'transactions.mobile', 'transactions.id', 'transactions.date', 'transactions.total', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no',  
             DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code')
             ,DB::raw('transactions.total + wms_transactions.advance_amount as total_amount'));

            $transaction_last->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $transaction_last->leftJoin('people', function($join) use($organization_id)
                {
                    $join->on('people.person_id','=', 'transactions.people_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('transactions.user_type', '0');
                });
            $transaction_last->leftJoin('people AS business', function($join) use($organization_id)
                {
                    $join->on('business.business_id','=', 'transactions.people_id')
                    ->where('business.organization_id', $organization_id)
                    ->where('transactions.user_type', '1');
                });

            $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
            $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');        
           
            $transaction_last->where('transactions.id', $request->id);
            $transactions = $transaction_last->first(); 

            $module_name = Session::get('module_name');

            if($module_name == "trade_wms"){
                $vehicle_note = $transactions->vehicle_note;

                if($vehicle_note == null){
                    $vehicle_note = "No Specific Notes";
                }else{
                    $vehicle_note = $transactions->vehicle_note;
                }
            }           


            $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();

            $business_name = Session::get('business');

        //dd($transaction_type->name);
        
            if($transaction_type->name == "purchases"|| $transaction_type->name == "receipt" || $transaction_type->name == "purchase_order" || $transaction_type->name == "sale_order" || $transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "delivery_note" || $transaction_type->name == "estimation" || $transaction_type->name == "job_request" || $transaction_type->name == "job_card" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" || $transaction_type->name == "credit_note" || $transaction_type->name == "debit_note" || $transaction_type->name == "goods_receipt_note") 
            {
                switch ($transaction_type->name) 
                {
                    case 'receipt':

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Payment of Rs. ".$transactions->total." for Invoice:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Receipt";
                        break;

                    case 'purchase_order':
                        $sms_content = "You have a new purchase order from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase Order";
                        break;

                    case 'purchases':
                        $sms_content = "You have a new purchase from ".$business_name." for Rs. ".$transactions->total. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Purchase";
                        break;  

                    case 'sale_order':
                        
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Sale Order of Rs. ".$transactions->total." for Sale Order Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Sale Order";
                        break;

                    case 'credit_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Sale Return of Rs. ".$transactions->total." for credit note:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Sale Return";
                    break;
                    case 'debit_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Purchase Return of Rs. ".$transactions->total." for debit note:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Purchase Return";
                    break;
                    case 'goods_receipt_note':
                    $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Receipt Note of Rs. ".$transactions->total." for purchase:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                    $mge ="Good Receipt Note";
                    break;

                        case 'estimation':                      

                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Estimation of Rs. ".$transactions->total." for Estimation Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Estimation";
                        break;

                    case 'sales':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Credit Sale";
                        break;

                    case 'sales_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date.""." \n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;
                        $mge ="Cash Sale";
                        break;

                    case 'delivery_note':
                        $sms_content = "Dear ".$transactions->customer.",". "\n\n" ."Your order for ".$transactions->reference_no. " of Rs. ".$transactions->total." has been delivered. Ref: ".$transactions->order_no. "\n\n" ."Thanks for choosing ".$business_name. "\n\n" ."Your Propel ID: ".$transactions->code;
                        $mge ="Delivery Note";
                        break;

                    case 'job_card':
                        /*$sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Your Jobcard Number:".$transactions->order_no." "."for vehicle"." "..$vehicle." "."Created on"." ".$current_date." "."."."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;*/
                        $url=url('jc_acknowladge/');
                        $sms_content ="Please note the Jobcard"." ".$transactions->order_no." "."for Vehicle ".$vehicle." "."dated ".$current_date."."."\n\n"."Vehicle Note: ".$vehicle_note."\n\n"."Visit below link for the Status of Job. " . $url . '/' . $transactions->id. '/'.$organization_id;
                        $mge ="Job Card";
                        break;

                    case 'job_request':
                        $url=url('viewlist/');
                        $sms_content="Click  this link to approve estimation  for your vehicle : ".$vehicle." ". $url . '/' . $transactions->id. '/'.$organization_id."\r\n".$customer_name;
                        $mge ="Estimation link ";
                        break;

                    case 'job_invoice':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n" ."Credit Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n" ."Vehicle Note: ".$vehicle_note."\n"."Thanks for choosing"." ".$business_name."."."\n"."Your Propel ID: ".$transactions->code;
                        
                        $mge = "Invoice";
                        break;

                    case 'job_invoice_cash':
                        $sms_content =  "Dear ".$transactions->customer.",". "\n\n" ."Cash Invoice of Rs. ".$transactions->total." for Invoice Number:".$transactions->order_no." "."Created on"." ".$current_date." ". "\n\n" ."Vehicle Note: ".$vehicle_note."\n\n"."Thanks for choosing"." ".$business_name."."."\n\n"."Your Propel ID: ".$transactions->code;

                        $mge ="Invoice";
                        break;
                }

                if($transactions->mobile != "") {

                    //$msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);
                    Custom::add_addon('sms');
                }

            }
                         Log::info("TransactionController->show_sms_popup :- return ");

            return view('inventory.job_invoice_sms',compact('sms_summary','sms_content','mobile_no','mge'));
 }
 public function send_sms(Request $request)
 {      
                     Log::info("TransactionController->send_sms :- Inside ");
             $message=$request->message;
             $mobile_no=$request->mobile_no;
             $mge=$request->mge;

                if($mobile_no != "")
                {
              $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no,$message);
                }
            
                Custom::add_addon('sms');

                     Log::info("TransactionController->send_sms :- return ");
                return response()->json(['status' => 1, 'message' =>$mge."  "."sent to ".$mobile_no."for approval"]); 
 }
 
 public function delete_attachment(Request $request)
 {

                     Log::info("TransactionController->delete_attachment :- Inside ");
    $isImageDel=WmsAttachment::where('id',$request->id)->first();

    if($isImageDel)
    {
        $imageName=$isImageDel->origional_file;
        $isImageDel->delete();
        $path = public_path(). 'wms_attachments/org_'.Session::get('organization_id').'/temp/'.$imageName;
        $status=0;
        if(File::exists($path))
        {

            unlink($path);
            $status=1;
        }
    }
                     Log::info("TransactionController->delete_attachment :- return ");
    return response()->json(['status' => $status, 'message' =>'image Deleted']); 

 }
 
  public function transaction_link_popup($id,$module_name=false)
    {
                     Log::info("TransactionController->transaction_link_popup :- Inside ");

        $now = Carbon::now();
        $current_date =  $now->format('Y-m-d H:i:s');
        $add_date = date("Y-m-d H:i:s", strtotime("+1 hours"));

        $organization_id = Session::get('organization_id');

       if($module_name){
        $module_name=$module_name;
      
       }else{
        $module_name = Session::get('module_name');

       }

        $transactions = Transaction::where('id',$id)->where('organization_id',$organization_id)->first();

        $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        ->where('account_vouchers.organization_id', $organization_id)
        ->where('modules.name', $module_name)
        ->where('account_vouchers.id', $transactions->transaction_type_id)
        ->first();

        $wms_transaction = WmsTransaction::select('wms_transactions.*','wms_transactions.service_type','wms_transactions.jobcard_status_id','wms_transactions.purchase_date','vehicle_register_details.*','wms_transactions.next_visit_mileage','wms_transactions.vehicle_next_visit','wms_transactions.vehicle_next_visit_reason','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.registration_id','wms_transactions.vehicle_note','wms_transactions.vehicle_complaints','vehicle_variants.vehicle_configuration','wms_transactions.driver','wms_transactions.driver_contact','wms_transactions.shift_id','wms_transactions.pump_id')
        ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id')
        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')
        ->where('wms_transactions.organization_id', $organization_id)       
        ->where('wms_transactions.transaction_id', $transactions->id)
        ->first();

        $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
        $employees->prepend('Select Sales Person', '');

        $person_id = Auth::user()->person_id;
        $employee = HrmEmployee::select('hrm_employees.id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->where('hrm_employees.person_id', $person_id)
        ->first();
        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');
        $getGen_no=Custom::getLastGenNumber( $transaction_type->id, $organization_id );
        //$gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
        
        $vou_restart_value = AccountVoucher::select('restart')->where('id',$transaction_type->id)->first();
          //dd($vou_restart_value);
         if($vou_restart_value->restart == 0)
          {
              $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
              Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
          }
          else
          {
               $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
                Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
          }

        $voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

        //new code**
        if($transactions->approval_status==0){
            $approval_status="Draft";
        }else{
            $approval_status="Approved";
        }

        $job_parts = TransactionItem::select(DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS item_name'),'transaction_items.description',
            'transaction_items.quantity','transaction_items.rate','transaction_items.discount_value as discount_percent',
        DB::raw('transaction_items.discount_value/100 AS discount'),'tax_groups.name as tax',
        
        DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount')
    )
           ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')
           ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')
           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
           ->where('transaction_items.transaction_id', $transactions->id)
           ->groupby('transaction_items.id')
           ->get();
          
           
          $add = explode("<br>", $transactions->address); 
          $address=implode("\n", $add);

          $bill_add = explode("<br>", $transactions->billing_address); 
          $billing_address=implode("\n", $bill_add);

          $by=Transaction::select('hrm_employees.id')
          ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
          ->where('transactions.id', $transactions->id)
          ->first();
    
        $voucher_terms = Term::where('organization_id', $organization_id)->pluck('display_name','id');
        //end new code**    

      $print_data ='';

         $jc_print_templates = PrintTemplate::select('print_templates.data') 
            ->where('print_templates.organization_id',$organization_id)
            ->where('print_templates.name','QuickTransactionPrint')                
            ->first(); 
            if($jc_print_templates)
            {
                $print_data =  $jc_print_templates->data;
            }
            //dd($print_data);
                     Log::info("TransactionController->transaction_link_popup :- return ");

        return view('inventory.transaction_link_popup', compact('id','transactions','transaction_type','job_parts','wms_transaction','employees','employee','payment_terms','voucher_no','approval_status','total','address','billing_address','by','taxes','voucher_terms','module_name','print_data'));
            
    }
        public function vechile_history($vechile_no=false)
    {
        Log::info("TransactionController->vechile_history :- Inside ");
        
        $organization_id = Session::get('organization_id');
        $report = WmsTransaction::select('transactions.order_no', 'wms_transactions.job_date', 'inventory_items.name','wms_transactions.vehicle_complaints','transaction_items.job_item_status',
            DB::raw('(CASE WHEN vehicle_register_details.user_type = 0 THEN person_communication_addresses.mobile_no ELSE business_communication_addresses.mobile_no END) AS mobile_no'));
        $report->leftjoin('transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
        $report->leftjoin('hrm_employees', 'transactions.employee_id', '=', 'hrm_employees.id');
        $report->leftjoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
        $report->leftjoin('inventory_items', 'transaction_items.item_id', '=', 'inventory_items.id');
        $report->leftjoin('account_vouchers', 'transactions.transaction_type_id', '=', 'account_vouchers.id');
        $report->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        $report->leftJoin('person_communication_addresses', function ($join)  {
            $join->on('person_communication_addresses.person_id', '=', 'vehicle_register_details.owner_id')
                ->where('vehicle_register_details.user_type', '0');
        });
        $report->leftJoin('business_communication_addresses', function ($join)  {
            $join->on('business_communication_addresses.business_id', '=', 'vehicle_register_details.owner_id')
                ->where('vehicle_register_details.user_type', '1');
        });
        $report->leftJoin('people', function ($join) use ($organization_id) {
            $join->on('people.person_id', '=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
        });
        $report->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });
        $report->where('wms_transactions.registration_id', '=', $vechile_no);
        $report->where('account_vouchers.name', '=', 'job_card');
        $report->where('transactions.organization_id', '=', $organization_id);
        $report->whereNull('transactions.deleted_at');
        $report->whereNotNull('transactions.order_no')
        ->orderBy('transactions.id', 'desc');
        $reports = $report->get();
       

        Log::info("TransactionController->vechile_history :- return ");

        return view('inventory.vechile_history', compact('reports' ));
    }



    } 
 