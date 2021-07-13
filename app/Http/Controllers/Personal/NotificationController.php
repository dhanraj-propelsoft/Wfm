<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountVoucher;
use App\AccountEntry;
use App\Transaction;
use App\HrmSalary;
use Carbon\Carbon;
use Auth;
use DB;

class NotificationController extends Controller
{

	public function notifications()
	{
		$notifications = [];
		$time = [];

		$user_id = Auth::id();

		$bill = Transaction::select('transactions.id', 'businesses.alias AS business', DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), 'transactions.total AS amount', DB::raw('"expense" AS transaction_type'), DB::raw('"" AS image'), 'transactions.order_no', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date', 'account_vouchers.display_name AS voucher_type'), 'transactions.updated_at', 'transactions.notification_status');
		$bill->leftJoin('people', function($join)
		{
			$join->on('people.person_id','=', 'transactions.people_id')
			->where('transactions.user_type', '0');
		});

		$bill->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$bill->leftjoin('businesses','businesses.id','=','organizations.business_id');
		$bill->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');

		$bill->leftJoin('personal_transactions', function($join)
		{
			$join->on('personal_transactions.reference_id','=', 'transactions.id')
			->where('personal_transactions.type', 'transaction');
		});

		$bill->where('people.person_id', Auth::user()->person_id);
		$bill->whereIn('account_vouchers.name', ['sales_cash', 'sales','job_invoice','job_invoice_cash']);
		$bill->where('transactions.notification_status', '!=', 0);
		$bill->where('transactions.approval_status', 1);
		$bill->groupby('transactions.id');
		$bill->orderby('transactions.date','desc');
		$bills = $bill->get();

		$salaries =  HrmSalary::select('hrm_salaries.id', 'hrm_salaries.salary_date', 'hrm_salaries.gross_salary', 'businesses.alias AS business', 'hrm_salaries.updated_at', 'hrm_salaries.notification_status')
		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
		->leftjoin('organizations', 'organizations.id', '=', 'hrm_salaries.organization_id')
		->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id', 'transactions.notification_status')
		->leftJoin('personal_transactions', function($join)
		{
			$join->on('personal_transactions.reference_id','=', 'hrm_salaries.id')
			->where('personal_transactions.type', 'salary');
		})
		->where('hrm_employees.person_id', Auth::user()->person_id)
		->whereNull('personal_transactions.reference_id')
		->get();

		$payment = AccountEntry::select('account_entries.id',
		'businesses.alias',
		'account_entries.voucher_no',
		DB::raw('IF(personal_transactions.reference_id IS NULL, 0, 1) AS notification_status'),
		'account_entries.date',
		'account_entries.voucher_id',
		'account_entries.voucher_id',
		'payment_modes.name AS payment_mode',
		'account_entries.reference_transaction_id',
		'account_vouchers.name AS voucher_name',
		DB::raw('SUM(account_transactions.amount) AS total'),
		'account_entries.updated_at');
		$payment->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$payment->leftjoin('account_transactions','account_entries.id','=','account_transactions.entry_id');
		$payment->leftjoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id');

		$payment->leftjoin('transactions','transactions.id','=','account_entries.reference_transaction_id');

		$payment->leftjoin('personal_transactions','account_entries.id','=','personal_transactions.reference_id');

		$payment->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$payment->leftjoin('businesses','businesses.id','=','organizations.business_id');

		$payment->whereIn('account_vouchers.name', ['receipt', 'payment']);
		$payment->where('transactions.people_id', Auth::user()->person_id);
		$payment->where('transactions.user_type', 0);
		$payment->groupby('account_entries.id');
		$payments = $payment->get();
		

		foreach ($payments as $payment_data) {

			if($payment_data->voucher_name == 'receipt' && $payment_data->payment_mode == 'credit_card') {
				$type = "liability";
				$payment_type = "Receipt";
			} else if($payment_data->voucher_name == 'receipt') {
				$type = "expense";
				$payment_type = "Receipt";
			} else if($payment_data->voucher_name == 'payment') {
				$type = "income";
				$payment_type = "Payable";
			}

			$notifications[] = ["id" => $payment_data->id, "type" => $type, "message" => 
			"Received \"".$payment_type. "\" from ".$payment_data->business." for Rs \"".$payment_data->total."\" on \"".Carbon::parse($payment_data->date)->format('d/m/Y')."\"", "time" => Carbon::parse($payment_data->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($payment_data->updated_at)->format('Y-m-d H:m:s'), "date" => $payment_data->date, "amount" => $payment_data->total, 'status' => $payment_data->notification_status];
		}

		

		foreach ($bills as $bill_data) {

			$notifications[] = ["id" => $bill_data->id, "type" => "expense", "message" => 
			"Received \"Invoice\" from ".$bill_data->business." for Rs \"".$bill_data->amount."\" on \"".Carbon::parse($bill_data->original_date)->format('d/m/Y')."\"", "time" => Carbon::parse($bill_data->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($bill_data->updated_at)->format('Y-m-d H:m:s'), "date" => $bill_data->date, "amount" => $bill_data->amount, 'status' => $bill_data->notification_status];
		}

		foreach ($salaries as $salary) {

			$notifications[] = ["id" => $salary->id, "type" => "income", 

			"message" => "Received \"Invoice\" from ".$salary->business." for Rs \"".$salary->gross_salary."\" on \"".Carbon::parse($salary->salary_date)->format('d/m/Y')."\"",


			"time" => Carbon::parse($salary->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($salary->updated_at)->format('Y-m-d H:m:s'), "date" => Carbon::parse($salary->salary_date)->format('Y-m-d H:m:s'), "amount" => $salary->gross_salary, 'status' => $salary->notification_status];
		}


		foreach ($salaries as $salary) {

			$notifications[] = ["id" => $salary->id, "type" => "income", 

			"message" => "Received \"Invoice\" from ".$salary->business." for Rs \"".$salary->gross_salary."\" on \"".Carbon::parse($salary->salary_date)->format('d/m/Y')."\"",


			"time" => Carbon::parse($salary->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($salary->updated_at)->format('Y-m-d H:m:s'), "date" => Carbon::parse($salary->salary_date)->format('Y-m-d H:m:s'), "amount" => $salary->gross_salary, 'status' => $salary->notification_status];
		}
		

		foreach ($notifications as $key => $val) {
			$time[$key] = $val['actual_time'];
		}

		array_multisort($time, SORT_DESC, $notifications);

		return view('personal.notifications', compact('notifications'));
	}

	public function get_notifications(Request $request)
	{  
		$notifications = [];
		$time = [];

		$user_id = Auth::id();

		$bill = Transaction::select('transactions.id', 'businesses.alias AS business', DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), 'transactions.total AS amount', DB::raw('"expense" AS transaction_type'), DB::raw('"" AS image'), 'transactions.order_no', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date'), 'account_vouchers.display_name AS voucher_type', 'transactions.updated_at');
		$bill->leftJoin('people', function($join)
		{
			$join->on('people.person_id','=', 'transactions.people_id')
			->where('transactions.user_type', '0');
		});

		$bill->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$bill->leftjoin('businesses','businesses.id','=','organizations.business_id');
		$bill->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');

		$bill->leftJoin('personal_transactions', function($join)
		{
			$join->on('personal_transactions.reference_id','=', 'transactions.id')
			->where('personal_transactions.type', 'transaction');
		});

		$bill->where('people.person_id', Auth::user()->person_id);
		$bill->whereIn('account_vouchers.name', ['sales_cash', 'sales','job_invoice','job_invoice_cash']);
		$bill->where('transactions.notification_status', '!=', 0);
		$bill->where('transactions.approval_status', 1);
		$bill->groupby('transactions.id');
		$bill->orderby('transactions.date','desc');
		$bills = $bill->get();

		$salaries =  HrmSalary::select('hrm_salaries.salary_date', 'hrm_salaries.gross_salary', 'businesses.alias AS business', 'hrm_salaries.updated_at')
		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
		->leftjoin('organizations', 'organizations.id', '=', 'hrm_salaries.organization_id')
		->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
		->leftJoin('personal_transactions', function($join)
		{
			$join->on('personal_transactions.reference_id','=', 'hrm_salaries.id')
			->where('personal_transactions.type', 'salary');
		})
		->where('hrm_employees.person_id', Auth::user()->person_id)
		->whereNull('personal_transactions.reference_id')
		->get();

		$payment = AccountEntry::select('account_entries.id',
		'businesses.alias',
		'account_entries.voucher_no',
		DB::raw('IF(personal_transactions.reference_id IS NULL, 0, 1) AS notification_status'),
		'account_entries.date',
		'account_entries.voucher_id',
		'account_entries.payment_mode_id',
		'account_entries.reference_transaction_id',
		'account_vouchers.name AS voucher_name',
		DB::raw('SUM(account_transactions.amount) AS total'),
		'account_entries.updated_at');
		$payment->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$payment->leftjoin('account_transactions','account_entries.id','=','account_transactions.entry_id');

		$payment->leftjoin('transactions','transactions.id','=','account_entries.reference_transaction_id');

		$payment->leftjoin('personal_transactions','account_entries.id','=','personal_transactions.reference_id');

		$payment->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$payment->leftjoin('businesses','businesses.id','=','organizations.business_id');

		$payment->whereIn('account_vouchers.name', ['receipt', 'payment']);
		$payment->where('transactions.people_id', Auth::user()->person_id);
		$payment->where('transactions.user_type', 0);
		$payment->groupby('account_entries.id');
		$payments = $payment->get();

		foreach ($payments as $payment_data) {

			if($payment_data->voucher_name == 'receipt') {
				$type = "income";
				$payment_type = "Receipt";
			} else if($payment_data->voucher_name == 'payment') {
				$type = "expense";
				$payment_type = "Payable";
			}

			$notifications[] = ["id" => $payment_data->id, "type" => $type, "message" => 
			"Received \"".$payment_type. "\" from ".$payment_data->business." for Rs \"".$payment_data->total."\" on \"".Carbon::parse($payment_data->date)->format('d/m/Y')."\"", "time" => Carbon::parse($payment_data->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($payment_data->updated_at)->format('Y-m-d H:m:s'), "date" => $payment_data->date, "amount" => $payment_data->total, 'status' => $payment_data->notification_status];
		}

		foreach ($bills as $bill_data) {

			$notifications[] = ["id" => $bill_data->id, "type" => "expense", 

			"message" => "Received \"Invoice\" from ".$bill_data->business." for Rs \"".$bill_data->amount."\" on \"".Carbon::parse($bill_data->original_date)->format('d/m/Y')."\"",

			"time" => Carbon::parse($bill_data->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($bill_data->updated_at)->format('Y-m-d H:m:s')];
		}

		foreach ($salaries as $salary) {

			$notifications[] = ["id" => $salary->id, "type" => "income", 

			"message" => "Received \"Invoice\" from ".$salary->business." for Rs \"".$salary->gross_salary."\" on \"".Carbon::parse($salary->salary_date)->format('d/m/Y')."\"",

			"time" => Carbon::parse($salary->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($salary->updated_at)->format('Y-m-d H:m:s')];
		}

		foreach ($notifications as $key => $val) {
			$time[$key] = $val['actual_time'];
		}

		array_multisort($time, SORT_DESC, $notifications);

		return response()->json(["notifications" => $notifications, "total" => count($notifications)]);
	}
}
