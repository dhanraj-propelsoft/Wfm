<?php

namespace App\Http\Controllers\Common\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Common\Services\CommonService;

class CommonController extends Controller
{
        /**
         * * To connect service **
         */
        public function __construct(CommonService $service)
        {
            $this->service = $service;
        }


    public function getPersonByMobileNo($mobileNo)
    {

        $datas = $this->service->getPersonByParameter($mobileNo);

        return $datas;
    }
    public function sendOtp()
    {
        $datas = $this->service->sendOtp(request('userId'));

        return $datas;
    }
    public function OTPVerification(Request $request)
    {
        $datas = $this->service->OTPVerification($request->all());
        return $datas;
    }
    public function updatePassword(Request $request)
    {
       $datas = $this->service->updatePassword($request->all());
       return $datas;

    }

}