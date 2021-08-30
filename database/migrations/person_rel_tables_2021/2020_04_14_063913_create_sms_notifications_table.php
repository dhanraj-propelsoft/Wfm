<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsNotificationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
      
        
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from_number', '12');
            $table->string('to_number', '12');
            $table->string('subject',50);
            $table->text('content_addressed_to'); // to whom the email should be addressed to
            $table->text('content');
            $table->string('message_id',20)->nullable();
            $table->string('category',25);
            $table->text('error')->nullable();
            $table->integer('retry_count')->default(0); // 3 retry count to send email upon failure
           // $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('status')->default(0); //0- not sent out, 1 - sent out

            //Foreign keys
            //$table->foreign('organization_id')->references('id')->on('organizations')->onDelete('restrict');
            
           
            // Common Fields and foreign keys
            $table->integer('created_by', false)->unsigned()->nullable();
            $table->integer('last_modified_by', false)->unsigned()->nullable();
            $table->timestamps();

            $table->integer('deleted_by', false)->unsigned()->nullable();
            $table->softDeletes();

//             $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
//             $table->foreign('last_modified_by')->references('id')->on('users')->onDelete('restrict');
//             $table->foreign('deleted_by')->references('id')->on('users')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_notifications');
    }
}
