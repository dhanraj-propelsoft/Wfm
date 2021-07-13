<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleFuelType;
use App\Custom;
use Validator;
use Session;

class FuelTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fuel_types = VehicleFuelType::select('id','name', 'description', 'status')->get();

        return view('trade_wms.fuel_type', compact('fuel_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.fuel_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_fuel_type_name(Request $request) {
        //dd($request->all());     
        $fuel_type = VehicleFuelType::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($fuel_type->id)) {
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

        $fuel_type = new VehicleFuelType;
        $fuel_type->name = $request->input('name');
        $fuel_type->display_name = $request->input('name');
        $fuel_type->description = $request->input('description');
        $fuel_type->save();

        Custom::userby($fuel_type, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Fuel Type'.config('constants.flash.added'), 'data' => ['id' => $fuel_type->id, 'name' => $fuel_type->name, 'display_name' => $fuel_type->display_name, 'description' => ($fuel_type->description != null) ? $fuel_type->description : "", 'status' => $fuel_type->status]]);
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
        $fuel_type = VehicleFuelType::where('id', $id)->first();
        if(!$fuel_type) abort(403);

        return view('trade_wms.fuel_type_edit', compact('fuel_type'));
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

        $fuel_type = VehicleFuelType::findOrFail($request->input('id'));
        $fuel_type->name = $request->input('name');
        $fuel_type->display_name = $request->input('name');
        $fuel_type->description = $request->input('description');
        $fuel_type->save();

        Custom::userby($fuel_type, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Fuel Type'.config('constants.flash.updated'), 'data' => ['id' => $fuel_type->id, 'name' => $fuel_type->name, 'display_name' => $fuel_type->display_name, 'description' => ($fuel_type->description != null) ? $fuel_type->description : "", 'status' => $fuel_type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $fuel_type = VehicleFuelType::findOrFail($request->input('id'));
        $fuel_type->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Fuel Type'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function fuel_type_status_approval(Request $request)
    {
        VehicleFuelType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $fuel_types = explode(',', $request->id);
        $fuel_type_list = [];

        foreach ($fuel_types as $fuel_type_id) {
            $fuel_type_delete = VehicleFuelType::findOrFail($fuel_type_id);
            $fuel_type_delete->delete();
            $fuel_type_list[] = $fuel_type_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Fuel Type'.config('constants.flash.deleted'),'data'=>['list' => $fuel_type_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $fuel_types = explode(',', $request->id);
        $fuel_type_list = [];

        foreach ($fuel_types as $fuel_type_id) {
            VehicleFuelType::where('id', $fuel_type_id)->update(['status' => $request->input('status')]);;
            $fuel_type_list[] = $fuel_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Fuel Type'.config('constants.flash.updated'),'data'=>['list' => $fuel_type_list]]);
    }
}
