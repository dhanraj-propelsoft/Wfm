<?php

use Illuminate\Database\Seeder;
use App\PlanAccountType;
use Carbon\Carbon;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $personal_type_id = PlanAccountType::where('name', 'personal')->first();
        $business_type_id = PlanAccountType::where('name', 'business')->first();

        DB::table('subscription_plans')->insert([
            [
                'name' => 'Free14Days',
                'display_name' => 'Free14Days',
                'description' => 'Free14Days Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Starter',
                'display_name' => 'Starter',
                'description' => 'Starter Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Lite',
                'display_name' => 'Lite',
                'description' => 'Lite Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Standard',
                'display_name' => 'Standard',
                'description' => 'Standard Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Professional',
                'display_name' => 'Professional',
                'description' => 'Professional Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Enterprise',
                'display_name' => 'Enterprise',
                'description' => 'Enterprise Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Corporate',
                'display_name' => 'Corporate',
                'description' => 'Corporate Account',
                'account_type_id' => $business_type_id->id,
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
