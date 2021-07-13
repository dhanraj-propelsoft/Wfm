<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    //

    public function person(){
        return $this->belongsTo('App\Person','person_id');
    }

    
    public function business(){
        return $this->belongsTo('App\Business','business_id');
    }

    public function PeoplePersonType(){
        return $this->hasMany('App\PeoplePersonType','people_id');

    }

    public function address(){
        return $this->hasOne('App\PeopleAddress','people_id');
    }
}
