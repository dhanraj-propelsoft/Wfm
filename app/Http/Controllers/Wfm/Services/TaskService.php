<?php

namespace App\Http\Controllers\Wfm\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Wfm\Repository\TaskRepository;
use App\Http\Controllers\Wfm\Repository\HrmEmployeeRepository;
use App\Http\Controllers\Organization\OrganizationRepository;
use App\Http\Controllers\Wfm\Repository\ProjectRepository;
use App\Http\Controllers\Wfm\Repository\PriorityRepository;
use App\Http\Controllers\Wfm\Repository\ProjectMasterRepository;
use App\Http\Controllers\Hrm\Repository\EmployeeRepository;
use App\Http\Controllers\Wfm\Model\Project;
use App\Http\Controllers\Wfm\Model\ProjectTask;
use App\Http\Controllers\Wfm\Model\TaskPriority;
use App\Http\Controllers\Wfm\Model\TaskAttachment;
use App\Http\Controllers\Wfm\Model\Task;
use App\Http\Controllers\Wfm\Model\TaskCreator;
use App\Http\Controllers\Wfm\Model\TaskWorkforce;
use App\Http\Controllers\Wfm\Model\TaskFlow;
use App\Http\Controllers\Wfm\Model\Tag;
use App\Http\Controllers\Wfm\Model\TagTask;
use App\Http\Controllers\Wfm\Model\TaskActionVO;
use App\Http\Controllers\Wfm\Model\TaskStatusVO;
use App\Http\Controllers\Wfm\Model\TaskAttachmentVO;
use App\Http\Controllers\Wfm\Model\TaskCreateVo;
use App\Http\Controllers\Wfm\Model\PriorityVO;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\TaskFollowerVo;
use App\Http\Controllers\Wfm\Model\TaskFollower;
use App\Http\Controllers\Wfm\Model\TagVO;
use App\Http\Controllers\Wfm\Model\TaskVO;
use App\Http\Controllers\Wfm\Model\TaskCategory;
use App\Http\Controllers\Wfm\Model\CategoryVO;
use App\HrmEmployee;
use App\HrmEmployeeVO;
use App\Custom;
use Session;
use Auth; 
use DB;
use DateTime;

class TaskService 
{
    /**
     * * To connect Repo **
    */
    
    public function __construct(TaskRepository $repo,OrganizationRepository $orgRepo,HrmEmployeeRepository $hrmRepo,ProjectRepository $projRepo,PriorityRepository $priorityRepo,ProjectMasterRepository $projmastRepo,EmployeeRepository $empRepo)
    {
    
        $this->repo = $repo;
        $this->orgRepo = $orgRepo;
        $this->hrmRepo = $hrmRepo;
        $this->projRepo = $projRepo;
        $this->priorityRepo = $priorityRepo;
        $this->projmastRepo = $projmastRepo;
        $this->empRepo = $empRepo;
      
    }



    public function create($orgId)
    {    
       Log::info('TaskService->create:-Inside ');

       // hard Core Org Id 53
        // $orgId = 53;

        $employeeDatas = $this->hrmRepo->getEmployeeDatasByOrgId($orgId);

        $categoryDatas = $this->projmastRepo->findAll($orgId);

        $priorityDatas = $this->priorityRepo->findAll(); 

        $ProjectDatas = $this->projRepo->findAll($orgId);
        
        $TaskCreateVO = $this->convertToTaskCreateVO($model = false,$id = false,$employeeDatas,$categoryDatas,$priorityDatas,$ProjectDatas);
        
       //  $response = [
       //      'message' => pStatusSuccess(),
       //      'data' =>  $vo
       //  ];      
        Log::info('TaskService->create:-Return '.json_encode($TaskCreateVO));

    
       return [
                'status'=>1,
                'message' => "Task Create data get sucessfully",
                'data' => $TaskCreateVO
              ];
       
   
    }

