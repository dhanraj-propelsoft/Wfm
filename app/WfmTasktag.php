<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\WfmTag;
use App\WfmTasks;

class WfmTasktag extends Model
{
    //

  public function Tags(){
  	return $this->belongsTo('App\WfmTag','tag_id')->select(array('id', 'tag_name'));
  }


  public function TaskTag(){
  	return $this->hasOne('App\WfmTasks','id')->select(array('id'));
  }
}
