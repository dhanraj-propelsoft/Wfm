<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionAccountEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_entries', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('entry_id', false)->unsigned()->nullable();

            $table->foreign('entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('transaction_entries');
    }
}
