<?php

use Illuminate\Database\Seeder;
use App\SubscriptionPlan;
use App\Addon;

class SubscriptionAddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {      
        $free = SubscriptionPlan::where('name', 'Free14Days')->first();
        $starter = SubscriptionPlan::where('name', 'Starter')->first();
        $lite = SubscriptionPlan::where('name', 'Lite')->first();
        $standard = SubscriptionPlan::where('name', 'Standard')->first();
        $professional = SubscriptionPlan::where('name', 'Professional')->first();
        $enterprise = SubscriptionPlan::where('name', 'Enterprise')->first();
        $corporate = SubscriptionPlan::where('name', 'Corporate')->first();

        $ledgers = Addon::where('name', 'records')->first();
        $sms = Addon::where('name', 'sms')->first();
        $employee = Addon::where('name', 'employee')->first();
        $customer = Addon::where('name', 'customer')->first();
        $supplier = Addon::where('name', 'supplier')->first();
        $purchase = Addon::where('name', 'purchase')->first();
        $invoice = Addon::where('name', 'invoice')->first();
        $grn = Addon::where('name', 'grn')->first();
        $vehicles = Addon::where('name', 'vehicles')->first();
        $job_card = Addon::where('name', 'job_card')->first();
        $transaction = Addon::where('name', 'transaction')->first();
        $print_template = Addon::where('name', 'print_template')->first();
        $storage = Addon::where('name', 'storage')->first();
        $call_in_hour = Addon::where('name', 'call_in_hour')->first();


        DB::table('subscription_addons')->insert([

        	[
                'subscription_plan_id' => $free->id,
                'addon_id' => $ledgers->id,
                'value' => '20'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $sms->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $employee->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $customer->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $supplier->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $purchase->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $invoice->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $grn->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $vehicles->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $job_card->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $transaction->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $storage->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $free->id,
                'addon_id' => $call_in_hour->id,
                'value' => '1'                
        	],




        	[
                'subscription_plan_id' => $starter->id,
                'addon_id' => $ledgers->id,
                'value' => '200'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $sms->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $employee->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $customer->id,
                'value' => '20'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $supplier->id,
                'value' => '5'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $purchase->id,
                'value' => '20'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $invoice->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $grn->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $vehicles->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $job_card->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $transaction->id,
                'value' => '500'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $storage->id,
                'value' => '153'                
            ],
            [
                'subscription_plan_id' => $starter->id,
                'addon_id' => $call_in_hour->id,
                'value' => '2'                
        	],




        	[
                'subscription_plan_id' => $lite->id,
                'addon_id' => $ledgers->id,
                'value' => '200'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $sms->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $employee->id,
                'value' => '2'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $customer->id,
                'value' => '20'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $supplier->id,
                'value' => '5'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $purchase->id,
                'value' => '20'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $invoice->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $grn->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $vehicles->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $job_card->id,
                'value' => '50'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $transaction->id,
                'value' => '500'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $storage->id,
                'value' => '153'                
            ],
            [
                'subscription_plan_id' => $lite->id,
                'addon_id' => $call_in_hour->id,
                'value' => '2'                
        	],





        	[
                'subscription_plan_id' => $standard->id,
                'addon_id' => $ledgers->id,
                'value' => '500'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $sms->id,
                'value' => '3003'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $employee->id,
                'value' => '5'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $customer->id,
                'value' => '250'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $supplier->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $purchase->id,
                'value' => '100'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $invoice->id,
                'value' => '1000'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $grn->id,
                'value' => '100'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $vehicles->id,
                'value' => '500'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $job_card->id,
                'value' => '1000'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $transaction->id,
                'value' => '10000'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $storage->id,
                'value' => '3003'                
            ],
            [
                'subscription_plan_id' => $standard->id,
                'addon_id' => $call_in_hour->id,
                'value' => '5'                
        	],





        	[
                'subscription_plan_id' => $professional->id,
                'addon_id' => $ledgers->id,
                'value' => '1000'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $sms->id,
                'value' => '6003'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $employee->id,
                'value' => '10'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $customer->id,
                'value' => '500'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $supplier->id,
                'value' => '25'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $purchase->id,
                'value' => '250'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $invoice->id,
                'value' => '2000'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $grn->id,
                'value' => '250'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $vehicles->id,
                'value' => '1000'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $job_card->id,
                'value' => '2000'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $transaction->id,
                'value' => '50000'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $storage->id,
                'value' => '6003'                
            ],
            [
                'subscription_plan_id' => $professional->id,
                'addon_id' => $call_in_hour->id,
                'value' => '10'                
        	],





        	[
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $ledgers->id,
                'value' => '1000'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $sms->id,
                'value' => '15003'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $employee->id,
                'value' => '25'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $customer->id,
                'value' => '1500'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $supplier->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $purchase->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $invoice->id,
                'value' => '5000'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $grn->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $vehicles->id,
                'value' => '2500'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $job_card->id,
                'value' => '5000'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $transaction->id,
                'value' => '100000'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $print_template->id,
                'value' => '0'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $storage->id,
                'value' => '15003'                
            ],
            [
                'subscription_plan_id' => $enterprise->id,
                'addon_id' => $call_in_hour->id,
                'value' => '25'                
        	],




        	[
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $ledgers->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $sms->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $employee->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $customer->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $supplier->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $purchase->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $invoice->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $grn->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $vehicles->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $job_card->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $transaction->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $print_template->id,
                'value' => '3'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $storage->id,
                'value' => '99999'                
            ],
            [
                'subscription_plan_id' => $corporate->id,
                'addon_id' => $call_in_hour->id,
                'value' => '99999'                
        	]



        ]);
    
	}
}
