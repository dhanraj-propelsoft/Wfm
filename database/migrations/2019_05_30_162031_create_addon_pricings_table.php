<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddonPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_pricings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value');
            $table->decimal('price', 10, 2)->default(0)->nullable();
            $table->integer('addon_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1);
            $table->timestamps();


            $table->foreign('addon_id')->references('id')->on('addons')
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
        Schema::dropIfExists('addon_pricings');
    }
}
