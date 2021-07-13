<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionShipmentMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_shipment_mode', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('shipment_mode_id', false)->unsigned()->nullable();
            $table->date('shipping_date')->nullable();
            $table->foreign('shipment_mode_id')->references('id')->on('shipment_modes')->onUpdate('cascade');
            $table->foreign('id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_shipment_mode');
    }
}
