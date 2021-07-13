<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('is_active', false)->default(0)->comment('0 - User not Purchased the application, 1 - User Purchased the application');
            $table->integer('status', false)->default(0)->comment('0 - In-Active, 1 - Active');
            $table->integer('business_id', false)->unsigned();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')
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
        Schema::dropIfExists('organizations');
    }
}
