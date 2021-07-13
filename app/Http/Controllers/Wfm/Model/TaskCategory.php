<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{
    //
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function Category(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\Category','id','category_id');

    }

    public function Task(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\Task','id','task_id');

    }
}
