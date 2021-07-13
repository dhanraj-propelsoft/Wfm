<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\DashboardRepositoryInterface;
use App\Http\Controllers\Wfm\Model\Task;
use Illuminate\Support\Facades\Log;
use App\HrmEmployee;
use Auth;
use Session;
use DB;


class DashboardRepository 
{
  



    public function findAllTaskListUserId($orgId)
    {   


        $loginId = Auth::user()->person_id;

        $HrmEmployeeId = HrmEmployee::where('person_id',$loginId)->first()->id;
        Log::info('DashboardService->findAll:-Inside '.json_encode($orgId));
        
         $qu = Task::select('propel_wfm.tasks.id')
                 ->leftjoin('propel_wfm.task_creators', 'propel_wfm.task_creators.task_id', '=', 'propel_wfm.tasks.id')
                ->leftjoin('propel_wfm.task_workforces', 'propel_wfm.task_workforces.task_id', '=', 'propel_wfm.tasks.id')
                ->leftjoin('propel_wfm.task_followers', 'propel_wfm.task_followers.task_id', '=', 'propel_wfm.tasks.id')
                ->leftjoin('propel_wfm.project_tasks', 'propel_wfm.project_tasks.task_id', '=', 'propel_wfm.tasks.id')
                ->leftjoin('propel_wfm.projects', 'propel_wfm.projects.id', '=', 'propel_wfm.project_tasks.project_id')
                ->leftjoin('propel_wfm.task_categories', 'propel_wfm.task_categories.task_id', '=', 'propel_wfm.tasks.id')
                ->leftjoin('propel_wfm.categories', 'propel_wfm.categories.id', '=', 'propel_wfm.task_categories.category_id')
                ->where('propel_wfm.tasks.organization_id',$orgId)
                ->where(function($q) use($HrmEmployeeId) {
                        $q->where('propel_wfm.task_creators.creator_id',$HrmEmployeeId)
                        ->orwhere('propel_wfm.task_workforces.workforcer_id',$HrmEmployeeId)
                        ->orwhere('propel_wfm.task_followers.follower_id',$HrmEmployeeId);
                })
                ->where(function($q) {
                        $q->whereNull('propel_wfm.project_tasks.project_id')
                            ->orwhere('propel_wfm.projects.status',1);
                })
                ->where(function($q) {
                        $q->whereNull('propel_wfm.task_categories.category_id')
                            ->orwhere('propel_wfm.categories.status',1);
                });
                $query = $qu->get();
             
                
        return $query;
       
    }


    public function findAllTaskCreatorByUserId($orgId,$personId,$cat,$proj)
    {   
        Log::info('DashboardService->findAll:-Inside '.json_encode($personId));
        
        $q = Task::with(['CategoryTask.Category','ProjectTask.Project','LogindTaskCreator.HrmEmployee'])
           ->has('LogindTaskCreator');
            $q->where('organization_id',$orgId);
        // $query = $q->get();
        $query = [];
             
        
        return $query;
       
    }

    public function findAllTaskWorkforcerByUserId($orgId,$personId,$cat,$proj)
    {   

        
        $q = Task::with(['CategoryTask.Category','ProjectTask.Project','LogindTaskWorkforcer.HrmEmployee'])
            ->has('LogindTaskWorkforcer');   
            $q->where('organization_id',$orgId);
            // $query = $q->get();
        $query = [];
        return $query;
       
    }

    public function findAllTaskFollowersByUserId($orgId,$personId,$cat,$proj)
    {   

        $q = Task::with(['CategoryTask.Category','ProjectTask.Project','LoginedTaskFollower.HrmEmployee'])
            ->has('LoginedTaskFollower');
            $q->where('organization_id',$orgId);
            // $query = $q->get();
        $query = [];
        return $query;
       
    }

   
}
