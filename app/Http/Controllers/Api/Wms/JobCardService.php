<?php
namespace App\Http\Controllers\Api\Wms;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Accounts\AccountVoucher\AccountVoucherRepository;
use App\VehicleRegisterDetail;
use App\Transaction;
use App\TransactionItem;
use App\VehicleJobcardStatus;
use Carbon\Carbon;
use App\VehicleChecklist;
use App\HrmEmployee;
use Auth;
use Session;
use DB;
use App\Custom;
use App\ShipmentMode;
// use App\Enums\JobCardStatus;
use App\GlobalItemCategory;
use App\GlobalItemCategoryType;
use App\State;
use App\Person;
use App\Business;
use App\GlobalItemMake;
use App\PersonCommunicationAddress;
use App\BusinessCommunicationAddress;
use App\WmsVehicleOrganization;
use App\People;
use App\PeopleAddress;
use App\Http\Controllers\Vehicle\VehicleService;
use App\Http\Controllers\Vehicle\VehicleRepository;
use App\Http\Controllers\People\PeopleRepository;
use App\Http\Controllers\User\UserRepository;
use App\Http\Controllers\Organization\OrganizationRepository;
use App\PeoplePersonType;
use App\PaymentMode;
use App\AccountLedger;
use File;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardItem;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardAttachment;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCard;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardDetail;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardChecklist;
use Illuminate\Support\Collection;
use App\Http\Controllers\People\CustomerDetailVO;
use App\Http\Controllers\Inventory\Item\InventoryItemRepository;
use App\Http\Controllers\Hrm\Repository\EmployeeRepository;
use App\Http\Controllers\Accounts\AccountLedger\AccountLedgerRepository;
use App\Notification\Service\SmsNotificationService;
use App\VehicleVariant;
use App\Http\Controllers\Tradewms\Jobcard\JobCardRepository;

use App\InventoryItem;
use Validator;
use App\WmsChecklist;
use App\AccountVoucher;
use App\WmsAttachment;
use App\OrgCustomValue;


use Illuminate\Contracts\Encryption\DecryptException;
/*
 * Note:
 * ?? - Null Coalescing Operator instead of ternary operator with conjuction of isset()
 *
 */
