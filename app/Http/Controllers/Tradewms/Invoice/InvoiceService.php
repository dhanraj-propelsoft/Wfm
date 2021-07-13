<?php

namespace App\Http\Controllers\Tradewms\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\BusinessCommunicationAddress;
use App\WmsTransactionComplaintService;
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
use App\WmsPriceList;
use App\AccountPersonType;
use App\InventoryItemBatch;

use Session;
use Mail;
use Auth;
use DB;
use PDF;
use DateTime;
use Illuminate\Support\Str;
use File;
use Validator;
use Storage;
use Input;
use App\VehiclePermit;
use App\WmsVehicleOrganization;
use App\PersonAddressType;
use App\PersonCommunicationAddress;
use App\Http\Controllers\Accounts\AccountVoucher\AccountVoucherRepository;
use App\Http\Controllers\Tradewms\Jobcard\JobCardRepository;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardItem;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardDetail;
use App\Http\Controllers\Inventory\Item\InventoryItemService;


class InvoiceService
{

    private $type;
    protected $type_id;

    protected $transaction_type;


    public function __construct(AccountVoucherRepository $accountRepo, JobCardRepository $jobCardRepo, InventoryItemService $inventoryItemServ)
    {
        $this->accountRepo = $accountRepo;
        $this->jobCardRepo = $jobCardRepo;
        $this->inventoryItemServ = $inventoryItemServ;

    }

    protected function setTransactionType($type)
    {
//        if($type == "job_invoice")
//        {
//             $this->type_id = Session::get('ji_type_id');
//        }
//        else if($type == "job_invoice_cash")
//        {
//             $this->type_id = Session::get('ji_cash_type_id');
//        }
//        $this->type = $type;
//
//         //dd("type".$this->type_id);
//         Log::info('InvoiceService->getTransactionType:- inv_type_id ' . $this->type_id);
//         Log::info('InvoiceService->getTransactionType:- ji_type_id ' . Session::get('organization_id'));
//         if (! $this->type_id) {
//             // get transaction type
//             $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $type);
//
//             if ($this->transaction_type) {
//                 if($type == "job_invoice")
//                 {
//                     Log::info('InvoiceService->getTransactionType:- put ji_type_id ' . json_encode($this->transaction_type));
//                     Session::put('ji_type_id', $this->transaction_type->id);
//                     $this->type_id = Session::get('ji_type_id');
//
//                 }
//                 else if($type == "job_invoice_cash")
//                 {
//                     Log::info('InvoiceService->getTransactionType:- put ji_cash_type_id ' . json_encode($this->transaction_type));
//                     Session::put('ji_cash_type_id', $this->transaction_type->id);
//                     $this->type_id = Session::get('ji_cash_type_id');
//
//                 }
//
//             }
//             Log::info('InvoiceService->getTransactionType:- put inv_type_id ' . $this->type_id);
//         } else {
//             Log::info('InvoiceService->getTransactionType:- put ji_type_id ' . $this->type_id);
//             $this->transaction_type = $this->accountRepo->findById($this->type_id);
//             //dd($this->type_id);
//         }
        if (! $this->type_id) {
            $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $type);
            Log::info('InvoiceService->getTransactionType:- put ji_type_id ' . json_encode($this->transaction_type));
            $this->type_id = $this->transaction_type->id;
            $this->type = $type;
        }
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

