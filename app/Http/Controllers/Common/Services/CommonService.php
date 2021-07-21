<?php

namespace App\Http\Controllers\Common\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;

use App\Http\Controllers\Common\Repository\CommonRepository;
class CommonService
{

        /**
         * * To connect Repo **
         */
        public function __construct(CommonRepository $commonRepo)
        {
            $this->commonRepo = $commonRepo;
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
}