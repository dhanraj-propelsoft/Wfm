<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWmsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wms_transactions', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->integer('transaction_id', false)->unsigned()->nullable(); 
            $table->integer('advance_amount')->nullable();
            $table->integer('registration_id', false)->unsigned()->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chasis_no')->nullable();
            $table->integer('jobcard_status_id', false)->unsigned()->nullable();
            $table->integer('purchase_date')->nullable();
            $table->integer('vehicle_usage_id', false)->unsigned()->nullable();
            $table->integer('service_type', false)->nullable();
            $table->integer('vehicle_details', false)->nullable();
            $table->integer('assigned_to', false)->unsigned()->nullable();
            $table->date('job_due_date')->nullable();
            $table->integer('payment_terms', false)->nullable();
            $table->integer('payment_details', false)->nullable();
            $table->integer('delivery_by', false)->unsigned()->nullable();
            $table->text('delivery_details')->nullable();
            $table->integer('vehicle_last_visit')->nullable();
            $table->integer('vehicle_last_job')->nullable();
            $table->integer('vehicle_mileage')->nullable();
            $table->integer('next_visit_mileage')->nullable();
            $table->date('vehicle_next_visit')->nullable();
            $table->text('vehicle_next_visit_reason')->nullable();
            $table->text('vehicle_note')->nullable();
            $table->text('before_job_notes')->nullable();
            $table->text('after_job_notes')->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('registration_id')->references('id')->on('vehicle_register_details')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('vehicle_usage_id')->references('id')->on('vehicle_usages')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('assigned_to')->references('id')->on('hrm_employees')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('delivery_by')->references('id')->on('hrm_employees')
            ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wms_transactions');
    }
}
