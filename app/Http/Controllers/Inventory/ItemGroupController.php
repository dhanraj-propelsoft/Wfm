<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemMainCategory;
use App\GlobalItemCategoryType;
use App\GlobalItemCategory;
use App\InventoryAdjustment;
use App\InventoryCategory;
use App\InventoryItemGroup;
use App\InventoryItemStock;
use App\GlobalItemModel;
use App\GlobalItemType;
use App\GlobalItemMake;
use App\InventoryItem;
use App\AccountLedger;
use App\AccountGroup;
use App\Organization;
use Carbon\Carbon;
use App\TaxGroup;
use App\Tax;
use App\Business;
use App\Custom;
use App\Unit;
use Session;
use DB;

class ItemGroupController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($item_group)
	{
		$organization_id = Session::get('organization_id');

		$inventory_items = InventoryItem::select('inventory_items.*', 'global_item_categories.name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', DB::raw('GROUP_CONCAT(DISTINCT(groups.name) separator "`") as item_name'),  DB::raw('GROUP_CONCAT(DISTINCT(inventory_item_groups.quantity) separator "`") as item_quantity'), DB::raw('SUM(taxes.value) AS tax'))
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
		->leftjoin('taxes', 'taxes.id', '=', 'group_tax.tax_id')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->leftjoin('inventory_item_groups', 'inventory_item_groups.item_group_id', '=', 'inventory_items.id')
		->leftjoin('inventory_items as groups', 'inventory_item_groups.item_id', '=', 'groups.id')
		->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
		->whereNotNull('inventory_item_groups.item_group_id')
		->where('inventory_items.organization_id', $organization_id)
		->groupby('inventory_items.id')
		->get();
               // dd($inventory_items);
		return view('inventory.item_group',compact('inventory_items', 'item_group'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		 $inventory_category = InventoryCategory::where('status', 1)->where('organization_id', $organization_id)->pluck('name', 'id');		

		$inventory_category->prepend('Select Category', '');

		 $units = Unit::where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $units->prepend('Select Unit', '');

		 $taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'))
				->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
				->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
				->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
				->where('tax_groups.organization_id', $organization_id)
				->where('tax_groups.is_sales', '1')
				->groupby('tax_groups.id')
				->get();


		 $purchase_taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'))
				->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
				->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
				->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
				->where('tax_groups.organization_id', $organization_id)
				->where('tax_groups.is_purchase', '1')
				->groupby('tax_groups.id')
				->get();


		 $sale_group = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

		 $sale_account = AccountLedger::where('group_id', $sale_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $sale_account->prepend('Select Account', '');

		 $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;
		 
		 $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $purchase_account->prepend('Select Account', '');

		 $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $inventory_items = InventoryItem::where('organization_id', $organization_id)->pluck('name', 'id');

		 $inventory_items->prepend('Select Item', '');

		 $inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

		$category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
		$category->prepend('Select Category', '');

		$itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
		$itemtype->prepend('Select Type', '');

		$make = GlobalItemMake::where('status', 1)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		 return view('inventory.item_group_create', compact('inventory_category','taxes', 'purchase_taxes', 'units', 'inventory_items', 'sale_account', 'purchase_account', 'inventory_account', 'inventory_types','category','itemtype','make'));
	}

	public function get_item_price(Request $request)
	{

		$item_price = InventoryItem::select('inventory_items.id','inventory_items.sale_price_data','inventory_items.hsn','inventory_items.tax_id')		
		->where('inventory_items.id', $request->input('id'))->first();


		$group_taxes = DB::table('group_tax')->select('group_tax.group_id','group_tax.tax_id')
		->where('group_tax.group_id',$item_price->tax_id)->get();

		$tax_id =[];


		foreach ($group_taxes as $group_tax) {
			$tax_id[] = $group_tax->tax_id;		
		}

		
		$tax_value = Tax::select('taxes.id', 'taxes.display_name AS name', 'taxes.value')
		->whereIn('id',$tax_id)->sum('value');


		$sale_price = Custom::get_least_closest_date(json_decode($item_price->sale_price_data, true));

		if($item_price->include_tax != null) {
			$price = Custom::two_decimal($sale_price['price']*(($item_price->tax/100) + 1)) ;
		}
		else {
			$price = $sale_price['price'];
		}

		return response()->json(['price' => $price,'tax_id' => $item_price->tax_id,'hsn'=> $item_price->hsn,'tax_value' =>$tax_value]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//dd($request->all());
		$organization_id = Session::get('organization_id');
		 if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		}
		

		 $tax_id = $request->input('tax_id');		 

		 //dd($item_tax_id);
		 //$purchase_tax_id = $request->input('purchase_tax_id');

		$sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
		 ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		 ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		 ->where('tax_groups.organization_id', $organization_id)
		 ->where('tax_groups.id', $tax_id)
		 ->groupby('tax_groups.id')->first();

		 //$purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $purchase_tax_id)->groupby('tax_groups.id')->first();
 
		 $inventory_item = new InventoryItem;
		 $inventory_item->name = $request->input('name');

		 /*if($request->input('grouping') == 0) {
		 	$inventory_item->global_item_model_id = $request->input('item_id');
		 }*/
		 
		 //$inventory_item->sku = $request->input('sku');
		 $inventory_item->hsn = $request->input('hsn');
		 $inventory_item->tax_id = $tax_id;

		 $list_price = Custom::two_decimal($request->input('list_price'));
		 $discount = $request->input('discount');

		 if($discount != null && $discount != 0) {
		 	$discount_amount = $list_price * ( $discount / 100);
		 } else {
		 	$discount_amount = 0;
		 }

		
		 $inventory_item->include_tax = $request->input('include_tax');

		 if($request->input('include_tax') != null) {
		 	$sale_price =  Custom::two_decimal( ( $list_price - $discount_amount ) / (($sales_tax_value->value/100) + 1));
		} else {
		 	$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) );
		}

		if($request->input('grouping') == 1) {
			$inventory_item->sale_price_data = json_encode([["list_price" => $list_price, "discount" => $discount, "discount_amount" => $discount_amount,  "sale_price" => $sale_price, "on_date" => $on_date]]);
		}		

		 //$inventory_item->category_id = $request->input('category_id');
		 $inventory_item->category_type_id = ($request->input('category_type_id') != null)  ? $request->input('category_type_id') : null;

		 $inventory_item->description = $request->input('description');
		 //$inventory_item->low_stock = $request->input('low_stock');
		 $inventory_item->income_account = $request->input('income_account');
		 //$inventory_item->expense_account = $request->input('expense_account');
		 //$inventory_item->inventory_account = $request->input('inventory_account');
		 $inventory_item->unit_id = ($request->input('unit_id') != null)  ? $request->input('unit_id') : null;

		 $inventory_item->is_group = $request->input('grouping');
		 
		 $inventory_item->organization_id = Session::get('organization_id');
		 $inventory_item->created_by = $request->input('created_by');
		 $inventory_item->last_modified_by = $request->input('last_modified_by');
		 $inventory_item->save();

		  Custom::userby($inventory_item, true);
		  Custom::add_addon('records');


		  $category = InventoryItem::select('global_item_category_types.name')
		  ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'inventory_items.category_type_id')
		  ->where('inventory_items.category_type_id',$inventory_item->category_type_id)
		  ->first()->name;

		  if($inventory_item->id) {

		  // Stock maintain for goods only				
			/*if($category == 'goods') {

				$inventory_item_stock = new InventoryItemStock;
				$inventory_item_stock->id = $inventory_item->id;
				$inventory_item_stock->in_stock = $request->input('initial_quantity');
				$inventory_item_stock->date = date('Y-m-d H:i:s');
				$inventory_item_stock->data = json_encode([["date" => date('Y-m-d H:i:s'), "in_stock" => $request->input('initial_quantity')]]);
				$inventory_item_stock->save();
				Custom::userby($inventory_item_stock, true);
			}*/

			

		$item_id = $request->input('item_id');
		$quantity = $request->input('quantity');
		$price 	= $request->input('price');
		$item_tax_id = $request->input('item_tax_id');

		$individual_price = 0.00;


			for($i = 0; $i < count($item_id); $i++) {
				
				if(($quantity[$i] != "" && $category == 'goods' || $quantity[$i] != 0 && $category == 'goods') || ($category == 'service')) {

					$inventory_group_item = new InventoryItemGroup;
					$inventory_group_item->item_group_id = $inventory_item->id;
					$inventory_group_item->item_id = $item_id[$i];
					$inventory_group_item->quantity = ($quantity[$i] != null) ? $quantity[$i] : null;

					if($price[$i] != "")
					{
						$inventory_group_item->price = $price[$i];
						$individual_price += $price[$i];
					}
					$inventory_group_item->tax_id = ($item_tax_id[$i] != null) ? $item_tax_id[$i] : null;
					
					$inventory_group_item->save();

					Custom::userby($inventory_group_item, true);
				}
			   
			}

			if($request->input('grouping') == 0) {

				$inventory_item->sale_price_data = json_encode([["list_price" => $individual_price, "discount" => 0, "discount_amount" => 0,  "sale_price" => $individual_price, "on_date" => $on_date]]);

				 $inventory_item->save();
			}
			
		  }


		$inventory_group_items = InventoryItemGroup::select(DB::raw('COALESCE(inventory_item_groups.quantity, "") as quantity'), 'inventory_items.name')
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_item_groups.item_id')
		->where('inventory_item_groups.item_group_id', $inventory_item->id)
		->get();

		$unit = ($inventory_item->unit_id != null) ? Unit::findOrFail($inventory_item->unit_id)->display_name : "";
		$sale_price = Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));

		$response = array('status' => 1, 'message' => 'Inventory Item Group'.config('constants.flash.added'));

		$response['data']['id'] = $inventory_item->id;
		$response['data']['name'] = $inventory_item->name;
		//$response['data']['sku'] = $inventory_item->sku;
		$response['data']['hsn'] = $inventory_item->hsn;		
		$response['data']['sale_price'] = $request->input('sale_price'). "<span style='color:#aaa'> From ".Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>";
		$response['data']['description'] = $inventory_item->description;
		//$response['data']['low_stock'] = $inventory_item->low_stock;
		$response['data']['include_tax'] = $inventory_item->include_tax;	
		$response['data']['income_account'] = $inventory_item->income_account;
		$response['data']['unit'] = $unit;
		//$response['data']['expense_account'] = $inventory_item->expense_account;
		//$response['data']['inventory_account'] = $inventory_item->inventory_account;
		$response['data']['category_id'] = $inventory_item->category_id;
		$response['data']['category_type_id'] = $inventory_item->category_type_id;

		/*if(isset($inventory_item_stock)) {
			$response['data']['in_stock'] = $inventory_item_stock->in_stock;
		}*/
		if(isset($inventory_group_item)) {
			$response['data']['groups'] = $inventory_group_items; 

		}
		
		return response()->json($response);

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

		 $inventory_items = InventoryItem::select('inventory_items.*', 'inventory_item_groups.quantity', 'global_item_category_types.name as type_name')
		 ->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')
		 ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'inventory_categories.category_type_id')
		 ->leftjoin('inventory_item_groups', 'inventory_item_groups.item_group_id', '=', 'inventory_items.id')
		 ->where('inventory_items.id', $id)
		 ->where('inventory_items.organization_id',$organization_id)->first();


		 //$selected_category = InventoryCategory::find($inventory_items->category_id);

		 

		//$inventory_category = InventoryCategory::where('status', 1)->where('organization_id', $organization_id)->where('category_type_id',$selected_category->category_type_id)->pluck('name', 'id');		

		//$inventory_category->prepend('Select Category', '');

		 $sale_price = Custom::get_least_closest_date(json_decode($inventory_items->sale_price_data, true));
		 $price = $sale_price['price'];
		 $on_date = Carbon::parse($sale_price['date'])->format('d-m-Y');

		 $group_items = InventoryItemGroup::where('item_group_id', $id)->get();
		 

		 if($inventory_items == null) abort(403);

		 $inventory_item_stocks = InventoryItemStock::find($id);

		 $inventory_item = InventoryItem::where('organization_id', $organization_id)->pluck('name', 'id');
		 $inventory_item->prepend('Select Item', '');

		 $units = Unit::where('organization_id', $organization_id)->pluck('display_name', 'id');
		 $units->prepend('Select Unit', '');

		 $taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'))
				->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
				->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
				->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
				->where('tax_groups.organization_id', $organization_id)
				->where('tax_groups.is_sales', '1')
				->groupby('tax_groups.id')
				->get();


		$purchase_taxes = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'))
				->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id')
				->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
				->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
				->where('tax_groups.organization_id', $organization_id)
				->where('tax_groups.is_purchase', '1')
				->groupby('tax_groups.id')
				->get();

		 $sale_group = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

		 $sale_account = AccountLedger::where('group_id', $sale_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');
		 $sale_account->prepend('Select Account', '');

		 $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;

		 $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');
		 $purchase_account->prepend('Select Account', '');

		 $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');
		 $inventory_account->prepend('Select Account', '');

		 $inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

		 

		$category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
		$category->prepend('Select Category', '');

		$itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
		$itemtype->prepend('Select Type', '');

		$make = GlobalItemMake::where('status', 1)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		 return view('inventory.item_group_edit', compact('account_groups', 'inventory_items', 'inventory_item_stocks', 'units', 'taxes', 'purchase_taxes', 'sale_account', 'purchase_account', 'inventory_account', 'price', 'on_date', 'inventory_types', 'group_items', 'inventory_item','inventory_category','category','itemtype','make'));
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
		$organization_id = Session::get('organization_id');		

		 if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		 }

		 $tax_id = $request->input('tax_id');
		 $purchase_tax_id = $request->input('purchase_tax_id');

		 $sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $tax_id)->groupby('tax_groups.id')->first();
		


		 $inventory_item =  InventoryItem::findOrFail($request->input('id'));

		 $inventory_item->name = $request->input('name');		 
		 //$inventory_item->sku = $request->input('sku');
		 $inventory_item->hsn = $request->input('hsn');
		 $inventory_item->tax_id = $tax_id;

		 $list_price = Custom::two_decimal($request->input('list_price'));
		 $discount = $request->input('discount');

		 if($discount != null && $discount != 0) {
		 	$discount_amount = $list_price * ( $discount / 100);
		 } else {
		 	$discount_amount = 0;
		 }

		
		 $inventory_item->include_tax = $request->input('include_tax');

		 if($request->input('include_tax') != null) {
		 	$sale_price =  Custom::two_decimal( ( $list_price - $discount_amount ) / (($sales_tax_value->value/100) + 1));
		} else {
		 	$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) );
		}

		if($request->input('grouping') == 1) {
			$inventory_item->sale_price_data = json_encode([["list_price" => $list_price, "discount" => $discount, "discount_amount" => $discount_amount,  "sale_price" => $sale_price, "on_date" => $on_date]]);
		}		

		 $inventory_item->category_id = $request->input('category_id');
		  $inventory_item->category_type_id = ($request->input('category_type_id') != null)  ? $request->input('category_type_id') : null;
		 $inventory_item->description = $request->input('description');
		 //$inventory_item->low_stock = $request->input('low_stock');
		 $inventory_item->income_account = $request->input('income_account');
		 //$inventory_item->expense_account = $request->input('expense_account');
		 //$inventory_item->inventory_account = $request->input('inventory_account');
		 $inventory_item->unit_id = ($request->input('unit_id') != null)  ? $request->input('unit_id') : null;

		 $inventory_item->is_group = $request->input('grouping');
		 
		 $inventory_item->organization_id = Session::get('organization_id');
		 $inventory_item->created_by = $request->input('created_by');
		 $inventory_item->last_modified_by = $request->input('last_modified_by');
		 $inventory_item->save();

		  Custom::userby($inventory_item, true);
		  Custom::add_addon('records');


		  $category = InventoryCategory::select('global_item_category_types.name')
		  ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'inventory_categories.category_type_id')
		  ->where('inventory_categories.id', $inventory_item->category_type_id)
		  ->first()->name;



		  if($inventory_item->id) {

		  // Stock maintain for goods only - updated		

			/*if($category == 'goods') {

				$inventory_item_stock = InventoryItemStock::findOrFail($inventory_item->id);
				
				$inventory_item_stock->in_stock = $request->input('initial_quantity');
				$inventory_item_stock->date = date('Y-m-d H:i:s');
				$inventory_item_stock->data = json_encode([["date" => date('Y-m-d H:i:s'), "in_stock" => $request->input('initial_quantity')]]);
				$inventory_item_stock->save();
				Custom::userby($inventory_item_stock, true);
			}*/

			$item_id = $request->input('item_id');
			$quantity = $request->input('quantity');
			$price 	= $request->input('price');
			$item_tax_id = $request->input('item_tax_id');

			$individual_price = 0.00;

				
			InventoryItemGroup::where(['item_group_id' => $inventory_item->id])->delete();

			for($i = 0; $i < count($item_id); $i++) {
				
				if(($quantity[$i] != "" && $category == 'goods' || $quantity[$i] != 0 && $category == 'goods') || ($category == 'service')) {

					$inventory_group_item = new InventoryItemGroup;
					$inventory_group_item->item_group_id = $inventory_item->id;
					$inventory_group_item->item_id = $item_id[$i];
					$inventory_group_item->quantity = ($quantity[$i] != null) ? $quantity[$i] : null;

					if($price[$i] != "")
					{
						$inventory_group_item->price = $price[$i];
						$individual_price += $price[$i];
					}
					$inventory_group_item->tax_id = ($item_tax_id[$i] != null) ? $item_tax_id[$i] : null;
					
					$inventory_group_item->save();

					Custom::userby($inventory_group_item, false);
				}			   
			}

			if($request->input('grouping') == 0) {

				$inventory_item->sale_price_data = json_encode([["list_price" => $individual_price, "discount" => 0, "discount_amount" => 0,  "sale_price" => $individual_price, "on_date" => $on_date]]);

				 $inventory_item->save();
			}
			
		 }


		$inventory_group_items = InventoryItemGroup::select(DB::raw('COALESCE(inventory_item_groups.quantity, "") as quantity'), 'inventory_items.name')
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_item_groups.item_id')
		->where('inventory_item_groups.item_group_id', $inventory_item->id)
		->get();

		$unit = ($inventory_item->unit_id != null) ? Unit::findOrFail($inventory_item->unit_id)->display_name : "";
		$sale_price = Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));

		$response = array('status' => 1, 'message' => 'Inventory Item Group'.config('constants.flash.updated'));

		$response['data']['id'] = $inventory_item->id;
		$response['data']['name'] = $inventory_item->name;
		//$response['data']['sku'] = $inventory_item->sku;
		$response['data']['hsn'] = $inventory_item->hsn;		
		$response['data']['sale_price'] = $request->input('sale_price'). "<span style='color:#aaa'> From ".Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>";
		$response['data']['description'] = $inventory_item->description;
		//$response['data']['low_stock'] = $inventory_item->low_stock;
		$response['data']['include_tax'] = $inventory_item->include_tax;	
		$response['data']['income_account'] = $inventory_item->income_account;
		$response['data']['unit'] = $unit;
		//$response['data']['expense_account'] = $inventory_item->expense_account;
		//$response['data']['inventory_account'] = $inventory_item->inventory_account;
		$response['data']['category_id'] = $inventory_item->category_id;
		$response['data']['category_type_id'] = $inventory_item->category_type_id;

		/*if(isset($inventory_item_stock)) {
			$response['data']['in_stock'] = $inventory_item_stock->in_stock;
		}*/
		if(isset($inventory_group_item)) {
			$response['data']['groups'] = $inventory_group_items;
		}		
		
		return response()->json($response);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$inventory_item = InventoryItem::findOrFail($request->id);
		$inventory_adjustments = InventoryAdjustment::where('item_id', $request->id)->get();

		if(count($inventory_adjustments) > 0) {
			foreach($inventory_adjustments as $inventory_adjustment) {
				InventoryAdjustment::where('id', $inventory_adjustment->id)->first()->delete();
				Custom::delete_addon('records');
			}
		}

		$inventory_item->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Inventory Item Group'.config('constants.flash.deleted'), 'data' =>[]]);
	}



	public function multidestroy(Request $request)
	{
		$inventory_items = explode(',', $request->id);

		$item_list = [];

		foreach ($inventory_items as $item_id) {
			$item = InventoryItem::findOrFail($item_id);
			$item->delete();
			$item_list[] = $item_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.deleted'),'data'=>['list' => $item_list]]);
	}


	public function multiapprove(Request $request)
	{
		$inventory_items = explode(',', $request->id);

		$item_list = [];

		foreach ($inventory_items as $item_id) {
			InventoryItem::where('id', $item_id)->update(['status' => $request->input('status')]);;
			$item_list[] = $item_id;
		}

		return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.updated'),'data'=>['list' => $item_list]]);
	}

	/*public function get_categories_group(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$category = InventoryCategory::select('inventory_categories.id', 'inventory_categories.name')
		->where('inventory_categories.organization_id', $organization_id)
		->where('inventory_categories.category_type_id', $request->id)->get();

		 //$category = InventoryCategory::select('inventory_categories.id', 'inventory_categories.name')->where('name', 'Other')->where('organization_id', $organization_id)->first()->id;

		return response()->json($category);

	}*/

	public function item_group_image_upload(Request $request) {

		$file = $request->file('file');
		$id = $request->input('id');

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$path_array = explode('/', 'organizations/'.$business_name.'/item_group');

		$public_path = '';

		foreach ($path_array as $p) {
			$public_path .= $p."/";
			if (!file_exists(public_path($public_path))) {
				mkdir(public_path($public_path), 0777, true);
			}
		}

		$name = $id.".".$file->getClientOriginalName();

		$request->file('file')->move(public_path($public_path), $name);

		return response()->json(['status'=>1, 'message'=>'Item Group'.config('constants.flash.updated'),'data'=>['id' => $id, 'path' => URL::to('/').'/public/organizations/'.$business_name.'/item_group/'.$name]]);

	}



 
}
