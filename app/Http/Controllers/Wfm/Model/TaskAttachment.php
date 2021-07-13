<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }
}
