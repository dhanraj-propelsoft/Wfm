<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmWeekOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_week_offs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('effective_date');
            $table->integer('first_week_off', false)->unsigned()->nullable();
            $table->integer('first_week_off_period', false)->nullable()->comment('0 - Every, 1 - 1st, 2 - 2nd, 3 - 3rd, 4 - 4th, 5 - Last, 6 - Alt(1, 3), 7 - Alt(2, 4), 8 - Alt(1, 3, 5)');
            $table->integer('first_week_half_day', false)->default(0);
            $table->time('first_half_minimum', false)->nullable();
            $table->integer('first_full_day_rule', false)->default(0)->comment('If weekoff comes between leaves comsidered as full day leave');
            $table->integer('second_week_off', false)->unsigned()->nullable();
            $table->integer('second_week_off_period', false)->nullable()->comment('0 - Every, 1 - 1st, 2 - 2nd, 3 - 3rd, 4 - 4th, 5 - Last, 6 - Alt(1, 3), 7 - Alt(2, 4), 8 - Alt(1, 3, 5)');
            $table->integer('second_week_half_day', false)->default(0);
            $table->time('second_half_minimum', false)->nullable();
            $table->integer('second_full_day_rule', false)->default(0)->comment('If weekoff comes between leaves comsidered as full day leave');
            $table->integer('pay_status', false);
            $table->integer('status', false)->default(1);
            $table->text('description')->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('first_week_off')->references('id')->on('weekdays')->onDelete('cascade');

            $table->foreign('second_week_off')->references('id')->on('weekdays')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('hrm_week_offs');
    }
}
