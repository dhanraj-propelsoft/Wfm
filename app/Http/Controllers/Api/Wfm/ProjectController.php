<?php

namespace App\Http\Controllers\Api\Wfm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WfmProjects;
use App\WfmProjectCategory;
use App\HrmEmployee;
use Session;
use DB;
use App\Helpers\Helper;
use App\Custom;
use App\OrganizationPerson;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;

use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200;

    /* public function __construct()
     {
         $this->middleware('auth:api');
     } */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*//
           $user = Auth::user();
           $apiKey=$user->createToken($user->name)->accessToken;
           $organization_id = request('organization_id');
           $project_category_list=WfmProjectCategory::where('organization_id',$organization_id)->where('status',1)->pluck('project_category_name','id');
         
            $project_category_list->prepend('select','');
       
     //   return view('wfm.project_create', compact('organization_id'));


        $people = WfmProjects::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', $organization_id)->get();

        $message['status'] =  '1';
        $message['people'] =  $people;*/

        return response()->json($message, $this->successStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function SaveProject(Request $request, $id = null)
    {
        //

    /*    $File=$request->file('file')[0];
        $Filename=$File->getClientOriginalName();
        dd($Filename);*/
        $inputs=$request->all();
     //  dd($inputs);
        $project_inputs_json=$inputs['data'];
  

        $project_inputs=json_decode($project_inputs_json,true);

        if ($id) {
            $ProjectInsertID=$id;
            $project_inputs['project_id']=$id;
            $project_inputs['organization_id']=WfmProjects::where('id',$id)->first()->organization_id;
            $ExisitingProject=WfmProjects::findorfail($id);
            $ExisitingProject->project_name = $project_inputs['project_name'];
            $ExisitingProject->project_details = $project_inputs['project_details'];
            $ExisitingProject->deadline_date = date_string($project_inputs['end_date']);
            $ExisitingProject->created_by = $project_inputs['project_owner'];
            $ExisitingProject->project_comments = $project_inputs['project_comments'];
            $ExisitingProject->project_status = $project_inputs['project_status'];
            $ExisitingProject->save();
           // dd((int)$project_inputs['project_status']);
        } else {
            $inputs = $request->all();

            $project_inputs_json=$inputs['data'];


            $project_inputs=json_decode($project_inputs_json,true); //Task Inputs array
          /*  dd( $request->has('file'));*/

            $where_clause = ['organization_id' => $project_inputs['organization_id'], 'project_name' => $project_inputs['project_name']];

           if ($this->CheckIf_exist($tb_name = "wfm_projects", $field_name = "project_name", $where_clause) == true) {

                $err_msg = $this->CheckIf_exist($tb_name = "wfm_projects", $field_name = "project_name", $where_clause, "Project name");

                return response()->json(['status' => 0, 'message' => 'Project Name Already Exists']);
            }
            //    dd(Project_code(request('organization_id')));



            $project = new WfmProjects;
            $project->project_code = Project_code($project_inputs['organization_id']);

            $project->project_name = $project_inputs['project_name'];
            $project->project_details = $project_inputs['project_details'];
            $project->organization_id = $project_inputs['organization_id'];
            $project->deadline_date = date_string($project_inputs['deadline_date']);
            $project->project_category_id = $project_inputs['project_category_id'];
            $project->created_by = $project_inputs['create_by'];
            $project->project_status = 1;
            //  dd($project);
            $project->save();

            $message['status'] = '1';
            $ProjectInsertID = $project->id;

            $project_inputs['project_id']=$project->id;
    

            $message['message'] = 'Project' . config('constants.flash.added');
            $message['latest_projects'] = $this->latest_projects(request('organization_id'), $employee_id = "", $select_fields = "");
            $message['projects'] = WfmProjects::orderBy('id', 'desc')->where('organization_id', request('organization_id'))->pluck("project_name", "id");
            $message['last_added_project'] = WfmProjects::orderBy('id', 'desc')->where('organization_id', request('organization_id'))->take(1)->pluck("project_name", "id");

        }
        
        if ($request->has('file')) {
                   
                $File=$request->file('file')[0];
                $Filename=$File->getClientOriginalName();

                if($Filename!="blob")
                {
                    //  dd($Filename);
                    $files=$request->file('file');
                    custom::attachments($project_inputs,$files,$prefix_id = $ProjectInsertID ,$attachments_type="1",$attachment_prefix="p");
                }


            }
                 $message['status'] = 1;
               $message['message'] = 'Project' . config('constants.flash.added');
        return response()->json($message, $this->successStatus);

    }
    
    public function UserProject($id)
    {
       $organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
    //  dd( $organization_id);
       $projects = WfmProjects::select('project_name')->where('organization_id', $organization_id)->get();
       $message['status'] = 1;
       $message['message'] = 'Project' . config('constants.flash.added');
       $message['data'] =['projects'=>$projects]; 
        return response()->json($message, $this->successStatus);

    }

    public function latest_projects($organization_id, $employee_id = "", $select_fields = "")
    {
        //

        $selects = [DB::raw("COUNT(wfm_tasks.id) as count"), 'wfm_projects.project_name', 'wfm_projects.id'];

        if (isset($select_fields) && $select_fields != "") {
            $selects = array_merge($select_fields, $selects);
        }
        $latest_projects = DB::table('wfm_projects')
            ->select($selects)
            ->orderBy('wfm_projects.id', 'desc')
            ->leftjoin('wfm_tasks', 'wfm_projects.id', '=', 'wfm_tasks.project_id')
            ->groupby('id');
        /*
               if($employee_id!="")
            {

        ->leftjoin('wfm_task_details','wfm_task_details.task_id','=','wfm_tasks.id')
            ->where('wfm_projects.organization_id',$organization_id)
            ->whereNotIn('wfm_task_details.status', [3])
            ->groupby('wfm_projects.project_name');

            }

            $latest_projects =$latest_projects->where('wfm_task_details.assigned_to',$employee_id);*/

        $latest_projects = $latest_projects->take(5)->get();

        // dd($latest_projects);
        return $latest_projects;
    }

    public function GetProject(Request $request, $id = null)
    {

        if (isset($id) && $id != "") {

            $ProjectlistData = \DB::table("wfm_projects")->where('organization_id', $id)->pluck('project_name', 'id');
            $EmployeeListData = HrmEmployee::where('organization_id', $id)->pluck('first_name', 'id');

            //$Priority
            return response()->json(['status' => 1, 'projectlist' => $ProjectlistData, 'employeelist' => $EmployeeListData], $this->successStatus);

        }
    }

    public function GetProjectCategory(Request $request, $id = null)
    {
        // DB::enableQueryLog();

        if (isset($id) && $id != "") {

            $project_categorylist = WfmProjectCategory::where('organization_id', $id)->pluck('project_category_name', 'id');

            return response()->json(['status' => 1, 'project_categorylist' => $project_categorylist], $this->successStatus);

        }
        //dd(DB::getQueryLog());

    }


    public function Save($tb_name, $data)
    {

        $query_insert = DB::table($tb_name)->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /*   public function Check_DuplicateFields($request,$status=null )
       {
         $messages = [];

        // dd($request['phrase']);
         $customAttributes = ['project_name' => 'Project name'];
         $validator = Validator::make($request, [
           'project_name' => 'required|unique:wfm_projects,project_name'
       ],$messages);
         $validator->setAttributeNames($customAttributes);

       //  print_r( $request);exit;

         if ($validator->fails()) {
             if($status==1)
             {

               return Response::json(['result'=>true,'status'=>0,'message'=>$validator->errors()]);
           }else{
               return true;
           }

       }
       }
       function CheckDuplicateFieldByName($tbname,$where_clause_array)
       {
           $where_clause=[];
           foreach($where_clause_array as $column_name => $value)
           {
               $where_clause[$column_name]=$value;
           }
           if(DB::table($tb_name)->where($where_clause)->count() > 0)
           {
               return 1;
           } else{
               return 0;

           }

       }*/

    public function CheckIf_exist($tb_name, $field_name, $where_clause = array(), $return_field = "", $id = "")
    {

        if (isset($where_clause) && $where_clause[$field_name] != "") {
            // dd("test");
            // dd($where_clause);
            $return_status = DB::table($tb_name)->where($where_clause);
            if ($id != "") {
                $return_status = $return_status->whereNotIn('id', [$id]);
            }

            $return_status = $return_status->exists();

            /*  return WfmProjectCategory::where('project_category_name',$Category )->where('organization_id', $organization_id)->exists();*/

            if ($return_status == true) {

                if ($return_field != "") {
                    //dd($action);
                    return $return_field;
                } else {

                    return $return_status;
                }
            } else {

                return $return_status;

            }
        }
    }


}
