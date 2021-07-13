<?php

use Illuminate\Database\Seeder;
use App\Package;
use App\Module;
use App\SubscriptionPlan;
use App\PlanAccountType;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business_account = PlanAccountType::where('name', 'business')->first()->id;
        
        $book = new Package;
        $book->name = 'books';
        $book->display_name = 'Books';
        $book->account_type_id = $business_account;
        $book->save();

        $hrm = new Package;
        $hrm->name = 'hrm';
        $hrm->display_name = 'HRM';
        $hrm->account_type_id = $business_account;
        $hrm->save();

        $wfm = new Package;
        $wfm->name = 'wfm';
        $wfm->display_name = 'WFM';
        $wfm->account_type_id = $business_account;
        $wfm->save();

        $inventory = new Package;
        $inventory->name = 'inventory';
        $inventory->display_name = 'Inventory';
        $inventory->account_type_id = $business_account;
        $inventory->save();

        $trade = new Package;
        $trade->name = 'trade';
        $trade->display_name = 'Trade';
        $trade->account_type_id = $business_account;
        $trade->save();
        

        $wms = new Package;
        $wms->name = 'trade_wms';
        $wms->display_name = 'WMS';
        $wms->account_type_id = $business_account;
        $wms->save();

        $inventory_trade = new Package;
        $inventory_trade->name = 'inventory_trade';
        $inventory_trade->display_name = 'Inventory + Trade';
        $inventory_trade->account_type_id = $business_account;
        $inventory_trade->save();

        $inventory_wms = new Package;
        $inventory_wms->name = 'inventory_wms';
        $inventory_wms->display_name = 'Inventory + WMS';
        $inventory_wms->account_type_id = $business_account;
        $inventory_wms->save();

        

        $free = SubscriptionPlan::where('name', 'Free14Days')->first();
        $starter = SubscriptionPlan::where('name', 'Starter')->first();
        $lite = SubscriptionPlan::where('name', 'Lite')->first();
        $standard = SubscriptionPlan::where('name', 'Standard')->first();
        $professional = SubscriptionPlan::where('name', 'Professional')->first();
        $enterprise = SubscriptionPlan::where('name', 'Enterprise')->first();
        $corporate = SubscriptionPlan::where('name', 'Corporate')->first();

        $account_module = Module::where('name', 'books')->first();
        $hrm_module = Module::where('name', 'hrm')->first();
        $wfm_module = Module::where('name', 'wfm')->first();
        $inventory_module = Module::where('name', 'inventory')->first();
        $trade_module = Module::where('name', 'trade')->first();
        $wms_module = Module::where('name', 'trade_wms')->first();       



        DB::table('package_plan')->insert([
            [
                'package_id' => $book->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $book->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],


            [
                'package_id' => $hrm->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],


            [
                'package_id' => $wfm->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $wfm->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],        


            [
                'package_id' => $inventory->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],      


            [
                'package_id' => $trade->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],
            
            
            [
                'package_id' => $wms->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $wms->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],



            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $inventory_trade->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ],


            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $free->id,
                'price' => 0
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $starter->id,
                'price' => 50
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $lite->id,
                'price' => 100
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $standard->id,
                'price' => 150
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $professional->id,
                'price' => 200
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $enterprise->id,
                'price' => 250
            ],
            [
                'package_id' => $inventory_wms->id,
                'plan_id' => $corporate->id,
                'price' => 300
            ]
            
            
        ]);

        DB::table('package_modules')->insert([
            [
                'package_id' => $book->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $hrm->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $hrm->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $wfm->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $wfm->id,
                'module_id' => $wfm_module->id
            ],
            [
                'package_id' => $inventory->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $inventory->id,
                'module_id' => $hrm_module->id
            ],            
            [
                'package_id' => $inventory->id,
                'module_id' => $inventory_module->id
            ],
            [
                'package_id' => $trade->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $trade->id,
                'module_id' => $hrm_module->id
            ], 
            [
                'package_id' => $trade->id,
                'module_id' => $trade_module->id
            ],
            [
                'package_id' => $wms->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $wms->id,
                'module_id' => $hrm_module->id
            ],            
            [
                'package_id' => $wms->id,
                'module_id' => $wms_module->id
            ],
            
            [
                'package_id' => $inventory_trade->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $inventory_trade->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $inventory_trade->id,
                'module_id' => $trade_module->id
            ],
            [
                'package_id' => $inventory_trade->id,
                'module_id' => $inventory_module->id
            ],

            [
                'package_id' => $inventory_wms->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $inventory_wms->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $inventory_wms->id,
                'module_id' => $wms_module->id
            ],
            [
                'package_id' => $inventory_wms->id,
                'module_id' => $inventory_module->id
            ]           

        ]);
    }
}
