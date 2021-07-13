<?php

namespace App\Http\Controllers\Wfm\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Services\ProjectService;
use Illuminate\Support\Facades\Log;
use Session; 
class ProjectController extends Controller
{
   
    /**
     * * To connect service **
    */
    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }
// Project List
    public function index($id)
    {      
        

        Log::info('ProjectController->index:-Inside ');
         $datas = $this->service->findAll($id);
        Log::info('ProjectController->index:- Return ');
    
        return $datas;

        // return view('wfm.project_list', compact('datas'));

    }
// Project List***

 
// Project List
    public function CategoryBasedProj($categoryId)
    {      


        Log::info('ProjectController->CategoryBasedProj:-Inside ');
         $datas = $this->service->CategoryBasedProj($categoryId);
        Log::info('ProjectController->CategoryBasedProj:- Return ');
    
        return $datas;

        // return view('wfm.project_list', compact('datas'));

    }
// Project List***

    public function ProjBasedCategory($projectId)
    {      


        Log::info('ProjectController->ProjBasedCategory:-Inside ');
         $datas = $this->service->ProjBasedCategory($projectId);
        Log::info('ProjectController->ProjBasedCategory:- Return ');
    
        return $datas;

        // return view('wfm.project_list', compact('datas'));

    }



// Project Create 
    public function create($id)
    {   

        Log::info('ProjectController->create:-Inside ');
        $data = $this->service->create($id);
        Log::info('ProjectController->create:-Inside ');
         return response()->json($data);
        // return view('wfm.project_details', compact('data'));
    }
// Project Create***  

// Project Store   
    public function store(Request $request)
    {         

        Log::info('ProjectController->store:-Inside ');
        $Data = $this->service->save($request->all());
        Log::info('ProjectController->store:-Inside ');

        return response()->json($Data); 
      
    }
// Project Store **

    
// Project Edit  
    public function edit($id,$orgid)
    {

       Log::info('ProjectController->store:-Inside ');
        $data = $this->service->findById($id,$orgid);
       Log::info('ProjectController->store:-Inside ');
       return response()->json($data);
        // return view('wfm.projectcategory_details', compact('data'));
    }
// Project Edit **   


// Project Update 
    public function update(Request $request,$id)
    {     
        Log::info('ProjectController->update:-Inside ');
         $Data = $this->service->save($request->all(),$id);
        Log::info('ProjectController->update:-Inside ');
        return response()->json($Data); 
      
    }

// Project Update 

   
    // public function destroy($id)
    // {
    //     Log::info('ProjectController->destroy:-Inside ');
    //     $data = $this->service->destroyById($id);
    //     Log::info('ProjectController->destroy:-Inside ');

        
    // }

    public function status(Request $request)
    {
        $data = $this->service->statusChangeById($request->all());
        
        return response()->json($data);
    }

    public function ProjSelectAll(Request $request)
    {
        
        $data = $this->service->ProjSelectAll($request->all());
        
        return response()->json($data);
    }

    public function file_send(Request $request)
    {       
    
        Log::info('ProjectService->statusChangeById:-Inside '.json_encode($request->all()));  

        // $name ="P_12" . $dt->format('Y-m-d-H-i-s') . "_" . $file->getClientOriginalName();

        //     $file->move($public_path, $name);

        // return response()->json($Data); 
      
    }

    

    
}
