<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTaskSubstatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_task_substatuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_status_id')->unsigned()->nullable();
            $table->string('Task_substatus_name')->nullable(20);
            $table->string('task_substatus_label')->nullable();
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('status', false)->default(1);
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
        Schema::dropIfExists('wfm_task_substatuses');
    }
}
