<?php

namespace App\Http\Controllers\Api\Personal;

use App\Http\Controllers\Controller;
use App\PersonalTransactionType;
use App\AccountFinancialYear;
use App\AccountLedgerType;
use App\PersonalCategory;
use App\AccountLedger;
use App\AccountGroup;
use Carbon\Carbon;
use App\Custom;
use App\User;
use Auth;
use URL;
use DB;

class CategoryController extends Controller
{
	public $successStatus = 200;

	public function index()
	{
		$user_id = request('user_id');

		$categories = PersonalCategory::select('personal_categories.id', 'personal_categories.name', 'personal_categories.status', 'personal_transaction_types.display_name AS type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'))
		->leftjoin('personal_transaction_types', 'personal_categories.transaction_type', '=', 'personal_transaction_types.id')
		->where('personal_categories.user_id', $user_id)
		->orderby('personal_categories.name')->get();

		$message['status'] =  1;
		$message['categories'] =  $categories;

		return response()->json($message, $this->successStatus);
	}

	public function store()
	{ 
		$user_id = request('user_id');
		$user = User::findOrFail($user_id);

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

		$year = AccountFinancialYear::where('user_id', $user_id)->first();

		$exist_name = PersonalCategory::where('name', request('name'))->where('user_id', $user_id)->first();
		
		if($exist_name != "")
		{
			return response()->json(['status' => 0, 'message' => 'Category Name Already Exist!']);
		}

		$type_name = PersonalTransactionType::select('id', 'name')->where('id', request('transaction_type'))->first();

		if($type_name->name == "asset") {
		  $ledger = AccountGroup::where('name', 'current_asset')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "expense") {
			$ledger = AccountGroup::where('name', 'direct_expense')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "income") {
			$ledger = AccountGroup::where('name', 'direct_income')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "liability") {
			$ledger = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();
		}

		$category = new PersonalCategory;
		$category->name = request('name');
		$category->transaction_type = request('transaction_type');
		$category->user_id = $user_id;   
		$category->status = request('status');     
		$category->save();

		Custom::userby($category, true, $user_id);

		$type = PersonalTransactionType::select('personal_transaction_types.name')->where('id', $category->transaction_type)->first()->name;

		$category->ledger_id = Custom::create_ledger($category->name, $user, $category->name, $impersonal_ledger->id, null, null, $ledger->id, $year->books_start_year, 'debit', '0.00', '1', '1', $user_id, true);
		$category->save();

		$message['status'] =  '1';
		$message['category'] =  $category;

		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.added'), 'data' => ['id' => $category->id, 'name' => $category->name, 'status' => $category->status, 'transaction_type' => $type_name->name]]);         
	}

	public function update()
	{
		$user_id = request('user_id');

		$exist_name = PersonalCategory::where('name', request('name'))->where('id', '!=', request('id'))->where('user_id', $user_id)->first();
		
		if($exist_name != "")
		{
			return response()->json(['status' => 0, 'message' => 'Category Name Already Exist!']);
		}

		$type_name = PersonalTransactionType::select('id', 'name')->where('id', request('transaction_type'))->first();

		if($type_name->name == "asset") {
		  $ledger = AccountGroup::where('name', 'current_asset')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "expense") {
			$ledger = AccountGroup::where('name', 'direct_expense')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "income") {
			$ledger = AccountGroup::where('name', 'direct_income')->where('user_id', $user_id)->first();
		} 
		else if($type_name->name == "liability") {
			$ledger = AccountGroup::where('name', 'current_liability')->where('user_id', $user_id)->first();
		}

		$category = PersonalCategory::findOrFail(request('id'));
		$category->name = request('name');
		$category->transaction_type = request('transaction_type'); 
		$category->status = request('status');
		$category->save();  

		$account_ledger = AccountLedger::findOrFail($category->ledger_id);
		$account_ledger->name = request('name');
		$account_ledger->display_name = request('name');
		$ledger->group_id = $ledger->id;
		$account_ledger->save();

		Custom::userby($category, false, $user_id);

		$categories = PersonalCategory::select('personal_categories.id', 'personal_categories.name', 'personal_categories.status', 'personal_transaction_types.display_name AS type', DB::raw('IF(personal_categories.image IS NULL, "", CONCAT("'.URL::to('/public/').'/users/icons/", personal_categories.image   )) AS image'))
		->leftjoin('personal_transaction_types', 'personal_categories.transaction_type', '=', 'personal_transaction_types.id')
		->where('personal_categories.user_id', $user_id)
		->orderby('personal_categories.name')->first();

		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.updated'), 'data' => ['id' => $categories->id, 'name' => $categories->name, 'status' => $categories->status, 'image' => $categories->image, 'transaction_type' => $categories->type]]);
	}

	public function destroy()
	{
		$personal_category = PersonalCategory::findOrFail(request('id'));

		if($personal_category->ledger_id!= null) {
			$account_ledger =  AccountLedger::findOrFail($personal_category->ledger_id);
			if($account_ledger != null) {
				$account_ledger->delete();
			}
		}

		$personal_category->delete();
		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function get_transaction_type()
	{
		$type = PersonalTransactionType::select('id', 'display_name AS name')->where('status', 1)->get();

		return response()->json(array('result' => $type));
	}
}
