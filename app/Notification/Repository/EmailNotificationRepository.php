<?php
namespace App\Notification\Repository;

use App\Notification\Model\EmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailNotificationRepository implements EmailNotificationRepositoryInterface
{
    public function findAll(){
        return EmailNotification::where([
            ['status' , 0],
            ['retry_count', '<=', 3]
        ])
        ->get();
    }
    
    public function findById($id)
    {
        return EmailNotification::where('id',$id)->first();
    }
    
    
    public function findByEmailId($toId)
    {
        return EmailNotification::where([
            'to_id' => $toId,
            'status' => 1
        ])->first();
    }
    
    public function save(EmailNotification $model)
    {
        Log::info('EmailNotificationRepository->save:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
                Log::info('EmailNotificationRepository->save:-Return Try');
                return $result;
        } catch (\Exception $e) {
            Log::info('EmailNotificationRepository->save:-Return Catch');
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }
}