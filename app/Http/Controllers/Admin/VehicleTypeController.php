<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleType;
use App\Custom;
use Validator;
use Session;
class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle_types = VehicleType::select('id','name','display_name','description','status')->get();
        return view('admin.vehicle_type', compact('vehicle_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vehicle_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'name' => 'required'        
        ]);
        $type = new VehicleType;
        $type->name = $request->input('name');
        $type->display_name = $request->input('name');
        $type->description = $request->input('description');
        $type->save();

        Custom::userby($type, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle type'.config('constants.flash.added'), 'data' => ['id' => $type->id, 'name' => $type->name, 'display_name' => $type->display_name, 'description' => ($type->description != null) ? $type->description : "", 'status' => $type->status]]);
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

         $vehicle_type = VehicleType::where('id', $id)->first();
        if(!$vehicle_type) abort(403);

        return view('admin.vehicle_type_edit', compact('vehicle_type'));
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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $type = VehicleType::findOrFail($request->input('id'));
        $type->name = $request->input('name');
        $type->display_name = $request->input('name');
        $type->description = $request->input('description');
        $type->save();

        Custom::userby($type, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle type'.config('constants.flash.updated'), 'data' => ['id' => $type->id, 'name' => $type->name, 'display_name' => $type->display_name, 'description' => ($type->description != null) ? $type->description : "", 'status' => $type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_type = VehicleType::findOrFail($request->input('id'));
        $vehicle_type->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle type'.config('constants.flash.deleted'), 'data' => []]);
    }
    public function multidestroy(Request $request)
    {
        $vehicle_types = explode(',', $request->id);
        $vehicle_type_list = [];

        foreach ($vehicle_types as $type_id) {
            $vehicle_type_delete = VehicleType::findOrFail($type_id);
            $vehicle_type_delete->delete();
            $vehicle_type_list[] = $type_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle type'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_type_list]]);
    } 
    public function multiapprove(Request $request)
    {
        $vehicle_types = explode(',', $request->id);
        $vehicle_type_list = [];

        foreach ($vehicle_types as $vehicle_type_id) {
            VehicleType::where('id', $vehicle_type_id)->update(['status' => $request->input('status')]);;
            $vehicle_type_list[] = $vehicle_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Category'.config('constants.flash.updated'),'data'=>['list' => $vehicle_type_list]]);
    }
    public function vehicle_type_status_approval(Request $request)
    {
        VehicleType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }
    public function vehicle_type_name(Request $request) {
        
        $vehicle_type = VehicleType::where('name', $request->name)
               ->where('id','!=', $request->id)->first();
        if(!empty($vehicle_type->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
