<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use Session;
use Hash;
use Auth;

class ChangePasswordController extends Controller
{
	public function change_password() {
		return view('auth.passwords.change');
	}

	public function store_password(Request $request) {
		$this->validate($request, [
			'old_password' => 'required',
			'password' => 'required|min:6|confirmed'
		]);

		$newuser = User::findOrfail(Auth::user()->id);
		if (Hash::check($request->input('old_password'), $newuser->password)) {
			$newuser->password = Hash::make($request->input('password'));
			$newuser->save();

			Session::flash('flash_message', 'Password Successfully Changed!');
			return redirect()->route('change_password');
		} else {
			return redirect()->back()->withErrors(['Current password is incorrect!']);
		}

		
	}
}
