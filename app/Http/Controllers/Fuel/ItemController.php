<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemMainCategory;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use App\Unit;
use App\ InventoryItem;
use App\FsmPump;
 use App\TaxGroup;
use App\AccountGroup;
use App\AccountLedger;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
  public function item_index(){

    $organization_id = Session::get('organization_id');

    $inventory_items = InventoryItem::select('inventory_items.*', 'global_item_categories.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.sale_price_data', DB::raw('SUM(taxes.value) AS tax'),'inventory_items.purchase_price','inventory_items.selling_price')
    ->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
    ->leftjoin('taxes', 'group_tax.tax_id', '=', 'inventory_items.id')
    ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
    ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )
    ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
    ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
    ->where('inventory_items.organization_id', $organization_id)
    ->groupby('inventory_items.id')
    ->get();

 return view('fuel_station.fsm_item',compact('inventory_items'));

  }
  public function item_create()
  {
    $organization_id = Session::get('organization_id');

   

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

     $sale_account->prepend('Sales', '39');
        
     $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;

     $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

     $purchase_account->prepend('Purchases', '43');


     $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');

     //$inventory_account->prepend('Select Account', '');

    $category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
    $category->prepend('Select Category', '');

    $itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
    $itemtype->prepend('Select Type', '');

    $make = GlobalItemMake::where('status', 1)->pluck('display_name', 'id');
    $make->prepend('Select Make', '');

    $inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

   



     return view('fuel_station.fsm_item_create', compact('sale_account', 'purchase_account', 'units', 'taxes', 'inventory_account', 'inventory_types', 'purchase_taxes','category','itemtype','make'));
  }
  public function getproductlist($id){

  
        $pump = FsmPump::where("fsm_pumps.id",$id)
                    ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
                    ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
                    ->pluck("inventory_items.name","inventory_items.id");
                  
                    
                
        return response()->json($pump);
    }
    public function get_category_type(Request $request)
  {
       
   $module_name= Session::get('module_name');

   if($module_name == 'fuel_station'){

    $main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
    ->where('global_item_main_categories.category_type_id', $request->id)
     ->where('global_item_main_categories.id', 9)
    ->orderby('global_item_main_categories.display_name')
    ->get();

   }
   else{

    $main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
    ->where('global_item_main_categories.category_type_id', $request->id)
    ->orderby('global_item_main_categories.display_name')
    ->get();

   }
 

    $organization_id = Session::get('organization_id');

    $default_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
    ->where('global_item_main_categories.category_type_id', $request->id)
    ->where('global_item_main_categories.status', 1)
    ->orderby('global_item_main_categories.display_name')
    ->get();
    

    $category = []; 

   

    $type = [];
  
    $make = [];

    $models = [];
   
    return response()->json(['data' => ['default_category' => $default_category,'main_category' => $main_category, 'category' => $category, 'type' => $type, 'make' => $make, 'model' => $models]]);

  }
  

}
