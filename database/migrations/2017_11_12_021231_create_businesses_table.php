<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bcrm_code', 10)->unique();
            $table->string('business_name');
            $table->string('otp', 50)->nullable();
            $table->string('alias', 50)->nullable();
            $table->integer('business_nature_id', false)->unsigned()->nullable();
            $table->integer('business_professionalism_id', false)->unsigned()->nullable();
            $table->string('pan')->nullable();
            $table->string('tin')->nullable();
            $table->string('gst')->nullable();
            $table->date('anniversary_date', 50)->nullable();
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('business_nature_id')->references('id')->on('business_natures');

            $table->foreign('business_professionalism_id')->references('id')->on('business_professionalisms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
