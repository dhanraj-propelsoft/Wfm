<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTaskDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_task_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->unsigned()->nullable();
            $table->integer('assigned_by')->nullable();
            $table->integer('assigned_to')->nullable();
            $table->integer('is_assigned_myself')->nullable();
            $table->string('comment')->nullable(225);
            $table->date('start_date')->nullable();
            $table->integer('status',false)->defalut(0  );
            $table->integer('size_id')->unsigned()->nullable();
            $table->integer('worth_id')->unsigned()->nullable();
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
        Schema::dropIfExists('wfm_task_details');
    }
}
