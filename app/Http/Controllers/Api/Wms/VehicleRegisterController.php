<?php

namespace App\Http\Controllers\Api\Wms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use DB;
use App\Helpers\Helper;

use App\OrganizationPerson;
use App\Organization;


use App\Custom;
use App\Business;
use App\User;

use App\City;

use App\VehicleVariant;
use App\VehicleCategory;
use App\VehicleRegisterDetail;
use App\WmsVehicleOrganization;
use App\RegisteredVehicleSpec;
use App\People;
use App\Person;
use App\PersonAddressType;
use App\State;
use App\Country;
use App\HrmEmployee;
use App\BusinessAddressType;
use App\BusinessCommunicationAddress;

use App\VehicleSpecification;
use App\PersonCommunicationAddress;
use App\AccountPersonType;
use App\PeopleAddress;
use App\PeopleTitle;
use App\PaymentMethod;
use App\Term;
use App\CustomerGroping;
Use Exception;

use Carbon\Carbon;
use DateTime;
use Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Vehicle\VehicleRepository;

class VehicleRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200;

    /* public function __construct()
     {
         $this->middleware('auth:api');
	 } */
         
             
         public function __construct(VehicleRepository $vehicleRepo)
         {
                $this->vehicleRepo = $vehicleRepo;

         }
         public function index(Request $request)
         {
           
                $organization_id = $request->org_id;
                $offset = $request->page;
                $limit = $request->per_page;
                
                Log::info("VehicleRegisterController->index_API :- Inside ");
                Log::info("VehicleRegisterController->index_API :- Inside Data - ".json_encode($request->all()));      
                $vehicles_registers = $this->vehicleRepo->findAll_API($request->all());

                Log::info("VehicleRegisterController->index_API :- Return ");

                return response()->json(['status'=>1,'data'=> $vehicles_registers], $this->successStatus);
       
                
         }

	 public function create($person_id,$organization_id)
	 {
		
              /*$vehicle_make_id = $this->vehicle_make_id;
        $vehicle_model_id = $this->vehicle_model_id;
        $vehicle_tyre_size = $this->vehicle_tyre_size;
        $vehicle_tyre_type = $this->vehicle_tyre_type;
        $vehicle_variant_id = $this->vehicle_variant;
        $vehicle_wheel = $this->vehicle_wheel;
        $fuel_type = $this->fuel_type;
        $rim_type = $this->rim_type;
        $body_type = $this->body_type;
        $vehicle_category = $this->vehicle_category;
        $vehicle_drivetrain = $this->vehicle_drivetrain;
        $service_type = $this->service_type;
        $vehicle_usage = $this->vehicle_usage;*/
        //$person_type = null;

// 
        // $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        // $vehicle_make_id->prepend('Select Vehicle Make', '');
        
        // $vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');
        // $vehicle_model_id->prepend('Select Vehicle Model', '');

        //  $vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');
        //  $vehicle_variant_id->prepend('Select Vehicle Variant', '');

      

        // $config_name = VehicleConfiguration::where('vehicle_configurations.organization_id', $organization_id)->orderBy('id')->pluck('vehicle_name', 'id');
        // $config_name->prepend('Select Vehicle Configuration', '');
//
                try{
        
                $vehicle_config=VehicleVariant::select('vehicle_configuration as name','id')->orderby('vehicle_configuration')->get();
 
                $vehicle_category = VehicleCategory::select('name', 'id')->orderBy('name')->get();
        
                $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
                ->where('user_type', 1)
                ->where('organization_id', $organization_id)
                ->where('business_id','!=',Null);
                $business = $business_list->get();

                $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
                ->where('user_type', 0)
                ->where('organization_id', $organization_id)
                ->where('person_id','!=',Null);
                
                $people = $people_list->get();
                
                $customer_list=People::select(DB::raw('(CASE WHEN person_id is NULL THEN business_id ELSE person_id END) AS id'), DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
               
                ->where('organization_id', $organization_id)
                ->where(function($query)
                {
                        $query->where('user_type', 0)
                        ->orWhere('user_type', 1);
                })
                ->orderByRaw('name');
                
                //   ->having('name','!=',NULL)
                
                $customer = $customer_list->get();
                
                // foreach($customer as $key=>$object){
                //         if($object->name==null )
                //         {
                //                 unset($customer[$key]);
                //         }
                        
                // }
                //dd($customer);

                $employee = HrmEmployee::select('hrm_employees.id')
                ->where('hrm_employees.organization_id', $organization_id)
                ->where('hrm_employees.person_id', $person_id)
                ->first();

                $selected_employee = ($employee != null) ? $employee->id : null;

                $business_id = Organization::find($organization_id)->business_id;
                
                $country_id = Country::where('name', 'India')->first()->id;
                $state = State::select('name', 'id')->where('country_id', $country_id)->orderBy('name')->orderby('name')->get();
        
       

     


       /* $people = $people_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');

        $business = $business_list->pluck('name', 'id');
        $business->prepend('Select Business', '');*/

//
        // $customer_type_label = 'Customer Type';
        // $customer_label = 'Customer';
        // $person_type = "customer";

        // $country_id = Country::where('name', 'India')->first()->id;
        // $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        // $state->prepend('Select State', '');

        // $title = PeopleTitle::pluck('display_name','id');
        // $title->prepend('Title','');

        // $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        // $payment->prepend('Select Payment Method','');

        // $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        // $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        // $terms->prepend('Select Term','');

        // $permit_type = VehiclePermit::orderby('name')->pluck('name','id');
        // //dd($permit_type);
        // $permit_type->prepend('Select Permit Type','');

        //
        
    

    
       

        //$person_type = "customer";
        // $specifications = VehicleSpecification::select('vehicle_types.name','vehicle_spec_masters.display_name AS spec_name','vehicle_spec_masters.id as spec_id',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name)as value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id)as value_id'),'vehicle_spec_masters.list','vehicle_specification_details.name as display_name','vehicle_specification_details.id as display_id')
        // ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specifications.vehicle_type_id')
        // ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','vehicle_specifications.vehicle_spec_id')
        // ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_specifications.vehicle_spec_id')
        // ->where('vehicle_specifications.organization_id',$organization_id)
        // ->where('vehicle_specifications.used',"1")
        // ->groupby('vehicle_spec_masters.display_name')
        // ->get();
        $specifications = VehicleSpecification::select('vehicle_types.name','vehicle_spec_masters.display_name AS spec_name','vehicle_spec_masters.id as spec_id',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name)as value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id)as value_id'),'vehicle_spec_masters.list','vehicle_specification_details.name as display_name','vehicle_specification_details.id as display_id')
       ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specifications.vehicle_type_id')
       ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','vehicle_specifications.vehicle_spec_id')
       ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_specifications.vehicle_spec_id')
       ->where('vehicle_specifications.organization_id',$organization_id)
       ->where('vehicle_specifications.used',"1")
       ->groupby('vehicle_spec_masters.display_name')
       ->get();
                

        $SpecData=[];
        $options_key=0;
        $text_key=0;
        foreach($specifications as $specification) {

                $values = $specification->value; 
                $var = explode(",",$values);
                $value_id =$specification->value_id;
                $value = explode(",",$value_id);
                $combined_array = array_combine($value, $var);
                $options_data=[];
                foreach($value as $key => $value)
                {
                        $options_data[]=['id'=>(int)$value,'value'=>$var[$key]]; 
                }
               
                if($specification->list==1)
                {
                        $SpecData[]=['key'=>$options_key,'display_name'=>$specification->spec_name,'id'=>(int)$specification->spec_id,'option'=>$options_data];
                        $options_key++;
                }
                if($specification->list==0)
                {
                        $SpecData[]=['key'=>$text_key,'display_name'=>$specification->spec_name,'id'=>(int)$specification->spec_id,'display_id'=>$combined_array];
                        $text_key++;
                }
                
        }
                     //dd($specifications);    
        /**
         * vehicle_config
         * vehicle_category
         * business
         * people
         * specifications
         */
        
     //   return view('trade_wms.vehicles_register_create', compact('config_name', 'vehicle_make_id', 'vehicle_model_id',  'vehicle_variant_id', 'vehicle_category','people', 'title', 'state', 'payment', 'terms','person_type','business','customer_type_label','customer_label','person_id','vehicle_config','selected_employee','permit_type','specifications'));
   
   	                        return response()->json(['status'=>1,'state'=>$state,'vehicle_config'=>$vehicle_config,'vehicle_category'=>$vehicle_category,'business'=>$business,'people'=>$people,'customer'=>$customer,'specifications'=>$specifications,'spec_data'=>$SpecData], $this->successStatus);
                        } 
                        catch (Exception $e) {
                                
                                $ReturnData[]= $e->getMessage();
                                return response()->json($ReturnData);
                        }

         }
         
         public function store(Request $request){

             try{  
                     
                
                
                $inputs=$request->all();
            
               // return response()->json(['status' => 1, $inputs], $this->successStatus);
       
                // $inputs['spec_list_id'];
                // $inputs['spec_list_value'];

                // $inputs['spec_list_vtext'];
                // $inputs['spec_text_value'];
                // $inputs['spec_text_key'];
                // $inputs['registration_no'];
                // $inputs['user_type'];
                // $inputs['people_id'];
                // $inputs['vehicle_category'];
                // $inputs['vehicle_config'];
                /*
                spec_list_id,
                spec_list_value,
                spec_list_vtext,
                spec_text_value,
                spec_text_key,
                */
                
                $config = VehicleVariant::where('id',$inputs['vehicle_config'])->first();
                
                $vehicle_register = new VehicleRegisterDetail;
                
                
                $vehicle_register->registration_no =  $inputs['registration_no'];;
                $vehicle_register->owner_id = $inputs['people_id'];
                $vehicle_register->user_type =   $inputs['user_type'];
                $vehicle_register->vehicle_configuration_id = $inputs['vehicle_config'];
                //$vehicle_register->is_own = $request->input('is_own');        
                $vehicle_register->vehicle_category_id = $inputs['vehicle_category'];
                $vehicle_register->vehicle_make_id = $config->vehicle_make_id;
                $vehicle_register->vehicle_model_id = $config->vehicle_model_id;
                $vehicle_register->vehicle_variant_id = $config->id;
                $vehicle_register->version = $config->version;
                
                $organization_id = (int)$inputs['organization_id'];
                
                $vehicle_register->organization_id = (int)$inputs['organization_id'];
                $vehicle_register->created_by=(array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;;
                $vehicle_register->last_modified_by=(array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;
                
                $vehicle_register->save(); 
                
                
                Custom::add_addon('records', $organization_id);
                
                
                
                
                
                if($vehicle_register)
                {
                        
                        $wms_vehicle_org =new  WmsVehicleOrganization;
                        $wms_vehicle_org->organization_id = (int)$inputs['organization_id'];
                        $wms_vehicle_org->vehicle_id = $vehicle_register->id;
                        $wms_vehicle_org->created_by =(array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;
                        $wms_vehicle_org->last_modified_by =(array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;
                        $wms_vehicle_org->save();
                }
                
                if(count($inputs['spec_text_key'])>0 && count($inputs['spec_text_value'])>0){
                        $vehicle_id = $vehicle_register->id;
                        $spec_text_values = $inputs['spec_text_value'];
                        $spec_text_id = $inputs['spec_text_key'];
                        $compains = array_combine($spec_text_id,$spec_text_values);
                        foreach ($compains as $key => $value) {
                                if($key && $value){
                                        
                                        $vehicle_register_spec = new RegisteredVehicleSpec;
                                        $vehicle_register_spec->registered_vehicle_id = $vehicle_id;
                                        $vehicle_register_spec->registered_vehicle =$inputs['registration_no'];
                                        $vehicle_register_spec->spec_id = $key;
                                        $vehicle_register_spec->spec_value = $value;
                                        $vehicle_register_spec->organization_id = (int)$inputs['organization_id'];
                                        $vehicle_register_spec->created_by = (array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;
                                        $vehicle_register_spec->save();
                                }
                        }
                }
                if(count($inputs['spec_list_id'])>0 && count($inputs['spec_list_value'])>0){
                        
                        
                        $spec_list_text = $inputs['spec_list_vtext'];
                        $vehicle_id = $vehicle_register->id;
                        $spec_list_values = $inputs['spec_list_value'];
                        $spec_list_keys = $inputs['spec_list_id'];
                        $list_compains = array_combine($spec_list_keys,$spec_list_values);
                        $value_compain = array_combine($spec_list_keys,$spec_list_text);
                        foreach ( $list_compains as $key => $value) {
                                
                                if($key && $value){
                                        
                                        $vehicle_register_spec = new RegisteredVehicleSpec;
                                        $vehicle_register_spec->registered_vehicle_id = $vehicle_id;
                                        $vehicle_register_spec->registered_vehicle = $inputs['registration_no'];
                                        $vehicle_register_spec->spec_id = $key;
                                        $vehicle_register_spec->spec_value = $value_compain[$key]; 
                                        $vehicle_register_spec->spec_value_id = $value;
                                        $vehicle_register_spec->organization_id = (int)$inputs['organization_id'];
                                        $vehicle_register_spec->created_by = (array_key_exists('auth_user_id',$inputs))?$inputs['auth_user_id'] :null;
                                        $vehicle_register_spec->save();
                                }
                                
                        }
                        
                }
             //   return response()->json(['status' => 0,"data"=>  $inputs], $this->successStatus);
                return response()->json(['status' => 1,'id'=>$vehicle_register->id,'name'=>$vehicle_register->registration_no], $this->successStatus);
                
        } 
        catch (Exception $e) {

                if ($e instanceof ModelNotFoundException) {
                        return response()->json(['status' => 0,
                            'error' => 'Entry for '.str_replace('App\\', '', $e->getModel()).' not found'], 200);
                    }
                //$ReturnData[]= $e->getMessage();
                $message="";
                if($e->getCode()==2300||$e->getCode()==0)
                {
                        $message="Please fill the all fields";
                }

                return response()->json(['status' => 0,"file" => $e->getFile(),
                "line" => $e->getLine(),
                "code" => $e->getCode(),
                "exception" => (new \ReflectionClass($e))->getShortName(),'message'=>$e->getMessage(),'ErrorMessage'=>$message],200);
        }

        }

         public function search(Request $request){

                $inputs=$request->all();
               // return response()->json(['status'=>1,'data'=> $inputs], $this->successStatus);

                if($inputs['person_type']==0)
                {

                        //DB::raw('CONCAT(persons.first_name, "  " , persons.last_name)AS name')
                        //$query = Person::select('persons.id as id',DB::raw("CONCAT(persons.first_name,' ',persons.last_name) AS name"),'person_communication_addresses.mobile_no as mobile_no');
                     //   $query->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
                     $query = Person::select('persons.id as id', 'persons.first_name as name', 'persons.last_name','person_communication_addresses.mobile_no as mobile_no');
                     $query->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
     
                        if($inputs && count($inputs)>0)
                        {
                              
        
                                        if($inputs['name']) {
                                                $query->where("first_name", $inputs['name']);
                                        }
                                        
                                        else if($inputs["mobile_no"]) {
                                                $query->where("mobile_no", 'LIKE', "%".$inputs["mobile_no"]."%");
                                        }
                                       
                              
                        }
        
                        $query->groupby('persons.id');
        
                      
                        $results = $query->get();

                        
                }

                
                if($inputs['person_type']==1)
                {
                                
                                                $query = Business::select('businesses.id as id','businesses.business_name as name','businesses.business_name', 'business_communication_addresses.mobile_no as mobile_no');
                                                $query->leftJoin('business_communication_addresses', 'businesses.id', '=', 'business_communication_addresses.business_id');
                                                $query->leftjoin('cities','cities.id','=','business_communication_addresses.city_id');
                                
                                        
                                                        if($inputs && count($inputs)>0 ) {
                                
                                                                if($inputs["name"]) {
                                                                        $query->where("business_name", 'LIKE', "%".$inputs["name"]."%");
                                                                }
                                                                
                                                                else if($inputs["mobile_no"]) {
                                                                        $query->where("mobile_no", 'LIKE', "%".$inputs["mobile_no"]."%");
                                                                }
                                                                
                                                        }
                                        
                                
                                                $query->groupby('businesses.id');
        
                       
        
        
                        $results = $query->get();
               
                }




		return response()->json(['status'=>1,'data'=> $results], $this->successStatus);
       
 
         }

         public function getCity($id)
         {
                        $city = City::select('id', 'name')->where('state_id',$id)->get();

			return response()->json(array('result' => $city));
         }

         public function add_business(Request $request) {
               
                                //dd($request->all());
                                $inputs= $request->all();
                                //return response()->json(array('result' => $inputs));
                                if($inputs['IsExistData']==1)
                                {
                                        $ReturnData=self::add_bussiness_exist($inputs);
                                
                                        return response()->json($ReturnData);
                                } 
                                                $business_mobile = $inputs['business_mobile'];

                                $mobile_no = User::where('mobile',$business_mobile)->first();
                                if(!empty($mobile_no->id))
                                {
                                        return response()->json(['status' => 0]);
                                }
                                else
                                {
                                $city = City::select('name')->where('id', $inputs['business_city'])->first()->name;

                                $bcrm_code = Custom::business_crm($city, $inputs['business_mobile'], $inputs['business_name']);

                                $business = new Business;
                                $business->bcrm_code = $bcrm_code;
                                $business->business_name = $inputs['business_name'];
                                $business->alias = $inputs['business_name'];
                                //$business->pan = $request->input('business_pan');
                                $business->gst = $inputs['gst'];

                                $business->save();

                                if($business->id) {

                                        $address_type = BusinessAddressType::where('name', 'business')->first();

                                        $business_address = new BusinessCommunicationAddress;
                                        $business_address->business_id = $business->id;
                                        $business_address->address_type = $address_type->id;
                        //		$business_address->address = $request->input('business_address');
                                        $business_address->city_id =  $inputs['business_city'];
                                        $business_address->mobile_no =  $inputs['business_mobile'];
                                        $business_address->mobile_no_prev =  $inputs['business_mobile'];
                                        $business_address->email_address = $inputs['business_email'];;
                                        $business_address->save();

                                        $people = new People();
                                        $people->business_id = $business->id;
                                        $people->display_name = $inputs['business_name'];
                                        $people->mobile_no =$inputs['business_mobile'];
                                        $people->organization_id = $inputs['organization_id'];
                                        $people->user_type = 1;
                                        $people->save();

                                        $people_address_data=City::select('states.id as id','states.name as state_name','cities.name as city_name')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$inputs['business_city'])->first();
                             
                                        if($people->id) {
                                                $people_address = new PeopleAddress();
                                                $people_address->people_id = $people->id;
                                                //$people_address->address = $request->input('address');
                                                $people_address->city_id = $inputs['business_city'];
                                                //$people_address->pin = $request->input('pin');
                                                 $people_address->address =$people_address_data->city_name.",".$people_address_data->state_name;;
				                // $people_address->address_type =  $person_info->address_type;
				               // $people_address->city_id = $person_info->city_id;
                                                $people_address->save();
                                        }
                                } 

                                if($inputs['person_type']!= null) {

                                        $person_type_id = AccountPersonType::where('name', $inputs['person_type'])->first()->id;

                                        $person_type = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id)->first();

                                        if($person_type == null) {
                                                DB::table('people_person_types')->insert(['people_id' => $people->id, 'person_type_id' => $person_type_id]);
                                        }
                                }


                                $responseData=['status' => 1, 'message' => 'Contact'.config('constants.flash.added'), 'data' => ['id' => $business->id, 'name' => $people->display_name.'-'.$inputs['business_mobile'],'user_type'=>$person_type_id]];
                                } 
                        
              
                  
                    
                    return response()->json($responseData);
             
        }
        

        public function add_user(Request $request) {

		//dd($request->all());
		//$mobile_number = $request->input('mobile_no');
                $inputs= $request->all();
                Log::info("VehicleRegisterController->add_user :-".json_encode($inputs));
                if($inputs['IsExistData']==1)
                {
                        $ReturnData=self::add_people($inputs);
                      
                        return response()->json($ReturnData);
                } 

              //  return response()->json(array('result' => $inputs));
		$mobile_no = User::where('mobile',$inputs['mobile_no'])->first();
		if(!empty($mobile_no->id))
		{
			return response()->json(['status' =>0]);
		}
		else
		{
		Log::info("VehicleRegisterController->add_user->getCityId :-".$inputs['city_id']);
		$city = City::select('name')->where('id', $inputs['city_id'])->first()->name;
		Log::info("VehicleRegisterController->add_user->city :-".$city);

		$crm_code = Custom::personal_crm($city,$inputs['mobile_no'], $inputs['first_name']);

		$person = new Person;
		$person->crm_code = $crm_code;
		$person->first_name = $inputs['first_name'];
		// $person->pan_no = $request->input('pan');
		// $person->aadhar_no = $request->input('aadhar_no');
		// $person->passport_no = $request->input('passport_no');
		// $person->license_no = $request->input('license_no');
		$person->save();

		if($person->id) {

			$address_type = PersonAddressType::where('name', 'residential')->first();

			$person_address = new PersonCommunicationAddress;
			$person_address->person_id = $person->id;
			$person_address->address_type = $address_type->id;
			//$person_address->address = $request->input('address');
			$person_address->city_id = $inputs['city_id'];
			$person_address->mobile_no = $inputs['mobile_no'];
			$person_address->mobile_no_prev = $inputs['mobile_no'];
			$person_address->email_address = $inputs['email'];
			$person_address->save();

			$people_exist = People::where('person_id', $person->id)->where('organization_id', $inputs['organization_id'])->first();

			if($people_exist == null) {
				$people = new People();
				$people->person_id = $person->id;
				$people->first_name = $inputs['first_name'];
				$people->display_name = $inputs['first_name'];
				$people->mobile_no = $inputs['mobile_no'];
				$people->email_address = $inputs['email'];;
				$people->organization_id = $inputs['organization_id'];
				$people->save();

				Custom::add_addon('records',$inputs['organization_id']);
			} else {
				$people = $people_exist;
			}

			if( $inputs['person_type'] != null) {

				$person_type_id = AccountPersonType::where('name', $inputs['person_type'])->first()->id;

				$person_type = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id)->first();

				if($person_type == null) {
					DB::table('people_person_types')->insert(['people_id' => $people->id, 'person_type_id' => $person_type_id]);
				}
			}
			

			 if($people->id) {

                                $people_address_data=City::select('states.id as id','states.name as state_name','cities.name as city_name')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$person_address->city_id)->first();
                            
				$people_address = new PeopleAddress();
				$people_address->people_id = $people->id;
                                $people_address->address_type = 0;
                                $people_address->address =$people_address_data->city_name."<br>".$people_address_data->state_name;;
				         
				//$people_address->address = $request->input('address');
				$people_address->city_id = $inputs['city_id'];
				//$people_address->pin = $request->input('pin');
				$people_address->save();
			}
		} 


		return response()->json(['status' => 1, 'message' => 'Contact'.config('constants.flash.added'), 'data' => ['id' => $person->id, 'name' => $people->first_name.'-'.$inputs['mobile_no'],'user_type'=>$person_type_id]]);   
		}   
        }
        
        public function check_business_gst_number(Request $request)
	{
		//dd($request->all());
		$gst = Business::where('gst', $request->number)->where('status', '1')->first();
                //dd($gst);
                $returnData="";
		if(!empty($gst->id)) {
                        $returnData=true;
		} else {
			$returnData=false;
                }
                
                return response()->json(['status' => 1,  'result' =>$returnData]);   

        }
        
        public function check_business_mobile_number(Request $request) {
		//dd($request->all());
                $mobile = BusinessCommunicationAddress::select('businesses.id','businesses.business_name as name')
                ->leftjoin('businesses','business_communication_addresses.business_id','=','businesses.id')
                ->where('business_communication_addresses.mobile_no', $request->mobile_no)
                ->where('business_communication_addresses.address_type', 1)
                ->where('business_communication_addresses.status', '1')->first();
                
                if(!empty($mobile->id)) {
                        $returnData=true;
		} else {
			$returnData=false;
                }
                return response()->json(['status' => 1,  'result' =>$returnData,'data'=>$mobile]);   

        }
        

        public function check_person_mobile_number(Request $request) {
        try
        {
		// $mobile = PersonCommunicationAddress::select('persons.id','persons.first_name as name')
                // ->leftjoin('persons','person_communication_addresses.person_id','=','persons.id')
                // ->where('person_communication_addresses.mobile_no', $request->mobile_no)
                // ->where('person_communication_addresses.address_type', 1)
                // ->where('person_communication_addresses.status', '1')
                // ->first();
                $query = Person::select('persons.id','persons.first_name as name');
		$query->leftJoin('person_communication_addresses', 'persons.id', '=', 'person_communication_addresses.person_id');
                $query->where('person_communication_addresses.mobile_no', $request->mobile_no);
                $query->groupby('persons.id');
                

                $PersonData=$query->first();

                if(!empty($PersonData->id)) {
		        $returnData=true;
		} else {
			$returnData=false;
                }
                return response()->json(['status' => 1,  'result' =>$returnData,'data'=>$PersonData]);   
        }  
        catch (Exception $e) {

         
                $ReturnData[]= $e->getMessage();
                return response()->json($ReturnData);
        } 
               

        }
        
       public function getPeople(Request $request)
       {
        try
        {
                $inputs=$request->all();

                if($inputs['customer_type']==1)
                {
                        
                $business = Business::findOrFail( $inputs['id']);
                      //  dd($business);
                $business_address_type = BusinessAddressType::where('name', 'business')->first();
                $business_communication_addresses = BusinessCommunicationAddress::where('business_id', $business->id)->where('address_type', $business_address_type->id)->first();
                
                $check_person = People::where('business_id', $business->id)->where('organization_id', $inputs['organization_id'])->first();
             // dd( $business_communication_addresses);
                if($check_person == null) {

                        $people_id = $business->id;
                // $business_id = $business->id;
                        $people_name=$business->alias;
                        $people_mobile=$business_communication_addresses->mobile_no ;
                        $people_city_id=$business_communication_addresses->city_id;
                        $people_gst=$business->gst;
                        
                }else{
                        $people_id = $check_person->id;
                        //$business_id = $check_person->business_id;
                        $people_name=$check_person->company;
                        $people_mobile=$business_communication_addresses->mobile_no ;
                        $people_city_id=$business_communication_addresses->city_id;
                        $people_gst=$business->gst;
                }
                        $people_state_id=City::select('states.id')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$people_city_id)->first()->id;
                }

                if($inputs['customer_type']==0)
                {

                        
        
		$person = Person::findOrFail($inputs['id']);
		$person_info = PersonCommunicationAddress::select('id','mobile_no','email_address','address_type','address','city_id')->where('person_id', $inputs['id'])->first();
		//dd($person_info);
		$people_exist = People::where('person_id', $person->id)->where('organization_id', $inputs['organization_id'])->where('mobile_no', $person_info->mobile_no)->first();

		if($people_exist == null) {
			

			
                        $person_id = $person->id;
                        $people_name=$person->first_name;
                        $people_mobile=$person_info->mobile_no;
                        $people_city_id=$person_info->city_id;
                        $people_gst=$person->gst_no;
                       
		} else {
			
                        $person_id = $people_exist->person_id;
                        $people_name=$people_exist->first_name;
                        $people_mobile=$people_exist->mobile_no ;
                        $people_city_id=$person_info->city_id;
                        $people_gst=$person->gst_no;
		}
                $people_state_id=City::select('states.id')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$people_city_id)->first()->id;

                }

                $ReturnData=['status' => 1, 'message' => 'Contact'.config('constants.flash.added'), 'data' => ['id' => $inputs['id'],'name'=>$people_name,'gst'=>$people_gst,'mobile_no'=>$people_mobile,'city_id'=>(int)$people_city_id,'state_id'=>(int)$people_state_id]];     
                return response()->json($ReturnData);
        }  
        catch (Exception $e) {

                $ReturnData[]= $e->getMessage();
                return response()->json($ReturnData);
        } 
       }


        public function add_bussiness_exist($inputs) {


                try{
                        
	
                //$inputs=$request->all();

		$business = Business::findOrFail( $inputs['id']);
		$business_address_type = BusinessAddressType::where('name', 'business')->first();
		$business_communication_addresses = BusinessCommunicationAddress::where('business_id', $business->id)->where('address_type', $business_address_type->id)->first();

		$check_person = People::where('business_id', $business->id)->where('organization_id', $inputs['organization_id'])->where('mobile_no', $business_communication_addresses->mobile_no)->first();

		if($check_person == null) {
			$people = new People;
			$people->business_id = $business->id;
			$people->company = $business->alias;
			$people->display_name = $business->alias;
			$people->mobile_no = $business_communication_addresses->mobile_no;
			$people->email_address = $business_communication_addresses->email_address;
			$people->organization_id = $inputs['organization_id'];
			$people->user_type = 1;
			$people->save();

			if($people->id) {
                                $people_address_data=City::select('states.id as id','states.name as state_name','cities.name as city_name')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$business_communication_addresses->city_id)->first();
                            
				$people_address = new PeopleAddress();
				$people_address->people_id = $people->id;
				$people_address->address = ($business_communication_addresses->address)?$business_communication_addresses->address."<br>".$people_address_data->city_name."<br>".$people_address_data->state_name:$people_address_data->city_name."<br>".$people_address_data->state_name;
				$people_address->city_id = $business_communication_addresses->city_id;
				$people_address->pin = $business_communication_addresses->pin;
				$people_address->landmark = $business_communication_addresses->landmark;
				$people_address->google = $business_communication_addresses->google;
				$people_address->save();
			}
                        $people_id = $people->id;
                        $people_name=$people->company;
                        $people_mobile=$people->mobile_no ;
                        $people_city_id=$people_address->city_id;
                        $people_gst=$people->gst_no;
                       
                        
			$business_id = $people->business_id;
		} else {
			$people_id = $check_person->id;
                        $business_id = $check_person->business_id;
                        $people_name=$check_person->company;
                        $people_mobile=$business_communication_addresses->mobile_no ;
                        $people_city_id=$business_communication_addresses->city_id;
                        $people_gst=$check_person->gst_no;
                       
                }
               
                $people_state_id=City::select('states.id')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$people_city_id)->first()->id;


		if($inputs['person_type'] != null) {

			$person_type_id = AccountPersonType::where('name', $inputs['person_type'])->first()->id;

			$person_type = DB::table('people_person_types')->where('people_id', $people_id)->where('person_type_id', $person_type_id)->first();

			if($person_type == null) {
				DB::table('people_person_types')->insert(['people_id' => $people_id, 'person_type_id' => $person_type_id]);
			}
                }
                
                $ReturnData=['status' => 1, 'message' => 'Contact'.config('constants.flash.added'), 'data' => ['id' => $people_id , 'name' => $people_name.'-'. $people_mobile,'user_type'=>$person_type_id]];     
                return $ReturnData;
        }
        catch (Exception $e) {
                
                $ReturnData[]= $e->getMessage();
                return $ReturnData;
        }
       
        }
        
        public function add_people($inputs) {

	
             //   $inputs=$request->all();
        try{
		$person = Person::findOrFail($inputs['id']);
		$person_info = PersonCommunicationAddress::select('id','mobile_no','email_address','address_type','address','city_id')->where('person_id', $inputs['id'])->first();
		//dd($person_info);
		$people_exist = People::where('person_id', $person->id)->where('organization_id', $inputs['organization_id'])->where('mobile_no', $person_info->mobile_no)->first();

		if($people_exist == null) {
			$people = new People;
			$people->person_id = $person->id;
			$people->first_name = $person->first_name;
			$people->last_name = $person->last_name;
			$people->display_name = $person->first_name;
			$people->mobile_no = $person_info->mobile_no;
			$people->email_address = $person_info->email_address;
			$people->organization_id = $inputs['organization_id'];
			$people->save();

			Custom::add_addon('records',$inputs['organization_id']);

			if($people->id) {

                                $people_address_data=City::select('states.id as id','states.name as state_name','cities.name as city_name')->leftjoin('states','cities.state_id','=','states.id')->where('cities.id',$person_info->city_id)->first();
                                $people_state_id=$people_address_data->id;
				$people_address = new PeopleAddress();
				$people_address->people_id = $people->id;
				$people_address->address =($person_info->address)?$person_info->address.",".$people_address_data->city_name."<br>".$people_address_data->state_name:$people_address_data->city_name."<br>".$people_address_data->state_name;;
				$people_address->address_type =  $person_info->address_type;
				$people_address->city_id = $person_info->city_id;

				$people_address->save();
			}

			$people_id = $people->id;
                        $person_id = $people->person_id;
                        $people_name=$people->first_name;
                        $people_mobile=$people->mobile_no ;
                        $people_city_id=$people_address->city_id;
                        $people_gst=$people->gst_no;
                       
		} else {
			$people_id = $people_exist->id;
                        $person_id = $people_exist->person_id;
                        $people_name=$people_exist->first_name;
                        $people_mobile=$people_exist->mobile_no ;
                        $people_city_id=$person_info->city_id;
                        $people_gst=$people_exist->gst_no;
		}
                
		

		if($inputs['person_type'] != null) {

			$person_type_id = AccountPersonType::where('name', $inputs['person_type'])->first()->id;

			$person_type = DB::table('people_person_types')->where('people_id', $people_id)->where('person_type_id', $person_type_id)->first();

			if($person_type == null) {
				DB::table('people_person_types')->insert(['people_id' => $people_id, 'person_type_id' => $person_type_id]);
			}
		}

                return ['status' => 1, 'message' => 'Contact'.config('constants.flash.added'), 'data' => ['id' => $people_id , 'name' => $people_name.'-'. $people_mobile,'user_type'=>$person_type_id]];     
  
        } 
        catch (Exception $e) {
                
                $ReturnData[]= $e->getMessage();
                return $ReturnData;
        }
}
        



}
