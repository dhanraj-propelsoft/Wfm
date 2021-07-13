<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\ProjectRepositoryInterface;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\Project;
use App\Organization;
use App\HrmEmployee;
use Illuminate\Support\Facades\Log;
use Auth;//Get authenticated user data
use Session;
use DB;


class ProjectRepository implements ProjectRepositoryInterface
{
  


    public function findAllList($orgId)
    {     
       
        Log::info('ProjectRepository->findAll:-Inside '.json_encode($orgId));


            $result = Project::select('propel_wfm.projects.*')
                    ->leftjoin('propel_wfm.categories', 'propel_wfm.categories.id', '=', 'propel_wfm.projects.category_id')
                    ->where('propel_wfm.projects.organization_id',$orgId)
                    ->where('propel_wfm.projects.status','!=',2)

                    ->where(function($q) {
                        $q->where('propel_wfm.categories.status',1);
                        // if($unassignedcatrgory != "false"){
                            $q->orwhereNull('propel_wfm.projects.category_id');
                        // }
                    })
                    // ->where('propel_wfm.categories.status',1)
                    ->get();
                  
                    
        // $res = Project::with('Category');
                // ->where(function($q) {
                //     $q->whereHas('Category', function($query)  {
                //             $query->where('status', '=', 1); 
                //     });
                    
                // });
                    // ->orwhereNull('category_id');
                // $res->whereHas('Category', function($q)  {
                //     $q->where('status', '=', 1); 
                // })
                
                
                
            
        //    if($unassignedcatrgory == "true"){
               
        //        $res->whereHas('Category', function($q)  {
        //             $q->where('status', '=', 1); 
        //         })
        //        ->where(function($q) {
        //                 $q->where('status','!=',2);
                            
                    
        //         })
        //         ->orwhere(function($q) {
        //                 $q->whereNull('category_id');
                                
        //         });
                
        //     }else{
               
        //        $res->whereHas('Category', function($q)  {
        //             $q->where('status', '=', 1); 
        //         });

        //         $res->where(function($q) {
        //                 $q->where('status','!=',2);
                    
        //         });
            
        //     }
        
        // $result = $res->get();
        

        // $consignment->where(function($query) use ($cities,$i){
        //           for ($i; $i <count($cities) ; $i++) { 
        //            if($i == 0){
        //             $query->where('logistics_doc_receipts.ship_to_city_id',$cities[$i]);
        //             }else{
        //           $query->orwhere('logistics_doc_receipts.ship_to_city_id',$cities[$i]);
        //             } 
        //           } 
        //    });
        Log::info('ProjectRepository->findAll:-Return '. json_encode($result));
        return $result;
          
    }

    public function findAll($orgId)
    {     

        $result = Project::where('organization_id',$orgId)
                    ->where('status','!=', 2) 
                    ->get(); 
                  
                    
        
       
        Log::info('ProjectRepository->findAll:-Return '. json_encode($result));
        return $result;
          
    }

 
    public function CategoryBasedProj($categoryId)
    {     
        Log::info('ProjectRepository->CategoryBasedProj:-Inside ');

        $result = Project::where('category_id',$categoryId)
        ->where('status','!=', 3) // 1- Enable,2-Disable,3-Closed
        ->get();
        
        Log::info('ProjectRepository->CategoryBasedProj:-Return '. json_encode($result));
        return $result;
          
    }

    public function ProjBasedCategory($projId)
    {     
        Log::info('ProjectRepository->findAll:-Inside ');

        $result = Project::findorfail($projId)->category_id;

        
        Log::info('ProjectRepository->findAll:-Return '. json_encode($result));
        return $result;
          
    }

        public function statusChangeById($request)
    {    
        Log::info('ProjectMasterRepository->statusChangeById:-Inside ');
        $result = Project::findOrFail($request['id']);
        $result->status = $request['status'];
        $result->save();
        Log::info('
            :-Return '. json_encode($result));

       return $result;
    
    }

    public function ProjSelectAll($request)
    {    
         Log::info('ProjectMasterRepository->statusChangeById:-Inside '.json_encode($request->id));


        $status = $request->status;

        $result = collect($request->id)->map(function ($model) use ($status) {
           
                $update = Project::findOrFail($model['pId']);
                $update->status = $status;
                $update->save();

                return $update;
            
        });
        
        
        Log::info('ProjectMasterRepository:-Return '. json_encode($result));

       return $result;
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create($orgId)
    // {
       
    //     // logined Persons Get Organization End**

    //     // Employee List Start
           
    //     // Employee List End**

    //         $user = Auth::user();
    //         $apiKey = $user->createToken($user->name)->accessToken;
            

    //     return $EmployeeList;

           
        
    // }


     public function ProjectAttachmentSave($model)
        {            

        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'message'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }
    public function save(Project $model, $id = false)
    {            

        try {
            

            $result = DB::transaction(function () use ($model) {
                $model->save();

                return [
                    'status' => 1,
                    'message'=>pStatusSuccess(),
                    'data' => $model
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'message' =>pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function findById($id)
    {
        
      return  Project::findorfail($id);
              
    }
 

    public function destroyById(Project $model)
    {
        
        try {
            
            $result = DB::transaction(function () use ($model) {
                $model->delete();

                return [
                    'message'=>"Project ".pDestroyMessage(),
                    'data' =>""
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 
            return [
                'message' => "Some Thing Went Wrong!!",
                'data' => $e
            ];
        }

    }



   
}
