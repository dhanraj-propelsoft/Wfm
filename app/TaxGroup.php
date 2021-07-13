<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxGroup extends Model
{
    public function taxes() {
        return $this->belongsToMany('App\Tax', 'group_tax', 'group_id', 'tax_id');
    }
}
