<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleBodyType;
use App\Custom;
use Validator;
use Session;

class BodyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $body_types = VehicleBodyType::select('id','name', 'description', 'status')->get();

        return view('trade_wms.body_type', compact('body_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.body_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_body_type_name(Request $request) {
        //dd($request->all());     
        $body_type = VehicleBodyType::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($body_type->id)) {
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

        $body_type = new VehicleBodyType;
        $body_type->name = $request->input('name');
        $body_type->display_name = $request->input('name');
        $body_type->description = $request->input('description');
        $body_type->save();

        Custom::userby($body_type, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Body Type'.config('constants.flash.added'), 'data' => ['id' => $body_type->id, 'name' => $body_type->name, 'display_name' => $body_type->display_name, 'description' => ($body_type->description != null) ? $body_type->description : "", 'status' => $body_type->status]]);
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
        $body_type = VehicleBodyType::where('id', $id)->first();
        if(!$body_type) abort(403);

        return view('trade_wms.body_type_edit', compact('body_type'));    }

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

        $body_type = VehicleBodyType::findOrFail($request->input('id'));
        $body_type->name = $request->input('name');
        $body_type->display_name = $request->input('name');
        $body_type->description = $request->input('description');
        $body_type->save();

        Custom::userby($body_type, false);

        return response()->json(['status' => 1, 'message' => 'Body Type'.config('constants.flash.updated'), 'data' => ['id' => $body_type->id, 'name' => $body_type->name, 'display_name' => $body_type->display_name, 'description' => ($body_type->description != null) ? $body_type->description : "", 'status' => $body_type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $body_type = VehicleBodyType::findOrFail($request->input('id'));
        $body_type->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Body Type'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function body_type_status_approval(Request $request)
    {
        VehicleBodyType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $body_types = explode(',', $request->id);
        $body_type_list = [];

        foreach ($body_types as $body_type_id) {
            $body_type_delete = VehicleBodyType::findOrFail($body_type_id);
            $body_type_delete->delete();
            $body_type_list[] = $body_type_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Body Type'.config('constants.flash.deleted'),'data'=>['list' => $body_type_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $body_types = explode(',', $request->id);
        $body_type_list = [];

        foreach ($body_types as $body_type_id) {
            VehicleBodyType::where('id', $body_type_id)->update(['status' => $request->input('status')]);;
            $body_type_list[] = $body_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Body Type'.config('constants.flash.updated'),'data'=>['list' => $body_type_list]]);
    }
}
