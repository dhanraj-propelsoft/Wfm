<?php
namespace App\Http\Controllers\Tradewms\Estimation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\AccountVoucher;
use App\WmsTransaction;
use App\AccountLedger;
use App\PeopleTitle;
use App\Transaction;
use App\PaymentMode;
use App\CustomerGroping;
use Carbon\Carbon;
use App\Country;
use App\Custom;
use App\State;
use App\Term;
use Session;
use Mail;
use Auth;
use DB;
use PDF;
use File;
use Validator;
use Storage;
use Input;
use App\Http\Controllers\Accounts\AccountVoucher\AccountVoucherRepository;
use App\Http\Controllers\Tradewms\Jobcard\JobCardRepository;
use App\TransactionItem;
use App\City;
use App\AccountVoucherType;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardItem;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCard;
use App\Http\Controllers\Inventory\Item\InventoryItemService;

class EstimationService
{

    private $type = "job_request";

    protected $type_id;

    protected $transaction_type;

    public function __construct(AccountVoucherRepository $accountRepo, JobCardRepository $jobCardRepo, InventoryItemService $inventoryItemServ)
    {
        $this->accountRepo = $accountRepo;
        $this->jobCardRepo = $jobCardRepo;
        $this->inventoryItemServ = $inventoryItemServ;
    }

