<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmployee;
use App\HrmAttendance;
use App\HrmAttendanceType;
use App\HrmDepartment;
use App\HrmShift;
use App\HrmHoliday;
use Carbon\Carbon;
use App\Custom;
use Response;
use Validator;
use Session;
use DateTime;
use DB;
use Auth;
use Calendar;

class AttendanceController extends Controller
{
		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index()
		{
			$organization_id = Session::get('organization_id');

			$now = Carbon::now();
			$current_date =  $now->format('Y-m-d');

			$shifts = HrmShift::select('id', 'name', 'from_time', 'to_time')->where('organization_id', $organization_id)->get();
			//dd($shifts);
			
			//to get in and out time to default general shift

			$in_out = HrmShift::select('from_time','to_time')
			->where('organization_id',$organization_id)
			->where('hrm_shifts.name',"General")
			->first();
			//dd($in_out);

			$attendance_types = HrmAttendanceType::where('organization_id', $organization_id)->get();

			$prsent_id = HrmAttendanceType::select('id')->where('name','Present')
			->where('organization_id', $organization_id)->first();
			//dd($prsent_id->id);

			$total_employee = HrmEmployee::select('hrm_employees.id','hrm_employees.first_name',DB::raw('Count(hrm_employees.id) as employee_count'))
			->where('hrm_employees.organization_id',$organization_id)
			->where('hrm_employees.deleted_at',Null)
			->first();
			//dd($total_employee->employee_count);


		
			$present_employees = HrmAttendance::select('hrm_attendances.attended_date as start','hrm_attendances.attended_date as end',DB::raw('count(hrm_attendances.id) as present_count'),DB::raw('CONCAT(count(hrm_attendances.id),"/",(SELECT COUNT(hrm_employees.id) AS employee_count FROM hrm_employees WHERE hrm_employees.organization_id = '.$organization_id.' AND hrm_employees.`deleted_at` IS NULL LIMIT 1)) as title'))
			->leftjoin('hrm_employees','hrm_employees.id','=','hrm_attendances.employee_id')
			->where('hrm_attendances.organization_id',$organization_id)
			->where('hrm_attendances.attendance_type_id',$prsent_id->id)
			->whereNull('hrm_employees.deleted_at')
			->groupby('hrm_attendances.attended_date')
			->get();
			//dd($present_employees);

	        $present_employee1 = json_decode($present_employees);
	        //dd($present_employee);

	         $holidays = HrmHoliday::select('hrm_holidays.id','hrm_holidays.name as title','hrm_holidays.holiday_date as start','hrm_holidays.holiday_date as end')
	        ->where('organization_id',$organization_id)
	        ->get();
	        //dd($holidays);
	        $holiday = json_decode($holidays);
	        //dd($holiday);
	        $present_employee = array_merge($present_employee1,$holiday);
	        //dd($present_employee);
 
			return view('hrm.attendance', compact('shifts','current_date','attendance_types','total_employee','present_employee','in_out','holiday'));
		}
		public function index_new()
		{
			$organization_id = Session::get('organization_id');

			$now = Carbon::now();
			$current_date =  $now->format('Y-m-d');

			$shifts = HrmShift::select('id', 'name', 'from_time', 'to_time')->where('organization_id', $organization_id)->get();

			$attendance_types = HrmAttendanceType::where('organization_id', $organization_id)->get();

			return view('hrm.attendance_new', compact('shifts','current_date','attendance_types'));
		}
		public function create($date)
		{
			$organization_id = Session::get('organization_id');

			$departments = HrmDepartment::select('id', 'name')->where('organization_id', $organization_id)->pluck('name', 'id');
			$departments->prepend('Select Department', '');

			$attendance_types = HrmAttendanceType::where('organization_id', $organization_id)->where('attendance_status', 1)->get();

			$shifts = HrmShift::select('id', 'name', 'from_time', 'to_time')->where('organization_id', $organization_id)->get();


			return view('hrm.attendance_create', compact('departments', 'attendance_types','shifts','date'));
		}

