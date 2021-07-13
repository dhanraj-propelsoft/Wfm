<?php
namespace App\Notification\Service;

use App\Notification\Model\SmsNotification;
use App\Notification\Repository\SmsNotificationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;
use App\SmsLedger;
use Carbon\Carbon;

class SmsNotificationService
{

    public function __construct(SmsNotificationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createModel($mobileNo, $subject, $contentAddressedTo, $content,$organizationId = null,$category)
    {
        Log::info('SmsNotificationService->createModel:- Inside');
        $model = new SmsNotification();
        $model->category = $category;
        $model->from_number = "11110000";
        $model->to_number = $mobileNo;
        $model->subject = $subject;
        $model->content_addressed_to = $contentAddressedTo;
        $model->content = $content;
        $model->organization_id = ($organizationId)?$organizationId:null;
        $model->status = 0; // is numberic not a string
       
        Log::info('SmsNotificationService->createModel:- return' . json_encode($model));
        return $model;
    }
    public function changeSentStatus($model)
    {
        Log::info('SmsNotificationService->changeSentStatus:- Inside');
        $model->status = 1;
        Log::info('SmsNotificationService->changeSentStatus:- Return');
        return $model;
    }

    public function save($mobileNo, $subject, $contentAddressedTo, $content,$organizationId = false,$category)
    {
       
        Log::info('SmsNotificationService->save:- Inside');

        $model = $this->createModel($mobileNo, $subject, $contentAddressedTo, $content,$organizationId,$category);
        $response = $this->repo->save($model);
        Log::info('SmsNotificationService->save:- return'.json_encode($response));
        return $response;
    }
   
    public function sendOutSmsNotification()
    {
        Log::info('SmsNotificationService->sendOutSmsNotification:- Inside');

        $models = $this->repo->findAll();

        collect($models)->map(function ($model) {
            Log::info('SmsNotificationService->sendOutSmsNotification:- $model ' . json_encode($model));

            try {

                $toMobileNo = $model->to_number;
                $message = $model->content;
                Log::info('SmsNotificationService->sendOutSmsNotification:- message ' . json_encode($message));
                $text = rawurlencode($message);

                $user = config('app.sms_gateway_username');
                $pass = config('app.sms_gateway_password');
                $sender = config('app.sms_gateway_sender');
                
                if($model->category == "TRANSACTION")
                {
                    $url = 'http://trans.smsfresh.co/api/sendmsg.php?user=' . $user . '&pass=' . $pass . '&sender=' . $sender . '&phone=' . $toMobileNo . '&text=' . $text . '&priority=ndnd&stype=normal';
                    Log::info('SmsNotificationService->sendOutSmsNotification:- Success ' . $url);
                }
                else
                {
                    $url = 'http://trans.smsfresh.co/api/sendmsg.php?user=' . $user . '&pass=' . $pass . '&sender=' . $sender . '&phone=' . $toMobileNo . '&text=' . $text . '&priority=ndnd&stype=normal';
                    Log::info('SmsNotificationService->sendOutSmsNotification:- Success ' . $url);
                    
                }

                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Connection: Keep-Alive'
                ]);

                $message_id = curl_exec($ch);
                Log::info('SmsNotificationService->sendOutSmsNotification:- MessageId ' . $message_id);

                if ($message_id) {
                    $model = $this->changeSentStatus($model);
                    $model->message_id = $message_id;
                    $data = $this->repo->save($model);
                    $date = Carbon::now();
                    $smsLedger =array('payment_date' =>$date->format("Y-M-D") ,"sms_notification_id"=>$model->id,'sms_ledger_type'=>"debit" );
                    $smsLedgerSave = $this->saveSmsLedger($smsLedger);
                    Log::info('SmsNotificationService->sendOutSmsNotification:- Success ' . $model->id);
                } else {
                    Log::info('SmsNotificationService->sendOutSmsNotification:- failed ' . $model->id);
                    $model->error = "Failed to send out Sms to id - " . json_encode($model->to_number);
                    $model->retry_count = $model->retry_count + 1;
                    $data = $this->repo->save($model);
                }
            } catch (Exception $e) {
                Log::info('SmsNotificationService->sendOutSmsNotification:- failed catch ' . $model->id . ' - ' . json_encode($e));
                $model->error = "Failed to send out Sms to id - " . json_encode($model->to_id) . " Reason:- " . json_encode($e);
                $model->retry_count = $model->retry_count + 1;
                $data = $this->repo->save($model);
            }
        });

        Log::info('EmailNotificationService->sendOutSmsNotification:- END');
    }
}