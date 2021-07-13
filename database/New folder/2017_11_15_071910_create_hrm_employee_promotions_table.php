<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no')->nullable();
            $table->integer('employee_id', false)->unsigned();
            $table->date('promotion_date');
            $table->text('description')->nullable();
            $table->integer('department_id', false)->unsigned()->nullable();
            $table->integer('previous_department_id', false)->unsigned()->nullable();
            $table->integer('designation_id', false)->unsigned();
            $table->integer('previous_designation_id', false)->unsigned();
            $table->integer('salary_scale_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(0);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('department_id')->references('id')->on('hrm_departments')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('previous_department_id')->references('id')->on('hrm_departments')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('designation_id')->references('id')->on('hrm_designations')->onDelete('cascade');

            $table->foreign('previous_designation_id')->references('id')->on('hrm_designations')->onDelete('cascade');

            $table->foreign('salary_scale_id')->references('id')->on('hrm_salary_scales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_promotions');
    }
}
