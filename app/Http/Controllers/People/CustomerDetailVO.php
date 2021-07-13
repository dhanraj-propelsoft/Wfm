<?php

namespace App\Http\Controllers\People;

use Illuminate\Support\Facades\Log;
use Session;

class CustomerDetailVO
{

    public $id;

    public $userType;

    public $peopleId;
    
    public $associatedType;

    public $associatedTypeId;

    public $isAssoicated;

    public $firstName;

    public $lastName;

    public $nameWithMobileNo;

    public $businessName;

    public $businessAliasName;

    public $GST;

    public $email;

    public $mobileNo;

    public $address;
    
    public $stateId;
    
    public $activeStateDropDown;

    public $cityId;

    public $activeCityDropDown;

    public $PIN;

    public $states;

    public $orgId;
    

    
    public function __construct($data = false,$userType = false,$orgId = false,$people = false,$associatedType = false, $activeStateDropDown = false,$activeCityDropDown = false,$states = false )
    {
       

        if ($data == false) {

            
            $this->id = '';

            $this->userType = '';

            $this->peopleId = '';

            $this->associatedType = '';

            $this->associatedTypeId = '';

            $this->isAssoicated = '';

            $this->firstName = '';

            $this->lastName = '';

            $this->nameWithMobileNo = '';

            $this->businessName = '';

            $this->businessAliasName = '';

            $this->GST = '';

            $this->email= '';

            $this->mobileNo  = '';
            
            $this->address = '';

            $this->stateId = '';

            $this->activeStateDropDown = '';

            $this->cityId = '';

            $this->activeCityDropDown = '';

            $this->PIN = '';


            $this->states = '';

            $this->orgId = '';
        } else {
          
            // check people 
            // string convert to number
            $userType = $people?(int) $people->user_type:(int)$userType;
            $peopleId = "";

            // if people orgId equal to org return org id else null
            if($people && $people->organization_id){
                if( $orgId &&  ( $orgId ==  $people->organization_id)){
                    $orgId = $people->organization_id;
                    $peopleId = $people->id;

                }else{
                    $orgId =  '';
                }
            }

            $this->id = $data->id;

            $this->userType = $userType; // string convert to number

            $this->peopleId = $peopleId;

            $this->associatedType = $associatedType && $orgId? $associatedType->accountType->display_name:"Unassigned";
            
            $this->associatedTypeId = $associatedType && $orgId? $associatedType->accountType->id:"";

            $this->isAssoicated = $orgId?"YES":"NO";

            $mobileNo = "";
            if($data->address){
                $mobileNo = $data->address->mobile_no;
            }



            $this->nameWithMobileNo = $userType >=0 ?( $userType == 0? $data->first_name.' '.$data->last_name."-".$mobileNo:$data->business_name."-".$mobileNo):'';

            $this->firstName =  $userType == 0?$data->first_name:"";

            $this->lastName = $userType == 0?$data->last_name:"";

            $this->businessName = $userType == 1?$data->business_name:"";

            $this->businessAliasName =  $userType == 1?$data->alias:"";

            $this->GST = $userType == 1?$data->gst:"";

            $this->email = $data->address?$data->address->email_address:"";




            $this->mobileNo  = $data->address?$data->address->mobile_no:"";
            
            $this->address = $data->address?$data->address->address:"";

            $this->stateId =  $data->address && $data->address->city?(int)$data->address->city->state_id:""; // string convert to number

            $this->activeStateDropDown = $activeStateDropDown?$activeStateDropDown:'';

            $this->cityId = $data->address && $data->address->city?$data->address->city->id:""; // string convert to number

            $this->PIN = $data->address?$data->address->pin:"";

            $this->activeCityDropDown = $activeCityDropDown?$activeCityDropDown:'';

            $this->states = $states?$states:'';

            $this->orgId =  $orgId;
        }
    }
}
