<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_plan', function (Blueprint $table) {
             $table->integer('package_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->decimal('price', 10, 2)->unsigned();

            $table->foreign('package_id')->references('id')->on('packages')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('subscription_plans')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['package_id', 'plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_plan');
    }
}
