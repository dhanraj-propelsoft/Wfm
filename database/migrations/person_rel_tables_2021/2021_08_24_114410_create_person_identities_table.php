<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_identities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id', false)->unsigned()->nullable(false);
            $table->integer('document_type', false)->unsigned()->nullable(false);
            $table->string('document_no','20')->nullable(false);
            $table->integer('document_status')->unsigned()->nullable(false)->default(1);
            $table->date('document_validity');
            $table->string('attachement_reference','200')->nullable(false);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

             $table->foreign('person_id')->references('id')->on('persons')->onDelete('restrict');
            $table->foreign('document_type')->references('id')->on('identity_document_types')->onDelete('restrict'); 
           
            $table->foreign('document_status')->references('id')->on('status_catgories')->onDelete('restrict'); 
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
        Schema::dropIfExists('person_identities');
    }
}
