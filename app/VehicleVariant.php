<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleVariant extends Model
{
    //

    public function vehicleCategory(){
        return $this->belongsTo('App\VehicleCategory','category_id');
    }
}
