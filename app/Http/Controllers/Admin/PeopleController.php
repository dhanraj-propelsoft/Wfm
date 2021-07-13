<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;
use App\Organization;
use DB;

class PeopleController extends Controller
{
    public function index()
    {       
       
        $peoples = Organization::select('businesses.bcrm_code AS propel_id','people.display_name','organizations.id', 'organizations.name', 'organizations.status', 'organizations.is_active', 'organizations.business_id',DB::raw('DATE_FORMAT(organizations.created_at, "%d %M, %Y") AS started_date'), DB::raw('DATE_FORMAT(organization_packages.expire_on, "%d %M, %Y") AS expire_on'))
        ->leftjoin('organization_packages', 'organization_packages.organization_id', '=', 'organizations.id')
        ->leftjoin('organization_person','organization_person.organization_id','organizations.id')
        ->leftjoin('people','people.person_id','=','organization_person.person_id')
      
        ->leftjoin('businesses','businesses.id','=','organizations.id')
        ->where('organization_packages.status', 1)
		->orWhere(function ($query) {
		    $query->where('organization_packages.status', '=', 0)
		          ->whereNull('organization_packages.subscription_id');
		})
        ->orderby('organizations.name')
        ->groupby('organizations.id')
        ->get();

        
        return view('admin.people', compact('peoples'));
    }
    public function status(Request $request)
    {
        Organization::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

        return response()->json(array('result' => "success"));
    }

}
