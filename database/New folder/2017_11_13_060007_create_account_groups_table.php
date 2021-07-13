<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->integer('status', false)->default(1)->comment('0 - In-Active, 1 - Active');
            $table->integer('approval_status', false)->default(0)->comment('0 - Not Approved, 1 - Approved');
            $table->integer('parent_id', false)->unsigned()->nullable();
            $table->integer('account_head', false)->unsigned();
            $table->string('opening_type')->nullable();
            $table->integer('organization_id', false)->unsigned()->nullable();
            $table->integer('user_id', false)->unsigned()->nullable();
            $table->integer('delete_status', false)->default(1)->comment('0 - Non Deletable, 1 - Deletable');
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->unique([ 'name', 'organization_id']);
            
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('last_modified_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('account_head')->references('id')->on('account_heads')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('account_groups', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('account_groups')
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
        Schema::dropIfExists('account_groups');
    }
}
