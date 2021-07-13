<?php

namespace App\Http\Controllers\Wfm\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Services\TaskService;
use Illuminate\Support\Facades\Log;
use Session;

class TaskController extends Controller
{
   
    /**
    //  * * To connect service **
    //  */
    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

// Task Create 
    public function create($id)
    {
        
        Log::info('TaskController->create:-Inside ');
        $data = $this->service->create($id);
        Log::info('TaskController->create:-Return '.json_encode($data));
        
        return response()->json($data);
        
    }
// Task Create***
    
// Task Store
    public function store(Request $request)
    {           
        Log::info('TaskController->store:-Inside ');
        $data = $this->service->store($request->all());
        Log::info('TaskController->store:-Return '.json_encode($data));
        return response()->json($data);
      
    }
// Task Store

// Get Task Information By Task id
    public function findById($id)
    {       
        
        Log::info('TaskController->store:-Inside');
        $data = $this->service->findByID_VO($id);
        Log::info('TaskController->store:-Return ');
        return response()->json($data);
      
    }


    public function taskfollowerupdate(Request $request)
    {       

        Log::info('TaskController->taskfollowerupdate:-Inside');
        $data = $this->service->taskfollowerupdate($request->all());
        Log::info('TaskController->taskfollowerupdate:-Return ');
        return response()->json($data);
      
    }

    // Task Edit 
    public function taskEdit(Request $request)
    {
        
        Log::info('TaskController->taskEdit:-Inside ');
        $data = $this->service->taskUpdate($request->all());
        Log::info('TaskController->taskEdit:-Return '.json_encode($data));
    
        return response()->json($data);
        
    }
    // Task Edit ***


    public function taskActionchg(Request $request)
    {
        
        
        Log::info('TaskController->taskActionchg:-Inside ');
        $data = $this->service->taskActionchg($request->all());
        Log::info('TaskController->taskActionchg:-Return '.json_encode($data));
    
        return response()->json($data);
        
    }

   

  
    
}
