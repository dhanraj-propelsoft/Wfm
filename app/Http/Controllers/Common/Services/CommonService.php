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
use App\Notification\Service\EmailNotificationService;
use Hash;
use App\Http\Controllers\Common\Model\PersonVO;
use App\Http\Controllers\Common\Model\PersonMobile;
use App\Person;
use App\Http\Controllers\Common\Model\PersonEmail;
class CommonService
{

        /**
         * * To connect Repo **
         */
        public function __construct(SmsNotificationService $smsNotifyService,CommonRepository $commonRepo,EmailNotificationService $emailNotifiyService)
        {
            $this->commonRepo = $commonRepo;
            $this->smsNotifyService = $smsNotifyService;
            $this->emailNotifiyService = $emailNotifiyService;
        }



       public function getPersonByParameter($mobileNo=false,$email = false,$person_id =false)
       {
               // $email = "smartdhana20@gmail.com";
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
            $mobileNo = $data->mobile_no;
            $name = $data->first_name;
            $fileName = $mobileNo.".json";

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
                    $subject = "User verification";
                    $smsContent = pSmsParser('UserVerificationOTP', ['{otp}' => $otp]);

                    $smsNotifyModel = $this->smsNotifyService->save($mobileNo, $subject, $name, $smsContent,"", "OTP");
                    if($smsNotifyModel['message'] == pStatusSuccess())
                    {
                        $response = ['message' => pStatusSuccess(),'data' =>  "Validate OTP"];

                    }else
                    {
                        $response = ['message' => pStatusFailed(),'data' =>  "Something Went wrong Please recreate"];
                    }

             }
             else
             {
                 $response = ['message' => pStatusFailed(),'data' => "Went Wrong With Verify OTP"];
             }

