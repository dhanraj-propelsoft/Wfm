<?php

namespace App\Http\Controllers\Tradewms\Jobcard\Model;

use Illuminate\Database\Eloquent\Model;

class JobCardDetail extends Model
{
    //

    public function jobCardStatus(){
        return $this->belongsTo('App\VehicleJobcardStatus','jobcard_status_id')->select(array('id', 'name','display_name','description','status'));
    }

    public function serviceType(){
        return $this->belongsTo('App\ServiceType','service_type');
    }

    public function assignedToEmployee(){
        return $this->belongsTo('App\HrmEmployee','assigned_to')->select(array('id', 'person_id','title_id','first_name','middle_name','last_name','employee_code','phone_no','email','organization_id'));
    }

    public function vehicleDetail(){
        return $this->belongsTo('App\VehicleRegisterDetail','registration_id');
    }

    // public function transaction(){
    //     return $this->hasOne('App\VehicleRegisterDetail','registration_id');
    // }
}
