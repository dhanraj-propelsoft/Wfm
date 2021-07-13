<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaveSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mysql2')->create('save_searches', function($table){

            $table->increments('id');
            $table->string('search_name',55)->nullable();
            $table->string('parameters')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
      
            $table->foreign('created_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('last_modified_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('save_searches');
    }
}
