<?php

namespace App\Http\Controllers\Api\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalTransactionType;
use App\PersonalTransaction;
use App\AccountLedgerType;
use App\PersonalCategory;
use App\PersonalAccount;
use App\AccountLedger;
use App\AccountEntry;
use App\AccountGroup;
use App\Transaction;
use Carbon\Carbon;
use App\Custom;
use App\User;
use File;
use Auth;
use URL;
use DB;

class TransactionContoller extends Controller
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

		$start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
		$end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

		$transactions = PersonalTransaction::select('personal_transactions.id', DB::raw('COALESCE(personal_categories.name, "") AS category'), 'personal_categories.id AS category_id', DB::raw('DATE_FORMAT(personal_transactions.date, "%d %b, %Y") AS date'), DB::raw('SUM(personal_transactions.amount) AS amount'), 'personal_transaction_types.name AS transaction_type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'), DB::raw('COALESCE(personal_transactions.source, "") AS source'),  DB::raw('COALESCE(personal_transactions.description, "") AS description'), DB::raw('DATE_FORMAT(personal_transactions.date, "%d-%m-%Y") AS original_date'), DB::raw('COALESCE(personal_accounts.name, "") AS account'), DB::raw('"" AS message'), DB::raw('"1" AS notification_status'))
		->leftjoin('personal_categories', 'personal_categories.id', '=', 'personal_transactions.category_id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->leftjoin('personal_accounts', 'personal_accounts.id', '=', 'personal_transactions.account_id')
		->where('personal_transactions.user_id', $user_id)
        ->whereBetween('personal_transactions.date', [$start_date, $end_date])
		->groupby('personal_categories.id')
		->get();

		$message['status'] =  '1';
		$message['transactions'] =  $transactions;

		return response()->json($message, $this->successStatus);
	}

	public function transactions()
	{
		$start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
		$end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

		$transactions = PersonalTransaction::select('personal_transactions.id', DB::raw('COALESCE(personal_categories.name, "") AS category'), 'personal_categories.id AS category_id', DB::raw('DATE_FORMAT(personal_transactions.date, "%d %b, %Y") AS date'), DB::raw('SUM(personal_transactions.amount) AS amount'), 'personal_transaction_types.name AS transaction_type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'), DB::raw('COALESCE(personal_transactions.source, "") AS source'),  DB::raw('COALESCE(personal_transactions.description, "") AS description'), DB::raw('DATE_FORMAT(personal_transactions.date, "%d-%m-%Y") AS original_date'), DB::raw('COALESCE(personal_accounts.name, "") AS account'), DB::raw('"" AS message'), DB::raw('"1" AS notification_status'))
		->leftjoin('personal_categories', 'personal_categories.id', '=', 'personal_transactions.category_id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->leftjoin('personal_accounts', 'personal_accounts.id', '=', 'personal_transactions.account_id')
		->where('personal_categories.id', request('category_id'))
        ->whereBetween('personal_transactions.date', [$start_date, $end_date])
		->groupby('personal_transactions.id')
		->get();

		$message['status'] =  '1';
		$message['transactions'] =  $transactions;

		return response()->json($message, $this->successStatus);
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
	public function store()
	{
		//return response()->json(request());
		$user_id = request('user_id');

		if(request('reference_id') != null) {
			$reference_transaction = Transaction::find(request('reference_id'));
			if($reference_transaction != null) {
				$reference_transaction->notification_status = 1;
				$reference_transaction->save();
			}
		}

		$transaction_type = PersonalTransactionType::where('name', request('transaction_type'))->first();
		$category = PersonalCategory::find(request('transaction_category'));
		$account = PersonalAccount::find(request('account_id'));
		
		$transaction = new PersonalTransaction();
		$transaction->source = request('source');
		$transaction->type = (request('type') != null) ? request('type') : null;
		$transaction->reference_id = (request('reference_id') != null) ? request('reference_id') : null;
		$transaction->category_id = request('transaction_category');
		$transaction->transaction_type = $transaction_type->id;
		$transaction->date = Carbon::parse(request('date'))->format('Y-m-d');
		$transaction->due_date = Carbon::parse(request('date'))->format('Y-m-d');
		$transaction->amount = request('amount');
		$transaction->account_id = request('account_id');
		$transaction->description = request('description');
		$transaction->user_id = Auth::id();        
		$transaction->save();
		Custom::userby($transaction, true);

		if(request('transaction_type') == "income") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'receipt';
		} if(request('transaction_type') == "expense") {
			$debit_ledger = $category->ledger_id;
			$credit_ledger = $account->ledger_id;
			$voucher_type = 'payment';
		} if(request('transaction_type') == "liability") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'payment';
		}

		$entry[] = array('debit_ledger_id' => $debit_ledger, 'credit_ledger_id' => $credit_ledger, 'amount' => $transaction->amount);

        $transaction->entry_id = Custom::add_entry($transaction->date, $entry, null, $voucher_type, $transaction->user_id, 1, true);
        $transaction->save();

        $data = PersonalTransaction::select('personal_transactions.id', 'personal_categories.name AS category', 'personal_categories.id AS category_id', DB::raw('DATE_FORMAT(personal_transactions.date, "%d %b, %Y") AS date'), 'personal_transactions.amount', 'personal_transaction_types.name AS transaction_type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'), DB::raw('COALESCE(personal_transactions.source, "") AS source'),  DB::raw('COALESCE(personal_transactions.description, "") AS description'), DB::raw('DATE_FORMAT(personal_transactions.date, "%d-%m-%Y") AS original_date'), 'personal_accounts.name AS account', DB::raw('"" AS message'), DB::raw('"1" AS notification_status'))
		->leftjoin('personal_categories', 'personal_categories.id', '=', 'personal_transactions.category_id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->leftjoin('personal_accounts', 'personal_accounts.id', '=', 'personal_transactions.account_id')
		->where('personal_transactions.user_id', $user_id)
		->where('personal_transactions.id', $transaction->id)
		->first();



		return response()->json(['status' => 1, 'message' => 'Personal Transaction'.config('constants.flash.added'), 'data' => $data ]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update()
	{
		$user_id = request('user_id');

		$transaction_type = PersonalTransactionType::where('name', request('transaction_type'))->first();
		$category = PersonalCategory::find(request('transaction_category'));
		$account = PersonalAccount::find(request('account_id'));
		
		$transaction = PersonalTransaction::find(request('id'));
		$transaction->source = request('source');
		$transaction->type = (request('type') != null) ? request('type') : null;
		$transaction->reference_id = (request('reference_id') != null) ? request('reference_id') : null;
		$transaction->category_id = request('transaction_category');
		$transaction->transaction_type = $transaction_type->id;
		$transaction->date = Carbon::parse(request('date'))->format('Y-m-d');
		$transaction->due_date = Carbon::parse(request('date'))->format('Y-m-d');
		$transaction->amount = request('amount');
		$transaction->account_id = request('account_id');
		$transaction->description = request('description');
		$transaction->user_id = Auth::id();        
		$transaction->save();
		Custom::userby($transaction, true);

		if(request('transaction_type') == "income") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'receipt';
		} if(request('transaction_type') == "expense") {
			$debit_ledger = $category->ledger_id;
			$credit_ledger = $account->ledger_id;
			$voucher_type = 'payment';
		} if(request('transaction_type') == "liability") {
			$debit_ledger = $account->ledger_id;
			$credit_ledger = $category->ledger_id;
			$voucher_type = 'payment';
		}

		$entry[] = array('debit_ledger_id' => $debit_ledger, 'credit_ledger_id' => $credit_ledger, 'amount' => $transaction->amount);

        $transaction->entry_id = Custom::add_entry($transaction->date, $entry, $transaction->entry_id, $voucher_type, $transaction->user_id, 1, true);
        $transaction->save();

        $data = PersonalTransaction::select('personal_transactions.id', 'personal_categories.name AS category', 'personal_categories.id AS category_id', DB::raw('DATE_FORMAT(personal_transactions.date, "%d %b, %Y") AS date'), 'personal_transactions.amount', 'personal_transaction_types.name AS transaction_type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'), DB::raw('COALESCE(personal_transactions.source, "") AS source'),  DB::raw('COALESCE(personal_transactions.description, "") AS description'), DB::raw('DATE_FORMAT(personal_transactions.date, "%d-%m-%Y") AS original_date'), 'personal_accounts.name AS account', DB::raw('"" AS message'), DB::raw('"1" AS notification_status'))
		->leftjoin('personal_categories', 'personal_categories.id', '=', 'personal_transactions.category_id')
		->leftjoin('personal_transaction_types', 'personal_transaction_types.id', '=', 'personal_transactions.transaction_type')
		->leftjoin('personal_accounts', 'personal_accounts.id', '=', 'personal_transactions.account_id')
		->where('personal_transactions.user_id', $user_id)
		->where('personal_transactions.id', $transaction->id)
		->first();



		return response()->json(['status' => 1, 'message' => 'Personal Transaction'.config('constants.flash.updated'), 'data' => $data ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		$transaction = PersonalTransaction::findOrFail(request('id'));

		if(!empty($transaction->entry_id)) {

			AccountEntry::where('account_entries.id', $transaction->entry_id)->first()->delete();
			
		}

		$transaction->delete();

		return response()->json(['status' => 1, 'message' => 'Personal Transaction'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function image_view()
	{
		$files = File::glob(public_path().'\images\category\*.jpg');
		//dd($files);

		$url = url('/images');
		$myArray = [];

		$i = 1;
		foreach ($files as $file) 
		{
			$ext  = pathinfo("$file", PATHINFO_BASENAME);   

			$name = explode('.', $ext);

			$myArray[] = ["id" => $i, "name" => $name[0], "url" => $url.'/'.$ext];

			$i++;
		}

		return response()->json($myArray);
	}

	public function image_store()
	{
		$image = request('image');
		$name  = request('name');

		$image_decode = base64_decode($image);

		if(file_put_contents(public_path().'/users/images/category/'.$name.'.jpg',$image_decode))
		{
			return response(['status'=>1,'message'=>'Successfully added']);
		}
		else
		{
			return response(['status'=>0,'message'=>'Failed']);
		}
	}

	public function get_account_list()
	{
		$user_id = request('user_id');

		$type = PersonalAccount::select('id', 'name')->where('user_id', $user_id)->where('status', 1)->get();

		return response()->json(array('result' => $type));
	}

	public function get_category_list()
	{
		$user_id = request('user_id');

		$transaction_type = request('transaction_type');

		$liability = PersonalTransactionType::where('name', 'liability')->first()->id;

		$current_liability = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();

		$account_type = User::find($user_id);

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

		if(date('n') > 3) {
			$start_year = Carbon::createFromDate(null, 04, 01)->format('Y-m-d');
		} else {
			$start_year = Carbon::createFromDate(null, 04, 01)->subYear()->format('Y-m-d');
		}

		$category_exists = PersonalCategory::where('name', 'Credit Card')->where('user_id', $user_id)->first();

		if($category_exists == null) {
			$category = new PersonalCategory;
			$category->name = 'Credit Card';
			$category->transaction_type = $liability;
			$category->ledger_id = Custom::create_ledger('Credit Card', $account_type, 'Credit Card', $impersonal_ledger->id, null, null, $current_liability->id,$start_year, 'credit', '0.00', '1', '0', $user_id, true);
			$category->user_id = $user_id;
			$category->image = 'card.png';    
			$category->save();
			Custom::userby($category, true);
		}

		$transaction_type  = PersonalTransactionType::where('name', request('transaction_type'))->first();

		$type = PersonalCategory::select('id', 'name')->where('transaction_type', $transaction_type->id)->where('user_id', $user_id)->where('status', 1)->get();

		return response()->json(array('result' => $type));
	}
}
