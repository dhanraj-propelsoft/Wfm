<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PersonAddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\PersonAddresstype::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('person_address_types')->insert([
            [
                'name' => 'residential',
                'display_name' => 'Residential',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'office',
                'display_name' => 'Office',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
