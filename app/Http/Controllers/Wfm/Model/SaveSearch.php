<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;

class SaveSearch extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        
        $this->connection = "mysql2";
    }
}
