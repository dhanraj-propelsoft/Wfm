<?php



namespace App\Http\Controllers\Personal;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Organization;

use App\VehicleRegisterDetail;

use App\VehicleCategory;

use App\VehicleVariant;

use Carbon\Carbon;

use App\Custom;

use App\User;

use App\VehicleConfiguration;

use App\VehicleDrivetrain;

use App\VehicleTyreSize;

use App\VehicleTyreType;

use App\VehicleBodyType;

use App\ VehicleSpecification;

use App\VehicleFuelType;

use App\VehicleRimType;

use App\PaymentMethod;

use App\VehicleWheel;

use App\VehicleModel;

use App\VehicleUsage;

use App\VehicleMake;

use App\ServiceType;

use App\PeopleTitle;

use App\HrmEmployee;

use App\VehiclePermit;

use App\Country;

use App\Person;

use App\People;

use App\Business;

use App\PaymentMode;

use App\Customer;

use Validator;

use App\State;

use App\Term;

use App\RegisteredVehicleSpec;

use Session;

use Auth;

use DB;



class vehicleRegistrationController extends Controller

{

	

	public function index()

	{

		

		 Auth::user()->id;



		 $person_id = Auth::user()->person_id;



		

		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



		 $organization_id = $organizations->organization_id;



		 $business = DB::table('organizations')->where('id',$organization_id)->first();       

		

		 $business_id = $business->business_id ;

	  



		 $vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id', 'vehicle_register_details.registration_no', 'vehicle_register_details.is_own', 'vehicle_register_details.description', 'vehicle_register_details.status', 'vehicle_configurations.vehicle_name', 'vehicle_categories.name AS category_name','vehicle_variants.vehicle_configuration','people.first_name as customer');

	  

		



		 $vehicles_register->leftJoin('people', function($join) use($organization_id)

			{

				$join->on('people.person_id','=', 'vehicle_register_details.owner_id')

				->where('people.organization_id', $organization_id)

				->where('vehicle_register_details.user_type', '0');

			});



		

	     $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');



		



		 $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');



		

		 $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

	  


		 $vehicles_register->where('vehicle_register_details.owner_id',$business_id);

          $vehicles_register->where ('people.first_name','!=' ,NULL);



		  $vehicles_register->orderby('vehicle_register_details.id');

		   $vehicles_register->groupby('vehicle_register_details.registration_no');

		  $vehicles_registers = $vehicles_register->get();



		return view('personal.Register_Vehicle',compact('vehicles_registers'));

	}



	public function vms_activestatus(Request $request){

	   ///dd($request->all());

		if($request->input('status')==="1")

		{

			$UpdateData=['status' => $request->input('status')];

		}else{

			$UpdateData=['status' => $request->input('status')];

		}

		VehicleRegisterDetail::where('id', $request->input('id'))->update($UpdateData);

		return response()->json(array('result' => "success",'status'=>$UpdateData));

	}





public function register_vehicle_create()

	{

	   

	   $person_id = Auth::user()->person_id;



		

		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



		 $organization_id = $organizations->organization_id;

		

		$vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');

		$vehicle_make_id->prepend('Select Vehicle Make', '');

		

		$vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');

		$vehicle_model_id->prepend('Select Vehicle Model', '');

   

		$vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');

		$vehicle_variant_id->prepend('Select Vehicle Variant', '');



		$vehicle_category = VehicleCategory::orderBy('name')->pluck('name', 'id');

		$vehicle_category->prepend('Select Vehicle Category', '');



		$config_name = VehicleConfiguration::pluck('vehicle_name', 'id')

		->where('status','1');

		 

		$config_name->prepend('Select Vehicle Configuration', '');



		$vehicle_config=VehicleVariant::orderby('vehicle_configuration')->pluck('vehicle_configuration','id');

		$vehicle_config->prepend('Select Vehicle Config','');



		$person_id = Auth::user()->person_id;

		$employee = HrmEmployee::select('hrm_employees.id')

		->where('hrm_employees.organization_id', $person_id)

		->where('hrm_employees.person_id', $person_id)

		->first();



		$selected_employee = ($employee != null) ? $employee->id : null;



		$business_id = Organization::find($person_id)->business_id;



	   



		$business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')

		->where('user_type', 1)

		->where('organization_id',  $person_id)

		->get();



		$people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')

		->where('user_type', 0)

		->where('person_id',$person_id)

		->get();

//dd($people_list );





		$customer_type_label = 'Customer Type';

		$customer_label = 'Customer';

		$person_type = "customer";

		$people = $people_list ->pluck('name', 'id');

//dd($people);

		$business = $business_list->pluck('name', 'id');

 //dd($business,$people);

	   







		$country_id = Country::where('name', 'India')->first()->id;

		$state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');

		$state->prepend('Select State', '');



		$title = PeopleTitle::pluck('display_name','id');

		$title->prepend('Title','');



		$payment = PaymentMode::where('status', '1')->pluck('display_name','id');

		$payment->prepend('Select Payment Method','');



		$voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $person_id)->get();