		/**
		 * Remove the specified resource from storage.
		 *
		 * @param  int  $id
		 * @return \Illuminate\Http\Response
		 */
		public function destroy(Request $request)
		{
				$attendance = HrmAttendance::findOrFail($request->id)->delete();

				return response()->json(['status' => 1, 'message' => 'Attendance'.config('constants.flash.deleted'), 'data' => []]);
		}

		public function attendance_update(Request $request)
		{
			//dd($request->all());
			$organization_id = Session::get('organization_id');

			$attendance_type = HrmAttendanceType::find($request->input('attendance_type_id'));  


			$attendance_values = ['attendance_type_id' => $attendance_type->id, 'status' => $request->status];

			if(($request->input('in_time') != null) && ($request->input('out_time') != null)) {
				$attendance_values = array_merge($attendance_values, ['in_time' => ($request->input('in_time') != null) ? Custom::time_twenty_four($request->input('in_time')) : null, 'out_time' => ($request->input('out_time') != null) ? Custom::time_twenty_four($request->input('out_time')): null]);
			}


			$attendances = HrmAttendance::updateOrCreate(['employee_id' => $request->input('employee_id'), 'shift_id' => ($request->input('shift_id') != null) ? $request->input('shift_id') : null, 'attended_date' => $request->date, 'organization_id' => $organization_id], $attendance_values);

			$attendance = [];

			$color = HrmAttendanceType::findOrFail($request->input('attendance_type_id'))->color;
			$shift = HrmShift::findOrFail($request->input('shift_id'))->name;

			$employees = HrmAttendance::select('hrm_employees.id AS employee_id', 
				DB::raw("CONCAT(hrm_employees.first_name, ' ', COALESCE(hrm_employees.last_name, '')) AS employee_name"), 
				DB::raw("TIME_FORMAT(hrm_attendances.in_time, '%h:%i %p') as in_time"), 
				DB::raw("TIME_FORMAT(hrm_attendances.out_time, '%h:%i %p') as out_time"), 
				'hrm_attendance_types.name AS attendance_type')
			->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
			->leftjoin('hrm_attendance_types', 'hrm_attendance_types.id', '=', 'hrm_attendances.attendance_type_id')
			->where('hrm_attendances.id', $attendances->id)
			->first();

			$attendance_types = HrmAttendanceType::select('id', 'name', 'color')
			->where('status', 1)
			->where('organization_id', $organization_id)
			->get();


			 $no_of_present_employees ='';
			  if($attendance_type->name == "Present")
			  {
			  	$prsent_id = HrmAttendanceType::select('id')->where('name','Present')
				->where('organization_id', $organization_id)->first();
				$present_employees = HrmAttendance::select('hrm_attendances.attended_date as start','hrm_attendances.attended_date as end',DB::raw('count(hrm_attendances.id) as present_count'),DB::raw('CONCAT(count(hrm_attendances.id),"/",(SELECT COUNT(hrm_employees.id) AS employee_count FROM hrm_employees WHERE hrm_employees.organization_id = '.$organization_id.' AND hrm_employees.`deleted_at` IS NULL LIMIT 1)) as title'))
				->leftjoin('hrm_employees','hrm_employees.id','=','hrm_attendances.employee_id')
				->where('hrm_attendances.organization_id',$organization_id)
				->where('hrm_attendances.attendance_type_id',$prsent_id->id)
				->whereNull('hrm_employees.deleted_at')
				->where('hrm_attendances.attended_date', $request->date)
				->groupby('hrm_attendances.attended_date')
				->get();

		        $present_employee = json_decode($present_employees);
		        //dd($present_employee[0]->title);
		        $no_of_present_employees = $present_employee[0]->title;
			  }

			$attendance['id'] = $attendances->id;
			$attendance['color'] = $color;
			$attendance['shift'] = $shift;
			$attendance['employee_name'] = $employees->employee_name;
			$attendance['in_time'] = $employees->in_time;
			$attendance['out_time'] = $employees->out_time;
			$attendance['attendance_type'] = $employees->attendance_type;
			$attendance['employee_id'] = $employees->employee_id;
			$attendance['attendance_type_list'] = $attendance_types;
			$attendance['date'] = $request->date;


			return response()->json(['status' => 1, 'message' => 'Attendance'.config('constants.flash.updated'), 'data' => $attendance,'present_employee' => $no_of_present_employees]);
		}


