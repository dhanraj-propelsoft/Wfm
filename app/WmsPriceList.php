<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WmsPriceList extends Model
{
     protected $fillable = ['inventory_item_id','vehicle_segments_id','base_price','price','organization_id','created_by','last_modified_by'];
}
