<?php

namespace App\Http\Controllers\Wfm\Model;


class TaskCreateVo 
{
    public $pId;

    public $pName;

    public $pDetails;

    public $pStartDate;

    public $pDueDate;

    public $pEndDate;

    public $pStatus;

// Dependent Vo
    public $pCategoryDatas;

    public $pAssignedbyList;

    public $pAssignedtoList;

    public $pFollowerList;

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
        public function setAssignedbyList($model)
        {
            return  $this->pAssignedbyList = $model;
        }

        public function setAssignedtoList($model)
        {
            return  $this->pAssignedtoList = $model;
        }

        public function setFollowerList($model)
        {
        return  $this->pFollowerList = $model;
        }

        public function setCategoryDatas($model)
        {
        return  $this->pCategoryDatas = $model;
        }

        public function setProjectDatas($model)
        {
        return  $this->pProjectDatas = $model;
        }

        public function setProirityDatas($model)
        {
        return  $this->pProirityDatas = $model;
        }

}