		public function attendance_update_new(Request $request)
		{
			//dd($request->all());
			$mode = $request->attendance_mode;
			$organization_id = Session::get('organization_id');
			$employee_id = $request->employee_id;
			$shift_id = $request->shift_id;
			$in_time = $request->in_time;
			$out_time = $request->out_time;
			//dd($employee_id,$shift_id,$in_time,$out_time);
			
				foreach ($employee_id as $key => $value) {
				$attendance = new HrmAttendance;
				$attendance->employee_id = $employee_id[$key];
				$attendance->shift_id = $shift_id[$key];
				$attendance->attended_date = $request->input('date');
				$attendance->in_time = $in_time[$key];
				$attendance->out_time = $out_time[$key];
				$attendance->attendance_type_id = $request->input('attendance_type_id');
				$attendance->organization_id = $organization_id;
				$attendance->created_by = Auth::user()->id;
				$attendance->save();
			   	}


			  $color = HrmAttendanceType::select('color','display_name')->where('id',$attendance->attendance_type_id)->first();

			
			/*$organization_id = Session::get('organization_id');

			$attendance_type = HrmAttendanceType::find($request->input('attendance_type_id'));  

			$attendance_values = ['attendance_type_id' => $attendance_type->id, 'status' => $request->status];

			if(($request->input('in_time') != null) && ($request->input('out_time') != null)) {
				$attendance_values = array_merge($attendance_values, ['in_time' => ($request->input('in_time') != null) ? Custom::time_twenty_four($request->input('in_time')) : null, 'out_time' => ($request->input('out_time') != null) ? Custom::time_twenty_four($request->input('out_time')): null]);
			}


			$attendances = HrmAttendance::updateOrCreate(['employee_id' => $request->input('employee_id'), 'shift_id' => ($request->input('shift_id') != null) ? $request->input('shift_id') : null, 'attended_date' => $request->date, 'organization_id' => $organization_id], $attendance_values);

			$attendance = [];

			$color = HrmAttendanceType::findOrFail($request->input('attendance_type_id'))->color;
			$shift = HrmShift::findOrFail($request->input('shift_id'))->name;

			$employees = HrmAttendance::select('hrm_employees.id AS employee_id', 
				DB::raw("CONCAT(hrm_employees.first_name, ' ', COALESCE(hrm_employees.last_name, '')) AS employee_name"), 
				DB::raw("TIME_FORMAT(hrm_attendances.in_time, '%h:%i %p') as in_time"), 
				DB::raw("TIME_FORMAT(hrm_attendances.out_time, '%h:%i %p') as out_time"), 
				'hrm_attendance_types.name AS attendance_type')
			->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
			->leftjoin('hrm_attendance_types', 'hrm_attendance_types.id', '=', 'hrm_attendances.attendance_type_id')
			->where('hrm_attendances.id', $attendances->id)
			->first();

			$attendance_types = HrmAttendanceType::select('id', 'name', 'color')
			->where('status', 1)
			->where('organization_id', $organization_id)
			->get();

			$attendance['id'] = $attendances->id;
			$attendance['color'] = $color;
			$attendance['shift'] = $shift;
			$attendance['employee_name'] = $employees->employee_name;
			$attendance['in_time'] = $employees->in_time;
			$attendance['out_time'] = $employees->out_time;
			$attendance['attendance_type'] = $employees->attendance_type;
			$attendance['employee_id'] = $employees->employee_id;
			$attendance['attendance_type_list'] = $attendance_types;*/

			return response()->json(['status' => 1, 'message' => 'Attendance'.config('constants.flash.updated'), 'data' => $attendance,'color'=>$color]);
		}

