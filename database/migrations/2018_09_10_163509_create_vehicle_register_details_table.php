<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleRegisterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_register_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration_no');
            $table->integer('owner_id', false)->unsigned()->nullable();
            $table->integer('driver_id', false)->unsigned()->nullable();
            $table->integer('is_own', false)->default(0);
            $table->string('engine_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->year('manufacturing_year')->nullable();
            $table->integer('vehicle_configuration_id', false)->unsigned()->nullable();
            $table->integer('vehicle_category_id', false)->unsigned()->nullable();
            $table->integer('vehicle_make_id', false)->unsigned()->nullable();
            $table->integer('vehicle_model_id', false)->unsigned()->nullable();
            $table->integer('vehicle_variant_id', false)->unsigned()->nullable();
            4table->integer('version',false)->nullable();
            $table->integer('vehicle_body_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_rim_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_tyre_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_tyre_size_id', false)->unsigned()->nullable();
            $table->integer('vehicle_wheel_type_id', false)->unsigned()->nullable();
            $table->integer('vehicle_drivetrain_id', false)->unsigned()->nullable();
            $table->integer('vehicle_usage_id', false)->unsigned()->nullable();
            $table->integer('fuel_type_id', false)->unsigned()->nullable();            
            $table->text('description')->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('owner_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('driver_id')->references('id')->on('persons')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_configuration_id')->references('id')->on('vehicle_configurations')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_category_id')->references('id')->on('vehicle_variants')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_make_id')->references('id')->on('vehicle_makes')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_variant_id')->references('id')->on('vehicle_variants')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_body_type_id')->references('id')->on('vehicle_body_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_rim_type_id')->references('id')->on('vehicle_rim_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_tyre_type_id')->references('id')->on('vehicle_tyre_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_tyre_size_id')->references('id')->on('vehicle_tyre_sizes')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_wheel_type_id')->references('id')->on('vehicle_wheels')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_drivetrain_id')->references('id')->on('vehicle_drivetrains')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_usage_id')->references('id')->on('vehicle_usages')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('fuel_type_id')->references('id')->on('vehicle_fuel_types')
                ->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('vehicle_register_details');
    }
}
