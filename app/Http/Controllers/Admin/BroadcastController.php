<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\Module;
use App\Package;
use App\PackageModule;
use App\OrganizationPackage;
use App\User;
use Auth;
use Session;
use App\SystemMessageBroadcast;

class BroadcastController extends Controller
{
    //
     public function index()
    {	
    	$broadcasts=SystemMessageBroadcast::Select('system_message_broadcasts.id','system_message_broadcasts.tittle','system_message_broadcasts.message_type','system_message_broadcasts.active','modules.name AS module_name','businesses.business_name AS organization_name','users.name AS user_name')
    		->leftjoin('modules','modules.id','=','system_message_broadcasts.module_id')
    		->leftjoin('businesses','businesses.id','=','system_message_broadcasts.organization_id')
    		->leftjoin('users','users.id','=','system_message_broadcasts.user_id')
    		->get();
    		

    	return view('admin.broadcast',compact('broadcasts'));
    }

    public function create($id=false)
    {  

    	 $organization_id = Session::get('organization_id');

    	 $module_name=Organization::leftjoin('organization_packages',
    		'organization_packages.organization_id','=','organizations.id')
    	   ->leftjoin('package_modules','package_modules.package_id','=','organization_packages.package_id')
    	   ->leftjoin('modules','modules.id','=','package_modules.module_id')
    	   ->leftjoin('packages','packages.id','=','organization_packages.package_id')
    	   ->where('organizations.id',$organization_id)
    	   ->pluck('modules.display_name', 'modules.id')
    	   ->prepend('Select Module','');

    	$organization_name=User::leftjoin('businesses','businesses.created_by','=','users.id')
    							->where('businesses.created_by',$organization_id)
    							->pluck('businesses.business_name', 'businesses.id')
    							->prepend('Select Organization','');
    					
    	$user_name=User::pluck('name', 'id')
    					->prepend('Select User','');
        if ($id==false)
        {
        $broadcast_details=[];
        }
    else{
         
        $broadcast_details=SystemMessageBroadcast::where('id',$id)
            				->first();        
    	}
       return view('admin.broadcast_details',compact('module_name','user_name',
       	'organization_name','broadcast_details'));
    }

   public function store(Request $request,$id=false)
   {		
      	if($id==false)

       {
          $broadcast= new \App\SystemMessageBroadcast;
       } 
       else 
        {   
        $broadcast=SystemMessageBroadcast::findOrFail($id);
        }

   		 $broadcast->tittle=$request->tittle;
         $broadcast->message_type=$request->message_type;
         $broadcast->message=$request->message;
         $broadcast->module_id=$request->module_id;
         $broadcast->organization_id=$request->organization_id;
         $broadcast->user_id=$request->user_id;
         $broadcast->active=$request->active;
         $broadcast->valid_from=$request->from;
         $broadcast->valid_to=$request->to;
         $broadcast->deleted_by=Auth::user()->id;
         $broadcast->created_by=Auth::user()->id;
         $broadcast->updated_by=Auth::user()->id;
         $broadcast->save();
            
         $broadcasts=SystemMessageBroadcast::Select('system_message_broadcasts.id','system_message_broadcasts.tittle','system_message_broadcasts.message_type','system_message_broadcasts.active','modules.name AS module_name','businesses.business_name AS organization_name','users.name AS user_name')
            ->leftjoin('modules','modules.id','=','system_message_broadcasts.module_id')
            ->leftjoin('businesses','businesses.id','=','system_message_broadcasts.organization_id')
            ->leftjoin('users','users.id','=','system_message_broadcasts.user_id')
            ->where('system_message_broadcasts.id',$broadcast->id)
            ->first();  
             
          if($id==false)
          {
			return response()->json([ 'message' => 'broadcast'.config('constants.flash.added'),
            'data' =>['broadcast'=>$broadcasts]]);
     	  }
     		return response()->json([ 'message' => 'broadcast'.config('constants.flash.updated'),
            'data' =>['broadcast'=>$broadcasts]]);
     	
   }


	 	public function status(Request $request)
	 	{
    
        if($request->input('status')==="1")
        {
            $UpdateData=['active' => $request->input('status')];
        }else{
            $UpdateData=['active' => $request->input('status')];
        }
       
        SystemMessageBroadcast::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));     

    	}
    	 public function destroy(Request $request)
    	{

        $broadcast = SystemMessageBroadcast::findOrFail($request->input('id'))
                    ->delete();
               
        return response()->json(['status' => 1, 'message' => 'broadcast'.config('constants.flash.deleted'), 'data' => []]);
    	}

}
