<?php

namespace App\Http\Controllers\Tradewms\Jobcard;

use Illuminate\Support\Facades\Log;

class JobcardListVO
{

    public $pId;

    // only for add family member
    //  public $pPrimaryUserMobile;

    public $pDate;

    public $pOrderNo;

    public $pCustomer;

    public $pVehicleId;

    public $pVehicleRegisterNo;

    public $pAssignToEmployee;

    public $pAdvanceAmount;

    public $pCreated;

    public $pLastModified;
    // public $pPassword;
    public $pStatus;

    public $pStatusId;
    
    public $pHasEstimate = 'False';
    public $pEstimateType;
    public $pEstimateId;
    
    public $pHasInvoice = 'False';
    public $pInvoiceType;
    public $pInvoiceId;
    public $pIsInvoiceApproved = 'False';

    public $pAckURL = '';
    
    public function __construct($data = false, $ackURL = false)
    {

        if ($data == false) {
            $this->pId = '';


            $this->pDate = '';

            $this->pOrderNo = '';

            $this->pCustomer = '';

            $this->pVehicleId = '';

            $this->pVehicleRegisterNo = '';

            $this->pAssignToEmployee = '';

           // $this->pAdvanceAmount = '';

            $this->pCreated = '';

            $this->pLastModified  = '';
            
            $this->pStatus = '';

            $this->pStatusId = '';
            $this->pAckURL = '';
        } else {
            $this->pId = $data->id;
            
            $this->pDate = $data->job_date;
            
            $this->pOrderNo = $data->order_no;

            $this->pCustomer = $data->user_type == "1" ? ( (isset($data->business->business_name)) ? $data->business->business_name : 'Not Available') : ((isset($data->person->full_name)) ? $data->person->full_name : "Not Available");

            $this->pVehicleId = (isset($data->jobCardDetail->vehicleDetail->id)) ? $data->jobCardDetail->vehicleDetail->id : 'Not Available';

            $this->pVehicleRegisterNo = isset($data->jobCardDetail->vehicleDetail->registration_no) ? $data->jobCardDetail->vehicleDetail->registration_no : 'Not Available';

            $this->pAssignToEmployee = isset($data->jobCardDetail->assignedToEmployee->full_name) ? $data->jobCardDetail->assignedToEmployee->full_name : 'Not Available';

         //   $this->pAssignToEmployee = $data->assigned_to;

            $this->pAdvanceAmount = isset($data->jobCardDetail->advance_amount)?number_format($data->jobCardDetail->advance_amount,2):0;

            $this->pCreated = isset($data->jobCardDetail->created_at)?$data->jobCardDetail->created_at->format(config('app.date_format_vo')): '01-01-1970';

            $this->pLastModified  = isset($data->jobCardDetail->updated_at)?$data->jobCardDetail->updated_at->format(config('app.date_format_vo')): '01-01-1970';

          //  $this->pStatus = '';

            $this->pStatusId = isset($data->jobCardDetail->jobCardStatus->id)? $data->jobCardDetail->jobCardStatus->id : 1;

            $this->pStatus = isset($data->jobCardDetail->jobCardStatus->name) ? $data->jobCardDetail->jobCardStatus->name : 'New';

            $this->pAckURL = $ackURL?$ackURL:"";
            //Log::info('JobCardService->VO:- ref trans out');
            if($data->referencedIn && count($data->referencedIn)){
                $references = $data->referencedIn;
                //Log::info('JobCardService->VO:- ref trans IN' . json_encode($references));
                $references->each(function ($refTransaction) {
                    $refTransaction = (object) $refTransaction;
                    $transaction_Type   =    (object) $refTransaction->accountVoucher;
                    if($transaction_Type->name == 'job_request'){
                        $this->pHasEstimate = 'True';
                        $this->pEstimateId  =   $refTransaction->id;
                        $this->pEstimateType  =   $transaction_Type->name;
                    }elseif ($transaction_Type->name == 'job_invoice' || $transaction_Type->name == 'job_invoice_cash'){
                        $this->pHasInvoice = 'True';
                        $this->pInvoiceId  =   $refTransaction->id;
                        $this->pInvoiceType  =   $transaction_Type->name;
                        //approval status
                        if ($refTransaction->approval_status == 1) {
                            $this->pIsInvoiceApproved = 'True';
                        }
                    }
                
                });
            }
        }
    }
}
