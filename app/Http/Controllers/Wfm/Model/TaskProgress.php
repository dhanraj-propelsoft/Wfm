<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskProgress extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }


    public function TaskAction(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskAction','id','task_action_id');
    }
}
