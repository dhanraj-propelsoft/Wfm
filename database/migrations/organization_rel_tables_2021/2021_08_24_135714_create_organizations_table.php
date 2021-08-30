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
    if (App::environment('local')) {
                // The environment is local
                Schema::disableForeignKeyConstraints();
                $this->down();
                Schema::enableForeignKeyConstraints();
            }

        Schema::connection(pBussinessDBConnectionName())->create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('organization_name','50')->nullable(false);
            $table->string('unit_name','50')->nullable(true);
            $table->string('alias','50')->nullable(true);
            $table->date('started_date')->nullable(true);
            $table->year('year_of_establishment')->nullable(true);
            $table->integer('organization_category_id')->unsigned()->nullable(false);
            $table->integer('organization_ownership_id')->unsigned()->nullable(false);
            $table->string('propel_id','20')->nullable(true);
            $table->integer('status')->nullable(true);
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();


            $table->timestamps();
            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->softDeletes();
            $table->foreign('created_by')->references('id')->on(pAuthMainDBName() . '.users')->onDelete('restrict');
            $table->foreign('last_modified_by')->references('id')->on(pAuthMainDBName() . '.users')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on(pAuthMainDBName() . '.users')->onDelete('restrict');
            $table->foreign('organization_category_id')->references('id')->on('organization_categories')->onDelete('restrict');
            $table->foreign('organization_ownership_id')->references('id')->on('organization_ownerships')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(pBussinessDBConnectionName())->dropIfExists('organizations');
    }
}