    public function store($request,$id = false)
    {  
      Log::info('TaskService->store:-Inside ');

      // hard Core org id and user id
      // $request['orgId'] = 53;
      $request['actionId'] = 1;
      $request['toStatusId'] = 1;
      $request['personId'] = HrmEmployee::where('person_id',Auth::user()->person_id)->first()->id;
      $request['pAssignedBy'] = HrmEmployee::where('person_id',$request['pAssignedBy'])->first()->id;
      $request['pAssignedTo'] = HrmEmployee::where('person_id',$request['pAssignedTo'])->first()->id;

      // $person_id = HrmEmployee::where('person_id',Auth::user()->person_id)->first()->id;
      
      $data = $request;
      $rule = $this->validator($data,$id);

      $validators = Validator::make($data, $rule);

        if ($validators->fails()) {

                    return [
                          'status'=>0,
                          'message' => $validators->messages()->first(),
                          'data' => ''
                      ];
        }
      
      // Convert Task Model
      $task_model = $this->convertToTaskModel($data);
     
     // if(!empty($data['pCategoryId'])){
      $task_categoryModel = $this->convertToTaskCategoryModel($data,$id = false);
     // }else{
     //  $task_categoryModel = false;
     // }

     
      
     // if(!empty($data['pProjectId'])){
      $task_projectModel = $this->convertToTaskProjectModel($data,$id = false);
     //  }else{
     //  $task_projectModel = false;
     // }

      $task_prorityModel = $this->convertToTaskProrityModel($data,$id = false);

      $task_CreatorModel = $this->convertToTaskCreatorModel($data,$id = false);

      $task_WorkforcerModel = $this->convertToTaskWorkForcerModel($data,$id = false);

      $task_FlowModel = $this->convertToTaskFlowModel($data,$id = false);

      $taskFollowerModels = false;
      if(!empty($data['pFollower'])){


        $taskFollowerModels = collect($data['pFollower'])->map(function ($follower) use($data) {

          $followerData = $this->empRepo->findById($follower);
          
          $taskFollowerModel = $this->convertToTaskFollowerModel($followerData);
        
            return $taskFollowerModel;
          });

      }

      $taskTagModels = false;
      if(!empty($data['pTag']))
      {
             

        $taskTagModels = collect($data['pTag'])->map(function ($tag) use($data) {

           $TagCheck = $this->repo->checkTagByOrgId($data['orgId'],$tag);
          
              if(!$TagCheck){
                $tag_model = $this->convertToTagModel($tag);

                $TagSave = $this->repo->TagSave($tag_model);

                  if($TagSave['status'] == 'SUCCESS'){
                      $tagId = $TagSave['data']->id;
                  }else{
                    return $TagSave;
                  }

              }else{
                $tagId = $TagCheck->id;
              }
          
                $taskTagModel = $this->convertToTaskTagModel($tagId);
                return $taskTagModel;
          }); 
           

      }
    
      // Save Task 
      $TaskSave = $this->repo->TaskSave($task_model,$task_categoryModel,$task_projectModel,$task_prorityModel,$task_CreatorModel,$task_WorkforcerModel,$task_FlowModel,$taskTagModels,$taskFollowerModels);

      
      if($TaskSave['message'] == "SUCCESS"){
      
          if(!empty($data['pFile']))
          {
            $Return_Data = $this->attachments($data,$data['pFile'],$TaskSave['data']->id,$attachment_prefix="T");
            
          }
         return $TaskSave; 
      }

      Log::info('TaskService->store:-Return ');
    }

