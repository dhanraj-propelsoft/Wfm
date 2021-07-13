<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleSegmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_segment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_category_id', false)->unsigned()->nullable();
            $table->integer('Vehicle_segment_id', false)->unsigned()->nullable();
            $table->integer('vehicle_make_id', false)->unsigned()->nullable();
            $table->integer('vehicle_model_id', false)->unsigned()->nullable();
            $table->integer('vehicle_variant_id', false)->unsigned()->nullable();
            $table->integer('division_id', false)->unsigned()->nullable();
            $table->integer('industry_id', false)->unsigned()->nullable();
            $table->text('description', false)->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('Vehicle_segment_id')->references('id')->on('vehicle_segments')
                ->onUpdate('cascade')->onDelete('set null');

             $table->foreign('vehicle_category_id')->references('id')->on('vehicle_categories')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_make_id')->references('id')->on('vehicle_makes')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_variant_id')->references('id')->on('vehicle_variants')
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
        Schema::dropIfExists('vehicle_segment_details');
    }
}
