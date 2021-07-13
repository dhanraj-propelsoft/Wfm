<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\VehicleRegisterDetail;
use App\VehicleVariant;
use Carbon\Carbon;
use App\Custom;
use App\User;
use App\VmsObservation;
use App\Person;
use App\People;
use Validator;
use App\VehicleComplaint;
use Session;
use Auth;
use DB;

class VehicleComplaintController extends Controller
{
	
	public function index()
	{
		
		 Auth::user()->id;

		 $person_id = Auth::user()->person_id;

		
		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

		 $organization_id = $organizations->organization_id;

		 $business = DB::table('organizations')->where('id',$organization_id)->first();       
		
		 $business_id = $business->business_id ;
	  

		 $vehicles_registers = VmsObservation::select('vms_observations.id', 'vms_observations.vehicle_id','vms_observations.abservation_summary','vms_observations.closure_status', DB::raw('DATE_FORMAT(vms_observations.observed_on, "%d %M, %Y") AS started_date'),'vehicle_register_details.registration_no')
		  ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'vms_observations.vehicle_id')
	  		->get();

	 //dd( $vehicles_registers);
		return view('personal.customer_complaint',compact('vehicles_registers'));
	}
	public function  add_complaint()
	{
		
			Auth::user()->id;

		 	$person_id = Auth::user()->person_id;

		
			 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

			 $organization_id = $organizations->organization_id;

			 $business = DB::table('organizations')->where('id',$organization_id)->first();       
			
			 $business_id = $business->business_id ;
		  
	 		
	     	 $current_timestamp = now()->format('YmdHis');
	     	 $complaint_number="PT0".$organization_id.  $current_timestamp;

           	 $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->where('owner_id',$person_id)->where('user_type',0)->groupby('registration_no')->pluck( 'registration_no','id');
          
	  		 $vehicles_register->prepend('Select vechicle Number', '');

		//dd( $vehicles_register );
				return view('personal.addcustomer_complaint',compact('vehicles_register','complaint_number'));

	}
	public function  store(Request $request)
	{
					//dd($request->all());
			$observed_on = Carbon::parse($request->input('set_on'))->format('Y-m-d ');
			//dd($observed_on);
			//dd($request->all());
			$vehicle= new \App\VmsObservation;
	        $vehicle->vehicle_id=$request->	get('vehicle_id');
	        $vehicle->observed_on=$observed_on;
	        $vehicle->abservation_summary=$request->get('complaint');
	        $vehicle->closure_status=$request->get('status');
	        $vehicle->created_by=Auth::user()->id;
	        $vehicle->last_modified_by=Auth::user()->id;
	        $vehicle->save();


	        $registration_no =($vehicle->vehicle_id != null) ? VehicleRegisterDetail::findorFail($vehicle->vehicle_id)->registration_no : "";
	        $observed_on = Carbon::parse($vehicle->observed_on)->format('d F, Y');
	         
	      	return response()->json([ 'message' => 'Your Vehicle Complaint '.config('constants.flash.added'), 'data' =>['id'=>$vehicle->id,'registration_no'=>$registration_no,'complaint'=>$vehicle->abservation_summary,'observed_on'=>$observed_on ,'status'=>$vehicle->closure_status]]);
			
		
		}

	public function activestatus(Request $request){
	  
		if($request->input('closure_status')==="1")
		{
			$UpdateData=['closure_status' => $request->input('status')];
		}else{
			$UpdateData=['closure_status' => $request->input('status')];
		}
		VmsObservation::where('id', $request->input('id'))->update($UpdateData);
		return response()->json(array('result' => "success",'closure_status'=>$UpdateData));
	}
	public function edit( $id){

			Auth::user()->id;

		 	$person_id = Auth::user()->person_id;

		
			$organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

			$organization_id = $organizations->organization_id;

			 $vehicles_registerno = VehicleRegisterDetail::where('organization_id', $organization_id)->where('owner_id',$person_id)->where('user_type',0)->groupby('registration_no')->pluck( 'registration_no','id');
			// dd($vehicles_register);
		
			$vechicles = VmsObservation::select('id', 'vehicle_id','abservation_summary','closure_status',DB::raw('DATE_FORMAT(observed_on, "%d-%m-%Y") AS observed_on'))
			
     		
       		 ->where('id',$id)->first();
       		 

			 //dd($vechicles);
			 return view('personal.editcustomer_complaint',compact('vehicles_registerno','vechicles'));

	}	
	public function  update(Request $request)
	{
		//dd($request->all());
		$observed_on = Carbon::parse($request->input('set_on'))->format('Y-m-d ');

		$vehicles = VmsObservation::findOrFail($request->input('id'));
        $vehicles->vehicle_id = $request->input('vehicle_number');
        $vehicles->abservation_summary=$request->input('complaint');
        $vehicles->observed_on=$observed_on;
        $vehicles->closure_status=$request->input('status');
   
        
        $vehicles->save();
        //dd($main_category);
        
        $registration_no =($vehicles->vehicle_id != null) ? VehicleRegisterDetail::findorFail($vehicles->vehicle_id)->registration_no : "";
	    $observed_on = Carbon::parse($vehicles->observed_on)->format('d F, Y');
	         
	    return response()->json([ 'message' => 'Your Vehicle Complaint '.config('constants.flash.updated'), 'data' =>['id'=>$vehicles->id,'registration_no'=>$registration_no,'complaint'=>$vehicles->abservation_summary,'observed_on'=>$observed_on ,'status'=>$vehicles->closure_status]]);
	 }



}
