<?php

namespace App\http\Controllers\Wfm\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\http\Controllers\Wfm\Services\OrganizationService;
use Illuminate\Support\Facades\Log;
use Session;
class OrganizationController extends Controller
{
   
    /**
     * * To connect service **
     */
    public function __construct(OrganizationService $service)
    {
        $this->service = $service;
    }


    public function SwitchOrg(Request $request)
    {         
        

        Log::info('OrganizationController->SwitchOrg:-Inside ');
        $Data = $this->service->SwitchOrg($request->all());
        Log::info('OrganizationController->SwitchOrg:-Return ');

        return response()->json($Data); 
      
    }
 


    
}
