<?php

namespace App\Http\Controllers\Wfm\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Repository\OrganizationRepository;
use App\Http\Controllers\Wfm\Model\CategoryVO;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Wfm\Model\Category;
use Session;
use Illuminate\Support\Facades\Validator;
use App\HrmEmployee;
use Auth;
class OrganizationService 
{
    public $successStatus = 200;
    public $unauthorised = 401;
    /** 
     * * To connect Repo **
     */
    
    public function __construct(OrganizationRepository $repo)
    {
        $this->repo = $repo;
    }


    public function SwitchOrg($request)
    {   
        Log::info('OrganizationService->SwitchOrg:-Inside ');
        $request = (object)$request;

        $data = $this->repo->SwitchOrg($request);

        Log::info('OrganizationService->SwitchOrg:- Return '.json_encode($data));
        
                
        return $data;
     
    }

}
