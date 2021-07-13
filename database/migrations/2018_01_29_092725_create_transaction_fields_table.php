<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('field_type_id', false)->unsigned()->nullable();
            $table->integer('transaction_type_id', false)->unsigned()->nullable();
            $table->integer('field_format_id', false)->unsigned()->nullable();
            $table->string('sub_heading')->nullable()->comment('Under the heading');
            $table->string('label')->nullable()->comment('Label name');
            $table->integer('group_id', false)->unsigned()->nullable();
            $table->integer('status', false)->default(0)->nullable()->comment('0 - Visible on current form only, 1 - Visible On all Forms');
            $table->integer('required_status', false)->default(0)->nullable()->comment('0 - Not Required, 1 - Required');
            $table->integer('delete_status', false)->default(1)->comment('0 - Non Deletable, 1 - Deletable');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('field_type_id')->references('id')->on('field_types')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('field_format_id')->references('id')->on('field_formats')
                ->onUpdate('cascade')->onDelete('set null');

             $table->foreign('transaction_type_id')->references('id')->on('account_vouchers')->onUpdate('cascade')->onDelete('set null');

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('transaction_fields', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('transaction_fields')
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
        Schema::dropIfExists('transaction_fields');
    }
}
