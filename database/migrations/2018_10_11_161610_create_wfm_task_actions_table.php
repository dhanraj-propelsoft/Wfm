<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTaskActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_task_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('progress_status_id')->unsigned()->nullable();
            $table->integer('action_id')->unsigned()->nullable();
            $table->integer('user_type')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('progress_status_id')->references('id')->on('wfm_statuses')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('action_id')->references('id')->on('wfm_actions')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('status_id')->references('id')->on('wfm_statuses')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfm_task_actions');
    }
}
