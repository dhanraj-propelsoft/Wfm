<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Unit;
use App\InventoryItem;
use App\Custom;
use Validator;
use Session;

class MarketPlaceController extends Controller
{
	Public function index(){
		$organization_id=Session::get('organization_id');

		$items=InventoryItem::select('inventory_items.id as item_id','inventory_items.name as item_name','inventory_items.mrp','inventory_items.marketing_price as price','inventory_items.description as notes','global_item_categories.name as catgory_name','global_item_makes.name as make_name','organizations.name as org_name','business_communication_addresses.mobile_no','cities.name as city_name')
		->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
		->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
		->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')
		->leftjoin('organizations','organizations.id','=','inventory_items.organization_id')
		->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','organizations.business_id')
		->leftjoin('business_address_types','business_address_types.id','=','business_communication_addresses.address_type')
		->leftjoin('cities','cities.id','=','business_communication_addresses.city_id')
		->where('business_address_types.name','business')
		->where('inventory_items.organization_id','!=',$organization_id)
		->whereNotNull('inventory_items.marketing_price')->orderby('inventory_items.id','asc')
		->groupby('inventory_items.id')->get();
		//dd($items);
	return view('inventory.market_place',compact('items'));

	}
	
}
