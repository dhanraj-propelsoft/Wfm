<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_works', function (Blueprint $table) {
            $table->integer('job_type_id')->unsigned();
            $table->integer('item_id')->unsigned();

            $table->foreign('job_type_id')->references('id')->on('job_types')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('item_id')->references('id')->on('inventory_items')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['job_type_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_works');
    }
}
