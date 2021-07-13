<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BusinessProfessionalismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_professionalisms')->insert([
            [
                'name' => 'tyre',
                'display_name' => 'Tyre',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'battery',
                'display_name' => 'Battery',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
