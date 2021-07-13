<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountLedgerType;
use App\AccountLedger;
use App\Organization;
use App\AccountGroup;
use App\TaxGroup;
use App\TaxType;
use App\Custom;
use Validator;
use Response;
use App\Tax;
use Session;
use DB;


class TaxController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name AS name', 'tax_types.display_name as tax_type', DB::raw('SUM(taxes.value) AS value'), 'tax_groups.status', 'taxes.is_percent')
		->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('tax_groups.organization_id', $organization_id)
		->groupby('tax_groups.id')
		->orderby('tax_groups.name')
		->get();

		return view('trade.tax',compact('taxes'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$tax_type = TaxType::where('status','1')->pluck('display_name', 'id');
		$tax_type->prepend('Select Type', '');

		$liability_group = AccountGroup::where('name', 'current_liability')->where('organization_id', $organization_id)->first()->id;

		$sales_ledgers = AccountLedger::select('account_ledgers.id',
		 'account_ledgers.display_name AS name')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.group_id', $liability_group],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$sales_ledgers = $sales_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$sales_ledgers->prepend('Select Sales Ledger', '');

		$purchase_ledgers = AccountLedger::select('account_ledgers.id',
		  'account_ledgers.display_name AS name')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.group_id', $liability_group],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$purchase_ledgers = $purchase_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$purchase_ledgers->prepend('Select Purchase Ledger', '');
		
		return view('trade.tax_create',compact('sales_ledgers','purchase_ledgers', 'tax_type'));
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
			'value' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');
		$organization = Organization::find($organization_id);

		$tax_group = new TaxGroup;
		$tax_group->name = $request->input('name');
		$tax_group->display_name = $request->input('name');
		
		
		if($request->input('is_sales') != null){
			$tax_group->is_sales = $request->input('is_sales');
		}

		
		if($request->input('is_purchase') !=null){
			$tax_group->is_purchase = $request->input('is_purchase');
		}
		$tax_group->tax_type_id = $request->input('tax_type_id');
		$tax_group->organization_id = $organization_id;
		$tax_group->description = $request->input('description');
		$tax_group->save();
		Custom::userby($tax_group, true);

		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
		$duties_taxes = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $organization_id)->first();

		$tax_type = TaxType::find($tax_group->tax_type_id);


		if($tax_group->id) {

			if($tax_type->name == "gst") {

				$tax_array = ['CGST', 'SGST'];

				for ($i=0; $i < count($tax_array); $i++) {

					$tax = new Tax;

					$tax->name = $tax_array[$i].' '.($request->input('value')/2).(($request->input('is_percent') != null) ? '%': '');

					$tax->display_name = $tax_array[$i].' '.($request->input('value')/2).(($request->input('is_percent') != null) ? '%' : '' );

					$tax->value = $request->input('value')/2;
					$tax->is_percent = $request->input('is_percent');
					$tax->is_sales = $request->input('is_sales');
					$tax->is_purchase = $request->input('is_purchase');
					
					if($request->input('is_percent') != null){
						$tax->is_percent = $request->input('is_percent');
					}
					
					if($request->input('is_sales') != null){
						$tax->is_sales = $request->input('is_sales');
					}
					
					if($request->input('is_purchase') !=null){
						$tax->is_purchase = $request->input('is_purchase');
					}

					$tax->purchase_ledger_id = $request->input('purchase_ledger_id');
					$tax->sales_ledger_id = $request->input('sales_ledger_id');
					$tax->organization_id = $organization_id;
					$tax->save();

					$ledger = Custom::create_ledger($tax->name, $organization, $tax->display_name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

					$tax->purchase_ledger_id = $ledger;
					$tax->sales_ledger_id = $ledger;
					$tax->save();

					Custom::userby($tax, true);

					$tax_group->taxes()->attach($tax);
				}

			} else {
				$tax = new Tax;

				$tax->name = $request->input('name').' '.($request->input('value')).
				(($request->input('is_percent') != null) ? '%' : '' );

				$tax->display_name = $request->input('name').' '.($request->input('value')).(($request->input('is_percent') != null) ? '%' : '' );

				$tax->value = $request->input('value');
				$tax->is_percent = $request->input('is_percent');
				$tax->is_sales = $request->input('is_sales');
				$tax->is_purchase = $request->input('is_purchase');
				$tax->organization_id = $organization_id;
				$tax->save();

				$ledger = Custom::create_ledger($tax->name, $organization, $tax->display_name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

				$tax->purchase_ledger_id = $ledger;
				$tax->sales_ledger_id = $ledger;
				$tax->save();

				Custom::userby($tax, true);

				$tax_group->taxes()->attach($tax);
			}
		}


		$taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name AS name', 'tax_types.display_name as tax_type', DB::raw('SUM(taxes.value) AS value'), 'tax_groups.status', 'taxes.is_percent')
		->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('taxes.id',$tax->id)
		->where('taxes.organization_id',$organization_id)
		->groupby('tax_groups.id')->first();

		return response()->json(['status' => 1, 'message' => 'Tax'.config('constants.flash.added'), 'data' => ['id' => $taxes->id, 'name' => $taxes->name,'value'=>$taxes->value,'tax_type'=>$taxes->tax_type,'status' => $taxes->status ]]);
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

		$tax_type = TaxType::where('status','1')->pluck('display_name', 'id');
		$tax_type->prepend('Select Type', '');

		$liability_group = AccountGroup::where('name', 'current_liability')->where('organization_id', $organization_id)->first()->id;

		$sales_ledgers = AccountLedger::select('account_ledgers.id',
		 'account_ledgers.display_name AS name')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.group_id', $liability_group],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		->orderby('name','asc');

		$sales_ledgers = $sales_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$sales_ledgers->prepend('Select Sales Ledger', '');

		$purchase_ledgers = AccountLedger::select('account_ledgers.id',
		 'account_ledgers.display_name AS name')
		  ->where([
					['account_ledgers.organization_id', $organization_id],
					['account_ledgers.group_id', $liability_group],
					['account_ledgers.approval_status', '1'],
					['account_ledgers.status', '1']
				])
		  ->orderby('name','asc');

		$purchase_ledgers = $purchase_ledgers->pluck('account_ledgers.name', 'account_ledgers.id');
		$purchase_ledgers->prepend('Select Purchase Ledger', '');
		

		$taxes = TaxGroup::select('tax_groups.id','tax_groups.tax_type_id', 'tax_groups.display_name AS name',  DB::raw('SUM(taxes.value) AS value'), 'tax_groups.status', 'taxes.is_percent','tax_types.display_name as tax_type','tax_groups.is_sales','tax_groups.is_purchase')
		->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('tax_groups.id',$id)
		->first();

		return view('trade.tax_edit',compact('taxes','sales_ledgers','purchase_ledgers','id','tax_type'));
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
			'value' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');
		$organization = Organization::find($organization_id);

		$tax_type = TaxType::where('name','gst')->first()->id;
		

		$tax_group = TaxGroup::findOrFail($request->input('id'));

		$tax_group->name = $request->input('name');
		$tax_group->display_name = $request->input('name');		
		$tax_group->tax_type_id = $request->input('tax_type_id');	
		if($request->input('is_sales') != null){
			$tax_group->is_sales = $request->input('is_sales');
		}		
		if($request->input('is_purchase') !=null){
			$tax_group->is_purchase = $request->input('is_purchase');
		}
		
		$tax_group->description = $request->input('description');
		$tax_group->save();

		Custom::userby($tax_group, false);


		$impersonal_ledger = AccountLedgerType::where('name', 'impersonal')->first();
		$duties_taxes = AccountGroup::where('name', 'duties_taxes')->where('organization_id', $organization_id)->first();

		$tax_type = TaxType::find($tax_group->tax_type_id);


		if($tax_group->id) {

			$group_tax = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

			foreach ($group_tax as $tax_id){
				Tax::findOrFail($tax_id->tax_id)->delete();

				Custom::delete_addon('records');
			}

			if($tax_type->name == "gst") {

				$tax_array = ['CGST', 'SGST'];

				for ($i=0; $i < count($tax_array); $i++) {

					$tax = new Tax;

					$tax->name = $tax_array[$i].' '.($request->input('value')/2).(($request->input('is_percent') != null) ? '%': '');

					$tax->display_name = $tax_array[$i].' '.($request->input('value')/2).(($request->input('is_percent') != null) ? '%' : '' );

					$tax->value = $request->input('value')/2;
					$tax->is_percent = $request->input('is_percent');
					$tax->is_sales = $request->input('is_sales');
					$tax->is_purchase = $request->input('is_purchase');
					
					if($request->input('is_percent') != null){
						$tax->is_percent = $request->input('is_percent');
					}
					
					if($request->input('is_sales') != null){
						$tax->is_sales = $request->input('is_sales');
					}
					
					if($request->input('is_purchase') !=null){
						$tax->is_purchase = $request->input('is_purchase');
					}

					$tax->purchase_ledger_id = $request->input('purchase_ledger_id');
					$tax->sales_ledger_id = $request->input('sales_ledger_id');
					
					$tax->save();

					$ledger = Custom::create_ledger($tax->name, $organization, $tax->display_name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

					$tax->purchase_ledger_id = $ledger;
					$tax->sales_ledger_id = $ledger;
					$tax->save();

					Custom::userby($tax, true);

					$tax_group->taxes()->attach($tax);
				}

			} else {
				$tax = new Tax;

				$tax->name = $request->input('name').' '.($request->input('value')).
				(($request->input('is_percent') != null) ? '%' : '' );

				$tax->display_name = $request->input('name').' '.($request->input('value')).(($request->input('is_percent') != null) ? '%' : '' );

				$tax->value = $request->input('value');
				$tax->is_percent = $request->input('is_percent');
				$tax->is_sales = $request->input('is_sales');
				$tax->is_purchase = $request->input('is_purchase');
				
				$tax->save();

				$ledger = Custom::create_ledger($tax->name, $organization, $tax->display_name, $impersonal_ledger->id, null, null, $duties_taxes->id, date('Y-m-d'), 'debit', '0.00', Session::get('ledger_approval'), '1', $organization_id, false);

				$tax->purchase_ledger_id = $ledger;
				$tax->sales_ledger_id = $ledger;
				$tax->save();

				Custom::userby($tax, true);

				$tax_group->taxes()->attach($tax);
			}
		}

		$taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name AS name', 'tax_types.display_name as tax_type', DB::raw('SUM(taxes.value) AS value'), 'tax_groups.status', 'taxes.is_percent')
		->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('tax_groups.id',$tax_group->id)
		->where('tax_groups.organization_id',$organization_id)
		->first();


		return response()->json(['status' => 1, 'message' => 'Tax'.config('constants.flash.updated'), 'data' => ['id' => $taxes->id, 'name' => $taxes->name,'value'=>$taxes->value,'tax_type'=>$taxes->tax_type,'status' => $taxes->status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$tax = TaxGroup::findOrFail($request->id);

		$tax->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Tax'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function status(Request $request)
	{
		TaxGroup::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}
}
