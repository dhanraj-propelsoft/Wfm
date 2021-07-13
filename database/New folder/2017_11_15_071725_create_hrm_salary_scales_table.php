<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmSalaryScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_salary_scales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('frequency_id', false)->unsigned()->nullable();
            $table->integer('print_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('round_off', false)->nullable()->comment('0 - Normal, 1 - Upward, 2 - Downward');
            $table->float('round_off_limit', false)->default(0);
            $table->integer('auto_generate', false)->default(0);
            $table->integer('organization_id', false)->unsigned();
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('frequency_id')->references('id')->on('hrm_payroll_frequencies')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('print_id')->references('id')->on('print_templates')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

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
        Schema::dropIfExists('hrm_salary_scales');
    }
}
