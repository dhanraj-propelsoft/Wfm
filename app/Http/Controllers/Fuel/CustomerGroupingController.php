<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FsmTank;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use App\Unit;
use App\FsmPump;
use App\InventoryItem;
use App\FsmShiftPumpCashDetail;
use App\FsmStackBookDetail;
use App\AccountLedger;
use App\FsmDipReading;
use App\CustomerGroping;
use App\InventoryAdjustment;  
use App\Transaction;
use DB;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerGroupingController extends Controller
{
  public function index()
  {

       $organization_id = Session::get('organization_id');
       $customer_groupings = CustomerGroping::where('organization_id',$organization_id)->get();
      
        return view('fuel_station.customer_grouping_index',compact('customer_groupings'));

  }
  public function adjusment_index()
  {
    $organization_id = Session::get('organization_id');

    $inventory_adjustment = InventoryAdjustment::select('inventory_adjustments.*', 'inventory_items.name as item_name')
    ->leftjoin('inventory_items', 'inventory_items.id', '=', 'inventory_adjustments.item_id')->where('inventory_adjustments.organization_id', $organization_id)->get();
   
      return view('fuel_station.adjustment_index',compact('inventory_adjustment'));

  }
  public function stackbook_index()
  {
    $organization_id = Session::get('organization_id');  
   

    $stockbook= FsmStackBookDetail::select('fsm_stack_book_details.id','fsm_stack_book_details.date','fsm_tanks.name as tankname','inventory_items.name as productname','fsm_stack_book_details.opening','fsm_stack_book_details.purchase','fsm_stack_book_details.sales','fsm_stack_book_details.total_stock','fsm_stack_book_details.testing','fsm_stack_book_details.closing','fsm_stack_book_details.unit_rate','fsm_stack_book_details.sales_worth','fsm_stack_book_details.stock_worth')

    ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_stack_book_details.tank_id')
    ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
    ->where('fsm_stack_book_details.organization_id',$organization_id)
    ->get();
  

     return view('fuel_station.stackbook_index',compact('stockbook'));
  }
 
   public function stackbook_create()
   {

    $organization_id = Session::get('organization_id');

    $date = now()->format("Y-m-d ");  

    $tank=Fsmtank::where('fsm_tanks.organization_id', $organization_id)
                  ->pluck('name','id');
    $tank->prepend('select a tank','');

    $product=InventoryItem::where('organization_id',$organization_id)->pluck('name','id');
    $product->prepend('select a product','');
    
    return view('fuel_station.stackbook_create',compact('tank','product'));
    
  }

    public function get_product_list($id)
    {
  
      $date = Carbon::now()->format("Y-m-d");
  
      $organization_id = Session::get('organization_id');
       
      $opening=FsmDipReading::select('fsm_dip_readings.quantity as opening')

      ->where('fsm_dip_readings.tank_id',$id)
      ->where('fsm_dip_readings.date',$date)
        ->where('fsm_dip_readings.reading_type',1)
      ->where('fsm_dip_readings.organization_id',$organization_id)
      ->first();
      
       if( $opening!=null)
       {
          $opening=$opening->opening;
       }
       else
       {
         $opening=0;
       }
    

   
      $purchases=Transaction::select('transaction_items.quantity as purchases')

      ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
      ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')    
      ->leftjoin('fsm_tanks','fsm_tanks.product','=','inventory_items.id')

      ->where('fsm_tanks.id',$id)

      ->where('transactions.date',$date)
       ->where('transactions.organization_id',$organization_id)
      ->where('transactions.transaction_type_id',17)
      ->first();
    
      if($purchases!=null)
      {
       $purchases= $purchases->purchases;
      }
      else{
         $purchases=0;
      }
       
      $sales=FsmShiftPumpCashDetail::leftjoin('fsm_pumps','fsm_pumps.id','=','fsm_shift_pump_cash_details.pump_id')
      ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_pumps.tank_id')
      ->where('fsm_shift_pump_cash_details.date',$date)
      ->where('fsm_shift_pump_cash_details.organization_id',$organization_id)
      ->where('fsm_tanks.id',$id)
      ->sum('fsm_shift_pump_cash_details.pump_salesquantity');
     
    
      $total_stock=($opening+$purchases)-$sales;

      $testing=InventoryAdjustment::select('quantity')
      ->leftjoin('inventory_items','inventory_items.id','=','inventory_adjustments.item_id')
     
      ->leftjoin('fsm_tanks','fsm_tanks.product','=','inventory_items.id')
      ->where('inventory_adjustments.date',$date)
      ->where('inventory_adjustments.organization_id',$organization_id)
      ->where('fsm_tanks.id',$id)
      ->first();
      if($testing!=null)
      {
        $testing=$testing->quantity;
      }
      else{
         $testing=0;
      }
      
      $closing=$total_stock-$testing;

     


       $product = FsmTank::select('inventory_items.id as product_id','inventory_items.selling_price')
          ->leftjoin('inventory_items','inventory_items.id','=','fsm_tanks.product')
        ->where('fsm_tanks.id',$id)
        ->where('fsm_tanks.organization_id',$organization_id)
        ->first();

         $products= $product->product_id;
         $rate= $product->selling_price;
         $sales_worth = $rate*$sales;
         $stock_worth = $rate*$total_stock;

        
      return response()->json(['product'=> $products,'opening'=>$opening,'purchases'=>$purchases,'sales'=>$sales,'total_stock'=>$total_stock,'testing'=>$testing,'closing'=>$closing,'unit_rate'=>$rate,'sales_worth'=>$sales_worth,'stock_worth'=>$stock_worth]);
    }
    public function stackbook_store(Request $request)
    {
    
      $organization_id = Session::get('organization_id');

      $date = now()->format("Y-m-d "); 

      $stockbook= new \App\FsmStackBookDetail;
      $stockbook->date=$date;
      $stockbook->tank_id=$request->tank_id;
      $stockbook->opening=$request->opening;
      $stockbook->purchase=$request->purchase;
      $stockbook->sales=$request->sales;
      $stockbook->total_stock=$request->total_stock;
      $stockbook->testing=$request->testing;
      $stockbook->closing=$request->closing;
      $stockbook->unit_rate=$request->unit_rate;
      $stockbook->sales_worth=$request->sales_worth;
      $stockbook->stock_worth=$request->stock_worth;
      $stockbook->status=1;
      $stockbook->organization_id=$organization_id ;
      $stockbook->created_by=Auth::user()->id;
      $stockbook->last_modified_by=Auth::user()->id;
      $stockbook->save();

      $tank =($stockbook->tank_id != null) ? FsmTank::findorFail($stockbook->tank_id )->name : "";
      $product = ($request->input('product') != null) ? InventoryItem::findorFail($request->input('product'))->name : "";

     



       return response()->json([ 'message' => 'Stock Book'.config('constants.flash.added'), 'data' =>['id'=>$stockbook->id,'date'=> $stockbook->date,'tankname'=>$tank,'product'=>$product,'opening'=> $stockbook->opening,'purchase'=> $stockbook->purchase,'sales'=> $stockbook->sales,'total_stock'=> $stockbook->total_stock,'testing'=> $stockbook->testing,'closing'=> $stockbook->closing,'unit_rate'=>$stockbook->unit_rate,'sales_worth'=>$stockbook->sales_worth,'stock_worth'=>$stockbook->stock_worth]]);



    }
     public function stockbook_multidestroy(Request $request)
     {
        $reading = explode(',', $request->id);

         foreach ($reading as $reading_id)
          {
             $readingdetails = FsmStackBookDetail::findOrFail($reading_id);
             
             FsmStackBookDetail::where('id', $readingdetails->id)->delete();
          }

        return response()->json(['status'=>1, 'message'=>'StackBookDetail'.config('constants.flash.deleted'),'data'=>['list' => $reading]]);

     }
     public function stockbook_edit($id)
     {
       $organization_id = Session::get('organization_id');
     
      $stockbook=FsmStackBookDetail::select('fsm_stack_book_details.id as bookid','fsm_tanks.name','fsm_stack_book_details.opening','fsm_stack_book_details.purchase','fsm_stack_book_details.sales','fsm_stack_book_details.total_stock','fsm_stack_book_details.testing','fsm_stack_book_details.closing','fsm_stack_book_details.unit_rate','fsm_stack_book_details.sales_worth','fsm_stack_book_details.stock_worth','fsm_stack_book_details.date')
       ->leftjoin('fsm_tanks','fsm_tanks.id','=','fsm_stack_book_details.tank_id')
       
       ->where('fsm_stack_book_details.id',$id)
       ->first();


       $tank=Fsmtank::where('fsm_tanks.organization_id', $organization_id)
                  ->pluck('name','id');

           return view('fuel_station.stackbook_edit',compact('stockbook','tank'));

    }
    public function stockbook_update( Request $request){
     // dd($request->all());

            $organization_id = Session::get('organization_id');
      
    $product = InventoryItem::select('inventory_items.name')
    ->leftjoin('fsm_tanks','fsm_tanks.product','=','inventory_items.id')
    ->where('fsm_tanks.id',$request->input('tank_id'))
    ->where('inventory_items.organization_id',$organization_id)
    
    ->first();
    $products=$product->name;

    


       $stockbook = FsmStackBookDetail::findOrFail($request->input('bookid'));
       $stockbook->date=$request->input('set_on');
       $stockbook->tank_id=$request->input('tank_id');
       $stockbook->opening=$request->input('opening');
       $stockbook->purchase=$request->input('purchase');
       $stockbook->sales=$request->input('sales');
       $stockbook->total_stock=$request->input('total_stock');
       $stockbook->testing=$request->input('testing');
       $stockbook->closing=$request->input('closing');
       $stockbook->unit_rate=$request->input('unit_rate');
       $stockbook->sales_worth=$request->input('sales_worth');
       $stockbook->stock_worth=$request->input('stock_worth');
       $stockbook->last_modified_by=Auth::user()->id;
       $stockbook->save();

        $tank =($stockbook->tank_id != null) ? FsmTank::findorFail($stockbook->tank_id )->name : "";

       $tank =($stockbook->tank_id != null) ? FsmTank::findorFail($stockbook->tank_id )->name : "";
      $product = ($request->input('product') != null) ? InventoryItem::findorFail($request->input('product'))->name : "";

     



       return response()->json([ 'message' => 'Stock Book'.config('constants.flash.added'), 'data' =>['id'=>$stockbook->id,'date'=> $stockbook->date,'tankname'=>$tank,'product'=>$product,'opening'=> $stockbook->opening,'purchase'=> $stockbook->purchase,'sales'=> $stockbook->sales,'total_stock'=> $stockbook->total_stock,'testing'=> $stockbook->testing,'closing'=> $stockbook->closing,'unit_rate'=>$stockbook->unit_rate,'sales_worth'=>$stockbook->sales_worth,'stock_worth'=>$stockbook->stock_worth]]);

    }

 
}
