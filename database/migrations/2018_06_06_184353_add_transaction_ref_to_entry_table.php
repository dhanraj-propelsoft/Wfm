<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionRefToEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_entries', function (Blueprint $table) {
             $table->integer('reference_transaction_id', false)->unsigned()->nullable();

             $table->foreign('reference_transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_entries', function (Blueprint $table) {
            $table->dropColumn('reference_transaction_id');
        });
    }
}
