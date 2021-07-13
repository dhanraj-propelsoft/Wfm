<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_discount', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->decimal('discount', 10, 2)->nullable();
            $table->integer('discount_is_percent', false)->nullable();
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
        Schema::dropIfExists('transaction_discount');
    }
}
