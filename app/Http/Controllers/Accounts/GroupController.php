<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountLedgerType;
use App\AccountGroupParent;
use App\AccountGroup;
use App\AccountHead;
use App\Setting;
use App\Custom;
use Validator;
use Session;
use Response;
use DB;

class GroupController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		
		$ledger_groups = AccountGroup::select('account_groups.id', 'account_groups.display_name', 'account_groups.name', 'account_groups.status', 'account_groups.approval_status', 'account_groups.delete_status', 'account_groups.status',  DB::raw('IF(parent.display_name IS NULL, account_heads.display_name, parent.display_name) AS parent_group'))
		->leftJoin('account_groups AS parent', 'parent.id', '=', 'account_groups.parent_id')
		->leftJoin('account_heads', 'account_heads.id', '=', 'account_groups.account_head')
		->where('account_groups.organization_id', $organization_id)->orderby('account_groups.id')->get();

		$settings = Setting::select('id', 'status')->where('name', 'ledgergroup_approval')->where('organization_id', Session::get('organization_id'))->first();


		return view('accounts.ledger_groups',compact('ledger_groups','settings'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$ledger_types = AccountLedgerType::select('id','display_name')->where('status', '1')->get();
		$ledger_groups = AccountGroup::where([
			['organization_id', $organization_id],
			['approval_status', '1'],
			['status', '1']
			])->pluck('display_name', 'id');
		$ledger_groups->prepend('Select Ledger Group', '');
	   
		//dd($ledger_groups);
		return view('accounts.ledger_groups_create', compact('ledger_groups', 'ledger_types'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];

		$organization_id = Session::get('organization_id');

		if(Custom::plan_expire($plan,$organization_id))
		{
			return response()->json(['status' => 0, 'message' => config('constants.error.expire')]);
		}

		$head = AccountGroup::findOrFail($request->input('parent_id'));

		
		
		$group = new AccountGroup;
		$group->name = $request->input('name');
		$group->display_name = $request->input('name');
		$group->parent_id = ($request->input('parent_id') != null) ? $request->input('parent_id') : null;
		$group->account_head = $head->account_head;
		$group->organization_id = $organization_id;
		$group->opening_type = $head->opening_type;
		$group->approval_status = Session::get('group_approval');

		$group->save();
		Custom::userby($group, true);

		//LEDGER TYPE
		$ledger_group_type = $request->input('account_ledger_group_types');

		for($i=0; $i<count($ledger_group_type); $i++) 
		{
			$group->find($group->id)->ledger_group()->attach($ledger_group_type[$i]);
		}

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Ledger Group'.config('constants.flash.added'), 'data' => ['id' => $group->id, 'name' => $group->name, 'parent_id' => $group->parent_id, 'approval_status' => $group->approval_status]]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$ledger_groups = AccountGroup::select('account_groups.id','account_groups.display_name', 'account_groups.approval_status', 'account_groups.parent_id','parent.name AS ledger_group_name')
		->leftJoin('account_groups as parent', 'parent.id', '=', 'account_groups.parent_id')
		->where('account_groups.id', '=', $id)->get();
		//return $ledger_groups;

		return view('accounts.ledger_groups_show',compact('ledger_groups'));
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
		 
		$ledger_groups = AccountGroup::select('account_groups.id','account_groups.name', 'account_groups.parent_id','account_groups.display_name','account_heads.display_name AS account_head');

		$ledger_groups->leftJoin('account_heads', 'account_heads.id','=','account_groups.account_head');
		$ledger_groups->where('account_groups.id', $id);

		$ledger_group = $ledger_groups->first();

		$ledger_group_list = AccountGroup::where('id','!=', $id)->where('organization_id',$organization_id)->pluck('display_name', 'id');
		$ledger_group_list->prepend('Select Ledger Group', '');
		
		$ledger_types = AccountLedgerType::select('display_name','id')->where('status', '1')->get();

		$selected_ledger_type = DB::table('account_ledgertype_group')->where('group_id', $id)->get();

		$selected_ledger_types = array();

		foreach($selected_ledger_type as $ledger_type)
		{
			$selected_ledger_types[] = $ledger_type->ledger_type_id;
		}

		return view('accounts.ledger_groups_edit', compact('ledger_group_list', 'ledger_group', 'ledger_types', 'selected_ledger_types'));
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
		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];

		$organization_id = Session::get('organization_id');

		if(Custom::plan_expire($plan,$organization_id))
		{
			return response()->json(['status' => 0, 'message' => config('constants.error.expire')]);
		}

		$head = AccountGroup::findOrFail($request->input('parent_id'));

		$organization_id = Session::get('organization_id');
		
		$group =  AccountGroup::findOrFail($request->input('id'));
		$group->name = $request->input('name');
		$group->display_name = $request->input('name');
		$group->parent_id = ($request->input('parent_id') != null) ? $request->input('parent_id') : null;
		$group->account_head = $head->account_head;
		$group->organization_id = $organization_id;
		
		$group->approval_status = Session::get('group_approval');

		$group->save();

		Custom::userby($group, false);

		DB::table('account_ledgertype_group')->where('group_id', $group->id)->delete();
		//LEDGER TYPE
		
		$ledger_group_type = $request->input('account_ledger_group_types');
		
		for($i=0; $i<count($ledger_group_type); $i++) {
			$group->find($group->id)->ledger_group()->attach($ledger_group_type[$i]);
		}

		return response()->json(['status' => 1, 'message' => 'Ledger Group'.config('constants.flash.updated'), 'data' => ['id' => $group->id, 'name' => $group->name, 'parent_id' => $group->parent_id, 'status' => $group->status, 'approval_status' => $group->approval_status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$ledger_group = AccountGroup::findOrFail($request->id);

		$ledger_group->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Ledger Group'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function parent_group(Request $request)
	{
		$id = $request->input('id');
		
		if(empty($id)) {
			return response::json(array(array("name" => "")));
		}

		$parent = AccountGroup::find($id);
		$head = AccountHead::where('id', '=', $parent->account_head)
		->orderBy('name', 'asc')
		->get();

		return response::json($head);
	}

	public function status(Request $request)
	{
		AccountGroup::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function approval_status(Request $request)
	{
		AccountGroup::where('id', $request->input('id'))->update(['approval_status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}   

	public function ledgergroup_approval(Request $request)
	{
		$setting = Setting::where('id', $request->input('id'))->where('organization_id', Session::get('organization_id'))->first();
		$setting->status = $request->input('status');
		$setting->save();

		if($setting->name == "ledgergroup_approval") {
			Session::put('group_approval', $setting->status);
		} else if($setting->name == "ledger_approval") {
			
			Session::put('ledger_approval', $setting->status);
		}
   
		return response::json(["status" => $request->input('status')]);
	}

	public function multidestroy(Request $request)
	{
		$groups = explode(',', $request->id);

		$group_list = [];

		foreach ($groups as $group_id) {
			$group = AccountGroup::findOrFail($group_id);
			$group->delete();
			$group_list[] = $group_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Ledger Group'.config('constants.flash.deleted'),'data'=>['list' => $group_list]]);
	}

	public function multiapprove(Request $request)
	{
		$groups = explode(',', $request->id);

		$group_list = [];

		foreach ($groups as $group_id) {
			AccountGroup::where('id', $group_id)->update(['status' => $request->input('status')]);;
			$group_list[] = $group_id;
		}

		return response()->json(['status'=>1, 'message'=>'Ledger Group'.config('constants.flash.updated'),'data'=>['list' => $group_list]]);
	}
}
