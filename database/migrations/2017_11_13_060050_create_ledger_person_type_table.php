<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerPersonTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_person_types', function (Blueprint $table) {
            $table->integer('ledger_id')->unsigned();
            $table->integer('person_type_id')->unsigned();


            $table->foreign('ledger_id')->references('id')->on('account_ledgers')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('person_type_id')->references('id')->on('account_person_types')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['ledger_id', 'person_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_person_type');
    }
}
