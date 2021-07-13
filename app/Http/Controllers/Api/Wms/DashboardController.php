<?php

namespace App\Http\Controllers\Api\Wms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use DB;
use App\Helpers\Helper;

use App\OrganizationPerson;

use App\AccountPersonType;
use App\AccountVoucher;
use App\AccountLedger;
use App\Transaction;
use App\VehicleJobcardStatus;
use Carbon\Carbon;
use App\Person;
use App\People;
use App\AccountVoucherType;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Tradewms\Jobcard\JobCardService;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $successStatus = 200;

    /* public function __construct()
     {
         $this->middleware('auth:api');
     } */

	public function __construct(JobCardService $serv)
	{
		 $this->serv = $serv;
	}
 

    public function index($id)
    {
        $organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
	   
		$person_type_id = AccountPersonType::where('name', 'customer')->first()->id;

		$transaction_type = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first();

		$cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;

		$return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

		$journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

		$transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

		$transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

		$sale_order_id = AccountVoucher::where('name', 'sale_order')->where('organization_id', $organization_id)->first()->id;

		$notifications = [];
		$time = [];

		$top_customers = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM(transactions.total) AS total'))
		->where('organization_id', $organization_id)
		->where('approval_status', '1')
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->groupby('transactions.people_id')
		->groupby('transactions.user_type')
		->orderby('total', 'desc')->take(10)->get();

		$employee_name = [];
		$employee_total = [];

		foreach ($top_customers as $key => $value) {
		   $employee_name[] = [$key, $value->name];
		   $employee_total[] = [$key, $value->total];
		}

		$customers_names = json_encode($employee_name);
		$customers_total_value = json_encode($employee_total);

		//return $top_customers->all();
		//return $customers_names;


		$total_customer = People::select(DB::raw('COUNT(people.id) AS id'))
		->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
		->leftJoin('persons', 'persons.id','=','people.person_id')
		->leftJoin('businesses', 'businesses.id','=','people.business_id')
		->leftJoin('transactions', function($query) use($transaction_id, $transaction_cash) {
				$query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->leftJoin('transactions AS business', function($query) use($transaction_id, $transaction_cash) {
				$query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', [$transaction_id, $transaction_cash]);
		})
		->where('people.organization_id', $organization_id)
		->where('people.status', '1')
		->where('people_person_types.person_type_id', $person_type_id)
		->first()->id;

		$total_sales = DB::table('transactions')
						   ->where('organization_id', $organization_id)->where('approval_status', 1)
						   ->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])->sum('total');
		
		// ********    Calculation for Total Receivables Begins   ********

		$total_receivable = DB::select("SELECT SUM(balance) AS total FROM (
	  	SELECT 
	    	transactions.id,
	     	IF(
	      		(SELECT SUM(account_transactions.amount) FROM account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE account_entries.reference_transaction_id = transactions.id 
	        		AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,
	      				SUM(transactions.total),
	      				SUM(transactions.total) - 
	      			(SELECT 
	        			SUM(account_transactions.amount) 
	      			FROM
	        			account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE account_entries.reference_transaction_id = transactions.id 
	        			AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))
	    		) AS balance 
		  	FROM
		    	transactions 
			WHERE transactions.organization_id = $organization_id 
			    AND transactions.approval_status = 1 
			    AND transactions.transaction_type_id = $transaction_id
			    AND transactions.deleted_at IS NULL 
			    GROUP BY transactions.id
		 	) AS trans");

		$total_receivables = $total_receivable[0]->total;

// ********    Calculation for Total Receivables Ends   ********		

// ********    Calculation for New Orders Begins   ********

		/*$new_orders = DB::select("SELECT COUNT(id) AS total FROM (SELECT id, transactions.total,
  				( transactions.total - COALESCE(  (  SELECT  SUM(account_transactions.amount) FROM  account_entries  
        	LEFT JOIN account_transactions  ON account_transactions.entry_id = account_entries.id  
        		WHERE account_entries.reference_transaction_id = transactions.id  AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher) ), 0 )) AS balance
			FROM
			  	transactions 
			WHERE organization_id = $organization_id
				AND transactions.transaction_type_id = $sale_order_id  
				-- if we need to calculate total sale order and estimate add the corresponding value/variable on this line (AND transactions.transaction_type_id IN (transaction_id, estimate_id, sale_order_id)
			HAVING balance != 0 ) AS trans");

		$new_order = (COUNT($new_orders)) ? $new_orders[0]->total : 0;*/

		$new_order = Transaction::select('transactions.id')
		->whereIn('transaction_type_id', [$sale_order_id])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->count('id');

// ********    Calculation for New Orders Ends   ********

// ********    Calculation of Graphical data for Sales Begins   ********
		
		$sales = Transaction::select('transactions.id', DB::raw('DATE_FORMAT(wms_transactions.job_date, "%b-%Y") AS date'), 
			DB::raw('SUM(transactions.total) AS total'), 'transactions.name',  DB::raw('MONTH(wms_transactions.job_date) AS month'))
		->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
		->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])
		->where('approval_status', 1)
		->where('transactions.organization_id', $organization_id)
		->groupby(\DB::raw('MONTH(wms_transactions.job_date)'))->get();

		//dd($sales); //DB::raw('DATE_FORMAT(cust.cust_dob, "%d-%b-%Y") as formatted_dob'

		$sales_data = [];
		
		foreach ($sales as $value) {

			$sales_data[] = [$value->date, $value->total];
		}

		$sales_data = json_encode($sales_data);

