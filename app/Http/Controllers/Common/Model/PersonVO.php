<?php

namespace App\Http\Controllers\Common\Model;
use Illuminate\Support\Carbon;
use App\Core\ValueObject;

class PersonVO extends ValueObject
{
    public $pId;
    public $pFirstName;
    public $pMiddleName;
    public $pLastName;
    public $pAlias;
    public $pDob;
    public $pGender;
    public $pBloodGroup;
    public $pPersonMobileDetails;
    public $pPersonUserDetails;



    public function __construct($model = false)
    {
        parent::__construct($model);
         if($model==false)
         {
          $this->pId = '';
          $this->pFirstName ='';
          $this->pMiddleName = '';
          $this->pLastName = '';
          $this->pAlias= '';
          $this->pDob = '';
          $this->pGender = '';
          $this->pBloodGroup = '';
          $this->pPersonMobileDetails ="";
          $this->pPersonUserDetails ="";
         }
         else
         {

              $this->pId = $model->id;
              $this->pFirstName =$model->first_name;
              $this->pMiddleName =$model->middle_name;
              $this->pLastName = $model->last_name;
              $this->pAlias= $model->alias;
              $this->pDob = $model->dob;
              $this->pGender = $model->gender_id;
              $this->pBloodGroup = $model->blood_group;
              $this->pPersonMobileDetails =$model['personMobile'];
              $this->pPersonUserDetails =$model['user'];
         }
    }

}