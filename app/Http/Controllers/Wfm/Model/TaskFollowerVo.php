<?php

namespace App\Http\Controllers\Wfm\Model;


class TaskFollowerVo 
{
    public $pId;

    public $pName;

    public $pDetails;

    public $pStartDate;

    public $pDueDate;

    public $pEndDate;

    public $pStatus;

    public $pEmployeeDatas;

    public $pProjectDatas;

    public $pProirityDatas;
  

        public function __construct($model = false)
        {
           
            
            if($model ==  false){


                $this->pId = "";
                
                $this->pName = "";

                $this->pDetails = "";

                $this->pStartDate = "";

                $this->pDueDate = "";

                $this->pEndDate = "";

                $this->pStatus = "";
            }
            else{
                
                $this->pId = $model->id; 

                $this->pName = $model->name;

                $this->pDetails = $model->details;

                $this->pStartDate = $model->start_date;

                $this->pDueDate = $model->due_date;

                $this->pDeadlineDate =$model->deadline_date;

                $this->pStatus = $model->status;
                
            }
        }
        public function setEmployeeDatas($pEmployeeDatas)
        {
        return  $this->pEmployeeDatas = $pEmployeeDatas;
        }

        public function setProjectDatas($ProjectDatas)
        {
        return  $this->pProjectDatas = $ProjectDatas;
        }

        public function setProirityDatas($priorityDatas)
        {
        return  $this->pProirityDatas = $priorityDatas;
        }

}