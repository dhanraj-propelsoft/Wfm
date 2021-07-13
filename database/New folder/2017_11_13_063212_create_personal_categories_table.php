<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id', false)->unsigned();
            $table->string('image')->nullable();
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->integer('transaction_type', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['name', 'user_id']);

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('transaction_type')->references('id')->on('personal_transaction_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_categories');
    }
}
