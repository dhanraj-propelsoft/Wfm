<?php

namespace App\Http\Controllers\Common\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Session;
use DB;
use App\Person;
use App\User;


class CommonRepository
{
     public function getPersonByParameters($mobileNo=false,$email = false,$person_id =false)
     {
          $datas = Person::with('personMobile','user');
          $datas->whereHas('personMobile', function ($query) use ($mobileNo)
          {
             $query->where(['mobile_no' => $mobileNo]);
          });
          $data = $datas->first();
          return $data;
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
}