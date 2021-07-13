<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Softon\Indipay\Facades\Indipay; 
use Illuminate\Support\Facades\Config;
use App\SubscriptionGateway;
use App\OrganizationPackage;
use App\Organization;
use App\Subscription;
use App\SubscriptionAddonPricing;
use App\AddonPricing;
use App\ModuleOrganization;
use App\Addon;
use Session;
use Mail;
use DB;

class PaymentController extends Controller
{
	public function response_org(Request $request)
	{
		//dd('test');

		//These data where received from Custom::subscriptions()
		$last_subscription_id = Session::get('last_subscription_id');
		$package_id = Session::get('package_id');
		$addons = Session::get('addons');

		$organization_id = Session::get('organization_id');
		$organization = Organization::find(Session::get('organization_id'));


		$subscription = Subscription::findOrFail($last_subscription_id);

		//Get Payment Response
		$response = Indipay::response($request);

		$gateway = new SubscriptionGateway;
		$gateway->subscription_id = $last_subscription_id;
		$gateway->gateway = Config::get('indipay.gateway');
		$gateway->response = json_encode($response);
		$gateway->save();

		if( $response['order_status'] == "Success") {
			//Package Id was received from session of subscription controller
			if($package_id != null) {

				$organization_package = OrganizationPackage::where('organization_id', $organization_id)->whereNotNull('subscription_id')->first();
				$organization_package->status = 1;
				$organization_package->save();

				DB::table('module_organization')->where('organization_id', $organization_id)->delete();

				$selected_modules = DB::table('package_modules')->select('module_id')->where('package_id', $package_id)->get();

				foreach ($selected_modules as $value) { 
					$organization->modules()->attach($value);
				}
			}
			//Check addon exists
			if(count($addons) > 0) {
				//Get Each addons
				foreach ($addons as $addon) {

					$org_addon_exists = DB::table('addon_organization')->where(
						'organization_id', $organization_id)->where('addon_id', $addon['addon_id'])->first();
					//When there is no remaining value in addon
					if($org_addon_exists == null) {
						DB::table('addon_organization')->insert(['organization_id' => $organization_id, 'addon_id' => $addon['addon_id'], 'value' => $addon['value'], 'subscription_id' => $subscription->id]);
					} 
					//When there is values in addons, current value will added to the existing addon
					else {
						$addon_name = Addon::findOrFail($addon['addon_id'])->name;
						if($addon_name == "sms") {
							DB::table('addon_organization')->where(
						'organization_id', $organization_id)->where('addon_id', $addon['addon_id'])->update(['value' => $org_addon_exists->value + $addon['value'], 'subscription_id' => $subscription->id]);
						} else {
							DB::table('addon_organization')->where(
						'organization_id', $organization_id)->where('addon_id', $addon['addon_id'])->update(['value' => $addon['value'], 'subscription_id' => $subscription->id]);
						}
					}
				}
			}

			
			$subscription->payment_status = 1;
			$subscription->save();

			$subscription = Subscription::select('subscriptions.total_price', 'subscriptions.transaction_id', 'subscriptions.order_id', 'billing_addresses.*', DB::raw('DATE_FORMAT(subscriptions.added_on, "%b %D, %Y") AS added_on'), DB::raw('DATE_FORMAT(subscriptions.expire_on, "%b %D, %Y") AS expire_on'), 'subscriptions.price_report')->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscriptions.id')->where('subscriptions.id', $last_subscription_id)->first();

			$data = ['transaction_id' => $subscription->transaction_id, 'order_id' => $subscription->order_id, 'total_price' => $subscription->total_price, 'name' => $subscription->name, 'address' => $subscription->door.', '.$subscription->street.', '.$subscription->area, 'city' => $subscription->city, 'state' => $subscription->state, 'added_on' => $subscription->added_on, 'expire_on' => $subscription->expire_on, 'pin' => $subscription->pin, 'report' => json_decode($subscription->price_report, true)];


			$email = $subscription->email_address;
			$name = $subscription->name;
			$subject = "Your subscription #".$subscription->order_id." is confirmed. Copy of invoice included.";

			Mail::send('emails.mail_subscription_invoice', $data, function ($message) use ($email, $name, $subject) {
				$message->from('support@propelsoft.in', 'PropelERP');
				$message->to($email, $name);
				$message->subject($subject);
			});

			Session::forget('last_subscription_id');

			return redirect()->route('subscription');

		} else {
			if($package_id != null) {
				$organization_package = OrganizationPackage::where('organization_id', $organization_id)->whereNotNull('subscription_id')->first();
				$organization_package->status = 0;
				$organization_package->save();

				$subscription = Subscription::findOrFail($last_subscription_id);
				$subscription->payment_status = 0;
				$subscription->save();
			}

			return redirect()->route('subscription')->WithErrors(['Subscription error' => 'Subscription Failed.']);
		}

	}

