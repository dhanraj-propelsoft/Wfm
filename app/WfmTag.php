<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class WfmTag extends Model
{
    //

  public function Tags(){
  	return $this->hasMany('App\WfmTasktag','tag_id');
  }
}