		public function get_attendance_details_new(Request $request) {
                //dd($request->all());
			$date = $request->date;
			$organization_id = Session::get('organization_id');

			/* $employees = DB::select("SELECT COALESCE(hrm_attendances.id, '') AS id, hrm_employees.id AS employee_id, hrm_shifts.name AS shift_name, hrm_attendance_types.name AS type_name, CONCAT(hrm_employees.first_name, ' ', COALESCE(hrm_employees.last_name, '')) AS employee_name, TIME_FORMAT(hrm_attendances.in_time, '%h:%i %p') as in_time, TIME_FORMAT(hrm_attendances.out_time, '%h:%i %p') as out_time, hrm_attendances.payroll_status, hrm_shifts.name as shift_name, hrm_attendances.attendance_type_id, hrm_attendance_types.color FROM hrm_employees 
				LEFT JOIN hrm_employee_working_periods 
					ON hrm_employee_working_periods.employee_id = hrm_employees.id
				LEFT JOIN hrm_attendances 
					ON hrm_attendances.employee_id = hrm_employees.id AND hrm_attendances.attended_date = '$request->date'
				LEFT JOIN hrm_shifts 
					ON hrm_shifts.id = hrm_attendances.shift_id 
				LEFT JOIN hrm_attendance_types 
					ON hrm_attendance_types.id = hrm_attendances.attendance_type_id 
				WHERE hrm_employee_working_periods.joined_date <= '$request->date'
				AND hrm_employees.organization_id = $organization_id
				ORDER BY employee_name");*/

	   
				$employees = HrmEmployee::select('hrm_employees.id AS employee_id',
   			'hrm_employees.employee_code AS employee_code',DB::raw('CONCAT(hrm_employees.first_name," ",COALESCE(hrm_employees.last_name, " ")) AS employee_name'),'hrm_designations.name AS employee_designation','hrm_teams.name AS team','hrm_shifts.id AS shift_id','hrm_shifts.name AS shift',DB::raw("TIME_FORMAT(hrm_shifts.from_time,'%h:%i %p') AS from_time"),DB::raw("TIME_FORMAT(hrm_shifts.to_time, '%h:%i %p') AS to_time"),'hrm_shifts.total_hours');
				$employees->leftjoin('hrm_employee_designation','hrm_employee_designation.employee_id','=','hrm_employees.id');
				$employees->leftjoin('hrm_designations','hrm_designations.id','=','hrm_employee_designation.designation_id');
				$employees->leftjoin('hrm_employee_team','hrm_employee_team.employee_id','=','hrm_employees.id');
				$employees->leftjoin('hrm_teams','hrm_teams.id','=','hrm_employee_team.team_id');
				$employees->leftjoin('hrm_shifts','hrm_shifts.id','=','hrm_employees.shift_id');
				$employees->leftjoin('hrm_employee_working_periods','hrm_employee_working_periods.employee_id','=','hrm_employees.id');
				$employees->where('hrm_employees.organization_id',$organization_id);
				$employees->where('hrm_employee_working_periods.joined_date','<=',$date);
				$employee = $employees->get();
				//dd($employee);


			
			$attendance_types = HrmAttendanceType::select('id', 'name', 'color')
			->where('status', 1)
			->where('organization_id', $organization_id)
			->get();

			return Response()->json(['result' => ['attendance' =>$employee, 'attendance_types' => $attendance_types]]);
		}
		public function get_attendance_details(Request $request) {

			$organization_id = Session::get('organization_id');

			 $employees = DB::select("SELECT COALESCE(hrm_attendances.id, '') AS id, hrm_employees.id AS employee_id, hrm_shifts.name AS shift_name, hrm_attendance_types.name AS type_name, CONCAT(hrm_employees.first_name, ' ', COALESCE(hrm_employees.last_name, '')) AS employee_name, TIME_FORMAT(hrm_attendances.in_time, '%h:%i %p') as in_time, TIME_FORMAT(hrm_attendances.out_time, '%h:%i %p') as out_time, hrm_attendances.payroll_status, hrm_shifts.name as shift_name, hrm_attendances.attendance_type_id, hrm_attendance_types.color FROM hrm_employees 
				LEFT JOIN hrm_employee_working_periods 
					ON hrm_employee_working_periods.employee_id = hrm_employees.id
				LEFT JOIN hrm_attendances 
					ON hrm_attendances.employee_id = hrm_employees.id AND hrm_attendances.attended_date = '$request->date'
				LEFT JOIN hrm_shifts 
					ON hrm_shifts.id = hrm_attendances.shift_id 
				LEFT JOIN hrm_attendance_types 
					ON hrm_attendance_types.id = hrm_attendances.attendance_type_id 
				WHERE hrm_employee_working_periods.joined_date <= '$request->date'
				AND hrm_employees.organization_id = $organization_id
				AND hrm_employees.deleted_at IS NULL
				ORDER BY employee_name");

			$attendance_types = HrmAttendanceType::select('id', 'name', 'color')
			->where('status', 1)
			->where('organization_id', $organization_id)
			->get();

			return Response()->json(['result' => ['attendance' =>$employees, 'attendance_types' => $attendance_types]]);
		}

		public function multidestroy(Request $request)
		{
			
			$attendances = explode(',', $request->id); 

			$attendance_list = [];

			foreach ($attendances as $attendance) {
				$attendance = HrmAttendance::find($attendance);
				if($attendance != null){

					$attendance_list[] = $attendance->id;

					$attendance->delete();

				}
			}
			

			return response()->json(['status' => 1, 'message' => 'Attendance'.config('constants.flash.deleted'), 'data' => ['list' => $attendance_list]]);

		}   

	
		public function multitime(Request $request)
		{
			//dd($request->all());
			$employees = explode(',', $request->employee_id);

			$attendance_list = [];

			$organization_id = Session::get('organization_id');

			foreach ($employees as $employee) {
				$attendance_id = HrmAttendance::updateOrCreate(['employee_id' => $employee, 'shift_id' => ($request->input('shift_id') != null) ? $request->input('shift_id') : null, 'attended_date' => $request->attended_date, 'organization_id' => $organization_id], ['attendance_type_id' => $request->input('attendance_type_id'), 'status' => $request->status, 'in_time' => ($request->input('in_time') != null) ? Custom::time_twenty_four($request->input('in_time')) : null, 'out_time' => ($request->input('out_time') != null) ? Custom::time_twenty_four($request->input('out_time')): null]);

			   $color = HrmAttendanceType::findOrFail($attendance_id->attendance_type_id)->color;

			 

			   $attendance_list[] = ['attendance_id' => $attendance_id->id, 'employee_id' => $attendance_id->employee_id, 'color' => $color];
			}
			  $attendance_type_name = HrmAttendanceType::select('id','name')
			   ->where('hrm_attendance_types.id',$request->attendance_type_id)
			   ->where('organization_id',$organization_id)->first();
			   //dd($attendance_type_name->name);
			   $no_of_present_employees ='';
			  if($attendance_type_name->name == "Present")
			  {
			  	$prsent_id = HrmAttendanceType::select('id')->where('name','Present')
				->where('organization_id', $organization_id)->first();
				$present_employees = HrmAttendance::select('hrm_attendances.attended_date as start','hrm_attendances.attended_date as end',DB::raw('count(hrm_attendances.id) as present_count'),DB::raw('CONCAT(count(hrm_attendances.id),"/",(SELECT COUNT(hrm_employees.id) AS employee_count FROM hrm_employees WHERE hrm_employees.organization_id = '.$organization_id.' AND hrm_employees.`deleted_at` IS NULL LIMIT 1)) as title'))
				->leftjoin('hrm_employees','hrm_employees.id','=','hrm_attendances.employee_id')
				->where('hrm_attendances.organization_id',$organization_id)
				->where('hrm_attendances.attendance_type_id',$prsent_id->id)
				->whereNull('hrm_employees.deleted_at')
				->where('hrm_attendances.attended_date', $request->attended_date)
				->groupby('hrm_attendances.attended_date')
				->get();

		        $present_employee = json_decode($present_employees);
		        //dd($present_employee[0]->title);
		        $no_of_present_employees = $present_employee[0]->title;
			  }
			  

			return response()->json(['status' => 1, 'message' => 'Attendance'.config('constants.flash.deleted'), 'data' => ['list' => $attendance_list,'date' => $request->attended_date,'attendance_type_name'=>$attendance_type_name->name,'present_employee' => $no_of_present_employees]]);
		}
	public function attendance_report_view()
	{
		return view('hrm.attendance_report');			
	}
	public function get_attedance_report(Request $request)
    {   $organization_id = Session::get('organization_id');

       	$month= $request->get('month');
       	$year= $request->get('year');

       	$date=$year."-".$month;
       	$start = new DateTime(date('Y-m-01', strtotime($date)));
		$end = new DateTime(date('Y-m-t', strtotime($date)));
		$start_date=$start->format('Y-m-d');
		$end_date=$end->format('Y-m-d');
		
       	$attedance_report=HrmAttendance::select('hrm_employees.id',
       		DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS first_name'),
       		'hrm_attendances.attended_date','hrm_attendances.attendance_type_id',
       		'hrm_employee_working_periods.relieved_date',
       		DB::raw("SUM(CASE WHEN hrm_attendance_types.name='Present' THEN 1 ELSE 0 END) AS present"),
       		DB::raw("SUM(CASE WHEN hrm_attendance_types.name='Casual Leave' THEN 1 ELSE 0 END) AS casual_leave"),
       		DB::raw("SUM(CASE WHEN hrm_attendance_types.name='Sick Leave' THEN 1 ELSE 0 END) AS sick_leave"),
       		DB::raw("SUM(CASE WHEN hrm_attendance_types.name='Leave' THEN 1 ELSE 0 END) AS formal_leave"),
        	DB::raw("SUM(CASE WHEN hrm_attendance_types.name!='Sick Leave' AND hrm_attendance_types.name!='Present' AND hrm_attendance_types.name!='Casual Leave' AND hrm_attendance_types.name!='Leave' THEN 1 ELSE 0 END ) AS others_leave"))
       		->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
       		->leftjoin('hrm_attendance_types', 'hrm_attendance_types.id', '=', 'hrm_attendances.attendance_type_id')
       		->leftjoin('hrm_employee_working_periods', 'hrm_employee_working_periods.employee_id', '=', 'hrm_employees.id')
       		->where('hrm_attendances.organization_id',$organization_id)
       		->whereBetween('hrm_attendances.attended_date',array($start_date,$end_date))
       		->where(function($query) use($start_date,$end_date){
       			 	$query->whereBetween('hrm_employee_working_periods.relieved_date',array($start_date,$end_date))
       			 			->orWhere('hrm_employee_working_periods.relieved_date',null);
       			 })
       			 ->groupby('hrm_employees.id')
       			 ->get();
       		return Response()->json(['result' => ['attendance' =>$attedance_report,'month'=>$month,'year'=>$year]]);		 	
    }
    public function attedance_details_view($id,$month,$year){
         		 
        	$organization_id = Session::get('organization_id');
         	$emp_name=HrmEmployee::select(DB::raw('CONCAT(hrm_employees.first_name, " ", COALESCE(hrm_employees.last_name, "")) AS name'))->where('id',$id)->first();
         	$employee_name=$emp_name->name;
        	 		
         	$attedance_view=HrmAttendance::select('hrm_employees.first_name',DB::raw('DATE_FORMAT(hrm_attendances.attended_date, "%d") as attended_date'),'hrm_attendance_types.name')
         	->leftjoin('hrm_employees', 'hrm_employees.id', '=', 'hrm_attendances.employee_id')
         	->leftjoin('hrm_attendance_types', 'hrm_attendance_types.id', '=', 'hrm_attendances.attendance_type_id')
         	->where('hrm_attendances.organization_id',$organization_id)
         	->where('hrm_attendances.employee_id',$id)
         	->whereMonth('hrm_attendances.attended_date', '=',	$month)
       		->whereYear('hrm_attendances.attended_date', '=',$year)
       		->orderBy(DB::raw("DATE_FORMAT(hrm_attendances.attended_date,'%d')"), 'asc')
         	->get();
         			

         		return view('hrm.attedance_details_view',compact('attedance_view','employee_name'));	
         	}
		
}
