<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_person', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('person_id')->unsigned()->nullable();
            $table->foreign('id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_person');
    }
}
