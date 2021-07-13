<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmAttendanceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_attendance_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->time('standard_working_hours');
            $table->time('min_hours_for_full_day');
            $table->time('min_hours_for_half_day');
            $table->time('min_hours_for_official_half_day');
            $table->time('grace_time');
            $table->float('deduction_days')->nullable();
            $table->integer('cancel_deduction', false)->nullable()->comment('If total hours greater than minimum hours');
            $table->integer('deduct_from', false)->nullable()->comment('0 - CL, 1 - LOP');
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('hrm_attendance_settings');
    }
}
