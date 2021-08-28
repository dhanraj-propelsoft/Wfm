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

    public function OrganizationAddress(){

        return $this->hasOne('App\Http\Controllers\Organization\Model\OrganizationAddress');
    }

    public function OrganizationCategory(){

        return $this->hasOne('App\Http\Controllers\Organization\Model\OrganizationCategory','id','organization_category_id');
    }

    public function OrganizationOwnership(){

        return $this->hasOne('App\Http\Controllers\Organization\Model\OrganizationOwnership','id','organization_ownership_id');
    }
}
