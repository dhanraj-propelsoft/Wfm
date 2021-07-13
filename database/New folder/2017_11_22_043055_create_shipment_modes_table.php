<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

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
        Schema::dropIfExists('shipment_modes');
    }
}
