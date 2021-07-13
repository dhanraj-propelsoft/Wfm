<?php
namespace App\Notification\Repository;

use App\Notification\Model\EmailNotification;

interface EmailNotificationRepositoryInterface
{
    public function findAll();
    
    public function save(EmailNotification $model);

    public function findByEmailId($emailId);
}