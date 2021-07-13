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
use App\Http\Controllers\Tradewms\Invoice\InvoiceService;
use App\WmsVehicleOrganization;
use App\PersonAddressType;
use App\PersonCommunicationAddress;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(InvoiceService $serv)
    {
        $this->serv = $serv;
    }
    public function index($id,Request $request,$type= false)
    {
        Log::info('InvoiceController->index:-Inside ');
        $entities = $this->serv->findAll($id,$request,$type);
        Log::info('InvoiceController->index:- Return ');
        return $entities;
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

    public function invoice_from_jobcard($id,$type=false)
    {
       //dd($type);
       Log::info('InvoiceController->invoice_from_jobcard:-Inside ');
       $entities = $this->serv->invoice_from_jobcard($id,$type);
       Log::info('InvoiceController->invoice_from_jobcard:- Return ');
       return $entities;
    }
}
