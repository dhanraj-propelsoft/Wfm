<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalConsumptionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_consumption_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('internal_consumption_id', false)->unsigned();
            $table->integer('item_id', false)->unsigned();
            $table->text('description')->nullable();
            $table->float('quantity', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('inventory_items')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('internal_consumption_id')->references('id')->on('internal_consumptions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_consumption_items');
    }
}
