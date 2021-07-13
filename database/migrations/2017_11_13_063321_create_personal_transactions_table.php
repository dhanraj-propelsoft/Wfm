<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('category_id', false)->unsigned()->nullable();
            $table->integer('transaction_type', false)->unsigned();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('account_id', false)->unsigned()->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('reference_id', false)->nullable()->comment('Reference of the transaction');
            $table->integer('interval', false)->nullable()->comment('0 - Daily, 1 - Weekly, 2 - Monthly');
            $table->integer('week_day_id', false)->unsigned()->nullable()->comment('Used for both week and month');
            $table->integer('day')->nullable()->comment('0 - Last -- Remaining are 1 to 28 days');
            $table->string('period')->nullable()->comment('0 - Last, 1 - 1st, 2 - 2nd, 3 - 3rd, 4 - 4th');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('end_occurence', false)->nullable();
            $table->text('source')->nullable();
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->integer('entry_id', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active'); 
            $table->integer('recurrence_status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('personal_categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('transaction_type')->references('id')->on('personal_transaction_types')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onDelete('cascade');

            $table->foreign('entry_id')->references('id')->on('account_entries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_transactions');
    }
}
