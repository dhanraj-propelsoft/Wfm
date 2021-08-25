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
    public function verifiy_email_otp(Request $request)
    {
        $datas = $this->service->verifiy_email_otp($request->all());
        return $datas;
    }
    public function updatePassword(Request $request)
    {
       $datas = $this->service->updatePassword($request->all());
       return $datas;
    }
    public function createPersonTmpFile(Request $request)
    {
       $datas = $this->service->createPersonTmpFile($request->all());
       return $datas;
    }
    public function getTmpPersonFile(Request $request)
    {
        $datas = $this->service->getTmpPersonFile($request->all());
        return $datas;
    }

    public function signup(Request $request)
    {
        $userModel = false;
        if($request->password){
            $userModel = true;
        }

        Log::info('CommonController->Signup:-Inside '.json_encode($request->all()));
        $Data = $this->service->signup($request->all(),$userModel);
        Log::info('CommonController->Signup:-Return '.json_encode($Data));

        return response()->json($Data);
        
    }

     public function updatePassword_and_login(Request $request)
    {
        Log::info('CommonController->signin:-Inside ');
        $Data = $this->service->updatePassword_and_login($request->all());
        Log::info('CommonController->signin:-Return '.json_encode($Data));

        return response()->json($Data);
    }

     public function persondetails(Request $request)
    {

        Log::info('CommonController->persondetails:-Inside ');
        $Data = $this->service->persondetails($request->all());
        Log::info('CommonController->persondetails:-Return '.json_encode($Data));

        return response()->json($Data);
    }

    public function finddataByPersonId($personId)
    {   

        
        Log::info('CommonController->finddataByPersonId:-Inside ');
        $Data = $this->service->finddataByPersonId($personId);
        Log::info('CommonController->finddataByPersonId:-Return '.json_encode($Data));

        return response()->json($Data);
    }

    public function sendOtpPerson(Request $request)
    {

        Log::info('CommonController->sendOtpPerson:-Inside ');
        $datas = $this->service->createPersonTmpFile($request->all());
        Log::info('CommonController->sendOtpPerson:-Return');
        return response()->json($datas);
    }

    public function get_account_list($mobileNo)
    {
            
        Log::info('CommonController->get_account_list:-Inside ');
        $datas = $this->service->get_account_list($mobileNo);
        Log::info('CommonController->get_account_list:-Return');
        return response()->json($datas);
    }

    public function sendotp_email(Request $request)
    {
        Log::info('CommonController->sendotp_email:-Inside ');
        $datas = $this->service->sendotp_email($request->all());
        Log::info('CommonController->sendotp_email:-Return');
        return response()->json($datas);
    }




}