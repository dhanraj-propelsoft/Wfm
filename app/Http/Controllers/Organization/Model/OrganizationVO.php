<?php

namespace App\Http\Controllers\Organization\Model;

class OrganizationVO 
{
    public $pId;

    public $pName;

    public $pUnitName;

    public $pAlias;

    public $pStaredDate;

    public $pYearOfEstblishment;

    public $pStatus;

    // Dependent VO

    public $pCategory;

    public $pOwnership;



        public function __construct($model = false)
        {
           
        
            if($model ==  false){
                
                $this->pId = "";

                $this->pName = "";

                $this->pUnitName = "";

                $this->pAlias = "";

                $this->pStaredDate = "";

                $this->pYearOfEstblishment = "";
                
                $this->pCategory = "";

                $this->pOwnership = "";
                
                $this->pStatus = "";
            }
            else{
                
                $this->pId = $model->id;

                $this->pName = $model->organization_name;

                $this->pUnitName = ($model->unit_name)?$model->unit_name:"";

                $this->pAlias = ($model->alias)?$model->alias:"";

                $this->pStaredDate = ($model->started_date)?$model->started_date:"";

                $this->pYearOfEstblishment = ($model->year_of_establishment)?$model->year_of_establishment:"";

                $this->pCategory = $model->OrganizationCategory->name;

                $this->pOwnership = $model->OrganizationOwnership->name;

                $this->pStatus = ($model->status == 1 ? true : 
                                 ($model->status == 0 ? false:  
                                ''));
                
            }
        }

}