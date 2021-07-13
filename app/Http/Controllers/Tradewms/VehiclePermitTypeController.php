<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehiclePermit;
use App\Custom;
use Session;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;



class VehiclePermitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200; 

 

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
        $permit_type=VehiclePermit::select('id','name','description','status')->get();
        
        return view('trade_wms.permit_type',compact('permit_type','state','city','title','payment','terms','group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.permit_type_create');
    }

    public function vehicle_permit_type_name(Request $request)
    {
        $vehicle_permit=VehiclePermit::where('name',$request->name)
        ->where('id','!=',$request->id)->first();
        if(!empty($vehicle_permit->id))
        {
            echo 'false';
        }
        else
        {
            echo 'true';
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id=null)
    {
        
        $this->validate($request,[
            'name' => 'required',
           
        ]);
        $organization_id=session::get('organization_id');
        $permit_type=new VehiclePermit;
        $permit_type->name=$request->input('name');
        $permit_type->display_name=$request->input('name');
        $permit_type->description=$request->input('description');
        $permit_type->organization_id=$organization_id;
        $permit_type->save();
        Custom::userby($permit_type, true);
        Custom::add_addon('records');
        return response()->json(['status' => 1 ,'message' => 'Vehicle Permit'.config('constants.flash.added'),'data' => ['name' => $permit_type->name ,'description' => $permit_type->description ]]);
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
        $permit_type = VehiclePermit::where('id' ,$id)->first();
        return view('trade_wms.permit_type_edit',compact('permit_type'));
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
        $this->validate($request,[
            'name' => 'required',
            
        ]);

        $permit_type=VehiclePermit::findorfail($request->input('id'));
        $permit_type->name = $request->input('name');
        $permit_type->display_name = $request->input('name');
        $permit_type->description = $request->input('description');
        $permit_type->save();

        Custom::userby($permit_type, false);
         
        return response()->json(['message' => 'Vehicle Permit'.config('constants.flash.updated'),'data' => ['id' => $permit_type->id,'name' => $permit_type->name ,'description' => $permit_type->description, 'status' => $permit_type->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        VehiclePermit::where('id',$request->input('id'))->delete();
        return response()->json(['status' => 1,'message' => 'Vehicle permit'.config('constants.flash.deleted'),'data' =>[]]);
    }

}
