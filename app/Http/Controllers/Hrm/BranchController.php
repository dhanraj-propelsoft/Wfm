<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessNature;
use App\HrmBranch;
use App\Business;
use App\People;
use App\PeopleTitle;
use App\PaymentMethod;
use App\Term;
use App\State;
use Carbon\Carbon;
use App\Country;
use App\Custom;
use Response;
use Session;
use Auth;
use DB;

class BranchController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$branches = HrmBranch::select('hrm_branches.id','hrm_branches.description',DB::raw('CONCAT(hrm_branches.branch_name, " - ", COALESCE(CONCAT("(",businesses.business_name,")"), "")) AS branches_name'))
		->leftjoin('businesses','businesses.id','=','hrm_branches.id')
		->where('organization_id', $organization_id)->get();

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		return view('hrm.branches', compact('branches','state','title','payment','terms'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$business->prepend('Select Party', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		return view('hrm.branches_create',compact('business','state','title','payment','terms'));
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
			'business_id' => 'required'
		]);
		//return $request->all();

		$organization_id = Session::get('organization_id');

		$check_business = HrmBranch::where('id',$request->input('business_id'))->where('organization_id', $organization_id)->first();

		if($check_business == null)
		{
			$hrmbranches = new HrmBranch;
			$hrmbranches->id = $request->input('business_id');
			$hrmbranches->branch_name = $request->input('branch_name');
			$hrmbranches->description = $request->input('description');
			$hrmbranches->organization_id = $organization_id;
			$hrmbranches->save();

			Custom::userby($hrmbranches, true);
			Custom::add_addon('records');

			$branches = HrmBranch::select('hrm_branches.id','hrm_branches.description',DB::raw('CONCAT(hrm_branches.branch_name, " - ", COALESCE(CONCAT("(",businesses.business_name,")"), "")) AS branches_name'))
				->leftjoin('businesses','businesses.id','=','hrm_branches.id')
				->where('hrm_branches.id', $request->input('business_id'))
				->where('hrm_branches.organization_id', $organization_id)->first();
				
			return response()->json(['status' => 1, 'message' => 'Branch'.config('constants.flash.added'), 'data' => ['id' => $branches->id, 'branch_name' => $branches->branches_name, 'description' => ($branches->description != null) ? $branches->description : ""]]);

		} else {
			return response()->json(['status' => 0, 'message' => 'Branch'.config('constants.flash.exist'), 'data' => []]);
		}
		

		
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

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$business->prepend('Select Party', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$branches = HrmBranch::where('organization_id',$organization_id)->where('id',$id)->first();

		if(!$branches) abort(403);

		
		return view('hrm.branches_edit',compact('branches','business','state','title','payment','terms'));
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
			'business_id' => 'required'
		]);
		$organization_id = Session::get('organization_id');

		$check_business = HrmBranch::where('id',$request->input('business_id'))->where('organization_id', $organization_id)->first();

		if($check_business != null)	{
			if($check_business->id == $request->input('id') ) {
				return response()->json(['status' => 0, 'message' => 'Branch'.config('constants.flash.exist'), 'data' => []]);
			}
		}
		
		if($check_business == null)
		{	
			$hrmbranches =  HrmBranch::findOrFail($request->input('id'));		
			$hrmbranches->id = $request->input('business_id');
			$hrmbranches->branch_name = $request->input('branch_name');
			$hrmbranches->description = $request->input('description');
			$hrmbranches->organization_id = $organization_id;
			$hrmbranches->save();
			Custom::userby($hrmbranches, false);

			$branches = HrmBranch::select('hrm_branches.id','hrm_branches.description',DB::raw('CONCAT(hrm_branches.branch_name, " - ", COALESCE(CONCAT("(",businesses.business_name,")"), "")) AS branches_name'))
				->leftjoin('businesses','businesses.id','=','hrm_branches.id')
				->where('hrm_branches.id', $request->input('business_id'))
				->where('hrm_branches.organization_id', $organization_id)->first();
				
			return response()->json(['status' => 1, 'message' => 'Branch'.config('constants.flash.updated'), 'data' => ['id' => $branches->id, 'branch_name' => $branches->branches_name, 'description' => ($branches->description != null) ? $branches->description : ""]]);

		} else {
			return response()->json(['status' => 0, 'message' => 'Branch'.config('constants.flash.exist'), 'data' => []]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$branches = HrmBranch::findOrFail($request->id);
		$branches->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Branches'.config('constants.flash.deleted'),'data'=>[]]);
	}

	public function multidestroy(Request $request)
	{
		$branches = explode(',', $request->id);

		$branch_list = [];

		foreach ($branches as $branch_id) {
			$branch = HrmBranch::findOrFail($branch_id);
			$branch->delete();
			$branch_list[] = $branch_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Branch'.config('constants.flash.deleted'),'data'=>['list' => $branch_list]]);
	}   

	public function multiapprove(Request $request)
	{
		$branches = explode(',', $request->id);

		$branch_list = [];

		foreach ($branches as $branch_id) {
			HrmBranch::where('id', $branch_id)->update(['status' => $request->input('status')]);;
			$branch_list[] = $branch_id;
		}

		return response()->json(['status'=>1, 'message'=>'Branch'.config('constants.flash.updated'),'data'=>['list' => $branch_list]]);
	}
}
