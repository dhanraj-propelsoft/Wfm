<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WfmProjects extends Model
{
    //

    public function ProjectTasks()
	{
	  return $this->hasMany('App\WfmTasks', 'project_id', 'id');
	}
}