	public function response(Request $request)
	{
		//These data where received from Custom::subscriptions()

		$last_subscription_id = Session::get('last_subscription_id');
		$package_id = Session::get('package_id');
		$plan_id = Session::get('plan_id');
		$addon_id = Session::get('addon_id');
		$addon_pricing_id = Session::get('addon_pricing_id');
		//$addons = Session::get('addons');

			

		$organization_id = Session::get('organization_id');
		$organization = Organization::find(Session::get('organization_id'));


		if($package_id != null && $plan_id != null && $addon_id != null && $addon_pricing_id != null)
		{		

			$subscription = SubscriptionAddonPricing::findOrFail($last_subscription_id);

			//Get Payment Response
			$response = Indipay::response($request);

			//dd($response);
			

			if( $response['order_status'] == "Success") {

				//Package Id was received from session of subscription controller

				if($addon_id != null) {

					/* ADDON update */

					$addon = AddonPricing::where('addon_pricings.id',$addon_pricing_id)->where('addon_pricings.addon_id',$addon_id)->first();					

					DB::table('addon_organization')->where('organization_id',$organization_id)->where('addon_id',$addon->addon_id)->update([
							'value'=> $addon->value								
						]);				

				}			
				
				$subscription->payment_status = 1;
				$subscription->save();

				$subscription = SubscriptionAddonPricing::select('subscription_addon_pricings.total_price', 'subscription_addon_pricings.transaction_id', 'subscription_addon_pricings.order_id', 'billing_addresses.*', DB::raw('DATE_FORMAT(subscription_addon_pricings.added_on, "%b %D, %Y") AS added_on'), DB::raw('DATE_FORMAT(subscription_addon_pricings.expire_on, "%b %D, %Y") AS expire_on'), 'subscription_addon_pricings.price_report','packages.display_name AS package_name','addons.display_name AS addon_name')

				->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscription_addon_pricings.subscription_id')

				->leftjoin('organization_packages', 'subscription_addon_pricings.id', '=', 'organization_packages.subscription_id')

				->leftjoin('addons', 'addons.id', '=', 'subscription_addon_pricings.addon_id')

				->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')

				->where('subscription_addon_pricings.id', $last_subscription_id)->first();			


				$data = ['transaction_id' => $subscription->transaction_id, 'order_id' => $subscription->order_id, 'total_price' => $subscription->total_price, 'name' => $subscription->name, 'address' => $subscription->door.', '.$subscription->street.', '.$subscription->area, 'city' => $subscription->city, 'state' => $subscription->state, 'added_on' => $subscription->added_on, 'expire_on' => $subscription->expire_on, 'pin' => $subscription->pin, 'item' => $subscription->addon_name];


				$email = $subscription->email_address;
				$name = $subscription->name;

				$subject = "Your Addon subscription #".$subscription->order_id." is confirmed. Copy of invoice included.";

				Mail::send('emails.mail_subscription_invoice', $data, function ($message) use ($email, $name, $subject) {
					$message->from('support@propelsoft.in', 'PropelERP');
					$message->to($email, $name);
					$message->subject($subject);
				});

				Session::forget('last_subscription_id');

				return redirect()->route('addon_subscription');
			}

			else {
				if($package_id != null) {

					
					$organization_package = OrganizationPackage::where('organization_id', $organization_id)->first();
					$organization_package->status = 0;
					$organization_package->save();

					$subscription = Subscription::findOrFail($last_subscription_id);
					$subscription->payment_status = 0;
					$subscription->save();
				}

				return redirect()->route('subscription')->WithErrors(['Subscription error' => 'Subscription Failed.']);
			}

		}

		else
		{
			$subscription = Subscription::findOrFail($last_subscription_id);

			//Get Payment Response
			$response = Indipay::response($request);

			//dd($response);

			$gateway = new SubscriptionGateway;
			$gateway->subscription_id = $last_subscription_id;
			$gateway->gateway = Config::get('indipay.gateway');
			$gateway->response = json_encode($response);
			$gateway->save();

			if($response['order_status'] == "Success") {

				//Package Id was received from session of subscription controller	
				if($package_id != null) {

					/*$organization_package = OrganizationPackage::where('organization_id', $organization_id)->whereNotNull('subscription_id')->first();
						$organization_package->status = 1;
						$organization_package->save();*/

					DB::table('organization_packages')->where('organization_id',$organization_id)->update([
					'package_id'=> $package_id,
					'plan_id' => $plan_id,
					'added_on' => $subscription->added_on,
					'expire_on' => $subscription->expire_on,
					'status' => 1,
					'subscription_id' => $subscription->id
					]);

					$selected_modules = DB::table('package_modules')->select('module_id')->where('package_id', $package_id)->get();

					/*Delete and update moudule organization*/

					DB::table('module_organization')->where('organization_id', $organization_id)->delete();

					foreach ($selected_modules as $module) {

						$ModuleOrganization = ModuleOrganization::where('organization_id',$organization_id)->updateOrCreate([
							"module_id" => $module->module_id,
							"organization_id" => $organization_id],
							["module_id" => $module->module_id,
							"organization_id" => $organization_id]);		
					}

					/*end*/

					/* ADDON update */

					$addons = OrganizationPackage::select('organization_packages.plan_id','subscription_addons.addon_id','subscription_addons.value')
					->leftjoin('subscription_addons','subscription_addons.subscription_plan_id','=','organization_packages.plan_id')
					->where('organization_packages.organization_id',$organization_id)->get();

					foreach ($addons as $addon) {

						DB::table('addon_organization')->where('organization_id',$organization_id)->where('addon_id',$addon->addon_id)->update([
						'value'=> $addon->value,
						'used'=> 0
						
						]);		
					}

					/*end*/

				}	

				
				$subscription->payment_status = 1;
				$subscription->save();

				$subscription = Subscription::select('subscriptions.total_price', 'subscriptions.transaction_id', 'subscriptions.order_id', 'billing_addresses.*', DB::raw('DATE_FORMAT(subscriptions.added_on, "%b %D, %Y") AS added_on'), DB::raw('DATE_FORMAT(subscriptions.expire_on, "%b %D, %Y") AS expire_on'), 'subscriptions.price_report','packages.display_name AS package_name')
				->leftjoin('billing_addresses', 'billing_addresses.subscription_id', '=', 'subscriptions.id')
				->leftjoin('organization_packages', 'subscriptions.id', '=', 'organization_packages.subscription_id')
				->leftjoin('packages', 'packages.id', '=', 'organization_packages.package_id')
				->where('subscriptions.id', $last_subscription_id)->first();

				/*$data = ['transaction_id' => $subscription->transaction_id, 'order_id' => $subscription->order_id, 'total_price' => $subscription->total_price, 'name' => $subscription->name, 'address' => $subscription->door.', '.$subscription->street.', '.$subscription->area, 'city' => $subscription->city, 'state' => $subscription->state, 'added_on' => $subscription->added_on, 'expire_on' => $subscription->expire_on, 'pin' => $subscription->pin, 'report' => json_decode($subscription->price_report, true)];*/


				$data = ['transaction_id' => $subscription->transaction_id, 'order_id' => $subscription->order_id, 'total_price' => $subscription->total_price, 'name' => $subscription->name, 'address' => $subscription->door.', '.$subscription->street.', '.$subscription->area, 'city' => $subscription->city, 'state' => $subscription->state, 'added_on' => $subscription->added_on, 'expire_on' => $subscription->expire_on, 'pin' => $subscription->pin, 'item' => $subscription->package_name];


				$email = $subscription->email_address;
				$name = $subscription->name;

				$subject = "Your subscription #".$subscription->order_id." is confirmed. Copy of invoice included.";

				Mail::send('emails.mail_subscription_invoice', $data, function ($message) use ($email, $name, $subject) {
					$message->from('support@propelsoft.in', 'PropelERP');
					$message->to($email, $name);
					$message->subject($subject);
				});

				Session::forget('last_subscription_id');

				return redirect()->route('subscription');
			}

			else {
				if($package_id != null) {
					
					$organization_package = OrganizationPackage::where('organization_id', $organization_id)->first();
					$organization_package->status = 0;
					$organization_package->save();

					$subscription = Subscription::findOrFail($last_subscription_id);
					$subscription->payment_status = 0;
					$subscription->save();
				}

				return redirect()->route('subscription')->WithErrors(['Subscription error' => 'Subscription Failed.']);
			}

		}


	}

	
}
