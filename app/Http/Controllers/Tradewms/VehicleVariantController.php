<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleRegisterDetail;
use App\VehicleVariant;
use App\VehicleModel;
use App\VehicleMake;
use App\Transaction;
use App\Organization;
use App\CustomerGroping;
use App\RegisteredVehicleSpec;
use App\Custom;
use App\Person;
use App\People;
use App\Country;

use App\Business;
use App\VehicleType;
use App\OrgCustomValue;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\BusinessProfessionalism;
use Validator;
use Session;
use Auth;
use DB;

use Illuminate\Support\Facades\Input;

class VehicleVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $organization_id = Session::get('organization_id');
        $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
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

         if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
            //dd($business_professionalism_id);
       
        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first();
        if($business_professionalism_id->business_professionalism_id == 4)
        {
            $type_id = VehicleType::where('name', $business_professionalism_name->name)->first()->id;
            //dd($type_id);
        }
      
        }
          if($business_professionalism_id->business_professionalism_id == 4)
        {
             $vehicle_variants = VehicleVariant::select('vehicle_variants.id','vehicle_variants.name', 'vehicle_variants.description', 'vehicle_variants.status','vehicle_variants.version','vehicle_variants.vehicle_configuration' ,'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')        
        ->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
        ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_variants.vehicle_make_id')
        ->where('vehicle_variants.type_id',$type_id)
        ->paginate(10);
        
        }
        else
        {
        $vehicle_variants = VehicleVariant::select('vehicle_variants.id','vehicle_variants.name', 'vehicle_variants.description', 'vehicle_variants.status','vehicle_variants.version','vehicle_variants.vehicle_configuration' ,'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')
        
        ->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
        ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_variants.vehicle_make_id')
        ->paginate(10);
        }
        
       
        //dd($vehicle_variants);
        return view('trade_wms.vehicle_variant', compact('vehicle_variants','state','city','title','payment','terms','group_name'));
    }


       /**
     * vehicle vatiant pagination for server side
     *
     * @return \Illuminate\Http\Response
     */

    function varient_pagination(Request $request)
    {
      //  dd($request->all());
        $organization_id = session::get('organization_id');
         $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
         //dd($business_id);
      
        if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
            //dd($business_professionalism_id);
       
        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first();
        if($business_professionalism_id->business_professionalism_id == 4)
        {
            $type_id = VehicleType::where('name', $business_professionalism_name->name)->first()->id;
            //dd($type_id);
        }
      
        }
     if($request->ajax())
     {
         if($business_professionalism_id->business_professionalism_id == 4)
        {
            $variant_query = VehicleVariant::select('vehicle_variants.id','vehicle_variants.name', 'vehicle_variants.description', 'vehicle_variants.status','vehicle_variants.version','vehicle_variants.vehicle_configuration' ,'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')        
        ->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
        ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_variants.vehicle_make_id')
        ->where('vehicle_variants.type_id',$type_id);
        }
        else
        {
        $variant_query = VehicleVariant::select('vehicle_variants.id','vehicle_variants.name', 'vehicle_variants.description', 'vehicle_variants.status','vehicle_variants.version','vehicle_variants.vehicle_configuration' ,'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')
        
        ->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
        ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_variants.vehicle_make_id');
        }
        
         
         if(Input::has('entrires')) {
             // Do something!
             $entrires=(is_numeric($request->input('entrires')))?$request->input('entrires'):10;
           //  dd($entrires);
            $vehicle_variants=$variant_query->paginate($entrires);
        }else{

            $vehicle_variants=$variant_query->paginate(10);
        }

        return view('trade_wms.vehicle_variant_pagination', compact('vehicle_variants'))->render();
     }
    }

      /**
     * vehicle variant global search
     *
     * @return \Illuminate\Http\Response
     */
    function varient_global_search(Request $request)
    {
        //Search column
        $columnsToSearch = [ 'vehicle_makes.name','vehicle_models.name','vehicle_variants.name','vehicle_variants.version','vehicle_variants.vehicle_configuration'];

        $searchQuery = '%' . $request->search . '%';
        //Search query
      //  dd($searchQuery);
        $variant_query = VehicleVariant::select('vehicle_variants.id','vehicle_variants.name', 'vehicle_variants.description', 'vehicle_variants.status','vehicle_variants.version','vehicle_variants.vehicle_configuration' ,'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')
        
                ->leftJoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
                ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_variants.vehicle_make_id');
      

                    
                  //  $variant_query->where('vehicle_variants.id', 'LIKE', $searchQuery);
                    $variant_query->Where(function ($query) use ($columnsToSearch,$searchQuery)  {
                   
                        foreach($columnsToSearch as $column) {
                        
                            $query->orWhere($column, 'LIKE', $searchQuery);
                        }  
                }); 
                   // $variant->groupBy('vehicle_variants.id')
              //  $variants = $variant_query->paginate(100);
                if(Input::has('entrires')) {
                // Do something!
                        $entrires=($request->input('entrires')!="false")?$request->input('entrires'):10;
              //  dd($entrires);
                        $vehicle_variants=$variant_query->paginate($entrires);
                        
                        }else{
   
                        $vehicle_variants=$variant_query->paginate(10);
                    } 
                    $vehicle_variants->appends(['search'=>$request->search]);
                    
                return view('trade_wms.vehicle_variant_pagination', compact('vehicle_variants'))->render();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');
        
        $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');
               
        return view('trade_wms.vehicle_variant_create', compact('vehicle_make_id'));
    }

    public function version_create()
    {
        $organization_id = Session::get('organization_id');
        
        $vehicle_version_id = VehicleVariant::orderBy('name')->groupby('name')->pluck('name', 'id');
        $vehicle_version_id->prepend('Select Vehicle Variant', '');

        return view('trade_wms.vehicle_variant_version_create', compact('vehicle_version_id'));

    }
    public function get_make_name(Request $request)
    {
        //dd($request->variant);
        $version_name = VehicleVariant::findorfail($request->variant)->name;
        $versions = VehicleVariant::select('vehicle_variants.id','vehicle_variants.vehicle_make_id','vehicle_variants.vehicle_model_id','vehicle_makes.name as make_name','vehicle_models.name as model_name','version')
        ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')
        ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')
        ->where('vehicle_variants.id',$request->variant)
        ->get();

        $version = VehicleVariant::select(DB::raw('group_concat(version) as version'))
        ->where('name',$version_name)
        ->get();
        //dd($version);

      //dd($versions);
      //dd(VehicleMake::findorFail($versions->vehicle_make_id)->id) ;
        //dd($make_id);
        //$model_id = ($versions->vehicle_model_id != null) ? VehicleModel::findorFail($versions->vehicle_model_id)->name : "";
       return response()->json(['status' => 1, 'data' => $versions,'version' => $version]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_variant_name(Request $request) {
        //dd($request->all());     
        $vehicle_variant = VehicleVariant::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_variant->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }  

    public function variant_name(Request $request)
    {
        //dd($request->all());
        $variant_name = VehicleVariant::where('name',$request->variant)->where('vehicle_make_id','!=', $request->make_id)->where('vehicle_model_id','!=',$request->model_id)->exists();

        if($variant_name)
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
    }  

    public function store(Request $request)
    {
       //dd($request->all());
        $this->validate($request, [
            'name' => 'required',       
            'make_id' => 'required',       
            'model_id' => 'required',
            'version' => 'required'
        ]);
        //$ver = explode(' ',$request->version);
        //dd($ver);
        $ver =$request->version;

        for($j=0;$j<count($ver);$j++)
        {
            $organization_id = Session::get('organization_id');
            $variant_name = VehicleVariant::where('name',$request->name)
            ->where('vehicle_make_id','=', $request->make_id)
            ->where('vehicle_model_id','=',$request->model_id)
            ->where('version','=',$ver[$j])
            ->exists();
             if($variant_name)
        {
            //dd("true");
            return response()->json(['status' => 2,'message' => 'Already Exist']);

        }
        else
        {
            //dd("false");
            $make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($request->make_id)->name : "";
            $model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($request->model_id)->name : "";
            //dd($make_id);

            //dd($config);
            //$version = explode(',',$request->input('version'));
            $version = $request->input('version');
            //dd($version);
            for($i=0;$i<count($version);$i++)
            {
            $config=($make_id)."/".($model_id)."/".($request->name)."/".($version[$i]);

            $vehicle_variant = new VehicleVariant;
            $vehicle_variant->name = $request->input('name');
            $vehicle_variant->display_name = $request->input('name');
            $vehicle_variant->vehicle_make_id = $request->input('make_id');
            $vehicle_variant->vehicle_model_id = $request->input('model_id');
            $vehicle_variant->version= $version[$i];
            $vehicle_variant->vehicle_configuration=$config;
            $vehicle_variant->description = $request->input('description');
            $vehicle_variant->organization_id = $organization_id;

            $vehicle_variant->save();
            }

            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";
            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";

            Custom::userby($vehicle_variant, true);
            Custom::add_addon('records');
           
             $ver = VehicleVariant::where('vehicle_make_id','=',$request->input('make_id'))
            ->where('vehicle_model_id','=',$request->input('model_id'))
            ->where('name',$variant_name)
            ->whereIn('version',$version)
            ->get();
           
            return response()->json(['status' => 1, 'message' => 'Vehicle Variant'.config('constants.flash.added'), 'data' => ['id' => $vehicle_variant->id, 'name' => $vehicle_variant->name, 'display_name' => $vehicle_variant->display_name, 'make_id' => $vehicle_make_id, 'model_id' => $vehicle_model_id, 'version' => $vehicle_variant->version,'config' => $vehicle_variant->vehicle_configuration, 'description' => ($vehicle_variant->description != null) ? $vehicle_variant->description : "", 'status' => $vehicle_variant->status]]);
        }
        }
        //dd($variant_name);
       

    }

    public function version_store(Request $request)
    {
        //dd($request->all());
         $this->validate($request, [
            'id' => 'required',       
            'make_id' => 'required',       
            'model_id' => 'required',
            'version' => 'required'
        ]);
        //$ver = explode(' ',$request->version);
        //dd($ver);
        $ver =$request->version;
        $variant_names = VehicleVariant::findorfail($request->id)->name;
        //dd($variant_name);
        for($j=0;$j<count($ver);$j++)
        {
            $organization_id = Session::get('organization_id');
            $variant_name = VehicleVariant::where('name',$variant_names)
            ->where('vehicle_make_id','=', $request->make_id)
            ->where('vehicle_model_id','=',$request->model_id)
            ->where('version','=',$ver[$j])
            ->exists();
             if($variant_name)
        {
            //dd("true");
            return response()->json(['status' => 2,'message' => 'Already Exist']);

        }
        else
        {
            //dd("false");
            $make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($request->make_id)->name : "";
            $model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($request->model_id)->name : "";
            //dd($make_id);

            //dd($config);
            //$version = explode(',',$request->input('version'));
            $version = $request->input('version');
            //dd($version);
            for($i=0;$i<count($version);$i++)
            {
            $config=($make_id)."/".($model_id)."/".($variant_names)."/".($version[$i]);

            $vehicle_variant = new VehicleVariant;
            $vehicle_variant->name = $variant_names;
            $vehicle_variant->display_name = $variant_names;
            $vehicle_variant->vehicle_make_id = $request->input('make_id');
            $vehicle_variant->vehicle_model_id = $request->input('model_id');
            $vehicle_variant->version= $version[$i];
            $vehicle_variant->vehicle_configuration=$config;
            $vehicle_variant->description = $request->input('description');
            $vehicle_variant->organization_id = $organization_id;

            $vehicle_variant->save();
            }

            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";
            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";

            Custom::userby($vehicle_variant, true);
            Custom::add_addon('records');
            
            $ver = VehicleVariant::where('vehicle_make_id','=',$request->input('make_id'))
            ->where('vehicle_model_id','=',$request->input('model_id'))
            ->where('name',$variant_names)
            ->whereIn('version',$version)
            ->get();
            //dd($ver);

            return response()->json(['status' => 1, 'message' => 'Vehicle Variant'.config('constants.flash.added'), 'data' => $ver,'make_id'=>$vehicle_make_id,'model_id' => $vehicle_model_id]);
        }
        }
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
        //dd($id);

         $organization_id = Session::get('organization_id');
         
        $vehicle_variant = VehicleVariant::where('id', $id)->first(); 
        //dd($vehicle_variant);
        $version = VehicleVariant::pluck('version','id')->where('id',$id);

        $vehicle_make_id = VehicleMake::pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');

        $vehicle_model_id = VehicleModel::pluck('name','id');
        $vehicle_model_id->prepend('Select model','');
               
        return view('trade_wms.vehicle_variant_edit', compact('vehicle_variant', 'vehicle_make_id','vehicle_model_id','version'));
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
            'make_id' => 'required',       
            'model_id' => 'required',
            'version' => 'required'
        ]);
        $ver = $request->version;

        $variant_name = VehicleVariant::where('name',$request->name)
            ->where('vehicle_make_id','=', $request->make_id)
            ->where('vehicle_model_id','=',$request->model_id)
            ->where('version','=',$ver)
            ->where('id','!=',$request->input('id'))
            ->exists();

        /*$ver = $request->version;
        for($j=0;$j<count($ver);$j++)
        {
            $variant_name = VehicleVariant::where('name',$request->name)
            ->where('vehicle_make_id','=', $request->make_id)
            ->where('vehicle_model_id','=',$request->model_id)
            ->where('version','=',$ver[$j])
            ->exists();*/

            if($variant_name)
            {
                return response()->json(['status' =>2 ,'message' => 'Already Exits']);
            }
            else
            {
            $make_name = VehicleMake::findorfail($request->make_id)->name;
            //dd($make_name);
            $model_name = VehicleModel::findorfail($request->model_id)->name;
            $version = $request->input('version');
            //dd($version);
                for($i=0;$i<count($version);$i++)
                {
                    $config = ($make_name).'/'.($model_name).'/'.($request->name).'/'.($version[$i]);

                    $vehicle_variant = VehicleVariant::findOrFail($request->input('id'));
                    //dd($vehicle_variant);
                    $vehicle_variant->name = $request->input('name');
                    $vehicle_variant->display_name = $request->input('name');
                    $vehicle_variant->version = $version[$i];
                    $vehicle_variant->vehicle_make_id = $request->input('make_id');
                    $vehicle_variant->vehicle_model_id = $request->input('model_id');
                    $vehicle_variant->vehicle_configuration = $config ;
                    $vehicle_variant->description = $request->input('description');
                    $vehicle_variant->save();
                }
            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";
            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";

            Custom::userby($vehicle_variant, false);

            return response()->json(['status' => 1, 'message' => 'Vehicle Variant'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_variant->id, 'name' => $vehicle_variant->name, 'display_name' => $vehicle_variant->display_name, 'version' => $vehicle_variant->version,'config' => $config,'make_id' => $vehicle_make_id, 'model_id' => $vehicle_model_id, 'description' => ($vehicle_variant->description != null) ? $vehicle_variant->description : "", 'status' => $vehicle_variant->status]]);
           
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_variant = VehicleVariant::findOrFail($request->input('id'));
        $vehicle_variant->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Variant'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_variant_status_approval(Request $request)
    {
        VehicleVariant::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $vehicle_variants = explode(',', $request->id);
        $vehicle_variant_list = [];

        foreach ($vehicle_variants as $vehicle_variant_id) {
            $vehicle_variant_delete = VehicleVariant::findOrFail($vehicle_variant_id);
            $vehicle_variant_delete->delete();
            $vehicle_variant_list[] = $vehicle_variant_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Variant'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_variant_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $vehicle_variants = explode(',', $request->id);
        $vehicle_variant_list = [];

        foreach ($vehicle_variants as $vehicle_variant_id) {
            VehicleVariant::where('id', $vehicle_variant_id)->update(['status' => $request->input('status')]);;
            $vehicle_variant_list[] = $vehicle_variant_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Variant'.config('constants.flash.updated'),'data'=>['list' => $vehicle_variant_list]]);
    }

    public function get_vehicle_datas(Request $request) {

        $organization_id = Session::get('organization_id');
           
        $vehicle_details = VehicleRegisterDetail::where('id', $request->id)->first();

        //dd($vehicle_details);

        $cus_name = People::select('people.gst_no',DB::raw('CONCAT(display_name,"-",mobile_no) as cus_name'));
        $cus_name->where('user_type',$vehicle_details->user_type);
        if($vehicle_details->user_type == 0)
        {
            $cus_name->where('person_id', $vehicle_details->owner_id);
        }
        if($vehicle_details->user_type == 1)
        {
            $cus_name->where('business_id',$vehicle_details->owner_id);
        }
        $customer_detail=$cus_name->first();

        $additional_contacts = $vehicle_details->additional_contacts;
       if($additional_contacts != null){
            $contacts = json_decode($additional_contacts, TRUE);
            $array_contact = array_keys($contacts);
            $array_mobile = array_values($contacts);
        }else{
            $contacts = " ";
            $array_contact = " ";
            $array_mobile = " ";
        }
        
        if($vehicle_details->user_type == "0"){
            $customer_id = Person::findorfail($vehicle_details->owner_id)->id;

            $group_name_id = People:: select('id','group_id')->where('people.person_id',$customer_id)->first();
           
            $group_name = CustomerGroping:: select('id','name') 
            ->where('id', $group_name_id->group_id)->first();
            //dd($group_name);


        }

        if($vehicle_details->user_type == "1"){
            $customer_id = Business::findorfail($vehicle_details->owner_id)->id;
           
        $group_name_id = People::select('id','group_id')->where('people.business_id',$customer_id)->first();
        
        $group_name = CustomerGroping:: select('id','name') ->where('id', $group_name_id->group_id)->first();
        //dd($group_name);
        }   

        $vehicle_name = VehicleVariant::select('id','vehicle_configuration')->where('id', $vehicle_details->vehicle_configuration_id)->first(); 
         /*$last_updated_datas = VehicleRegisterDetail::select('transactions.id','vehicle_register_details.registration_no','transactions.reference_no','wms_transactions.job_date')->leftjoin('wms_transactions','wms_transactions.registration_id','=','vehicle_register_details.id')->leftjoin('transactions','transactions.id','=','wms_transactions.transaction_id')->where('wms_transactions.jobcard_status_id','8')->orderby('transactions.id',"DESC")->first();*/



         $last_updated_datas = Transaction::select('transactions.id','vehicle_register_details.registration_no','wms_transactions.job_date','transactions.reference_no','transactions.order_no');
        $last_updated_datas->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        $last_updated_datas->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id');
        $last_updated_datas->where('vehicle_register_details.registration_no',$vehicle_details->registration_no);
        $last_updated_datas->where(function ($query) {
                $query->where('wms_transactions.jobcard_status_id', '!=',"8")
                      ->orWhere('wms_transactions.jobcard_status_id', '=',null);
        });
        $last_updated_datas->where('transactions.organization_id',$organization_id);
        $last_updated_datas->orderBy('transactions.id',"DESC");
       $last_updated_data = $last_updated_datas->first();

        //dd($last_updated_data);
               
        if( $last_updated_data == null){
            $job_date="";
            $job_reference_no = "";
        }else{
            $job_date = $last_updated_data->job_date;
            $job_reference_no = $last_updated_data->order_no;
        }
                              

        $spec_values = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',
'registered_vehicle_specs.spec_value')->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')->where('registered_vehicle_specs.organization_id',$organization_id)->where('registered_vehicle_specs.registered_vehicle_id',$request->id)->get();



        /*$specifications = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name',
'registered_vehicle_specs.spec_value')->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')->where('registered_vehicle_specs.organization_id',$organization_id)->where('registered_vehicle_specs.registered_vehicle_id',$request->id)->get();*/
           
            $values = [];
            $spec = [];
         foreach ($spec_values as $key => $value) {
             $values['spec_values'][] = $spec_values[$key]->spec_value;
            $spec['specification'][] = $spec_values[$key]->display_name;
         }
                  
        if($vehicle_details != null) {
            return response()->json(['status' => 1, 'message' => 'Vehicle Datas Retreived Successfully.', 'data' => [
                'id' => $vehicle_details->id,
                'registration_no' => $vehicle_details->registration_no,
                'name' => $vehicle_details->name,
                'user_type' => $vehicle_details->user_type, 
                'owner_id' => $customer_id,
                'display_name' => $vehicle_details->display_name,
                'engine_no' => $vehicle_details->engine_no,
                'chassis_no' => $vehicle_details->chassis_no,
                'manufacturing_year' => $vehicle_details->manufacturing_year,
                'vehicle_category_id' => $vehicle_details->vehicle_category_id, 
                'vehicle_make_id' => $vehicle_details->vehicle_make_id, 
                'vehicle_model_id' => $vehicle_details->vehicle_model_id, 
                'vehicle_variant_id' => $vehicle_details->vehicle_variant_id, 
                'vehicle_version' => $vehicle_details->version,
                'vehicle_body_type_id' => $vehicle_details->vehicle_body_type_id, 
                'vehicle_rim_type_id' => $vehicle_details->vehicle_rim_type_id, 
                'vehicle_tyre_type_id' => $vehicle_details->vehicle_tyre_type_id, 
                'vehicle_tyre_size_id' => $vehicle_details->vehicle_tyre_size_id, 
                'vehicle_wheel_type_id' => $vehicle_details->vehicle_wheel_type_id, 
                'vehicle_drivetrain_id' => $vehicle_details->vehicle_drivetrain_id,
                'vehicle_usage_id' => $vehicle_details->vehicle_usage_id,
                'fuel_type_id' => $vehicle_details->fuel_type_id,
                'description' => ($vehicle_details->description != null) ? $vehicle_details->description : "", 
                'status' => $vehicle_details->status,
                'last_update_date' => $job_date,
                'last_update_jc' => $job_reference_no,
                'spec_values' => $values,
                'spec' =>  $spec,
                'driver'=> $vehicle_details->driver,
                'driver_contact'=> $vehicle_details->driver_mobile_no,
                
                'vehicle_permit_type'=>$vehicle_details->permit_type,
                'fc_due'=>$vehicle_details->fc_due,
                'permit_due'=>$vehicle_details->permit_due,
                'tax_due'=>$vehicle_details->tax_due,
                'vehicle_insurance'=>$vehicle_details->insurance,
                'vehicle_insurance_due'=>$vehicle_details->premium_date,
                'bank_loan'=>$vehicle_details->bank_loan,
                'month_due_date'=>$vehicle_details->month_due_date,
                'warranty_km'=>$vehicle_details->warranty_km,
                'warranty_yrs'=>$vehicle_details->warranty_years,
                 'group_name' => $group_name,
                'vehicle_name' => $vehicle_name->vehicle_configuration,
                'additional_contacts' => $contacts,
                'array_contact' => $array_contact,
                'array_mobile' => $array_mobile,
                'cus_name' => $customer_detail->cus_name,
                'gst' => $customer_detail->gst_no

                
            ]]);
        } else {
            return response()->json(['status' => 0, 'message' => 'No Vehicle Datas Available.', 'data' => []]);
        }
           
    }

    

    
}
