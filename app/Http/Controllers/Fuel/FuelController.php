<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Weekday;
use App\InventoryItem;
use App\Organization;
use App\user;
use App\FsmTank;
use App\FsmPumpMechine;
use App\FsmDipMeasurement;
use App\FsmPump;
use App\FsmDipReading;
use App\HrmEmployee;
use App\FsmShiftCashManage;
use App\FsmShiftPumpCashDetail;
use App\FuelCreditSales;
use App\HrmShift;
use App\Transaction;
use App\AccountVoucher;
use App\AccountVoucherType;
use App\ReferenceVoucher;
use App\VehicleRegisterDetail;
use App\VehicleJobcardStatus;
use App\People;
use App\TransactionField;
use App\FieldType;
use App\PaymentMode;
use App\PaymentTerm;
use App\TransactionItem;
use App\TaxGroup;
use App\BusinessCommunicationAddress;
use App\BusinessAddressType;
use App\ServiceType;
use App\VehicleServiceType;
use App\VehicleChecklist;
use App\VehicleMake;
use App\WmsChecklist;
use App\VehicleJobItemStatus;
use App\VehicleSpecMaster;
use App\VehicleSegmentDetail;
use App\CustomerGroping;
use App\Discount;
use App\Business;
use App\TaxType;
use App\JobType;
use App\Country;
use App\Person;
use App\Custom;
use App\State;
use App\Term;
use App\Unit;
use App\City;
use App\Tax;
use App\VehicleMaintenanceReading;
use App\Jobs\SendTransactionEmail;
use App\GlobalItemCategoryType;
use App\WmsTransactionReading;
use App\TransactionFieldValue;
use App\TransactionRecurring;
use App\AccountFinancialYear;
use App\InventoryAdjustment;
use App\InventoryItemGroup;
use App\InventoryItemStock;
use App\GlobalItemCategory;
use App\AccountLedgerType;
use App\VehicleDrivetrain;
use App\InventoryCategory;
use App\WmsReadingFactor;
use App\VehicleFuelType;
use App\GlobalItemModel;
use App\VehicleTyreSize;
use App\VehicleTyreType;
use App\VehicleBodyType;
use App\VehicleSpecification;
use App\RegisteredVehicleSpec;
use App\VehicleCategory;
use App\VehicleVariant;
use App\VehicleRimType;
use App\WmsTransaction;
use App\WmsAttachment;
use App\AccountLedger;
use App\PeopleAddress;
use App\AccountEntry;
use App\AccountGroup;
use App\ShipmentMode;
use App\Jobs\SendSms;
use App\VehicleModel;
use App\VehicleWheel;
use App\VehicleUsage;
use App\PeopleTitle;
use App\FieldFormat;
use App\MultiTemplate;
use App\WmsPriceList;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Session;
use Carbon\Carbon;
use DB;


class FuelController extends Controller
{
    public function tank()
    {
        $organization_id = Session::get('organization_id');

        $tank=FsmTank::select('fsm_tanks.id','fsm_tanks.name','inventory_items.name as product','fsm_tanks.reading_time','fsm_tanks.reading_time1','fsm_tanks.reading_time2','fsm_tanks.status')
        ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
          ->where('fsm_tanks.organization_id',$organization_id)
        ->get();
        return view('fuel_station.tank_index',compact('tank'));
    }

    public function tank_multidestroy(Request $request)
    {      
        $tank = explode(',', $request->id);

        $tank_list = [];

        foreach ($tank as $tank_id) 
        {

            $tankdetails = FsmTank::findOrFail($tank_id);
           
            FsmTank::where('id', $tankdetails->id)->first()->delete();
        }

        return response()->json(['status'=>1, 'message'=>'Tank'.config('constants.flash.deleted'),'data'=>['list' => $tank]]);
    }


     public function tank_create()
    {

        $organization_id = Session::get('organization_id');

        $product =InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id') ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
       
          ->where('organization_id',$organization_id)
          ->pluck('inventory_items.name','inventory_items.id');
        $product->prepend("Select product",'');          


      return view('fuel_station.tank_create',compact('product'));
    }

