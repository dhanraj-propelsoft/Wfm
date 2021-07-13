<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleSpecification extends Model
{
    protected $fillable = ['pricing','vehicle_spec_id','organization_id','vehicle_type_id','created_by','used'];
}
