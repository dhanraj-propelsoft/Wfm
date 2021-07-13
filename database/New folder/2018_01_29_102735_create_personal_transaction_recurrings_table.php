<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTransactionRecurringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_transaction_recurrings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('interval', false)->nullable()->comment('0 - Daily, 1 - Weekly, 2 - Monthly');
            $table->string('period')->nullable()->comment('0 - Last, 1 - 1st, 2 - 2nd, 3 - 3rd, 4 - 4th');
            $table->integer('week_day_id', false)->unsigned()->nullable()->comment('Used for both week and month');
            $table->integer('day')->nullable()->comment('0 - Last -- Remaining are 1 to 28 days');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('end_occurence', false)->nullable();
            $table->integer('frequency', false)->nullable()->comment('Repeating Period');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('week_day_id')->references('id')->onUpdate('cascade')->on('weekdays')->onDelete('cascade');

            $table->foreign('id')->references('id')->onUpdate('cascade')->on('personal_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_transaction_recurrings');
    }
}
