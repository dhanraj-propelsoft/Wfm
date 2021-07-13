<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->timestamp('added_on');
            $table->timestamp('expire_on')->comment('Organization subscription expiration date');
            $table->integer('status', false)->default(0)->comment('0 - In-Active, 1 - Active');
            $table->integer('subscription_id', false)->unsigned()->nullable();

            $table->foreign('package_id')->references('id')->on('packages')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('plan_id')->references('id')->on('subscription_plans')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('subscription_id')->references('id')->on('subscriptions')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_packages');
    }
}
