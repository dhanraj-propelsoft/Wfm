<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('subscription_types')->insert([
			[
				'name' => 'package',
				'display_name' => 'Package',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			],
			[
				'name' => 'addon',
				'display_name' => 'Addon',
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			]
		]);
	}
}
