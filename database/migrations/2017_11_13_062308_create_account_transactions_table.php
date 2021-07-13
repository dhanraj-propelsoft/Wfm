<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('debit_ledger_id', false)->unsigned();
            $table->integer('credit_ledger_id', false)->unsigned();
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('entry_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('credit_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('debit_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
}
