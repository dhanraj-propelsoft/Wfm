<?php

namespace App\Http\Controllers\Api\Wfm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WfmProjects;
use App\WfmProjectCategory;
use App\HrmEmployee;
use App\WfmFollower;
use App\WfmPriority;
use Session;
use DB;
use App\Helpers\Helper;
use App\WfmComments;
use App\WfmSize;
use App\WfmCommentCount;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Custom;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     function updateOrCreateData($tb_name,array $attributes, array $values = array())
     {
              $instance=DB::table($tb_name)->where($attributes);
              if($instance->count() != 0) {
                $instance->update($values);
                $result=0;
              } else {
             $InsertDefalut["created_at"] =  \Carbon\Carbon::now(); # \Datetime()
             $InsertDefalut["updated_at"] =\Carbon\Carbon::now();;
             $values=array_merge($values,$InsertDefalut);
             $instance = DB::table($tb_name)->Insert($values);
             $result=1;
           }
       if(isset($attributes) ){

     //   return DB::table($tb_name)->where($attributes)->get()->first()->id;
     // dd();
        }
    //  return DB::getPdo()->lastInsertId();
      // dd($instance);
    }
function updateActivityLog(Request $request){
  $request=request()->all();
 // dd($request);
  $org_id =$request["organization_id"];
  $data_type=$request["data_type"];
  $url=$request["url"];
  $subject=$request["subject"];
  $action=$request["action"];
  $user=$request["user"];

  $person_id=Auth::user()->person_id;
  
  $user_name=GetEmployeeName($org_id,$person_id);
  if($user!="")
  {
    $user_name=$user;
  }
  //dd($user_name);
  $logs=LogActivity::updateActivityLog($data_type,$url,$org_id,$user_name,$subject,$action);
  return response()->json(['status'=>1,'log_data'=>$logs,$this->successStatus]);

}
      public function Addfollowers(Request $request)
    {
        $request=request()->all();
        $follower_list_Array=$request['followers'];
        $employeeName=[];
        $FollowerId=[];
        foreach ($follower_list_Array as $key => $follower_employee_id) {
        $follower=new WfmFollower;
        $follower->follower_id = $follower_employee_id;
        $follower->task_id = $request['task_id'];
        $follower->project_id = $request['project_id'];
        $follower->organization_id = $request['organization_id'];
        $follower->save();
        $follower_id=$follower->id;
        $employeeName[$follower_employee_id]=GetEmployeeNameById($follower_employee_id);
         $FollowerIdArray[$follower_employee_id]=$follower_id;
          # code...
        }
       
        $message['status']= 1;
        $message['followers']= $employeeName;
        $message['followers_id']=$FollowerIdArray;
        $message['message']= "Followers Added Successfully";


        // Add default store comments in add task

        $loggined_Employee_ID = GetEmployeeData($request['organization_id'],Auth::user()->person_id);
        
        $loggined_Employee_Name = GetEmployeeNameById($loggined_Employee_ID);

        $comments ='<i>'.$loggined_Employee_Name.'<span style="color:black;"> Added</span>  <i>'.implode(",",$employeeName).'</i> <span style=
        "color:black;">as a Follower to the Task</span>';
       

        app('App\Http\Controllers\Wfm\DashboardController')->addComments($comments,$loggined_Employee_Name,$loggined_Employee_ID,$request['task_id']);

        $data = app('App\Http\Controllers\Wfm\DashboardController')->getCommentByTask(["task_id" => $request['task_id']],$loggined_Employee_ID);


// Count unread message Start
       $admin = WfmCommentCount::where('task_id',$request['task_id'])->where('hrm_employees_id','!=',$loggined_Employee_ID)->get();

        for($i=0; $i<count($admin);$i++){
          $old_count = $admin[$i]->comment_count;
          $admin[$i]->comment_count =  $old_count+1;
          $admin[$i]->save();
       }

       for($i=0; $i<count($follower_list_Array);$i++){
        $counts = New WfmCommentCount;
        $counts->hrm_employees_id = $follower_list_Array[$i];
        $counts->task_id = $request['task_id'];
        $counts->comment_count = 1;
        $counts->save();
       }

       $logined_count = WfmCommentCount::where('task_id',$request['task_id'])->where('hrm_employees_id','=',$loggined_Employee_ID)->first();

       $logined_count->comment_count = 0;
       $logined_count->save();
    
// Count unread message End******

        $message['task_comments']= $data;


       return response()->json($message, $this->successStatus);
    }

    public  function GetEmployeeName($organization_id,$id,$id_type)
    {
      $EmployeeName=HrmEmployee::select("hrm_employees.first_name")->where('organization_id',$organization_id)->where($id_type,$id);

      if($EmployeeName->exists()==true)
      {
        return $EmployeeName->first()->full_name;
      }else{
        return 0;

      }
 
 }


  public function UpdateTask(Request $request,$id=null)
    {
        $request=request()->all();
     //   dd($request);
       if($id)
       {
            /*if(isset($InserData['end_date']) && $InserData['end_date']!="")
            {

           $InserData['end_date']=date_string($InserData['end_date']);
           }*/
            
            /*START INSERT OR UPDATE DATA in WFM_TASKS table*/
            $TaskData['end_date']= date_string($request['end_date']);
            $TaskData['task_details']= $request['task_details'];
      
          
            $where_clause_task=['id'=>$id];
            $return_data=$this->updateOrCreateData($tb_name="wfm_tasks",$where_clause_task, $TaskData);
            /*END INSERT OR UPDATE DATA in WFM_TASKS table*/



            /*START INSERT OR UPDATE DATA in WFM_TASK_DETAILS table*/
            $TaskDetailsData['assigned_to']= $request['assigned_to'];
       
            $where_clause_task_details=['task_id'=>$id];
            
            $this->updateOrCreateData($tb_name="wfm_task_details",$where_clause_task_details, $TaskDetailsData);
            /*START INSERT OR UPDATE DATA in WFM_TASK_DETAILS table*/
            


            $message['status']=1;
           // $message['data']=array_values($InserData)[0];
            $message['message']='Task'.config('constants.flash.updated');;
            return response()->json($message, $this->successStatus);
            
        
       }
   }

    public function SaveProject(Request $request,$id=null)
    {
        //

       // dd($request->all());
        if($id)
        {


        }else{
          $Inputs=$request->all();
             $where_clause=['organization_id'=>request('organization_id'),'project_name'=>request('project_name')];
        if($this->CheckIf_exist($tb_name="wfm_projects",$field_name="project_name",$where_clause)==true){
            $err_msg=$this->CheckIf_exist($tb_name="wfm_projects",$field_name="project_name",$where_clause,"Project name");
                 return response()->json(['status' => 0, 'message' =>'Project Name Already Exists']);
        }
           // dd($Inputs);
            $project=new WfmProjects;
            $project->project_name=request('project_name');
            $project->project_details=request('project_details');
            $project->organization_id=request('organization_id');
            $project->deadline_date=date_string(request('deadline_date'));
            $project->project_category_id=request('project_category_id');
            $project->created_by=1 ;
            $project->save();

            $message['status'] =  '1';
            $ProjectInsertID= $project->id;


            if ($request->has('attachments')) {


                $org_id=request('organization_id');
                $pro_id=$ProjectInsertID;

                if (!file_exists(public_path('attachment/org_'. $org_id.'/pro_'. $pro_id))) {
                    mkdir(public_path('attachment/org_'. $org_id.'/pro_'. $pro_id), 0777, true);
                }


                $file_array=request('attachments');
                $file_type_array=request('attachments_type');

                for( $i=0; $i < count( $file_array); $i++) {
                    $fileName=time().$i.".".$file_type_array[$i];
              
                    $upload_file = base64_decode($file_array[$i]);
                    $fileName_path=public_path().'/attachment/org_'.$org_id.'/pro_'. $pro_id.'/'.$fileName;
                    $success =  file_put_contents($fileName_path,$upload_file);

                    $data=array();
                    $data['attach_type']=1;
                    $data['attach_id']=$ProjectInsertID;

                    $data['upload_file']='/attachment/org_'.$org_id.'/pro_'. $pro_id.'/'.$fileName;
                    $data['file_suffix']=$file_type_array[$i];

                    $data['created_by']=Auth::user()->person_id;
                     $data["created_at"] =  \Carbon\Carbon::now(); # \Datetime()
                    $data["updated_at"] =\Carbon\Carbon::now();

                    $this->Save($tb_name="wfm_attachments",$data);


        }
    }


    $message['message'] = 'Project'.config('constants.flash.added');
    $message['latest_projects'] =  $this->latest_projects(request('organization_id'),$employee_id="",$select_fields="");
    $message['projects'] =  WfmProjects::orderBy('id', 'desc')->where('organization_id',request('organization_id'))->pluck("project_name","id");
    $message['last_added_project'] =  WfmProjects::orderBy('id', 'desc')->where('organization_id',request('organization_id'))->take(1)->pluck("project_name","id");

}



return response()->json($message, $this->successStatus);

}
public function latest_projects($organization_id,$employee_id="",$select_fields="")
    {
        //

      $selects=[DB::raw("COUNT(wfm_tasks.id) as count"),'wfm_projects.project_name','wfm_projects.id'];

      if(isset($select_fields) && $select_fields!="")
      {
       $selects=array_merge($select_fields, $selects);
     }
     $latest_projects = DB::table('wfm_projects')
     ->select( $selects)
     ->orderBy('wfm_projects.id', 'desc')
    ->leftjoin('wfm_tasks','wfm_projects.id','=','wfm_tasks.project_id')
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

     $latest_projects= $latest_projects->take(5)->get();

   // dd($latest_projects);
     return $latest_projects;
   }
  public function DashBoard($id,$project_id=null)
  { 
    
  $organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
  
   if($project_id!="All")
   {
    $chart_datas = WfmProjects::select('wfm_task_details.assigned_to', 'hrm_employees.first_name', DB::raw("COUNT(wfm_tasks.id) as total_tasks"))->leftjoin('wfm_tasks', 'wfm_tasks.project_id', '=', 'wfm_projects.id')->leftjoin('wfm_task_details', 'wfm_task_details.task_id', '=', 'wfm_tasks.id')->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'wfm_task_details.assigned_to')->where('project_id', '=', $project_id)->where('wfm_projects.organization_id', $organization_id)->groupby('wfm_task_details.assigned_to')->get();
    
   }else{
    $chart_datas = WfmProjects::select('wfm_task_details.assigned_to', 'hrm_employees.first_name', DB::raw("COUNT(wfm_tasks.id) as total_tasks"))->leftjoin('wfm_tasks', 'wfm_tasks.project_id', '=', 'wfm_projects.id')->leftjoin('wfm_task_details', 'wfm_task_details.task_id', '=', 'wfm_tasks.id')->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'wfm_task_details.assigned_to')->where('wfm_projects.organization_id', $organization_id)->groupby('wfm_task_details.assigned_to')->get();
        

   }
   $chart = [];
   foreach ($chart_datas as $value) {
       $chart[] = ["label"=>$value->first_name, "value"=>(int)$value->total_tasks];

   }
   $projects = WfmProjects::select('project_name','id')->where('organization_id', $organization_id)->get();
  
   $chart_Color=["#3366CC","#DC3912","#FF9900","#109618","#990099","#3B3EAC","#0099C6","#DD4477","#66AA00","#B82E2E","#316395","#994499","#22AA99","#AAAA11","#6633CC","#E67300","#8B0707","#329262","#5574A6","#3B3EAC"];
  // $k = ;
  // dataSets: [{
  //   values: [{value: 45, label: 'Sandwiches'},
  //     {value: 21, label: 'Salads'},
  //     {value: 15, label: 'Soup'},
  //     {value: 9, label: 'Beverages'},
  //     {value: 15, label: 'Desserts'}],
  //   label: 'Pie dataset',
  //   config: {
  //     colors: [processColor('#C0FF8C'), processColor('#FFF78C'), processColor('#FFD08C'), processColor('#8CEAFF'), processColor('#FF8C9D')],
  //     valueTextSize: 14,
  //     valueTextColor: processColor('white'),
  //     sliceSpace: 5,
  //     selectionShift: 13,
  //     // xValuePosition: "OUTSIDE_SLICE",
  //     // yValuePosition: "OUTSIDE_SLICE",
  //     valueFormatter: "#.#'%'",
  //     valueLineColor: processColor('white'),
  //     valueLinePart1Length: 0.5
  //   }
  // }]
  $chartColor=[];
 foreach ( $chart_datas as $datas ) {
  $chartColor[]=$chart_Color[array_rand($chart_Color)];
 } 
 
  $chartData=["values"=>$chart,"label"=>"Issues By Members","colors"=>$chartColor];
  
 //  $v = $chart_Color[array_rand($chart_Color)];
   $return_data['status']=1;
   $return_data['data']=['pie_chart_data'=>$chartData,'projects'=>$projects];

   return response()->json($return_data,$this->successStatus);
     
  }

  public function getAddTaskData($id)
  {
    $organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
    $Projects = WfmProjects::select("project_name","id")->where('wfm_projects.organization_id', $organization_id)->get();
    $Sizes = WfmSize::select('id','size_name')->where('organization_id', $organization_id)->orderBy('id','desc')->get();
    $Priority = WfmPriority::select('id', 'priority_name')->orderBy('id','desc')->get();
    $EmployeeList= HrmEmployee::select('first_name','id')->where('organization_id',$organization_id)->get();
    $emp_id=GetEmployeeData($organization_id,$id);
    $return_data['status']=1;
    $return_data['data']=['Projects'=>$Projects,'Sizes'=>$Sizes,'Priority'=>$Priority,'Emp_id'=>$emp_id,'Employee_List'=>$EmployeeList];
 
 
    return response()->json($return_data,$this->successStatus);
  }


 public function GetProject(Request $request,$id=null)
 { 
 
    if(isset($id) && $id!="")
    {
   
    $ProjectlistData=  \DB::table("wfm_projects")->where('organization_id',$id)->pluck('project_name','id');
    $EmployeeListData= HrmEmployee::where('organization_id',$id)->pluck('first_name','id');

    //$Priority
      return response()->json(['status'=>1,'projectlist'=>$ProjectlistData,'employeelist'=>$EmployeeListData], $this->successStatus);
        
    }
 }

 public function GetProjectCategory(Request $request,$id=null)
 {  
   // DB::enableQueryLog();
    
    if(isset($id) && $id!="")
    {
 
     $project_categorylist=WfmProjectCategory::where('organization_id',$id)->pluck('project_category_name','id');
  
      return response()->json(['status'=>1,'project_categorylist'=>$project_categorylist], $this->successStatus);
  
 }
   //dd(DB::getQueryLog());
   
 }


 public function Save($tb_name,$data)
     {

        $query_insert = DB::table($tb_name)->insert($data);
        return DB::getPdo()->lastInsertId();
    }


  public function  Add_comments(Request $request)
  {
    $request= $request->all();

 
    $employee_id=GetEmployeeData($request['organization_id'],Auth::user()->person_id);

    $where_clause['id']="0";
    $InsertData=[];
    $New_Comments=New WfmComments;
    $New_Comments->comments=$request['comments'];
    $New_Comments->commenter_name=GetEmployeeNameById($employee_id);
    $New_Comments->task_id=$request['task_id'];
    $New_Comments->created_by=$employee_id;
    $New_Comments->last_modified_by=$employee_id;
    $New_Comments->save();

     
     $data = app('App\Http\Controllers\Wfm\DashboardController')->getCommentByTask(["task_id" => $New_Comments->task_id],$employee_id);

// Comments Unread
        $unread_count = app('App\Http\Controllers\Wfm\DashboardController')->add_comments_count($New_Comments->task_id,$employee_id);
// Comments Unread End***

  
     return response()->json(['status'=>1,'commenter_name'=>$New_Comments->commenter_name,'comments'=>$New_Comments->comments,'updated_at'=>$New_Comments->updated_at,'task_comments'=>$data,'message'=>'Commet'.config('constants.flash.added')], $this->successStatus);
  
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
    public function delete_follower(Request $request,$id)
    {
        $Follower=WfmFollower::where('id',$id);
        $org_id = $Follower->first()->organization_id;

        $follower_id = $Follower->first()->follower_id;

        $task_id = $Follower->first()->task_id;

        if($Follower)
        {
            $IsDelete=WfmFollower::destroy('id',$id);
            //dd($IsDelete);
          if($IsDelete)
          {

// / Add default store comments in add task
        $loggined_Employee_ID = GetEmployeeData($org_id,Auth::user()->person_id);

         $follower_name = GetEmployeeNameById($follower_id);
         
      $loggined_Employee_Name = GetEmployeeNameById($loggined_Employee_ID);

        $comments = '<i>'. $loggined_Employee_Name. '</i> <span style="color:black;"> has Removed </span style="color:black;"><i>.'. $follower_name. '</i> <span style="color:black;"> from the Task</span>';  

       app('App\Http\Controllers\Wfm\DashboardController')->addComments($comments,$loggined_Employee_Name,$loggined_Employee_ID,$task_id);

       $data = app('App\Http\Controllers\Wfm\DashboardController')->getCommentByTask(["task_id" => $task_id],$loggined_Employee_ID);

// Comments Unread
       $delete_follower = WfmCommentCount::where('hrm_employees_id',$follower_id)->delete();


       $admin = WfmCommentCount::where('task_id',$task_id)->where('hrm_employees_id','!=',$loggined_Employee_ID)->get();

        for($i=0; $i<count($admin);$i++){
          $old_count = $admin[$i]->comment_count;
          $admin[$i]->comment_count =  $old_count+1;
          $admin[$i]->save();
        }
        
    $logined_count = WfmCommentCount::where('task_id',$task_id)->where('hrm_employees_id','=',$loggined_Employee_ID)->first();

       $logined_count->comment_count = 0;
       $logined_count->save();
    


          return response()->json(['status'=>1,'task_comments'=>$data],$this->successStatus);
      }

        }
    }

         public function deleteComment(Request $request,$id)
            {
        $Comment=WfmComments::where('id',$id);
       

        if($Comment)
        {
            $IsDelete=WfmComments::destroy('id',$id);
            //dd($IsDelete);
          if($IsDelete)
          {

            return response()->json(['status'=>1,'message'=>"Comments Deleted Successfully"],$this->successStatus);
          }
          }
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

   public function CheckIf_exist($tb_name,$field_name,$where_clause=array(),$return_field="",$id="")
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

    







}
