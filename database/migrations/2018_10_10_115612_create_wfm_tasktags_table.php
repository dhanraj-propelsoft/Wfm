<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmTasktagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_tasktags', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('tag_id', false)->unsigned()->nullable();
            $table->integer('project_id', false)->unsigned()->nullable();
            $table->integer('task_id', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
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
        Schema::dropIfExists('wfm_tasktags');
    }
}
