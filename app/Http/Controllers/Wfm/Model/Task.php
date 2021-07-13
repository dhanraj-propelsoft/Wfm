<?php

namespace App\Http\Controllers\Wfm\Model;

use Illuminate\Database\Eloquent\Model;
use App\HrmEmployee;
use Auth;
use Illuminate\Support\Facades\Log;

class Task extends Model
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql2";
    }

    public function CategoryTask(){

        return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskCategory')->latest();
    }

    public function ProjectTask(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\ProjectTask')->latest();
    }

    

    public function TaskCreator(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskCreator')->latest();
    }

    public function LogindTaskCreator(){

        $loginId = Auth::user()->person_id;

        
        
        $HrmEmployeeId = HrmEmployee::where('person_id',$loginId)->first()->id;

        
        return $this->TaskCreator()->where('creator_id',$HrmEmployeeId)->latest();
    }

    public function TaskWorkForce(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskWorkforce','task_id','id')->latest();
    }

    public function LogindTaskWorkforcer(){

        $loginId = Auth::user()->person_id;

        $HrmEmployeeId = HrmEmployee::where('person_id',$loginId)->first()->id;

        return $this->TaskWorkForce()->where('workforcer_id',$HrmEmployeeId)->latest();
    }

    public function TaskPriority(){

    	return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskPriority')->latest();
    }

    public function TaskAttachment(){

    	return $this->hasMany('App\Http\Controllers\Wfm\Model\TaskAttachment');
    }

    public function TaskTag(){

    	return $this->hasMany('App\Http\Controllers\Wfm\Model\TagTask');
    }

    public function TaskFollower(){

        return $this->hasMany('App\Http\Controllers\Wfm\Model\TaskFollower');
    }

    public function LoginedTaskFollower(){
        $loginId = Auth::user()->person_id;

        $HrmEmployeeId = HrmEmployee::where('person_id',$loginId)->first()->id;

        return $this->hasMany('App\Http\Controllers\Wfm\Model\TaskFollower')->where('follower_id',$HrmEmployeeId);
    }

    public function TaskFlow(){

        return $this->hasOne('App\Http\Controllers\Wfm\Model\TaskFlow')->latest();
    }


}
