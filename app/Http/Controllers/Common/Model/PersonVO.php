<?php

namespace App\Http\Controllers\Common\Model;
use Illuminate\Support\Carbon;
use App\Core\ValueObject;

class PersonVO extends ValueObject
{
    public $pId;

    public $pSalutionId;

    public $pFirstName;

    public $pMiddleName;

    public $pLastName;

    public $pAlias;

    public $pDob;

    public $pGender;

    public $pBloodGroup;

    public $pPersonEmail;

    public $pPersonMobile;

    public $pPersonMobileDetails;

    public $pPersonEmailDetails;

    public $pPersonUserDetails;

    Public $pHavingUser;

    Public $pSalutionList;

    Public $pGenderList;

    Public $pBloodGroupsList;





    public function __construct($model = false)
    { 

        parent::__construct($model);
         
         if($model==false)
         {

          $this->pId = '';

          $this->pSalutionId = '';

          $this->pFirstName ='';

          $this->pMiddleName = '';

          $this->pLastName = '';

          $this->pAlias= '';

          $this->pDob = '';

          $this->pGender = '';

          $this->pBloodGroup = '';

          $this->pPersonMobile = '';

          $this->pPersonEmail = '';

          $this->pPersonMobileDetails = null;

          $this->pPersonEmailDetails = null;

          $this->pPersonUserDetails = null;

          $this->pHavingUser = "";
         }
         else
         {

           
              $this->pId = $model->id;

              $this->pSalutionId = ($model->salutation)?$model->salutation:"";

              $this->pFirstName =$model->first_name;

              $this->pMiddleName =($model->middle_name)?$model->middle_name:"";

              $this->pLastName = $model->last_name;

              $this->pAlias= ($model->alias)?$model->alias:"";

              $this->pDob = $model->dob;

              $this->pGender = ($model->gender_id)?$model->gender_id:"";

              $this->pBloodGroup = ($model->blood_group_id)?$model->blood_group_id:"";

              $this->pPersonMobileDetails =$model['personMobile'];

              $this->pPersonEmailDetails =$model['personEmail'];

              $this->pPersonUserDetails = $model['user'];

              $this->pHavingUser = ($model['user'])?true:false;
         }
    }

    public function setSalutationsList($items)
    {
        return $this->pSalutionList = $items;
    }
    public function setGenderList($items)
    {
        return $this->pGenderList = $items;
    }

    public function setBloodGroupList($items)
    {
        return $this->pBloodGroupsList = $items;
    }

}