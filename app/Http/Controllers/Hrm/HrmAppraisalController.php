<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployeeWorkingPeriod;
use App\HrmEmployee;
use App\HrmDesignation;
use App\HrmAppraisalKpi;
use App\HrmAppraisal;
use Carbon\Carbon;
use App\Custom;
use Session;
use Auth;
use DB;


class HrmAppraisalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id=session::get('organization_id');

        $appraisals=HrmAppraisal::select('hrm_appraisals.id','hrm_appraisals.appraisal_year','hrm_employees.first_name as name','hrm_employee_addresses.mobile_no','hrm_appraisals.status')
        ->leftjoin('hrm_employees','hrm_employees.id','=','hrm_appraisals.employee_id')
        ->leftjoin('hrm_employee_addresses','hrm_employee_addresses.employee_id','=','hrm_employees.id')
        ->where('hrm_appraisals.organization_id',$organization_id)
        ->get();
        return view('hrm.appraisal',compact('appraisals'));
    }
    
     public function appraisal_status(Request $request)
    {
        $vacancy_status=HrmAppraisal::where('id',$request->input('id'))
        ->update(['status'=>$request->input('status')]);
        return response()->json(['status'=>$request->input('status')]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id=session::get('organization_id');

        $employees=HrmEmployee::select('hrm_employees.id','first_name','hrm_employee_working_periods.joined_date as joined_date','hrm_designations.name as designation_name')
        ->where('hrm_employees.organization_id',$organization_id)
        ->leftjoin('hrm_employee_working_periods','hrm_employee_working_periods.employee_id','=','hrm_employees.id')
        ->leftjoin('hrm_employee_designation','hrm_employee_designation.employee_id','=','hrm_employees.id')
        ->leftjoin('hrm_designations','hrm_designations.id','=','hrm_employee_designation.designation_id')
        ->get();
      // dd($employees);
        
        return view('hrm.appraisal_create',compact('employees'));
    }

    public function appraisal_random()
    {

        $organization_id=session::get('organization_id');

        $employee_name=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');
        $employee_name->prepend('Choose a name','');

        $appraisals_kpis=HrmAppraisalKpi::select('hrm_appraisal_kpis.id','hrm_appraisal_kpis.name','hrm_appraisal_kpis.weight')->where('organization_id',$organization_id)->get();

        $designation=HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
        $designation->prepend('Choose a designation','');

        return view('hrm.appraisal_random_create',compact('employee_name','appraisals_kpis','designation'));
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
                $organization_id=session::get('organization_id');
                $appraisal_year=$request->input('appraisal_year');
                // dd($appraisal_year);
                $id = $request->input('id');
                $user=Auth::user()->id;

                for ( $i=0;$i<count($id);$i++)

               {
                    DB::table('hrm_appraisals')->insert([
                            'employee_id' => $id[$i],
                            'appraisal_year' => $appraisal_year,
                            'created_by' => $user,
                            'last_modified_by' => $user,
                            'organization_id'=> $organization_id
                        ]);
                  
                   
                }
       $appraisals=HrmAppraisal::select('hrm_appraisals.id','hrm_employees.first_name as name','hrm_appraisals.appraisal_year')
        ->leftjoin('hrm_employees','hrm_employees.id','=','hrm_appraisals.employee_id')
        ->where('hrm_appraisals.organization_id',$organization_id)
        ->where('hrm_appraisals.employee_id',$id)
        ->first();

              
        return response()->json(['status' => '1','message' => 'Appraisal'.config('constants.flash.added'), 'data' => ['name' => $appraisals->name, 'appraisal_year' => $appraisals->appraisal_year]]);

    }
    public function initiate_store(Request $request)
    {
        //dd($request->all());
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
        //
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
