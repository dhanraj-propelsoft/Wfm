<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccountVoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_voucher_types')->insert([
            [
                'name' => 'payment',
                'display_name' => 'Payment',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'receipt',
                'display_name' => 'Receipt',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'journal',
                'display_name' => 'Journal',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll',
                'display_name' => 'Payroll',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'credit_note',
                'display_name' => 'Credit Note',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'debit_note',
                'display_name' => 'Debit Note',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'contra',
                'display_name' => 'Contra',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase_order',
                'display_name' => 'Purchase Order',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase',
                'display_name' => 'Purchase',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'goods_receipt_note',
                'display_name' => 'Goods Receipt Note',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sale_order',
                'display_name' => 'Sale Order',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales',
                'display_name' => 'Sales',
                /*'type' => 1,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'delivery_note',
                'display_name' => 'Delivery Note',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'special_memorandum_account',
                'display_name' => 'Special Memorandum Account',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'reversing_journal',
                'display_name' => 'Reversing Journal',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'stock_journal',
                'display_name' => 'Stock Journal',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'deposit',
                'display_name' => 'Deposit',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'withdrawal',
                'display_name' => 'Withdrawal',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job_card',
                'display_name' => 'Job Card',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'estimate',
                'display_name' => 'Estimate',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job_request',
                'display_name' => 'Job Estimation',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job_invoice',
                'display_name' => 'Job Invoice',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job_invoice_cash',
                'display_name' => 'Job Invoice Cash',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'wms_receipt',
                'display_name' => 'WMS Receipt',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job_status',
                'display_name' => 'Job Status',
                /*'type' => 0,*/
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
