<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmSaveSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_save_searches', function (Blueprint $table) {
            $table->increments('id');
             $table->string('search_name',55)->nullable();
            $table->string('parameters')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
      

        

            $table->foreign('created_by')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('set null');
       

            $table->foreign('last_modified_by')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfm_save_searches');
    }
}
