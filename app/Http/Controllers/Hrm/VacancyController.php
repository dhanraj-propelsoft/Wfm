<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmDesignation;
use App\HrmVacancyStatus;
use App\HrmEmployee;
use App\HrmVacancy;
use Carbon\carbon;
use App\HrmTeam;
use App\Custom;
use Validator;
use Session;
use DB;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $organization_id=session::get('organization_id');

        $vacancies=HrmVacancy::select('hrm_vacancies.id','hrm_designations.name as designation_name','no_of_vacancies','create_update_date','hrm_vacancies.status','hrm_vacancies.no_of_positions')
        ->leftjoin('hrm_designations','hrm_designations.id','=','hrm_vacancies.designation_id')
        ->orderby('hrm_designations.name')
        ->where('hrm_vacancies.organization_id',$organization_id)->get();

         /*$status=HrmVacancyStatus::pluck('name','id');
        $status->prepend('Choose a status','');*/
       
        return view('hrm.hrm_vacancy',compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id=session::get('organization_id');

        $designations=HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
        $designations->prepend('Choose Designation','');

        $teams=HrmTeam::where('organization_id',$organization_id)->pluck('name','id');
        $teams->prepend('Choose Teams','');

        $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');
        $employee->prepend('Choose Employee','');

       

       return view('hrm.hrm_vacancy_create',compact('designations','teams','employee'));
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
        $this->validate($request,[
            'designations'=>'required',
            'vacancies'=>'required'
        ]);
        $organization_id=Session::get('organization_id');

        $vacancies = new HrmVacancy;
        $vacancies->designation_id = $request->input('designations');
        $vacancies->no_of_positions = $request->input('positions');

        $vacancies->no_of_vacancies = $request->input('vacancies');
        $vacancies->team_id = $request->input('team');
        $vacancies->employee_id = $request->input('employee_id');
        $vacancies->notes = $request->input('notes');
        if($request->input('created_at') != null)
        {
        $vacancies->create_update_date=($request->input('created_at') != null)? carbon::parse($request->input('created_at'))->format('Y-m-d'): null;
        }
        $vacancies->organization_id=$organization_id;
        $vacancies->save();


        Custom::userby($vacancies, true);
        $designations=HrmDesignation::findorfail($vacancies->designation_id)->name;
        $teams=HrmTeam::findorfail($vacancies->team_id)->name;
        $employees=HrmEmployee::findorfail($vacancies->employee_id)->name;
      
        return response()->json(['status'=>0, 'message' => 'vacancy'.config('constants.flash.added'), 'data'=> ['id'=> $vacancies->id, 'designation'=>$designations, 'no_of_vacancy'=>$vacancies->no_of_vacancies , 'notes'=>$vacancies->notes, 'create_date'=>$vacancies->create_update_date,'status'=>$vacancies->status,'team' => $teams ,'employee' => $employees ,'no_of_position' => $vacancies->no_of_positions ]]);
    }
    public function vacancy_status(Request $request)
    {
        $vacancy_status=HrmVacancy::where('id',$request->input('id'))
        ->update(['status'=>$request->input('status')]);
        return response()->json(['status'=>$request->input('status')]);
    }

    public function get_positions(Request $request)
    {

        $no_of_positions=HrmDesignation::select('id','positions')->where('id',$request->input('id'))->first();

        return response()->json(['positions' => $no_of_positions]);

    }

    public function vacancy_status_search(Request $request)
    {
            //dd($request->all());
            $status_id=$request->input('id');
            //dd($status_id);
                
            $status=HrmVacancy::select('hrm_vacancies.id','hrm_designations.name as designation_name','no_of_vacancies','create_update_date','hrm_vacancies.status','hrm_vacancies.no_of_positions')->leftjoin('hrm_designations','hrm_designations.id','=','hrm_vacancies.designation_id')->where('hrm_vacancies.status','LIKE',$status_id)->get();
            //dd($status);
            if(count($status)>0)
            {
                return response()->json(['status' =>$status]);
            }
            else
            {
                $message="No search result ";
                return response()->json(['message' =>$message]);

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
        $organization_id=session::get('organization_id');

        $vacancy=HrmVacancy::where('id',$id)->where('organization_id',$organization_id)->first();

        $designations=HrmDesignation::where('organization_id',$organization_id)
        ->pluck('name','id');
        $designations->prepend('Choose Designation','');

        $teams=HrmTeam::where('organization_id',$organization_id)->pluck('name','id');
        $teams->prepend('Choose Teams','');

        $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');
        $employee->prepend('Choose Employee','');

       return view('hrm.hrm_vacancy_edit',compact('vacancy','designations','teams','employee'));
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

         //dd($request->id);

        $this->validate($request,[
            'designations'=>'required',
            'vacancies'=>'required'
        ]);
        $organization_id=session::get('organization_id');
        
        $vacancies = HrmVacancy::findOrFail($request->input('id'));

        $vacancies->designation_id = $request->input('designations');
        $vacancies->no_of_positions = $request->input('positions');
        $vacancies->no_of_vacancies = $request->input('vacancies');
        $vacancies->team_id = $request->input('team');
        $vacancies->employee_id = $request->input('employee_id');
        $vacancies->notes = $request->input('notes');
        if($request->input('created_at') != null)
        {
        $vacancies->create_update_date=($request->input('created_at') != null)? carbon::parse($request->input('created_at'))->format('Y-m-d'): null;
        }
        $vacancies->organization_id=$organization_id;
        $vacancies->save();


        Custom::userby($vacancies, true);
        $designations=HrmDesignation::findorfail($vacancies->designation_id)->name;
        $teams=HrmTeam::findorfail($vacancies->team_id)->name;
        $employees=HrmEmployee::findorfail($vacancies->employee_id)->name;
      
        return response()->json(['status'=>0, 'message' => 'vacancy'.config('constants.flash.added'), 'data'=> ['id'=> $vacancies->id, 'designation'=> $designations, 'no_of_vacancy'=> $vacancies->no_of_vacancies, 'notes'=> $vacancies->notes, 'create_date'=> $vacancies->create_update_date,'status'=> $vacancies->status ,'team' => $teams ,'employee' => $employees , 'positions' => $vacancies->no_of_positions]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vacancy=HrmVacancy::where('id',$request->id)->delete();

        return response()->json(['status'=>1 ,'message' => 'vacancy'.config('constants.flash.deleted'),'data'=>[]]);
    }
}
