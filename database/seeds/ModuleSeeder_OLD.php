<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ModuleSeeder_OLD extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\Module::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('modules')->insert([
        	[
	            'name' => 'super_admin',
	            'display_name' => 'Admin',
	            'description' => 'Access to every module in the whole application.',
                'status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'books',
                'display_name' => 'Books',
                'description' => 'Access to the Accounts Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm',
                'display_name' => 'HRM',
                'description' => 'Access to the HRM Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'inventory',
                'display_name' => 'Inventory',
                'description' => 'Access to the Inventory Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales',
                'display_name' => 'Sales',
                'description' => 'Access to the Sales Module.',
                'status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'trade',
                'display_name' => 'Trade',
                'description' => 'Access to the Trade Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project',
                'display_name' => 'Project',
                'description' => 'Access to the Project Module.',
                'status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'workshop',
                'display_name' => 'Workshop',
                'description' => 'Access to the Workshop Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'trade_wms',
                'display_name' => 'Trade-WMS',
                'description' => 'Access to the Trade WMS Module.',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
