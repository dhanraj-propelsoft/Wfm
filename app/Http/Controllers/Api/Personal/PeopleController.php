<?php

namespace App\Http\Controllers\Api\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonalRelationship;
use App\PersonalPeople;
use App\AccountLedger;
use App\Custom;
use DB;

class PeopleController extends Controller
{
    private $successStatus = 200;

	public function index()
	{
		$user_id = request('user_id');

		$people = PersonalPeople::select('personal_peoples.id', 'personal_peoples.name', 'personal_relationships.display_name AS relationship', DB::raw('COALESCE(personal_peoples.mobile, "") AS mobile'), DB::raw('COALESCE(personal_peoples.email, "") AS email'), DB::raw('COALESCE(personal_peoples.aadhar, "") AS aadhar'), DB::raw('COALESCE(personal_peoples.pan, "") AS pan'), DB::raw('COALESCE(personal_peoples.dob, "") AS dob'), 'personal_peoples.status', DB::raw('"" AS image'))
		->leftjoin('personal_relationships', 'personal_relationships.id', '=', 'personal_peoples.relationship_id')
		->where('personal_peoples.user_id', $user_id)->get();

		$message['status'] =  1;
		$message['peoples'] =  $people;

		return response()->json($message, $this->successStatus);
	}

	public function get_relation_list()
	{

		$relation = PersonalRelationship::select('id', 'display_name AS name')->where('status', 1)->get();

		return response()->json(array('result' => $relation));
	}

	public function store(Request $request)
	{
		$user_id = request('user_id');

		$people = new PersonalPeople();
		$people->name = request('name');
		$people->relationship_id = request('relationship_id');
		$people->dob = request('dob');
		$people->mobile = request('mobile');
		$people->email = request('email');
		$people->aadhar = request('aadhar');
		$people->pan = request('pan');
		$people->user_id = $user_id;
		$people->status = request('status');
		$people->save();

		Custom::userby($people, true);

		$relationship = PersonalRelationship::find($request->input('relationship_id'));

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.added'), 'data' => ['id' => $people->id, 'name' => $people->name, 'relationship' => $relationship->display_name, 'mobile' => ($people->mobile != null) ? $people->mobile  : "", 'email' => ($people->email != null) ? $people->email : "", 'aadhar' => ($people->aadhar != null) ? $people->aadhar : "" , 'pan' => ($people->pan != null) ? $people->pan : "", 'dob' => ($people->dob != null) ? $people->dob : "", 'status' => $people->status, 'image' => "" ]]);
	}

	public function update(Request $request)
	{
		$user_id = request('user_id');

		$people = PersonalPeople::findOrFail(request('id'));
		$people->name = request('name');
		$people->relationship_id = request('relationship_id');
		$people->dob = request('dob');
		$people->mobile = request('mobile');
		$people->email = request('email');
		$people->aadhar = request('aadhar');
		$people->pan = request('pan');
		$people->status = request('status');
		$people->save();

		Custom::userby($people, true);

		$relationship = PersonalRelationship::find($request->input('relationship_id'));

		return response()->json(['status' => 1, 'message' => 'People'.config('constants.flash.updated'), 'data' => ['id' => $people->id, 'name' => $people->name, 'relationship' => $relationship->display_name, 'mobile' => ($people->mobile != null) ? $people->mobile  : "", 'email' => ($people->email != null) ? $people->email : "", 'aadhar' => ($people->aadhar != null) ? $people->aadhar : "" , 'pan' => ($people->pan != null) ? $people->pan : "", 'dob' => ($people->dob != null) ? $people->dob : "", 'status' => $people->status, 'image' => "" ]]);
	}

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
}
