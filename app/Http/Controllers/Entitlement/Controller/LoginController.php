<?php

namespace App\Http\Controllers\Entitlement\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;
use Auth;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
	public $successStatus = 200;
	public $unauthorised = 401;
	private $orgId;

	public function login()
	{

		$result = $this->signin(request('mobile'),request('password'));



		if($result['status'] == 1){

			return response()->json($result['data'], $this->successStatus);
		}else{
			return response()->json(['status'=>0,'message'=>"Wrong Credentials"], $this->unauthorised);
		}
		
	
    }

    public function signin($mobile_no,$password) {

    	

		if(Auth::attempt(['mobile' => $mobile_no, 'password' => $password, 'status' => 1])) {


			$user = Auth::user();

			$success['status'] = 1;
			$success['user'] =  $user;
			$success['person_id'] =  $user->person_id;
			$success['image'] =  "";
			$success['token'] =  $user->createToken($user->name)->accessToken;


			$success['firstOrg'] =  0;

			 $result = [
				'status'=>1,
				'data'=>$success
			];


			 return $result;
		} else {

			$result = [
				'status'=>0,
				'data'=>"Wrong Credentials"
			];

			return $result;
		}
	}
}