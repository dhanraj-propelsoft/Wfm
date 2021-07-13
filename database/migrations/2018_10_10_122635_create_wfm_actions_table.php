<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_action')->nullable();
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
        Schema::dropIfExists('wfm_actions');
    }
}
