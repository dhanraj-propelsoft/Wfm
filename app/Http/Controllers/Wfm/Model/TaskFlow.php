<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskFlow extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function TaskAction(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskAction','id','task_action_id');
    }

    public function TaskStatus(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskStatus','id','task_status_id');
    }
}
