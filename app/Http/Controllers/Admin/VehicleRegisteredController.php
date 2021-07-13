<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\OrganizationPackage;
use App\ Custom;
use App\SubscriptionPlan;
use Carbon\Carbon;
use App\Gst;
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
use App\Country;
use App\Person;
use App\People;
use App\Business;
use App\PaymentMode;
use App\Customer;
use App\PeopleAddress;
use Validator;
use App\State;
use App\Term;
use Session;
use Auth;
use DB;

class VehicleRegisteredController extends Controller
{    
    public function index()
    {
        $organization_id = Session::get('organization_id');
        $module_name = Session::get('module_name');
         //dd( $module_name);
        $now = Carbon::now();
        $current_date =  $now->format('d-m-Y h:i:s');
        $add_date = date("d-m-Y h:i:s", strtotime("+1 hours"));

        //dd($current_date);

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $country_id = Country::where('name', 'India')->first()->id;
        $state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');
        $state->prepend('Select State', '');

        $payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $payment->prepend('Select Title','');

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');

        $group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',$organization_id);
        $group_name->prepend('Select Group Name','');
        if($module_name == 'super_admin'){
            $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id', 'vehicle_register_details.registration_no','organizations.name as org_name',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),'vehicle_categories.name AS category_name','vehicle_configurations.vehicle_name','vehicle_variants.vehicle_configuration',DB::raw('DATE_FORMAT(vehicle_register_details.created_at, "%d %M, %Y") AS started_date'));

           $vehicles_register->leftJoin('people', function($join) 
                {
                    $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                   
                    ->where('vehicle_register_details.user_type', '0');
                });

            $vehicles_register->leftJoin('people AS business', function($join) 
                {
                    $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                  
                    ->where('vehicle_register_details.user_type', '1');
            });
              $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');

            $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');

             $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

            $vehicles_register->leftjoin('organizations','organizations.id','=','vehicle_register_details.organization_id');
              $vehicles_register->groupby('vehicle_register_details.registration_no');


            $vehicles_register->orderby('vehicle_register_details.id');
            

            $vehicles_registers = $vehicles_register->get();
        }
       
        else{
            $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id', 'vehicle_register_details.registration_no', 'vehicle_register_details.is_own', 'vehicle_register_details.description', 'vehicle_register_details.status', 'vehicle_configurations.vehicle_name', 'vehicle_categories.name AS category_name', 'vehicle_makes.name AS make_name', 'vehicle_models.name AS model_name', 'vehicle_variants.name AS variant_name', 'vehicle_body_types.name AS body_type_name', 'vehicle_rim_types.name AS rim_type_name', 'vehicle_tyre_types.name AS tyre_type_name', 'vehicle_tyre_sizes.name AS tyre_size_name', 'vehicle_wheels.name AS wheel_name', 'vehicle_drivetrains.name AS drivetrain_name', 'vehicle_fuel_types.name AS fuel_type_name', 'vehicle_usages.name AS usage_name','vehicle_variants.vehicle_configuration',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));
      
        

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
        
        }
       
        if($module_name == 'super_admin'){
        return view('admin.vehicle_register', compact('vehicles_registers', 'title', 'state', 'payment', 'terms','group_name','module_name'));
         }
         else{

        return view('fuel_station.vehicles_register', compact('vehicles_registers', 'title', 'state', 'payment', 'terms','group_name','module_name'));

             }
    }

}

