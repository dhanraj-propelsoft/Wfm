<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::connection('mysql2')->create('task_flows', function($table){
        $table->increments('id');
        $table->integer('task_id', false);
        $table->integer('task_action_id');
        $table->integer('task_status_id');
        $table->integer('status', false);
        $table->integer('organization_id')->unsigned()->nullable();
        $table->integer('created_by', false)->unsigned()->nullable();
        $table->integer('last_modified_by', false)->unsigned()->nullable();
        $table->integer('deleted_by', false)->unsigned()->nullable();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable(); 

        $table->foreign('task_id')->references('id')->on('mysql2.tasks')->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('task_action_id')->references('id')->on('mysql2.task_actions')->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('task_status_id')->references('id')->on('mysql2.task_statuses')->onUpdate('cascade')->onDelete('cascade');
        $table->foreign('organization_id')->references('id')->on('mysql.organizations')->onUpdate('cascade')->onDelete('cascade');
        
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

        Schema::connection('mysql2')->dropIfExists('task_flows');
    }
}
