<?php

use Illuminate\Database\Seeder;
use App\Package;
use App\Module;
use App\SubscriptionPlan;
use App\PlanAccountType;

class PackageSeeder_OLD extends Seeder
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

        $workshop = new Package;
        $workshop->name = 'workshop';
        $workshop->display_name = 'Workshop';
        $workshop->account_type_id = $business_account;
        $workshop->save();

        $trade_wms = new Package;
        $trade_wms->name = 'trade_wms';
        $trade_wms->display_name = 'Trade-WMS';
        $trade_wms->account_type_id = $business_account;
        $trade_wms->save();

        $professional = SubscriptionPlan::where('name', 'professional')->first();
        $enterprise = SubscriptionPlan::where('name', 'enterprise')->first();

        $account_module = Module::where('name', 'books')->first();
        $hrm_module = Module::where('name', 'hrm')->first();
        $inventory_module = Module::where('name', 'inventory')->first();
        $trade_module = Module::where('name', 'trade')->first();
        $workshop_module = Module::where('name', 'workshop')->first();
        $trade_wms_module = Module::where('name', 'trade_wms')->first();

        DB::table('package_plan')->insert([
            [
                'package_id' => $book->id,
            	'plan_id' => $professional->id,
            	'price' => 100
            ],
            [
                'package_id' => $book->id,
            	'plan_id' => $enterprise->id,
            	'price' => 125
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $professional->id,
                'price' => 100
            ],
            [
                'package_id' => $hrm->id,
                'plan_id' => $enterprise->id,
                'price' => 125
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $professional->id,
                'price' => 100
            ],
            [
                'package_id' => $inventory->id,
                'plan_id' => $enterprise->id,
                'price' => 125
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $professional->id,
                'price' => 100
            ],
            [
                'package_id' => $trade->id,
                'plan_id' => $enterprise->id,
                'price' => 125
            ],
            [
                'package_id' => $workshop->id,
                'plan_id' => $professional->id,
                'price' => 100
            ],
            [
                'package_id' => $workshop->id,
                'plan_id' => $enterprise->id,
                'price' => 125
            ],
            [
                'package_id' => $trade_wms->id,
                'plan_id' => $professional->id,
                'price' => 100
            ],
            [
                'package_id' => $trade_wms->id,
                'plan_id' => $enterprise->id,
                'price' => 125
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
                'module_id' => $inventory_module->id
            ],
            [
                'package_id' => $trade->id,
                'module_id' => $trade_module->id
            ],
            [
                'package_id' => $trade_wms->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $trade_wms->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $trade_wms->id,
                'module_id' => $inventory_module->id
            ],
            [
                'package_id' => $trade_wms->id,
                'module_id' => $trade_module->id
            ],
            [
                'package_id' => $trade_wms->id,
                'module_id' => $trade_wms_module->id
            ],
            [
                'package_id' => $workshop->id,
                'module_id' => $account_module->id
            ],
            [
                'package_id' => $workshop->id,
                'module_id' => $hrm_module->id
            ],
            [
                'package_id' => $workshop->id,
                'module_id' => $inventory_module->id
            ],
            [
                'package_id' => $workshop->id,
                'module_id' => $trade_module->id
            ],
            [
                'package_id' => $workshop->id,
                'module_id' => $trade_wms_module->id
            ],            
            [
                'package_id' => $workshop->id,
                'module_id' => $workshop_module->id
            ]         

        ]);
    }
}
