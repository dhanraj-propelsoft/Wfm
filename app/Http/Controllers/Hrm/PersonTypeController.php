<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmPersonType;
use App\Custom;
use Validator;
use Session;

class PersonTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$person = HrmPersonType::select('hrm_person_types.id', 'hrm_person_types.name', 'hrm_person_types.type', 'hrm_person_types.description', 'hrm_person_types.status', 'hrm_person_types.delete_status');
		$person->where('organization_id', $organization_id);
		$person->orderby('hrm_person_types.name');
		$persons = $person->paginate(10);

		return view('hrm.person_types', compact('persons'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('hrm.person_types_create');
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
			'name' => 'required',
		]);

		$organization_id = Session::get('organization_id');

		$person = new HrmPersonType;
		$person->name = $request->input('name');
		if($request->input('description') != "") {
			$person->description = $request->input('description');
		}
		if($request->input('type') != "") {
			$person->type = $request->input('type');
		}
		$person->organization_id = $organization_id;
		$person->save();

		Custom::userby($person, true);
		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Person Type'.config('constants.flash.added'), 'data' => ['id' => $person->id, 'name' => $person->name, 'description' => ($person->description != null) ? $person->description : "", 'type' => ($person->type == '1') ? "Employee" : "Guest"]]);
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
		$organization_id = Session::get('organization_id');

		$person = HrmPersonType::where('id', $id)->where('organization_id', $organization_id)->first();
		if(!$person) abort(403);

		return view('hrm.person_types_edit', compact('person'));
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

		$person = HrmPersonType::findOrFail($request->input('id'));
		$person->name = $request->input('name');
		$person->description = $request->input('description');
		if($request->input('type') == "1") {
			$person->type = '1';
		} elseif($request->input('type') == "") {
			$person->type = '0';
		}
		$person->save();

		Custom::userby($person, false);

		return response()->json(['status' => 1, 'message' => 'Person Type'.config('constants.flash.updated'), 'data' => ['id' => $person->id, 'name' => $person->name, 'description' => ($person->description != null) ? $person->description : "", 'type' => ($person->type == '1') ? "Employee" : "Guest"]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$person = HrmPersonType::findOrFail($request->input('id'));
		$person->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Person Type'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$persons = explode(',', $request->id);

		$person_list = [];

		foreach ($persons as $person_id) {
			$person = HrmPersonType::findOrFail($person_id);
			$person->delete();
			$person_list[] = $person_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Person Type'.config('constants.flash.deleted'),'data'=>['list' => $person_list]]);
	}

	public function multiapprove(Request $request)
	{
		$persons = explode(',', $request->id);

		$person_list = [];

		foreach ($persons as $person_id) {
			HrmPersonType::where('id', $person_id)->update(['status' => $request->input('status')]);
			$person_list[] = $person_id;
		}

		return response()->json(['status'=>1, 'message'=>'Person Type'.config('constants.flash.updated'),'data'=>['list' => $person_list]]);
	}

	public function person_type_status_approval(Request $request)
	{
		HrmPersonType::where('id', $request->input('id'))
		  ->update(['status' => $request->input('status')]);

		return response()->json(["status" => $request->input('status')]);
	}
}
