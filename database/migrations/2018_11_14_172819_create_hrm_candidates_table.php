<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('education')->nullable();
            $table->integer('designation_id', false)->unsigned()->nullable();
            $table->date('applied_on')->nullable();
            $table->integer('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->integer('experience')->nullable();
            $table->string('skill_set_1')->nullable();
            $table->string('skill_set_2')->nullable();
            $table->string('skill_set_3')->nullable();
            $table->date('tech_interview_on')->nullable();
            $table->integer('tech_employee_id',false)->unsigned()->nullable();
            $table->text('tech_comments')->nullable();
            $table->date('hr_interview_on')->nullable();
            $table->integer('hr_employee_id',false)->unsigned()->nullable();
            $table->text('hr_comments')->nullable();
            $table->integer('recruitment_status',false)->unsigned()->nullable();
            $table->date('last_modified')->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('designation_id')->references('id')->on('hrm_designations')->onUpdate('cascade')->onDelete('cascade');

             $table->foreign('recruitment_status')->references('id')->on('hrm_recruitment_statuses')->onUpdate('cascade')->onDelete('cascade'); 

             $table->foreign('tech_employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('cascade');
             
             $table->foreign('hr_employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('cascade');

             $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            
            
            
            
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_candidates');
    }
}
