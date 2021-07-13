<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PayHeadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hrm_pay_head_types')->insert([
            [
                'name' => 'earnings',
                'display_name' => 'Earnings',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'deductions',
                'display_name' => 'Deductions',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
