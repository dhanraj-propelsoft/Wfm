<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleSegmentDetail extends Model
{
    protected $fillable = ['vehicle_segment_id','vehicle_make_id','vehicle_model_id','vehicle_variant_id','vehicle_variant_name','created_by'];
}
