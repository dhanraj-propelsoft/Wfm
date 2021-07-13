<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmPayrollFrequenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payroll_frequencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->integer('frequency_type', false)->nullable()->comment('0 - Daily, 1 - Weekly, 2 - Monthly');
            $table->string('salary_period')->nullable()->comment('0 - Last, 1 - 1st, 2 - 2nd, 3 - 3rd, 4 - 4th');
            $table->integer('week_day_id', false)->unsigned()->nullable()->comment('Used for both week and month');
            $table->integer('salary_day')->nullable()->comment('0 - Last -- Remaining are 1 to 28 days');
            $table->integer('status', false)->default(1);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('week_day_id')->references('id')->on('weekdays')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payroll_frequencies');
    }
}
