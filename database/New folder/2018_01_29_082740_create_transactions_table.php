<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_id', false)->unsigned()->nullable();
            $table->string('order_no')->nullable();
            $table->integer('gen_no', false)->nullable();
            $table->string('company_reference_no')->nullable();
            $table->string('reference_no')->nullable()->comment('Reference of transaction number [In some cases there will be no actual reference id in backend]');
            $table->integer('reference_id', false)->unsigned()->nullable()->comment('Reference of transaction Id in backend  [Both for remote and current]');
            $table->string('remote_reference_no')->nullable()->comment('Reference of remote transaction number');
            $table->integer('reference_type_id', false)->unsigned()->nullable()->comment('Reference of transaction type');
            $table->string('pin')->nullable();
            $table->integer('user_type', false)->comment('0 - Person, 1 - Company');
            $table->integer('people_id', false)->unsigned()->comment('Person or Business ID depends on user type');
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('transaction_type_id', false)->unsigned();
            $table->integer('payment_method_id', false)->unsigned()->nullable();
            $table->integer('term_id', false)->unsigned()->nullable();
            $table->integer('employee_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('billing_email')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_mobile')->nullable();
            $table->string('shipping_email')->nullable();
            $table->text('shipping_address')->nullable();
            $table->integer('shipment_mode_id', false)->unsigned()->nullable();
            $table->date('shipping_date')->nullable();
            $table->integer('tax_type', false)->comment('0 - Out Of Scope, 1 - Include Tax, 2 - Exclude Tax');
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->integer('discount_is_percent', false)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->integer('notification_status', false)->default(0)->comment('0 - Pending in notification, 1 - Viewed in notification [Company to Company Notification]');
            $table->integer('item_update_status', false)->default(0)->comment('0 - Not Updated, 1 - Updated');
            $table->integer('approval_status', false)->default(0)->comment('0 - Not-Approved, 1 - Approved');
            $table->integer('complete_status', false)->default(0)->comment('0 - Partial, 1 - Completed [Transaction is partial order or it is fully completed, no need to make another transaction using it.]');
            $table->integer('status', false)->default(0)->comment('0 - Pending, 1 - Completed, 2 - Cancelled');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('organization_id', false)->unsigned()->nullable();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade');

            $table->foreign('transaction_type_id')->references('id')->on('account_vouchers')->onUpdate('cascade');

            $table->foreign('reference_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('reference_type_id')->references('id')->on('reference_vouchers')->onUpdate('cascade');

            $table->foreign('term_id')->references('id')->on('terms')->onUpdate('cascade');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onUpdate('cascade');

            $table->foreign('shipment_mode_id')->references('id')->on('shipment_modes')->onUpdate('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