// ********    Calculation of Graphical data for Sales Ends   ********

		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
		->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
		->leftJoin('account_transactions', 'account_transactions.debit_ledger_id', '=', 'account_ledgers.id')
		->whereIn('account_groups.name', ['cash'])
		->where('account_ledgers.organization_id', $organization_id)
		->where('account_ledgers.approval_status', '1')
		->where('account_ledgers.status', '1')
		->orderby('account_ledgers.id','asc')
		->sum('account_transactions.amount');

		//dd($ledgers);
		
		Log::info('API_DasboardController->index:- return ');
		$message['status'] = 1;
		$message['data'] =['total_customer'=>$total_customer,'total_sales'=>number_format($total_sales, 2),'total_receivables'=>number_format($total_receivables, 2),'new_order'=>$new_order]; 
		 return response()->json($message, $this->successStatus);

      //  return view('Api.dashboard', compact('top_customers', 'customers_names', 'customers_total_value', 'total_customer', 'total_sales', 'total_receivables', 'new_order', 'sales_data'));


       
	}
	

	public function job_status($id,$organization_id)
	{
		
		Log::info('API_DasboardController->job_status:- Inside');
		Log::info('API_DasboardController->job_status:- Inside Person_id '.$id);
		Log::info('API_DasboardController->job_status:- Inside OrgId '.$organization_id);
		
		//$organization_id = DB::table('organization_person')->where('person_id',$id)->first()->organization_id;
	//	dd($organization_id);
		$person_type_id = AccountPersonType::where('name', 'customer')->first()->id;

		    
		$box1_result = DB::table('jobcard_status_view')->where('organization_id',$organization_id)->select(DB::raw('count(*) AS box1_value'))->where(function ($query) {
                        $query->where('jobcard_Status_id',1)
                            ->orwhere('jobcard_Status_id',2)
                            ->orwhere('jobcard_Status_id',3);       
                    });
		$box1 = $box1_result->first();
		 
		
		$box2_result = DB::table('jobcard_status_view')->where('organization_id',$organization_id)->select(DB::raw('count(*) AS box2_value'))->where(function ($query) {
                        $query->where('jobcard_Status_id',4)
                            ->orwhere('jobcard_Status_id',5);      
                    });
		$box2 = $box2_result->first();

        $box3_result =  DB::table('jobcard_status_view')->where('organization_id',$organization_id)->select(DB::raw('count(*) AS box3_value'))->where(function ($query) {
                        $query->where('jobcard_Status_id',6)
                            ->orwhere('jobcard_Status_id',7);      
                    });
		 $box3 = $box3_result->first();

		 $box4_result =  DB::table('jobcard_status_view')->where('organization_id',$organization_id)->select(DB::raw('count(*) AS box4_value'))->where(function ($query) {
                        $query->where('jobcard_Status_id',8);     
                    });
		 $box4 = $box4_result->first();


        $pie_charts = Transaction::select('vehicle_jobcard_statuses.display_name',DB::raw('
          COUNT(vehicle_jobcard_statuses.id) as count'))
                     ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                     ->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')
                     ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
                     ->where('transactions.organization_id',$organization_id)
                     ->where('account_vouchers.name',"job_card")
                     ->where('vehicle_jobcard_statuses.id','!=',"8")
                     ->where('transactions.deleted_at',NULL)
                     ->groupby('vehicle_jobcard_statuses.display_name')
                     ->orderby('vehicle_jobcard_statuses.display_name')
					 ->get();
					 
					 $chart_Color=["#3366CC","#DC3912","#FF9900","#109618","#990099","#3B3EAC","#0099C6","#DD4477","#66AA00","#B82E2E","#316395","#994499","#22AA99","#AAAA11","#6633CC","#E67300","#8B0707","#329262","#5574A6","#3B3EAC"];
					  $chart_Color_array=[];
                      $pie_chart = [];
			  
					  foreach ($pie_charts as $value) {
						
							$pie_chart[] = [$value->display_name,(int)$value->count];
							
  							$chart_Color_array[]=$chart_Color[array_rand($chart_Color)];
  	      				}
        $pie_chart_value = $pie_chart;
        // $array1 = ["JC Status","Job Card"];
        // $a1 = [$array1];
        // $mergearray = array_merge($a1, $pie_charts);
        // $pie_chart_value = json_encode($mergearray);
		//$pie_chart_value = json_decode(json_encode($pie_chart_value), true);
        $bar_charts = Transaction::select('transaction_items.assigned_employee_id','hrm_employees.first_name',DB::raw('SUM(transaction_items.job_item_status = "1") AS open'),DB::raw('SUM(transaction_items.job_item_status = "2") AS closed'),DB::raw('SUM(transaction_items.job_item_status = "3") AS on_hold'),DB::raw('SUM(transaction_items.job_item_status = "4") AS progress'))->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')->leftjoin('hrm_employees','hrm_employees.id','=','transaction_items.assigned_employee_id')->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')->leftjoin('vehicle_job_item_statuses','vehicle_job_item_statuses.id','=','transaction_items.job_item_status')->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')->where('transactions.deleted_at',NULL)->where('transactions.organization_id',$organization_id)->where('vehicle_jobcard_statuses.id','!=',"8")->where('account_vouchers.name',"job_card")->groupby('transaction_items.assigned_employee_id')->get();
                    
        	  $bar_chart = [];
			  $EmployeeList=[];
			   foreach ($bar_charts as $value) {
							array_push($EmployeeList,$value->first_name);		
							$bar_chart[] = [(int)$value->open,(int)$value->closed,(int)$value->on_hold,(int)$value->progress];

        		}
		$bar_chart_value = $bar_chart;
        $Job_StatusList = ["Open","Closed","On Hold","Progress"];
     
      
     //   $bar_chart_value = json_encode($bar_charts);
        
        $charts = Transaction::select('transactions.order_no','vehicle_register_details.registration_no',DB::raw('SUM(transaction_items.job_item_status = "1") AS open'),DB::raw('SUM(transaction_items.job_item_status = "2") AS closed'),DB::raw('SUM(transaction_items.job_item_status = "3") AS on_hold'),DB::raw('SUM(transaction_items.job_item_status = "4") AS progress'))
        ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
        ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
        ->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id')
        ->leftjoin('vehicle_job_item_statuses','vehicle_job_item_statuses.id','=','transaction_items.job_item_status')
        ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
        ->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')
        ->where('transactions.deleted_at',NULL)
        ->where('transactions.organization_id',$organization_id)
        ->where('account_vouchers.name',"job_card")
        ->where('vehicle_jobcard_statuses.id','!=',"8")
        ->groupby('transactions.order_no')->get();
        //dd($charts);
		   $chart = [];
		   $register_vechicles = [];
		  

          //$colors = ["#ff9933","#33cc33","#ff3300","#ffcc00"];
              foreach ($charts as $value) {
			
						$chart[] = [(int)$value->open,(int)$value->closed,(int)$value->on_hold,(int)$value->progress];
						array_push($register_vechicles,$value->registration_no);
        	}
        $bar_chart_value2 = $chart;
        //dd($charts);
        // $headers2 = ["Open","Closed","On Hold","Progress"];
        // $header2 = [$headers2];
        // $mergevalues2 = array_merge($header2, $charts);
        // $bar_chart_value2 = json_encode($mergevalues2);
       // dd($bar_chart_value2);
		
	   $tables = Transaction::select('transactions.id','transactions.order_no','vehicle_register_details.registration_no','inventory_items.name as item','hrm_employees.first_name as assigned_to','transaction_items.start_time as from','transaction_items.end_time as to')
       ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
       ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
       ->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id')
       ->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')
       ->leftjoin('hrm_employees','hrm_employees.id','=','transaction_items.assigned_employee_id')
       ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
       ->leftjoin('vehicle_job_item_statuses','vehicle_job_item_statuses.id','=','transaction_items.job_item_status')
       ->where('transactions.organization_id',$organization_id)
       ->where('account_vouchers.name','=',"job_card")
       ->where('vehicle_job_item_statuses.id',"3")
       ->where('transactions.deleted_at',null)->get(); 
	   $message['status'] = 1;
	   foreach($EmployeeList as $key =>$name )
	   {
		if($name==null){
			$EmployeeList[$key]="";
		}
	   }
	   $message['data'] =['box1'=>$box1->box1_value,'box2'=>$box2->box2_value,'box3'=>$box3->box3_value,'box4'=>$box4->box4_value,'bar_chart1'=>$bar_chart_value,'bar_chart2'=>$bar_chart_value2,'register_vechicles'=>$register_vechicles,'pie_chart_data'=>$pie_chart_value,'pie_chart_colors'=>$chart_Color_array,'job_status_list'=>$Job_StatusList,'EmployeeList'=>$EmployeeList]; 
		
	   
		Log::info('API_DasboardController->job_status:- Return');

	   return response()->json($message, $this->successStatus);

	
	  // return view('trade_wms.jobstatus_dashboard',compact('box1','box2','box3','box4','tables','pie_chart_value','bar_chart_value','bar_chart_value2'));
		
	}



	public function JobCardList(Request $request,$jobcard_id=null)
	{
		Log::info('API_DasboardController->JobCardList:- Inside');
		$response = $this->serv->findAll_API($request);
		Log::info('API_DasboardController->JobCardList:- Return');
		return response()->json($response['data'], $this->successStatus);
 

	}


}
