<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleSpecificationDetails;
use App\VehicleSpecification;
use App\VehicleType;
use App\VehicleSpecMaster;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class VehicleSpecificationValueController extends Controller
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

        $spec_values = VehicleSpecificationDetails::select('vehicle_specification_details.id','vehicle_specification_details.status','vehicle_specification_details.name as value','vehicle_specification_details.description','vehicle_types.name as type_name','vehicle_spec_masters.name AS spec_name')
        ->leftjoin('vehicle_specifications','vehicle_specifications.id','=','vehicle_specification_details.vehicle_specifications_id')
        ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specification_details.vehicle_type_id')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=', 'vehicle_specification_details.vehicle_specifications_id')
        ->where('vehicle_spec_masters.list',"1")
        ->where('vehicle_spec_masters.organization_id' , $organization_id)
        ->get();
    
        return view('trade_wms.vehicle_spec_values',compact('spec_values','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id=session::get('organization_id');

        $type = VehicleType::pluck('name', 'id');
        $type->prepend('Select Type', '');

        $specification = VehicleSpecMaster::select('vehicle_spec_masters.name','vehicle_spec_masters.id')->leftjoin('vehicle_specifications','vehicle_specifications.vehicle_spec_id','=','vehicle_spec_masters.id')->where('vehicle_specifications.used',"1")->where('vehicle_spec_masters.list','1')->pluck('vehicle_spec_masters.name','vehicle_spec_masters.id');
        $specification->prepend('Select specification','');
        return view('trade_wms.vehicle_spec_valuescreate',compact('type','specification'));
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
        $organization_id=session::get('organization_id');

        $spec_values = new VehicleSpecificationDetails;
        $spec_values->vehicle_type_id =  $request->input('type');
        $spec_values->vehicle_specifications_id =  $request->input('specification');
        $spec_values->name = $request->input('value');
        $spec_values->display_name  = $request->input('value');
        $spec_values->description  = $request->input('description');
        $spec_values->organization_id  = $organization_id;
        $spec_values->save();
          
          $data = VehicleSpecificationDetails::select('vehicle_specification_details.id','vehicle_specification_details.status','vehicle_specification_details.name as value','vehicle_specification_details.description','vehicle_types.name as type_name','vehicle_spec_masters.name AS spec_name')->leftjoin('vehicle_specifications','vehicle_specifications.id','=','vehicle_specification_details.vehicle_specifications_id')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specification_details.vehicle_type_id')->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=', 'vehicle_specification_details.vehicle_specifications_id')->where('vehicle_specification_details.organization_id',$organization_id)->first();

          $spec_values = VehicleSpecificationDetails::select('vehicle_specification_details.id','vehicle_specification_details.status','vehicle_specification_details.name as value','vehicle_specification_details.description','vehicle_types.name as type_name','vehicle_spec_masters.name AS spec_name')
        ->leftjoin('vehicle_specifications','vehicle_specifications.id','=','vehicle_specification_details.vehicle_specifications_id')
        ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specification_details.vehicle_type_id')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=', 'vehicle_specification_details.vehicle_specifications_id')
        ->where('vehicle_specification_details.organization_id',$organization_id)
        ->where('vehicle_specification_details.id',$spec_values->id)
        ->where('vehicle_spec_masters.list',"1")
        ->first();
         
         return response()->json(['status' => 1, 'message' => 'specification value'.config('constants.flash.updated'), 'data' => ['id' => $spec_values->id,'type' => $spec_values->type_name,'specification' => $spec_values->spec_name,'value' => $spec_values->value ,'description' => ($spec_values->description != null) ? $spec_values->description : "",'list' => $spec_values->list]]);
        

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
       $organization_id=session::get('organization_id');

        $type = VehicleType::pluck('name', 'id');
        $type->prepend('Select Type', '');

      $spec_value = VehicleSpecificationDetails::where('id', $id)->first();
        if(!$spec_value) abort(403);

        $specification = VehicleSpecMaster::select('vehicle_spec_masters.name','vehicle_spec_masters.id')->leftjoin('vehicle_specifications','vehicle_specifications.vehicle_spec_id','=','vehicle_spec_masters.id')->where('vehicle_specifications.used',"1")->where('vehicle_spec_masters.list','1')->pluck('vehicle_spec_masters.name','vehicle_spec_masters.id');
        $specification->prepend('Select specification','');

        $selected_spec_value = VehicleSpecificationDetails::select('vehicle_spec_masters.id','vehicle_specification_details.name as value')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=','vehicle_specification_details.vehicle_specifications_id')->where('vehicle_specification_details.id', $id)->first();
       
        return view('trade_wms.vehicle_spec_valuesedit',compact('type','specification','selected_spec_value','spec_value'));
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
      
        $organization_id = Session::get('organization_id');
     
        $spec_values =VehicleSpecificationDetails::findOrFail($request->input('id'));
        $spec_values->vehicle_type_id =  $request->input('type');
        $spec_values->vehicle_specifications_id =  $request->input('specification');
        $spec_values->name = $request->input('value');
        $spec_values->display_name  = $request->input('value');
        $spec_values->description  = $request->input('description');
        $spec_values->organization_id  = $organization_id;
        $spec_values->save();


         $spec_values = VehicleSpecificationDetails::select('vehicle_specification_details.id','vehicle_specification_details.status','vehicle_specification_details.name as value','vehicle_specification_details.description','vehicle_types.name as type_name','vehicle_spec_masters.name AS spec_name')
        ->leftjoin('vehicle_specifications','vehicle_specifications.id','=','vehicle_specification_details.vehicle_specifications_id')
        ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_specification_details.vehicle_type_id')
        ->leftjoin('vehicle_spec_masters','vehicle_spec_masters.id','=', 'vehicle_specification_details.vehicle_specifications_id')
        ->where('vehicle_specification_details.organization_id',$organization_id)
        ->where('vehicle_specification_details.id',$request->id)
        ->where('vehicle_spec_masters.list',"1")
        ->first();

        return response()->json(['status' => 1, 'message' => 'specification value'.config('constants.flash.updated'), 'data' => ['id' => $spec_values->id,'type' => $spec_values->type_name,'specification' => $spec_values->spec_name,'value' => $spec_values->value ,'description' => ($spec_values->description != null) ? $spec_values->description : "",'list' => $spec_values->list]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
         $spec_values = VehicleSpecificationDetails::findOrFail($request->input('id'));
        $spec_values->delete();

        return response()->json(['status' => 1, 'message' => 'Vehicle specification'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function specification_values_status_approval(Request $request)
    {
        VehicleSpecificationDetails::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }
}
