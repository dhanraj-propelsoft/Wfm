<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_no');
            $table->string('reference_voucher')->nullable();
            $table->integer('reference_voucher_id', false)->unsigned()->nullable();
            $table->string('gen_no');
            $table->date('date')->nullable();
            $table->integer('voucher_id', false)->unsigned();
            $table->integer('payment_mode_id', false)->unsigned()->nullable();
            $table->integer('cheque_book_id', false)->nullable();
            $table->string('cheque_no')->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->unique(array('voucher_no','organization_id','user_id'));

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('voucher_id')->references('id')->on('account_vouchers');

            $table->foreign('organization_id')->references('id')->on('organizations');

            $table->foreign('payment_mode_id')->references('id')->on('payment_modes');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('reference_voucher_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');
            $table->string('grn_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_entries');
    }
}
