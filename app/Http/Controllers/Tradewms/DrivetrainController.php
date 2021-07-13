<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleDrivetrain;
use App\Custom;
use Validator;
use Session;


class DrivetrainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivetrains = VehicleDrivetrain::select('id','name', 'description', 'status')->get();

        return view('trade_wms.drivetrain', compact('drivetrains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.drivetrain_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_drivetrain_name(Request $request) {
        //dd($request->all());     
        $drivetrain = VehicleDrivetrain::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($drivetrain->id)) {
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

        $drivetrain = new VehicleDrivetrain;
        $drivetrain->name = $request->input('name');
        $drivetrain->display_name = $request->input('name');
        $drivetrain->description = $request->input('description');
        $drivetrain->save();

        Custom::userby($drivetrain, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Drivetrain'.config('constants.flash.added'), 'data' => ['id' => $drivetrain->id, 'name' => $drivetrain->name, 'display_name' => $drivetrain->display_name, 'description' => ($drivetrain->description != null) ? $drivetrain->description : "", 'status' => $drivetrain->status]]);
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
        $drivetrain = VehicleDrivetrain::where('id', $id)->first();
        if(!$drivetrain) abort(403);

        return view('trade_wms.drivetrain_edit', compact('drivetrain'));
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

        $drivetrain = VehicleDrivetrain::findOrFail($request->input('id'));
        $drivetrain->name = $request->input('name');
        $drivetrain->display_name = $request->input('name');
        $drivetrain->description = $request->input('description');
        $drivetrain->save();

        Custom::userby($drivetrain, false);

        return response()->json(['status' => 1, 'message' => 'Drivetrain'.config('constants.flash.updated'), 'data' => ['id' => $drivetrain->id, 'name' => $drivetrain->name, 'display_name' => $drivetrain->display_name, 'description' => ($drivetrain->description != null) ? $drivetrain->description : "", 'status' => $drivetrain->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $drivetrain = VehicleDrivetrain::findOrFail($request->input('id'));
        $drivetrain->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Drivetrain'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function drivetrain_status_approval(Request $request)
    {
        VehicleDrivetrain::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $drivetrains = explode(',', $request->id);
        $drivetrain_list = [];

        foreach ($drivetrains as $drivetrain_id) {
            $drivetrain_delete = VehicleDrivetrain::findOrFail($drivetrain_id);
            $drivetrain_delete->delete();
            $drivetrain_list[] = $drivetrain_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Drivetrain'.config('constants.flash.deleted'),'data'=>['list' => $drivetrain_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $drivetrains = explode(',', $request->id);
        $drivetrain_list = [];

        foreach ($drivetrains as $drivetrain_id) {
            VehicleDrivetrain::where('id', $drivetrain_id)->update(['status' => $request->input('status')]);;
            $drivetrain_list[] = $drivetrain_id;
        }

        return response()->json(['status'=>1, 'message'=>'Drivetrain'.config('constants.flash.updated'),'data'=>['list' => $drivetrain_list]]);
    }
}
