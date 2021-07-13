<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleOrganization extends Model
{

	protected $table = 'module_organization';
    protected $fillable = array('module_id', 'organization_id');
    public $timestamps = false;

}
