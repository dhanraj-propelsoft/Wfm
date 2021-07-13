<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVmsObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vms_observations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_configurations_id', false)->unsigned()->nullable();
            $table->text('driver_name');
            $table->date('observed_on');
            $table->date('serviced_on');
            $table->text('abservation_summary');
            $table->text('abservation_note');
            $table->integer('closure_status', false)->default(0);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('deleted_flag', false)->default(1);

            $table->timestamps();

             $table->foreign('vehicle_configurations_id')->references('id')->on('vehicle_configurations')
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
        Schema::dropIfExists('vms_observations');
    }
}
