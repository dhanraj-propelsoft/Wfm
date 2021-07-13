<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;
use App\HrmEmployee;
use Auth;
use Session;
use App\Custom;
use App\Organization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
	public $successStatus = 200;
	public $unauthorised = 401;
	private $orgId;

	public function login() {

		
		Log::info('API_LoginController->login :- Login request received for mobile number : '.request('mobile'));
		Log::info('API_LoginController->login :- Login request received for PassWord : '.request('password'));
		
		if(Auth::attempt(['mobile' => request('mobile'), 'password' => request('password'), 'status' => 1])) {


			$user = Auth::user();

			$success['status'] = '1';
			$success['user'] =  $user;
			$success['person_id'] =  $user->person_id;
			$success['hrm_employee_id'] =  HrmEmployee::where('person_id',$user->person_id)->first()->id;
			$success['propelId'] = Person::find($user->person_id)->crm_code;
			$success['image'] =  "";
			$success['token'] =  $user->createToken($user->name)->accessToken;
			// $success['token'] =  Str::random(80);
			
			$organization = Organization::select('organizations.*');
			$organization->leftJoin('organization_person', 'organizations.id', '=', 'organization_person.organization_id');
			$organization->leftJoin('persons', 'persons.id', '=', 'organization_person.person_id');
			$organization->leftJoin('module_organization', 'module_organization.organization_id', '=', 'organizations.id');
			$organization->leftJoin('modules', 'module_organization.module_id', '=', 'modules.id');
			$organization->where('persons.id', $user->person_id);
			$organization->where('modules.name', "wfm");
			$organizations = $organization->groupBy('organizations.id')->get();

			$orgId = 0;
			if(count($organizations) > 0){
				$orgId = $organizations[0]->id;

			// 

			$res = collect($organizations)->map(function ($model)  {

           
                    $update = Organization::findOrFail($model['id']);
                    $update->is_active = 0;
                    $update->save();

                    return $update;   
            	});

                  $futureorg = Organization::findOrFail($orgId);
                  $futureorg->is_active = 1;
                  $futureorg->save();	
			}
			$success['firstOrg'] =  $orgId;
			
			Log::info('API_LoginController->login :- Login request received for PassWord : '.$success['firstOrg']);
			
			
			return response()->json($success, $this->successStatus);
		} else {
			return response()->json(['status'=>'Wrong Credentials!'], $this->unauthorised);
		}

	}


	public function logout(Request $request) {

		// $org = Custom::organization_id();
		// Log::info('LoginController->logout :- inside'.json_encode($org));
		if($request->user()->token()) {
			$request->user()->token()->revoke();
			$success['status'] =  '1';
			return response()->json($success, $this->successStatus);
		}

		return response()->json(['error'=>'Unauthorised'], $this->unauthorised);


	}
}
