<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskWorkforce extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function HrmEmployee(){

    	return $this->hasOne('App\HrmEmployee','id','workforcer_id');
    }

    public function Person(){

    	return $this->hasOne('App\Person','id','workforcer_id');
    }
}
