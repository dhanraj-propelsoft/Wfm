<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleRegisterDetail;
use App\VehicleConfiguration;
use App\VehicleDrivetrain;
use App\VehicleTyreSize;
use App\VehicleTyreType;
use App\VehicleBodyType;
use App\VehicleCategory;
use App\VehicleFuelType;
use App\VehicleSpecification;
use App\VehicleRimType;
use App\VehicleVariant;
use App\CustomerGroping;
use App\PaymentMethod;
use App\VehicleWheel;
use App\VehicleModel;
use App\VehicleUsage;
use App\VehicleMake;
use App\ServiceType;
use App\PeopleTitle;
use App\HrmEmployee;
use App\VehiclePermit;
use App\VehicleSpecificationDetails;
use App\RegisteredVehicleSpec;
use App\WmsVehicleOrganization;
use App\BusinessProfessionalism;
use App\Country;
use App\Custom;
use App\Person;
use App\People;
use App\Business;
use App\Organization;
use App\PaymentMode;
use App\Customer;
use App\PeopleAddress;
use Validator;
use App\State;
use App\City;
use App\Term;
use App\VehicleType;
use Carbon\Carbon;
use Session;
use Auth;
use DB;

class VehicleRegisteredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*public $vehicle_make_id, $vehicle_model_id, $vehicle_tyre_size, $vehicle_tyre_type, $vehicle_variant, $vehicle_wheel, $fuel_type, $rim_type, $body_type, $vehicle_category, $vehicle_drivetrain, $service_type, $vehicle_usage;*/

    public function __construct()
    {
        $organization_id = Session::get('organization_id');

        $this->vehicle_make_id = VehicleMake::where('vehicle_makes.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_make_id->prepend('Select Vehicle Make', '');
        
        $this->vehicle_model_id = VehicleModel::where('vehicle_models.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_model_id->prepend('Select Vehicle Model', '');

        $this->vehicle_tyre_size = VehicleTyreSize::where('vehicle_tyre_sizes.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_size->prepend('Select Tyre Size', '');

        $this->vehicle_tyre_type = VehicleTyreType::where('vehicle_tyre_types.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_type->prepend('Select Tyre Type', '');

        $this->vehicle_variant = VehicleVariant::where('vehicle_variants.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_variant->prepend('Select Vehicle Variant', '');

       /* $this->vehicle_wheel = VehicleWheel::where('vehicle_wheels.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_wheel->prepend('Select Vehicle Wheel', '');*/

        $this->fuel_type = VehicleFuelType::where('vehicle_fuel_types.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->fuel_type->prepend('Select Fuel Type', '');

        $this->rim_type = VehicleRimType::where('vehicle_rim_types.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->rim_type->prepend('Select Rim Type', '');

        $this->body_type = VehicleBodyType::where('vehicle_body_types.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->body_type->prepend('Select Body Type', '');

        $this->vehicle_category = VehicleCategory::where('vehicle_categories.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_category->prepend('Select Vehicle Category', '');

        $this->vehicle_drivetrain = VehicleDrivetrain::where('vehicle_drivetrains.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_drivetrain->prepend('Select Drivetrain', '');

        $this->service_type = ServiceType::where('service_types.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->service_type->prepend('Select Service Type', '');

        $this->vehicle_usage = VehicleUsage::where('vehicle_usages.organization_id', $organization_id)->orderBy('name')->pluck('name', 'id');
        $this->vehicle_usage->prepend('Select Vehicle Usage', '');

    }

    public function index()
    {
        $organization_id = Session::get('organization_id');
        // $module_name = Session::get('module_name');
        //  //dd( $module_name);
        $now = Carbon::now();
        $current_date =  $now->format('d-m-Y h:i:s');
        $add_date = date("d-m-Y h:i:s", strtotime("+1 hours"));

        //dd($current_date);

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $payment->prepend('Select Title','');

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

        $group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',$organization_id);
        $group_name->prepend('Select Group Name','');
        
        $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id', 'vehicle_register_details.registration_no', 'vehicle_register_details.is_own', 'vehicle_register_details.description', 'vehicle_register_details.status', 'vehicle_configurations.vehicle_name', 'vehicle_categories.name AS category_name', 'vehicle_makes.name AS make_name', 'vehicle_models.name AS model_name', 'vehicle_variants.name AS variant_name', 'vehicle_body_types.name AS body_type_name', 'vehicle_rim_types.name AS rim_type_name', 'vehicle_tyre_types.name AS tyre_type_name', 'vehicle_tyre_sizes.name AS tyre_size_name', 'vehicle_wheels.name AS wheel_name', 'vehicle_drivetrains.name AS drivetrain_name', 'vehicle_fuel_types.name AS fuel_type_name', 'vehicle_usages.name AS usage_name','vehicle_variants.vehicle_configuration',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));
      
        

        $vehicles_register->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        $vehicles_register->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });

       
        $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');

        

        $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');

        $vehicles_register->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');

        $vehicles_register->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_register_details.vehicle_model_id');

        $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

        $vehicles_register->leftJoin('vehicle_body_types', 'vehicle_body_types.id','=','vehicle_register_details.vehicle_body_type_id');

        $vehicles_register->leftJoin('vehicle_rim_types', 'vehicle_rim_types.id','=','vehicle_register_details.vehicle_rim_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_types', 'vehicle_tyre_types.id','=','vehicle_register_details.vehicle_tyre_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_sizes', 'vehicle_tyre_sizes.id','=','vehicle_register_details.vehicle_tyre_size_id');

        $vehicles_register->leftJoin('vehicle_wheels', 'vehicle_wheels.id','=','vehicle_register_details.vehicle_wheel_type_id');

        $vehicles_register->leftJoin('vehicle_drivetrains', 'vehicle_drivetrains.id','=','vehicle_register_details.vehicle_drivetrain_id');

        $vehicles_register->leftJoin('vehicle_fuel_types', 'vehicle_fuel_types.id','=','vehicle_register_details.fuel_type_id');

        $vehicles_register->leftJoin('vehicle_usages', 'vehicle_usages.id','=','vehicle_register_details.vehicle_usage_id');

        $vehicles_register->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');

        $vehicles_register->where('vehicle_register_details.status', '1');

        $vehicles_register->where('wms_vehicle_organizations.organization_id', $organization_id);

        $vehicles_register->orderby('vehicle_register_details.id');
        

        $vehicles_registers = $vehicles_register->get();
        
    

        return view('trade_wms.vehicles_register', compact('vehicles_registers', 'title', 'state', 'payment', 'terms','group_name','module_name','city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        $organization_id = Session::get('organization_id');
        $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
      
        if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
       
        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first();
        if($business_professionalism_id->business_professionalism_id == 4)
        {
            $type_id = VehicleType::where('name', $business_professionalism_name->name)->first()->id;
        }
      
        }
        

        $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');
        
        $vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');
        $vehicle_model_id->prepend('Select Vehicle Model', '');

         $vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');
         $vehicle_variant_id->prepend('Select Vehicle Variant', '');

        $vehicle_category = VehicleCategory::orderBy('name')->pluck('name', 'id');
        $vehicle_category->prepend('Select Vehicle Category', '');

        $config_name = VehicleConfiguration::where('vehicle_configurations.organization_id', $organization_id)->orderBy('id')->pluck('vehicle_name', 'id');
        $config_name->prepend('Select Vehicle Configuration', '');

        if($business_professionalism_id->business_professionalism_id == 4)
        {
           
            $vehicle_config=VehicleVariant::where('type_id',$type_id)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
           
           
        }
        else
        {
            $vehicle_config=VehicleVariant::where('type_id','!=',2)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
            //dd("test1");
           
        }
        

        $person_id = Auth::user()->person_id;

        $employee = HrmEmployee::select('hrm_employees.id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->where('hrm_employees.person_id', $person_id)
        ->first();

        $selected_employee = ($employee != null) ? $employee->id : null;

        $business_id = Organization::find($organization_id)->business_id;

       

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
        ->where('user_type', 1)
        ->where('organization_id', Session::get('organization_id'));

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
        ->where('user_type', 0)
        ->where('organization_id', Session::get('organization_id'));

        $busi=People::select(DB::raw('(CASE WHEN person_id is NULL THEN business_id ELSE person_id END) AS id'), DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
               ->where('organization_id', $organization_id)
               ->where(function($query)
               {
                       $query->where('user_type', 0)
                       ->orWhere('user_type', 1);
               })
               ->orderByRaw('name');
               $bus = $busi->get();



       /* $people = $people_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');

        $business = $business_list->pluck('name', 'id');
        $business->prepend('Select Business', '');*/


        $customer_type_label = 'Customer Type';
        $customer_label = 'Customer';
        $person_type = "customer";
        $people = $people_list->pluck('name', 'id');
        $business = $business_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');
        $business->prepend('Select Business', '');


        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::select('name', 'id')->where('country_id', $country_id)->orderBy('name')->orderby('name')->get();
       

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $permit_type = VehiclePermit::orderby('name')->pluck('name','id');
        //dd($permit_type);
        $permit_type->prepend('Select Permit Type','');
       

        //$person_type = "customer";
        $specifications = VehicleSpecification::select('vehicle_types.name','vehicle_spec_masters.display_name AS spec_name','vehicle_spec_masters.id as spec_id',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name)as value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id)as value_id'),'vehicle_spec_masters.list','vehicle_specification_details.name as display_name','vehicle_specification_details.id as display_id')
        ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specifications.vehicle_type_id')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','vehicle_specifications.vehicle_spec_id')
        ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_specifications.vehicle_spec_id')
        ->where('vehicle_specifications.organization_id',$organization_id)
        ->where('vehicle_specifications.used',"1")
        ->groupby('vehicle_spec_masters.display_name')
        ->get();
        //dd($specifications);    

        return view('trade_wms.vehicles_register_create', compact('config_name', 'vehicle_make_id', 'vehicle_model_id',  'vehicle_variant_id', 'vehicle_category','people', 'title', 'state', 'payment', 'terms','person_type','business','customer_type_label','customer_label','person_id','vehicle_config','selected_employee','permit_type','specifications','bus'));
    }

     public function jc_create()
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

        $organization_id = Session::get('organization_id');
        $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
      
        if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
       
        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first();
        if($business_professionalism_id->business_professionalism_id == 4)
        {
            $type_id = VehicleType::where('name', $business_professionalism_name->name)->first()->id;
        }
      
        }

        $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');
        
        $vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');
        $vehicle_model_id->prepend('Select Vehicle Model', '');

         $vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');
         $vehicle_variant_id->prepend('Select Vehicle Variant', '');

        $vehicle_category = VehicleCategory::orderBy('name')->pluck('name', 'id');
        $vehicle_category->prepend('Select Vehicle Category', '');

        $config_name = VehicleConfiguration::where('vehicle_configurations.organization_id', $organization_id)->orderBy('id')->pluck('vehicle_name', 'id');
        $config_name->prepend('Select Vehicle Configuration', '');
        
        if($business_professionalism_id->business_professionalism_id == 4)
        {
           
            $vehicle_config=VehicleVariant::where('type_id',$type_id)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
           
           
        }
        else
        {
            $vehicle_config=VehicleVariant::where('type_id','!=',2)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
           
        }

       

        $person_id = Auth::user()->person_id;

        $employee = HrmEmployee::select('hrm_employees.id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->where('hrm_employees.person_id', $person_id)
        ->first();

        $selected_employee = ($employee != null) ? $employee->id : null;

        $business_id = Organization::find($organization_id)->business_id;

       

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')
        ->where('user_type', 1)
        ->where('organization_id', Session::get('organization_id'));

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')
        ->where('user_type', 0)
        ->where('organization_id', Session::get('organization_id'));

        $busi=People::select(DB::raw('(CASE WHEN person_id is NULL THEN business_id ELSE person_id END) AS id'), DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'),'user_type')
               ->where('organization_id', $organization_id)
               ->where(function($query)
               {
                       $query->where('user_type', 0)
                       ->orWhere('user_type', 1);
               })
               ->orderByRaw('name');
        $bus = $busi->get();

        



       /* $people = $people_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');

        $business = $business_list->pluck('name', 'id');
        $business->prepend('Select Business', '');*/


        $customer_type_label = 'Customer Type';
        $customer_label = 'Customer';
        $person_type = "customer";
        $people = $people_list->pluck('name', 'id');
        $business = $business_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');
        $business->prepend('Select Business', '');


        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::select('name', 'id')->where('country_id', $country_id)->orderBy('name')->orderby('name')->get();
       

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $permit_type = VehiclePermit::orderby('name')->pluck('name','id');
        //dd($permit_type);
        $permit_type->prepend('Select Permit Type','');
       

        //$person_type = "customer";
        $specifications = VehicleSpecification::select('vehicle_types.name','vehicle_spec_masters.display_name AS spec_name','vehicle_spec_masters.id as spec_id',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name)as value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id)as value_id'),'vehicle_spec_masters.list','vehicle_specification_details.name as display_name','vehicle_specification_details.id as display_id')
        ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specifications.vehicle_type_id')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','vehicle_specifications.vehicle_spec_id')
        ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_specifications.vehicle_spec_id')
        ->where('vehicle_specifications.organization_id',$organization_id)
        ->where('vehicle_specifications.used',"1")
        ->groupby('vehicle_spec_masters.display_name')
        ->get();
        //dd($specifications); 

        return view('trade_wms.jc_vehicles_register_create', compact('config_name', 'vehicle_make_id', 'vehicle_model_id',  'vehicle_variant_id', 'vehicle_category','people', 'title', 'state', 'payment', 'terms','person_type','business','customer_type_label','customer_label','person_id','vehicle_config','selected_employee','permit_type','specifications','bus'));
    }
    
  public function get_register_number(Request $request)
    {
        //dd($request->all());

        $organization_id = Session::get('organization_id');
        // dd($organization_id);
        $reg_no=VehicleRegisterDetail::leftjoin('wms_vehicle_organizations','vehicle_register_details.id','=','wms_vehicle_organizations.vehicle_id')->where('registration_no',$request->registration_no)->where('wms_vehicle_organizations.organization_id',$organization_id)
         ->first();
       //dd($reg_no);
        if($reg_no == null)
        {
            //dd($reg_no);
            $vehicle_no = VehicleRegisterDetail::where('registration_no',$request->registration_no)->first();
            //dd($vehicle_no);


            if($vehicle_no != null)
            {
                 //dd($vehicle_no);
                  $name = '';

                  $check = People::where('user_type',$vehicle_no->user_type);
                  $check->where('organization_id',$organization_id);
                  $check->where(function ($query) use ($vehicle_no){
                    $query->where('people.person_id', '=', $vehicle_no->owner_id)
                          ->orWhere('people.business_id', '=',$vehicle_no->owner_id);
                      });
                  $check_people=$check->exists();

                  //dd($check_people);
                    if($check_people == false)
                    {
                        $owner_name = People::where('user_type',$vehicle_no->user_type)->where('person_id',$vehicle_no->owner_id)->OrWhere('business_id',$vehicle_no->owner_id)->first();

                    

                        if($owner_name->user_type == 0)
                        {
                            $add_customer = new People;
                            $add_customer->person_id = $owner_name->person_id;
                            $add_customer->organization_id =  $organization_id;
                            $add_customer->title_id = $owner_name->title_id;
                            $add_customer->first_name = $owner_name->first_name;
                            $add_customer->middle_name = $owner_name->middle_name;
                            $add_customer->last_name = $owner_name->last_name;
                            $add_customer->display_name = $owner_name->display_name;
                            $add_customer->gender_id = $owner_name->gender_id;
                            $add_customer->mobile_no = $owner_name->mobile_no;
                            $add_customer->email_address = $owner_name->email_address;
                            $add_customer->phone = $owner_name->phone;
                            $add_customer->pan_no = $owner_name->pan_no;
                            $add_customer->payment_mode_id = $owner_name->payment_mode_id;
                            $add_customer->term_id = $owner_name->term_id;
                            $add_customer->group_id = $owner_name->group_id;
                            $add_customer->status = $owner_name->status;
                            $add_customer->user_type = $owner_name->user_type;
                            $add_customer->save();
                            //dd($add_customer->display_name);

                          
                            if($add_customer->display_name)
                            {
                                $name = $add_customer->display_name;
                            }



                        }
                        if($owner_name->user_type == 1)
                        {
                            $add_customer = new People;
                            $add_customer->business_id = $owner_name->business_id;
                            $add_customer->company = $owner_name->company;
                            $add_customer->organization_id =  $organization_id;
                            $add_customer->title_id = $owner_name->title_id;
                            $add_customer->first_name = $owner_name->first_name;
                            $add_customer->middle_name = $owner_name->middle_name;
                            $add_customer->last_name = $owner_name->last_name;
                            $add_customer->display_name = $owner_name->display_name;
                            $add_customer->gender_id = $owner_name->gender_id;
                            $add_customer->mobile_no = $owner_name->mobile_no;
                            $add_customer->email_address = $owner_name->email_address;
                            $add_customer->phone = $owner_name->phone;
                            $add_customer->pan_no = $owner_name->gst_no;
                            $add_customer->payment_mode_id = $owner_name->payment_mode_id;
                            $add_customer->term_id = $owner_name->term_id;
                            $add_customer->group_id = $owner_name->group_id;
                            $add_customer->status = $owner_name->status;
                            $add_customer->user_type = $owner_name->user_type;
                            $add_customer->save();
                            //dd($add_customer->display_name);

                            if($add_customer->display_name)
                            {
                                $name = $add_customer->display_name;
                            }


                        }
                         if($add_customer->id)
                        {
                            $people_add = PeopleAddress::where('people_id',$owner_name->id)->first();
                            $people_address = new PeopleAddress();
                            $people_address->people_id = $add_customer->id;
                            $people_address->address = $people_add->address;
                            $people_address->city_id = $people_add->city_id;
                            $people_address->pin = $people_add->pin;
                            $people_address->landmark = $people_add->landmark;
                            $people_address->google = $people_add->google;
                            $people_address->save();
                        }
                           
                    }

                    $vehicle_details = VehicleRegisterDetail::where('registration_no',$request->registration_no)->first();
                  
                    return response()->json(['status' => '1','message' => 'vehicle added', 'data' => $vehicle_details,'name' => $name]);

            }
            else
            {
                return response()->json(['status' => '1','message' => 'No data for this vehicle', 'data' => null]);
            }
             
           
        }
        else
        {
            //echo 'true';
            return response()->json(['status' => '0','message' => 'vehicle already exits','data' =>[]]);
             

        }
    }

    public function get_vehicle_details(Request $request)
    {
        //dd($request->all());
        $vehicle_no = VehicleRegisterDetail::where('registration_no',$request->input('no'))->first();
        //dd( $vehicle_no);
        return response()->json(['message' => 'vehicle already exits', 'data' => $vehicle_no]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());

        $this->validate($request, [
            'registration_no' => 'required|alpha_num',         
            'people_id' => 'required',         
            /*'vehicle_name' => 'required',*/        
            'vehicle_category' => 'required',  
            'vehicle_config' => 'required',   
        ]);
        $organization_id = Session::get('organization_id');
        $vehicle_register=$request->vehicle_config;
        $config = VehicleVariant::where('id',$vehicle_register)->first();
        $vehicle = VehicleRegisterDetail::where('registration_no',$request->registration_no)->first();
        if($vehicle != null)
        {
        $vehicle_register =VehicleRegisterDetail::findOrFail($vehicle->id);
        $vehicle_register->registration_no = $request->input('registration_no');
        $vehicle_register->owner_id = $request->input('people_id');
        $vehicle_register->vehicle_configuration_id = $request->input('vehicle_config');
        //$vehicle_register->is_own = $request->input('is_own');        
        $vehicle_register->vehicle_category_id = $request->input('vehicle_category');
        $vehicle_register->vehicle_make_id = $config->vehicle_make_id;
        $vehicle_register->vehicle_model_id = $config->vehicle_model_id;
        $vehicle_register->vehicle_variant_id = $config->id;
        $vehicle_register->version = $config->version;
        /*$vehicle_register->vehicle_body_type_id = $request->input('vehicle_body_type');
        $vehicle_register->vehicle_rim_type_id = $request->input('vehicle_rim_type');
        $vehicle_register->vehicle_tyre_type_id = $request->input('vehicle_tyre_type');
        $vehicle_register->vehicle_tyre_size_id = $request->input('vehicle_tyre_size');
        $vehicle_register->vehicle_wheel_type_id = $request->input('vehicle_wheel_type');
        $vehicle_register->vehicle_drivetrain_id = $request->input('vehicle_drivetrain');
        $vehicle_register->fuel_type_id = $request->input('fuel_type');        
        $vehicle_register->vehicle_usage_id = $request->input('vehicle_usage');*/
        $vehicle_register->engine_no = $request->input('engine_no');
        $vehicle_register->chassis_no = $request->input('chassis_no');
        $vehicle_register->manufacturing_year = $request->input('manufacturing_year');
        $vehicle_register->permit_type =$request->input('vehicle_permit_type');

        if($request->input('fc_due') != null)
        {
            $vehicle_register->fc_due = ($request->input('fc_due') != null) ? carbon::parse($request->input('fc_due')): null;
        }
        if($request->input('permit_due') != null)
        {
            $vehicle_register->permit_due = ($request->input('permit_due') != null) ? carbon::parse($request->input('permit_due')) : null;
        }
        if($request->input('tax_due') != null)
        {
            $vehicle_register->tax_due = ($request->input('tax_due') != null) ? carbon::parse($request->input('tax_due')) : null; 
        }
        $vehicle_register->insurance = $request->input('vehicle_insurance');
        if($request->input('premium_date') != null)
        {
            $vehicle_register->premium_date = ($request->input('premium_date')) ? carbon::parse($request->input('premium_date')) : null;
        }
        $vehicle_register->bank_loan = $request->bank_loan;
        if($request->input('month_due_date') != null)
        {
            $vehicle_register->month_due_date = ($request->input('month_due_date') != null) ? carbon::parse($request->input('month_due_date')) : null;
        }
        $vehicle_register->warranty_km = $request->input('warranty_km');
        $vehicle_register->warranty_years= $request->input('warranty_years');
        $vehicle_register->driver = $request->input('driver');
        $vehicle_register->driver_mobile_no = $request->input('driver_mobile_no');
        $vehicle_register->description = $request->input('description');
        $vehicle_register->organization_id = $organization_id;
        $vehicle_register->save();

        //$wms_org = WmsVehicleOrganization::where('vehicle_id',$request->registration_no)->first();
        //dd($wms_org);
      /*  $wms_vehicle_org =new WmsVehicleOrganization;
        $wms_vehicle_org->organization_id = $organization_id;
        $wms_vehicle_org->vehicle_id = $vehicle_register->id;
        $wms_vehicle_org->created_by = Auth::user()->id;
        $wms_vehicle_org->last_modified_by = Auth::user()->id;
        $wms_vehicle_org->save();
        */
            
        }

        else
        {
             if($request->input('contact_person') != null){
                $contact_person = $request->input('contact_person');
                $contact_mobile = $request->input('contact_number');
                $contact_info = array_combine($contact_person, $contact_mobile);
                $contact = json_encode($contact_info, JSON_FORCE_OBJECT);
            }else{
                $contact = null;
            }
        $vehicle_register = new VehicleRegisterDetail;
        $vehicle_register->registration_no = $request->input('registration_no');
        $vehicle_register->owner_id = $request->input('people_id');
        $vehicle_register->user_type = $request->input('user_type');
        $vehicle_register->vehicle_configuration_id = $request->input('vehicle_config');
        //$vehicle_register->is_own = $request->input('is_own');        
        $vehicle_register->vehicle_category_id = $request->input('vehicle_category');
        $vehicle_register->vehicle_make_id = $config->vehicle_make_id;
        $vehicle_register->vehicle_model_id = $config->vehicle_model_id;
        $vehicle_register->vehicle_variant_id = $config->id;
        $vehicle_register->version = $config->version;
        /*$vehicle_register->vehicle_body_type_id = $request->input('vehicle_body_type');
        $vehicle_register->vehicle_rim_type_id = $request->input('vehicle_rim_type');
        $vehicle_register->vehicle_tyre_type_id = $request->input('vehicle_tyre_type');
        $vehicle_register->vehicle_tyre_size_id = $request->input('vehicle_tyre_size');
        $vehicle_register->vehicle_wheel_type_id = $request->input('vehicle_wheel_type');
        $vehicle_register->vehicle_drivetrain_id = $request->input('vehicle_drivetrain');
        $vehicle_register->fuel_type_id = $request->input('fuel_type');        
        $vehicle_register->vehicle_usage_id = $request->input('vehicle_usage');*/
        $vehicle_register->driver_mobile_no = $request->input('driver_mobile_no');
        $vehicle_register->engine_no = $request->input('engine_no');
        $vehicle_register->chassis_no = $request->input('chassis_no');
        $vehicle_register->manufacturing_year = $request->input('manufacturing_year');
        $vehicle_register->permit_type =$request->input('vehicle_permit_type');

        Custom::userby($vehicle_register, true);
        Custom::add_addon('records');

        if($request->input('fc_due') != null)
        {
            $vehicle_register->fc_due = ($request->input('fc_due') != null) ? carbon::parse($request->input('fc_due')): null;
        }
        if($request->input('permit_due') != null)
        {
            $vehicle_register->permit_due = ($request->input('permit_due') != null) ? carbon::parse($request->input('permit_due')) : null;
        }
        if($request->input('tax_due') != null)
        {
            $vehicle_register->tax_due = ($request->input('tax_due') != null) ? carbon::parse($request->input('tax_due')) : null; 
        }
        $vehicle_register->insurance = $request->input('vehicle_insurance');
        if($request->input('premium_date') != null)
        {
            $vehicle_register->premium_date = ($request->input('premium_date')) ? carbon::parse($request->input('premium_date')) : null;
        }
        if($request->input('month_due_date') != null)
        {
            $vehicle_register->month_due_date = ($request->input('month_due_date') != null) ? carbon::parse($request->input('month_due_date')) : null;
        }
        $vehicle_register->bank_loan = $request->input('bank_loan');
        $vehicle_register->warranty_km = $request->input('warranty_km');
        $vehicle_register->warranty_years= $request->input('warranty_years');
        $vehicle_register->driver = $request->input('driver');
        $vehicle_register->additional_contacts = $contact;
        $vehicle_register->description = $request->input('description');
        $vehicle_register->organization_id = $organization_id;
        $vehicle_register->save();  
        }
       // dd($vehicle_register);
         if($vehicle_register)
        {
            //dd($vehicle_register);

            $wms_vehicle_org =new  WmsVehicleOrganization;
            $wms_vehicle_org->organization_id = $organization_id;
            $wms_vehicle_org->vehicle_id = $vehicle_register->id;
            $wms_vehicle_org->created_by = Auth::user()->id;
            $wms_vehicle_org->last_modified_by = Auth::user()->id;
            $wms_vehicle_org->save();
        }

       
        if($request->values && $request->text_key){
            $vehicle_id = $vehicle_register->id;
            $values = $request->values;
            $value_id = $request->text_key;
            $compains = array_combine($value_id,$values);
            foreach ($compains as $key => $value) {
                   $vehicle_register_spec = new RegisteredVehicleSpec;
                   $vehicle_register_spec->registered_vehicle_id = $vehicle_id;
                   $vehicle_register_spec->registered_vehicle = $request->input('registration_no');
                   $vehicle_register_spec->spec_id = $key;
                   $vehicle_register_spec->spec_value = $value;
                   $vehicle_register_spec->organization_id = $organization_id;
                   $vehicle_register_spec->created_by = Auth::user()->id;
                   $vehicle_register_spec->save();
              }
        }
        if($request->list_key && $request->articles){
             $articles_id = $request->articles_id;
             $vehicle_id = $vehicle_register->id;
             $list_value = $request->articles;
             $list_id = $request->list_key;
             $list_compains = array_combine($list_id,$list_value);
             $arry_compain = array_combine($list_id,$articles_id);
             foreach ( $list_compains as $key => $value) {
                   $vehicle_register_spec = new RegisteredVehicleSpec;
                   $vehicle_register_spec->registered_vehicle_id = $vehicle_id;
                   $vehicle_register_spec->registered_vehicle = $request->input('registration_no');
                   $vehicle_register_spec->spec_id = $key;
                   $vehicle_register_spec->spec_value = $value;
                   $vehicle_register_spec->spec_value_id = $arry_compain[$key];
                   $vehicle_register_spec->organization_id = $organization_id;
                   $vehicle_register_spec->created_by = Auth::user()->id;
                   $vehicle_register_spec->save();
              }
             
        }
        
        


        //$owner_name = People::findorFail($vehicle_register->owner_id)->first_name;


        $owner_name = VehicleRegisterDetail::select('vehicle_register_details.id',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));

        $owner_name->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        $owner_name->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });

       $owner_name->where('vehicle_register_details.owner_id',$vehicle_register->owner_id);

        $customer =  $owner_name->first();

        //$vehicle_name = VehicleConfiguration::findorFail($vehicle_register->vehicle_configuration_id)->vehicle_name;
        $vehicle_category_name = VehicleCategory::findorFail($vehicle_register->vehicle_category_id)->name;
        $vehicle_make_name = VehicleMake::findorFail($vehicle_register->vehicle_make_id)->name;
        $vehicle_model_name = VehicleModel::findorFail($vehicle_register->vehicle_model_id)->name;
        $vehicle_variant_name = VehicleVariant::findorFail($vehicle_register->vehicle_variant_id)->name;
        $vehicle_config_name= VehicleVariant::findorfail($vehicle_register->vehicle_configuration_id)->vehicle_configuration;
        //dd($vehicle_register->vehicle_configuration_id);

        //$vehicle_body_name = VehicleBodyType::findorFail($vehicle_register->vehicle_body_type_id)->name;
        //$vehicle_rim_type_name = VehicleRimType::findorFail($vehicle_register->vehicle_rim_type_id)->name;
        //$vehicle_tyre_type_name = VehicleTyreType::findorFail($vehicle_register->vehicle_tyre_type_id)->name;
        //$vehicle_tyre_size_name = VehicleTyreSize::findorFail($vehicle_register->vehicle_tyre_size_id)->name;
        //$vehicle_wheel_name = VehicleWheel::findorFail($vehicle_register->vehicle_wheel_type_id)->name;
        //$vehicle_drivetrain_name = VehicleDrivetrain::findorFail($vehicle_register->vehicle_drivetrain_id)->name;
       // $vehicle_fuel_type_name = VehicleFuelType::findorFail($vehicle_register->fuel_type_id)->name;
        //$vvehicle_usage_name = VehicleUsage::findorFail($vehicle_register->vehicle_usage_id)->name;

        return response()->json(['status' => 1, 'message' => 'Vehicle Configuration'.config('constants.flash.added'), 
            'data' => [                
                'id' => $vehicle_register->id, 
                'registration_no' => $vehicle_register->registration_no, 
                'owner_name' => $customer->customer,
                
                'vehicle_category' => $vehicle_category_name,
                'vehicle_make' => $vehicle_make_name,
                'vehicle_model' => $vehicle_model_name,
                'vehicle_variant' => $vehicle_variant_name,
                'vehicle_config_name' =>$vehicle_config_name,
                /*'vehicle_body_type' => $vehicle_body_name,
                'vehicle_rim_type' => $vehicle_rim_type_name,
                'vehicle_tyre_type' => $vehicle_tyre_type_name,
                'vehicle_tyre_size' => $vehicle_tyre_size_name,
                'vehicle_wheel_type' => $vehicle_wheel_name,
                'vehicle_drivetrain' => $vehicle_drivetrain_name,
                'fuel_type' => $vehicle_fuel_type_name,
                'vehicle_usage' => $vvehicle_usage_name,*/
                'description' => ($vehicle_register->description != null) ? $vehicle_register->description : "", 
                /*'status' => $vehicle_register->status*/
            ]]);
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


       /* $vehicle_make_id = $this->vehicle_make_id;
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
        $business_name= '';
        $person_name= '';
        $organization_id = Session::get('organization_id');
        $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
      
        if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
       
        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first();
        if($business_professionalism_id->business_professionalism_id == 4)
        {
            $type_id = VehicleType::where('name', $business_professionalism_name->name)->first()->id;
        }
      
        }

        

       $vehicles = VehicleRegisterDetail::select('vehicle_register_details.id','vehicle_register_details.*')->leftjoin('wms_vehicle_organizations','vehicle_register_details.id','=','wms_vehicle_organizations.vehicle_id')->where('vehicle_register_details.id', $id)->where('wms_vehicle_organizations.organization_id', $organization_id)->first();

        $additional_contacts = $vehicles->additional_contacts;
        $contacts = json_decode($additional_contacts, TRUE);

         $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');
        
        $vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');
        $vehicle_model_id->prepend('Select Vehicle Model', '');

         $vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');
         $vehicle_variant_id->prepend('Select Vehicle Variant', '');

        $vehicle_category = VehicleCategory::orderBy('name')->pluck('name', 'id');
        $vehicle_category->prepend('Select Vehicle Category', '');

       
        $config_name = VehicleConfiguration::where('organization_id', $organization_id)->orderBy('id')->pluck('vehicle_name', 'id');
        $config_name->prepend('Select Vehicle Configuration', '');
        if($business_professionalism_id->business_professionalism_id == 4)
        {
           
            $vehicle_config=VehicleVariant::where('type_id',$type_id)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
           
           
        }
        else
        {
            $vehicle_config=VehicleVariant::where('type_id','!=',2)->orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
            $vehicle_config->prepend('Select Vehicle Config','');
           
        }

        $person_id = Auth::user()->person_id;
        $business_id = Organization::find($organization_id)->business_id;
     
        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));

        /*$people = $people_list->pluck('name', 'id');
        $people->prepend('Select Owner', '');*/


        $customer_type_label = 'Customer Type';
        $customer_label = 'Customer';
        $person_type = "customer";
        $people = $people_list->pluck('name', 'id');
        $business = $business_list->pluck('name', 'id');
        $people->prepend('Select Customer', '');
        $business->prepend('Select Business', '');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

        /*$vehicle_config = VehicleVariant::orderby('vehicle_configuration')->pluck('vehicle_configuration','id');
        $vehicle_config->prepend('Choose a Configuartion' ,'');*/

        $permit_type = VehiclePermit::orderby('name')->pluck('name','id');
        $permit_type->prepend('Select Permit Type','');

        /*if($vehicles->user_type == 0)
            {           
                $reference_business = Person::find($vehicles->people_id)->id;
                $reference_business_name = Person::find($vehicles->people_id)->first_name;
            } else {
                $reference_business_data = Business::find($vehicles->people_id);
                $reference_business = $reference_business_data->id;
                $reference_business_name = $reference_business_data->alias;
            }*/
         
        if($vehicles->user_type == "0")
        {
            //dd(Person::findorfail($vehicles->owner_id)->first_name);
            $person_name = Person::findorfail($vehicles->owner_id)->id;
             
        }

        if($vehicles->user_type == "1"){
            //dd(Business::findorfail($vehicles->owner_id)->alias);
            $business_name = Business::findorfail($vehicles->owner_id)->id;

        }  
            //dd(Person::findorfail($vehicles->owner_id)->first_name);
            //dd($vehicles->owner_id);
        $sp = WmsVehicleOrganization::where('vehicle_id',$id)->first();

        $specifications = RegisteredVehicleSpec::select('registered_vehicle_specs.id','registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name AS spec','registered_vehicle_specs.spec_value','registered_vehicle_specs.spec_value_id','vehicle_spec_masters.list',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name) AS value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id) AS value_id'))
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')
        ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_spec_masters.id')
        ->where('registered_vehicle_specs.registered_vehicle_id',$id)
        ->groupby('vehicle_spec_masters.display_name')
        ->get();

       //dd($specifications);


        return view('trade_wms.vehicles_register_edit', compact('vehicles','config_name', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_variant_id', 'vehicle_category','people', 'title', 'state', 'payment', 'terms','vehicle_config','business','customer_type_label','customer_label','person_type','permit_type','person_name','business_name','specifications','contacts'));
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
         //dd('test');
         //dd($request->all());

         $this->validate($request, [
            'registration_no' => 'required',         
            'people_id' => 'required',         
            /*'vehicle_name' => 'required',*/        
            'vehicle_category' => 'required',  
            'vehicle_configuration_id' => 'required',      
            ]);
         //dd($request->input('id'));
        $organization_id = Session::get('organization_id');

         $vehicle_configuration = $request->vehicle_configuration_id;

         if($request->input('contact_person') != null){
            $additional_contact = $request->input('contact_person');
            $additional_mobile_no = $request->input('contact_number');
            $contact_details = array_combine($additional_contact, $additional_mobile_no);
            $contact = json_encode($contact_details, JSON_FORCE_OBJECT);
        }else{
            $contact = null;
        }

         $config = VehicleVariant::where('id',$vehicle_configuration)->first();
         //dd($config);

        $vehicle_register =VehicleRegisterDetail::findOrFail($request->input('id'));
        $vehicle_register->registration_no = $request->input('registration_no');
        $vehicle_register->owner_id = $request->input('people_id');
        $vehicle_register->user_type = $request->input('user_type');
        
        $vehicle_register->vehicle_configuration_id = $request->input('vehicle_configuration_id');
        //$vehicle_register->is_own = $request->input('is_own');        
        $vehicle_register->vehicle_category_id = $request->input('vehicle_category');
        $vehicle_register->vehicle_make_id = $config->vehicle_make_id;
        $vehicle_register->vehicle_model_id = $config->vehicle_model_id;
        $vehicle_register->vehicle_variant_id = $config->id;
        $vehicle_register->version = $config->version;
        /*$vehicle_register->vehicle_body_type_id = $request->input('vehicle_body_type');
        $vehicle_register->vehicle_rim_type_id = $request->input('vehicle_rim_type');
        $vehicle_register->vehicle_tyre_type_id = $request->input('vehicle_tyre_type');
        $vehicle_register->vehicle_tyre_size_id = $request->input('vehicle_tyre_size');
        $vehicle_register->vehicle_wheel_type_id = $request->input('vehicle_wheel_type');
        $vehicle_register->vehicle_drivetrain_id = $request->input('vehicle_drivetrain');
        $vehicle_register->fuel_type_id = $request->input('fuel_type');        
        $vehicle_register->vehicle_usage_id = $request->input('vehicle_usage');*/
        $vehicle_register->engine_no = $request->input('engine_no');
        $vehicle_register->chassis_no = $request->input('chassis_no');
        $vehicle_register->manufacturing_year = $request->input('manufacturing_year');
        $vehicle_register->permit_type =$request->input('vehicle_permit_type');

        if($request->input('fc_due') != null)
        {
            $vehicle_register->fc_due = ($request->input('fc_due') != null) ? carbon::parse($request->input('fc_due')): null;
        }
        if($request->input('permit_due') != null)
        {
            $vehicle_register->permit_due = ($request->input('permit_due') != null) ? carbon::parse($request->input('permit_due')) : null;
        }
        if($request->input('tax_due') != null)
        {
            $vehicle_register->tax_due = ($request->input('tax_due') != null) ? carbon::parse($request->input('tax_due')) : null; 
        }
        $vehicle_register->insurance = $request->input('vehicle_insurance');
        if($request->input('premium_date') != null)
        {
            $vehicle_register->premium_date = ($request->input('premium_date')) ? carbon::parse($request->input('premium_date')) : null;
        }
        $vehicle_register->bank_loan = $request->bank_loan;
        if($request->input('month_due_date') != null)
        {
            $vehicle_register->month_due_date = ($request->input('month_due_date') != null) ? carbon::parse($request->input('month_due_date')) : null;
        }
        $vehicle_register->warranty_km = $request->input('warranty_km');
        $vehicle_register->warranty_years= $request->input('warranty_years');
        $vehicle_register->driver = $request->input('driver');
        $vehicle_register->driver_mobile_no = $request->input('driver_mobile_no');
        $vehicle_register->additional_contacts = $contact;
        $vehicle_register->description = $request->input('description');
        $vehicle_register->organization_id = $organization_id;
        $vehicle_register->save();       

        Custom::userby($vehicle_register, true);
        Custom::add_addon('records');
    if($request->text){
        $registered_id = $request->text_registered_id;
        $vehicle_registration_id = $request->id;
        $vehicle_registration_no = $request->registration_no;
        $spec_id = $request->text_spec_id;
        $text = $request->text;
        $text_combine = array_combine($spec_id,$text);
        $reg_combine = array_combine($spec_id,$registered_id);
         foreach ($text_combine as $key => $value) {
          RegisteredVehicleSpec::updateOrCreate(
          [  
             'id' => $reg_combine[$key],
             'registered_vehicle_id' =>  $vehicle_registration_id,
             'registered_vehicle' => $vehicle_registration_no,
             'spec_id' => $key,
          ],[
             'registered_vehicle_id' => $vehicle_registration_id,
             'registered_vehicle' => $vehicle_registration_no,
             'spec_id' => $key,
             'spec_value' =>  $value,
             'organization_id' => $organization_id,
             'created_by' => Auth::user()->id
          ]);
}
    }


    if($request->values){
        $registered_id = $request->list_registered_id;
        $spec_id = $request->list_spec_id;
        $values = $request->values;
        $value_id = $request->value_id;
        $vehicle_registration_id = $request->id;
        $vehicle_registration_no = $request->registration_no;
        $value_combine = array_combine($spec_id,$values);
        $value_id_combaine = array_combine($spec_id,$value_id);
        $reg_id = array_combine($spec_id,$registered_id);
        foreach ($value_combine as $key => $value) {
         RegisteredVehicleSpec::updateOrCreate(
          [  
             'id' =>$reg_id[$key],
             'registered_vehicle_id' => $vehicle_registration_id,
             'registered_vehicle' =>$vehicle_registration_no,
             'spec_id' =>$key
          ],[
             'registered_vehicle_id' =>$vehicle_registration_id,
             'registered_vehicle' =>$vehicle_registration_no,
             'spec_id' =>$key,
             'spec_value' => $value,
             'spec_value_id' =>$value_id_combaine[$key],
             'organization_id' =>$organization_id,
             'created_by' =>Auth::user()->id
          ]);
}

    }

   /* */


        /*$owner_name = Person::findorFail($vehicle_register->owner_id)->first_name;*/

         $owner_name = VehicleRegisterDetail::select('vehicle_register_details.id',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));

        $owner_name->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        $owner_name->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });

       $owner_name->where('vehicle_register_details.owner_id',$vehicle_register->owner_id);

        $customer =  $owner_name->first();

        //$vehicle_name = VehicleConfiguration::findorFail($vehicle_register->vehicle_configuration_id)->vehicle_name;
        $vehicle_category_name = VehicleCategory::findorFail($vehicle_register->vehicle_category_id)->name;
        $vehicle_make_name = VehicleMake::findorFail($vehicle_register->vehicle_make_id)->name;
        $vehicle_model_name = VehicleModel::findorFail($vehicle_register->vehicle_model_id)->name;
        $vehicle_variant_name = VehicleVariant::findorFail($vehicle_register->vehicle_variant_id)->name;
        $vehicle_config_name =VehicleVariant::findorfail($vehicle_register->vehicle_configuration_id)->vehicle_configuration;
        //$vehicle_body_name = VehicleBodyType::findorFail($vehicle_register->vehicle_body_type_id)->name;
        //$vehicle_rim_type_name = VehicleRimType::findorFail($vehicle_register->vehicle_rim_type_id)->name;
        //$vehicle_tyre_type_name = VehicleTyreType::findorFail($vehicle_register->vehicle_tyre_type_id)->name;
        //$vehicle_tyre_size_name = VehicleTyreSize::findorFail($vehicle_register->vehicle_tyre_size_id)->name;
        //$vehicle_wheel_name = VehicleWheel::findorFail($vehicle_register->vehicle_wheel_type_id)->name;
        //$vehicle_drivetrain_name = VehicleDrivetrain::findorFail($vehicle_register->vehicle_drivetrain_id)->name;
       // $vehicle_fuel_type_name = VehicleFuelType::findorFail($vehicle_register->fuel_type_id)->name;
        //$vvehicle_usage_name = VehicleUsage::findorFail($vehicle_register->vehicle_usage_id)->name;

        return response()->json(['status' => 1, 'message' => 'Vehicle Configuration'.config('constants.flash.added'), 
            'data' => [                
                'id' => $vehicle_register->id, 
                'registration_no' => $vehicle_register->registration_no, 
                'owner_name' => $customer->customer,
                /*'is_own' => ($vehicle_register->is_own == '1') ? "Own" : "Loan",*/
                /*'name' => $vehicle_name,*/ 
                'vehicle_category' => $vehicle_category_name,
                'vehicle_make' => $vehicle_make_name,
                'vehicle_model' => $vehicle_model_name,
                'vehicle_variant' => $vehicle_variant_name,
                'vehicle_config_name' => $vehicle_config_name,
                /*'vehicle_body_type' => $vehicle_body_name,
                'vehicle_rim_type' => $vehicle_rim_type_name,
                'vehicle_tyre_type' => $vehicle_tyre_type_name,
                'vehicle_tyre_size' => $vehicle_tyre_size_name,
                'vehicle_wheel_type' => $vehicle_wheel_name,
                'vehicle_drivetrain' => $vehicle_drivetrain_name,
                'fuel_type' => $vehicle_fuel_type_name,
                'vehicle_usage' => $vvehicle_usage_name,*/
                'description' => ($vehicle_register->description != null) ? $vehicle_register->description : "", 
                /*'status' => $vehicle_register->status*/
            ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //dd($request->all());
       /* $vehicle_resister = VehicleRegisterDetail::findOrFail($request->input('id'));
        $vehicle_resister->delete();*/
        $organization_id = session::get('organization_id');
        //dd($request->input('id'));
        $vehicle_resister = WmsVehicleOrganization::where('vehicle_id',$request->input('id'))->where('organization_id',$organization_id)->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Details'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function multidestroy(Request $request)
    {

        $vehicles = explode(',', $request->id);
        //dd($vehicles);
        $vehicle_list = [];

        foreach ($vehicles as $vehicle_id) { 

            $vehicle_delete = VehicleRegisterDetail::findOrFail($vehicle_id);   
            $vehicle_delete->delete();
            $vehicle_list[] = $vehicle_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_list]]);
    }



    public function get_vehicle_all_data(Request $request) {
        //dd($request->all());     
        $vehicle_details = VehicleConfiguration::where('id', $request->id)->first();        
        
        //dd($vehicle_details);
        if($vehicle_details != null) {
            return response()->json(['status' => 1, 'message' => 'Vehicle Datas Retreived Successfully.', 'data' => [
                'id' => $vehicle_details->id, 
                'name' => $vehicle_details->vehicle_name, 
                'vehicle_category_id' => $vehicle_details->vehicle_category_id, 
                'vehicle_make_id' => $vehicle_details->vehicle_make_id, 
                'vehicle_model_id' => $vehicle_details->vehicle_model_id, 
                'vehicle_variant_id' => $vehicle_details->vehicle_variant_id, 
                'vehicle_body_type_id' => $vehicle_details->vehicle_body_type_id, 
                'vehicle_rim_type_id' => $vehicle_details->vehicle_rim_type_id, 
                'vehicle_tyre_type_id' => $vehicle_details->vehicle_tyre_type_id, 
                'vehicle_tyre_size_id' => $vehicle_details->vehicle_tyre_size_id, 
                'vehicle_wheel_type_id' => $vehicle_details->vehicle_wheel_type_id, 
                'vehicle_drivetrain_id' => $vehicle_details->vehicle_drivetrain_id,
                'fuel_type_id' => $vehicle_details->fuel_type_id
            ]]);
        } else {
            return response()->json(['status' => 0, 'message' => 'No Vehicle Datas Available.', 'data' => []]);
        }

    }

    public function get_vehicle_model_name(Request $request) {
    //dd($request->all());
        $this->validate($request, [ 'id'  => 'required']);

        $model = VehicleModel::select('id', 'name')->where('vehicle_make_id', $request->input('id'))->orderBy('name')->get();

        return response()->json(array('result' => $model));
    }

    public function get_vehicle_variant_name(Request $request) {

        $this->validate($request, [ 'id'  => 'required']);

        $variant = VehicleVariant::select('id', 'name')->where('vehicle_model_id', $request->input('id'))->orderBy('name')->get();

        return response()->json(array('result' => $variant));
    }


    public function get_vehicle_category(Request $request)
    {
        // /dd($request->all());

        $configuration = VehicleVariant::select('vehicle_variants.category_id','vehicle_categories.display_name')->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_variants.category_id')->where('vehicle_variants.id',$request->Configuration)->first();
       return response()->json($configuration);
    }
     public function get_people_name(Request $request)
    {
       //dd($request->all());
        if($request->people_from == "business_id"){
             $people_details = People::select('display_name','mobile_no')->where('business_id',$request->people_id);
        }else if($request->people_from == "person_id"){
            $people_details = People::select('display_name','mobile_no')->where('person_id',$request->people_id);
        }

         $customer_info = $people_details->first();

      /* $people_details = People::select('people.display_name','people.mobile_no');
       $people_details->where('people.person_id',$request->people_id);
       $people_details->orwhere('people.business_id',$request->people_id);
       $customer_info = $people_details->first();
*/
     
      
       return response()->json(array('data' => $customer_info));
    }

     public function upload_vehicle_image(Request $request) {

       // dd($request->all());
        $inputs=$request->all();
        $organization_id= session::get('organization_id');
        $id = $request->input('id');
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        $path_name = '/Vehicle_images/vehicle_id-'.$id;
        $name = $id.".jpg";  
        $img=Custom::image_resize($file,800,$name,$path_name);
        $vehicle_register = VehicleRegisterDetail::findOrFail($id);
        $vehicle_register->vehicle_image = $file_name;
        $vehicle_register->save();

       return response()->json(['message'=>"Image Uploaded."]);
       
    }
    
     public function discount_popup_create($sub_total,$tax_amount)
    {
    
        $organization_id = Session::get('organization_id');
        $total_with_tax = $sub_total + $tax_amount;

        return view('trade_wms.discount_popup',compact('transactions_items_data','total_with_tax'));
    }
    public function multiapprove(Request $request){
        
        $vehicle_ids = explode(',', $request->id);
       
        $vehicle_id_list = [];

        foreach ($vehicle_ids as $vehicle_id) {
            VehicleRegisterDetail::where('id', $vehicle_id)->update(['status' => $request->input('status')]);;
            $vehicle_id_list[] = $vehicle_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicles'.config('constants.flash.updated'),'data'=>['list' => $vehicle_id_list]]);
    }
    

}
