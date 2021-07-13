<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeePfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_pfs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->integer('pf_number', false)->nullable();
            $table->integer('esi_number', false)->nullable();
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
        Schema::dropIfExists('hrm_employee_pfs');
    }
}
