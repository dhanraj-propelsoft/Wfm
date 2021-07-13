<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransactionItem;
use App\Transaction;
use App\HrmEmployee;
use Session;
use Auth;
use DB;
use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
class JobStatusController extends Controller
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
        $person_id=Auth::user()->person_id;
       // dd($person_id);

         $transaction_details=Transaction::select(DB::raw('CONCAT(transactions.order_no, " - ", vehicle_register_details.registration_no," -  ",vehicle_jobcard_statuses.`name`) AS job_card_name'),'transactions.id','transactions.order_no','transaction_items.start_time as start_date','transaction_items.end_time as due_date','transactions.name as customer_name','vehicle_register_details.registration_no','inventory_items.name as item','hrm_employees.first_name as assigned_to','transaction_items.job_item_status','account_vouchers.name','transaction_items.id as transaction_id','transaction_items.duration')->join('wms_transactions','wms_transactions.transaction_id','=','transactions.id')->join('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id')->join('transaction_items','transactions.id','=','transaction_items.transaction_id')->join('inventory_items','transaction_items.item_id','=','inventory_items.id')->join('hrm_employees','hrm_employees.id','=','transaction_items.assigned_employee_id')->join('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')->join('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id')->where('transactions.organization_id',$organization_id)->where('vehicle_jobcard_statuses.id','!=',8)->where('account_vouchers.name','=',"job_card")->where('transactions.deleted_at',null)->get();

        return view('trade_wms.vehicle_jobstatus',compact('transaction_details','state','city','title','payment','terms','group_name'));
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
     public function status_approval(Request $request)
    {
      // dd($request->all());
        TransactionItem::where('id', $request->input('id'))
          ->update(['job_item_status' => $request->input('status')]);
   
        return response()->json(["status" => $request->input('status')]);

}
}
