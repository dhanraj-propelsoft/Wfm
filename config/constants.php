<?php
    

return [
    'sms' => [
        'user' => 'propelsoft',
        'pass' => '123456',
        'sender' => 'PROPEL'
    ],
    'error' => [
        'expire' => 'Currently your Subscription is expired.',
        'limit_exceed' => 'As per your Subscription, total number of Transactions had exceeded the allowance.',
        'ledger_limit' => 'As per your Subscription, total number of Ledgers had exceeded the allowance.',
        'sms_no' => 'As per your Subscription, total number of SMS had exceeded the allowance.',
        'promotion_sms_no' => 'As per your Subscription, total number of Promotion SMS had exceeded the allowance.',
        'revenue_limit' => 'As per your Subscription, total value of Revenue had exceeded the allowance.',
        'storage_limit' => 'As per your Subscription, total number of Attachments Storage had exceeded the allowance.'
    ],
    'flash' => [
        'added' => ' Successfully Added!',
        'added_approved' => ' Successfully Approved! & SMS Sent',
        'updated' => ' Successfully Updated!',
        'deleted' => ' Successfully Removed!',
        'exist' => ' Already Exists!'
    ],
    'messages' => [
        'activation' => 'Activation link has been sent to your registered Email ID. Kindly open the link and enter OTP which has been sent to your mobile to activate it!',
        'activation_error' => 'Oops some error occured. Please try again later!',
        'sms_activation' => 'is your OTP to verify your account on PROPEL ERP'
    ],


    /*START Breadcrumb*/
    'wfm'=>[
        'taskfilter'=>"[View=Task:",
        'dashboard'=>"[View=",
        'get_task_orguser'=>"[View=User",
        'advancefilter'=>"[View=Search:Custom",
        'get_savedsearch'=>"[View=Search:",
        'searchtask'=>"[View=Search",
    ],
    /*END Breadcrumb*/

];