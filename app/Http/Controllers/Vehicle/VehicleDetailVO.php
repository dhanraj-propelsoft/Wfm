<?php

namespace App\Http\Controllers\Vehicle;

use Illuminate\Support\Facades\Log;

class VehicleDetailVO
{

    public $id;
    
    public $registerNo;
    
    public $vehicleName;
    
    public $configurationList;
    
    public $configurationId;

    public $categoryName;

    public $categoryId;

    public $manufactYear;

    public $engineNo;

    public $chassisNo;

    public $permitTypeList;
    
    public $permitTypeId;
    
    public $insurance;

    public $insuranceDue;

    //public $fc;

    public $fcDue;

    public $taxDue;

    public $permitDue;

    public $monthDueDate;

    public $warrantyKM;

    public $warrantyYR;
    
    public $bankLoan;

    public $bankLoanList;
    

    // public $driverName;

    // public $driverNumber;

    public $customerDetail;
    // publ$ $pPassword;
    
   // public $status;
    
  //  public $statusId; 

    
    public function __construct($data = false, $permitTypeArray = false, $ConfigArray = false, $bankLoanArray = false, $customerDetail = false)
    {

        if ($data == false) {
            $this->id = '';
            

            $this->registerNo = '';
            
            $this->vehicleName = '';
            
            $this->configurationList = '';
            
            $this->configurationId = '';
        
            $this->categoryName = '';

            $this->categoryId = '';
        
            $this->manufactYear = '';
        
            $this->engineNo = '';
        
            $this->chassisNo = '';
        
            $this->permitTypeList = '';
            
            $this->permitTypeId = '';
            
            $this->insurance = '';
        
            $this->insuranceDue = '';
        
        //    $this->fc = '';
        
            $this->fcDue = '';

            $this->taxDue = '';
        
            $this->permitDue = '';
        
            $this->monthDueDate = '';
        
            $this->warrantyKM = '';
        
            $this->warrantyYR = '';
            
            $this->bankLoan = '';
        
            $this->bankLoanList = '';
            
        
            // $this->driverName = '';
        
            // $this->driverNumber = '';

            $this->customerDetail = '';
            // publ$ $pPassword = '';
            
         //   $this->status = '';
            
          //  $this->statusId = ''; 
        
        } else {
            $this->id = $data->id;
    
            $this->registerNo = $data->registration_no;
            
            $this->vehicleName = $data->vehicleVariant->vehicle_configuration;
            
            $this->configurationList = $ConfigArray;
            
            $this->configurationId = (int)$data->vehicle_configuration_id;
        
            $this->categoryName = $data->vehicleCategory->display_name;

            $this->categoryId = $data->vehicle_category_id;
        
            $this->manufactYear = $data->manufacturing_year;
        
            $this->engineNo = $data->engine_no;
        
            $this->chassisNo = $data->chassis_no;
        
            $this->permitTypeList = $permitTypeArray;
            
            $this->permitTypeId = $data->permit_type;
            
            $this->insurance =  $data->insurance;
        
            $this->insuranceDue = $data->premium_date;
        
            //$this->fc = '';
            $this->taxDue = $data->tax_due;

            $this->fcDue = $data->fc_due;
        
            $this->permitDue = $data->permit_due;
        
            $this->monthDueDate = $data->month_due_date;
        
            $this->warrantyKM =  $data->warranty_km;
        
            $this->warrantyYR = $data->warranty_years;
            
            $this->bankLoan = $data->bank_loan;
        
            $this->bankLoanList = $bankLoanArray;
            
        
            // $this->driverName = $data->driver;
        
            // $this->driverNumber = $data->driver_mobile_no;

            $this->customerDetail = $customerDetail;
            // publ$ $pPassword = '';
            
        }
    }
}
