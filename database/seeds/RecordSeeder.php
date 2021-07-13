<?php

use Illuminate\Database\Seeder;
use App\PlanAccountType;
use App\Addon;
use Carbon\Carbon;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_type_id = PlanAccountType::where('name', 'business')->first();

        $records = Addon::where('name', 'records')->first();
        $sms = Addon::where('name', 'sms')->first();

        DB::table('records')->insert([
            [
                'name' => '0-500',
            	'display_name' => 'Ledger 0 - 500',
            	'size' => 500,
            	'price' => 50,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-2500',
            	'display_name' => 'Ledger 0 - 2500',
            	'size' => 2500,
            	'price' => 250,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10000',
            	'display_name' => 'Ledger 0 - 10000',
            	'size' => 10000,
            	'price' => 750,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-25000',
            	'display_name' => 'Ledger 0 - 25000',
            	'size' => 25000,
            	'price' => 1500,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-50000',
            	'display_name' => 'Ledger 0 - 50000',
            	'size' => 50000,
            	'price' => 2500,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-100000',
            	'display_name' => 'Ledger 0 - 100000',
            	'size' => 100000,
            	'price' => 4000,
            	'account_type_id' => $account_type_id->id,
                'addon_id' => $records->id,
            	'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '50',
                'display_name' => 'SMS 50',
                'size' => 50,
                'price' => 25,
                'account_type_id' => $account_type_id->id,
                'addon_id' => $sms->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
