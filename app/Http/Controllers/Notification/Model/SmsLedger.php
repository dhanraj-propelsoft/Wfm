<?php

namespace App\Http\Controllers\Notification\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class SmsLedger extends Model
{
     use SoftDeletes;
     
     public function smsNotification()
     {
         return $this->belongsTo('App\Notification\Model\SmsNotification','sms_notification_id');
         
     }
}