    public function validator($data,$id = false)
    {  
        
        // $rule = ['pName' => 'required'];

        // return $rule;

        // hard Core Org Id
        $organization_id = $data['orgId'];

        $rule = [ 'pName' => 'required|unique:'.'.propel_wfm.tasks,name,' . ($id ? "$id" : 'NULL') . ',id,organization_id,' . $organization_id];

        return $rule;
    }

    
    public function attachments($data, $file, $task_id,$attachment_prefix)
    { 
        $files_array = $file;

      
        $org_id = $data['orgId'];

        $public_path = task_attachment_path($org_id, $task_id);
      

        if (!file_exists($public_path)) {
            mkdir(($public_path), 0777, true);
        }
        $dt = new DateTime();
        $TaskAttachmentModel = [];

        foreach ($files_array as $file) {

            $name = $attachment_prefix . "_" . $task_id . "_" . $dt->format('Y-m-d-H-i-s') . "_" . $file->getClientOriginalName();

            $file->move($public_path, $name);

            $data['upload_file'] = $name;
            $data['file_original_name'] = $file->getClientOriginalName();
          
            $TaskAttachmentModel = $this->convertToTaskAttachmentModel($data,$task_id);

            $TaskAttachmentSave = $this->repo->TaskAttachmentSave($TaskAttachmentModel);
            

        }
      
        return response()->json(['status'=>1]);
       
    }
    
    public function findByID_VO($id)
    { 
        Log::info("TaskService->findByID_VO :- Inside " . json_encode($id));

        $model = $this->repo->findById($id);
     

        
        if($model){


          $employeeDatas = $this->hrmRepo->getEmployeeDatasByOrgId($model->organization_id);


          if($model->CategoryTask->category_id != 'null'){
          // Convert Category VO
          $categoryVO = $this->convertToCategoryVO($model->CategoryTask->Category);
          }else{
          $categoryVO = false;
          }
          

          if($model->ProjectTask->project_id != 'null'){
          // Convert ProjectTask VO
          $projectVO = $this->convertToProjectVO($model->ProjectTask->Project);
          }else{
           $projectVO = false;
          }

          //Convert  Task Creator VO
          $taskCreatorVO = $this->convertToTaskCreatorVO($model->TaskCreator->HrmEmployee);

          //Convert  Task Workforcer VO
          $TaskWorkforceVO = $this->convertToTaskWorkforceVO($model->TaskWorkForce->HrmEmployee);

        // Convert Task Priority VO
          $PriorityVO = $this->convertToPriorityVO($model->TaskPriority->Priority);

        // Convert Task Action VO
          $TaskActionVO = $this->convertToTaskActionVO($model->TaskFlow->TaskAction);

          // Convert Task Status VO
          $TaskStatusVO = $this->convertToTaskStatusVO($model->TaskFlow->TaskStatus);

          $taskFollowerVOs = false;
          // convert Task Follower Vo
          if($model->TaskFollower){
            
            $taskFollowerVOs = collect($model->TaskFollower)->map(function ($follower) {
              $taskFollowerVO = $this->convertToTaskFollowerVO($follower->HrmEmployee);
                return $taskFollowerVO;
            });
          }

          
          $taskAttachmentVOs = false;
        // Convert TaskAttachment Vo
          if($model->TaskAttachment){
            
            $taskAttachmentVOs = collect($model->TaskAttachment)->map(function ($attachment) {
                $taskAttachmentVO = $this->convertToTaskAttachmentVO($attachment);
                return $taskAttachmentVO;
            });
          }
         $tasktagVOs = false;

          // Convert TaskTag Vo
          if($model->TaskTag){
            
            $tasktagVOs = collect($model->TaskTag)->map(function ($tasktag) {

                $tasktagVO = $this->convertToTaskTagVO($tasktag->Tag);
                return $tasktagVO;
            });
           
          }

          // Task Status Based on Action Dropdown show
         
          if($model->TaskFlow){

              $ActionListVo = $this->repo->StatusBasedActionByStatusId($model->TaskFlow->task_status_id);
              
               
              
          }


          $taskVO = $this->convertToVO($model,$employeeDatas,$categoryVO,$projectVO,$taskCreatorVO,$TaskWorkforceVO,$PriorityVO,$taskAttachmentVOs,$tasktagVOs,$TaskActionVO,$TaskStatusVO,$taskFollowerVOs,$ActionListVo);

            // dd($taskVO);
          return [
                'status' => pStatusSuccess(),
                'data' => $taskVO
            ];

        }
        else{
          return [
                'status' => pStatusFailed(),
                'error' => 'Task Not Found'
            ];
        }


        Log::info('TaskService->findByID_VO:-Return ');

    }

