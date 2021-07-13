<?php

namespace App\Http\Controllers\Api\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalTransactionRecurring;
use App\PersonalTransactionType;
use App\PersonalTransaction;
use App\PersonalCategory;
use App\AccountLedger;
use Carbon\Carbon;
use DateInterval;
use App\Custom;
use DatePeriod;
use App\Weekday;
use DateTime;
use File;
use Auth;
use DB;

class BillController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	private $successStatus = 200;

	public function index()
	{
		$user_id = request('user_id');

		$recurrings = PersonalTransactionRecurring::select('personal_transaction_recurrings.start_date', 'personal_transaction_recurrings.end_date', 'personal_transaction_recurrings.end_occurence')->leftjoin('personal_transactions', 'personal_transaction_recurrings.id', '=', 'personal_transactions.id')->get();

		foreach ($recurrings as $key => $recurring) {

			$start_date = $recurring->start_date;

			if($recurring->end_date == null && $recurring->end_occurence == null) {
				
			} else if($recurring->end_date != null) {
				$end_date = $recurring->end_date;
			} else if($recurring->end_occurence != null) {
				
			}
			//print_r($recurring->end_date);
		}

		/*$period = new DatePeriod(new DateTime($start_date), new DateInterval('P1D'), (new DateTime($end_date))->modify('+1 day'));

		foreach ($period as $key => $value) {
			echo $value->format('Y-m-d');
		}

		$message['status'] =  '1';
		$message['data'] =  $transactions;

		return response()->json($message, $this->successStatus);*/
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user_id = request('user_id');

		$transaction_type = PersonalTransactionType::where('name', request('transaction_type'))->first();

		$transaction = new PersonalTransaction;
		$transaction->name = request('name');
		$transaction->category_id = request('category_id');
		$transaction->transaction_type = $transaction_type->id;
		$transaction->date = Carbon::parse($request->input('date'))->format('Y-m-d');
		$transaction->due_date = Carbon::parse($request->input('due_date'))->format('Y-m-d');
		$transaction->amount = request('amount');
		$transaction->description = request('description');  
		if(request('ledger_id') == "")
		{
			$ledger_id = AccountLedger::where('name', 'cash')->where('user_id', $user_id)->first()->id;
			$transaction->ledger_id = $ledger_id;
		}
		else {
			$transaction->ledger_id = request('ledger_id');
		}      
		$transaction->user_id = $user_id;
		$transaction->save();

		Custom::userby($transaction, true, $user_id);

		$ledger = PersonalCategory::find($transaction->category_id)->ledger_id;

		if($transaction_type->name == "expense") {
			$entry[] = ['debit_ledger_id' => $transaction->ledger_id, 'credit_ledger_id' => $ledger, 'amount' =>$transaction->amount];
			$transaction->entry_id = Custom::add_entry($transaction->date, $entry, null, 'payment', $user_id, 1, true);
		} else if($transaction_type->name == "income") {
			$entry[] = ['debit_ledger_id' => $ledger, 'credit_ledger_id' => $transaction->ledger_id, 'amount' => $transaction->amount];
			$transaction->entry_id = Custom::add_entry($transaction->date, $entry, null, 'receipt', $user_id, 1, true);
		}
		
		$transaction->save();

		if($transaction->id) {
			$recurring = new PersonalTransactionRecurring;
			$recurring->id = $transaction->id;
			$recurring->name = request('recurring_name');
			$recurring->interval = request('interval');
			$recurring->period = request('period');
			$recurring->week_day_id = request('week_day_id');
			$recurring->day = request('day');
			$recurring->start_date = (request('start_date') != null) ? Carbon::parse(request('start_date'))->format('Y-m-d') : $transaction->date;
			$recurring->end_date = (request('start_date') != null) ? Carbon::parse(request('end_date'))->format('Y-m-d') : null;
			$recurring->end_occurence = request('end_occurence');
			$recurring->frequency = request('frequency');
			$recurring->save();

			Custom::userby($transaction, true, $user_id);
		}



		return response()->json(['status' => 1, 'message' => 'Personal Transaction'.config('constants.flash.added'), 
			'data' => [
				'id' => $transaction->id, 
				'name' => $transaction->name, 
				'category_id' => $transaction->category_id, 
				'transaction_type' => $transaction->transaction_type, 
				'date' => Carbon::parse($transaction->date)->format('Y-m-d'), 
				'due_date' => Carbon::parse($transaction->due_date)->format('Y-m-d'),  
				'amount' => $transaction->amount, 
				'user_id' => $transaction->user_id]
			]);
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

	public function interval()
	{
		$interval = [['id' => '0', 'name' => 'Daily'], ['id' => '1', 'name' => 'Weekly'], ['id' => '2', 'name' => 'Monthly']];

		$period = [['id' => '0', 'name' => 'Day'], ['id' => '1', 'name' => 'First'], ['id' => '2', 'name' => 'Second'], ['id' => '3', 'name' => 'Third'], ['id' => '4', 'name' => 'Fourth'], ['id' => '5', 'name' => 'Last']];

		$week_days = Weekday::select('weekdays.id', 'weekdays.display_name AS name')->get();

		$days = [];
		for ($i=1; $i <= 28; $i++) { 
			$days[$i] = $i;
		}
		$days[0] = "Last";

		return response()->json(['status' => 1, 'data' => ['interval' => $interval, 'period' => $period, 'week_days' => $week_days, 'days' => $days ]], $this->successStatus);
	}

}
