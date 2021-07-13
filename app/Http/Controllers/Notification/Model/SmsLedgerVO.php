<?php

namespace App\Http\Controllers\Notification\Model;
use App\Core\ValueObject;
class SmsLedgerVO extends ValueObject
{    

        public $pDate;
        public $pDescribtion;
        public $pCredit;
        public $pDebit;
        public $pBalance;
        
        
        
        public function __construct($model = false)
        {
           
            parent::__construct($model);
               
            if ($model == false) {
                $this->pName = '';
                
            } else {
                
                $this->pDate = $model->payment_date;
                $this->pDescribtion = ($model['smsNotification'])?"SMS To:".$model['smsNotification']->to_number:"Reference: ".$model->payment_reference_id." Paid:".$model->payment;
                $this->pCredit=($model->credit)?$model->credit:"";
                $this->pDebit=($model->debit)?$model->debit:"";
                $this->pBalance=$model->balance;
                }
          
        }
}

