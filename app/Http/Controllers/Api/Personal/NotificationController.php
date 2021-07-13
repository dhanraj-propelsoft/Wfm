<?php

namespace App\Http\Controllers\Api\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransactionItem;
use App\AccountEntry;
use App\Transaction;
use App\HrmSalary;
use Carbon\Carbon;
use App\User;
use Auth;
use DB;

class NotificationController extends Controller
{
    private $successStatus = 200;

	public function index()
	{
		$user_id = request('user_id');

		$start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
		$end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

		$person_id = User::find($user_id)->person_id;

		$bill = Transaction::select('transactions.id', DB::raw('COALESCE(businesses.alias, "") AS category'), DB::raw("'' as category_id"), DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), 'transactions.total AS amount', DB::raw('"expense" AS transaction_type'), DB::raw('"" AS image'), 'transactions.order_no', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date'), 'transactions.notification_status', DB::raw('CONCAT("Voucher No.", transactions.order_no) AS message'), DB::raw('COALESCE(transactions.notification_status, "0") AS notification_status'));
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

        $bill->where('people.person_id', $person_id);
        $bill->whereIn('account_vouchers.name', ['sales_cash', 'sales']);
        $bill->whereBetween('transactions.date', [$start_date, $end_date]);
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();

		$message['status'] =  '1';
		$message['transactions'] = $bills;

		return response()->json($message, $this->successStatus);
	}

