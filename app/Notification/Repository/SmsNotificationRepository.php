<?php
namespace App\Notification\Repository;

use App\Notification\Model\SmsNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsNotificationRepository implements SmsNotificationRepositoryInterface
{

    public function findAll()
    {
        return SmsNotification::where([['status', 0 ],['retry_count','<=', 3]])->get();
    }

    public function save(SmsNotification $model)
    {
        // ToDo Donated By Data
        Log::info('SmsNotificationRepository->saveSmsNotification:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
            Log::info('SmsNotificationRepository->saveSmsNotification:-Return Try');
            return $result;
        } catch (\Exception $e) {
            Log::info('SmsNotificationRepository->saveSmsNotification:-Return Catch');
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function findSmsNotificationByMobileNo($mobileNo)
    {
        return SmsNotification::where([
            'to_number' => $mobileNo,
            'status' => 1
        ])->first();
    }
}
