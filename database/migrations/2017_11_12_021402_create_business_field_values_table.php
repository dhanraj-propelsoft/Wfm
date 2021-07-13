<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_field_values', function (Blueprint $table) {
            $table->integer('businesses_id')->unsigned();
            $table->integer('business_field_id')->unsigned();
            $table->string('business_information');


            $table->foreign('businesses_id')->references('id')->on('businesses')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('business_field_id')->references('id')->on('business_fields')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([ 'businesses_id', 'business_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_field_values');
    }
}
