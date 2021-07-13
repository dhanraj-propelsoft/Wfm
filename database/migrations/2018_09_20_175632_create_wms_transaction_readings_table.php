<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWmsTransactionReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wms_transaction_readings', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('division_master_id', false)->unsigned()->nullable();
            $table->integer('reading_factor_id', false)->unsigned()->nullable();
            $table->integer('reading_values', false)->nullable();
            $table->integer('reading_calculation', false)->nullable();
            $table->string('reading_notes')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('division_master_id')->references('id')->on('wms_applicable_divisions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('reading_factor_id')->references('id')->on('wms_reading_factors')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wms_transaction_readings');
    }
}
