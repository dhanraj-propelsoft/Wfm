<?php

namespace App\Http\Controllers\Wfm\Model;
// use App\Core\ValueObject;

class CategoryVO 
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

                $this->pStatus = ($model->status == 1 ? true : 
                                 ($model->status == 0 ? false:  
                                ''));
                
            }
        }

}