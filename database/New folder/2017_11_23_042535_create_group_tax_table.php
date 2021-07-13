<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_tax', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('tax_id')->unsigned();


            $table->foreign('group_id')->references('id')->on('tax_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['group_id', 'tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_taxes');
    }
}
