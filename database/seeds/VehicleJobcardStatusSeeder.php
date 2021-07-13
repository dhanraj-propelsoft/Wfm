<?php

use Illuminate\Database\Seeder;

class VehicleJobcardStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_jobcard_statuses')->insert([
            [
                'name' => 'New',
            	'display_name' => 'New',
            	'description' => 'New',            	
            	'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'First Inspected',
                'display_name' => 'First Inspected',
                'description' => 'First Inspected',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Estimation Pending',
            	'display_name' => 'Estimation Pending',
            	'description' => 'Estimation Pending',            	
            	'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Estimation Approved',
                'display_name' => 'Estimation Approved',
                'description' => 'Estimation Approved',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Work in Progress',
                'display_name' => 'Work in Progress',
                'description' => 'Work in Progress',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Final Inspected',
                'display_name' => 'Final Inspected',
                'description' => 'Final Inspected',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Vehicle Ready',
                'display_name' => 'Vehicle Ready',
                'description' => 'Vehicle Ready',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Closed',
                'display_name' => 'Closed',
                'description' => 'Closed',                
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
