<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleSegment;
use App\Custom;
use Validator;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class VehicleSegmentController extends Controller
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
        $segments = Vehiclesegment::where('organization_id', $organization_id)->paginate(10);

        return view('trade_wms.vehicle_segment', compact('segments','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.vehicle_segment_create');
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
            'name' => 'required',
            'display_name' => 'required',        
        ]);

        $organization_id = Session::get('organization_id');

        $segment = new Vehiclesegment;
        $segment->name = $request->input('name');
        $segment->display_name = $request->input('display_name');
        $segment->description = $request->input('description');
        $segment->organization_id = $organization_id;
        $segment->save();

        Custom::userby($segment, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'segment'.config('constants.flash.added'), 'data' => ['id' => $segment->id, 'name' => $segment->name, 'display_name' => $segment->display_name, 'description' => ($segment->description != null) ? $segment->description : ""]]);
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
        //dd($request->all());
       $organization_id = Session::get('organization_id');

        $segment = Vehiclesegment::where('id', $id)->where('organization_id', $organization_id)->first();
        if(!$segment) abort(403);

        return view('trade_wms.vehicle_segment_edit', compact('segment'));
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
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $segment = Vehiclesegment::findOrFail($request->input('id'));
        $segment->name = $request->input('name');
        $segment->display_name = $request->input('display_name');
        $segment->description = $request->input('description');        
        $segment->save();

        Custom::userby($segment, false);
       
        return response()->json(['status' => 1, 'message' => 'segment'.config('constants.flash.updated'), 'data' => ['id' => $segment->id, 'name' => $segment->name, 'display_name' => $segment->display_name, 'description' => ($segment->description != null) ? $segment->description : "", 'status' => $segment->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        $segment = Vehiclesegment::findOrFail($request->input('id'));
        $segment->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'segment'.config('constants.flash.deleted'), 'data' => []]);
    }
}
