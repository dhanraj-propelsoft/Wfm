<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmLogRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_log_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->date('log_date')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('purpose')->nullable();
            $table->text('description')->nullable();
            $table->text('employer_note')->nullable();
            $table->integer('person_type_id', false)->unsigned();
            $table->integer('person_id', false)->unsigned()->nullable();
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('person_type_id')->references('id')->on('hrm_person_types')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');

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
        Schema::dropIfExists('hrm_log_registers');
    }
}
