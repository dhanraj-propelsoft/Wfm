<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HrmRecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hrm_recruitment_statuses')->insert([
        	[
        		'recruitment_status'=>'New',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status'=>'Progress',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status'=>'Passed',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status'=>'Failed',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status' =>'Offered',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status' =>'Recruited',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'recruitment_status' =>'Discarded',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
            [
                'recruitment_status' =>'On hold',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]

        ]);
    }
}
