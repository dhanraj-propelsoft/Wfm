<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
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
use Session;
use Auth;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user_id = Auth::user()->id;

		$categories = PersonalCategory::select('personal_categories.*', 'personal_transaction_types.display_name AS transaction')
		->leftjoin('personal_transaction_types', 'personal_categories.transaction_type', '=', 'personal_transaction_types.id')
		->where('personal_categories.user_id', $user_id)->orderby('personal_categories.name')->get();

		return view('personal.category', compact('categories'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$type = PersonalTransactionType::pluck('display_name', 'id');
		$type->prepend('Select Type', '');

		return view('personal.category_create', compact('type'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required'
		]);

		$user_id = Auth::user()->id;

		$user = Auth::User();

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();

		$year = AccountFinancialYear::where('user_id', $user_id)->first();

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

		$exist_name = PersonalCategory::where('name', request('name'))->where('user_id', $user_id)->first();
		
		if($exist_name != "")
		{
			return response()->json(['status' => 0, 'message' => 'Category Name Already Exists!']);
		}

		$category = new PersonalCategory;
		$category->name = $request->input('name');
		$category->transaction_type = $request->input('transaction_type');
		$category->user_id = $user_id;        
		$category->save();
		Custom::userby($category, true);

		$category->ledger_id = Custom::create_ledger($category->name, $user, $category->name, $impersonal_ledger->id, null, null, $ledger->id, $year->books_start_year, 'debit', '0.00', Session::get('ledger_approval'), '1', $user_id, true);
		$category->save();

		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.added'), 'data' => ['id' => $category->id, 'name' => $category->name, 'transaction_type' => $category->transaction_type]]);        
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
		$user_id = Auth::user()->id;

		$type = PersonalTransactionType::pluck('display_name', 'id');

		$categories = PersonalCategory::where('id', $id)->where('user_id', $user_id)->first();
		if(!$categories) abort(403);

		return view('personal.category_edit', compact('type', 'categories'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
		]);
	   

	   $user_id = Auth::id();

		$exist_name = PersonalCategory::where('name', $request->input('name'))->where('id', '!=', $request->input('id'))->where('user_id', $user_id)->first();
		
		if($exist_name != "")
		{
			return response()->json(['status' => 0, 'message' => 'Category Name Already Exists!']);
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

		$category = PersonalCategory::findOrFail($request->input('id'));
		$category->name = $request->input('name');
		$category->transaction_type = $request->input('transaction_type');
		$category->save();

		Custom::userby($category, false);

		$account_ledger = AccountLedger::findOrFail($category->ledger_id);
		$account_ledger->name = $request->input('name');
		$account_ledger->display_name = $request->input('name');
		$ledger->group_id = $ledger->id;
		$account_ledger->save();

		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.updated'), 'data' => ['id' => $category->id, 'name' => $category->name, 'transaction_type' => $category->transaction_type]]);
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$personal_category = PersonalCategory::findOrFail($request->input('id'));

		if($personal_category->ledger_id!= null) {
			$account_ledger =  AccountLedger::findOrFail($personal_category->ledger_id);
			if($account_ledger != null) {
				$account_ledger->delete();
			}
		}

		$personal_category->delete();
		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function personal_category_status_approval(Request $request)
	{
		PersonalCategory::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}
}
