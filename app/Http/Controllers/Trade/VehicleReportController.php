<?php

namespace App\Http\Controllers\Trade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountEntry;
use Session;

class VehicleReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function receipt_report()
    {  
        return view('trade.receivables_report');
    }

    public function receipt_details(Request $request)
    {  

        $from_date ="";
        $to_date ="";

        if($request->input('from_date'))
        {
        $from_date =date_string($request->input('from_date'));
        }
    
        if($request->input('to_date'))
        {
        $to_date = date_string($request->input('to_date'));
        }
        $organization_id = session::get('organization_id');
       
       
       $report = AccountEntry::select('account_entries.id','transactions.id as transaction_id','account_entries.voucher_no','account_entries.reference_voucher_id','transactions.order_no','account_transactions.amount','transactions.name as customer_name','account_entries.date')->leftjoin('transactions','transactions.id','=','account_entries.reference_voucher_id')->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')->where('account_entries.organization_id',$organization_id)->where('account_vouchers.name','=','receipt');

        if(!empty($from_date) && !empty($to_date))
       {
        $report->wherebetween('account_entries.date',[$from_date,$to_date]);
      
        }
        if($request->input('from_date'))
       {
        $report->where('account_entries.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
            $report->where('account_entries.date','<=',$to_date);
        }
        $reports=$report->get();

               
        if(count($reports) > 0 )
        {
            return response()->json(['status' => 1 ,'data' => $reports]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => "No data available."]);
        }
        
    }

     public function view_receipt_details($id)
    {
       $view_receipt_detail =  AccountEntry::select('account_entries.id','account_entries.voucher_no',
                               'account_vouchers.display_name AS voucher_name','account_entries.cheque_no',
                               'account_entries.date','account_entries.reference_voucher_id',
                               'transactions.order_no','account_entries.payment_mode_id',
                               'payment_modes.name as payment','account_transactions.debit_ledger_id',
                               'account_transactions.credit_ledger_id','al1.name as credit_ledger','al2.name as debit_ledger',
                               'account_entries.description','account_transactions.amount')
                             ->leftjoin('transactions','transactions.id','=','account_entries.reference_voucher_id')
                             ->leftjoin('payment_modes','payment_modes.id','=','account_entries.payment_mode_id')
                             ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
                             ->leftjoin('account_ledgers AS al1','al1.id','=','account_transactions.credit_ledger_id')
                             ->leftjoin('account_ledgers AS al2','al2.id','=','account_transactions.debit_ledger_id')
                             ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
                             ->where('account_vouchers.name','=','receipt')
                             ->where('account_entries.id',$id)->first();

                             

        return view('trade.receivables_report_view',compact('view_receipt_detail'));
    }
}
