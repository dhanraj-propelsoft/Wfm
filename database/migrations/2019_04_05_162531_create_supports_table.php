<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ticket_number');
                $table->integer('priority',false)->default(1)->comment('1-high,2-medium,3-low');
                $table->string('ticket_name');
                $table->text('ticket_message')->nullable();
                $table->text('propel_reply')->nullable();
                $table->integer('organization_id', false)->unsigned()->nullable();
                $table->integer('assigned_by',false)->unsigned()->nullable();
                 $table->integer('issued_by',false)->unsigned()->nullable();
                $table->integer('status', false)->default(1)->comment('1-open,2-Progress,3-close
');
                $table->integer('created_by');
                $table->integer('last_modified_by', false)->unsigned()->nullable();
                $table->timestamps();
                  $table->softDeletes();

                $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

                $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supports');
    }
}
