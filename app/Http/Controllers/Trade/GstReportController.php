<?php



namespace App\Http\Controllers\Trade;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Transaction;

use App\Custom;

use App\Unit;

use App\AccountVoucher;

use Carbon\Carbon;
use App\Person;
use App\CustomerGroping;

use App\Country;
use App\State;
use App\City;
use App\PeopleTitle;
use App\PaymentMode;
use App\Term;

use Auth;

use Session;

use URL;

use DB;



class GstReportController extends Controller

{

	/**

	 * Display a listing of the resource.

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function index($module=null)

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


		$start_date = Carbon::parse(date('01-m-Y'))->format('d-m-Y');

		

		$end_date = Carbon::parse($start_date)->endOfMonth()->format('d-m-Y');



		return view('trade.gst_report',compact('start_date','end_date','module','state','city','title','payment','terms','group_name'));

	}



	public function get_gst_report(Request $request) {



		$organization_id = Session::get('organization_id');



		$transaction_type = AccountVoucher::where('name', 'sales')->where('organization_id', $organization_id)->first()->id;



		$start_date = Carbon::parse($request->input('start_date'))->format('Y-m-d');

		$end_date = Carbon::parse($request->input('end_date'))->format('Y-m-d');



		$select_type = $request->input('select_type');		

		//dd($select_type);



		$query = Transaction::select('transactions.*','transactions.transaction_type_id','wms_transactions.job_date','transactions.total AS total',
			DB::raw('IF(account_vouchers.name = "sales" OR account_vouchers.name = "sales_cash", "Sales Invoice", account_vouchers.display_name ) AS transaction_type'),'transaction_items.item_id as item_no' ,'transaction_items.quantity','transaction_items.rate','transaction_items.amount','transaction_items.discount','transaction_items.is_discount_percent','inventory_items.name as description','units.display_name as unit','transaction_items.tax','inventory_items.hsn','global_item_category_types.display_name as category_type','transaction_items.discount_value','business.gst_no as company_gst',
			DB::raw('DATE_FORMAT(transactions.shipping_date, "%d-%m-%Y") AS shipping_date'),
			DB::raw('DATE_FORMAT(transactions.date, "%d-%m-%Y") AS date'),'transactions.name as customer_name','transactions.pin',DB::raw('((CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value/100 END) * (transaction_items.amount)) AS discount_amount'),DB::raw('((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) AS taxable'),'tax_groups.display_name as tax','taxes.display_name as cgst','tax_types.id AS tax_type',DB::raw('sum(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_amount'),DB::raw('(((transaction_items.amount) -(transaction_items.amount) *(CASE WHEN transaction_items.discount_value IS NULL THEN 0 ELSE transaction_items.discount_value / 100 END)) * taxes.value / 100) AS tax_value'));
		
		$query->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id');
		$query->leftjoin('transaction_items','transactions.id','=','transaction_items.transaction_id');
		$query->leftjoin('inventory_items','transaction_items.item_id','=','inventory_items.id');
		$query->leftjoin('units','inventory_items.unit_id','=','units.id');
		$query->leftjoin('global_item_models','inventory_items.global_item_model_id','=','global_item_models.id');
		$query->leftjoin('global_item_categories','global_item_models.category_id','=','global_item_categories.id');
		$query->leftjoin('global_item_main_categories','global_item_categories.main_category_id','=','global_item_main_categories.id');
		$query->leftjoin('global_item_category_types','global_item_main_categories.category_type_id','=','global_item_category_types.id');
		$query->leftjoin('wms_transactions','wms_transactions.transaction_id','=','transactions.id');
		$query->leftjoin('organizations','organizations.id','=','transactions.organization_id');
		$query->leftjoin('businesses','businesses.id','=','organizations.business_id');	
		$query->leftjoin('tax_groups','tax_groups.id','=','transaction_items.tax_id');
		$query->leftjoin('group_tax','group_tax.group_id','=','tax_groups.id');
		$query->leftjoin('taxes','taxes.id','=','group_tax.tax_id');
		$query->leftjoin('tax_types','tax_types.id','=','tax_groups.tax_type_id');
		$query->leftJoin('people AS business', function($join) use($organization_id)

        	{

            	$join->on('business.business_id','=', 'transactions.people_id')

            	->where('business.organization_id',$organization_id)

            	->where('transactions.user_type', '1');

        	});
		$query->where('transactions.organization_id',$organization_id);	

		if($select_type == 'sales'){
			$query->whereIn('account_vouchers.name', ['sales', 'sales_cash']);
		} else if ($select_type == 'purchases') {
			$query->whereIn('account_vouchers.name', ['purchases']);
		}else if($select_type == 'wms_sales')
		{
			$query->whereIn('account_vouchers.name', ['job_invoice_cash','job_invoice']);
			$query->where('transactions.approval_status','=', 1);

		}
		else  {
			$query->whereIn('account_vouchers.name', ['sales','sales_cash','purchases']);
		}
		/*if($select_type == 'wms_sales')
		{
			$query->whereIn('account_vouchers.name', ['job_invoice_cash','job_invoice']);
			
		}*/
		if($select_type == 'sales' || $select_type == 'purchases')
		{
		$query->whereBetween('transactions.date', [$start_date, $end_date]);
		}

		if($select_type == 'wms_sales')
		{
			$query->whereBetween('wms_transactions.job_date',[$start_date,$end_date]);
		}
		$query->groupby('transactions.transaction_type_id');
		$query->groupby('transaction_items.transaction_id');
		$query->groupby('transaction_items.item_id');

