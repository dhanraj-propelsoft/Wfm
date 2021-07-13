<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->integer('registration_no', false)->nullable();
            $table->integer('engine_no', false)->nullable();
            $table->integer('chassis_no', false)->nullable();
            $table->integer('manufacturing_year', false)->nullable();
            $table->integer('vehicle_permit_id', false)->unsigned()->nullable();
            $table->integer('vehicle_model_id', false)->unsigned()->nullable();
            $table->integer('vehicle_body_id', false)->unsigned()->nullable();
            $table->integer('vehicle_rim_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_tyre_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_tyre_size_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('vehicle_permit_id')->references('id')->on('vehicle_permits')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_body_id')->references('id')->on('vehicle_body_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_rim_type_id')->references('id')->on('vehicle_rim_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_tyre_type_id')->references('id')->on('vehicle_tyre_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_tyre_size_id')->references('id')->on('vehicle_tyre_sizes')
                ->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('vehicles');
    }
}
