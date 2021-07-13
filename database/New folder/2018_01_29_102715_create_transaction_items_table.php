<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id', false)->unsigned();
            $table->integer('item_id', false)->unsigned();   
            $table->time('start_time')->nullable();   
            $table->time('end_time')->nullable();
            $table->integer('status', false)->default(0)->nullable();
            $table->string('percentage')->nullable();
            $table->integer('preceding_task', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->float('quantity', 8, 2)->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('tax')->nullable();
            $table->integer('tax_id', false)->unsigned()->nullable();
            $table->integer('is_tax_percent', false)->default(1)->nullable();
            $table->text('discount')->nullable();
            $table->float('discount_value', 8, 2)->nullable();
            $table->integer('discount_id', false)->unsigned()->nullable();
            $table->integer('is_discount_percent', false)->default(1)->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('inventory_items')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('tax_id')->references('id')->on('tax_groups')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('discount_id')->references('id')->on('discounts')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
}
