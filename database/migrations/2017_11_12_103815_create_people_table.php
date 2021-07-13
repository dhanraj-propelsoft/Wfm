<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('business_id', false)->unsigned()->nullable();
            $table->string('company')->nullable();
            $table->integer('title_id', false)->unsigned()->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->integer('gender_id', false)->unsigned()->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->integer('payment_mode_id', false)->unsigned()->nullable();
            $table->integer('term_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('user_type', false)->default(0)->comment('0 - Person, 1 - Company');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['user_type', 'person_id', 'organization_id']);
            $table->unique(['user_type', 'business_id', 'organization_id']);

            $table->foreign('title_id')->references('id')->on('people_titles')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('person_id')->references('id')->on('persons')
            ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('business_id')->references('id')->on('businesses')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('gender_id')->references('id')->on('genders')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('term_id')->references('id')->on('terms')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('payment_mode_id')->references('id')->on('payment_methods')
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
        Schema::dropIfExists('people');
    }
}
