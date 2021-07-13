<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ServiceType;
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

class ServiceTypeController extends Controller
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
        $service_types = ServiceType::select('id','name', 'description', 'status')->get();

        return view('trade_wms.service_type', compact('service_types','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.service_type_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function service_type_name(Request $request) {
        //dd($request->all());     
        $service_type = ServiceType::where('name', $request->name)
                ->where('id','!=', $request->id)->first();
        if(!empty($service_type->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store(Request $request)
    {
         $organization_id=session::get('organization_id');
        //dd($request->all());
        $this->validate($request, [
            'name' => 'required'        
        ]);

        $service_type = new ServiceType;
        $service_type->name = $request->input('name');
        $service_type->display_name = $request->input('name');
        $service_type->description = $request->input('description');
        $service_type->organization_id = $organization_id;
        $service_type->save();

        Custom::userby($service_type, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Service Type'.config('constants.flash.added'), 'data' => ['id' => $service_type->id, 'name' => $service_type->name, 'display_name' => $service_type->display_name, 'description' => ($service_type->description != null) ? $service_type->description : "", 'status' => $service_type->status]]);
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
        $service_type = ServiceType::where('id', $id)->first();
        if(!$service_type) abort(403);

        return view('trade_wms.service_type_edit', compact('service_type'));
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

        $service_type = ServiceType::findOrFail($request->input('id'));
        $service_type->name = $request->input('name');
        $service_type->display_name = $request->input('name');
        $service_type->description = $request->input('description');
        $service_type->save();

        Custom::userby($service_type, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Service Type'.config('constants.flash.updated'), 'data' => ['id' => $service_type->id, 'name' => $service_type->name, 'display_name' => $service_type->display_name, 'description' => ($service_type->description != null) ? $service_type->description : "", 'status' => $service_type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $service_type = ServiceType::findOrFail($request->input('id'));
        $service_type->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Service Type'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function service_type_status_approval(Request $request)
    {
        ServiceType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $service_types = explode(',', $request->id);
        $service_type_list = [];

        foreach ($service_types as $service_type_id) {
            $service_type_delete = ServiceType::findOrFail($service_type_id);
            $service_type_delete->delete();
            $service_type_list[] = $service_type_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Service Type'.config('constants.flash.deleted'),'data'=>['list' => $service_type_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $service_types = explode(',', $request->id);
        $service_type_list = [];

        foreach ($service_types as $service_type_id) {
            ServiceType::where('id', $service_type_id)->update(['status' => $request->input('status')]);;
            $service_type_list[] = $service_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Service Type'.config('constants.flash.updated'),'data'=>['list' => $service_type_list]]);
    }
}
