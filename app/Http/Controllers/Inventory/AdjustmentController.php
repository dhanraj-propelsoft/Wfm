<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryAdjustment;
use App\InventoryItemStock;
use App\AccountVoucher;
use App\AccountEntry;
use App\InventoryItem;
use App\InventoryStore;
use App\InventoryRack;
use App\InventoryItemBatch;
use Carbon\Carbon;
use App\Custom;
use Session;
use App\FsmTank;
use DB;
use App\InventoryItemStockLedger;
use App\AccountLedger;

class AdjustmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$inventory_adjustments = InventoryAdjustment::select('inventory_adjustments.*', 'inventory_items.name as item_name')
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_adjustments.item_id')->where('inventory_adjustments.organization_id', $organization_id)->get();

		return view('inventory.adjustment',compact('inventory_adjustments'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$inventory_item = InventoryItem::select('inventory_items.*', 'inventory_item_stocks.id as items_name')
		->join('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')->where('organization_id', $organization_id)->pluck('inventory_items.name', 'id');

		$inventory_item->prepend('Select Item', '');

		$inventory_item_stocks = InventoryItemStock::select('inventory_item_stocks.*')->where('inventory_item_stocks.id', $request->input('id'))->groupBy('inventory_item_stocks.id')->first();

		$voucher_master = AccountVoucher::where('name', 'stock_journal')->where('organization_id', $organization_id)->first();

		 $previous_entry = InventoryAdjustment::where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		 $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;

		 $adjustment_no = Custom::generate_accounts_number($voucher_master->name, $gen_no, false);

		 $date_setting = $voucher_master->date_setting;
		 $module_name=Session::get('module_name');
		$fsm_tanks=FsmTank::where('organization_id', $organization_id)->pluck('name','id');
		$fsm_tanks->prepend('Select Tank', '');
		 
		return view('inventory.adjustment_create', compact('inventory_item', 'inventory_item_stocks', 'date_setting', 'adjustment_no','module_name','fsm_tanks'));

	}

	public function get_available_quantity(Request $request)
	{
		$inventory_item_stocks = InventoryItemStock::select('inventory_item_stocks.in_stock')
			->where('inventory_item_stocks.id', $request->input('id'))
			->groupBy('inventory_item_stocks.id')->first();
			
    	$batches = InventoryItemBatch::where('item_id',$request->input('id'))->get();
    	
	    return response()->json(array('result' =>$inventory_item_stocks,'batches'=>$batches));
	
	}
	//fuelstation
	 public function get_product_details($id){

    
       //dd($id);
      	$item = DB::table("fsm_tanks")
      
        ->where('fsm_tanks.id',$id)
        ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
        ->pluck('inventory_items.name','inventory_items.id');


    	$stock = DB::table("fsm_tanks")
      
        ->where('fsm_tanks.id',$id)
        ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
        ->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
        ->select('inventory_item_stocks.in_stock','inventory_item_stocks.id')
        ->first();
        $stock=$stock->in_stock;
        //dd($stock);
         return response()->json(['item'=>$item,'stock'=>$stock]);
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
		$date = explode('-',$request->input('date'));

		$voucher_master = AccountVoucher::where('name', 'adjustment')->where('organization_id', $organization_id)->first();


		$previous_entry = InventoryAdjustment::where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;

		$inventory_adjustment = new InventoryAdjustment;
		$inventory_adjustment->adjustment_no = Custom::generate_accounts_number($voucher_master->name, $gen_no, false);
		$inventory_adjustment->gen_no = $gen_no;
		$inventory_adjustment->date =  $date[2]."-".$date[1]."-".$date[0];
		$inventory_adjustment->item_id = $request->input('item_id');
		$inventory_adjustment->organization_id = $organization_id;
		$inventory_adjustment->description = $request->input('description');
		$inventory_adjustment->quantity = $request->input('quantity');
		$inventory_adjustment->save();

		Custom::userby($inventory_adjustment, true);

		if($inventory_adjustment->item_id) {

			$inventory_item_stock = InventoryItemStock::findOrFail($inventory_adjustment->item_id);	

			$inventory_item = InventoryItem::findOrFail($inventory_adjustment->item_id);


			$stock = ($inventory_item_stock->in_stock)+($inventory_adjustment->quantity);
			$inventory_item_stock->in_stock = $stock;
			$inventory_item_stock->date = date('Y-m-d H:i:s');

			$data = json_decode($inventory_item_stock->data, true);

			$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();

			/*$data[] = ["transaction_id" => null,"entry_id" => $inventory_item_stock->entry_id,"voucher_type" => 'stock_journal',"order_no" => $voucher->voucher_no,"quantity" => $inventory_adjustment->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $stock,'status' => 1];*/

			$data[] = ["transaction_id" => null,"entry_id" => $inventory_item_stock->entry_id,"voucher_type" => $voucher_master->display_name,"order_no" => $inventory_adjustment->adjustment_no,"quantity" => $inventory_adjustment->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $stock ,'purchase_price' => $inventory_item->purchase_price,'sale_price' => $inventory_item->base_price,'status' => 1];

			$inventory_item_stock->data = json_encode($data);
			$inventory_item_stock->save();



			/* Account Entry and Account transaction stored */

			$credit_ledger = AccountLedger::where('name', 'opening_equity')->where('organization_id', $organization_id)->first();

			$entry[] = ['debit_ledger_id' => $inventory_item->inventory_account, 'credit_ledger_id' => $credit_ledger->id, 'amount' => ($inventory_item->purchase_price * $inventory_item_stock->in_stock) ];

			Custom::add_entry($inventory_adjustment->date, $entry, $inventory_item_stock->entry_id,$voucher_master->display_name, $organization_id, 1, false,null,null,null,null,null,null);

			/*End*/

			/*Item Stock ledger item quantity Update*/

			/*$item_stock_ledger = InventoryItemStockLedger::where('inventory_item_stock_ledgers.inventory_item_stock_id',$inventory_adjustment->item_id)->where('inventory_item_stock_ledgers.voucher_type','Adjustment')->first();			

			$item_stock_ledger->quantity = $inventory_item_stock->in_stock;
			$item_stock_ledger->in_stock = $inventory_item_stock->in_stock;
			$item_stock_ledger->save();*/

			/*End*/

			

			/*Item batch item quantity Update*/
				// $batch_item_stock = InventoryItemBatch::where('inventory_item_batches.item_id',$inventory_adjustment->item_id)->first();

				// $batch_item_stock->quantity = $inventory_item_stock->in_stock;
				// $batch_item_stock->save();
			   $batch_quantity = InventoryItemBatch::findOrFail($request->input('batch_id'));
	    	   $batch_quantity->quantity = $request->input('batch_quantity');
	    	   $batch_quantity->save(); 
			/*End*/


			/*Inventory item stock ledger*/
			$model = new InventoryItemStockLedger();
            $model->inventory_item_stock_id = $inventory_item_stock->id;
            $model->inventory_item_batch_id = $request->input('batch_id');
            $model->transaction_id =  null;
            $model->account_entry_id =  null; 
            $model->voucher_type = $voucher_master->display_name; 
            $model->order_no = $inventory_adjustment->adjustment_no; 

            $model->quantity = (isset($inventory_adjustment->quantity)) ? $inventory_adjustment->quantity : 0.00;

            $model->date = $inventory_item_stock->date; 

            $model->in_stock = (isset($inventory_item_stock->in_stock)) ? $inventory_item_stock->in_stock : 0.00;

            $model->purchase_price = (isset($inventory_item->purchase_price)) ? $inventory_item->purchase_price : 0.00;

            $model->sale_price = (isset($inventory_item->base_price)) ? $inventory_item->base_price : 0.00;

            $model->status = 1;

            $model->created_at = (Carbon::now());

            $model->save();	

            /*End*/

			Custom::userby($inventory_item_stock, true);
		  }

		  $unit = InventoryItem::select('units.display_name')
		  ->leftjoin('units', 'units.id', '=', 'inventory_items.unit_id')
		  ->where('inventory_items.id', $inventory_adjustment->item_id)->first()->display_name;
		  
		   

		 return response()->json(array('status' => 1, 'message' => 'Inventory Adjustment'.config('constants.flash.added'), 'data' => ['id' => $inventory_adjustment->id, 'adjustment_no' => $inventory_adjustment->adjustment_no, 'date' => Carbon::parse($request->input('date'))->format('d-m-Y'), 'item_id' => $inventory_adjustment->item_id, 'description' => $inventory_adjustment->description, 'quantity' => $inventory_adjustment->quantity, 'in_stock' => $inventory_item_stock->in_stock, 'unit' => $unit]));
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

		$inventory_adjustments = InventoryAdjustment::where('id', $id)->where('organization_id',$organization_id)->first();

		$inventory_item = InventoryItem::pluck('name', 'id');

		$inventory_item->prepend('Select Item', '');

		$inventory_item_stocks = InventoryItemStock::findOrFail($inventory_adjustments->item_id)->in_stock;

		return view('inventory.adjustment_edit', compact('inventory_item', 'inventory_item_stocks', 'inventory_adjustments'));
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
		//dd('update');
		$date = explode('-',$request->input('date'));

		$voucher_master = AccountVoucher::where('name', 'adjustment')->where('organization_id', $organization_id)->first();

		$inventory_adjustment = InventoryAdjustment::findOrFail($request->input('id'));

		$item_stock = InventoryItemStock::findOrFail($inventory_adjustment->item_id);
		$item_stock->in_stock = ($item_stock->in_stock)+($inventory_adjustment->quantity);
		$item_stock->save();
		
		$inventory_adjustment->date =  $date[2]."-".$date[1]."-".$date[0];
		$inventory_adjustment->item_id = $request->input('item_id');
		$inventory_adjustment->organization_id = Session::get('organization_id');
		$inventory_adjustment->description = $request->input('description');
		$inventory_adjustment->quantity = $request->input('quantity');
		$inventory_adjustment->save();

		Custom::userby($inventory_adjustment, false);

		$inventory_item_stock = InventoryItemStock::findOrFail($inventory_adjustment->item_id);

		$inventory_item = InventoryItem::findOrFail($inventory_adjustment->item_id);

		$stock = ($inventory_item_stock->in_stock)+($inventory_adjustment->quantity);

		$inventory_item_stock->in_stock = $stock;

		$inventory_item_stock->date = date('Y-m-d H:i:s');

		$data = json_decode($inventory_item_stock->data, true);

		$voucher = AccountEntry::where('id',$inventory_item_stock->entry_id)->first();

		/*$data[] = ["transaction_id" => null,"entry_id" => $inventory_item_stock->entry_id,"voucher_type" => 'stock_journal',"order_no" => $voucher->voucher_no,"quantity" => $inventory_adjustment->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $stock,'status' => 1];*/

		$data[] = ["transaction_id" => null,"entry_id" => $inventory_item_stock->entry_id,"voucher_type" => $voucher_master->display_name,"order_no" => $inventory_adjustment->adjustment_no,"quantity" => $inventory_adjustment->quantity,"date" => date('Y-m-d H:i:s'), "in_stock" => $stock ,'purchase_price' => $inventory_item->purchase_price,'sale_price' => $inventory_item->base_price,'status' => 1];

		$inventory_item_stock->data = json_encode($data);
		
		$inventory_item_stock->save();

		/*item stock ledger*/

		$item_stock_ledger = InventoryItemStockLedger::where('inventory_item_stock_ledgers.inventory_item_stock_id',$inventory_adjustment->item_id)->where('inventory_item_stock_ledgers.voucher_type','Adjustment')->first();

			$item_stock_ledger->quantity = $inventory_adjustment->quantity;
			$item_stock_ledger->in_stock = $inventory_item_stock->in_stock;
			$item_stock_ledger->save();

		/*End*/



		/*Item Batch*/

		$batch_item_stock = InventoryItemBatch::find($inventory_adjustment->item_id);
		$batch_item_stock->quantity = $inventory_item_stock->in_stock;
		$batch_item_stock->save();

		/*End*/

		Custom::userby($inventory_item_stock, false);




		return response()->json(array('status' => 1, 'message' => 'Inventory Adjustment'.config('constants.flash.added'), 'data' => ['id' => $inventory_adjustment->id, 'adjustment_no' => $inventory_adjustment->adjustment_no, 'date' => Carbon::parse($request->input('date'))->format('d-m-Y'), 'item_id' => $inventory_adjustment->item_id, 'description' => $inventory_adjustment->description, 'quantity' => $inventory_adjustment->quantity, 'in_stock' => $inventory_item_stock->in_stock]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$inventory_adjustment = InventoryAdjustment::findOrFail($request->input('id'));
 
		$inventory_item_stock = InventoryItemStock::findOrFail($inventory_adjustment->item_id);

		$inventory_item_stock->in_stock = ($inventory_item_stock->in_stock)-($inventory_adjustment->quantity);

		$inventory_item_stock->save();


		/*Item Stock ledger item quantity Update*/

			$item_stock_ledger = InventoryItemStockLedger::where('inventory_item_stock_ledgers.inventory_item_stock_id',$inventory_adjustment->item_id)->where('inventory_item_stock_ledgers.voucher_type','Adjustment')->first();

			$item_stock_ledger->quantity = $inventory_adjustment->quantity;
			$item_stock_ledger->in_stock = $inventory_item_stock->in_stock;
			$item_stock_ledger->save();

		/*End*/

		/*Item batch item quantity delete*/

		$batch_item_stock = InventoryItemBatch::where('inventory_item_batches.item_id',$inventory_adjustment->item_id)->first();
		$batch_item_stock->quantity = $inventory_item_stock->in_stock;
		$batch_item_stock->save();

		/*end*/

		$inventory_adjustment->delete();

		return response()->json(['status' => 1, 'message' => 'Inventory adjustment'.config('constants.flash.deleted'), 'data' =>[]]);
	}
}
