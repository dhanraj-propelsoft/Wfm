<?php

namespace App\Http\Controllers\Api\Wfm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WfmProjects;
use App\WfmTasks;
use App\WfmTaskDetails;
use App\WfmAttachments;
use App\WfmTag;
use App\WfmTasktag;
use App\HrmEmployee;
use Session;
use DB;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;
use App\Custom;
use DateTime;
use Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200; 

  /*  public function construct()
    {
      $object = new stdClass;
    $object->foo = 'bar';

    var_dump($object)
    }*/
    public function __construct()
    {
       // $ReturnData = new \stdClass();;
      

       
    } 


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


        $people = wfm_projects::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', $organization_id)->get();

        $message['status'] =  '1';
        $message['people'] =  $people;*/

        return response()->json($message, $this->successStatus);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SaveData(Request $request,$id=null)
    {  
        // dd($request->all());
      $tags_data = $request['data'];
      /*$mobile_no="9159948995,9629846836";
      $msg=Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'),$mobile_no, "hii");
      dd($msg);*/
    //
      
      $inputs=$request->all();
    // return response()->json(['status'=>1,'data'=>$inputs]);
      
      $Filename=$request->file('file')[0];
          
  
        $inputs=$request->all();
       
        $task_inputs_json=$inputs['data'];
  

        $task_inputs=json_decode($task_inputs_json,true); //Task Inputs array
        
        $EmployeeName= GetEmployeeNameById($task_inputs['assigned_to']);

      
        $where_clause=['organization_id'=>$task_inputs['organization_id'],'project_id'=>$task_inputs['project_id'],'task_name'=>$task_inputs['task_name']];
      if($this->Check_TableFields($tb_name="wfm_tasks",$field_name="task_name",$where_clause)==true){
        $err_msg=$this->Check_TableFields($tb_name="wfm_tasks",$field_name="task_name",$where_clause,"Task name");
        return response()->json(['status' => 0, 'message' =>'Task Name Already Exists']);
      }

      $fileName_array=[];
      $fileExtension_array=[];


      $request['task_name']=$task_inputs['task_name'];
      $request['task_details']=$task_inputs['task_details'];
    /*  $request['task_type']=$task_inputs['task_type'];*/
      $request['task_type']=1;
      $request['priority_id']=$task_inputs['priority_id'];
      $request['project_id']=$task_inputs['project_id'];
      $request['organization_id']=$task_inputs['organization_id'];
      $request['create_date']=$task_inputs['create_date'];
      $request['end_date']=$task_inputs['end_date'];
      $request['size_id']=$task_inputs['size_id'];
      $request['worth_id']=$task_inputs['worth_id'];
      $request['status']=1;

      $TaskDetailsRequest['assigned_by']=$task_inputs['assigned_by'];
      $TaskDetailsRequest['assigned_to']=$task_inputs['assigned_to'];
      /**/


      /**/
      $is_assigned_myself=0;
      if($TaskDetailsRequest['assigned_by']==$TaskDetailsRequest['assigned_to'])
      {
        $is_assigned_myself=1;
      }
      $TaskDetailsRequest['is_assigned_myself']=$is_assigned_myself;
   /*   $TaskDetailsRequest['comment']=$task_inputs['comment'];*/
      $TaskDetailsRequest['Status']=1;
    // dd( $TaskDetailsRequest);
    /*  if($id)
      {
        $TaskDetailsRequest['task_id']=$this->SaveTask($request,$id);

        $TaskDetailsInsertID=$this->SaveTaskDetails($TaskDetailsRequest,$id);

      }else{*/

      $TaskDetailsRequest['task_id']=$this->SaveTask($request);
     //   dd();

      $TaskDetailsInsertID=$this->SaveTaskDetails($TaskDetailsRequest);

      $inputs['task_id'] = $TaskDetailsRequest['task_id'];
     
   
      $TaskInsertID=$TaskDetailsRequest['task_id'];
     // $this->SaveTaskProgress($TaskDetailsInsertID,$is_assigned_myself);
      $assigned_to =$task_inputs['assigned_to'];
      $assigned_by =$task_inputs['assigned_by'];

       //dd($assigned_to);
      $project_id=$task_inputs['project_id'];
      $task_title=$task_inputs['task_name'];

      $assigned_to_name=$task_inputs['assigned_to_name'];
      $created_by_name=$task_inputs['assigned_by_name'];
      $task_due_date=$task_inputs['end_date'];

      $message = strtoupper($task_title)." assigned to ".$assigned_to_name." by ".$created_by_name. " to finish by ".$task_due_date;
      
      
      $mobile=HrmEmployee::select('hrm_employees.phone_no')->leftjoin('wfm_projects','hrm_employees.id','=','wfm_projects.created_by')->where('hrm_employees.id',$task_inputs['assigned_to'])->Orwhere('hrm_employees.id',$task_inputs['assigned_by'])->OrWhere('wfm_projects.id',$project_id)->get();
        // dd($mobile);
        /* foreach ($mobile as $value) {
          $msg= Custom::send_transms(config('constants.sms.user'), config('constants.sms.pass'), config('constants.sms.sender'), $value->phone_no, $message);
        }*/
        /*activity log*/
        $logs=LogActivity::addToLog($request['organization_id'],$request['project_id'],$TaskInsertID,$EmployeeName,$subject="task",$action="create");
        /*activity log*/

        if (!empty(request('file'))) {
       /*   $org_id=$task_inputs['organization_id'];
          $pro_id=$task_inputs['project_id'];

          if (!file_exists(public_path('attachment/org_'. $org_id.'/pro_'. $pro_id))) {
            mkdir(public_path('attachment/org_'. $org_id.'/pro_'. $pro_id), 0777, true);
          }

          $file_array=request('attachments');
          $file_type_array=request('attachments_type');

          for( $i=0; $i < count( $file_array); $i++) {
        

            $data=array();
            $data['attach_type']=2;
            $data['attach_id']=$TaskInsertID;

            $data['upload_file']= "T".$task_id."_".$fileName."_".$dt->format('Y-m-d-H-i-s');
            $data['file_origional_name']=$fileName;
            $data['project_id']=$project_id;
            $data['organization_id']=$org_id;

            $data['created_by']=Auth::user()->person_id;
            $data["created_at"] =  \Carbon\Carbon::now(); # \Datetime()
            $data["updated_at"] =\Carbon\Carbon::now();

            $this->Save($tb_name="wfm_attachments",$data);


          }*/
          $File=$request->file('file')[0];
          $Filename=$File->getClientOriginalName();
         
         if($Filename!="blob")
         {

          $this->Task_attachments($inputs);
         }


        }
        if(!empty($task_inputs['tags']))
        {

          $this->SaveTag($request,$id=null,$TaskInsertID);
        }
      
  //Task Activity Log

      //  $logs=LogActivity::addToLog("test");
  //Task Activity Log
 // dd($logs);

      /*  $append_data_array=array_merge($TaskDetailsRequest,$request);
        $append_data=$this->ReturnTaskDataFields($append_data_array,$request['return_fields']);
        dd();*/
  /*
        $message['status1'] =  1;
        $message['message'] = 'Task '.config('constants.flash.added');*/
       // $message['data'] = $append_data;
   /*     $message['org_id'] = $request['organization_id'];
        $message['pro_id'] = $request['project_id'];
        dd($message);*/
     //   return response()->json($message, $this->successStatus);
    //   $TaskDetailsRequest['assigned_to']=GetEmployeeData

        $loggined_Employee_ID = GetEmployeeData($task_inputs['organization_id'],Auth::user()->person_id);

        if($TaskDetailsRequest['assigned_to']== $loggined_Employee_ID)
        {
          $IsUserTask=1;
          $UserEm_Id=$TaskDetailsRequest['assigned_to'];
          $UserProject_id=$task_inputs['project_id'];
         // $UserProject=GetProjectNameById($UserProject_id);
        //  $Name_User=GetEmployeeNameById($TaskDetailsRequest['assigned_to']);
        }else{
          $IsUserTask=0;
          $UserProject_id="";
          $UserProject="";
          $UserEm_Id="";
        }
          $UserProject="";
          $Name_User='';

  // Add default store comments in add task
           $priority_index = WfmTasks::leftjoin('wfm_priorities','wfm_priorities.id','=','wfm_tasks.priority_id')
           ->where('wfm_tasks.id',$inputs['task_id'])
           ->first()->priority_name;
           
           $project_name = WfmTasks::leftjoin('wfm_projects','wfm_projects.id','=','wfm_tasks.project_id')
           ->where('wfm_tasks.id',$inputs['task_id'])
           ->first()->project_name;

          $assigned_by_name = GetEmployeeNameById($assigned_by);
          $assigned_to_name = GetEmployeeNameById($assigned_to);
          $loggined_Employee_Name = GetEmployeeNameById($loggined_Employee_ID);

          $comments = '<i style="font-weight:bold;">'.$assigned_by_name.'</i> has created a New Task <i>"'.$task_title .'"</i> Assigned to <i>'.$assigned_to_name.'</i> With <i>'.$priority_index.'</i> Priority under <i>'.$project_name.'</i>';


         
       app('App\Http\Controllers\Wfm\DashboardController')->addComments($comments,$loggined_Employee_Name,$loggined_Employee_ID,$inputs['task_id']);



    return response()->json(['status'=>1,'UserTask'=>$IsUserTask,'UserEm_Id'=>$UserEm_Id,'Name_User'=> $Name_User? GetEmployeeNameById($TaskDetailsRequest['assigned_to']): "",'UserProject'=>$UserProject? GetProjectNameById($UserProject_id): "",'Project_id'=>$UserProject_id,'message' => 'Task '.config('constants.flash.added')], $this->successStatus);
      }


      public function Save($tb_name,$data)
      {

        $query_insert = DB::table($tb_name)->insert($data);
        return DB::getPdo()->lastInsertId();
      }
      public function getTaskDetailsByConditions($where_clause_array,$selects="")
      {
        $selects_array=["priority_id","task_name","","first_name","create_date","end_date","","size_id",""];
        if( isset($selects) && count($selects)>0)
        {

          $selects_array=array_merge($selects_array,$selects);
        }


        if( isset($where_clause_array) && $where_clause_array['wfm_task.id']!="")
        {
          $query=WfmTasks::select($selects_array)
          ->Join('wfm_task_details', 'wfm_task_details.task_id', '=', 'wfm_tasks.id')
          ->where($where_clause_array);
          return $query->get();
        }



      }
      public function Project_TasksByUser($where_clause,$select_fields="")
      {
    //DB::connection()->enableQueryLog();
        if(isset($select_fields) && $select_fields!="")
        {

        }else{
          $select_fields=[

            'hrm_employees.first_name',
            'wfm_projects.project_name',
            'wfm_tasks.id',
            'wfm_tasks.task_name',
            'wfm_tasks.task_details',
            'wfm_tasks.priority_id',
            'wfm_tasks.create_date',
            'wfm_tasks.end_date',
            'wfm_tasks.size_id',
            'wfm_tasks.worth_id',
            'wfm_task_details.status',
            'wfm_tasks.status as task_status'
          ];
        }
        if(isset($where_clause['employee_id']) && $where_clause['employee_id']!="" && isset($where_clause['project_id']) && $where_clause['project_id']!="" && isset($where_clause['organization_id']) && $where_clause['organization_id']!="")
        {

          $user_id=$where_clause['employee_id'];
          $Return_result=WfmTasks::orderBy('wfm_tasks.priority_id', 'desc')
          ->Join('wfm_task_details', 'wfm_tasks.id', '=', 'wfm_task_details.task_id')
          ->Join('wfm_projects', 'wfm_projects.id', '=', 'wfm_tasks.project_id')
          ->Join('hrm_employees','hrm_employees.id','=','wfm_tasks.created_by')

          ->where(function($query) use ($user_id) {
            /** @var $query Illuminate\Database\Query\Builder  */
            return $query->Where('wfm_task_details.assigned_to',$user_id)
            ->Where('wfm_task_details.assigned_by',$user_id)

            ->OrWhere('wfm_task_details.assigned_to',$user_id);
          })
          ->whereNotIn('wfm_task_details.status', [3])
          ->where('wfm_tasks.project_id',  $where_clause['project_id'])
          ->where('wfm_projects.organization_id',$where_clause['organization_id']);
          $Return_result=$Return_result->get();

          return  $Return_result;
        }else{
    //$queries = DB::getQueryLog(); dd($queries);dd($Return_result);
          return "Where condition fields are missing";
        }
      }
      function SaveTaskProgress($TaskDetailsID,$is_assigned_myself)
      {

       $SaveData=array();
       $SaveData['task_details_id']=$TaskDetailsID;
       $SaveData['date']=\Carbon\Carbon::now();
       $SaveData['action_id']=1;
       $SaveData['progress_id']=1;
       $SaveData['status_id']=1;
       $SaveData['is_assigned_myself']=$is_assigned_myself;
       $SaveData['created_by']=Auth::user()->person_id;
       $SaveData["created_at"] =  \Carbon\Carbon::now(); # \Datetime()
       $SaveData["updated_at"] =\Carbon\Carbon::now();

       return  $this->Save('wfm_task_progresses', $SaveData);
     }

     function SaveTask($request=array(),$id=null)
     {

      if($id)
      {

        $TaskData=WfmTasks::findOrFail($id);
        $TaskData->task_name=$request['task_name'];
        $TaskData->task_details=$request['task_details'];
        $TaskData->task_type=$request['task_type'];
        $TaskData->priority_id=$request['priority_id'];
        $TaskData->project_id=$request['project_id'];
        $TaskData->organization_id=$request['organization_id'];
        $TaskData->create_date=$request['create_date'];
        $TaskData->end_date=$request['end_date'];
        $TaskData->size_id=$request['size_id'];
        $TaskData->worth_id=$request['worth_id'];
        $TaskData->status=$request['status'];
        $TaskData->save();

        return $TaskData->id;
      }else{

        $NewTask=new WfmTasks;
       // dd(Task_code($request['organization_id'],$request['project_id']));
        $NewTask->task_code=Task_code($request['organization_id'],$request['project_id']);
        $NewTask->task_name=$request['task_name'];
        $NewTask->task_details=$request['task_details'];
        $NewTask->task_type=$request['task_type'];
        $NewTask->priority_id=$request['priority_id'];
        $NewTask->project_id=$request['project_id'];
        $NewTask->organization_id=$request['organization_id'];

        $NewTask->create_date=date_string($request['create_date']);
        $NewTask->end_date=date_string($request['end_date']);
        $NewTask->size_id=$request['size_id'];
        $NewTask->worth_id=$request['worth_id'];
        $NewTask->status=$request['status'];
   
        $NewTask->created_by=GetEmployeeData($request['organization_id'],Auth::user()->person_id) ;
        $NewTask->save();
//dd($NewTask->id);

        return $NewTask->id;
      }


    }
    public function GetEmployeeDetailsByPersonID($id,$return_singleData=false,$return_column_name="",$where_clause="")
    {
      $clauses=['created_by'=>$id];
      if(isset($where_clause) && $where_clause!="")
      {

        $clauses=array_merge($clauses, $where_clause);
      }

      $Query=HrmEmployee::where($clauses)->get();
      if($return_singleData==true)
      {
        $Query=$Query->first()->$return_column_name;
      }
        //dd($Query->first()->$return_column_name);
      return $Query;

    }

    function SaveTaskDetails($request=array(),$id=null)
    {

      if($id)
      {
//`task_id`, `assigned_by`, `assigned_to`, `comment`, `Status`
        $TaskDetailsData=WfmTaskDetails::findOrFail($id);
        /*$TaskDetailsData->task_id=$request['task_id'];
        $TaskDetailsData->assigned_by=$request['assigned_by'];
        $TaskDetailsData->assigned_to=$request['assigned_to'];
        $TaskDetailsData->comment=$request['comment'];
        $TaskDetailsData->Status=$request['Status'];*/
        foreach ($request as $key => $value) {
          $NewTaskDetailsData->$key=$value;
        }

        $TaskDetailsData->save();

        return $TaskData->id;
      }else{


        $NewTaskDetailsData=new WfmTaskDetails;
       /* $NewTaskDetailsData->task_id=$request['task_id'];
        $NewTaskDetailsData->assigned_by=$request['assigned_by'];
        $NewTaskDetailsData->assigned_to=$request['assigned_to'];
        $NewTaskDetailsData->comment=$request['comment'];
        $NewTaskDetailsData->Status=$request['Status'];*/
        foreach ($request as $key => $value) {
          $NewTaskDetailsData->$key=$value;
        }

        $NewTaskDetailsData->save();

        return $NewTaskDetailsData->id;
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

    public function Task_attachments($inputs)
    {
     // dd($inputs);
      $files_array = $inputs['file'];
      
      $task_inputs_json=$inputs['data'];
      
      $task_inputs=json_decode($task_inputs_json,true);
      
      $id=$inputs['task_id'];
  

      $Return_Data=$this->attachments($task_inputs,$files_array,$id,$attachments_type="2",$attachment_prefix="T");

       return response()->json(['status'=>1,$Return_Data]);
    }
    public function activity_log_attachments(Request $request)
    {

      $inputs=$request->all();
      $id=$inputs['task_id'];
      $files_array = $inputs['file'];
      
      $Return_Data=$this->attachments($inputs,$files_array,$id,$attachments_type="3",$attachment_prefix="C_T");

       return response()->json(['status'=>1,$Return_Data]);

    }


    /**
     * @param $inputs
     * @param $file
     * @param $id
     * @param $attachments_type
     * @param $attachment_prefix
     * @return array
     */
    public function attachments($inputs, $file, $id, $attachments_type, $attachment_prefix)
    {
        $files_array = $file;

        // dd($files_array);
        //Task Inputs array

        $task_id = $id;
        $org_id = $inputs['organization_id'];
        $project_id = $inputs['project_id'];
        $public_path = comment_attachment_path($org_id, $project_id);

        if (!file_exists($public_path)) {
            mkdir(($public_path), 0777, true);
        }
        $dt = new DateTime();
        $return_data = [];
        $attachment_path = [];

        foreach ($files_array as $file) {
            $name = $attachment_prefix . "_" . $task_id . "_" . $dt->format('Y-m-d-H-i-s') . "_" . $file->getClientOriginalName();

            $file->move($public_path, $name);


            $data['attach_type'] = $attachments_type;
            $data['attach_id'] = $task_id;

            $data['upload_file'] = $name;
            $data['file_original_name'] = $file->getClientOriginalName();
            $data['project_id'] = $project_id;
            $data['organization_id'] = $org_id;

            $data['file_suffix'] = "";

            $data['created_by'] = Auth::user()->person_id;
            $data["created_at"] = \Carbon\Carbon::now(); # \Datetime()
            $data["updated_at"] = \Carbon\Carbon::now();

            $attachment_id = $this->Save($tb_name = "wfm_attachments", $data);
            $return_data[$attachment_id] = $data['file_original_name'];
            $attachment_path[$attachment_id] = json_encode("org_" . $data['organization_id'] . '/pro_' . $data['project_id'] . '/' . $name);

            // $request->file('file')->move($public_path, $name);
            //       return

        }
        return array('uploaded_files' => $return_data, 'attachment_path' => $attachment_path);
        //

    }

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
    /*public function Upload(Request $request) {

        $file = $request->file('file');
        $id = $request->input('id');

        $business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
       // $business_name = Business::findOrFail($business_id)->business_name;

        $path_array = explode('/', 'organizations/'.$business_name.'/items');

        $public_path = '';

        foreach ($path_array as $p) {
            $public_path .= $p."/";
            if (!file_exists(public_path($public_path))) {
                mkdir(public_path($public_path), 0777, true);
            }
        }

        $name = $id.".jpg";

        $request->file('file')->move(public_path($public_path), $name);

        return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.updated'),'data'=>['id' => $id, 'path' => URL::to('/').'/public/organizations/'.$business_name.'/items/'.$name]]);
      }*/

      function SaveTag($request,$id=null,$task_id=null)
      { 
        $allData = $request->all();
        // $tag_data = $allData['data'];
        $result = json_decode($allData['data'], true);
        $TagsArray = $result['tags'];
         // dd($context);
        

        // $TagsArray=request('tags');
       
       
        foreach ( $TagsArray as $key => $tag) {
            # code...
          $where_clause_array=['organization_id'=>$request['organization_id'],'tag_name'=>$tag];
          $tag_id=$this->getIdByFieldName($tb_name='wfm_tags',$where_clause_array);
        //dd($where_clause_array);
          if($tag_id==0)
          {
           $tag_id=$this->InserTag($tag,$request);
         }

         $this->InserTasktag( $request,$tag_id,$task_id);
       }
     }

     function ReturnTaskDataFields($request,$return_field=array())
     {
      $tr_data="";
      //  echo"<pre>";print_r($request);echo"</pre>";exit;
      $tr_data.="<tr class='popUp get_detailsbar' data-id='".$request['task_id']."' data-org-id='".$request['organization_id']."' id='task_id_".$request['task_id']."' data-pro-id='".$request['project_id']."' data-activity-log='/org_".$request['organization_id']."/pro_".$request['project_id']."/task_".$request['task_id']."'   data-action-id='2'>";
      foreach($return_field as $key=>$field)
      {
        if($field=="priority_id")
        {

          $tr_data.="<td data-sort='".$request[$field]."'>".priority($request[$field])."</td>";
        }elseif(isset($request[$field]))
        {
          $tr_data.="<td >".$request[$field]."</td>";

        }elseif($field=="create_date"||$field=="end_date"){
          $tr_data.="<td >".date_($request[$field])."</td>";
//GetTaskStatus
        }elseif($field=="task_status"){
          $tr_data.="<td >".GetTaskStatus($request[$field])."</td>";
//GetTaskStatus
        }
        elseif($field=="status"){
          $tr_data.="<td >".GetTaskAction($request[$field])."</td>";
//GetTaskStatus
        }else{
         $tr_data.="<td ></td>";
       }
     }
        //echo 
     $tr_data.="</tr>";
     return $tr_data;
   }


   function getIdByFieldName($tb_name,$where_clause_array)
   {
    $query = DB::table($tb_name)->where($where_clause_array)->first();
    if($query)
    {

      return $query->id;
    }else{
      return 0;
    }
  }
  function InserTag($tag_name, $request)
  {
 //dd( $request['organization_id']);
    $tag=new WfmTag;
    $tag->tag_name=$tag_name;
    $tag->organization_id=$request['organization_id'];
    $tag->person_id=$request['person_id'];
    $tag->last_modified_by=$request['person_id'];
    $tag->created_by=GetEmployeeData($request['organization_id'],Auth::user()->person_id) ;;
           // dd($tag);
    $tag->save();   
    return $tag->id;
  }
  function InserTasktag( $request,$tag_id,$task_id)
  {
    $Tasktag=new WfmTasktag;
    $Tasktag->tag_id=$tag_id;
    $Tasktag->project_id=$request['project_id'];
    $Tasktag->task_id=$task_id;
    $Tasktag->Last_modified_by=GetEmployeeData($request['organization_id'],Auth::user()->person_id);
    $Tasktag->created_by=GetEmployeeData($request['organization_id'],Auth::user()->person_id);
    $Tasktag->save(); 
    return   $Tasktag->id;
  }

  function ActvityLog(Request $request)
  {

    $file = $request->file('file');
    $id = $request->input('id');
    $fileName=$file->getClientOriginalName();;
    $file_ext=explode(".",$fileName)[1];
    $business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
    $business_name = Business::findOrFail($business_id)->business_name;

    $path_array = explode('/', 'attachment/org_'.Session::get('organization_id').'/items');

    $public_path = '';

    foreach ($path_array as $p) {
      $public_path .= $p."/";
      if (!file_exists(public_path($public_path))) {
        mkdir(public_path($public_path), 0777, true);
      }
    }

        //$name = Carbon::now()->toDateTimeString();
    $name=time().".".$file_ext;

    $request->file('file')->move(public_path($public_path), $name);

    return response()->json(['status'=>1, 'file_origional_name'=>$fileName,'data'=>['id' => $id, 'path' => URL::to('/').'/public/attachment/'.Session::get('organization_id').'/items/'.$name]]);


  }

  public function Check_TableFields($tb_name,$field_name,$where_clause=array(),$return_field="",$id="")
  {

    if( isset($where_clause) && $where_clause[$field_name]!="" )
    {
      // dd("test");    
          // dd($where_clause);
     $return_status=DB::table($tb_name)->where($where_clause);
     if($id!="")
     {
      $return_status=$return_status->whereNotIn('id',[$id]);
    }

    $return_status=$return_status->exists();

    /*  return WfmProjectCategory::where('project_category_name',$Category )->where('organization_id', $organization_id)->exists();*/

    if($return_status==true)
    {

      if($return_field!="")
      {
                    //dd($action);
        return $return_field;
      }else{

        return $return_status; 
      }
    }else{

      return $return_status; 

    }
  }
}

 public function delete_attachment($id)
    {
        $Attachment=WfmAttachments::where('id',$id);
       
            $AttachmentData=GetAttachmentById(['id'=>$id])[0];
    //  dd();
        // $AttachmentPath=
        if($Attachment)
        {
            $IsDeleted=WfmAttachments::destroy('id',$id);
            //dd($IsDelete);
          if($IsDeleted)
          {
            if(file_exists(public_path('/attachment/'.$AttachmentData->file_wpath))){
              unlink(public_path('/attachment/'.$AttachmentData->file_wpath));
            //  dd("success");
                  }else{

            }
            return response()->json(['status'=>1],$this->successStatus);
          }
          }
        }
        

}
