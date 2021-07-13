<?php
namespace App\Http\Controllers\Tradewms\Jobcard;

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

    protected function setTransactionType()
    {
//         $this->type_id = Session::get('jc_type_id');
//         Log::info('JobCardService->setTransactionType:- jc_type_id ' . $this->type_id);
//         Log::info('JobCardService->setTransactionType:- orgid ' . Session::get('organization_id'));
//         if (! $this->type_id) {
//             // get transaction type
//             $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $this->type);
//             Log::info('JobCardService->setTransactionType:- put je_type_id ' . json_encode($this->transaction_type));
//             if ($this->transaction_type) {
//                 Session::put('jc_type_id', $this->transaction_type->id);
//                 $this->type_id = Session::get('jc_type_id');
//             }
//             Log::info('JobCardService->setTransactionType:- put jc_type_id ' . $this->type_id);
//         } else {
//             $this->transaction_type = $this->accountRepo->findById($this->type_id);
//         }
        if (! $this->type_id) {
            $this->transaction_type = $this->accountRepo->findByOrgIdAndType(Session::get('organization_id'), $this->type);
            Log::info('JobCardService->setTransactionType:- put jc_type_id ' . json_encode($this->transaction_type));
            $this->type_id = $this->transaction_type->id;
        }
    }

    /* All get and find functions*/
    
    public function findAll($request)
    {
        $this->setTransactionType();

        $request = (object) $request;
        Log::info('JobCardService->findAll:- Inside' . $this->type_id);
        Log::info('JobCardService->findAll:- data ' . json_encode($request));
        // Array convert to object
        // request from data table pagination

        $organization_id = Session::get('organization_id');

        //dd(Session::all());

        // get transaction type
        // $transaction_type = $this->accountRepo->findByOrgIdAndType($organization_id, $this->type);

        if ($this->type_id) {

            $fromDate = '';
            $toDate = '';
            $currentDate = Carbon::now()->toDateString();
            // toDate is empty in request.we set the Today date as a toDate
            if ($request->from_date) {

                $fromDate = $request->from_date;
                if ($request->to_date) {
                    $toDate = $request->to_date;
                } else {
                    $toDate = $currentDate;
                }
            } else if ($request->to_date) {

                $fromDate = Carbon::parse($request->to_date)->subDays(1)->toDateString();
                $toDate = $request->to_date;
            } else {
                $fromDate = Carbon::now()->subDay(1)->toDateString();
                $toDate = $currentDate;
            }

            // from date and to date are adding in object
            if ($fromDate && $toDate) {

                $request->qfrom_date = $fromDate;
                $request->qto_date = $toDate;
            }

            // get transaction type
            $request->org_id = $organization_id;
            $request->transaction_type = $this->type;
            $request->transaction_type_id = $this->type_id;
            /* get result from repo and convertToVO */
            $transactions = $this->jobCardRepo->findAll($request);
            // dd($transactions);
            $entities = collect($transactions)->map(function ($transaction) {
                $ackURL = generateEncryptedURL(url('job_card_acknowledgement/'),$transaction->id);
                // Log::info('JobCardService->findAll:- Iterate ' .json_encode($this->convertToVO($transaction)));
                return $this->convertToVO($transaction,$ackURL);
            });
        }
        Log::info('JobCardService->findAll:- Return ' . json_encode($entities));
        /* data convert the format of datatable server side rendering */
        $response = datatables()->of($entities)
            ->with([
            'from_date' => $request->qfrom_date,
            'to_date' => $request->qto_date
        ])
            ->make(true);
        // $response->original->input->from_date = $request->qfrom_date;
        // $response->original->input->to_date = $request->qto_date;
        // Log::info('JobCardService->findAll:- Response ' . json_encode($response->input));
        return $response;
    }

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

    public function getMasterData($id)
    {
        $this->setTransactionType();
        // dd($withItems);
        Log::info("JobCardService->getMasterData :- Inside ");
        $organization_id = Session::get('organization_id');

        $vehicleCheckListData = $this->jobCardRepo->findAllCheckList($id);

        $vehicleSeviceTypeArray = $this->vehicleRepo->findVehicleServiceType($isPluck = true);
       // $vehicleSeviceTypeArray = ServiceType::where('status', '1')->orderBy('name')->pluck('display_name', 'id');
        // $vehicleSeviceTypeArray->prepend('Select Service type', '');

     //   $shipmentModeArray = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipmentModeArray = $this->jobCardRepo->findAllShipmentMode($isPluck = true);
        // $shipmentModeArray->prepend('Select Shipment Mode', '');

      //  $employeesData = HrmEmployee::where('organization_id', $organization_id)->get();
        $employeesData =$this->employeeRepo->findAllEmployee($organization_id);
        // $employees->prepend('Select Employee', '');

        // convert to array
        $employees = [];
        $employeeId = '';
        $authUserPersonId = Auth::user()->person_id;

        foreach ($employeesData as $value) {
            $empId = $value->id;
            $person_id = $value->person_id;
            $employees[$empId] = $value->full_name;

            // get active employee id
            if ($authUserPersonId == $person_id) {
                $employeeId = $empId;
            }
        }

        /* Item filter */
        // $itemCategories = GlobalItemCategory::distinct()->select('global_item_categories.id', 'global_item_categories.name', 'global_item_categories.display_name AS Itemcategory')
        //     ->join('global_item_models', 'global_item_categories.id', '=', 'global_item_models.category_id')
        //     ->join('inventory_items', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        //     ->where('inventory_items.organization_id', $organization_id)
        //     ->where('inventory_items.status', 1)
        //     ->orderby('global_item_categories.display_name')
        //     ->get();
        $itemCategories = $this->itemRepo->findAllItemCategories();

        $itemCategoryTypes = $this->itemRepo->findAllItemCategoryType();

        $itemMakes = $this->itemRepo->findAllItemMake();

        // $itemCategoryTypes = GlobalItemCategoryType::distinct()->select('global_item_category_types.id', 'global_item_category_types.name', 'global_item_category_types.display_name')
        //     ->orderby('global_item_category_types.display_name')
        //     ->get();

        // $itemMakes = GlobalItemMake::distinct()->select('global_item_makes.id', 'global_item_makes.name', 'global_item_makes.display_name AS ItemMake')
        //     ->join('global_item_models', 'global_item_makes.id', '=', 'global_item_models.make_id')
        //     ->join('inventory_items', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        //     ->where('inventory_items.organization_id', $organization_id)
        //     ->where('inventory_items.status', 1)
        //     ->orderby('global_item_makes.display_name');
        // Log::info('JobCardService->getMasterData: items query - ' . $itemMakes->toSql());
        // $itemMakes = $itemMakes->get();
        /* Item filter END */

        $jobCardStatuses = $this->jobCardRepo->findAllJobCardStatuses($isPluck = true);
        //$jobCardStatuses = VehicleJobcardStatus::pluck('display_name', 'id');

        $jobItemStatus = $this->jobCardRepo->findAllJobItemStatuses($isPluck = true);

        /* JOBCARD Master Data */
        if ($id) {
            //$transactionData = JobCard::with('jobCardDetail')->where('id', $id)->first();
            $transactionData = $this->jobCardRepo->findJobCardWithDetailById($id);
            $vehicleServiceId = $transactionData->jobCardDetail->service_type;
            $shipmentModeId = $transactionData->shipment_mode_id;
        } else {
            $vehicleServiceId = array_search('Paid Service', $vehicleSeviceTypeArray->toArray());
            $shipmentModeId = "";
        }

        $data = [
            "vehicleSevices" => $vehicleSeviceTypeArray,
            "vehicleServiceId" => $vehicleServiceId,
            "shipmentModes" => $shipmentModeArray,
            "shipmentModeId" => $shipmentModeId,
            "vehicleCheckListData" => $vehicleCheckListData,
            "employees" => $employees,
            "employeeId" => $employeeId,
            "itemCategories" => $itemCategories,
            "itemCategoryTypes" => $itemCategoryTypes,
            "itemMakes" => $itemMakes,
            "jobCardStatuses" => $jobCardStatuses,
            "jobItemStatus" => $jobItemStatus
        ];

        Log::info("JobCardService->getMasterData :- Return ");

        return [
            "status" => "SUCCESS",
            "data" => $data
        ];
    }

    public function findCustomerByMobile($request)
    {
        $this->setTransactionType();
        // array convert to object
        $request = (object) $request;
        Log::info('JobCardService->findCustomerByMobile:-Inside ');
        $organization_id = Session::get('organization_id');
        Log::info('JobCardService->findCustomerByMobile:-org Id ' . $organization_id);
      //  $states = State::where('country_id', 101)->pluck('name', 'id');
        $states = Custom::getStateByCountryId(false,$isPluck = true);

        $customerType = $request->customer_type;
        if ($request->customer_type == "0") {
            Log::info('JobCardService->findCustomerByMobile:-Inside If 0(User)');

            // IN the below query result,
            // 1. if organization_id is null, means this user is not part of org.
            // 2. if person_type_id is null or not equal = 2, the user is part of org but not as customer(2), might be a vendor(3)/employee(1)/trade-agent(4)
            // $customerDetails = Person::select('persons.*','person_communication_addresses.mobile_no','person_communication_addresses.email_address','person_communication_addresses.address','person_communication_addresses.pin','person_communication_addresses.city_id','cities.state_id','people.id AS people_id','people.organization_id','people_person_types.person_type_id AS cust_type_id','account_person_types.display_name AS cust_type')
            // ->leftjoin('person_communication_addresses','persons.id','=','person_communication_addresses.person_id')
            // ->leftjoin('cities','person_communication_addresses.city_id','=','cities.id')
            $mobileNumber = $request->mobile_number;
           
            $customerDetails = $this->userRepo->findPersonAssocDetailByMobile($mobileNumber);

            //1. if organization_id is null, means this user is not part of org.
            // 2. if person_type_id is null or not equal = 2, the user is part of org but not as customer(2), might be a vendor(3)/employee(1)/trade-agent(4)
            // if user is already exist as customer only fetch that specific data and push to collection, otherwise pull all data into collection
            // cusomerData - associatedTypeId - represent person type id -  (2 - customer)
            $customerData = new collection();

            Log::info('JobCardService->findCustomerByMobile:-count' . count($customerDetails));
            if (count($customerDetails) > 0) {
                $customerDetails->each(function ($model) use ($states, $customerType, $organization_id,&$customerData) {
                    Log::info('JobCardService->findCustomerByMobile:-data' . json_encode($model));
                    $activeStateDropdown = (object) [
                        $model->address->city->state_id => $states[$model->address->city->state_id]
                    ];
                    $activeCityDropdown = (object) [
                        $model->address->city->id => $model->address->city->name
                    ];
                    // dd($activeStateDropdown);
                    Log::info('JobCardService->findCustomerByMobile:-activeStateDropdown' . json_encode($activeStateDropdown));
                    if (count($model->personOrgAssoication) > 0) {
                        foreach ($model->personOrgAssoication as $people) {

                         //   Log::info('JobCardService->findCustomerByMobile:-PeoplePersonType' . json_encode($people->PeoplePersonType));
                            if (count($people->PeoplePersonType) > 0) {
                                foreach ($people->PeoplePersonType as $assoicatedType) {
                                    if(($people && $organization_id ==  $people->organization_id) && $assoicatedType->person_type_id == "2"){

                                        $customerData = new collection();
                                        $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, $assoicatedType, $activeStateDropdown, $activeCityDropdown));
                                    
                                    }else if(!$customerData->contains('associatedTypeId',2) ){
                                        $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, $assoicatedType, $activeStateDropdown, $activeCityDropdown));
                             
                                    }

                                   
                                }
                            } else if(!$customerData->contains('associatedTypeId',2)){
                              
                                
                                $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, false, $activeStateDropdown, $activeCityDropdown));
                            }
                        }
                    } else  if(!$customerData->contains('associatedTypeId',2)){
                        $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people = false, false, $activeStateDropdown, $activeCityDropdown));
                    }
                });
                $customerData = $customerData->filter()->all();
            } else {
                $customerData = [];
            }
            Log::info('JobCardService->findCustomerByMobile:-End ');
            return [
                'status' => pStatusSuccess(),
                'data' => [
                    'customerDetail' => $customerData,
                    'states' => $states
                ]
            ];
        }
        if ($request->customer_type == "1") {
            Log::info('JobCardService->findCustomerByMobile:-Inside If 1 (business)');
            // $customerDetails = Business::select('businesses.id','businesses.business_name as name','businesses.business_name','business_communication_addresses.mobile_no','businesses.gst','business_communication_addresses.pin','business_communication_addresses.address','business_communication_addresses.email_address','business_communication_addresses.city_id','cities.state_id','people.organization_id')
            // ->leftjoin('business_communication_addresses','businesses.id','=','business_communication_addresses.business_id')
            // ->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
            // ->leftJoin('people', function($join) use ($organization_id)
            // {
            // $join->on('businesses.id', '=', 'people.business_id')
            // ->where('people.organization_id', '=', $organization_id);
            // })
            // ->where('business_communication_addresses.mobile_no',$request->mobile_number)->get();

            // IN the below query result,
            // 1. if organization_id is null, means this user is not part of org.
            // 2. if person_type_id is null or not equal = 2, the user is part of org but not as customer(2), might be a vendor(3)/employee(1)/trade-agent(4)
            // $customerDetails = Business::select('businesses.*','business_communication_addresses.mobile_no','business_communication_addresses.email_address','business_communication_addresses.address','business_communication_addresses.pin','business_communication_addresses.city_id','cities.state_id','people.id AS people_id','people.organization_id','people_person_types.person_type_id AS cust_type_id','account_person_types.display_name AS cust_type')
            // ->leftjoin('business_communication_addresses','businesses.id','=','business_communication_addresses.business_id')
            // ->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
            // ->leftJoin('people', function($join) use ($organization_id)
            // {
            // $join->on('businesses.id', '=', 'people.business_id');
            // $join->where('people.organization_id', '=', $organization_id);
            // $join->leftJoin('people_person_types', function($join2)
            // {
            // $join2->on('people.id','=','people_person_types.people_id');
            // //$join2->where('people_person_types.person_type_id', '=', '2');
            // $join2->leftJoin('account_person_types', function($join3)
            // {
            // $join3->on('people_person_types.person_type_id','=','account_person_types.id');
            // });
            // });
            // })
            // ->where('business_communication_addresses.mobile_no',$request->mobile_number);
            $mobileNumber = $request->mobile_number;
            // $customerDetails = Business::with('address.city', 'businessOrgAssociation.PeoplePersonType.accountType')->whereHas('address', function ($query) use ($mobileNumber) {
            //     $query->where('mobile_no', $mobileNumber);
            // });
            // Log::info('JobCardService->findCustomerByMobile:-query ... ' . $customerDetails->toSql());
            // $customerDetails = $customerDetails->get();
            // // dd($customerDetails);
            // Log::info('JobCardService->findCustomerByMobile:-count' . count($customerDetails));

            $customerDetails = $this->userRepo->findBusinessAssocDetailByMobile($mobileNumber);
            Log::info('JobCardService->findCustomerByMobile:-UserData' . json_encode($customerDetails));
            
            //1. if organization_id is null, means this user is not part of org.
            // 2. if person_type_id is null or not equal = 2, the user is part of org but not as customer(2), might be a vendor(3)/employee(1)/trade-agent(4)
            // if user is already exist as customer only fetch that specific data and push to collection, otherwise pull all data into collection
            // cusomerData - associatedTypeId - represent person type id -  (2 - customer)
            $customerData = new collection();

            if (count($customerDetails) > 0) {
                 $customerDetails->each(function ($model) use ($states, $customerType, $organization_id,&$customerData) {
                    Log::info('JobCardService->findCustomerByMobile:-data' . json_encode($model));
                    $activeStateDropdown = (object) [
                        $model->address->city->state_id => $states[$model->address->city->state_id]
                    ];
                    $activeCityDropdown = (object) [
                        $model->address->city->id => $model->address->city->name
                    ];
                    // dd($activeStateDropdown);
                    Log::info('JobCardService->findCustomerByMobile:-activeStateDropdown' . json_encode($activeStateDropdown));
                    if (count($model->businessOrgAssociation) > 0) {
                        foreach ($model->businessOrgAssociation as $people) {

                            Log::info('JobCardService->findCustomerByMobile:-PeoplePersonType' . json_encode($people->PeoplePersonType));
                            if (count($people->PeoplePersonType) > 0) {
                                foreach ($people->PeoplePersonType as $assoicatedType) {
     
                                    if(($people && $organization_id ==  $people->organization_id) && $assoicatedType->person_type_id == "2"){

                                        $customerData = new collection();
                                        $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, $assoicatedType, $activeStateDropdown, $activeCityDropdown));
                                   
                                    }else if(!$customerData->contains('associatedTypeId',2) ){
                                        $customerData->push ($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, $assoicatedType, $activeStateDropdown, $activeCityDropdown));
                                   
                                    }
                                }
                            } else if(!$customerData->contains('associatedTypeId',2)){
                                $customerData->push($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people, false, $activeStateDropdown, $activeCityDropdown));
                            }
                        }
                    } else if(!$customerData->contains('associatedTypeId',2)){
                        $customerData->push($this->convertToCustomerDetailVO($model, $customerType, $organization_id, $people = false, false, $activeStateDropdown, $activeCityDropdown));
                    }
                });
                $customerData = $customerData->filter()->all();
              //  dd( $customerData);
            } else {
                $customerData = [];
            }
            Log::info('JobCardService->findCustomerByMobile:-End ');
            return [
                'status' => pStatusSuccess(),
                'data' => [
                    'customerDetail' => $customerData,
                    'states' => $states
                ]
            ];
        }
    }

    public function findById($id)
    {
        Log::info("JobCardService->findById :- Inside ");
        $this->setTransactionType();

        $organization_id = Session::get('organization_id');

        // estimate details
        $pHasEstimate = 'False';
        $pEstimateType = '';
        $pEstimateId = '';

        // invoice details
        $pHasInvoice = 'False';
        $pInvoiceType = '';
        $pInvoiceId = '';
        $pIsInvoiceApproved = 'False';

        // $transaction_type = AccountVoucher::where('name', $request->type)->where('organization_id', $organization_id)->first()->id;

        // $transaction = Transaction::select('transactions.*', DB::raw('DATE_FORMAT(transactions.due_date, "%d-%m-%Y") as due_date'), 'vehicle_register_details.registration_no')
        // ->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id')
        // ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id')
        // ->where('transactions.id', $request->order_id)
        // ->where('transactions.transaction_type_id', $this->type_id)
        // ->where('transactions.organization_id', $organization_id)->first();
        $transaction = $this->jobCardRepo->findJobCardDetail($id, $this->type_id, $organization_id);
        // dd($transaction);
        if ($transaction != null) {

            if ($transaction->referencedIn && count($transaction->referencedIn)) {
                $references = $transaction->referencedIn;
                $references->each(function ($refTransaction) use (&$pHasEstimate, &$pEstimateId, &$pEstimateType, &$pHasInvoice, &$pInvoiceId, &$pInvoiceType, &$pIsInvoiceApproved) {
                    $refTransaction = (object) $refTransaction;
                    $transaction_Type = (object) $refTransaction->accountVoucher;
                    if ($transaction_Type->name == 'job_request') {
                        $pHasEstimate = 'True';
                        $pEstimateId = $refTransaction->id;
                        $pEstimateType = $transaction_Type->name;
                    } elseif ($transaction_Type->name == 'job_invoice' || $transaction_Type->name == 'job_invoice_cash') {
                        $pHasInvoice = 'True';
                        $pInvoiceId = $refTransaction->id;
                        $pInvoiceType = $transaction_Type->name;
                        // approval status
                        if ($refTransaction->approval_status == 1) {
                            $pIsInvoiceApproved = 'True';
                        }
                    }
                });
            }

            // $items_query = JobCardItem::select('job_card_items.*', 'inventory_item_stocks.in_stock as in_stock', 'inventory_items.name AS item_name', 'inventory_item_batches.quantity AS batch_stock', 'global_item_categories.display_name AS category', 'global_item_main_categories.category_type_id AS category_type_id', 'global_item_makes.name AS make')->leftjoin('inventory_items', 'inventory_items.id', '=', 'job_card_items.item_id')
            //     ->leftjoin('inventory_item_batches', 'inventory_item_batches.id', '=', 'job_card_items.batch_id')
            //     ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
            //     ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            //     ->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
            //     ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
            //     ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
            //     ->where('job_card_items.job_card_id', $transaction->id);
            // $transaction_items = $items_query->get();
            $transaction_items = $this->jobCardRepo->findJobcardItemsByJobcardId($transaction->id);
        } else {
            $transaction_items = [];
        }
        Log::info('JobCardService->findById- items Data - ' . json_encode($transaction_items));

        // spilt inventory items by category
        $parts = [];
        $service = [];

        if (count($transaction_items) > 0) {
            foreach ($transaction_items as $items) {
                if ($items->category_type_id == 1) {
                    array_push($parts, $items);
                } else if ($items->category_type_id == 2) {
                    array_push($service, $items);
                }
            }
        }

        $vehicleId = $transaction->jobCardDetail->registration_id;

        // id is optional param, it only for get last jobcard detail
        $vehicleWithCustomerDetail = $this->vehicleService->findVehicleDetail(false, $vehicleId, true, $id);
        $data = array();

        $stateList = Custom::getStateByCountryId()->pluck('name', 'id'); // defalut get the india states
     

        // $city = Custom::getCityByStateId($customerCityId, $isPluck = true);
        // billing shipping city

        $billingStateId = "";
        $shippingStateId = "";

        if (($transaction->billing_city_id && $transaction->shipping_city_id) && ($transaction->billing_city_id == $transaction->shipping_city_id)) {

            $stateId = custom::getCityById($transaction->billing_city_id)->state_id;
            $billingStateId = $stateId;
            $shippingStateId = $stateId;
        } else if (($transaction->billing_city_id && $transaction->shipping_city_id)) {

            // $cities = custom::getCityByStateId($id )->pluck('name','id');
            $billingStateId = custom::getCityById($transaction->billing_city_id)->state_id;
            $shippingStateId = custom::getCityById($transaction->shipping_city_id)->state_id;
        }

        // $data['vehicleWithCustomerDetail'] = $this->vehicleService->findVehicleDetail(false,$transaction->wmsTransaction->vehicleDetail->id);
        $data['jobCardDetail'] = [
            'jobcardDate' => $transaction->jobCardDetail->job_date,
            'orderNo' => $transaction->order_no,
            'vehicleMileage' => $transaction->jobCardDetail->vehicle_mileage,
            'vehicleNote' => $transaction->jobCardDetail->vehicle_note,
            'vehicleNo' => $transaction->jobCardDetail->vehicleDetail->registration_no,
            'assignedTo' => $transaction->jobCardDetail->assigned_to,
            'billingName' => $transaction->billing_name,
            'billingMobileNo' => $transaction->billing_mobile,
            'billingEmail' => $transaction->billing_email,
            'billingAddr' => $transaction->billing_address,
            'billingGST' => $transaction->billing_gst,
            'billingStateId' => $billingStateId,
            'billingCityId' => $transaction->billing_city_id,
            'billingPinCode' => $transaction->billing_pincode,
            'shippingName' => $transaction->shipping_name,
            'shippingMobileNo' => $transaction->shipping_mobile,
            'shippingEmail' => $transaction->shipping_email,
            'shippingPinCode' => $transaction->shipping_pincode,
            'shippingStateId' => $shippingStateId,
            'shippingCityId' => $transaction->shipping_city_id,
            'shippingAddr' => $transaction->shipping_address,
            'jobDueDate' => $transaction->jobCardDetail->job_due_date,
            'complaints' => $transaction->jobCardDetail->vehicle_complaints,
            'JCCompletedDate' => $transaction->jobCardDetail->job_completed_date,
            'vehicleNextVisitReason' => $transaction->jobCardDetail->vehicle_next_visit_reason,
            'vehicleNextVisitMileage' => $transaction->jobCardDetail->next_visit_mileage,
            'vehicleNextVisitDate' => $transaction->jobCardDetail->vehicle_next_visit,
            'jobStatusId' => $transaction->jobCardDetail->jobcard_status_id,
            'driverName' => $transaction->jobCardDetail->driver,
            'driverNumber' => $transaction->jobCardDetail->driver_contact,
            'states' => $stateList,
            'pHasEstimate' => $pHasEstimate,
            'pEstimateType' => $pEstimateType,
            'pEstimateId' => $pEstimateId,
            'pHasInvoice' => $pHasInvoice,
            'pInvoiceType' => $pInvoiceType,
            'pInvoiceId' => $pInvoiceId,
            'pIsInvoiceApproved' => $pIsInvoiceApproved
        ];
        Log::info('JobCardService->findById:- return dagta' . json_encode($data));
        $data['jobCardItems'] = [
            'parts' => $parts,
            'service' => $service
        ];

        // For Jobcard Acknowledgement
        $data['encryptedAckURL'] = generateEncryptedURL(url('job_card_acknowledgement/'),$id);
        
        // jobcard Images
        $data['jobCardImages'] =  $this->findJobCardImageByTID($id);

        $data = array_merge($data, $vehicleWithCustomerDetail);

        Log::info("JobCardService->findById :- Return ");

        return [
            'status' => 'SUCCESS',
            'data' => $data
        ];
    }

    public function findJobCardImageByTID($id, $organization_id = false)
    {
        if (! $organization_id) {

            $organization_id = Session::get('organization_id');
        }

        Log::info("JobCardService->findJobCardImageByTID :- Inside ");
        // $wms_attachments_before = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
        //     ->where('image_category', 1)
        //     ->where('organization_id', $organization_id)
        //     ->get();

        // $wms_attachments_progress = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
        //     ->where('image_category', 2)
        //     ->where('organization_id', $organization_id)
        //     ->get();

        // $wms_attachments_after = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
        //     ->where('image_category', 3)
        //     ->where('organization_id', $organization_id)
        //     ->get();
        $type = 1;
        $wms_attachments_before = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type );

        $type = 2;
        $wms_attachments_progress = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type );

        $type = 3;
        $wms_attachments_after = $this->jobCardRepo->findJobcardAttachmentByIdAndType($id,$type );

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

    public function jobcard_advance($request)
    {
        Log::info("JobCardService->jobcard_advance :- Inside ");

        $organization_id = Session::get('organization_id');
        $jobCard_id = $request->id;

      //  $jobCardModel = JobCard::findorfail($jobCard_id);
        $jobCardModel = $this->jobCardRepo->findJobCardById($jobCard_id);
        Log::info("JobCardService->convertToTransactionModel :- Inside " . json_encode($jobCardModel));
        $transactionModel = $jobCardModel->referencedIn()
            ->where('transaction_type_id', $jobCardModel->transaction_type_id)
            ->first();
        Log::info("JobCardService->convertToTransactionModel :- Inside " . json_encode($transactionModel));

        if ($transactionModel && $transactionModel->id) {
            $transaction_id = $transactionModel->id;

            //$payment = PaymentMode::where('status', 1)->select('display_name', 'id')->get();
            $payment = $this->jobCardRepo->findAllPaymentMode();

            // $ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name', 'account_groups.name AS group')->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
            //     ->whereIn('account_groups.name', [
            //     'cash'
            // ])
            //     ->where('account_ledgers.organization_id', $organization_id)
            //     ->where('account_ledgers.approval_status', '1')
            //     ->where('account_ledgers.status', '1')
            //     ->orderby('account_ledgers.id', 'asc')
            //     ->get();

            $paymentType = 'cash';
    
            $ledgers = $this->accountLedgerRepo->findByOrgIdAndType($paymentType);

            // $selected_job_card = Transaction::select('transactions.order_no', 'transactions.id', 'user_type', 'total')->where('transactions.organization_id', $organization_id)
            //     ->where('transactions.id', $transaction_id)
            //     ->first();
            $selected_job_card = $this->jobCardRepo->findJobCardTransactionById($transaction_id);

            if ($selected_job_card->user_type == 0) {
                // $cus_name = Transaction::select('transactions.id', 'transactions.total', 'people.display_name', 'people.person_id', 'transactions.user_type')->leftjoin('people', 'people.person_id', '=', 'transactions.people_id')
                //     ->where('transactions.order_no', $selected_job_card->order_no)
                //     ->where('transactions.organization_id', $organization_id)
                //     ->whereNull('transactions.deleted_at')
                //     ->first();
                $cus_name =  $this->jobCardRepo->findPersonTransactionByOrderNo( $selected_job_card->order_no);
            }
            if ($selected_job_card->user_type == 1) {
                // $cus_name = Transaction::select('transactions.id', 'transactions.total', 'people.display_name', 'people.business_id', 'transactions.user_type')->leftjoin('people', 'people.business_id', '=', 'transactions.people_id')
                //     ->where('transactions.order_no', $selected_job_card->order_no)
                //     ->where('transactions.organization_id', $organization_id)
                //     ->whereNull('transactions.deleted_at')
                //     ->first();
                $cus_name =  $this->jobCardRepo->findBusinessTransactionByOrderNo( $selected_job_card->order_no);
            }
            return [
                'payment' => $payment,
                'ledgers' => $ledgers,
                'selected_job_card' => $selected_job_card,
                'name' => $cus_name
            ];
        } else {
            return [
                'message' => 'Transaction not found. Please contact propel customer support.'
            ];
        }

        Log::info("JobCardService->jobcard_advance :- End ");
    }

    /* Store and Update functions */
    public function changeStatus($request)
    {
        Log::info("JobCardService->changeStatus :- Inside ");
        $this->setTransactionType();
        // dd($request->all());
        $candidate_status = JobCardDetail::where('job_card_id', $request->input('id'))->update([
            'jobcard_status_id' => $request->input('status')
        ]);
        // dd($candidate_status);

        Log::info("JobCardService->changeStatus :- return ");
        return [
            'status' => pStatusSuccess()
        ];
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



    public function store($data)
    {
        Log::info("JobCardService->store :- Inside ");

        $this->setTransactionType();


        Log::info("JobCardService->store :- data " . json_encode($data));
       // dd();

        //$validation = $this->validator($data);
        $alerts = new Collection();

        $validator = $this->validator($data);

        if ($validator->fails()) {
            $errors = $validator->messages();
            $responseErrorMsgs = $this->convertToResponseErrorFormat($errors);

            return [
                'message' => PStatusFailed(),
                'data' => [
                    "alerts" => $responseErrorMsgs
                ]
            ];
        }

        // dd($data);
        Log::info("JobCardService->store :- data " . json_encode($data));

        // dd($item_goods_array,$item_service_array,$jobcardItems);
        $data = (object) $data;

        $customerId = $data->customer_id;
        $vehicleId = $data->vehicle_id;
        $peopleId = $data->people_id;
        $isCustomerSaved = $data->customer_existing;
        $isVehicleSaved = $data->vehicle_existing;
        $jobcardNo = "";
        $isSaveJCHeader = "false";
        $id = "";

        try {
            // Store Customer
            $responseCustomer = $this->store_Customer($data);

            if ($responseCustomer && $responseCustomer['message'] == pStatusFailed()) {
                //return $responseCustomer;
                $alerts->push([
                    'tab' => 'Detail',
                    'sub_section' => 'Customer',
                    'field' => '',
                    'error_message' => 'Customer # Failed to Save Customer Team. Please contact our Support Team.'
                ]);
                return [
                    'message' => pStatusFailed(),
                    'data' => "",
                    'error' => "",
                    'alerts' => $alerts
                ];
            }

            $customerId = $responseCustomer['data']['customerId'];
            $peopleId = $responseCustomer['data']['peopleId'];
            $isCustomerSaved = "true";

            // Store Vehicle
            $responseVehicle = $this->store_Vehicle($data, $customerId);

            if ($responseVehicle && $responseVehicle['message'] == pStatusFailed()) {
                $isVehicleSaved = "false";

                $data = [
                    'isCustomerSaved' => $isCustomerSaved,
                    "customerId" => $customerId,
                    "peopleId" => $peopleId,
                    'isVehicleSaved' => $isVehicleSaved,
                    "vehicleId" => ''
                ];
                $alerts->push([
                    'tab' => 'Detail',
                    'sub_section' => 'Vehicle',
                    'field' => '',
                    'error_message' => 'Vehicle # Failed to Save Vehicle Details. Please contact our Support Team.'
                ]);

                return [
                    'message' => pStatusFailed(),
                    'data' => $data,
                    'error' => $responseVehicle['data'],
                    "alerts" => $alerts
                ];
            }

            $isVehicleSaved = "true";
            $vehicleId = $responseVehicle['data']['vehicleId'];

            // Store jobcard details
            $responseWmsTransaction = $this->store_JC_Header($data, $customerId, $vehicleId);
            $orderNoGenFailed = false;
            if ($responseWmsTransaction && $responseWmsTransaction['message'] == pStatusFailed()) {
                if ($responseWmsTransaction['error'] && $responseWmsTransaction['error'] == 'ORDER_NO_FAILED') {
                    $orderNoGenFailed = true;
                    $alerts->push([
                        'tab' => 'Detail',
                        'sub_section' => '',
                        'field' => 'Job Card #',
                        'error_message' => $responseWmsTransaction['error_message']
                    ]);
                    // $alerts->push($responseWmsTransaction['error_message']);
                } else {
                    $isSaveJCHeader = "false";

                    $data = [
                        'isCustomerSaved' => $isCustomerSaved,
                        "customerId" => $customerId,
                        'isVehicleSaved' => $isVehicleSaved,
                        "vehicleId" => $vehicleId,
                        "peopleId" => $peopleId,
                        "isSaveJCHeader" => $isSaveJCHeader,
                        "id" => $id
                    ];

                    return [
                        'message' => pStatusFailed(),
                        'data' => $data,
                        'error' => $responseWmsTransaction['data']
                    ];
                }
            }

            $isSaveJCHeader = "true";
            $id = $responseWmsTransaction['data']->id;
            $jobcardNo = $responseWmsTransaction['data']->order_no;

            // Store Items
            $responseJobCardItems = $this->store_JC_Items($data, $id);

            if ($responseJobCardItems && $responseJobCardItems['message'] == pStatusFailed()) {
                
                $alerts->push([
                    'tab' => 'ITEM',
                    'sub_section' => '',
                    'field' => 'ITEM',
                    'error_message' => 'Parts/Service # Failed to Save JobCard Items. Please contact our Support Team.'
                ]);
            
            }

            // Store CheckList
            $responseVehicleCheckList = $this->store_JC_CheckList($data, $id);
            if ($responseVehicleCheckList && $responseVehicleCheckList['message'] == pStatusFailed()) {
                
                $alerts->push([
                    'tab' => 'CHECKLIST',
                    'sub_section' => '',
                    'field' => 'CHECKLIST',
                    'error_message' => 'CHECKLIST # Failed to Save JobCard Check List. Please contact our Support Team.'
                ]);
            
            }

            // Store Images
            $responseImages = $this->store_JC_images($data, $id);
            if ($responseImages && $responseImages['message'] == pStatusFailed()) {
                
                $alerts->push([
                    'tab' => 'IMAGE',
                    'sub_section' => '',
                    'field' => 'IMAGE',
                    'error_message' => 'Before /Progress /After # Failed to Save JobCard Image. Please contact our Support Team.'
                ]);
            
            }

            // TODO: these are sample errors, will be removed when validation is completed
            // $alerts->push([
            //     'tab' => 'Detail',
            //     'sub_section' => '',
            //     'field' => 'Job Card #',
            //     'error_message' => 'Job Card # failed to generate. Please try save again.'
            // ]);
            // $alerts->push([
            //     'tab' => 'Detail',
            //     'sub_section' => 'Vehicle',
            //     'field' => 'Registration Number',
            //     'error_message' => 'Registration Number is required. Cannot be blank.'
            // ]);
            // $alerts->push([
            //     'tab' => 'Detail',
            //     'sub_section' => 'Vehicle - Other Info',
            //     'field' => 'Insurance',
            //     'error_message' => 'Insurance is required. Cannot be blank.'
            // ]);
            $data = [
                'isCustomerSaved' => $isCustomerSaved,
                "customerId" => $customerId,
                'isVehicleSaved' => $isVehicleSaved,
                "vehicleId" => $vehicleId,
                "peopleId" => $peopleId,
                "isSaveJCHeader" => $isSaveJCHeader,
                "id" => $id,
                "jobcardNo" => $jobcardNo,
                "alerts" => $alerts
            ];

            return [
                'message' => pStatusSuccess(),
                'data' => $data,
                'error' => ''
            ];
        } catch (\Exception $e) {
            Log::info("JobCardService->store :- return Catch " . $e->getMessage());
            return response()->json([
                'message' => pStatusFailed(),
                'error' => $e->getMessage(),
                "data" => [
                    'isCustomerSaved' => $isCustomerSaved,
                    "customerId" => $customerId,
                    'isVehicleSaved' => $isVehicleSaved,
                    "vehicleId" => $vehicleId,
                    "peopleId" => $peopleId,
                    "isSaveJCHeader" => $isSaveJCHeader,
                    "id" => $id,
                    "jobcardNo" => $jobcardNo
                ]
            ]);
        }
    }

    public function store_Customer($data)
    {
        Log::info("JobCardService->store_Customer :- Inside ");
        $customerId = $data->customer_id;
        $peopleId = $data->people_id;

        $responseCustomer = "";
        $organizationId = Session::get('organization_id');

        if ($data->customer_existing == "false" || ($data->customer_id && $data->customer_id > 0 && ($data->IsCustomerRequiredFieldEmpty == 'true'))) {

            Log::info("JobCardService->store_Customer :- Inside - Customer Id ".$data->customer_id);
            Log::info("JobCardService->store_Customer :- Inside - Customer Required Field ".$data->IsCustomerRequiredFieldEmpty);
          
            if ($data->customer_type == 0) {

                $personModel = $this->convertToPersonModel($data);
                $personCommunicationModel = $this->convertToPersonCommModel($data);
                Log::info("JobCardService->store_Customer :- Inside - convertToPersonCommModel ".$personCommunicationModel);
                $responseCustomer = $this->userRepo->savePerson($personModel, $personCommunicationModel);
            } else if ($data->customer_type == 1) {

                $businessModel = $this->convertToBusinessModel($data);
                $businessCommunicationModel = $this->convertToBusinessCommModel($data);
                $responseCustomer = $this->orgRepo->saveBusiness($businessModel, $businessCommunicationModel);
            }
        }

        if ($responseCustomer && $responseCustomer['message'] == pStatusFailed()) {
            return $responseCustomer;
        } else {

            Log::info("JobCardService->Customer :- return " . json_encode($responseCustomer));
            // customer newly added
            if ($responseCustomer && $responseCustomer['message'] == pStatusSuccess()) {

                $customerId = $responseCustomer['data']->id;
            }
            // if($data->customer_type == 1){
            // $isExistPeople = $this->peopleRepo->isExistPeopleByBusinessIdAndOrgId($customerId,$organization_id);

            // } else if($data->customer_type == 0){
            // $isExistPeople = $this->peopleRepo->isExistPeopleByPersonIdAndOrgId($customerId,$organization_id);

            // }

            // just fetch the people id not associated with customer
             // just fetch the people id not associated with customer
             if (! $data->people_id) {

                $peopleData = $this->peopleRepo->findPeople($organizationId,$data->customer_type,$customerId);
                Log::info("JobCardService->Customer :-  PeopleData  " . json_encode($responseCustomer));
                if(!$peopleData ){

                    $customerType = "customer";
                    $customerTypeId = $this->peopleRepo->findAccountPersonTypeByName($customerType)->id;
                    $peopleModel = $this->convertToPeopleModel($data, $customerId);
                    // $peopleAddrModel = $this->convertToPeopleAddrModel($data);

                    $accPersonTypeModel = new PeoplePersonType();
                    $accPersonTypeModel->person_type_id = $customerTypeId;

                    // $responsePeople = $this->peopleRepo->savePeople($peopleModel,$peopleAddrModel);
                    $responsePeople = $this->peopleRepo->savePeople($peopleModel, $accPersonTypeModel);

                    if ($responsePeople['message'] == pStatusFailed()) {
                        return [
                            'message' => pStatusSuccess(),
                            'data' => [
                                'customerId' => $customerId,
                                "peopleId" => $peopleId
                            ]
                        ];
                    } else if ($responsePeople['message'] == pStatusSuccess()) {
                        $peopleId = $responsePeople['data']->id;
                    }
                }else if( $peopleData && $peopleData->id){
                    $data->people_id = $peopleData->id;
                }

            } 
            Log::info("JobCardService->Customer :-  PeopleId  " . json_encode($data->people_id));
            
            if ($data->people_id) {

                // update people if customer required field is empty
                if($data->IsCustomerRequiredFieldEmpty == 'true'){
                   $peopleModel = $this->convertToPeopleModel($data, $customerId,$data->people_id);
                   $responsePeople = $this->peopleRepo->savePeople($peopleModel);
                }
                
                $customerType = "customer";
                $customerTypeId = $this->peopleRepo->findAccountPersonTypeByName($customerType)->id;
                // if existing people check if customer
                $isExistingCustomer = $this->peopleRepo->isExistCustomer($data->people_id);
                Log::info("JobCardService->storeCustomer :- Data ");
                Log::info("JobCardService->storeCustomer :- isExistingCustomer " . $isExistingCustomer);

                if (! $isExistingCustomer) {
                    Log::info("JobCardService->storeCustomer :- Data ");
                    $accPersonTypeModel = new PeoplePersonType();
                    $accPersonTypeModel->person_type_id = $customerTypeId;
                    $accPersonTypeModel->people_id = $data->people_id;

                    Log::info("JobCardService->storeCustomer :- People " . json_encode($accPersonTypeModel));

                    $responsePeopleAccountType = $this->peopleRepo->savePeopleAccountType($accPersonTypeModel);

                    if ($responsePeopleAccountType['message'] == pStatusFailed()) {
                        return $responsePeopleAccountType;
                    }
                }
            }
            Log::info("JobCardService->store_Customer :- Inside ");
            return [
                'message' => pStatusSuccess(),
                'data' => [
                    'customerId' => $customerId,
                    "peopleId" => $peopleId
                ]
            ];
        }
    }

    public function store_Vehicle($data, $customerId)
    {
        Log::info("JobCardService->store_Vehicle :- Inside");

        $vehicleId = $data->vehicle_id;
        $organizationId = Session::get('organization_id');

        if ($data->vehicle_existing == "false") {
            $configData = $this->vehicleRepo->findVehicleVariantById($data->vehicle_config);

            $model = $this->convertToVehicleRegisterDetailModel($data, $configData, $customerId);
            $vehicleOrgAssocModel = $this->convertToWmsVehicleOrgModel($data);
            $responseVechicle = $this->vehicleRepo->saveVehicle($model, $vehicleOrgAssocModel);

            if ($responseVechicle['message'] == pStatusFailed()) {
                return $responseVechicle;
            } else if ($responseVechicle['message'] == pStatusSuccess()) {

                //
                if (! $vehicleId) {
                    $vehicleModel = $responseVechicle['data'];
                    $vehicleId = $vehicleModel->id;
                }
            }
        } else if ($data->vehicle_existing == "true") {

            $isVehicleExistInOrg = $this->vehicleRepo->isVehicleExistInOrganization($vehicleId, $organizationId);
            
            if (! $isVehicleExistInOrg) {
                $vehicleOrgAssocModel = $this->convertToWmsVehicleOrgModel($data);
                $responseVechicleOrgAssoc = $this->vehicleRepo->saveVehicleOrgAssoc($vehicleOrgAssocModel);

                if ($responseVechicleOrgAssoc['message'] == pStatusFailed()) {
                    return $responseVechicleOrgAssoc;
                }
            }
        }

        $data = [
            "vehicleId" => $vehicleId
        ];
        Log::info("JobCardService->store_Vehicle :- Return");
        return [
            'message' => pStatusSuccess(),
            'data' => $data
        ];
    }

    public function store_JC_Header($data, $customerId, $vehicleId)
    {
        Log::info("JobCardService->store_JC_Header :- Inside " . json_encode($data));

        $organizationId = Session::get('organization_id');
        $transactionType = $this->transaction_type;
        $newJobCard = false;

        // order no and gen no field only for create a new jobcard
        $genNo = "";
        $orderNo = "";

        if (! $data->id || ! $data->job_card_no || $data->job_card_no == 'Temp-Number') {
            $newJobCard = true;
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

        $model = $this->convertToJobCardModel($data, $customerId, $orderNo, $genNo, $data->id);
        // TODO : This has to be removed when accoutns/books is redesinged to use th new jobcard table
        $transactionModel = $this->convertToTransactionModel($model);
        $detailModel = $this->convertToJobCardDetailModel($data, $vehicleId);
        $response = $this->jobCardRepo->saveJobCard($model, $detailModel, $transactionModel);

        if ($newJobCard && $response['message'] == pStatusSuccess()) {
            Log::info("JobCardService->store_JC_Header :- Inside gen no update");
            // get next gen number and save it to jobcard/transaction table. increment account voucher with the next number
            $commitedModel = $response['data'];
            $commitedTransactionModel = $commitedModel->referencedIn()
                ->where('transaction_type_id', $this->type_id)
                ->first();

            $genOrderNumber = $this->getNextGenAndOrderNumber();

            if ($genOrderNumber) {
                $genNo = $genOrderNumber['nextGenNumber'];
                $orderNo = $genOrderNumber['orderNo'];

                $commitedModel->order_no = $orderNo;
                $commitedModel->gen_no = $genNo;

                $commitedTransactionModel->order_no = $orderNo;

                $accountVoucherModel = $this->accountRepo->findById($this->type_id);
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
        $isCheckedItemExist = property_exists($data, "wms_checklist_status") ? $data->wms_checklist_status : null;

        if ($isCheckedItemExist) {
            $checkListData = $data->wms_checklist_status;
        } else {
            $checkListData = [];
        }

        if (count($checkListData) > 0) {
            // get checked list key value pair
            // get matching values from checklist_id
            $checkListIdArray = $data->checklist_id;
            $checkedItemDataArray = $data->wms_checklist_status;
            $checkedListNotes = $data->wms_checklist_notes;
            $checkedListArray = array_intersect($checkListIdArray, $checkedItemDataArray);

            // get count of unchecked data from table
            $unCheckedData = $this->jobCardRepo->findUnCheckedCLData($checkListData, $jobCardId);

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
            foreach ($checkedListNotes as $key => $value) {
                if (array_key_exists($key, $checkedListArray)) {
                    $checkListId = $checkedListArray[$key];

                    Log::info('JobCardRepository->checklist Save :- ' . json_encode($data));
                    $data = JobCardChecklist::updateOrCreate([
                        'job_card_id' => $jobCardId,
                        'checklist_id' => $checkListId
                    ], [
                        'checklist_notes' => $value,
                        'checklist_status' => 1,
                        'created_by' => Auth::user()->id,
                        'last_modified_by' => Auth::user()->id,
                        'created_at' => $dateTimeString,
                        'updated_at' => $dateTimeString


                    ]);
                }
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

    public function store_JC_images($data, $jobCardId)
    {
        Log::info("JobCardService->store_JC_images :- Inside");

        /* check if before,progress,after images exist or not */
        $beforeImage = property_exists($data, "before_image") ? $data->before_image : null;
        $progressImage = property_exists($data, "progress_image") ? $data->progress_image : NULL;
        $afterImage = property_exists($data, "after_image") ? $data->after_image : NULL;

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
                
                collect($beforeImage)->each(function ($item) use ($jobCardId, &$imgSequenceNo, &$jobcardAttachments) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    $imgData = $this->imgArrayFormat($item, $jobCardId, $imgCategory = 1, $imgSequenceNo);
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

                collect($progressImage)->each(function ($item, $key) use ($jobCardId, &$jobcardAttachments, &$imgSequenceNo) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    $imgData = $this->imgArrayFormat($item, $jobCardId, $imgCategory = 2, $imgSequenceNo);
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

                collect($afterImage)->each(function ($item, $key) use ($jobCardId, &$jobcardAttachments, &$imgSequenceNo) {

                    // img sequence increment by one
                    $imgSequenceNo = $imgSequenceNo + 1;

                    // upload image file
                    $imgData = $this->imgArrayFormat($item, $jobCardId, $imgCategory = 3, $imgSequenceNo);
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

    /* Destory functions */
    public function destroy($id)
    {
        Log::info("JobCardService->destroy :- Inside ");

        $this->setTransactionType();

        $jobCardModel = $this->jobCardRepo->findJobCardById($id);
        // TODO need to work on this when creating a dummy transaction entry
        // $transactionModel = $this->jobCardRepo->findByTransactionId($id);

        if ($jobCardModel) {

            // soft delete not implemented in the below tables, cannot delete. As transactoin has softdelete
            // $wmsTransactionModel = $transactionModel->wmsTransaction();
            // $transactionItemModel = $transactionModel->transactionItem();

            $response = $this->jobCardRepo->destroy($jobCardModel);

            if ($response['message'] == pStatusSuccess()) {
                /* update organization transaction usage, this is for propel management to identify the usage */
                Custom::delete_addon('transaction');

                Log::info("JobCardService->destroy :- return ");
                return [
                    'message' => pStatusSuccess()
                ];
            } else {
                return $response;
            }
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
        //$organization_id = Session::get('organization_id');
        // {{asset('public/wms_attachments/org_'.$organization_id).'/temp/'}};
        return asset('public/wms_attachments/org_' . $organization_id) . '/temp/';
    }

    public function getNextGenAndOrderNumber()
    {
        Log::info("JobCardService->getNextGenNumber :- Inside ");

        $accountVoucher = $this->accountRepo->findById($this->type_id);
        $nextGenNumber = false;
        $orderNo = "";

        if ($accountVoucher) {
            $nextGenNumber = $accountVoucher->starting_value;

            if (! $nextGenNumber || $nextGenNumber == 0) {
                $nextGenNumber = 1;
            }
            $orderNo = Custom::generate_accounts_number($accountVoucher->name, $nextGenNumber, false);
        }

        Log::info("JobCardService->getNextGenNumber :- Return ");
        return [
            'nextGenNumber' => $nextGenNumber,
            'orderNo' => $orderNo
        ];
    }

    public function imgArrayFormat($item, $transactionId, $imgType, $imgSequence)
    {
        $path = jobCardImagePath();
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
            $imgData['organization_id'] = Session::get('organization_id');
            $imgData['created_by'] = Auth::user()->id;
            $imgData['last_modified_by'] = Auth::user()->id;
            $imgData['created_at'] = $dateTimeString;
            $imgData['updated_at'] = $dateTimeString;
            return $imgData;
        } else {
            return false;
        }
    }

    /* All Covert to VO and Model functions */
    public function convertToPersonModel($data)
    {
        Log::info("JobCardService->convertToPersonModel :- Inside ");

        // $data = (object) $data;
        // $city = City::select('name')->where('id', $request->input('city'))->first()->name;

        $cityName = Custom::getCityById($data->city)->name;
        $crm_code = Custom::personal_crm($cityName, $data->customer_mobile_number, $data->first_name);

        if($data->customer_id){
            
           // $model = Person::find($data->customer_id);
           $model = $this->userRepo->findPersonById($data->customer_id);
        }else{

            $model = new Person();
        }

        // create modeel
        $model->crm_code = $crm_code;
        $model->first_name = $data->first_name;
        $model->last_name = $data->last_name;
        Log::info("JobCardService->convertToPersonModel :- Return ");
        return $model;
    }

    public function convertToPersonCommModel($data)
    {
        Log::info("JobCardService->convertToPersonCommModel :- Inside ");
        // $data = (object) $data;
        // $city = City::select('name')->where('id', $request->input('city'))->first()->name;

        $addressType = Custom::addressTypePerson();

        if($data->customer_id){
            
            // $model = Person::find($data->customer_id);
            $personAddressModel = $this->userRepo->findPersonCommunicationByPersonId($data->customer_id);
         }else{
            $personAddressModel = new PersonCommunicationAddress();
         }

        // model doesn't exist and customer id exist 
       if(!$personAddressModel && $data->customer_id){
            $personAddressModel = new PersonCommunicationAddress();
            $personAddressModel->person_id = $data->customer_id;
        }
        

        $personAddressModel->address_type = $addressType->id;
        $personAddressModel->address = $data->customer_address;
        $personAddressModel->city_id = $data->city;
        $personAddressModel->mobile_no = $data->customer_mobile_number;
        // $person_address->mobile_no_prev = $data->customer_mobile_number;
        $personAddressModel->email_address = $data->customer_email;
        $personAddressModel->pin = $data->pincode;

        Log::info("JobCardService->convertToPersonCommModel :- Return ");
        return $personAddressModel;
    }

    public function convertToBusinessModel($data)
    {
        Log::info("JobCardService->convertToBusinessModel :- Inside ");
        // $data = (object) $data;
        // $city = City::select('name')->where('id', $request->input('city'))->first()->name;

        $cityName = Custom::getCityById($data->city)->name;
        // $crm_code = Custom::business_crm($cityName, $data->mobile_no, $data->first_name);
        $bcrm_code = Custom::business_crm($cityName, $data->customer_mobile_number, $data->business_name);

        if($data->customer_id){
            $model = $this->userRepo->findBusinessById($data->customer_id);
        }else{
            $model = new Business();
        }


        // create modeel
        
        $model->bcrm_code = $bcrm_code;
        $model->business_name = $data->business_name;
        $model->alias = $data->business_name;
        $model->gst = $data->customer_gst;

        Log::info("JobCardService->convertToBusinessModel :- Return ");
        return $model;
    }

    public function convertToBusinessCommModel($data)
    {
        Log::info("JobCardService->convertToBusinessCommModel :- Inside ");
        // $data = (object) $data;
        // $city = City::select('name')->where('id', $request->input('city'))->first()->name;

        $addressType = Custom::addressTypeBusiness();

        
       // $model = new BusinessCommunicationAddress();

       if($data->customer_id){
          // $model = BusinessCommunicationAddress::where('business',$data->customer_id)->first();
           $model = $this->userRepo->findBusinessCommunicationByBusinessId($data->customer_id);
       }else{
           $model = new BusinessCommunicationAddress();
       }

       // model doesn't exist and customer id exist 
       if(!$model && $data->customer_id){
           $model = new BusinessCommunicationAddress();
           $model->business_id = $data->customer_id;
       }


        $model->address_type = $addressType->id;
        $model->address = $data->customer_address;
        $model->city_id = $data->city;
        $model->mobile_no = $data->customer_mobile_number;
        // $model->mobile_no_prev = $data->customer_mobile_number;
        $model->email_address = $data->customer_email;
        $model->pin = $data->pincode;

        Log::info("JobCardService->convertToBusinessCommModel :- Return ");
        return $model;
    }

    public function convertToPeopleModel($data, $customerId,$peopleId = false)
    {
        Log::info("JobCardService->convertToPeopleModel :- Inside ");
        if($peopleId){
            $model = $this->peopleRepo->findById($peopleId);
        }else{
            $model = new People();
        }
        // if($request->input('customer_id') != null)
        // {
        $model->user_type = $data->customer_type;
        if ($data->customer_type == 0) {
            $model->person_id = $customerId;
            $model->first_name = $data->first_name;
            $model->last_name = $data->last_name;
            $model->display_name = $data->first_name . " " . $data->last_name;
        } else if ($data->customer_type == 1) {
            $model->business_id = $customerId;
            $model->company = $data->business_name;
            $model->display_name = $data->business_name;
            $model->gst_no = $data->customer_gst;
        }
        // }
        // else
        // {
        // if($request->customer_type == 0)
        // {
        // $model->person_id = $person->id;
        // }
        // else if($request->customer_type == 1)
        // {
        // $model->business_id = $business->id;
        // // $model->company = $request->input('customer_name');

        // }
        // }

        $model->mobile_no = $data->customer_mobile_number;
        $model->email_address = $data->customer_email;
        $model->organization_id = Session::get('organization_id');

        Log::info("JobCardService->convertToPeopleModel :- Return ");
        return $model;
    }

    public function convertToPeopleAddrModel($data)
    {
        Log::info("JobCardService->convertToPeopleAddrModel :- Inside ");
        $model = new PeopleAddress();
        $model->address_type = 0;
        $model->address = $data->customer_address;
        $model->city_id = $data->city;
        $model->pin = $data->pincode;

        Log::info("JobCardService->convertToPeopleAddrModel :- Return ");
        return $model;
    }

    public function convertToVehicleRegisterDetailModel($data, $configData = false, $customerId = false)
    {
        Log::info("JobCardService->convertToVehicleRegisterDetailModel :- Inside ");

        if ($data->vehicle_id) {

            $model = $this->vehicleRepo->findById($data->vehicle_id);
        } else {

            $model = new VehicleRegisterDetail();
            $model->registration_no = $data->vehicle_registration_number;
            $model->user_type = $data->customer_type;
            $model->owner_id = $customerId;
            $model->vehicle_configuration_id = $data->vehicle_config;
            $model->vehicle_category_id = $data->vehicle_category_id;
            $model->vehicle_make_id = $configData->vehicle_make_id;
            $model->vehicle_model_id = $configData->vehicle_model_id;
            $model->vehicle_variant_id = $configData->id;
            $model->version = $configData->version;

            $model->engine_no = $data->engine_number;
            $model->chassis_no = $data->chassis_number;
            $model->manufacturing_year = $data->manufacturing_year;
            $model->permit_type = isset($data->permit_type) && $data->permit_type ? $data->permit_type : null;
            $model->fc_due = ($data->fc_due != null) ? carbon::parse($data->fc_due) : null;
            $model->permit_due = ($data->permit_due != null) ? carbon::parse($data->permit_due) : null;
            $model->tax_due = ($data->tax_due != null) ? carbon::parse($data->tax_due) : null;
            $model->insurance = $data->vehicle_insurance;
            /* TODO: field doesn't exist in form */
            $model->premium_date = ($data->insurance_due != null) ? carbon::parse($data->insurance_due) : null;
            $model->month_due_date = ($data->month_due_date != null) ? carbon::parse($data->month_due_date) : null;
            $model->bank_loan = isset($data->bank_loan) && $data->bank_loan ? $data->bank_loan : null;
            $model->warranty_km = $data->warranty_km;
            $model->warranty_years = $data->warrenty_yrs;

            $model->description = $data->vehicle_note;
            $model->organization_id = Session::get('organization_id');
        }

        $model->driver = $data->driver;
        $model->driver_mobile_no = $data->driver_contact;
        // Custom::userby($model, true);
        // Custom::add_addon('records');
        Log::info("JobCardService->convertToVehicleRegisterDetailModel :- Return ");
        return $model;
    }

    public function convertToWmsVehicleOrgModel($data)
    {
        Log::info("JobCardService->convertToWmsVehicleOrgModel :- Inside ");

        $model = new WmsVehicleOrganization();
        $model->organization_id = Session::get('organization_id');
        $model->vehicle_id = $data->vehicle_id;
        $model->created_by = Auth::user()->id;
        $model->last_modified_by = Auth::user()->id;

        Log::info("JobCardService->convertToWmsVehicleOrgModel :- Return ");
        return $model;
    }

    public function convertToJobCardModel($data, $customerId, $orderNo, $genNo, $id = false)
    {
        Log::info("JobCardService->convertToJobCardModel :- Inside ");

        $customerName = $data->customer_type == 0 ? $data->first_name : $data->business_name;
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

        $model->employee_id = $data->employee_id;
        $model->date = $data->job_date;

        /* TODO:These information needed here */
        $model->name = $customerName;
        $model->mobile = $data->customer_mobile_number;
        $model->email = $data->customer_email;
        $model->gst = $data->customer_gst ? $data->customer_gst : Null; // refer Notes : find ??
        $model->address = $data->customer_address;
        $model->pin = $data->pincode;
        $model->shipment_mode_id = $data->shipment_mode_id;

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

        /* */
        $model->date = ($data->job_date != null) ? Carbon::parse($data->job_date)->format('Y-m-d') : null;
        $model->transaction_type_id = $this->type_id;
        $model->user_type = $data->customer_type;
        $model->people_id = $customerId;

        $model->organization_id = Session::get('organization_id');
        $model->notification_status = 1;

        Log::info("JobCardService->convertToJobCardModel :- Return ");
        return $model;
        // $transaction->save();
        // Custom::userby($transaction, true);
    }

    // Need to have a dummy entry in transaction table for advance payment to work.
    // TODO : remove this when we correct Accounts/Books to use the new job card tables
    public function convertToTransactionModel($jobCardModel)
    {
        Log::info("JobCardService->convertToTransactionModel :- Inside ");

        if ($jobCardModel->id) {
            $model = $jobCardModel->referencedIn()
                ->where('transaction_type_id', $this->type_id)
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

    public function convertToJobCardDetailModel($data, $vehicleId)
    {
        Log::info("JobCardService->convertToJobCardDetailModel :- Inside ");

        if ($data->id) {
            $model = JobCardDetail::where('job_card_id', $data->id)->first();
        } else {
            $model = new JobCardDetail();
            // $model->transaction_id = $transaction->id;
        }
        $model->registration_id = $vehicleId;
        $model->engine_no = $data->engine_number;
        $model->chasis_no = $data->chassis_number;
        $model->jobcard_status_id = $data->jobcard_status_id;
        $model->service_type = $data->service_type;
        $model->assigned_to = $data->employee_id;
        $model->job_date = ($data->job_date != null) ? Carbon::parse($data->job_date)->format('Y-m-d') : null;
        $model->job_due_date = ($data->job_due_date != null) ? Carbon::parse($data->job_due_date)->format('Y-m-d') : null;
        $model->job_completed_date = ($data->job_completed_date != null) ? Carbon::parse($data->job_completed_date)->format('Y-m-d') : null;
        $model->vehicle_last_visit = $data->last_visit;
        $model->vehicle_last_job = $data->vehicle_last_job;
        $model->vehicle_mileage = $data->vehicle_mileage;
        $model->next_visit_mileage = $data->next_visit_mileage;
        $model->vehicle_next_visit = ($data->next_visit_date != null) ? Carbon::parse($data->next_visit_date)->format('Y-m-d') : null;
        $model->vehicle_next_visit_reason = $data->next_visit_reason;
        $model->vehicle_note = $data->vehicle_note;
        $model->vehicle_complaints = $data->complaint;
        $model->driver = $data->driver;
        $model->driver_contact = $data->driver_contact;
        $model->organization_id = Session::get('organization_id');
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

    public function convertToVO($data,$ackURL = false)
    {
        // Log::info('JobCardService->findAll:- Iterate ' .json_encode($data));
        // Log::info("JobCardService->convertToVO :- Inside ");
        $vo = new JobcardListVO($data,$ackURL);
        // Log::info("JobCardService->convertToVO :- return ");
        return $vo;
    }

    public function convertToCustomerDetailVO($data, $userType = false, $organization_id = false, $person = false, $assoicatedType = false, $activeStateDropdown = false, $activeCityDropdown = false)
    {
        $vo = new CustomerDetailVO($data, $userType, $organization_id, $person, $assoicatedType, $activeStateDropdown, $activeCityDropdown);
        // Log::info("JobCardService->convertToVO :- return ");
        return $vo;
    }

    /* Not using this currently*/
    public function print($request)
    {
        Log::info("JobCardService->print :- Inside ");
        $organization_id = Session::get('organization_id');
        
        $transactions = Transaction::select('transactions.id', 'transactions.order_no', 'transactions.date', 'transactions.due_date', 'transactions.email as email_id', 'transactions.sub_total', 'transactions.billing_name', 'transactions.billing_address', 'transactions.shipping_address', 'transactions.total', 'account_vouchers.display_name AS transaction_type', 'payment_modes.display_name AS payment_method', 'print_templates.data', 'transactions.name', 'transactions.address', 'vehicle_register_details.registration_no', DB::raw('CONCAT(vehicle_makes.name, " - ",vehicle_models.name," - ",vehicle_variants.name) AS make_model_variant'), 'organizations.name as organization_name', 'business_communication_addresses.mobile_no as company_phone', 'business_communication_addresses.address as company_address', 'account_transactions.amount', 'businesses.gst as company_gst', 'transactions.gst as customer_communication_gst', 'transactions.billing_gst as billing_communication_gst', DB::raw('CASE WHEN(transactions.user_type=0) THEN people.`gst_no` ELSE business.gst_no END AS customer_gst'), 'people.gst_no as customer_gst1', 'wms_transactions.vehicle_mileage as warranty_km', 'hrm_employees.first_name as assigned_to', 'transactions.mobile as customer_mobile', DB::raw('if(vehicle_register_details.driver is null,transactions.name,vehicle_register_details.driver)as driver'), DB::raw('if(vehicle_register_details.driver_mobile_no is null,transactions.mobile,vehicle_register_details.driver_mobile_no)as driver_mobile_no'), 'vehicle_register_details.warranty_km as warranty', "vehicle_register_details.insurance", "vehicle_register_details.engine_no", "vehicle_register_details.chassis_no", DB::raw("(GROUP_CONCAT(DISTINCT vehicle_spec_masters.name,':',vehicle_specification_details.name)) as spec"), 'wms_transactions.job_due_date as job_due_on', 'wms_transactions.vehicle_last_visit as last_visit_on', 'wms_transactions.vehicle_next_visit as next_visit_on', 'wms_transactions.vehicle_complaints');
        
        $transactions->leftjoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');
        
        $transactions->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        
        $transactions->leftjoin('vehicle_variants', 'vehicle_variants.id', '=', 'vehicle_register_details.vehicle_variant_id');
        
        $transactions->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id');
        
        $transactions->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_register_details.vehicle_make_id');
        
        $transactions->leftjoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id');
        
        $transactions->leftjoin('payment_modes', 'payment_modes.id', '=', 'transactions.payment_mode_id');
        
        $transactions->leftjoin('print_templates', 'print_templates.id', '=', 'account_vouchers.print_id');
        
        $transactions->leftjoin('organizations', 'organizations.id', '=', 'transactions.organization_id');
        
        $transactions->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id');
        
        $transactions->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id');
        
        $transactions->leftJoin('people', function ($join) use ($organization_id)
        {
            
            $join->on('people.person_id', '=', 'transactions.people_id')
            ->
            where('people.organization_id', $organization_id)
            ->
            where('transactions.user_type', '0');
        });
        
        $transactions->leftJoin('people AS business', function ($join) use ($organization_id)
        {
            
            $join->on('business.business_id', '=', 'transactions.people_id')
            ->
            where('business.organization_id', $organization_id)
            ->
            where('transactions.user_type', '1');
        });
        
        $transactions->leftjoin('account_entries', 'account_entries.reference_transaction_id', '=', 'transactions.id');
        
        $transactions->leftjoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id');
        
        $transactions->leftjoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
        
        $transactions->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'transactions.employee_id');
        
        $transactions->leftjoin('registered_vehicle_specs', 'registered_vehicle_specs.registered_vehicle_id', '=', 'vehicle_register_details.id');
        $transactions->leftjoin('vehicle_spec_masters', 'vehicle_spec_masters.id', '=', 'registered_vehicle_specs.spec_id');
        $transactions->leftjoin('vehicle_specification_details', 'vehicle_specification_details.id', '=', 'registered_vehicle_specs.spec_value_id');
        
        if ($remote == null) {
            
            $transactions->where('transactions.organization_id', $organization_id);
        }
        
        $transactions->where('transactions.id', $request->id);
        
        $transaction = $transactions->first();
        // dd($transaction);
        
        $exact_address = $transaction->address;
        
        $address = str_replace("<br>", " ", $exact_address);
        
        $exact_billing_address = $transaction->billing_address;
        $exact_shipping_address = $transaction->shipping_address;
        
        $billing_address = str_replace("<br>", " ", $exact_billing_address);
        
        $shipping_address = str_replace("<br>", " ", $exact_shipping_address);
        
        $last_updated_datas = Transaction::select('transactions.id', 'vehicle_register_details.registration_no', 'wms_transactions.job_date', 'transactions.reference_no');
        $last_updated_datas->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id');
        $last_updated_datas->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        $last_updated_datas->where('vehicle_register_details.registration_no', $transaction->registration_no);
        $last_updated_datas->where(function ($query) {
            $query->where('wms_transactions.jobcard_status_id', '!=', "8")
            ->orWhere('wms_transactions.jobcard_status_id', '=', null);
        });
            $last_updated_datas->where('transactions.organization_id', $organization_id);
            $last_updated_datas->orderBy('transactions.id', "DESC");
            $last_updated_data = $last_updated_datas->first();
            
            $job_card_transaction_id = $request->id;
            
            $checklist = VehicleChecklist::select('vehicle_checklists.name as checklist', 'vehicle_checklists.id AS checklist_id', 'wms_checklists.transaction_id', 'wms_checklists.checklist_status', 'wms_checklists.checklist_notes as notes', 'wms_checklists.id AS id');
            $checklist->LeftJoin('wms_checklists', function ($join) use ($job_card_transaction_id) {
                
                $join->on('wms_checklists.checklist_id', '=', 'vehicle_checklists.id');
                
                $join->where('wms_checklists.transaction_id', '=', $job_card_transaction_id);
            });
                
                $checklist->orderby('vehicle_checklists.name', 'asc')->get();
                // dd($checklist);
                $job_card_checklist = $checklist->skip(5)
                ->take(13)
                ->get();
                
                $first_checklists = $checklist->skip(1)
                ->take(5)
                ->get();
                
                $checklist_fuellevel = $checklist->skip(0)
                ->take(1)
                ->get();
                
                $transaction_items = TransactionItem::select('transaction_items.id', DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'), 'inventory_items.hsn', 'tax_groups.display_name AS gst', 'discounts.value AS discount', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent')->
                leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                ->
                leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                ->
                leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')
                ->
                where('transaction_items.transaction_id', $transaction->id)
                ->get();
                // dd($transaction_items);
                
                $job_card_items = TransactionItem::select('transaction_items.amount as amt', 'transaction_items.quantity as qty', 'inventory_items.name as item_name');
                $job_card_items->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id');
                $job_card_items->where('transaction_items.transaction_id', $request->id);
                $jc_items = $job_card_items->get();
                
                $discount_result = TransactionItem::select(DB::raw('GROUP_CONCAT(discount) AS discount'), DB::raw('SUM(amount) AS amount'))->where('transaction_id', $transaction->id)
                ->groupby('discount_id')
                ->get();
                
                $tax_result = TransactionItem::select('tax', DB::raw('SUM(amount) AS amount'))->where('transaction_id', $transaction->id)
                ->groupby('tax_id')
                ->get();
                
                $discount_array = [];
                
                $tax_array = [];
                
                $discount = [];
                
                $tax = [];
                
                if ($discount_result != null) {
                    
                    for ($i = 0; $i < count($discount_result); $i ++) {
                        
                        $discount_array[] = json_decode(
                            str_replace('}', ', "total-amount": ' . $discount_result[$i]->amount . ' }',
                                str_replace('],[', ',', $discount_result[$i]->discount)
                                ),
                            true);
                    }
                }
                
                asort($discount_array);
                
                if ($tax_result != null) {
                    
                    for ($i = 0; $i < count($tax_result); $i ++) {
                        
                        $tax_array[] = json_decode(
                            str_replace('}', ', "total-amount": ' . $tax_result[$i]->amount . ' }',
                                str_replace('],[', ',', $tax_result[$i]->tax)
                                ),
                            true);
                    }
                }
                
                asort($tax_array);
                
                $discount_id = null;
                
                $discount_val = null;
                
                foreach ($discount_array as $value) {
                    
                    if ($discount_id != $value["id"]) {
                        
                        $discount_id = $value["id"];
                        
                        $discount_val = $value["amount"];
                    } else {
                        
                        foreach ($discount as $key => $t) {
                            
                            if ($t["id"] == $value["id"]) {
                                
                                unset($discount[$key]);
                            }
                        }
                        
                        $discount_val += $value["amount"];
                    }
                    
                    if ($value["id"] != null) {
                        
                        $discount[] = [
                            "id" => $value["id"],
                            "key" => $value["name"] . " @" . $value["value"] . "% on " . $value['total-amount'],
                            "value" => "- " . Custom::two_decimal($discount_val)
                        ];
                    }
                }
                
                $tax_id = null;
                
                $tax_val = null;
                
                if (count(array_filter($tax_array)) > 0) {
                    
                    foreach (array_filter($tax_array) as $tax_arr) {
                        
                        foreach ($tax_arr as $value) {
                            
                            if ($tax_id != $value["id"]) {
                                
                                $tax_id = $value["id"];
                                
                                $tax_val = $value["amount"];
                            } else {
                                
                                foreach ($tax as $key => $t) {
                                    
                                    if ($t["id"] == $value["id"]) {
                                        
                                        unset($tax[$key]);
                                    }
                                }
                                
                                $tax_val += $value["amount"];
                            }
                            
                            $tax[] = [
                                "id" => $value["id"],
                                "key" => $value["name"] . " @" . $value["value"] . "% on " . $value['total-amount'],
                                "value" => Custom::two_decimal($tax_val)
                            ];
                        }
                    }
                }
                
                $no_tax_values = TransactionItem::select('transaction_items.id', DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'), 'transaction_items.quantity', 'transaction_items.rate', DB::raw('transaction_items.discount_value/100 AS discount'), DB::raw('((transaction_items.amount) - (`transaction_items`.`amount`) *( CASE WHEN `transaction_items`.`discount_value` IS NULL  THEN 0 ELSE `transaction_items`.`discount_value`/100 END)) AS amount'), DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'), DB::raw('SUM(((transaction_items.rate) -(transaction_items.rate) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_rate'))->
                leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                ->
                leftjoin('group_tax', 'group_tax.group_id', '=', 'transaction_items.tax_id')
                ->
                leftjoin('taxes', 'taxes.id', '=', 'group_tax.tax_id')
                ->
                where('transaction_items.transaction_id', $transaction->id)
                ->
                groupby('transaction_items.id')
                ->get();
                
                // dd($no_tax_values);
                
                $invoice_items = TransactionItem::select('transaction_items.id', DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'), 'inventory_items.hsn', 'tax_groups.display_name AS gst', 'transaction_items.discount AS discount', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent', DB::raw('((transaction_items.amount)-(CASE WHEN discounts.value is null THEN 0 ELSE discounts.value END))as t_amount'))->
                leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                ->
                leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                ->
                leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')
                ->
                where('transaction_items.transaction_id', $transaction->id)
                ->get();
                // dd($invoice_items);
                
                $total_qty = TransactionItem::select('transaction_items.id', DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS name'), 'inventory_items.hsn', 'tax_groups.display_name AS gst', 'discounts.value AS discount', 'transaction_items.quantity', 'transaction_items.rate', 'transaction_items.amount', 'tax_groups.display_name AS tax', 'transaction_items.is_discount_percent', DB::raw('sum(transaction_items.quantity) AS total_qty'))->
                leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                ->
                leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                ->
                leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')
                ->
                where('transaction_items.transaction_id', $transaction->id)
                ->first();
                
                $total_amount = TransactionItem::select('transaction_items.id', DB::raw('sum(discounts.value)AS total_discount'), DB::raw('sum((transaction_items.amount)-(CASE WHEN discounts.value is null THEN 0 ELSE discounts.value END))as total_amount'))->
                leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                ->
                leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                ->
                leftjoin('discounts', 'discounts.id', '=', 'transaction_items.discount_id')
                ->
                where('transaction_items.transaction_id', $transaction->id)
                ->first();
                
                $unique_tax = TransactionItem::select('transaction_items.id AS item_id', 'inventory_items.name AS item',
                    'tax_groups.display_name AS tax', 'tax_types.id AS tax_type', 'transaction_items.quantity AS qty',
                    'transaction_items.rate AS rate', 'transaction_items.amount AS amount', 'transaction_items.discount_value AS discount', DB::raw('transaction_items.amount * transaction_items.discount_value/100 as discount_amount'), DB::raw('SUM((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) AS taxable'), DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS Tax_amount'), 'taxes.display_name',
                    'taxes.value AS tax_value', 'tax_groups.name')->
                    leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                    ->
                    leftjoin('group_tax', 'group_tax.group_id', '=', 'transaction_items.tax_id')
                    ->
                    leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                    ->
                    leftjoin('taxes', 'taxes.id', '=', 'group_tax.tax_id')
                    ->
                    leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
                    ->
                    where('transaction_items.transaction_id', $transaction->id)
                    ->
                    groupby('taxes.Name')
                    ->orderby('taxes.Name', 'taxable')
                    ->get();
                    
                    $invoice_tax = $unique_tax->unique('name');
                    
                    $hsn_b2b_tax = TransactionItem::select('transaction_items.id AS item_id',
                        'inventory_items.name AS item', 'inventory_items.hsn', 'tax_groups.display_name AS tax',
                        'tax_types.id AS tax_type', 'transaction_items.quantity AS qty',
                        'transaction_items.rate AS rate', 'transaction_items.amount AS amount', 'transaction_items.discount_value AS discount', DB::raw('transaction_items.amount * transaction_items.discount_value/100 as discount_amount'), DB::raw('SUM((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) AS taxable'), DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS Tax_amount'), 'taxes.display_name',
                        'taxes.value AS tax_value', 'tax_groups.name')->
                        leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
                        ->
                        leftjoin('group_tax', 'group_tax.group_id', '=', 'transaction_items.tax_id')
                        ->
                        leftjoin('tax_groups', 'tax_groups.id', '=', 'transaction_items.tax_id')
                        ->
                        leftjoin('taxes', 'taxes.id', '=', 'group_tax.tax_id')
                        ->
                        leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
                        ->
                        where('transaction_items.transaction_id', $transaction->id)
                        ->
                        groupby('taxes.Name', 'inventory_items.hsn')
                        ->orderby('inventory_items.hsn', 'DESC')
                        ->get();
                        
                        $hsn_based_invoice_tax = $hsn_b2b_tax->unique('item_id');
                        
                        $data = [];
                        
                        if ($request->data != null) {
                            
                            $data['transaction_data'] = $request->data;
                        } else {
                            
                            $data['transaction_data'] = $transaction->data;
                        }
                        
                        $data['transaction_type'] = $transaction->transaction_type;
                        
                        $data['estimate_no'] = $transaction->order_no;
                        // dd($data['estimate_no']);
                        
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
                        
                        $data['amount'] = $transaction->amount;
                        
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
                        
                        $data['no_tax_sale'] = $no_tax_values;
                        
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
                        Log::info("JobCardService->print :- return ");
                        
                        return $data;
    }


    public function job_card_acknowledgement($transaction_id)
    {

        
    // dd( decrypt($transaction_id));
    try {
        Log::info("JobCardService->print :- Inside Try");
          $transaction_id = decrypt($transaction_id);
     
        $orgInfo = $this->jobCardRepo->findJobcardOrgById($transaction_id);
        $orgId = $orgInfo->org_id;
    
        $customer_details = $this->jobCardRepo->findJobcardCustomerDetailAssoById($transaction_id);


        $jobcard = $this->jobCardRepo->findJobCardWithDetailById($transaction_id);
        $jobcardDetail = $jobcard->jobCardDetail;

        $complaints = $jobcardDetail->complaints;
        $transaction_items = $this->jobCardRepo->findJobcardItemsByJobcardId($transaction_id);
       
        // spilt inventory items by category
        $parts = [];
        $service = [];

        if (count($transaction_items) > 0) {
            foreach ($transaction_items as $items) {
                if ($items->category_type_id == 1) {
                    array_push($parts, $items);
                } else if ($items->category_type_id == 2) {
                    array_push($service, $items);
                }
            }
        }

        $items = [
            'parts' => $parts,
            'service' => $service
        ];

        if($jobcardDetail)
        {

            $transactionType = $this->accountRepo->findByOrgIdAndType($orgId, $this->type);
             
            $historical_jc_infos = $this->jobCardRepo->findPreviousJobcards($jobcardDetail->registration_id,$transactionType->id,$orgId);
        
        }
        
        $checkLists = $this->jobCardRepo->findCheckListByJobCardId($transaction_id);
        
        // remove empty element in array
        $checklistData = collect($checkLists)->filter(function ($checklist) {
          
           return $checklist->id != null ;
        });

        $beforeImg =  $this->jobCardRepo->findJobcardAttachmentByIdAndType($transaction_id,1); 
        $progressImg =  $this->jobCardRepo->findJobcardAttachmentByIdAndType($transaction_id,2); 
        $afterImg =  $this->jobCardRepo->findJobcardAttachmentByIdAndType($transaction_id,3); 

        // set path to image file
        $beforeImg = collect($beforeImg)->map(function ($item) use ($orgId)  {
            $item->image_url = $this->getImagePath($orgId) . $item->origional_file;
            return $item;
        });

        $progressImg = collect($progressImg)->map(function ($item) use ($orgId)  {
            $item->image_url = $this->getImagePath($orgId) . $item->origional_file;
            return $item;
        });

        $afterImg = collect($afterImg)->map(function ($item) use ($orgId)  {
            $item->image_url = $this->getImagePath($orgId) . $item->origional_file;
            return $item;
        });

        $images = [
            "beforeImg" => $beforeImg,
            "progressImg" => $progressImg,
            "afterImg" => $afterImg
        ];


        $custom_values = OrgCustomValue::select('data1 as data1')
                 ->where('screen','customer_jc_status_view_page')
                 ->where('organization_id',$orgId)
                 ->get();
        //dd($vehicleCheckListData);

        return  [
            'company_info' => $orgInfo ,
            'customer_details' => $customer_details ,
            'complaints' => $complaints , 
            'checklists' => $checklistData ,
            'items' => $items,
            'imges' => $images ,
            'custom_values' => $custom_values, 
            'historical_jc_infos' => $historical_jc_infos,
            'organization_id' => $orgId 
        ];
     } catch (DecryptException $e) {
        Log::info("JobCardService->print :- Inside Catch");
            //TODO: Add Exception Message
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
     }
     
    }

    public function sendSMS($id){
        
        Log::info("JobCardService->sendSMS :- Inside ");
            /* TODO: Code will changed after merging code  */
            $jobcardAssocDetail = $this->jobCardRepo->findJobcardCustomerDetailAssoById($id);
            // TODO: What is purpose of showing date?
            $date = Carbon::now();

            $current_date =  $date->format('d-m-Y');
        
            $url = url('job_card_acknowledgement/');
            $encryptedURL = generateEncryptedURL($url,$id);


        

             /* SMS Send code */
            $content_addresed_to = $jobcardAssocDetail->customer_name;
            $mobile_no = $jobcardAssocDetail->customer_mobile;
            $organization_id = Session::get('organization_id');
            $subject = "Propel - Job Card Acknowledgement";
            $message =  "Job Card #"." ".$jobcardAssocDetail->jobcard_no." "."for registration # ".$jobcardAssocDetail->registration_no.". Click link for details, ".$encryptedURL;
            $sms_notify_model = $this->SmsNotificationService->save($mobile_no, $subject, $content_addresed_to, $message, $organization_id, "TRANSACTION");


            // TODO: After merging code It will be removed
             // $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),"9361673164", $sms_content);
            // Custom::add_addon('sms');
            
        Log::info("JobCardService->sendSMS :- Return ");
            return [
                'message' => pStatusSuccess(),
                'data' => "SMS request registered successfully."
            ];

     }
    
}