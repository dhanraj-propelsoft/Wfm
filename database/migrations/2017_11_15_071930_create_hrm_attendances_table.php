<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('shift_id', false)->unsigned()->nullable();
            $table->date('attended_date');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('description')->nullable();
            $table->integer('payroll_status', false)->default(0);
            $table->integer('status', false)->default(0);
            $table->integer('attendance_type_id', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('shift_id')->references('id')->on('hrm_shifts')
            ->onDelete('cascade');

            $table->foreign('attendance_type_id')->references('id')->on('hrm_attendance_types')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_attendances');
    }
}
