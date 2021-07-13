<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('account_number')->nullable();
            $table->integer('account_type', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->integer('ledger_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active'); 
            $table->integer('delete_status', false)->default(1)->comment('0 - Non Deletable, 1 - Deletable');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('ledger_id')->references('id')->on('account_ledgers')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_accounts');
    }
}
