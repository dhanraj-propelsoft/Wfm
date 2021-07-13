<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountFormatSeparatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_format_separator', function (Blueprint $table) {
            $table->integer('format_id')->unsigned();
            $table->integer('separator_id')->unsigned();
            $table->integer('order', false)->nullable()->comment('Order arrangement of separators');
            $table->integer('value', false)->nullable()->comment('Preceding value before auto number');

            $table->foreign('format_id')->references('id')->on('account_voucher_formats')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('separator_id')->references('id')->on('account_voucher_separators')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([ 'format_id', 'separator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_format_separator');
    }
}
