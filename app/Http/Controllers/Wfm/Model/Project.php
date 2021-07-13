<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        
        $this->connection = "mysql2";
    }

    public function hrmEmployees(){

        return $this->hasOne('App\HrmEmployee','id','project_owner');
    }

    public function Category(){

        return $this->hasOne('App\Http\Controllers\Wfm\Model\Category','id','category_id');
    }

    public function ActiveCategory(){

        // return $this->hasOne('App\Http\Controllers\Wfm\Model\Category','id','category_id');

        return $this->Category()->where('status','=', 1)->first();

         // return $this->videos()->where('available','=', 1)->get();
    }

    
}
