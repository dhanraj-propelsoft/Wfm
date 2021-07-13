<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmAppraisalKpi;
use Carbon\Carbon;
use App\Custom;
use Session;
use Auth;
use DB;

class AppraisalKpiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id=session::get('organization_id');

        $appraisals=HrmAppraisalKpi::select('hrm_appraisal_kpis.id','hrm_appraisal_kpis.name','hrm_appraisal_kpis.description','hrm_appraisal_kpis.weight','hrm_appraisal_kpis.valid_from')->where('organization_id',$organization_id)->get();
        return view('hrm.appraisal_kpi',compact('appraisals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hrm.appraisal_kpi_create');
    }


    public function appraisal_weight_check(Request $request)
    
    {

        $organization_id=Session::get('organization_id');

        $weights=HrmAppraisalKpi::select('weight')->where('organization_id',$organization_id)->sum('weight');
        $weight= 1 - $weights;
         return response()->json([ 'weight' => $weight]);



         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->all());
        $organization_id=Session::get('organization_id');

         if($request->input('weight') != null)
        {
            $weight=HrmAppraisalKpi::select('weight')->where('organization_id',$organization_id)->sum('weight');
           $weight_sum=round( $weight,2);
            //dd($weight_sum);
            $weight_value=$request->input('weight');

            $weight_round=round($weight_value,2);
            //dd($weight_round);
            $weights= $weight_sum + $weight_round;
            //dd($weights);
            $balance= 1-$weight_sum;
            //dd(round($weights),2);
            if($weight_value != 0)
            {
            if($weights <= 1.0 && $weight_value <= 1.0 )
            {

                $appraisal=new HrmAppraisalKpi;
                $appraisal->name=$request->input('name');
                //dd($name);
                $appraisal->description=$request->input('definition');
                $appraisal->weight=$weight_round;
                if($request->input('valid_from') != null)
                {
                $appraisal->valid_from=($request->input('valid_from') != null)? carbon::parse($request->input('valid_from'))->format('Y-m-d'): null;
                }  
                
                $appraisal->organization_id=$organization_id;
                $appraisal->save();

                Custom::userby($appraisal, true);

                return response()->json(['status' => '1','message' => 'Appraisal'.config('constants.flash.added'), 'data' => ['id' =>$appraisal->id,'name' => $appraisal->name , 'description' => $appraisal->description , 'weight' =>  $appraisal->weight ,'valid_from' =>  $appraisal->valid_from]]);
            }

            else
            {
                $message='Weight must be lessthan or equal to  '.round($balance,2);
                //dd($message);
               return response()->json(['status' => '0','message' => $message, 'data' => []]);

            }
            }
            else
            {
                return response()->json(['status' => '0','message' => 'Weight cannot be 0.', 'data' => []]);

            }
        }
        
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
