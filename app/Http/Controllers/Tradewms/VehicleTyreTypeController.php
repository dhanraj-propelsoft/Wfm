<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleTyreType;
use App\Custom;
use Validator;
use Session;

class VehicleTyreTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tyre_types = VehicleTyreType::select('id','name', 'description', 'status')->get();

        return view('trade_wms.tyre_type', compact('tyre_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.tyre_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function tyre_type_name(Request $request) {
        //dd($request->all());     
        $tyre_type = VehicleTyreType::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($tyre_type->id)) {
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

        $tyre_type = new VehicleTyreType;
        $tyre_type->name = $request->input('name');
        $tyre_type->display_name = $request->input('name');
        $tyre_type->description = $request->input('description');
        $tyre_type->save();

        Custom::userby($tyre_type, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Type'.config('constants.flash.added'), 'data' => ['id' => $tyre_type->id, 'name' => $tyre_type->name, 'display_name' => $tyre_type->display_name, 'description' => ($tyre_type->description != null) ? $tyre_type->description : "", 'status' => $tyre_type->status]]);
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
        $tyre_type = VehicleTyreType::where('id', $id)->first();
        if(!$tyre_type) abort(403);

        return view('trade_wms.tyre_type_edit', compact('tyre_type'));
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

        $tyre_type = VehicleTyreType::findOrFail($request->input('id'));
        $tyre_type->name = $request->input('name');
        $tyre_type->display_name = $request->input('name');
        $tyre_type->description = $request->input('description');
        $tyre_type->save();

        Custom::userby($tyre_type, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Type'.config('constants.flash.updated'), 'data' => ['id' => $tyre_type->id, 'name' => $tyre_type->name, 'display_name' => $tyre_type->display_name, 'description' => ($tyre_type->description != null) ? $tyre_type->description : "", 'status' => $tyre_type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tyre_type = VehicleTyreType::findOrFail($request->input('id'));
        $tyre_type->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Type'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function tyre_type_status_approval(Request $request)
    {
        VehicleTyreType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $tyre_types = explode(',', $request->id);
        $tyre_type_list = [];

        foreach ($tyre_types as $tyre_type_id) {
            $tyre_type_delete = VehicleTyreType::findOrFail($tyre_type_id);
            $tyre_type_delete->delete();
            $tyre_type_list[] = $tyre_type_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Tyre Type'.config('constants.flash.deleted'),'data'=>['list' => $tyre_type_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $tyre_types = explode(',', $request->id);
        $tyre_type_list = [];

        foreach ($tyre_types as $tyre_type_id) {
            VehicleTyreType::where('id', $tyre_type_id)->update(['status' => $request->input('status')]);;
            $tyre_type_list[] = $tyre_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Tyre Type'.config('constants.flash.updated'),'data'=>['list' => $tyre_type_list]]);
    }
}
