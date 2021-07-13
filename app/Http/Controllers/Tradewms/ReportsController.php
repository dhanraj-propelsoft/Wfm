<?php

namespace App\Http\Controllers\Tradewms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\VehicleRegisterDetail;
use App\AccountVoucher;
use App\AccountEntry;
use App\InventoryItem;
use App\People;
use App\HrmBranch;
use Carbon\Carbon;
use Session;
use DB;
use App\Country;
use App\State;
use App\City;
use App\Expense;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;
use App\CustomerGroping;
use App\TaxGroup;
use App\Custom;
use App\InventoryItemStockLedger;
class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');
      
          $branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;   
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
         return view('trade_wms.all_reports',compact('branch','state','city','title','payment','terms','group_name'));
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
    public function get_report_details(Request $request)
    {
       // dd($request->all());
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');

        $organization_id = Session::get('organization_id');

        $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

        $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;

        $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

/*Stock Report*/
    $total_item = InventoryItem::select(DB::raw('count(inventory_items.id) as total_items'),DB::raw('SUM((CASE WHEN inventory_item_stocks.in_stock < 0 THEN 0 ELSE inventory_item_stocks.in_stock END) * inventory_items.selling_price)AS total_items_value'));
    $total_item->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
    $total_item->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
    $total_item->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
    $total_item->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
    $total_item->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
    $total_item->where('organization_id',$organization_id);
    $total_item->where('global_item_category_types.id',1);
    $total_items = $total_item->first();
    
        $total_goods = Transaction::select(DB::raw('(SUM(((transaction_items.amount) -(transaction_items.amount)*(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END) ) * (CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END)) + transaction_items.amount)as total_sale'));
            $total_goods->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
            $total_goods->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
            $total_goods->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
            $total_goods->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id');
            $total_goods->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
            $total_goods->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
            $total_goods->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
            $total_goods->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
            $total_goods->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
            $total_goods->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
            $total_goods->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
            $total_goods->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
            $total_goods->where('transactions.organization_id',$organization_id);
            $total_goods->where('account_voucher_types.name','=',"job_invoice");
            $total_goods->where('transactions.approval_status','=',1);
            $total_goods->where('global_item_category_types.id','=',1);
            $total_goods->groupby('transaction_items.id');
            if(!empty($from_date) && !empty($to_date))
            {
            $total_goods->wherebetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $total_goods->where('transactions.date','>=',$from_date);
            }
            if($request->input('to_date'))
            {
            $total_goods->where('transactions.date','<=',$to_date);
            }
            $goods_sale = $total_goods->get();
          
            $total_goods_sale = 0;
            foreach ($goods_sale as $key => $value) 
            {
               $total_goods_sale +=$value->total_sale;
            }
            //total purchase
            $total_purchase=Transaction::select(DB::raw('SUM(CASE WHEN account_vouchers.code = "PU"  THEN transactions.total ELSE 0 END)AS total_sale'));      
            $total_purchase->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id'); 
            $total_purchase->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');     
            $total_purchase->wherebetween('transactions.date',[$from_date,$to_date]);
            $total_purchase->where('transactions.date','>=',$from_date);  
            $total_purchase->where('transactions.date','<=',$to_date);  
            $total_purchase->where('transactions.organization_id',$organization_id); 
            $total_purchase->where('account_voucher_types.name','purchase');
            $total_purchase->where('transactions.approval_status',1);          
            $total_purchase->groupby('transactions.order_no');
            $purchase_sale= $total_purchase->get();
            $total_purchase_sale=0;

            foreach ($purchase_sale as $key => $value)
            {

               $total_purchase_sale=$total_purchase_sale+$value->total_sale;
               
            } 
            
        $transaction_cash1 = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher1 = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
        $return_voucher1 = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
        $journal_voucher1 = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
          
           $payable=Transaction::select(DB::raw('SUM(CASE WHEN account_vouchers.code = "PU"  THEN transactions.total ELSE 0 END)AS total_sale'),DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash1, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher1, $cash_voucher1, $return_voucher1)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher1, $cash_voucher1, $return_voucher1)) ) )) AS pending"));
            $payable->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
            $payable->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=','transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });                     
            $payable->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');  
            $payable->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id') ;    
            $payable->wherebetween('transactions.date',[$from_date,$to_date]);
            $payable->where('transactions.date','>=',$from_date);  
            $payable->where('transactions.date','<=',$to_date);  
            $payable->where('transactions.organization_id',$organization_id);
            $payable->where('account_voucher_types.name','purchase');
            $payable->where('transactions.approval_status',1);     
            $payable->groupby('transactions.people_id');           
            $payable->havingRaw('pending > 0');
            $payable_report= $payable->get();
            $total_payable=$payable_report->sum('pending');
        
        $lowstock_items=DB::table('inventory_items')->select(DB::raw('COUNT(inventory_items.id)as stock_total'))->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')->where('inventory_item_stocks.in_stock','<=','inventory_items.minimum_order_quantity')
         ->where('inventory_items.organization_id',$organization_id)
         ->first();
         $no_lowstock_items=$lowstock_items->stock_total;

        $lowstock_itemamount =DB::table('inventory_items')->select(DB::raw('SUM(((inventory_items.purchase_price)*taxes.value/100)+inventory_items.purchase_price*inventory_items.minimum_order_quantity) as amount'))
                ->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
                ->leftjoin('tax_groups','tax_groups.id','=','inventory_items.purchase_tax_id')
       
                 ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                 ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                ->where('inventory_item_stocks.in_stock','<=','inventory_items.minimum_order_quantity')
                ->where('inventory_items.organization_id',$organization_id)

                ->first();
                $lowstock_amount=$lowstock_itemamount->amount;

        /*end*/

        /*Low Stock report*/
        $item=InventoryItem::select(DB::raw('COUNT(inventory_item_stocks.in_stock) AS items'),DB::raw('SUM(inventory_items.selling_price)AS total_purchase_value'));
        $item->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
        $item->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
        $item->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id');
        $item->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id');
        $item->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
        $item->where('inventory_items.organization_id',$organization_id);
        $item->where('global_item_category_types.id',1);
        $item->where('inventory_item_stocks.in_stock','<',0);
        $inventory_items = $item->first();
        
      /*end*/

    /*Invoice report*/
        $total_invoices = Transaction::select(DB::raw('COUNT(transactions.id) AS total_invoice'),DB::raw('sum((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_invoice_value'),DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) )) AS pending"));
        $total_invoices->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
        $total_invoices->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $total_invoices->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
        $total_invoices->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        $total_invoices->where('transactions.organization_id',$organization_id);
        $total_invoices->where('account_voucher_types.name',"job_invoice");
        $total_invoices->where('transactions.approval_status',1);
        if(!empty($from_date) && !empty($to_date))
        {
        $total_invoices->wherebetween('transactions.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
        {
        $total_invoices->where('transactions.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
        $total_invoices->where('transactions.date','<=',$to_date);
        }
        $total_invoice = $total_invoices->first();


        $total_cashinvoices = Transaction::select(DB::raw('COUNT(transactions.id) AS total_cash_invoice'),DB::raw('sum((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_cashinvoice_value'));
        $total_cashinvoices->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
        $total_cashinvoices->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $total_cashinvoices->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        $total_cashinvoices->where('transactions.organization_id',$organization_id);
        $total_cashinvoices->where('account_vouchers.name',"job_invoice_cash");
        $total_cashinvoices->where('transactions.approval_status',1);
        $total_cashinvoices->whereNull('transactions.deleted_at');
        if(!empty($from_date) && !empty($to_date))
        {
        $total_cashinvoices->wherebetween('transactions.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
        {
        $total_cashinvoices->where('transactions.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
        $total_cashinvoices->where('transactions.date','<=',$to_date);
        }
        $total_cashinvoice = $total_cashinvoices->first();

                           
        $total_creditinvoices = Transaction::select(DB::raw('COUNT(transactions.id) AS total_invoice_credit'),DB::raw('sum((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) as total_creditinvoice_value'));
        $total_creditinvoices->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
        $total_creditinvoices->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $total_creditinvoices->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
        $total_creditinvoices->where('transactions.organization_id',$organization_id);
        $total_creditinvoices->where('account_vouchers.name',"job_invoice");
        $total_creditinvoices->where('transactions.approval_status',1);
        $total_creditinvoices->whereNull('transactions.deleted_at');
        if(!empty($from_date) && !empty($to_date))
        {
        $total_creditinvoices->wherebetween('transactions.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
        {
        $total_creditinvoices->where('transactions.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
        $total_creditinvoices->where('transactions.date','<=',$to_date);
        }
        $total_creditinvoice = $total_creditinvoices->first(); 


        $credit_invoice_pending = Transaction::select('transactions.id','transactions.order_no',DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"));
        $credit_invoice_pending->where('transactions.organization_id', $organization_id);
        $credit_invoice_pending->where(function ($query) use ($transaction_sales) {

            $query->where('transactions.transaction_type_id', '=', $transaction_sales);
                    });
        $credit_invoice_pending->where('transactions.approval_status',1);
        $credit_invoice_pending->whereNull('transactions.deleted_at');

        $credit_invoice_pending->where('transactions.notification_status','!=',2);

        $credit_invoice_pending->groupby('transactions.id');

        $credit_invoice_pending->orderBy('transactions.updated_at','desc');

        if(!empty($from_date) && !empty($to_date))
        {
        $credit_invoice_pending->wherebetween('transactions.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
        {
        $credit_invoice_pending->where('transactions.date','>=',$from_date);
        }
        if($request->input('to_date'))
        {
        $credit_invoice_pending->where('transactions.date','<=',$to_date);
        }

        $credit_invoice_pendings=$credit_invoice_pending->where('status',0)->orWhere('status',2)->get()->count($credit_invoice_pending);
            // dd($credit_invoice_pendings); 
       // $Count = count($credit_invoice_pendings->where("status",0)->where("status",2)); 
        $transaction_id = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;
       
       $today = Carbon::today()->format('Y-m-d');
    $last_six_month = Carbon::now()->subMonths(6)->startOfMonth();
        $six_month = $last_six_month->format('Y-m-d');

    $total_receivable = DB::select("SELECT SUM(balance) AS total FROM (
        SELECT 
            transactions.id,
            IF(
                (SELECT SUM(account_transactions.amount) FROM account_entries 
                    LEFT JOIN account_transactions 
                        ON account_transactions.entry_id = account_entries.id 
                    WHERE account_entries.reference_transaction_id = transactions.id 
                    AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,
                        SUM(transactions.total),
                        SUM(transactions.total) - 
                    (SELECT 
                        SUM(account_transactions.amount) 
                    FROM
                        account_entries 
                    LEFT JOIN account_transactions 
                        ON account_transactions.entry_id = account_entries.id 
                    WHERE account_entries.reference_transaction_id = transactions.id 
                        AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher))
                ) AS balance 
            FROM
                transactions 
                left join account_entries on account_entries.id = transactions.entry_id
            WHERE transactions.organization_id = $organization_id 
            AND account_entries.date BETWEEN '$six_month' AND '$today'
                AND transactions.approval_status = 1 
                AND transactions.transaction_type_id = $transaction_id
                AND transactions.deleted_at IS NULL 
                GROUP BY transactions.id
            ) AS trans");

        $total_receivables = $total_receivable[0]->total;
        /*end*/

        /*Purchase Report*/
        $total_purchases = Transaction::select(DB::raw('COUNT(transactions.id) AS total_purchase'),DB::raw('SUM(transactions.total) as total_purchase_value'));
        $total_purchases->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
        $total_purchases->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
        $total_purchases->where('transactions.organization_id',$organization_id);
        $total_purchases->where('account_vouchers.name',"purchases");
        $total_purchases->whereNull('transactions.deleted_at');
        $total_purchases->where('transactions.approval_status',1);
        if(!empty($from_date) && !empty($to_date))
       {
        $total_purchases->wherebetween('transactions.date',[$from_date,$to_date]);
        }
        if($request->input('from_date'))
       {
        $total_purchases->where('transactions.date','>=',$from_date);
      
        }
        if($request->input('to_date'))
        {
        $total_purchases->where('transactions.date','<=',$to_date);
        }
        $total_purchase = $total_purchases->first();

    /*end*/

/*Customers/ventors*/
        $total_customer = People::select(DB::raw('count(people.id) as total_customers'));
        $total_customer->leftjoin('people_person_types','people_person_types.people_id','=','people.id');
        $total_customer->where('people.organization_id',$organization_id);
        $total_customer->where('people_person_types.person_type_id','=',2);
        $total_customers = $total_customer->first();
        
               //new customers
               $total_newcustomer = People::select(DB::raw('count(people.id) as total_newcustomers'));
                $total_newcustomer->leftjoin('people_person_types','people_person_types.people_id','=','people.id');
                $total_newcustomer->where('people.organization_id',$organization_id);
                $total_newcustomer->where('people_person_types.person_type_id','=',2);
                if($request->input('from_date'))
                     {
                         $total_newcustomer->where('people.created_at','>=',$from_date);
                     }
                if($request->input('to_date'))
                    {
                    $total_newcustomer->where('people.created_at','<=',$to_date);
                    }
                $total_newcustomers = $total_newcustomer->first(); 

        $total_vehicle = VehicleRegisterDetail::select(DB::raw('count(vehicle_register_details.id)as total_vehicles'));
        $total_vehicle->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');
        $total_vehicle->where('vehicle_register_details.status','=',1);
        $total_vehicle->where('wms_vehicle_organizations.organization_id',$organization_id); 
        $total_vehicles = $total_vehicle->first();
        //new vehicles
        $total_newvehicle = VehicleRegisterDetail::select(DB::raw('count(vehicle_register_details.id)as total_newvehicles'));
                $total_newvehicle->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');
                $total_newvehicle->where('vehicle_register_details.status','=',1);
                $total_newvehicle->where('wms_vehicle_organizations.organization_id',$organization_id); 
                if($request->input('from_date'))
                     {
                         $total_newvehicle->where('vehicle_register_details.created_at','>=',$from_date);
                     }
                if($request->input('to_date'))
                    {
                    $total_newvehicle->where('vehicle_register_details.created_at','<=',$to_date);
                    }
               
                $total_newvehicles = $total_newvehicle->first();
/*end*/   

/*Sales by Spares*/
    $sales_by_spares = Transaction::select(DB::raw('(transaction_items.amount - (transaction_items.amount * (CASE WHEN transaction_items.discount_value IS NULL THEN 0 
        ELSE transaction_items.discount_value / 100 END))) + SUM(((transaction_items.amount) - (transaction_items.amount) * (CASE WHEN transaction_items.discount_value IS NULL THEN 0 
          ELSE transaction_items.discount_value / 100 END)) * (
        CASE WHEN taxes.`value` IS NULL THEN 0 ELSE taxes.value/ 100 END)) as sales_by_spares'));
    $sales_by_spares->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
    $sales_by_spares->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
    $sales_by_spares->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
    $sales_by_spares->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id');
    $sales_by_spares->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
    $sales_by_spares->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
    $sales_by_spares->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
    $sales_by_spares->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
    $sales_by_spares->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
    $sales_by_spares->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
    $sales_by_spares->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
    $sales_by_spares->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
    $sales_by_spares->where('transactions.organization_id',$organization_id);
    $sales_by_spares->where('account_voucher_types.name','=',"job_invoice");
    $sales_by_spares->where('transactions.approval_status','=',1);
    $sales_by_spares->where('global_item_category_types.id','=',1);
    $sales_by_spares->groupby('transaction_items.id');
    if(!empty($from_date) && !empty($to_date))
    {
    $sales_by_spares->wherebetween('transactions.date',[$from_date,$to_date]);
    }
    if($request->input('from_date'))
    {
    $sales_by_spares->where('transactions.date','>=',$from_date);
    }
    if($request->input('to_date'))
    {
    $sales_by_spares->where('transactions.date','<=',$to_date);
    }
    $spares = $sales_by_spares->get();
    $sales_by_spare = 0;
    foreach ($spares as $key => $value) {
       $sales_by_spare +=$value->sales_by_spares;
    }
   
         
/*end*/  

/*Sales by Works*/
   $sales_by_works =Transaction::select(DB::raw('(transaction_items.amount - (transaction_items.amount * (CASE WHEN transaction_items.discount_value IS NULL THEN 0 
        ELSE transaction_items.discount_value / 100 END))) + SUM(((transaction_items.amount) - (transaction_items.amount) * (CASE WHEN transaction_items.discount_value IS NULL THEN 0 
          ELSE transaction_items.discount_value / 100 END)) * (
        CASE WHEN taxes.`value` IS NULL THEN 0 ELSE taxes.value/ 100 END)) as sales_by_works'));
    $sales_by_works->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
    $sales_by_works->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
    $sales_by_works->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
    $sales_by_works->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id');
    $sales_by_works->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
    $sales_by_works->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
    $sales_by_works->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
    $sales_by_works->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
    $sales_by_works->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
    $sales_by_works->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
    $sales_by_works->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
    $sales_by_works->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
    $sales_by_works->where('transactions.organization_id',$organization_id);
    $sales_by_works->where('account_voucher_types.name','=',"job_invoice");
    $sales_by_works->where('transactions.approval_status','=',1);
    $sales_by_works->where('global_item_category_types.id','=',2);
    $sales_by_works->groupby('transaction_items.id');
    if(!empty($from_date) && !empty($to_date))
    {
    $sales_by_works->wherebetween('transactions.date',[$from_date,$to_date]);
    }
    if($request->input('from_date'))
    {
    $sales_by_works->where('transactions.date','>=',$from_date);
    }
    if($request->input('to_date'))
    {
    $sales_by_works->where('transactions.date','<=',$to_date);
    }
    $works = $sales_by_works->get();
       
    $sales_by_work = 0;
    foreach ($works as $key => $value) {
       $sales_by_work += $value->sales_by_works;
    }
   
/*End*/   

 /*Daily Expenses*/
    $total_expenses = AccountEntry::select(DB::raw('sum(expenses_transactions.amount) as total'),DB::raw('count(account_entries.id)as total_expenses'));
    $total_expenses->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
    $total_expenses->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
    $total_expenses->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
    $total_expenses->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
    $total_expenses->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');
    $total_expenses->where('account_entries.organization_id','=',$organization_id);
    $total_expenses->where('account_ledgers.name','=','Daily Expenses');
    $total_expenses->where('expenses_transactions.amount','!=','0.0');
    if(!empty($from_date) && !empty($to_date))
    {
    $total_expenses->wherebetween('account_entries.date',[$from_date,$to_date]);
    }
    if($request->input('from_date'))
    {
    $total_expenses->where('account_entries.date','>=',$from_date);
      
    }
    if($request->input('to_date'))
    {
    $total_expenses->where('account_entries.date','<=',$to_date);
        }
    $total_expense = $total_expenses->first();
    /*End*/

       /*Company Expenses*/

            $company_expenses = AccountEntry::select('account_entries.date','expenses_transactions.amount','expenses_transactions.id',DB::raw('SUM(expenses_transactions.amount) as expenses_total'));
            $company_expenses->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
            $company_expenses->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.credit_ledger_id');
            $company_expenses->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_transactions.entry_id');
            $company_expenses->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
            $company_expenses->where('account_entries.organization_id','=',$organization_id);
            $company_expenses->where('expenses_transactions.expense_type',2);
           
           /*$company_expenses->where(function($query){
               $query->where('expenses_transactions.expense_type',2)
               ->orWhere('expenses_transactions.expense_type',NULL);

           });*/
           $company_expenses->where('expenses_transactions.amount','!=','0.0');
            if(!empty($from_date) && !empty($to_date))
            {
            $company_expenses->wherebetween('account_entries.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $company_expenses->where('account_entries.date','>=',$from_date);
              
            }
            if($request->input('to_date'))
            {
            $company_expenses->where('account_entries.date','<=',$to_date);
                }
            $company_expense = $company_expenses->first();
            //dd($company_expense);

        /*End*/


        return response()->json(['status'=>1,'total_invoice' => $total_invoice, 'total_cashinvoice' => $total_cashinvoice, 'total_creditinvoice' =>$total_creditinvoice,'credit_invoice_pending'=>$credit_invoice_pendings,'total_invoice_value'=>$total_invoice->total_invoice_value,'total_cashinvoice_value'=>$total_cashinvoice->total_cashinvoice_value,'total_creditinvoice_value'=>$total_creditinvoice->total_creditinvoice_value,'total_customers'=>$total_customers,'total_vehicles'=>$total_vehicles,'total_purchase'=>$total_purchase,'total_purchase_value'=>$total_purchase->total_purchase_value,'total_items'=>$total_items->total_items,'total_items_value'=>$total_items->total_items_value,'jc_job_card_total'=>$inventory_items->items,'jc_job_card_total_amount'=>$inventory_items->total_purchase_value,'sales_by_spares'=>$sales_by_spare,'sales_by_works'=>$sales_by_work,'total_expenses'=>$total_expense,'total_goods_sale'=>$total_goods_sale,'total_purchase_sale'=>$total_purchase_sale,'total_payable'=>$total_payable,'no_lowstock_items'=>$no_lowstock_items,'lowstock_amount'=>$lowstock_amount,'total_receivables'=>$total_receivables,'total_newcustomers'=>$total_newcustomers->total_newcustomers,'total_newvehicles'=>$total_newvehicles->total_newvehicles,'company_expense' => $company_expense]);                                  
    }
   public function export_report(Request $request){
       
        $organization_id = Session::get('organization_id');
        $name = $request->name;
        $financial_date = $request->financial_date;
       
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
      
        $start_date=$from_date;
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $end_date=$to_date;

       if($name == "Stock Report"){
         $today_date = now()->format("Y-m-d "); 
            $stocks = InventoryItem::select('inventory_items.id','inventory_items.name','inventory_items.hsn','global_item_categories.name AS category_name','global_item_main_categories.name AS main_category_name','global_item_category_types.name AS type_name','global_item_makes.name AS make_name','global_item_models.identifier_a','inventory_item_stocks.in_stock AS in_stock','inventory_items.selling_price','inventory_items.sale_price_data','inventory_items.include_tax','taxes.value as tax_value','inventory_items.base_price','inventory_items.purchase_price');
            $stocks->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
            $stocks->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
            $stocks->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
            $stocks->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id');
            $stocks->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id');
            $stocks->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
            $stocks->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
            $stocks->leftjoin('transaction_items','transaction_items.item_id','=','inventory_items.id');
            $stocks->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id');
            $stocks->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
            $stocks->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
            $stocks->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
            $stocks->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id');
            $stocks->where('inventory_items.organization_id',$organization_id);
            $stocks->where('global_item_category_types.id',1);
            $stocks->where(DB::raw('date(transactions.created_at)'),'=',$today_date);
            $stocks->groupby('inventory_items.id');
            $stock_report = $stocks->get();
             if($stock_report->isNotEmpty())
            {

                $total_stock_value = InventoryItem::select(DB::raw('count(inventory_items.id) as total_items'),DB::raw('SUM((CASE WHEN inventory_item_stocks.in_stock < 0 THEN 0 ELSE inventory_item_stocks.in_stock END) * inventory_items.selling_price)AS total_items_value'),DB::raw('SUM(inventory_items.selling_price)AS total_selling'),DB::raw('SUM(inventory_items.purchase_price)AS total_purchase'));
                $total_stock_value->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
                $total_stock_value->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
                $total_stock_value->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
                $total_stock_value->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
                $total_stock_value->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
                 $total_stock_value->leftjoin('transaction_items','transaction_items.item_id','=','inventory_items.id');
                $total_stock_value->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id');
          
                  $total_stock_value->where('inventory_items.organization_id',$organization_id);
                $total_stock_value->where('global_item_category_types.id',1);
                 $total_stock_value->where(DB::raw('date(transaction_items.created_at)'),'=',$today_date);
                  $total_stock_value->groupby('inventory_items.id');
                $total_stock_values = $total_stock_value->first();

                $currentstock_totalitemsvalue=$total_stock_values->total_items_value;
                 $currenstock_totalitems=count($stock_report);
            }
            else{
                $currentstock_totalitemsvalue=0;
                $currenstock_totalitems=0;
            }

            //dd($total_stock_values);


            return response()->json(['name' => "Stock Report" ,'stock_report' => $stock_report,'total_stock_value'=>$currentstock_totalitemsvalue,'total_in_stock'=>$currenstock_totalitems]);
        }
       else if($name == "Job card Low Stocks"){     
           
            $low_stocks = InventoryItem::select('transaction_items.rate','inventory_items.id','inventory_items.name','inventory_items.selling_price',DB::raw('IF(inventory_items.purchase_price IS NULL,0,inventory_items.purchase_price)AS purchase_price'),'transaction_items.quantity AS qty','hrm_employees.first_name AS assigned_to','transactions.name AS customer','transactions.id','transactions.date AS jc_date','transactions.order_no AS jc_no','taxes.display_name AS tax','taxes.value AS tax_value','transactions.created_at','vehicle_register_details.registration_no','inventory_item_stocks.in_stock',DB::raw('((inventory_items.selling_price *taxes.value/100)+inventory_items.purchase_price)as total_purchase_price'),DB::raw('(((inventory_items.selling_price *taxes.value/100)+inventory_items.purchase_price)*(CASE WHEN transaction_items.quantity IS NULL THEN 1  ELSE transaction_items.quantity  END))as total_payment'));
            $low_stocks->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
            $low_stocks->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
            $low_stocks->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id');
            $low_stocks->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id');
            $low_stocks->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
            $low_stocks->leftjoin('transaction_items','transaction_items.item_id','=','inventory_items.id');
            $low_stocks->leftjoin('hrm_employees','hrm_employees.id','=','transaction_items.assigned_employee_id');
            $low_stocks->leftjoin('transactions','transactions.id','=','transaction_items.transaction_id');
            $low_stocks->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
            $low_stocks->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
            $low_stocks->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
            $low_stocks->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id');
            $low_stocks->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');  
            $low_stocks->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id'); 
            $low_stocks->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $low_stocks->leftjoin('vehicle_jobcard_statuses','vehicle_jobcard_statuses.id','=','wms_transactions.jobcard_status_id');  
            $low_stocks->leftjoin('vehicle_register_details','vehicle_register_details.id','=','wms_transactions.registration_id');  
            $low_stocks->where('inventory_items.organization_id','=',$organization_id);
            $low_stocks->where('global_item_category_types.id','=',1);
            $low_stocks->where('account_voucher_types.name','job_card');
            $low_stocks->whereColumn('inventory_item_stocks.in_stock','<','transaction_items.quantity');
           
            $low_stocks->where('vehicle_jobcard_statuses.name','<>','Closed');
            $low_stocks->where('vehicle_jobcard_statuses.name','<>','Vehicle Ready');
            $low_stocks->groupby('transactions.id');
            $low_stock_reports = $low_stocks->get();
         

            $total_amount=$low_stock_reports->sum('total_payment');
           
     

            return response()->json(['name' => "Job card Low Stocks" ,'low_stock_reports' => $low_stock_reports,'total_amount'=>$total_amount]);
        }
       else if ($name=="Stock Flow") {
              $inventory_details = InventoryItem::select('inventory_items.id AS item_id','inventory_items.name','inventory_item_stocks.in_stock','inventory_items.purchase_price','inventory_items.base_price','inventory_items.purchase_tax_id','inventory_item_stocks.entry_id')
                ->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
                ->leftjoin('account_entries','account_entries.id','=','inventory_item_stocks.entry_id')
                ->whereBetween('account_entries.date',[$financial_date,$end_date])
                ->where('inventory_items.organization_id',$organization_id)
                ->get();        


                $opening_balance = [];

                $inwards = [];
                $outwards = [];
                $update = [];
                $i = 0; 

                $grand_quantity = 0;
                $grand_value = 0;
                $grand_inwards_quantity = 0;
                $grand_inwards_value = 0;
                $grand_outwards_quantity = 0;
                $grand_outwards_value = 0;
                $grand_closing_quantity = 0;
                $grand_closing_value = 0;

                foreach ($inventory_details as $key => $value) 
                {
                    $opening_qty = 0;
                    $inwards_qty = 0;
                    $outwards_qty = 0;
                    $opening_val = 0;
                    $inwards_val = 0;
                    $outwards_val = 0;
                    $sum_inward_quantity = 0;
                    $sum_outward_quantity = 0;


                    

                    $item_id = $inventory_details[$key]->item_id;
                    $entry_id = $inventory_details[$key]->entry_id;
                    $item_name = $inventory_details[$key]->name;
                    $purchase_price = $inventory_details[$key]->purchase_price;
                    $sale_price = $inventory_details[$key]->base_price;
                    $in_stock = $inventory_details[$key]->in_stock;
                    $purchase_tax_id = $inventory_details[$key]->purchase_tax_id;

                   if($in_stock == 0){
                  $in_stock = 0.00;
                  }


                    $purchase_tax_value = TaxGroup::select(DB::raw('SUM(taxes.value) AS value'))
                    ->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id')
                    ->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
                    ->where('tax_groups.organization_id', $organization_id)
                    ->where('tax_groups.id', $purchase_tax_id)
                    ->groupby('tax_groups.id')->first();

                    if($purchase_tax_id != null)
                    {
                        $purchase_tax_amount = Custom::two_decimal(($purchase_tax_value->value/100) * ($purchase_price));

                        $purchase_tax_price = Custom::two_decimal($purchase_price + $purchase_tax_amount);
                    }
                    else{
                        $purchase_tax_price = $purchase_price;
                    }        
                        
                    $list = InventoryItemStockLedger::where('inventory_item_stock_id',$item_id)->get();
                    // $list = json_decode($inventory_details[$key]->data,true);

                    if($list != null || $list != ''){
                        
                        foreach ($list as $key => $value) 
                        {
                            if($list[$key] != null)
                            {

                                $opening_balance[$i]['item_name'] = $item_name;

                                $opening_balance[$i]['entry_id'] = $entry_id;


                                //$opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_price);
                                

                                      if(isset($list[$key]['voucher_type']) && ($list[$key]['voucher_type']== 'Goods Receipt Note' ||  
                      $list[$key]['voucher_type'] == 'Purchase') && isset($list[$key]['status']) &&  $list[$key]['status']== 1)
                    {

                                    $sum_inward_quantity = Custom::two_decimal($sum_inward_quantity + $list[$key]['quantity']);

                                    $opening_balance[$i]['inwards_quantity'] = $sum_inward_quantity;

                                    $inwards_qty = $opening_balance[$i]['inwards_quantity'];                    

                                    $opening_balance[$i]['inwards_price'] = $list[$key]['purchase_price'];

                                    $opening_balance[$i]['inwards_value'] = Custom::two_decimal($sum_inward_quantity * $list[$key]['purchase_price']);

                                    $inwards_val = $opening_balance[$i]['inwards_value'];               
                                }

                                if( isset($list[$key]['voucher_type']) && ($list[$key]['voucher_type'] == 'Delivery Note' ||  
                                $list[$key]['voucher_type'] == 'Job Invoice Credit' 
                                || $list[$key]['voucher_type'] == 'Job Invoice Cash' || $list[$key]['voucher_type'] == 'Invoice' || $list[$key]['voucher_type'] == 'Invoice Cash' ) && isset($list[$key]['status']) && $list[$key]['status']== 1)
                              {
                                    //$opening_balance['opening_balance']['inwards_quantity'] = $list[$key]['quantity'];

                                    $sum_outward_quantity = Custom::two_decimal($sum_outward_quantity + $list[$key]['quantity']);

                                    $opening_balance[$i]['outwards_quantity'] = $sum_outward_quantity;

                                    $outwards_qty = $opening_balance[$i]['outwards_quantity'];                      

                                    $opening_balance[$i]['outwards_price'] = $list[$key]['sale_price'];

                                    $opening_balance[$i]['outwards_value'] = Custom::two_decimal($sum_outward_quantity * $list[$key]['sale_price']);

                                    $outwards_val = $opening_balance[$i]['outwards_value'];     
                                }


                                $opening_quantity = Custom::two_decimal($in_stock - $inwards_qty + $outwards_qty);

                                if($opening_quantity > 0){

                                    $opening_balance[$i]['opening_quantity'] = $opening_quantity;
                                }else{
                                    $opening_balance[$i]['opening_quantity'] = 0.00;
                                }                       

                                $opening_qty = $opening_balance[$i]['opening_quantity'];

                                $opening_balance[$i]['opening_price'] = $purchase_tax_price;

                                $opening_balance[$i]['opening_value'] = Custom::two_decimal( $opening_qty * $purchase_tax_price);

                                $opening_val = $opening_balance[$i]['opening_value'];

                                $opening_balance[$i]['opening_quantity'] = (isset($opening_qty)) ? $opening_qty : $in_stock;

                                $opening_balance[$i]['opening_value'] = (isset($opening_val)) ? $opening_val : Custom::two_decimal($in_stock * $purchase_tax_price);



                                $opening_balance[$i]['inwards_quantity'] = (isset($inwards_qty)) ? $inwards_qty : 0.00;     

                                $opening_balance[$i]['inwards_value'] = (isset($inwards_val)) ? $inwards_val : 0.00;

                                $opening_balance[$i]['outwards_quantity'] = (isset($outwards_qty))  ? $outwards_qty : 0.00 ;            

                                $opening_balance[$i]['outwards_value'] =(isset($outwards_val)) ? $outwards_val : 0.00 ;


                                if(isset($opening_balance[$i]['opening_quantity'])) {
                                    
                                    $opening_qty = $opening_balance[$i]['opening_quantity'];
                                    $opening_val = $opening_balance[$i]['opening_value'];
                                }
                                if(isset($opening_balance[$i]['inwards_quantity'])){
                                    $inwards_qty = $opening_balance[$i]['inwards_quantity'];
                                    $inwards_val = $opening_balance[$i]['inwards_value'];
                                }
                                if(isset($opening_balance[$i]['outwards_quantity'])){
                                    $outwards_qty = $opening_balance[$i]['outwards_quantity'];
                                    $outwards_val = $opening_balance[$i]['outwards_value'];
                                }

                                $opening_balance[$i]['closing_quantity'] = Custom::two_decimal($in_stock );

                                $opening_balance[$i]['closing_value'] =Custom::two_decimal($in_stock * $purchase_tax_price);                        

                            }
                            else{

                                $opening_balance[$i]['item_name'] = $item_name;
                                $opening_balance[$i]['entry_id'] = $entry_id;
                                
                                $opening_balance[$i]['opening_quantity'] = $in_stock;
                                $opening_balance[$i]['opening_price'] = $purchase_tax_price;
                                $opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_tax_price);                      

                                $opening_balance[$i]['inwards_quantity'] = 0.00;
                                $opening_balance[$i]['inwards_price'] = 0.00;
                                $opening_balance[$i]['inwards_value'] = 0.00;

                                $opening_balance[$i]['outwards_quantity'] = 0.00 ;
                                $opening_balance[$i]['outwards_price'] = 0.00 ;
                                $opening_balance[$i]['outwards_value'] = 0.00 ;

                                $opening_balance[$i]['closing_quantity'] = $in_stock ;
                                $opening_balance[$i]['closing_value'] = Custom::two_decimal($in_stock * $purchase_tax_price);
                            }               
                            
                        }
                        
                    }
                    else{

                
                        $opening_balance[$i]['item_name'] = $item_name;
                        
                        $opening_balance[$i]['entry_id'] = $entry_id;

                        $opening_balance[$i]['opening_quantity'] = $in_stock;

                        $opening_balance[$i]['opening_price'] = $purchase_tax_price;

                        $opening_balance[$i]['opening_value'] = Custom::two_decimal( $in_stock * $purchase_tax_price);

                        $opening_balance[$i]['inwards_quantity'] = 0.00;
                        $opening_balance[$i]['inwards_price'] = 0.00;
                        $opening_balance[$i]['inwards_value'] = 0.00;

                        $opening_balance[$i]['outwards_quantity'] = 0.00 ;
                        $opening_balance[$i]['outwards_price'] = 0.00 ;
                        $opening_balance[$i]['outwards_value'] = 0.00 ;

                        $opening_balance[$i]['closing_quantity'] = $in_stock ;
                        $opening_balance[$i]['closing_value'] = Custom::two_decimal($in_stock * $purchase_tax_price);
                    }


                    $grand_quantity = Custom::two_decimal($grand_quantity + $opening_balance[$i]['opening_quantity']);

                    $grand_value = Custom::two_decimal($grand_value + $opening_balance[$i]['opening_value']);

                    $grand_inwards_quantity = Custom::two_decimal($grand_inwards_quantity + $opening_balance[$i]['inwards_quantity']);

                    $grand_inwards_value = Custom::two_decimal($grand_inwards_value + $opening_balance[$i]['inwards_value']);

                    $grand_outwards_quantity = Custom::two_decimal($grand_outwards_quantity + $opening_balance[$i]['outwards_quantity']);

                    $grand_outwards_value = Custom::two_decimal($grand_outwards_value + $opening_balance[$i]['outwards_value']);

                    $grand_closing_quantity = Custom::two_decimal($grand_closing_quantity + $opening_balance[$i]['closing_quantity']);

                    $grand_closing_value = Custom::two_decimal($grand_closing_value + $opening_balance[$i]['closing_value']);       

                
                    $i++;   

                }
              

                return response()->json(['name'=>'Stock Flow','result' =>$opening_balance,'grand_quantity' =>$grand_quantity , 'grand_value' => $grand_value,'grand_inwards_quantity' => $grand_inwards_quantity, 'grand_inwards_value' => $grand_inwards_value,'grand_outwards_quantity'=> $grand_outwards_quantity, 'grand_outwards_value'=>$grand_outwards_value,'grand_closing_quantity'=> $grand_closing_quantity,'grand_closing_value' =>$grand_closing_value]);
               
                   
           
        }
        else if($name == "Invoice Report"){
            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
            $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;

            $invoice_reports = Transaction::select('transactions.date','transactions.order_no','transactions.name as customer','inventory_items.name','inventory_items.hsn','transaction_items.rate','transaction_items.quantity','transaction_items.amount','transaction_items.discount_value AS discount','transaction_items.tax','taxes.display_name as taxes','tax_types.id as tax_type','payment_modes.display_name AS payment_mode',DB::raw('((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_sales'),DB::raw("IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS pending_payment"),'taxes.value AS tax_value','tax_groups.display_name as gst','global_item_category_types.name as category','account_vouchers.display_name as sale_type');
            $invoice_reports->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
            $invoice_reports->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
            $invoice_reports->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
            $invoice_reports->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id');
            $invoice_reports->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
            $invoice_reports->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
            $invoice_reports->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
            $invoice_reports->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
            $invoice_reports->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id');
            $invoice_reports->leftjoin('payment_modes','payment_modes.id','=','transactions.payment_mode_id');
            $invoice_reports->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id');
            $invoice_reports->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id');
            $invoice_reports->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id');
            $invoice_reports->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id');
            $invoice_reports->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $invoice_reports->where('transactions.organization_id',$organization_id);
            $invoice_reports->where('account_voucher_types.name',"job_invoice");
            $invoice_reports->where('transactions.approval_status',1);
            $invoice_reports->whereNull('transactions.deleted_at');
            $invoice_reports->groupby('transaction_items.id');
             if(!empty($from_date) && !empty($to_date))
            {
            $invoice_reports->wherebetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $invoice_reports->where('transactions.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $invoice_reports->where('transactions.date','<=',$to_date);
            }
            $invoice_report = $invoice_reports->get();

    
            $total_invoices = Transaction::select(DB::raw('COUNT(transactions.id) AS total_invoice'),DB::raw('sum((CASE WHEN wms_transactions.advance_amount IS NULL THEN 0 ELSE wms_transactions.advance_amount END) + transactions.total) AS total_invoice_value'),DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) )) AS pending"));
            $total_invoices->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
            $total_invoices->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
            $total_invoices->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id');
            $total_invoices->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
            $total_invoices->where('transactions.organization_id',$organization_id);
            $total_invoices->where('account_voucher_types.name',"job_invoice");
            $total_invoices->where('transactions.approval_status',1);
            if(!empty($from_date) && !empty($to_date))
            {
            $total_invoices->wherebetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $total_invoices->where('transactions.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $total_invoices->where('transactions.date','<=',$to_date);
            }
            $total_invoice = $total_invoices->first();


            return response()->json(['name' => "Invoice Report" ,'invoice_report' => $invoice_report,'total_sales'=>$total_invoice->total_invoice_value,'total_pending'=>$total_invoice->pending]);
        }
        else if($name == "Purchase Report"){

            $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;

            $transaction_cash = AccountVoucher::where('name','purchases')->where('organization_id', $organization_id)->first()->id;

            $cash_voucher = 0;

            $return_voucher = 0;

            $purchase_report = Transaction::select('transactions.id','transactions.date','transactions.order_no','transactions.name AS customer','inventory_items.name AS item_name','inventory_items.hsn AS hsn','transaction_items.new_selling_price','inventory_items.purchase_price','transaction_items.quantity','transaction_items.amount AS total','transaction_items.tax','transactions.total AS total_sales',DB::raw('IF(transactions.total - account_transactions.amount  IS NULL,transactions.total,transactions.total - account_transactions.amount)AS amount'),DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance"),'taxes.display_name as tax','tax_types.id as tax_type','transaction_items.amount as total_amount','transaction_items.rate',DB::raw('sum(taxes.value) as tax_value'));
            $purchase_report->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
            $purchase_report->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id');
            $purchase_report->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id');
            $purchase_report->leftjoin('account_entries','account_entries.reference_voucher_id','=','transactions.id');
            $purchase_report->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
            $purchase_report->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id');
            $purchase_report->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
            $purchase_report->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
            $purchase_report->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id');
            $purchase_report->where('transactions.organization_id',$organization_id);
            $purchase_report->where('account_vouchers.name','purchases');
            $purchase_report->where('transactions.approval_status',1);
            $purchase_report->groupby('transactions.id','inventory_items.name');
            if(!empty($from_date) && !empty($to_date))
            {
                $purchase_report->wherebetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
                $purchase_report->where('transactions.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
                $purchase_report->where('transactions.date','<=',$to_date);
            }
                $purchase_reports = $purchase_report->get();


            $total_purchases = Transaction::select(DB::raw('COUNT(transactions.id) AS total_purchase'),DB::raw('SUM(transactions.total) as total_purchase_value'));
            $total_purchases->leftjoin('account_entries','account_entries.id','=','transactions.entry_id');
            $total_purchases->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id');
            $total_purchases->where('transactions.organization_id',$organization_id);
            $total_purchases->where('account_vouchers.name',"purchases");
            $total_purchases->whereNull('transactions.deleted_at');
            $total_purchases->where('transactions.approval_status',1);
            if(!empty($from_date) && !empty($to_date))
            {
            $total_purchases->wherebetween('transactions.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $total_purchases->where('transactions.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $total_purchases->where('transactions.date','<=',$to_date);
            }
            $total_purchase = $total_purchases->first();
           
            
            return response()->json(['name' => "Purchase Report" ,'purchase_reports' =>$purchase_reports,'total_purchase'=>$total_purchase->total_purchase_value]);
        }
        else if($name == "Customers/Vehicles"){
            $customer_vehicles = VehicleRegisterDetail::select('vehicle_register_details.id','vehicle_register_details.registration_no','vehicle_register_details.created_at',DB::raw('CONCAT(vehicle_makes.name,"-",vehicle_models.name,"-",vehicle_variants.name) AS vehicle_name'),DB::raw('IF(people.display_name IS NULL,business.display_name,people.display_name) AS customer'),DB::raw('IF(customer_gropings.display_name IS NULL,group.display_name,customer_gropings.display_name)as group_name'),DB::raw('IF(account_ledger_credit_infos.max_credit_limit IS NULL, business_ledger_credit_infos.max_credit_limit, account_ledger_credit_infos.max_credit_limit) AS credit_limit'),DB::raw('IF(people.mobile_no IS NULL,business.mobile_no,people.mobile_no) AS mobile_no'),DB::raw('IF(city.name IS NULL,citie.name,city.name)AS city'),DB::raw('SUM(CASE WHEN account_vouchers.code = "JI" OR account_vouchers.code = "JIC" THEN transactions.total ELSE 0 END)AS total_sale'));
            $customer_vehicles->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });
            $customer_vehicles->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
            });
            $customer_vehicles->leftJoin('vehicle_variants', 'vehicle_variants.id','=','vehicle_register_details.vehicle_variant_id');
            $customer_vehicles->leftJoin('vehicle_models', 'vehicle_models.id','=','vehicle_register_details.vehicle_model_id');
            $customer_vehicles->leftJoin('vehicle_makes', 'vehicle_makes.id','=','vehicle_register_details.vehicle_make_id');
            $customer_vehicles->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id');
            $customer_vehicles->leftjoin('customer_gropings as group','group.id','=','business.group_id');
            $customer_vehicles->leftJoin('account_ledgers', function($join) use($organization_id)
            {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
            });
            $customer_vehicles->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id');
            $customer_vehicles->leftJoin('account_ledgers AS business_ledgers', function($join) use($organization_id)
            {
                $join->on('business.business_id', '=', 'business_ledgers.business_id')
                ->where('business_ledgers.organization_id', $organization_id);
            });
            $customer_vehicles->leftjoin('account_ledger_credit_infos AS business_ledger_credit_infos','business_ledgers.id','=','business_ledger_credit_infos.id');
            $customer_vehicles->leftjoin('person_communication_addresses', function($join){
                $join->on('person_communication_addresses.person_id','=','people.person_id')
                ->where('person_communication_addresses.address_type', '1');
            });
            $customer_vehicles->leftjoin('business_communication_addresses', function($join){
                $join->on('business_communication_addresses.business_id','=','business.business_id')
                ->where('business_communication_addresses.address_type', '1');
            });
            $customer_vehicles->leftjoin('cities AS city','city.id','=','person_communication_addresses.city_id');
            $customer_vehicles->leftjoin('cities AS citie','citie.id','=','business_communication_addresses.city_id');
            $customer_vehicles->leftjoin('wms_transactions','wms_transactions.registration_id','=','vehicle_register_details.id');
            $customer_vehicles->leftjoin('transactions','transactions.id','=','wms_transactions.transaction_id');
            $customer_vehicles->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
            $customer_vehicles->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');
            $customer_vehicles->where('vehicle_register_details.status','=','1');
            $customer_vehicles->where('wms_vehicle_organizations.organization_id','=',$organization_id);
            $customer_vehicles->groupby('vehicle_register_details.id');
            $customer_vehicles->orderBy('vehicle_register_details.id','ASC');
            $vehicle_details = $customer_vehicles->get();

            $total_customer = People::select(DB::raw('count(people.id) as total_customers'));
            $total_customer->leftjoin('people_person_types','people_person_types.people_id','=','people.id');
            $total_customer->where('people.organization_id',$organization_id);
            $total_customer->where('people_person_types.person_type_id','=',2);
            $total_customers = $total_customer->first();

            $total_vehicle = VehicleRegisterDetail::select(DB::raw('count(vehicle_register_details.id)as total_vehicles'));
            $total_vehicle->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');
            $total_vehicle->where('vehicle_register_details.status','=',1);
            $total_vehicle->where('wms_vehicle_organizations.organization_id',$organization_id); 
            $total_vehicles = $total_vehicle->first();

            $total_sales = $vehicle_details->sum('total_sale');
        

            return response()->json(['name' => "Customers/Vehicles" ,'vehicle_details' =>$vehicle_details,'total_customer'=>$total_customers->total_customers,'total_vehicle'=>$total_vehicles->total_vehicles,'total_sales'=>$total_sales]);
        }
        else if($name == "Expense Report"){

                    
            $expenses_report = AccountEntry::select('account_entries.id','account_entries.voucher_no','account_entries.date','expenses.display_name','expenses_transactions.amount as exp_amount','hrm_employees.first_name','account_transactions.amount');
            $expenses_report->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
            $expenses_report->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
            $expenses_report->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
            $expenses_report->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
            $expenses_report->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');
            $expenses_report->where('account_entries.organization_id','=',$organization_id);
           /* $expenses_report->where('account_ledgers.name','=','Daily Expenses');*/
            $expenses_report->where(function($query){
               $query->where('expenses_transactions.expense_type',1)
               ->orWhere('expenses_transactions.expense_type',NULL);

           });
            $expenses_report->where('expenses_transactions.amount','!=','0.0');
            if(!empty($from_date) && !empty($to_date))
            {
            $expenses_report->wherebetween('account_entries.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $expenses_report->where('account_entries.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $expenses_report->where('account_entries.date','<=',$to_date);
            }
            $expenses_reports = $expenses_report->get();

            $total_expenses = AccountEntry::select(DB::raw('sum(expenses_transactions.amount) as total'));
            $total_expenses->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
            $total_expenses->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
            $total_expenses->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
            $total_expenses->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
            $total_expenses->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');
            $total_expenses->where('account_entries.organization_id','=',$organization_id);
          
            $total_expenses->where(function($query){
               $query->where('expenses_transactions.expense_type',1)
               ->orWhere('expenses_transactions.expense_type',NULL);

           });
            $total_expenses->where('expenses_transactions.amount','!=','0.0');
            if(!empty($from_date) && !empty($to_date))
            {
            $total_expenses->wherebetween('account_entries.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $total_expenses->where('account_entries.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $total_expenses->where('account_entries.date','<=',$to_date);
            }
            $total_expense = $total_expenses->first();


        

            return response()->json(['name' => "Expense Report" ,'expenses_reports' => $expenses_reports,'total_expenses'=>$total_expense->total]);
        }
        else if($name== "Daily Report By Type"){

            $daily_expenses_by_type=Expense::select('expenses.name',DB::raw("SUM(CASE WHEN account_entries.date BETWEEN '$from_date' AND '$to_date' THEN expenses_transactions.amount ELSE 0 END)as amount"))
            ->leftjoin('expenses_transactions','expenses.id','=','expenses_transactions.expense_id')
            ->leftjoin('account_transactions','account_transactions.id','=','expenses_transactions.reference_entry_id')
            ->leftjoin('account_entries','account_entries.id','=','account_transactions.entry_id')
            ->where('expenses.organization_id','=',$organization_id)
            ->wherebetween('account_entries.date',[$from_date,$to_date])
            ->orwhere('expenses.organization_id','=',$organization_id)
            ->groupBy('expenses_transactions.expense_id')
            ->get(); 


            $total_expenses = AccountEntry::select(DB::raw('sum(expenses_transactions.amount) as total'));
            $total_expenses->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id');
            $total_expenses->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id');
            $total_expenses->leftjoin('expenses_transactions','expenses_transactions.reference_entry_id','=','account_transactions.id');
            $total_expenses->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id');
            $total_expenses->leftjoin('hrm_employees','hrm_employees.id','=','expenses_transactions.employee');
            $total_expenses->where('account_entries.organization_id','=',$organization_id);
          
            $total_expenses->where(function($query){
               $query->where('expenses_transactions.expense_type',1)
               ->orWhere('expenses_transactions.expense_type',NULL);

           });
            $total_expenses->where('expenses_transactions.amount','!=','0.0');
            if(!empty($from_date) && !empty($to_date))
            {
            $total_expenses->wherebetween('account_entries.date',[$from_date,$to_date]);
            }
            if($request->input('from_date'))
            {
            $total_expenses->where('account_entries.date','>=',$from_date);
      
            }
            if($request->input('to_date'))
            {
            $total_expenses->where('account_entries.date','<=',$to_date);
            }
            $total_expense = $total_expenses->first();

            return response()->json(['name' => "Daily Report By Type" ,'expenses_reports_by_types' =>$daily_expenses_by_type,'total_expenses'=>$total_expense->total]);
     
        }
       else if($name == "Customer Report"){
            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
            $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;         

            $customer=Transaction::select('transactions.created_at','transactions.name as customer','customer_gropings.display_name as group_name','account_ledger_credit_infos.max_credit_limit AS credit_limit','people.mobile_no',DB::raw('IF(city.name IS NULL,citie.name,city.name)AS city'),DB::raw('SUM(CASE WHEN account_vouchers.code = "JI" OR account_vouchers.code = "JIC" THEN transactions.total ELSE 0 END)AS total_sale'),DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) )) AS pending"))

            ->leftjoin('people','people.id','=','transactions.people_id')        
          
            ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')

             ->leftJoin('account_ledgers', function($join) use($organization_id)
             {
                $join->on('people.person_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);

                $join->on('people.business_id', '=', 'account_ledgers.person_id')
                ->where('account_ledgers.organization_id', $organization_id);
                })
                ->leftjoin('account_ledger_credit_infos','account_ledgers.id','=','account_ledger_credit_infos.id')
                ->leftjoin('account_entries','account_entries.id','=','transactions.entry_id')

               ->leftjoin('person_communication_addresses', function($join){
                    $join->on('person_communication_addresses.person_id','=','people.person_id')
                    ->where('person_communication_addresses.address_type', '1');
     
                    })  
                ->leftjoin('business_communication_addresses', function($join){
                    $join->on('business_communication_addresses.business_id','=','people.business_id')
                    ->where('business_communication_addresses.address_type', '1');
                }) 
                ->leftjoin('cities AS city','city.id','=','person_communication_addresses.city_id')

                ->leftjoin('cities AS citie','citie.id','=','business_communication_addresses.city_id')
                ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')  
                ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')  
                ->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')     
                ->wherebetween('transactions.date',[$from_date,$to_date])
                ->where('transactions.date','>=',$from_date)  
                ->where('transactions.date','<=',$to_date)    
                ->where('transactions.organization_id',$organization_id) 
                ->where('account_voucher_types.name','job_invoice')
                ->where('transactions.approval_status',1)          
                ->groupby('transactions.name')
                ->get();     
                $customer_details = $customer;
             
                $tot_customer=Transaction::select('transactions.name')->where('organization_id',$organization_id)
                 ->wherebetween('transactions.date',[$from_date,$to_date])->groupby('transactions.name')
                 ->where('transactions.date','>=',$from_date)
                ->where('transactions.date','<=',$to_date)
                ->get();
                 $total_customer=count($customer_details);


                $total_sale= $customer_details->sum('total_sale');
                $pending_payment=$customer_details->sum('pending');

                return response()->json(['name' => "Customer Report" ,'customer_details' => $customer_details,'total_customer'=>$total_customer,'total_sale'=>$total_sale,'pending_payment'=>$pending_payment]); 
        }
        else if ($name == "Receivable Report") {

            $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
            $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
           
            $receivables=Transaction::select('transactions.name as customer_name','transactions.order_no as invoice_number',DB::raw('SUM(CASE WHEN account_vouchers.code = "JI" OR account_vouchers.code = "JIC" THEN transactions.total ELSE 0 END)AS total_sale'),
              DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) )) AS pending"))

                ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
                ->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id')  
                ->wherebetween('transactions.date',[$from_date,$to_date])
                ->where('transactions.date','>=',$from_date)  
                ->where('transactions.date','<=',$to_date)    
                ->where('transactions.organization_id',$organization_id) 
                ->where('account_voucher_types.name','job_invoice') 
                ->where('transactions.approval_status',1)
                ->groupby('transactions.order_no')
                ->havingRaw('pending > 0')           
                ->get();
      
        


            $total_sale= $receivables->sum('total_sale');
            $pending_payment= $receivables->sum('pending');


            $total_customer =count( $receivables->unique('customer_name'));


            
            return response()->json(['name'=>"Receivable Report",'receivables'=>$receivables,'total_customer'=>$total_customer,'total_sale'=>$total_sale,'pending_payment'=>$pending_payment]);       
        }
        else if($name =="Payable Report"){
            
            $transaction_cash = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;
            $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
            $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;

            $payable=Transaction::select('transactions.id',DB::raw('IF(people.display_name IS NULL,business.display_name,people.display_name) AS supplier'),'transactions.order_no as purchase_number',DB::raw('SUM(CASE WHEN account_vouchers.code = "PU"  THEN transactions.total ELSE 0 END)AS total_sale'),DB::raw("sum(IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) )) AS pending"));
            $payable->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
            $payable->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=','transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });                     
            $payable->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');  
            $payable->leftjoin('account_voucher_types','account_voucher_types.id','=','account_vouchers.voucher_type_id') ;    
            $payable->wherebetween('transactions.date',[$from_date,$to_date]);
            $payable->where('transactions.date','>=',$from_date);  
            $payable->where('transactions.date','<=',$to_date);  
            $payable->where('transactions.organization_id',$organization_id);
            $payable->where('account_voucher_types.name','purchase');
            $payable->where('transactions.approval_status',1);     
            $payable->groupby('transactions.people_id');           
            $payable->havingRaw('pending > 0');
            $payable_report= $payable->get();

            

            $total_supplier=$payable_report->count('id');
              
           
            $total_sale=$payable_report->sum('total_sale');
            $pending_payment=$payable_report->sum('pending');

            return response()->json(['name'=>"Payable Report",'payable_report'=>$payable_report,'total_supplier'=>$total_supplier,'total_sale'=>$total_sale,'pending_payment'=>$pending_payment]);
        }
        else if ($name == "Low Stock Report"){


            $low_stock=DB::table('inventory_items')->select('inventory_items.id as item_id','inventory_items.hsn','global_item_categories.name AS category_name','global_item_main_categories.name AS main_category_name','global_item_category_types.name AS type_name','global_item_makes.name AS make_name','global_item_models.identifier_a','inventory_items.purchase_price','inventory_items.selling_price','taxes.value AS tax_value','inventory_items.name as item_name','inventory_items.low_stock','inventory_item_stocks.in_stock AS in_stock','inventory_items.minimum_order_quantity as moq',DB::raw('(((inventory_items.selling_price *(CASE WHEN inventory_items.minimum_order_quantity IS NULL THEN 1  ELSE inventory_items.minimum_order_quantity  END))*taxes.value/100)+inventory_items.purchase_price)as total_purchase_price'),DB::raw('((((inventory_items.selling_price *(CASE WHEN inventory_items.minimum_order_quantity IS NULL THEN 1  ELSE inventory_items.minimum_order_quantity  END))*taxes.value/100)+inventory_items.purchase_price)*inventory_items.minimum_order_quantity)as total_amount'))
                    ->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
                    ->leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')
                    
                    ->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id')
                    ->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id')
                    ->leftjoin('global_item_types','global_item_models.type_id','=','global_item_types.id')
                    ->leftjoin('global_item_makes','global_item_models.make_id','=','global_item_makes.id')
                    ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')

                    ->leftjoin('group_tax','group_tax.group_id','=','inventory_items.purchase_tax_id')
                    ->leftjoin('tax_groups','tax_groups.id','=','inventory_items.purchase_tax_id')
                    ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
                    ->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id')
                    ->where('inventory_items.organization_id',$organization_id)
                     ->where('global_item_category_types.id','=','1')
                     ->whereColumn('inventory_items.low_stock','>=','inventory_item_stocks.in_stock')
                     ->groupby('inventory_items.id')
                     ->get();
              

                $total_items=$low_stock->count('item_id');
                $total_purchase_price=$low_stock->sum('total_purchase_price');

                $total_payment=$low_stock->sum('total_amount');       
                 
         
                    
               return response()->json(['name' => "All Low Stock Report" ,'low_stock' =>$low_stock,'total_items'=>$total_items,'total_purchase_price'=>$total_purchase_price,'total_payment'=>$total_payment]);
        }
        else if($name == "CompanyExpense Report")
        {
            $company_expenses_report = AccountEntry::select('expenses_transactions.id',
                'account_entries.voucher_no','account_entries.date','expenses_transactions.amount as expense_amount','account_transactions.amount','ledger_name.name as ledger_name','account_entries.reference_voucher as reference',
                DB::raw('(SELECT ac_entry.voucher_no AS vou FROM account_entries AS ac_entry
            LEFT JOIN expenses_transactions AS exp_tran ON exp_tran.reference_account_entry_id = ac_entry.id 
            LEFT JOIN account_vouchers AS ac_vouchers 
            ON `ac_vouchers`.`id` = `ac_entry`.`voucher_id` 
            WHERE exp_tran.reference_account_entry_id= expenses_transactions.reference_account_entry_id AND `ac_vouchers`.`code` = "CEX") AS expense_voucher'),
                'credit_account.display_name AS from_account','account_ledgers.display_name AS to_account')
             ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
             ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_transactions.entry_id')
             ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
             ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
             ->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id')
             ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
             ->leftjoin('account_ledgers AS ledger_name','ledger_name.id','=','expenses_transactions.reference_ledger_id')
             ->where('account_entries.organization_id',$organization_id)           
            
             ->where('expenses_transactions.expense_type',2)
             ->where('expenses_transactions.amount','!=',0.0)
             ->wherebetween('account_entries.date',[$from_date,$to_date])
             ->get();
           


            $company_total_expenses = AccountEntry::select('expenses_transactions.id',DB::raw('SUM(expenses_transactions.amount) as total_company_expense'),'account_entries.voucher_no','account_entries.date','expenses_transactions.amount as expense_amount','account_transactions.amount')
             ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
             ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_transactions.entry_id')
             ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
             ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.credit_ledger_id')
             ->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id')
             ->where('account_entries.organization_id',$organization_id)
             ->where('expenses_transactions.expense_type',2)
             ->where('expenses_transactions.amount','!=',0.0)
             ->wherebetween('account_entries.date',[$from_date,$to_date])
             ->first();

           
            return response()->json(['name' => "CompanyExpense Report" ,'company_expenses_report' => $company_expenses_report,'total_company_expenses'=>$company_total_expenses->total_company_expense]);
        }
        else if($name == "Company Expenses By Type")
        {
            $company_expenses_report = AccountEntry::select('expenses_transactions.id',
            'ledger_name.name as ledger_name',
            DB::raw('sum(expenses_transactions.amount) as amount'))
             ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
             ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_transactions.entry_id')
             ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
             ->leftjoin('account_ledgers AS credit_account','credit_account.id','=','account_transactions.credit_ledger_id')
             ->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id')
             ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.debit_ledger_id')
             ->leftjoin('account_ledgers AS ledger_name','ledger_name.id','=','expenses_transactions.reference_ledger_id')
             ->where('account_entries.organization_id',$organization_id)
             ->where('expenses_transactions.expense_type',2)
             ->where('expenses_transactions.amount','!=',0.0)
             ->wherebetween('account_entries.date',[$from_date,$to_date])
             ->groupBy('ledger_name.id')
             ->get();

             $company_total_expenses = AccountEntry::select('expenses_transactions.id',DB::raw('SUM(expenses_transactions.amount) as total_company_expense'),'account_entries.voucher_no','account_entries.date','expenses_transactions.amount as expense_amount','account_transactions.amount')
             ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
             ->leftjoin('expenses_transactions','expenses_transactions.entry_id','=','account_transactions.entry_id')
             ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
             ->leftjoin('account_ledgers','account_ledgers.id','=','account_transactions.credit_ledger_id')
             ->leftjoin('expenses','expenses.id','=','expenses_transactions.expense_id')
             ->where('account_entries.organization_id',$organization_id)
             ->where('expenses_transactions.expense_type',2)
             ->where('expenses_transactions.amount','!=',0.0)
             ->wherebetween('account_entries.date',[$from_date,$to_date])
             ->first();

              return response()->json(['name' => "Company Expenses By Type" ,'company_expenses_report' =>$company_expenses_report,'total_company_expenses'=>$company_total_expenses->total_company_expense]);
        }

       
    }
     public function today_summary_report()
    {       
        $organization_id = Session::get('organization_id');
            
        $branch = HrmBranch::where('organization_id', Session::get('organization_id'))->first()->branch_name;   

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

      
        $today = Carbon::today()->format('Y-m-d');
        $today_date = Carbon::today()->format('d-F-Y');

        $purchase = Transaction::select('global_item_category_types.name','transaction_items.quantity','transactions.order_no','transaction_items.amount',
             DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) *(CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END))AS tax_amount'),
            DB::raw('transaction_items.amount+SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * (CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END))AS total'))
            ->leftjoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id')
           ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
           ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
           ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
           ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')
           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')
           ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')
           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->where('transactions.organization_id', $organization_id)
           ->where('account_vouchers.name','purchases')
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->groupby('transaction_items.id')
           ->get();
           $total_purchase_goods_quantity=0;
           $total_purchase_service_quantity=0;
           $total_purchase_goods_amount=0;
           $total_purchase_service_amount=0;
           for ($i=0; $i<count($purchase); $i++)
           { 

                if($purchase[$i]->name=="goods")
                {
                    $total_purchase_goods_quantity+=$purchase[$i]->quantity;
                    $total_purchase_goods_amount+=$purchase[$i]->total;
                }
                else
                {                        
                    $total_purchase_service_quantity+=$purchase[$i]->quantity;
                    $total_purchase_service_amount+=$purchase[$i]->total;
                }
                  
            }

        $sales = Transaction::select('global_item_category_types.name','transaction_items.quantity','transactions.order_no','transaction_items.amount',
             DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) *(CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END))AS tax_amount'),
            DB::raw('transaction_items.amount+SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * (CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END))AS total'))
            ->leftjoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id')
           ->leftjoin('inventory_items', 'inventory_items.id', '=', 'transaction_items.item_id')
           ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
           ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
           ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')
           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')
           ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')
           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->where('transactions.organization_id', $organization_id)
            ->where(function($query){
               $query->where('account_vouchers.name','job_invoice_cash')
                        ->orWhere('account_vouchers.name','job_invoice')
                        ->orWhere('account_vouchers.name','sales')
                        ->orWhere('account_vouchers.name','sales_cash');

           })
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->groupby('transaction_items.id')
           ->get();

           $total_sales_goods_quantity=0;
           $total_sales_service_quantity=0;
           $total_sales_goods_amount=0;
           $total_sales_service_amount=0;
           for ($i=0; $i<count($sales); $i++)
           { 

                if($sales[$i]->name=="goods")
                {
                    $total_sales_goods_quantity+=$sales[$i]->quantity;
                    $total_sales_goods_amount+=$sales[$i]->total;
                }
                else
                {                        
                    $total_sales_service_quantity+=$sales[$i]->quantity;
                    $total_sales_service_amount+=$sales[$i]->total;
                }
                  
            }



          $jobcard_status= Transaction::select(DB::raw('SUM(CASE WHEN wms_transactions.jobcard_status_id = 1 || wms_transactions.jobcard_status_id = 2 || wms_transactions.jobcard_status_id = 3 THEN 1 ELSE 0 END) AS new_jobcard'),
            DB::raw('SUM(CASE WHEN wms_transactions.jobcard_status_id = 4 || wms_transactions.jobcard_status_id = 5 THEN 1 ELSE 0 END) AS progress_jobcard'),
            DB::raw('SUM(CASE WHEN wms_transactions.jobcard_status_id = 6 || wms_transactions.jobcard_status_id = 7 THEN 1 ELSE 0 END) AS ready_jobcard'),
            DB::raw('SUM(CASE WHEN wms_transactions.jobcard_status_id = 8 THEN 1 ELSE 0 END) AS closed_jobcard'))
          ->leftjoin('wms_transactions', 'wms_transactions.transaction_id', '=', 'transactions.id')
          ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
          ->where('transactions.organization_id', $organization_id)
          ->where('transactions.date',$today)
          ->where('account_vouchers.name','job_card')
          ->first();

      
        
         $estimations=Transaction::select(DB::raw('SUM(CASE WHEN transactions.approval_status = 0 THEN 1 ELSE 0 END) AS draft'),
            DB::raw('SUM(CASE WHEN transactions.approval_status = 1 THEN 1 ELSE 0 END) AS Approved'))
         ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
          ->where('transactions.organization_id', $organization_id)
          ->where('transactions.date',$today)
          ->where(function($query){
                    $query->where('account_vouchers.name','job_request')
                        ->orwhere('account_vouchers.name','estimation');
                        
                        

           })
          ->first();

         $invoices=Transaction::select(DB::raw('SUM(CASE WHEN transactions.approval_status = 0 THEN 1 ELSE 0 END) AS draft'),
            DB::raw('SUM(CASE WHEN transactions.approval_status = 1 THEN 1 ELSE 0 END) AS Approved'),
            DB::raw('SUM(CASE WHEN transactions.transaction_type_id = 29 THEN 1 ELSE 0 END) AS wms_credit'),
            DB::raw('SUM(CASE WHEN transactions.transaction_type_id = 20 THEN 1 ELSE 0 END) AS trade_credit')
         )
         ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
          ->where('transactions.organization_id',$organization_id)
          ->where('transactions.date',$today)
          ->where(function($query){
               $query->where('account_vouchers.name','job_invoice_cash')
                        ->orWhere('account_vouchers.name','job_invoice')
                        ->orWhere('account_vouchers.name','sales')
                        ->orWhere('account_vouchers.name','sales_cash');

           })
          ->first();


        $purchases=Transaction::select(DB::raw('SUM(CASE WHEN transactions.approval_status = 0 THEN 1 ELSE 0 END) AS draft'),
            DB::raw('SUM(CASE WHEN transactions.approval_status = 1 THEN 1 ELSE 0 END) AS Approved'),
            DB::raw('SUM(CASE WHEN transactions.term_id != 1 THEN 1 ELSE 0 END) AS credit_bill')

         )
         ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
         ->leftjoin('terms','terms.id','=','transactions.term_id')
          ->where('transactions.organization_id', $organization_id)
          ->where('transactions.date',$today)
          ->where('account_vouchers.name','purchases')
          ->first();


          $invoice_credit = Transaction::select('transaction_items.quantity','transactions.order_no','transaction_items.amount',
        DB::raw('transaction_items.amount+SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * (CASE WHEN taxes.value IS NULL THEN 0 ELSE taxes.value / 100 END))AS total'))
            ->leftjoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id')
           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')
           ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')
           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->where('transactions.organization_id', $organization_id)
          ->where(function($query){
               $query->Where('account_vouchers.name','job_invoice')
                        ->orWhere('account_vouchers.name','sales');
                        

           })
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->groupby('transaction_items.id')
           ->get();
           
            $invoice_credit_total=0;
           foreach ($invoice_credit as $key => $value) {
                 
                 $invoice_credit_total+=$value->total; 
           }
     

        $invoice_credit_pending = Transaction::select(DB::raw('SUM(transactions.total) as total'))
            
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->where('transactions.organization_id', $organization_id)
          ->where(function($query){
               $query->Where('account_vouchers.name','job_invoice')
                        ->orWhere('account_vouchers.name','sales');
                        

           })
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->first();

        $purchase_credit = Transaction::select('transaction_items.quantity','transactions.order_no','transaction_items.amount',
            DB::raw('SUM(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'))
            ->leftjoin('transaction_items', 'transaction_items.transaction_id', '=', 'transactions.id')
           ->leftjoin('group_tax','group_tax.group_id','=','transaction_items.tax_id')
           ->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id')
           ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->leftjoin('terms','terms.id','=','transactions.term_id')
           ->where('transactions.term_id','!=',1)
           ->where('transactions.organization_id', $organization_id)
           ->where('account_vouchers.name','purchases')
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->groupby('transaction_items.id')
           ->get();
            $purchase_credit_total=0;
           foreach ($purchase_credit as $key => $value) {
                 
                 $purchase_credit_total+=$value->amount+$value->tax_amount; 
           }
           $purchase_credit_pending = Transaction::select(DB::raw('SUM(transactions.total) as total'))
            ->leftjoin('terms','terms.id','=','transactions.term_id')
           ->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
           ->where('transactions.organization_id', $organization_id)
           ->where('transactions.term_id','!=',1)
          ->where('account_vouchers.name','purchases')
           ->where('transactions.approval_status',1)
           ->where('transactions.date',$today)
           ->first();


        $wms_receipt_payment=AccountEntry::select(DB::raw('SUM(CASE WHEN account_vouchers.name = "receipt" || account_vouchers.name = "wms_receipt" THEN account_transactions.amount ELSE 0 END) AS wms_receipt'),
            DB::raw('SUM(CASE WHEN account_vouchers.name = "payment" THEN account_transactions.amount ELSE 0 END) AS payment'))
        ->leftjoin('account_vouchers','account_vouchers.id','=','account_entries.voucher_id')
        ->leftjoin('account_transactions','account_transactions.entry_id','=','account_entries.id')
        ->where('account_entries.date',$today)
        ->where('account_entries.organization_id', $organization_id)
        ->first();
       

        $from = new Carbon('first day of this month');
        $from_date_to_show = $from->format('d-m-Y');
        $from_date = $from->format('Y-m-d');
        $to =Carbon::today();
        $to_date_to_show = $to->format('d-m-Y');
        $to_date = $to->format('Y-m-d');
        $cash_icici = DB::select("SELECT 
                        account_ledgers.id AS ledger_id,
                        account_groups.id AS group_id,
                        IF(
                            parent_groups.id IS NULL,
                            account_groups.id,
                            parent_groups.id
                        ) AS parent_group_id,
                        account_groups.account_head,
                        account_groups.display_name AS group_name,
                        IF(
                            parent_groups.display_name IS NULL,
                            account_groups.display_name,
                            parent_groups.display_name
                        ) AS parent_name,
                        account_ledgers.display_name AS ledger_name,
                        account_ledgers.group_id AS ledger_group_id,
                        account_groups.parent_id,
                        parent_groups.parent_id AS group_parent_id,
                        credit_account.credit,
                        debit_account.debit,
                        IF(
                        opening_balance_type = 'Debit',
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(debit_account.debit, 0) - COALESCE(credit_account.credit, 0)
                        ) - opening_balance
                      ) AS closing_balance,
                    IF(
                        opening_balance_type = 'Credit',
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) + opening_balance,
                        (
                          COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
                        ) - opening_balance
                      ) AS closing_balance1,
                    opening_balance_type 
                    FROM
                    account_ledgers 
                    LEFT JOIN persons 
                        ON account_ledgers.person_id = persons.id 
                    LEFT JOIN businesses 
                        ON account_ledgers.business_id = businesses.id 
                    LEFT JOIN account_transactions 
                        ON account_transactions.debit_ledger_id = account_ledgers.id 
                    LEFT JOIN account_groups 
                        ON account_groups.id = account_ledgers.group_id 
                    LEFT JOIN account_groups AS parent_groups 
                        ON parent_groups.id = account_groups.parent_id 
                    LEFT JOIN account_heads 
                        ON account_heads.id = account_groups.account_head 
                    LEFT JOIN 
                        (SELECT 
                            account_transactions.credit_ledger_id AS cr,
                            MIN(account_entries.date) AS cr_date,
                            SUM(account_transactions.amount) AS credit 
                            FROM
                            account_transactions 
                            LEFT JOIN account_entries 
                                ON account_transactions.entry_id = account_entries.id 
                            LEFT JOIN account_vouchers 
                                ON account_vouchers.id = account_entries.voucher_id 
                            WHERE  account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY cr) AS credit_account 
                            ON credit_account.cr = account_ledgers.id 
                        LEFT JOIN 
                            (SELECT 
                            account_transactions.debit_ledger_id AS dr,
                            MIN(account_entries.date) AS dr_date,
                            SUM(account_transactions.amount) AS debit 
                            FROM
                            account_transactions 
                            LEFT JOIN account_entries 
                                ON account_transactions.entry_id = account_entries.id 
                            LEFT JOIN account_vouchers 
                                ON account_vouchers.id = account_entries.voucher_id 
                            WHERE account_entries.organization_id = '".$organization_id."'
                            AND account_entries.status = 1 
                            AND account_vouchers.name != 'stock_journal' 
                            GROUP BY dr) AS debit_account 
                            ON debit_account.dr = account_ledgers.id 
                        WHERE account_ledgers.organization_id = '".$organization_id."' 
                        AND (
                                account_groups.name = 'cash'
                                OR account_groups.name = 'bank_account'
                              )
                        GROUP BY  account_ledgers.id");

        
        return view('trade_wms.today_summary_report',compact('branch','state','city','title','payment','terms','group_name','today_date','total_purchase_goods_quantity','total_purchase_service_quantity','total_purchase_goods_amount','total_purchase_service_amount','total_sales_goods_quantity','total_sales_service_quantity','total_sales_goods_amount','total_sales_service_amount','jobcard_status','estimations','invoices','invoice_credit_total','invoice_credit_pending','purchases','purchase_credit_total','purchase_credit_pending','wms_receipt_payment','cash_icici'));

             
    }
}
