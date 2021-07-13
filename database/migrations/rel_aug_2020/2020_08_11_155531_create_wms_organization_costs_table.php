<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWmsOrganizationCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wms_organization_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('factor1')->nullable();
            $table->integer('value1', false)->unsigned()->nullable();

            $table->string('factor2')->nullable();
            $table->integer('value2', false)->unsigned()->nullable();

            $table->string('factor3')->nullable();
            $table->integer('value3', false)->unsigned()->nullable();

            $table->string('factor4')->nullable();
            $table->integer('value4', false)->unsigned()->nullable();

            $table->string('factor5')->nullable();
            $table->integer('value5', false)->unsigned()->nullable();

            $table->string('factor6')->nullable();
            $table->integer('value6', false)->unsigned()->nullable();

            $table->string('factor7')->nullable();
            $table->integer('value7', false)->unsigned()->nullable();

            $table->string('factor8')->nullable();
            $table->integer('value8', false)->unsigned()->nullable();

            $table->string('factor9')->nullable();
            $table->integer('value9', false)->unsigned()->nullable();

            $table->string('factor10')->nullable();
            $table->integer('value10', false)->unsigned()->nullable();

            $table->integer('month_expense_total', false)->unsigned()->nullable();
            $table->integer('days_per_month', false)->unsigned()->nullable();
            $table->integer('hours_per_day', false)->unsigned()->nullable();
            $table->integer('hours_per_month', false)->unsigned()->nullable();
            $table->integer('hourly_org_cost', false)->unsigned()->nullable();
            $table->integer('no_of_employees', false)->unsigned()->nullable();
            $table->integer('hourly_employee_cost', false)->unsigned()->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wms_organization_costs');
    }
}
