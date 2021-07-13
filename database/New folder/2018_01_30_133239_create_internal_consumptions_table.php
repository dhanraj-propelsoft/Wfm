<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_consumptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no')->nullable();
            $table->integer('gen_no', false)->nullable();
            $table->string('reference_no')->nullable();
            $table->date('date')->nullable();
            $table->integer('transaction_type_id', false)->unsigned();
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->integer('store_id', false)->unsigned()->nullable();
            $table->integer('rack_id', false)->unsigned()->nullable();
            $table->integer('warehouse_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->integer('organization_id', false)->unsigned()->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('transaction_type_id')->references('id')->on('account_vouchers')->onUpdate('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('store_id')->references('id')->on('inventory_stores')
                ->onUpdate('cascade');

            $table->foreign('rack_id')->references('id')->on('inventory_racks')
                ->onUpdate('cascade');

            $table->foreign('warehouse_id')->references('id')->on('business_communication_addresses')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_consumptions');
    }
}
