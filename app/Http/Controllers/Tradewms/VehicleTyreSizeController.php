<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleTyreSize;
use App\Custom;
use Validator;
use Session;

class VehicleTyreSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tyre_sizes = VehicleTyreSize::select('id','name', 'description', 'status')->get();

        return view('trade_wms.tyre_size', compact('tyre_sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.tyre_size_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function tyre_size_name(Request $request) {
        //dd($request->all());     
        $tyre_size = VehicleTyreSize::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($tyre_size->id)) {
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

        $tyre_size = new VehicleTyreSize;
        $tyre_size->name = $request->input('name');
        $tyre_size->display_name = $request->input('name');
        $tyre_size->description = $request->input('description');
        $tyre_size->save();

        Custom::userby($tyre_size, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Size'.config('constants.flash.added'), 'data' => ['id' => $tyre_size->id, 'name' => $tyre_size->name, 'display_name' => $tyre_size->display_name, 'description' => ($tyre_size->description != null) ? $tyre_size->description : "", 'status' => $tyre_size->status]]);
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
        $tyre_size = VehicleTyreSize::where('id', $id)->first();
        if(!$tyre_size) abort(403);

        return view('trade_wms.tyre_size_edit', compact('tyre_size'));
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

        $tyre_size = VehicleTyreSize::findOrFail($request->input('id'));
        $tyre_size->name = $request->input('name');
        $tyre_size->display_name = $request->input('name');
        $tyre_size->description = $request->input('description');
        $tyre_size->save();

        Custom::userby($tyre_size, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Size'.config('constants.flash.updated'), 'data' => ['id' => $tyre_size->id, 'name' => $tyre_size->name, 'display_name' => $tyre_size->display_name, 'description' => ($tyre_size->description != null) ? $tyre_size->description : "", 'status' => $tyre_size->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tyre_size = VehicleTyreSize::findOrFail($request->input('id'));
        $tyre_size->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Tyre Size'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function tyre_size_status_approval(Request $request)
    {
        VehicleTyreSize::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $tyre_sizes = explode(',', $request->id);
        $tyre_size_list = [];

        foreach ($tyre_sizes as $tyre_size_id) {
            $tyre_size_delete = VehicleTyreSize::findOrFail($tyre_size_id);
            $tyre_size_delete->delete();
            $tyre_size_list[] = $tyre_size_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Tyre Size'.config('constants.flash.deleted'),'data'=>['list' => $tyre_size_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $tyre_sizes = explode(',', $request->id);
        $tyre_size_list = [];

        foreach ($tyre_sizes as $tyre_size_id) {
            VehicleTyreSize::where('id', $tyre_size_id)->update(['status' => $request->input('status')]);;
            $tyre_size_list[] = $tyre_size_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Tyre Size'.config('constants.flash.updated'),'data'=>['list' => $tyre_size_list]]);
    }
}
