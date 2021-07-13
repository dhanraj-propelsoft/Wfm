<?php


namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Transaction;
use App\PersonCommunicationAddress;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class JobcardVisitingController extends Controller
{
    //
    public function visiting_jobcard()
    {  
    	$organization_id=Session::get('organization_id');
    	
    	Carbon::setWeekStartsAt(Carbon::SUNDAY);
       	$weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
       	Carbon::setWeekEndsAt(Carbon::SATURDAY);
       	$weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');
  
       $Transaction_data=Transaction::select('transactions.order_no','vehicle_register_details.registration_no','transactions.name','transactions.mobile',
      	'hrm_employees.first_name',DB::raw('DATE_FORMAT(wms_transactions.vehicle_next_visit, "%d-%m-%Y") as vehicle_next_visit'))
      	->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
      	->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
      	->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
      	->leftjoin('hrm_employees','hrm_employees.id','=','wms_transactions.assigned_to')
      	->where('account_vouchers.name','job_card')
      	->where('transactions.organization_id',$organization_id)
      	->whereBetween('wms_transactions.vehicle_next_visit',[$weekStartDate,$weekEndDate])
      	->get(); 

    $country = Country::where('name', 'India')->first();

    $state = State::where('country_id', $country->id)->pluck('name', 'id');
    $state->prepend('Select State', '');

    $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

    $title = PeopleTitle::pluck('display_name','id');
    $title->prepend('Title','');

    $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
    $payment->prepend('Select Payment Method','');


    $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
    $terms->prepend('Select Term','');

    $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

      	return view('trade_wms.visting_jobcards',compact('Transaction_data', 'state', 'title', 'payment', 'terms','group_name','city'));
    } 
    public function user_task()
      {
      	 $organization_id=Session::get('organization_id');

      	 $login_person=Auth::user()->person_id;
   

   
      	 	
      	$user_task=Transaction::select('transactions.order_no','hrm_employees.person_id','vehicle_register_details.registration_no','transactions.name','transactions.mobile',
      	'hrm_employees.first_name',DB::raw('DATE_FORMAT(wms_transactions.vehicle_next_visit, "%d-%m-%Y") as vehicle_next_visit'))
      	->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
      	->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
      	->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
      	->leftjoin('hrm_employees','hrm_employees.id','=','wms_transactions.assigned_to')
        ->where('hrm_employees.person_id',$login_person)
        ->where('transactions.organization_id',$organization_id)
        ->get(); 
      
      return view('trade_wms.user_task',compact('user_task'));
           
      }    
}
