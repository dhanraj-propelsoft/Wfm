<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTaskProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_task_progresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_details_id', false)->unsigned();
            $table->date('date')->nullable();
            $table->string('comment')->nullable(255);
            $table->integer('action_id', false)->unsigned()->nullable();
            $table->integer('progress_id', false)->unsigned()->nullable();
            $table->integer('status', false)->unsigned()->nullable();
            $table->integer('is_assigned_myself', false)->unsigned()->nullable()->comment('0-No,1-Yes');

            $table->integer('status_id', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
          

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('action_id')->references('id')->on('wfm_task_statuses')->onDelete('set null');
            $table->foreign('progress_id')->references('id')->on('wfm_task_statuses')->onDelete('set null');
         
         
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
        Schema::dropIfExists('wfm_task_progresses');
    }
}
