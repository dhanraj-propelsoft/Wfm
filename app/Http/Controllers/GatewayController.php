<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Organization;
use App\BusinessNature;
use App\BusinessProfessionalism;
use App\BusinessInformation;
use App\AccountVoucherType;
use App\BusinessInfo;
use App\Business;
use App\Person;
use App\Register;
use App\Setting;
use App\Custom;
use App\Module;
use App\City;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
	public function index()
	{
		$organization = Organization::select('organizations.name', 'organizations.id');
		$organization->leftJoin('organization_person', 'organizations.id', '=', 'organization_person.organization_id');
		$organization->leftJoin('persons', 'persons.id', '=', 'organization_person.person_id');
		$organization->where('persons.id', Auth::user()->person_id);
		$organizations = $organization->get();
		if($organization->count() == 1) {
			$single = 1;
			return view('auth.organizations', compact('organizations', 'single'));
		} else {
			Session::put('account_type', 'user');
			return view('auth.organizations', compact('organizations'));
		}
	}

	public function store(Request $request)
	{
		/*$this->validate($request, [
			'account_type' => 'required'
		]);


		if($request->input('account_type') == 0) {

			$theme = json_decode(Setting::select('data')->where('name', 'theme')->where('user_id', Auth::id())->whereNull('organization_id')->first()->data);

			Session::put('theme_header', $theme->header);
			Session::put('theme_sidebar', $theme->sidebar);
			Session::put('account_type', 'user');

			return redirect()->route('user.dashboard');
		} else if($request->input('account_type') == 1) {

			$business = Organization::findOrFail($request->organizations)->business_id;

			$nature = Business::select('business_natures.name')->leftjoin('business_natures', 'business_natures.id', '=', 'businesses.business_nature_id')->where('businesses.id', $business)->first()->name;

			Session::put('account_type', 'business');
			Session::put('organization_id', $request->organizations);
			Session::put('business_nature', $nature);

			$theme = json_decode(Setting::select('data')->where('name', 'theme')->where('user_id', Auth::id())->where('organization_id', $request->organizations)->first()->data);

			Session::put('theme_header', $theme->header);
			Session::put('theme_sidebar', $theme->sidebar);

		   return redirect()->route('dashboard');

		}
*/
		
	}

	public function persons_store()
	{
		Session::forget('organization_id');

		$data = Setting::select('data')->where('name', 'theme')->where('user_id', Auth::id())->whereNull('organization_id')->first();

		if($data  != null) {
			$theme = json_decode($data->data);

			Session::put('theme_header', $theme->header);
			Session::put('theme_sidebar', $theme->sidebar);
		} else {
			$theme = ["header" => "bg-gradient-8", "sidebar" => "gradient bg-gradient-8"];

			Session::put('theme_header', "bg-gradient-8");
			Session::put('theme_sidebar', "gradient bg-gradient-8");
		}
		

		$person = Person::select('crm_code')->where('id', Auth::user()->person_id)->first();

		Session::put('crm_code', $person->crm_code);

		Session::forget('bcrm_code');
		Session::forget('business');

		

		Session::put('account_type', 'user');
		return redirect()->route('user.dashboard'); 
	}

	public function companies_store($id)
	{
	    
		Log::info("GatwayController->companies_store :-Inside Of The function");

		Session::put('account_type', 'business');
		Session::put('organization_id', $id);
        
		Log::info("GatwayController->companies_store ->GetOrganization Id :- ".$id);
		
		$organization = Organization::findOrFail($id)->business_id;
		
		Log::info("GatwayController->companies_store :- Get Organization Data ".json_encode($organization));

		$person = Person::select('crm_code')->where('id', Auth::user()->person_id)->first();

		Session::put('crm_code', $person->crm_code);

		$business = Business::select('businesses.alias AS name', 'business_natures.name AS nature', 'businesses.bcrm_code')->leftjoin('business_natures', 'business_natures.id', '=', 'businesses.business_nature_id')->where('businesses.id', $organization)->first();


		if(Organization::checkModuleExists('trade', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'trade')->first();

			$trade_modules = ['sale_order', 'sales', 'delivery_note', 'estimate', 'credit_note'];

			foreach ($trade_modules as $trade_module) {
				$voucher_type = AccountVoucherType::where('name', $trade_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}
		if(Organization::checkModuleExists('inventory', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'inventory')->first();


			$inventory_modules = ['purchase_order', 'purchase', 'goods_receipt_note', 'debit_note'];

			foreach ($inventory_modules as $inventory_module) {
				$voucher_type = AccountVoucherType::where('name', $inventory_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}

		if(Organization::checkModuleExists('trade_wms', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'trade_wms')->first();

			$wms_modules = ['job_card', 'job_request', 'job_invoice', 'delivery_note'];

			foreach ($wms_modules as $wms_module) {
				$voucher_type = AccountVoucherType::where('name', $wms_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}




		Session::put('bcrm_code', $business->bcrm_code);
		Session::put('business', $business->name);
		Session::put('business_nature', $business->nature);

		$theme_setting = Setting::select('data')->where('name', 'theme')->where('user_id', Auth::id())->where('organization_id', $id)->first();

		if($theme_setting != null) {
			$theme = json_decode($theme_setting->data);
		} else {
			$setting = new Setting();
			$setting->name = 'theme';
			$setting->status = 1;
			$setting->data = json_encode(["header" => "bg-gradient-8", "sidebar" => "gradient bg-gradient-8"]);
			$setting->user_id = Auth::id();
			$setting->organization_id = Session::get('organization_id');
			$setting->save();

			$theme = json_decode($setting->data);
		}

		Session::put('theme_header', $theme->header);
		Session::put('theme_sidebar', $theme->sidebar);
	//	dd($theme->sidebar);
		$group_approval = Setting::select('status')->where('name', 'ledgergroup_approval')->where('organization_id', $id)->first()->status;

		$ledger_approval = Setting::select('status')->where('name', 'ledger_approval')->where('organization_id', $id)->first()->status;

		Session::put('group_approval', $group_approval);
		Session::put('ledger_approval', $ledger_approval);


		Log::info("GatwayController->companies_store:-End Of the function");
		
		return redirect()->route('dashboard');
	}
		public function permissions(Request $request)
	{   
		$module = Organization::select('organizations.name', 'organizations.id','modules.name as module_name');
		$module->leftJoin('organization_person', 'organizations.id', '=', 'organization_person.organization_id');
		$module->leftJoin('persons', 'persons.id', '=', 'organization_person.person_id');
		$module->leftJoin('module_organization', 'module_organization.organization_id', '=', 'organizations.id');
		$module->leftJoin('modules', 'modules.id', '=', 'module_organization.module_id');
		$module->where('persons.id', Auth::user()->person_id)
					 ->where('organizations.id', $request->id);
		$modules = $module->get();
	
		return response()->json(['data' =>$modules]);

	}

	public function quick_access(Request $request)
	{	$id = $request->id;
		Session::put('account_type', 'business');
		Session::put('organization_id', $id);

		$organization = Organization::findOrFail($id)->business_id;

		//dd($organization);

		$person = Person::select('crm_code')->where('id', Auth::user()->person_id)->first();

		Session::put('crm_code', $person->crm_code);

		$business = Business::select('businesses.alias AS name', 'business_natures.name AS nature', 'businesses.bcrm_code')->leftjoin('business_natures', 'business_natures.id', '=', 'businesses.business_nature_id')->where('businesses.id', $organization)->first();


		if(Organization::checkModuleExists('trade', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'trade')->first();

			$trade_modules = ['sale_order', 'sales', 'delivery_note', 'estimate', 'credit_note'];

			foreach ($trade_modules as $trade_module) {
				$voucher_type = AccountVoucherType::where('name', $trade_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}
		if(Organization::checkModuleExists('inventory', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'inventory')->first();


			$inventory_modules = ['purchase_order', 'purchase', 'goods_receipt_note', 'debit_note'];

			foreach ($inventory_modules as $inventory_module) {
				$voucher_type = AccountVoucherType::where('name', $inventory_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}

		if(Organization::checkModuleExists('trade_wms', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'trade_wms')->first();

			$wms_modules = ['job_card', 'job_request', 'job_invoice', 'delivery_note'];

			foreach ($wms_modules as $wms_module) {
				$voucher_type = AccountVoucherType::where('name', $wms_module)->first();
				if($voucher_type != null) {
					if(!$module->hasVoucherType($voucher_type->id)) {
						$module->voucher_type()->attach($voucher_type->id);
					}
				}
			}
		}

		/*if(Organization::checkModuleExists('mship', Session::get('organization_id'))) {

			$module = Module::select('id')->where('name', 'mship')->first();		

			
		}*/




		Session::put('bcrm_code', $business->bcrm_code);
		Session::put('business', $business->name);
		Session::put('business_nature', $business->nature);

		$theme_setting = Setting::select('data')->where('name', 'theme')->where('user_id', Auth::id())->where('organization_id', $id)->first();

		if($theme_setting != null) {
			$theme = json_decode($theme_setting->data);
		} else {
			$setting = new Setting();
			$setting->name = 'theme';
			$setting->status = 1;
			$setting->data = json_encode(["header" => "bg-gradient-8", "sidebar" => "gradient bg-gradient-8"]);
			$setting->user_id = Auth::id();
			$setting->organization_id = Session::get('organization_id');
			$setting->save();

			$theme = json_decode($setting->data);
		}

		Session::put('theme_header', $theme->header);
		Session::put('theme_sidebar', $theme->sidebar);
			
		//dd($theme->sidebar);

		$group_approval = Setting::select('status')->where('name', 'ledgergroup_approval')->where('organization_id', $id)->first()->status;

		$ledger_approval = Setting::select('status')->where('name', 'ledger_approval')->where('organization_id', $id)->first()->status;

		Session::put('group_approval', $group_approval);
		Session::put('ledger_approval', $ledger_approval);

		return response()->json($request->url);
		
		
		
	}
}
