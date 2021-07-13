<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemStockLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_stock_ledgers', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('inventory_item_stock_id', false)->unsigned()->nullable();
            $table->integer('inventory_item_batch_id', false)->unsigned()->nullable();
            $table->integer('transaction_id', false)->unsigned()->nullable();
            $table->integer('account_entry_id', false)->unsigned()->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('order_no')->nullable();
            $table->float('quantity', 8, 2)->nullable();
            $table->date('date')->nullable();
            $table->float('in_stock', 8, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0)->nullable();
            $table->decimal('sale_price', 10, 2)->default(0)->nullable();
            $table->integer('status', false)->default(1);
            
//             ["transaction_id" => $transaction->id,
//             "entry_id" => $transaction->entry_id,
//             "voucher_type" => $transaction_type_name->voucher_type,
//             "order_no" => $transaction->order_no,
//             "quantity" => $t_items->quantity,
//             "date" => ($transaction->date != null) ? Carbon::parse($transaction->date)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'), 
//             "in_stock" => $inventory_stock,
//             'purchase_price' => $purchase_tax_price,
//             'sale_price' => $inventory_item->base_price,
//             'status' => 1];
            
//             ["date" => $internal_consumption->date, 
//             "in_stock" => ($stock->in_stock - $quantity[$i])];
            
            
            $table->timestamps();
            
            
            $table->foreign('account_entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('inventory_item_stock_id')->references('id')->on('inventory_item_stocks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventory_item_batch_id')->references('id')->on('inventory_item_batches')->onUpdate('cascade')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_item_stock_ledgers');
    }
}
