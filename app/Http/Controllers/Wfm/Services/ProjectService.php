<?php

namespace App\Http\Controllers\Wfm\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\ProjectRepository;
use App\Http\Controllers\Wfm\Repository\ProjectMasterRepository;
use App\Http\Controllers\Wfm\Repository\HrmEmployeeRepository;
use App\Http\Controllers\Organization\OrganizationRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\Project;
use App\Http\Controllers\Wfm\Model\ProjectAttachment;
use Illuminate\Support\Facades\Log;
use App\HrmEmployee;
use Session;
use Auth;
use DB;
use App\Custom;
use DateTime;

class ProjectService 
{
    public $successStatus = 200;
    public $unauthorised = 401;
    /**
     * * To connect Repo **
     */
    
    public function __construct(ProjectRepository $repo,OrganizationRepository $orgRepo,HrmEmployeeRepository $hrmRepo,ProjectMasterRepository $projMasterRepo)
    {
        $this->repo = $repo;
        $this->orgRepo = $orgRepo;
        $this->hrmRepo = $hrmRepo;
        $this->projMasterRepo = $projMasterRepo;
    }


    public function findAll($orgId)
    {   
        Log::info('ProjectService->findAll:-Inside ');

        // hard Core Org Id 53
        // $orgId = 53;
    
        $models = $this->repo->findAllList($orgId);

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
        Log::info('ProjectService->findAll:-' . json_encode($models));

        $projectsVOs = collect($models)->map(function ($model) {
            
            $projectsVO = $this->convertToVO($model);

            return $projectsVO;
        });

           
         return response()->json(['status' => 1, 'message' => 'Project Data has been get Successfully!','selectall'=>$selectall, 'data' =>$projectsVOs], $this->successStatus);

         Log::info('ProjectService->findAll:-Return '. json_encode($projectsVOs));
    }
 
    public function CategoryBasedProj($categoryId)
    {   
        Log::info('ProjectService->CategoryBasedProj:-Inside ');

       
    
        $models = $this->repo->CategoryBasedProj($categoryId);


        // Log::info('ProjectService->findAll:-' . json_encode($models));

        // $projectsVOs = collect($models)->map(function ($model) {
            
        //     $projectsVO = $this->convertToVO($model);

        //     return $projectsVO;
        // });

           
         return response()->json(['status' => 1, 'message' => 'Project Data has been get Successfully!', 'data' =>$models], $this->successStatus);

         Log::info('ProjectService->findAll:-Return '. json_encode($projectsVOs));
    }

    public function ProjBasedCategory($projId)
    {   
        Log::info('ProjectService->CategoryBasedProj:-Inside ');

       

        $projecData = $this->repo->findById($projId);
        
        $categoryData = null;
        if($projecData->category_id != null){
            $categoryData = $this->projMasterRepo->findById($projecData->category_id);
        }

        // $categoryDatas = $this->projMasterRepo->findById();

        // $CategoryList = $this->projMasterRepo->findAll(53);

        // Log::info('ProjectService->findAll:-' . json_encode($models));

        // $projectsVOs = collect($models)->map(function ($model) {
            
        //     $projectsVO = $this->convertToVO($model);

        //     return $projectsVO;
        // });

           
         return response()->json(['status' => 1, 'message' => 'Project Data has been get Successfully!', 'categoryData' =>$categoryData,'projecData'=>$projecData], $this->successStatus);

         Log::info('ProjectService->findAll:-Return '. json_encode($projectsVOs));
    }

