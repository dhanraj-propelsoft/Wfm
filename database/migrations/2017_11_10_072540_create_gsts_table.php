<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->string('code');
            $table->string('chapter')->nullable();
            $table->string('chapter_name')->nullable();
            $table->text('description')->nullable();
            $table->float('rate', 8, 2)->nullable();
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
        Schema::dropIfExists('gsts');
    }
}
