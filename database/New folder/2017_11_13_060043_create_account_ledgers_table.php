<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('ledger_type');
            $table->integer('person_id', false)->nullable();
            $table->integer('business_id', false)->nullable();
            $table->date('opening_balance_date')->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->string('opening_balance_type')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('micr')->nullable();
            $table->string('nbfc_name')->nullable();
            $table->string('nbfc_branch')->nullable();
            $table->integer('group_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->integer('approval_status', false)->default(0)->default(0)->comment('0 - Not Approved, 1 - Approved');
            $table->integer('delete_status', false)->default(1)->comment('0 - Non Deletable, 1 - Deletable');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['name', 'group_id']);

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('group_id')->references('id')->on('account_groups')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
}
