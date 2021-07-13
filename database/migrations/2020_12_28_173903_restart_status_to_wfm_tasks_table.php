<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestartStatusIdToWfmTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfm_tasks', function (Blueprint $table) {
            
        $table->integer('restart_status',false)->unsigned()->nullable()->comment('0-not yet restarted,1-restrated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfm_tasks', function (Blueprint $table) {
            //
        });
    }
}
