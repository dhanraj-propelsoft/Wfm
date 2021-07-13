<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        
        $this->connection = "mysql2";
    }

    public function Project(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\Project','id','project_id');

    }

    public function ActiveProject(){

        return $this->Project()->where('status',1)->latest();
        // return $this->hasOne('App\Http\Controllers\Wfm\Model\Project','id','project_id');

    }
}
