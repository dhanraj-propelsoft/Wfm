<?php

namespace App\Http\Controllers\Wfm\Model;
use Carbon\Carbon;

class TaskAttachmentVO
{
    public $pId;

    public $pTaskId;

    public $pUploadedFilePath;

    public $pFileOriginalName;

    public $pStatus;

        public function __construct($model = false)
        {
           
        
            if($model ==  false){
               
                $this->pId = ""; 

                $this->pTaskId = "";

                $this->pUploadedFilePath = "";

                $this->pFileOriginalName = "";

                $this->pStatus = "";
            }
            else{
                    
                $this->pId = $model->id; 

                $this->pTaskId = $model->task_id;

                // here Org Id Hardcore
                $this->pUploadedFilePath = task_attachment_path(53,$model->task_id).$model->upload_file;

                $this->pFileOriginalName = $model->file_original_name;

                $this->pStatus = ($model->status == 1 ? "Active" : 
                                 ($model->status == 0 ? "InActive":  
                                ''));
                
            }
        }
        

}