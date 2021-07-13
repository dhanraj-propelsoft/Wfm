<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmEmployeeAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_type', false)->comment('0 - Present, 1 - Permanent');
            $table->string('person')->nullable()->comment('Contact Person Name');
            $table->integer('person_id', false)->unsigned()->nullable()->comment('Contact Person ID');
            $table->integer('employee_id', false)->unsigned();
            $table->text('address')->nullable();
            $table->integer('city_id', false)->unsigned()->nullable();
            $table->string('pin', 6)->nullable();
            $table->string('landmark')->nullable();
            $table->string('google')->nullable();

            $table->string('mobile_no', 20)->nullable();
            $table->text('mobile_no_prev')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('phone_prev', 20)->nullable();
            $table->string('email_address', 100)->nullable();
            $table->text('email_address_prev')->nullable();
            $table->string('web_address', 255)->nullable();
            $table->text('web_address_prev')->nullable();
            $table->text('address_prev')->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_addresses');
    }
}
