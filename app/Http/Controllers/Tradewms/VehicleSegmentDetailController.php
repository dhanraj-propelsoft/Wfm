<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleSegmentDetail;
use App\VehicleVariant;
use App\VehicleSegment;
use App\Custom;
use Auth;
use Validator;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class VehicleSegmentDetailController extends Controller
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

        $vehicle_variants = VehicleVariant::select('vehicle_variants.id','vehicle_variants.vehicle_model_id','vehicle_segments.name as segment_name','vehicle_variants.vehicle_make_id','vehicle_variants.name', 'vehicle_variants.status', 'vehicle_models.name AS model_name', 'vehicle_makes.name AS make_name')
        ->leftJoin('vehicle_models', 'vehicle_models.id', '=','vehicle_variants.vehicle_model_id')
        ->leftJoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')
        ->leftJoin('vehicle_segment_details','vehicle_segment_details.vehicle_variant_id','vehicle_variants.id')
        ->leftJoin('vehicle_segments', function($join) use($organization_id)
            {
                $join->on('vehicle_segment_details.Vehicle_segment_id','=','vehicle_segments.id')
                ->where('vehicle_segments.organization_id', $organization_id);
            })
        ->groupby('vehicle_variants.name')
        ->groupby('vehicle_variants.vehicle_confi')->get();   
        

        $segments=VehicleSegment::where('vehicle_segments.organization_id',$organization_id)->pluck('name','id');
        

       $segment_lists=VehicleSegment::select('vehicle_segments.name')
       ->leftjoin('vehicle_segment_details','vehicle_segment_details.Vehicle_segment_id','=','vehicle_segments.id')
       ->where('vehicle_segments.organization_id',$organization_id)
       ->get();
      

        return view('trade_wms.vehicle_segmentdetails', compact('vehicle_variants','segments','segment_lists','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
       //  $segment_details=new VehicleSegmentDetail;
      //  $segment_details->vehicle_make_id=$request->make;
      //  $segment_details->vehicle_model_id=$request->model;
       // $segment_details->vehicle_variant_id=$request->variant;
      //  $segment_details->vehicle_segment_id=$request->segment;
       // $segment_details->save();
     //   Custom::userby($segment_details, true);
      //    Custom::add_addon('records');
          
          

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
    public function update(Request $request)
    {

       //dd($request->all());

        $variant_name = VehicleVariant::findOrFail($request->variant)->name;

        //dd($variant_name);

         $segment_details=VehicleSegmentDetail::updateOrCreate(
          [
            'vehicle_make_id'=>$request->make,
            'vehicle_model_id'=>$request->model,
            'vehicle_variant_id'=>$request->variant
          ],[
            'vehicle_segment_id'   => $request->segment,
            'vehicle_make_id'=>$request->make,
            'vehicle_model_id'=>$request->model,
            'vehicle_variant_id'=>$request->variant,
            'vehicle_variant_name'=>$variant_name,
             'created_by'=>Auth::user()->id,
          ]);
          // dd($segment_details);
          // $segment_details->save();
        return response()->json(['status' => 1, 'message' => 'segment details'.config('constants.flash.updated'),'data'=> $segment_details]);

           // return response()->json(['status'=>1,'data'=> $segment_details]);
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
