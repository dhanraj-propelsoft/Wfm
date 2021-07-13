<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReferenceVoucherSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('reference_vouchers')->insert([
			[
				'name' => 'direct',
				'display_name' => 'Direct',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'purchase_order',
				'display_name' => 'Purchase Order',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'purchases',
				'display_name' => 'Purchase',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'goods_receipt_note',
				'display_name' => 'Goods Receipt Note',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'debit_note',
				'display_name' => 'Debit Note',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'estimation',
				'display_name' => 'Estimation',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'sale_order',
				'display_name' => 'Sale Order',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'sales',
				'display_name' => 'Invoice',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'job_invoice',
				'display_name' => 'Job Invoice',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'job_request',
				'display_name' => 'Job Request',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'delivery_note',
				'display_name' => 'Delivery Note',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'credit_note',
				'display_name' => 'Credit Note',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			]
		]);
	}
}
