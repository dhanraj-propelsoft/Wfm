<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(pAuthMainDBName())->create('persons', function (Blueprint $table) {
            
             $table->increments('id');
            $table->string('crm_code', 10)->unique();
            $table->integer('salutation', false)->unsigned()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();         
            $table->string('alias')->nullable();
            $table->date('dob')->nullable();
            $table->integer('gender_id', false)->unsigned()->nullable();
            $table->integer('blood_group_id', false)->unsigned()->nullable();
            $table->integer('life_state_id', false)->unsigned()->nullable()->default(1);
            $table->integer('depone_state_id', false)->unsigned()->nullable()->default(1);
             $table->integer('current_state_id', false)->unsigned()->nullable();     
           
           
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

             $table->foreign('salutation')->references('id')->on(pAuthMainDBName() .'salutations')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('gender_id')->references('id')->on(pAuthMainDBName() .'genders')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('blood_group_id')->references('id')->on(pAuthMainDBName() .'blood_groups')
                ->onUpdate('cascade')->onDelete('set null');


            $table->foreign('life_state_id')->references('id')->on(pAuthMainDBName() .'person_life_states')
                ->onUpdate('cascade')->onDelete('set null');  

            $table->foreign('depone_state_id')->references('id')->on(pAuthMainDBName() .'person_depone_states')
                ->onUpdate('cascade')->onDelete('set null');  

            $table->foreign('current_state_id')->references('id')->on(pAuthMainDBName() .'person_current_states')
                ->onUpdate('cascade')->onDelete('set null');         

            $table->foreign('created_by')->references('id')->on(pAuthMainDBName() .'users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on(pAuthMainDBName() .'users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::connection(pAuthMainDBName())->dropIfExists('persons');
    }
}
