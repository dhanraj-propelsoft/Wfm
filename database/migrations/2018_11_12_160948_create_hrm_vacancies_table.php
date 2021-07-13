<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_vacancies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('designation_id', false)->unsigned()->nullable();
            $table->integer('no_of_positions', false)->nullable();
            $table->integer('no_of_vacancies')->nullable();
            $table->integer('team_id', false)->unsigned()->nullable();
            $table->text('notes')->nullable();
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->date('create_update_date')->nullable();
            $table->integer('status', false)->default(0);
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

             $table->foreign('designation_id')->references('id')->on('hrm_designations')->onUpdate('cascade')->onDelete('cascade');

            
            $table->foreign('team_id')->references('id')->on('hrm_teams')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

             $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');


            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_vacancies');
    }
}
