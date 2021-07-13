<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmPayHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_pay_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payhead_type_id', false)->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('code');
            $table->integer('calculation_type', false)->comment('0 - Flat, 1 - Percent');
            $table->integer('formula', false)->nullable()->comment('0 - Sub Total, 1 - Specific Pay Head, 2 - Total Earnings, 3 - Total Deductions');
            $table->integer('wage_type', false)->nullable()->comment('0 - Hour Based, 1 - Day Based, 2 - Month Based');
            $table->integer('fixed_month', false)->nullable()->comment('0 - Fixed Days, 1 - Calendar Month');
            $table->integer('fixed_days', false)->nullable();
            $table->integer('minimum_attendance', false)->default(0)->comment('Subtract number of days from actual period');
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('payhead_type_id')->references('id')->on('hrm_pay_head_types')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_pay_heads');
    }
}
