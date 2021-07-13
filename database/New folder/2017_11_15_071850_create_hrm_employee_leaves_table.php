<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_leaves', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->integer('leave_type_id')->unsigned();
            $table->decimal('remaining_leaves', 10, 2)->unsigned();
            $table->timestamp('updated_date');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('hrm_leave_types')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['employee_id', 'leave_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_leaves');
    }
}
