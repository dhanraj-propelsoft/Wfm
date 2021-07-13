<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployeeWorkingPeriod;
use App\OrganizationPerson;
use App\AccountPersonType;
use App\HrmDepartment;
use App\HrmEmployee;
use Carbon\Carbon;
use App\Custom;
use App\People;
use Validator;
use Session;
use DB;

class EmployeeRelieveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id = Session::get('organization_id');

        $employee_relieve = HrmEmployeeWorkingPeriod::select('hrm_employee_working_periods.id', 'hrm_employee_working_periods.employee_id', DB::raw('DATE_FORMAT(hrm_employee_working_periods.relieved_date, "%d-%m-%Y") AS relieved_date'), 'hrm_employee_working_periods.reason', 'hrm_employees.first_name AS employee_name')
        ->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_employee_working_periods.employee_id')
        ->where('hrm_employees.organization_id', $organization_id)
        ->whereNotNull('hrm_employees.deleted_at')
        ->orderby('hrm_employees.first_name');
        $employee_relieving = $employee_relieve->paginate(10);

        return view('hrm.employee_relieve', compact('employee_relieving'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = Session::get('organization_id');

        $parent_dept = HrmDepartment::where('organization_id',$organization_id)->pluck('name', 'id');
        $parent_dept->prepend('Select Department', '');

        return view('hrm.employee_relieve_create', compact('parent_dept'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();

        $this->validate($request, [
            'department_id' => 'required',
            'employee_id' => 'required',
            'reason' => 'required',
            'relieved_date' => 'required',
        ]);

        $organization_id = Session::get('organization_id');
        $employee_id = $request->input('employee_id');
        
        $employee = HrmEmployee::findorFail($employee_id);

        $employee_relieve = HrmEmployeeWorkingPeriod::where('employee_id', $request->input('employee_id'))->first();
        $employee_relieve->reason = $request->input('reason');      
        $employee_relieve->relieved_date = Carbon::parse($request->input('relieved_date'))->format('Y-m-d');
        $employee_relieve->save();

        $people = People::where('person_id', $employee->person_id)->where('user_type', '0')->first();

        if($people != null) {
            $person_type_id = AccountPersonType::where('name', 'employee')->first()->id;
            $people_person = DB::table('people_person_types')->where('people_id', $people->id)->where('person_type_id', $person_type_id);

            if($people_person != null) {
                $people_person->delete();
            }
        }
        if($employee_relieve != null){
            
            $organization_person = DB::table('organization_person')->where('person_id', $employee->person_id)->where('organization_id', $organization_id)->delete();

            $employee->delete();
        }

        Custom::userby($employee_relieve, true);
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Employee Relieve'.config('constants.flash.added'), 'data' => ['id' => $employee_relieve->id, 'employee_name' => $employee->first_name, 'reason' => $employee_relieve->reason, 'relieved_date' => $employee_relieve->relieved_date]]);
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
        
        $employee_relieve = HrmEmployeeWorkingPeriod::where('id', $id)->first();
        if(!$employee_relieve) abort(403);

        //return $employee_relieve;
        /*$employee_relieve = HrmEmployeeWorkingPeriod::select('hrm_employee_working_periods.id', 'hrm_employee_working_periods.employee_id', 'hrm_employee_working_periods.reason', 'hrm_employee_working_periods.relieved_date', 'hrm_departments.name AS department_name')
        ->leftJoin('hrm_departments', 'hrm_departments.id','=','hrm_employee_working_periods')
        ->where('id', $id)->first();*/

        $parent_dept = HrmDepartment::where('organization_id',$organization_id)->pluck('name', 'id');
        $parent_dept->prepend('Select Department', '');

        return view('hrm.employee_relieve_edit', compact('employee_relieve', 'parent_dept'));
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
            'reason' => 'required',
            'relieved_date' => 'required',
        ]);

        $employee_relieve = HrmEmployeeWorkingPeriod::where('employee_id', $request->input('employee_id'))->first();
        //dd($employee_relieve);
        $employee_relieve->reason = $request->input('reason');        
        $employee_relieve->relieved_date = Carbon::parse($request->input('relieved_date'))->format('Y-m-d');
        $employee_relieve->save();

        $employee_name = HrmEmployee::withTrashed()->where('id',  $request->input('employee_id'))->first()->first_name;

        Custom::userby($employee_relieve, false);

        return response()->json(['status' => 1, 'message' => 'Employee Relieve'.config('constants.flash.updated'), 'data' => ['id' => $employee_relieve->id, 'employee_name' => $employee_name, 'reason' => $employee_relieve->reason, 'relieved_date' => $request->input('relieved_date')]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
