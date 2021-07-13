<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;
use App\OrganizationPerson;
use DB;

class PersonController extends Controller
{
    public function index()
    {
         $person=Person::select('persons.crm_code as propel_id','persons.first_name as person_name','people.organization_id','organizations.name as organization_name','person_communication_addresses.mobile_no','person_communication_addresses.email_address')
        ->leftjoin('people','people.person_id','=','persons.id')
        ->leftjoin('organizations','organizations.id','=','people.organization_id')
        ->leftjoin('person_communication_addresses','person_communication_addresses.person_id','=','persons.id')
        ->orderby('persons.id')
        ->groupby('persons.crm_code')
        ->get();
        return view('admin.Person',compact('person'));
    }
     public function status(Request $request)
    {
        //dd($request->all());
        Person::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

        return response()->json(array('result' => "success"));
    }
   
}
