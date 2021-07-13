<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_voucher', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('voucher_id')->unsigned();


            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('voucher_id')->references('id')->on('account_vouchers')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['module_id', 'voucher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_voucher');
    }
}
