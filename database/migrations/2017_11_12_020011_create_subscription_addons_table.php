<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_addons', function (Blueprint $table) {
            $table->integer('subscription_plan_id')->unsigned();
            $table->integer('addon_id')->unsigned();
            $table->string('value')->nullable();


            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('addon_id')->references('id')->on('addons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['subscription_plan_id', 'addon_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_addons');
    }
}
