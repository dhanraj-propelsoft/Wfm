<?php

namespace App\Http\Controllers\Organization\Model;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = "mysql2";
    }
}
