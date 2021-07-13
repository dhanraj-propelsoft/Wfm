<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountChequeBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_cheque_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ledger_id', false)->unsigned();
            $table->string('book_no')->nullable();
            $table->string('no_of_leaves')->nullable();
            $table->string('cheque_no_from')->nullable();
            $table->string('cheque_no_to')->nullable();
            $table->string('next_book_warning')->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_cheque_books');
    }
}
