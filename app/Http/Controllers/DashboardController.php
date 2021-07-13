<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountFinancialYear;
use App\PlanAccountType;
use App\AccountHead;
use App\Package;
use App\Custom;
use Session;
use Carbon;
use App\SystemMessageBroadcast;
use DB;
use App\Transaction;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
	public function index()
	{
		$organization_id = Session::get('organization_id');
		if($organization_id) {
			if(Custom::check_module_list()) {

				$account_type_id = PlanAccountType::where('name', 'business')->first();

				$packages = Package::select('packages.id', 'packages.name', 'packages.display_name', 'packages.image', DB::raw('GROUP_CONCAT(modules.display_name SEPARATOR " + ") AS modules'))->where('packages.status', '1')->leftjoin('package_modules', 'packages.id', '=', 'package_modules.package_id')->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')->where('packages.account_type_id', $account_type_id->id)->groupby('id')->get();

				$mytime = Carbon\Carbon::now();
				$today_date=$mytime->format('Y-m-d');

				$dashboard_news=SystemMessageBroadcast::Select('system_message_broadcasts.message')
				->where('message_type','!=','System Down')
				->where('valid_from','<=',$today_date)
				->where('valid_to','>=',$today_date)
				->where('active','=',1)
	    		->get();
	    		//dd($dashboard_news);
	    		$count_of_news = count($dashboard_news);

				return view('dashboard', compact('packages' ,'dashboard_news','count_of_news'));
			} else {

				$module = DB::table('module_organization')->select('modules.name')->leftjoin('modules', 'modules.id', '=', 'module_organization.module_id')->where('organization_id', $organization_id)->first()->name;

				switch ($module) {
					case 'books':
						return redirect()->route('books.dashboard');
						break;

					case 'hrm':
						return redirect()->route('hrm.dashboard');
						break;

					case 'wfm':
						return redirect()->route('wfm.dashboard');
						break;
					

					case 'inventory':
						return redirect()->route('inventory.dashboard');
						break;

					case 'trade':
						return redirect()->route('trade.dashboard');
						break;

					case 'trade_wms':
						return redirect()->route('trade_wms.dashboard');
						break;

					case 'wfm':
						return redirect()->route('wfm.dashboard');
						break;
					
					default:
						abort(403);
						break;
				}
			}
		} else {
			return redirect()->route('user.dashboard');
		}
		
		
	}
	public function message_show()
	{
			
			$mytime = Carbon\Carbon::now();
			$today_date=$mytime->format('Y-m-d');

			$dashboard_panel=SystemMessageBroadcast::Select('system_message_broadcasts.message')
				->where('message_type','=','System Down')
				->where('valid_from','<=',$today_date)
				->where('valid_to','>=',$today_date)
				->where('active','=',1)
	    		->first();
	    
		return response()->json(['result'=>	$dashboard_panel]);
	}
		public function order_no_search(Request $request){
		
		$module_name =	Session::get('module_name');

		//dd($module_name);

		$keyword = $request->input('term');

		$organization_id = Session::get('organization_id');

		//dd($organization_id);
			
		 $result = Transaction::select('transactions.order_no','transactions.id','modules.name')
		 		->leftjoin('account_vouchers', 'account_vouchers.id', '=', 
		 			'transactions.transaction_type_id')
		 		->leftjoin('account_voucher_types', 'account_voucher_types.id', '=', 'account_vouchers.voucher_type_id')
		 		->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
				->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')			
		 		 ->where('transactions.order_no','LIKE','%' . $keyword .'%') 
		 		 ->where('transactions.organization_id',$organization_id)
		 		 
		 		 ->where('modules.name','!=','fuel_station')
		 		 ->take(10)              
                 ->get();
                
                 //dd($result);
          $item_array = [];

		foreach ($result as  $value ) {

			$item_array[] = ['id' => $value->id, 'label' => $value->order_no ,'name' =>$value->order_no,'module'=>$value->name];
		}		       
          
		
		return response()->json($item_array);	
		

	}
}
