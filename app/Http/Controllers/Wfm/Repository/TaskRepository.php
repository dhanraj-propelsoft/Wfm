<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\TaskRepositoryInterface;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\Task;
use App\Http\Controllers\Wfm\Model\ProjectTask;
use App\Http\Controllers\Wfm\Model\TaskPriority;
use App\Http\Controllers\Wfm\Model\TaskCreator;
use App\Http\Controllers\Wfm\Model\TaskWorkforce;
use App\Http\Controllers\Wfm\Model\TaskFlow;
use App\Http\Controllers\Wfm\Model\Tag;
use App\Http\Controllers\Wfm\Model\TagTask;
use App\Http\Controllers\Wfm\Model\TaskProgress;
use App\Http\Controllers\Wfm\Model\TaskFollower;
use Illuminate\Support\Facades\Log;
use App\Organization;
use App\HrmEmployee;
use Auth;//Get authenticated user data
use Session;
use DB;


class TaskRepository 
{
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkTagByOrgId($orgId,$tag)
    {
        Log::info('TaskRepository->findTaskById:- Inside ' . $orgId.'.'.$tag);
        $where_clause_array = ['organization_id'=>$orgId,'name'=>$tag];

        $query = Tag::where($where_clause_array)->first();

        Log::info('TaskRepository->findTaskById:-  return'.json_encode($query));

        return $query;
    }

    public function findById($id)
    {
        Log::info('TaskRepository->findById:- Inside ' . $id);
        $query = Task::with('ProjectTask.Project','TaskCreator.HrmEmployee','TaskWorkForce.HrmEmployee','TaskPriority.Priority','TaskAttachment','TaskTag.Tag','TaskFlow.TaskAction','TaskFlow.TaskStatus','TaskFollower.HrmEmployee')
                ->where([
                'id' => $id]);

        Log::info('TaskRepository->findById:-  Query - ' . $query->toSql());
        Log::info('TaskRepository->findById:-  QueryBinding ' . json_encode($query->getBindings()));
        Log::info('TaskRepository->findById:-  return');
        return $query->first();
    }

    
    public function StatusBasedActionByStatusId($id)
    {
        Log::info('TaskRepository->StatusBasedActionByStatusId:- Inside ' . $id);
        $query = TaskProgress::with('TaskAction')
        ->where('from_task_status_id',$id);

        Log::info('TaskRepository->StatusBasedActionByStatusId:-  Query - ' . $query->toSql());
        Log::info('TaskRepository->StatusBasedActionByStatusId:-  QueryBinding ' . json_encode($query->getBindings()));
        Log::info('TaskRepository->StatusBasedActionByStatusId:-  return');
        return $query->get();
    }


    public function findtoStatusId($data)
    {
        Log::info('TaskRepository->findtoStatusId:- Inside ');
        
        $query = TaskProgress::where(['from_task_status_id'=>$data['statusId'],'task_action_id'=>$data['actionId']]);

        Log::info('TaskRepository->findtoStatusId:-  Query - ' . $query->toSql());
        Log::info('TaskRepository->findtoStatusId:-  QueryBinding ' . json_encode($query->getBindings()));
        Log::info('TaskRepository->findtoStatusId:-  return');
        return $query->first();
    }

    public function TaskSave($model,$task_categoryModel,$task_projectModel,$task_prorityModel,$task_CreatorModel,$task_WorkforcerModel,$task_FlowModel,$taskTagModels,$taskFollowerModels)
        {    
        Log::info('TaskRepository->TaskSave:- Inside');        

        try {

            $result = DB::transaction(function () use ($model,$task_categoryModel,$task_projectModel,$task_prorityModel,$task_CreatorModel,$task_WorkforcerModel,$task_FlowModel,$taskTagModels,$taskFollowerModels) {

                $model->save();
                if($task_categoryModel){
                $model->CategoryTask()->save($task_categoryModel);
                }
                
                if($task_projectModel){
                $model->ProjectTask()->save($task_projectModel);
                }

                $model->TaskPriority()->save($task_prorityModel);

                $model->TaskCreator()->save($task_CreatorModel);

                $model->TaskWorkForce()->save($task_WorkforcerModel);

                $model->TaskFlow()->save($task_FlowModel);

                if($taskTagModels){
                    $model->TaskTag()->saveMany($taskTagModels);
                }
                if($taskFollowerModels){
                    $model->TaskFollower()->saveMany($taskFollowerModels);
                }

            Log::info('TaskRepository->TaskSave:Success-'.json_encode($model));   
                return [
                    'status'=>1,
                    'message'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 

            Log::info('TaskRepository->TaskSave:Error-'.json_encode($e)); 

            return [
                'status'=>0,
                'message' => $e,
                'data' => ""
            ];
        }
    }

    public function TaskAttachmentSave($model)
    {            

        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'status'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'status' => pStatusFailed(),
                'error' => $e
            ];
        }
    }

    public function taskActionUpdate($model)
    {            

        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'status'=>1,
                    'message'=>"Task Action has been changed",
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'status' => 0,
                'error' => $e
            ];
        }
    }

    public function TagSave($model)
    {            

        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'status'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'status' => pStatusFailed(),
                'error' => $e
            ];
        }
    }

    public function TaskUpdate($model)
    {       
        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();

                return [
                    'status'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'status' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function taskfollowerupdate($data)
    {       
        try {
            $result = DB::transaction(function () use ($data) {
                
                // $org = TaskFollower::where('task_id', $data->task_id)->get();
                // $org->TaskFollower()->where('task_id', $data->task_id)->get()->delete();

                $old_follower = TaskFollower::where('task_id',$data->task_id)->delete();
                foreach ($data->follower_id as $key => $value) {

                    

                    $followerorg = $this->findById($data->task_id);

                    $new_follower = new TaskFollower;
                    $new_follower->task_id = $data->task_id;
                    $new_follower->follower_id = $value;
                    $new_follower->organization_id = $followerorg->organization_id;
                    $new_follower->created_by = Auth::user()->id;
                    $new_follower->last_modified_by = Auth::user()->id;
                    $new_follower->save();
                    
                        // 'created_by' => Auth::user()->id,
                        // 'last_modified_by' => Auth::user()->id,


                }

                return [
                    'status'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'status' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

  

   
}
