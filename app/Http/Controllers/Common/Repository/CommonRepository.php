<?php

namespace App\Http\Controllers\Common\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Session;
use DB;
use App\Person;
use App\Http\Controllers\Common\Model\PersonMobile;
use App\Http\Controllers\Common\Model\PersonEmail;
use App\AdminModel\Salutation;
use App\Gender;
use App\User;
use App\BloodGroup;


class CommonRepository
{
     public function getPersonByParameters($mobileNo=false,$email = false,$person_id =false)
     {
          $datas = Person::with('personMobile','user','personEmail');
          $datas->whereHas('personMobile', function ($query) use ($mobileNo)
          {
             $query->where(['mobile_no' => $mobileNo]);
          });

//           if($mobileNo&&$email)
//           {
//              return $datas->get();
//           }
//           else
//           {
               $datass = $datas->first();
//           }

          return $datass;
     }

     
     public function getpersonIdByMobileNo($mobile_no)
     {

        $query = PersonMobile::where('mobile_no',$mobile_no)->first();


        if($query != null){

            return $query;

        }else{
            return false;
        }

        
     }
     public function findAllSalutations()
     {

       $query =  Salutation::get();

       return $query;

        
     }
     public function findAllGender()
     {

       $query =  Gender::get();

       return $query;

        
     }

     public function findAllBloodGroups()
     {

       $query =  BloodGroup::get();

       return $query;

        
     }

     public function get_account_list($mobile_no)
     {

        $query = PersonMobile::where('mobile_no',$mobile_no)->get();

        return $query;


        
        
     }

     public function checkmailidByPersonId_and_email($personId,$email)
     {

        
        $query = PersonEmail::where('person_id',$personId)
                            ->where('email',$email)
                            ->first();
       

        if($query != null){

            return $query;

        }else{
            return false;
        }



        
     }


     public function findDataByPersonId($personId)
     {
        $query = Person::with('personMobile','user','personEmail')
                ->where('id',$personId)
                ->first();



        return $query;
     }
     public function getUserDataByUserId($userId)
     {
        $model = User::findOrFail($userId);
        return $model;
     }
     public function saveUser($model)
     {
            try {

                $result = DB::transaction(function () use ($model) {

                    $model->save();
                    return [
                        'message' => pStatusSuccess(),
                        'data' => $model
                    ];
                });

                return $result;
            } catch (\Exception $e) {

                return [
                    'message' => pStatusFailed(),
                    'data' => $e
                ];
            }

          }
          public function savePerson($model)
               {
                      try {

                          $result = DB::transaction(function () use ($model) {

                              $model->save();
                              return [
                                  'message' => pStatusSuccess(),
                                  'data' => $model
                              ];
                          });

                          return $result;
                      } catch (\Exception $e) {

                          return [
                              'message' => pStatusFailed(),
                              'data' => $e
                          ];
                      }

                    }

        public function signup($personModel,$personMobilemodel,$personEmailModel,$usermodel){
                        
            try {

            $result = DB::transaction(function () use ($personModel,$personMobilemodel,$personEmailModel,$usermodel) {


                $personModel->save();
                
                $personModel->personMobile()->save($personMobilemodel);

                $personModel->personEmail()->save($personEmailModel);
                if($usermodel){
                $personModel->user()->save($usermodel);
                }

            // Log::info('TaskRepository->TaskSave:Success-'.json_encode($model));   
                return [
                    'status'=>1,
                    'message'=>pStatusSuccess(),
                    'data' => $personModel
                ];
            });
           
            return $result;
        } catch (\Exception $e) { 

            // Log::info('TaskRepository->TaskSave:Error-'.json_encode($e)); 

            return [
                'status'=>0,
                'message' => $e,
                'data' => ""
            ];
        }    


        }



}