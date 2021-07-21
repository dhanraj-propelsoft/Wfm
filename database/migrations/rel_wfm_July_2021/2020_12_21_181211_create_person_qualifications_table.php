<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_qualifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable();
            $table->integer('qualification_id', false)->unsigned()->nullable();
            $table->integer('year_of_passing');
            $table->integer('institution_id');           
            $table->integer('status', false)->default(1);
        //Common Fields and foreign keys
        $table->integer('created_by', false)->unsigned()->nullable();
        $table->integer('last_modified_by', false)->unsigned()->nullable();
        $table->timestamps();             
        $table->integer('deleted_by', false)->unsigned()->nullable();
        $table->softDeletes();
        //forign keys
        $table->foreign('person_id')->references('id')->on('persons')->onDelete('restrict');
        $table->foreign('qualification_id')->references('id')->on('education_qualifications')->onDelete('restrict');
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
        Schema::dropIfExists('person_qualifications');
    }
}
