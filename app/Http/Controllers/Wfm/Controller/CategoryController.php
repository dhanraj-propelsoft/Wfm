<?php

namespace App\Http\Controllers\Wfm\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wfm\Services\CategoryService;
use Illuminate\Support\Facades\Log;
use Session;
class CategoryController extends Controller
{
    
    /**
     * * To connect service **
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }
 

    public function index($id)
    {   
        
        Log::info('ProjectMasterController->index:-Inside ');
        $datas = $this->service->findAll($id);
        Log::info('ProjectMasterController->index:- Return ');
        
        return $datas;

    }

    public function create()
    {
       
        Log::info('ProjectMasterController->create:-Inside ');
        $data = $this->service->create();
        Log::info('ProjectMasterController->create:-Inside ');
        return $data;

        // return view('wfm.projectcategory_details', compact('data'));
    }

    
    public function store(Request $request)
    {         

        Log::info('ProjectMasterController->store:-Inside ');
        $Data = $this->service->save($request->all());
        Log::info('ProjectMasterController->store:-Return '.json_encode($Data));

        return response()->json($Data);

      
    }

    
    public function edit($id)
    {

       Log::info('ProjectMasterController->store:-Inside ');
        $data = $this->service->findById($id);
       Log::info('ProjectMasterController->store:-Inside ');
       return response()->json($data);
        // return view('wfm.projectcategory_details', compact('data'));
    }

  
    public function update(Request $request,$id)
    {         
        $data = $this->service->save($request->all(),$id);

        return response()->json($data); 
    }

   
    public function destroy($id)
    {
        
        
        $data = $this->service->destroyById($id);
       

        return response()->json(['status' => 1, 'message' => $data['message'], 'data' => $data['data']]);
    }


   
    public function status(Request $request)
    {
        $data = $this->service->statusChangeById($request->all());
        
        return response()->json($data);
    }

    public function CategorySelectAll(Request $request)
    {
        
        $data = $this->service->CategorySelectAll($request->all());
        
        return response()->json($data);
    }

    
}