    public function findAll($id,Request $request,$type)
    {

        Log::info("InvoiceService->Index :- Inside ");

        $this->setTransactionType($type);

        Log::info("InvoiceService->Index :- type - " . $this->type);

        $request->session()->put('transaction-type', $this->type);
        $session_get_type = $request->session()->get('transaction-type');

        // dd($ses_get);

        $organization_id = Session::get('organization_id');
        $module_name = Session::get('module_name');

        if ($session_get_type) {

            $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');
            $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
            $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
            $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
            $transaction_types->where('account_vouchers.organization_id', $organization_id);
            /*
             * if(Session::get('module_name') != null) {
             * $transaction_types->where('modules.name', Session::get('module_name'));
             * }
             */
            $transaction_types->where('account_vouchers.name', $this->type);
            $transaction_type = $transaction_types->first();
        }

        // dd($transaction_type);

        if ($transaction_type == null)
            abort(404);

        // AccountVoucher::where('name', $type)->where('organization_id', $organization_id)->first();

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        if ($transaction_type->module == "trade_wms") {
            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
        }

        $today = Carbon::today()->format('d-m-Y');
        $firstDay = new Carbon('first day of last month');
        $firstDay_only = $firstDay->format('d-m-Y');
        // dd($firstDay_only);
        $from_date = $firstDay->format('Y-m-d');
        $to_date = Carbon::today()->format('Y-m-d');

        $from_date_trade_wms = Carbon::today()->subDays(30)->format('Y-m-d');
        $to_date_trade_wms = Carbon::today()->format('Y-m-d');

        $firstDay_only_trade_wms = Carbon::today()->subDays(30)->format('d-m-Y');

        $transaction = Transaction::select(DB::raw('COUNT(transactions.id)'), 'transactions.id', 'transactions.order_no', 'transactions.originated_from_id','referenced_in.order_no as jc_order_no', 'transactions.reference_id', 'transactions.approved_on', 'vehicle_register_details.id as vehicle_id', DB::raw('sum( (CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as jobcard_total'), DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"), 'transactions.date as original_date', 'transactions.due_date as original_due_date', 'transactions.total', DB::raw(" 0 AS balance"), DB::raw(" 1 AS status"), 'transactions.approval_status', 'transactions.transaction_type_id', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw("IF(people.display_name IS NULL, business.display_name, CONCAT(people.first_name, ' ', COALESCE(people.last_name))) as customer_contact"), DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"), DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"), DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'), 'vehicle_register_details.registration_no', 'hrm_employees.first_name AS assigned_to', 'service_types.name as service_type', 'vehicle_jobcard_statuses.id as job_card_status_id', 'vehicle_jobcard_statuses.name as jobcard_status', 'wms_transactions.name as name_of_job', 'wms_transactions.job_date', 'wms_transactions.job_due_date', 'wms_transactions.job_completed_date', 'wms_transactions.advance_amount');

        $transaction->leftJoin('people', function ($join) use ($organization_id) {
            $join->on('people.person_id', '=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
        });
        $transaction->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });

        $transaction->leftjoin('transactions AS reference_transactions', 'transactions.reference_id', '=', 'reference_transactions.id');

        $transaction->leftjoin('job_cards AS referenced_in', 'transactions.originated_from_id', '=', 'referenced_in.id');

        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        //$transaction->where('transactions.organization_id', $organization_id);

        //Dont need this line as we are passing transaction id
        //$transaction->where('transactions.transaction_type_id', $transaction_type->id);

        //$transaction->whereNull('transactions.deleted_at');
        //$transaction->where('transactions.notification_status', '!=', 2);
        $transaction->where('transactions.id', '=', $id);

        $transaction->groupby('transactions.id');
        //$transaction->orderBy('transactions.updated_at', 'desc');
        $transactions = $transaction->get();

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMode::where('status', '1')->pluck('display_name', 'id');
        $payment->prepend('Select Payment Method', '');

        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term', '');

        $group_name = CustomerGroping::where('organization_id', $organization_id)->pluck('display_name', 'id');
        $group_name->prepend('Select Group Name', '');

        $ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name', 'account_groups.name AS group');
        $ledger->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id');
        $ledger->whereIn('account_groups.name', [
            'cash',
            'bank_account'
        ]);
        $ledger->where('account_ledgers.organization_id', $organization_id);
        $ledger->where('account_ledgers.approval_status', '1');
        $ledger->where('account_ledgers.status', '1');
        $ledger->orderby('account_ledgers.id', 'asc');

