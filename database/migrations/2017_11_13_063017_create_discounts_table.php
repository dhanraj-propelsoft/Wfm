<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->float('value', 8, 2);
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('is_percent', false)->nullable();
            $table->integer('is_sales', false)->nullable();
            $table->integer('is_purchase', false)->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('purchase_ledger_id', false)->unsigned()->nullable();
            $table->integer('sales_ledger_id', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('sales_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('purchase_ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
