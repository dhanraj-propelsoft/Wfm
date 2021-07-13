<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user')->nullable();
            $table->string('pass')->nullable();
            $table->string('sender')->nullable();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('priority')->nullable();
            $table->string('stype')->nullable();
            $table->string('message_id')->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')
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
        Schema::dropIfExists('sms');
    }
}
