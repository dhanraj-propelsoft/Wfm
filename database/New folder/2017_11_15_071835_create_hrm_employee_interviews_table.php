<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeeInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_interviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id', false)->unsigned()->nullable();
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->date('interview_date');
            $table->text('interviewer_report')->nullable();
            $table->text('hr_report')->nullable();
            $table->integer('designation_id', false)->unsigned();
            $table->integer('ref_employee_id', false)->unsigned()->nullable();
            $table->integer('ref_person_id', false)->unsigned()->nullable();
            $table->integer('notice_period', false)->nullable();
            $table->integer('experience', false)->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(0);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('candidate_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('ref_person_id')->references('id')->on('persons')->onDelete('cascade');

            $table->foreign('ref_employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('designation_id')->references('id')->on('hrm_designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_interviews');
    }
}
