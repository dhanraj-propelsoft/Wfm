<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('code');
            $table->integer('voucher_type_id', false)->unsigned()->nullable();
            $table->integer('date_setting', false)->default(0)->comment('0 - Current Date, 1 - Custom Date');
            $table->integer('format_id', false)->unsigned()->nullable();
            $table->integer('print_id', false)->unsigned()->nullable();
            $table->integer('debit_ledger_id', false)->unsigned()->nullable();
            $table->integer('credit_ledger_id', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->integer('starting_value', false)->default(1);
            $table->integer('type', false)->nullable()->comment('0 - Expense, 1 - Income');
            $table->integer('account_status', false)->default(1)->comment('0 - Don\'t show in books module, 1 - Show in books module');
            $table->integer('delete_status', false)->default(1)->comment('0 - Non Deletable, 1 - Deletable');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->unique([ 'name', 'organization_id']);

            
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('voucher_type_id')->references('id')->on('account_voucher_types');

            $table->foreign('format_id')->references('id')->on('account_voucher_formats')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('print_id')->references('id')->on('print_templates')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('debit_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('credit_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_vouchers');
    }
}
