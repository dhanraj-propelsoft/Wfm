<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleChecklist;
use App\Transaction;
use App\TransactionItem;
use App\WmsTransaction;
use App\WmsAttachment;
use App\TaxGroup;
use App\WmsChecklist;
use App\OrgCustomValue;
use App\AccountVoucher;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
use App\Custom;
use Validator;
use Session;
use DB;

use Illuminate\Support\Facades\Log;
class VehicleChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $organization_id = Session::get('organization_id');
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
        $checklists = VehicleChecklist::get();

        return view('trade_wms.vehicle_checklist', compact('checklists','state','city','title','payment','terms','group_name'));
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.vehicle_checklist_create');
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
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',        
        ]);

        $organization_id = Session::get('organization_id');

        $checklist = new VehicleChecklist;
        $checklist->name = $request->input('name');
        $checklist->display_name = $request->input('display_name');
        $checklist->description = $request->input('description');
        $checklist->organization_id = $organization_id;
        $checklist->save();

        Custom::userby($checklist, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Checklist'.config('constants.flash.added'), 'data' => ['id' => $checklist->id, 'name' => $checklist->name, 'display_name' => $checklist->display_name, 'description' => ($checklist->description != null) ? $checklist->description : ""]]);
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
       //dd($request->all());
       $organization_id = Session::get('organization_id');

        $checklist = VehicleChecklist::where('id', $id)->where('organization_id', $organization_id)->first();
        if(!$checklist) abort(403);

        return view('trade_wms.vehicle_checklist_edit', compact('checklist'));
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
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $checklist = VehicleChecklist::findOrFail($request->input('id'));
        $checklist->name = $request->input('name');
        $checklist->display_name = $request->input('display_name');
        $checklist->description = $request->input('description');        
        $checklist->save();

        Custom::userby($checklist, false);
       
        return response()->json(['status' => 1, 'message' => 'Checklist'.config('constants.flash.updated'), 'data' => ['id' => $checklist->id, 'name' => $checklist->name, 'display_name' => $checklist->display_name, 'description' => ($checklist->description != null) ? $checklist->description : "", 'status' => $checklist->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       // dd($request->all());
        $checklist = VehicleChecklist::findOrFail($request->input('id'));
        $checklist->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'checklist'.config('constants.flash.deleted'), 'data' => []]);
    }

     public function checklistby_id($transaction_id,$organization_id)
    {
        
        Log::info("VehicleCheckListController->checklistby_id :- Inside Of The Function"); 
        if($organization_id){
            $organization_id = $organization_id;
        }
        else{
            $organization_id = Session::get('organization_id');
        }

         $company_info = Transaction::select('transactions.organization_id AS org_id','organizations.name AS org_name','business_communication_addresses.address AS org_address','business_communication_addresses.mobile_no AS org_ph','businesses.gst AS org_gst')->leftjoin('organizations','organizations.id','=','transactions.organization_id')->leftjoin('businesses','businesses.id','=','organizations.business_id')->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id')->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->first();

         //dd($company_info);

         $customer_details = Transaction::select('transactions.name AS customer_name','transactions.mobile AS customer_mobile','vehicle_register_details.registration_no',DB::raw('CONCAT(vehicle_makes.name, " - ",vehicle_models.name," - ",vehicle_variants.name) AS make_model_variant'),'vehicle_jobcard_statuses.display_name AS current_status', 'wms_transactions.job_date AS last_updated','wms_transactions.vehicle_complaints AS complaints')
                        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                        ->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')
                        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
                        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id')
                        ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')
                        ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_register_details.vehicle_make_id')
                        ->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->first();

        $complaints = Transaction::select('wms_transactions.vehicle_complaints AS complaint')
                                 ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                                 ->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->get();
                                 // dd($complaints);
        $checklists = WmsChecklist::select('wms_checklists.id','vehicle_checklists.name','wms_checklists.checklist_notes')
                                ->leftjoin('vehicle_checklists','vehicle_checklists.id','=','wms_checklists.checklist_id')
                                ->where('transaction_id',$transaction_id)->where('vehicle_checklists.organization_id',$organization_id)->get();

        $Works_Spares =  TransactionItem::select(DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS item_name'),'transaction_items.quantity','transaction_items.amount',DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'))->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')->leftjoin('taxes','taxes.id','=','group_tax.tax_id')->where('transaction_items.transaction_id',$transaction_id)->where('inventory_items.organization_id',$organization_id)->groupby('transaction_items.id')->get();

        Log::info("VehicleChecklistController->checklistby_id :- Get transaction_id".$transaction_id. ' --> Get organization_id'.$organization_id);
       $transaction=Transaction::select('transactions.id','transactions.name','transactions.order_no',DB::raw('CASE WHEN (transactions.total = 0) THEN 0.00 ELSE transactions.total END AS total'),'organizations.name as company_name','transactions.sub_total','transactions.total','transactions.approval_status','transactions.reference_id','transactions.transaction_type_id','transactions.approved_on as approved_date','transactions.signature','transactions.organization_id')->leftjoin('organizations','organizations.id','=','transactions.organization_id')->where('transactions.id',$transaction_id)->where('transactions.organization_id',$organization_id)->first();
        
       Log::info("VehicleCheckListController->checklistby_id :-Get Transaction Data ".$transaction);
       if($transaction){
           Log::info("VehicleCheckListController->checklistby_id :- Get Transaction Total".$transaction->total);
       }



        $photos = WmsAttachment::select('origional_file','organization_id','image_category')->where('transaction_id',$transaction_id)->where('wms_attachments.organization_id',$organization_id)->get();  

        $custom_values = OrgCustomValue::select('data1 as data1')
                 ->where('screen','customer_jc_status_view_page')
                 ->where('organization_id',$organization_id)
                 ->get();
     
    
       
           // dd($transactions);    
            /* $transaction_lists=TransactionItem::select('transaction_items.id','transaction_items.item_id','transaction_items.quantity','transaction_items.amount','transaction_items.tax','inventory_items.name as name','taxes.display_name')->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')->leftjoin('taxes','taxes.id','=','transaction_items.tax_id')->where('transaction_items.transaction_id',$transaction_id)->get();*/

            Log::info("VehicleCheckListController->checklistby_id :- End Of the Function");
        return view('trade_wms.estimation',compact('company_info','customer_details','complaints','checklists','Works_Spares','transaction','photos','custom_values','custom_value'));
    }

    public function jc_acknowladge($transaction_id,$organization_id)
    {
        if($organization_id){
            $organization_id = $organization_id;
        }
        else{
            $organization_id = Session::get('organization_id');
        }

         $company_info = Transaction::select('transactions.organization_id AS org_id','organizations.name AS org_name','business_communication_addresses.address AS org_address','business_communication_addresses.mobile_no AS org_ph','businesses.gst AS org_gst')->leftjoin('organizations','organizations.id','=','transactions.organization_id')->leftjoin('businesses','businesses.id','=','organizations.business_id')->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','businesses.id')->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->first();

         $customer_details = Transaction::select('transactions.name AS customer_name','transactions.mobile AS customer_mobile','vehicle_register_details.registration_no',DB::raw('CONCAT(vehicle_makes.name, " - ",vehicle_models.name," - ",vehicle_variants.name) AS make_model_variant'),'vehicle_jobcard_statuses.display_name AS current_status', 'wms_transactions.job_date AS last_updated','wms_transactions.vehicle_complaints AS complaints','wms_transactions.vehicle_note')
                        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                        ->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')
                        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
                        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id')
                        ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')
                        ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_register_details.vehicle_make_id')
                        ->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->first();

        $complaints = Transaction::select('wms_transactions.vehicle_complaints AS complaint')
                                 ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                                 ->where('transactions.organization_id',$organization_id)->where('transactions.id',$transaction_id)->whereNull('transactions.deleted_at')->get();
                                 // dd($complaints);
        $checklists = WmsChecklist::select('wms_checklists.id','vehicle_checklists.name','wms_checklists.checklist_notes')
                                ->leftjoin('vehicle_checklists','vehicle_checklists.id','=','wms_checklists.checklist_id')
                                ->where('transaction_id',$transaction_id)->where('vehicle_checklists.organization_id',$organization_id)->get();

        $Works_Spares =  TransactionItem::select(DB::raw('CONCAT_WS(CHAR(13),inventory_items.name,transaction_items.description) AS item_name'),'transaction_items.quantity','transaction_items.amount',DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'))->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')->leftjoin('taxes','taxes.id','=','group_tax.tax_id')->where('transaction_items.transaction_id',$transaction_id)->where('inventory_items.organization_id',$organization_id)->groupby('transaction_items.id')->get();


       $transaction=Transaction::select('transactions.*','transactions.id','transactions.name','transactions.order_no','transactions.total','organizations.name as company_name','transactions.sub_total','transactions.total','transactions.approval_status','transactions.reference_id','transactions.transaction_type_id','transactions.approved_on as approved_date','transactions.signature','transactions.organization_id')->leftjoin('organizations','organizations.id','=','transactions.organization_id')->where('transactions.id',$transaction_id)->where('transactions.organization_id',$organization_id)->first();

        if($transaction)
       {
        $job_c = AccountVoucher::where('organization_id',$organization_id)
        ->where('account_vouchers.name',"job_card")
        ->where('account_vouchers.delete_status',0)->first()->id;
        //dd($job_c);
       
            $historical_jc_infos = Transaction::select('vehicle_register_details.registration_no','transactions.order_no','transactions.id','wms_transactions.job_date')
            ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
            ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
            ->where('transactions.organization_id',$organization_id)
           ->where('transactions.user_type','=', $transaction->user_type)
           ->where('transactions.people_id','=',$transaction->people_id)
           ->where('transactions.transaction_type_id',$job_c)
           ->whereNull('transactions.deleted_at')
           ->get();
           //dd($historical_jc_infos);
        
        }
        

        $photos = WmsAttachment::select('origional_file','organization_id','image_category')->where('transaction_id',$transaction_id)->where('wms_attachments.organization_id',$organization_id)->get();  

        $custom_values = OrgCustomValue::select('data1 as data1')
                 ->where('screen','customer_jc_status_view_page')
                 ->where('organization_id',$organization_id)
                 ->get();


        return view('trade_wms.ac_page',compact('company_info','customer_details','complaints','checklists','Works_Spares','transaction','photos','custom_values','custom_value','historical_jc_infos','organization_id'));
    }

    public function change_status(Request $request,$id){

        $organization_id = session::get('organization_id');
        $result = array();
        $imagedata = base64_decode($request->input('img_data'));
        $file = md5(date("dmYhisA"));
        $filename = $file.'.png';
        //Location to where you want to created sign image
        $filepath = public_path('signatures/organization_id-'.$organization_id.'/transaction_id-'.$id);
       if (!file_exists(public_path('signatures/organization_id-'.$organization_id.'/transaction_id-'.$id))) {
              mkdir(public_path('signatures/organization_id-'.$organization_id.'/transaction_id-'.$id), 0777, true);

        }

        $full_path = $filepath.'/'.$filename;
       
        file_put_contents($full_path,$imagedata);
       // dd($request->all());
        if($request->reference_id != null){
            
            $status= Transaction::where('reference_id',$request->reference_id)->findOrFail($id);
            $status->approval_status = $request->approval_status;
            $status->approved_on = $request->approved_on;
            $status->signature = $filename;
            $status->save();
            if($status->approval_status == "1"){
                WmsTransaction::where('transaction_id',$request->reference_id)
                ->update(['jobcard_status_id' => $request->input('job_card_status')]); 
            }
        }
        elseif($request->reference_id == null){
   
            $job_card_status = WmsTransaction::where('transaction_id',$id)
                ->update(['jobcard_status_id' => $request->input('job_card_status')]); 

            if($job_card_status == 1){
                $update_sign = Transaction::findOrFail($id);
                $update_sign->signature = $filename;
                $update_sign->approval_status = $request->approval_status;
                $update_sign->approved_on = $request->approved_on;
                $update_sign->save();
            }
        }

        return response()->json(['status' => 1]);
          
    }
    
}
