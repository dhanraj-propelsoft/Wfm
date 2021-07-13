<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = 'companies';
	protected $redirectAfterLogout = 'login';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function showRegistrationForm()
	{
		return redirect('login');
	}

	public function username()
	{
		return 'email';
	}

	protected function validateLogin(Request $request)
	{
		$username = "";

		if(isset($request->mobile)) {
		Log::info('LoginController->validateLogin :- Login request received for mobile number : '.$request->mobile);
			$username = 'mobile';
		} else if(isset($request->email)) {
		Log::info('LoginController->validateLogin :- Login request received for email : '.$request->email);
			$username = 'email';
		}

		$this->validate($request, [
			$username => 'required', 'password' => 'required',
		]);
	}

	protected function sendFailedLoginResponse(Request $request)
	{
		$username = "";

		if(isset($request->mobile)) {
	    Log::info('LoginController->sendFailedLoginResponse :- Login request received for email : '.$request->email);
			$username = 'mobile';
		} else if(isset($request->email)) {
		Log::info('LoginController->sendFailedLoginResponse :- Login request received for email : '.$request->email);
			$username = 'email';
		}

			return redirect()->back()
			->withInput($request->only($username, 'remember'))
			->withErrors([
				$username => Lang::get('auth.failed'),
			]);
	}

	protected function credentials(Request $request)
	{
		$username = "";

		if(isset($request->mobile)) {
			$username = 'mobile';
		} else if(isset($request->email)) {
			$username = 'email';
		}
		
		return array_merge($request->only($username, 'password'), ['status' => 1]);
	}

	public function logout(Request $request)
	{
		Log::info('LoginController->logout :- logout request received');
		$this->guard()->logout();

		$request->session()->flush();

		$request->session()->regenerate();

		return redirect('login');
	}
}
