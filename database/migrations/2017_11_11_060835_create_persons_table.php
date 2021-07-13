<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('crm_code', 10)->unique();
            $table->integer('salutation', false)->unsigned()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('otp', 50)->nullable();
            $table->string('alias')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('license_no')->nullable(); 
            $table->date('dob')->nullable();
            $table->integer('gender_id', false)->unsigned()->nullable();
            $table->integer('blood_group_id', false)->unsigned()->nullable();
            $table->integer('marital_status_id', false)->unsigned()->nullable();
            $table->date('anniversary_date', 50)->nullable();
            $table->string('nationality')->nullable();
            $table->text('known_languages')->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('gender_id')->references('id')->on('genders')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('blood_group_id')->references('id')->on('blood_groups')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('marital_status_id')->references('id')->on('marital_statuses')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('salutation')->references('id')->on('people_titles')
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
        Schema::dropIfExists('persons');
    }
}