        $ledgers = $ledger->pluck('name', 'id');

        Log::info("InvoiceService->Index :- return  type - " . $this->type);

        // needed for the view line below
        $type = $this->type;

        return view('inventory.transaction', compact('transactions', 'transaction_type', 'type', 'state', 'title', 'payment', 'terms', 'group_name', 'firstDay_only', 'today', 'city', 'ledgers', 'firstDay_only_trade_wms', 'from_date_trade_wms', 'to_date'));
    }

    public function findAll_API($request){

        Log::info("InvoiceService->findAll_API :- Inside ");
        Log::info("InvoiceService->findAll_API :- Inside Data " .json_encode($request));

        $request = (object) $request;

        $organization_id = $request->org_id;
        $offset = $request->page;
        $limit = $request->per_page;

        $journal_voucher = $this->accountRepo->findByOrgIdAndType($organization_id,'journal')->id;
        $cash_voucher = $this->accountRepo->findByOrgIdAndType($organization_id,'wms_receipt')->id;
        $return_voucher = $this->accountRepo->findByOrgIdAndType($organization_id,'credit_note')->id;
        $transaction_sales = $this->accountRepo->findByOrgIdAndType($organization_id,'job_invoice')->id;
        $transaction_cash = $this->accountRepo->findByOrgIdAndType($organization_id,'job_invoice_cash')->id;

        // $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        // $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
        // $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

        // $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

        // $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

        $transaction = Transaction::select('transactions.mobile as mobile_no', 'transactions.id', 'transactions.order_no',  'transactions.total', DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance"),'transactions.transaction_type_id', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), 'vehicle_register_details.registration_no');

        $transaction->leftJoin('people', function ($join) use ($organization_id) {
            $join->on('people.person_id', '=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
        });
        $transaction->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
        });

        $transaction->leftjoin('job_cards AS referenced_in', 'transactions.originated_from_id', '=', 'referenced_in.id');

        $transaction->leftjoin('transactions AS reference_transactions', 'transactions.reference_id', '=', 'reference_transactions.id');

        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        // $transaction->where('transactions.organization_id', $organization_id);

        $transaction->where('transactions.organization_id', $organization_id);

        $transaction->where(function ($query) use ($transaction_sales, $transaction_cash) {
            $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
        });

        $transaction->whereNull('transactions.deleted_at');
        $transaction->where('transactions.notification_status', '!=', 2);
        $transaction->having('balance', '>', 0);

        $transaction->groupby('transactions.id');
        $transaction->orderBy('transactions.updated_at', 'desc');
        $transaction->skip($offset * $limit);
        $transaction->take($limit);

        /*
         * Search by customer name, jobstatus,jobcard number
         *
         * Code By Manimaran - 18-6-2019
         */
        // Search column

        if (! isset($request->jobcard_no) && ! isset($request->customer_name)) {

            $transactions = $transaction->get();
        } else {

            $columnsToSearch = [
                'transactions.order_no'
            ];

            $jobcard_no_query = ($request->jobcard_no) ? $request->jobcard_no : '';

            $customer_name_query = ($request->customer_name) ? $request->customer_name : '';

            $searchQuery = [
                $jobcard_no_query
            ];

            // dd($searchQuery);

            $transaction->Where(function ($query) use ($columnsToSearch, $searchQuery) {

                foreach ($columnsToSearch as $key => $column) {

                    if ($searchQuery[$key] != null) {

                        $query->Where($column, 'LIKE', '%' . $searchQuery[$key] . '%');
                    }
                }
            });

            $SearchCustomer = [
                $customer_name_query,
                $customer_name_query
            ];

            $columnsToSearch_Cust = [
                'business.display_name',
                'people.display_name'
            ];

            $transaction->Where(function ($query) use ($columnsToSearch_Cust, $SearchCustomer) {

                foreach ($columnsToSearch_Cust as $key => $column) {

                    if ($SearchCustomer[$key] != null) {

                        $query->orWhere($column, 'LIKE', '%' . $SearchCustomer[$key] . '%');
                    }
                }
            });

            $transactions = $transaction->get();
        }

        /*
         * END Search by customer name, jobstatus,jobcard number
         */
        Log::info("InvoiceService->findAll_API :- data ".json_encode($transactions));
        Log::info("InvoiceService->findAll_API :- Return");
        return $transactions;

    }

	public function invoice_from_jobcard($jobCard_id,$type)
	{
        //dd($type);
        Log::info("InvoiceService->invoice_from_jobcard :- Inside ".$jobCard_id);

        $organization_id = Session::get('organization_id');

        $this->setTransactionType($type);

        Log::info("InvoiceService->invoice_from_jobcard :- type - " . $this->type);

        $job_card = Transaction::select('transactions.id as transaction_id','job_cards.id as job_card_id','job_cards.name as job_name','job_cards.*','job_card_details.*')
            ->leftjoin('job_cards','transactions.originated_from_id','=','job_cards.id')
            ->leftjoin('job_card_details','job_cards.id','=','job_card_details.job_card_id')
            ->where('transactions.originated_from_id',$jobCard_id)->first();

        $job_card = $this->jobCardRepo->findJobCardById($jobCard_id);

        $jc_transactionModel = $job_card->referencedIn()
                                ->where('transaction_type_id', $job_card->transaction_type_id)
                                ->first();

        $jobCardDetailModel =  $job_card->jobCardDetail;

            if($job_card != null)
            {
                try
                {
                    Log::info("InvoiceService->invoice_from_jobcard :- Try Inside ");

                    $result = DB::transaction(function () use ($job_card,$jobCard_id,$organization_id,$type,$jc_transactionModel,$jobCardDetailModel)
                    {

                        // $organization = Organization::findOrFail($organization_id);
                        // $person = People::select('id as people_org_id','user_type', 'display_name');
                        // if($job_card->user_type == 0) {
                        //     $person->where('person_id', $job_card->people_id);
                        // }
                        // else if($job_card->user_type == 1) {
                        //     $person->where('business_id', $job_card->people_id);
                        // }
                        // $person->where('organization_id', $organization_id);
                        // $persons = $person->first();


                        // $account_ledgers = AccountLedger::select('account_ledgers.id');

                        // if($persons->user_type == 0) {
                        // $account_ledgers->where('person_id', $job_card->people_id);
                        // $person_id = $job_card->people_id;
                        // $business_id = null;
                        // }
                        // else if($persons->user_type == 1) {
                        // $account_ledgers->where('business_id', $job_card->people_id);
                        // $business_id = $job_card->people_id;
                        // $person_id = null;
                        // }

                        // $person_type_id = AccountPersonType::where('name', "customer")->first()->id;

                        // $person_type = DB::table('people_person_types')->where('people_id', $persons->people_org_id)->where('person_type_id', $person_type_id)->first();

                        // if($person_type == null) {
                        //     DB::table('people_person_types')->insert(['people_id' => $persons->people_org_id, 'person_type_id' => $person_type_id]);
                        // }

                        // $account_ledgers->where('organization_id', $organization_id);

                        // $account_ledger = $account_ledgers->first();

                        // $impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

                        // if($account_ledger != null){
                        //     $customer_ledger = $account_ledger->id;
                        // }
                        // else
                        // {

                        //     $ledgergroup = AccountGroup::where('name', 'sundry_debtor')->where('organization_id', $organization_id)->first();
                        //     $customer_ledger = Custom::create_ledger($persons->display_name, $organization, $persons->display_name, $impersonal_ledger->id, $person_id, $business_id, $ledgergroup->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

                        // }

                         // Transaction Table
                         $invoice_transaction = Transaction::where('reference_id', $jc_transactionModel->id)->where('transaction_type_id', $this->transaction_type->id)->whereNull('deleted_at')->first();

                        if(! $invoice_transaction)
                        {
                            //Create new invoice
                            $invoice_transaction = new Transaction;
                            Log::info("InvoiceService:-transaction_type->id - ".$this->transaction_type->id);
                            $getGen_no = Custom::getLastGenNumber($this->transaction_type->id, $organization_id,false);

                            $vou_restart = $this->transaction_type->restart;
                            if($vou_restart == 0)
                            {
                                $gen_no=($getGen_no)?$getGen_no:$this->transaction_type->starting_value;
                                Log::info("InvoiceService->gen_no :- after if Custom::gen_no - ".$gen_no);
                            }
                            else
                            {
                                $gen_no=($vou_restart == 1)?$this->transaction_type->starting_value:$getGen_no;
                                Log::info("InvoiceService->gen_no :- after Custom::gen_no - ".$gen_no);
                            }


                            // $invoice_transaction->entry_id = Custom::add_entry(($invoice_transaction->date != null) ? Carbon::parse($invoice_transaction->date)->format('Y-m-d') : date('Y-m-d'), $entry, ($invoice_transaction != null) ? $invoice_transaction->entry_id : null, $this->type, $organization_id, 1, false, null,$gen_no, null, null, null, null,1,null,null,null);

                            $invoice_transaction->order_no = Custom::generate_accounts_number($this->type, $gen_no, false);
                            $invoice_transaction->gen_no = $gen_no;
                            $invoice_transaction->notification_status = 3;
                            $invoice_transaction->date =  currentDate()->toDateString();
                        }
                        // set transcation fields
                        //$invoice_transaction->reference_no = $job_card->order_no;
                        //$invoice_transaction->remote_reference_no = $job_card->order_no;
                        $invoice_transaction->reference_id = $jc_transactionModel->id;
                        $invoice_transaction->employee_id = $job_card->employee_id;
                        $invoice_transaction->name = $job_card->name;
                        $invoice_transaction->mobile = $job_card->mobile;
                        $invoice_transaction->email = $job_card->email;
                        $invoice_transaction->gst = $job_card->gst;
                        $invoice_transaction->address = $job_card->address;
                        $invoice_transaction->billing_name = $job_card->billing_name;
                        $invoice_transaction->billing_mobile = $job_card->billing_mobile;
                        $invoice_transaction->billing_email = $job_card->billing_email;
                        $invoice_transaction->billing_gst = $job_card->billing_gst;
                        $invoice_transaction->billing_address = $job_card->billing_address;
                        $invoice_transaction->shipping_name = $job_card->shipping_name;
                        $invoice_transaction->shipping_mobile = $job_card->shipping_mobile;
                        $invoice_transaction->shipping_email = $job_card->shipping_email;
                        $invoice_transaction->shipping_address = $job_card->shipping_address;
                        $invoice_transaction->transaction_type_id = $this->transaction_type->id;
                        $invoice_transaction->user_type = $job_card->user_type;
                        $invoice_transaction->people_id = $job_card->people_id;
                        $invoice_transaction->tax_type = 2;
                        if($job_card->sub_total && $job_card->sub_total != null && $job_card->sub_total > 0) {
                            $invoice_transaction->sub_total = $job_card->sub_total;

                        }
                        if($job_card->total && $job_card->total != null && $job_card->total > 0) {
                            $invoice_transaction->total = $job_card->total;
                        }
                        $invoice_transaction->billing_city_id = $job_card->billing_city_id;
                        $invoice_transaction->billing_pincode = $job_card->billing_pincode;
                        $invoice_transaction->shipping_city_id = $job_card->shipping_city_id;
                        $invoice_transaction->shipping_pincode = $job_card->shipping_pincode;
                        $invoice_transaction->organization_id = $organization_id;
                        $job_card->referencedIn()->save($invoice_transaction);
                        Custom::userby($invoice_transaction, true);


                        if($invoice_transaction)
                        {
                            //WMS transaction table
                            $invoice_wms_transaction = WmsTransaction::where('transaction_id',$invoice_transaction->id)->first();

                            if(! $invoice_wms_transaction)
                            {
                                //Create new invoice
                                $invoice_wms_transaction = new WmsTransaction;
                                $invoice_wms_transaction->job_date = currentDate()->toDateString();
                            }
                            //set wms transaction fields
                            $invoice_wms_transaction->transaction_id = $invoice_transaction->id;
                            $invoice_wms_transaction->registration_id = $jobCardDetailModel->registration_id;
                            $invoice_wms_transaction->engine_no = $jobCardDetailModel->engine_no;
                            $invoice_wms_transaction->chasis_no = $jobCardDetailModel->chasis_no;
                            $invoice_wms_transaction->jobcard_status_id = $jobCardDetailModel->jobcard_status_id;
                            $invoice_wms_transaction->service_type = $jobCardDetailModel->service_type;
                            $invoice_wms_transaction->assigned_to = $jobCardDetailModel->assigned_to;
                            $invoice_wms_transaction->job_due_date = $jobCardDetailModel->job_due_date;
                            $invoice_wms_transaction->job_completed_date = $jobCardDetailModel->ijob_completed_date;
                            $invoice_wms_transaction->vehicle_last_visit = $jobCardDetailModel->vehicle_last_visit;
                            $invoice_wms_transaction->vehicle_last_job = $jobCardDetailModel->vehicle_last_job;
                            $invoice_wms_transaction->vehicle_mileage = $jobCardDetailModel->vehicle_mileage;
                            $invoice_wms_transaction->next_visit_mileage = $jobCardDetailModel->next_visit_mileage;
                            $invoice_wms_transaction->vehicle_next_visit = $jobCardDetailModel->vehicle_next_visit;
                            $invoice_wms_transaction->vehicle_next_visit_reason = $jobCardDetailModel->vehicle_next_visit_reason;
                            $invoice_wms_transaction->vehicle_note = $jobCardDetailModel->vehicle_note;
                            $invoice_wms_transaction->vehicle_complaints = $jobCardDetailModel->vehicle_complaints;
                            $invoice_wms_transaction->driver = $jobCardDetailModel->driver;
                            $invoice_wms_transaction->driver_contact = $jobCardDetailModel->driver_contact;
                            $invoice_wms_transaction->organization_id = $organization_id;
                            $invoice_wms_transaction->save();
                            Custom::userby($invoice_wms_transaction, true);

                        }

                        //  //check if there are existing items in the invoice, if so delete all
                        // if($invoice_transaction != null)
                        // {
                        //     $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id',$invoice_transaction->id)->delete();

                        // }
                        // //get items from job card
                        // $transaction_items = TransactionItem::where('transaction_id',$jobCard_id)->get();

                        // //start create invoice items
                        // if($transaction_items != null && $invoice_transaction->id != null)
                        // {

                        //     Log::info("item_id".count($transaction_items));

                        //     for($i=0; $i<count($transaction_items); $i++)
                        //     {
                        //         Log::info("inside goods iterate value".$transaction_items[$i]);

                        //         $item_goods = new TransactionItem;
                        //         $item_goods->item_id = $transaction_items[$i]->item_id;
                        //         $item_goods->quantity = ($transaction_items[$i]->quantity) ? $transaction_items[$i]->quantity : 0.00;
                        //         $item_goods->description = ($transaction_items[$i]->description) ? $transaction_items[$i]->description : null;
                        //         $item_goods->job_item_status = ($transaction_items[$i]->job_item_status) ? $transaction_items[$i]->job_item_status : null;
                        //         $item_goods->assigned_employee_id = ($transaction_items[$i]->assigned_employee_id) ? $transaction_items[$i]->assigned_employee_id : null;
                        //         $item_goods->transaction_id = $invoice_transaction->id;
                        //         $item_goods->save();
                        //         Log::info(" goods save ".json_encode($item_goods));

                        //         // if($transaction_items[$i] != null)
                        //         // {
                        //         //     $current_item = InventoryItem::find($transaction_items[$i]->item_id);
                        //         // }

                        //         // if($item_goods->id != null)
                        //         // {
                        //         //     $sale_ledger = AccountLedger::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
                        //         //     $income_account = ($current_item->income_account != null) ? $current_item->income_account : $sale_ledger;
                        //         //     //Item sale is income, All incomes are credit
                        //         //     //Customer gets the item, Debit the receiver
                        //         //     $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $income_account, 'amount' =>null];

                        //         // }

                        //     }

                        // }


                         // check if there are existing items in the invoice, if so delete all
                         if ($invoice_transaction != null)
                         {

                            // DELETE ITEMS in INVOICE if not in JOBCARD
                            // invoice item connected to jobcard item
                            // if refs_items.id is null, then the item in invoice has to be deleted
                            $invoice_items = TransactionItem::select('transaction_items.id AS inv_trans_item_id', 'transaction_items.transaction_id AS inv_trans_id', 'transaction_items.item_id AS inv_item_id', 'refs_items.id AS ref_trans_item_id', 'refs_items.job_card_id AS ref_trans_id', 'refs_items.item_id AS ref_item_id')->leftJoin('job_card_items as refs_items', function ($join) use ($invoice_transaction) {
                                $join->on('transaction_items.item_id', '=', 'refs_items.item_id');
                                $join->where('refs_items.job_card_id', '=', $invoice_transaction->reference_id);
                                // $join->on('refs_items.transaction_id', '=', 'transactions.reference_id');
                            })
                                ->whereNull('refs_items.item_id')
                                ->where('transaction_items.transaction_id', $invoice_transaction->id);
                            Log::info('InvoiceService->invoice_from_jobcard:-query ... ' . $invoice_items->toSql());
                            Log::info('InvoiceService->invoice_from_jobcard:-  QueryBinding ' . json_encode($invoice_items->getBindings()));
                            $invoice_items = $invoice_items->get();
                            Log::info('InvoiceService->invoice_from_jobcard:-  QueryBinding ' . json_encode($invoice_items));

                            $invoice_items->each(function ($invoice_item) {
                                $item_to_delete = $invoice_item;
                                Log::info('InvoiceService->invoice_from_jobcard:-  $item_goods ' . json_encode($item_to_delete));
                                $deleted_item = DB::table('transaction_items')->where('transaction_items.id', $item_to_delete->inv_trans_item_id)
                                    ->delete();
                                Log::info('InvoiceService->invoice_from_jobcard:-  $$deleted_item ' . json_encode($deleted_item));
                            });

                            // UPDATE or CREATE from JOBCARD into INVOICE
                            // get items from job card
                            $jobCard_items = JobCardItem::where('job_card_id', $jobCard_id)->get();

                            $jobCard_items->each(function ($jobCard_item) use ($invoice_transaction,$invoice_wms_transaction) {
                                $item_to_createupdate = $jobCard_item;
                                Log::info('InvoiceService->invoice_from_jobcard:-  item_to_createupdate ' . json_encode($item_to_createupdate));

                                Log::info('InvoiceService->invoice_from_jobcard:-  item_to_createupdate transaction->id' . json_encode($invoice_transaction->id));
                                $invoice_item_createupdated = TransactionItem::updateOrCreate([
                                    'item_id' => $item_to_createupdate->item_id,
                                    'transaction_id' => $invoice_transaction->id
                                ], [
                                    'quantity' => ($item_to_createupdate->quantity) ? $item_to_createupdate->quantity : 0.00,
                                    'description' => ($item_to_createupdate->description) ? $item_to_createupdate->description : null,
                                    'assigned_employee_id' => ($item_to_createupdate->assigned_employee_id) ? $item_to_createupdate->assigned_employee_id : null,
                                    'job_item_status' => ($item_to_createupdate->job_item_status) ? $item_to_createupdate->job_item_status : null,
                                    'duration' => ($item_to_createupdate->duration) ? $item_to_createupdate->duration : null
                                ]);
                                Log::info('InvoiceService->invoice_from_jobcard:- invoice_item_createupdated ' . json_encode($invoice_item_createupdated));

//                                 'rate' => ($item_to_createupdate->rate) ? $item_to_createupdate->rate : 0.00,
//                                 'amount' => ($item_to_createupdate->amount) ? $item_to_createupdate->amount : 0.00,
//                                 'tax' => ($item_to_createupdate->tax) ? $item_to_createupdate->tax : null,
//                                 'tax_id' => ($item_to_createupdate->tax_id) ? $item_to_createupdate->tax_id : null

                                $doSave = false;
                                if ((!$invoice_item_createupdated->rate && $invoice_item_createupdated->rate == null) || $invoice_item_createupdated->rate == 0) {
                                    if($item_to_createupdate->rate && $item_to_createupdate->rate != null && $item_to_createupdate->rate > 0) {
                                        $invoice_item_createupdated->rate = $item_to_createupdate->rate;
                                        $doSave = true;
                                    }else {
                                        $invoice_item_createupdated->rate = $this->inventoryItemServ->getInventoryItemRate($item_to_createupdate->item_id, $invoice_wms_transaction->registration_id);
                                        $doSave = true;
                                    }
                                }

                                if ((!$invoice_item_createupdated->amount && $invoice_item_createupdated->amount == null) || $invoice_item_createupdated->amount == 0) {
                                    if($item_to_createupdate->amount && $item_to_createupdate->amount != null && $item_to_createupdate->amount > 0) {
                                        $invoice_item_createupdated->amount = $item_to_createupdate->amount;
                                        $doSave = true;
                                    }
                                }

                                if ((!$invoice_item_createupdated->tax && $invoice_item_createupdated->tax == null) || $invoice_item_createupdated->tax == 0) {
                                    if($item_to_createupdate->tax && $item_to_createupdate->tax != null) {
                                        $invoice_item_createupdated->tax = $item_to_createupdate->tax;
                                        $doSave = true;
                                    }
                                }

                                if ((!$invoice_item_createupdated->tax_id && $invoice_item_createupdated->tax_id == null) || $invoice_item_createupdated->tax_id == 0) {
                                    if($item_to_createupdate->tax_id && $item_to_createupdate->tax_id != null){
                                        $invoice_item_createupdated->tax_id = $item_to_createupdate->tax_id;
                                        $doSave = true;
                                    }
                                }

                                if($doSave) {
                                    $invoice_item_createupdated->save();
                                }
                            });
                        }
                       
                        JobCardDetail::where('job_card_id',$jobCard_id)->update(['jobcard_status_id' => 8]);
                                 
                        Log::info("InvoiceService->invoice_from_jobcard :- return TRY");
                        if($invoice_transaction != null)
                        {
        
                            return redirect()->route("job_invoice.index", [$invoice_transaction->id,$type])->with('message', 'updated');	
                        }
                        else
                        {
                            return redirect()->route("job_invoice.index", [$invoice_transaction->id,$type])->with('message', 'stored');
                        }
        
                    
                    });
        
                    Log::info("InvoiceService->invoice_from_jobcard :- return TRY OUT");
                    return $result;
        
                }
        
                catch (\Exception $e) 
                {
                  Log::info("InvoiceService->invoice_from_jobcard :- return Catch".$e->getMessage());
                  return response()->json(['status' => 2, 'error' =>  $e->getMessage()]);
                }
            }

	}
	
}