    public function taskfollowerupdate($request){

       Log::info("TaskService->taskfollowerupdate :- Inside " . json_encode($request));

        $data = (object)$request;

        $model = $this->repo->taskfollowerupdate($data);
       

        // foreach ($data->follower_id as $key => $value) {

        //     $followerorg = $this->repo->findById($data->task_id);


        //     $result = TaskFollower::updateOrCreate([
        //                 'task_id' => $data->task_id,
        //                 'follower_id' => $value
        //             ], [
        //                 'task_id'=>$data->task_id,
        //                 'follower_id'=>$value,
        //                 'organization_id'=>$followerorg->organization_id,
        //                 'created_by' => Auth::user()->id,
        //                 'last_modified_by' => Auth::user()->id,
        //             ]);
              
        // }

        return [
                'message' => pStatusSuccess()
                ];
        


    }
    public function taskUpdate($request)
    {
        
        Log::info("TaskService->taskUpdate :- Inside " . json_encode($request));
        $data = (object)$request;
        
      
        if($data->pOperation == "project"){

            $model = $this->convertToTaskProjectModel($data,$data->pId);

          Log::info("TaskService->taskUpdate :- Inside Project Model" . json_encode($model));

        }elseif($data->pOperation == "prority"){

            $model = $this->convertToTaskProrityModel($data,$data->pId);

          Log::info("TaskService->taskUpdate :- Inside prority Model" . json_encode($model));

        }elseif($data->pOperation == "taskcreator"){

            $model = $this->convertToTaskCreatorModel($data,$data->pId);

          Log::info("TaskService->taskUpdate :- Inside taskcreator Model" . json_encode($model));  

        }elseif($data->pOperation == "taskworkforcer"){

            $model = $this->convertToTaskWorkForcerModel($data,$data->pId);

          Log::info("TaskService->taskUpdate :- Inside taskworkforcer Model" . json_encode($model));
        }
        elseif($data->pOperation == "name" || $data->pOperation == "details" ||$data->pOperation == "end_date"){

            $result = Task::where("id",$data->pId)->update(array($data->pOperation => $data->pValue));
            Log::info("TaskService->taskUpdate :- Inside task Model" . json_encode($result));

            if($result){
           
              return [
                    'status'=>pStatusSuccess(),
                    'data' => $data->pValue
                ];
            }else{
              return [
                    'status'=>pStatusFailed(),
                    'error' => 'Task Table Update Failed.Contact Propel.'
                ];
            }
        }else{
            return [
                    'status'=>pStatusFailed(),
                    'error' => 'Operation Name does not exist.Contact Propel.'
                ];
        }
        
        // Update
        $response = $this->repo->TaskUpdate($model);

        Log::info("TaskService->taskUpdate :- change Task Details" . json_encode($response));

        if($response['status'] == "SUCCESS"){

            return $response;
        }else{
            return [
                'status' => pStatusFailed(),
                'error' => "Task Update Something Wrong.Contact Propel."
            ];
        }
    }