    public function tankname_check(Request $request) 
    {
           $organization_id = Session::get('organization_id');
        $tankname = FsmTank::where('name', $request->tankname)->where('organization_id',$organization_id)
                   ->first();
        if(!empty($tankname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
     }

    public function tank_store(Request $request)
    {

         
        $organization_id = Session::get('organization_id');

        $liter=$request->get('volume');
        $liter_conversion=(explode(',',$liter));

        $length=$request->get('length');
        $length_conversion=(explode(',',$length));

      
        $tank= new \App\FsmTank;
        $tank->name=$request->get('tankname');
        $tank->display_name=$request->get('tankname');
        $tank->product=$request->get('product');
        $tank->tank_structure=$request->get('tank_structure');
        $tank->reading_time=$request->get('reading_time');
        $tank->reading_time1=$request->get('reading_time1');
        $tank->reading_time2=$request->get('reading_time2');
        $tank->smsto_manager=$request->get('smstomanager');
        $tank->smsto_owner=$request->get('smstoowner');
        $tank->organization_id=$organization_id;
        $tank->status=1;
        $tank->created_by=Auth::user()->id;
        $tank->last_modified_by=Auth::user()->id;
        $tank->save();

        
      foreach ($liter_conversion as $key => $value) {

        $volume=new \App\FsmDipMeasurement;
        $volume->tank_id=$tank->id;
        $volume->product_id=$request->get('product');
        $volume->length= $length_conversion[$key];
        $volume->volume=$value;
        $volume->status=1;
        $volume->organization_id= $organization_id;
        $volume->created_by=Auth::user()->id;
        $volume->last_modified_by=Auth::user()->id;
        $volume->save();
        }

         $product =($tank->product != null) ? InventoryItem::findorFail($tank->product)->name : "";

         return response()->json([ 'message' => 'Tank Name'.config('constants.flash.added'), 'data' =>['id'=>$tank->id,'name'=>$tank->name,'product'=>$product,'reading_time'=>$tank->reading_time,'reading_time1'=>$tank->reading_time1,'reading_time2'=>$tank->reading_time2,'status'=>$tank->status]]);
    }



     public function tank_status(Request $request)
     {
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        FsmTank::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }

    public function tank_edit($id)
    {
      
        $organization_id = Session::get('organization_id');
   
        $tank=FsmTank::select('fsm_dip_measurements.id as length_id','fsm_tanks.id as tank_id','fsm_tanks.product','fsm_tanks.name','fsm_tanks.tank_structure','fsm_tanks.reading_time','fsm_tanks.reading_time1','fsm_tanks.reading_time2','fsm_tanks.smsto_manager','fsm_tanks.smsto_owner','inventory_items.name as productname')
        ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
        ->leftjoin('fsm_dip_measurements','fsm_dip_measurements.tank_id','=','fsm_tanks.id')->where('fsm_tanks.id',$id)
        ->where('fsm_tanks.organization_id',$organization_id)
        ->first();

        $product=InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id') ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
        ->where('organization_id',$organization_id)
        ->pluck('inventory_items.name','inventory_items.id');
        $product->prepend("Select product",'');  

        $length=FsmDipMeasurement::where('tank_id',$id)->pluck('volume','length');
        $length_id=FsmDipMeasurement::leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_dip_measurements.tank_id')
                             ->where('fsm_tanks.id',$id)
                             ->pluck('fsm_dip_measurements.id','fsm_dip_measurements.length');
                         
        return view('fuel_station.tank_edit',compact('tank','product','volume','length','length_id'));

    } 

    public function tanknameedit_check(Request $request) 
    {
        $tankname = FsmTank::where('id','!=', $request->id)  
          ->where('name', $request->tankname)
          ->first();
 
        if(!empty($tankname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function tank_update(Request $request)
    {
       
        
        $length=$request->get('length');
        $length_conversion=(explode(',',$length));

        $length_id= $request->get('length_id');
        $lengthid_conversion=(explode(',',$length_id));

        $volume= $request->get('volume');
        $volume_conversion=(explode(',',$volume));

        $length_new=$request->get('length1');
        $lengthnew_conversion=(explode(',',$length_new));

        $volume_new= $request->get('volume1');
        $volumenew_conversion=(explode(',',$volume_new));
        $total_rows=count($volume_conversion);


        $organization_id = Session::get('organization_id');    
      
            $tank= FsmTank::findOrFail($request->input('tank_id'));
            $tank->name=$request->input('tankname');
            $tank->display_name=$request->input('tankname');
            $tank->product=$request->input('product');
            $tank->tank_structure=$request->input('tank_structure');
            $tank->reading_time=$request->input('reading_time');
            $tank->reading_time1=$request->input('reading_time1');
            $tank->reading_time2=$request->input('reading_time2');
            $tank->smsto_manager=$request->input('smstomanager');
            $tank->smsto_owner=$request->input('smstoowner');
            $tank->last_modified_by=Auth::user()->id;
            $tank->save();
       
        for($i=0;$i<$total_rows;$i++)
        {
           
          
            if(  $volume_conversion[$i] == 0)
            {

              $data= DB::table('fsm_dip_measurements')->where('id',$lengthid_conversion[$i])->delete();
             
            }
            else
            {  
                    

                $data=["tank_id"=>$request->input('tank_id'),"product_id"=>$request->input('product'),"length"=>$length_conversion[$i],"volume"=>$volume_conversion[$i],"status"=>1,"organization_id"=>$organization_id,"created_by" => Auth::user()->id ,"last_modified_by"=>Auth::user()->id];
                

                $measurement=FsmDipMeasurement::updateOrCreate(['id'=>""],$data);
            }


               
            
        }


            $product =($tank->product != null) ? InventoryItem::findorFail($tank->product)->name : "";


             return response()->json([ 'message' => 'Tank Name'.config('constants.flash.updated'), 'data' =>['id'=>$tank->id,'name'=>$tank->name,'product'=>$product,'reading_time'=>$tank->reading_time,'reading_time1'=>$tank->reading_time1,'reading_time2'=>$tank->reading_time2,'status'=>$tank->status]]);

         
    }

    public function pumpmechine()
    {
        $organization_id = Session::get('organization_id');
        $pumpmechine=FsmPumpMechine::select('fsm_pump_mechines.id','fsm_pump_mechines.name','fsm_pump_mechines.status','fsm_tanks.name as tankname')
                    ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pump_mechines.tank_id')

        ->where('fsm_pump_mechines.organization_id',$organization_id)
        ->get();

        return view('fuel_station.pumpmechine_index',compact('pumpmechine'));
    }

    public function pumpmechine_multidestroy(Request $request)
    {      
        $pump = explode(',', $request->id);

        $pump_list = [];

        foreach ($pump as $pump_id) {

            $pumpdetails = FsmPumpMechine::findOrFail($pump_id);
           
           FsmPumpMechine::where('id', $pumpdetails->id)->delete();
        }
      return response()->json(['status'=>1, 'message'=>'pumpmechine'.config('constants.flash.deleted'),'data'=>['list' => $pump]]);
    }
    
    public function pumpmechine_status(Request $request)
   {
        if($request->input('status')==="1")
        {
          $UpdateData=['status' => $request->input('status')];
        }else{
           $UpdateData=['status' => $request->input('status')];
        }

        FsmPumpMechine::where('id', $request->input('id'))->update($UpdateData);
         return response()->json(array('result' => "success",'status'=>$UpdateData));
    }

    public function pumpmechine_create()
    {
        $organization_id = Session::get('organization_id');

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
        $tankname->prepend("Select Tank Name",'');
        return view('fuel_station.pumpmechine_create',compact('tankname'));
    }

    public function pumpmechinename_check(Request $request)
    {
        $mechinename = FsmPumpMechine::where('fsm_pump_mechines.name', $request->pump_mechine)->where('fsm_pump_mechines.tank_id',$request->tank_id)->first();
        if(!empty($mechinename->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }
    public function pumpmechine_store(Request $request)
    {
       
        $organization_id = Session::get('organization_id');
        $pumpmechine= new \App\FsmPumpMechine;
        $pumpmechine->name=$request->get('pumpmechine');
        $pumpmechine->display_name=$request->get('pumpmechine');
        $pumpmechine->tank_id=$request->get('tankname');
        $pumpmechine->status=1;
        $pumpmechine->organization_id=  $organization_id;
        $pumpmechine->created_by=Auth::user()->id;
        $pumpmechine->last_modified_by=Auth::user()->id;
        $pumpmechine->save();

         $tank =($pumpmechine->tank_id != null) ? FsmTank::findorFail($pumpmechine->tank_id)->name : "";

        return response()->json([ 'message' => 'Pump Mechine'.config('constants.flash.added'), 'data' =>['id'=>$pumpmechine->id,'tank'=>$tank,'name'=>$pumpmechine->name,'status'=>$pumpmechine->status]]);

    }

    public function pumpmechine_edit($id)
    {
        $organization_id = Session::get('organization_id');

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
       

        $pumpmechine=FsmPumpMechine::select('fsm_pump_mechines.id as mechine_id','fsm_pump_mechines.name as mechinename','fsm_tanks.id as tank_id')
                    ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pump_mechines.tank_id')
                    ->where('fsm_pump_mechines.id',$id)
                    ->first();
                 
                     
        return view('fuel_station.pumpmechine_edit',compact('pumpmechine','tankname'));

    }
    public function edit_pumpmechinename_check(Request $request)
    {
          
        $mechinename = FsmPumpMechine::where('id','!=', $request->mechine_id)  
          ->where('name', $request->pump_mechine)
           ->where('tank_id', $request->tank_id)
          ->first();
 
        if(!empty($mechinename->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }

    public function pumpmechine_update(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $pumpmechine=FsmPumpMechine::findorFail($request->input('mechine_id'));
        $pumpmechine->name=$request->get('mechine_name');
        $pumpmechine->display_name=$request->get('mechine_name');
        $pumpmechine->tank_id=$request->get('tank_id');
        $pumpmechine->organization_id=  $organization_id;
        $pumpmechine->created_by=Auth::user()->id;
        $pumpmechine->last_modified_by=Auth::user()->id;
        $pumpmechine->save();

        $tank =($pumpmechine->tank_id != null) ? FsmTank::findorFail($pumpmechine->tank_id)->name : "";

          return response()->json([ 'message' => 'Pump Mechine'.config('constants.flash.updated'), 'data' =>['id'=>$pumpmechine->id,'name'=>$pumpmechine->name,'tank'=>$tank,'status'=>$pumpmechine->status]]);
    }
    
    public function pump()
    {
        $organization_id = Session::get('organization_id');

        $pump=FsmPump::select('fsm_pumps.id','fsm_pumps.name','fsm_pumps.status','fsm_tanks.name as tankname','fsm_pump_mechines.name as mechinename')
        ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
        ->leftjoin('fsm_pump_mechines','fsm_pump_mechines.id','=','fsm_pumps.mechine_id')
        ->where('fsm_pumps.organization_id',$organization_id)
        ->get();
        
        return view('fuel_station.pump_index',compact('pump'));
    }

    public function pump_multidestroy(Request $request)
    {
       
       
        $pump = explode(',', $request->id);

        $pump_list = [];

        foreach ($pump as $pump_id) {

            $pumpdetails = FsmPump::findOrFail($pump_id);
           
           FsmPump::where('id', $pumpdetails->id)->first()->delete();
        }
        return response()->json(['status'=>1, 'message'=>'Pump'.config('constants.flash.deleted'),'data'=>['list' => $pump]]);
    }

    public function pump_status(Request $request)
    {
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }

        FsmPump::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }

    public function pump_create()
    {
        $organization_id = Session::get('organization_id');

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
        $tankname->prepend("Select Tank Name",'');
        
        return view('fuel_station.pump_create',compact('tankname'));
    }


    public function get_mechine_list($id)
    {
        $mechine = DB::table("fsm_pump_mechines")
      
        ->where('tank_id',$id)
        ->pluck("name","id");
               
        return response()->json($mechine);
    }
     public function pumpname_check(Request $request)
    {
        $pumpname = FsmPump::where('name', $request->pump_name)->where('tank_id', $request->tank_id)->where('mechine_id', $request->mechine_id)
                   ->first();
        if(!empty($pumpname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }
    public function pump_store(Request $request)
    { 
        
        $organization_id = Session::get('organization_id');
       
        $pump=new \App\FsmPump;
        $pump->name=$request->get('pump_name');
        $pump->tank_id=$request->get('tankname');
        $pump->mechine_id=$request->get('mechinename');
        $pump->description=$request->get('description');
        $pump->status=1;
        $pump->organization_id=$organization_id;
        $pump->created_by=Auth::user()->id;
        $pump->last_modified_by=Auth::user()->id;
        $pump->save();

        $tank =($pump->tank_id != null) ? FsmTank::findorFail($pump->tank_id)->name : "";
        $pumpmechine =($pump->mechine_id != null) ? FsmPumpMechine::findorFail($pump->mechine_id)->name : "";

        return response()->json([ 'message' => 'Pump Name'.config('constants.flash.added'), 'data' =>['id'=>$pump->id,'name'=>$pump->name,'pumpmechine'=>$pumpmechine,'tank'=>$tank,'status'=>$pump->status]]);

    }
    public function pump_edit($id){
     
        $organization_id = Session::get('organization_id');

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
      



        $pump_mechine=FsmPumpMechine::where('organization_id',$organization_id)->pluck('name','id');
       
        $pump=FsmPump::select('fsm_pumps.id as pumpid','fsm_pumps.name as pumpname','fsm_tanks.id as tankid','fsm_pump_mechines.id as pumpmechine_id','fsm_pumps.description')->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
        ->leftjoin('fsm_pump_mechines','fsm_pump_mechines.id','=','fsm_pumps.mechine_id')
        ->where('fsm_pumps.id',$id)
        ->first();
      
        return view('fuel_station.pump_edit',compact('pump','tankname','pump_mechine'));
    }
    public function edit_pumpname_check(Request $request)
    {
       

        $pump = FsmPump::where('id','!=', $request->pump_id)  
          ->where('name', $request->pump_name)
          ->where('mechine_id', $request->mechine_id)
          ->where('tank_id', $request->tank_id)
          ->first();
 
        if(!empty($pump->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }

    public function pump_update(Request $request){
        

        $organization_id = Session::get('organization_id');

        $tank=FsmTank::select('fsm_tanks.name')->where('id',$request->get('tank_id'))->first();
        $tank_name=$tank->name;
       
        $pumpmechine=FsmPumpMechine::select('fsm_pump_mechines.name')->where('id',$request->get('mechine_id'))->first();
        $pumpmechine_name=$pumpmechine->name;
        $description=($tank_name)."/".($pumpmechine_name)."/".$request->get('pump_name');
        $pump=FsmPump::findorFail($request->pump_id);
        $pump->name=$request->get('pump_name');
        $pump->tank_id=$request->get('tank_id');
        $pump->mechine_id=$request->get('mechine_id');
        $pump->description= $description;
         $pump->organization_id=$organization_id;
        $pump->created_by=Auth::user()->id;
        $pump->last_modified_by=Auth::user()->id;
        $pump->save();

        $tank =($pump->tank_id != null) ? FsmTank::findorFail($pump->tank_id)->name : "";
        $pumpmechine =($pump->mechine_id != null) ? FsmPumpMechine::findorFail($pump->mechine_id)->name : "";

         return response()->json([ 'message' => 'Pump Name'.config('constants.flash.added'), 'data' =>['id'=>$pump->id,'name'=>$pump->name,'pumpmechine'=>$pumpmechine,'tank'=>$tank,'status'=>$pump->status]]);
    }

     
    public function dipreading_index()
    {
        $organization_id = Session::get('organization_id');

        $reading=FsmDipReading::select('fsm_dip_readings.id','fsm_tanks.name','fsm_dip_readings.dip_reading','fsm_dip_readings.temparature','fsm_dip_readings.quantity','hrm_employees.first_name',DB::raw('DATE_FORMAT(fsm_dip_readings.created_at, "%d %M, %Y") AS start_date'),DB::raw('TIME_FORMAT(fsm_dip_readings.created_at, "%h:%i") AS start_time'),'fsm_dip_readings.status','fsm_dip_readings.reading_type')
        ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_dip_readings.tank_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','fsm_dip_readings.reading_by')
        ->where('fsm_dip_readings.organization_id',$organization_id)
        ->get();
        return view('fuel_station.dip_reading_index',compact('reading'));

    }

    public function dipreading_multidestroy(Request $request)
    { 
        $reading = explode(',', $request->id);
        
        foreach ($reading as $reading_id)
        {

            $readingdetails = FsmDipReading::findOrFail($reading_id);
           
           FsmDipReading::where('id', $readingdetails->id)->delete();
        }
        return response()->json(['status'=>1, 'message'=>'Dipreading'.config('constants.flash.deleted'),'data'=>['list' => $reading]]);
    }

    public function dipreading_create()
    {
        $person_id=Auth::user()->person_id;

        $organization_id = Session::get('organization_id');

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
        $tankname->prepend("Select Tank Name",'');

        $product =InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id') ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
        ->where('global_item_categories.main_category_id',9)
          ->where('organization_id',$organization_id)
          ->pluck('inventory_items.name','inventory_items.id');
        $product->prepend("Select product",'');          


        $reading_by=HrmEmployee::where('organization_id',$organization_id)->where('person_id',$person_id)->pluck('first_name','id');
     

        return view('fuel_station.dip_reading_create',compact('tankname','product','reading_by'));


    }

     public function get_dipreading($reading,$tank_id)
     {

        $reading = DB::table("fsm_dip_measurements")
                ->where('fsm_dip_measurements.tank_id',$tank_id)
                ->where('fsm_dip_measurements.length',$reading)
                ->select('fsm_dip_measurements.volume')->first(); 
        if($reading!=null){
             $reading= $reading->volume;
        } else
        {
            $reading= 0;
        }    
       return response()->json(['reading'=>$reading]); 
    }

    public function dipreading_store(Request $request)
    {
        
        $date = now()->format("Y-m-d ");  
        $organization_id = Session::get('organization_id');
   
        $reading= new \App\FsmDipReading;

        $reading->date=$date;
        $reading->tank_id=$request->get('tankname');
        $reading->product_id=$request->get('product');
        $reading->reading_type=$request->get('reading_type');
        $reading->dip_reading=$request->get('dip_reading');
        $reading->temparature=$request->get('temparature');
        $reading->quantity=$request->get('quantity');
        $reading->reading_by=$request->get('reading_by');
        $reading->status=1;
        $reading->organization_id=$organization_id;
        $reading->created_by=Auth::user()->id;
        $reading->last_modified_by=Auth::user()->id;
        $reading->save();

        $tank =($reading->tank_id != null) ? FsmTank::findorFail($reading->tank_id )->name : "";
        $reading_by =($reading->reading_by != null) ? HrmEmployee::findorFail($reading->reading_by )->first_name : "";
     

         return response()->json([ 'message' => 'Dip Reading'.config('constants.flash.added'), 'data' =>['id'=>$reading->id,'date'=> $reading->created_at->format('d F, Y'),'tankname'=>$tank,'readingat'=> $reading->created_at->format('h:m'),'reading'=> $reading->dip_reading,'quantity'=> $reading->quantity,'temparature'=> $reading->temparature,'reading_by'=> $reading_by,'status'=>$reading->status]]);
    }

    public function dipreading_status(Request $request) 
    {

        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        FsmDipReading::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }
  public function get_product_list($id)
    {
       
       $date=now()->format("Y-m-d ");
       $check_today=FsmDipReading::where('tank_id',$id)->where('date',today())->first();

        if($check_today==null)
        {

          $mechine = DB::table("fsm_tanks")
          
            ->where('fsm_tanks.id',$id)
            ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
            ->pluck('inventory_items.name','inventory_items.id');

            $type=FsmDipReading::select('fsm_dip_readings.reading_type')
                         ->where('fsm_dip_readings.tank_id',$id)
                          ->where('fsm_dip_readings.date',$date)               
                         ->first();
                         if($type!=null)
                         {
                             $type=$type->reading_type;
                         }
                         else{
                              $type=4;
                         }
                         $tank=2;

        }
        else
        {
            $mechine=0;$type=0;$tank=1;
        }
        
        return response()->json(['data'=>['mechine'=>$mechine,'type'=>$type,'tank'=>$tank]]);
    }
    public function dipreading_edit($id)
    {
        $person_id=Auth::user()->person_id;
     
        $organization_id = Session::get('organization_id');
        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
        $product=InventoryItem::where('organization_id',$organization_id)->pluck('name','id');
       
        $reading=FsmDipReading::select('fsm_dip_readings.*')
                        ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_dip_readings.tank_id')
                        ->leftjoin('inventory_items','inventory_items.id','=','fsm_dip_readings.product_id')
                        ->leftjoin('hrm_employees','hrm_employees.id','=','fsm_dip_readings.reading_by')
                        ->where('fsm_dip_readings.id',$id)
                        ->first();
                     
        $reading_by=HrmEmployee::where('organization_id',$organization_id)->where('person_id',$person_id)->pluck('first_name','id');

        return view('fuel_station.dip_reading_edit',compact('tankname','reading_by','product','reading'));
    }
    public function dipreading_update(Request $request)
    {
        $organization_id = Session::get('organization_id');
        $reading=FsmDipReading::findorFail($request->id);
        $reading->tank_id=$request->tank_id;
        $reading->product_id=$request->product_id;
        $reading->reading_type=$request->reading_type;
        $reading->dip_reading=$request->dip_reading;        
        $reading->temparature=$request->temparature;        
        $reading->quantity=$request->quantity;
        $reading->reading_by=$request->reading_by;
        $reading->organization_id=$organization_id ;
        $reading->created_by=Auth::user()->id;
        $reading->last_modified_by=Auth::user()->id;
        $reading->save();

        $tank =($reading->tank_id != null) ? FsmTank::findorFail($reading->tank_id )->name : "";
        $reading_by =($reading->reading_by != null) ? HrmEmployee::findorFail($reading->reading_by )->first_name : "";
     

        return response()->json([ 'message' => 'Dip Reading'.config('constants.flash.updated'), 'data' =>['id'=>$reading->id,'date'=> $reading->created_at->format('d F, Y'),'tankname'=>$tank,'readingat'=> $reading->created_at->format('h:m'),'reading'=> $reading->dip_reading,'quantity'=> $reading->quantity,'temparature'=> $reading->temparature,'reading_by'=> $reading_by,'reading_type'=>$reading->reading_type,'status'=>$reading->status]]);
    }

    public function shiftmanagement_index()
    {
        $date = now()->format("Y-m-d "); 
        $organization_id = Session::get('organization_id');
        $data= FsmShiftCashManage::where('end_time',null)->latest()->first();
       
        $total_rows= FsmShiftCashManage::where('organization_id',$organization_id)->count();
        if($data == null || $total_rows==0){
           $start_shift=1;
        }
        else
        {
             $start_shift=2;
        }


        $shift=FsmShiftCashManage::select('fsm_shift_pump_cash_details.pump_openmeter','fsm_shift_pump_cash_details.pump_closemeter','fsm_shift_pump_cash_details.pump_testing','fsm_shift_pump_cash_details.pump_salesquantity','fsm_shift_pump_cash_details.pump_sales','fsm_shift_cash_manages.id','hrm_shifts.name as shift_name',DB::raw('DATE_FORMAT(fsm_shift_cash_manages.date, "%d %M, %Y") AS start_date'),'fsm_shift_cash_manages.approvel_status','fsm_shift_cash_manages.pump_id','fsm_pumps.name as pumpname','fsm_tanks.name as tankname','fsm_pump_mechines.name as pumpmechinename','hrm_employees.first_name as employeename',DB::raw('TIME_FORMAT(fsm_shift_cash_manages.start_time, "%h:%i %p") AS start_time'),DB::raw('TIME_FORMAT(fsm_shift_cash_manages.end_time, "%h:%i %p") AS end_time'))

            ->leftjoin('fsm_pumps','fsm_pumps.id','=','fsm_shift_cash_manages.pump_id')
            ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
            ->leftjoin('fsm_pump_mechines','fsm_pump_mechines.tank_id','=','fsm_tanks.id')
            ->leftjoin('hrm_employees','hrm_employees.id','=','fsm_shift_cash_manages.employee_id')
            ->leftjoin('hrm_shifts','hrm_shifts.id','=','fsm_shift_cash_manages.shift_id')
            ->leftJoin('fsm_shift_pump_cash_details', function($join)
                {
                    $join->on('fsm_shift_pump_cash_details.shift_id', '=', 'fsm_shift_cash_manages.shift_id'); 
                    $join->on('fsm_shift_pump_cash_details.pump_id', '=', 'fsm_shift_cash_manages.pump_id'); 
                     $join->on('fsm_shift_pump_cash_details.date', '=', 'fsm_shift_cash_manages.date'); 
                     $join->where('fsm_shift_cash_manages.end_time','!=',null);
                   
                })
            ->where('fsm_shift_cash_manages.organization_id', $organization_id)
            ->groupby('fsm_shift_cash_manages.id') 
            ->orderby('fsm_shift_cash_manages.date', 'DESC')          
            ->get();
         
          
        return view('fuel_station.shiftmanagement_index',compact('shift','start_shift'));

    }


    public function shift_multidestroy(Request $request)
    {
       
        $shift = explode(',', $request->id);

        $shift_list = [];

        foreach ($shift as $key => $value)
        {

            $shiftdetails = FsmShiftCashManage::findOrFail($shift[$key]);
          
            $shift_date=$shiftdetails->date;
            $shift_id=$shiftdetails->shift_id;
            $pump_id=$shiftdetails->pump_id;

           
           
           FsmShiftCashManage::where('id', $shiftdetails->id)->first()->delete();
           if($shiftdetails->end_time !=null)
           {

                FsmShiftPumpCashDetail::where('shift_id',$shift_id)->where('date',$shift_date)->where('pump_id',$pump_id)->first()->delete();

            }

          
        }
      return response()->json(['status'=>1, 'message'=>'Shift Details'.config('constants.flash.deleted'),'data'=>['list' => $shift]]);
    }
    public function shiftmanagement_create()
    {

        $organization_id = Session::get('organization_id');
        $mytime = date("H:i");

        $tankname=FsmTank::where('organization_id',$organization_id)->pluck('name','id');
        $tankname->prepend("Select Tank Name",'');

        $employee=HrmEmployee::where('status',1)->where('organization_id', $organization_id)->pluck('first_name','id');
        $employee->prepend("Select Employee Name",'');
      
        $shift=HrmShift::where('status',1)->where('organization_id', $organization_id)->pluck('name','id');
        $shift->prepend("Select Shift","");    

        $pumpname=FsmPump::select('fsm_pumps.name as pumpsname','fsm_pumps.id as pumpid','inventory_items.name as productname','inventory_items.id as productid')
            ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
            ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
            ->where('fsm_pumps.organization_id',$organization_id)
            ->get();
           
        return view('fuel_station.shiftmanagement_startpump',compact('tankname','employee','mytime','pumpname','shift'));
    }
    public function get_shift($id){
      
        $date = now()->format("Y-m-d "); 
        $shift = FsmShiftCashManage::where('shift_id','=', $id)  
          ->where('date', $date)
          ->first();
        
          if ($shift != null){
            $shift=1;
          }
          else{
            $shift=0;
          }
            return response()->json($shift);
    }

    public function get_pump_list($id)
    {
       
        $pump = DB::table("fsm_pumps")
      
        ->where('fsm_pumps.tank_id',$id)
        
        ->pluck('fsm_pumps.name','fsm_pumps.id');
        
        return response()->json($pump);
    }
    public function shiftstartpump_store(Request $request)
    {
       

        $organization_id = Session::get('organization_id');
        $date = now()->format("Y-m-d "); 
        $pump=FsmPump::select('fsm_pumps.id')->where('organization_id',$organization_id)->get();
         
            if (count($pump) == 0) 
            {
                return response()->json(['message' => 'not here Pumps']);
            }
            else
            {
                for ($i=0; $i <count($pump) ; $i++) 
                { 
                    $start_pump=new \App\FsmShiftCashManage;
                    $start_pump->pump_id=$pump[$i]->id;
                    $start_pump->date=$date;
                    $start_pump->shift_id=$request->get('Shift');
                    $start_pump->employee_id=$request->get('employee');
                    $start_pump->start_time=$request->get('start_time');
                    $start_pump->notes=$request->get('notes');
                    $start_pump->approvel_status=1;
                    $start_pump->organization_id=$organization_id;
                    $start_pump->approvel_by=Auth::user()->id;
                    $start_pump->created_by=Auth::user()->id;
                    $start_pump->last_updated_by=Auth::user()->id;
                    $start_pump->save();

                }
                $shift =($start_pump->shift_id != null) ? HrmShift::findorFail($start_pump->shift_id )->name : "";
                $pump =($start_pump->pump_id != null) ? FsmPump::findorFail($start_pump->pump_id )->name : "";
                $person =( $start_pump->employee_id != null) ? HrmEmployee::findorFail( $start_pump->employee_id )->first_name : "";
                       
                       
                return response()->json(['message' => 'Shift Start'.config('constants.flash.added'), 'data' => ['id' => $start_pump->id,'shift'=>$shift,'date'=> $start_pump->date,'pump'=>$pump,'person'=>$person,'start_time'=> $start_pump->start_time]]);

            }
    }

     
    public function end_pumpshift_index($id)
    {
        $organization_id = Session::get('organization_id');

        $date = now()->format("Y-m-d ");  

        $shift=FsmShiftCashManage::select('shift_id')->where('id',$id)->first();
        $shift=$shift->shift_id;
        

        $shift_date=FsmShiftCashManage::select()->where('id',$id)->first();
        $shift_date=$shift_date->date;

        $data=FsmShiftCashManage::select('fsm_shift_cash_manages.id')
                ->where('fsm_shift_cash_manages.shift_id',$shift)
                ->where('fsm_shift_cash_manages.date',$shift_date)
                ->where('fsm_shift_cash_manages.end_time',"=",null)
                ->get();
  
       
        $end_pumpshift=FsmShiftCashManage::select('hrm_shifts.id as shift_id','fsm_shift_cash_manages.employee_id','hrm_shifts.name as shift_name',DB::raw('DATE_FORMAT(fsm_shift_cash_manages.date, "%d.%m.%Y")as date'),'hrm_employees.first_name','fsm_shift_cash_manages.start_time AS start_time','fsm_shift_cash_manages.end_time AS end_time')

        ->leftjoin('hrm_employees','hrm_employees.id','=','fsm_shift_cash_manages.employee_id')
      
       ->leftjoin('hrm_shifts','hrm_shifts.id','=','fsm_shift_cash_manages.shift_id')
        ->where('fsm_shift_cash_manages.organization_id',$organization_id)
        ->where('fsm_shift_cash_manages.id',$id)
        ->first();    
   

        $mytime = date("H:i");
        $organization_id = Session::get('organization_id');

        $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');
      

   
     $pumpname=FsmShiftCashManage::select('fsm_shift_cash_manages.id as fsm_id','fsm_pumps.name as pumpsname','inventory_items.name as productname','fsm_pumps.id as pumpid','inventory_items.id as productid','inventory_items.selling_price')
         ->leftjoin('fsm_pumps','fsm_pumps.id','=','fsm_shift_cash_manages.pump_id')
        ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
        ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
        ->where('fsm_shift_cash_manages.shift_id',$shift)
        ->where('fsm_shift_cash_manages.date',$shift_date)
        ->where('fsm_shift_cash_manages.organization_id',$organization_id)
        ->get();
       
        /*Table datas*/

        /*cash sale table*/
        $cash_sale=Transaction::SELECT('transactions.id','transactions.order_no as invoice_number','transactions.total','transaction_items.quantity','transaction_items.amount','transaction_items.rate','inventory_items.name as item_name','fsm_pumps.name as pump_name','vehicle_register_details.registration_no','hrm_employees.first_name as employee_name')

        ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')

        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')

        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id')

        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')

        ->leftjoin('fsm_pumps','fsm_pumps.id','=','wms_transactions.pump_id')

        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')

        ->leftjoin('fsm_shift_cash_manages','fsm_shift_cash_manages.shift_id','wms_transactions.shift_id')

        ->where('account_vouchers.name','job_invoice_cash')
        ->where('global_item_categories.id','=',45)
      
        ->where('fsm_shift_cash_manages.shift_id', $shift )
        ->where(DB::raw('date(wms_transactions.created_at)'),'=',$shift_date)
        ->where('transactions.organization_id', $organization_id )
        ->groupby('transactions.order_no')
        ->get();
      
         //** credit  sale table**

        $credit_sale=Transaction::SELECT('transactions.id','transactions.order_no as invoice_number','transactions.total','transaction_items.quantity','transaction_items.amount','transaction_items.rate','inventory_items.name as item_name','fsm_pumps.name as pump_name','vehicle_register_details.registration_no','hrm_employees.first_name as employee_name')

        ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')

        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')

        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id')

        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')

        ->leftjoin('fsm_pumps','fsm_pumps.id','=','wms_transactions.pump_id')

        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
        ->leftjoin('fsm_shift_cash_manages','fsm_shift_cash_manages.shift_id','wms_transactions.shift_id')
        ->where('account_vouchers.name','job_invoice')
        ->where('global_item_categories.id','=',45)      
        ->where('fsm_shift_cash_manages.shift_id', $shift )
        ->where(DB::raw('date(wms_transactions.created_at)'),'=',$shift_date)
        ->where('transactions.organization_id', $organization_id )
        ->groupby('transactions.order_no')
        ->get();

        /* credit card sale table*/
         $creditcard_sale=Transaction::SELECT('transactions.id','transactions.order_no as invoice_number','transactions.total','transaction_items.quantity','transaction_items.amount','transaction_items.rate','inventory_items.name as item_name','fsm_pumps.name as pump_name','vehicle_register_details.registration_no','hrm_employees.first_name as employee_name')

        ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')

        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')

        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id')

        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')

        ->leftjoin('fsm_pumps','fsm_pumps.id','=','wms_transactions.pump_id')

        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
        ->leftjoin('fsm_shift_cash_manages','fsm_shift_cash_manages.shift_id','wms_transactions.shift_id')
        ->where('account_vouchers.name','job_invoice')
        ->where('transactions.payment_mode_id','=',4)
        ->where('global_item_categories.id','=',45)      
        ->where('fsm_shift_cash_manages.shift_id', $shift )
        ->where(DB::raw('date(wms_transactions.created_at)'),'=',$shift_date)
        ->where('transactions.organization_id', $organization_id )
        ->groupby('transactions.order_no')
        ->get();

    
      
        /*other sale table*/
         $others_sale=Transaction::SELECT('transactions.id','transactions.order_no as invoice_number','transactions.total','transaction_items.quantity','transaction_items.amount','transaction_items.rate','inventory_items.name as item_name','fsm_pumps.name as pump_name','vehicle_register_details.registration_no','hrm_employees.first_name as employee_name')

        ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')

        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')

        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id')

        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')

        ->leftjoin('fsm_pumps','fsm_pumps.id','=','wms_transactions.pump_id')

        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
         ->leftjoin('fsm_shift_cash_manages','fsm_shift_cash_manages.shift_id','wms_transactions.shift_id')
         ->where(function($q) {
          $q->where('account_vouchers.name','job_invoice')
        ->orWhere('account_vouchers.name','job_invoice_cash');
         })        
        ->where('global_item_categories.id','=',46)      
       ->where('fsm_shift_cash_manages.shift_id', $shift )
        ->where(DB::raw('date(wms_transactions.created_at)'),'=',$shift_date)
        ->where('transactions.organization_id', $organization_id )
        ->groupby('transactions.order_no')
        ->get();       
     
        /*Report*/
       /*case sale reports  */

        $cashsale_report=Transaction::leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
           
        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
          
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
          
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id') 
        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id')       
        ->where('account_vouchers.name','job_invoice_cash')
       
        ->where('global_item_categories.id','=',45)
        ->where(DB::raw('date(transactions.created_at)'),'=',$shift_date)
        ->where('wms_transactions.shift_id',$shift)
        ->where('transactions.organization_id', $organization_id )
        ->sum('transactions.total');
           

         $creditsale_report=Transaction::leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
           
        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
          
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
          
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id') 
        ->where('account_vouchers.name','job_invoice')       
        ->where('global_item_categories.id','=',45)
        ->where(DB::raw('date(transactions.created_at)'),'=',$shift_date)
        ->where('wms_transactions.shift_id',$shift)
        ->where('transactions.organization_id', $organization_id )
        ->sum('transactions.total');
 
          //*** credit card sale Reports**
          $creditcardsale_report= Transaction::leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
          ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
          ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
          ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id') 
          ->where('account_vouchers.name','job_invoice') 
          ->where('transactions.payment_mode_id','=',4)
          ->where('global_item_categories.id','=',45)
          ->where(DB::raw('date(transactions.created_at)'),'=',$shift_date)
          ->where('wms_transactions.shift_id',$shift)
          ->where('transactions.organization_id', $organization_id )
          ->sum('transactions.total');

          /*others cash report*/
            $otherscash_report=Transaction::leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
           
        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
          
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
          
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id') 
        ->where('account_vouchers.name','job_invoice_cash')
       
        ->where('global_item_categories.id','=',46)
       ->where(DB::raw('date(transactions.created_at)'),'=',$shift_date)
          ->where('wms_transactions.shift_id',$shift)
        ->where('transactions.organization_id', $organization_id )
        ->sum('transactions.total');
          /*others credit report*/
        $otherscredit_report=Transaction::leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
           
        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
          
        ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
          
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
          
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transaction_items.transaction_id') 
        ->where('account_vouchers.name','job_invoice')
       
        ->where('global_item_categories.id','=',46)
        ->where(DB::raw('date(transactions.created_at)'),'=',$shift_date)
          ->where('wms_transactions.shift_id',$shift)
        ->where('transactions.organization_id', $organization_id )
        ->sum('transactions.total');

        if(count($data)==0)
        {
            $end_data=FsmShiftCashManage::select('fsm_shift_cash_manages.id as manage_id','fsm_shift_pump_cash_details.id as cash_detail_id','fsm_pumps.name as pumpsname','inventory_items.name as productname','fsm_pumps.id as pumpid','inventory_items.id as productid','inventory_items.selling_price','fsm_shift_pump_cash_details.*','fsm_shift_cash_manages.*')
                 ->leftJoin('fsm_shift_pump_cash_details', function($join)
                    {
                        $join->on('fsm_shift_pump_cash_details.shift_id', '=', 'fsm_shift_cash_manages.shift_id'); 
                        $join->on('fsm_shift_pump_cash_details.pump_id', '=', 'fsm_shift_cash_manages.pump_id'); 
                        $join->on('fsm_shift_pump_cash_details.date', '=', 'fsm_shift_cash_manages.date'); 
                            
                    })
                ->leftjoin('fsm_pumps','fsm_pumps.id','=','fsm_shift_cash_manages.pump_id')
                ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
                ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')        
                ->where('fsm_shift_cash_manages.shift_id',$shift)
                ->where('fsm_shift_cash_manages.date',$shift_date)
                ->where('fsm_shift_cash_manages.end_time',"!=",null)
                ->get();

                $cash_details=FsmShiftCashManage::select('fsm_shift_cash_manages.collected_fuel_sales','fsm_shift_cash_manages.collected_other_sales','fsm_shift_cash_manages.collected_other_receipts','fsm_shift_cash_manages.collected_expenses','fsm_shift_cash_manages.collected_total_sales')
                 ->leftJoin('fsm_shift_pump_cash_details', function($join)
                    {
                        $join->on('fsm_shift_pump_cash_details.shift_id', '=', 'fsm_shift_cash_manages.shift_id'); 
                        $join->on('fsm_shift_pump_cash_details.pump_id', '=', 'fsm_shift_cash_manages.pump_id'); 
                        $join->on('fsm_shift_pump_cash_details.date', '=', 'fsm_shift_cash_manages.date'); 
                            
                    })
                ->leftjoin('fsm_pumps','fsm_pumps.id','=','fsm_shift_cash_manages.pump_id')
                ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
                ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')        
                ->where('fsm_shift_cash_manages.shift_id',$shift)
                ->where('fsm_shift_cash_manages.date',$shift_date)
                ->where('fsm_shift_cash_manages.end_time',"!=",null)
                ->first();
                $fuelsales=$cash_details->collected_fuel_sales;
                $othersales=$cash_details->collected_other_sales;
                $other_receipts=$cash_details->collected_other_receipts;
                $expenses=$cash_details->collected_expenses;
                $total_sales=$cash_details->collected_total_sales;
             

        }
           
         if(count($data)==0)
         {
            return view('fuel_station.shiftmanagement_endpumpedit',compact('end_pumpshift','mytime','employee','pumpname','pump','cash_sale','credit_sale','creditcard_sale','others_sale','cashsale_report','creditsale_report','creditcardsale_report','otherscash_report','otherscredit_report','end_data','fuelsales','othersales','other_receipts','expenses','total_sales'));
         }
         else
         {
        
        return view('fuel_station.shiftmanagement_endpump',compact('end_pumpshift','mytime','employee','pumpname','pump','cash_sale','credit_sale','creditcard_sale','others_sale','cashsale_report','creditsale_report','creditcardsale_report','otherscash_report','otherscredit_report'));
         }
    }



public function shiftendpump_store(Request $request)
    {
        //dd($request->all());
      $organization_id = Session::get('organization_id');

      $fsm_id=$request->fsm_id;
      $shift_id=$request->shiftid;
      $employee=$request->employee;     
      $pump_id=$request->pumpid;   
      $product_id=$request->productid;
      $At_rate=$request->At_rate;
      $open_meter=$request->openmeter;
      $close_meter=$request->close_meter;
      $testing=$request->testing;
      $quantity=$request->quantity;
      $salesby_cash=$request->salesby_cash;
      $attendant=$request->attendant;
      $sale_quantity=$request->sale_quantity;
      $sale_cash=$request->sale_cash;
      $more_less=$request->more_less;
      $notes=$request->notes;
      $end_at=$request->end_at;
      $collected_fuel_sales=$request->fuelsales;
      $collected_other_sales=$request->othersales;
      $collected_other_receipts=$request->otherreceipt;
      $collected_expenses=$request->expenses;
      $collected_total_sales=$request->total;
      $invoiced_fuel_cash_sales=$request->fuelcashsale;
      $invoiced_fuel_credit_sales=$request->fuelcreditcard_sale;
      $invoiced_fuel_credit_card=$request->fuelcredit_sale;


      $date = now()->format("Y-m-d ");  

     foreach ($pump_id as $key => $value) {

            $data= new \App\FsmShiftPumpCashDetail;

            $data->date=$date;
            $data->shift_id=$shift_id;
            $data->pump_id=$pump_id[$key];
            $data->product_id=$product_id[$key];
            $data->at_the_rate=$At_rate[$key];
            $data->pump_openmeter=$open_meter[$key];
            $data->pump_closemeter=$close_meter[$key];
            $data->pump_testing=$testing[$key];
            $data->pump_salesquantity=$quantity[$key];
            $data->pump_sales=$salesby_cash[$key];
            $data->rep_pumpattendant= $attendant[$key];
            $data->rep_salesquantity=$sale_quantity[$key];
            $data->rep_salesbycash=$sale_cash[$key];
            $data->created_by=Auth::user()->id;
            $data->organization_id=$organization_id ;
            $data->status=1;
            $data->save();
                     
        }

          foreach ($pump_id as $key => $value) {

           $id= $fsm_id[$key];
         

            $data = FsmShiftCashManage::findOrFail($id);
            $data->notes=$notes[$key];
            $data->end_time=$end_at;
            $data->collected_fuel_sales=$collected_fuel_sales;
            $data->collected_other_sales=$collected_other_sales;
            $data->collected_other_receipts=$collected_other_receipts;
            $data->collected_expenses=$collected_expenses;
            $data->collected_total_sales=$collected_total_sales;
            $data->pump_sales_quantity=$quantity[$key];
            $data->pump_total_sales=$salesby_cash[$key];
            $data->rep_sales_quantity=$sale_quantity[$key];
            $data->rep_total_sales=$sale_cash[$key];
            $data->repvspump_descrepancy=$more_less[$key];
            $data->invoiced_fuel_cash_sales=$invoiced_fuel_cash_sales;
            $data->invoiced_fuel_credit_sales=$invoiced_fuel_credit_sales;
            $data->invoiced_fuel_credit_card=$invoiced_fuel_credit_card;
            $data->last_updated_by=Auth::user()->id;
            $data->save();    

          
          }


         return response()->json(['status' => 1,'message' => 'Shift '.config('constants.flash.added'), 'data' => ['id' => $data->id]]);

    } 

    public function shiftendpump_update(Request $request){
        
      $fsm_id=$request->fsm_id;
      $shift_id=$request->shiftid;
      $employee=$request->employee;     
      $cash_detail_id=$request->cash_detail_id;   
      $product_id=$request->productid;
      $At_rate=$request->At_rate;
      $open_meter=$request->openmeter;
      $close_meter=$request->close_meter;
      $testing=$request->testing;
      $quantity=$request->quantity;
      $salesby_cash=$request->salesby_cash;
      $attendant=$request->attendant;
      $sale_quantity=$request->sale_quantity;
      $sale_cash=$request->sale_cash;
      $more_less=$request->more_less;
      $notes=$request->notes;
      $end_at=$request->end_at;
      $collected_fuel_sales=$request->fuelsales;
      $collected_other_sales=$request->othersales;
      $collected_other_receipts=$request->otherreceipt;
      $collected_expenses=$request->expenses;
      $collected_total_sales=$request->total;
      $invoiced_fuel_cash_sales=$request->fuelcashsale;
      $invoiced_fuel_credit_sales=$request->fuelcreditcard_sale;
      $invoiced_fuel_credit_card=$request->fuelcredit_sale;
      $invoiced_fuel_othercash=$request->otherscash_report;
      $invoiced_fuel_othercredit=$request->otherscredit_report;

        $manage_id=$request->manage_id;
     
       for($i=0;$i<count($manage_id);$i++)
        {
            $data=FsmShiftCashManage::findorFail($manage_id[$i]);
            $data->notes=$notes[$i];
            $data->end_time=$end_at;
            $data->collected_fuel_sales=$collected_fuel_sales;
            $data->collected_other_sales=$collected_other_sales;
            $data->collected_other_receipts=$collected_other_receipts;
            $data->collected_expenses=$collected_expenses;
            $data->collected_total_sales=$collected_total_sales;
            $data->pump_sales_quantity=$quantity[$i];
            $data->pump_total_sales=$salesby_cash[$i];
            $data->rep_sales_quantity=$sale_quantity[$i];
            $data->rep_total_sales=$sale_cash[$i];
            $data->repvspump_descrepancy=$more_less[$i];
            $data->invoiced_fuel_cash_sales=$invoiced_fuel_cash_sales;
            $data->invoiced_fuel_credit_sales=$invoiced_fuel_credit_sales;
            $data->invoiced_fuel_credit_card=$invoiced_fuel_credit_card;
            $data->invoiced_other_cash_sales=$invoiced_fuel_othercash;
            $data->invoiced_other_credit_sales=$invoiced_fuel_othercredit;
            $data->last_updated_by=Auth::user()->id;
            $data->save();    


       }

       for($j=0;$j<count($cash_detail_id);$j++)
       {
            $cash_detail=FsmShiftPumpCashDetail::findorFail($cash_detail_id[$j]);
            $cash_detail->pump_openmeter=$open_meter[$j];
            $cash_detail->pump_closemeter=$close_meter[$j];
            $cash_detail->pump_testing=$testing[$j];
            $cash_detail->pump_salesquantity=$quantity[$j];
            $cash_detail->pump_sales=$salesby_cash[$j];
            $cash_detail->rep_pumpattendant=$attendant[$j];
            $cash_detail->rep_salesquantity=$sale_quantity[$j];
            $cash_detail->rep_salesbycash=$sale_cash[$j];
            $cash_detail->updated_by=Auth::user()->id;
            $cash_detail->save();
       }

     return response()->json(['status' => 1,'message' => 'Shift '.config('constants.flash.updated'),'data' => ['id' => $data->id]]);
    }
     public function invoice_index(){

        $type='job_invoice';
        $module_name=Session::get('module_name');
       
        
        $organization_id = Session::get('organization_id');
      
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

   

        if($transaction_type == null) abort(404);

       
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


        $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        
        $transaction = Transaction::select('transactions.id', 'transactions.order_no','transactions.approved_on','vehicle_register_details.id as vehicle_id','vehicle_register_details.registration_no',
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"),  'transactions.approval_status', 'transactions.transaction_type_id',
            DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"),'transactions.date as original_date', 'transactions.due_date as original_due_date','transactions.total',
             DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.name as jobcard_status','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date',DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance")); 
            
        

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
          $transaction->groupby('transactions.id');
          $transaction->orderBy('transactions.updated_at','desc');
          $transactions = $transaction->get();    

        return view('fuel_station.invoice_index',compact('transactions'));

    }
    public function invoice_create($id){

        $organization_id = Session::get('organization_id');

        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }

        $days[0] = "Last";

        $mytime =Date('H:i:s');

        $shifttime=HrmShift::where(function ($q) {
                    $q->where('hrm_shifts.from_time', '<=',Date('H:i:s'));
                    $q->where('hrm_shifts.to_time', '>=', Date('H:i:s'));
                     }) 
                     ->where('hrm_shifts.organization_id',$organization_id)
                     ->pluck('name','id');
        
        $customer_label = 'Customer';

        $type=$id;
       
        $organization_id = Session::get('organization_id');

        $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');
      

        $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
        $job_card_status->prepend('Select Jobcard Status', '');


        $job_status = VehicleJobcardStatus::where('name', 'New')->first()->id;

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        ->where('account_vouchers.organization_id', $organization_id)
        ->where('modules.name', Session::get('module_name'))
        ->where('account_vouchers.name', $type)
        ->first();      

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));
    
        $address_label = 'Customer Address';
        $service_type_label = 'Service Type';
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
        $person_type = "customer";
        $people = $people_list->pluck('name', 'id');
        $business = $business_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');
        $business->prepend('Select Business', '');
                               
        $shift=HrmShift::where('status',1)->where('organization_id', $organization_id)->pluck('name','id');
        $shift->prepend("Select Shift","");

        $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');

        $pumpname=FsmPump::where('organization_id',$organization_id)->pluck('description','id');
        $pumpname->prepend('Select Pumpname ','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;

       

        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('status', 1)->where('organization_id', $organization_id);

        $discounts = $discount->get();

        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();
        $address_label='Customer Address';
        $company_label=true;
            $address_type = BusinessAddressType::where('name', 'business')->first();
            $business_id = Organization::find($organization_id)->business_id;

        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        ->leftjoin('states', 'cities.state_id', '=', 'states.id')
        ->where('address_type', $address_type->id)
        ->where('business_id', $business_id)
        ->first();

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


        $company_name = $business_communication_address->placename;
        $company_email = $business_communication_address->email_address;
        $company_mobile = $business_communication_address->mobile_no;
        $company_address = $business_company_address;

        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();



        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;



        $voucher_no = Custom::generate_accounts_number($type, $gen_no, false);

        $customer_type_label = 'Customer Type';

        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')

        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')

        ->get();

        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

        $mainproduct = InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')

        ->leftjoin('global_item_categories','global_item_categories.id','=','inventory_items.category_id')
        ->where('inventory_items.organization_id',$organization_id)

        ->pluck('inventory_items.name','inventory_items.id');
        $mainproduct->prepend('select a item here','');           

        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')

          ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

          ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
         
          ->where('global_item_categories.main_category_id',9)
          ->orWhere('global_item_categories.main_category_id',9)
          ->where('inventory_items.organization_id', $organization_id)
          ->orderby('global_item_categories.display_name')
          ->get();
         

        $uuid=Custom::GUID();
       

        return view('fuel_station.invoice_create',compact('uuid','weekday','weekdays','days','vehicles_register','job_card_status','job_status','transaction_type','customer_label','people','person_type','business','shift','employee','pumpname','payment','payment_terms','payment_term','product','discounts','taxes','address_label','company_label','company_name','company_email','company_mobile','company_address','transactions','voucher_no','customer_type_label','type','field_types','sub_heading','shifttime','mainproduct','items'));
    }
      public function invoice_edit($id,$vehicle_id)
    {
        $organization_id = Session::get('organization_id');

        $vehicles = VehicleRegisterDetail::select('vehicle_register_details.*')->where('id', $vehicle_id)->where('organization_id', $organization_id)->first();
          
            if($vehicles->user_type == "0")
             {
          
            $customer_type = Person::findorfail($vehicles->owner_id)->id;
          
             }

            if($vehicles->user_type == "1")
            {
           
            $customer_type = Business::findorfail($vehicles->owner_id)->id;
            }

      

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

        $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');

        $reading_factor = WmsReadingFactor::select('wms_reading_factors.id AS reading_factor_id', 'wms_reading_factors.name AS reading_factor_name', 'wms_applicable_divisions.id AS wms_division_id', 'wms_applicable_divisions.division_name')
        ->leftJoin('wms_applicable_divisions', 'wms_applicable_divisions.id','=','wms_reading_factors.wms_division_id')
            ->where('wms_reading_factors.organization_id', $organization_id)->get();


        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));


        $transactions = Transaction::where('id', $id)->where('organization_id',$organization_id)->first();
      

        $print_templates = MultiTemplate::select('multi_templates.id','multi_templates.voucher_id','account_voucher_types.display_name','multi_templates.print_temp_id','print_templates.display_name','print_templates.data') ->leftjoin('account_voucher_types','account_voucher_types.id','=','multi_templates.voucher_id')
             ->leftjoin('print_templates','print_templates.id','=','multi_templates.print_temp_id')
             ->where('multi_templates.organization_id',$organization_id)
             ->groupby('multi_templates.print_temp_id')
             ->get();
             

        $wms_transaction_readings = WmsReadingFactor::select('wms_reading_factors.name As reading_factor_name','wms_reading_factors.id AS reading_factor_id', 'wms_transaction_readings.reading_values','wms_transaction_readings.reading_notes','wms_transaction_readings.id As id')->LeftJoin('wms_transaction_readings', function($join)  use ($id) {
            $join->on('wms_transaction_readings.reading_factor_id', '=', 'wms_reading_factors.id') ;
            $join->where('wms_transaction_readings.transaction_id', '=',$id) ;})
        ->where('wms_reading_factors.organization_id', $organization_id)->get();


        $wms_attachments_before=WmsAttachment::select('organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 1)->where('organization_id',$organization_id)->get();
      
        $wms_attachments_progress=WmsAttachment::select('organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 2)->where('organization_id',$organization_id)->get();
        $wms_attachments_after=WmsAttachment::select('organization_id','image_name','image_origional_name','thumbnail_file','thumbnail_file','origional_file','transaction_id')->where('transaction_id', $id)->where('image_category', 3)->where('organization_id',$organization_id)->get();

        //  dd($id);
        $wms_checklist_query=VehicleChecklist::select('vehicle_checklists.name','vehicle_checklists.id as checklist_id','wms_checklists.transaction_id','wms_checklists.checklist_status','wms_checklists.checklist_notes','wms_checklists.id as id')->LeftJoin('wms_checklists', function($join)  use ($id) {
            $join->on('wms_checklists.checklist_id', '=', 'vehicle_checklists.id') ;
            $join->where('wms_checklists.transaction_id', '=',$id) ;});
        
        $wms_checklist=$wms_checklist_query->get();
              



        $reference_transaction_type = null;

        $reference_transaction = Transaction::find($transactions->reference_id);  
         
        $wms_transaction = WmsTransaction::select('wms_transactions.*','wms_transactions.service_type','wms_transactions.jobcard_status_id','wms_transactions.purchase_date','vehicle_register_details.*','wms_transactions.next_visit_mileage','wms_transactions.vehicle_next_visit','wms_transactions.vehicle_next_visit_reason','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date','wms_transactions.registration_id','wms_transactions.vehicle_note','wms_transactions.vehicle_complaints','vehicle_variants.vehicle_configuration','vehicle_register_details.driver as drivername','vehicle_register_details.driver_mobile_no as drivermobileno','hrm_employees.id as employeename','transactions.payment_mode_id','inventory_items.name as itemname'
    )
        ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id')
        ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')
        ->leftjoin('transactions','transactions.id','=','wms_transactions.transaction_id')
        ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
        ->leftJoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
        ->leftJoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->where('wms_transactions.organization_id', $organization_id)       
        ->where('wms_transactions.transaction_id', $transactions->id)
        ->first();
        //dd($wms_transaction);

        

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
        ->where('modules.name', Session::get('module_name'))
        ->where('account_vouchers.id', $transactions->transaction_type_id)
        ->first();   

        if($transaction_type == null) {
            return null;
        }       

        
       
        $type = $transaction_type->name;


        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

        $voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

        $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

        $account_ledgers = AccountLedger::where('group_id', $sale_account)->where('organization_id', $organization_id)->pluck('name', 'id');
        $account_ledgers->prepend('Select Account', '');

        $employees = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name', 'id');
        

        $shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipment_mode->prepend('Select Shipment Mode', '');


        $job_item_status = VehicleJobItemStatus::where('status', '1')->pluck('name', 'id');
        $job_item_status->prepend('Select Status', '');

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

         


        $items = InventoryItem::leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')  
        ->leftjoin('transaction_items','transaction_items.item_id','=','inventory_items.id')
        ->where('transaction_items.transaction_id',$id)         
        ->where('inventory_items.organization_id', $organization_id)
        ->where('global_item_categories.main_category_id',9)
         ->orderby('global_item_categories.display_name')
        ->select('inventory_items.id');
        
         $items_list = InventoryItem::where('organization_id',$organization_id) ->pluck('inventory_items.name', 'inventory_items.id');
     
        

        $query = InventoryItemGroup::select('inventory_items.name','inventory_items.is_group',DB::raw('COALESCE(inventory_item_groups.price, "") as price'),'inventory_item_groups.quantity','inventory_item_groups.tax_id','inventory_item_groups.item_id');

        $query->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_item_groups.item_id');        
        $query->where('inventory_item_groups.item_group_id', $id);      

        $item_group = $query->get();


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
       

        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $make->prepend('Select Make', '');

        $job_type = JobType::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');

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
            break;
            case 'sales':
                $address_label = 'Customer Address';
              
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

       

        $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',
            'registered_vehicle_specs.spec_value')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')
        ->where('registered_vehicle_specs.organization_id',$organization_id)
        ->where('registered_vehicle_specs.registered_vehicle_id',$vehicle_id)
        ->get();

       // dd($spec_values);
        $vehicle_data=VehicleRegisterDetail::select('vehicle_variants.vehicle_configuration','vehicle_register_details.user_type','vehicle_register_details.driver as driver_name','vehicle_register_details.driver_mobile_no as driver_number')

      ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')        
       
        ->where('vehicle_register_details.id',$vehicle_id)     
         ->where('vehicle_register_details.organization_id',$organization_id)      
        ->first();


    
          $org_id = Session::get('organization_id');
        return view('fuel_station.invoice_edit', compact('people', 'business', 'voucher_no', 'account_ledgers', 'employees', 'shipment_mode', 'items', 'taxes', 'discounts', 'transaction_type', 'state', 'title', 'payment', 'terms', 'voucher_terms', 'weekdays', 'days', 'weekday', 'type', 'due_date_label', 'term_label', 'order_label', 'payment_label', 'sales_person_label', 'include_tax_label', 'date_label', 'customer_type_label', 'customer_label', 'person_type', 'field_types', 'transaction_fields', 'make', 'selected_make', 'model', 'job_type', 'sub_heading', 'discount_option', 'due_date', 'order_type', 'order_type_value', 'address_label', 'transaction_address_type', 'company_name', 'company_email', 'company_mobile', 'company_address', 'company_label' , 'transactions', 'id','shipping_date', 'reference_transaction_type','item_group','service_type_label','vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain','service_type', 'vehicle_usage', 'maintanance_reading', 'vehicles_register','reading_factor','wms_transaction','vehicle_sevice_type','job_card_status','wms_transaction_readings','wms_attachments_before','wms_attachments_progress','wms_attachments_after','wms_checklist','approvel_status','approved_date','job_item_status','payment_terms','spec_values','org_id','print_templates','vehicle_data','customer_type','items_list'));
  


    }

}
