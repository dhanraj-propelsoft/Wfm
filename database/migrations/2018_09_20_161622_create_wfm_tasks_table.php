<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_code')->nullable(12);
            $table->string('task_name')->nullable(20);
            $table->string('task_details')->nullable(225);
            $table->integer('task_type', false)->default(0);
            $table->integer('priority_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('organization_id')->unsigned()->nullable();
            $table->date('create_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('size_id')->unsigned()->nullable();
            $table->integer('worth_id')->unsigned()->nullable();
            $table->string('status')->nullable()->default('Todo');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfm_tasks');
    }
}
