<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_code')->nullable(12);
            $table->string('project_name')->nullable(20);
            $table->string('project_details')->nullable(225);
            $table->string('project_comments')->nullable(225);
            $table->date('deadline_date')->nullable();
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('project_category_id')->unsigned()->nullable();
            $table->integer('project_status',false)->default(1)->comment('1- Enable, 2 - Disable,3- Closed');
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
        Schema::dropIfExists('wfm_projects');
    }
}
