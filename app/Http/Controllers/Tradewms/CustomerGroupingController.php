<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CustomerGroping;
use Validator;
use App\Discount;
use App\Custom;
use Session;
use DB;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
class CustomerGroupingController extends Controller
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
        
        $customer_groupings = CustomerGroping::where('organization_id',$organization_id)->get();
         return view('trade_wms.customer_grouping',compact('customer_groupings','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');
       $discounts = Discount::where('organization_id',$organization_id)->pluck('display_name', 'id');
        $discounts->prepend('Select Discount', '');
        return view('trade_wms.customer_grouping_create',compact('discounts'));
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

        $organization_id = Session::get('organization_id');

        $customer_grouping = new CustomerGroping;
        $customer_grouping->name = $request->input('name');
        $customer_grouping->display_name = $request->input('name');
        $customer_grouping->discount_value = $request->input('discount_value');
        $customer_grouping->description = $request->input('description');
        $customer_grouping->organization_id = $organization_id;
        $customer_grouping->save();

        Custom::userby($customer_grouping, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Customer Group Name'.config('constants.flash.added'), 'data' => ['id' => $customer_grouping->id, 'name' => $customer_grouping->name, 'display_name' => $customer_grouping->display_name,'discount_value' => $customer_grouping->discount_value,'description' => ($customer_grouping->description != null) ? $customer_grouping->description : ""]]);
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

        $discounts = Discount::where('organization_id',$organization_id)->pluck('display_name', 'id');
        $discounts->prepend('Select Discount', '');

        $customer_grouping = CustomerGroping::where('id', $id)->where('organization_id', $organization_id)->first();
        if(!$customer_grouping) abort(403);

        return view('trade_wms.customer_grouping_edit', compact('customer_grouping','discounts'));
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

        $customer_grouping = CustomerGroping::findOrFail($request->input('id'));
        $customer_grouping->name = $request->input('name');
        $customer_grouping->discount_value = $request->input('discount_value');
        $customer_grouping->display_name = $request->input('name');
        $customer_grouping->description = $request->input('description');        
        $customer_grouping->save();

        Custom::userby($customer_grouping, false);
       
        return response()->json(['status' => 1, 'message' => 'Customer Group Name'.config('constants.flash.updated'), 'data' => ['id' => $customer_grouping->id, 'name' => $customer_grouping->name, 'display_name' => $customer_grouping->display_name,'discount_value' => $customer_grouping->discount_value,'description' => ($customer_grouping->description != null) ? $customer_grouping->description : "", 'status' => $customer_grouping->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $customer_grouping = CustomerGroping::findOrFail($request->input('id'));
        $customer_grouping->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Customer Group Name'.config('constants.flash.deleted'), 'data' => []]);
    }

     public function customer_grouping_status_approvel(Request $request)
    {
        CustomerGroping::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(["status" => $request->input('status')]);
    }

     public function customer_grouping_name(Request $request) {
        //dd($request->all());
        $organization_id = Session::get('organization_id');

        $customer_grouping = CustomerGroping::where('name', $request->name)
                ->where('id','!=', $request->id)->where('organization_id','=', $organization_id)->first();
        if(!empty($customer_grouping->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

     public function multidestroy(Request $request)
    {
        $customer_grouping = explode(',', $request->id);
        $customer_grouping_list = [];

        foreach ($customer_grouping as $customer_grouping_id) {
            $customer_grouping_delete = CustomerGroping::findOrFail($customer_grouping_id);
            $customer_grouping_delete->delete();
            $customer_grouping_list[] = $vehicle_make_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Customer Group Name'.config('constants.flash.deleted'),'data'=>['list' => $customer_grouping_list]]);
    }   

    public function multiapprove(Request $request)
    {
        $customer_grouping = explode(',', $request->id);
        $customer_grouping_list = [];

        foreach ($customer_grouping as $customer_grouping_id) {
            CustomerGroping::where('id', $customer_grouping_id)->update(['status' => $request->input('status')]);;
            $customer_grouping[] = $customer_grouping_id;
        }

        return response()->json(['status'=>1, 'message'=>'Customer Group Name'.config('constants.flash.updated'),'data'=>['list' => $customer_grouping]]);
    }

    public function discount_search(Request $request)
    {
     $organization_id = Session::get('organization_id');

     $discount_value = Discount::select('value')->where('organization_id',$organization_id)->where('id',$request->discount_id)->first();
     return response()->json(['discount_value' => $discount_value]);

}
}