<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemMainCategory;
use App\GlobalItemCategoryType;
use App\InventoryAdjustment;
use App\InventoryItemStock;
use App\GlobalItemCategory;
use App\InventoryCategory;
use App\InventoryItemBatch;
use App\GlobalItemModel;
use App\GlobalItemType;
use App\GlobalItemMake;
use App\InventoryItem;
use App\AccountLedger;
use App\AccountGroup;
use App\AccountEntry;
use App\Organization;
use App\OrgCustomValue;
use App\BusinessProfessionalism;
use App\WmsPriceList;
use Carbon\Carbon;
use App\TaxGroup;
use App\Business;
use App\Custom;
use App\Unit;
use Session;
use URL;
use DB;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
use App\Gst;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\InventoryItemStockLedger;
use App\Http\Controllers\Inventory\Item\InventoryItemService;


class ItemController extends Controller
{


    public function __construct(InventoryItemService $serv)
    {
        $this->serv = $serv;
    }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($item)
	{
		//dd($item);
		$organization_id = Session::get('organization_id');
		$country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $city = City::orderBy('name')->orderby('name')->pluck('name', 'id');
        $city->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

		$inventory_items = InventoryItem::select('inventory_items.*', DB::raw('if(global_item_makes.name is null,global_item_models.display_name,CONCAT(global_item_models.display_name, " - " , global_item_makes.name)) AS name'),'global_item_categories.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.sale_price_data', DB::raw('SUM(taxes.value) AS tax'),'inventory_items.purchase_price','inventory_items.selling_price')
		
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'inventory_items.id')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )
		->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id')

		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
		->where('inventory_items.organization_id', $organization_id)
		->groupby('inventory_items.id')
		->paginate(10);

		$module_name=Session::get('module_name');

		return view('inventory.item', compact('inventory_items', 'item','module_name','state','city','title','payment','terms','group_name'));
	}


	public function batch_migrate()
	{
		$inventory_items = InventoryItem::select('inventory_items.*', 'inventory_item_stocks.in_stock as in_stock')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->get();


		foreach ($inventory_items as $inventory_item) {

			$on_date = date('Y-m-d');
			$batch_date = str_replace('-', '', $on_date);

			$purchase_tax_id = $inventory_item->purchase_tax_id;
			

			$purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
			->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
			->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
			->where('tax_groups.id', $purchase_tax_id)
			->groupby('tax_groups.id')
			->first();

			if($purchase_tax_id != null)
		 	{
		 		$purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

		 		$purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
		 	}
			else{
		 		$purchase_tax_price = $inventory_item->purchase_price;
		 	}

			
			$inventory_item_batch = new InventoryItemBatch;

			$inventory_item_batch->item_id = $inventory_item->id;
			$inventory_item_batch->global_item_model_id = $inventory_item->global_item_model_id;
			$inventory_item_batch->batch_number = $batch_date.'/'.$inventory_item->id.'/SJ-Initial';
			$inventory_item_batch->purchase_price = $inventory_item->purchase_price;
			$inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
			$inventory_item_batch->purchase_tax_id = $purchase_tax_id;
			$inventory_item_batch->selling_price = $inventory_item->selling_price;
			$inventory_item_batch->selling_plus_tax_price = $inventory_item->base_price;

			$inventory_item_batch->sales_tax_id = $inventory_item->tax_id;
			$inventory_item_batch->quantity =  ($inventory_item->in_stock == '') ? 0.00 : $inventory_item->in_stock ;
			$inventory_item_batch->unit_id = $inventory_item->unit_id;
			$inventory_item_batch->organization_id = $inventory_item->organization_id;
			$inventory_item_batch->created_by = $inventory_item->created_by;
			$inventory_item_batch->last_modified_by = $inventory_item->last_modified_by;

			$inventory_item_batch->save();
		}
	}

	 /**
     * vehicle vatiant pagination for server side
     *
     * @return \Illuminate\Http\Response
     */

    function Item_pagination(Request $request,$item)
    {
      
	    if($request->ajax())
	    {
			$organization_id = Session::get('organization_id'); 
			
			$inventory_items_query = InventoryItem::select('inventory_items.*', DB::raw('if(global_item_makes.name is null,global_item_models.display_name,CONCAT(global_item_models.display_name, " - " , global_item_makes.name)) AS name'),'global_item_categories.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.sale_price_data', DB::raw('SUM(taxes.value) AS tax'),'inventory_items.purchase_price','inventory_items.selling_price')
			->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
			->leftjoin('taxes', 'group_tax.tax_id', '=', 'inventory_items.id')
			->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
			->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )
			->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id')

			->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
			->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
			->where('inventory_items.organization_id', $organization_id)
			->groupby('inventory_items.id');

	         if(Input::has('entrires')) {
	             // Do something!
	             $entrires=(is_numeric($request->input('entrires')))?$request->input('entrires'):10;
	           //  dd($entrires);
	            $inventory_items=$inventory_items_query->paginate($entrires);
	        }else{

	            $inventory_items=$inventory_items_query->paginate(10);
	        }

	        return view('inventory.item_pagination', compact('inventory_items'))->render();
	     }
	}

      /**
     * vehicle variant global search
     *
     * @return \Illuminate\Http\Response
     */
    function Item_global_search(Request $request,$item)
    {
        //Search column
        $columnsToSearch = ['inventory_items.name','global_item_categories.display_name','inventory_item_stocks.in_stock', 'units.name','inventory_items.purchase_price','inventory_items.selling_price'];

        $searchQuery = '%' . $request->search . '%';
        //Search query
      //  dd($searchQuery);
	  	$organization_id = Session::get('organization_id'); 
		
	  	$inventory_items_query = InventoryItem::select('inventory_items.*', 'global_item_categories.display_name AS category_name', 'inventory_item_stocks.in_stock as in_stock', 'units.name as unit', 'inventory_items.sale_price_data', DB::raw('SUM(taxes.value) AS tax'),'inventory_items.purchase_price','inventory_items.selling_price')
			->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
			->leftjoin('taxes', 'group_tax.tax_id', '=', 'inventory_items.id')
			->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
			->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )
			->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
			->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
			->where('inventory_items.organization_id', $organization_id)
			->groupby('inventory_items.id');
                    
                  //  $variant_query->where('vehicle_variants.id', 'LIKE', $searchQuery);
        $inventory_items_query->Where(function ($query) use ($columnsToSearch,$searchQuery)  {
                   
            foreach($columnsToSearch as $column) {
                        
                            $query->orWhere($column, 'LIKE', $searchQuery);
                }  
            }); 
                   // $variant->groupBy('vehicle_variants.id')
              //  $variants = $variant_query->paginate(100);
                if(Input::has('entrires')) {
                // Do something!
                        $entrires=($request->input('entrires')!="false")?$request->input('entrires'):10;
              //  dd($entrires);
                        $inventory_items=$inventory_items_query->paginate($entrires);
                        
                        }else{
   
                        $inventory_items=$inventory_items_query->paginate(10);
                    } 
                      $inventory_items->appends(['search'=>$request->search]);
                    
					return view('inventory.item_pagination', compact('inventory_items'))->render();
 
    }


	public function item_search(Request $request)
	{		
		$organization_id = Session::get('organization_id');

		$keyword = $request->input('term');

		 $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
      
        if($business_id)
        {
            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
            //dd($business_professionalism_id->business_professionalism_id);
       
        }

       	$query = GlobalItemModel::select('global_item_models.id','global_item_models.hsn','global_item_models.name as models',DB::raw('CONCAT(global_item_models.display_name, " - " , global_item_makes.name) AS model'),'global_item_categories.name as category_name','global_item_main_categories.name as main_category_name','global_item_makes.name as make_name','global_item_types.name as type_name','global_item_main_categories.id as main_category_id','global_item_categories.id as category_id','global_item_makes.id as make_id','global_item_types.id as type_id');
		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$query->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id');
		$query->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id');


		//$item_details = $query->get();		

		//$search = GlobalItemModel::select('global_item_models.id','global_item_models.name as model', 'global_item_models.hsn');

		$query->where("global_item_models.display_name", 'LIKE', '%'.$keyword.'%');
		if($business_professionalism_id->business_professionalism_id == 4)
		{
			$query->where("global_item_categories.main_category_id","=",12);
		}
		$item_search = $query->take(10)->get();


		$item_type = GlobalItemType::where('name', 'Finished Product')->where('status',1)->first()->id;
		     

		$item_array = [];

		foreach ($item_search as  $value ) {

			$item_array[] = ['id' => $value->id, 'label' => $value->model,'search_model' => substr( $value->model, 0, strpos( $value->model, "-")),'value' => $value->model, 'gst' => $value->hsn,'main_category_name' => $value->main_category_name, 'category_name' => $value->category_name, 'type_name' => $value->type_name, 'make_name' => $value->make_name,'main_category_id' => $value->main_category_id, 'category_id' => $value->category_id,'make_id'=> $value->make_id, 'type_id' => ($value->type_id != null) ? $value->type_id : $item_type,'models' => $value->models];
		}		
          
		return response()->json($item_array);		

		//return response()->json(['data' => ['model'=> $item_search ,'category' => $category,'main_category' => $main_category,'type' => $type, 'make' => $make]]);

	}
	//to check item name + make name
	public function check_duplicate_make_name(Request $request)
	{
		//dd($request->all());
		$item_name = $request->input('item_name');
		

		$organization_id = Session::get('organization_id');
		$check_make_duplicates = GlobalItemModel::leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id');
		$check_make_duplicates->where('global_item_models.name',$item_name);
		if(is_numeric($request->make_name))
		{
		$check_make_duplicates->where('global_item_models.make_id',$request->input('make_name'));

		}
		else
		{
		$check_make_duplicates->where('global_item_makes.name',$request->input('make_name'));

		}
		$check_make_duplicate = $check_make_duplicates->exists();
		//dd($check_make_duplicate);
		$check = InventoryItem::where('name',$request->input('item_name'))->where('organization_id',$organization_id)->exists();
		
		if(is_numeric($request->make_name))
		{
			$make_name = GlobalItemMake::where('id',$request->input('make_name'))->first()->name;
			//dd($make_name);

		}
		else
		{		
			$make_name = $request->input('make_name');
		}

		return response()->json(['data' => $check_make_duplicate ,'global' => $check ,'make_name' => $make_name]);
	}
	public function check_duplicate_item_name(Request $request)
	{
		//dd($request->all());
		$item_name = $request->input('item_name');
		

		$organization_id = Session::get('organization_id');
		$check_make_duplicates = GlobalItemModel::leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id');
		$check_make_duplicates->leftjoin('inventory_items','inventory_items.global_item_model_id','=','global_item_models.id');
		$check_make_duplicates->where('global_item_models.name',$item_name);
		if(is_numeric($request->make_name))
		{
		$check_make_duplicates->where('global_item_models.make_id',$request->input('make_name'));

		}
		else
		{
		$check_make_duplicates->where('global_item_makes.name',$request->input('make_name'));

		}
		$check_make_duplicates->where('inventory_items.organization_id',$organization_id);
		$check_make_duplicate = $check_make_duplicates->exists();
		//dd($check_make_duplicate);

		$check = GlobalItemModel::leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id');
		$check->where('global_item_models.name',$item_name);
		if(is_numeric($request->make_name))
		{
		$check->where('global_item_models.make_id',$request->input('make_name'));

		}
		else
		{
		$check->where('global_item_makes.name',$request->input('make_name'));

		}
	

		$check_globally = $check->exists();
		//dd($check_globally);

		
		if(is_numeric($request->make_name))
		{
			$make_name = GlobalItemMake::where('id',$request->input('make_name'))->first()->name;
			//dd($make_name);

		}
		else
		{
		
			$make_name = $request->input('make_name');

		}

		return response()->json(['data' => $check_make_duplicate ,'global' => $check_globally ,'make_name' => $make_name]);
	}


	/*public function item_search(Request $request)
	{

		$organization_id = Session::get('organization_id');

		$keyword = $request->input('term');

		$search = GlobalItemModel::select('global_item_models.id','global_item_models.hsn','global_item_models.name as model','global_item_categories.id as category_id','global_item_main_categories.id as main_category_id','global_item_makes.id as make_id','global_item_types.id as type_id');
		$search->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$search->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$search->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id');
		$search->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id');

		$search->where("global_item_models.name", 'LIKE', $keyword.'%');

		//$search->orWhere("global_item_categories.name", 'LIKE', $keyword.'%');
		//$search->orWhere("global_item_main_categories.name", 'LIKE', $keyword.'%');
		//$search->orWhere("global_item_types.name", 'LIKE', $keyword.'%');
		//$search->orWhere("global_item_makes.name", 'LIKE', $keyword.'%');

		$item_search = $search->get();

		

		$item_category = [];
		$item_main_category = [];
		$item_type = [];
		$item_make = [];

		foreach ($item_search as $item) {

			if ($item->category_id != '') {
				$item_category[] = $item->category_id;
			}
			if ($item->main_category_id != '') {
				$item_main_category[] = $item->main_category_id;
			}
			if ($item->type_id != '') {
				$item_type[] = $item->type_id;
			}
			if ($item->make_id != '') {
				$item_make[] = $item->make_id;
			}
			
		}

		$category = GlobalItemCategory::select('global_item_categories.id', 'global_item_categories.name AS category')
		->whereIn('global_item_categories.id', array_unique($item_category))->get();

		//dd($category);

		//$main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.name AS main_category');
		//$main_category->leftjoin('global_item_category_types','global_item_main_categories.category_type_id','=','global_item_category_types.id');
		//$main_category->where('global_item_main_categories.id',)->get();

		$main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS main_category')
		->where('global_item_main_categories.category_type_id', $request->category_type)
		->where('global_item_main_categories.status', 1)
		->orderby('global_item_main_categories.display_name')
		->get();

		$type = GlobalItemType::select('global_item_types.id', 'global_item_types.name AS type')
		->whereIn('global_item_types.id', $item_type)->get();

		$make = GlobalItemMake::select('global_item_makes.id', 'global_item_makes.name AS make')
		->whereIn('global_item_makes.id', $item_make)->get();

		return response()->json([['value' => '1', 'label' => 'redmi'], ['value' => '2', 'label' => 'test']]);

		return response()->json(['data' => ['model'=> $item_search ,'category' => $category,'main_category' => $main_category,'type' => $type, 'make' => $make]]);

	}*/

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($type)
	{
		$type_name = $type;
		$organization_id = Session::get('organization_id');

		/*$inventory_category = InventoryCategory::where('status', 1)->where('organization_id', $organization_id)->pluck('name', 'id');		

		$inventory_category->prepend('Select Category', '');*/

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

		 //$sale_account->prepend('Sales', '39');
        
		 $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;

		 $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 //$purchase_account->prepend('Purchases', '43');


		 $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 //$inventory_account->prepend('Select Account', '');

		 $item_type = GlobalItemType::where('name', 'Finished Product')->where('status',1)->first()->id;

		$category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
		$category->prepend('Select Category', '');


		$itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
		$itemtype->prepend('Select Type', '');

		$make = GlobalItemMake::where('status', 1)->orderby('display_name','asc')->pluck('display_name', 'id');
		$make->prepend('Select Make', '');
		//dd($make);
		$inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

		 $custom_values = OrgCustomValue::select('data1 as data1')
                 ->where('screen','item_Master_page')
                 ->where('organization_id',$organization_id)
                 ->first();
                 if($custom_values != null)
                 {
                 	$custom_name = Unit::where('organization_id',$organization_id)
        			 ->where('units.name',$custom_values->data1)->pluck('id');
                 	//dd($custom_name);
                 	
                 	
                 }
                 else
                 {
                 	$custom_name = Unit::where('organization_id',$organization_id)
        			 ->pluck('name','id');
        			 $custom_name->prepend('Select Item','');

                 }
         
                 //dd($custome_name);

		 return view('inventory.item_create', compact('sale_account', 'purchase_account', 'units', 'taxes', 'inventory_account', 'inventory_types', 'purchase_taxes','category','itemtype','make','item_type','custom_name','type_name'));
	}

	public function jc_create()
	{

		$module_name = Session::get('module_name');

		$organization_id = Session::get('organization_id');

		//dd(Session::get('module_name'));

		/*$inventory_category = InventoryCategory::where('status', 1)->where('organization_id', $organization_id)->pluck('name', 'id');		

		$inventory_category->prepend('Select Category', '');*/

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

		 //$sale_account->prepend('Sales', '39');
        
		 $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;

		 $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 //$purchase_account->prepend('Purchases', '43');


		 $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 //$inventory_account->prepend('Select Account', '');

		$category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
		$category->prepend('Select Category', '');

		$itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
		$itemtype->prepend('Select Type', '');

		$make = GlobalItemMake::where('status', 1)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		$inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();
		 $item_type = GlobalItemType::where('name', 'Finished Product')->where('status',1)->first()->id;


		 $custom_values = OrgCustomValue::select('data1 as data1')
                 ->where('screen','item_Master_page')
                 ->where('organization_id',$organization_id)
                 ->first();
                 if($custom_values != null)
                 {
                 	$custom_name = Unit::where('organization_id',$organization_id)
        			 ->where('units.name',$custom_values->data1)->pluck('id');
                 	//dd($custom_name);
                 	
                 	
                 }
                 else
                 {
                 	$custom_name = Unit::where('organization_id',$organization_id)
        			 ->pluck('name','id');
        			 $custom_name->prepend('Select Item','');

                 }

		 return view('inventory.jc_item_create', compact('sale_account', 'purchase_account', 'units', 'taxes', 'inventory_account', 'inventory_types', 'purchase_taxes','category','itemtype','make','item_type','type','custom_name'));
	}


	public function item_batch(Request $request, $id)
	{
		//dd($id);	
		$organization_id = Session::get('organization_id');

		$query = InventoryItem::select('global_item_main_categories.category_type_id');

		$query->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id');

		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');

		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');	

		$query->where('inventory_items.id', $id);
		$query->where('inventory_items.organization_id',$organization_id);

		$main_type = $query->first();

		//dd($main_type);

		if($main_type->category_type_id == 1){

			$item_batches = InventoryItem::select('inventory_item_batches.id AS item_batch_id','inventory_item_batches.batch_number','inventory_item_batches.quantity','inventory_item_batches.purchase_price','inventory_item_batches.selling_price','inventory_item_batches.purchase_plus_tax_price','inventory_item_batches.selling_plus_tax_price','inventory_items.name','inventory_items.tax_id','inventory_items.purchase_tax_id','tax_groups.name AS tax','inventory_items.id AS item_id')

			->leftjoin('inventory_item_batches','inventory_item_batches.item_id','=','inventory_items.id')	

			->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')
			
			->where('inventory_item_batches.item_id', '=', $id)
			->where('inventory_item_batches.organization_id', '=', $organization_id)
			->where('inventory_item_batches.quantity', '>', 0)
			->get();

		}

		if($main_type->category_type_id == 2){

			$item_batches = InventoryItem::select('inventory_items.name','inventory_items.tax_id','inventory_items.purchase_tax_id','tax_groups.name AS tax','wms_price_lists.id AS service_batch_id','wms_price_lists.price AS service_batch_price','inventory_items.id AS item_id','vehicle_segments.name AS segment_name')

			->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')

			->leftjoin('vehicle_segments', 'vehicle_segments.id', '=', 'wms_price_lists.vehicle_segments_id')

			->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')

			->where('wms_price_lists.inventory_item_id', '=', $id)  
			->where('wms_price_lists.organization_id', '=', $organization_id)
			->get();

		}	

		//$servive_batches = WmsPriceList::where('inventory_item_id',$id)->where('organization_id', $organization_id)->get();

		//dd($item_batches);

		return view('inventory.item_batch',compact('item_batches','main_type'));
	}

	public function select_batch(Request $request)
	{	
		$organization_id = Session::get('organization_id');
		$id = $request->id;
		$item_id = $request->item_id;
		$item_type = $request->item_type;

		//dd($id);

		/* $item_type 1 Goods, 2 Service*/

		$module_name = Session::get('module_name');

		if($item_type == 1)
		{
			$batch_query = InventoryItem::select('inventory_item_batches.id AS goods_batch_id','inventory_item_batches.batch_number','inventory_item_batches.quantity','inventory_item_batches.purchase_price','inventory_item_batches.selling_price','inventory_item_batches.purchase_plus_tax_price','inventory_item_batches.selling_plus_tax_price','inventory_items.name','inventory_items.tax_id','inventory_items.purchase_tax_id','inventory_items.id')

			->leftjoin('inventory_item_batches','inventory_item_batches.item_id','=','inventory_items.id')

			->where('inventory_items.id', '=', $item_id)
			->where('inventory_item_batches.id', '=', $id)
			->where('inventory_item_batches.organization_id', '=', $organization_id)
			->first();

			/* not use this */

			$service_base_price = $batch_query->selling_price;		
		}
		

		if($item_type == 2)
		{
			$batch_query = InventoryItem::select('inventory_items.name','inventory_items.tax_id','inventory_items.purchase_tax_id','inventory_items.id AS inventory_item_id','wms_price_lists.id AS service_batch_id','wms_price_lists.price AS service_batch_price','inventory_items.id')

			->leftjoin('inventory_item_batches','inventory_item_batches.item_id','=','inventory_items.id')

			->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')

			->where('inventory_items.id', '=', $item_id)
			->where('wms_price_lists.id', '=', $id)    
			->where('wms_price_lists.organization_id', '=', $organization_id)
			 ->first();	

			$sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $batch_query->tax_id)->groupby('tax_groups.id')->first();			


			if($batch_query->tax_id != null)
			{
			 	//$tax_amount = Custom::two_decimal(($sales_tax_value->value/100) * ($batch_query->service_batch_price));

			 	$tax_amount = Custom::two_decimal($batch_query->service_batch_price / (($sales_tax_value->value/100) + 1 ));			 	

			 	$service_base_price = $tax_amount;
			 	
			}
			else{
			 	$service_base_price = $batch_query->service_batch_price;
			}			
		}


		

		return response()->json(array('status' => 1, 'message' => 'Item'.config('constants.flash.added'), 'batch_query' => $batch_query,'module_name' => $module_name,'item_type' => $item_type,'service_base_price' => $service_base_price));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	public function store(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$module_name = Session::get('module_name');
		
		 if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		}

		 $tax_id = $request->input('tax_id');
		 $purchase_tax_id = $request->input('purchase_tax_id');

		$sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('tax_groups.organization_id', $organization_id)
		->where('tax_groups.id', $tax_id)
		->groupby('tax_groups.id')
		->first();

		$purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('tax_groups.organization_id', $organization_id)
		->where('tax_groups.id', $purchase_tax_id)
		->groupby('tax_groups.id')
		->first();

		 $global_item = GlobalItemModel::find($request->input('item_id'));
		 
		 if($global_item->hsn == null) {
		 	$global_item->hsn = $request->input('hsn');
		 	$global_item->save();
		 }


		 $global_item_category = GlobalItemCategory::select('global_item_categories.id','global_item_categories.name','global_item_categories.display_name','global_item_category_types.id AS category_type_id')
		 ->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')
		  ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')
		 ->where('global_item_categories.id',$global_item->category_id)
		 ->first();

		 //dd($global_item_category);

		

		 $item_category_exist =  InventoryCategory::where('name', $global_item_category->name)->where('organization_id', $organization_id)->first();

		 if($item_category_exist != null) {
		 	$category_id = $item_category_exist->id;
		 } else {
		 	$category = new InventoryCategory;

		 	$category->name = $global_item_category->name;
		 	$category->display_name = $global_item_category->display_name;
		 	$category->category_type_id = $global_item_category->category_type_id;
		 	$category->status = 1;
			$category->organization_id = $organization_id;
			$category->save();
			$category_id = $category->id;	
		 }


		 $inventory_item = new InventoryItem;
		 $inventory_item->name = $request->input('name');
		 $inventory_item->category_id = $category_id;
		//$inventory_item->category_id = $global_item->category_id;
		 $inventory_item->global_item_model_id = $request->input('item_id');
		 $inventory_item->sku = $request->input('sku');
		 $inventory_item->hsn = $request->input('hsn');
		 $inventory_item->mpn = $request->input('mpn');
		 $inventory_item->duration = $request->input('duration');
		 $inventory_item->mrp = $request->input('mrp');
		 $inventory_item->marketing_price = $request->input('marketing_price');

		 $inventory_item->selling_price = ($request->input('list_price') != null)  ? $request->input('list_price') : 0.00;

		 $inventory_item->base_price = ($request->input('sale_price') != null)  ? $request->input('sale_price') : 0.00;

		 $inventory_item->description = $request->input('description');
		 $inventory_item->low_stock = $request->input('low_stock');
		 $inventory_item->include_tax = $request->input('include_tax');
		 $inventory_item->tax_id = $tax_id;

		 $list_price = Custom::two_decimal(($request->input('list_price') != null)  ? $request->input('list_price') : 0);

		 $discount = $request->input('discount');

		 if($discount != null && $discount != 0) {
		 	$discount_amount = $list_price * ( $discount / 100);
		 } else {
		 	$discount_amount = 0;
		 }
		 

		 if($request->input('include_tax') != null && $tax_id != null) {
		 	$sale_price =  Custom::two_decimal( ( $list_price - $discount_amount ) / (($sales_tax_value->value/100) + 1));
		} else {
		 	$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) );
		}

		 $inventory_item->sale_price_data = json_encode([["list_price" => $list_price, "discount" => $discount, "discount_amount" => $discount_amount,  "sale_price" => $sale_price, "on_date" => $on_date]]);
		 //dd($inventory_item->sale_price_data);

		 $inventory_item->include_purchase_tax = ($request->input('include_purchase_tax') != null)  ? $request->input('include_purchase_tax') : 0.00;


		if($request->input('include_purchase_tax') != null && $purchase_tax_id != null) {
		 	$inventory_item->purchase_price = Custom::two_decimal($request->input('purchase_price') / (($purchase_tax_value->value/100) + 1 ));
		}
		 else {
		 	$inventory_item->purchase_price = ($request->input('purchase_price') != null)  ? $request->input('purchase_price') : 0.00;
		}
	 
		 $inventory_item->income_account = $request->input('income_account');
		 $inventory_item->expense_account = $request->input('expense_account');
		 $inventory_item->inventory_account = $request->input('inventory_account');
		 $inventory_item->unit_id = ($request->input('unit_id') != null)  ? $request->input('unit_id') : null;
		 $inventory_item->minimum_order_quantity = ($request->input('minimum_order_quantity') != null)  ? $request->input('minimum_order_quantity') : null;
		 $inventory_item->purchase_tax_id = $purchase_tax_id;
		 $inventory_item->organization_id = $organization_id;
		 $inventory_item->save();

		  Custom::userby($inventory_item, true);
		  Custom::add_addon('records');


		 if($purchase_tax_id != null)
		 {
		 	$purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

		 	$purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
		 }
		 else{
		 	$purchase_tax_price = $inventory_item->purchase_price;
		 }



		if($inventory_item->id && $request->input('purchase')) 
		  {
			$inventory_item_stock = new InventoryItemStock;
			$inventory_item_stock->id = $inventory_item->id;
			$inventory_item_stock->in_stock = $request->input('initial_quantity');
			$inventory_item_stock->date = date('Y-m-d H:i:s');	
			
			$inventory_item_stock->save();

			Custom::userby($inventory_item_stock, true);

			$credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

			$entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_item_stock->in_stock) ];

			$inventory_item_stock->entry_id = Custom::add_entry($on_date, $entry, null, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);

			$inventory_item_stock->save();

			$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();				

			$data = json_decode($inventory_item_stock->data, true);	

			$data[] = ["transaction_id" => null,"entry_id" => $inventory_item_stock->entry_id,"voucher_type" => 'Stock Journal',"order_no" => $voucher->voucher_no,"quantity" => $request->input('initial_quantity'),"date" => date('Y-m-d H:i:s'), "in_stock" => $request->input('initial_quantity'),'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

			$inventory_item_stock->data = json_encode($data);

			$inventory_item_stock->save();				

		}

		if($inventory_item->id && $request->input('purchase'))
		{ 
			$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();
			

			$batch_date = str_replace('-', '', $on_date);			

			$inventory_item_batch = new InventoryItemBatch;

			$inventory_item_batch->item_id = $inventory_item->id;
			$inventory_item_batch->global_item_model_id = $inventory_item->global_item_model_id;
			$inventory_item_batch->batch_number = $batch_date.'/'.$inventory_item->id.'/'.$voucher->voucher_no;

			$inventory_item_batch->purchase_price = $inventory_item->purchase_price;
			$inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
			
			$inventory_item_batch->selling_price = $inventory_item->selling_price;
			$inventory_item_batch->selling_plus_tax_price = $inventory_item->base_price;

			$inventory_item_batch->purchase_tax_id = $purchase_tax_id;
			$inventory_item_batch->sales_tax_id = $tax_id;

			$inventory_item_batch->quantity = $request->input('initial_quantity');
			$inventory_item_batch->unit_id = $inventory_item->unit_id;
			
			$inventory_item_batch->organization_id = $organization_id;
			$inventory_item_batch->save();

			Custom::userby($inventory_item_batch, true);


			/*Inventory item stock ledger*/

			$model = new InventoryItemStockLedger();
            $model->inventory_item_stock_id = $inventory_item_stock->id;
            $model->inventory_item_batch_id = $inventory_item_batch->id;
            $model->transaction_id =  null;
            $model->account_entry_id = (isset($inventory_item_stock->entry_id)) ? $inventory_item_stock->entry_id : null; 
            $model->voucher_type = 'Stock Journal'; 
            $model->order_no = $voucher->voucher_no; 
            $model->quantity = (isset($inventory_item_stock->in_stock)) ? $inventory_item_stock->in_stock : 0.00;

            $model->date = $inventory_item_stock->date; 
            $model->in_stock = (isset($inventory_item_stock->in_stock)) ? $inventory_item_stock->in_stock : 0.00;

            $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;

            $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;

            $model->status = 1;

            $model->created_at = (Carbon::now());

            $model->save();	

            /*End*/

		}
		  

		$unit = ($inventory_item->unit_id != null) ? Unit::findOrFail($inventory_item->unit_id)->display_name : "";
		$sale_price = Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));
      
		$response = array('status' => 1, 'message' => 'Item'.config('constants.flash.added'));


		$response['data']['id'] = $inventory_item->id;
		$response['data']['name'] = $inventory_item->name;
		$response['data']['sku'] = $inventory_item->sku;
		$response['data']['hsn'] = $inventory_item->hsn;
		//$response['data']['purchase_price'] = $inventory_item->purchase_price;
		$response['data']['sale_price'] = $request->input('sale_price'). "<span style='color:#aaa'> From ".Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>";
		$response['data']['description'] = $inventory_item->description;
		$response['data']['low_stock'] = $inventory_item->low_stock;
		$response['data']['include_tax'] = $inventory_item->include_tax;
		$response['data']['include_purchase_tax'] = $inventory_item->include_purchase_tax;
		$response['data']['tax_id'] = $inventory_item->tax_id;
		$response['data']['purchase_tax_id'] = $inventory_item->purchase_tax_id;
		$response['data']['income_account'] = $inventory_item->income_account;
		$response['data']['unit'] = $unit;
		$response['data']['expense_account'] = $inventory_item->expense_account;
		$response['data']['inventory_account'] = $inventory_item->inventory_account;
		//$response['data']['category_id'] = $inventory_item->category_id;
		$response['data']['category_name'] = $global_item_category->display_name;
		$response['data']['image'] = $inventory_item->image;
        $response['data']['purchase_price'] = $inventory_item->purchase_price;
        $response['data']['selling_price'] = $inventory_item->selling_price;
		if(isset($inventory_item_stock)) {
			$response['data']['in_stock'] = $inventory_item_stock->in_stock;
			$response['data']['date'] = $inventory_item_stock->date;
		   
		}

		$response['data']['module_name'] = $module_name;
		
		return response()->json($response);

		

	}

	public function get_item(Request $request) {
		
		$inventory_item = InventoryItem::select('inventory_items.*', 'global_item_categories.name AS category_name', 'inventory_item_stocks.in_stock', 'units.name as unit', 'inventory_item_stocks.date')

		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		
		//->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id' )
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )->where('inventory_items.id', $request->input('id'))
		->where('inventory_items.organization_id', $organization_id)
		->first();

		 
		 return response()->json(array('status' => 1, 'message' => 'Item'.config('constants.flash.added'), 'data' => ['id' => $inventory_item->id, 'name' => $inventory_item->name, 'sku' => $inventory_item->sku, 'hsn' => $inventory_item->hsn, 'purchase_price' => $inventory_item->purchase_price, 'sale_price_data' => $inventory_item->sale_price_data, 'description' => $inventory_item->description, 'low_stock' => $inventory_item->low_stock, 'include_tax' => $inventory_item->include_tax, 'include_purchase_tax' => $inventory_item->include_purchase_tax, 'income_account' => $inventory_item->income_account, 'unit_id' => $inventory_item->unit_id, 'expense_account' => $inventory_item->expense_account, 'inventory_account' => $inventory_item->inventory_account, 'category_id' =>  $inventory_item->category_id, 'initial_quantity' => $inventory_item->initial_quantity, 'date'=> $inventory_item->date, 'in_stock' => $inventory_item->in_stock]));

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
		$organization_id = Session::get('organization_id');

		$item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.name AS category_name', 'inventory_item_stocks.in_stock', 'inventory_items.sale_price_data')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )
		->where('inventory_items.organization_id', $organization_id)
		->where('inventory_items.id', $id)
		->first();

		if(!$item) abort(404);

		$sale_price_data = json_decode($item->sale_price_data, true);

		 usort($sale_price_data, function ($item1, $item2) {
			return $item2['on_date'] <=> $item1['on_date'];
		});
   

		return view('inventory.item_show', compact('item', 'sale_price_data'));
	}

	public function data_remove(Request $request) {

	  $sale_price = [];

		$item = InventoryItem::findOrFail($request->id);

		$sale_price_data = json_decode($item->sale_price_data, true);

		foreach($sale_price_data as $data){
			if($data['sale_price'] != $request->sale_price && $data['on_date'] != $request->on_date) {

				 $sale_price[] = ['sale_price' => $data['sale_price'], 'on_date' => $data['on_date']];
			}


		}

		$item->sale_price_data = json_encode($sale_price);
		$item->save();

		return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.updated'),'data'=>[]]);
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

		$query = InventoryItem::select('inventory_items.*', 'global_item_category_types.name as type_name','global_item_categories.name as category_name','global_item_main_categories.name as main_category_name','global_item_makes.name as make_name','global_item_types.name as type','global_item_models.identifier_a');

		$query->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id');
		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$query->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id');
		$query->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id');		
			
		$query->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id');

		$query->where('inventory_items.id', $id);
		$query->where('inventory_items.organization_id',$organization_id);

		$inventory_items = $query->first();
		

		$purchase_price_tax ='';
		if($inventory_items->purchase_price)
		{
			$purchase_price = $inventory_items->purchase_price;
			
			$purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
					 ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
					 ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
					 ->where('tax_groups.organization_id', $organization_id)
					 ->where('tax_groups.id', $inventory_items->purchase_tax_id)
					 ->groupby('tax_groups.id')->first();
					// dd($purchase_tax_value);
					if($purchase_tax_value != null)
					{
						$val = $purchase_tax_value->value/100;
						$to = $purchase_price * $val;
						$purchase_price_tax1 = $purchase_price + $to;
						$purchase_price_tax= Custom::two_decimal($purchase_price_tax1);
					}
					if($purchase_tax_value == null)
					{
					 	$purchase_price_tax= Custom::two_decimal($purchase_price);
					}
					


		}

		/*$selected_category = InventoryCategory::find($inventory_items->category_id);

		$inventory_category = InventoryCategory::where('status', 1)->where('organization_id', $organization_id)->where('category_type_id',$selected_category->category_type_id)->pluck('name', 'id');		

		$inventory_category->prepend('Select Category', '');*/
		


		 $sale_price = Custom::get_least_closest_date(json_decode($inventory_items->sale_price_data, true));
		 $list_price = $sale_price['list_price'];
		 $discount = $sale_price['discount'];
		 $price = $sale_price['price'];

		 $date = Carbon::parse($sale_price['date'])->format('d-m-Y');

		 $inventory_item_stocks = InventoryItemStock::find($id);

		 $units = Unit::where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $units->prepend('Select Unit', '');

		 $sale_group = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

		 $sale_account = AccountLedger::where('group_id', $sale_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $sale_account->prepend('Select Account', '');

		 $purchase_group = AccountGroup::where('name', 'purchase_account')->where('organization_id', $organization_id)->first()->id;

		 $purchase_account = AccountLedger::where('group_id', $purchase_group)->where('organization_id', $organization_id)->pluck('display_name', 'id');

		 $purchase_account->prepend('Select Account', '');

		 $inventory_account = AccountLedger::where('name', 'inventory_asset')->where('organization_id', $organization_id)->pluck('display_name', 'id');

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

		$category = GlobalItemCategory::where('status', 1)->pluck('display_name', 'id');
		$category->prepend('Select Category', '');

		$itemtype = GlobalItemType::where('status', 1)->pluck('display_name', 'id');
		$itemtype->prepend('Select Type', '');

		$make = GlobalItemMake::where('status', 1)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		$inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

		$item_type = GlobalItemType::where('name', 'Finished Product')->where('status',1)->first()->id;

		 return view('inventory.item_edit', compact('inventory_items', 'inventory_item_stocks', 'units', 'taxes', 'purchase_taxes', 'sale_account', 'purchase_account', 'inventory_account', 'list_price', 'discount', 'price', 'date', 'inventory_types','category','itemtype','make','item_type','purchase_price_tax'));

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
		//dd($request->all());
		$organization_id = Session::get('organization_id');

		if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		} 
		else {
			$on_date = date('Y-m-d');
		}

		$tax_id = $request->input('tax_id');
		$purchase_tax_id = $request->input('purchase_tax_id');

		$sales_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $tax_id)->groupby('tax_groups.id')->first();

		 $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')->where('tax_groups.organization_id', $organization_id)->where('tax_groups.id', $purchase_tax_id)->groupby('tax_groups.id')->first();

		 $query = InventoryItem::select('inventory_items.*', 'global_item_category_types.name as type_name','global_item_categories.name as category_name','global_item_main_categories.name as main_category_name','global_item_makes.name as make_name','global_item_types.name as type','global_item_models.identifier_a');

		$query->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id');
		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$query->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id');
		$query->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id');		
			
		$query->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id');

		$query->where('inventory_items.id',$request->input('id'));
		$query->where('inventory_items.organization_id',$organization_id);

		$category_name = $query->first();

		 $inventory_item =  InventoryItem::findOrFail($request->input('id'));
		 $inventory_item->name = $request->input('name');
		// $inventory_item->category_id = $request->input('category_id');
		 $inventory_item->sku = $request->input('sku');
		 $inventory_item->hsn = $request->input('hsn');
		 $inventory_item->mpn = $request->input('mpn');
		 $inventory_item->duration = $request->input('duration');
		 $inventory_item->mrp = $request->input('mrp');
		 $inventory_item->marketing_price = $request->input('marketing_price');
		 $inventory_item->include_tax = $request->input('include_tax');
		 $inventory_item->include_purchase_tax = $request->input('include_purchase_tax');
		 $inventory_item->selling_price = ($request->input('list_price') != null)  ? $request->input('list_price') : 0.00;
		 $inventory_item->base_price = ($request->input('sale_price') != null)  ? $request->input('sale_price') : 0.00;

		 $inventory_item->tax_id = $tax_id;
		 $inventory_item->purchase_tax_id = $purchase_tax_id;

		 $list_price = Custom::two_decimal(($request->input('list_price') != null)  ? $request->input('list_price') : 0);

		 $discount = $request->input('discount');

		 if($discount != null && $discount != 0) {
		 	$discount_amount = $list_price * ( $discount / 100);
		 } else {
		 	$discount_amount = 0;
		 }

		 if($tax_id != null)
		 {
		 	$tax_amount1 = Custom::two_decimal(($sales_tax_value->value/100) * ($list_price));
		 }
		 else{
		 	$tax_amount1 = 0.00;
		 }
		

		 $sale_price_array = json_decode($inventory_item->sale_price_data, true);

		 if($request->input('include_tax') != null && $tax_id != null) {

		 	/*tax exclude*/

		 	//$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) / (($sales_tax_value->value/100) + 1));

		 	/*tax include*/

		 	$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) +  $tax_amount1 );

		 } else {
		 	$sale_price = Custom::two_decimal( ( $list_price - $discount_amount ) );
		 }

		foreach ($sale_price_array as $key => $value) {
			if($value['on_date'] == $on_date) {
				unset($sale_price_array[$key]);
			}
		}
		
    	 $sale_price_data = array_values($sale_price_array);

		 $sale_price_data[] = ["list_price" => $list_price, "discount" => $discount, "discount_amount" => $discount_amount,  "sale_price" => $sale_price, "on_date" => $on_date];

		 $inventory_item->sale_price_data = json_encode($sale_price_data);

		 $inventory_item->include_purchase_tax = $request->input('include_purchase_tax');

		 if($inventory_item->include_purchase_tax != null && $purchase_tax_id != null) {

		 	$inventory_item->purchase_price = Custom::two_decimal($request->input('purchase_price') / (($purchase_tax_value->value/100) + 1 ));
		 }
		 else {
		 	$inventory_item->purchase_price = ($request->input('purchase_price') != null)  ? $request->input('purchase_price') : 0.00;
		 }

		$inventory_item->description = $request->input('description');
		$inventory_item->low_stock = $request->input('low_stock');
		$inventory_item->income_account = $request->input('income_account');
		$inventory_item->unit_id = $request->input('unit_id');
		$inventory_item->minimum_order_quantity = ($request->input('minimum_order_quantity') != null)  ? $request->input('minimum_order_quantity') : null;
		$inventory_item->organization_id = $organization_id;
		$inventory_item->expense_account = $request->input('expense_account');
		$inventory_item->inventory_account = $request->input('inventory_account');
		$inventory_item->created_by = $request->input('created_by');
		$inventory_item->last_modified_by = $request->input('last_modified_by');
		$inventory_item->save();

		if($purchase_tax_id != null)
		 {
		 	$purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($inventory_item->purchase_price));

		 	$purchase_tax_price = Custom::two_decimal($inventory_item->purchase_price + $purchase_tax_amount);
		 }
		 else{
		 	$purchase_tax_price = $inventory_item->purchase_price;
		 }

		Custom::userby($inventory_item, false);

			$inventory_item_stock =  InventoryItemStock::find($inventory_item->id);

			if($request->input('purchase') != false) {
			 	
				if($inventory_item_stock != null) {
			   
					$inventory_stock =  InventoryItemStock::findOrFail($inventory_item->id);

					$voucher = AccountEntry::where('id',$inventory_stock->entry_id)->first();

					$inventory_stock->date = date('Y-m-d H:i:s');

					$inventory_stock->in_stock = $request->input('initial_quantity');

					$data = json_decode($inventory_stock->data, true);	

					$data[] = ["transaction_id" => null,"entry_id" => $inventory_stock->entry_id,"voucher_type" => 'Stock Journal',"order_no" => $voucher->voucher_no,"quantity" => $request->input('initial_quantity'),"date" => date('Y-m-d H:i:s'), "in_stock" => $request->input('initial_quantity'),'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

					$inventory_stock->data = json_encode($data);	
					

					$credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

					$entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($purchase_tax_price * $inventory_stock->in_stock) ];

					$inventory_stock->entry_id = Custom::add_entry($on_date, $entry, $inventory_stock->entry_id, 'stock_journal', $organization_id, 1, false,null,null,null,null,null,null);

					$inventory_stock->save();

					/*inventoty item stock leger*/

					$model = InventoryItemStockLedger::where('inventory_item_stock_id',$inventory_stock->id)->first();
		            
		            //$model->transaction_id =  null;
		            //$model->account_entry_id = $inventory_stock->entry_id; 
		            //$model->voucher_type = 'Stock Journal'; 
		            //$model->order_no = $voucher->voucher_no; 
		            $model->quantity = (isset($inventory_stock->in_stock)) ? $inventory_stock->in_stock : 0.00;
		            
		            $model->date = $inventory_stock->date; 

		            $model->in_stock = (isset($inventory_stock->in_stock)) ? $inventory_stock->in_stock : 0.00;

		            $model->purchase_price = (isset($purchase_tax_price)) ? $purchase_tax_price : 0.00;

		            $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;

		            //$model->status = 1;

		            $model->created_at = (Carbon::now());

		            $model->save();

		            /*end*/	

					Custom::userby($inventory_stock, false);

				}
				else {

					$inventory_stock = new InventoryItemStock;
					$inventory_stock->id = $inventory_item->id;
					$inventory_stock->in_stock = $request->input('initial_quantity');
					$inventory_stock->date = date('Y-m-d H:i:s');

					$inventory_stock->save();

					$credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

					$entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($inventory_item->purchase_price * $inventory_stock->in_stock) ];

					$inventory_stock->entry_id = Custom::add_entry($on_date, $entry, null, 'stock_journal', $organization_id, 1, false);

					$inventory_stock->save();

					$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();

					$data = json_decode($inventory_stock->data, true);	

					$data[] = ["transaction_id" => null,"entry_id" => $inventory_stock->entry_id,"voucher_type" => 'Stock Journal',"order_no" => $voucher->voucher_no,"quantity" => $request->input('initial_quantity'),"date" => date('Y-m-d H:i:s'), "in_stock" => $request->input('initial_quantity'),'purchase_price' => $purchase_tax_price,'sale_price' => $inventory_item->base_price,'status' => 1];

					$inventory_stock->data = json_encode($data);

					Custom::userby($inventory_stock, true);
				}

			}
			else {
				if($inventory_item_stock != null) {
					$inventory_stock =  InventoryItemStock::findOrFail($inventory_item->id)->delete();

					if($inventory_item_stock->entry_id != null) {
						AccountEntry::where('account_entries.id', $inventory_item_stock->entry_id)->first()->delete();
					}
				}
			}


		if($inventory_item->id && $request->input('purchase'))
		{ 
			//dd($inventory_item->id);
			$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();

			$batch_date = str_replace('-', '', $on_date);

			//$inventory_item_batch = InventoryItemBatch::find($item->batch_id);
			
			$inventory_item_batch = InventoryItemBatch::where('inventory_item_batches.item_id',$inventory_item->id)->first();

			//dd($inventory_item_batch);

			//$inventory_item_batch->item_id = $inventory_item->id;
			//$inventory_item_batch->global_item_model_id = $inventory_item->global_item_model_id;
			//$inventory_item_batch->batch_number = $batch_date.'/'.$inventory_item->id.'/'.$voucher->voucher_no;

			$inventory_item_batch->purchase_price = $inventory_item->purchase_price;
			$inventory_item_batch->purchase_plus_tax_price = $purchase_tax_price;
			
			$inventory_item_batch->selling_price = $inventory_item->selling_price;
			$inventory_item_batch->selling_plus_tax_price = $inventory_item->base_price;

			$inventory_item_batch->purchase_tax_id = $purchase_tax_id;
			$inventory_item_batch->sales_tax_id = $tax_id;

			$inventory_item_batch->quantity = $request->input('initial_quantity');
			$inventory_item_batch->unit_id = $inventory_item->unit_id;
			
			$inventory_item_batch->organization_id = $organization_id;
			$inventory_item_batch->save();

			Custom::userby($inventory_item_batch, true);

		}		
		

		$unit = ($inventory_item->unit_id != null) ? Unit::findOrFail($inventory_item->unit_id)->display_name : "";
		$sale_price = Custom::get_least_closest_date(json_decode($inventory_item->sale_price_data, true));

		$response = array('status' => 1, 'message' => 'Item'.config('constants.flash.added'));

		$response['data']['id'] = $inventory_item->id;
		$response['data']['name'] = $inventory_item->name;
		$response['data']['sku'] = $inventory_item->sku;
		$response['data']['hsn'] = $inventory_item->hsn;
		$response['data']['purchase_price'] = $inventory_item->purchase_price;
		$response['data']['sale_price'] = $request->input('sale_price'). "<span style='color:#aaa'> From ".Carbon::parse($sale_price['date'])->format('jS \\of M Y')."</span>";
		$response['data']['description'] = $inventory_item->description;
		$response['data']['low_stock'] = $inventory_item->low_stock;
		$response['data']['include_tax'] = $inventory_item->include_tax;
		$response['data']['include_purchase_tax'] = $inventory_item->include_purchase_tax;
		$response['data']['income_account'] = $inventory_item->income_account;
		$response['data']['unit'] = $unit;
		$response['data']['expense_account'] = $inventory_item->expense_account;
		$response['data']['inventory_account'] = $inventory_item->inventory_account;
		$response['data']['category_name'] = $category_name->category_name;
		$response['data']['status'] = $inventory_item->status;
		$response['data']['selling_price'] = $inventory_item->selling_price;
        $response['data']['purchase_price'] = $inventory_item->purchase_price;
		if(isset($inventory_stock)) {
			$response['data']['date'] = $inventory_stock->date;
			$response['data']['in_stock'] = $inventory_stock->in_stock;
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

		$inventory_item_stock =  InventoryItemStock::find($inventory_item->id);

		if(!empty($inventory_item_stock->entry_id)) {
			AccountEntry::where('account_entries.id', $inventory_item_stock->entry_id)->first()->delete();
		}

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$file = public_path('organizations/'.$business_name.'/items').'/'.$inventory_item->id.'.jpg';

		if (file_exists($file)) {
			unlink($file);
		}

		$inventory_item->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Item'.config('constants.flash.deleted'), 'data' =>[]]);
	}

	public function status(Request $request)
	{
		InventoryItem::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$inventory_items = explode(',', $request->id);

		$item_list = [];

		foreach ($inventory_items as $item_id) {
			$item = InventoryItem::findOrFail($item_id);
			$item->delete();

			$inventory_adjustments = InventoryAdjustment::where('item_id', $item_id)->get();

			if(count($inventory_adjustments) > 0) {
				foreach($inventory_adjustments as $inventory_adjustment) {
					InventoryAdjustment::where('id', $inventory_adjustment->id)->first()->delete();
				}
			}

			$inventory_item_stock =  InventoryItemStock::find($item_id);

			if($inventory_item_stock->entry_id != null) {
				AccountEntry::where('account_entries.id', $inventory_item_stock->entry_id)->first()->delete();
			}

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

	public function get_category_type(Request $request)
	{
        //dd($request->all());
		$organization_id = Session::get('organization_id');

		$default_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
		->where('global_item_main_categories.category_type_id', $request->id)
		->where('global_item_main_categories.status', 1)
		->orderby('global_item_main_categories.display_name')
		->get();

     	if($request->id==1){

     		/* $business_id = Organization::select('id','business_id')->where('id',$organization_id)->first();
      
	        if($business_id)
	        {
	            $business_professionalism_id = Business::select('id','business_professionalism_id')->where('id',$business_id->business_id)->first();
	       
	        $business_professionalism_name = BusinessProfessionalism::select('id','name')->where('id',$business_professionalism_id->business_professionalism_id)->first()->name;
	      
	        }

	        if($business_professionalism_name == "Two Wheeler")
	        {
	            $bussiness_nature=Business::select('business_nature_id')->leftjoin('organizations','businesses.id','=','organizations.business_id')->where('organizations.id',$organization_id)->first();

	            $main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
			->leftjoin('business_natures','business_natures.id','=','global_item_main_categories.maincategory_type_id')
			->where('global_item_main_categories.category_type_id', $request->id)
			->where('global_item_main_categories.maincategory_type_id', $bussiness_nature->business_nature_id)
			
			->orderby('global_item_main_categories.display_name')
			->get();
	        //dd($bussiness_nature);
	       
	           
	        }
	        else
	        {
	             $bussiness_nature=Business::select('business_nature_id')->leftjoin('organizations','businesses.id','=','organizations.business_id')->where('organizations.id',$organization_id)->first();
	        //dd($bussiness_nature);
	       
	        	$main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
			->leftjoin('business_natures','business_natures.id','=','global_item_main_categories.maincategory_type_id')
			->where('global_item_main_categories.category_type_id', $request->id)
			->where('global_item_main_categories.maincategory_type_id', $bussiness_nature->business_nature_id)
			->orderby('global_item_main_categories.display_name')
			->get();
			//dd($main_category);
	           
	        }*/

	        $bussiness_nature=Business::select('business_nature_id')->leftjoin('organizations','businesses.id','=','organizations.business_id')->where('organizations.id',$organization_id)->first();
	        $custom_values = OrgCustomValue::select('data1 as data1')
                ->where('screen','item_Master_page')
                ->where('factor','Main_category_default_values')
                ->where('organization_id',$organization_id)
                ->first();
                //$tr = trim($custom_values->data1,',');
                //dd($custom_values);
                if($custom_values != null)
                {
                	$data1 = explode(",", $custom_values->data1);
               		//var_dump([$custom_values->data1]);
             		//dd($data1);
                	$main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
					->leftjoin('business_natures','business_natures.id','=','global_item_main_categories.maincategory_type_id')
					->where('global_item_main_categories.category_type_id', $request->id)
					->whereIn('global_item_main_categories.id',$data1)
					->orderby('global_item_main_categories.display_name')
					->get();

				}
				else
				{
					   $main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
					->leftjoin('business_natures','business_natures.id','=','global_item_main_categories.maincategory_type_id')
					->where('global_item_main_categories.category_type_id', $request->id)
					->where('global_item_main_categories.maincategory_type_id', $bussiness_nature->business_nature_id)
					->orderby('global_item_main_categories.display_name')
					->get();
					//dd($main_category);
				}
              
           
               


	       /* $main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
			->leftjoin('business_natures','business_natures.id','=','global_item_main_categories.maincategory_type_id')
			->where('global_item_main_categories.category_type_id', $request->id)
			->where('global_item_main_categories.maincategory_type_id', $bussiness_nature->business_nature_id)
			->orderby('global_item_main_categories.display_name')
			->get();*/
	     		
	       
		}
		else{
			$main_category = GlobalItemMainCategory::select('global_item_main_categories.id', 'global_item_main_categories.display_name AS name')
			->where('global_item_main_categories.category_type_id', $request->id)
			->orderby('global_item_main_categories.display_name')
			->get();

		}
		$category = []; 

		/*GlobalItemCategory::select('global_item_categories.id', 'global_item_categories.display_name AS name')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->where('global_item_main_categories.category_type_id', $request->id)
		->where('global_item_categories.status', 1)
		->orderby('global_item_categories.display_name')
		->get();*/

		$type = [];
		/*GlobalItemType::select('global_item_types.id', 'global_item_types.display_name AS name')
		->leftjoin('global_item_models', 'global_item_models.type_id', '=', 'global_item_types.id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_types.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->where('global_item_main_categories.category_type_id', $request->id)
		->where('global_item_types.status', 1)
		->whereNotNull('global_item_models.name')
		->orderby('global_item_types.display_name')
		->get();*/

		$make = [];/*GlobalItemModel::select('global_item_makes.id', 'global_item_makes.display_name AS name')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
		->where('global_item_main_categories.category_type_id', $request->id)
		->where('global_item_makes.status', 1)
		->groupby('global_item_makes.id')
		->orderby('global_item_makes.display_name')
		->get();*/

		$models = [];
		/*GlobalItemModel::select('global_item_models.id', 'global_item_models.display_name AS name')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->where('global_item_main_categories.category_type_id', $request->id)
		->where('global_item_models.status', 1)
		->orderby('global_item_models.display_name')
		->get();*/
	

		return response()->json(['data' => ['default_category' => $default_category,'main_category' => $main_category, 'category' => $category, 'type' => $type, 'make' => $make, 'model' => $models]]);

	}

	public function get_main_categories(Request $request)
	{
		//dd($request->id);
		$organization_id = Session::get('organization_id');
        // dd('test');    
		$category = GlobalItemCategory::select('global_item_categories.id', 'global_item_categories.display_name AS name')
		->where('global_item_categories.main_category_id', $request->id)
		->where('global_item_categories.status', 1)
		->orderby('global_item_categories.display_name')
		->get();
		//dd($category);
		//$type = [];
		$type = GlobalItemType::select('global_item_types.id', 'global_item_types.display_name AS name')
		->where('global_item_types.status', 1)
		->orderby('global_item_types.display_name')
		->get();
		/*GlobalItemType::select('global_item_types.id', 'global_item_types.display_name AS name')
		->leftjoin('global_item_models', 'global_item_models.type_id', '=', 'global_item_types.id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_types.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->where('global_item_main_categories.id', $request->id)
		->where('global_item_types.status', 1)
		->whereNotNull('global_item_models.name')
		->orderby('global_item_types.display_name')
		->get();*/

		$make = GlobalItemModel::select('global_item_makes.id', 'global_item_makes.display_name AS name', 'global_item_models.hsn')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
		->where('global_item_main_categories.id', $request->id)
		->where('global_item_makes.status', 1)
		->whereNotNull('global_item_makes.id')
		->groupby('global_item_makes.id')
		->orderby('global_item_makes.display_name')
		->get();
		//dd($make);

		$models = [];/*GlobalItemModel::select('global_item_models.id', 'global_item_models.display_name AS name', 'global_item_models.hsn')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
		->where('global_item_main_categories.id', $request->id)
		->where('global_item_models.status', 1)
		->orderby('global_item_models.display_name')
		->get();*/
	

		return response()->json(['data' => ['category' => $category, 'type' => $type, 'make' => $make, 'model' => $models]]);

	}

	public function get_categories(Request $request)
	{
		//dd($request->id);
		$organization_id = Session::get('organization_id');
           // dd($request->all());
		$type = GlobalItemType::select('global_item_types.id', 'global_item_types.display_name AS name')
		->where('global_item_types.status', 1)
		->orderby('global_item_types.display_name')
		->get();

		$make = GlobalItemModel::select('global_item_makes.id', 'global_item_makes.display_name AS name')
		->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
		->where('global_item_models.category_id', $request->id)
		->where('global_item_makes.status', 1)
		->whereNotNull('global_item_makes.id')
		->groupby('global_item_makes.id')
		->orderby('global_item_makes.display_name')
		->get();

		$model = GlobalItemModel::select('global_item_models.id', 'global_item_models.display_name AS name', 'global_item_models.hsn');
		if($request->identifier_a != null || $request->identifier_a != "") {
			$model->where('global_item_models.identifier_a', 'LIKE', "%$request->identifier_a%");
		}
		$model->where('global_item_models.category_id', $request->id);
		$model->where('global_item_models.status', 1);
		$model->orderby('global_item_models.display_name');
		$models = $model->get();

		return response()->json(['data' => ['type' => $type, 'make' => $make, 'model' => $models]]);
         
	}

	public function get_types(Request $request)
	{
		//dd($request->all());
		$organization_id = Session::get('organization_id');

		$make = GlobalItemModel::select('global_item_makes.id', 'global_item_makes.display_name AS name')
		->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
		->where('global_item_models.type_id', $request->id)
		->where('global_item_makes.status', 1)
		->whereNotNull('global_item_makes.id')
		->groupby('global_item_makes.id')
		->orderby('global_item_makes.display_name')
		->get();

		$model = GlobalItemModel::select('global_item_models.id', 'global_item_models.display_name AS name', 'global_item_models.hsn');
		if($request->identifier_a != null || $request->identifier_a != "") {
			$model->where('global_item_models.identifier_a', 'LIKE', "%$request->identifier_a%");
		}
		$model->where('global_item_models.type_id', $request->id);
		$model->where('global_item_models.status', 1);
		$model->orderby('global_item_models.display_name');
		$models = $model->get();
	
		return response()->json(['data' => ['make' => $make, 'model' => $models]]);

	}

	public function get_make(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$model = GlobalItemModel::select('id', 'name', 'hsn');
		if($request->identifier_a != null) {
			$model->where('identifier_a', 'LIKE', "%$request->identifier_a%");
		}
		$model->where('make_id', $request->id);
		$model->where('status', 1);
		$model->orderby('display_name');
		$models = $model->get();
	

		return response()->json(['data' => ['model' => $models]]);

	}

	public function get_identifier(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$model = GlobalItemModel::select('id', 'name', 'hsn');

		if($request->global_category != null) {
			$model->where('category_id', $request->global_category);
		}
		if($request->global_type != null) {
			$model->where('type_id', $request->global_type);
		}
		if($request->global_make != null) {
			$model->where('make_id', $request->global_make);
		}
		if($request->identifier_a != null || $request->identifier_a != "") {
			$model->where('identifier_a', 'LIKE', "%$request->identifier_a%");
		}
		$model->where('status', 1);
		$model->orderby('display_name');
		$models = $model->get();	

		return response()->json(['data' => ['model' => $models]]);

	}

	public function add_global_item(Request $request)
	{

		$this->validate($request,[
			'global_model' => 'required',
			'global_main_category' => 'required',
			'global_category' => 'required',	
		]);

		$organization_id = Session::get('organization_id');
		$global_type =  null;
		$global_make = null;

		//dd($request->all());

		if(is_numeric($request->global_make) != null) {
			
			$global_make = $request->global_make;

		} else if($request->global_make != null) {
			$make = new GlobalItemMake();
			$make->name = $request->global_make;
  			$make->display_name = $request->global_make;
  			$make->status = 1;
  			$make->save();

			$global_make = $make->id;
		}


        $item = new GlobalItemModel();
        $item->name = $request->global_model;
  		$item->display_name = $request->global_model;

  		if($request->global_category != null) {
			$item->category_id = $request->global_category;
		}
		if($request->global_type != null) {
			$item->type_id = $request->global_type;
		}  		
  		if($request->global_make != null) {
  			$item->make_id = $global_make;
  		}
  		$item->identifier_a = ($request->identifier_a != null) ? $request->identifier_a : null;
  		$item->status = 1;
  		$item->save();
  		//dd($item);
  		$model = GlobalItemModel::select('global_item_models.id', 'global_item_main_categories.display_name AS main_category_name','global_item_categories.display_name as category_name','global_item_types.display_name AS type_name','global_item_makes.display_name AS make_name')
  		->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
  		->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')
  		->leftjoin('global_item_types','global_item_types.id','=','global_item_models.type_id')
  		->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')
  		->where('global_item_models.id',$item->id)->get();

  	      
		/*return response()->json(['data' => ['id' => $item->id, 'name' => $item->name, 'main_category_name' => $model->main_category_name, 'category_name' => $model->category_name, 'type_name'=> $model->type_name, 'make_name'=> $model->make_name]]);*/

		return response()->json(['data' => ['item' => $item, 'model' => $model]]);

	}

	public function item_image_upload(Request $request) {

		$file = $request->file('file');
		$id = $request->input('id');

		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$path_array = explode('/', 'organizations/'.$business_name.'/items');

		$public_path = '';

		foreach ($path_array as $p) {
			$public_path .= $p."/";
			if (!file_exists(public_path($public_path))) {
				mkdir(public_path($public_path), 0777, true);
			}
		}

		$name = $id.".jpg";

		$request->file('file')->move(public_path($public_path), $name);

		return response()->json(['status'=>1, 'message'=>'Item'.config('constants.flash.updated'),'data'=>['id' => $id, 'path' => URL::to('/').'/public/organizations/'.$business_name.'/items/'.$name]]);
	}
	public function hsn_name_search(Request $request)
    {
        //dd($request->all());
        $keyword = $request->input('term');
        $organization_id = Session::get('organization_id');

      

          $query =Gst::select('gsts.id','gsts.code');
          $query->where('gsts.code','LIKE','%' . $keyword .'%') ;
        
        $hsn_search = $query->take(10)->get();
        //dd($hsn_search);

        $hsns = [];

        foreach($hsn_search as $value)
        {
           
            //dd($price);
           
            //dd($list_price);
            $hsns[]=['id' => $value->id ,'label' => $value->code,'name' => $value->code ];
            //dd($inventory_item_array);

        }

        return response()->json($hsns);
    }

    public function gst_hsn_taxes(Request $request)
    {
    	//dd($request->all());
    	$tax_per = Gst::where('id',$request->id)->first();
    	return response()->json($tax_per);
    }

     public function batch_item($id,$where)
	{		$organization_id = Session::get('organization_id');

		$item_name=InventoryItem::where('id',$id)->first()->name;
		$item_batche = InventoryItem::select('inventory_item_batches.batch_number','inventory_item_batches.quantity','inventory_item_batches.purchase_plus_tax_price','inventory_item_batches.selling_plus_tax_price',

		DB::raw('CASE WHEN inventory_item_batches.transaction_id != null THEN transactions.name ELSE "Entered directly" END AS purchase_company_name
			'),
		DB::raw('CASE WHEN inventory_item_batches.transaction_id != null THEN transactions.date ELSE DATE_FORMAT(inventory_item_batches.created_at,"%d-%m-%Y") END AS date
			')
		)
		->leftjoin('inventory_item_batches','inventory_item_batches.item_id','inventory_items.id' )
		->leftjoin('transactions','transactions.id','inventory_item_batches.transaction_id')
		->leftjoin('account_vouchers','account_vouchers.id','transactions.transaction_type_id')
		->where('inventory_items.id',$id);
		if($where == "low_stock"){
		$item_batche->whereNotNull('inventory_item_batches.transaction_id')
					->where('account_vouchers.name','=','purchases');
		}
		$item_batche->where('inventory_item_batches.organization_id',$organization_id)
					->orderBy('inventory_item_batches.quantity', 'DESC')
					->take(10);
		$item_batches = $item_batche->get();
			
		$total_quantity = 0;
		$total_pu_value = 0;
		$total_sale_value = 0;
		foreach($item_batches as $key=>$value)
		{	$total_quantity+=$value->quantity;
			$total_pu_value+=$value->quantity*$value->purchase_plus_tax_price;
			$total_sale_value+=$value->quantity*$value->selling_plus_tax_price;
			   
		}

				
		return view('inventory.item_batch_list',compact('item_batches','item_name','total_quantity','total_pu_value','total_sale_value','where'));


	}
	public function age_of_goods(){
		$organization_id = Session::get('organization_id');
		$today_date = today()->format('Y-m-d');
		$fifteen_days=today()->subDay(15)->format('Y-m-d');
		$thirty_days=today()->subDay(30)->format('Y-m-d');
		$sixty_days=today()->subDay(60)->format('Y-m-d');
		$ninty_days=today()->subDay(90)->format('Y-m-d');
		

	$age_of_goods = InventoryItem::select('inventory_items.id','inventory_items.name',DB::raw('SUM(inventory_item_batches.quantity) as total_quantity'),
		DB::raw('SUM(CASE WHEN inventory_item_batches.created_at >= "'.$fifteen_days.' 00:00:00" && inventory_item_batches.created_at <= "'.$today_date.' 23:59:59"
			 THEN inventory_item_batches.quantity ELSE 0 END) AS fiftten_days'),
		DB::raw('SUM(CASE WHEN inventory_item_batches.created_at >= "'.$thirty_days.' 00:00:00" && inventory_item_batches.created_at <= "'.$fifteen_days.' 23:59:59"
			 THEN inventory_item_batches.quantity ELSE 0 END) AS thirty_days'),
		DB::raw('SUM(CASE WHEN inventory_item_batches.created_at >= "'.$sixty_days.' 00:00:00" && inventory_item_batches.created_at <= "'.$thirty_days.' 23:59:59"
			 THEN inventory_item_batches.quantity ELSE 0 END) AS sixty_days'),
		DB::raw('SUM(CASE WHEN inventory_item_batches.created_at >= "'.$ninty_days.' 00:00:00" && inventory_item_batches.created_at <= "'.$sixty_days.' 23:59:59"
			 THEN inventory_item_batches.quantity ELSE 0 END) AS ninty_days'),
		DB::raw('SUM(CASE WHEN inventory_item_batches.created_at <= "'.$ninty_days.' 00:00:00" 
			 THEN inventory_item_batches.quantity ELSE 0 END) AS ninty_days_plus'))
			->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		   ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
           ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
           ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')
           ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
         	->leftjoin('inventory_item_batches','inventory_item_batches.item_id','inventory_items.id' )
		   ->where('inventory_items.organization_id',$organization_id)
		   ->where('global_item_category_types.name','=','goods')
		   ->havingRaw('0 < sum(inventory_item_batches.quantity)')
		   ->groupby('inventory_items.id')
		   ->orderBy('inventory_items.name')
		   ->get();
		 
		return view('inventory.age_of_goods',compact('age_of_goods'));
	}
	public function today_stock_report(){

 		$organization_id = Session::get('organization_id');
		$today_date = date('Y-m-d H:i:s');
		$today = Carbon::today()->format('d-m-Y');
		$today_stock_reports = InventoryItem::select('inventory_items.name as item_name','global_item_makes.name as make_name','inventory_item_stocks.in_stock',
		DB::raw('SUM(CASE WHEN inventory_item_stock_ledgers.voucher_type = "Stock Journal" OR inventory_item_stock_ledgers.voucher_type = "purchase" THEN inventory_item_stock_ledgers.quantity ELSE 0 END) AS inward_quantity'),
		DB::raw('SUM(CASE WHEN inventory_item_stock_ledgers.voucher_type = "Stock Journal" OR inventory_item_stock_ledgers.voucher_type = "purchase" THEN inventory_item_stock_ledgers.purchase_price ELSE 0 END) AS inward_amount'),
		DB::raw('SUM(CASE WHEN inventory_item_stock_ledgers.voucher_type = "Job Invoice Cash" OR inventory_item_stock_ledgers.voucher_type = "Job Invoice Credit" OR inventory_item_stock_ledgers.voucher_type = "Invoice" OR inventory_item_stock_ledgers.voucher_type = "Invoice Cash" THEN inventory_item_stock_ledgers.sale_price ELSE 0 END) AS outward_amount'),
		DB::raw('SUM(CASE WHEN inventory_item_stock_ledgers.voucher_type = "Job Invoice Cash" OR inventory_item_stock_ledgers.voucher_type = "Job Invoice Credit" OR inventory_item_stock_ledgers.voucher_type = "Invoice" OR inventory_item_stock_ledgers.voucher_type = "Invoice Cash" THEN inventory_item_stock_ledgers.quantity ELSE 0 END) AS outward_quantity'))
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
		->leftjoin('inventory_item_stock_ledgers', 'inventory_item_stock_ledgers.inventory_item_stock_id', '=', 'inventory_item_stocks.id' )
		->where('inventory_items.organization_id',$organization_id)
		->where('inventory_item_stock_ledgers.updated_at', '>=', date('Y-m-d').' 00:00:00')
		->groupby('inventory_items.id')
		->orderBy('inventory_items.name')
		->get();
		
		
		return view('inventory.today_stock_report',compact('today_stock_reports','today'));
		
 	}
    
 	public function findAllForProductChooser( ){

 	    Log::info('ItemController->findAllForProductChooser:-Inside ');
 	    $response = $this->serv->findAllForProductChooser();
 	    Log::info('ItemController->findAllForProductChooser:- Return ');
 	    return response()->json($response);
 	}
 
}
