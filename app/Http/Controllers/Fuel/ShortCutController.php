<?php

namespace App\Http\Controllers\Fuel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Weekday;
use App\InventoryItem;
use App\Organization;
use App\user;
use App\FsmTank;
use App\FsmPumpMechine;
use App\FsmDipMeasurement;
use App\FsmPump;
use App\FsmDipReading;
use App\HrmEmployee;
use App\FsmShiftCashManage;
use App\FuelCreditSales;
use App\HrmShift;
use App\Transaction;
use App\AccountVoucher;
use App\AccountVoucherType;
use App\ReferenceVoucher;
use App\VehicleRegisterDetail;
use App\VehicleJobcardStatus;
use App\VehicleVariant;
use App\People;
use App\TransactionField;
use App\FieldType;
use App\PaymentMode;
use App\PaymentTerm;
use App\Discount;
use App\TransactionItem;
use App\TaxGroup;
use App\BusinessCommunicationAddress;
use App\BusinessAddressType;
use App\Business;
use App\Person;
use App\WmsTransaction;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Session;
use App\State;
use App\Term;
use App\City;
use App\PeopleTitle;
use App\Country;
use App\CustomerGroping;
use Carbon\Carbon;
use DB;
use App\Custom;

class ShortCutController extends Controller
{
   
    public function easy_way_index()
    {
         $organization_id = Session::get('organization_id');

        $pump=FsmPump::select('fsm_pumps.id','fsm_pumps.name')
                ->where('organization_id',$organization_id)
                ->get();
                //dd($pump);
             
              
         $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');


        
        $type='job_invoice';
        $module_name=Session::get('module_name');
       
        
        $organization_id = Session::get('organization_id');
      
        $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');
        $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
          
        $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
          
        $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
          
        $transaction_types->where('account_vouchers.organization_id', $organization_id);
        if(Session::get('module_name') != null) {
              $transaction_types->where('modules.name', Session::get('module_name'));
          }
        $transaction_types->where('account_vouchers.name', $type);

        $transaction_type = $transaction_types->first();

   

        if($transaction_type == null) abort(404);

       
        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        $reference_type = ReferenceVoucher::where('name', 'purchases')->first()->id;

        
        if($transaction_type->module == "trade" || $transaction_type->module == "inventory" )
        {
        $transaction_sales = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'sales_cash')->where('organization_id', $organization_id)->first()->id;

        }

        if($transaction_type->module == "trade_wms" || $transaction_type->module == "fuel_station")
        {
        $transaction_sales = AccountVoucher::where('name', 'job_invoice')->where('organization_id', $organization_id)->first()->id;

        $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;

        }
        


