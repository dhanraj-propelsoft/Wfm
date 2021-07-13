<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleChecklist extends Model
{
    //

     public function jobCardChecklist(){
        return $this->hasOne('App\Http\Controllers\Tradewms\Jobcard\Model\JobCardChecklist','checklist_id','id');
     }
}
