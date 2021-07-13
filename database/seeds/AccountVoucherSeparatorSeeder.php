<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountVoucherSeparatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_voucher_separators')->insert([
            [
                'name' => 'auto_number',
                'display_name' => 'Auto gen Number',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'financial_year',
                'display_name' => 'Financial Year',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher_code',
                'display_name' => 'Voucher Code',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
