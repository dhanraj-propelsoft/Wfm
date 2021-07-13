<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comments')->nullable();
            $table->string('commenter_name')->nullable(25);
            $table->integer('parent_id',false)->default(0);
            $table->integer('employee_id',false)->unsigned()->nullable();
            $table->integer('task_id',false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('wfm_tasks')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('set null');
            
            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('wfm_comments');
    }
}
