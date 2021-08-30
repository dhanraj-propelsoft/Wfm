<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonKnownLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_known_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable(false); 
            $table->integer('language_id', false)->unsigned()->nullable(false); 
            $table->integer('language_status_id', false)->unsigned()->nullable(false); 
              //Common Fields and foreign keys
              $table->integer('created_by', false)->unsigned()->nullable();
              $table->integer('last_modified_by', false)->unsigned()->nullable();
              $table->timestamps();             
              $table->integer('deleted_by', false)->unsigned()->nullable();
              $table->softDeletes();
              //forign keys
              $table->foreign('person_id')->references('id')->on('persons')->onDelete('restrict');
              $table->foreign('language_id')->references('id')->on('languages')->onDelete('restrict');
              $table->foreign('language_status_id')->references('id')->on('language_statuses')->onDelete('restrict'); 
              $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
              $table->foreign('last_modified_by')->references('id')->on( 'users')->onDelete('restrict');
              $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_known_languages');
    }
}
