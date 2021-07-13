<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_reference', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->string('reference_no')->nullable()->comment('Reference of transaction number [In some cases there will be no actual reference id in backend]');
            $table->integer('reference_id', false)->unsigned()->nullable()->comment('Reference of transaction Id in backend  [Both for remote and current]');

            $table->foreign('id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_reference');
    }
}
