<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmRecruitmentStatus;
use App\PaymentMethod;
use App\HrmDesignation;
use App\HrmCandidate;
use App\HrmEmployee;
use App\HrmVacancy;
use Carbon\carbon;
use App\Custom;
use App\Term;
use App\People;
use Validator;
use Session;
use DB;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id=session::get('organization_id');

        $candidates=HrmCandidate::select('hrm_candidates.id','hrm_candidates.name','hrm_candidates.education','hrm_candidates.designation_id','hrm_candidates.contact_number','hrm_candidates.recruitment_status','hrm_candidates.applied_on','hrm_candidates.skill_set_1')->where('organization_id',$organization_id)->get();
        //dd($candidates);
        $recruitment_statuses=HrmRecruitmentStatus::pluck('recruitment_status','id');
        $recruitment_statuses->prepend('Choose Status','');

        return view('hrm.hrm_candidate',compact('candidates','recruitment_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $organization_id=session::get('organization_id');
        
        $designations=HrmDesignation::where('hrm_designations.organization_id',$organization_id)->leftjoin('hrm_vacancies','hrm_vacancies.designation_id','=','hrm_designations.id')->pluck('name','hrm_designations.id');
        $designations->prepend('Choose a Designation','');

         $employees=HrmEmployee::where('organization_id',$organization_id)
         ->pluck('first_name','id');
        $employees->prepend('Choose a Employee','');

        $recruitment_statuses=HrmRecruitmentStatus::pluck('recruitment_status','id');
        $recruitment_statuses->prepend('Choose Status','');

        
        /*$payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
        $terms->prepend('Select Terms','');
$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
        $people->prepend('Select Person', '');      
*/
        return view('hrm.hrm_candidate_create',compact('designations','employees','recruitment_statuses','people','payment','terms'));
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
                'name'=>'required', 
                'max_education'=>'required',
                'designations'=>'required', 
                'phone_number'=>'required',
                'email_id'=>'required' 
        ]);
        $organization_id=Session::get('organization_id');

        $candidate= new HrmCandidate;
        $candidate->name=$request->input('name'); 
        $candidate->education=$request->input('max_education'); 
        $candidate->designation_id=$request->input('designations');
        if($request->input('applied_on') != null)
        {
        $candidate->applied_on=($request->input('applied_on') != null) ? carbon::parse($request->input('applied_on'))->format('Y-m-d'): null;
        } 
        
        $candidate->contact_number=$request->input('phone_number'); 
        $candidate->email=$request->input('email_id'); 
        $candidate->experience=$request->input('experience'); 
        $candidate->skill_set_1=$request->input('skill_set_1'); 
        $candidate->skill_set_2=$request->input('skill_set_2'); 
        $candidate->skill_set_3=$request->input('skill_set_3');

        if($request->input('interview_on_first') != null)
        {
        $candidate->tech_interview_on=($request->input('interview_on_first') != null) ? carbon::parse($request->input('interview_on_first'))->format('Y-m-d'): null;
        }  
        
        $candidate->tech_employee_id=$request->input('interview_by_first'); 
        $candidate->tech_comments=$request->input('comments_first'); 
         if($request->input('interview_on_second') != null)
        {
        $candidate->hr_interview_on=($request->input('interview_on_second') != null) ? carbon::parse($request->input('interview_on_second'))->format('Y-m-d'): null;
        }  
         
        $candidate->hr_employee_id=$request->input('interview_by_second'); 
        $candidate->hr_comments=$request->input('comments_second'); 
        $candidate->recruitment_status=$request->input('status'); 
        if($request->input('last_modified') != null)
        {
        $candidate->last_modified=($request->input('last_modified') != null)? carbon::parse($request->input('last_modified'))->format('Y-m-d'): null;
        }  
        
        $candidate->organization_id=$organization_id;
        $candidate->save();
        Custom::userby($candidate, true);

        return response()->json(['message'=>'candidate'.config('constants.flash.added'), 'data' =>['id'=>  $candidate->id,'name'=> $candidate->name, 'contact' => $candidate->contact_number, 'skill' => $candidate->skill_set_1, 'applied_designation' => $candidate->designation_id,'applied_on' =>$candidate->applied_on ,'status' => $candidate->recruitment_status ]]);
      
    }
    public function candidate_status(Request $request)
    {
        //dd($request->all());
        $candidate_status=HrmCandidate::where('id',$request->input('id'))
        ->update(['recruitment_status'=>$request->input('status')]);
        //dd($candidate_status);
        return response()->json(['status'=>$request->input('status')]);
    }

    public function get_recruitment_status(Request $request)
    {
        //dd($request->all());
        $statuses=HrmCandidate::select('hrm_candidates.id','hrm_candidates.name','contact_number','skill_set_1','hrm_designations.name as designation_name','applied_on','hrm_candidates.recruitment_status')->leftjoin('hrm_designations','hrm_designations.id','=','hrm_candidates.designation_id')->where('hrm_candidates.recruitment_status',$request->input('id'))->get();
        //dd($statuses);
        return response()->json(['status' => $statuses]);

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
        $designations=HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
        $designations->prepend('Choose a Designation','');

         $employees=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');
        $employees->prepend('Choose a Employee','');

        $recruitment_statuses=HrmRecruitmentStatus::pluck('recruitment_status','id');
        $recruitment_statuses->prepend('Choose Status','');

         $candidate=HrmCandidate::where('id',$id)->where('organization_id',$organization_id)->first();

        return view('hrm.hrm_candidate_edit',compact('designations','employees','recruitment_statuses','candidate'));
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
                'name'=>'required', 
                'max_education'=>'required',
                'designations'=>'required', 
                'phone_number'=>'required',
                'email_id'=>'required' 
        ]);
        $organization_id=Session::get('organization_id');


        $candidate = HrmCandidate::findOrFail($request->input('id'));
         $candidate->name=$request->input('name'); 
        $candidate->education=$request->input('max_education'); 
        $candidate->designation_id=$request->input('designations');
        if($request->input('applied_on') != null)
        {
        $candidate->applied_on=($request->input('applied_on') != null)? carbon::parse($request->input('applied_on'))->format('Y-m-d'): null;
        } 
        
        $candidate->contact_number=$request->input('phone_number'); 
        $candidate->email=$request->input('email_id'); 
        $candidate->experience=$request->input('experience'); 
        $candidate->skill_set_1=$request->input('skill_set_1'); 
        $candidate->skill_set_2=$request->input('skill_set_2'); 
        $candidate->skill_set_3=$request->input('skill_set_3');
        if($request->input('interview_on_first') != null)
        {
        $candidate->tech_interview_on=($request->input('interview_on_first') != null)? carbon::parse($request->input('interview_on_first'))->format('Y-m-d'): null;
        }  
        
        $candidate->tech_employee_id=$request->input('interview_by_first'); 
        $candidate->tech_comments=$request->input('comments_first'); 
         if($request->input('interview_on_second') != null)
        {
        $candidate->hr_interview_on=($request->input('interview_on_second') != null)? carbon::parse($request->input('interview_on_second'))->format('Y-m-d'): null;
        }  
         
        $candidate->hr_employee_id=$request->input('interview_by_second'); 
        $candidate->hr_comments=$request->input('comments_second'); 
        $candidate->recruitment_status=$request->input('status'); 
        if($request->input('last_modified') != null)
        {
        $candidate->last_modified=($request->input('last_modified') != null)? carbon::parse($request->input('last_modified'))->format('Y-m-d'): null;
        }  
        
        $candidate->organization_id=$organization_id;
        $candidate->save();
        Custom::userby($candidate, true);

        return response()->json(['message'=>'candidate'.config('constants.flash.added'), 'data' =>['id'=>  $candidate->id,'name'=> $candidate->name, 'contact' => $candidate->contact_number, 'skill' => $candidate->skill_set_1, 'applied_designation' => $candidate->designation_id,'applied_on' =>$candidate->applied_on,'status'=>$candidate->recruitment_status ]]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       $candidate=HrmCandidate::where('id',$request->input('id'))->delete();
        return response()->json(['status'=>1 , 'message' =>'candidate'.config('constants.flash.added'), 'data' =>[]]);
    }
}
