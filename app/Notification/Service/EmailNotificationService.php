<?php
namespace App\Notification\Service;

use App\Notification\Model\EmailNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Notification\Repository\EmailNotificationRepository;
use function GuzzleHttp\json_encode;

class EmailNotificationService
{

    public function __construct(EmailNotificationRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function createModel($toId, $subject, $contentAddressedTo, $content, $template,$organizationId)
    {
        $model = new EmailNotification();
        $model->from_id = config('mail.from.address'); // "abcd@gmail.com";
        $model->to_id = $toId;
        $model->subject = $subject;
        $model->content_addressed_to = $contentAddressedTo;
        $model->content = $content;
        $model->template = $template;
        $model->organization_id = ($organizationId)?$organizationId:null;
        $model->status = 0;
        return $model;
    }

    public function changeSentStatus($model)
    {
        Log::info('EmailNotificationService->changeSentStatus:- Inside');
        $model->status = 1;
        Log::info('EmailNotificationService->changeSentStatus:- Return');
        return $model;
    }

    public function save($toId, $subject, $contentAddressedTo, $content ,$template,$organizationId =false)
    {
        Log::info('EmailNotificationService->save:- Inside');
        $model = $this->createModel($toId, $subject, $contentAddressedTo, $content,$template,$organizationId);
        $response = $this->repo->save($model);
        Log::info('EmailNotificationService->save:- return');
        return $response;
    }

    public function sendOutEmailNotification()
    {
        Log::info('EmailNotificationService->sendOutNotification:- Inside');

        $models = $this->repo->findAll();

        collect($models)->map(function ($model) {
            Log::info('EmailNotificationService->sendOutNotification:- $model ' . json_encode($model));

            try {
                
                $content = [
                    'name' => $model->content_addressed_to,
                    'emailContent' => $model->content,
                    'url'=>config('app.web_client_url')
                ];

                Mail::send($model->template, $content, function ($message) use ($model) {
                    $message->from(config('mail.from.address'),config('mail.from.name'));
                    $message->to($model->to_id);
                    $message->subject($model->subject);
                });

                $failCount = count(Mail::failures());
                if ($failCount == 0) {
                    $model = $this->changeSentStatus($model);
                    $data = $this->repo->save($model);
                } else {
                    Log::info('EmailNotificationService->sendOutNotification:- failed ' . $model->id . ' Fail Count ' . json_encode($failCount));
                    $model->error = "Failed to send out email to id - ".json_encode($model->to_id);
                    $model->retry_count = $model->retry_count + 1;
                    $data = $this->repo->save($model);
                }
            } catch (Exception $e) {
                Log::info('EmailNotificationService->sendOutNotification:- failed catch ' . $model->id . ' - ' . json_encode($e));
                $model->error = "Failed to send out email to id - ".json_encode($model->to_id). " Reason:- ".json_encode($e);
                $model->retry_count = $model->retry_count + 1;
                $data = $this->repo->save($model);
            }
        });

        Log::info('EmailNotificationService->sendOutNotification:- END');
    }

}