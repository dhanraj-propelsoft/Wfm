<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredVehicleSpec extends Model
{
    protected $fillable = ['registered_vehicle_id','registered_vehicle','spec_id','spec_value','organization_id','created_by','spec_value_id'];
}
