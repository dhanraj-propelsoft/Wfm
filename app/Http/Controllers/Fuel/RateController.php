<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use App\Unit;
use App\FsmPump;
use App\FsmProduct;
use App\TaxGroup;
use App\AccountGroup;
use App\AccountLedger;
use App\HrmEmployee;
use App\CustomerGroping;
use App\InventoryAdjustment; 
use App\InventoryItem; 
use Carbon\Carbon;
use App\Custom;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
 public function change_rate(){
 	//dd("hai");
 	$organization_id=Session::get('organization_id');
 	$item=InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
 						->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
 						->where('global_item_categories.main_category_id',9)
 						->where('organization_id',$organization_id)
 						->select('inventory_items.name as item_name','inventory_items.id as item_id')
 						->get();
 					
 	   $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

            $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
            $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
            $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
            $tax->where('tax_groups.organization_id', $organization_id);

            $tax->groupby('tax_groups.id');
            $taxes = $tax->get();
 			//dd($taxes);

 	return view('fuel_station.today_rate',compact('item','taxes'));
 }
 public function fsmitem_update(Request $request){
 	//dd($request->all());

 	$organization_id = Session::get('organization_id');

		 if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		 }

		$id=$request->input('id');	
		$tax_id = $request->input('tax_id');
        $list_price= $request->input('list_price');
        $sale_price= $request->input('sale_price');


       for ($i=0; $i <count($id); $i++)
       {		

      

			$sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $tax_id[$i])->groupby('tax_groups.id')->first();
				
			 $inventory_item =  InventoryItem::findOrFail($id[$i]);

			

			 $inventory_item->selling_price = $list_price[$i];
			
			 $inventory_item->base_price = $sale_price[$i];
			 $inventory_item->tax_id = $tax_id[$i];
			

			$discount =0;
			$discount_amount = 0;
			$sale_price_array = json_decode($inventory_item->sale_price_data, true);
			$sale_price1= $sale_price[$i];

			foreach ($sale_price_array as $key => $value) {
				if($value['on_date'] == $on_date) {
					unset($sale_price_array[$key]);
				}
			}

			 $sale_price_data = array_values($sale_price_array);


			 $sale_price_data[] = ["list_price" => $list_price[$i], "discount" => $discount, "discount_amount" => $discount_amount,  "sale_price" => $sale_price1, "on_date" => $on_date];
			 $inventory_item->sale_price_data = json_encode($sale_price_data);
			 $inventory_item->organization_id = $organization_id;
			 $inventory_item->last_modified_by = Auth::user()->id;
			$inventory_item->save();	
	      
	    }
	     return response()->json([ 'message' => 'Item Rate'.config('constants.flash.updated'), 'data' =>['id'=>$inventory_item->id]]);
	}

}

