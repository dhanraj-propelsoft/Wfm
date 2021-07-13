<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Calendar;
use App\WmsTransaction;
use App\PeopleTitle;
use App\CustomerGroping;
use App\PaymentMode;
use App\VehicleRegisterDetail;
use App\VehicleJobcardStatus;
use App\Transaction;
use App\Country;
use App\State;
use App\Term;
use Session;
use App\AccountLedger;
use DB;

class ScheduleBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $organization_id=session::get('organization_id');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');


        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
        $group_name->prepend('Select Group Name','');

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');
        
        $events = [];
        $transaction = Transaction::select('transactions.id',
                     'vehicle_register_details.registration_no','wms_transactions.job_date','wms_transactions.job_due_date');
        $transaction->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
   
       

        $transaction->leftjoin('transactions AS reference_transactions','transactions.reference_id','=','reference_transactions.id');


        $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id'); 

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');
        $transaction->where('account_vouchers.name','job_card');
        $transaction->where('transactions.organization_id', $organization_id);   
        $transaction->whereNull('transactions.deleted_at');
        $transaction->groupby('transactions.id');
        $transaction->orderBy('transactions.updated_at','desc');
        $transactions = $transaction->get();
        
        if($transactions->count()) {
            foreach ($transactions as $key => $value) {
                $events[] = Calendar::event(
                    $value->registration_no,
                    true,
                    new \DateTime($value->job_date),
                    new \DateTime($value->job_due_date.' +1 day'),
                    null,  
    
                    [
                        'color' => '#FF4500',
                        'url' => route('transaction.index', ['job_card']) ,
                        
                          'right'=> 'month',
                       
                     ]
              );
            }
        }
        $ledger = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name','account_groups.name AS group');
        $ledger->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id');
        $ledger->whereIn('account_groups.name', ['cash']);
        $ledger->where('account_ledgers.organization_id', $organization_id);
        $ledger->where('account_ledgers.approval_status', '1');
        $ledger->where('account_ledgers.status', '1');
        $ledger->orderby('account_ledgers.id','asc');

        $ledgers = $ledger->pluck('name', 'id');
        $calendar = Calendar::addEvents($events);
         return view('trade_wms.schedule_board', compact('calendar','title','payment','terms','group_name','country','state','ledgers'));
    }
        //return view('trade_wms.job_card');


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
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
