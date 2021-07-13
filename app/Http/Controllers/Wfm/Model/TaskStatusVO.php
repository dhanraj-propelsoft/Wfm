<?php

namespace App\Http\Controllers\Wfm\Model;
use Carbon\Carbon;

class TaskStatusVO
{
    public $pId;

    public $pName;

    public $pStatus;

        public function __construct($model = false)
        {
           
        
            if($model ==  false){
               
                $this->pId = ""; 

                $this->pName = "";

                $this->pStatus = "";
            }
            else{
                
                $this->pId = $model->id; 

                $this->pName = $model->name;

                $this->pStatus = ($model->status == 1 ? "Active" : 
                                 ($model->status == 0 ? "InActive":  
                                ''));
                
            }
        }
        

}