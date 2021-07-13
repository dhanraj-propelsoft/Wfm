<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalRelationship;
use App\AccountFinancialYear;
use App\AccountLedgerType;
use App\PersonalCategory;
use App\PersonalPeople;
use App\AccountLedger;
use App\AccountGroup;
use App\Custom;
use App\User;
use Session;
use Auth;

class PeopleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user_id = Auth::id();

		$people = PersonalPeople::select('personal_peoples.id', 'personal_peoples.name', 'personal_relationships.display_name AS relationship', 'personal_peoples.mobile', 'personal_peoples.email', 'personal_peoples.aadhar', 'personal_peoples.pan', 'personal_peoples.status')
		->leftjoin('personal_relationships', 'personal_relationships.id', '=', 'personal_peoples.relationship_id')
		->where('personal_peoples.user_id', $user_id)->get();

		return view('personal.people', compact('people'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$user_id = Auth::id();

		$relationship = PersonalRelationship::where('status', 1)->pluck('display_name', 'id');
		$relationship->prepend('Select Relationship', '');

		$category = PersonalCategory::where('user_id', $user_id)->where('status', 1)->pluck('name', 'id');
		$category->prepend('Select Category', '');

		return view('personal.people_create', compact('relationship', 'category'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user_id = Auth::id();

		$people = new PersonalPeople();
		$people->name = $request->input('name');
		$people->relationship_id = $request->input('relationship_id');
		$people->dob = $request->input('dob');
		$people->mobile = $request->input('mobile');
		$people->email = $request->input('email');
		$people->aadhar = $request->input('aadhar');
		$people->pan = $request->input('pan');
		$people->user_id = $user_id;
		$people->save();

		Custom::userby($people, true);

		$relationship = PersonalRelationship::find($request->input('relationship_id'));

		if($request->input('category_id') != null) {
			$category = PersonalCategory::find($request->input('category_id'));
			$ledger = AccountLedger::find($category->ledger_id);
			$ledgergroup = AccountGroup::find($ledger->group_id);
			$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
			$year = AccountFinancialYear::where('user_id', $user_id)->first();

			$account_type = User::find(Auth::id());
		   
			$people->ledger_id = Custom::create_ledger($people->name, $account_type, $people->name, $personal_ledger->id, null, null, $ledgergroup->id, $year->books_year, 'debit', '0.00', Session::get('ledger_approval'), '0', $user_id, 'true');
			$people->save();
		}

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.added'), 'data' => ['id' => $people->id, 'name' => $people->name, 'mobile' => $people->mobile, 'email' => ($people->email != null) ? $people->email: "", 'aadhar' => ($people->aadhar != null) ? $people->aadhar : "", 'pan' => ($people->pan != null) ? $people->pan : ""]]);
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
		$user_id = Auth::id();

		$people = PersonalPeople::where('id', $id)->where('user_id', $user_id)->first();
		if(!$people) abort(403);

		$relationship = PersonalRelationship::where('status', 1)->pluck('display_name', 'id');
		$relationship->prepend('Select Relationship', '');

		$category = PersonalCategory::where('user_id', $user_id)->where('status', 1)->pluck('name', 'id');
		$category->prepend('Select Category', '');

		return view('personal.people_edit', compact('relationship', 'category', 'people'));
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
		$user_id = Auth::id();

		$people = PersonalPeople::find($request->input('id'));
		$people->name = $request->input('name');
		$people->relationship_id = $request->input('relationship_id');
		$people->dob = $request->input('dob');
		$people->mobile = $request->input('mobile');
		$people->email = $request->input('email');
		$people->aadhar = $request->input('aadhar');
		$people->pan = $request->input('pan');
		$people->user_id = $user_id;
		$people->save();

		Custom::userby($people, true);

		$relationship = PersonalRelationship::find($request->input('relationship_id'));

		if($request->input('category_id') != null) {

			$category = PersonalCategory::find($request->input('category_id'));
			$ledger = AccountLedger::find($category->ledger_id);
			$ledgergroup = AccountGroup::find($ledger->group_id);

			$personal_ledger = AccountLedgerType::where('name', 'personal')->first();
			$year = AccountFinancialYear::where('user_id', $user_id)->first();

			$account_type = User::find(Auth::id());
		   
			$people->ledger_id = Custom::create_ledger($people->name, $account_type, $people->name, $personal_ledger->id, null, null, $ledgergroup->id, $year->books_year, 'debit', '0.00', Session::get('ledger_approval'), '0', $user_id, 'true');
			$people->save();

		}
		

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.updated'), 'data' => ['id' => $people->id, 'name' => $people->name, 'mobile' => $people->mobile, 'email' => ($people->email != null) ? $people->email: "", 'aadhar' => ($people->aadhar != null) ? $people->aadhar : "", 'pan' => ($people->pan != null) ? $people->pan : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$people = PersonalPeople::findOrFail($request->input('id'));
		$account_ledger =  AccountLedger::find($people->ledger_id); 
		if($account_ledger != null) {
			$account_ledger->delete();
		}
		
		$people->delete();
		
		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$people = explode(',', $request->id);

		$people_list = [];

		foreach ($people as $id) {
			$person = PersonalPeople::findOrFail($id);
			$account_ledger =  AccountLedger::find($person->ledger_id); 
			if($account_ledger != null) {
				$account_ledger->delete();
			}

			$person->delete();
			$people_list[] = $id;
		}

		return response()->json(['status'=>1, 'message'=>'People'.config('constants.flash.deleted'),'data'=>['list' => $people_list]]);
	}

	public function people_status_approval(Request $request)
	{
		PersonalPeople::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}
}
