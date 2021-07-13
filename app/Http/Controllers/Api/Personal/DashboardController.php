<?php

namespace App\Http\Controllers\Api\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalTransaction;
use App\PersonalCategory;
use App\Transaction;
use App\HrmSalary;
use Carbon\Carbon;
use App\Custom;
use App\User;
use DB;

class DashboardController extends Controller
{
    private $successStatus = 200;
	public function index()
	{
		$user_id = request('user_id');

		$start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
		$end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

		$person_id = User::find($user_id)->person_id;

		$bill = Transaction::select('transactions.id', DB::raw('COALESCE(businesses.alias, "") AS name'), DB::raw("'' as category_id"), DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), 'transactions.total AS amount', DB::raw('"expense" AS transaction_type'), DB::raw('"" AS image'), 'transactions.order_no', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date'), 'transactions.updated_at', 'transactions.notification_status', DB::raw('COALESCE(transactions.notification_status, "0") AS notification_status'));
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
        $bill->where('transactions.notification_status', '0');
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();


		$expense_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.user_id', $user_id)
		->where('personal_transaction_types.name', "expense")
        ->whereBetween('personal_transactions.date', [$start_date, $end_date])
		->groupby('personal_transactions.transaction_type')
		->first();
		
		$income_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.user_id', $user_id)
		->where('personal_transaction_types.name', "income")
        ->whereBetween('personal_transactions.date', [$start_date, $end_date])
		->groupby('personal_transactions.transaction_type')
		->first();

		$expense = ($expense_transaction != null) ? $expense_transaction->amount : 0;
		$income = ($income_transaction != null) ? $income_transaction->amount : 0;

		$data = [];
		$notifications = [];

		$expense_object = new \stdClass;
		$expense_object->label = "Expense";
		$expense_object->data = $expense;
		$expense_object->color = "#e5b437";

		$data[] = $expense_object;

		$income_object = new \stdClass;
		$income_object->label = "Income";
		$income_object->data = $income;
		$income_object->color = "#63d1f9";

		$data[] = $income_object;

		$transaction_status = "";
		$transaction_value = "0";
		$transaction_color = "#FFFFFF";

		if($income < $expense) {

			$transaction_status = "Loss";
			$transaction_value = Custom::two_decimal($expense - $income);
			$transaction_color = "#e85c25";

			$object = new \stdClass;
			$object->label = $transaction_status;
			$object->data = $transaction_value;
			$object->color = $transaction_color;
			$data[] = $object;

		} else if($income > $expense) {

			$transaction_status = "Cash-In-Hand";
			$transaction_value = Custom::two_decimal($income - $expense);
			$transaction_color = "#0a932a";

			$object = new \stdClass;
			$object->label = $transaction_status;
			$object->data = $transaction_value;
			$object->color = $transaction_color;
			$data[] = $object;
		}

		$top_expense_data = $data;



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

        foreach ($bills as $bill_data) {

        	$notifications[] = [
	        	"id" => $bill_data->id,
	            "category" => ($bill_data->name != null) ? $bill_data->name : "",
	            "category_id" => "",
	            "real_date" => Carbon::parse($bill_data->date)->format('Y-m-d'),
	            "date" => Carbon::parse($bill_data->date)->diffForHumans(),
	            "amount" => $bill_data->amount,
	            "transaction_type" => "expense",
	            "image" => "",
	            "order_no" =>  $bill_data->order_no,
	            "reference_id" =>  $bill_data->id,
	            "original_date" => Carbon::parse($bill_data->original_date)->format('d-m-Y'),
	            "message" => "Received \"Invoice\" from ".$bill_data->business." for Rs \"".$bill_data->amount."\" on \"".Carbon::parse($bill_data->original_date)->format('d/m/Y')."\"",
	            "notification_status" => "1"
            ];
		}


        foreach ($salaries as $salary) {

        	$notifications[] = [
	        	"id" => $salary->id,
	            "category" => ($salary->business != null) ? $salary->business : "",
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
	            "notification_status" => "1"
            ];
		}
		

		foreach ($notifications as $key => $val) {
		    $time[$key] = $val['original_date'];
		}
		
		if(count(array_filter($notifications)) > 0) {
			usort($notifications, function ($item1, $item2) {
				return $item2['real_date'] <=> $item1['real_date'];
			});	
		}

		$message['status'] =  '1';
		$message['expense'] =  ($expense_transaction) ? $expense_transaction->amount : "0.00";
		$message['income'] =  ($income_transaction != null) ? $income_transaction->amount : "0.00";
		$message['notifications'] =  array_slice($notifications, 0, 3);
		$message['chart'] =  $top_expense_data;
		$message['transaction_label'] =  $transaction_status;
		$message['transaction_value'] =  $transaction_value;
		$message['transaction_color'] =  $transaction_color;

		return response()->json($message, $this->successStatus);
	}

	public function getChart() {

		$user_id = request('user_id');

		$type = request('type');

		$start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
		$end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

		$data = [];

		if($type != "") {

			$transactions = PersonalCategory::select('personal_categories.name', DB::raw('SUM(personal_transactions.amount) AS amount'))
			->leftjoin('personal_transactions', 'personal_transactions.category_id', '=', 'personal_categories.id')
			->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_categories.transaction_type')
			->where('personal_transactions.user_id', $user_id)
			->where('personal_transaction_types.name', $type)
        	->whereBetween('personal_transactions.date', [$start_date, $end_date])
			->groupby('personal_categories.id')
			->orderby('personal_categories.name')
			->take(10)
			->get();

			foreach ($transactions as $transaction) {

				$object = new \stdClass;
				$object->label = $transaction->name;
				$object->data = $transaction->amount;

				$data[] = $object;
			}

		} else {

			$expense_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
			->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
			->where('personal_transactions.user_id', $user_id)
			->where('personal_transaction_types.name', "expense")
        	->whereBetween('personal_transactions.date', [$start_date, $end_date])
			->groupby('personal_transactions.transaction_type')
			->first();
			
			$income_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
			->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
			->where('personal_transactions.user_id', $user_id)
			->where('personal_transaction_types.name', "income")
        	->whereBetween('personal_transactions.date', [$start_date, $end_date])
			->groupby('personal_transactions.transaction_type')
			->first();

			$expense = ($expense_transaction != null) ? $expense_transaction->amount : 0;
			$income = ($income_transaction != null) ? $income_transaction->amount : 0;

			$expense_object = new \stdClass;
			$expense_object->label = "Expense";
			$expense_object->data = $expense;
			$expense_object->color = "#e5b437";

			$data[] = $expense_object;

			$income_object = new \stdClass;
			$income_object->label = "Income";
			$income_object->data = $income;
			$income_object->color = "#63d1f9";

			$data[] = $income_object;

			$transaction_status = "";
			$transaction_value = "0";
			$transaction_color = "#FFFFFF";

			if($income < $expense) {

				$transaction_status = "Loss";
				$transaction_value = Custom::two_decimal($expense - $income);
				$transaction_color = "#e85c25";

				$object = new \stdClass;
				$object->label = $transaction_status;
				$object->data = $transaction_value;
				$object->color = $transaction_color;
				$data[] = $object;

			} else if($income > $expense) {

				$transaction_status = "Cash-In-Hand";
				$transaction_value = Custom::two_decimal($income - $expense);
				$transaction_color = "#0a932a";

				$object = new \stdClass;
				$object->label = $transaction_status;
				$object->data = $transaction_value;
				$object->color = $transaction_color;
				$data[] = $object;
			}

		}

		$message['chart'] = $data;

		return response()->json($message, $this->successStatus);
		
	}
}
