<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('project_attachments', function($table){

        $table->increments('id');
        $table->integer('project_id')->unsigned();
        $table->string('upload_file')->nullable(20);
        $table->string('file_original_name')->nullable(20);
        $table->integer('status', false);
        $table->integer('organization_id')->unsigned()->nullable();
        $table->integer('created_by', false)->unsigned()->nullable();
        $table->integer('last_modified_by', false)->unsigned()->nullable();
        $table->integer('deleted_by', false)->unsigned()->nullable();
        $table->timestamps();
        $table->timestamp('deleted_at')->nullable(); 

    
        // $table->foreign('organization_id')->references('id')->on('mysql.organizations')->onUpdate('cascade')->onDelete('cascade');

        // $table->foreign('created_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');

        // $table->foreign('last_modified_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');

        // $table->foreign('deleted_by')->references('id')->on('mysql.hrm_employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('project_attachments');
    }
}
