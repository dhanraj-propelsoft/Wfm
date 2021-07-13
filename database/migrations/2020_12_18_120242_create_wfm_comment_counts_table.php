<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWfmCommentCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfm_comment_counts', function (Blueprint $table) {
            $table->increments('id');
        $table->integer('hrm_employees_id',false)->unsigned()->nullable();
        $table->integer('task_id',false)->unsigned()->nullable();          
        $table->integer('comment_count');
        $table->timestamps();
        
        $table->foreign('hrm_employees_id')->refference('id')->on('hrm_empoloyees')
            ->onUpdate('cascade')->onDelete('cascade');           

        $table->foreign('task_id')->refference('id')->on('wfm_tasks')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfm_comment_counts');
    }
}
