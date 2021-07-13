<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PersonCommunicationAddress;
use App\ People;
use App\Person;
use Carbon\Carbon;
use App\User;
use DB;

class EntityMappingController extends Controller
{
    public function index()
    {
      $mapping= Person::select('persons.crm_code as propel_id','persons.id as person_id','persons.first_name as person_name','person_communication_addresses.mobile_no as person_mobile','people.id as people_id','people.first_name as people_name','people.mobile_no as people_mobile','users.id as user_id','users.name as user_name','users.mobile as user_mobile','users.status AS status')
     ->leftjoin('people','people.person_id','=','persons.id')
      ->leftjoin('users','users.person_id','=','persons.id')
      ->leftjoin('person_communication_addresses','person_communication_addresses.person_id','=','persons.id')
      ->get();
   //dd($mapping);
  
       return view('admin.Entity_Mapping',compact('mapping'));
    }
  }
