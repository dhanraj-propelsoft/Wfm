<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalItemModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_item_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('mpn')->nullable();
            $table->string('hsn')->nullable();
            $table->string('identifier_a')->nullable();
            $table->integer('category_id', false)->unsigned()->nullable();
            $table->integer('type_id', false)->unsigned()->nullable();
            $table->integer('make_id', false)->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('status', false)->default(1);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('category_id')->references('id')->on('global_item_categories')->onDelete('cascade');

            $table->foreign('type_id')->references('id')->on('global_item_types')->onDelete('cascade');

            $table->foreign('make_id')->references('id')->on('global_item_makes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_item_models');
    }
}
