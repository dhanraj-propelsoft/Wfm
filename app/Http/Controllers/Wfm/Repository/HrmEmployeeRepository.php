<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\HrmEmployeeRepositoryInterface;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\Project;
use App\Organization;
use App\HrmEmployee;
use Auth;//Get authenticated user data
use Session;
use DB;


class HrmEmployeeRepository implements HrmEmployeeRepositoryInterface
{
  
    public function getEmployeeDatasByOrgId($orgId)
    {
      
         return  HrmEmployee::where('organization_id',$orgId)->get();

    }

    public function getEmployeeByPersonId($personId)
    {
      
         return  HrmEmployee::where('person_id',$personId)->first();

    }
 
}
