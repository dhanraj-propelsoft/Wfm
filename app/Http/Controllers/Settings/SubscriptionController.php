<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessCommunicationAddress;
use Softon\Indipay\Facades\Indipay;
use App\PersonCommunicationAddress;
use App\OrganizationPackage;
use App\SubscriptionPlan;
use App\SubscriptionType;
use App\SubscriptionAddonPricing;
use App\AddonPricing;
use App\PlanAccountType;
use App\BillingAddress;
use App\BusinessNature;
use App\BusinessField;
use App\BusinessProfessionalism;
use App\Subscription;
use App\Organization;
use App\PaymentMode;
use App\ModuleOrganization;
use App\TermPeriod;
use Carbon\Carbon;
use App\TaxGroup;
use App\Package;
use App\Country;
use App\Record;
use App\Custom;
use App\Addon;
use App\State;
use App\City;
use App\Tax;
use Session;
use Auth;
use DB;

class SubscriptionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		$organization_id = Session::get('organization_id');

		$subscriptions = Subscription::select('subscriptions.*', 'term_periods.display_name AS term', DB::raw('DATE_FORMAT(subscriptions.expire_on, "%b %d, %Y") AS expire_on'))
		->leftjoin('term_periods', 'term_periods.id', '=', 'subscriptions.term_period_id')
		->where('subscriptions.organization_id', $organization_id)
		->orderby('subscriptions.id', 'desc')->get();

		return view('settings.subscriptions', compact('subscriptions'));
	}

	public function addon_index()
	{
		$organization_id = Session::get('organization_id');

		$subscriptions = SubscriptionAddonPricing::select('subscription_addon_pricings.*', 'term_periods.display_name AS term', DB::raw('DATE_FORMAT(subscription_addon_pricings.expire_on, "%b %d, %Y") AS expire_on'),'addons.display_name AS addon_name')

		->leftjoin('term_periods', 'term_periods.id', '=', 'subscription_addon_pricings.term_period_id')

		->leftjoin('addons', 'addons.id', '=', 'subscription_addon_pricings.addon_id')

		->where('subscription_addon_pricings.organization_id', $organization_id)
		->orderby('subscription_addon_pricings.id', 'desc')->get();

		return view('settings.addon_subscription', compact('subscriptions'));
	}

	public function plan()
	{
		$organization_id = Session::get('organization_id');
		$renew = false;

		//$subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)->whereNotNull('subscription_id')->first();

		$subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)->first();

		//dd($subscription);

		if($subscription != "") {
			$total_days = Custom::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

			if($total_days < 15) {
				$renew = true;
			}
		}

		$free_plan = SubscriptionPlan::where('id', $subscription->plan_id)->first()->display_name;



		$organization_pack_free = OrganizationPackage::select(DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'))
		->where('organization_packages.organization_id', $organization_id)
		->whereNull('organization_packages.subscription_id')->first();

		$organization_pack_paid = OrganizationPackage::select(DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'))
		->where('organization_packages.organization_id', $organization_id)
		->whereNotNull('organization_packages.subscription_id')->first();

		if($organization_pack_paid != null) {
			$added_on = $organization_pack_paid->added_on;
			$expires_on = $organization_pack_paid->expire_on;
		} else {
			$added_on = $organization_pack_free->added_on;
			$expires_on = $organization_pack_free->expire_on;
		}

		$package = OrganizationPackage::select('packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name',  DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'),  DB::raw('DATE_FORMAT(organization_packages.expire_on, "%b %d, %Y") AS expire_on'))->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')->where('organization_packages.organization_id', $organization_id)->whereNotNull('organization_packages.subscription_id')->first();

		$organization_addons = DB::table('addon_organization')
		->leftjoin('addons', 'addons.id', '=', 'addon_organization.addon_id')
		->where('organization_id', $organization_id)
		->where('addons.status', 1)
		->get();

		return view('settings.plan', compact('package', 'free_plan', 'added_on', 'expires_on', 'organization_addons', 'renew'));
	}

	public function pricing($type)
	{
		//dd($type);

		$organization_id = Session::get('organization_id');

		$default_package = OrganizationPackage::where('organization_id',$organization_id)->first();

		//dd($default_package);

		if($default_package->subscription_id != null ){			

		$plan_details = DB::table('package_plan')
		->select('subscription_plans.name AS plan_name','subscriptions.total_price AS total_price','subscriptions.term_period_id','package_plan.price')
		->leftjoin('subscription_plans','subscription_plans.id','=','package_plan.plan_id')
		->leftjoin('organization_packages', 'package_plan.package_id', '=', 'organization_packages.package_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		
		->where('package_plan.package_id',$default_package->package_id)
		->where('package_plan.plan_id',$default_package->plan_id)

		->where('organization_packages.organization_id',$default_package->organization_id)

		->first();

		}else{

		$plan_details = DB::table('package_plan')
		->select('subscription_plans.name AS plan_name','package_plan.price','subscriptions.term_period_id')	
		->leftjoin('subscription_plans','subscription_plans.id','=','package_plan.plan_id')
		
		->leftjoin('organization_packages', 'package_plan.package_id', '=', 'organization_packages.package_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')		
		->where('package_plan.package_id',$default_package->package_id)
		->where('package_plan.plan_id',$default_package->plan_id)
		->where('organization_packages.organization_id',$default_package->organization_id)
		->first();
		}

		//dd($plan_details);

		

		$address_details = Organization::select('organizations.business_id','business_communication_addresses.*','cities.state_id')
		->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','organizations.business_id')
		->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
		->where('organizations.id',$organization_id)
		->first();


		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];

		$check_plan = Custom::check_plan();

		if(($type == "renew" && !$check_plan) || ($type == "plan-change" && !$check_plan)) {
			return redirect()->route('subscribe', ['upgrade']);
		}

		if($type == "renew" && !Custom::plan_expire($plan,$organization_id)) {
			return redirect()->route('subscribe', ['upgrade']);
		}
		

		$addon_id = Addon::where('name', 'records')->first()->id;

		$current_size = DB::table('addon_organization')->where('addon_id', $addon_id)->where('organization_id', $organization_id)->first();



		$account_type_id = PlanAccountType::where('name', 'business')->first();

		$country_id = Country::where('name', 'India')->first()->id;

		$state = State::select('name', 'id')->where('country_id', $country_id)->where('status', 1)->pluck('name', 'id');

		$state->prepend('Select State', '');

		$city = [];

	   if(!empty($address_details->city_id)) {
	   		$selected_city = City::where('id', $address_details->city_id)->first();
		   	$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

			$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
			$city->prepend('Select City', '');
	   }

		$payment_type = PaymentMode::where('name', 'online_payment')->first();

		$plans = SubscriptionPlan::where('account_type_id', $account_type_id->id)->where('status', 1)->pluck('display_name', 'id');
		$plans->prepend('Select Plan', '');

		$organization_name = Organization::select('businesses.business_name AS name')->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')->where('organizations.id', Session::get('organization_id'))->first()->name;
		
		$subscription_types = SubscriptionType::where('status', 1)->pluck('display_name','id');

		$term_periods = TermPeriod::where('status', 1)->where('account_type_id', $account_type_id->id)->pluck('display_name','id');
		$term_periods->prepend('Select Term', '');

		$ledgers = Record::where('status', 1)->where('addon_id', $addon_id)->whereRaw('size >= '.$current_size->value)->pluck('display_name','id');
		$ledgers->prepend('Select Ledger Size', '');


		$packages = OrganizationPackage::select('packages.display_name AS package', 'subscription_plans.display_name AS plan', 'organization_packages.plan_id', 'subscription_plans.name AS plan_name', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), 'packages.id AS package_id', DB::raw('GROUP_CONCAT(DISTINCT(modules.display_name) SEPARATOR ", ") AS modules'), 'subscriptions.term_period_id');
		$packages->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id');
		$packages->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id');
		$packages->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id');
		$packages->leftjoin('package_modules', 'package_modules.package_id', '=', 'packages.id');
		$packages->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id');
		$packages->where('organization_packages.organization_id', $organization_id);

		if($check_plan) {
			$packages->whereNotNull('organization_packages.subscription_id');
		}

		$package = $packages->first();

		//dd($package);

		$package_list = Package::where('status', 1)->pluck('display_name','id');

		$current_term = $package->term_period_id;

		$current_plan = $package->plan_id;

		$current_package = $package->package_id;

		$account_type_id = PlanAccountType::where('name', 'business')->first();

		$businessnature =  BusinessNature::select('display_name AS name', 'id')->get();

		$subscription_plan = SubscriptionPlan::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
		$subscription_plan->prepend('Choose Plan','');

		$package_plan = Package::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
		$package_plan->prepend('Choose Package','');

		$businessprofessionalism =  BusinessProfessionalism::select('display_name AS name', 'id')->get();

		$businessinformation =  BusinessField::where('status','1')->get();

		$packages1 = Package::select('packages.id', 'packages.display_name', 'packages.image', DB::raw('GROUP_CONCAT(modules.display_name SEPARATOR " + ") AS modules'))
		->where('packages.status', '1')
		->leftjoin('package_modules', 'packages.id', '=', 'package_modules.package_id')
		->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
		->where('packages.account_type_id', $account_type_id->id)
		->groupby('id')->get();




		return view('settings.pricing', compact('payment_type', 'state', 'package', 'subscription_types', 'term_periods', 'ledgers', 'plans', 'organization_name', 'package_list', 'type', 'current_size', 'current_term', 'current_plan','businessnature','businessprofessionalism', 'businessinformation', 'packages1', 'request','subscription_plan','default_package','plan_details','address_details','city','package_plan','current_package'));
	}

	public function addon_pricing($type,$id)
	{
		//dd($type);

		$organization_id = Session::get('organization_id');

		$default_package = OrganizationPackage::where('organization_id',$organization_id)->first();

		if($default_package->subscription_id != null ){

		$plan_details = DB::table('package_plan')
		->select('subscription_plans.name AS plan_name','subscriptions.total_price AS total_price','subscriptions.term_period_id','package_plan.price')
		->leftjoin('subscription_plans','subscription_plans.id','=','package_plan.plan_id')
		->leftjoin('organization_packages', 'package_plan.package_id', '=', 'organization_packages.package_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		
		->where('package_plan.package_id',$default_package->package_id)
		->where('package_plan.plan_id',$default_package->plan_id)
		->where('organization_packages.organization_id',$default_package->organization_id)
		->first();

		}else{

		$plan_details = DB::table('package_plan')
		->select('subscription_plans.name AS plan_name','package_plan.price','subscriptions.term_period_id')	
		->leftjoin('subscription_plans','subscription_plans.id','=','package_plan.plan_id')
		
		->leftjoin('organization_packages', 'package_plan.package_id', '=', 'organization_packages.package_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')		
		->where('package_plan.package_id',$default_package->package_id)
		->where('package_plan.plan_id',$default_package->plan_id)
		->where('organization_packages.organization_id',$default_package->organization_id)
		->first();
		}
		

		$address_details = Organization::select('organizations.business_id','business_communication_addresses.*','cities.state_id')
		->leftjoin('business_communication_addresses','business_communication_addresses.business_id','=','organizations.business_id')
		->leftjoin('cities','business_communication_addresses.city_id','=','cities.id')
	   ->leftjoin('states','cities.state_id','=','states.id')
		->where('organizations.id',$organization_id)
		->first();


		$plan = ['Free14Days','Starter','Lite','Standard','Professional','Enterprise','Corporate'];

		$check_plan = Custom::check_plan();

		if(($type == "renew" && !$check_plan) || ($type == "plan-change" && !$check_plan)) {
			return redirect()->route('subscribe', ['upgrade']);
		}

		if($type == "renew" && !Custom::plan_expire($plan,$organization_id)) {
			return redirect()->route('subscribe', ['upgrade']);
		}
		

		$addon_id = Addon::where('name', 'records')->first()->id;

		$current_size = DB::table('addon_organization')->where('addon_id', $addon_id)->where('organization_id', $organization_id)->first();



		$account_type_id = PlanAccountType::where('name', 'business')->first();

		$country_id = Country::where('name', 'India')->first()->id;

		$state = State::select('name', 'id')->where('country_id', $country_id)->where('status', 1)->pluck('name', 'id');

		$state->prepend('Select State', '');

		$city = [];

	   if(!empty($address_details->city_id)) {
	   		$selected_city = City::where('id', $address_details->city_id)->first();
		   	$selected_state = State::select('id')->where('id', $selected_city->state_id)->first()->id;

			$city = City::select('id', 'name')->where('state_id', $selected_state)->pluck('name', 'id');
			$city->prepend('Select City', '');
	   }

		$payment_type = PaymentMode::where('name', 'online_payment')->first();

		$plans = SubscriptionPlan::where('account_type_id', $account_type_id->id)->where('status', 1)->pluck('display_name', 'id');
		$plans->prepend('Select Plan', '');

		$organization_name = Organization::select('businesses.business_name AS name')->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')->where('organizations.id', Session::get('organization_id'))->first()->name;
		
		$subscription_types = SubscriptionType::where('status', 1)->pluck('display_name','id');

		$term_periods = TermPeriod::where('status', 1)->where('account_type_id', $account_type_id->id)->pluck('display_name','id');
		$term_periods->prepend('Select Term', '');

		$ledgers = Record::where('status', 1)->where('addon_id', $addon_id)->whereRaw('size >= '.$current_size->value)->pluck('display_name','id');
		$ledgers->prepend('Select Ledger Size', '');


		$packages = OrganizationPackage::select('packages.display_name AS package', 'subscription_plans.display_name AS plan', 'organization_packages.plan_id', 'subscription_plans.name AS plan_name', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), 'packages.id AS package_id', DB::raw('GROUP_CONCAT(DISTINCT(modules.display_name) SEPARATOR ", ") AS modules'), 'subscriptions.term_period_id');
		$packages->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id');
		$packages->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id');
		$packages->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id');
		$packages->leftjoin('package_modules', 'package_modules.package_id', '=', 'packages.id');
		$packages->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id');
		$packages->where('organization_packages.organization_id', $organization_id);

		if($check_plan) {
			$packages->whereNotNull('organization_packages.subscription_id');
		}

		$package = $packages->first();

		$package_list = Package::where('status', 1)->pluck('display_name','id');

		$current_term = $package->term_period_id;

		$current_plan = $package->plan_id;


		$account_type_id = PlanAccountType::where('name', 'business')->first();

		$businessnature =  BusinessNature::select('display_name AS name', 'id')->get();

		$subscription_plan = SubscriptionPlan::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
		$subscription_plan->prepend('Choose Plan','');

		$package_plan = Package::where('status', '1')->where('account_type_id', '2')->pluck('display_name', 'id');
		$package_plan->prepend('Choose Package','');

		$addon_ledger = AddonPricing::where('addon_id','1')->pluck('name', 'id');
		$addon_ledger->prepend('Choose Ledgers','');

		$addon_sms = AddonPricing::where('addon_id','2')->pluck('name', 'id');
		$addon_sms->prepend('Choose SMS','');

		$addon_employee = AddonPricing::where('addon_id','3')->pluck('name', 'id');
		$addon_employee->prepend('Choose Employees','');

		$addon_customer = AddonPricing::where('addon_id','4')->pluck('name', 'id');
		$addon_customer->prepend('Choose Customers','');

		$addon_supplier = AddonPricing::where('addon_id','5')->pluck('name', 'id');
		$addon_supplier->prepend('Choose Suppliers','');

		$addon_purchase = AddonPricing::where('addon_id','6')->pluck('name', 'id');
		$addon_purchase->prepend('Choose Purchases','');

		$addon_invoice = AddonPricing::where('addon_id','7')->pluck('name', 'id');
		$addon_invoice->prepend('Choose Invoice','');

		$addon_grn = AddonPricing::where('addon_id','8')->pluck('name', 'id');
		$addon_grn->prepend('Choose Goods Receipt Note','');

		$addon_vehicles = AddonPricing::where('addon_id','9')->pluck('name', 'id');
		$addon_vehicles->prepend('Choose Vehicles','');

		$addon_jobcard = AddonPricing::where('addon_id','10')->pluck('name', 'id');
		$addon_jobcard->prepend('Choose Jobcards','');

		$addon_transaction = AddonPricing::where('addon_id','11')->pluck('name', 'id');
		$addon_transaction->prepend('Choose Transactions','');

		$addon_storage = AddonPricing::where('addon_id','13')->pluck('name', 'id');
		$addon_storage->prepend('Choose Storage Size','');

		$addon_call_hour = AddonPricing::where('addon_id','14')->pluck('name', 'id');
		$addon_call_hour->prepend('Choose Hours','');


		$businessprofessionalism =  BusinessProfessionalism::select('display_name AS name', 'id')->get();

		$businessinformation =  BusinessField::where('status','1')->get();

		$packages1 = Package::select('packages.id', 'packages.display_name', 'packages.image', DB::raw('GROUP_CONCAT(modules.display_name SEPARATOR " + ") AS modules'))
		->where('packages.status', '1')
		->leftjoin('package_modules', 'packages.id', '=', 'package_modules.package_id')
		->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
		->where('packages.account_type_id', $account_type_id->id)
		->groupby('id')->get();


		return view('settings.addon_pricing', compact('payment_type', 'state', 'package', 'subscription_types', 'term_periods', 'ledgers', 'plans', 'organization_name', 'package_list', 'type', 'current_size', 'current_term', 'current_plan','businessnature','businessprofessionalism', 'businessinformation', 'packages1', 'request','subscription_plan','default_package','plan_details','address_details','city','package_plan','addon_ledger','id','addon_sms','addon_employee','addon_customer','addon_supplier','addon_purchase','addon_invoice','addon_grn','addon_vehicles','addon_jobcard','addon_transaction','addon_storage','addon_call_hour'));
	}

	public function get_addon_details(Request $request)
	{
		
		$addon_price_id = $request->input('addon_price_id');
		$addon_id = $request->input('addon_id');

		$addon_price = AddonPricing::where('id',$addon_price_id)->where('addon_id', $addon_id)->get();	


		return response()->json(['result' => $addon_price]);

	}

	public function get_estimate_price(Request $request) {

		$organization_id = Session::get('organization_id');

		$package = Package::select(
			'packages.display_name AS package',
			'package_plan.price',  
			DB::raw('GROUP_CONCAT(DISTINCT(modules.display_name)) AS modules')
			)
		->leftjoin('package_modules', 'package_modules.package_id', '=', 'packages.id')
		->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id')
		->leftjoin('package_plan', 'package_plan.package_id', '=', 'packages.id')
		->where('packages.id', $request->input('package'))
		->where('package_plan.plan_id', $request->input('plan'))
		->first();

		$term = TermPeriod::findOrFail($request->input('term'));

		$months = 1;

		if($term->name == "quarterly") {
			$months = 3;
		} else if($term->name == "half_yearly") {
			$months = 6;
		} else if($term->name == "annually") {
			$months = 12;
		}

		$ledger = Record::find($request->input('ledger'));

		$ledger_price = ($request->input('ledger') != "") ? $ledger->price : 0;

		$ledger_name = ($request->input('ledger') != "") ? $ledger->display_name : 0;

		$subtotal = ($package->price * $months) + ($ledger_price * $months);

		$discount_amount = ($package->price * $months) * ($term->discount / 100);

		$full_price = ($package->price * $months - $discount_amount) + ($ledger_price * $months);

		$tax_group = TaxGroup::where('name', "18.0% GST")->where('organization_id', $organization_id)->first();

		$tax_amount = 0;
		$tax_array = [];
		if($tax_group != null) {

			$taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

			foreach ($taxgroups as $taxgroup) {

				$tax_value = Tax::where('id', $taxgroup->tax_id)->first();

				if($tax_value->is_percent == 1) {
					$tax = ($tax_value->value/100) * $full_price;
					$tax_amount += Custom::two_decimal($tax);
				} else if($tax_value->is_percent == 0) {
					$tax = $tax_value->value;
					$tax_amount += Custom::two_decimal($tax);
				}

				$tax_array[] = ["name" => $tax_value->display_name." @".$tax_value->value. "% on ".$full_price, "amount" => Custom::two_decimal($tax)];

			}

		}


		$total = $full_price + $tax_amount;

		return response()->json(['package' => $package->package, 'modules' => $package->modules,'package_price' => Custom::two_decimal($package->price* $months),  'single_package_price' => Custom::two_decimal($package->price), 'discount' => $term->discount, 'discount_amount' => Custom::two_decimal($discount_amount), 'ledger' => $ledger_name, 'ledger_price' => Custom::two_decimal(($ledger_price * $months)), 'subtotal' => Custom::two_decimal($subtotal), 'tax' => $tax_array, 'total' => Custom::two_decimal($total)]);

	}

	public function get_address_type(Request $request) {

		$user = Auth::user();
		$organization = Organization::findOrfail(Session::get('organization_id'));

		if($request->input('address_type') == "personal" ) {
			$address_type = PersonCommunicationAddress::select('person_communication_addresses.id', 'person_address_types.display_name AS name')->leftjoin('person_address_types', 'person_address_types.id', '=', 'person_communication_addresses.address_type')->where('person_id', $user->person_id)->get();
		} else if($request->input('address_type') == "business" ) {
			$address_type = BusinessCommunicationAddress::select('business_communication_addresses.id', 'business_address_types.display_name AS name')->leftjoin('business_address_types', 'business_address_types.id', '=', 'business_communication_addresses.address_type')->where('business_id', $organization->business_id)->get();
		}

		return $address_type;
	}

	public function get_address(Request $request) {

		if($request->input('address_type') == "personal" ) {
			$address = PersonCommunicationAddress::findOrfail($request->input('id'));
		} else if($request->input('address_type') == "business" ) {
			$address = BusinessCommunicationAddress::findOrfail($request->input('id'));
		}

		$cities = City::where('state_id', $address->state)->where('status', 1)->get();

		return response()->json(array('address' => $address, 'cities' => $cities));
	}

	public function pricing_store(Request $request)
	{

		$this->validate($request, [
			'payment_mode_id' => 'required',
		]);
		

		//dd($request->all());

		$organization_id = Session::get('organization_id');

		$package_id =  $request->input('package_id');
		$term_period_id = $request->input('term_period_id');

		if($request->input('discount_payment') != null){

			$discount_payment = $request->input('discount_payment');

		}else{

			$discount_payment = $request->input('total_payment');
		}
		
		$plan_id = $request->input('plan_id');
		$payment_mode = $request->input('payment_mode_id');		

		$city = City::findOrfail($request->input('city'));		
		$state = State::findOrfail($request->input('state'));
		

		$term_period = TermPeriod::where('id', $term_period_id)->first();

		$total_amount = DB::table('package_plan')->select('package_plan.price')	
		->where('package_plan.package_id', $package_id)
		->where('package_plan.plan_id', $plan_id)
		->first();	
		

		$existing_subscription = OrganizationPackage::select('organization_packages.expire_on', 'organization_packages.plan_id', 'subscriptions.term_period_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		->where('organization_packages.organization_id', $organization_id)
		->where('organization_packages.status', 1)
		->whereNotNull('organization_packages.subscription_id')
		->first();

		if($term_period_id == 3) {
			$expire_date = Carbon::now()->addDays(90)->format('Y-m-d H:i:s');	
		}

		if($term_period_id == 4) {
			$expire_date = Carbon::now()->addDays(180)->format('Y-m-d H:i:s');
		}

		if($term_period_id == 5) {

			$expire_date = Carbon::now()->addDays(365)->format('Y-m-d H:i:s');
		}		

		

		$address = ['name' => $request->input('name'), 'door' => $request->input('door'), 'street' => $request->input('street'), 'area' => $request->input('street'), 'area' => $request->input('area'), 'city' => $city->name, 'state' => $state->name, 'pin' => $request->input('pin'), 'landmark' => $request->input('landmark'), 'mobile_no' => $request->input('mobile_no'), 'phone' => $request->input('phone'), 'email_address' => $request->input('email_address')];

		//$records = ($ledger_id != null) ? ["record" => $ledger_id] : [];

		$sub_id = Subscription::select('id')->orderby('id', 'desc')->first();
		$subid = 1;

		if($sub_id != null) {
			$subid = $sub_id->id + 1;
		}


		$subscription = new Subscription;
		$subscription->organization_id = $organization_id;
		$subscription->subscription_type_id = 1;
		$subscription->tax_amount = null;
		$subscription->total_price = Custom::two_decimal($discount_payment);
		$subscription->price_report = null;
		$subscription->tax_report = null;
		$subscription->transaction_id = Custom::transaction_id(16);
		$subscription->order_id = Carbon::now()->format('ym').str_pad($subid, '6', '0', STR_PAD_LEFT);
		$subscription->added_on = Carbon::now()->format('Y-m-d H:i:s');
		$subscription->term_period_id = $term_period_id;
		$subscription->expire_on = $expire_date;
		$subscription->payment_mode_id = $payment_mode;
		$subscription->save();


		if($subscription->id) {	
			$billing_address = new BillingAddress;
			$billing_address->name = $address['name'];
			$billing_address->door = $address['door'];
			$billing_address->street = $address['street'];
			$billing_address->area = $address['area'];
			$billing_address->city = $address['city'];
			$billing_address->state = $address['state'];
			$billing_address->pin = $address['pin'];
			$billing_address->landmark = $address['landmark'];
			$billing_address->mobile_no = $address['mobile_no'];
			$billing_address->phone = $address['phone'];
			$billing_address->email_address = $address['email_address'];
			$billing_address->subscription_id = $subscription->id;
			$billing_address->save();
		}


		Session::put('last_subscription_id', $subscription->id);

			if(!empty($package_id)) {
				Session::put('package_id', $package_id);
			}

			if(!empty($plan_id)) {
				Session::put('plan_id', $plan_id);
			}


		if($subscription->id) {	

			$last_subscription_id = Session::get('last_subscription_id');

			$subscription_param = Subscription::select('subscriptions.total_price', 'subscriptions.transaction_id', 'subscriptions.order_id', 'billing_addresses.*')
			->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscriptions.id')
			->where('subscriptions.id', $last_subscription_id)
			->first();	

			

			$parameters = [
	  
				'tid' => $subscription_param->transaction_id,
				
				'order_id' => $subscription_param->order_id,
				
				'amount' => $subscription_param->total_price,

				'billing_name' => $subscription_param->name,

				'billing_address' => ($subscription_param->door != "" || $subscription_param->door != "" || $subscription_param->street != "") ? $subscription_param->door.', '.$subscription_param->street.', '.$subscription_param->area : null,

				'billing_city' => $subscription_param->city,

				'billing_country' => 'India',

				'billing_state' => $subscription_param->state,

				'billing_zip' => $subscription_param->pin,

				'billing_tel' => $subscription_param->mobile_no,

				'billing_email' => $subscription_param->email_address
				
			  ];
			  
			  //Takes data to PaymentController@response

			  $id = 1;

			  $order = Indipay::prepare($parameters);

			  return Indipay::process($order);


		/*Refer Payment-Controller for next step change package,plan and addon */

		} else {
			return redirect()->back()->WithErrors(['Subscription error' => 'Subscription Failed. Please try after some ']);
		}
	}

	public function addon_pricing_store(Request $request)
	{

		$this->validate($request, [
			'addon_pricing_id' => 'required',
		]);

		//dd($request->all());		

		$organization_id = Session::get('organization_id');

		$addon_id = $request->input('addon_id');
		$addon_value = $request->input('addon_value');
		$addon_pricing_id = $request->input('addon_pricing_id');
		$package_id =  $request->input('package_id');				
		$plan_id = $request->input('plan_id');
		$term_period_id = $request->input('term_period_id');
		$discount_payment = $request->input('total_payment');

		$addon_type = $request->input('type');
		//$payment_mode = $request->input('payment_mode_id');		

		//$city = City::findOrfail($request->input('city'));		
		//$state = State::findOrfail($request->input('state'));
		

		$term_period = TermPeriod::where('id', $term_period_id)->first();		


		$total_amount = DB::table('package_plan')->select('package_plan.price')	
		->where('package_plan.package_id', $package_id)
		->where('package_plan.plan_id', $plan_id)
		->first();	
		

		$existing_subscription = OrganizationPackage::select('organization_packages.subscription_id','subscriptions.*')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		->where('organization_packages.organization_id', $organization_id)
		->where('organization_packages.status', 1)
		->whereNotNull('organization_packages.subscription_id')
		->first();			

		//dd($existing_subscription);

		/*$address = ['name' => $request->input('name'), 'door' => $request->input('door'), 'street' => $request->input('street'), 'area' => $request->input('street'), 'area' => $request->input('area'), 'city' => $city->name, 'state' => $state->name, 'pin' => $request->input('pin'), 'landmark' => $request->input('landmark'), 'mobile_no' => $request->input('mobile_no'), 'phone' => $request->input('phone'), 'email_address' => $request->input('email_address')];*/

		//$records = ($ledger_id != null) ? ["record" => $ledger_id] : [];

		$sub_id = SubscriptionAddonPricing::select('id')->orderby('id', 'desc')->first();
		$subid = 1;

		if($sub_id != null) {
			$subid = $sub_id->id + 1;
		}

		//return false;

		$subscription = new SubscriptionAddonPricing;

		$subscription->subscription_id = $existing_subscription->subscription_id;
		$subscription->organization_id = $organization_id;	

		$subscription->transaction_id = $existing_subscription->transaction_id;
		$subscription->order_id = $existing_subscription->order_id;

		$subscription->transaction_id = Custom::transaction_id(16);
		$subscription->order_id = Carbon::now()->format('ym').str_pad($subid, '6', '0', STR_PAD_LEFT);

		$subscription->subscription_type_id = 1;

		$subscription->addon_id = $addon_id;
		$subscription->addon_price_id = $addon_pricing_id;
		$subscription->addon_value = $addon_value;

		$subscription->added_on = $existing_subscription->added_on;
		$subscription->expire_on = $existing_subscription->expire_on;
		$subscription->term_period_id = $term_period_id;

		$subscription->tax_amount = null;
		$subscription->total_price = Custom::two_decimal($discount_payment);
		$subscription->price_report = null;
		$subscription->tax_report = null;	
		$subscription->payment_mode_id = $existing_subscription->payment_mode_id;
		$subscription->save();


		Session::put('last_subscription_id', $subscription->id);

			if(!empty($package_id)) {
				Session::put('package_id', $package_id);
			}

			if(!empty($plan_id)) {
				Session::put('plan_id', $plan_id);
			}

			if($addon_type =='addon_upgrade')
			{
				if(!empty($addon_id)) {
					Session::put('addon_id', $addon_id);
				}

				if(!empty($addon_pricing_id)) {
					Session::put('addon_pricing_id', $addon_pricing_id);
				}

			}		


		if($subscription->id) {	

			$last_subscription_id = Session::get('last_subscription_id');

			$subscription_param = SubscriptionAddonPricing::select('subscription_addon_pricings.total_price', 'subscription_addon_pricings.transaction_id', 'subscription_addon_pricings.order_id', 'billing_addresses.*')
			->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscription_addon_pricings.subscription_id')
			->where('subscription_addon_pricings.id', $last_subscription_id)
			->first();

			//dd($subscription_param);

			$parameters = [
	  
				'tid' => $subscription_param->transaction_id,
				
				'order_id' => $subscription_param->order_id,
				
				'amount' => $subscription_param->total_price,

				'billing_name' => $subscription_param->name,

				'billing_address' => ($subscription_param->door != "" || $subscription_param->door != "" || $subscription_param->street != "") ? $subscription_param->door.', '.$subscription_param->street.', '.$subscription_param->area : null,

				'billing_city' => $subscription_param->city,

				'billing_country' => 'India',

				'billing_state' => $subscription_param->state,

				'billing_zip' => $subscription_param->pin,

				'billing_tel' => $subscription_param->mobile_no,

				'billing_email' => $subscription_param->email_address
				
			];
			  
			  //Takes data to PaymentController@response


			  $order = Indipay::prepare($parameters);

			  return Indipay::process($order);


		/*Refer Payment-Controller for next step change package,plan and addon */

		} else {
			
			return redirect()->back()->WithErrors(['Subscription error' => 'Subscription Failed. Please try after some ']);
		}
	}



	public function store_pricing(Request $request)
	{

		$this->validate($request, [
			'payment_mode_id' => 'required',
		]);

		//dd($request->all());

		$package_id = ($request->input('package_id') != null) ? $request->input('package_id') : null ;
		$term_period_id = ($request->input('term_period_id') != null) ? $request->input('term_period_id') : null ;
		$ledger_id = ($request->input('ledger_id') != null) ? $request->input('ledger_id') : null ;
		$plan_id = ($request->input('plan_id') != null) ? $request->input('plan_id') : null ;
		
		$organization = Organization::findOrfail(Session::get('organization_id'));
		$organization_id = Session::get('organization_id');

		$city = City::findOrfail($request->input('city'));
		$state = State::findOrfail($request->input('state'));

		$term_period = TermPeriod::where('id', $request->input('term_period_id'))->first();

		$existing_subscription = OrganizationPackage::select('organization_packages.expire_on', 'organization_packages.plan_id', 'subscriptions.term_period_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		->where('organization_packages.organization_id', $organization_id)
		->where('organization_packages.status', 1)
		->whereNotNull('organization_packages.subscription_id')
		->first();

		$expire_date = null;

		if($term_period == null) {
			$term_period_id = $existing_subscription->term_period_id;
		}

		if($plan_id == null) {
			$plan_id = $existing_subscription->plan_id;
		}

		if($request->input('type') == "plan-change") {	


			$total_days = Custom::time_difference(Carbon::parse($existing_subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

			$expire_date = Carbon::parse($existing_subscription->expire_on)->format('Y-m-d H:i:s');
			$period = Custom::two_decimal($total_days/30);


		} else {

			if($existing_subscription != null) {
				$expire_on = $existing_subscription->expire_on;
			} else {
				$expire_on = null;
			}

			$remaining_days = Custom::time_difference(Carbon::parse()->format('Y-m-d'), Carbon::now($expire_on)->format('Y-m-d'), 'd');

			//dd($remaining_days);

			if($term_period->name == 'quarterly') {

				if($request->input('type') == "upgrade") {
					$expire_date = Carbon::now()->addDays(90)->format('Y-m-d H:i:s');
				} else if($request->input('type') == "renew") {

					if($remaining_days < 0) {
						$expire_date = Carbon::now()->addDays(90)->format('Y-m-d H:i:s');
					} else {
						$expire_date = Carbon::parse($expire_on)->addDays(90)->format('Y-m-d H:i:s');
					}
				}
				
				$period = 3;
				$total_days = 3 * 30;

			} else if($term_period->name == 'half_yearly') {

				if($request->input('type') == "upgrade") {
					$expire_date = Carbon::now()->addDays(180)->format('Y-m-d H:i:s');
				} else if($request->input('type') == "renew") {
					if($remaining_days < 0) {
						$expire_date = Carbon::now()->addDays(180)->format('Y-m-d H:i:s');
					} else {
						$expire_date = Carbon::parse($expire_on)->addDays(180)->format('Y-m-d H:i:s');
					}
				}

				$period = 6;
				$total_days = 6 * 30;

			} else if($term_period->name == 'annually') {

				if($request->input('type') == "upgrade") {
					$expire_date = Carbon::now()->addDays(365)->format('Y-m-d H:i:s');

				} else if($request->input('type') == "renew") {
					if($remaining_days < 0) {
						$expire_date = Carbon::now()->addDays(365)->format('Y-m-d H:i:s');
					} else {
						$expire_date = Carbon::parse($expire_on)->addDays(365)->format('Y-m-d H:i:s');
					}
				}

				$period = 12;
				$total_days = 12 * 30;

			}

		}

		$address = ['name' => $request->input('name'), 'door' => $request->input('door'), 'street' => $request->input('street'), 'area' => $request->input('street'), 'area' => $request->input('area'), 'city' => $city->name, 'state' => $state->name, 'pin' => $request->input('pin'), 'landmark' => $request->input('landmark'), 'mobile_no' => $request->input('mobile_no'), 'phone' => $request->input('phone'), 'email_address' => $request->input('email_address')];

		$records = ($ledger_id != null) ? ["record" => $ledger_id] : [];

		if( Custom::subscriptions($request->input('type'), $period, $total_days, $request->input('payment_mode_id'), $expire_date, $address, $package_id, $plan_id, $records, "package", $term_period_id) ) {

			$last_subscription_id = Session::get('last_subscription_id');

			$subscription = Subscription::select('subscriptions.total_price', 'subscriptions.transaction_id', 'subscriptions.order_id', 'billing_addresses.*')->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscriptions.id')->where('subscriptions.id', $last_subscription_id)->first();

			//amount   --->  $subscription->total_price

			$parameters = [
	  
				'tid' => $subscription->transaction_id,
				
				'order_id' => $subscription->order_id,
				
				'amount' => "1",

				'billing_name' => $subscription->name,

				'billing_address' => ($subscription->door != "" || $subscription->door != "" || $subscription->street != "") ? $subscription->door.', '.$subscription->street.', '.$subscription->area : null,

				'billing_city' => $subscription->city,

				'billing_country' => 'India',

				'billing_state' => $subscription->state,

				'billing_zip' => $subscription->pin,

				'billing_tel' => $subscription->mobile_no,

				'billing_email' => $subscription->email_address
				
			  ];
			  
			  //Takes data to PaymentController@response
			  $order = Indipay::prepare($parameters);
			  return Indipay::process($order);

		} else {
			return redirect()->back()->WithErrors(['Subscription error' => 'Subscription Failed. Please try after some ']);
		}
	}

	public function buy_addon()
	{
		$organization_id = Session::get('organization_id');

		$subscription = OrganizationPackage::where('organization_id', Session::get('organization_id'))->where('status', 1)->whereNotNull('subscription_id')->first();

		$remaining_days = 0;

		$remaining_month = 0;

		$remaining_ledger_amount = 0;

		if($subscription) {
			$total_days = Custom::time_difference(Carbon::parse($subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

			$remaining_days = $total_days;

			$remaining_day = ($total_days%30);
			$remaining_month = floor($total_days/30);

			$remaining_month_text = $remaining_month != 0 ? $remaining_month. " month". (($remaining_month > 1) ? "s ": " ") : "";

			$remaining_day_text = "*For ".$remaining_month_text .(($remaining_month != 0 && $remaining_day != 0) ? "and ": ""). (($remaining_day != 0) ? $remaining_day." day".(($remaining_day > 1) ? "s": "") : "");

		if($total_days <= 0) {
			return redirect()->route('subscribe', ['upgrade']);
		}
		} else {
			return redirect()->route('subscribe', ['upgrade']);
		}

		$addons = Addon::where('status', '1')->get();

		$record_id = Addon::where('name', 'records')->first()->id;
		$sms_id = Addon::where('name', 'sms')->first()->id;

		$current_addon = DB::table('addon_organization')->where('addon_id', $record_id)->where('organization_id', $organization_id)->first();

		$remaining_total = $current_addon->value - $current_addon->used;
	  
		$account_type_id = PlanAccountType::where('name', 'business')->first();

		$country_id = Country::where('name', 'India')->first()->id;

		$state = State::select('name', 'id')->where('country_id', $country_id)->where('status', 1)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$payment_type = PaymentMode::where('name', 'online_payment')->first();

		$organization_name = Organization::select('businesses.business_name AS name')->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')->where('organizations.id', Session::get('organization_id'))->first()->name;

		$existing_ledger = Record::where('status', 1)->where('size', $current_addon->value)->first();

		if($remaining_days == 90) {
			$remaining_ledger_amount = 3 * $existing_ledger->price;
		} else if($remaining_days == 180) {
			$remaining_ledger_amount = 6 * $existing_ledger->price;
		} else if($remaining_days == 365) {
			$remaining_ledger_amount = 12 * $existing_ledger->price;
		} else {
			$remaining_ledger_amount = $remaining_days  * ($existing_ledger->price / 30);
		}
		
		//Greater record than the existing one. It works for ledgers only
		$ledgers = Record::where('status', 1)->where('addon_id', $record_id)->whereRaw('size > '.$current_addon->value)->get();

		$sms = Record::where('status', 1)->where('addon_id', $sms_id)->get();

		$packages = OrganizationPackage::select('packages.display_name AS package', 'subscription_plans.display_name AS plan', 'subscription_plans.name AS plan_name', DB::raw('DATE_FORMAT(organization_packages.added_on, "%b %d, %Y") AS added_on'), 'packages.id AS package_id', DB::raw('GROUP_CONCAT(DISTINCT(modules.display_name) SEPARATOR ", ") AS modules'))
		->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id');

		$packages->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id');
		$packages->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id');
		$packages->leftjoin('package_modules', 'package_modules.package_id', '=', 'packages.id');
		$packages->leftjoin('modules', 'modules.id', '=', 'package_modules.module_id');
		$packages->where('organization_packages.organization_id', $organization_id);

		if(Custom::check_plan()) {
			$packages->whereNotNull('organization_packages.subscription_id');
		}

		$package = $packages->first();

		$tax_group = TaxGroup::where('name', "18.0% GST")->where('organization_id', $organization_id)->first();

		$tax_array = [];
		$tax = 0;

		if($tax_group != null) {

			$taxgroups = DB::table('group_tax')->where('group_id', $tax_group->id)->get();

			foreach ($taxgroups as $taxgroup) {

				$tax_value = Tax::where('id', $taxgroup->tax_id)->first();

				$tax += $tax_value->value;

				$tax_array[] = ["name" => $tax_value->display_name, "amount" => $tax_value->value, "tax" => $tax ];

			}

		}

		return view('settings.buy_addon', compact('payment_type', 'state', 'term_periods', 'ledgers', 'plans', 'organization_name', 'package', 'addons', 'sms', 'remaining_days', 'remaining_day_text', 'remaining_total', 'remaining_ledger_amount', 'tax_array'));
	}

	public function store_addon(Request $request)
	{
		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrfail($organization_id);

		$existing_subscription = OrganizationPackage::select('organization_packages.expire_on', 'subscriptions.term_period_id')
		->leftjoin('subscriptions', 'subscriptions.id', '=', 'organization_packages.subscription_id')
		->where('organization_packages.organization_id', $organization_id)
		->where('organization_packages.status', 1)
		->whereNotNull('organization_packages.subscription_id')
		->first();

		$city = City::findOrfail($request->input('city'));
		$state = State::findOrfail($request->input('state'));

		$total_days = Custom::time_difference(Carbon::parse($existing_subscription->expire_on)->format('Y-m-d'), Carbon::now()->format('Y-m-d'), 'd');

		$address = ['name' => $request->input('name'), 'door' => $request->input('door'), 'street' => $request->input('street'), 'area' => $request->input('street'), 'area' => $request->input('area'), 'city' => $city->name, 'state' => $state->name, 'pin' => $request->input('pin'), 'landmark' => $request->input('landmark'), 'mobile_no' => $request->input('mobile_no'), 'phone' => $request->input('phone'), 'email_address' => $request->input('email_address')];

		$subscription_type_id = SubscriptionType::where('name', 'addon')->first()->id;

		$price_report = [];

		$addon_array = [];

		$full_price = 0.00;

		$records = [];

		if($request->input('ledger_id') != null) {
			$records["record"] = $request->input('ledger_id');
		}

		if($request->input('sms_id') != null) {
			$records["sms"] = $request->input('sms_id');
		}

		if( Custom::subscriptions("addon", Custom::two_decimal($total_days/30), $total_days, $request->input('payment_mode_id'), $existing_subscription->expire_on, $address, null, null, $records, "addon", null) ) {

			$last_subscription_id = Session::get('last_subscription_id');

			$subscription = Subscription::select('subscriptions.total_price', 'subscriptions.transaction_id', 'subscriptions.order_id', 'billing_addresses.*')
			->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscriptions.id')
			->where('subscriptions.id', $last_subscription_id)->first();

			$parameters = [
	  
				'tid' => $subscription->transaction_id,
				
				'order_id' => $subscription->order_id,
				
				'amount' => $subscription->total_price,

				'billing_name' => $subscription->name,

				'billing_address' => ($subscription->door != "" || $subscription->door != "" || $subscription->street != "") ? $subscription->door.', '.$subscription->street.', '.$subscription->area : null,

				'billing_city' => $subscription->city,

				'billing_country' => 'India',

				'billing_state' => $subscription->state,

				'billing_zip' => $subscription->pin,

				'billing_tel' => $subscription->mobile_no,

				'billing_email' => $subscription->email_address
				
			  ];
			  
			  $order = Indipay::prepare($parameters);
			  return Indipay::process($order);

		} else {
			 return redirect()->back()->WithErrors(['Subscription error' => 'Subscription Failed. Please try after some ']);
		}

		/*if($request->input('ledger_id') != null) {

			$ledger = Record::findOrFail($request->input('ledger_id'));

			$ledger_per_day = $ledger->price / 30;

			$record_id = Addon::where('name', 'records')->first()->id;

			$current_addon = DB::table('addon_organization')->where('addon_id', $record_id)->where('organization_id', $organization_id)->first();

			$remaining_total = $current_addon->value - $current_addon->used;

			$existing_ledger = Record::where('status', 1)->where('addon_id', $record_id)->where('size', $current_addon->value)->first();

			$remaining_ledger_amount = 0.00;

			if($remaining_days == 90) {
				$remaining_ledger_amount = 3 * $existing_ledger->price;
			} else if($remaining_days == 180) {
				$remaining_ledger_amount = 6 * $existing_ledger->price;
			} else if($remaining_days == 365) {
				$remaining_ledger_amount = 12 * $existing_ledger->price;
			} else {
				$remaining_ledger_amount = $remaining_days  * ($existing_ledger->price / 30);
			}


			$subtotal = ($ledger_per_day * $remaining_days) - $remaining_ledger_amount;

			$total = $subtotal;

			$full_price += $total;

			$price_report[] = array('ledger_id' => $ledger->id, 'ledger' => $ledger->display_name. " - " .$ledger_count , 'ledger_price' => $ledger->price, 'subtotal' => $subtotal, 'total' => $total);

			$ledgers = Addon::where('name', 'records')->first()->id;

			$addon_array[] = ['organization_id' => $organization_id, 'addon_id' => $ledgers, 'value' => $ledger->size];


		}

		if($request->input('sms_id') != null) {

			$sms = Record::findOrFail($request->input('sms_id'));

			$subtotal = $sms->price;

			$total = $sms->price;

			$full_price += $total;

			$price_report[] = array('ledger_id' => $sms->id, 'ledger' => $sms->display_name, 'ledger_price' => $sms->price, 'subtotal' => $subtotal, 'total' => $total);

			$sms = Addon::where('name', 'sms')->first()->id;

			$addon_array[] = ['organization_id' => $organization_id, 'addon_id' => $sms, 'value' => $sms->size];
		}	

		$sub_id = Subscription::orderby('id', 'desc')->first();
		$subid = 1;

		if($sub_id != null) {
			$subid = $sub_id->id + 1;
		}

		$subscription = new Subscription;
		$subscription->organization_id = $organization_id;
		$subscription->subscription_type_id = $subscription_type_id;
		$subscription->total_price = Custom::two_decimal($full_price);
		$subscription->price_report = json_encode($price_report);
		$subscription->transaction_id = Custom::transaction_id(16);
		$subscription->order_id = Carbon::now()->format('ym').str_pad($subid, '6', '0', STR_PAD_LEFT);
		$subscription->added_on = Carbon::now()->format('Y-m-d H:i:s');
		$subscription->term_period_id = $existing_subscription->term_period_id;
		$subscription->expire_on = Carbon::parse($existing_subscription->expire_on)->format('Y-m-d');
		$subscription->payment_mode_id = $request->input('payment_mode_id');
		$subscription->save();

		if($subscription->id) {

			$state = State::findOrFail($request->input('state'))->name;

			$city = City::findOrFail($request->input('city'))->name;

			$billing_address = new BillingAddress;
			$billing_address->name = $request->input('name');
			$billing_address->door = $request->input('door');
			$billing_address->street = $request->input('street');
			$billing_address->area = $request->input('area');
			$billing_address->city = $city;
			$billing_address->state = $state;
			$billing_address->pin = $request->input('pin');
			$billing_address->landmark = $request->input('landmark');
			$billing_address->mobile_no = $request->input('mobile_no');
			$billing_address->phone = $request->input('phone');
			$billing_address->email_address = $request->input('email_address');
			$billing_address->subscription_id = $subscription->id;
			$billing_address->save();

			Session::put('last_subscription_id', $subscription->id);

			Session::put('addons', $addon_array);

			$parameters = [
	  
				'tid' => $subscription->transaction_id,
				
				'order_id' => $subscription->order_id,
				
				'amount' => $subscription->total_price,

				'billing_name' => $billing_address->name,

				'billing_address' => ($billing_address->door != "" || $billing_address->door != "" || $billing_address->street != "") ? $billing_address->door.', '.$billing_address->street.', '.$billing_address->area : null,

				'billing_city' => $billing_address->city,

				'billing_country' => 'India',

				'billing_state' => $billing_address->state,

				'billing_zip' => $billing_address->pin,

				'billing_tel' => $billing_address->mobile_no,

				'billing_email' => $billing_address->email_address
				
			  ];
			  
			  $order = Indipay::prepare($parameters);
			  return Indipay::process($order);


		} else {

			return redirect()->back()->WithErrors(['Subscription error' => 'Subscription Failed. Please try after some ']);

		}*/


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
