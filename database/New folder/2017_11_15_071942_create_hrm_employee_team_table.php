<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeeTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_team', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->integer('team_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('hrm_employees')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('hrm_teams')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['employee_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_team');
    }
}
