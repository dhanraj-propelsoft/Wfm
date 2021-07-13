<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWmsPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wms_price_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_item_id',false)->unsigned()->nullable();
            $table->integer('vehicle_segments_id',false)->unsigned()->nullable();
            $table->decimal('base_price',10,2)->default(0)->nullable();
            $table->string('applicable')->default(T)->nullable();
            $table->decimal('price',10,2)->default(0)->nullable();
            $table->integer('status',false)->default(1);
            $table->date('effective_date')->nullable();
            $table->integer('organization_id',false)->unsigned();
            $table->integer('created_by',false)->unsigned()->nullable();
            $table->integer('last_modified_by',false)->unsigned()->ullable();
            $table->timestamps();

            $table->foreign('created_by')->refference('id')->on('users')
            ->onUpdate('cascade')->onDelete('set null');
            
            $table->foreign('last_modified_by')->refference('id')->on('users')->onUpdate('cascade')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wms_price_lists');
    }
}