    public function taskActionchg($request){

      Log::info("TaskService->taskActionchg :- Inside " . json_encode($request));
      
      
      $toStatusId = $this->repo->findtoStatusId($request);
      // dd($toStatusId->to_task_status_id);
      $request['toStatusId'] = $toStatusId->to_task_status_id;

      $data = (object)$request;

        
      $task_FlowModel = $this->convertToTaskFlowModel($data,$data->taskId);
      

      $response = $this->repo->taskActionUpdate($task_FlowModel);

      return $response;


    }

    
    public function convertToTaskModel($modelData,$id = false)
    {
       
        $data = (object)$modelData;
       
        if($id)
        {
            

        }
        else
        {
            $model = new Task;
        }
        $model->name = $data->pName;
        $model->details = $data->pDetails;
        $model->start_date = $data->pStartDate;
        $model->end_date = $data->pEndDate;
        $model->organization_id = $data->orgId;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskCategoryModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
        
        $model = new TaskCategory;
        if(!empty($data->pCategoryId)){
            $model->category_id = $data->pCategoryId; 
        }
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskProjectModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
        
        $model = new ProjectTask;
         if(!empty($data->pProjectId)){
        $model->project_id = $data->pProjectId;
        }
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskAttachmentModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
        
        $model = new TaskAttachment;
      
        $model->task_id = $id;
        $model->upload_file = $data->upload_file;
        $model->file_original_name = $data->file_original_name;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskFollowerModel($modelData)
    {
       
        $data = (object)$modelData;
       
         
        $model = new TaskFollower;
      
        
        $model->follower_id = $data->id;
        $model->organization_id = $data->organization_id;
        $model->created_by = Auth::user()->id; 
        $model->last_modified_by = Auth::user()->id; 

        return $model;
    }

    public function convertToTaskProrityModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
       
        $model = new TaskPriority;
      
        $model->task_id = $id;
        $model->priorty_id = $data->pProrityId;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskCreatorModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
       
        $model = new TaskCreator;
      
        $model->task_id = $id;
        $model->creator_id = $data->pAssignedBy;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskWorkForcerModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
       
        $model = new TaskWorkforce;
      
        $model->task_id = $id;
        $model->workforcer_id= $data->pAssignedTo;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTaskFlowModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
       
        $model = new TaskFlow;
        if($id)
        {
          $model->task_id =$id; 
        }
        $model->task_action_id = $data->actionId;
        $model->task_status_id = $data->toStatusId;
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId

        return $model;
    }

    public function convertToTagModel($tag)
    {
       
        // $data = (object)$modelData;
       
       
        $model = new Tag;
        $model->name = $tag;
        $model->organization_id = 53; //Hard Core Organization Id
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId
        // $model->save();   
        return $model;

        
    }

    public function convertToTaskTagModel($tagId)
    {
        $model = new TagTask;
        $model->tag_id = $tagId;
        $model->organization_id = 53; //Hard Core Organization Id
        $model->created_by = Auth::user()->id; //Hard Core Auth->userId
        $model->last_modified_by = Auth::user()->id; //Hard Core Auth->userId
       
        return $model;
    }

    public function convertToTaskCreateVO($model = false,$id = false,$employeeDatas = false,$categoryDatas = false,$priorityDatas = false,$ProjectDatas = false)
    {
       Log::info('TaskService->create:-Inside ');
       $vo = new TaskCreateVo($model,$id);
       $vo->setAssignedbyList($employeeDatas);
       $vo->setAssignedtoList($employeeDatas);
       $vo->setFollowerList($employeeDatas);
       $vo->setCategoryDatas($categoryDatas);
       $vo->setProjectDatas($ProjectDatas);
       $vo->setProirityDatas($priorityDatas);
       Log::info('TaskService->create:-Inside ');  
       
       return $vo;
    }

    //Project VO 
    public function convertToCategoryVO($model)
    {
       Log::info('TaskService->convertToProjectVO:-Inside ');

       $vo = new CategoryVO($model);  
        
       Log::info('TaskService->convertToProjectVO:-Return '. json_encode($vo));

       return $vo;
    }

    //Project VO 
    public function convertToProjectVO($model)
    {
       Log::info('TaskService->convertToProjectVO:-Inside ');

       $vo = new ProjectVo($model);  
        
       Log::info('TaskService->convertToProjectVO:-Return '. json_encode($vo));

       return $vo;
    }

