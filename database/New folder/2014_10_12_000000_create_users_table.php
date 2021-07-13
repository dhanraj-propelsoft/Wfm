<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('person_id', false)->unsigned();
            $table->string('otp');
            $table->timestamp('otp_time');
            $table->integer('otp_sent', false)->default(0)->comment('Number of times otp sent in an hour');
            $table->integer('is_active', false)->default(0)->comment('0 - User not Purchased the application, 1 - User Purchased the application');
            $table->integer('status', false)->default(0)->comment('0 - Un-Verified, 1 - Verified');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
