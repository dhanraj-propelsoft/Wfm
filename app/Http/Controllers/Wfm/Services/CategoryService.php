<?php

namespace App\Http\Controllers\Wfm\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\CategoryRepository;
use App\Http\Controllers\Wfm\Model\CategoryVO;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Wfm\Model\Category;
use Session;
use Illuminate\Support\Facades\Validator;
use App\HrmEmployee;
use Auth; 
class CategoryService 
{
    public $successStatus = 200;
    public $unauthorised = 401;
    /** 
     * * To connect Repo **
     */
    
    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }


    public function findAll($orgId)
    {   
        
        // hard Core Organization id
        // $orgId = 53;

        Log::info('ProjectMasterService->findAll:-Inside'.json_encode($orgId));


        $models = $this->repo->findAll($orgId);
      

        $status = [];
        foreach ($models as $key => $value) 
         {

            array_push($status,$value->status);
         }
         
        if((count(array_unique($status)) === 1)) 
        {
              $select = (int)$status[0];
        } 
        else {
              $select = 0;
        }
        $selectall = $select == 0?false:true;
        

        $entities = collect($models)->map(function ($model) {
            
            return $this->convertToVO($model);
        });
    
       
        Log::info('ProjectMasterService->findAll:-Return '. json_encode($entities));

   

         return response()->json(['status' => 1, 'message' => 'Category Data has been get Successfully!','selectall'=>$selectall, 'data' =>$entities], $this->successStatus);
       
    }

    public function create()
    {   
       Log::info('ProjectMasterService->create:-Inside ');

       return $this->convertToVO(); 

       Log::info('ProjectMasterService->create:-Return');
       
    }

    public function save($data,$id = false)
    {   
        Log::info('ProjectMasterService->save:-Return '. json_encode($data));
        // hard Core OrgId
        // $data['orgId'] = 53;
        $rule = $this->validator($data,$id);

        $validators = Validator::make($data, $rule);

        if ($validators->fails()) {
            return [
                        'status'=>0,
                        'message' => $validators->messages()->first(),
                        'data' => ''
                 ];
                }
                // 
        
        $data['personId'] = HrmEmployee::where('person_id',Auth::user()->person_id)->first()->id;
       
        $model = $this->convertToModel($data,$id);

        $storedModel = $this->repo->save($model,$id);
       

        Log::info('ProjectMasterService->save:-Return '. json_encode($storedModel));

        return $storedModel;
        
    }
     public function findById($id)
    {   
    
      Log::info('ProjectMasterService->create:-Inside ');

      $model = $this->repo->findById($id);

      Log::info('ProjectMasterService->create:-Return');

      return $this->convertToVO($model);
       
    }

    
    
    public function convertToVO($model = false)
    {
       
       $vo = new CategoryVO($model);  
       
        return $vo;
    }


   
    public function validator($data,$id = false)
    {  
        
    
        $organization_id = $data['orgId'];

        $rule = [ 'pName' => 'required|unique:'.'.propel_wfm.categories,name,' . ($id ? "$id" : 'NULL') . ',id,organization_id,' . $organization_id];

        return $rule;
    }

     public function statusChangeById($request)
    {   
        Log::info('ProjectMasterService->statusChangeById:-Inside ');

        $data = $this->repo->statusChangeById($request);

        Log::info('ProjectMasterService->statusChangeById:- Return '.json_encode($data));
        $status = $data->status=="1"?" Active":" InActive";
        
                                
        return [        'status'=>1,
                        'data'=>$data->name." is". $status
                 ];
     
    }

    public function CategorySelectAll($request)
    {   
        Log::info('ProjectMasterService->CategorySelectAll:-Inside ');
        $request = (object)$request;

        $data = $this->repo->CategorySelectAll($request);

        Log::info('ProjectMasterService->CategorySelectAll:- Return '.json_encode($data));
        $status = $request->status=="1"?" Active":" InActive";
        
                                
        return [        'status'=>1,
                        'data'=>"All Category is". $status
                ];
     
    }
    public function destroyById($id)
    {
        
        $model = $this->repo->findById($id);
        $data = $this->repo->destroyById($model);
        return $data;       
    
    }
    
    public function convertToModel($modelData,$id = false)
    {
       
        $data = (object)$modelData;
  
        if($id)
        {
            $model = $this->repo->findById($id); 

        }
        else
        {
            $model = new Category;
        }
        $model->name = $data->pName;
        $model->organization_id = $data->orgId;
        $model->created_by = $data->personId;
        $model->last_modified_by = $data->personId;

         return $model;
    }


}
