<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use App\BusinessAddressType;
use App\MaterialReceipt;
use App\MaterialReceiptItem;
use App\AccountVoucher;
use App\CustomerGroping;
use App\AccountLedger;
use App\InventoryItem;
use App\InventoryStore;
use App\InventoryRack;
use App\GlobalItemCategoryType;
use App\Transaction;
use App\PaymentMethod;
use App\HrmEmployee;
use App\PeopleTitle;
use App\Country;
use App\People;
use App\Custom;
use App\State;
use App\Term;
use Carbon\Carbon;
use Session;
use DB;

class MaterialReceiptController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Payment Method','');

		$terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
		$terms->prepend('Select Term','');
		$group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',$organization_id);
        $group_name->prepend('Select Group Name','');

		$material_receipts = MaterialReceipt::select('material_receipts.id','material_receipts.order_no','material_receipts.date','hrm_employees.first_name')
		->leftjoin('hrm_employees','material_receipts.employee_id','=','hrm_employees.id')
		->where('material_receipts.organization_id', $organization_id)->get();
		

		return view('inventory.material_receipt', compact('material_receipts','state', 'title', 'payment', 'terms','group_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
	   
		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$people->prepend('Select Customer', '');

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$business->prepend('Select Business', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$transaction_type = AccountVoucher::where('name', 'material_receipt')->where('organization_id', $organization_id)->first();

		$previous_entry = MaterialReceipt::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

		$voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
		$employees->prepend('Select Sales Person', '');

		$items = $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_models.category_id')
		->where('inventory_items.organization_id', $organization_id)
		->orderby('global_item_category_types.display_name')
		->get();

		$warehouse_id = BusinessAddressType::where('name','warehouse')->first()->id;

		$warehouse = BusinessCommunicationAddress::select('business_communication_addresses.*')
		->leftjoin('organizations','business_communication_addresses.business_id','=','organizations.business_id')
		->where('address_type',$warehouse_id)
		->where('business_communication_addresses.status', '1')
		->where('organizations.id', $organization_id)
		->pluck('placename','id');   
		$warehouse->prepend('Select Warehouse','');

		$stores = InventoryStore::where('organization_id', $organization_id)->where('status', '1')->pluck('name', 'id');
		$stores->prepend('Choose Store Name', '');


		return view('inventory.material_receipt_create', compact('voucher_no', 'employees', 'items', 'people', 'business', 'transaction_type', 'warehouse', 'stores'));
	}

	public function get_store(Request $request)
	{
		$stores = InventoryStore::select('inventory_stores.id AS store_id', 'inventory_stores.name AS store_name');
		if($request->input('warehouse_id') != null){
			$stores->where('inventory_stores.warehouse_id', $request->input('warehouse_id'));
		}		
		$stores->where('inventory_stores.organization_id',Session::get('organization_id'));
		$stores->where('inventory_stores.status', '1');
		$store = $stores->get();

		if($request->input('warehouse_id') != null){
		$rack = InventoryRack::select('inventory_racks.id AS rack_id', 'inventory_racks.name AS rack_name')
		->where('inventory_racks.status', '1')
		->where('inventory_racks.warehouse_id', $request->input('warehouse_id'))
		->where('inventory_racks.organization_id', Session::get('organization_id'))
		->get();
		}
		else{
			$rack =[];
		}

		//return response()->json(array('result' => $store));

		return response()->json(array('store_result' => $store,'rack_result'=> $rack));
	}
	
	public function get_rack(Request $request)
	{
		$this->validate($request, [
			  'store_id'  => 'required'
		]);

		$rack = InventoryRack::select('id', 'name')->where('store_id', $request->input('store_id'))->where('status', '1')
				->where('inventory_racks.organization_id', Session::get('organization_id'))->get();
		return response()->json(array('result' => $rack));
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
			'people_id' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', 'material_receipt')->where('organization_id', $organization_id)->first();

		$previous_entry = MaterialReceipt::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

		$material_receipt = new MaterialReceipt;
		$material_receipt->user_type = $request->input('user_type');
		$material_receipt->order_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);
		$material_receipt->gen_no = $gen_no;

		$material_receipt->people_id = $request->input('people_id');
		$material_receipt->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		$material_receipt->warehouse_id = $request->input('warehouse_id');
		$material_receipt->store_id = $request->input('store_id');
		$material_receipt->rack_id = $request->input('rack_id');
		if($request->input('employee_id') != null){
		$material_receipt->employee_id = $request->input('employee_id');
		}
		if($request->input('work_id') != null){
			$material_receipt->work_id = $request->input('work_id');
		}        
		$material_receipt->transaction_type_id = $transaction_type->id;
		$material_receipt->organization_id = $organization_id;
		$material_receipt->save();

		$item_id = $request->input('item_id');
		$description =$request->input('description');
		$quantity = $request->input('quantity');

		if(count($item_id) > 0)
		{
			for($i = 0;$i<count($item_id);$i++)
			{
				DB::table('material_receipt_items')->insert([
					'material_receipt_id' => $material_receipt->id, 
					'item_id' => $item_id[$i], 
					'description' => $description[$i],
					'quantity' => $quantity[$i]
				]);
			}
		}

		$material_receipts = MaterialReceipt::select('material_receipts.id','material_receipts.order_no','material_receipts.date','hrm_employees.first_name')
		->leftjoin('hrm_employees','material_receipts.employee_id','=','hrm_employees.id')
		->where('material_receipts.id',$material_receipt->id)
		->where('material_receipts.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Material Receipt'.config('constants.flash.added'), 'data' => ['id' => $material_receipts->id, 'order_no' => $material_receipts->order_no,'employee_name' => $material_receipts->first_name,'date'=>$material_receipts->date]]);

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
	   
		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$people->prepend('Select Customer', '');

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');
		$business->prepend('Select Business', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$transaction_type = AccountVoucher::where('name', 'material_receipt')->where('organization_id', $organization_id)->first();

	 
		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
		$employees->prepend('Select Person', '');

		$items = $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_models.category_id')
		->where('inventory_items.organization_id', $organization_id)
		->orderby('global_item_category_types.display_name')
		->get();

		$warehouse_id = BusinessAddressType::where('name','warehouse')->first()->id;

		$warehouse = BusinessCommunicationAddress::select('business_communication_addresses.*')
		->leftjoin('organizations','business_communication_addresses.business_id','=','organizations.business_id')
		->where('address_type',$warehouse_id)
		->where('organizations.id', $organization_id)
		->pluck('placename','id');   
		$warehouse->prepend('Select Warehouse','');

		$stores = InventoryStore::where('organization_id',$organization_id)->pluck('name','id');
		$stores->prepend('Select Store', '');
 
		$racks = InventoryRack::where('organization_id',$organization_id)->pluck('name','id');
		$racks->prepend('Select Store', '');

		/*$material_receipt = MaterialReceipt::where('organization_id',$organization_id)->where('id',$id)->first();*/

		$material_receipt = MaterialReceipt::select('material_receipts.*') 
		->where('material_receipts.organization_id',$organization_id)
		->where('id',$id)
		->first();

		$receipt_items = MaterialReceiptItem::select('material_receipt_items.*','inventory_items.name as item_name')
		->leftjoin('inventory_items','material_receipt_items.item_id','=','inventory_items.id')
		->where('material_receipt_items.material_receipt_id', $material_receipt->id)
		->get();

		$voucher_no = $material_receipt->order_no;

		return view('inventory.material_receipt_edit', compact('id','material_receipt','voucher_no', 'employees', 'items', 'people', 'business', 'transaction_type','warehouse','stores','racks','receipt_items'));
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
		//return $request->all();
		$organization_id = Session::get('organization_id');

		$material_receipt = MaterialReceipt::findorFail($request->input('id'));
		$material_receipt->user_type = $request->input('user_type');		
		$material_receipt->people_id = $request->input('people_id');
		$material_receipt->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		$material_receipt->warehouse_id = $request->input('warehouse_id');
		$material_receipt->store_id = $request->input('store_id');
		$material_receipt->rack_id = $request->input('rack_id');
		if($request->input('employee_id') != null){
		$material_receipt->employee_id = $request->input('employee_id');
		}
		if($request->input('work_id') != null){
			$material_receipt->work_id = $request->input('work_id');
		}        
	  
		$material_receipt->save();

		DB::table('material_receipt_items')->where('material_receipt_id', $material_receipt->id)->delete();

		$item_id = $request->input('item_id');
		$description =$request->input('description');
		$quantity = $request->input('quantity');

		if(count($item_id) > 0)
		{
			for($i = 0;$i<count($item_id);$i++)
			{
				DB::table('material_receipt_items')->insert([
					'material_receipt_id' => $material_receipt->id, 
					'item_id' => $item_id[$i], 
					'description' => $description[$i],
					'quantity' => $quantity[$i]
				]);
			}
		}

		$material_receipts = MaterialReceipt::select('material_receipts.id','material_receipts.order_no','material_receipts.date','hrm_employees.first_name')
		->leftjoin('hrm_employees','material_receipts.employee_id','=','hrm_employees.id')
		->where('material_receipts.id',$material_receipt->id)
		->where('material_receipts.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Material Receipt'.config('constants.flash.updated'), 'data' => ['id' => $material_receipts->id, 'order_no' => $material_receipts->order_no,'date'=>$material_receipts->date,'employee_name' => $material_receipts->first_name]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$material_receipt = MaterialReceipt::findOrFail($request->input('id'));
		$material_receipt->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Material Receipt'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$material_receipts = explode(',', $request->id);

		$material_receipt_list = [];

		foreach ($material_receipts as $material_receipt_id) {
			$material_receipt = MaterialReceipt::findOrFail($material_receipt_id);
			$material_receipt->delete();
			$material_receipt_list[] = $material_receipt_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Material Receipt'.config('constants.flash.deleted'),'data'=>['list' => $material_receipt_list]]);
	}
}
