<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WfmTaskDetails extends Model
{
    //


    public function assigner()
    {
        return $this->belongsTo('App\HrmEmployee','assigned_by','id')->whereNotIn('');//c_id - customer id
    }
    public function assignee()
    {
        return $this->belongsTo('App\HrmEmployee','assigned_to','id');//s_id - staff id
    }
}
