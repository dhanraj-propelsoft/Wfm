<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VehicleRegisterDetail extends Model
{

    public function owner()
    {
        Log::info('vehicleRegisterDetail model' . $this->user_type);
        if ($this->user_type == 0) {
            return $this->belongsTo('App\Person', 'owner_id');
        } else if ($this->user_type == 1) {
            return $this->belongsTo('App\Business', 'owner_id');
        }
    }

    public function vehicleVariant()
    {
        return $this->belongsTo('App\VehicleVariant', 'vehicle_configuration_id');
    }
    public function vehicleCategory()
    {
        return $this->belongsTo('App\VehicleCategory', 'vehicle_category_id');
    }

    public function vehicleOrgAssoc(){
        return $this->hasOne('App\WmsVehicleOrganization', 'vehicle_id','id');
    }
}
