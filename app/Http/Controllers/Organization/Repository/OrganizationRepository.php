<?php

namespace App\Http\Controllers\Organization\Repository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Controllers\Organization\Model\Organization;
use App\Http\Controllers\Organization\Model\OrganizationCategory;
use App\Http\Controllers\Organization\Model\OrganizationOwnership;

class OrganizationRepository 
{
    

    public function findAll()
    {   

       Log::info('ProjectMasterRepository->findAll:-Inside ');
        $result =  Organization::with('OrganizationAddress','OrganizationCategory','OrganizationOwnership')->get();
       Log::info('ProjectMasterRepository->findAll:-Return '. json_encode($result));
       return $result;
        
    }
    public function save($model)
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
                'status' => 0,
                'message' =>pStatusFailed(),
                'data' => $e
            ];
        }
    
      
    } 

    public function findById($id)
    {
        Log::info('OrganizationRepository->findById:-Inside ');

        $result =  Organization::findOrFail($id);
       

        Log::info('OrganizationRepository->findById:-Return '. json_encode($result));

       return $result;
              
    }  

    public function getOrgMasterData()
    {
        Log::info('OrganizationRepository->findById:-Inside ');

        $category =  OrganizationCategory::get();


        $ownership =  OrganizationOwnership::get();
       

        Log::info('OrganizationRepository->findById:-Return ');

       return ['status'=>1,'CategoryData'=>$category,'ownershipData'=>$ownership];
              
    }
       
   
}
