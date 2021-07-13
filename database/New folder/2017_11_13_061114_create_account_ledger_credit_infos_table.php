<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLedgerCreditInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledger_credit_infos', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('credit_period', false)->nullable();
            $table->decimal('min_debit_limit', 10, 2)->nullable();
            $table->decimal('max_debit_limit', 10, 2)->nullable();
            $table->decimal('min_credit_limit', 10, 2)->nullable();
            $table->decimal('max_credit_limit', 10, 2)->nullable();
            $table->integer('warning_status', false)->default(0)->nullable()->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledger_credit_infos');
    }
}
