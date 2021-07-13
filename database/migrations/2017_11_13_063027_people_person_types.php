<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PeoplePersonTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_person_types', function (Blueprint $table) {
            $table->integer('people_id')->unsigned();
            $table->integer('person_type_id')->unsigned();


            $table->foreign('people_id')->references('id')->on('people')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('person_type_id')->references('id')->on('account_person_types')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['people_id', 'person_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_person_types');
    }
}
