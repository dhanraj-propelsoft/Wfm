<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeePayHead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_pay_head', function (Blueprint $table) {
            $table->integer('pay_head_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->decimal('value', 10, 2)->nullable();

            $table->foreign('pay_head_id')->references('id')->on('hrm_pay_heads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['pay_head_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_pay_head');
    }
}
