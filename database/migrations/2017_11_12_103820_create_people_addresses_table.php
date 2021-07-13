<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_addresses', function (Blueprint $table) {
           $table->increments('id');
            $table->integer('people_id', false)->unsigned()->nullable();
            $table->integer('address_type', false)->default(0)->comment('0 - Billing Address, 1 - Shipping Address');
            $table->string('address')->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->string('pin')->nullable();
            $table->string('landmark')->nullable();
            $table->string('google')->nullable();
            $table->integer('status', false)->default(1);
            $table->timestamps();

            $table->foreign('people_id')->references('id')->on('people')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_addresses');
    }
}
