<?php

namespace App\Http\Controllers\Wfm\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Services\DashboardService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Wfm\Model\Task;
use App\Http\Controllers\Wfm\Model\ProjectTask;
use Session;

class DashboardController extends Controller
{
   
    /**
    //  * * To connect service **
    //  */
    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }


    // Dashboard List 
    public function index($orgId)
    {   


        Log::info('DashboardController->index:-Inside ');
        $data = $this->service->findAll($orgId);
        Log::info('DashboardController->index:-Return '.json_encode($data));
        return response()->json($data);
        
    }
     // Dashboard List ***


   


  
    
}
