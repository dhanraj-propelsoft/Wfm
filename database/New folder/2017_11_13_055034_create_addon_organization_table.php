<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddonOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_organization', function (Blueprint $table) {
            $table->integer('addon_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->string('used')->nullable();
            $table->string('value')->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('subscription_id', false)->unsigned()->nullable();

            $table->foreign('addon_id')->references('id')->on('addons')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onUpdate('cascade')->onDelete('cascade');

            $table->primary([ 'addon_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addon_organization');
    }
}
