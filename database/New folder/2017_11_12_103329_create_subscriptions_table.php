<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('order_id')->nullable();
            $table->integer('subscription_type_id', false)->unsigned()->nullable();
            $table->timestamp('added_on');
            $table->integer('term_period_id', false)->unsigned()->nullable();
            $table->timestamp('expire_on')->comment('Expiry date for the subscription');
            $table->text('remarks')->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->text('price_report')->nullable();
            $table->text('tax_report')->nullable();
            $table->integer('payment_mode_id', false)->unsigned();
            $table->integer('payment_status', false)->default(0);
            $table->integer('status', false)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('payment_mode_id')->references('id')->on('payment_modes');

            $table->foreign('subscription_type_id')->references('id')->on('subscription_types');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('term_period_id')->references('id')->on('term_periods')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('subscriptions');
    }
}
