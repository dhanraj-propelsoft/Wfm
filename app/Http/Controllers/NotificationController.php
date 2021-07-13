<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountLedger;
use App\Organization;
use App\AccountGroup;
use App\AccountEntry;
use App\PaymentMode;
use App\Transaction;
use Carbon\Carbon;
use App\User;
use Session;
use Auth;
use DB;

class NotificationController extends Controller
{
	public function notifications()
	{
		$notifications = [];
		$time = [];

		$organization_id = Session::get('organization_id');
		$business = Organization::find($organization_id)->business_id;

		$user = User::findorFail(Auth::id());

		$transactions = Transaction::select('organizations.name AS organization', 'account_vouchers.name AS voucher', 'account_vouchers.display_name AS voucher_type','transactions.id','transactions.order_no','transactions.reference_no','transactions.user_type', 'transactions.people_id', 'transactions.date','transactions.due_date', 'transactions.transaction_type_id', 'transactions.payment_mode_id', 'transactions.term_id','transactions.employee_id','transactions.description','transactions.billing_address', 'transactions.shipping_address', 'transactions.shipment_mode_id', 'transactions.shipping_date', 'transactions.sub_total','transactions.discount','transactions.discount_is_percent','transactions.total', 'transactions.notification_status AS status','transactions.updated_at','transactions.created_at')
		->leftJoin('businesses', function($join){
		    $join->on('businesses.id', '=', 'transactions.people_id')
		         ->where('transactions.user_type', '=', 1);
		})
		->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id')
		->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
		->where('transactions.people_id', $business)
		->where('transactions.user_type', 1)
		->where('transactions.notification_status', '!=', 0)
		->where('transactions.approval_status', 1)
		->whereNotIn('account_vouchers.name', ['purchases', 'goods_receipt_note'])
		->whereNotNull('businesses.id')->get();

		$receipts = AccountEntry::select('account_entries.id', 'account_vouchers.name AS type', 'account_entries.date', DB::raw('SUM(account_transactions.amount) AS amount'), 'organizations.name AS organization', 'account_entries.updated_at','transactions.people_id',DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS payment_date'),'account_entries.created_at')
		->leftJoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
		->leftJoin('transactions', 'transactions.id', '=', 'account_entries.reference_transaction_id')
		->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
		->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id')
		->whereIn('account_vouchers.name', ['payment', 'receipt'])
		->where('transactions.user_type', '1')
		->where('transactions.people_id', $business)
		->where('account_entries.status', '1')
		->groupby('account_entries.id')
		->get();


		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name', 'account_ledgers.updated_at','account_ledgers.notification_status')
	  		->where('account_ledgers.organization_id', $organization_id)
	        ->where('account_ledgers.approval_status', 0)
	        ->where('account_ledgers.status', 1)
	        ->orderBy('account_ledgers.name', 'asc')
	        ->get();

        $groups = AccountGroup::select('account_groups.id', 'account_groups.display_name', 'account_groups.updated_at')
	  		->where('account_groups.organization_id', $organization_id)
	        ->where('account_groups.approval_status', 0)
	        ->where('account_groups.status', 1)
	        ->orderBy('account_groups.name', 'asc')
	        ->get();

	       

        if($user->can('inventory') || $user->can('trade') || $user->can('trade_wms')) {

        	foreach ($receipts as $receipt) {

        		//dd($receipt);

        		$type = ($receipt->type == 'payment') ? 'receipt' : 'payment';

				$notifications[] = ["id" => $receipt->id, "type" => $type, "category" => $receipt->type, "message" => $receipt->organization." has created ".$receipt->type. " for ".$receipt->amount. " rupees", "time" => Carbon::parse($receipt->created_at)->diffForHumans(), "actual_time" => Carbon::parse($receipt->updated_at)->format('Y-m-d H:m:s'), 'user_type' => 1, 'people_id' => $receipt->people_id, 'total' => $receipt->amount, 'date' => $receipt->payment_date ];
			}
			//dd($type);

			foreach ($transactions as $transaction) {

				$voucher_type = $transaction->voucher_type;

				//dd($transaction->status);

				if(preg_match('/^[aeiou]/i', $voucher_type)) {					
					$voucher_type = "an ".strtolower($transaction->voucher_type);
				}

				$notifications[] = ["id" => $transaction->id, "type" => "trade", "category" => $transaction->voucher, "message" => $transaction->organization." has created ".$voucher_type. " for ".$transaction->total. " rupees", "time" => Carbon::parse($transaction->created_at)->diffForHumans(), "actual_time" => Carbon::parse($transaction->updated_at)->format('Y-m-d H:m:s'),"notification_status" => $transaction->status];
			}
		}

		if($user->can('books')) {
			foreach ($ledgers as $ledger) {
				$notifications[] = ["id" => $ledger->id, "type" => "accounts", "category" => "ledger", "message" => $ledger->display_name. " ledger has been waiting for your approval ", "time" => Carbon::parse($ledger->created_at)->diffForHumans(), "actual_time" => Carbon::parse($ledger->updated_at)->format('Y-m-d H:m:s'),'notification_status' => $ledger->notification_status];
			}

			foreach ($groups as $group) {
				$notifications[] = ["id" => $group->id, "type" => "accounts", "category" => "group", "message" => $group->display_name. " ledger group has been waiting for your approval", "time" => Carbon::parse($group->created_at)->diffForHumans(), "actual_time" => Carbon::parse($group->updated_at)->format('Y-m-d H:m:s')];
			}
		}

		/*if($user->can('hrm')) {

		}*/
		

		foreach ($notifications as $key => $val) {
		    $time[$key] = $val['actual_time'];
		}

		array_multisort($time, SORT_DESC, $notifications);

		//dd($notifications);

		$payment = PaymentMode::where('status', 1)->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group')
		->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
		->whereIn('account_groups.name', ['cash'])
		->where('account_ledgers.organization_id', $organization_id)
		->where('account_ledgers.approval_status', '1')
		->where('account_ledgers.status', '1')
		->orderby('account_ledgers.id','asc')
		->pluck('name', 'id');


		return view('settings.notifications', compact('notifications', 'payment', 'ledgers'));
	}

    public function get_notifications(Request $request)
	{
		$notifications = [];
		$time = [];


		$organization_id = Session::get('organization_id');
		$business = Organization::find($organization_id)->business_id;
		$user = User::findorFail(Auth::id());

			//dd($user);

			/*$transactions = Transaction::select('organizations.name AS organization', 'account_vouchers.name AS voucher_name', 'account_vouchers.display_name AS voucher_type','transactions.id','transactions.order_no','transactions.reference_no','transactions.user_type', 'transactions.people_id', 'transactions.date','transactions.due_date', 'transactions.transaction_type_id', 'transactions.payment_mode_id', 'transactions.term_id','transactions.employee_id','transactions.description','transactions.billing_address', 'transactions.shipping_address', 'transactions.shipment_mode_id', 'transactions.shipping_date', 'transactions.sub_total','transactions.discount','transactions.discount_is_percent','transactions.total', 'transactions.notification_status AS status','transactions.updated_at')
			->leftJoin('businesses', function($join){
			    $join->on('businesses.id', '=', 'transactions.people_id')
			         ->where('transactions.user_type', '=', 1);
			})
			->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id')
			->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
			->where('account_vouchers.name','!=', 'purchases')
			->where('account_vouchers.name','!=', 'goods_receipt_note')
			->where('transactions.people_id', $business)
			->where('transactions.user_type', 1)
			->where('transactions.notification_status', 0)
			->where('transactions.approval_status', 1)
			->whereNotNull('businesses.id')->get();*/

			$transactions = Transaction::select('organizations.name AS organization', 'account_vouchers.name AS voucher', 'account_vouchers.display_name AS voucher_type','transactions.id','transactions.order_no','transactions.reference_no','transactions.user_type', 'transactions.people_id', 'transactions.date','transactions.due_date', 'transactions.transaction_type_id', 'transactions.payment_mode_id', 'transactions.term_id','transactions.employee_id','transactions.description','transactions.billing_address', 'transactions.shipping_address', 'transactions.shipment_mode_id', 'transactions.shipping_date', 'transactions.sub_total','transactions.discount','transactions.discount_is_percent','transactions.total', 'transactions.notification_status AS status','transactions.updated_at','transactions.created_at')
		->leftJoin('businesses', function($join){
		    $join->on('businesses.id', '=', 'transactions.people_id')
		         ->where('transactions.user_type', '=', 1);
		})
		->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id')
		->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'transactions.transaction_type_id')
		->where('transactions.people_id', $business)
		->where('transactions.user_type', 1)
		->where('transactions.notification_status', 1)
		->where('transactions.approval_status', 1)
		->whereNotIn('account_vouchers.name', ['purchases', 'goods_receipt_note'])
		->whereNotNull('businesses.id')->get();

		$receipts = AccountEntry::select('account_entries.id', 'account_vouchers.name AS type', 'account_entries.date', DB::raw('SUM(account_transactions.amount) AS amount'), 'organizations.name AS organization', 'account_entries.updated_at','transactions.people_id',DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS payment_date'),'account_entries.created_at')
		->leftJoin('account_transactions', 'account_transactions.entry_id', '=', 'account_entries.id')
		->leftJoin('transactions', 'transactions.id', '=', 'account_entries.reference_transaction_id')
		->leftJoin('account_vouchers', 'account_vouchers.id', '=', 'account_entries.voucher_id')
		->leftJoin('organizations', 'organizations.id', '=', 'transactions.organization_id')
		->whereIn('account_vouchers.name', ['payment', 'receipt'])
		->where('transactions.user_type', '1')
		->where('transactions.people_id', $business)
		->where('account_entries.status', '1')
		->groupby('account_entries.id')
		->get();

			$ledgers = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name', 'account_ledgers.updated_at')
      		->where('account_ledgers.organization_id', '=', $organization_id)
            ->where('account_ledgers.approval_status', '=', 0)
            ->where('account_ledgers.status', '=', 1)
            ->orderBy('account_ledgers.name', 'asc')
            ->get();

            $groups = AccountGroup::select('account_groups.id', 'account_groups.display_name', 'account_groups.updated_at')
      		->where('account_groups.organization_id', '=', $organization_id)
            ->where('account_groups.approval_status', '=', 0)
            ->where('account_groups.status', '=', 1)
            ->orderBy('account_groups.name', 'asc')
            ->get();

            if($user->can('inventory') || $user->can('trade') || $user->can('trade_wms')) {

        	foreach ($receipts as $receipt) {

        		

        		$type = ($receipt->type == 'payment') ? 'receipt' : 'payment';

				$notifications[] = ["id" => $receipt->id, "type" => $type, "category" => $receipt->type, "message" => $receipt->organization." has created ".$receipt->type. " for ".$receipt->amount. " rupees", "time" => Carbon::parse($receipt->created_at)->diffForHumans(), "actual_time" => Carbon::parse($receipt->updated_at)->format('Y-m-d H:m:s'), 'user_type' => 1, 'people_id' => $receipt->people_id, 'total' => $receipt->amount, 'date' => $receipt->payment_date ];
			}
			

			foreach ($transactions as $transaction) {

				$voucher_type = $transaction->voucher_type;

				//dd($transaction->status);

				if(preg_match('/^[aeiou]/i', $voucher_type)) {					
					$voucher_type = "an ".strtolower($transaction->voucher_type);
				}

				$notifications[] = ["id" => $transaction->id, "type" => "trade", "category" => $transaction->voucher, "message" => $transaction->organization." has created ".$voucher_type. " for ".$transaction->total. " rupees", "time" => Carbon::parse($transaction->created_at)->diffForHumans(), "actual_time" => Carbon::parse($transaction->updated_at)->format('Y-m-d H:m:s'),"notification_status" => $transaction->status];
			}
		}

			if($user->can('books')) {
				foreach ($ledgers as $ledger) {
					$notifications[] = ["id" => $ledger->id, "type" => "accounts", "message" => $ledger->display_name. " ledger has been waiting for your approval ", "time" => Carbon::parse($ledger->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($ledger->updated_at)->format('Y-m-d H:m:s')];
				}

				foreach ($groups as $group) {
					$notifications[] = ["id" => $group->id, "type" => "accounts", "message" => $group->display_name. " ledger group has been waiting for your approval", "time" => Carbon::parse($group->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($group->updated_at)->format('Y-m-d H:m:s')];
				}
			}

			/*if($user->can('hrm')) {

			}*/
			

			foreach ($notifications as $key => $val) {
			    $time[$key] = $val['actual_time'];
			}

			array_multisort($time, SORT_DESC, $notifications);

			//dd($notifications);

			return response()->json(["notifications" => $notifications, "total" => count($notifications)]);
	}

	public function discard_notifications(Request $request) {

		Transaction::where('id', $request->input('id'))->update(['notification_status' => 4]);
		return response()->json(["status" => $request->input('status')]);
	}

	public function ledger_notifications(Request $request) {

		AccountLedger::where('id', $request->input('id'))->update(['notification_status' => 4]);
		return response()->json(["status" => $request->input('status')]);
	}


	public function notification_status(Request $request) {
		
		$organization_id = Session::get('organization_id');
		$business = Organization::find($organization_id)->business_id;
		$user = User::findorFail(Auth::id());

		//Transaction::where('people_id', $business)->where('user_type','1')->update(['notification_status' => '4']);


		return response()->json(["status" => 1]);
	}
}
