<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class WfmTasks extends Model
{
    //

    //protected $table = 't2';
/**
*The function get the tags based on task_id


**/
  public function Tasktags(){
  	return $this->hasMany('App\WfmTasktag','task_id');
  }


}
