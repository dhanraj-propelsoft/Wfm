<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AddonPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addon_pricings')->insert([
            [
                'name' => '0-200',
                'value' => '200',
                'price' => '25',
                'addon_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-500',
                'value' => '500',
                'price' => '50',
                'addon_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-1000',
                'value' => '1000',
                'price' => '100',
                'addon_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10000',
                'value' => '10000',
                'price' => '200',
                'addon_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-153',
                'value' => '153',
                'price' => '25',
                'addon_id' => '2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-3003',
                'value' => '3003',
                'price' => '50',
                'addon_id' => '2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-6003',
                'value' => '6003',
                'price' => '100',
                'addon_id' => '2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-15003',
                'value' => '15003',
                'price' => '200',
                'addon_id' => '2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-2',
                'value' => '2',
                'price' => '25',
                'addon_id' => '3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-5',
                'value' => '5',
                'price' => '50',
                'addon_id' => '3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10',
                'value' => '10',
                'price' => '100',
                'addon_id' => '3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-25',
                'value' => '25',
                'price' => '200',
                'addon_id' => '3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => '0-20',
                'value' => '20',
                'price' => '25',
                'addon_id' => '4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-250',
                'value' => '250',
                'price' => '50',
                'addon_id' => '4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-500',
                'value' => '500',
                'price' => '100',
                'addon_id' => '4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-1500',
                'value' => '1500',
                'price' => '200',
                'addon_id' => '4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => '0-5',
                'value' => '5',
                'price' => '25',
                'addon_id' => '5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10',
                'value' => '10',
                'price' => '50',
                'addon_id' => '5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-25',
                'value' => '25',
                'price' => '100',
                'addon_id' => '5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '200',
                'addon_id' => '5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-20',
                'value' => '20',
                'price' => '25',
                'addon_id' => '6',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-100',
                'value' => '100',
                'price' => '50',
                'addon_id' => '6',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-250',
                'value' => '250',
                'price' => '100',
                'addon_id' => '6',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '200',
                'addon_id' => '6',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '6',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-50',
                'value' => '50',
                'price' => '25',
                'addon_id' => '7',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-1000',
                'value' => '1000',
                'price' => '50',
                'addon_id' => '7',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-2000',
                'value' => '2000',
                'price' => '100',
                'addon_id' => '7',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-5000',
                'value' => '99999',
                'price' => '200',
                'addon_id' => '7',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '7',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => '0-20',
                'value' => '20',
                'price' => '25',
                'addon_id' => '8',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-100',
                'value' => '100',
                'price' => '50',
                'addon_id' => '8',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-250',
                'value' => '250',
                'price' => '100',
                'addon_id' => '8',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '200',
                'addon_id' => '8',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '8',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => '0-50',
                'value' => '50',
                'price' => '25',
                'addon_id' => '9',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-500',
                'value' => '500',
                'price' => '50',
                'addon_id' => '9',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-1000',
                'value' => '1000',
                'price' => '100',
                'addon_id' => '9',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-2500',
                'value' => '2500',
                'price' => '200',
                'addon_id' => '9',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '9',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => '0-50',
                'value' => '50',
                'price' => '25',
                'addon_id' => '10',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-1000',
                'value' => '1000',
                'price' => '50',
                'addon_id' => '10',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-2000',
                'value' => '2000',
                'price' => '100',
                'addon_id' => '10',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-5000',
                'value' => '5000',
                'price' => '200',
                'addon_id' => '10',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '10',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-500',
                'value' => '500',
                'price' => '25',
                'addon_id' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10000',
                'value' => '10000',
                'price' => '50',
                'addon_id' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-25000',
                'value' => '50000',
                'price' => '100',
                'addon_id' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-50000',
                'value' => '50000',
                'price' => '200',
                'addon_id' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '11',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-153',
                'value' => '153',
                'price' => '25',
                'addon_id' => '13',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-3003',
                'value' => '3003',
                'price' => '50',
                'addon_id' => '13',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-6003',
                'value' => '6003',
                'price' => '100',
                'addon_id' => '13',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-15003',
                'value' => '99999',
                'price' => '200',
                'addon_id' => '13',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '13',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => '0-2',
                'value' => '2',
                'price' => '25',
                'addon_id' => '14',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-5',
                'value' => '5',
                'price' => '50',
                'addon_id' => '14',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-10',
                'value' => '10',
                'price' => '100',
                'addon_id' => '14',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-25',
                'value' => '25',
                'price' => '200',
                'addon_id' => '14',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => '0-99999',
                'value' => '99999',
                'price' => '500',
                'addon_id' => '14',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]



        ]);
    }
}
