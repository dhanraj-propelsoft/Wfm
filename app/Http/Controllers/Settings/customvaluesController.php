<?php

namespace App\Http\Controllers\settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\OrgCustomValue;
use App\Unit;
use Session;
use DB;
class customvaluesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');
        $custom_values = OrgCustomValue::where('organization_id', $organization_id)->get();

        return view('settings.custom_values',compact('custom_values'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
       $custom_values = OrgCustomValue::where('organization_id', $organization_id)->where('id',$id)->first(); 
        if($custom_values->screen!='item_page'){
            $sample_data=$custom_values->sample; 

            if($custom_values->data1=="")
            {
                $data=substr($sample_data, 0, 6);          
            }
            else
            {
                $data=$custom_values->data1; 
            }      
       }
       else
        {
            $unit_data=unit::select( DB::raw('group_concat(name) as names'))->where('organization_id', $organization_id)->first(); 
            $sample_data=$unit_data->names;
            if($custom_values->data1=="")
            {
                $data1=explode(',', $sample_data);

                $data=$data1[1];
            }
            else
            {
                $data=$custom_values->data1; 
            }
        }    

        return view('settings.custom_values_edit',compact('custom_values','sample','data','data1','data2','data3','data4','sample_data','real_data'));
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
        $organization_id = Session::get('organization_id');
       
        $custom_values =OrgCustomValue::findOrFail($request->input('id'));
        $custom_values->module = $request->input('module'); 
        $custom_values->screen = $request->input('screen'); 
        $custom_values->factor = $request->input('factor'); 
        $custom_values->multiple = $request->input('multiple'); 
        $custom_values->sample = $request->input('sample'); 
        $custom_values->data1 = $request->input('data'); 
        $custom_values->organization_id = $organization_id; 
        $custom_values->last_modified_by = Auth::user()->id; 
        $custom_values->save();
       
        $multiple = '';
        if($custom_values->multiple == 0){
            $multiple = 'no';
        }else{
            $multiple = 'yes';
        }

     return response()->json(['status' => 1, 'message' => 'Custom Values'.config('constants.flash.updated'), 'data' => ['id' => $custom_values->id,'module'=>$custom_values->module,'screen'=>$custom_values->screen,'factor'=>$custom_values->factor,'multiple'=>$multiple,'value' => $custom_values->sample]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