            return $response;

        }
        public function getTmpPersonFile($datas)
        {
            Log::info('CommonService->signup:-Inside '.json_encode($datas));

             $data = (object)$datas;

             $fileName = $data->mobile_no.".json";
             $getFile = Storage::disk('local')->get($fileName);
             $decodedData = json_decode ($getFile, true);

             if($datas['otp'] == $decodedData['otp'])
             {
                $response = ['message' => pStatusSuccess(),'data' =>  "OTP MATCHED"];  
                
             }else
             {
                 
                $response = ['message' => pStatusFailed(),'data' =>  "OTP Missmatched"];
             }
            Log::info('CommonService->signup:-Return '.json_encode($response));

            return $response;
        }
        public function savePerson($datas)
        {
            $datas = (object)$datas;
            $personModel = $this->convertToPersonModel($datas);
            $person =$this->commonRepo->savePerson($responseData);
            return $person;
        }

        public function get_account_list($mobileNo)
        {
           
           $PersonAccountList = $this->commonRepo->get_account_list($mobileNo);

           
            $PersonVos = collect($PersonAccountList)->map(function ($personMobile){

              $personVo = $this->finddataByPersonId($personMobile->person_id);

              

                return $personVo['data'];
             
            });
            

            return[
                'status'=>1,
                'data'=>$PersonVos
            ];

        }

        public function signup($datas,$userModel)
        {
                
            Log::info('CommonService->signup:-Inside '.json_encode($datas));


            // OTP Verfied
            $otpValidate = $this->getTmpPersonFile($datas);

            if($otpValidate['message'] == pStatusSuccess()){

                if($datas['pId'] == "false"){
                    $datas['pId'] = false;
                }

            $datas = (object)$datas;
            

           

            $personModel = $this->convertToPersonModel($datas);
            


            Log::info('CommonService->signup:-return personmodel'.json_encode($personModel));

            $personMobileModel = $this->convertToPersonMobileModel($datas);

                
            Log::info('CommonService->signup:-return personMobileModel'.json_encode($personMobileModel));

            $personEmailModel = $this->convertToPersonEmailModel($datas);

            Log::info('CommonService->signup:-return personEmailModel'.json_encode($personEmailModel));

            if($userModel){

               $userModel = $this->convertToUserModel($datas);  
            }
            
            
            
           
            

            Log::info('CommonService->signup:-return UserModel'.json_encode($userModel));



            $result =$this->commonRepo->signup($personModel,$personMobileModel,$personEmailModel,$userModel);

            if($userModel != true){
                return [
                    'message'=>pStatusSuccess(),
                    'data'=>$personModel
                ];
            }

            Log::info('CommonService->signup:-return Signup'.json_encode($result));

        
            if($result['status'] == 1){


                $res = app('App\Http\Controllers\Entitlement\Controller\LoginController')->signin($datas->mobile_no,$datas->password);

                Log::info('CommonService->signup:-return Signin'.json_encode($res));

                    if($res['status'] == 1){

                    Log::info('CommonService->signup:-return Success'.json_encode($res));

                         return $res['data'];

                    }else{

                    Log::info('CommonService->signup:-return failed');

                        return response()->json(['status'=>'Contact Admin!'], $this->unauthorised);

                    }        
            }   

            }else{

                return $otpValidate;
            }

           
        }
        public function sendotp_email($request)
       {
            
            
            $otp = pGenarateOTP(4);
            $request['otp'] = $otp;

            $data = (object)$request;

            $email_content = $otp ." is the OTP for logging in to your Propel Account.keep the OTP safe.we will never call to ask for your OTP.";
            
            $emailNotifyResponse = $this->emailNotifiyService->save($data->email_id, "OTP", $data->name, $email_content, " ", "");

            if($emailNotifyResponse['message'] == "SUCCESS"){
            
                $fileName = $data->email_id.".json";
                $encodedData = json_encode($data);
               
                // create temp file
             if(Storage::disk('local')->put($fileName, $encodedData))
             {
                return [
                    'status'=>1,
                    'message'=>"OTP has been send Successfully."
                ];
             }else{
                return [
                    'status'=>0,
                    'message'=>"Json file did not saved.Contact Admin."
                ];
             }
            }else{
                return [
                    'status'=>0,
                    'message'=>"OTP did not send,Contact Admin."
                ];
            }
            



       }
         public function verifiy_email_otp($datas)
        {
             $data = (object)$datas;
             $fileName = $data->email_id.".json";
             $getFile = Storage::disk('local')->get($fileName);
             $decodedData = json_decode ($getFile, true);

             if($datas['otp'] == $decodedData['otp'])
             {
                 
                // $person = $this->savePerson($decodedData);
                
                // if($person['message'] == pStatusSuccess())
                // {
                //     // $user = $this->saveUser($person['data']);
                // }
                
                $response = ['message' => pStatusSuccess(),'data' =>  "OTP MATCHED"];
             }else
             {
                 
                $response = ['message' => pStatusFailed(),'data' =>  "OTP Missmatched"];
             }
            return $response;
        }

        public function updatePassword_and_login($data){

            
            $data =(object)$data;
           
            $userModel = $this->commonRepo->getUserDataByUserId($data->userId);

            $userModel->password = Hash::make($data->password);

            $userResponse = $this->commonRepo->saveUser($userModel);
           
           
           if ($userResponse['message'] == pStatusSuccess())
           {


            $res = app('App\Http\Controllers\Entitlement\Controller\LoginController')->signin($userModel->mobile,$data->password);
            
            
            Log::info('CommonService->signup:-return Signin'.json_encode($res));

            if($res['status'] == 1){

            Log::info('CommonService->signup:-return Success'.json_encode($res));

                 return $res['data'];
           }
           else
            {
               return [
                'status'=>0,
                'message'=>'Wrong Credentials,contact admin'
               ];
            }
        }
        else{

            return [
                'status'=>0,
                'message'=>'password updated failed,contact admin'
               ];
        }

        
    }
        public function persondetails($data){

            $data =(object)$data;

            $personIdByMobileNo = $this->commonRepo->getpersonIdByMobileNo($data->mobileNo);

            

            if($personIdByMobileNo){

                
                $conform_personId = $this->commonRepo->checkmailidByPersonId_and_email($personIdByMobileNo->id,$data->email);

                if($conform_personId){

                    $res = $this->commonRepo->findDataByPersonId($conform_personId->person_id);
 

                    return [
                        'status'=>1,
                        'data'=>$res
                    ];
                }else{

                    return [
                        'status'=>0,
                        'message'=>'MobileNo and Email does not matched any persons'
                    ];

                }

            }else{

                    return [
                        'status'=>0,
                        'message'=>'Mobile does not matched any persons'
                    ];

            }
            

            // return $res;

        }
        
        public function finddataByPersonId($id)
        {
               
               $res = $this->commonRepo->findDataByPersonId($id);

               $salutation = $this->commonRepo->findAllSalutations();

               $gender = $this->commonRepo->findAllGender();

               $blood_groups = $this->commonRepo->findAllBloodGroups();


               $personVo = $this->convertToPersonVO($res,$salutation,$gender,$blood_groups);

                

                return [
                        'status'=>1,
                        'data'=>$personVo
                    ];
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
         
            $model = new Person;
           }

           $model->salutation  = $datas->salutation;
           $model->first_name = $datas->first_name;
           $model->middle_name = $datas->middle_name;
           $model->last_name = $datas->last_name;
           $model->alias = $datas->alias;
           $model->dob = $datas->dob;
           // $model->father_name = $datas->father_name;
           // $model->mother_name = $datas->mother_name;
           // $model->pan_no = $datas->pan_no;
           $model->gender_id = $datas->gender_id;
           $model->blood_group_id = $datas->blood_group_id;
           // $model->country_id = $datas->country_id;
           // $model->status_id = $datas->status_id;

           return $model;
        }

        public function convertToPersonMobileModel($datas)
        {

           if($datas->pId)
           {
             $model = PersonMobile::where('person_id',$datas->pId)->first();
           }
           else
           {
            $model = new PersonMobile;
           }

            $model->mobile_no = $datas->mobile_no;

            return $model;


        }

        public function convertToPersonEmailModel($datas)
        {

           if($datas->pId)
           {
             $model = PersonEmail::where('person_id',$datas->pId)->first();
           }
           else
           {
            $model = new PersonEmail;
           }
            

            $model->email = $datas->email;

            return $model;


        }
        public function convertToUserModel($datas)
        {

            
                $model = new User;
               


          
              $model->name = $datas->first_name.' '.$datas->middle_name.' '.$datas->last_name;
              $model->mobile = $datas->mobile_no;
              $model->email = $datas->email;
              $model->password =Hash::make($datas->password);
              // $model->person_id =$datas->person_id;
              $model->otp =$datas->otp;
              // $model->otp_time = $datas->otp_time;
              // $model->otp_sent = $datas->otp_sent;
              // $model->is_active = $datas->is_active;
              $model->status = 1;
              // $model->remember_token = $datas->remember_token;
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
      public function convertToPersonVO($model = false,$salutation = false,$gender = false,$blood_groups = false)
          {

              $vo = new PersonVO($model);
              $vo->setSalutationsList($salutation);
              $vo->setGenderList($gender);
              $vo->setBloodGroupList($blood_groups);

              return $vo;
          }


}