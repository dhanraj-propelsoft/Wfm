<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeeWorkingPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_working_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id', false)->unsigned();
            $table->integer('branch_id', false)->unsigned();
            $table->date('joined_date');
            $table->integer('employment_type_id', false)->unsigned()->nullable();
            $table->integer('confirmation_period', false)->unsigned()->nullable();
            $table->text('reason')->nullable();
            $table->date('relieved_date')->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('branch_id')->references('id')->on('hrm_branches')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
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
        Schema::dropIfExists('hrm_employee_working_periods');
    }
}
