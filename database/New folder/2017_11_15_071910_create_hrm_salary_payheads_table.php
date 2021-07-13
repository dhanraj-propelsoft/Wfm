<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmSalaryPayheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_salary_payheads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salary_id', false)->unsigned()->nullable();
            $table->integer('pay_head_id', false)->unsigned()->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('salary_id')->references('id')->on('hrm_salaries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pay_head_id')->references('id')->on('hrm_pay_heads')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_salary_payheads');
    }
}
