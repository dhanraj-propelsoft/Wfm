<?php

namespace App\Http\Controllers\Wfm\Model;
use Carbon\Carbon;

class TaskVO
{
    public $pId;

    public $pName;

    public $pDetails;

    public $pStartDate;

    public $pDueDate;

    public $pEndDate;

    public $pRestartStatus;

    public $pStatus;

    //Dependent VOs
    public $pCategoryVO;

    public $pEmployeeVO;

    public $pProjectVO;

    public $pTaskCreatorVO;
    
    public $pTaskWorkforceVO;
    
    public $pPriorityVO;
    
    public $pTaskAttachmentVOs;
    
    public $pTasktagVOs;

    public $pTaskActionVO;
    
    public $pTaskStatusVO;

    public $pTaskFollowerVO;

    public $pTaskActionListVo;



        public function __construct($model = false)
        {
           
        
            if($model ==  false){
               
                $this->pId = ""; 

                $this->pName = "";

                $this->pDetails = "";

                $this->pStartDate = "";

                $this->pDueDate = "";

                $this->pEndDate = "";

                $this->pRestartStatus = "";

                $this->pStatus = "";

            }
            else{
                
                $this->pId = $model->id; 

                $this->pName = $model->name;

                $this->pDetails = isset($model->details)?$model->details:'';

                $this->pStartDate = isset($model->start_date)?Carbon::parse($model->start_date)->format('Y-m-d'):'';

                $this->pDueDate = isset($model->due_date)?Carbon::parse($model->due_date)->format('Y-m-d'):'';

                $this->pEndDate = isset($model->end_date)?Carbon::parse($model->end_date)->format('Y-m-d'):'';

                $this->pRestartStatus = "";

                $this->pStatus = ($model->status == 1 ? "Active" : 
                                 ($model->status == 0 ? "InActive":  
                                ''));
                
            }
        }

    public function setTaskCategoryVO($model)
    {
        return $this->pCategoryVO = $model;
    }

    public function setEmployessVO($model)
    {
        return $this->pEmployeeVO = $model;
    }

    public function setTaskProjectVO($projectVO)
    {
        return $this->pProjectVO = $projectVO;
    }
    public function setTaskCreatorVO($taskCreatorVO)
    {
        return $this->pTaskCreatorVO = $taskCreatorVO;
    }
    public function setTaskWorkforceVO($TaskWorkforceVO)
    {
        return $this->pTaskWorkforceVO = $TaskWorkforceVO;
    }
    public function setTaskPriorityVO($PriorityVO)
    {
        return $this->pPriorityVO = $PriorityVO;
    }
    public function setTaskAttachmentVO($taskAttachmentVOs)
    {
        return $this->pTaskAttachmentVOs = $taskAttachmentVOs;
    }
    public function setTaskTagVO($tasktagVOs)
    {
        return $this->pTasktagVOs = $tasktagVOs;
    }

    public function setTaskActionVO($taskActionVO)
    {
        return $this->pTaskActionVO = $taskActionVO;
    }
    public function setTaskStatusVO($TaskStatusVO)
    {
        return $this->pTaskStatusVO = $TaskStatusVO;
    }

    public function setTaskFollowerVO($taskFollowerVOs)
    {
        return $this->pTaskFollowerVO = $taskFollowerVOs;
    }

    public function setTaskActionListVO($model)
    {
        return $this->pTaskActionListVo = $model;
    }

        

}