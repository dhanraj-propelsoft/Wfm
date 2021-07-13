<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active'); 
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->integer('entry_id', false)->unsigned()->nullable();
            $table->integer('transaction_id', false)->unsigned();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('transaction_id')->references('id')->on('personal_transactions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('entry_id')->references('id')->on('account_entries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_payments');
    }
}
