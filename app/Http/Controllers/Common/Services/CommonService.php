<?php

namespace App\Http\Controllers\Common\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Common\Repository\CommonRepository;
use App\Notification\Service\SmsNotificationService;
class CommonService
{

        /**
         * * To connect Repo **
         */
        public function __construct(SmsNotificationService $smsNotifyService,CommonRepository $commonRepo)
        {
            $this->commonRepo = $commonRepo;
            $this->smsNotifyService = $smsNotifyService;
        }



   public function getPersonByParameter($mobileNo=false,$email = false,$person_id =false)
   {
         $datas = $this->commonRepo->getPersonByParameters($mobileNo);
         if($datas)
         {
            $response = ['message' => pStatusSuccess(),'data' =>  $datas];
         }
         else
         {
           $response = ['message' => pStatusFailed(), 'data' =>  ""];
         }
          return $response;
   }

   public function sendOtp($userId)
   {
        $newuser = User::findOrFail($userId);

        $newuser->otp_time = Carbon::now()->format('Y-m-d H:i:s');
        $newuser->otp = pGenarateOTP(4);
        $newuser->otp_sent += 1;
        $userModel = $this->commonRepo->saveUser($newuser);
       if($userModel['message']==pStatusSuccess())
       {
              $name = $newuser->name;
              $mobile = $newuser->mobile;
              $subject = "OTP SEND";
              $sms_content = "$newuser->otp " . config('constants.messages.sms_activation');
              $smsNotifyResponse = $this->smsNotifyService->save($mobile, $subject, $name, $sms_content, " ", "OTP");

            if ($smsNotifyResponse['message'] == pStatusSuccess())
            {
               return ['message' => $smsNotifyResponse['message'],'data' => "OTP Sended" ];
            }
            else
            {
                return $smsNotifyResponse;
            }
       }else
       {
            return $userModel;
       }
   }
   public function OTPVerification($datas)
   {
         $data =(object)$datas;
         $userModel = $this->commonRepo->getUserDataByUserId($data->userId);
         if($userModel)
         {
              if($userModel->otp == $data->otp )
              {
                 return ['message' => pStatusSuccess(),'data' => "OTP matched" ];
              }else
              {
                  return ['message' => pStatusFailed(),'data' => "OTP MissMatched" ];
              }
         }else
         {
           return ['message' => pStatusFailed(),'data' => "This User Not Available" ];
         }
   }


}