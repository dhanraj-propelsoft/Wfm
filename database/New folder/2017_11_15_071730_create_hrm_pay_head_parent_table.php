<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmPayHeadParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_pay_head_parent', function (Blueprint $table) {
            $table->integer('pay_head_parent_id')->unsigned();
            $table->integer('pay_head_id')->unsigned();


            $table->foreign('pay_head_id')->references('id')->on('hrm_pay_heads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pay_head_parent_id')->references('id')->on('hrm_pay_heads')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['pay_head_id', 'pay_head_parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_pay_head_parent');
    }
}
