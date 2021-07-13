<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmSalaryScalePayHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_salary_scale_pay_head', function (Blueprint $table) {
            $table->integer('pay_head_id')->unsigned();
            $table->integer('salary_scale_id')->unsigned();
            $table->decimal('value', 10, 2)->unsigned()->nullable();


            $table->foreign('pay_head_id')->references('id')->on('hrm_pay_heads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('salary_scale_id')->references('id')->on('hrm_salary_scales')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['pay_head_id', 'salary_scale_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_salary_scale_pay_head');
    }
}
