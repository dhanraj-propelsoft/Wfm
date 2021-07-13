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
use App\WmsTransaction;
use Session;
use Auth;
use DB;

class VehicleReportController extends Controller
{
	
	public function index()
	{
		
		 Auth::user()->id;

		 $person_id = Auth::user()->person_id;

		
		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

		 $organization_id = $organizations->organization_id;

		 $business = DB::table('organizations')->where('id',$organization_id)->first();       
		
		 $business_id = $business->business_id ;

	  	 $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->where('owner_id',$person_id)->where('user_type',0)->groupby('registration_no')->pluck( 'registration_no','id');
          
	  		 $vehicles_register->prepend('Select vechicle Number', '');
	  		 //dd( $vehicles_register);
		
		return view('personal.vehicle_report',compact('vehicles_register'));
	}
	 public function get_vehicle_service_report(Request $request)
    {
        //dd($request->all());
        $reg_no = $request->input('vehicle_no');
      
        $from_date ="";
        $to_date ="";
       
        if($request->input('from_date'))
        {
        $from_date =date_string($request->input('from_date'));
        }
      
        if($request->input('to_date'))
        {
        $to_date = date_string($request->input('to_date'));
        }

        Auth::user()->id;

		 $person_id = Auth::user()->person_id;

		
		 $organizations = DB::table('organization_person')->where('person_id',$person_id)->first();

		$organization_id = $organizations->organization_id;
      
       
       $report=WmsTransaction::select('transactions.order_no','organizations.name as orgname','inventory_items.name','transactions.total')
       ->leftjoin('transactions','wms_transactions.transaction_id','=','transactions.id')
       ->leftjoin('hrm_employees','transactions.employee_id','=','hrm_employees.id')
       ->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id')
       ->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')
       ->leftjoin('account_vouchers','transactions.transaction_type_id','=','account_vouchers.id')
        ->leftjoin('organizations','transactions.organization_id' ,'=','organizations.id') 
       ->whereIn('account_vouchers.name',['job_invoice','job_invoice_cash'] )
        ->where('transactions.organization_id','=',$organization_id)
        ->whereNotNull('transactions.order_no');

       if(!empty($reg_no))
       {
       $report=WmsTransaction::select('transactions.order_no','organizations.name as orgname','hrm_employees.first_name','inventory_items.name','transactions.total')
       ->leftjoin('transactions','wms_transactions.transaction_id','=','transactions.id')
       ->leftjoin('hrm_employees','transactions.employee_id','=','hrm_employees.id')
       ->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id')
       ->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')
       ->leftjoin('account_vouchers','transactions.transaction_type_id','=','account_vouchers.id')
        ->leftjoin('organizations','transactions.organization_id' ,'=','organizations.id')
     	->where('wms_transactions.registration_id','=',$reg_no)
       ->whereIn('account_vouchers.name',['job_invoice','job_invoice_cash'] )
        ->where('transactions.organization_id','=',$organization_id)
        ->whereNotNull('transactions.order_no');
        }
  		//dd($report);

        if(!empty($from_date) && !empty($to_date))
       {
        $report->wherebetween('wms_transactions.job_date',[$from_date,$to_date]);
       //dd($data);
        }

        $reports=$report->get();

   // dd($reports);
        if(count($reports) > 0 )
        {
            return response()->json(['status' => 1 ,'data' => $reports]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => "No data available."]);
        }


    }
	


}
