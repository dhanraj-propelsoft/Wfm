<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VehicleChecklist;
use App\Transaction;
use App\TransactionItem;
use App\Custom;
use Validator;
use Session;
class VehicleChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $organization_id = Session::get('organization_id');
        $checklists = VehicleChecklist::where('organization_id', $organization_id)->paginate(10);

        return view('trade_wms.vehicle_checklist', compact('checklists'));
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trade_wms.vehicle_checklist_create');
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
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',        
        ]);

        $organization_id = Session::get('organization_id');

        $checklist = new VehicleChecklist;
        $checklist->name = $request->input('name');
        $checklist->display_name = $request->input('display_name');
        $checklist->description = $request->input('description');
        $checklist->organization_id = $organization_id;
        $checklist->save();

        Custom::userby($checklist, true);
        Custom::add_addon('records');
       
        return response()->json(['status' => 1, 'message' => 'Checklist'.config('constants.flash.added'), 'data' => ['id' => $checklist->id, 'name' => $checklist->name, 'display_name' => $checklist->display_name, 'description' => ($checklist->description != null) ? $checklist->description : ""]]);
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
       //dd($request->all());
       $organization_id = Session::get('organization_id');

        $checklist = VehicleChecklist::where('id', $id)->where('organization_id', $organization_id)->first();
        if(!$checklist) abort(403);

        return view('trade_wms.vehicle_checklist_edit', compact('checklist'));
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
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $checklist = VehicleChecklist::findOrFail($request->input('id'));
        $checklist->name = $request->input('name');
        $checklist->display_name = $request->input('display_name');
        $checklist->description = $request->input('description');        
        $checklist->save();

        Custom::userby($checklist, false);
       
        return response()->json(['status' => 1, 'message' => 'Checklist'.config('constants.flash.updated'), 'data' => ['id' => $checklist->id, 'name' => $checklist->name, 'display_name' => $checklist->display_name, 'description' => ($checklist->description != null) ? $checklist->description : "", 'status' => $checklist->status]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       // dd($request->all());
        $checklist = VehicleChecklist::findOrFail($request->input('id'));
        $checklist->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'checklist'.config('constants.flash.deleted'), 'data' => []]);
    }

     public function checklistby_id($transaction_id)
    {
        //dd($transaction_id);
       $transactions=Transaction::select('id','name','order_no','total')->where('id',$transaction_id)->first();
           // dd($transactions);    
   $transaction_lists=TransactionItem::select('transaction_items.id','transaction_items.item_id','transaction_items.amount','inventory_items.name as name')->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id')->where('transaction_items.transaction_id',$transaction_id)->get();
// dd($transaction_list);
      return view('trade_wms.estimation',compact('transactions','transaction_lists'));
    }
    public function change_status(Request $request,$id){
      //  dd($request->all());
         $status= Transaction::findOrFail($id);
        $status->approval_status = $request->approval_status;
        $status->save();
    }
    
}
