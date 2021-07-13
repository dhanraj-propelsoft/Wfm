<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id', false)->unsigned()->nullable();
            $table->integer('transaction_id', false)->unsigned()->nullable();
            $table->string('value', false)->nullable();
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('transaction_fields')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_field_values');
    }
}
