<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingCityIdToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
    
                 //columns
                 $table->integer('billing_city_id', false)->unsigned()->nullable();
                 $table->string('billing_pincode', 8)->nullable();
                 
                 $table->integer('shipping_city_id', false)->unsigned()->nullable();
                 $table->string('shipping_pincode', 8)->nullable();
     
                 // foreign key Relationship
                 $table->foreign('billing_city_id')->references('id')->on('cities')
                 ->onUpdate('cascade')->onDelete('set null');
                 $table->foreign('shipping_city_id')->references('id')->on('cities')
                 ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
           
            // 1. Drop foreign key constraints
            // $table->dropForeign(['billing_city_id']);
            // $table->dropForeign(['shipping_city_id']);

            // 2. Drop the column
            $table->dropColumn('billing_city_id');
            $table->dropColumn('billing_pin_code');
            $table->dropColumn('shipping_city_id');
            $table->dropColumn('shipping_pin_code');
        });
    }

    
}
