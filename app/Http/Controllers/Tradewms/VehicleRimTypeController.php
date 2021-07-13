<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleRimType;
use App\Custom;
use Validator;
use Session;

class VehicleRimTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rim_types = VehicleRimType::select('id','name', 'description', 'status')->get();

        return view('trade_wms.rim_type', compact('rim_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.rim_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_rim_name(Request $request) {
        //dd($request->all());     
        $vehicle_rim = VehicleRimType::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_rim->id)) {
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

        $vehicle_rim = new VehicleRimType;
        $vehicle_rim->name = $request->input('name');
        $vehicle_rim->display_name = $request->input('name');
        $vehicle_rim->description = $request->input('description');
        $vehicle_rim->save();

        Custom::userby($vehicle_rim, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Rim / Wheel'.config('constants.flash.added'), 'data' => ['id' => $vehicle_rim->id, 'name' => $vehicle_rim->name, 'display_name' => $vehicle_rim->display_name, 'description' => ($vehicle_rim->description != null) ? $vehicle_rim->description : "", 'status' => $vehicle_rim->status]]);
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
        $vehicle_rim = VehicleRimType::where('id', $id)->first();
        if(!$vehicle_rim) abort(403);

        return view('trade_wms.rim_type_edit', compact('vehicle_rim'));
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

        $vehicle_rim = VehicleRimType::findOrFail($request->input('id'));
        $vehicle_rim->name = $request->input('name');
        $vehicle_rim->display_name = $request->input('name');
        $vehicle_rim->description = $request->input('description');
        $vehicle_rim->save();

        Custom::userby($vehicle_rim, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Rim / Wheel'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_rim->id, 'name' => $vehicle_rim->name, 'display_name' => $vehicle_rim->display_name, 'description' => ($vehicle_rim->description != null) ? $vehicle_rim->description : "", 'status' => $vehicle_rim->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_rim = VehicleRimType::findOrFail($request->input('id'));
        $vehicle_rim->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Rim / Wheel'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_rim_status_approval(Request $request)
    {
        VehicleRimType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $vehicle_rims = explode(',', $request->id);
        $vehicle_rim_list = [];

        foreach ($vehicle_rims as $vehicle_rim_id) {
            $vehicle_rim_delete = VehicleRimType::findOrFail($vehicle_rim_id);
            $vehicle_rim_delete->delete();
            $vehicle_rim_list[] = $vehicle_rim_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Rim / Wheel'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_rim_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $vehicle_rims = explode(',', $request->id);
        $vehicle_rim_list = [];

        foreach ($vehicle_rims as $vehicle_rim_id) {
            VehicleRimType::where('id', $vehicle_rim_id)->update(['status' => $request->input('status')]);;
            $vehicle_rim_list[] = $vehicle_rim_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Rim / Wheel'.config('constants.flash.updated'),'data'=>['list' => $vehicle_rim_list]]);
    }
}
