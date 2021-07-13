<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskPriority extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function Priority(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\Priority','id','priorty_id');
    }
}
