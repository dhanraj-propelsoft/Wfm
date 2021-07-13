<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskWorkforcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::connection('mysql2')->create('task_workforces', function($table){
        $table->increments('id');
        $table->integer('task_id', false);
        $table->integer('workforcer_id');
        $table->integer('status', false);
        $table->integer('created_by', false)->unsigned()->nullable();
        $table->integer('last_modified_by', false)->unsigned()->nullable();
        $table->integer('deleted_by', false)->unsigned()->nullable();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable(); 

        $table->foreign('task_id')->references('id')->on('mysql2.tasks')->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('workforce_id')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('cascade');

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
        
        Schema::connection('mysql2')->dropIfExists('task_workforces');
    }
}
