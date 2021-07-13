<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmOtRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_ot_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id', false)->unsigned();
            $table->date('attended_date');
            $table->integer('shift_id', false)->unsigned();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->float('over_time_hours', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(0);
            $table->integer('payroll_status', false)->default(0);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('shift_id')->references('id')->on('hrm_shifts')->onDelete('cascade');

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
        Schema::dropIfExists('hrm_ot_registers');
    }
}
