<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Setting;
use Session;
use Auth;
use Response;
use App\SmsTemplate;

class SmsTemplateController extends Controller
{
    public function index()
	{		
		$sms_templates=SmsTemplate::where('status',1)->get();
				
		return view('settings.sms_template',compact('sms_templates'));
	}
	public function create($id=false)
    {   	
    	if ($id==false)
     	{		
       		$sms_template=[];
     	}
    else{
           $sms_template=SmsTemplate::where('id',$id)
           					->first();
    	}
       return view('settings.sms_template_details',compact('sms_template'));
    }
      public function store(Request $request,$id=false)
   {		
      	if($id==false)

       {
        	$sms_template= new \App\SmsTemplate;
       } 
       else 
        {   $sms_template=SmsTemplate::findOrFail($id);
        	
        }
        	$sms_template->sms_type=$request->summary;
         	$sms_template->sms_content=$request->smstext;
         	$sms_template->status=1;
         	$sms_template->created_by=Auth::user()->id;
         	$sms_template->updated_by=Auth::user()->id;
         	$sms_template->deleted_by=Auth::user()->id;
         	$sms_template->save();

          if($id==false)
          {
         return response()->json([ 'message' => 'SMS Template'.config('constants.flash.added'),
            'data' =>['sms_template'=>$sms_template]]);
         }
    	return response()->json([ 'message' => 'SMS Template'.config('constants.flash.updated'),
            'data' =>['sms_template'=>$sms_template]]);
         
     	
   }

   	public function destroy(Request $request)
    	{

        $broadcast = SmsTemplate::findOrFail($request->input('id'))
                    ->delete();
               
        return response()->json(['status' => 1, 'message' => 'sms_template'.config('constants.flash.deleted'), 'data' => []]);
    	}

}
