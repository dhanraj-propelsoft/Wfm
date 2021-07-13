<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionItemStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_item_status', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('item_update_status', false)->default(0)->comment('0 - Not Updated, 1 - Updated');
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
        Schema::dropIfExists('transaction_item_status');
    }
}
