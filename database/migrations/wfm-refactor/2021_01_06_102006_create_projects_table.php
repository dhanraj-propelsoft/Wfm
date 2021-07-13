<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('projects', function($table){
            $table->increments('id');
            $table->string('name')->nullable(20);
            $table->string('details')->nullable(225);
            $table->integer('project_owner')->nullable(20);
            $table->integer('category_id')->nullable(20);
            $table->date('start_date')->nullable();
            $table->string('deadline_date')->nullable();
            $table->integer('status',false)->default(1)->comment('1- Enable, 2 - Disable,3- Closed');
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('project_owner')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('mysql2.categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('mysql.organizations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('projects');
    }
}
