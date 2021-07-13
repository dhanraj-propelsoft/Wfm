<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployee;
use App\HrmLeave;
use App\HrmLeaveType;
use App\HrmAttendance;
use App\HrmAttendanceType;
use App\HrmDepartment;
use App\User;
use Carbon\Carbon;
use App\Custom;
use DatePeriod;
use DateTime;
use DateInterval;
use Session;
use Auth;
use DB;

class LeaveController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$user = User::find(Auth::user()->id);

		$person_id = Auth::user()->person_id;		

		$employee_id = HrmEmployee::where('organization_id', $organization_id)
		->where('person_id',$person_id)->first()->id;
		

		$query = HrmLeave::select('hrm_leaves.*','hrm_leaves.employee_id','hrm_employees.first_name AS employee_name','hrm_leave_types.id AS leave_type_id','hrm_leave_types.name AS leave_type_name');
		$query->leftjoin('hrm_employees','hrm_leaves.employee_id','=','hrm_employees.id');
		$query->leftjoin('hrm_leave_types','hrm_leaves.leave_type_id','=','hrm_leave_types.id');
		$query->where('hrm_leaves.organization_id', $organization_id);
		
		if(!$user->can('leave-approval')){

			$query->where('hrm_leaves.employee_id',$employee_id);
		}

		$leaves = $query->get();

		return view('hrm.leaves',compact('leaves'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$user = User::findorFail(Auth::user()->id);

		$employee_id = HrmEmployee::select('hrm_employees.id')
		->leftjoin('persons','hrm_employees.person_id','=','persons.id')
		->where('hrm_employees.person_id',$user->person_id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		$departments = HrmDepartment::select('id', 'name')->where('organization_id', $organization_id)->pluck('name', 'id');
			$departments->prepend('Select Department', '');

		//return $employee_id;

		if($employee_id == null && !$user->can('leave-approval')){
			return response()->json(['status' => 0, 'message' => 'Kindly add yourself as an employee in employee menu!', 'data' => []]);
		}

		$leaves_type = HrmLeaveType::where('organization_id',$organization_id)->pluck('name','id');
		$leaves_type->prepend("Select Leave Type", "");

		return view('hrm.leaves_create',compact('leaves_type','departments','employee_id'));
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
			'leave_type_id' => 'required',
		]);

		//return $request->all();		

		$organization_id = Session::get('organization_id');

		$user = User::findorFail(Auth::user()->id);

		$employee_id = HrmEmployee::select('hrm_employees.id')
		->leftjoin('persons','hrm_employees.person_id','=','persons.id')
		->where('hrm_employees.person_id',$user->person_id)
		->where('hrm_employees.organization_id',$organization_id)->first();

		//return $employee_id;

		$leave = new HrmLeave;
		
		if($request->input('admin_employee_id') != null )
		{
			$leave->employee_id = $request->input('admin_employee_id');
		}
		else{
			$leave->employee_id = $employee_id->id;
		}
		$leave->leave_type_id = $request->input('leave_type_id');
		$leave->reason = $request->input('reason');
		$leave->from_date = ($request->input('from_date')!=null) ? Carbon::parse($request->input('from_date'))->format('Y-m-d') : null;
		$leave->to_date = ($request->input('to_date')!=null) ? Carbon::parse($request->input('to_date'))->format('Y-m-d') : null;
		$leave->leave_days = $request->input('leave_days');
		$leave->organization_id = $organization_id;
		$leave->save();

		Custom::userby($leave, true);

		$leaves = HrmLeave::select('hrm_leaves.*','hrm_employees.first_name AS employee_name','hrm_leave_types.id AS leave_type_id','hrm_leave_types.name AS leave_type_name')
		->leftjoin('hrm_employees','hrm_leaves.employee_id','=','hrm_employees.id')
		->leftjoin('hrm_leave_types','hrm_leaves.leave_type_id','=','hrm_leave_types.id')
		->where('hrm_leaves.id',$leave->id)
		->where('hrm_leaves.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Leaves'.config('constants.flash.added'), 'data' => ['id' => $leaves->id, 'employeename' => $leaves->employee_name, 'leavetype' => $leaves->leave_type_name,'leave_days' => $leaves->leave_days,'reason'=>$leaves->reason,'approval_status' => $leaves->approval_status]]);
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
		$leaves_type = HrmLeaveType::where('organization_id',$organization_id)->pluck('name','id');
		$leaves_type->prepend("Select Leave Type", "");

		$leaves = HrmLeave::where('organization_id',$organization_id)->where('id', $id)->first();

		//return $leave_types;

		if(!$leaves) abort(403);

		return view('hrm.leaves_edit',compact('leaves','leaves_type'));
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
		//return $request->all();
		$organization_id = Session::get('organization_id');			

		$leave =  HrmLeave::findOrFail($request->input('id'));		
		$leave->leave_type_id = $request->input('leave_type_id');
		$leave->reason = $request->input('reason');
		$leave->from_date = ($request->input('from_date')!=null) ? Carbon::parse($request->input('from_date'))->format('Y-m-d') : null;
		$leave->to_date = ($request->input('to_date')!=null) ? Carbon::parse($request->input('to_date'))->format('Y-m-d') : null;
		$leave->leave_days = $request->input('leave_days');
		$leave->save();

		Custom::userby($leave, false);

		$leaves = HrmLeave::select('hrm_leaves.*','hrm_employees.first_name AS employee_name','hrm_leave_types.id AS leave_type_id','hrm_leave_types.name AS leave_type_name')
		->leftjoin('hrm_employees','hrm_leaves.employee_id','=','hrm_employees.id')
		->leftjoin('hrm_leave_types','hrm_leaves.leave_type_id','=','hrm_leave_types.id')
		->where('hrm_leaves.id',$leave->id)
		->where('hrm_leaves.organization_id', $organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Leaves'.config('constants.flash.updated'), 'data' => ['id' => $leaves->id, 'employeename' => $leaves->employee_name, 'employee_id' => $leaves->employee_id, 'leavetype' => $leaves->leave_type_name,'leave_days' => $leaves->leave_days,'reason'=>$leaves->reason,'approval_status' => $leaves->approval_status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$leaves = HrmLeave::findOrFail($request->id);
		$leaves->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Leaves'.config('constants.flash.deleted'),'data'=>[]]);
	}
	
	public function multidestroy(Request $request)
	{
		$leaves = explode(',', $request->id);

		$leaves_list = [];

		foreach ($leaves as $leave_id) {
			$leaves = HrmLeave::findOrFail($leave_id);
			$leaves->delete();
			$leaves_list[] = $leave_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Leaves'.config('constants.flash.deleted'),'data'=>['list' => $leaves_list]]);
	}

	public function multiapprove(Request $request)
    {
        $leaves = explode(',', $request->id);
        $leaves_list = [];

        foreach ($leaves as $leave_id) {
            $leave = HrmLeave::find($request->input('id'));
			$leave->approval_status = $request->input('approval_status');
			$leave->save();

			if($leave->approval_status == 1)
			{
				$leave_type = HrmLeaveType::find($leave->leave_type_id)->name;

				$attendance_type = HrmAttendanceType::where('name',$leave_type)->first();

				if($attendance_type != null)
				{
					$attendance_type_id = $attendance_type->id;
				}else{
					$attendance_type_id = HrmAttendanceType::where('name','Absent')->first()->id;
				}

				$period = new DatePeriod(new DateTime($leave->from_date), new DateInterval('P1D'), (new DateTime($leave->to_date))->modify('+1 day'));

				foreach ($period as $key => $value) 
					{
				  	$attendance = new HrmAttendance;
					$attendance->employee_id = $request->input('id');
					$attendance->payroll_status = 0;
					$attendance->attendance_type_id = $attendance_type_id;
					
				    $attendance->attended_date =  $value->format('Y-m-d');
					

					$attendance->organization_id = $organization_id;
					$attendance->save();

					Custom::userby($attendance, true);
				}
			}


            $leaves_list[] = $leave_id;
        }

        return response()->json(['status'=>1, 'message'=>'Leaves'.config('constants.flash.updated'),'data'=>['list' => $leaves_list]]);
    }
	public function leaves_status_approval(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$leave = HrmLeave::find($request->input('id'));
		$leave->approval_status = $request->input('approval_status');
		$leave->save();

	if($leave->approval_status == 1)
	{
		$leave_type = HrmLeaveType::find($leave->leave_type_id)->name;

		$attendance_type = HrmAttendanceType::where('name',$leave_type)->first();

		if($attendance_type != null)
		{
			$attendance_type_id = $attendance_type->id;
		}else{
			$attendance_type_id = HrmAttendanceType::where('name','Absent')->first()->id;
		}

		$period = new DatePeriod(new DateTime($leave->from_date), new DateInterval('P1D'), (new DateTime($leave->to_date))->modify('+1 day'));

		  	foreach ($period as $key => $value) 
			{
		  	$attendance = new HrmAttendance;
			$attendance->employee_id = $request->input('employee_id');
			$attendance->payroll_status = 0;
			$attendance->attendance_type_id = $attendance_type_id;
			
		    $attendance->attended_date =  $value->format('Y-m-d');
			

			$attendance->organization_id = $organization_id;
			$attendance->save();

			Custom::userby($attendance, true);
			}
		}
		return response()->json(['approval_status'=>$request->input('approval_status'), 'message'=>'Leaves'.config('constants.flash.updated'),'data'=>[]]);
	}

}
