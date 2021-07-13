<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TermPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('term_periods')->insert([
            [
                'name' => 'half_yearly',
                'display_name' => 'Half-yearly',
                'account_type_id' => 1,
                'discount' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'annually',
                'display_name' => 'Annually',
                'account_type_id' => 1,
                'discount' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'quarterly',
                'display_name' => 'Quarterly',
                'account_type_id' => 2,
                'discount' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'half_yearly',
                'display_name' => 'Half-yearly',
                'account_type_id' => 2,
                'discount' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'annually',
                'display_name' => 'Annually',
                'account_type_id' => 2,
                'discount' => 20,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