class JobCardService
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $accountRepo;

    protected $accountLedgerRepo;

    protected $jobCardRepo;

    protected $userRepo;

    protected $peopleRepo;

    protected $vehicleRepo;

    protected $employeeRepo;

    protected $itemRepo;

    protected $vehicleService;

    private $type = "job_card";

    protected $type_id;

    protected $transaction_type;

    public function __construct(AccountVoucherRepository $accountRepo, JobCardRepository $jobCardRepo, VehicleService $vehicleService, PeopleRepository $peopleRepo, VehicleRepository $vehicleRepo, OrganizationRepository $orgRepo, UserRepository $userRepo, InventoryItemRepository $itemRepo,EmployeeRepository $employeeRepo,AccountLedgerRepository $accountLedgerRepo,SmsNotificationService $SmsNotificationService)
    {
        $this->accountRepo = $accountRepo;
        $this->jobCardRepo = $jobCardRepo;
        $this->vehicleService = $vehicleService;
        $this->peopleRepo = $peopleRepo;
        $this->vehicleRepo = $vehicleRepo;
        $this->orgRepo = $orgRepo;
        $this->userRepo = $userRepo;
        $this->itemRepo = $itemRepo;
        $this->employeeRepo = $employeeRepo;
        $this->accountLedgerRepo = $accountLedgerRepo;
        $this->SmsNotificationService = $SmsNotificationService;
    }

 
    /* All get and find functions*/
    

    public function findAll_API($request)
    {
        Log::info('JobCardService->findAll_Api:- Inside');
        Log::info('JobCardService->findAll_Api:- data ' . json_encode($request->all()));
     //   $request = $request->all();

       // $request = (object) $request;
        
       $organization_id = $request->org_id;

        // $id,$organization_id
        // $organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
        $transaction_type = $this->accountRepo->findByOrgIdAndType($organization_id, $this->type);

         // get transaction type
         $request->org_id = $organization_id;
        //  $request->transaction_type = $this->type;
        //  $request->transaction_type_id = $transaction_type->id;

         $request->request->add(['transaction_type' => $this->type,'transaction_type_id'=>$transaction_type->id]); //add request
         $transactions = $this->jobCardRepo->findAll_API($request->all());

         $job_card_status = $this->jobCardRepo->findAllJobCardStatuses();

        /*
         * END Search by customer name, jobstatus,jobcard number
         */
        /* In Jobcard Status, hence we using display_name as value(alias_name).
           For the reason for using collection 
        */
        $array = [];  
        $jobCardStatus =  collect($job_card_status)->map(function($item,$key) use ($array)  {
            
            $array['value'] = $item['display_name'];
            $array['id'] = $item['id'];

            return $array;
            });

        $response['status'] = 1;
        $response['data'] = [
            "jobcardList" => $transactions,
            "jobcardStatus" => $jobCardStatus
        ];

        Log::info('JobCardService->findAll_Api:- Return ' . json_encode($response['data']));

        return $response;
    }

    public function create_API($person_id, $organization_id){

        Log::info('API_JobcardController->create_API:- Inside' );
        Log::info('API_JobcardController->create_API:- Person Id '.$person_id );
        Log::info('API_JobcardController->create_API:- Org Id '.$organization_id );
        
        $transaction_type = $this->accountRepo->findByOrgIdAndType( $organization_id, 'job_card');

      //  $job_item_status = VehicleJobItemStatus::select('name', 'id')->where('status', '1')->get();
        $jobItemStatuses = $this->jobCardRepo->findAllJobItemStatuses();
        $job_item_status = $jobItemStatuses->map->only(['id', 'name'])->all();
        $Defalut_status = $jobItemStatuses->firstWhere('name', 'Open')->id;
      //  $Defalut_status =  $jobItemStatuses->map->where('name', 'open')->first()->id;
      
        Log::info('API_JobcardController->create_API:- $job_item_status '. json_encode($Defalut_status) );
        Log::info('API_JobcardController->create_API:- $job_item_status2 '. json_encode($job_item_status) );
        

        if ($transaction_type == null) {
            return null;
        }

        
        $jobCardStatuses = $this->jobCardRepo->findAllJobCardStatuses();
        $job_card_status =  $jobCardStatuses->map->only(['id', 'name'])->all();
        Log::info('API_JobcardController->create_API:- $job_card_status '. json_encode($jobCardStatuses) );
        Log::info('API_JobcardController->create_API:- $job_card_status2 '. json_encode($job_card_status) );
        $job_status = $jobCardStatuses->firstWhere('name', 'New')->id;

        // $vehicles_register = VehicleRegisterDetail::select('registration_no', 'id')->where('organization_id', $organization_id)->orderby('registration_no','ASC')->get('registration_no', 'id');
        // $vehicles_register = VehicleRegisterDetail::leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id')->get('registration_no', 'vehicle_register_details.id');
        // ->where('wms_vehicle_organizations.organization_id', $organization_id)

        $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.registration_no as name', 'vehicle_register_details.id')->leftjoin('wms_vehicle_organizations', 'wms_vehicle_organizations.vehicle_id', '=', 'vehicle_register_details.id')
            ->where('wms_vehicle_organizations.organization_id', $organization_id)
            ->orderBy('vehicle_register_details.registration_no', 'ASC')
            ->get('registration_no', 'vehicle_register_details.id');

     //   $vehicle_check_list = VehicleChecklist::select('name', 'display_name', 'id')->get();
        $vehicle_check_list = $this->jobCardRepo->findAllCheckList();

        // dd($organization_id);
        // $items=[];
        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')->
        leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->
        leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
            ->
        where('inventory_items.organization_id', $organization_id)
            ->orderby('global_item_categories.display_name')
            ->get();

        $items->each(function ($item) {
            $item->setAppends([]);
        });

       $data = [
            'status' => 1,
            'transaction_type' => $transaction_type,
            'vehicles_register' => $vehicles_register,
            'jobcard_number' => "",
            'checkbox_list' => $vehicle_check_list,
            'jobcard_statuses' => $job_card_status,
            'c_job_status' => $job_status,
            'jobcard_items' => $items,
            'jobitem_statuses' => $job_item_status,
            'defalut_item_status' => $Defalut_status
       ];
       
       Log::info('API_JobcardController->create_API:- Return ');
       return $data;
    }


    public function findById_API($id, $vehicle_id, $organization_id){

        Log::info("JobCardService->findById_API :- Inside ");
        
        
        $vehicles_register = $this->vehicleRepo->findAllVehicleByOrgId($organization_id);
        
        $vehiclesRegisterList = $vehicles_register->pluck('registration_no', 'id')->all();
        
        Log::info(" JobCardService->findById_API :- Vehicle Register List ".json_encode($vehiclesRegisterList));
        
      // $vehicles_register->prepend('Select Vehicle', '');


        $job_item_status = $this->jobCardRepo->findAllJobItemStatuses();
        
        $Defalut_status = $job_item_status->firstWhere('name', 'Open')->id;

        
    //    $transactions = Transaction::select('name', 'user_type', 'mobile', 'address', 'order_no', 'id', 'people_id', 'transaction_type_id', 'email')->where('id', $id)
    //         ->where('organization_id', $organization_id)
    //         ->first();
        $jobCard = $this->jobCardRepo->findJobCardWithDetailById($id);
        
        Log::info(" JobCardService->findById_API :- JobCard ".json_encode($jobCard));

        // $path = url('/') . '/public/wms_attachments/org_' . $organization_id . '/temp/';
        // $img_path = "CONCAT('$path', origional_file) AS imageurl";

        // $wms_attachments_before = WmsAttachment::select(DB::raw($img_path), 'organization_id', 'image_name', 'image_origional_name', 'thumbnail_file', 'thumbnail_file', 'origional_file', 'transaction_id')->where('transaction_id', $id)
        //     ->where('image_category', 1)
        //     ->where('organization_id', $organization_id)
        //     ->pluck('imageurl');

        // $wms_attachments_progress = WmsAttachment::select(DB::raw($img_path), 'organization_id', 'image_name', 'image_origional_name', 'thumbnail_file', 'thumbnail_file', 'origional_file', 'transaction_id')->where('transaction_id', $id)
        //     ->where('image_category', 2)
        //     ->where('organization_id', $organization_id)
        //     ->pluck('imageurl');

        // $wms_attachments_after = WmsAttachment::select(DB::raw($img_path), 'organization_id', 'image_name', 'image_origional_name', 'thumbnail_file', 'thumbnail_file', 'origional_file', 'transaction_id')->where('transaction_id', $id)
        //     ->where('image_category', 3)
        //     ->where('organization_id', $organization_id)
        //     ->pluck('imageurl');

        $attachments =  $this->findJobCardImageByTID($id,$organization_id);

        
        Log::info(" JobCardService->findById_API :- Attachments ".json_encode($attachments));


        $wms_checklist_query = VehicleChecklist::select('vehicle_checklists.name', 'vehicle_checklists.id as checklist_id', 'job_card_checklists.job_card_id', 'job_card_checklists.checklist_status', 'job_card_checklists.checklist_notes', 'job_card_checklists.id as id')->LeftJoin('job_card_checklists', function ($join) use ($id) {

            $join->on('job_card_checklists.checklist_id', '=', 'vehicle_checklists.id');

            $join->where('job_card_checklists.job_card_id', '=', $id);
        });

        $wms_checklist = $wms_checklist_query->orderBy('vehicle_checklists.id', 'ASc')->get();

        // $reference_transaction_type = null;

        // $reference_transaction = Transaction::find($transactions->reference_id);

       // $vehicles_register = VehicleRegisterDetail::select('registration_no', 'id')->where('organization_id', $organization_id)->get('registration_no', 'id');

        $jobCardDetail = JobCardDetail::select('job_card_details.jobcard_status_id', 'job_card_details.job_date', 'job_card_details.job_due_date', 'job_card_details.job_completed_date', 'job_card_details.registration_id', 'job_card_details.id as wms_transcation_id', 'job_card_details.vehicle_complaints', 'vehicle_variants.vehicle_configuration')->
        leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'job_card_details.registration_id')
            ->
        leftjoin('vehicle_variants', 'vehicle_variants.id', '=', 'vehicle_register_details.vehicle_configuration_id')
            ->
        where('job_card_details.organization_id', $organization_id)
            ->
        where('job_card_details.job_card_id', $jobCard->id)
            ->
        first();

        // $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')->
        // leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        //     ->
        // leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        //     ->
        // leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        //     ->
        // where('account_vouchers.organization_id', $organization_id)
        //     ->
        // where('modules.name', 'trade_wms')
        //     ->
        // where('account_vouchers.id', $jobCard->transaction_type_id)
        //     ->
        // first();





        // $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');

        // $employees->prepend('Select Sales Person', '');

        // $address_type = BusinessAddressType::where('name', 'business')->first();

        // $business_id = Organization::find($organization_id)->business_id;

        // $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')->
        // leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        //     ->
        // leftjoin('states', 'cities.state_id', '=', 'states.id')
        //     ->
        // where('address_type', $address_type->id)
        //     ->
        // where('business_id', $business_id)
        //     ->
        // first();

        // $business_company_address = $business_communication_address->address;

        // if ($business_communication_address->address != "" && $business_communication_address->city != "") {

        //     $business_company_address .= "\n";
        // }

        // $business_company_address .= $business_communication_address->city;

        // if ($business_communication_address->city != "" && $business_communication_address->state != "") {

        //     $business_company_address .= "\n";
        // }

        // $business_company_address .= $business_communication_address->state . " " . $business_communication_address->pin;

        $vehicleConfigId = $vehicles_register->firstWhere("id",$vehicle_id)->vehicle_configuration_id;

        $vehicle_name = $this->vehicleRepo->findVehicleVariantById($vehicleConfigId);

        $job_card_status = VehicleJobcardStatus::where('status', '1')->select('name', 'id')->get();

        $JobItemList = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')->
        leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->
        leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
            ->
        where('inventory_items.organization_id', $organization_id)
            ->orderby('global_item_categories.display_name')
            ->get();

        $JobItemList->each(function ($item) {
            $item->setAppends([]);
        });

        /**
         * Transaction Items
         *
         * select('transaction_items.*', 'inventory_items.is_group','vehicle_job_item_statuses.id as item_status','inventory_item_stocks.in_stock','inventory_items.sale_price_data')
         */

        $jobCardItems = JobCardItem::select('job_card_items.item_id as id', 'job_card_items.quantity', 'job_card_items.job_item_status as item_status')->
        leftjoin('vehicle_job_item_statuses', 'vehicle_job_item_statuses.id', '=', 'job_card_items.job_item_status', 'job_card_items.new_selling_price')
            ->
        leftjoin('inventory_items', 'inventory_items.id', '=', 'job_card_items.item_id')
            ->
        leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
            ->
        where('job_card_items.job_card_id', $id)
            ->get();

        foreach ($jobCardItems as $key => $items) {
            $jobCardItems[$key]->id = (int) $items->id;
            $jobCardItems[$key]->quantity = (int) $items->quantity;
            $jobCardItems[$key]->item_status = (int) $items->item_status;
        }

   

        $TransactionData = array_merge($jobCardDetail->toArray(), $jobCard->toArray());

        $OtherDatas = [
            'vehicles_register' => $vehicles_register,
            'checkbox_list' => $wms_checklist,
            'vehicle_details' => $vehicle_name,
            'wms_attachments_before' => $attachments['beforeImg'],
            'wms_attachments_progress' => $attachments['progressImg'],
            'wms_attachments_after' => $attachments['afterImg'],
            'JobStatuses' => $job_card_status,
            'JobCardItemsList' => $JobItemList,
            'JobItems' => $jobCardItems
        ];

        $ReturnData = array_merge($TransactionData, $OtherDatas);

        return response()->json([
            'data' => $ReturnData,
            'jobitem_statuses' => $job_item_status,
            'defalut_item_status' => $Defalut_status
        ]);
    }



    public function findJobCardImageByTID($id, $organization_id = false)
    {
        if (! $organization_id) {

            $organization_id = Session::get('organization_id');
        }

        Log::info("JobCardService->findJobCardImageByTID :- Inside ");
        $wms_attachments_before = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
            ->where('image_category', 1)
            ->where('organization_id', $organization_id)
            ->get();

        $wms_attachments_progress = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
            ->where('image_category', 2)
            ->where('organization_id', $organization_id)
            ->get();

        $wms_attachments_after = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
            ->where('image_category', 3)
            ->where('organization_id', $organization_id)
            ->get();
        // $type = 1;
        // $wms_attachments_before = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type,$organization_id );

        // $type = 2;
        // $wms_attachments_progress = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type,$organization_id );

        // $type = 3;
        // $wms_attachments_after = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type,$organization_id );

        // set path to image file
        $beforeImg = collect($wms_attachments_before)->map(function ($item) use ($organization_id) {
            $item->image_url = $this->getImagePath($organization_id) . $item->origional_file;
            return $item;
        });

        $progressImg = collect($wms_attachments_progress)->map(function ($item) use ($organization_id) {
            $item->image_url = $this->getImagePath($organization_id) . $item->origional_file;
            return $item;
        });

        $afterImg = collect($wms_attachments_after)->map(function ($item) use ($organization_id) {
            $item->image_url = $this->getImagePath($organization_id) . $item->origional_file;
            return $item;
        });

        $data = [
            "beforeImg" => $beforeImg,
            "progressImg" => $progressImg,
            "afterImg" => $afterImg
        ];

        Log::info("JobCardService->findJobCardImageByTID :- Return ");

        return $data;
    
    }




    public function validationRule($data){

   // validate field based on customer type
        if($data['customer_type'] == '1')
        {
            $rules['business_name'] = 'required';
            $rules['customer_gst'] = 'required';

        }else if($data['customer_type'] == '0' ){

            $rules['first_name'] = 'required';
            $rules['last_name'] = 'required';
        }

        $rules['job_date'] = 'required';
        $rules['vehicle_registration_number'] = 'required';
        $rules['vehicle_config'] = 'required';
        $rules['vehicle_category_id'] = 'required';
        $rules['jobcard_status_id'] = 'required';
        $rules['customer_mobile_number'] = 'required';
        $rules['state'] = 'required';
        $rules['city'] = 'required';
        $rules['service_type'] = 'required';
        $rules['vehicle_mileage'] = 'required';

        // validate in new jobcard
        // Below fields might doesn't fill in existing jobcard.
        if( $data['customer_existing'] == "false"){

            // if customer type is 0 - Person, validate the field
            if($data['customer_type'] == '0' ){
                $rules['last_name'] = 'required';
            }

            $rules['customer_address'] = 'required';
            $rules['pincode'] = 'required';
        }

        // validate those fields when jobcard status is closed - 8
        if($data['jobcard_status_id'] == '8' ){

        $rules['next_visit_reason'] = 'required';
        $rules['next_visit_mileage'] = 'required';
        $rules['next_visit_date'] = 'required';

        }
        return $rules;
    }

    public function validationMessage(){
        $messages = [
            'customer_type'    => 'DETAIL: Customer: Customer Type : Select Organization or Individual(Person).',
            'business_name'    => 'DETAIL: Customer: Business Name :Is required, cannot be empty.',
            'alias_name' => 'DETAIL: Customer: Alias Name :Is required, cannot be empty.',
            'customer_gst'      => "DETAIL: Customer: GST :Is required, cannot be empty.",
            'first_name'      => 'DETAIL: Customer: First Name :Is required, cannot be empty.',
            'last_name'      => 'DETAIL: Customer: Last Name :Is required, cannot be empty.',
            'job_date'      => 'DETAIL: Job Card #:Date # :Is required, cannot be empty.',
            'vehicle_registration_number'  => 'DETAIL: Vehicle: Registration Number :Is required, cannot be empty.',
            'vehicle_config'      => 'DETAIL: Vehicle: Make/ Modal / variant / Version :Is required, cannot be empty.',
            'vehicle_category_id'      => 'DETAIL: Vehicle: Category :Is required, cannot be empty.',
            'jobcard_status_id'      => 'DETAIL: Job Card #: Job Card Status # :Is required, cannot be empty.',
            'vehicle_mileage'      => 'DETAIL: Vehicle: Odometer Mileage :Is required, cannot be empty.',
            'customer_mobile_number'      => 'DETAIL: Customer: Customer Mobile Number :Is required, cannot be empty.',
            'customer_address'      => "DETAIL: Customer: Address  :Is required, cannot be empty.",
            'state'      =>  "DETAIL: Customer: State :Is required, cannot be empty.",
            'city'      =>  "DETAIL: Customer: City :Is required, cannot be empty.",
            'pincode'      => "DETAIL: Customer: Pincode  :Is required, cannot be empty.",
            'service_type'      => "DETAIL: Job Details: Service Type :Is required, cannot be empty.",
            'next_visit_reason'      =>  "DETAIL: Follow Up Visit:Next Visit Reason :Is required, cannot be empty.",
            'next_visit_mileage'      => "DETAIL: Follow Up Visit:Next Visit - Odometer Mileage :Is required, cannot be empty.",
            'next_visit_date'      => "DETAIL: Follow Up Visit:Next Visit - Date :Is required, cannot be empty."
        ];
        return $messages;
    }

    public function validator(array $data)
    {
        $rule = $this->validationRule($data);
        return Validator::make($data, $rule);
    }

    public function convertToResponseErrorFormat($errors){
        $messages = $this->validationMessage();
       // dd($errors);
        $alerts = collect();
        collect($errors)->each(function ($error,$field) use($messages,&$alerts) {
            
            $errorMsg = $messages[$field];
            $errorFormatArray = explode(":",$errorMsg);
            $alerts->push( [
                'tab' => $errorFormatArray[0], // tab
                'sub_section' => $errorFormatArray[1], 
                'field' => $errorFormatArray[2],
                'error_message' => $errorFormatArray[3],
           ]);
        });
      //  dd($alerts);
        return  $alerts;

    }




    
    public function storeAPI($data,$id = false){
        Log::info("JobCardService->storeAPI :- Inside ");
        $dataObj = (object) $data;

        $jobcard_inputs_json = $dataObj->data;
        // return response()->json(['status' =>1,'data'=> $jobcard_inputs_json], $this->successStatus);
        // dd();
        $data = json_decode($jobcard_inputs_json, true);
        $data = (object) $data;
        
        Log::info("JobCardService->storeAPI :- Inside Data ".gettype( $data));
        
        $orgId = $data->organization_id;
        Log::info("JobCardService->storeAPI :- Inside Org Id ".$orgId);
     
        $transaction_type = $this->accountRepo->findByOrgIdAndType($orgId, $this->type);
        $vehicleId = $data->registration_id;
        $customerId = $data->people_id;
        $organizationId = $data->organization_id;

        $saveJCHeaderResponse  = $this->store_JC_Header($data,$customerId,$vehicleId,$organizationId,$transaction_type,true);
      
        if($saveJCHeaderResponse && $saveJCHeaderResponse['message'] == pStatusSuccess()){
        //return $response;
            $jobcard = $saveJCHeaderResponse['data'];

            Log::info("JobCardService->storeAPI :- Inside Data Save ".json_encode( $jobcard));
            $JobCardItemArray = $data->jobItemData;


             if ($jobcard->id) {

            // $wms_transaction = WmsTransaction::where('transaction_id',$transaction_id)->first();
            // DB::table('transaction_items')->where('transaction_items.transaction_id', $transaction_id)->delete();
            // $TransItem=TransactionItem::Where(['transaction_id'=>$id])->delete();
            // $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id', $id)->delete();

                foreach ($JobCardItemArray as $obj) {
                // code...
                /*
                 * *
                 * @method get_item_rate
                 * Get the Price,quantity,status of the item.
                 *
                 */

                $TransItem = JobCardItem::Where([
                    'job_card_id' => $jobcard->id,
                    "item_id" => $obj['id']
                ])->exists();
                if (! $TransItem) {

                    // dd($ItemData);

                    $item = new JobCardItem();
                    $item->job_card_id = $jobcard->id;
                    $item->item_id = $obj['id'];
                    $item->quantity = $obj['quantity'];
                    $item->job_item_status = $obj['item_status'];
                    $item->save();

         
                    // END TAX CALCULATION
                } else {

                    // $ItemData=$this->get_item_rate($organization_id,$jobcard_inputs['registration_id'],$obj['id']);

                    $UpdateTransItem = JobCardItem::Where([
                        'job_card_id' => $jobcard->id,
                        "item_id" => $obj['id']
                    ])->firstOrFail();

                    $UpdateTransItem->quantity = $obj['quantity'];
                    // $UpdateTransItem->rate = ($ItemData['segment_price']==null)?$ItemData['base_price']:$ItemData['segment_price'];
                    // $amount=($ItemData['segment_price']==null)?$ItemData['base_price']:$ItemData['segment_price'];
                    //$UpdateTransItem->amount = ($obj['quantity'] * $UpdateTransItem->rate);
                    $UpdateTransItem->job_item_status = $obj['item_status'];
                    $UpdateTransItem->save();
                }
            }
        } 
        

        // Save Images
     //   $Inspected_Images = $request->hasFile('images_inspected');   /* check if checklist checked or not */
        // $Inspected_Images = property_exists($dataObj, "images_inspected") ? $dataObj->images_inspected : null;
        // $Progress_Images = property_exists($dataObj, "images_progress") ? $dataObj->images_progress : null;
        // $Ready_Images = property_exists($dataObj, "images_ready") ? $dataObj->images_ready : null;

        $saveJCImages =  $this->store_JC_images($dataObj, $jobcard->id,$organizationId);

        $saveJCCheckList =  $this->store_JC_CheckList($data,  $jobcard->id);
        Log::info("JobCardService->storeAPI :- return ");
        $data = [
            "id"=> $jobcard->id,
            "orderNo" => $jobcard->order_no
        ];
        return [
            "message" => pStatusSuccess(),
            "data" => $data

        ];
    }

    
    }

  

    public function store_JC_Header($data, $customerId, $vehicleId,$organizationId = false,$transactionType = false,$isAPI = false )
    {
        Log::info("JobCardService->store_JC_Header :- Inside " . json_encode($data));

        if(!$organizationId){

            $organizationId = Session::get('organization_id');
        }

        if(!$transactionType ){

            $transactionType = $this->transaction_type;
            $transTypeId =  $this->type_id;
        }else{
            $transTypeId = $transactionType->id;
        }

      
        Log::info("JobCardService->store_JC_Header :- transTypeId " .$transTypeId);

        $newJobCard = false;

        // order no and gen no field only for create a new jobcard
        $genNo = "";
        $orderNo = "";

        if (! $data->id || ! $data->job_card_no || $data->job_card_no == 'Temp-Number') {
            $newJobCard = true;
              
        Log::info("JobCardService->store_JC_Header :- NewJobcard ");

            // $getGen_no = Custom::getLastGenNumber( $transactionType->id, $organizationId );

            // if($transactionType->restart == 0)
            // {
            // $genNo=($getGen_no)?$getGen_no:$transactionType->starting_value;
            // }
            // else
            // {
            // $genNo=($transactionType->restart == 1)?$transactionType->starting_value:$getGen_no;
            // }

            // $orderNo = Custom::generate_accounts_number($transactionType->name, $genNo, false);
        }

        $model = $this->convertToJobCardModel($data, $customerId, $orderNo, $genNo, $data->id,$organizationId,$transTypeId,$isAPI);
        // TODO : This has to be removed when accoutns/books is redesinged to use th new jobcard table
        $transactionModel = $this->convertToTransactionModel($model,$transTypeId);
        $detailModel = $this->convertToJobCardDetailModel($data, $vehicleId,$organizationId,$isAPI);
        $response = $this->jobCardRepo->saveJobCard($model, $detailModel, $transactionModel);

        // If API Call return here

        if ($newJobCard && $response['message'] == pStatusSuccess()) {
            Log::info("JobCardService->store_JC_Header :- Inside gen no update");
            // get next gen number and save it to jobcard/transaction table. increment account voucher with the next number
            $commitedModel = $response['data'];
            $commitedTransactionModel = $commitedModel->referencedIn()
                ->where('transaction_type_id',  $transTypeId)
                ->first();

            $genOrderNumber = $this->getNextGenAndOrderNumber( $transTypeId,$organizationId,$isAPI);

            Log::info("JobCardService->store_JC_Header :- genOrderNumber ".json_encode($genOrderNumber));
            if ($genOrderNumber) {
                $genNo = $genOrderNumber['nextGenNumber'];
                $orderNo = $genOrderNumber['orderNo'];

                $commitedModel->order_no = $orderNo;
                $commitedModel->gen_no = $genNo;

                $commitedTransactionModel->order_no = $orderNo;

                Log::info("JobCardService->store_JC_Header :- accountVoucherModel ".json_encode($transTypeId));
                $accountVoucherModel = $this->accountRepo->findById( $transTypeId);
                
                Log::info("JobCardService->store_JC_Header :- accountVoucherModel ".json_encode($accountVoucherModel));
                
                $accountVoucherModel->starting_value = $genNo + 1;

                $responseUpdate = $this->jobCardRepo->updateJobCardOrderNumber($commitedModel, $commitedTransactionModel, $accountVoucherModel);

                if ($responseUpdate['message'] == pStatusFailed()) {
                    return [
                        'message' => pStatusFailed(),
                        'error' => 'ORDER_NO_FAILED',
                        'error_message' => 'Job card number generation failed, try to save again. If problem persist, please contact Propel customer care.',
                        'data' => $response['data']
                    ];
                }
            } else {
                return [
                    'message' => pStatusFailed(),
                    'error' => 'ORDER_NO_FAILED',
                    'error_message' => 'Job card number generation failed, try to save again. If problem persist, please contact Propel customer care.',
                    'data' => $response['data']
                ];
            }
        }
        Log::info("JobCardService->store_JC_Header :- return");
        return $response;
    }

    public function store_JC_Items($data, $jobCardId)
    {
        Log::info("JobCardService->store_JC_Items :- Inside");

        // check if transaction item exist or not in input data
        $jobCardItems = property_exists($data, "transaction_item") ? $data->transaction_item : null;

        // if exist get the all item id from collection
        if ($jobCardItems && count($jobCardItems) > 0) {
            $itemIdArray = collect($jobCardItems)->pluck('item_id');

            // delete if item is not exist in transaction item input array
            $responseDeletedItem = $this->jobCardRepo->destroyUnSelectedJobCardItems($jobCardId, $itemIdArray);

            $response = $this->jobCardRepo->saveJobCardItem($jobCardItems, $jobCardId);

            if ($response['message'] == pStatusFailed()) {
                return $response;
            } else {
                return $response;
            }
        } else {
            $responseDeletedItem = $this->jobCardRepo->destroyUnSelectedJobCardItems($jobCardId);
            // if item doesn't exist, return success
            return [
                "message" => pStatusSuccess(),
                'data' => ''
            ];
        }
    }

    public function store_JC_CheckList($data, $jobCardId)
    {
        Log::info("JobCardService->store_JC_CheckList :- Inside");

        /* check if checklist checked or not */
        $checkListData = json_decode($data->CheckListData, true);


        if (count($checkListData) > 0) {

             $selectedCheckList = collect($checkListData)->pluck('CheckList_id');

            // get count of unchecked data from table
            $unCheckedData = $this->jobCardRepo->findUnCheckedCLData($selectedCheckList, $jobCardId);

            if (count($unCheckedData) > 0) {
                $unCheckedIdArray = $unCheckedData->pluck('id');
                $unCheckedData = JobCardChecklist::where('job_card_id', $jobCardId)->whereNotIn('checklist_id', $unCheckedIdArray);
                // delete unchecked data from table
                $responseDeletedData = $this->jobCardRepo->destroyCheckListData($unCheckedData);

                if ($responseDeletedData['message'] == pStatusFailed()) {
                    return $responseDeletedData;
                }
            }
            $dateTimeString = Carbon::now()->toDateTimeString();
            // checked id and note key value pair for find changes in existing notes in table
            foreach ($checkListData as $key => $value) {
                $value = (object) $value;
                Log::info('JobCardRepository->checklist Save :- ' . json_encode($value));
                    $checkListId = $value->CheckList_id;

                    $data = JobCardChecklist::updateOrCreate([
                        'job_card_id' => $jobCardId,
                        'checklist_id' => $checkListId
                    ], [
                        'checklist_notes' => $value->CheckList_Comments,
                        'checklist_status' => 1,
                        'created_by' => Auth::user()->id,
                        'last_modified_by' => Auth::user()->id,
                        'created_at' => $dateTimeString,
                        'updated_at' => $dateTimeString


                    ]);
              
            }

            return [
                'message' => pStatusSuccess()
            ];
        } else {
            $deletedData = JobCardChecklist::where('job_card_id', $jobCardId);
            // delete unchecked data from table
            $responseDeletedData = $this->jobCardRepo->destroyCheckListData($deletedData);

            if ($responseDeletedData['message'] == pStatusFailed()) {
                return $responseDeletedData;
            } else if ($responseDeletedData['message'] == pStatusSuccess()) {
                return $responseDeletedData;
            }
        }
    }

    public function store_JC_images($data, $jobCardId,$jobCard_OrgId)
    {
        Log::info("JobCardService->store_JC_images :- Inside");
        
        Log::info("JobCardService->store_JC_images :- data".json_encode($data));
        /* check if before,progress,after images exist or not */
        $beforeImage = property_exists($data, "images_inspected") ? $data->images_inspected : null;
        $progressImage = property_exists($data, "images_progress") ? $data->images_progress : NULL;
        $afterImage = property_exists($data, "images_ready") ? $data->images_ready : NULL;
        
        $jobcardAttachments = [];
        if ($beforeImage || $progressImage || $afterImage) {

            Log::info('JobCardService->store_JC_images :-beforeImg ' . json_encode($beforeImage));
            Log::info('JobCardService->store_JC_images :-ProgressImg ' . json_encode($progressImage));
            Log::info('JobCardService->store_JC_images :-AfterImg ' . json_encode($afterImage));

            if ($beforeImage && count($beforeImage) > 0) {

                // get image sequuence by transaction id and type id
                $imgSequence = $this->jobCardRepo->findLastJCImageSequence($jobCardId, 1);

                /* TODO: After rename the column, change the field value */
                // sequence number - if data exist and it should be a numberic , it will be set to the sequence number otherwise 0
                $imgSequenceNo = 0;
                if ($imgSequence) {
                    if ($imgSequence->uuid && is_numeric($imgSequence->uuid)) {
                        $imgSequenceNo = $imgSequence->uuid;
                    }
                }
                
                collect($beforeImage)->each(function ($item) use ($jobCardId, &$imgSequenceNo, &$jobcardAttachments,$jobCard_OrgId) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    $imgData = $this->imgArrayFormat($item, $jobCardId,$jobCard_OrgId, $imgCategory = 1, $imgSequenceNo);
                    if ($imgData) {
                        $jobcardAttachments[] = $imgData;
                    }
                });
            }

            if ($progressImage && count($progressImage) > 0) {

                // get image sequuence by transaction id and type id
                $imgSequence = $this->jobCardRepo->findLastJCImageSequence($jobCardId, 2);

                /* TODO: After rename the column, change the field value */
                // sequence number - if data exist and it should be a numberic , it will be set to the sequence number otherwise 0
                $imgSequenceNo = 0;
                if ($imgSequence) {
                    if ($imgSequence->uuid && is_numeric($imgSequence->uuid)) {
                        $imgSequenceNo = $imgSequence->uuid;
                    }
                }

                collect($progressImage)->each(function ($item, $key) use ($jobCardId, &$jobcardAttachments, &$imgSequenceNo,$jobCard_OrgId) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    $imgData = $this->imgArrayFormat($item, $jobCardId,$jobCard_OrgId, $imgCategory = 2, $imgSequenceNo);
                    if ($imgData) {
                        $jobcardAttachments[] = $imgData;
                    }
                });
            }

            if ($afterImage && count($afterImage) > 0) {

                // get image sequuence by transaction id and type id
                $imgSequence = $this->jobCardRepo->findLastJCImageSequence($jobCardId, 3);

                /* TODO: After rename the column, change the field value */
                // sequence number - if data exist and it should be a numberic , it will be set to the sequence number otherwise 0
                $imgSequenceNo = 0;
                if ($imgSequence) {
                    if ($imgSequence->uuid && is_numeric($imgSequence->uuid)) {
                        $imgSequenceNo = $imgSequence->uuid;
                    }
                }

                collect($afterImage)->each(function ($item, $key) use ($jobCardId, &$jobcardAttachments, &$imgSequenceNo,$jobCard_OrgId) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    // upload image file
                    $imgData = $this->imgArrayFormat($item, $jobCardId,$jobCard_OrgId, $imgCategory = 3, $imgSequenceNo);
                    if ($imgData) {
                        $jobcardAttachments[] = $imgData;
                    }
                });
            }
        }

        if ($jobcardAttachments && count($jobcardAttachments) > 0) {
            $reponse = $this->jobCardRepo->saveAttachementImages($jobcardAttachments);
            return $reponse;
        } else {
            return [
                'message' => pStatusSuccess()
            ];
        }
    }


    public function destroyJobCardImage($id)
    {
        Log::info("JobCardService->destroyJobCardImage :- Inside ");

        $data = $this->jobCardRepo->findJobcardImageById($id);
        $reponseData = "";

        if ($data) {
            $imageName = $data->origional_file;
            $response = $this->jobCardRepo->destroyAttachment($data);

            if ($response['message'] == pStatusSuccess()) {

                $reponseData = $response;

                $organization_id = Session::get('organization_id');

                $path = public_path().'/wms_attachments/org_' . $organization_id . '/temp/' . $imageName;

                Log::info("JobCardService->destroyJobCardImage :- Img " . $path);
                
                if (File::delete($path)) {
          
                    $reponseData = [
                        "message" => pStatusSuccess(),
                        "data" => "Image Deleted successfully!"
                    ];
                } else {

                    $reponseData = [
                        "message" => pStatusSuccess(),
                        "data" => "Image Deleted successfully!"
                    ];
                }
            } else {
                $reponseData = $response;
            }
        } else {
            $reponseData = [
                "message" => pStatusFailed(),
                "data" => "Image doesn't exist"
            ];
        }

        Log::info("JobCardService->destroyJobCardImage :- return ");
        return response()->json($reponseData);
    }

    /* All Util functions */
    public function getImagePath($organization_id)
    {
         
        // {{asset('public/wms_attachments/org_'.$organization_id).'/temp/'}};
        return asset('public/wms_attachments/org_' . $organization_id) . '/temp/';
    }

    public function getNextGenAndOrderNumber($type_id = false, $orgId = false, $isAPI = false)
    {
        Log::info("JobCardService->getNextGenNumber :- Inside ");
        if(!$type_id){
            $type_id = $this->type_id;
        }

        $accountVoucher = $this->accountRepo->findById($type_id);
        $nextGenNumber = false;
        $orderNo = "";

        if ($accountVoucher) {
            $nextGenNumber = $accountVoucher->starting_value;

            if (! $nextGenNumber || $nextGenNumber == 0) {
                $nextGenNumber = 1;
            }

            if($isAPI){

                $orderNo = Custom::generate_accounts_number($accountVoucher->name, $nextGenNumber, false,  null, $orgId);
            }else{
                
                $orderNo = Custom::generate_accounts_number($accountVoucher->name, $nextGenNumber, false);
            }
        }

        Log::info("JobCardService->getNextGenNumber :- Return ");
        return [
            'nextGenNumber' => $nextGenNumber,
            'orderNo' => $orderNo
        ];
    }

    public function imgArrayFormat($item, $transactionId,$orgId, $imgType, $imgSequence)
    {
        $path = jobCardImagePath($orgId);
        $extension = $item->getClientOriginalExtension();

        // date convert to timestamp
        $current_timestamp = Carbon::now()->timestamp;

        $fileName = "";
        if ($imgType == 1) {
            $fileName = "JC_" . $transactionId . "_before_img_" . $imgSequence . "_" . $current_timestamp . "." . $extension;
        } else if ($imgType == 2) {
            $fileName = "JC_" . $transactionId . "_progress_img_" . $imgSequence . "_" . $current_timestamp . "." . $extension;
        } else if ($imgType == 3) {
            $fileName = "JC_" . $transactionId . "_after_img_" . $imgSequence . "_" . $current_timestamp . "." . $extension;
        }

        $img = Custom::image_resize($item, 800, $fileName, $path);
        $dateTimeString = Carbon::now()->toDateTimeString();
        // if image upload successfully only save
        if ($img) {

            $imgData['job_card_id'] = $transactionId;
            $imgData['image_category'] = $imgType;
            $imgData['origional_file'] = $fileName;
            $imgData['uuid'] = $imgSequence;
            $imgData['organization_id'] = $orgId;
            $imgData['created_by'] = Auth::user()->id;
            $imgData['last_modified_by'] = Auth::user()->id;
            $imgData['created_at'] = $dateTimeString;
            $imgData['updated_at'] = $dateTimeString;
            return $imgData;
        } else {
            return false;
        }
    }

    

    public function convertToJobCardModel($data, $customerId, $orderNo, $genNo, $id = false, $orgId =  false, $transactionTypeId = false, $isAPI = false)
    {
        Log::info("JobCardService->convertToJobCardModel :- Inside ");

        if(! $orgId){
            $orgId = Session::get('organization_id');
        } 

        if(!$transactionTypeId){
            $transactionTypeId = $this->type_id;
        }


        if($isAPI){
            $customerName = $data->customer;
        }else{
            $customerName = $data->customer_type == 0 ? $data->first_name : $data->business_name;
        }


        if ($id) {
            $model = JobCard::findorfail($id);
        } else {
            // order no, generation number only for new transaction
            $model = new JobCard();
            // $model->order_no = $orderNo;
            // $model->gen_no = $genNo;
            $model->order_no = 'Temp-Number';
            $model->gen_no = 999999;
        }

        // sample code to use in estimation and invoice creation process to save the originated from field
        /*
         * $jc_model = JobCard::findorfail($id);
         * $jc_model->referencedIn()->save($model);
         */

        $model->employee_id = isset($data->employee_id)?$data->employee_id:null;
        $model->date = $data->job_date;

        /* TODO:These information needed here */
        $model->name = $customerName;
        $model->mobile = $data->customer_mobile_number;
        $model->email = $data->customer_email;
        $model->gst = isset($data->customer_gst) ? $data->customer_gst : Null; // refer Notes : find ??
        $model->address = $data->customer_address;
        $model->pin = isset($data->pincode)?$data->pincode:null;
        $model->shipment_mode_id = isset($data->shipment_mode_id)?$data->shipment_mode_id:null;

        // Not a API
        if(! $isAPI ){
                /* TODO: Add State,City,GST in Transaction Table */
                /* Billing Information */
               

                $model->billing_name = $data->billing_name;
                $model->billing_mobile = $data->billing_mobile;
                $model->billing_email = $data->billing_email;
                $model->billing_gst = $data->billing_gst;
                $model->billing_address = $data->billing_address;
                $model->billing_city_id = isset($data->billing_city) && $data->billing_city ? $data->billing_city : null;
                $model->billing_pincode = $data->billing_pincode;

                  /* TODO: Add State,City in Transaction Table */
                /* Shipping information */
                $model->shipping_name = $data->shipping_name;
                $model->shipping_mobile = $data->shipping_mobile;
                $model->shipping_email = $data->shipping_email;
                $model->shipping_address = $data->shipping_address;
                $model->shipping_city_id = isset($data->shipping_city) && $data->shipping_city ? $data->shipping_city : null;
                $model->shipping_pincode = $data->shipping_pincode;
        
                // Api For New Jobcard
        }else if(  $isAPI  && !$data->id){

                 if($data->customer_type == 0 ){
                     $commDetail = $this->userRepo->findBusinessCommunicationByBusinessId($data->people_id);
                     
                 }else if($data->customer_type == 1){
                    $commDetail = $this->userRepo->findBusinessCommunicationByBusinessId($data->people_id);
                 }
                
                 Log::info("JobCardService->convertToJobCardModel :- Address log ".json_encode($commDetail));

                /* TODO: Add State,City,GST in Transaction Table */
                /* Billing Information */
                
                $model->billing_name = $customerName;
                $model->billing_mobile = $data->customer_mobile_number;
                $model->billing_email = $data->customer_email;
                $model->billing_address = $commDetail? $commDetail->address:null;
                $model->billing_city_id = $commDetail? $commDetail->city_id:null;
                $model->billing_pincode = $commDetail? $commDetail->pin:null;

                  /* TODO: Add State,City in Transaction Table */
                /* Shipping information */
                $model->shipping_name = $customerName;
                $model->shipping_mobile = $data->customer_mobile_number;
                $model->shipping_email = $data->customer_email;
                $model->shipping_address = $commDetail? $commDetail->address:null;
                $model->shipping_city_id = $commDetail? $commDetail->city_id:null;
                $model->shipping_pincode = $commDetail? $commDetail->pin:null;
        }
      

        /* */
        $model->date = ($data->job_date != null) ? Carbon::parse($data->job_date)->format('Y-m-d') : null;
        $model->transaction_type_id = $transactionTypeId;
        $model->user_type = $data->customer_type;
        $model->people_id = $customerId;

        $model->organization_id =  $orgId;
        $model->notification_status = 1;

        Log::info("JobCardService->convertToJobCardModel :- Return ");
        return $model;
        // $transaction->save();
        // Custom::userby($transaction, true);
    }

    // Need to have a dummy entry in transaction table for advance payment to work.
    // TODO : remove this when we correct Accounts/Books to use the new job card tables
    public function convertToTransactionModel($jobCardModel, $transTypeId = false)
    {
        Log::info("JobCardService->convertToTransactionModel :- Inside ");

        if(!$transTypeId){
            $transTypeId = $this->type_id;
        }

        if ($jobCardModel->id) {
            $model = $jobCardModel->referencedIn()
                ->where('transaction_type_id', $transTypeId)
                ->first();
            Log::info("JobCardService->convertToTransactionModel :- Inside " . json_encode($model));

            if ($model && $model->id) {
                $model = Transaction::findorfail($model->id);
            } else {
                $model = new Transaction();
            }
        } else {
            $model = new Transaction();
        }

        // sample code to use in estimation and invoice creation process to save the originated from field
        /*
         * $jc_model = JobCard::findorfail($id);
         * $jc_model->referencedIn()->save($model);
         */

        $model->order_no = $jobCardModel->order_no;
        $model->date = $jobCardModel->date;
        $model->transaction_type_id = $jobCardModel->transaction_type_id;
        $model->user_type = $jobCardModel->user_type;
        $model->people_id = $jobCardModel->people_id;
        $model->organization_id = $jobCardModel->organization_id;
        $model->notification_status = $jobCardModel->notification_status;

        Log::info("JobCardService->convertToTransactionModel :- Return ");
        return $model;
    }

    public function convertToJobCardDetailModel($data, $vehicleId, $orgId = false,$isAPI = false)
    {
        Log::info("JobCardService->convertToJobCardDetailModel :- Inside ");

        if(!$orgId){
            $orgId = Session::get('organization_id');
        }
        if ($data->id) {
            $model = JobCardDetail::where('job_card_id', $data->id)->first();
        } else {
            $model = new JobCardDetail();
            // $model->transaction_id = $transaction->id;
        }
        $model->registration_id = $vehicleId;
        
        if(!$isAPI){
            
        $model->engine_no = $data->engine_number;
        $model->chasis_no = $data->chassis_number;
        
        $model->service_type = $data->service_type;
        $model->assigned_to = $data->employee_id;
        $model->job_completed_date = ($data->job_completed_date != null) ? Carbon::parse($data->job_completed_date)->format('Y-m-d') : null;
        $model->vehicle_last_visit = $data->last_visit;
        $model->vehicle_last_job = $data->vehicle_last_job;
        $model->vehicle_mileage = $data->vehicle_mileage;
        $model->next_visit_mileage = $data->next_visit_mileage;
        $model->vehicle_next_visit = ($data->next_visit_date != null) ? Carbon::parse($data->next_visit_date)->format('Y-m-d') : null;
        $model->vehicle_next_visit_reason = $data->next_visit_reason;
        $model->vehicle_note = $data->vehicle_note;
        
        $model->driver = $data->driver;
        $model->driver_contact = $data->driver_contact;

        }

        $model->jobcard_status_id = $data->jobcard_status_id;
        $model->job_date = ($data->job_date != null) ? Carbon::parse($data->job_date)->format('Y-m-d') : null;
        $model->job_due_date = ($data->job_due_date != null) ? Carbon::parse($data->job_due_date)->format('Y-m-d') : null;
     
        $model->vehicle_complaints = $data->complaint;
        $model->organization_id = $orgId;
        // $model->save();

        Log::info("JobCardService->convertToJobCardDetailModel :- Model " . json_encode($model));
        Log::info("JobCardService->convertToJobCardDetailModel :- Return ");
        return $model;
    }

    public function convertToTransactionItem($data, $type, $transactionId)
    {
        // $item_goods = new TransactionItem;
        // $item_goods->item_id = $item_goods_array[$i];
        // $item_goods->quantity = ($quantity[$i]) ? $quantity[$i] : 0.00;
        // $item_goods->transaction_id = $transaction->id;
    }

   

  }