    public function create($orgId)
    {    
        Log::info('ProjectService->create:-Inside ');

        // hard Core Org Id 53
        // $orgId = 53;


        $employeeDatas = $this->hrmRepo->getEmployeeDatasByOrgId($orgId);

        $categoryDatas = $this->projMasterRepo->findAll($orgId);
    
        
        $ProjectDetailsVO = $this->convertToVO($model = false,$id = false,$employeeDatas,$categoryDatas);
        
        $response = [
            'status' => 1,
            'message' => pStatusSuccess(),
            'data' =>  $ProjectDetailsVO
        ];   
        
        Log::info('ProjectService->create:-Return '.json_encode($ProjectDetailsVO));

       return $response; 


      
    }
     public function findById($id,$orgId)
    {   
         
      $model = $this->repo->findById($id);
      
       
      $employeeDatas = $this->hrmRepo->getEmployeeDatasByOrgId($orgId);

      $categoryDatas = $this->projMasterRepo->findAll($orgId);
        
      $ProjectDetailsVO = $this->convertToVO($model,$id,$employeeDatas,$categoryDatas);
      
      $response = [
            'status' => 1,
            'message' => pStatusSuccess(),
            'data' =>  $ProjectDetailsVO
        ];    

       return $response; 
       
    }
    public function convertToVO($model = false,$id = false,$EmployeeDatas = false,$categoryDatas = false)
    {   
       Log::info('ProjectService->convertToVO:-Inside ');

       $vo = new ProjectVo($model,$id);  
       $vo->setEmployeeListVO($EmployeeDatas);
       $vo->setCategoryListVO($categoryDatas);

       Log::info('ProjectService->convertToVO:-Return '. json_encode($vo));

       return $vo;

    }
    public function save($data,$id = false)
    {   

        Log::info('ProjectService->save:-Inside ');
      
        $rule = $this->validator($data,$id);

        $validators = Validator::make($data, $rule);

        if ($validators->fails()) {

                    return [
                        'status' => 0,
                        'message' => $validators->messages()->first(),
                        'data' => ""
                    ];
                }
        // hard Core Org Id and Person id
        // $data['orgId'] = 53;
        $data['personId'] = HrmEmployee::where('person_id',Auth::user()->person_id)->first()->id;

        // $data['pProjectOwner'] = HrmEmployee::where('person_id',$data['pProjectOwner'])->first()->id;
       
        $model = $this->convertToModel($data,$id);

        $storedModel = $this->repo->save($model,$id);
       
       
        if($storedModel['message'] == "SUCCESS"){

            if(!empty($data['pFile']))
            {   
                $Return_Data = $this->attachments($data,$data['pFile'],$storedModel['data']->id,$attachment_prefix="P");
            }
        }

    
        Log::info('ProjectService->save:-Return '.json_encode($storedModel));

        return $storedModel;
       
    }

    public function attachments($data, $file, $project_id,$attachment_prefix)
    { 
        $files_array = $file;

        
        $org_id = $data['orgId'];

        $public_path = ProjectAttachmentPath($org_id, $project_id);
      

        if (!file_exists($public_path)) {
            mkdir(($public_path), 0777, true);
        }
        $dt = new DateTime();
        $ProjectAttachmentModel = [];

        foreach ($files_array as $file) {

            $name = $attachment_prefix . "_" . $project_id . "_" . $dt->format('Y-m-d-H-i-s') . "_" . $file->getClientOriginalName();

            $file->move($public_path, $name);

            $data['upload_file'] = $name;
            $data['file_original_name'] = $file->getClientOriginalName();
          
            $ProjectAttachmentModel = $this->convertToProjectAttachmentModel($data,$project_id);

            $ProjectAttachmentSave = $this->repo->ProjectAttachmentSave($ProjectAttachmentModel);
        
        }
      
        return response()->json(['status'=>1]);
        
    }

    public function convertToProjectAttachmentModel($modelData,$id)
    {
       
        $data = (object)$modelData;
       
        
        $model = new ProjectAttachment;
      
        $model->project_id = $id;
        $model->upload_file = $data->upload_file;
        $model->file_original_name = $data->file_original_name;
        $model->created_by = $data->personId;
        $model->last_modified_by = $data->personId;
        $model->deleted_by = $data->personId;

        return $model;
    }

   
   
    public function validator($data,$id = false)
    {  
        // hard Core Org Id
        $organization_id = $data['orgId'];

        $rule = [ 'pName' => 'required|unique:'.'.propel_wfm.projects,name,' . ($id ? "$id" : 'NULL') . ',id,organization_id,' . $organization_id];

        return $rule;
    }

       public function statusChangeById($request)
        {   
        Log::info('ProjectService->statusChangeById:-Inside ');

        $data = $this->repo->statusChangeById($request);

        Log::info('ProjectService->statusChangeById:- Return '.json_encode($data));
        $status = $data->status=="1"?" Active":" InActive";
        
                                
        return [        'status'=>1,
                        'data'=>$data->name." is". $status
                 ];
     
    }

    public function ProjSelectAll($request)
    {   
        Log::info('ProjectService->ProjSelectAll:-Inside ');
        $request = (object)$request;

        $data = $this->repo->ProjSelectAll($request);

        Log::info('ProjectService->ProjSelectAll:- Return '.json_encode($data));
        $status = $request->status=="1"?" Active":" InActive";
        
                                
        return [        'status'=>1,
                        'data'=>"All Project is". $status
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
        Log::info('ProjectService->convertToModel:-Inside '.json_encode($data));

        if($id)
        {
            $model = $this->repo->findById($id); 

        }
        else
        {
            $model = new Project;
        }


        $model->name = $data->pName;
        $model->details = $data->pDetails;
        $model->category_id = $data->pCategoryId;
        $model->project_owner = $data->pProjectOwner;
        $model->start_date = $data->pStartDate;
        $model->deadline_date = $data->pDeadlineDate;
        $model->organization_id = $data->orgId;
        $model->created_by = Auth::user()->id;
        $model->last_modified_by = Auth::user()->id;

        return $model;
    }


}
