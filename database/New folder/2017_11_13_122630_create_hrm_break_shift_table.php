<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmBreakShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_break_shift', function (Blueprint $table) {
            $table->integer('shift_id')->unsigned();
            $table->integer('break_id')->unsigned();


            $table->foreign('shift_id')->references('id')->on('hrm_shifts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('break_id')->references('id')->on('hrm_breaks')->onUpdate('cascade')->onDelete('cascade');

            $table->primary([ 'shift_id', 'break_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_break_shift');
    }
}
