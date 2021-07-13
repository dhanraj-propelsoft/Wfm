<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeoplePersonType extends Model
{
    //
    protected $table = "people_person_types";
    public $timestamps = false;

    public function accountType(){
      return  $this->belongsTo('App\AccountPersonType','person_type_id');
    }
}
