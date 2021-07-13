<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WfmFollower extends Model
{
    //
    public function follower_name() {

    return $this->belongsTo('App\HrmEmployee','follower_id','id');

			}
}
