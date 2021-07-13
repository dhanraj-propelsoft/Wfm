<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItemGroup extends Model
{
     protected $fillable = array('item_group_id', 'item_id', 'quantity');
}
