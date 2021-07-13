<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLedgertypeGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgertype_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ledger_type_id', false)->unsigned();
            $table->integer('group_id', false)->unsigned();

            $table->foreign('ledger_type_id')->references('id')->on('account_ledger_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('account_groups')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgertype_group');
    }
}
