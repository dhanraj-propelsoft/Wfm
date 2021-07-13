<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('status', false)->default(0)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('account_type_id', false)->unsigned();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('account_type_id')->references('id')->on('plan_account_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
}
