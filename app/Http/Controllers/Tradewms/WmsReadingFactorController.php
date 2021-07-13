<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WmsApplicableDivision;
use App\WmsReadingFactor;
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

class WmsReadingFactorController extends Controller
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

        $reading_factors = WmsReadingFactor::select('wms_reading_factors.id','wms_reading_factors.name', 'wms_reading_factors.description', 'wms_reading_factors.status', 'wms_applicable_divisions.division_name')
            ->leftJoin('wms_applicable_divisions', 'wms_applicable_divisions.id','=','wms_reading_factors.wms_division_id')
            ->where('wms_reading_factors.organization_id', $organization_id)->get();

        return view('trade_wms.reading_factor', compact('reading_factors','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');

        $division_id = WmsApplicableDivision::where('status', '1')->pluck('division_name', 'id');
        $division_id->prepend('Select Division', '');

        return view('trade_wms.reading_factor_create', compact('division_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function reading_factor_name(Request $request) {
        //dd($request->all());
        $organization_id = Session::get('organization_id');

        $reading_factor = WmsReadingFactor::where('name', $request->name)
                ->where('id','!=', $request->id)->where('organization_id','=', $organization_id)->first();
        if(!empty($reading_factor->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
           // 'wms_division_id' => 'required',      
            'name' => 'required',        
        ]);

        $organization_id = Session::get('organization_id');

        $reading_factor = new WmsReadingFactor;
        $reading_factor->name = $request->input('name');
       // $reading_factor->wms_division_id = $request->input('wms_division_id');
        $reading_factor->description = $request->input('description');
        $reading_factor->organization_id = $organization_id;
        $reading_factor->save();

        //$division_name = WmsApplicableDivision::findorFail($reading_factor->wms_division_id)->division_name;

        Custom::userby($reading_factor, true);
        Custom::add_addon('records');

         return response()->json(['status' => 1, 'message' => 'Wms Reading Factor'.config('constants.flash.added'), 'data' => ['id' => $reading_factor->id, 'name' => $reading_factor->name, 'description' => ($reading_factor->description != null) ? $reading_factor->description : "", 'status' => $reading_factor->status]]);
       
       /* return response()->json(['status' => 1, 'message' => 'Wms Reading Factor'.config('constants.flash.added'), 'data' => ['id' => $reading_factor->id, 'name' => $reading_factor->name, 'division_name' => $division_name, 'description' => ($reading_factor->description != null) ? $reading_factor->description : "", 'status' => $reading_factor->status]]);*/
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

        $reading_factor = WmsReadingFactor::where('id', $id)->where('organization_id', $organization_id)->first();
        if(!$reading_factor) abort(403);

        $division_id = WmsApplicableDivision::where('status', '1')->pluck('division_name', 'id');
        $division_id->prepend('Select Division', '');

        return view('trade_wms.reading_factor_edit', compact('reading_factor', 'division_id'));
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
            //'wms_division_id' => 'required',      
            'name' => 'required',
        ]);

        $reading_factor = WmsReadingFactor::findOrFail($request->input('id'));
        $reading_factor->name = $request->input('name');
       // $reading_factor->wms_division_id = $request->input('wms_division_id');
        $reading_factor->description = $request->input('description');
        $reading_factor->save();

       // $division_name = WmsApplicableDivision::findorFail($reading_factor->wms_division_id)->division_name;

        Custom::userby($reading_factor, false);

         return response()->json(['status' => 1, 'message' => 'Wms Reading Factor'.config('constants.flash.updated'), 'data' => ['id' => $reading_factor->id, 'name' => $reading_factor->name, 'description' => ($reading_factor->description != null) ? $reading_factor->description : "", 'status' => $reading_factor->status]]);

       /* return response()->json(['status' => 1, 'message' => 'Wms Reading Factor'.config('constants.flash.updated'), 'data' => ['id' => $reading_factor->id, 'name' => $reading_factor->name, 'division_name' => $division_name, 'description' => ($reading_factor->description != null) ? $reading_factor->description : "", 'status' => $reading_factor->status]]);*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $reading_factor = WmsReadingFactor::findOrFail($request->input('id'));
        $reading_factor->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Wms Reading Factor'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function reading_factor_status_approval(Request $request)
    {
        WmsReadingFactor::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $reading_factors = explode(',', $request->id);
        $reading_factor_list = [];

        foreach ($reading_factors as $reading_factor_id) {
            $reading_factor_delete = WmsReadingFactor::findOrFail($reading_factor_id);
            $reading_factor_delete->delete();
            $reading_factor_list[] = $reading_factor_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Wms Reading Factor'.config('constants.flash.deleted'),'data'=>['list' => $reading_factor_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $reading_factors = explode(',', $request->id);
        $reading_factor_list = [];

        foreach ($reading_factors as $reading_factor_id) {
            WmsReadingFactor::where('id', $reading_factor_id)->update(['status' => $request->input('status')]);;
            $reading_factor_list[] = $reading_factor_id;
        }

        return response()->json(['status'=>1, 'message'=>'Wms Reading Factor'.config('constants.flash.updated'),'data'=>['list' => $reading_factor_list]]);
    }
}
