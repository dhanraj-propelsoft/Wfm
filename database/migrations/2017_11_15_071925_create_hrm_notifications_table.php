
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrmNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id', false)->unsigned();
            $table->text('message')->nullable();
            $table->integer('organization_id', false)->unsigned();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');

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
        Schema::dropIfExists('hrm_notifications');
    }
}
