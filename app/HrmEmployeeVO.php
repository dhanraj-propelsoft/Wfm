<?php

namespace App;
use Carbon\Carbon;

class HrmEmployeeVO
{
    public $pId;

    public $pPersonId;

    public $pTitleId;

    public $pFirstName;

    public $pMiddleName;

    public $pLastName;

    public $pEmployeeCode;

    public $pPhoneNo;

    public $pEmergencyNo;

    public $pEmail;

    public $pDob;

    public $pBloodGroupId;

    public $pGenderId;

    public $pShiftId;

    public $pKnownLanguage;

    public $pMartialStatus;

    public $pMotherName;

    public $pFatherName;

    public $pPANNo;

    public $pAADHAR;

    public $pPassportNo;

    public $pLicenseNo;

    public $pLicenseType;
    
    public $pStaftTypeId;

    public $pStatus;

    public $pReportingPerson;

    public $pLedgerId;



        public function __construct($model = false)
        {
           
        
            if($model ==  false){
               
                $this->pId = "";

                $this->pPersonId= "";

                $this->pTitleId= "";

                $this->pFirstName= "";

                $this->pMiddleName= "";

                $this->pLastName= "";

                $this->pEmployeeCode= "";

                $this->pPhoneNo= "";

                $this->pEmergencyNo= "";

                $this->pEmail= "";

                $this->pDob = "";

                $this->pBloodGroupId= "";

                $this->pGenderId= "";

                $this->pShiftId= "";

                $this->pKnownLanguage= "";

                $this->pMartialStatus= "";

                $this->pMotherName= "";

                $this->pFatherName= "";

                $this->pPANNo= "";

                $this->pAADHAR= "";

                $this->pPassportNo= "";

                $this->pLicenseNo= "";

                $this->pLicenseType= "";
                
                $this->pStaftTypeId= "";

                $this->pStatus= "";

                $this->pReportingPerson= "";

                $this->pLedgerId= "";

            }
            else{
                
                $this->pId = $model->id;

                $this->pPersonId = $model->person_id;

                $this->pTitleId = ($model->title_id)?$model->title_id:'';

                $this->pFirstName= $model->first_name;

                $this->pMiddleName = ($model->middle_name)?$model->middle_name:'';

                $this->pLastName = ($model->last_name)?$model->last_name:'';

                $this->pEmployeeCode =($model->employee_code)?$model->employee_code:'';

                $this->pPhoneNo = ($model->phone_no)?$model->phone_no:'';

                $this->pEmergencyNo = ($model->emergency_no)?$model->emergency_no:'';

                $this->pEmail = ($model->email)?$model->email:'';

                $this->pDob = ($model->dob)?$model->dob:'';

                $this->pBloodGroupId = ($model->blood_group_id)?$model->blood_group_id:'';

                $this->pGenderId = ($model->gender_id)?$model->gender_id:'';

                $this->pShiftId = ($model->shift_id)?$model->shift_id:'';

                $this->pKnownLanguage = ($model->known_languages)?$model->known_languages:'';

                $this->pMartialStatus = ($model->martial_status)?$model->martial_status:'';

                $this->pMotherName = ($model->mother_name)?$model->mother_name:'';

                $this->pFatherName= ($model->father_name)?$model->father_name:'';

                $this->pPANNo= ($model->pan_no)?$model->pan_no:'';

                $this->pAADHAR= ($model->aadhar_no)?$model->aadhar_no:'';

                $this->pPassportNo= ($model->passport_no)?$model->passport_no:'';

                $this->pLicenseNo= ($model->license_no)?$model->license_no:'';

                $this->pLicenseType= ($model->license_type_id)?$model->license_type_id:'';
                
                $this->pStaftTypeId= ($model->staff_type_id)?$model->staff_type_id:'';

                $this->pStatus= ($model->status)?$model->status:'';
                
                $this->pReportingPerson= ($model->reporting_person_id)?$model->reporting_person_id:'';

                $this->pLedgerId = ($model->ledger_id)?$model->ledger_id:'';
                
            }
        }
        

}