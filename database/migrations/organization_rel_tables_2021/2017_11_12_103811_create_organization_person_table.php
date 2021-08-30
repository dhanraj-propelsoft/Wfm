<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(pBussinessDBConnectionName())->create('organization_person', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned();
            $table->integer('person_id')->unsigned();


            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on(pAuthMainDBName() . '.persons')->onDelete('restrict');


            $table->primary([ 'organization_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(pBussinessDBConnectionName())->dropIfExists('organization_people');
    }
}
