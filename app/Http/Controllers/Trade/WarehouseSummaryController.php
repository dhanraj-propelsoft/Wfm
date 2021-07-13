<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryItem;
use Carbon\Carbon;
use App\Custom;
use Session;
use DB;

class WarehouseSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');

        $warehouses = InventoryItem::select('inventory_items.name','global_item_categories.display_name AS category_name', 'inventory_item_stocks.in_stock as total_quantity', 'units.name as unit') 
        ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' ) 
        ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )
        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id' )   
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', '1')
        ->groupby('inventory_items.name')->get();

        //return $warehouses->all();

        return view('trade.warehouse_summary', compact('warehouses'));
    }

    public function date_summary(Request $request)
    {
        $on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
        $organization_id = Session::get('organization_id');

        $warehouse_list = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'inventory_item_stocks.in_stock as total_quantity', 'units.name as unit', 'inventory_item_stocks.data') 
        ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' ) 
        ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id' )   
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', '1')
        ->groupby('inventory_items.name')->get();

        $warehouses = [];

        foreach ($warehouse_list as $warehouse) {
            $data = json_decode($warehouse->data, true);
            foreach ($data as $datum) {
                 $warehouse_obj = new \stdClass();
                 $warehouse_obj->id = $warehouse->id;
                 $warehouse_obj->name = $warehouse->name;
                 $warehouse_obj->unit = $warehouse->unit;
                 $warehouse_obj->total_quantity = $datum['in_stock'];
                 $warehouse_obj->date = $datum['date'];

                 $warehouses[] = $warehouse_obj;
            }
          
        }

        //Custom::get_least_closest_date(json_decode($warehouses->data, true));
//dd($warehouses);
       //return $warehouses->all();
 //return view('trade.warehouse_summary', compact('warehouses'));
        return response()->json(['status' => 1, 'message' => 'Warehose Summary'.config('constants.flash.added'), 'data' => [$warehouses]]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
