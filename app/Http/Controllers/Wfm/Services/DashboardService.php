<?php

namespace App\Http\Controllers\Wfm\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Wfm\Repository\DashboardRepository;
use App\Http\Controllers\Wfm\Services\TaskService;
use App\Http\Controllers\Wfm\Model\WfmDepartmentVo;
use App\Http\Controllers\Wfm\Model\WfmDepartment;
use Illuminate\Support\Collection;
use Session;
use Illuminate\Support\Facades\Validator;
use App\HrmEmployee;
use Auth;
class DashboardService 
{
    /** 
     * * To connect Repo **
     */
    
    public function __construct(DashboardRepository $repo,TaskService $task_service)
    {
        $this->repo = $repo;
        $this->task_service = $task_service;
    }


    public function findAll($organization_id)
    {   
        Log::info('DashboardService->findAll:-Inside ');
        // it is hard core Organization Id
        

        $person_id = HrmEmployee::where('person_id',Auth::user()->person_id)->first()->id;


        Log::info('DashboardService->findAll:-login_id '.json_encode($person_id));


        $loginedUserTaskListId = $this->repo->findAllTaskListUserId($organization_id);



       
        // Log::info('DashboardService->findAll:-task_creators '.json_encode($task_creators));

        
        // $task_workforcers = $this->repo->findAllTaskWorkforcerByUserId($organization_id,$person_id,$cat,$proj);

        // Log::info('DashboardService->findAll:-Task task_workforcers '.json_encode($task_workforcers));

        // $task_followers = $this->repo->findAllTaskFollowersByUserId($organization_id,$person_id,$cat,$proj);

        // Log::info('DashboardService->findAll:-Task Followers '.json_encode($task_followers));


        //  $task_creators_task_id = new Collection();

        //  foreach ($task_creators as $key => $value)
        //  {      $task_creators_task_id->push(
        //             $value->id
        //         );
        //  }
         
        //  $task_workforcer_task_id = new Collection();
        //  foreach ($task_workforcers as $key => $value) 
        //  {     
        //     $task_workforcer_task_id->push(
        //             $value->id
        //         );
        //  }
         
        //  $task_followers_task_id = new Collection();

        //  foreach ($task_followers as $key => $value) 
        //  {     
        //     $task_followers_task_id->push(
        //             $value->id
        //         );
        //  }

        // $loginedUserTaskId = $task_creators_task_id->merge($task_workforcer_task_id)->merge($task_followers_task_id);

        // $loginedUserTaskId = $loginedUserTaskId->unique(function ($item) {

        //            return $item;

        //             });

        // $loginedUserTaskId->all();
        // Log::info('DashboardService->findAll:-Merge all task id '.json_encode($loginedUserTaskId));
        
        $result = collect($loginedUserTaskListId)->map(function ($taskList) {
        
            
            $task = $this->task_service->findByID_VO($taskList->id);

            if($task['status'] == "SUCCESS"){
            
                 return $task['data'];
            }
            
        });

    
            return [
                    'status'=>1,
                    'message' => pStatusSuccess(),
                    'data' =>$result
                ];
        Log::info('DashboardService->findAll:-Return '.json_encode($result));

        
        
       
    }



}
