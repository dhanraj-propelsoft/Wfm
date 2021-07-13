<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountVoucher;
use App\Transaction;
use App\ReferenceVoucher;
use App\People;
use App\AccountGroup;
use App\AccountLedger;
use App\HrmEmployee;
use App\ShipmentMode;
use App\InventoryItem;
use App\TaxGroup;
use App\Discount;
use App\Weekday;
use App\Country;
use App\State;
use App\PeopleTitle;
use App\PaymentMethod;
use App\Term;
use App\VehicleMake;
use App\JobType;
use App\BusinessAddressType;
use App\Organization;
use App\BusinessCommunicationAddress;
use App\FieldType;
use App\TransactionField;
use App\Custom;
use Session;
use Auth;
use DB;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->person_id;

        $bill = Transaction::select('transactions.id', 'transactions.order_no', 'businesses.alias AS business', 'people.display_name AS people', 'transactions.total', DB::raw('COALESCE(personal_transactions.reference_id, "0") AS reference_id'), DB::raw('DATE_FORMAT(transactions.date, "%d %b, %Y") AS date'), DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS original_date'), DB::raw('"0.00" AS balance_due'), DB::raw('"1" AS status'), 'transactions.organization_id');
        $bill->leftJoin('people', function($join)
        {
            $join->on('people.person_id','=', 'transactions.people_id')
            ->where('transactions.user_type', '0');
        });

        $bill->leftjoin('organizations','organizations.id','=','transactions.organization_id');
        $bill->leftjoin('businesses','businesses.id','=','organizations.business_id');
        $bill->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');

        $bill->leftJoin('personal_transactions', function($join)
        {
            $join->on('personal_transactions.reference_id','=', 'transactions.id')
            ->where('personal_transactions.type', 'transaction');
        });

        $bill->where('people.person_id', $user_id);
        $bill->whereIn('account_vouchers.name', ['sales_cash', 'sales']);
        $bill->groupby('transactions.id');
        $bill->orderby('transactions.date','desc');
        $bills = $bill->get();

        return view('personal.bills', compact('bills'));
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
    public function show($id, $organization)
    {
        $transactions = Transaction::where('id', $id)->first();

        $organization_id = $transactions->organization_id;

        $reference_vouchers = ReferenceVoucher::select('id', 'name', 'display_name');

        $selected_reference_voucher = null;
       
        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', $organization_id);

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', $organization_id);

        

        $transaction_type = AccountVoucher::findOrFail($transactions->transaction_type_id);

        $type = $transaction_type->name;
        $job = null;

        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

        $voucher_no = $transactions->order_no;

        $sale_account = AccountGroup::where('name', 'sale_account')->where('organization_id', $organization_id)->first()->id;

        $account_ledgers = AccountLedger::where('group_id', $sale_account)->where('organization_id', $organization_id)->pluck('name', 'id');
        $account_ledgers->prepend('Select Account', '');

        $employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))->where('organization_id', $organization_id)->pluck('name', 'id');
        $employees->prepend('Select Sales Person', '');


        $shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
        $shipment_mode->prepend('Select Shipment Mode', '');

        $items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')
        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
        ->where('inventory_items.organization_id', $organization_id)
        ->orderby('global_item_categories.display_name')
        ->get();

        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'));
        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);


        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('organization_id', $organization_id);

        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }
        $days[0] = "Last";

        if($transaction_type == null) abort(404);

        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');

        $payment = PaymentMethod::where('organization_id', $organization_id)->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $voucher_terms = Term::select('id', 'name', 'display_name', 'days')->where('organization_id', $organization_id)->get();

        $terms = Term::select('id', 'display_name')->where('organization_id', $organization_id)->pluck('display_name', 'id');
        $terms->prepend('Select Term','');

        $make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
        $make->prepend('Select Make', '');

        $job_type = JobType::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');

        $address_type = BusinessAddressType::where('name', 'business')->first();

        $business_id = Organization::find($organization_id)->business_id;

        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        ->leftjoin('states', 'cities.state_id', '=', 'states.id')
        ->where('address_type', $address_type->id)
        ->where('business_id', $business_id)
        ->first();

        $date_label = null;
        $due_date_label = null;
        $term_label = null;
        $order_type = null;
        $address_label = null;
        $order_type_value = [];
        $order_label = null;
        $payment_label = null;
        $sales_person_label = null;
        $include_tax_label = null;
        $customer_type_label = null;
        $customer_label = null;
        $discount_option = false;
        $person_type = null;
        $due_date = null;
        $transaction_address_type = null;
        $company_label = false;
        $company_name = null;
        $company_email = null;
        $company_mobile = null;
        $company_address = null;

        $business_company_address = $business_communication_address->address;

        if($business_communication_address->address != "" && $business_communication_address->city != "") {
            $business_company_address .= "\n";
        }

        $business_company_address .= $business_communication_address->city;

        if($business_communication_address->city != "" && $business_communication_address->state != "") {
            $business_company_address .= "\n";
        }

        $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;
    

        switch($type) {
            case 'estimation':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Expiry Date';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $due_date = Carbon::now()->addDays(30)->format('d-m-Y');
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sale_order':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Select Type', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'sales':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'sale_order', 'estimation'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Select Type', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'sales_cash':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'job_card':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'estimation'))->where('status', 1)->orderby('id')->get();
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'delivery_note':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('sale_order', 'sales'))->where('status', 1)->orderby('name', 'desc')->get();
                $order_label = 'Order#';
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sale_order', 'sales'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
                $discount_option = true;
            break;
            case 'receipt':
                $address_label = 'Customer Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('sales'))->where('status', 1)->orderby('id')->get();
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'payment':
                $address_label = 'Vendor Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('purchases'))->where('status', 1)->orderby('id')->get();
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_label = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'credit_note':
                $address_label = 'Customer Address';
                $order_type = "Order Type";
                $reference_voucher = $reference_vouchers->whereIn('name', array('sales', 'delivery_note'))->where('status', 1)->orderby('id')->get();
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('sales', 'delivery_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Sales Person';
                $date_label = 'Date';
                $customer_type_label = 'Customer Type';
                $customer_label = 'Customer';
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_sales', '1');
                $discount->where('is_sales', '1');
            break;
            case 'purchase_order':
                $address_label = 'Vendor Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct'))->where('status', 1)->orderby('id')->get();
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'purchases':
                $address_label = 'Vendor Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('direct', 'purchase_order'))->where('status', 1)->orderby('id')->get();
                $due_date_label = 'Due Date';
                $term_label = 'Terms';
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'estimation'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Select Type', '');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'debit_note':
                $address_label = 'Vendor Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('purchases', 'goods_receipt_note'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchases', 'goods_receipt_note'))->where('organization_id', $organization_id)->orderby('name', 'desc')->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Created By';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
            case 'goods_receipt_note':
                $address_label = 'Vendor Address';
                $reference_voucher = $reference_vouchers->whereIn('name', array('purchase_order', 'purchases'))->where('status', 1)->orderby('id')->get();
                $order_type = "Order Type";
                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('purchase_order', 'purchases'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_label = 'Order#';
                $payment_label = 'Payment Method';
                $date_label = 'Date';
                $customer_type_label = 'Vendor Type';
                $customer_label = 'Vendor';
                $person_type = "vendor";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Vendor', '');
                $business->prepend('Select Business', '');
                $tax->where('tax_groups.is_purchase', '1');
                $discount->where('is_purchase', '1');
                $discount_option = true;
                $company_name = $business_communication_address->placename;
                $company_email = $business_communication_address->email_address;
                $company_mobile = $business_communication_address->mobile_no;
                $company_address = $business_company_address;
            break;
        }

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();
        
        $discounts = $discount->get();

        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')
        ->get();

        $transaction_fields = TransactionField::select('transaction_fields.id', 'transaction_fields.name', 'field_formats.name as field_format', 'field_types.name as field_type', 'transaction_fields.field_format_id', 'transaction_fields.field_type_id', DB::Raw('GROUP_CONCAT(group_fields.name SEPARATOR "`")as group_name'), 'transaction_fields.sub_heading')
        ->leftjoin('field_formats', 'field_formats.id', '=', 'transaction_fields.field_format_id')
        ->leftjoin('field_types', 'field_types.id', '=', 'transaction_fields.field_type_id')
        ->leftjoin('transaction_fields as group_fields', 'group_fields.group_id', '=', 'transaction_fields.id')
        ->where('transaction_fields.transaction_type_id', $transaction_type->id)
        ->where('transaction_fields.status', 1)
        ->groupby('transaction_fields.id')
        ->orderby('transaction_fields.sub_heading')
        ->get();
        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

        $selected_make = null;

        $model = ['' => 'Select Model'];

        return view('personal.bills_edit', compact('people', 'business', 'voucher_no', 'account_ledgers', 'employees', 'shipment_mode', 'items', 'taxes', 'discounts', 'transaction_type', 'state', 'title', 'payment', 'terms', 'voucher_terms', 'weekdays', 'days', 'weekday', 'type', 'due_date_label', 'term_label', 'order_label', 'payment_label', 'sales_person_label', 'include_tax_label', 'date_label', 'customer_type_label', 'customer_label', 'person_type', 'field_types', 'transaction_fields', 'make', 'selected_make', 'model', 'job', 'job_type', 'sub_heading', 'discount_option', 'due_date', 'order_type', 'order_type_value', 'address_label', 'transaction_address_type', 'company_name', 'company_email', 'company_mobile', 'company_address', 'company_label', 'reference_voucher','selected_reference_voucher', 'transactions', 'id','reference_vouchers', 'organization'));
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
