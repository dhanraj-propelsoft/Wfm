<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmLogRegister;
use App\HrmPersonType;
use App\HrmEmployee;
use Session;
use Response;
use Validator;
use App\Custom;
use DateTime;
use DB;

class LogRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');

        $log_registers = HrmLogRegister::select('hrm_log_registers.*',DB::raw('DATE_FORMAT(hrm_log_registers.in_time,"%h:%i %p") AS in_time'), DB::raw('DATE_FORMAT(hrm_log_registers.out_time,"%h:%i %p") AS out_time'),'hrm_person_types.name as person_type','persons.first_name as person_name', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS employee_name'))
        ->leftJoin('hrm_person_types', 'hrm_log_registers.person_type_id', '=', 'hrm_person_types.id')
        ->leftJoin('persons', 'hrm_log_registers.person_id', '=', 'persons.id')
        ->leftJoin('hrm_employees', 'hrm_log_registers.employee_id', '=', 'hrm_employees.id')
        ->where('hrm_log_registers.organization_id', $organization_id)->paginate(10);

        return view('hrm.log_registers',compact(('log_registers')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');
         
        $person_type = HrmPersonType::select('hrm_person_types.id','hrm_person_types.name','hrm_person_types.type')->where('organization_id', $organization_id);
        $person_types = $person_type->get();

        $employees = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name', 'id');
        $employees->prepend('Choose Employee', '');

        return view('hrm.log_registers_create',compact('person_types','employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'log_date' => 'required',
            'in_time' => 'required',
            'out_time' => 'required',
            'person_type_id' => 'required',
        ]);

        $organization_id = Session::get('organization_id');
        //dd($organization_id);

        //return $request->all();

        $log_date = explode('-', $request->input('log_date'));
        $in_time = new DateTime($request->input('in_time'));
        $out_time = new DateTime($request->input('out_time'));

        $log_register = new HrmLogRegister;
        $log_register->log_date = $log_date[2]."-".$log_date[1]."-".$log_date[0];
        $log_register->in_time = $in_time->format('H:i:s');
        $log_register->out_time = $out_time->format('H:i:s');
        $log_register->purpose = $request->input('purpose');
        $log_register->description = $request->input('description');
        $log_register->employer_note = $request->input('employer_note');
        $log_register->person_type_id = $request->input('person_type_id');
        
        if($request->input('person_id') != null)
        {
            $log_register->person_id = $request->input('person_id');
        }
        if($request->input('employee_id') != null)
        {
            $log_register->employee_id = $request->input('employee_id');
        }  
        $log_register->organization_id = $organization_id; 
        $log_register->save();

        Custom::userby($log_register, false);

        Custom::add_addon('records');

        $log_registers = HrmLogRegister::select('hrm_log_registers.*',DB::raw('DATE_FORMAT(hrm_log_registers.in_time,"%h:%i %p") AS in_time'), DB::raw('DATE_FORMAT(hrm_log_registers.out_time,"%h:%i %p") AS out_time'),'hrm_person_types.name as person_type','persons.first_name as person_name', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS employee_name'))
        ->leftJoin('hrm_person_types', 'hrm_log_registers.person_type_id', '=', 'hrm_person_types.id')
        ->leftJoin('persons', 'hrm_log_registers.person_id', '=', 'persons.id')
        ->leftJoin('hrm_employees', 'hrm_log_registers.employee_id', '=', 'hrm_employees.id')
        ->where('hrm_log_registers.id',$log_register->id)
        ->where('hrm_log_registers.organization_id', $organization_id)->first();

        return response()->json(['status' => 1, 'message' => 'Log Register'.config('constants.flash.added'), 'data' => ['id' => $log_registers->id, 'log_date' => $log_registers->log_date, 'person_type' => $log_registers->person_type,'employee_name' => ($log_registers->employee_name != null) ? $log_registers->employee_name : $log_registers->person_name, 'in_time'=>$in_time->format('h:i A'), 'out_time'=>$out_time->format('h:i A')]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization_id = Session::get('organization_id');
         
        $person_type = HrmPersonType::select('hrm_person_types.id','hrm_person_types.name','hrm_person_types.type')->where('organization_id', $organization_id);
        $person_types = $person_type->get();

        $employees = HrmEmployee::where('organization_id', $organization_id)->pluck('first_name', 'id');
        $employees->prepend('Choose Employee', '');

        $log_registers = HrmLogRegister::where('organization_id',$organization_id)->where('id',$id)->first();

        if(!$log_registers) abort(403);

        return view('hrm.log_registers_edit',compact('log_registers','person_types','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'log_date' => 'required',
            'in_time' => 'required',
            'out_time' => 'required',
            'person_type_id' => 'required',
        ]);

        $organization_id = Session::get('organization_id');

        $log_date = explode('-', $request->input('log_date'));
        $in_time = new DateTime($request->input('in_time'));
        $out_time = new DateTime($request->input('out_time'));

        $log_register = HrmLogRegister::findorFail($request->input('id'));
        $log_register->log_date = $log_date[2]."-".$log_date[1]."-".$log_date[0];
        $log_register->in_time = $in_time->format('H:i:s');
        $log_register->out_time = $out_time->format('H:i:s');
        $log_register->purpose = $request->input('purpose');
        $log_register->description = $request->input('description');
        $log_register->employer_note = $request->input('employer_note');

        $log_register->person_type_id = $request->input('person_type_id');
        
        
        $log_register->person_id = null;
        if($request->input('person_id') != null) $log_register->person_id = $request->input('person_id');
       
        $log_register->employee_id = null;
        if($request->input('employee_id') != null) $log_register->employee_id = $request->input('employee_id');
        
        $log_register->save();

        Custom::userby($log_register, false);


        $log_registers = HrmLogRegister::select('hrm_log_registers.*',DB::raw('DATE_FORMAT(hrm_log_registers.in_time,"%h:%i %p") AS in_time'), DB::raw('DATE_FORMAT(hrm_log_registers.out_time,"%h:%i %p") AS out_time'),'hrm_person_types.name as person_type','persons.first_name as person_name', DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS employee_name'))
        ->leftJoin('hrm_person_types', 'hrm_log_registers.person_type_id', '=', 'hrm_person_types.id')
        ->leftJoin('persons', 'hrm_log_registers.person_id', '=', 'persons.id')
        ->leftJoin('hrm_employees', 'hrm_log_registers.employee_id', '=', 'hrm_employees.id')
        ->where('hrm_log_registers.id',$log_register->id)
        ->where('hrm_log_registers.organization_id', $organization_id)->first();

        return response()->json(['status' => 1, 'message' => 'Log Register'.config('constants.flash.updated'), 'data' => ['id' => $log_registers->id, 'log_date' => $log_registers->log_date, 'person_type' => $log_registers->person_type,'employee_name' => ($log_registers->employee_name != null) ? $log_registers->employee_name : $log_registers->person_name, 'in_time'=>$in_time->format('h:i A'), 'out_time'=>$out_time->format('h:i A')]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $log_register = HrmLogRegister::findorFail($request->id);
        $log_register->delete();

        Custom::delete_addon('records');

        return response()->json(['status'=>1, 'message'=>'Log Register'.config('constants.flash.deleted'), 'data'=>[]]);
    }

    public function multidestroy(Request $request)
    {
        $log_registers = explode(',', $request->id);

        $log_register_list = [];

        foreach ($log_registers as $log_register_id) {
            $log_register = HrmLogRegister::findOrFail($log_register_id);
            $log_register->delete();
            $log_register_list[] = $log_register_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Log Register'.config('constants.flash.deleted'),'data'=>['list' => $log_register_list]]);
    }
}
