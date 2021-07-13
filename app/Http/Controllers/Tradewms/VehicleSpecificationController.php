<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleType;
use App\VehicleSpecification;
use App\VehicleSpecMaster;
use App\WmsVehicleSpec;
use Auth;
use DB;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class VehicleSpecificationController extends Controller
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
        /* $specifications =  VehicleSpecMaster::select('vehicle_spec_masters.id','vehicle_spec_masters.name','vehicle_types.name as type','vehicle_spec_masters.description','vehicle_specifications.used','vehicle_specifications.pricing')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->leftjoin('vehicle_specifications','vehicle_specifications.vehicle_spec_id','=','vehicle_spec_masters.id')->orderby('vehicle_specifications.used','desc')->orderby('vehicle_specifications.pricing','desc')->get();*/

         $spec =  VehicleSpecMaster::select('vehicle_spec_masters.id','vehicle_spec_masters.name','vehicle_types.name as type','vehicle_types.id as type_id','vehicle_spec_masters.description','vehicle_specifications.used','vehicle_specifications.pricing','vehicle_specifications.id as spec_id');
        $spec ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id');
        $spec->leftjoin('vehicle_specifications', function($query) use ($organization_id) {
                $query->on('vehicle_specifications.vehicle_spec_id', '=', 'vehicle_spec_masters.id');
                $query->where('vehicle_specifications.organization_id', '=', $organization_id);
            });
        $spec ->orderby('vehicle_specifications.used','desc');
        $spec ->orderby('vehicle_specifications.pricing','desc');
        $specifications= $spec->get();

        
        
          $count = VehicleSpecification::select(DB::raw('count(pricing) as pricing'))->where('pricing','1')->where('organization_id',$organization_id)->first();
         return view('trade_wms.vehicle_specification',compact('specifications','count','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        /*$type = VehicleType::pluck('name', 'id');
        $type->prepend('Select Type', '');

        $specification = VehicleSpecMaster::pluck('name','id');
        $specification->prepend('Select Specification');
        return view('trade_wms.vehicle_spec_create',compact('type','specification'));*/
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
       
       $results = VehicleSpecMaster::select('vehicle_spec_masters.id AS spec_id',
   'vehicle_types.id AS type_id')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->where('vehicle_spec_masters.id',$request->id)->first();
         
          $specification=VehicleSpecification::updateOrCreate(
          [
             'id'=>$request->spec_details_id,
             'vehicle_spec_id'=>$request->id,
             'organization_id' => $organization_id,
             'vehicle_type_id' => $results->type_id
          ],[
             'used' => $request->status,
             'vehicle_spec_id'=>$request->id,
             'organization_id' => $organization_id,
             'vehicle_type_id' => $results->type_id,
             'created_by' => Auth::user()->id
          ]);


            
       return response()->json(['status' => 1, 'message' => 'specification'.config('constants.flash.added'),'data'=> $specification->used]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $specification = VehicleSpecification::findOrFail($request->input('id'));
        $specification->delete();

        return response()->json(['status' => 1, 'message' => 'Vehicle specification'.config('constants.flash.deleted'), 'data' => []]);
    }
     
     public function specification_status_approval(Request $request)
    {
        VehicleSpecification::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }
    public function pricing_update(Request $request)
    {

      //dd($request->all());

         $organization_id=session::get('organization_id');
        
         $results = VehicleSpecMaster::select('vehicle_spec_masters.id AS spec_id',
   'vehicle_types.id AS type_id')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_spec_masters.vehicle_type_id')->where('vehicle_spec_masters.id',$request->id)->first();

          $specification=VehicleSpecification::updateOrCreate(
          [
             'id'             =>$request->spec_details_id,
             'vehicle_spec_id'=>$request->id,
             'organization_id' => $organization_id,
             'vehicle_type_id' => $results->type_id
          ],[
             'pricing' => $request->status,
             'vehicle_spec_id'=>$request->id,
             'organization_id' => $organization_id,
             'vehicle_type_id' => $results->type_id,
             'created_by' => Auth::user()->id
          ]);
if(!empty($specification))
{
    $pricing_specs = VehicleSpecification::select('vehicle_spec_id')->where('organization_id',$organization_id)->where('used',1)->where('pricing',1)->get();

                  $count = count($pricing_specs);
     if($count <= 1){
         $pricing_specs = VehicleSpecification::select('vehicle_spec_id')->where('organization_id',$organization_id)->where('used',1)->where('pricing',1)->first();
         $price_count = count($pricing_specs);
        if($price_count == 1){
          $pricing_specification = WmsVehicleSpec::updateOrCreate(
          [
             'organization_id' => $organization_id
          ],[
             'pricing_spec1' =>$pricing_specs->vehicle_spec_id,
             'pricing_spec2'=>'null',
             'organization_id' => $organization_id,
             'created_by' => Auth::user()->id
          ]);
      }else if($price_count == 0){
              $pricing_specification = WmsVehicleSpec::updateOrCreate(
          [
             'organization_id' => $organization_id
          ],[
             'pricing_spec1' =>'null',
             'pricing_spec2'=>'null',
             'organization_id' => $organization_id,
             'created_by' => Auth::user()->id
          ]);
      }
     }else{
        $pricing_specs = VehicleSpecification::select('vehicle_spec_id')->where('organization_id',$organization_id)->where('used',1)->where('pricing',1)->get();
          foreach ($pricing_specs as $key => $value) {
            $pricing_specification = WmsVehicleSpec::updateOrCreate(
          [
             'organization_id' => $organization_id
          ],[
             'pricing_spec1' =>$pricing_specs[0]->vehicle_spec_id,
             'pricing_spec2'=>$pricing_specs[1]->vehicle_spec_id,
             'organization_id' => $organization_id,
             'created_by' => Auth::user()->id
          ]);
          
     }
}
}
    
    /*if($specification){
          foreach ($pricing_specs as $key => $value) {
            dd($pricing_specs);
            $pricing_specification = WmsVehicleSpec::updateOrCreate(
          [
             'organization_id' => $organization_id
          ],[
             'pricing_spec1' =>$pricing_specs[0]->vehicle_spec_id,
             'pricing_spec2'=>$pricing_specs[1]->vehicle_spec_id,
             'organization_id' => $organization_id,
             'created_by' => Auth::user()->id
          ]);
          }

          $pricing_specs = VehicleSpecification::select('vehicle_spec_id')->where('organization_id',$organization_id)->where('used',1)->where('pricing',1)->get();

    if(!empty($pricing_specs)){
            foreach ($pricing_specs as $key => $value)
             {
              $spec1 = $pricing_specs[0]->vehicle_spec_id;
              $spec2 = $pricing_specs[1]->vehicle_spec_id;
             }
                          }
    else{
             $spec1 = 'null';
             $spec2 = 'null';
    }

}*/

//dd($pricing_specification);

          return response()->json(['status' => 1, 'message' => 'specification pricing'.config('constants.flash.added'),'data'=> $specification->pricing]); 
    }

   
}
