<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonWebAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_web_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable(false);           
            $table->string('web_address','50')->nullable(true);
            $table->integer('status_id', false)->unsigned()->nullable(false);       
           
            //Foreign keys
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('restrict');
            $table->foreign('status_id')->references('id')->on('status_categories')->onDelete('restrict');            
            //Common Fields and foreign keys
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();
            
            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->softDeletes();
            
            //Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('last_modified_by')->references('id')->on( 'users')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_web_addresses');
    }
}
