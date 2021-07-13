<?php
namespace App\Http\Controllers\Api\Wms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App\Helpers\Helper;
use App\Notification\Service\SmsNotificationService;
use App\OrganizationPerson;
use App\Organization;
use App\Custom;
use App\AccountVoucher;
use App\AccountVoucherType;
use App\VehicleRegisterDetail;
use App\Transaction;
use App\Person;
use App\Business;
use App\RegisteredVehicleSpec;
use App\VehicleVariant;
use App\People;
use App\VehicleChecklist;
use App\WmsTransaction;
use App\WmsAttachment;
use App\WmsChecklist;
use App\HrmEmployee;
use App\TransactionItem;
use App\BusinessAddressType;
use App\BusinessCommunicationAddress;
use App\TransactionField;
use App\VehicleJobcardStatus;
use App\InventoryItem;
use App\InventoryItemGroup;
use App\VehicleSegmentDetail;
use App\WmsPriceList;
use App\VehicleSpecification;
use App\VehicleSpecificationDetails;
use App\VehicleJobItemStatus;
use App\TaxGroup;
use App\Tax;
use Carbon\Carbon;
use DateTime;
use Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Tradewms\Invoice\InvoiceService;
use App\Http\Controllers\Api\Wms\JobCardService;

use App\Http\Controllers\Accounts\AccountVoucher\AccountVoucherRepository;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCard;

class JobcardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200;

    public function __construct(SmsNotificationService $SmsNotificationService,InvoiceService $invoServ,JobCardService $jobCardServ)
    {
        $this->SmsNotificationService = $SmsNotificationService;
        $this->invoServ = $invoServ;
        $this->jobCardServ = $jobCardServ;
        //$this->accountRepo = $accountRepo;
    }

    public function create($person_id, $organization_id)
    {

        Log::info('API_JobcardController->create:- Inside' );

        $data = $this->jobCardServ->create_API($person_id, $organization_id);
        Log::info('API_JobcardController->create:- Return data '.json_encode($data) );
        Log::info('API_JobcardController->create:- Return' );
        return response()->json($data, $this->successStatus);
    }

    public function get_vehicle_datas($id, $organization_id = false)
    {
        $vehicle_details = VehicleRegisterDetail::where('id', $id)->first();

        // dd($vehicle_details);

        if ($vehicle_details->user_type == "0") {
            $customer_id = Person::findorfail($vehicle_details->owner_id)->id;
        }
        // dd($vehicle_details->owner_id);
        if ($vehicle_details->user_type == "1") {
            $customer_id = Business::findorfail($vehicle_details->owner_id)->id;
        }

        /* $last_updated_datas = VehicleRegisterDetail::select('transactions.id','vehicle_register_details.registration_no','transactions.reference_no','wms_transactions.job_date')->leftjoin('wms_transactions','wms_transactions.registration_id','=','vehicle_register_details.id')->leftjoin('transactions','transactions.id','=','wms_transactions.transaction_id')->where('wms_transactions.jobcard_status_id','8')->orderby('transactions.id',"DESC")->first(); */

        $last_updated_datas = Transaction::select('transactions.id', 'vehicle_register_details.registration_no', 'wms_transactions.job_date', 'transactions.reference_no');
        $last_updated_datas->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
        $last_updated_datas->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        $last_updated_datas->where('vehicle_register_details.registration_no', $vehicle_details->registration_no);
        $last_updated_datas->where(function ($query) {
            $query->where('wms_transactions.jobcard_status_id', '!=', "8")
                ->orWhere('wms_transactions.jobcard_status_id', '=', null);
        });
        $last_updated_datas->where('transactions.organization_id', $organization_id);
        $last_updated_datas->orderBy('transactions.id', "DESC");
        $last_updated_data = $last_updated_datas->first();

        // dd($last_updated_data);

        if ($last_updated_data == null) {
            $job_date = "";
            $job_reference_no = "";
        } else {
            $job_date = $last_updated_data->job_date;
            $job_reference_no = $last_updated_data->reference_no;
        }

        $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id', 'vehicle_spec_masters.display_name', 'registered_vehicle_specs.spec_value')->leftjoin('vehicle_spec_masters', 'vehicle_spec_masters.id', '=', 'registered_vehicle_specs.spec_id')
            ->where('registered_vehicle_specs.organization_id', $organization_id)
            ->where('registered_vehicle_specs.registered_vehicle_id', $id)
            ->get();
        $vehicle_name = VehicleVariant::select('id', 'vehicle_configuration')->where('id', $vehicle_details->vehicle_configuration_id)->first();

        /*
         * $specifications = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',
         * 'registered_vehicle_specs.spec_value')->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')->where('registered_vehicle_specs.organization_id',$organization_id)->where('registered_vehicle_specs.registered_vehicle_id',$request->id)->get();
         */

        $values = [];
        $spec = [];
        foreach ($spec_values as $key => $value) {
            $values['spec_values'][] = $spec_values[$key]->spec_value;
            $spec['specification'][] = $spec_values[$key]->display_name;
        }

        $people = People::select('people.first_name', 'people.last_name', 'people.display_name', 'people.mobile_no', 'people.email_address', 'people_titles.id AS title_id', 'genders.id AS gender_id', DB::raw('COALESCE(billing_city.name, "") AS billing_city'), DB::raw('COALESCE(billing_state.name, "") as billing_state'), DB::raw('COALESCE(billing_address.address, "") as billing_address'), DB::raw('COALESCE(billing_address.pin, "") as billing_pin'), DB::raw('COALESCE(billing_address.google, "") as billing_google'), 'billing_address.id AS billing_id', DB::raw('COALESCE(shipping_city.name, "") AS shipping_city'), DB::raw('COALESCE(shipping_state.name, "") as shipping_state'), DB::raw('COALESCE(shipping_address.address, "") as shipping_address'), DB::raw('COALESCE(shipping_address.pin, "") as shipping_pin'), DB::raw('COALESCE(shipping_address.google, "") as shipping_google'), 'shipping_address.id AS shipping_id');
        $people->leftJoin('genders', 'genders.id', '=', 'people.gender_id');
        $people->leftJoin('people_titles', 'people_titles.id', '=', 'people.title_id');
        $people->leftJoin('people_addresses AS billing_address', function ($join) {
            $join->on('billing_address.people_id', '=', 'people.id')
                ->where('billing_address.address_type', '0');
        });
        $people->leftJoin('people_addresses AS shipping_address', function ($join) {
            $join->on('shipping_address.people_id', '=', 'people.id')
                ->where('shipping_address.address_type', '1');
        });

        if ($vehicle_details->user_type == 0) {
            $people->where('people.person_id', $customer_id);
        } else if ($vehicle_details->user_type == 1) {
            $people->where('people.business_id', $customer_id);
        }
        $people->leftjoin('cities AS billing_city', 'billing_address.city_id', '=', 'billing_city.id');
        $people->leftjoin('states AS billing_state', 'billing_city.state_id', '=', 'billing_state.id');
        $people->leftjoin('cities AS shipping_city', 'shipping_address.city_id', '=', 'shipping_city.id');
        $people->leftjoin('states AS shipping_state', 'shipping_city.state_id', '=', 'shipping_state.id');
        $people->where('people.organization_id', $organization_id);

        $person = $people->first();

        $vehicle_check_list = VehicleChecklist::select('name', 'display_name', 'id')->where('organization_id', $organization_id)->get();

        if ($vehicle_details != null) {
            return response()->json([
                'status' => 1,
                'message' => 'Vehicle Datas Retreived Successfully.',
                'data' => [
                    'id' => $vehicle_details->id,
                    'registration_no' => $vehicle_details->registration_no,
                    'name' => $vehicle_name,
                    'user_type' => $vehicle_details->user_type,
                    'owner_id' => $customer_id,
                    // 'display_name' => $vehicle_details->display_name,
                    // 'engine_no' => $vehicle_details->engine_no,
                    // 'chassis_no' => $vehicle_details->chassis_no,
                    // 'manufacturing_year' => $vehicle_details->manufacturing_year,
                    // 'vehicle_category_id' => $vehicle_details->vehicle_category_id,
                    // 'vehicle_make_id' => $vehicle_details->vehicle_make_id,
                    // 'vehicle_model_id' => $vehicle_details->vehicle_model_id,
                    // 'vehicle_variant_id' => $vehicle_details->vehicle_variant_id,
                    // 'vehicle_version' => $vehicle_details->version,
                    // 'vehicle_body_type_id' => $vehicle_details->vehicle_body_type_id,
                    // 'vehicle_rim_type_id' => $vehicle_details->vehicle_rim_type_id,
                    // 'vehicle_tyre_type_id' => $vehicle_details->vehicle_tyre_type_id,
                    // 'vehicle_tyre_size_id' => $vehicle_details->vehicle_tyre_size_id,
                    // 'vehicle_wheel_type_id' => $vehicle_details->vehicle_wheel_type_id,
                    // 'vehicle_drivetrain_id' => $vehicle_details->vehicle_drivetrain_id,
                    // 'vehicle_usage_id' => $vehicle_details->vehicle_usage_id,
                    // 'fuel_type_id' => $vehicle_details->fuel_type_id,
                    // 'description' => ($vehicle_details->description != null) ? $vehicle_details->description : "",
                    // 'status' => $vehicle_details->status,
                    // 'last_update_date' => $job_date,
                    // 'last_update_jc' => $job_reference_no,
                    // 'spec_values' => $values,
                    // 'spec' => $spec,
                    // 'driver'=> $vehicle_details->driver,
                    // 'vehicle_permit_type'=>$vehicle_details->permit_type,
                    // 'fc_due'=>$vehicle_details->fc_due,
                    // 'permit_due'=>$vehicle_details->permit_due,
                    // 'tax_due'=>$vehicle_details->tax_due,
                    // 'vehicle_insurance'=>$vehicle_details->insurance,
                    // 'vehicle_insurance_due'=>$vehicle_details->premium_date,
                    // 'bank_loan'=>$vehicle_details->bank_loan,
                    // 'month_due_date'=>$vehicle_details->month_due_date,
                    // 'warranty_km'=>$vehicle_details->warranty_km,
                    // 'warranty_yrs'=>$vehicle_details->warranty_years,
                    'people' => $person
                ]
            ], $this->successStatus);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Vehicle Datas Available.',
                'data' => []
            ], $this->successStatus);
        }
    }

    public function store(Request $request, $id = null)
    {
        // try {
            Log::info("API_JobCardService->store :- try inside");
            $inputs = $request->all();

         

            Log::info("API_JobCardService->store :- data " . json_encode($inputs));

            $data = $this->jobCardServ->StoreAPI($inputs);

            Log::info("API_JobCardService->store :- return " . json_encode($data));
            return  $data;

        
            // type_id:type,

            // jobcard_no:JobCardNumber,
            // JobCardItems:SelectedJobItemList,
            // registration_id:VehicleId,
            // user_type:user_type,
            // people_id:CustomerId,
            // complaints:VehicleComplaints,
            // customer:Customer,
            // customerphone:CustomerPhone,
            // customeremail:CustomerEmail,
            // jobcard_date:JobCardDate,
            // jobcard_duedate:JobCardDueDate,
            // organization_id:organization_id,

            // $validator = Validator::make( $jobcard_inputs, [
            // 'type_id' => 'required|number',
            // 'jobcard_no' => 'required|string|max:50',
            // 'registration_id' => 'required|string',
            // 'user_type' => 'required',
            // 'people_id' => 'required',
            // 'customer' => 'required|string',
            // 'customerphone' => 'required|string',
            // ]);

            // if ($validator->fails()) {
            // $Msg=Session::flash('error', $validator->messages()->first());

            // return response()->json(['status' =>1,'message'=>$Msg], $this->successStatus);
            // }
            // $itemData=$jobcard_inputs['jobItemData'][0]['id'];;
            // return response()->json(['status' =>1, $itemData], $this->successStatus);
            // ;
            // $Data=array_merge($request->file('images_inspected'),$request->file('images_progress'),$request->file('images_ready'));
            // $count=$jobcard_inputs["CheckListData"];
            // $array= (array)$count;
            // return response()->json(['status' => 1,$jobcard_inputs], $this->successStatus);
        //     $organization_id = $jobcard_inputs['organization_id'];
        //     $person_id = $jobcard_inputs['Person_id'];

        //     $employee = HrmEmployee::select('hrm_employees.id')->where('hrm_employees.organization_id', $organization_id)
        //         ->where('hrm_employees.person_id', $person_id)
        //         ->first();

        //     // $CLDArray=array();

        //     $organization = Organization::findOrFail($organization_id);
        //     $transaction_type = AccountVoucher::where('name', 'job_card')->where('organization_id', $organization_id)->first();

        //     if ($id) {
        //         $transaction = Transaction::findOrFail($id);
        //     } else {
        //         $transaction = new Transaction();
        //     }

        //     if ($id == null) {
        //         $transaction->order_no = $jobcard_inputs['jobcard_no'];

        //         $dum_gen_no = '~';

        //         $dum_order_no = Custom::generate_accounts_number($transaction_type->name, $dum_gen_no, false, null, $organization_id);

        //         $gen_no = Custom::get_string_diff($jobcard_inputs['jobcard_no'], $dum_order_no);

        //         $transaction->gen_no = $gen_no;
        //     } 
        //     // $transaction->order_no =$jobcard_inputs['jobcard_no'];
        //     $transaction->user_type = $jobcard_inputs['user_type'];
        //     $transaction->people_id = $jobcard_inputs['people_id'];
        //     $transaction->transaction_type_id = $transaction_type->id;

        //     if (! $id) {

        //         $transaction->employee_id = $employee->id;
        //     }

        //     $transaction->name = $jobcard_inputs['customer'];
        //     $transaction->mobile = $jobcard_inputs['customerphone'];
        //     $transaction->email = $jobcard_inputs['customeremail'];
        //     $transaction->address = $jobcard_inputs['customeraddress'];
        //     $transaction->billing_name = $jobcard_inputs['customer'];
        //     $transaction->billing_mobile = $jobcard_inputs['customerphone'];
        //     $transaction->billing_email = $jobcard_inputs['customeremail'];
        //     $transaction->billing_address = $jobcard_inputs['customeraddress'];
        //     $transaction->shipping_name = $jobcard_inputs['customer'];
        //     $transaction->shipping_mobile = $jobcard_inputs['customerphone'];
        //     $transaction->shipping_email = $jobcard_inputs['customeremail'];
        //     $transaction->shipping_address = $jobcard_inputs['customeraddress'];
        //     $transaction->tax_type = 2;
        //     $transaction->notification_status = 1;
        //     $transaction->organization_id = $jobcard_inputs['organization_id'];

        //     $transaction->save();
        //     $transaction_id = $transaction->id;

        //     Custom::userby($transaction, true);

        //     if ($id) {

        //         $wms_transaction = WmsTransaction::where('transaction_id', $transaction_id)->first();
        //     } else {

        //         $wms_transaction = new WmsTransaction();
        //         // dd($wms_transaction);
        //     }

        //     $wms_transaction->transaction_id = $transaction_id;

        //     $wms_transaction->registration_id = $jobcard_inputs['registration_id'];

        //     $wms_transaction->vehicle_complaints = $jobcard_inputs['complaints'];

        //     if (! $id) {

        //         $wms_transaction->assigned_to = $employee->id;
        //     }

        //     $wms_transaction->job_date = ($jobcard_inputs['jobcard_date'] != null) ? Carbon::parse($jobcard_inputs['jobcard_date'])->format('Y-m-d') : null;

        //     $wms_transaction->job_due_date = ($jobcard_inputs['jobcard_duedate'] != null) ? Carbon::parse($jobcard_inputs['jobcard_duedate'])->format('Y-m-d') : null;
        //     $wms_transaction->job_completed_date = ($jobcard_inputs['jobcard_duedate'] != null) ? Carbon::parse($jobcard_inputs['jobcard_duedate'])->format('Y-m-d') : null;
        //     $wms_transaction->jobcard_status_id = $jobcard_inputs['jobcard_status'];
        //     $wms_transaction->organization_id = $organization_id;
        //     $wms_transaction->save();

        //     Custom::userby($wms_transaction, true);

        //     $JobCardItemArray = $jobcard_inputs['jobItemData'];

        //     if ($id) {

        //         // $wms_transaction = WmsTransaction::where('transaction_id',$transaction_id)->first();
        //         // DB::table('transaction_items')->where('transaction_items.transaction_id', $transaction_id)->delete();
        //         // $TransItem=TransactionItem::Where(['transaction_id'=>$id])->delete();
        //         // $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id', $id)->delete();

        //         foreach ($JobCardItemArray as $obj) {
        //             // code...
        //             /*
        //              * *
        //              * @method get_item_rate
        //              * Get the Price,quantity,status of the item.
        //              *
        //              */

        //             $TransItem = TransactionItem::Where([
        //                 'transaction_id' => $id,
        //                 "item_id" => $obj['id']
        //             ])->exists();
        //             $ItemData = $this->get_item_rate($organization_id, $jobcard_inputs['registration_id'], $obj['id']);

        //             if (! $TransItem) {

        //                 // dd($ItemData);

        //                 $item = new TransactionItem();
        //                 $item->transaction_id = $transaction->id;
        //                 $item->item_id = $obj['id'];

        //                 $item->parent_item_id = null;
        //                 $item->description = null;
        //                 $item->quantity = $obj['quantity'];
        //                 $item->tax_id = ($ItemData['tax_id']) ? $ItemData['tax_id'] : null;

        //                 $item->rate = ($ItemData['segment_price'] == null) ? $ItemData['base_price'] : $ItemData['segment_price'];
        //                 $amount = ($ItemData['segment_price'] == null) ? $ItemData['base_price'] : $ItemData['segment_price'];
        //                 $item->amount = $obj['quantity'] * $amount;
        //                 $item->new_selling_price = null;

        //                 $item->start_time = Carbon::now();
        //                 $item->end_time = Carbon::now();
        //                 // $item->assigned_employee_id = $employee->id;
        //                 $item->job_item_status = $obj['item_status'];
        //                 $item->save();

        //                 // START TAX CALCULATION
        //                 $total_tax = 0;
        //                 $tax_array_text = [];

        //                 $tax_group = TaxGroup::where('id', $obj['id'])->first();

        //                 if ($tax_group != null) {

        //                     $taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

        //                     $original_rate = 0;

        //                     $total_tax_value = 0;

        //                     foreach ($taxgroups as $t) {
        //                         $taxvalue = Tax::where('id', $t->tax_id)->first();
        //                         $total_tax_value += $taxvalue->value;
        //                     }
        //                     // Inclusive Tax = Rate * Tax / Tax + 100;
        //                     // Original Amount = Rate - Inclusive Tax ;
        //                     $original_rate = $item->rate - ($item->rate * $total_tax_value / ($total_tax_value + 100));

        //                     foreach ($taxgroups as $taxgroup) {

        //                         $tax_value = Tax::where('id', $taxgroup->tax_id)->first();
        //                         if ($tax_value->is_percent == 1) {
        //                             $tax_amount = ($tax_value->value / 100) * (1 * $original_rate);
        //                         } else if ($tax_value->is_percent == 0) {
        //                             $tax_amount = $tax_value->value;
        //                         }

        //                         // if($tax_amount != 0) {
        //                         // if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {
        //                         // //Sales Tax is expense, All expenses are debit
        //                         // //Vendor (Payables) gives the item, Credit the giver
        //                         // $entry[] = ['debit_ledger_id' => $tax_value->purchase_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax_amount];
        //                         // }
        //                         // else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" )
        //                         // {
        //                         // //Sales Tax is liability, Liabilities are credit
        //                         // //Customer (Receivables) gets the item, Debit the receiver
        //                         // $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $tax_value->sales_ledger_id, 'amount' => $tax_amount];
        //                         // }
        //                         // }
        //                         $total_tax += $tax_amount;
        //                         $tax_array_text[] = [
        //                             "id" => $tax_value->id,
        //                             "name" => $tax_value->display_name,
        //                             "value" => $tax_value->value,
        //                             "is_percent" => $tax_value->is_percent,
        //                             "amount" => $tax_amount
        //                         ];
        //                     }

        //                     $item->tax_id = ($item->tax_id) ? $item->tax_id : null;
        //                     $item->is_tax_percent = ($tax_value != null) ? $tax_value->is_percent : null;
        //                     if (count($tax_array_text) > 0) {
        //                         $item->tax = json_encode($tax_array_text);
        //                     }
        //                     $item->save();
        //                 }

        //                 // END TAX CALCULATION
        //             } else {

        //                 // $ItemData=$this->get_item_rate($organization_id,$jobcard_inputs['registration_id'],$obj['id']);

        //                 $UpdateTransItem = TransactionItem::Where([
        //                     'transaction_id' => $id,
        //                     "item_id" => $obj['id']
        //                 ])->firstOrFail();

        //                 $UpdateTransItem->quantity = $obj['quantity'];
        //                 // $UpdateTransItem->rate = ($ItemData['segment_price']==null)?$ItemData['base_price']:$ItemData['segment_price'];
        //                 // $amount=($ItemData['segment_price']==null)?$ItemData['base_price']:$ItemData['segment_price'];
        //                 $UpdateTransItem->amount = ($obj['quantity'] * $UpdateTransItem->rate);
        //                 $UpdateTransItem->job_item_status = $obj['item_status'];
        //                 $UpdateTransItem->save();
        //             }
        //         }
        //     } else {

        //         foreach ($JobCardItemArray as $value) {
        //             // code...

        //             $ItemData = $this->get_item_rate($organization_id, $jobcard_inputs['registration_id'], $value['id']);

        //             $item = new TransactionItem();
        //             $item->transaction_id = $transaction->id;
        //             $item->item_id = $value['id'];

        //             $item->parent_item_id = null;
        //             $item->description = null;
        //             $item->quantity = $value['quantity'];
        //             $item->tax_id = ($ItemData['tax_id']) ? $ItemData['tax_id'] : null;

        //             $item->rate = ($ItemData['segment_price'] == null) ? $ItemData['base_price'] : $ItemData['segment_price'];
        //             $amount = ($ItemData['segment_price'] == null) ? $ItemData['base_price'] : $ItemData['segment_price'];
        //             $item->amount = ($value['quantity'] * $amount);
        //             $item->new_selling_price = null;

        //             $item->start_time = Carbon::now();
        //             $item->end_time = Carbon::now();
        //             $item->assigned_employee_id = $employee->id;
        //             $item->job_item_status = $value['item_status'];
        //             $item->save();

        //             // START TAX CALCULATION
        //             $total_tax = 0;
        //             $tax_array_text = [];

        //             $tax_group = TaxGroup::where('id', $value['id'])->first();

        //             if ($tax_group != null) {

        //                 $taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

        //                 $original_rate = 0;

        //                 $total_tax_value = 0;

        //                 foreach ($taxgroups as $t) {
        //                     $taxvalue = Tax::where('id', $t->tax_id)->first();
        //                     $total_tax_value += $taxvalue->value;
        //                 }
        //                 // Inclusive Tax = Rate * Tax / Tax + 100;
        //                 // Original Amount = Rate - Inclusive Tax ;
        //                 $original_rate = $item->rate - ($item->rate * $total_tax_value / ($total_tax_value + 100));

        //                 foreach ($taxgroups as $taxgroup) {

        //                     $tax_value = Tax::where('id', $taxgroup->tax_id)->first();
        //                     if ($tax_value->is_percent == 1) {
        //                         $tax_amount = ($tax_value->value / 100) * (1 * $original_rate);
        //                     } else if ($tax_value->is_percent == 0) {
        //                         $tax_amount = $tax_value->value;
        //                     }

        //                     // if($tax_amount != 0) {
        //                     // if($transaction_type->name == "purchases" || $transaction_type->name == "credit_note") {
        //                     // //Sales Tax is expense, All expenses are debit
        //                     // //Vendor (Payables) gives the item, Credit the giver
        //                     // $entry[] = ['debit_ledger_id' => $tax_value->purchase_ledger_id, 'credit_ledger_id' => $customer_ledger, 'amount' => $tax_amount];
        //                     // }
        //                     // else if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "debit_note" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash" )
        //                     // {
        //                     // //Sales Tax is liability, Liabilities are credit
        //                     // //Customer (Receivables) gets the item, Debit the receiver
        //                     // $entry[] = ['debit_ledger_id' => $customer_ledger, 'credit_ledger_id' => $tax_value->sales_ledger_id, 'amount' => $tax_amount];
        //                     // }
        //                     // }
        //                     $total_tax += $tax_amount;
        //                     $tax_array_text[] = [
        //                         "id" => $tax_value->id,
        //                         "name" => $tax_value->display_name,
        //                         "value" => $tax_value->value,
        //                         "is_percent" => $tax_value->is_percent,
        //                         "amount" => $tax_amount
        //                     ];
        //                 }

        //                 $item->tax_id = ($item->tax_id) ? $item->tax_id : null;
        //                 $item->is_tax_percent = ($tax_value != null) ? $tax_value->is_percent : null;
        //                 if (count($tax_array_text) > 0) {
        //                     $item->tax = json_encode($tax_array_text);
        //                 }
        //                 $item->save();
        //             }

        //             // END TAX CALCULATION
        //         }

        //         // $item = new TransactionItem;
        //         // $item->transaction_id = $transaction->id;
        //         // if($method == "remote") {
        //         // $item->item_id = $itemId;
        //         // } else if($method == "store" || $method == "update" || $method == "lowstock") {
        //         // $item->item_id = $item_id[$i];
        //         // }
        //         // $item->parent_item_id = ($parent_item_id[$i]) ? $parent_item_id[$i] : null;
        //         // $item->description = ($description[$i]) ? $description[$i] : null;
        //         // $item->quantity = ($quantity[$i]) ? $quantity[$i] : null;
        //         // $item->rate = ($rate[$i]) ? $rate[$i] : null;
        //         // $item->amount = ($quantity[$i] && $rate[$i]) ? $quantity[$i]*$rate[$i] : null;
        //         // $item->new_selling_price = ($new_selling_price[$i]) ? $new_selling_price[$i] : null;

        //         // $item->start_time = ($start_time[$i]) ? $start_time[$i] : null;
        //         // $item->end_time = ($end_time[$i]) ? $end_time[$i] : null;
        //         // $item->assigned_employee_id = ($assigned_employee_id[$i]) ? $assigned_employee_id[$i] : null;
        //         // $item->job_item_status = ($job_item_status[$i]) ? $job_item_status[$i] : null;

        //         // $item->save();
        //         // dd($wms_transaction);
        //     }

        //     $Inspected_Images = $request->hasFile('images_inspected');
        //     $Progress_Images = $request->hasFile('images_progress');
        //     $Ready_Images = $request->hasFile('images_ready');

        //     if ($Inspected_Images) {
        //         $FileArray = $request->file('images_inspected');

        //         $this->wms_attachments($FileArray, $organization_id, $transaction_id, $image_category = 1);
        //     }

        //     if ($Progress_Images) {
        //         $FileArray = $request->file('images_progress');

        //         $this->wms_attachments($FileArray, $organization_id, $transaction_id, $image_category = 2);
        //     }

        //     if ($Ready_Images) {
        //         $FileArray = $request->file('images_ready');

        //         $this->wms_attachments($FileArray, $organization_id, $transaction_id, $image_category = 3);
        //     }

        //     if ($jobcard_inputs["CheckListData"]) {
        //         $CheckListInputs = json_decode($jobcard_inputs["CheckListData"], true);

        //         // if(count($CheckListInputs)>0)
        //         // {
        //         DB::table('wms_checklists')->where('transaction_id', $transaction_id)->delete();
        //         // }
        //         for ($i = 0; $i < count($CheckListInputs); $i ++) {

        //             // return response()->json(['status' => 1,'data'=>$JsonData[$i]['CheckList_id']], $this->successStatus);

        //             $Data = [
        //                 "transaction_id" => $transaction->id,
        //                 "checklist_id" => $CheckListInputs[$i]['CheckList_id'],
        //                 "checklist_notes" => $CheckListInputs[$i]['CheckList_Comments'],
        //                 "checklist_status" => 1
        //             ];
        //             // dd($Data);
        //             $WmsChecklist = WmsChecklist::updateOrCreate([
        //                 "id" => ""
        //             ], $Data);
        //             // Custom::userby($WmsChecklist, true);
        //         }
        //     }

        //     return response()->json([
        //         'status' => 1
        //     ], $this->successStatus);
        // } catch (Exception $e) {

        //     $ReturnData[] = $e->getMessage();
        //     return response()->json($ReturnData);
        // }
    }

    public function wms_attachments($attachments, $organization_id, $transaction_id, $image_category)
    {

        // dd($request->all());
        $ImgData = [];

        foreach ($attachments as $file) 
        {
            $FileName_origional = $file->getClientOriginalName();

            $dt = new DateTime();

            $file_name_array = explode(".", $file->getClientOriginalName());
            $file_name_array[0] = $file_name_array[0] . "_origional";
            $Modify_filename_origional = implode(".", $file_name_array);

            $public_path = 'wms_attachments/org_' . $organization_id . '/temp';
            $path_array = explode('/', $public_path);

            $public_path = '';

            foreach ($path_array as $p) {
                $public_path .= $p . "/";
                if (! file_exists(public_path($public_path))) {
                    mkdir(public_path($public_path), 0777, true);
                }
            }

            $file->move(public_path($public_path), $Modify_filename_origional);

            // $img=Custom::image_resize($file,800,$Modify_filename_origional,$public_path);

            $WmsAttachment = new WmsAttachment();
            $WmsAttachment->transaction_id = $transaction_id;
            $WmsAttachment->image_name = $FileName_origional;
            $WmsAttachment->image_category = $image_category;
            $WmsAttachment->image_origional_name = $file->getClientOriginalName();
            /* $WmsAttachment->thumbnail_file=$name_thumbnail; */
            $WmsAttachment->origional_file = $Modify_filename_origional;

            $WmsAttachment->organization_id = $organization_id;
            $WmsAttachment->save();

            // return response()->json(['status' =>$img], $this->successStatus);
        }
    }

    public function image_resize($image, $size, $name, $path)
    {
        try {

            $extension = $image->getClientOriginalExtension();
            $imageRealPath = $image->getRealPath();

            $dt = new DateTime();

            $img = Image::make($imageRealPath); // use this if you want facade style code
            $img->resize(intval($size), null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $path_array = explode('/', $path);

            $public_path = '';

            foreach ($path_array as $p) {
                $public_path .= $p . "/";
                if (! file_exists(public_path($public_path))) {
                    mkdir(public_path($public_path), 0777, true);
                }
            }
            $save = $img->save(public_path($path) . '/' . $name);
        } catch (Exception $e) {
            // return false;
        }
    }

    public function edit($id, $vehicle_id, $organization_id)
    {
        Log::info("API_JobCardController->edit :- Inside ");
        Log::info("API_JobCardController->edit :- id ".$id );
        Log::info("API_JobCardController->edit :- vehicle_id ".$vehicle_id );
        Log::info("API_JobCardController->edit :- org_id ".$organization_id );
        $response = $this->jobCardServ->findById_API($id, $vehicle_id, $organization_id);
        Log::info("API_JobCardController->edit :- Return ");
        return $response;
    
    }

    public function Job_invoice(Request $request)
    {
        // $transaction_type=""

        // Job Invoice 8-6-2019
        // Get Job Invoice data depending on organization id and transaction type
        //
        Log::info("JobCardController->Job_invoice :- Inside ");
        $transactions = $this->invoServ->findAll_API($request->all());
        Log::info("JobCardController->Job_invoice :- Return ");
        $message['data'] = [
            "jobInvoice" => $transactions
        ];

        return response()->json($message['data'], $this->successStatus);
    }

    public function estimation_sms(Request $request, $organization_id)
    {
        // dd($request->all());
        $sms_date = Carbon::now();
        $current_date = $sms_date->format('d-m-Y');

        $org_id = $organization_id;

        // dd($org_id);

        // $organization_id =session::get('organization_id');
        $id = $request->id;
        $sms_content_requerment = JobCard::select('vehicle_register_details.registration_no as vehicle_no', 'job_cards.name', 'job_cards.mobile','job_cards.order_no')->leftjoin('job_card_details', 'job_card_details.job_card_id', '=', 'job_cards.id')
            ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'job_card_details.registration_id')
            ->where('job_cards.id', $id)
            ->first();
        // dd($sms_content_requerment);
       // foreach ($sms_content_requerment as $key => $value) {
            $vehicle = $sms_content_requerment->vehicle_no;
            $mobile_no = $sms_content_requerment->mobile;
            $customer_name = $sms_content_requerment->name;
            $orderNo =  $sms_content_requerment->order_no;
       // }

        /*
         * Hence User can send the message to alternative mobile_number;
         * if mobile number is exist send message this number
         */
        $mobile_no = ($request->mobile_no) ? $request->mobile_no : $mobile_no;

        if ($request->mobile_no) {

            $transaction_update = JobCard::findorfail($id);
            $transaction_update->mobile = $request->mobile_no;
            $transaction_update->save();
        }

        // $transaction_last = Transaction::select('transactions.transaction_type_id', 'transactions.mobile', 'transactions.id', 'transactions.date', 'transactions.total', DB::raw('COALESCE(transactions.reference_no, "") AS reference_no'), 'transactions.order_no', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), DB::raw('IF(persons.crm_code IS NULL, businesses.bcrm_code, persons.crm_code) AS code'), DB::raw('transactions.total + wms_transactions.advance_amount as total_amount'));

        // $transaction_last->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
        // $transaction_last->leftJoin('people', function ($join) use ($organization_id) {
        //     $join->on('people.person_id', '=', 'transactions.people_id')
        //         ->where('people.organization_id', $organization_id)
        //         ->where('transactions.user_type', '0');
        // });
        // $transaction_last->leftJoin('people AS business', function ($join) use ($organization_id) {
        //     $join->on('business.business_id', '=', 'transactions.people_id')
        //         ->where('business.organization_id', $organization_id)
        //         ->where('transactions.user_type', '1');
        // });

        // $transaction_last->leftjoin('persons', 'people.person_id', '=', 'persons.id');
        // $transaction_last->leftjoin('businesses', 'business.business_id', '=', 'businesses.id');

        // $transaction_last->where('transactions.id', $request->id);
        // $transactions = $transaction_last->first();

        $transaction_type = AccountVoucher::where('name', $request->input('type'))->where('organization_id', $organization_id)->first();
        $business_name = "";
        $url = url('jc_acknowladge/');
        $sms_content = "Please note the Jobcard" . " " . $orderNo . " " . "for Vehicle " . $vehicle . " " . "dated " . $current_date . "." . "\n\n" . "Visit below link for the Status of Job. " . $url . '/' . $id . '/' . $org_id;
        $mge = "Job Card";
        $msg = $this->SmsNotificationService->save($mobile_no, $mge, $business_name, $sms_content, $org_id, "TRANSACTION");

        // $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);

        /*
         * $url=url('viewlist/');;
         * $sms_content="Click this link to approve estimation for your vehicle : ".$vehicle." ". $url . '/' . $id."\r\n".$customer_name;
         * // dd($sms_content);
         * $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, $sms_content);
         */

        if ($msg['message'] == pStatusSuccess()) {
            Log::info("TransactionController->send_sms :- Stored Success And Return");
            Custom::add_addon('sms');
            Log::info("TransactionController->send_sms :- Return ");
            return response()->json([
                'status' => 1,
                'message' => $mge . "  " . "sent to " . $mobile_no . " for approval",
                'data' => []
            ]);
        } else {
            Log::info("TransactionController->send_sms :- Stored failed And Return");

            return response()->json([
                'status' => 0,
                'message' => "failed to send sms",
                'data' => []
            ]);
        }
        // return response()->json(['status' => 1, 'message' =>$mge." "."sent to ".$mobile_no." for approval", 'data' =>[]]);
    }

  
}
