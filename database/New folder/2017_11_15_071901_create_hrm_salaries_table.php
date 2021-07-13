<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_id', false)->unsigned()->nullable();
            $table->date('salary_date');
            $table->string('over_time_hours');
            $table->integer('payment_method_id', false)->unsigned()->nullable();
            $table->integer('salary_scale_id', false)->unsigned(); 
            $table->integer('employee_id', false)->unsigned();
            $table->decimal('gross_salary', 10, 2)->nullable();
            $table->integer('status', false)->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('batch', false);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('salary_scale_id')->references('id')->on('hrm_salary_scales')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_salaries');
    }
}