	public function cash_transactions()
	{
		$user_id = request('user_id');
		$type = request('type');

		$person_id = User::find($user_id)->person_id;

		$payment = AccountEntry::select('account_entries.id', 'payment_modes.name AS payment_mode',
		DB::raw('COALESCE(businesses.alias, "") AS category'),
		DB::raw("'' as category_id"),
		DB::raw('DATE_FORMAT(account_entries.date, "%d %b, %Y") AS date'),
		DB::raw('SUM(account_transactions.amount) AS amount'),
		DB::raw('

		CASE
		    WHEN account_vouchers.name = "receipt" AND payment_modes.name = "credit_card" THEN "liability"
		    WHEN account_vouchers.name = "receipt" THEN "expense"
		    WHEN account_vouchers.name = "payment" THEN "income"
		    ELSE "income"
		END

		 AS transaction_type'),
		 DB::raw('"" AS image'),
		'account_entries.voucher_no AS order_no',
		DB::raw('IF(personal_transactions.reference_id IS NULL, 0, 1) AS notification_status'),
		 DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(account_entries.date, "%d-%m-%Y") AS original_date'),
		 DB::raw('CONCAT("Voucher No.", account_entries.voucher_no) AS message'));
		$payment->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
		$payment->leftjoin('account_transactions','account_entries.id','=','account_transactions.entry_id');
		$payment->leftjoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id');

		$payment->leftjoin('transactions','transactions.id','=','account_entries.reference_transaction_id');

		$payment->leftjoin('personal_transactions','account_entries.id','=','personal_transactions.reference_id');

		$payment->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$payment->leftjoin('businesses','businesses.id','=','organizations.business_id');

		$payment->whereIn('account_vouchers.name', [$type]);
		$payment->where('transactions.people_id', $person_id);
		$payment->where('transactions.user_type', 0);
		$payment->groupby('account_entries.id');
		$payments = $payment->get();

		$message['status'] =  '1';
		$message['transactions'] = $payments;

		return response()->json($message, $this->successStatus);
	}


	public function notifications()
	{
		$notifications = [];
		$time = [];

		$user_id = request('user_id');

		$person_id = User::find($user_id)->person_id;



		$bill = Transaction::select('transactions.id', DB::raw('COALESCE(businesses.alias, "") AS category'), DB::raw("'' as category_id"), DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), 'transactions.total AS amount', DB::raw('"expense" AS transaction_type'), DB::raw('"" AS image'), 'transactions.order_no', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date'), 'transactions.updated_at', DB::raw('COALESCE(transactions.notification_status, "0") AS notification_status'));
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

        $bill->where('people.person_id', $person_id);
        $bill->whereIn('account_vouchers.name', ['sales_cash', 'sales']);
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();

		$notifications = [];

		$salaries =  HrmSalary::select('hrm_salaries.id', 'hrm_salaries.salary_date', 'hrm_salaries.gross_salary', 'businesses.alias AS business', 'hrm_salaries.updated_at', DB::raw('COALESCE(hrm_salaries.notification_status, "0") AS notification_status'))
		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
		->leftjoin('organizations', 'organizations.id', '=', 'hrm_salaries.organization_id')
		->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
		->leftJoin('personal_transactions', function($join)
        {
            $join->on('personal_transactions.reference_id','=', 'hrm_salaries.id')
            ->where('personal_transactions.type', 'salary');
        })
		->where('hrm_employees.person_id', $person_id)
		->whereNull('personal_transactions.reference_id')
		->get();

		$payment = AccountEntry::select('account_entries.id',
		DB::raw('COALESCE(businesses.alias, "") AS category'),
		DB::raw("'' as category_id"),
		'account_entries.voucher_no',
		DB::raw('IF(personal_transactions.reference_id IS NULL, 0, 1) AS notification_status'),
		'account_entries.date',
		'account_entries.voucher_id',
		'payment_modes.name AS payment_mode',
		'account_entries.payment_mode_id',
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

        	$notifications[] = [
	        	"id" => $payment_data->id,
	            "category" => $payment_data->category,
	            "category_id" => $payment_data->category_id,
	            "date" => Carbon::parse($payment_data->date)->diffForHumans(),
	            "real_date" => Carbon::parse($payment_data->date)->format('Y-m-d'),
	            "amount" => $payment_data->total,
	            "transaction_type" => $type,
	            "image" => "",
	            "order_no" =>  $payment_data->voucher_no,
	            "reference_id" =>  $payment_data->id,
	            "original_date" => Carbon::parse($payment_data->date)->format('d-m-Y'),
	            "message" => "Received \"".$payment_type."\" from ".$payment_data->category." for Rs \"".$payment_data->total."\" on \"".Carbon::parse($payment_data->date)->format('d/m/Y')."\"",
	            "notification_status" => $payment_data->notification_status
            ];
		}

        foreach ($bills as $bill_data) {

        	$notifications[] = [
	        	"id" => $bill_data->id,
	            "category" => $bill_data->category,
	            "category_id" => $bill_data->category_id,
	            "date" => Carbon::parse($bill_data->date)->diffForHumans(),
	            "real_date" => Carbon::parse($bill_data->date)->format('Y-m-d'),
	            "amount" => $bill_data->amount,
	            "transaction_type" => "expense",
	            "image" => "",
	            "order_no" =>  $bill_data->order_no,
	            "reference_id" =>  $bill_data->id,
	            "original_date" => Carbon::parse($bill_data->original_date)->format('d-m-Y'),
	            "message" => "Received \"Invoice\" from ".$bill_data->business." for Rs \"".$bill_data->amount."\" on \"".Carbon::parse($bill_data->original_date)->format('d/m/Y')."\"",
	            "notification_status" => $bill_data->notification_status
            ];
		}


        foreach ($salaries as $salary) {

        	$notifications[] = [
	        	"id" => $salary->id,
	            "category" => $salary->business,
	            "category_id" => "",
	            "date" => Carbon::parse($salary->salary_date)->diffForHumans(),
	            "real_date" => Carbon::parse($salary->salary_date)->format('Y-m-d'),
	            "amount" => $salary->gross_salary,
	            "transaction_type" => "income",
	            "image" => "",
	            "order_no" =>  "",
	            "reference_id" =>  $salary->id,
	            "original_date" => Carbon::parse($salary->salary_date)->format('d-m-Y'),
	            "message" => "Received \"Invoice\" from ".$salary->business." for Rs \"".$salary->gross_salary."\" on \"".Carbon::parse($salary->salary_date)->format('d/m/Y')."\"",
	            "notification_status" => $salary->notification_status
            ];
		}
		

		foreach ($notifications as $key => $val) {
		    $time[$key] = $val['real_date'];
		}
		
		if(count(array_filter($notifications)) > 0) {
			usort($notifications, function ($item1, $item2) {
				return $item2['real_date'] <=> $item1['real_date'];
			});	
		}

		$message['status'] =  '1';
		$message['notifications'] =  $notifications;

		return response()->json($message, $this->successStatus);
	}

	public function notification_count()
	{
		$notifications = [];
		$time = [];

		$user_id = request('user_id');

		$person_id = User::find($user_id)->person_id;

		$bill = Transaction::select('transactions.id');
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

        $bill->where('people.person_id', $person_id);
        $bill->whereIn('account_vouchers.name', ['sales_cash', 'sales']);
        $bill->where('transactions.notification_status', '0');
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();


        $payment = AccountEntry::select('account_entries.id');
		$payment->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $payment->leftjoin('personal_transactions','account_entries.id','=','personal_transactions.reference_id');
		$payment->leftjoin('transactions','transactions.id','=','account_entries.reference_transaction_id');

		$payment->whereIn('account_vouchers.name', ['receipt', 'payment']);
		$payment->where('transactions.people_id', Auth::user()->person_id);
		$payment->where('transactions.user_type', 0);
		$payment->whereNull('personal_transactions.reference_id');
		$payment->groupby('account_entries.id');
		$payments = $payment->get();


		$notifications = [];

		$salaries =  HrmSalary::select('hrm_salaries.id')
		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_salaries.employee_id')
		->leftjoin('organizations', 'organizations.id', '=', 'hrm_salaries.organization_id')
		->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
		->leftJoin('personal_transactions', function($join)
        {
            $join->on('personal_transactions.reference_id','=', 'hrm_salaries.id')
            ->where('personal_transactions.type', 'salary');
        })
		->where('hrm_employees.person_id', $person_id)
		->whereNull('personal_transactions.reference_id')
		->get();

		foreach ($payments as $payment) {

        	$notifications[] = [
	        	"id" => $payment->id
            ];
		}


        foreach ($bills as $bill_data) {

        	$notifications[] = [
	        	"id" => $bill_data->id
            ];
		}


        foreach ($salaries as $salary) {

        	$notifications[] = [
	        	"id" => $salary->id
            ];
		}
		


		$message['status'] =  '1';
		$message['notifications'] =  count($notifications);

		return response()->json($message, $this->successStatus);
	}


	public function view_bill()
	{
		$transactions = [];

		$id = request('id');

		$bill = Transaction::select('transactions.id', DB::raw('COALESCE(businesses.alias, "") AS business'), DB::raw("'' as category_id"), DB::raw("DATE_FORMAT(transactions.date, 'On %d %b, %Y') as date"), 'transactions.sub_total', 'transactions.total', 'transactions.notification_status');
        $bill->leftjoin('organizations','organizations.id','=','transactions.organization_id');
        $bill->leftjoin('businesses','businesses.id','=','organizations.business_id');
        $bill->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
        $bill->where('transactions.id', $id);

        $bill_array = $bill->first()->toArray();
        $bill_data = $bill->first();

        $bill_data->notification_status = 1;
        $bill_data->save();

        foreach ($bill_array as $key => $value) {
        	if($key != "id" && $key != "business" && $key != "date" && $key != "sub_total" && $key != "total" && $key != "notification_status" && $key != "category_id") {
        		$transactions[] = ["key" => $key, "value" => $value];
        	}
        	
        }

        $items = TransactionItem::select('inventory_items.name AS item', 'transaction_items.quantity', 'transaction_items.amount')
        ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
        ->where('transaction_items.transaction_id', $id)->get();

		$message['status'] =  '1';
		$message['business'] = $bill_data->business;
		$message['date'] = $bill_data->date;
		$message['sub_total'] = $bill_data->sub_total;
		$message['total'] = $bill_data->total;
		$message['transactions'] = $transactions;
		$message['taxes'] = [];
		$message['items'] = $items;

		return response()->json($message, $this->successStatus);
	}
}
