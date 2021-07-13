<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned();
            $table->integer('title_id', false)->unsigned()->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('employee_code')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('emergency_no')->nullable();
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->integer('blood_group_id', false)->unsigned()->nullable();
            $table->integer('gender_id', false)->unsigned()->nullable();
            $table->string('known_languages')->nullable();
            $table->integer('marital_status', false)->unsigned()->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('license_no')->nullable(); 
            $table->integer('license_type_id', false)->unsigned()->nullable();
            $table->integer('staff_type_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active'); 
            $table->integer('reporting_person', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('interview_details', false)->unsigned()->nullable();
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('blood_group_id')->references('id')->on('blood_groups')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('marital_status')->references('id')->on('marital_statuses')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('license_type_id')->references('id')->on('license_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('staff_type_id')->references('id')->on('hrm_staff_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('person_id')->references('id')->on('persons');

            $table->foreign('reporting_person')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('title_id')->references('id')->on('people_titles')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('gender_id')->references('id')->on('genders')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employees');
    }
}
