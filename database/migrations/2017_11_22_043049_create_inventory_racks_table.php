<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryRacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_racks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id', false)->unsigned()->nullable();
            $table->integer('warehouse_id', false)->unsigned()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('store_id')->references('id')->on('inventory_stores')
                ->onUpdate('cascade');

            $table->foreign('warehouse_id')->references('id')->on('business_communication_addresses')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('inventory_racks');
    }
}
