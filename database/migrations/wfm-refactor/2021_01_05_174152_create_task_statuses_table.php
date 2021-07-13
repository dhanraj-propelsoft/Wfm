<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('task_statuses', function($table){
                $table->bigIncrements('id')->unsigned();
                $table->string('name')->nullable();
                $table->integer('status', false);
                $table->integer('created_by', false)->unsigned()->nullable();
                $table->integer('last_modified_by', false)->unsigned()->nullable();
                $table->integer('deleted_by', false)->unsigned()->nullable();
                $table->timestamps();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::connection('mysql2')->dropIfExists('task_statuses');
    }
}
