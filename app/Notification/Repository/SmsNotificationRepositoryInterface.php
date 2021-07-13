<?php
namespace App\Notification\Repository;

use App\Notification\Model\SmsNotification;

interface SmsNotificationRepositoryInterface
{
    public function findAll(); 
    
    public function save(SmsNotification $model);

    public function findSmsNotificationByMobileNo($mobileNo);
}