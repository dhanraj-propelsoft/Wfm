<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleMake;
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

class VehicleMakeController extends Controller
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
        
        
        $vehicle_makers = VehicleMake::select('id','name', 'description', 'status')
        ->get();

    

        return view('trade_wms.vehicle_make', compact('vehicle_makers','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.vehicle_make_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_make_name(Request $request) {
        //dd($request->all());     
        $vehicle_make = VehicleMake::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_make->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'        
        ]);
        $organization_id=Session::get('organization_id');

        $vehicle_make = new VehicleMake;
        $vehicle_make->name = $request->input('name');
        $vehicle_make->display_name = $request->input('name');
        $vehicle_make->description = $request->input('description');
        $vehicle_make->organization_id = $organization_id;

        $vehicle_make->save();

        Custom::userby($vehicle_make, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Make'.config('constants.flash.added'), 'data' => ['id' => $vehicle_make->id, 'name' => $vehicle_make->name, 'display_name' => $vehicle_make->display_name, 'description' => ($vehicle_make->description != null) ? $vehicle_make->description : "", 'status' => $vehicle_make->status]]);
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
        $organization_id=Session::get('organization_id');
        
        $vehicle_make = VehicleMake::where('id', $id)->first();
        if(!$vehicle_make) abort(403);

        return view('trade_wms.vehicle_make_edit', compact('vehicle_make'));
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
            'name' => 'required'
        ]);

        $vehicle_make = VehicleMake::findOrFail($request->input('id'));
        $vehicle_make->name = $request->input('name');
        $vehicle_make->display_name = $request->input('name');
        $vehicle_make->description = $request->input('description');
        $vehicle_make->save();

        Custom::userby($vehicle_make, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Make'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_make->id, 'name' => $vehicle_make->name, 'display_name' => $vehicle_make->display_name, 'description' => ($vehicle_make->description != null) ? $vehicle_make->description : "", 'status' => $vehicle_make->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_make = VehicleMake::findOrFail($request->input('id'));
        $vehicle_make->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Make'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_make_status_approval(Request $request)
    {
        VehicleMake::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $vehicle_makes = explode(',', $request->id);
        $vehicle_make_list = [];

        foreach ($vehicle_makes as $vehicle_make_id) {
            $vehicle_make_delete = VehicleMake::findOrFail($vehicle_make_id);
            $vehicle_make_delete->delete();
            $vehicle_make_list[] = $vehicle_make_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Make'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_make_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $vehicle_makes = explode(',', $request->id);
        $vehicle_make_list = [];

        foreach ($vehicle_makes as $vehicle_make_id) {
            VehicleMake::where('id', $vehicle_make_id)->update(['status' => $request->input('status')]);;
            $vehicle_make_list[] = $vehicle_make_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Make'.config('constants.flash.updated'),'data'=>['list' => $vehicle_make_list]]);
    }
}
