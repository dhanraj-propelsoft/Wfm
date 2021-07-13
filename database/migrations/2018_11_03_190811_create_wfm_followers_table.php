<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('follower_id')->unsigned()->nullable();
            $table->integer('task_id', false)->unsigned()->nullable();
            $table->integer('project_id', false)->unsigned()->nullable();
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
          
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('wfm_followers');
    }
}
