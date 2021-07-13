<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FsmDipMeasurement extends Model
{
     protected $fillable = ['tank_id','product_id','length','volume','status','organization_id','created_by','last_modified_by'];
}
