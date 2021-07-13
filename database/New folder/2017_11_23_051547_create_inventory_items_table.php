<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('global_item_model_id', false)->unsigned();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->string('hsn')->nullable();
            $table->string('mpn')->nullable();
            $table->decimal('purchase_price', 10, 2)->default(0)->nullable();
            $table->text('sale_price_data')->nullable();
            $table->integer('unit_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->text('purchase_description')->nullable();
            $table->float('low_stock', 10, 2)->default(0)->nullable();
            $table->float('minimum_order_quantity', 10, 2)->default(1)->nullable();
            $table->integer('include_tax', false)->default(0)->nullable();
            $table->integer('tax_id', false)->unsigned()->nullable();
            $table->integer('include_purchase_tax', false)->default(0)->nullable();
            $table->integer('purchase_tax_id', false)->unsigned()->nullable();
            $table->integer('income_account', false)->unsigned()->nullable();
            $table->integer('expense_account', false)->unsigned()->nullable();
            $table->integer('inventory_account', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('global_item_model_id')->references('id')->on('global_item_models')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('income_account')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('expense_account')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('inventory_account')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('tax_id')->references('id')->on('tax_groups')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('purchase_tax_id')->references('id')->on('tax_groups')->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('inventory_items');
    }
}
