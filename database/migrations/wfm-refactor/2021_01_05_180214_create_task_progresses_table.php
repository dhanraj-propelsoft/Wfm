<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('task_progresses', function($table){
            $table->bigIncrements('id')->unsigned();
            $table->integer('from_task_status_id', false)->unsigned()->nullable(false);
            $table->integer('task_action_id', false)->unsigned()->nullable(false);
            $table->integer('to_task_status_id', false)->unsigned()->nullable(false);
            $table->integer('status', false);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('from_task_status_id')->references('id')->on('mysql2.task_statuses')->onUpdate('cascade')->onDelete('set null');        ;
            
            $table->foreign('task_action_id')->references('id')->on('mysql2.task_actions')->onUpdate('cascade')->onDelete('set null');        ;

            $table->foreign('to_task_status_id')->references('id')->on('mysql2.task_statuses')->onUpdate('cascade')->onDelete('set null');        


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('task_progresses');
        
    }
}
