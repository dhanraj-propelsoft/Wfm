<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\ProjectMasterRepositoryInterface;
use App\Http\Controllers\Wfm\Model\Category;
use Illuminate\Support\Facades\Log;
use Session;
use DB;


class ProjectMasterRepository implements ProjectMasterRepositoryInterface
{
  


    public function findAll($orgId)
    {   

       Log::info('ProjectMasterRepository->findAll:-Inside ');
        $result =  Category::where('organization_id',$orgId)->get();
       Log::info('ProjectMasterRepository->findAll:-Return '. json_encode($result));
       return $result;
        
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function findById($id)
    {
        Log::info('ProjectMasterRepository->findById:-Inside ');

        $result =  Category::where('id', $id)->first();
       

        Log::info('ProjectMasterRepository->findById:-Return '. json_encode($result));

       return $result;
              
    }
 
    public function save(Category $model, $id = false)
    {            

        try {
            if($id == false){
            $message = "Category ".pAddMessage();
            }else{
            $message ="Category ". pUpdateMessage();
            }

            $result = DB::transaction(function () use ($model,$message) {
                $model->save();

                // return [
                //     'message'=>$message,
                //     'data' => $model
                // ];
                return ['status' => 1, 
                        'message' => $message, 
                        'data' =>$model
                       ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            
            return ['status' => 0, 
                    'message' => "Category Does not Save.Contact Propel Admin", 
                    'data' =>""
                    ];
        }
    }

    
   
    // public function destroyById(WfmDepartment $model)
    // {
        
    //     try {
            
    //         $result = DB::transaction(function () use ($model) {
    //             $model->delete();

    //             return [
    //                 'message'=>"Department ".pDestroyMessage(),
    //                 'data' =>""
    //             ];
    //         });
           
    //         return $result;
    //     } catch (\Exception $e) { 
    //         return [
    //             'message' => "Some Thing Went Wrong!!",
    //             'data' => $e
    //         ];
    //     }

    // }



    public function statusChangeById($request)
    {    
        Log::info('ProjectMasterRepository->statusChangeById:-Inside ');
        $result = Category::findOrFail($request['id']);
        $result->status = $request['status'];
        $result->save();
        Log::info('
            :-Return '. json_encode($result));

       return $result;
    
    }

    public function CategorySelectAll($request)
    {    
         Log::info('ProjectMasterRepository->statusChangeById:-Inside '.json_encode($request->id));


        $status = $request->status;

        $result = collect($request->id)->map(function ($model) use ($status) {
           
                $update = Category::findOrFail($model['pId']);
                $update->status = $status;
                $update->save();

                return $update;
            
        });
        
        
        Log::info('ProjectMasterRepository:-Return '. json_encode($result));

       return $result;
    
    }

    // public function CheckIf_exist($array=array())
    // {
    //      $organization_id = Session::get('organization_id');
    //     if( isset($array) && $array['wfm_department']!="" )
    //     {
           
    //         $Category=trim($array['wfm_department']);
            
    //         return WfmDepartment::where('wfm_department',$Category )->where('organization_id', $organization_id)->exists();
          
    //     }
    // }
}
