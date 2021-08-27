<?php

namespace App\Http\Controllers\Organization\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Organization\Services\OrganizationService;

class OrganizationController extends Controller
{
        /**
         * * To connect service **
         */

    public function __construct(OrganizationService $service)
    {
            $this->service = $service;
    }


    public function findById($id)
    {         
        
       Log::info('OrganizationController->findById:-Inside ');
        $data = $this->service->findById($id);
       Log::info('OrganizationController->findById:-Inside ');
       return response()->json($data);
      
    }

    public function getOrgMasterData()
    {         
        
       Log::info('OrganizationController->findById:-Inside ');
        $data = $this->service->getOrgMasterData();
       Log::info('OrganizationController->findById:-Inside ');
       return response()->json($data);
      
    }

    public function store(Request $request)
    {         
        
        Log::info('OrganizationController->store:-Inside ');
        $Data = $this->service->save($request->all());
        Log::info('OrganizationController->store:-Return '.json_encode($Data));

        return response()->json($Data);
      
    }



}