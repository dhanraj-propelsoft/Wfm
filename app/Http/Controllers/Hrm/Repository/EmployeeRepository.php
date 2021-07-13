<?php

namespace App\Http\Controllers\Hrm\Repository;
use App\Http\Controllers\Hrm\Repository\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\HrmEmployee;
use Session;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    public function findAllEmployee($orgId){
        
        Log::info('EmployeeRepository->findAllEmployee:- Inside .... '.$orgId);
        $query =  HrmEmployee::where('organization_id', $orgId)->get();
        Log::info('EmployeeRepository->findAllEmployee:- Return'.json_encode($query));
        return $query;
    }

    public function findById($id){
        
        Log::info('EmployeeRepository->findById:- Inside .... '.$id);
        $query =  HrmEmployee::where('person_id', $id)->first();
        Log::info('EmployeeRepository->findById:- Return'.json_encode($query));
        return $query;
    }


    
}
