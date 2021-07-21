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

//dd("done");
		if(Auth::attempt(['mobile' => request('mobile'), 'password' => request('password'), 'status' => 1])) {


			$user = Auth::user();

			$success['status'] = '1';
			$success['user'] =  $user;
			$success['person_id'] =  $user->person_id;
			$success['image'] =  "";
			$success['token'] =  $user->createToken($user->name)->accessToken;
			// $success['token'] =  Str::random(80);

			$orgId = 0;
			return response()->json($success, $this->successStatus);
		} else {
			return response()->json(['status'=>'Wrong Credentials!'], $this->unauthorised);
		}
    }
}