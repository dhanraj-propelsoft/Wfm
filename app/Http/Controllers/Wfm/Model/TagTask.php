<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TagTask extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function Tag(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\Tag','id','tag_id');
    }
}
