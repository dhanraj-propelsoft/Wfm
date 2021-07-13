<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\FieldFormat;

class FieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         DB::table('field_formats')->insert([
            [
                'name' => 'date',
                'display_name' => 'Date',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            /*[
                'name' => 'time',
                'display_name' => 'Time',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'date_time',
                'display_name' => 'Date Time',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],*/
            [
                'name' => 'price',
                'display_name' => 'Price',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'decimal',
                'display_name' => 'Decimal',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'numbers',
                'display_name' => 'Number',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);

        $date = FieldFormat::where('name', 'date')->first()->id;
        //$time = FieldFormat::where('name', 'time')->first()->id;
        //$date_time = FieldFormat::where('name', 'date_time')->first()->id;
        $price = FieldFormat::where('name', 'price')->first()->id;
		$decimal = FieldFormat::where('name', 'decimal')->first()->id;
		$number = FieldFormat::where('name', 'numbers')->first()->id;


        DB::table('field_types')->insert([
            [
                'name' => 'textbox',
                'display_name' => 'Single Line',
                'field_format_id' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'textarea',
                'display_name' => 'Multi Line',
                'field_format_id' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'textbox',
                'display_name' => 'Date',
                'field_format_id' => $date,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            /*[
                'name' => 'textbox',
                'display_name' => 'Time',
                'field_format_id' => $time,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'textbox',
                'display_name' => 'Date Time',
                'field_format_id' => $date_time,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],*/
            [
                'name' => 'textbox',
                'display_name' => 'Rate',
                'field_format_id' => $price,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            /*[
                'name' => 'textbox',
                'display_name' => 'Decimal',
                'field_format_id' => $decimal,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],*/
            [
                'name' => 'textbox',
                'display_name' => 'Number',
                'field_format_id' => $number,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            /*[
                'name' => 'select',
                'display_name' => 'Drop Down',
                'field_format_id' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'radio',
                'display_name' => 'Radio',
                'field_format_id' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'checkbox',
                'display_name' => 'Checkbox',
                'field_format_id' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]*/
        ]);
    }
}
