<?php

namespace App\Http\Controllers\Common\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Common\Repository\CommonRepository;
use App\Notification\Service\SmsNotificationService;
use Hash;
use App\Http\Controllers\Common\Model\PersonVO;
use App\Person;
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
               $email = "smartdhana20@gmail.com";
               $datas = $this->commonRepo->getPersonByParameters($mobileNo,$email);

               $entities = $this->convertToPersonVO($datas);

               $response = ['message' => pStatusSuccess(),'data' =>  $entities];

               return $response;
       }
        public function createPersonTmpFile($datas)
        {
            $otp = pGenarateOTP(4);
            $datas['otp'] = $otp;
            $data = (object)$datas;

            $fileName = $data->mobile_no.".json";
            $encodedData = json_encode($datas);

            //find file name
            if(Storage::disk('local')->exists($fileName))
            {
                // Remove temp file
                Storage::disk('local')->delete($fileName);
            }
             // create temp file
             if(Storage::disk('local')->put($fileName, $encodedData))
             {
                $response = ['message' => pStatusSuccess(),'data' =>  "Validate OTP"];
             }
             else
             {
                 $response = ['message' => pStatusFailed(),'data' => "Went Wrong With Verify OTP"];
             }

            return $response;

        }
        public function getTmpPersonFile($datas)
        {
             $data = (object)$datas;
             $fileName = $data->mobile_no.".json";
             $getFile = Storage::disk('local')->get($fileName);
             $decodedData = json_decode ($getFile, true);

             if($datas['otp'] == $decodedData['otp'])
             {
                $person = $this->savePerson($decodedData);
                if($person['message'] == pStatusSuccess())
                {
                    $user = $this->saveUser($person['data']);
                }
                dd($response);
                $response = ['message' => pStatusSuccess(),'data' =>  "OTP MATCHED"];
             }else
             {
                $response = ['message' => pStatusFailed(),'data' =>  "OTP Missmatched"];
             }
            return $response;
        }
        public function savePerson($datas)
        {
            $datas = (object)$datas;
            $personModel = $this->convertToPersonModel($datas);
            $person =$this->commonRepo->savePerson($responseData);
            return $person;
        }
        public function saveUser($datas)
        {
                $datas = (object)$datas;
                $userModel = $this->convertToUserModel($datas);
                $user =$this->commonRepo->saveUser($userModel);
                return $user;
        }

        public function convertToPersonModel($datas)
        {
           if($datas->pId)
           {
             $model = Person::findOrFail($datas->pId);
           }
           else
           {
            $model =new Person;
           }

           $model->salutation  = $datas->salutation;
           $model->first_name = $datas->first_name;
           $model->middle_name = $datas->middle_name;
           $model->last_name = $datas->last_name;
           $model->alias = $datas->alias;
           $model->dob = $datas->dob;
           $model->father_name = $datas->father_name;
           $model->mother_name = $datas->mother_name;
           $model->pan_no = $datas->pan_no;
           $model->gender_id = $datas->gender_id;
           $model->blood_group_id = $datas->blood_group_id;
           $model->country_id = $datas->country_id;
           $model->status_id = $datas->status_id;

           return $model;
        }
        public function convertToUserModel($datas)
        {
              $model =new User;
              $model->name = $datas->name;
              $model->mobile = $datas->mobile;
              $model->email = $datas->email;
              $model->password =$datas->person_id;
              $model->person_id =$datas->person_id;
              $model->otp =$datas->otp;
              $model->otp_time = $datas->otp_time;
              $model->otp_sent = $datas->otp_sent;
              $model->is_active = $datas->is_active;
              $model->status = $datas->status;
              $model->remember_token = $datas->remember_token;
              return $model;
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
       public function updatePassword($datas)
      {

            $data =(object)$datas;
            $validator = $this->passwordValidation($datas);


            if ($validator->fails()) {

                return [
                    'message' => $validator->messages()->first(),
                    'data' => ''
                ];
            }

            $userModel = $this->commonRepo->getUserDataByUserId($data->userId);
            $userModel->password = Hash::make($data->new_password);
            $userResponse = $this->commonRepo->saveUser($userModel);
           if ($userResponse['message'] == pStatusSuccess())
           {
               return ['message' => $userResponse['message'],'data' => "Password Updated Successfully" ];
           }
           else
            {
               return $userResponse;
            }
      }
      public function passwordValidation($data)
      {
         $rule=['new_password' => ['required'],'new_confirm_password' => ['same:new_password']];

         $validator = Validator::make($data, $rule);

         return $validator;
      }
      public function convertToPersonVO($model = false)
          {

              $vo = new PersonVO($model);
              return $vo;
          }


}