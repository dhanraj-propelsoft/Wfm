<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_leave_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('code');
            $table->string('yearly_limit')->default(0);
            $table->string('yearly_carry_limit')->default(0);
            $table->string('monthly_limit')->default(0);
            $table->string('monthly_carry_limit')->default(0);
            $table->integer('part_of_weekoff', false)->default(0)->comment('0 - false, 1 - true');
            $table->integer('part_of_holiday', false)->default(0)->comment('0 - false, 1 - true');
            $table->integer('before_weekoff', false)->nullable();
            $table->integer('after_weekoff', false)->nullable();
            $table->integer('before_holiday', false)->nullable();
            $table->integer('after_holiday', false)->nullable();
            $table->integer('applicable_gender', false)->unsigned()->nullable();
            $table->integer('applicable_employment_type', false)->unsigned()->nullable();
            $table->integer('applicable_department', false)->unsigned()->nullable();
            $table->integer('applicable_designation', false)->unsigned()->nullable();
            $table->integer('effective_from', false)->default(0)->comment('0 - From Joining Date, 1 - After 3 months, 2 - After 6 months, 3 - After a year, 4 - User Defined Date');
            $table->integer('period_type', false)->default(0)->comment('0 - Days, 1 - Months');
            $table->integer('activation_period', false)->default(0)->comment('Number of days or months');
            $table->integer('pay_status', false)->comment('0 - Not Paid, 1 - Paid');
            $table->integer('status', false)->default(1);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('applicable_employment_type')->references('id')->on('hrm_employment_types')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('applicable_department')->references('id')->on('hrm_departments')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('applicable_designation')->references('id')->on('hrm_designations')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('applicable_gender')->references('id')->on('genders')
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
        Schema::dropIfExists('hrm_leave_types');
    }
}
