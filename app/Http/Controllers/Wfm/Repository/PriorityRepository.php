<?php

namespace App\Http\Controllers\Wfm\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\PriorityRepositoryInterface;
use App\Http\Controllers\Wfm\Model\ProjectVo;
use App\Http\Controllers\Wfm\Model\Project;
use App\Http\Controllers\Wfm\Model\Priority;
use Illuminate\Support\Facades\Log;
use App\Organization;
use App\HrmEmployee;
use Auth;//Get authenticated user data
use Session;
use DB;


class PriorityRepository implements PriorityRepositoryInterface
{
  

    public function findAll()
    {     
       Log::info('PriorityRepository->findAll:-Inside ');
        $result =  Priority::get();
       Log::info('PriorityRepository->findAll:-Return '. json_encode($result));
       return $result;
       
    }

   
}
