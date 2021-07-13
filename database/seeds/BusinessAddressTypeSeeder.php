<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BusinessAddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\BusinessAddressType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('business_address_types')->insert([
            [
                'name' => 'business',
                'display_name' => 'Business',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse',
                'display_name' => 'Warehouse',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