    protected function setTransactionType()
    {
//         $this->type_id = Session::get('je_type_id');
//         Log::info('EstimationService->setTransactionType:- je_type_id ' . $this->type_id);
//         Log::info('EstimationService->setTransactionType:- org id ' . Session::get('organization_id'));
//         if (! $this->type_id) {
//             // get transaction type
//             $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $this->type);
//             Log::info('EstimationService->setTransactionType:- put je_type_id ' . json_encode($this->transaction_type));
//             if ($this->transaction_type) {
//                 Session::put('je_type_id', $this->transaction_type->id);
//                 $this->type_id = Session::get('je_type_id');
//             }
//             Log::info('EstimationService->setTransactionType:- put je_type_id ' . $this->type_id);
//         } else {
//             $this->transaction_type = $this->accountRepo->findById($this->type_id);
//         }
        if (! $this->type_id) {
            $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $this->type);
            Log::info('EstimationService->setTransactionType:- put je_type_id ' . json_encode($this->transaction_type));
            $this->type_id = $this->transaction_type->id;
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findAll($id, Request $request)
    {
        Log::info("EstimationService->Index :- Inside ");

        $this->setTransactionType();
        
        Log::info("EstimationService->Index :- type - " . $this->type);
        
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

        $transaction = Transaction::select(DB::raw('COUNT(transactions.id)'), 'transactions.id', 'transactions.order_no', 'transactions.originated_from_id', 'transactions.reference_id', 'transactions.approved_on', 'vehicle_register_details.id as vehicle_id', DB::raw('sum( (CASE WHEN wms_transactions.advance_amount is NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as jobcard_total'), DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"), 'transactions.date as original_date', 'transactions.due_date as original_due_date', 'transactions.total', DB::raw(" 0 AS balance"), DB::raw(" 1 AS status"), 'transactions.approval_status', 'transactions.transaction_type_id', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw("IF(people.display_name IS NULL, business.display_name, CONCAT(people.first_name, ' ', COALESCE(people.last_name))) as customer_contact"), DB::raw("DATE_FORMAT(transactions.shipping_date, '%d %b, %Y') as shipping_date"), DB::raw("COALESCE(transactions.reference_no, '') AS reference_no"), DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'), 'vehicle_register_details.registration_no', 'hrm_employees.first_name AS assigned_to', 'service_types.name as service_type', 'vehicle_jobcard_statuses.id as job_card_status_id', 'vehicle_jobcard_statuses.name as jobcard_status', 'wms_transactions.name as name_of_job', 'wms_transactions.job_date', 'wms_transactions.job_due_date', 'wms_transactions.job_completed_date', 'wms_transactions.advance_amount');

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

        $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

        $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');

        //$transaction->where('transactions.organization_id', $organization_id);

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

        Log::info("EstimationService->Index :- return  type - " . $this->type);

        // needed for the view line below
        $type = $this->type;

        return view('inventory.transaction', compact('transactions', 'transaction_type', 'type', 'state', 'title', 'payment', 'terms', 'group_name', 'firstDay_only', 'today', 'city', 'ledgers', 'firstDay_only_trade_wms', 'from_date_trade_wms', 'to_date'));
    }

    /**
     * create or update estimate from job card.
     *
     * @return \Illuminate\Http\Response
     */
    public function estimation_from_jobcard($jobCard_id)
    {
        Log::info("EstimationService->estimation_from_jobcard :- Inside " . $jobCard_id);

        $organization_id = Session::get('organization_id');
       
        $this->setTransactionType();
        
        Log::info("EstimationService->estimation_from_jobcard :- type - " . $this->type);
        
        $job_card = $this->jobCardRepo->findJobCardById($jobCard_id);
     
        $jc_transactionModel = $job_card->referencedIn()
                                ->where('transaction_type_id', $job_card->transaction_type_id)
                                ->first();
                               
        $jobCardDetailModel =  $job_card->jobCardDetail;
                             
        if ($job_card != null) {
            try {
                Log::info("EstimationService->estimation_from_jobcard :- Try Inside 1");
                Log::info("EstimationService->estimation_from_jobcard :- Try Inside ".$this->transaction_type->id);
                Log::info("EstimationService->estimation_from_jobcard :- Try Inside 2");
                
                $result = DB::transaction(
                    function () use ($job_card, $jobCard_id, $organization_id,$jc_transactionModel,$jobCardDetailModel) {
                        // Transaction Table
                        $estimation_transaction = Transaction::where('reference_id', $jc_transactionModel->id)->where('transaction_type_id', $this->transaction_type->id)->first();
                        
                       
                        if (! $estimation_transaction) {
                            // Create new estimate
                            $estimation_transaction = new Transaction();
                            $getGen_no = Custom::getLastGenNumber($this->transaction_type->id, $organization_id, false);
                            //$vou_restart = AccountVoucher::select('restart')->where('id', $this->transaction_type->id)->first();
                            $vou_restart = $this->transaction_type->restart;
    
                            if ($vou_restart == 0) {
                                $gen_no = ($getGen_no) ? $getGen_no : $this->transaction_type->starting_value;
                                Log::info("EstimationService->gen_no :- after if Custom::gen_no - " . $gen_no);
                            } else {
                                $gen_no = ($vou_restart == 1) ? $this->transaction_type->starting_value : $getGen_no;
                                Log::info("EstimationService->gen_no :- after Custom::gen_no - " . $gen_no);
                            }
                            
                            $estimation_transaction->order_no = Custom::generate_accounts_number($this->transaction_type->name, $gen_no, false);
                            $estimation_transaction->gen_no = $gen_no;
                            $estimation_transaction->notification_status = 3;
                            $estimation_transaction->date = currentDate()->toDateString();
                        }
                        // set transcation fields
                       
                        $estimation_transaction->reference_no = $job_card->order_no;
                        $estimation_transaction->remote_reference_no = $job_card->order_no;
                        $estimation_transaction->reference_id = $jc_transactionModel->id;
                        $estimation_transaction->employee_id = $job_card->employee_id;
                        $estimation_transaction->name = $job_card->name;
                        $estimation_transaction->mobile = $job_card->mobile;
                        $estimation_transaction->email = $job_card->email;
                        $estimation_transaction->gst = $job_card->gst;
                        $estimation_transaction->address = $job_card->address;
                        $estimation_transaction->billing_name = $job_card->billing_name;
                        $estimation_transaction->billing_mobile = $job_card->billing_mobile;
                        $estimation_transaction->billing_email = $job_card->billing_email;
                        $estimation_transaction->billing_gst = $job_card->billing_gst;
                        $estimation_transaction->billing_address = $job_card->billing_address;
                        $estimation_transaction->shipping_name = $job_card->shipping_name;
                        $estimation_transaction->shipping_mobile = $job_card->shipping_mobile;
                        $estimation_transaction->shipping_email = $job_card->shipping_email;
                        $estimation_transaction->shipping_address = $job_card->shipping_address;
                        $estimation_transaction->transaction_type_id = $this->transaction_type->id;
                        $estimation_transaction->user_type = $job_card->user_type;
                        $estimation_transaction->people_id = $job_card->people_id;
                        $estimation_transaction->tax_type = 2;
                        if($job_card->sub_total && $job_card->sub_total != null && $job_card->sub_total > 0) {
                            $estimation_transaction->sub_total = $job_card->sub_total;
                        }
                        if($job_card->total && $job_card->total != null && $job_card->total > 0) {
                            $estimation_transaction->total = $job_card->total;
                        }
                        $estimation_transaction->billing_city_id = $job_card->billing_city_id;
                        $estimation_transaction->billing_pincode = $job_card->billing_pincode;
                        $estimation_transaction->shipping_city_id = $job_card->shipping_city_id;
                        $estimation_transaction->shipping_pincode = $job_card->shipping_pincode;
                        $estimation_transaction->organization_id = $organization_id;
                        
                        // save estimate to transaction table
                        //use this to automatically update the orginated_from fields
                        $job_card->referencedIn()->save($estimation_transaction);
                        //commenting out below line as the above line will do the same.
                        //$estimation_transaction->save();
                        Custom::userby($estimation_transaction, true);
                        

                        // WMS transaction table
                        if ($estimation_transaction) {
                            // update existing estimate
                            $estimation_wms_transaction = WmsTransaction::where('transaction_id', $estimation_transaction->id)->first();
                           
                            if (! $estimation_wms_transaction) {
                                // Create new estimate
                                $estimation_wms_transaction = new WmsTransaction();
                                $estimation_wms_transaction->job_date = currentDate()->toDateString();
                            }

                            // set wms transaction fields
                            $estimation_wms_transaction->transaction_id = $estimation_transaction->id;
                            $estimation_wms_transaction->registration_id = $jobCardDetailModel->registration_id;
                            $estimation_wms_transaction->engine_no = $jobCardDetailModel->engine_no;
                            $estimation_wms_transaction->chasis_no = $jobCardDetailModel->chasis_no;
                            $estimation_wms_transaction->jobcard_status_id = $jobCardDetailModel->jobcard_status_id;
                            $estimation_wms_transaction->service_type = $jobCardDetailModel->service_type;
                            $estimation_wms_transaction->assigned_to = $jobCardDetailModel->assigned_to;
                            $estimation_wms_transaction->job_due_date = $jobCardDetailModel->job_due_date;
                            $estimation_wms_transaction->job_completed_date = $jobCardDetailModel->ijob_completed_date;
                            $estimation_wms_transaction->vehicle_last_visit = $jobCardDetailModel->vehicle_last_visit;
                            $estimation_wms_transaction->vehicle_last_job = $jobCardDetailModel->vehicle_last_job;
                            $estimation_wms_transaction->vehicle_mileage = $jobCardDetailModel->vehicle_mileage;
                            $estimation_wms_transaction->next_visit_mileage = $jobCardDetailModel->next_visit_mileage;
                            $estimation_wms_transaction->vehicle_next_visit = $jobCardDetailModel->vehicle_next_visit;
                            $estimation_wms_transaction->vehicle_next_visit_reason = $jobCardDetailModel->vehicle_next_visit_reason;
                            $estimation_wms_transaction->vehicle_note = $jobCardDetailModel->vehicle_note;
                            $estimation_wms_transaction->vehicle_complaints = $jobCardDetailModel->vehicle_complaints;
                            $estimation_wms_transaction->driver = $jobCardDetailModel->driver;
                            $estimation_wms_transaction->driver_contact = $jobCardDetailModel->driver_contact;
                            $estimation_wms_transaction->organization_id = $organization_id;
                            $estimation_wms_transaction->save();
                            Custom::userby($estimation_wms_transaction, true);
                            
                        }
                      //dd($estimation_transaction);
                        // check if there are existing items in the estimate, if so delete all
                        if ($estimation_transaction != null) {

                            // DELETE ITEMS in ESTIMATE if not in JOBCARD
                            // estimate item connected to jobcard item
                            // if refs_items.id is null, then the item in estimate has to be deleted
                            $estimate_items = TransactionItem::select('transaction_items.id AS est_trans_item_id', 'transaction_items.transaction_id AS est_trans_id', 'transaction_items.item_id AS est_item_id', 'refs_items.id AS ref_trans_item_id', 'refs_items.job_card_id AS ref_trans_id', 'refs_items.item_id AS ref_item_id')->leftJoin('job_card_items as refs_items', function ($join) use ($estimation_transaction) {
                                $join->on('transaction_items.item_id', '=', 'refs_items.item_id');
                                $join->where('refs_items.job_card_id', '=', $estimation_transaction->reference_id);
                                // $join->on('refs_items.transaction_id', '=', 'transactions.reference_id');
                            })
                                ->whereNull('refs_items.item_id')
                                ->where('transaction_items.transaction_id', $estimation_transaction->id);
                            Log::info('EstimationService->estimation_from_jobcard:-query ... ' . $estimate_items->toSql());
                            Log::info('EstimationService->estimation_from_jobcard:-  QueryBinding ' . json_encode($estimate_items->getBindings()));
                            $estimate_items = $estimate_items->get();
                            //dd($estimate_items);
                            Log::info('EstimationService->estimation_from_jobcard:-  QueryBinding ' . json_encode($estimate_items));

                            $estimate_items->each(function ($estimate_item) {
                                $item_to_delete = $estimate_item;
                                Log::info('EstimationService->estimation_from_jobcard:-  $item_goods ' . json_encode($item_to_delete));
                                $deleted_item = DB::table('transaction_items')->where('transaction_items.id', $item_to_delete->est_trans_item_id)
                                    ->delete();
                                Log::info('EstimationService->estimation_from_jobcard:-  $$deleted_item ' . json_encode($deleted_item));
                            });

                            // UPDATE or CREATE from JOBCARD into ESTIMATE
                            // get items from job card
                            $jobCard_items = JobCardItem::where('job_card_id', $jobCard_id)->get();

                            $jobCard_items->each(function ($jobCard_item) use ($estimation_transaction,$estimation_wms_transaction) {
                                $item_to_createupdate = $jobCard_item;
                                Log::info('EstimationService->estimation_from_jobcard:-  item_to_createupdate ' . json_encode($item_to_createupdate));

                                Log::info('EstimationService->estimation_from_jobcard:-  item_to_createupdate transaction->id' . json_encode($estimation_transaction->id));
                                $estimate_item_createupdated = TransactionItem::updateOrCreate([
                                    'item_id' => $item_to_createupdate->item_id,
                                    'transaction_id' => $estimation_transaction->id
                                ], [
                                    'quantity' => ($item_to_createupdate->quantity) ? $item_to_createupdate->quantity : 0.00,
                                    'description' => ($item_to_createupdate->description) ? $item_to_createupdate->description : null,
                                    'assigned_employee_id' => ($item_to_createupdate->assigned_employee_id) ? $item_to_createupdate->assigned_employee_id : null,
                                    'job_item_status' => ($item_to_createupdate->job_item_status) ? $item_to_createupdate->job_item_status : null,
                                    'duration' => ($item_to_createupdate->duration) ? $item_to_createupdate->duration : null
                                ]);
                                Log::info('EstimationService->estimation_from_jobcard:-  estimate_item_createupdated ' . json_encode($estimate_item_createupdated));
                                //'rate' => $rate,
                                //'amount' => $amount,
                                //'tax' => ($item_to_createupdate->tax) ? $item_to_createupdate->tax : null,
                                //'tax_id' => ($item_to_createupdate->tax_id) ? $item_to_createupdate->tax_id : null

                                $doSave = false;
                                if ((!$estimate_item_createupdated->rate && $estimate_item_createupdated->rate == null) || $estimate_item_createupdated->rate == 0) {
                                    if($item_to_createupdate->rate && $item_to_createupdate->rate != null && $item_to_createupdate->rate > 0) {
                                        $estimate_item_createupdated->rate = $item_to_createupdate->rate;
                                        $doSave = true;
                                    }else{
                                        $estimate_item_createupdated->rate = $this->inventoryItemServ->getInventoryItemRate($item_to_createupdate->item_id, $estimation_wms_transaction->registration_id);
                                        $doSave = true;
                                    }
                                }

                                if ((!$estimate_item_createupdated->amount && $estimate_item_createupdated->amount == null) || $estimate_item_createupdated->amount == 0) {
                                    if($item_to_createupdate->amount && $item_to_createupdate->amount != null && $item_to_createupdate->amount > 0) {
                                        $estimate_item_createupdated->amount = $item_to_createupdate->amount;
                                        $doSave = true;
                                    }
                                }

                                if ((!$estimate_item_createupdated->tax && $estimate_item_createupdated->tax == null) || $estimate_item_createupdated->tax == 0) {
                                    if($item_to_createupdate->tax && $item_to_createupdate->tax != null) {
                                        $estimate_item_createupdated->tax = $item_to_createupdate->tax;
                                        $doSave = true;
                                    }
                                }

                                if ((!$estimate_item_createupdated->tax_id && $estimate_item_createupdated->tax_id == null) || $estimate_item_createupdated->tax_id == 0) {
                                    if($item_to_createupdate->tax_id && $item_to_createupdate->tax_id != null) {
                                        $estimate_item_createupdated->tax_id = $item_to_createupdate->tax_id;
                                        $doSave = true;
                                    }
                                }

                                if($doSave) {
                                    $estimate_item_createupdated->save();
                                }
                            });

                         

                            // jocard card item connected with estimation
                            // this will bring only recrods which are new in jobcard and not available in estimate
                            // $jc_items = TransactionItem::select('transaction_items.*','je_items.*')
                            // ->leftJoin('transaction_items as je_items', function($join) use ($estimation_transaction)
                            // {
                            // $join->on('transaction_items.item_id', '=', 'je_items.item_id');
                            // $join->where('je_items.transaction_id', '=', $estimation_transaction->id);
                            // //$join->on('refs_items.transaction_id', '=', 'transactions.reference_id');
                            // })
                            // ->whereNull('je_items.id')
                            // ->where('transaction_items.transaction_id',$estimation_transaction->reference_id);
                            // Log::info('EstimationService->estimation_from_jobcard:-query ... '.$jc_items->toSql());
                            // Log::info('EstimationService->estimation_from_jobcard:- QueryBinding ' . json_encode($jc_items->getBindings()));
                            // $jc_items = $jc_items->get();
                        }

                        // //get items from job card
                        // $transaction_items = TransactionItem::where('transaction_id',$jobCard_id)->get();

                        // //start create estimate items
                        // if($transaction_items != null && $estimation_transaction->id != null)
                        // {
                        // Log::info("item_id".count($transaction_items));
                        // for($i=0; $i<count($transaction_items); $i++)
                        // {
                        // Log::info("inside goods iterate value".$transaction_items[$i]);

                        // $item_goods = new TransactionItem;
                        // $item_goods->item_id = $transaction_items[$i]->item_id;
                        // $item$estimation_transactiontity = ($transaction_items[$i]->quantity) ? $transaction_items[$i]->quantity : 0.00;
                        // $item_goods->description = ($transaction_items[$i]->description) ? $transaction_items[$i]->description : null;
                        // $item_goods->start_time = ($transaction_items[$i]->start_time) ? $transaction_items[$i]->start_time : null;
                        // $item_goods->end_time=($transaction_items[$i]->end_time) ? $transaction_items[$i]->end_time : null;
                        // $item_goods->job_item_status = ($transaction_items[$i]->job_item_status) ? $transaction_items[$i]->job_item_status : null;
                        // $item_goods->assigned_employee_id = ($transaction_items[$i]->assigned_employee_id) ? $transaction_items[$i]->assigned_employee_id : null;
                        // $item_goods->transaction_id = $estimation_transaction->id;
                        // $item_goods->save();
                        // Log::info(" goods save ".json_encode($item_goods));
                        // }
                        // }

                        Log::info("EstimationService->estimation_from_jobcard :- return TRY");
                        if ($estimation_transaction != null) {
                            return redirect()->route("job_estimation.index", $estimation_transaction->id)->with('message', 'updated');
                        } else {
                            return redirect()->route("job_estimation.index", $estimation_transaction->id)->with('message', 'stored');
                        }
                    });

                Log::info("EstimationService->estimation_from_jobcard :- return TRY OUT");
                return $result;
            } 
            catch (\Exception $e) {
                Log::info("EstimationService->estimation_from_jobcard :- return Catch" . $e->getMessage());
                return response()->json([
                    'status' => 2,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
