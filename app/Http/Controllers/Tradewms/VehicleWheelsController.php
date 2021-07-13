<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleWheel;
use App\Custom;
use Validator;
use Session;

class VehicleWheelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle_wheels = VehicleWheel::select('id','name', 'description', 'status')->get();

        return view('trade_wms.vehicle_wheel', compact('vehicle_wheels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('trade_wms.vehicle_wheel_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_wheels_name(Request $request) {
        //dd($request->all());     
        $vehicle_wheel = VehicleWheel::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_wheel->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'name' => 'required'        
        ]);

        $vehicle_wheel = new VehicleWheel;
        $vehicle_wheel->name = $request->input('name');
        $vehicle_wheel->display_name = $request->input('name');
        $vehicle_wheel->description = $request->input('description');
        $vehicle_wheel->save();

        Custom::userby($vehicle_wheel, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle No of Wheels'.config('constants.flash.added'), 'data' => ['id' => $vehicle_wheel->id, 'name' => $vehicle_wheel->name, 'display_name' => $vehicle_wheel->display_name, 'description' => ($vehicle_wheel->description != null) ? $vehicle_wheel->description : "", 'status' => $vehicle_wheel->status]]);
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
        $vehicle_wheel = VehicleWheel::where('id', $id)->first();
        if(!$vehicle_wheel) abort(403);

        return view('trade_wms.vehicle_wheel_edit', compact('vehicle_wheel'));
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

        $vehicle_wheel = VehicleWheel::findOrFail($request->input('id'));
        $vehicle_wheel->name = $request->input('name');
        $vehicle_wheel->display_name = $request->input('name');
        $vehicle_wheel->description = $request->input('description');
        $vehicle_wheel->save();

        Custom::userby($vehicle_wheel, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle No of Wheels'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_wheel->id, 'name' => $vehicle_wheel->name, 'display_name' => $vehicle_wheel->display_name, 'description' => ($vehicle_wheel->description != null) ? $vehicle_wheel->description : "", 'status' => $vehicle_wheel->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_wheel = VehicleWheel::findOrFail($request->input('id'));
        $vehicle_wheel->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle No of Wheels'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_wheels_status_approval(Request $request)
    {
        VehicleWheel::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $vehicle_wheels = explode(',', $request->id);
        $vehicle_wheel_list = [];

        foreach ($vehicle_wheels as $vehicle_wheel_id) {
            $vehicle_wheel_delete = VehicleWheel::findOrFail($vehicle_wheel_id);
            $vehicle_wheel_delete->delete();
            $vehicle_wheel_list[] = $vehicle_wheel_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle No of Wheels'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_wheel_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $vehicle_wheels = explode(',', $request->id);
        $vehicle_wheel_list = [];

        foreach ($vehicle_wheels as $vehicle_wheel_id) {
            VehicleWheel::where('id', $vehicle_wheel_id)->update(['status' => $request->input('status')]);;
            $vehicle_wheel_list[] = $vehicle_wheel_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle No of Wheels'.config('constants.flash.updated'),'data'=>['list' => $vehicle_wheel_list]]);
    }
}
