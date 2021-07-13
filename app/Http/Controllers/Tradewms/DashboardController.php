<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;
use App\AccountVoucher;
use App\AccountLedger;
use App\Transaction;
use App\HrmEmployee;
use App\VehicleJobcardStatus;
use App\VehicleRegisterDetail;
use Carbon\Carbon;
use App\Person;
use App\People;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
use Session;
use Auth;
use DB;

class DashboardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($id=null)
	{
		if($id)
		{
			$organization_id = $id;
			
		}else{

		$organization_id = Session::get('organization_id');
		
		}
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

		$today = Carbon::today()->format('Y-m-d');
        $last_six_month = Carbon::now()->subMonths(6)->startOfMonth();
        $six_month = $last_six_month->format('Y-m-d');

        $six_month_view = $last_six_month->format('d-m-Y');
		$today_view = Carbon::today()->format('d-m-Y');


		$top_customers = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total'))
		->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
		->where('transactions.organization_id', $organization_id)
		->wherebetween('wms_transactions.job_date', [$six_month ,$today])
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


		$total_customer = People::select(DB::raw(count('people.id')))
        ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
        ->leftJoin('persons', 'persons.id','=','people.person_id')
        ->leftJoin('businesses', 'businesses.id','=','people.business_id')

        ->leftJoin('transactions', function($query) use($transaction_type) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })
        ->leftJoin('transactions AS business', function($query)  use($transaction_type){
                $query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })

        ->leftJoin('account_ledgers', function($join) use($organization_id)
            {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id')

        ->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
            {
                $join->on('people.business_id', '=', 'business_ledgers.business_id')
                ->where('business_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id')
        ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')

        ->where('people.organization_id', $organization_id)
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('people.first_name')
 		->get();
		$count_customer=count($total_customer);
	

		/*$total_sales = DB::table('transactions')
						   ->where('organization_id', $organization_id)->where('approval_status', 1)
						   ->whereIn('transaction_type_id', [$transaction_id, $transaction_cash])->sum('total');*/

		$total_sales = Transaction::select(DB::raw('SUM((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_amount'))
		->leftjoin('account_entries','account_entries.id','=','transactions.entry_id')
		->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')
		->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
		->where('transactions.organization_id',$organization_id)
		->where('approval_status', 1)
		->where('account_voucher_types.name','=', "job_invoice")
		->wherebetween('wms_transactions.job_date', [$six_month ,$today])
		->where('transactions.deleted_at','=',NULL)
		->first();
		
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
		    	left join account_entries on account_entries.id = transactions.entry_id
			WHERE transactions.organization_id = $organization_id 
			    AND account_entries.date BETWEEN '$six_month' AND '$today' AND transactions.approval_status = 1 
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

		/*$new_order = Transaction::select('transactions.id')
		->whereIn('transaction_type_id', [$sale_order_id])
		->where('approval_status', 1)
		->where('organization_id', $organization_id)
		->count('id');*/
	$vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id');
      
        

        $vehicles_register->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        $vehicles_register->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });

       
        $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');

        

        $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');

        $vehicles_register->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');

        $vehicles_register->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_register_details.vehicle_model_id');

        $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

        $vehicles_register->leftJoin('vehicle_body_types', 'vehicle_body_types.id','=','vehicle_register_details.vehicle_body_type_id');

        $vehicles_register->leftJoin('vehicle_rim_types', 'vehicle_rim_types.id','=','vehicle_register_details.vehicle_rim_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_types', 'vehicle_tyre_types.id','=','vehicle_register_details.vehicle_tyre_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_sizes', 'vehicle_tyre_sizes.id','=','vehicle_register_details.vehicle_tyre_size_id');

        $vehicles_register->leftJoin('vehicle_wheels', 'vehicle_wheels.id','=','vehicle_register_details.vehicle_wheel_type_id');

        $vehicles_register->leftJoin('vehicle_drivetrains', 'vehicle_drivetrains.id','=','vehicle_register_details.vehicle_drivetrain_id');

        $vehicles_register->leftJoin('vehicle_fuel_types', 'vehicle_fuel_types.id','=','vehicle_register_details.fuel_type_id');

        $vehicles_register->leftJoin('vehicle_usages', 'vehicle_usages.id','=','vehicle_register_details.vehicle_usage_id');

        $vehicles_register->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');

        $vehicles_register->where('vehicle_register_details.status', '1');

        $vehicles_register->where('wms_vehicle_organizations.organization_id', $organization_id);

        $vehicles_register->orderby('vehicle_register_details.id');

        

        $vehicles_registers = $vehicles_register->count('wms_vehicle_organizations.id');
// ********    Calculation for New Orders Ends   ********

// ********    Calculation of Graphical data for Sales Begins   ********

		$sale_datas = DB::table('sales')->select('month',DB::raw('SUM(IF(category = "service",total,0))AS service_amount'),DB::raw('SUM(IF(category = "goods",total,0))AS goods_amount'))->where('organization_id','=',$organization_id)->wherebetween('job_date', [$six_month ,$today])->groupby(DB::raw('MONTH(job_date)'))->get();


		$sale_data = [];
        foreach ($sale_datas as $value) {
            $sale_data[] = [$value->month,(float)$value->goods_amount,(float)$value->service_amount];

        }
        $sale = $sale_data;
        $headers = ["Month","Goods","Services"];
        $header = [$headers];
        $mergevalues = array_merge($header, $sale);
        $sale_value = json_encode($mergevalues);

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

		
	
		return view('trade_wms.dashboard', compact('top_customers', 'customers_names', 'customers_total_value', 'count_customer', 'total_sales', 'total_receivables',
		 'vehicles_registers', 'sale_value','today','six_month','six_month_view','today_view','state','city','title','payment','terms','group_name'));
	}

	public function search_index(Request $request)
	{
		
		//dd($request->all());

		$organization_id = Session::get('organization_id');
		
		if($request->ajax())
		{


		
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
		//dd($request->from_date);
		$six_month_view = $request->input('from_date');
		$today_view =$request->input('to_date');
		if($request->input('from_date'))
 		{
 			$from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
 		}
 	

 		if($request->input('to_date'))
 		{
			$to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
		}


		$total_sales = Transaction::select(DB::raw('SUM((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_amount'))
		->leftjoin('account_entries','account_entries.id','=','transactions.entry_id')
		->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
		->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')
		->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
		->where('transactions.organization_id',$organization_id)
		->where('approval_status', 1)
		->where('account_voucher_types.name','=', "job_invoice")
		 ->wherebetween('wms_transactions.job_date',[$from_date ,$to_date])
		->where('transactions.deleted_at','=',NULL)
		->first();
						 
		//dd($total_sales);


		$top_customers = Transaction::select('transactions.id', 'transactions.entry_id', 'transactions.date', 'transactions.transaction_type_id', 'transactions.name', DB::raw('SUM((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total'))
		->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
		->where('transactions.organization_id', $organization_id)
		->wherebetween('wms_transactions.job_date', [$from_date ,$to_date])
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

			

		$total_receivable = DB::select("SELECT SUM(balance) AS total FROM (
	  	SELECT 
	    	transactions.id,
	     	IF(
	      		(SELECT SUM(account_transactions.amount) FROM account_entries 
	        		LEFT JOIN account_transactions 
	          			ON account_transactions.entry_id = account_entries.id 
	      			WHERE account_entries.reference_transaction_id = transactions.id 
	        		AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher) ) IS NULL,
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
		    	left join account_entries on account_entries.id = transactions.entry_id
			WHERE transactions.organization_id = $organization_id 
			AND account_entries.date BETWEEN '$from_date' 
    AND '$to_date' 
			   AND transactions.approval_status = 1 
			    AND transactions.transaction_type_id = $transaction_id

			    AND transactions.deleted_at IS NULL 
			    GROUP BY transactions.id
		 	) AS trans");
		//dd($total_receivable);

		$total_receivables = $total_receivable[0]->total;


		$new_order = VehicleRegisterDetail::select('id')
		->where('organization_id',$organization_id)
		->count('id');

		
		$sale_datas = DB::table('sales')->select('month',DB::raw('SUM(IF(category = "service",total,0))AS service_amount'),DB::raw('SUM(IF(category = "goods",total,0))AS goods_amount'))->where('organization_id','=',$organization_id)->wherebetween('job_date',[$from_date ,$to_date])->groupby(DB::raw('MONTH(job_date)'))->get();


		$sale_data = [];
        foreach ($sale_datas as $value) {
            $sale_data[] = [$value->month,(float)$value->goods_amount,(float)$value->service_amount];

        }
        $sale = $sale_data;
        $headers = ["Month","Goods","Services"];
        $header = [$headers];
        $mergevalues = array_merge($header, $sale);
        $sale_value = json_encode($mergevalues);
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
        
        $get_total_customer = People::select(DB::raw(count('people.id')))
        ->leftJoin('people_person_types', 'people_person_types.people_id','=','people.id')
        ->leftJoin('persons', 'persons.id','=','people.person_id')
        ->leftJoin('businesses', 'businesses.id','=','people.business_id')

        ->leftJoin('transactions', function($query) use($transaction_type) {
                $query->on('transactions.people_id','=','people.person_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })
        ->leftJoin('transactions AS business', function($query)  use($transaction_type){
                $query->on('business.people_id','=','people.business_id');
                $query->whereIn('transactions.transaction_type_id', $transaction_type);
        })

        ->leftJoin('account_ledgers', function($join) use($organization_id)
            {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id')

        ->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
            {
                $join->on('people.business_id', '=', 'business_ledgers.business_id')
                ->where('business_ledgers.organization_id', $organization_id);
            })
        ->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id')
        ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')

        ->where('people.organization_id', $organization_id)
        ->where('people_person_types.person_type_id', $person_type_id)
        ->groupBy('people.id')
        ->orderBy('people.first_name')
 		->get();

		$count_customer=count($get_total_customer);
	
		
		$vehicles_register = VehicleRegisterDetail::select('vehicle_register_details.id');     

        $vehicles_register->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

        $vehicles_register->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });

       
        $vehicles_register->leftJoin('vehicle_configurations', 'vehicle_configurations.id','=','vehicle_register_details.vehicle_configuration_id');

        $vehicles_register->leftJoin('vehicle_categories', 'vehicle_categories.id','=','vehicle_register_details.vehicle_category_id');

        $vehicles_register->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');

        $vehicles_register->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_register_details.vehicle_model_id');

        $vehicles_register->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');

        $vehicles_register->leftJoin('vehicle_body_types', 'vehicle_body_types.id','=','vehicle_register_details.vehicle_body_type_id');

        $vehicles_register->leftJoin('vehicle_rim_types', 'vehicle_rim_types.id','=','vehicle_register_details.vehicle_rim_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_types', 'vehicle_tyre_types.id','=','vehicle_register_details.vehicle_tyre_type_id');

        $vehicles_register->leftJoin('vehicle_tyre_sizes', 'vehicle_tyre_sizes.id','=','vehicle_register_details.vehicle_tyre_size_id');

        $vehicles_register->leftJoin('vehicle_wheels', 'vehicle_wheels.id','=','vehicle_register_details.vehicle_wheel_type_id');

        $vehicles_register->leftJoin('vehicle_drivetrains', 'vehicle_drivetrains.id','=','vehicle_register_details.vehicle_drivetrain_id');

        $vehicles_register->leftJoin('vehicle_fuel_types', 'vehicle_fuel_types.id','=','vehicle_register_details.fuel_type_id');

        $vehicles_register->leftJoin('vehicle_usages', 'vehicle_usages.id','=','vehicle_register_details.vehicle_usage_id');

        $vehicles_register->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');

        $vehicles_register->where('vehicle_register_details.status', '1');

        $vehicles_register->where('wms_vehicle_organizations.organization_id', $organization_id);

        $vehicles_register->orderby('vehicle_register_details.id');

        $vehicles_registers = $vehicles_register->count('wms_vehicle_organizations.id');
		
		
		return view('trade_wms.dashboard_search', compact('top_customers', 'customers_names', 'customers_total_value', 'total_customer', 'total_sales', 'total_receivables', 'new_order', 'sale_value','today','six_month','six_month_view','today_view','count_customer','vehicles_registers'))->render();
	}
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
	public function job_status(Request $request)
	{
		$organization_id = Session::get('organization_id');
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
           
                      $pie_chart = [];
              foreach ($pie_charts as $value) {
            $pie_chart[] = [$value->display_name,(int)$value->count];

        }
        $pie_charts = $pie_chart;
        $array1 = ["JC Status","Job Card"];
        $a1 = [$array1];
        $mergearray = array_merge($a1, $pie_charts);
        $pie_chart_value = json_encode($mergearray);

        $bar_charts = HrmEmployee::select('hrm_employees.first_name',DB::raw('SUM(transaction_items.job_item_status = "1") AS open'),DB::raw('SUM(transaction_items.job_item_status = "2") AS closed'),DB::raw('SUM(transaction_items.job_item_status = "3") AS on_hold'),DB::raw('SUM(transaction_items.job_item_status = "4") AS progress'));
        	$bar_charts->leftjoin('transaction_items','transaction_items.assigned_employee_id','=','hrm_employees.id' );
        	$bar_charts->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id');
        	$bar_charts->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
        	$bar_charts->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        	$bar_charts->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id');
        	$bar_charts->where('hrm_employees.organization_id',$organization_id);
        	$bar_charts->where(function ($query) {
            					$query->where('account_vouchers.name',"job_card")
                  					  ->orWhere('account_vouchers.name','=',NULL);
        						});
        	$bar_charts->where(function ($query) {
            					$query->where('vehicle_jobcard_statuses.id','!=',8)
                  					  ->orWhere('vehicle_jobcard_statuses.id','=',NULL);
        						});
        	$bar_charts->where('transactions.deleted_at','=',null);
        	$bar_charts->groupby('hrm_employees.id');
        	$charts = $bar_charts->get();
                    $bar_chart = [];
            foreach ($charts as $value) {
            $bar_chart[] = [$value->first_name,(int)$value->open,(int)$value->closed,(int)$value->on_hold,(int)$value->progress];

        	}
        $chart_values = $bar_chart;
        $headers = ["Employee","Open","Closed","On Hold","Progress"];
        $header = [$headers];
        $mergevalues = array_merge($header, $chart_values);
        $bar_chart_value = json_encode($mergevalues);
        
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
          //$colors = ["#ff9933","#33cc33","#ff3300","#ffcc00"];
              foreach ($charts as $value) {
            $chart[] = [$value->registration_no,(int)$value->open,(int)$value->closed,(int)$value->on_hold,(int)$value->progress];

        }
        $charts = $chart;
        //dd($charts);
        $headers2 = ["Vehicle","Open","Closed","On Hold","Progress"];
        $header2 = [$headers2];
        $mergevalues2 = array_merge($header2, $charts);
        $bar_chart_value2 = json_encode($mergevalues2);
       // dd($bar_chart_value2);
       $tables = Transaction::select('transactions.id','transactions.order_no','vehicle_register_details.registration_no','inventory_items.name as item','hrm_employees.first_name as assigned_to','transaction_items.start_time as from','transaction_items.end_time as to')
       ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
       ->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')
       ->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id')
       ->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')
       ->leftjoin('hrm_employees','hrm_employees.id','=','transaction_items.assigned_employee_id')
       ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
       ->leftjoin('vehicle_job_item_statuses','vehicle_job_item_statuses.id','=','transaction_items.job_item_status')
       ->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')
       ->where('transactions.organization_id',$organization_id)
       ->where('account_vouchers.name','=',"job_card")
       ->where('vehicle_job_item_statuses.id',"3")
       ->where('vehicle_jobcard_statuses.id','!=','8')
       ->where('transactions.deleted_at',null)->get(); 
		return view('trade_wms.jobstatus_dashboard',compact('box1','box2','box3','box4','tables','pie_chart_value','bar_chart_value','bar_chart_value2','state','city','title','payment','terms','group_name'));
		
	}
}