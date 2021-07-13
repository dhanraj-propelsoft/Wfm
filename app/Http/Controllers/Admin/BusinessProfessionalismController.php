<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessProfessionalism;
use App\Business;
use App\Custom;
use Validator;
use Response;
use Session;
use DB;

class BusinessProfessionalismController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $businessprofessionalisms = BusinessProfessionalism::select('business_professionalisms.id', 'business_professionalisms.display_name', 'business_professionalisms.status')
            ->orderby('display_name')->get();
        return view('admin.business_professionalisms',compact('businessprofessionalisms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.business_professionalisms_create');
    }

    public function check_business_professionalism_name(Request $request) {
        //dd($request->all());

        $business = BusinessProfessionalism::where('display_name', $request->display_name)
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

        $businessprofessionalism = new BusinessProfessionalism();
        $businessprofessionalism->name = $request->input('name');
        $businessprofessionalism->display_name = $request->input('name');
        $businessprofessionalism->save();

        Custom::userby($businessprofessionalism, true);

        Custom::add_addon('records');

        return response()->json(['status' => 1, 'message' => 'Business Professionalism'.config('constants.flash.added'), 'data' => ['id' => $businessprofessionalism->id, 'display_name' => $businessprofessionalism->display_name, 'status' => $businessprofessionalism->status ]]);
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
        $businessprofessionalism = BusinessProfessionalism::where('id', $id)->first();
        if(!$businessprofessionalism) abort(403);

        return view('admin.business_professionalism_edit', compact('businessprofessionalism'));
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

        $businessprofessionalism = BusinessProfessionalism::findOrFail($request->input('id'));      
        $businessprofessionalism->name = $request->input('name');
        $businessprofessionalism->display_name = $request->input('name');
        $businessprofessionalism->save();

        Custom::userby($businessprofessionalism, false);

        return response()->json(['status' => 1, 'message' => 'Business Professionalism'.config('constants.flash.updated'), 'data' => ['id' => $businessprofessionalism->id, 'display_name' => $businessprofessionalism->display_name, 'status' => $businessprofessionalism->status ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $business_id = Business::where('business_professionalism_id', $request->id)->first();
        //dd($business_id);

        if($business_id != null)
        {
            return response()->json(['status' => 0, 'message' => 'This Business Professionalism is used on some Business.', 'data' => []]);
        }
        else {
            $businessprofessionalism = BusinessProfessionalism::findOrFail($request->input('id'));
            $businessprofessionalism->delete();

            Custom::delete_addon('records');

            return response()->json(['status' => 1, 'message' => 'Business Professionalism'.config('constants.flash.deleted'), 'data' => []]);
        }
    }

    public function multidestroy(Request $request)
    {
        $businessprofessionalisms = explode(',', $request->id);

        $businessprofessionalism_list = [];

        foreach ($businessprofessionalisms as $businessprofessionalism_id) {
            $business_professionalism = BusinessProfessionalism::findOrFail($businessprofessionalism_id);
            $business_professionalism->delete();
            $businessprofessionalism_list[] = $businessprofessionalism_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Business Professionalism'.config('constants.flash.deleted'),'data'=>['list' => $businessprofessionalism_list]]);
    }

    public function multiapprove(Request $request)
    {
        $businessprofessionalisms = explode(',', $request->id);

        $businessprofessionalism_list = [];

        foreach ($businessprofessionalisms as $businessprofessionalism_id) {
            BusinessProfessionalism::where('id', $businessprofessionalism_id)->update(['status' => $request->input('status')]);
            $businessprofessionalism_list[] = $businessprofessionalism_id;
        }

        return response()->json(['status'=>1, 'message'=>'Business Professionalism'.config('constants.flash.updated'),'data'=>['list' => $businessprofessionalism_list]]);
    }

    public function status_approval(Request $request)
    {
        BusinessProfessionalism::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(['status'=>1, 'message'=>'Business Professionalism'.config('constants.flash.updated'),'data'=>[]]);
    }

}
