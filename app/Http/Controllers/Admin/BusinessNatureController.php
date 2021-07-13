<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessNature;
use App\Business;
use App\Custom;
use Validator;
use Response;
use Session;
use DB;

class BusinessNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $businessnatures = BusinessNature::select('business_natures.id', 'business_natures.display_name', 'business_natures.status')
            ->orderby('display_name')->get();
        return view('admin.business_nature',compact('businessnatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.business_nature_create');
    }

    public function check_business_nature_name(Request $request) {
        //dd($request->all());

        $business = BusinessNature::where('display_name', $request->display_name)
                ->where('id','!=', $request->id)->first();
        if(!empty($business->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
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
            'name' => 'required',
        ]);

        $businessnature = new BusinessNature();
        $businessnature->name = $request->input('name');
        $businessnature->display_name = $request->input('name');
        $businessnature->save();

        Custom::userby($businessnature, true);

        Custom::add_addon('records');

        return response()->json(['status' => 1, 'message' => 'Business Nature'.config('constants.flash.added'), 'data' => ['id' => $businessnature->id, 'display_name' => $businessnature->display_name, 'status' => $businessnature->status ]]);
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
        $businessnature = BusinessNature::where('id', $id)->first();
        if(!$businessnature) abort(403);

        return view('admin.business_nature_edit', compact('businessnature'));
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

        $businessnature = BusinessNature::findOrFail($request->input('id'));      
        $businessnature->name = $request->input('name');
        $businessnature->display_name = $request->input('name');
        $businessnature->save();

        Custom::userby($businessnature, false);

        return response()->json(['status' => 1, 'message' => 'Business Nature'.config('constants.flash.updated'), 'data' => ['id' => $businessnature->id, 'display_name' => $businessnature->display_name, 'status' => $businessnature->status ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $business_id = Business::where('business_nature_id', $request->id)->first();
        //dd($business_id);

        if($business_id != null)
        {
            return response()->json(['status' => 0, 'message' => 'This Business Nature is used on some Business.', 'data' => []]);
        }
        else {
            $businessnature = BusinessNature::findOrFail($request->input('id'));
            $businessnature->delete();

            Custom::delete_addon('records');

            return response()->json(['status' => 1, 'message' => 'Business Nature'.config('constants.flash.deleted'), 'data' => []]);
        }
    }

    public function multidestroy(Request $request)
    {
        $businessnatures = explode(',', $request->id);

        $businessnature_list = [];

        foreach ($businessnatures as $businessnature_id) {
            $business_nature = BusinessNature::findOrFail($businessnature_id);
            $business_nature->delete();
            $businessnature_list[] = $businessnature_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Business Nature'.config('constants.flash.deleted'),'data'=>['list' => $businessnature_list]]);
    }

    public function multiapprove(Request $request)
    {
        $businessnatures = explode(',', $request->id);

        $businessnature_list = [];

        foreach ($businessnatures as $businessnature_id) {
            BusinessNature::where('id', $businessnature_id)->update(['status' => $request->input('status')]);
            $businessnature_list[] = $businessnature_id;
        }

        return response()->json(['status'=>1, 'message'=>'Business Nature'.config('constants.flash.updated'),'data'=>['list' => $businessnature_list]]);
    }

    public function status_approval(Request $request)
    {
        BusinessNature::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(['status'=>1, 'message'=>'Business Nature'.config('constants.flash.updated'),'data'=>[]]);
    }    

}