    // Task Creator VO
    public function convertToTaskCreatorVO($model)
    {
       Log::info('TaskService->convertToTaskCreatorVO:-Inside ');

       $vo = new HrmEmployeeVO($model);  
  
       Log::info('TaskService->convertToTaskCreatorVO:-Return '. json_encode($vo));

       return $vo;
    }

    // Task WorkForcer VO
    public function convertToTaskWorkforceVO($model)
    {
       Log::info('TaskService->convertToTaskWorkforceVO:-Inside ');

       $vo = new HrmEmployeeVO($model);  
  
       Log::info('TaskService->convertToTaskWorkforceVO:-Return '. json_encode($vo));

       return $vo;
    }

    // Task Priority VO
    public function convertToPriorityVO($model)
    {
       Log::info('TaskService->convertToPriorityVO:-Inside ');

       $vo = new PriorityVO($model);  
  
       Log::info('TaskService->convertToPriorityVO:-Return '. json_encode($vo));

       return $vo;
    }

    // Task Attachment VO
    public function convertToTaskAttachmentVO($model)
    {
       Log::info('TaskService->convertToTaskAttachmentVO:-Inside ');

       $vo = new TaskAttachmentVO($model);  
  
       Log::info('TaskService->convertToTaskAttachmentVO:-Return '. json_encode($vo));

       return $vo;
    }

    // Task Tag VO
    public function convertToTaskTagVO($model)
    {
       Log::info('TaskService->convertToTaskTagVO:-Inside ');

       $vo = new TagVO($model);  
  
       Log::info('TaskService->convertToTaskTagVO:-Return '. json_encode($vo));

       return $vo;
    }

    public function convertToTaskActionVO($model)
    {
       Log::info('TaskService->convertToTaskActionVO:-Inside ');

       $vo = new TaskActionVO($model);  
  
       Log::info('TaskService->convertToTaskActionVO:-Return '. json_encode($vo));

       return $vo;
    }

    public function convertToTaskStatusVO($model)
    {
       Log::info('TaskService->convertToTaskStatusVO:-Inside ');

       $vo = new TaskStatusVO($model);  
  
       Log::info('TaskService->convertToTaskStatusVO:-Return '. json_encode($vo));

       return $vo;
    }

    public function convertToTaskFollowerVO($model)
    {
       Log::info('TaskService->convertToTaskWorkforceVO:-Inside ');

       $vo = new HrmEmployeeVO($model);  
  
       Log::info('TaskService->convertToTaskWorkforceVO:-Return '. json_encode($vo));

       return $vo;
    }

    public function convertToVO($model,$employees,$categoryVO,$projectVO,$taskCreatorVO,$TaskWorkforceVO,$PriorityVO,$taskAttachmentVOs,$tasktagVOs,$taskActionVO,$TaskStatusVO,$taskFollowerVOs,$ActionListVo)
    {
        Log::info("TaskService->convertToVO :- Inside");

        $vo = new TaskVO($model);
        $vo->setEmployessVO($employees);
        $vo->setTaskCategoryVO($categoryVO);
        $vo->setTaskProjectVO($projectVO);
        $vo->setTaskCreatorVO($taskCreatorVO);
        $vo->setTaskWorkforceVO($TaskWorkforceVO);
        $vo->setTaskPriorityVO($PriorityVO);
        $vo->setTaskActionVO($taskActionVO);
        $vo->setTaskStatusVO($TaskStatusVO);
        $vo->setTaskFollowerVO($taskFollowerVOs);
        $vo->setTaskActionListVO($ActionListVo);
        if($taskAttachmentVOs){
            $vo->setTaskAttachmentVO($taskAttachmentVOs);
        }
        if($tasktagVOs){
           $vo->setTaskTagVO($tasktagVOs);
        }
        Log::info("TaskService->convertToVO :- Return" . json_encode($vo));

        return $vo;
    }

}
