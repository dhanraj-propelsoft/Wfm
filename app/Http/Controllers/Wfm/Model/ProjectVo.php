<?php

namespace App\Http\Controllers\Wfm\Model;
use Carbon\Carbon;

class ProjectVo
{
    public $pId;

    public $pName;

    public $pDetails;

    public $pProjectOwner;

    public $pCategoryId;

    public $pStartDate;

    public $pDeadlineDate;

    public $pStatus;

    // Dependent VO
    public $pEmployeeDatas;

    public $pCategoryDatas;


        public function __construct($model = false)
        {
           
        
            if($model ==  false){
               
                $this->pId = ""; 

                $this->pName = "";

                $this->pDetails = "";

                $this->pProjectOwner = "";

                $this->pStartDate = "";

                $this->pCategoryId = "";

                $this->pDeadlineDate = "";

                $this->pStatus = "";
            }
            else{
                
                $this->pId = $model->id; 

                $this->pName = $model->name;

                $this->pDetails = isset($model->details)?$model->details:'';

                $this->pProjectOwner = (int)$model->project_owner;

                $this->pCategoryId = (int)$model->category_id;
               
                $this->pStartDate =  isset($model->start_date)?$model->start_date:'';

                $this->pDeadlineDate = isset($model->deadline_date)?$model->deadline_date:'';

                // $this->pDeadlineDate = isset($this->pDeadline == LifeTime)? "LifeTime":Carbon::parse($model->deadline_date)->format('d-m-Y');

                $this->pStatus = ($model->status == 1 ? true : 
                                 ($model->status == 0 ? false:  
                                ''));
                
            }
        }
        public function setEmployeeListVO($model)
        {
            return  $this->pEmployeeDatas = $model;
        }

        public function setCategoryListVO($model)
        {

            return  $this->pCategoryDatas = $model;
        }

}