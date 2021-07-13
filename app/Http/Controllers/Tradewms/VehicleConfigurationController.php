<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleConfiguration;
use App\VehicleDrivetrain;
use App\VehicleTyreSize;
use App\VehicleTyreType;
use App\VehicleBodyType;
use App\VehicleCategory;
use App\VehicleFuelType;
use App\VehicleRimType;
use App\VehicleVariant;
use App\VehicleWheel;
use App\VehicleModel;
use App\VehicleUsage;
use App\VehicleMake;
use App\ServiceType;
use App\Custom;
use Validator;
use Session;


class VehicleConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $vehicle_make_id, $vehicle_model_id, $vehicle_tyre_size, $vehicle_tyre_type, $vehicle_variant, $vehicle_wheel, $fuel_type, $rim_type, $body_type, $vehicle_category, $vehicle_drivetrain, $service_type, $vehicle_usage;

    public function __construct()
    {
        $this->vehicle_make_id = VehicleMake::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_make_id->prepend('Select Vehicle Make', '');
        
        $this->vehicle_model_id = VehicleModel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_model_id->prepend('Select Vehicle Model', '');

        $this->vehicle_tyre_size = VehicleTyreSize::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_size->prepend('Select Tyre Size', '');

        $this->vehicle_tyre_type = VehicleTyreType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_tyre_type->prepend('Select Tyre Type', '');

        $this->vehicle_variant = VehicleVariant::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_variant->prepend('Select Vehicle Variant', '');

        $this->vehicle_wheel = VehicleWheel::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_wheel->prepend('Select Vehicle Wheel', '');

        $this->fuel_type = VehicleFuelType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->fuel_type->prepend('Select Fuel Type', '');

        $this->rim_type = VehicleRimType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->rim_type->prepend('Select Rim Type', '');

        $this->body_type = VehicleBodyType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->body_type->prepend('Select Body Type', '');

        $this->vehicle_category = VehicleCategory::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_category->prepend('Select Vehicle Category', '');

        $this->vehicle_drivetrain = VehicleDrivetrain::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_drivetrain->prepend('Select Drivetrain', '');

        $this->service_type = ServiceType::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->service_type->prepend('Select Service Type', '');

        $this->vehicle_usage = VehicleUsage::where('status', '1')->orderBy('name')->pluck('name', 'id');
        $this->vehicle_usage->prepend('Select Vehicle Usage', '');

    }

    public function index()
    {
        $vehicles = VehicleConfiguration::select('vehicle_configurations.id', 'vehicle_configurations.vehicle_name', 'vehicle_configurations.description', 'vehicle_configurations.status', 'vehicle_categories.name AS category_name', 'vehicle_makes.name AS make_name', 'vehicle_models.name AS model_name', 'vehicle_variants.name AS variant_name', 'vehicle_body_types.name AS body_type_name', 'vehicle_rim_types.name AS rim_type_name', 'vehicle_tyre_types.name AS tyre_type_name', 'vehicle_tyre_sizes.name AS tyre_size_name', 'vehicle_wheels.name AS wheel_name', 'vehicle_drivetrains.name AS drivetrain_name', 'vehicle_fuel_types.name AS fuel_type_name')
        ->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_configurations.vehicle_category_id')
        ->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_configurations.vehicle_make_id')
        ->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_configurations.vehicle_model_id')
        ->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_configurations.vehicle_variant_id')
        ->leftJoin('vehicle_body_types', 'vehicle_body_types.id','=','vehicle_configurations.vehicle_body_type_id')
        ->leftJoin('vehicle_rim_types', 'vehicle_rim_types.id','=','vehicle_configurations.vehicle_rim_type_id')
        ->leftJoin('vehicle_tyre_types', 'vehicle_tyre_types.id','=','vehicle_configurations.vehicle_tyre_type_id')
        ->leftJoin('vehicle_tyre_sizes', 'vehicle_tyre_sizes.id','=','vehicle_configurations.vehicle_tyre_size_id')
        ->leftJoin('vehicle_wheels', 'vehicle_wheels.id','=','vehicle_configurations.vehicle_wheel_type_id')
        ->leftJoin('vehicle_drivetrains', 'vehicle_drivetrains.id','=','vehicle_configurations.vehicle_drivetrain_id')
        ->leftJoin('vehicle_fuel_types', 'vehicle_fuel_types.id','=','vehicle_configurations.fuel_type_id')
        ->where('vehicle_configurations.status', '1')
        ->orderby('vehicle_configurations.vehicle_name')
        ->get();

        return view('trade_wms.vehicle_configuration', compact('vehicles'));
    }

    public function vehicle_name(Request $request) {
        //dd($request->all());     
        $vehicles = VehicleConfiguration::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicles->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicle_make_id = $this->vehicle_make_id;
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
        $vehicle_usage = $this->vehicle_usage;

        $get_id = VehicleConfiguration::select('id')->orderBy('id', 'desc')->first();

        $last_id = isset($get_id->id) ? ($get_id->id+1) : "1";
        $config_id = isset($get_id->id) ? 'ID# '.($get_id->id+1) : "ID# 1";
//dd($config_id);
        return view('trade_wms.vehicle_configuration_create', compact('vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant_id', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain', 'service_type', 'vehicle_usage', 'config_id', 'last_id'));
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
            'vehicle_make' => 'required',         
            'vehicle_model' => 'required',         
            'vehicle_variant' => 'required',         
        ]);

        $vehicle_config = new VehicleConfiguration;
        $vehicle_config->vehicle_name = $request->input('name');
        $vehicle_config->vehicle_category_id = $request->input('vehicle_category');
        $vehicle_config->vehicle_make_id = $request->input('vehicle_make');
        $vehicle_config->vehicle_model_id = $request->input('vehicle_model');
        $vehicle_config->vehicle_variant_id = $request->input('vehicle_variant');
        $vehicle_config->vehicle_body_type_id = $request->input('vehicle_body_type');
        $vehicle_config->vehicle_rim_type_id = $request->input('vehicle_rim_type');
        $vehicle_config->vehicle_tyre_type_id = $request->input('vehicle_tyre_type');
        $vehicle_config->vehicle_tyre_size_id = $request->input('vehicle_tyre_size');
        $vehicle_config->vehicle_wheel_type_id = $request->input('vehicle_wheel_type');
        $vehicle_config->vehicle_drivetrain_id = $request->input('vehicle_drivetrain');
        $vehicle_config->fuel_type_id = $request->input('fuel_type');
        $vehicle_config->description = $request->input('description');
        $vehicle_config->save();

        /*if($vehicle_config){
            VehicleConfiguration::where('id', $vehicle_config->id)
          ->update(['vehicle_name' => $request->input('status')]);
        }*/

        Custom::userby($vehicle_config, true);
        Custom::add_addon('records');

        $vehicle_category_name = VehicleCategory::findorFail($vehicle_config->vehicle_category_id)->name;
        $vehicle_make_name = VehicleMake::findorFail($vehicle_config->vehicle_make_id)->name;
        $vehicle_model_name = VehicleModel::findorFail($vehicle_config->vehicle_model_id)->name;
        $vehicle_variant_name = VehicleVariant::findorFail($vehicle_config->vehicle_variant_id)->name;
        $vehicle_body_name = VehicleBodyType::findorFail($vehicle_config->vehicle_body_type_id)->name;
        $vehicle_rim_type_name = VehicleRimType::findorFail($vehicle_config->vehicle_rim_type_id)->name;
        $vehicle_tyre_type_name = VehicleTyreType::findorFail($vehicle_config->vehicle_tyre_type_id)->name;
        $vehicle_tyre_size_name = VehicleTyreSize::findorFail($vehicle_config->vehicle_tyre_size_id)->name;
        $vehicle_wheel_name = VehicleWheel::findorFail($vehicle_config->vehicle_wheel_type_id)->name;
        $vehicle_drivetrain_name = VehicleDrivetrain::findorFail($vehicle_config->vehicle_drivetrain_id)->name;
        $vehicle_fuel_type_name = VehicleFuelType::findorFail($vehicle_config->fuel_type_id)->name;
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Configuration'.config('constants.flash.added'), 
            'data' => [
                'id' => $vehicle_config->id, 
                'name' => $vehicle_config->vehicle_name, 
                'vehicle_category' => $vehicle_category_name,
                'vehicle_make' => $vehicle_make_name,
                'vehicle_model' => $vehicle_model_name,
                'vehicle_variant' => $vehicle_variant_name,
                'vehicle_body_type' => $vehicle_body_name,
                'vehicle_rim_type' => $vehicle_rim_type_name,
                'vehicle_tyre_type' => $vehicle_tyre_type_name,
                'vehicle_tyre_size' => $vehicle_tyre_size_name,
                'vehicle_wheel_type' => $vehicle_wheel_name,
                'vehicle_drivetrain' => $vehicle_drivetrain_name,
                'fuel_type' => $vehicle_fuel_type_name,
                'description' => ($vehicle_config->description != null) ? $vehicle_config->description : "", 
                'status' => $vehicle_config->status
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
        $vehicle_config = VehicleConfiguration::where('id', $id)->first();
        if(!$vehicle_config) abort(403);

        $vehicle_make_id = $this->vehicle_make_id;
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
        $vehicle_usage = $this->vehicle_usage;

        return view('trade_wms.vehicle_configuration_edit', compact('vehicle_config', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_tyre_size', 'vehicle_tyre_type', 'vehicle_variant_id', 'vehicle_wheel', 'fuel_type', 'rim_type', 'body_type', 'vehicle_category', 'vehicle_drivetrain', 'service_type', 'vehicle_usage'));
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
            'vehicle_make' => 'required',         
            'vehicle_model' => 'required',         
            'vehicle_variant' => 'required',         
        ]);

        $vehicle_config = VehicleConfiguration::findOrFail($request->input('id'));
        $vehicle_config->vehicle_name = $request->input('name');
        $vehicle_config->vehicle_category_id = $request->input('vehicle_category');
        $vehicle_config->vehicle_make_id = $request->input('vehicle_make');
        $vehicle_config->vehicle_model_id = $request->input('vehicle_model');
        $vehicle_config->vehicle_variant_id = $request->input('vehicle_variant');
        $vehicle_config->vehicle_body_type_id = $request->input('vehicle_body_type');
        $vehicle_config->vehicle_rim_type_id = $request->input('vehicle_rim_type');
        $vehicle_config->vehicle_tyre_type_id = $request->input('vehicle_tyre_type');
        $vehicle_config->vehicle_tyre_size_id = $request->input('vehicle_tyre_size');
        $vehicle_config->vehicle_wheel_type_id = $request->input('vehicle_wheel_type');
        $vehicle_config->vehicle_drivetrain_id = $request->input('vehicle_drivetrain');
        $vehicle_config->fuel_type_id = $request->input('fuel_type');
        $vehicle_config->description = $request->input('description');
        $vehicle_config->save();

        Custom::userby($vehicle_config, false);

        $vehicle_category_name = VehicleCategory::findorFail($vehicle_config->vehicle_category_id)->name;
        $vehicle_make_name = VehicleMake::findorFail($vehicle_config->vehicle_make_id)->name;
        $vehicle_model_name = VehicleModel::findorFail($vehicle_config->vehicle_model_id)->name;
        $vehicle_variant_name = VehicleVariant::findorFail($vehicle_config->vehicle_variant_id)->name;
        $vehicle_body_name = VehicleBodyType::findorFail($vehicle_config->vehicle_body_type_id)->name;
        $vehicle_rim_type_name = VehicleRimType::findorFail($vehicle_config->vehicle_rim_type_id)->name;
        $vehicle_tyre_type_name = VehicleTyreType::findorFail($vehicle_config->vehicle_tyre_type_id)->name;
        $vehicle_tyre_size_name = VehicleTyreSize::findorFail($vehicle_config->vehicle_tyre_size_id)->name;
        $vehicle_wheel_name = VehicleWheel::findorFail($vehicle_config->vehicle_wheel_type_id)->name;
        $vehicle_drivetrain_name = VehicleDrivetrain::findorFail($vehicle_config->vehicle_drivetrain_id)->name;
        $vehicle_fuel_type_name = VehicleFuelType::findorFail($vehicle_config->fuel_type_id)->name;
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Configuration'.config('constants.flash.updated'), 
            'data' => [
                'id' => $vehicle_config->id, 
                'name' => $vehicle_config->vehicle_name, 
                'vehicle_category' => $vehicle_category_name,
                'vehicle_make' => $vehicle_make_name,
                'vehicle_model' => $vehicle_model_name,
                'vehicle_variant' => $vehicle_variant_name,
                'vehicle_body_type' => $vehicle_body_name,
                'vehicle_rim_type' => $vehicle_rim_type_name,
                'vehicle_tyre_type' => $vehicle_tyre_type_name,
                'vehicle_tyre_size' => $vehicle_tyre_size_name,
                'vehicle_wheel_type' => $vehicle_wheel_name,
                'vehicle_drivetrain' => $vehicle_drivetrain_name,
                'fuel_type' => $vehicle_fuel_type_name,
                'description' => ($vehicle_config->description != null) ? $vehicle_config->description : "", 
                'status' => $vehicle_config->status
            ]]);
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
}
