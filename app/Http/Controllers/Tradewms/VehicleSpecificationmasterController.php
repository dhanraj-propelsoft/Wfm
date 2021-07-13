<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleType;
use App\VehicleSpecification;
use App\VehicleSpecMaster;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class VehicleSpecificationmasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
         $organization_id=session::get('organization_id');
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
         $specification_masters = VehicleSpecMaster::select('vehicle_spec_masters.id','vehicle_types.name as type','vehicle_spec_masters.name as specification','vehicle_spec_masters.status','vehicle_spec_masters.description')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->get();
         
         return view('trade_wms.vehicle_specificationmaster',compact('specification_masters','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $type = VehicleType::pluck('name', 'id');
        $type->prepend('Select Type', '');
        return view('trade_wms.vehicel_specificationmaster_create',compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization_id=session::get('organization_id');

        $specification_master = new VehicleSpecMaster;
        $specification_master->vehicle_type_id = $request->input('type'); 
        $specification_master->name = $request->input('specification'); 
        $specification_master->display_name = $request->input('specification'); 
        $specification_master->list = $request->input('list');
        $specification_master->description = $request->input('description'); 
        $specification_master->organization_id = $organization_id; 
        $specification_master->save();
        
        $data = VehicleSpecMaster::select('vehicle_spec_masters.id','vehicle_types.name as type','vehicle_spec_masters.name as specification','vehicle_spec_masters.status')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->where('vehicle_spec_masters.id',$specification_master->id)->where('vehicle_spec_masters.organization_id',$organization_id)->first();
        return response()->json(['status' => '1','message' => 'specification'.config('constants.flash.added'), 'data' => ['id' => $data->id, 'specification' => $data->specification,'type' => $data->type,'description' => ($data->description != null) ? $data->description : "",'status'=>$data->status]]);

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
        
         $organization_id = Session::get('organization_id');

       $specification = VehicleSpecMaster::where('id', $id)->first();
       
        if(!$specification) abort(403);

          $type = VehicleType::pluck('name', 'id');
        $type->prepend('Select Type', '');


        $spec = VehicleSpecMaster::select('display_name')->where('id', $id)->first();
       
     
        return view('trade_wms.vehicle_specificationmaster_edit',compact('type','specification','spec'));
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

       // dd($request->all());
       /* $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);*/
         $organization_id = Session::get('organization_id');
     
        $specification_master =VehicleSpecMaster::findOrFail($request->input('id'));
        $specification_master->vehicle_type_id = $request->input('type'); 
        $specification_master->name = $request->input('specification'); 
        $specification_master->display_name = $request->input('specification'); 
        $specification_master->list = $request->input('list');
        $specification_master->description = $request->input('description'); 
        $specification_master->organization_id = $organization_id; 
        $specification_master->save();


        $data = VehicleSpecMaster::select('vehicle_spec_masters.id','vehicle_types.name as type','vehicle_spec_masters.name as specification','vehicle_spec_masters.status','vehicle_spec_masters.description','vehicle_spec_masters.list')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->where('vehicle_spec_masters.organization_id',$organization_id)->where('vehicle_spec_masters.id',$specification_master->id)->first();

        return response()->json(['status' => 1, 'message' => 'specification'.config('constants.flash.updated'), 'data' => ['id' => $data->id, 'specification' => $data->specification, 'type' => $data->type, 'description' => ($data->description != null) ? $data->description : "",'list' => $data->list,'status' => $data->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
         $specification_master = VehicleSpecMaster::findOrFail($request->input('id'));
        $specification_master->delete();

        return response()->json(['status' => 1, 'message' => 'Vehicle specification'.config('constants.flash.deleted'), 'data' => []]);
    }
    public function specification_master_status_approval(Request $request)
    {
        VehicleSpecMaster::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }
    public function vehicle_spec_name(Request $request) {
      // dd($request->all());
        $vehicle_spec_master = VehicleSpecMaster::where('name', $request->specification)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_spec_master->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