		$terms = Term::select('id', 'display_name')->where('organization_id', $person_id)->pluck('display_name', 'id');

		$terms->prepend('Select Term','');



		$permit_type = VehiclePermit::orderby('name')->pluck('name','id');

		$permit_type->prepend('Select Permit Type','');

	   



		//$person_type = "customer";

		

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

		





		return view('personal.Register_Vehicle_Create', compact('config_name', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant_id', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain', 'service_type', 'vehicle_usage', 'people', 'title', 'state', 'payment', 'terms','person_type','business','customer_type_label','customer_label','person_id','vehicle_config','selected_employee','permit_type','specifications'));

	

	}

	public function check_registernumber(Request $request) {

//dd($request->all());     

		$registration_no = VehicleRegisterDetail::where('registration_no', $request->registration_no)

		->where('registration_no',$request->registration_no)

		->first();

		if(!empty($registration_no->id)) {

			echo 'false';

		} else {

			echo 'true';

		}

	}

	public function store(Request $request)



    {

    	//dd($request->all());

    	 $person_id = Auth::user()->person_id;



       $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



		 $organization_id = $organizations->organization_id;

       

        $vehicle_register=$request->vehicle_config;

   

        $config = VehicleVariant::where('id',$vehicle_register)->first();

   

        



        $vehicle_register = new VehicleRegisterDetail;

        $vehicle_register->registration_no = $request->input('registration_no');

        $vehicle_register->owner_id = $person_id;

        $vehicle_register->user_type = $request->input('user_type');

        $vehicle_register->vehicle_configuration_id = $request->input('vehicle_config');

         

        $vehicle_register->vehicle_category_id = $request->input('vehicle_category');

        $vehicle_register->vehicle_make_id = $config->vehicle_make_id;

        $vehicle_register->vehicle_model_id = $config->vehicle_model_id;

        $vehicle_register->vehicle_variant_id = $config->id;

        $vehicle_register->version = $config->version;

        

        $vehicle_register->driver_mobile_no = $request->input('driver_mobile_no');

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

        if($request->input('month_due_date') != null)

        {

            $vehicle_register->month_due_date = ($request->input('month_due_date') != null) ? carbon::parse($request->input('month_due_date')) : null;

        }

        $vehicle_register->bank_loan = $request->input('bank_loan');

        $vehicle_register->warranty_km = $request->input('warranty_km');

        $vehicle_register->warranty_years= $request->input('warranty_years');

        $vehicle_register->driver = $request->input('driver');

        $vehicle_register->description = $request->input('description');

        $vehicle_register->organization_id = $organization_id;

        

        $vehicle_register->save();  

        if($request->values && $request->list_key && $request->text_key && $request->articles){

              $vehicle_id = $vehicle_register->id;

              $spec_list_values = $request->articles;

              $spec_text_values = $request->values;

              $spec_list_id = $request->list_key;

              $spec_text_id = $request->text_key;



             $compaind_id = array_merge($spec_list_id,$spec_text_id);

             $compaind_array = array_merge($spec_list_values,$spec_text_values);

      

              $array_with_key_value = array_combine($compaind_id,$compaind_array);

            

             

              foreach ($array_with_key_value as $key => $value) {

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

        Custom::userby($vehicle_register, true);

        				Custom::add_addonpersonal('records');



       



		$owner_name = VehicleRegisterDetail::select('vehicle_register_details.id',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"));



		$owner_name->leftJoin('people', function($join) 

			{

				$join->on('people.person_id','=', 'vehicle_register_details.owner_id') 



			 

				->where('vehicle_register_details.user_type', '0');

			});



		$owner_name->leftJoin('people AS business', function($join) 

			{

				$join->on('business.business_id','=', 'vehicle_register_details.owner_id')

			 

				->where('vehicle_register_details.user_type', '1');

		});



	   $owner_name->where('vehicle_register_details.owner_id',$vehicle_register->owner_id);



		$customer =  $owner_name->first();



		

		$vehicle_category_name = VehicleCategory::findorFail($vehicle_register->vehicle_category_id)->name;

		$vehicle_make_name = VehicleMake::findorFail($vehicle_register->vehicle_make_id)->name;

		$vehicle_model_name = VehicleModel::findorFail($vehicle_register->vehicle_model_id)->name;

		$vehicle_variant_name = VehicleVariant::findorFail($vehicle_register->vehicle_variant_id)->name;

		$vehicle_config_name= VehicleVariant::findorfail($vehicle_register->vehicle_configuration_id)->vehicle_configuration;

		

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

				

				'description' => ($vehicle_register->description != null) ? $vehicle_register->description : "", 

				

			]]);

	}



	 public function edit($id)

	{

			//dd($id);

		$business_name= '';

		$person_name= ''; 

		$person_id = Auth::user()->id;

		$organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



		$organization_id = $organizations->organization_id;

		//dd($organization_id);

		$vehicles = VehicleRegisterDetail::where('id', $id)->where('organization_id', $person_id)->first();

		//dd($vehicles);

		$vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');

		$vehicle_make_id->prepend('Select Vehicle Make', '');

		

		$vehicle_model_id = VehicleModel::orderBy('name')->pluck('name', 'id');

		$vehicle_model_id->prepend('Select Vehicle Model', '');



		 $vehicle_variant_id = VehicleVariant::orderBy('name')->pluck('name', 'id');

		 $vehicle_variant_id->prepend('Select Vehicle Variant', '');



		$vehicle_category = VehicleCategory::orderBy('name')->pluck('name', 'id');

		$vehicle_category->prepend('Select Vehicle Category', '');



	   

		$config_name = VehicleConfiguration::where('organization_id', $person_id)->orderBy('id')->pluck('vehicle_name', 'id');

		$config_name->prepend('Select Vehicle Configuration', '');



		$person_id = Auth::user()->person_id;

		$business_id = Organization::find($person_id)->business_id;

	 

		$business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', $person_id);



		$people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('person_id', $person_id);

		





		$customer_type_label = 'Customer Type';

		$customer_label = 'Customer';

		$person_type = "customer";

		$people = $people_list->pluck('name', 'id');

		$business = $business_list->pluck('name', 'id');

		



		$country_id = Country::where('name', 'India')->first()->id;

		$state = State::where('country_id', $country_id)->orderBy('name')->orderby('name')->pluck('name', 'id');

		$state->prepend('Select State', '');



		$title = PeopleTitle::pluck('display_name','id');

		$title->prepend('Title','');



		$payment = PaymentMethod::where('organization_id', $person_id)->pluck('display_name','id');

		$payment->prepend('Select Payment Method','');



		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');

		$terms->prepend('Select Terms','');



		$vehicle_config = VehicleVariant::orderby('vehicle_configuration')->pluck('vehicle_configuration','id');

		//dd($vehicle_config);

		$vehicle_config->prepend('Choose a Configuartion' ,'');



		$permit_type = VehiclePermit::orderby('name')->pluck('name','id');

		$permit_type->prepend('Select Permit Type','');



		

		 

		if($vehicles->user_type == "0")

		{

	 

			$person_name = Person::findorfail($vehicles->owner_id)->id;

			 

		}



		 $specifications = RegisteredVehicleSpec::select('registered_vehicle_specs.spec_id','vehicle_spec_masters.display_name as spec_name','registered_vehicle_specs.spec_value',DB::raw('GROUP_CONCAT(vehicle_specification_details.display_name) AS value'),DB::raw('GROUP_CONCAT(vehicle_specification_details.id) AS value_id'),'vehicle_spec_masters.list')

                         ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','registered_vehicle_specs.spec_id')

                         ->leftjoin('vehicle_specifications','vehicle_specifications.vehicle_spec_id','=','vehicle_spec_masters.id')

                         ->leftjoin('vehicle_specification_details','vehicle_specification_details.vehicle_specifications_id','=','vehicle_specifications.vehicle_spec_id')

                         ->where('registered_vehicle_specs.organization_id',$organization_id)

                         ->where('registered_vehicle_specs.registered_vehicle_id',$id)

                         ->groupby('vehicle_spec_masters.display_name')->get();

                    

		//dd($specifications);

		//dd($vehicles);

		return view('personal.Register_Vehicle_Edit', compact('vehicles','config_name', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_variant_id', 'vehicle_category','people', 'title', 'state', 'payment', 'terms','vehicle_config','business','customer_type_label','customer_label','person_type','permit_type','person_name','business_name','specifications'));

		}



		public function update(Request $request)

			{

				 //dd('test');

			//dd($request->all());



		 		$this->validate($request, ['registration_no' => 'required',

		 			'vehicle_category' => 'required',

		 			'vehicle_configuration_id' => 'required',      

					]);

				//dd($request->input('id'));

				 $person_id = Auth::user()->person_id;



				

				 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



				 $organization_id = $organizations->organization_id;

				

				 $vehicle_configuration = $request->vehicle_configuration_id;



				 $config = VehicleVariant::where('id',$vehicle_configuration)->first();



				$vehicle_register =VehicleRegisterDetail::findOrFail($request->input('id'));

				$vehicle_register->registration_no = $request->input('registration_no');

			   

				$vehicle_register->vehicle_configuration_id = $request->input('vehicle_configuration_id');

				//$vehicle_register->is_own = $request->input('is_own');        

				$vehicle_register->vehicle_category_id = $request->input('vehicle_category');

				$vehicle_register->vehicle_make_id = $config->vehicle_make_id;

				$vehicle_register->vehicle_model_id = $config->vehicle_model_id;

				$vehicle_register->vehicle_variant_id = $config->id;

				$vehicle_register->version = $config->version;

			   

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



				if($request->values && $request->list_key && $request->text_key && $request->articles){

                 $vehicle_id = $vehicle_register->id;

         // dd($vehicle_id);

              $spec_list_values = $request->articles;

              $spec_text_values = $request->values;

              $spec_list_id = $request->list_key;

              $spec_text_id = $request->text_key;

             $compaind_id = array_merge($spec_list_id,$spec_text_id);

             $compaind_array = array_merge($spec_list_values,$spec_text_values);

      // dd($compaind_array);

              $array_with_key_value = array_combine($compaind_id,$compaind_array);

                     //dd($array_with_key_value);

             // $key_array = array_keys($array_with_key_value);

              //$value_array = array_values($array_with_key_value);

             

              foreach ($array_with_key_value as $key => $value) {

             

                  //  $vehicle_register_spec = RegisteredVehicleSpec::where('registered_vehicle_id', $vehicle_id)->firstOrFail();

                  //  //dd($vehicle_register_spec);

                  // $vehicle_register_spec->registered_vehicle_id = $vehicle_id;

                  // $vehicle_register_spec->registered_vehicle = $request->input('registration_no');

                  // $vehicle_register_spec->spec_id = $key;

                  // $vehicle_register_spec->spec_value = $value;

                  // $vehicle_register_spec->organization_id = $organization_id;

                  // $vehicle_register_spec->created_by = Auth::user()->id;

                  // $vehicle_register_spec->save();



                    RegisteredVehicleSpec::updateOrCreate(

          [  

             'registered_vehicle_id' => $vehicle_id,

            

             'spec_id' => $key,

          ],[

             'registered_vehicle_id' => $vehicle_id,

             'registered_vehicle' => $request->input('registration_no'),

             'spec_id' => $key,

             'spec_value' =>  $value,

             'organization_id' => $organization_id,

             'created_by' => Auth::user()->id,

          ]);

              }

 }



				Custom::userby($vehicle_register, true);

				Custom::add_addonpersonal('records');



				   $customer = Person::findorFail($vehicle_register->owner_id)->first_name;

					$vehicle_category_name = VehicleCategory::findorFail($vehicle_register->vehicle_category_id)->name;

					$vehicle_make_name = VehicleMake::findorFail($vehicle_register->vehicle_make_id)->name;

					$vehicle_model_name = VehicleModel::findorFail($vehicle_register->vehicle_model_id)->name;

					$vehicle_variant_name = VehicleVariant::findorFail($vehicle_register->vehicle_variant_id)->name;

					$vehicle_config_name =VehicleVariant::findorfail($vehicle_register->vehicle_configuration_id)->vehicle_configuration;

					



					return response()->json(['status' => 1, 'message' => 'Vehicles'.config('constants.flash.added'), 

						'data' => [	'id' => $vehicle_register->id, 

						'registration_no' => $vehicle_register->registration_no, 

						'owner_name' => $customer,	

						'vehicle_category' => $vehicle_category_name,

						'vehicle_make' => $vehicle_make_name,

						'vehicle_model' => $vehicle_model_name,

						'vehicle_variant' => $vehicle_variant_name,

						'vehicle_config_name'=>$vehicle_config_name ,

						'description' => ($vehicle_register->description != null) ? $vehicle_register->description : "", 

					

				]]);

			}

	public function get_register_number(Request $request)

	{

	   //dd($request->all());



		 $person_id = Auth::user()->person_id;



		

		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



		 $organization_id = $organizations->organization_id;

		//dd($organization_id);

		$reg_no=VehicleRegisterDetail::where('registration_no',$request->registration_no)

		->where('organization_id',$organization_id)

		->exists();

		if($reg_no)

		{

			echo 'false';

		}

		else

		{

			echo 'true';

		}

	}

	// public function get_register_number(Request $request)

	// {

	//    //dd($request->all());



	//      $person_id = Auth::user()->person_id;



		

	//      $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();



	//      $organization_id = $organizations->organization_id;

	//     //dd($organization_id);

	//     $reg_no=VehicleRegisterDetail::where('registration_no',$request->registration_no)

	//     ->where('organization_id',$organization_id)

	//     ->exists();

	//     if($reg_no)

	//     {

	//         echo 'false';

	//     }

	//     else

	//     {

	//         echo 'true';

	//     }

	// }

	

}