		$results = $query->get();

		//dd($results);



		$transactions = [];



		foreach ($results as $transaction) {



			//$discounts = json_decode($transaction->discount);



			//dd($discounts);

			

			//$discount = "";

			//$discount_amount = "";



			/*if(count($discounts) > 0 && $discounts != null)

			{

				$discount_amount = $discounts->amount;								

			}*/



			/*if($discounts != null)

			{

				$discount_amount = $discounts->amount;								

			}

		}



		foreach ($results as $transaction) {



			$billing_addresses = $transaction->billing_address;		

			

			$billing_address = "";*/



			/*if(count($billing_addresses) > 0 && $billing_addresses != null)

			{

				$billing_address = $billing_addresses;

			}*/


/*
			if($billing_addresses != null)

			{

				$billing_address = $billing_addresses;

			}

		}
*/


		//dd($results);

		/*foreach ($results as $transaction) {

			

			$taxes = json_decode($transaction->tax, true);

			

			$cgst = "";

			$sgst = "";

			$igst = "";

			$cgst_amount = "";

			$sgst_amount = "";

			$igst_amount = "";

			$gst = "";
*/


				//dd($taxes);
/*
			if($taxes != null)

			{*/

				/*foreach ($taxes as $tax) {

				

					if(strpos($tax['name'], 'CGST') !== false) {

						$cgst = $tax['value'];

						$cgst_amount = $tax['amount'];



					} else if(strpos($tax['name'], 'SGST') !== false) {

						$sgst = $tax['value'];

						$sgst_amount = $tax['amount'];

					} else if(strpos($tax['name'], 'IGST') !== false) {

						$igst = $tax['value'];

						$igst_amount = $tax['amount'];

					}

				}*/

/*

				if($cgst != "" && $sgst != "")

				{

					$igst = $cgst + $sgst;

					$igst_amount = $cgst_amount + $sgst_amount;

				}*/

				

				/*foreach ($taxes as $tax) {

				

					if(strpos($tax['name'], 'CGST') !== false) {

						$cgst = $tax['value'];

						//dd($cgst);

						$cgst_amount = $tax['amount'];



					}else if(strpos($tax['name'], 'SGST') !== false) {

						$sgst = $tax['value'];

						//dd($sgst);

						$sgst_amount = $tax['amount'];

					} else if(strpos($tax['name'], 'IGST') !== false) {

						$igst = $tax['value'];

						$igst_amount = $tax['amount'];

					}

				}

				if($cgst == "" && $sgst == "")

				{

					$igst =$tax['value'];

					$igst_amount = $tax['amount'];

				}

				

				if(is_numeric($cgst) && is_numeric($sgst))

				{

				$gst =$cgst + $sgst;

				//dd($gst);

				}

			}

*/



			$gst_report = new \stdClass;

			//$gst_report->gst = Custom::two_decimal($gst);

			//$gst_report->cgst = Custom::two_decimal($cgst);

			//$gst_report->cgst_amount = Custom::two_decimal($cgst_amount);

			//$gst_report->sgst = Custom::two_decimal($sgst);

			//$gst_report->sgst_amount = Custom::two_decimal($sgst_amount);

			//$gst_report->igst = Custom::two_decimal($igst);

			//$gst_report->igst_amount = Custom::two_decimal($igst_amount);

			$gst_report->id = $transaction->id;
			$gst_report->order_no = $transaction->order_no;
			$gst_report->transaction_type = $transaction->transaction_type;
			$gst_report->date = $transaction->date;
			$gst_report->billing_name = $transaction->billing_name;
			$gst_report->reference_no = $transaction->reference_no;
			$gst_report->item_no = $transaction->item_no;
			$gst_report->quantity = $transaction->quantity;
			$gst_report->rate = $transaction->rate;
			$gst_report->amount = $transaction->amount;
			$gst_report->is_discount_percent = $transaction->is_discount_percent;
			$gst_report->discount = $transaction->discount_value;
			$gst_report->total = $transaction->total;
			$gst_report->description = $transaction->description;
			$gst_report->unit = $transaction->unit;
			$gst_report->tax = $transaction->tax;
			$gst_report->hsn = $transaction->hsn;
			$gst_report->category_type = $transaction->category_type;
			$gst_report->discount_amount = $transaction->discount_amount;
			$gst_report->billing_address = $transaction->billing_address;
			$gst_report->shipping_date = $transaction->shipping_date;
			$gst_report->customer_name = $transaction->customer_name;
			$gst_report->pin = $transaction->pin;
			$gst_report->job_date=$transaction->job_date;
			$gst_report->company_gst=$transaction->company_gst;
			$gst_report->taxable_amount=$transaction->taxable;
			$gst_report->cgst=$transaction->cgst;
			$gst_report->tax_value=$transaction->tax_value;
			$gst_report->tax_type=$transaction->tax_type;
			$gst_report->tax_amount=$transaction->tax_amount;
			$gst_report->billing_gst=$transaction->billing_gst;
			$transactions[] = $gst_report;

		}



		//dd($transactions);



		$total = Transaction::select('transactions.id','transactions.transaction_type_id',DB::raw('SUM(transactions.total) AS total'))

		->leftjoin('account_vouchers','account_vouchers.id','=','transactions.transaction_type_id')

		->where('transactions.organization_id',$organization_id)

		->whereIn('account_vouchers.name', ['sales', 'sales_cash','purchases','credit_note','debit_note'])

		->first();	





		return response()->json(array('transactions' => $transactions,'total' => $total));

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

