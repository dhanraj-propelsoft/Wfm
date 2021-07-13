<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Organization;
use Carbon\Carbon;
use App\Business;
use App\Person;
use App\User;
use DateTime;
use Session;
use Auth;
use File;
use DB;

class UserLogController extends Controller
{
	public function index()
	{
		return view('settings.user_logs');
	}

	public function get_user_log(Request $request) {

		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrFail($organization_id);
		$organization_name = $organization->name;

		$business_id = $organization->business_id;
		$business = Business::findOrFail($business_id);
		$business_name = $business->business_name;

		$date_time = Carbon::parse('01'.'-'.$request->input('date'))->format('Y-m');
	
		$files = File::glob(public_path().'\organizations\\'.$business_name.'\user_log\\'.$date_time.'\*.txt');

		$users_log = [];

		   foreach($files as $file){

				$logs = explode('\\', $file);

				$user_id = str_replace(".txt", "", end($logs));

				$user = User::findOrFail($user_id);
				$person = Person::select(DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS person_name'))->where('id', $user->person_id)->first();

				$users_log[] = array("id" => $user_id,"name" => $person->person_name);
		   }


		return response()->json(array('result' => $users_log));
	}

	public function list_user_log(Request $request) {

		$user = User::findOrFail($request->input('id'));

		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrFail($organization_id);
		$organization_name = $organization->name;

		$business_id = $organization->business_id;
		$business = Business::findOrFail($business_id);
		$business_name = $business->business_name;

		$date_time = Carbon::parse('01'.'-'.$request->input('date'))->format('Y-m');

		$path = public_path().'\organizations\\'.$business_name.'\user_log\\'.$date_time."/".$user->person_id.".txt";

		$data = "[".file_get_contents($path)."]";

		return json_decode($data);

	}

	public function user_log(Request $request) {

		$user = Auth::user();
		$datetime = new DateTime();

		$organization_id = Session::get('organization_id');
		$organization = Organization::findOrFail($organization_id);
		$organization_name = $organization->name;

		$business_id = $organization->business_id;
		$business = Business::findOrFail($business_id);
		$business_name = $business->business_name;

		$path = 'organizations/'.$business_name.'/user_log/'.$datetime->format('Y-m');
			
		$user_log = [];
		$user_log = array("user_name" => $user->name, "datetime" => $datetime->format('Y-m-d H:m'), "url" => $request->input('url'), "page" => $request->input('page'));
		
		if($request->input('page') != null) {

			$path_array = explode('/', $path);

			$public_path = '';

			foreach ($path_array as $p) {
				$public_path .= $p."/";
				if (!file_exists(public_path($public_path))) {
					mkdir(public_path($public_path), 0777, true);
				}
			}

			$userFile = public_path($public_path)."/".$user->id.".txt";
			$logfile = fopen($userFile, "a+");

			if (trim(file_get_contents($userFile)) == false) fwrite($logfile, json_encode($user_log));

			else fwrite($logfile, "," . json_encode($user_log));
					
					
			fclose($logfile);
		}

		return response()->json(array('result' => $user_log));
	}
}