        if($type == "purchases") {
            $cash_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'debit_note')->where('organization_id', $organization_id)->first()->id;
        } 
        else if($type == "sales" || $type == "sales_cash") {
            $cash_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        else if($type == "job_invoice" || $type == "job_invoice_cash") {
            $cash_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
            $return_voucher = AccountVoucher::where('name', 'credit_note')->where('organization_id', $organization_id)->first()->id;
        }
        
        

        $payment_voucher = AccountVoucher::where('name', 'payment')->where('organization_id', $organization_id)->first()->id;   

        if($transaction_type->module != "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'receipt')->where('organization_id', $organization_id)->first()->id;
        }

        if($transaction_type->module == "trade_wms")
        {
            $receipt_voucher = AccountVoucher::where('name', 'wms_receipt')->where('organization_id', $organization_id)->first()->id;
        }


        $transaction_cash = AccountVoucher::where('name', 'job_invoice_cash')->where('organization_id', $organization_id)->first()->id;
        $journal_voucher = AccountVoucher::where('name', 'journal')->where('organization_id', $organization_id)->first()->id;
        $cash_voucher = 0;
        $return_voucher = 0;

        
        $transaction = Transaction::select('transactions.id', 'transactions.order_no','transactions.approved_on','vehicle_register_details.id as vehicle_id','vehicle_register_details.registration_no',
            DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 1, CASE  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) = 0  THEN 1   WHEN transactions.due_date < CURDATE()  THEN 3  WHEN (transactions.total - SUM((SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)))) > 0  THEN 2 ELSE 0  END  ) AS status"),  'transactions.approval_status', 'transactions.transaction_type_id',
            DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),
            DB::raw("DATE_FORMAT(transactions.date, '%d %b, %Y') as date"), 
            DB::raw("DATE_FORMAT(transactions.due_date, '%d %b, %Y') as due_date"),'transactions.date as original_date', 'transactions.due_date as original_due_date','transactions.total',
             DB::raw('COALESCE(reference_vouchers.display_name, "Direct") as reference_type'),'vehicle_register_details.registration_no','hrm_employees.first_name AS assigned_to','service_types.name as service_type','vehicle_jobcard_statuses.name as jobcard_status','wms_transactions.name as name_of_job','wms_transactions.job_date','wms_transactions.job_due_date','wms_transactions.job_completed_date',DB::raw(" IF(transactions.transaction_type_id = $transaction_cash, 0, IF( (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) IS NULL,  transactions.total,  transactions.total -  (SELECT SUM(account_transactions.amount) FROM account_entries LEFT JOIN account_transactions ON account_transactions.entry_id = account_entries.id WHERE account_entries.reference_transaction_id = transactions.id AND account_entries.voucher_id IN ($journal_voucher, $cash_voucher, $return_voucher)) ) ) AS balance")); 
            
        

           $transaction->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'transactions.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('transactions.user_type', '0');
            });
           $transaction->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'transactions.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('transactions.user_type', '1');
            });
        

          $transaction->leftjoin('transactions AS reference_transactions','transactions.reference_id','=','reference_transactions.id');


          $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

          $transaction->leftJoin('wms_transactions', 'transactions.id', '=', 'wms_transactions.transaction_id');

          $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'wms_transactions.jobcard_status_id');

          $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

          $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'wms_transactions.registration_id');

          $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'wms_transactions.assigned_to');


          $transaction->where('transactions.organization_id', $organization_id);

          if($transaction_type->name == "sales" || $transaction_type->name == "sales_cash" || $transaction_type->name == "job_invoice" || $transaction_type->name == "job_invoice_cash") {
              
              $transaction->where(function ($query) use ($transaction_sales, $transaction_cash) {
                  $query->where('transactions.transaction_type_id', '=', $transaction_sales)
                        ->orWhere('transactions.transaction_type_id', '=', $transaction_cash);
          });

          } 
          else {
              $transaction->where('transactions.transaction_type_id',$transaction_type->id);
          }
          $transaction->whereNull('transactions.deleted_at');
          $transaction->where('transactions.notification_status','!=',2);
          $transaction->where('transactions.status','!=',1);
          $transaction->groupby('transactions.id');
          $transaction->orderBy('transactions.updated_at','desc');
          $transactions = $transaction->get();
           //dd(  $transactions);
            $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');
    
        $title = PeopleTitle::pluck('display_name','id');
        $title->prepend('Title','');
    
        $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');
    
    
        $terms = Term::select('id', 'display_name')->where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Term','');  
    
        $group_name = CustomerGroping::where('organization_id',$organization_id)->pluck('display_name','id');
            $group_name->prepend('Select Group Name',''); 
           


        return view('fuel_station.fsm_shortcut_index',compact('pump','vehicles_register','transactions','state', 'title', 'payment', 'terms','group_name'));
    }

    
     public function shortcut_invoice_create($id)
    {
        //dd($id);
       
        $pump_id=$id;
       $organization_id = Session::get('organization_id');
        $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }
        $days[0] = "Last";

        $mytime =Date('H:i:s');

      

      $person_id = VehicleRegisterDetail::select('people.person_id');     
        

     $person_id->leftJoin('people', function($join) use($organization_id)
            {
                $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                ->where('people.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '0');
            });

      $person_id->leftJoin('people AS business', function($join) use($organization_id)
            {
                $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                ->where('business.organization_id', $organization_id)
                ->where('vehicle_register_details.user_type', '1');
        });
        $person_id->where('registration_no','ZZZZ0000');
        $person_id->leftjoin('wms_vehicle_organizations','wms_vehicle_organizations.vehicle_id','=','vehicle_register_details.id');

        $person_id->where('vehicle_register_details.status', '1');

        $person_id->where('wms_vehicle_organizations.organization_id', $organization_id);

        $person_id->orderby('vehicle_register_details.id');
        

        $person_id = $person_id->first();
       $person_id= $person_id->person_id;
       //dd( $person_id);
        $customer_label = 'Customer';

        $type='job_invoice_cash';
      
         $organization_id = Session::get('organization_id');

         $vehicles_register = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
        $vehicles_register->prepend('Select Vehicle', '');
      

         $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
        $job_card_status->prepend('Select Jobcard Status', '');


        $job_status = VehicleJobcardStatus::where('name', 'New')->first()->id;

        $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

        $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
        ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
        ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
        ->where('account_vouchers.organization_id', $organization_id)
        ->where('modules.name', Session::get('module_name'))
        ->where('account_vouchers.name', $type)
        ->first();
      

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));
  
                 $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
              

                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
              
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
                $people->prepend('Select Customer', '');
                $business->prepend('Select Business', '');
                    $date = now()->format("Y-m-d ");            
         $shift=HrmShift::where('status',1)->where('organization_id', $organization_id)->pluck('name','id');
     
         
          $shifttime=FsmShiftCashManage::select('shift_id')->where('date',$date)->where('end_time','=',null)->first();
         
             if($shifttime!=null)
             {
              $shift_id=$shifttime->shift_id;
             }
             else
             {
              $shift_id='';
             }
             //dd($shift_id);

        $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');

        $pumpname=FsmPump::where('organization_id',$organization_id)->pluck('name','id');
     
        $pumplist=FsmPump::where('organization_id',$organization_id)->pluck('description','id');
       

        $payment = PaymentMode::where('display_name', 'Cash')->pluck('display_name','id');
    

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;
         $product=InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')->where('global_item_models.category_id',42)
          ->orWhere('global_item_models.category_id',43)
          ->where('inventory_items.organization_id',$organization_id)
          ->pluck('inventory_items.name','inventory_items.id');
      $product->prepend("Select product",'');


        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('status', 1)->where('organization_id', $organization_id);

        $discounts = $discount->get();

        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();
        $address_label='Customer Address';
        $company_label=true;
            $address_type = BusinessAddressType::where('name', 'business')->first();
            $business_id = Organization::find($organization_id)->business_id;

        $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
        ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
        ->leftjoin('states', 'cities.state_id', '=', 'states.id')
        ->where('address_type', $address_type->id)
        ->where('business_id', $business_id)
        ->first();

        if($business_communication_address != "") {
            $business_company_address = $business_communication_address->address;
        
            if($business_communication_address->address != "" && $business_communication_address->city != "") {
                $business_company_address .= "\n";
            }
    
            $business_company_address .= $business_communication_address->city;
    
            if($business_communication_address->city != "" && $business_communication_address->state != "") {
                $business_company_address .= "\n";
            }
    
            $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;
        }   


        $company_name = $business_communication_address->placename;
        $company_email = $business_communication_address->email_address;
        $company_mobile = $business_communication_address->mobile_no;
        $company_address = $business_company_address;

        $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();



        $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;



        $voucher_no = Custom::generate_accounts_number($type, $gen_no, false);

    $customer_type_label = 'Customer Type';

    $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')

        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')

        ->get();
        $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

         $mainproduct = InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')

                ->leftjoin('global_item_categories','global_item_categories.id','=','inventory_items.category_id')
                ->where('organization_id',$organization_id)

                ->pluck('inventory_items.name','inventory_items.id');
                 $mainproduct->prepend('select a item here','');
                
                

                       $uuid=Custom::GUID();

            $item_id=FsmTank::SELECT('fsm_tanks.product')
                   ->leftjoin('fsm_pumps','fsm_pumps.tank_id','=','fsm_tanks.id')
                   ->where('fsm_pumps.id',$id)
                   ->first();
                   $item_id=$item_id->product;
                
          $itemdetails = InventoryItem::select('inventory_items.id', 'inventory_items.name','inventory_items.is_group', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.selling_price', 'inventory_items.base_price', 'taxes.id as tax_id','tax_groups.id as tax_groupid')

        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

        ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

        ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

        ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')

      ->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')

      ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')     

      ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
      ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
      ->where('inventory_items.organization_id', $organization_id)
      ->where('inventory_items.id', $item_id)     
      ->first();
      $price=$itemdetails->selling_price;
      $quantity=1;
      $rate=$price*$quantity;
      $tax_id=$itemdetails->tax_id;
      $tax_groupid=$itemdetails->tax_groupid;
      //dd($tax_groupid);

      $tax_amount=TaxGroup::leftjoin('group_tax','group_tax.group_id','=','tax_groups.id')
                  ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
                  ->where('tax_groups.id',$tax_id)
                  ->sum('taxes.value');
                
      $total_taxamount=(float)$tax_amount/100*$price;
    // dd($total_taxamount);
   $total_amount=  $total_taxamount+$rate;

             $people_details = People::select('people.first_name', 'people.last_name', 'people.display_name', 'people.mobile_no', 'people.email_address', 'people_titles.id AS title_id', 'genders.id AS gender_id',  
        DB::raw('COALESCE(billing_city.name, "") AS billing_city'), 
        DB::raw('COALESCE(billing_state.name, "") as billing_state'),  
        DB::raw('COALESCE(billing_address.address, "") as billing_address'), 
        DB::raw('COALESCE(billing_address.pin, "") as billing_pin'), 
        DB::raw('COALESCE(billing_address.google, "") as billing_google'), 
        'billing_address.id AS billing_id', 
        DB::raw('COALESCE(shipping_city.name, "") AS shipping_city'), 
        DB::raw('COALESCE(shipping_state.name, "") as shipping_state'), 
        DB::raw('COALESCE(shipping_address.address, "") as shipping_address'), 
        DB::raw('COALESCE(shipping_address.pin, "") as shipping_pin'),  
        DB::raw('COALESCE(shipping_address.google, "") as shipping_google'), 
        'shipping_address.id AS shipping_id');
      $people_details->leftJoin('genders', 'genders.id', '=', 'people.gender_id');
      $people_details->leftJoin('people_titles', 'people_titles.id', '=', 'people.title_id');
      $people_details->leftJoin('people_addresses AS billing_address', function($join)
          {
              $join->on('billing_address.people_id', '=', 'people.id')
              ->where('billing_address.address_type', '0');
          });
      $people_details->leftJoin('people_addresses AS shipping_address', function($join)
          {
              $join->on('shipping_address.people_id', '=', 'people.id')
              ->where('shipping_address.address_type', '1');
          });   
   
        $people_details->where('people.person_id',$person_id);

     
      $people_details->leftjoin('cities AS billing_city','billing_address.city_id','=','billing_city.id');
        $people_details->leftjoin('states AS billing_state','billing_city.state_id','=','billing_state.id');
      $people_details->leftjoin('cities AS shipping_city','shipping_address.city_id','=','shipping_city.id');
        $people_details->leftjoin('states AS shipping_state','shipping_city.state_id','=','shipping_state.id');
          $people_details->where('people.organization_id', Session::get('organization_id'));
      
      $person = $people_details->first();
     // dd($person);
      $anonymous_vehicle=VehicleRegisterDetail::select('id')->where('registration_no','ZZZZ0000')->where('organization_id',$organization_id)->first();
     $anonymous_vehicle_id=$anonymous_vehicle->id;
     $vehicle_variants=VehicleVariant::select('vehicle_variants.vehicle_configuration','vehicle_register_details.driver','driver_mobile_no')
		 	->leftjoin('vehicle_register_details','vehicle_register_details.vehicle_variant_id','=','vehicle_variants.id')
		 
		 	->where('vehicle_register_details.id',$anonymous_vehicle_id)
		 	->first();
	$vehicle_configuration=$vehicle_variants->vehicle_configuration;

	$driver_name=$vehicle_variants->driver;

	$driver_number=$vehicle_variants->driver_mobile_no;

    
                    $items = InventoryItem::leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

                      ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
                       ->where(function($q) {
                         $q->where('global_item_models.category_id',44)
                         ->orWhere('global_item_models.category_id',45);
                        })
        
                        ->where('inventory_items.organization_id', $organization_id)
                       ->orderby('global_item_categories.display_name')
                       ->pluck('inventory_items.name','inventory_items.id');
                       //dd($items);
        
            
           
         $vehicle_data=VehicleRegisterDetail::select('vehicle_variants.vehicle_configuration','vehicle_register_details.user_type','vehicle_register_details.driver as driver_name','vehicle_register_details.driver_mobile_no as driver_number',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),DB::raw("IF(people.mobile_no IS NULL, business.mobile_no, people.mobile_no) as mobile_no"),DB::raw("IF(people.email_address IS NULL, business.email_address, people.email_address) as email_address"),'people_addresses.address','customer_gropings.display_name as group_name')

          ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id') 
          -> leftJoin('people', function($join) use($organization_id)
                {
                    $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('vehicle_register_details.user_type', '0');
                })

          ->leftJoin('people AS business', function($join) use($organization_id)
                {
                    $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                  
                    ->where('business.organization_id', $organization_id)
                    ->where('vehicle_register_details.user_type', '1');
            }) 
            ->leftjoin('people_addresses','people_addresses.people_id','=','people.id') 
            ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')
           
            ->where('vehicle_register_details.id',$anonymous_vehicle_id)     
             ->where('vehicle_register_details.organization_id',$organization_id)      
            ->first();
            //dd($vehicle_data);
             
       

        return view('fuel_station.shortcut_invoice_createby_pump',compact('pumplist','pump_id','uuid','weekday','weekdays','days','vehicles_register','job_card_status','job_status','transaction_type','customer_label','people','person_type','business','shift','employee','pumpname','payment','payment_terms','payment_term','product','discounts','taxes','address_label','company_label','company_name','company_email','company_mobile','company_address','transactions','voucher_no','customer_type_label','type','field_types','sub_heading','shifttime','mainproduct','items','item','selling_price','quantity','in_stock','anonymous_vehicle_id','person','person_id','itemdetails','rate','tax_id','total_taxamount','total_amount','tax_groupid','shift_id','vehicle_data','vehicle_configuration','driver_name','driver_number'));

    }
    public function shortcut_invoice_registernumber($id,$pump_id)
    {
      //dd($id,$pump_id);
       
        $vehicle_id=$id;
        $organization_id = Session::get('organization_id');
   
         $weekdays = Weekday::pluck('display_name','id');
        $weekday = Weekday::where('name','monday')->first()->id;
        $date = now()->format("Y-m-d ");         

        $days = [];
        for ($i=1; $i <= 28; $i++) { 
            $days[$i] = $i;
        }
        $days[0] = "Last";

        $mytime =Date('H:i:s');       

        $customer_label = 'Customer';

        $type='job_invoice_cash';
    
         $organization_id = Session::get('organization_id');

         $vehicles_register = VehicleRegisterDetail::select('id')->where('organization_id', $organization_id)->where('id',$vehicle_id)->first();
       
         $vehicles_register =$vehicles_register->id;

         $job_card_status = VehicleJobcardStatus::where('status', '1')->pluck('name', 'id');
         $job_card_status->prepend('Select Jobcard Status', '');


         $job_status = VehicleJobcardStatus::where('name', 'New')->first()->id;

         $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));

           $transaction_type = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module')
          ->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id')
          ->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id')
          ->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id')
          ->where('account_vouchers.organization_id', $organization_id)
          ->where('modules.name', Session::get('module_name'))
          ->where('account_vouchers.name', $type)
          ->first();
       

            $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));
         
                 $address_label = 'Customer Address';
                $service_type_label = 'Service Type';
               
                $order_type = "Order Type";

                $order_type_value = AccountVoucher::select('display_name', 'name')->whereIn('name', array('job_card'))->where('organization_id', $organization_id)->pluck('display_name', 'name');
                $order_type_value->prepend('Direct', '');

                $due_date_label = 'Payment Due Date';
                $term_label = 'Payment Terms';
                $order_type = "Order Type";
                $order_label = 'Job Card Number#';
                $payment_label = 'Payment Method';
                $sales_person_label = 'Invoice By';
                $date_label = 'Invoice Date';
                $customer_type_label = 'Customer Type';
              
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
                $business = $business_list->pluck('name', 'id');
              
                $business->prepend('Select Business', '');
                               
             $shift=HrmShift::where('organization_id', $organization_id)
                              ->pluck('name','id');

            $shifttime=FsmShiftCashManage::select('shift_id')->where('date',$date)->where('end_time','=',null)->first();
         
             if($shifttime!=null)
             {
              $shift_id=$shifttime->shift_id;
             }
             else
             {
              $shift_id='';
             }

           // dd($shift_id);

            $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');

            $pumpname=FsmPump::where('organization_id',$organization_id)->where('id',$pump_id)->pluck('name','id');     

            $payment = PaymentMode::where('display_name', 'Cash')->pluck('display_name','id');


            $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
            $payment_terms->prepend('Select Payment Term ','');

            $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;

            $product=InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')->where('global_item_models.category_id',42)
              ->orWhere('global_item_models.category_id',43)
              ->where('inventory_items.organization_id',$organization_id)
              ->pluck('inventory_items.name','inventory_items.id');
          $product->prepend("Select product",'');

            $discount = Discount::select('id', 'display_name', 'value');
            $discount->where('status', 1)->where('organization_id', $organization_id);

            $discounts = $discount->get();

            $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

            $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
            $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
            $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
            $tax->where('tax_groups.organization_id', $organization_id);

            $tax->groupby('tax_groups.id');
            $taxes = $tax->get();
            $address_label='Customer Address';
            $company_label=true;
                $address_type = BusinessAddressType::where('name', 'business')->first();
                $business_id = Organization::find($organization_id)->business_id;

            $business_communication_address = BusinessCommunicationAddress::select('business_communication_addresses.placename', 'business_communication_addresses.mobile_no', 'business_communication_addresses.email_address', 'business_communication_addresses.address', 'cities.name AS city', 'states.name AS state', 'business_communication_addresses.pin')
            ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
            ->leftjoin('states', 'cities.state_id', '=', 'states.id')
            ->where('address_type', $address_type->id)
            ->where('business_id', $business_id)
            ->first();

            if($business_communication_address != "") {
                $business_company_address = $business_communication_address->address;
            
                if($business_communication_address->address != "" && $business_communication_address->city != "") {
                    $business_company_address .= "\n";
                }
        
                $business_company_address .= $business_communication_address->city;
        
                if($business_communication_address->city != "" && $business_communication_address->state != "") {
                    $business_company_address .= "\n";
                }
        
                $business_company_address .= $business_communication_address->state." ".$business_communication_address->pin;
            }   


            $company_name = $business_communication_address->placename;
            $company_email = $business_communication_address->email_address;
            $company_mobile = $business_communication_address->mobile_no;
            $company_address = $business_company_address;

            $previous_entry = Transaction::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();



            $gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;



            $voucher_no = Custom::generate_accounts_number($type, $gen_no, false);

        $customer_type_label = 'Customer Type';

        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')

            ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')

            ->get();
            $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

             $mainproduct = InventoryItem::leftjoin('global_item_models','global_item_models.id','=','inventory_items.global_item_model_id')

                    ->leftjoin('global_item_categories','global_item_categories.id','=','inventory_items.category_id')

                    ->pluck('inventory_items.name','inventory_items.id');
                     $mainproduct->prepend('select a item here','');
                    
                    
                   

                      $items = InventoryItem::leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

                          ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
                           ->where(function($q) {
                             $q->where('global_item_models.category_id',45)
                             ->orWhere('global_item_models.category_id',46);
                            })
            
                            ->where('inventory_items.organization_id', $organization_id)
                           ->orderby('global_item_categories.display_name')
                           ->pluck('inventory_items.name','inventory_items.id');
                          // dd($items);
                         

                           $uuid=Custom::GUID();

                $vehicles = VehicleRegisterDetail::where('id', $id)->where('organization_id', $organization_id)->first();

                if($vehicles->user_type == "0")
                 {
              
                $customer_type = Person::findorfail($vehicles->owner_id)->id;
                 }

                if($vehicles->user_type == "1")
                {
               
                $customer_type = Business::findorfail($vehicles->owner_id)->id;
                }
                

           

            $vehicle_data=VehicleRegisterDetail::select('vehicle_variants.vehicle_configuration','vehicle_register_details.user_type','vehicle_register_details.driver as driver_name','vehicle_register_details.driver_mobile_no as driver_number',DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"),DB::raw("IF(people.mobile_no IS NULL, business.mobile_no, people.mobile_no) as mobile_no"),DB::raw("IF(people.email_address IS NULL, business.email_address, people.email_address) as email_address"),'people_addresses.address','customer_gropings.display_name as group_name')

          ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id') 
          -> leftJoin('people', function($join) use($organization_id)
                {
                    $join->on('people.person_id','=', 'vehicle_register_details.owner_id')
                    ->where('people.organization_id', $organization_id)
                    ->where('vehicle_register_details.user_type', '0');
                })

          ->leftJoin('people AS business', function($join) use($organization_id)
                {
                    $join->on('business.business_id','=', 'vehicle_register_details.owner_id')
                  
                    ->where('business.organization_id', $organization_id)
                    ->where('vehicle_register_details.user_type', '1');
            }) 
            ->leftjoin('people_addresses','people_addresses.people_id','=','people.id') 
            ->leftjoin('customer_gropings','customer_gropings.id','=','people.group_id')
           
            ->where('vehicle_register_details.id',$vehicle_id)     
             ->where('vehicle_register_details.organization_id',$organization_id)      
            ->first();
            //dd($vehicle_data);
           
             $vehicles_list = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');
            $item_id=FsmTank::SELECT('fsm_tanks.product')
                       ->leftjoin('fsm_pumps','fsm_pumps.tank_id','=','fsm_tanks.id')
                       ->where('fsm_pumps.id',$pump_id)
                       ->first();
            $item_id=$item_id->product;

              $itemdetails = InventoryItem::select('inventory_items.id', 'inventory_items.name','inventory_items.is_group', 'global_item_category_types.id AS main_category_id','global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.selling_price', 'inventory_items.base_price', 'taxes.id as tax_id','tax_groups.id as tax_groupid')

            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')

            ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')

            ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')

            ->leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')

          ->leftjoin('tax_groups', 'tax_groups.id', '=', 'inventory_items.tax_id')

          ->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')     

          ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id' )
          ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id' )
          ->where('inventory_items.organization_id', $organization_id)
          ->where('inventory_items.id', $item_id)     
          ->first();
          $price=$itemdetails->selling_price;
          $quantity=1;
          $rate=$price*$quantity;
          $tax_id=$itemdetails->tax_id;
          $tax_groupid=$itemdetails->tax_groupid;
          //dd($tax_groupid);

          $tax_amount=TaxGroup::leftjoin('group_tax','group_tax.group_id','=','tax_groups.id')
                      ->leftjoin('taxes','taxes.id','=','group_tax.tax_id')
                      ->where('tax_groups.id',$tax_id)
                      ->sum('taxes.value');
                    
          $total_taxamount=(float)$tax_amount/100*$price;
        // dd($total_taxamount);
          $total_amount=  $total_taxamount+$rate;



        return view('fuel_station.shortcut_invoice_createby_registernumber',compact('pump_id','uuid','weekday','weekdays','days','vehicles_register','job_card_status','job_status','transaction_type','customer_label','people','person_type','business','shift','employee','pumpname','payment','payment_terms','payment_term','product','discounts','taxes','address_label','company_label','company_name','company_email','company_mobile','company_address','transactions','voucher_no','customer_type_label','type','field_types','sub_heading','shift_id','mainproduct','items','vehicle_data','customer_type','vehicles_list','vehicles','itemdetails',
      'selling_price','quantity','in_stock','anonymous_vehicle_id','person','person_id','itemdetails','rate','tax_id','total_taxamount','total_amount','tax_groupid'));

    } public function due_invoice_create($id)
    {
        dd($id);
        $module_name=Session::get('module_name');       
        $organization_id = Session::get('organization_id');
      

        $transactions=Transaction::select('wms_transactions.registration_id','account_vouchers.name','hrm_employees.first_name as employeename','transactions.payment_mode_id','inventory_items.id as item_id','transaction_items.amount','inventory_items.purchase_price','inventory_item_stocks.in_stock','transaction_items.quantity','transactions.order_no')
                    ->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id')
                    ->leftJoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')
                    ->leftjoin('hrm_employees','hrm_employees.id','=','transactions.employee_id')
                    ->leftjoin('transaction_items','transaction_items.transaction_id','=','transactions.id')
                    ->leftjoin('inventory_items','inventory_items.id','=','transaction_items.item_id')
                     ->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id')
                    ->where('transactions.id',$id)
                    ->first();
                    $type=$transactions->name;
                    $item_id=$transactions->item_id;
                    $purchase_price=$transactions->purchase_price;
                    $in_stock=$transactions->in_stock;
                    $quantity=$transactions->quantity;
                    $amount=$transactions->amount;
                    $voucher_no=$transactions->order_no;
          $customer_type_label = 'Customer Type';
          $vehicle_data=VehicleRegisterDetail::select('vehicle_variants.vehicle_configuration','vehicle_register_details.user_type','vehicle_register_details.driver as driver_name','vehicle_register_details.driver_mobile_no as driver_number')

      ->leftjoin('vehicle_variants','vehicle_variants.id','=','vehicle_register_details.vehicle_configuration_id')        
       
        ->where('vehicle_register_details.id', $transactions->registration_id)     
         ->where('vehicle_register_details.organization_id',$organization_id)      
        ->first();
         //dd($transactions );
         $customer_label = 'Customer';

          $vehicles_list = VehicleRegisterDetail::where('organization_id', $organization_id)->pluck('registration_no', 'id');

        $people_list = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'));
                $person_type = "customer";
                $people = $people_list->pluck('name', 'id');
               
                $people->prepend('Select Customer', '');
              

         $business_list = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'));
          $business = $business_list->pluck('name', 'id');
            $business->prepend('Select Business', '');

            $vehicles = VehicleRegisterDetail::where('id',  $transactions->registration_id)->where('organization_id', $organization_id)->first();
            $vehicles_register =$vehicles->id;
            //dd($vehicles_register);


            if($vehicles->user_type == "0")
             {
          
            $customer_type = Person::findorfail($vehicles->owner_id)->id;
             }

            if($vehicles->user_type == "1")
            {
           
            $customer_type = Business::findorfail($vehicles->owner_id)->id;
            }

              $shifttime=HrmShift::where(function ($q) {
                    $q->where('hrm_shifts.from_time', '<=',Date('H:i:s'));
                    $q->where('hrm_shifts.to_time', '>=', Date('H:i:s'));
                     }) 
         ->where('hrm_shifts.organization_id',$organization_id)
                     ->pluck('hrm_shifts.name','hrm_shifts.id');

       
         $employee=HrmEmployee::where('organization_id',$organization_id)->pluck('first_name','id');

          $pumpname=FsmPump::where('organization_id',$organization_id)->pluck('name','id');
         $pumpname->prepend('Select Pumpname ','');

          $payment = PaymentMode::where('status', '1')->pluck('display_name','id');
        $payment->prepend('Select Payment Method','');

        $payment_terms = PaymentTerm::where('status', '1')->pluck('display_name','id');
        $payment_terms->prepend('Select Payment Term ','');

        $payment_term = PaymentTerm::where('name', 'Immediate')->first()->id;
        $items = InventoryItem::leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')      

                      ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
                      // ->where('inventory_items.category_id',42)
                      // ->orWhere('inventory_items.category_id',43)
        
                        ->where('inventory_items.organization_id', $organization_id)
                       ->orderby('global_item_categories.display_name')
                       ->pluck('inventory_items.name', 'inventory_items.id');
        $discount = Discount::select('id', 'display_name', 'value');
        $discount->where('status', 1)->where('organization_id', $organization_id);

        $discounts = $discount->get();
        $tax = TaxGroup::select('tax_groups.id', 'tax_groups.display_name', 'tax_types.name as tax_type', DB::raw('SUM(taxes.value) AS value'),'taxes.id as tax_id', 'taxes.display_name AS tax_name', DB::raw("CONCAT('[', GROUP_CONCAT('{', '\"id\":', taxes.id,  ',',  '\"name\": ', '\"',taxes.name,'\"', ',', '\"value\":', taxes.value, '}'),']') AS tax_value"));

        $tax->leftjoin('tax_types', 'tax_types.id', '=', 'tax_groups.tax_type_id');
        $tax->leftjoin('group_tax', 'group_tax.group_id', '=', 'tax_groups.id');
        $tax->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id');
        $tax->where('tax_groups.organization_id', $organization_id);

        $tax->groupby('tax_groups.id');
        $taxes = $tax->get();

        $address_label = 'Customer Address';

        $company_label=true;
        $transaction_types = AccountVoucherType::select('account_vouchers.*', 'modules.name AS module');
        $transaction_types->leftjoin('module_voucher', 'module_voucher.voucher_type_id', '=', 'account_voucher_types.id');
          
        $transaction_types->leftjoin('account_vouchers', 'account_vouchers.voucher_type_id', '=', 'account_voucher_types.id');
          
        $transaction_types->leftjoin('modules', 'modules.id', '=', 'module_voucher.module_id');
          
        $transaction_types->where('account_vouchers.organization_id', $organization_id);
        if(Session::get('module_name') != null) {
              $transaction_types->where('modules.name', Session::get('module_name'));
          }
        $transaction_types->where('account_vouchers.name', $type);

        $transaction_type = $transaction_types->first();
        $field_types = FieldType::select('field_types.id', 'field_types.display_name', 'field_types.name', 'field_formats.id AS format_id', 'field_formats.name AS format')

        ->leftjoin('field_formats', 'field_formats.id', '=', 'field_types.field_format_id')

        ->get();
         $sub_heading = TransactionField::select(DB::Raw('DISTINCT(transaction_fields.sub_heading)'))->whereNotNull('transaction_fields.sub_heading')->get();

        return view('fuel_station.due_invoice_create',compact('customer_type_label','transactions','vehicle_data','customer_label','people','business','vehicles','customer_type','person_type','vehicles_list','vehicles_register','shifttime','employee','pumpname','payment','payment_terms','payment_term','items','discounts','taxes','address_label','company_label','item_id','purchase_price','amount','in_stock','quantity','type','transaction_type','voucher_no','field_types','sub_heading'));

    }
    public function get_customer_details(Request $request){
    
      $data=VehicleRegisterDetail::select('vehicle_register_details.owner_id','vehicle_register_details.user_type')->where('id',$request->id)->first();
      $id=$data->owner_id;
      $user_type=$data->user_type;
 
      
      $people = People::select('people.first_name', 'people.last_name', 'people.display_name', 'people.mobile_no', 'people.email_address', 'people_titles.id AS title_id', 'genders.id AS gender_id',  
        DB::raw('COALESCE(billing_city.name, "") AS billing_city'), 
        DB::raw('COALESCE(billing_state.name, "") as billing_state'),  
        DB::raw('COALESCE(billing_address.address, "") as billing_address'), 
        DB::raw('COALESCE(billing_address.pin, "") as billing_pin'), 
        DB::raw('COALESCE(billing_address.google, "") as billing_google'), 
        'billing_address.id AS billing_id', 
        DB::raw('COALESCE(shipping_city.name, "") AS shipping_city'), 
        DB::raw('COALESCE(shipping_state.name, "") as shipping_state'), 
        DB::raw('COALESCE(shipping_address.address, "") as shipping_address'), 
        DB::raw('COALESCE(shipping_address.pin, "") as shipping_pin'),  
        DB::raw('COALESCE(shipping_address.google, "") as shipping_google'), 
        'shipping_address.id AS shipping_id');
      $people->leftJoin('genders', 'genders.id', '=', 'people.gender_id');
      $people->leftJoin('people_titles', 'people_titles.id', '=', 'people.title_id');
      $people->leftJoin('people_addresses AS billing_address', function($join)
          {
              $join->on('billing_address.people_id', '=', 'people.id')
              ->where('billing_address.address_type', '0');
          });
      $people->leftJoin('people_addresses AS shipping_address', function($join)
          {
              $join->on('shipping_address.people_id', '=', 'people.id')
              ->where('shipping_address.address_type', '1');
          });
      
      if($user_type == 0) {
        $people->where('people.person_id', $id);

      } else if($user_type == 1) {
        $people->where('people.business_id', $id);

      }
      $people->leftjoin('cities AS billing_city','billing_address.city_id','=','billing_city.id');
        $people->leftjoin('states AS billing_state','billing_city.state_id','=','billing_state.id');
      $people->leftjoin('cities AS shipping_city','shipping_address.city_id','=','shipping_city.id');
        $people->leftjoin('states AS shipping_state','shipping_city.state_id','=','shipping_state.id');
          $people->where('people.organization_id', Session::get('organization_id'));
      
      $person = $people->first();
  


      return response()->json($person);
    }

    
}
