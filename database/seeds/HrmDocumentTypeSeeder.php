<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class HrmDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hrm_document_types')->insert([

        	[
        		'name' =>'Leave and Permission',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' =>'Procurement',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' =>'Code and Conduction',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' =>'Separation',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' =>'PHP Training',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' =>'Others',
        		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	]

        ]);
    }
}
