<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable(false);            
            $table->integer('address_type_id', false)->unsigned()->nullable(true);                      
            $table->integer('door_no');          
            $table->string('building_name')->nullable(true); 
            $table->string('street')->nullable(true); 
            $table->string('area')->nullable(true); 
            $table->integer('city_id', false)->unsigned()->nullable(true);
            $table->integer('pin')->nullable(true);
            $table->string('phone', false)->nullable(true);            
            $table->string('landmark', false)->nullable(true);            
            $table->string('google_location', false)->nullable(true);
            $table->integer('status_id', false)->unsigned()->nullable(false);   
                       
             //Common Fields and foreign keys
             $table->integer('created_by', false)->unsigned()->nullable();
             $table->integer('last_modified_by', false)->unsigned()->nullable();
             $table->timestamps();             
             $table->integer('deleted_by', false)->unsigned()->nullable();
             $table->softDeletes();
             
            //Foreign keys
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('restrict');
            $table->foreign('address_type_id')->references('id')->on('address_types')->onDelete('restrict');
            $table->foreign('status_id')->references('id')->on('status_categories')->onDelete('restrict'); 
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
        Schema::dropIfExists('person_addresses');
    }
}
