<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleModel;
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

class VehicleModelController extends Controller
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

        $vehicle_models = VehicleModel::select('vehicle_models.id','vehicle_models.name', 'vehicle_models.description', 'vehicle_models.status', 'vehicle_makes.name AS make_name')
        
        ->leftJoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_models.vehicle_make_id')
        ->get();
        //dd($vehicle_models);

        return view('trade_wms.vehicle_model', compact('vehicle_models','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');
        
        $vehicle_make_id = VehicleMake::orderBy('name')->pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');

        return view('trade_wms.vehicle_model_create', compact('vehicle_make_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_model_name(Request $request) {
        //dd($request->all());     
        $vehicle_model = VehicleModel::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        //dd($vehicle_model);
        if(!empty($vehicle_model->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'name' => 'required',       
            'make_id' => 'required'
        ]);

        $organization_id = Session::get('organization_id');

        $vehicle_model = new VehicleModel;
        $vehicle_model->name = $request->input('name');
        $vehicle_model->display_name = $request->input('name');
        $vehicle_model->vehicle_make_id = $request->input('make_id');
        $vehicle_model->description = $request->input('description');
        $vehicle_model->organization_id = $organization_id;
        $vehicle_model->save();

        $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_model->vehicle_make_id)->name : "";

        Custom::userby($vehicle_model, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Model'.config('constants.flash.added'), 'data' => ['id' => $vehicle_model->id, 'name' => $vehicle_model->name, 'display_name' => $vehicle_model->display_name, 'make_id' => $vehicle_make_id, 'description' => ($vehicle_model->description != null) ? $vehicle_model->description : "", 'status' => $vehicle_model->status]]);
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
        $organization_id = Session::get('organization_id');

        $vehicle_model = VehicleModel::where('id', $id)->first();
        if(!$vehicle_model) abort(403);

        $vehicle_make_id = VehicleMake::pluck('name', 'id');
        $vehicle_make_id->prepend('Select Vehicle Make', '');

        return view('trade_wms.vehicle_model_edit', compact('vehicle_model', 'vehicle_make_id'));
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
            'name' => 'required',
            'make_id' => 'required'
        ]);

        $vehicle_model = VehicleModel::findOrFail($request->input('id'));
        $vehicle_model->name = $request->input('name');
        $vehicle_model->display_name = $request->input('name');
        $vehicle_model->vehicle_make_id = $request->input('make_id');
        $vehicle_model->description = $request->input('description');
        $vehicle_model->save();

        $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_model->vehicle_make_id)->name : "";

        Custom::userby($vehicle_model, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Model'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_model->id, 'name' => $vehicle_model->name, 'display_name' => $vehicle_model->display_name, 'make_id' => $vehicle_make_id, 'description' => ($vehicle_model->description != null) ? $vehicle_model->description : "", 'status' => $vehicle_model->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle_model = VehicleModel::findOrFail($request->input('id'));
        $vehicle_model->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Model'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_model_status_approval(Request $request)
    {
        VehicleModel::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $vehicle_models = explode(',', $request->id);
        $vehicle_model_list = [];

        foreach ($vehicle_models as $vehicle_model_id) {
            $vehicle_model_delete = VehicleModel::findOrFail($vehicle_model_id);
            $vehicle_model_delete->delete();
            $vehicle_model_list[] = $vehicle_model_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Model'.config('constants.flash.deleted'),'data'=>['list' => $vehicle_model_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $vehicle_models = explode(',', $request->id);
        $vehicle_model_list = [];

        foreach ($vehicle_models as $vehicle_model_id) {
            VehicleModel::where('id', $vehicle_model_id)->update(['status' => $request->input('status')]);;
            $vehicle_model_list[] = $vehicle_model_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Model'.config('constants.flash.updated'),'data'=>['list' => $vehicle_model_list]]);
    }
}
