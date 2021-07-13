<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleCategory;
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

class VehicleCategoryController extends Controller
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
        
        $categories = VehicleCategory::select('id','name', 'description', 'status')->get();
        return view('trade_wms.vehicle_category', compact('categories','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.vehicle_category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function vehicle_category_name(Request $request) {
        //dd($request->all());     
         $organization_id=session::get('organization_id');

        $category = VehicleCategory::where('name', $request->name)
               ->where('id','!=', $request->id)->first();
        if(!empty($category->id)) {
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
         $organization_id=Session::get('organization_id');
        $category = new VehicleCategory;
        $category->name = $request->input('name');
        $category->display_name = $request->input('name');
        $category->description = $request->input('description');
         $category->organization_id = $organization_id;
        $category->save();

        Custom::userby($category, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Vehicle Category'.config('constants.flash.added'), 'data' => ['id' => $category->id, 'name' => $category->name, 'display_name' => $category->display_name, 'description' => ($category->description != null) ? $category->description : "", 'status' => $category->status]]);
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
        $organization_id=Session::get('organization_id');
        $category = VehicleCategory::where('id', $id)->first();
        if(!$category) abort(403);

        return view('trade_wms.vehicle_category_edit', compact('category'));
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

        $category = VehicleCategory::findOrFail($request->input('id'));
        $category->name = $request->input('name');
        $category->display_name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();

        Custom::userby($category, false);

        return response()->json(['status' => 1, 'message' => 'Vehicle Category'.config('constants.flash.updated'), 'data' => ['id' => $category->id, 'name' => $category->name, 'display_name' => $category->display_name, 'description' => ($category->description != null) ? $category->description : "", 'status' => $category->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $category = VehicleCategory::findOrFail($request->input('id'));
        $category->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Vehicle Category'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function vehicle_category_status_approval(Request $request)
    {
        VehicleCategory::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

    public function multidestroy(Request $request)
    {
        $categorys = explode(',', $request->id);
        $category_list = [];

        foreach ($categorys as $category_id) {
            $category_delete = VehicleCategory::findOrFail($category_id);
            $category_delete->delete();
            $category_list[] = $category_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Category'.config('constants.flash.deleted'),'data'=>['list' => $category_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $categorys = explode(',', $request->id);
        $category_list = [];

        foreach ($categorys as $category_id) {
            VehicleCategory::where('id', $category_id)->update(['status' => $request->input('status')]);;
            $category_list[] = $category_id;
        }

        return response()->json(['status'=>1, 'message'=>'Vehicle Category'.config('constants.flash.updated'),'data'=>['list' => $category_list]]);
    }
}
