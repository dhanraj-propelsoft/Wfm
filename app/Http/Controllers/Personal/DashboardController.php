<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalTransaction;
use App\PersonalCategory;
use App\Transaction;
use App\HrmSalary;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function index()
	{

		$expense_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.user_id', Auth::id())
		->where('personal_transaction_types.name', "expense")
		->groupby('personal_transactions.transaction_type')
		->first();
		
		$income_transaction = PersonalTransaction::select('personal_transactions.id', DB::raw('SUM(personal_transactions.amount) AS amount'))
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->where('personal_transactions.user_id', Auth::id())
		->where('personal_transaction_types.name', "income")
		->groupby('personal_transactions.transaction_type')
		->first();

		$expense = ($expense_transaction != null) ? $expense_transaction->amount : 0;
		$income = ($income_transaction != null) ? $income_transaction->amount : 0;

		/*$top_expenses = PersonalCategory::select('personal_categories.name', DB::raw('SUM(personal_transactions.amount) AS amount'))
		->leftjoin('personal_transactions', 'personal_transactions.category_id', '=', 'personal_categories.id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_categories.transaction_type')
		->where('personal_transactions.user_id', Auth::id())
		->where('personal_transaction_types.name', "expense")
		->groupby('personal_categories.id')
		->orderby('personal_categories.name')
		->take(10)
		->get();*/

		$data = [];
		$notifications = [];

		//foreach ($top_expenses as $list) {

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

			$transaction_status = null;
			$transaction_value = null;
			$transaction_color = null;

			

			if($income < $expense) {

				$transaction_status = "Loss";
				$transaction_value = $expense - $income;
				$transaction_color = "#e85c25";

				$object = new \stdClass;
				$object->label = $transaction_status;
				$object->data = $transaction_value;
				$object->color = $transaction_color ;
				$data[] = $object;

			} else if($income > $expense) {

				$transaction_status = "Profit";
				$transaction_value = $income - $expense;
				$transaction_color = "#0a932a";

				$object = new \stdClass;
				$object->label = $transaction_status;
				$object->data = $transaction_value;
				$object->color = $transaction_color ;
				$data[] = $object;
			}

			
		//}

		$top_expense_data = json_encode($data);

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
        $bill->where('transactions.notification_status', '0');
        $bill->where('people.person_id', Auth::user()->person_id);
        $bill->whereIn('account_vouchers.name', ['sales_cash', 'sales']);
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();



        foreach ($bills as $bill_data) {

        	$notifications[] = ["id" => $bill_data->id, "type" => "expense", 
        	"message" => "Received \"Invoice\" from ".$bill_data->business." for Rs \"".$bill_data->amount."\" on \"".Carbon::parse($bill_data->original_date)->format('d/m/Y')."\"",
        	"time" => Carbon::parse($bill_data->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($bill_data->updated_at)->format('Y-m-d H:m:s')];
		}



        foreach ($salaries as $salary) {

			$notifications[] = ["id" => $salary->id, "type" => "income", 
			"message" => "Received \"Invoice\" from ".$salary->business." for Rs \"".$salary->gross_salary."\" on \"".Carbon::parse($salary->salary_date)->format('d/m/Y')."\"",
			"amount" => $salary->gross_salary, "date" => $salary->salary_date, "time" => Carbon::parse($salary->updated_at)->diffForHumans(), "actual_time" => Carbon::parse($salary->updated_at)->format('Y-m-d H:m:s')];
		}
		

		foreach ($notifications as $key => $val) {
		    $time[$key] = $val['actual_time'];
		}
		
		if(count(array_filter($notifications)) > 0) {
			array_multisort($time, SORT_DESC, $notifications);
		}
		

		return view('personal.dashboard', compact('expense', 'income', 'top_expense_data', 'salaries', 'notifications', 'transaction_status', 'transaction_value', 'transaction_color'));
	}
}
