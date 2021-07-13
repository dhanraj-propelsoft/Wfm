<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmEmploymentType;
use App\HrmAttendanceType;
use App\HrmDesignation;
use App\HrmDepartment;
use App\HrmLeaveType;
use App\Custom;
use App\Gender;
use Response;
use Session;

class LeaveTypeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$leavetypes = HrmLeaveType::where('organization_id',$organization_id)->paginate(10);
		return view('hrm.leave_types',compact('leavetypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$genders = Gender::pluck('name','id');
		$genders->prepend('Select Gender', '');

		$employment_types = HrmEmploymentType::where('organization_id',$organization_id)->pluck('name','id');
		$employment_types->prepend('Select Employment Type','');

		$departments = HrmDepartment::where('organization_id',$organization_id)->pluck('name','id');
		$departments->prepend('Select Department','');

		$designations = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designations->prepend('Select Designation','');

		return view('hrm.leave_types_create',compact('genders','employment_types','departments','designations'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
			'name'=>'required',
			'code'=> 'required'
		]);

		//return $request->all();
		
		$organization_id = Session::get('organization_id');

		$leave_types = new HrmLeaveType;

		$leave_types->name = $request->input('name');
		$leave_types->display_name = $request->input('name');
		$leave_types->code = $request->input('code');
		
		if($request->input('yearly_limit') != null)
		{
			$leave_types->yearly_limit = $request->input('yearly_limit');  
		}
		if($request->input('yearly_carry_limit') != null)
		{
			$leave_types->yearly_carry_limit = $request->input('yearly_carry_limit');
		}
		if($request->input('monthly_limit') != null)
		{
		   $leave_types->monthly_limit = $request->input('monthly_limit'); 
		}
		if($request->input('monthly_carry_limit') != null)
		{
		   $leave_types->monthly_carry_limit = $request->input('monthly_carry_limit'); 
		}
		if($request->input('part_of_weekoff') != null)
		{
		   $leave_types->part_of_weekoff = $request->input('part_of_weekoff'); 
		}
		if($request->input('part_of_holiday') != null)
		{
		   $leave_types->part_of_holiday = $request->input('part_of_holiday'); 
		}
		if($request->input('before_weekoff') != null)
		{
		   $leave_types->before_weekoff = $request->input('before_weekoff'); 
		}
		if($request->input('after_weekoff') != null)
		{
		   $leave_types->after_weekoff = $request->input('after_weekoff'); 
		}
		if($request->input('before_holiday') != null)
		{
		   $leave_types->before_holiday = $request->input('before_holiday'); 
		}
		if($request->input('after_holiday') != null)
		{
		   $leave_types->after_holiday = $request->input('after_holiday'); 
		}
		if($request->input('applicable_gender') != null)
		{
		   $leave_types->applicable_gender = $request->input('applicable_gender'); 
		}
		if($request->input('applicable_employment_type') != null)
		{
		   $leave_types->applicable_employment_type = $request->input('applicable_employment_type'); 
		}
		if($request->input('applicable_department') != null)
		{
		   $leave_types->applicable_department = $request->input('applicable_department');
		}
		if($request->input('applicable_designation') != null)
		{
		   $leave_types->applicable_designation = $request->input('applicable_designation'); 
		}
		if($request->input('effective_from') != null)
		{
		   $leave_types->effective_from = $request->input('effective_from'); 
		}
		if($request->input('period_type') != null)
		{
		   $leave_types->period_type = $request->input('period_type'); 
		}
		if($request->input('activation_period') != null)
		{
		   $leave_types->activation_period = $request->input('activation_period'); 
		}        
		
		$leave_types->pay_status = $request->input('pay_status');

		$leave_types->organization_id = $organization_id;

		$leave_types->save();

		if($leave_types)
		{
			$attendance_type = new HrmAttendanceType;
			$attendance_type->name = $request->input('name');
			$attendance_type->display_name = $request->input('name');
			$attendance_type->color = $request->input('color');
			$attendance_type->paid_status = $request->input('pay_status');
			$attendance_type->delete_status = 1;
			$attendance_type->organization_id = $organization_id;
			$attendance_type->save();

			Custom::userby($attendance_type, true);
			Custom::add_addon('records');
		}

		Custom::userby($leave_types,true);

		Custom::add_addon('records');

		return response()->json(['status' => 1, 'message' => 'Leave Types'.config('constants.flash.added'), 'data' => ['id' => $leave_types->id, 'name' => $leave_types->name, 'code' => $leave_types->code,'status' => $leave_types->status]]);       
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

		$genders = Gender::pluck('name','id');
		$genders->prepend('Select Gender', '');

		$employment_types = HrmEmploymentType::where('organization_id',$organization_id)->pluck('name','id');
		$employment_types->prepend('Select Employment Type','');

		$departments = HrmDepartment::where('organization_id',$organization_id)->pluck('name','id');
		$departments->prepend('Select Department','');

		$designations = HrmDesignation::where('organization_id',$organization_id)->pluck('name','id');
		$designations->prepend('Select Designation','');

		$leave_types = HrmLeaveType::where('organization_id',$organization_id)->where('id', $id)->first();

		//return $leave_types;

		if(!$leave_types) abort(403);

		return view('hrm.leave_types_edit',compact('genders','employment_types','departments','designations','leave_types'));
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
		$this->validate($request,[
			'name'=>'required',
			'code'=> 'required'
		]);

		//return $request->all();
		
		$organization_id = Session::get('organization_id');

		$leave_types =  HrmLeaveType::findOrFail($request->input('id'));

		$leave_types->name = $request->input('name');
		$leave_types->display_name = $request->input('name');
		$leave_types->code = $request->input('code');

		$leave_types->yearly_limit = 0;
		if($request->input('yearly_limit') != null)  $leave_types->yearly_limit = $request->input('yearly_limit'); 

		$leave_types->yearly_carry_limit = 0;
		if($request->input('yearly_carry_limit') != null) $leave_types->yearly_carry_limit = $request->input('yearly_carry_limit');

		$leave_types->monthly_limit = 0;
		if($request->input('monthly_limit') != null) $leave_types->monthly_limit = $request->input('monthly_limit'); 
	   
		$leave_types->monthly_carry_limit = 0;
		if($request->input('monthly_carry_limit') != null) $leave_types->monthly_carry_limit = $request->input('monthly_carry_limit');
		
		$leave_types->part_of_weekoff = 0;
		if($request->input('part_of_weekoff') != null) $leave_types->part_of_weekoff = $request->input('part_of_weekoff');
		
		$leave_types->part_of_holiday = 0;
		if($request->input('part_of_holiday') != null) $leave_types->part_of_holiday = $request->input('part_of_holiday');
		
		$leave_types->before_weekoff = 0;
		if($request->input('before_weekoff') != null) $leave_types->before_weekoff = $request->input('before_weekoff');
		
		$leave_types->after_weekoff = 0;
		if($request->input('after_weekoff') != null) $leave_types->after_weekoff = $request->input('after_weekoff');
		
		$leave_types->before_holiday = 0;
		if($request->input('before_holiday') != null) $leave_types->before_holiday = $request->input('before_holiday');
		
		$leave_types->after_holiday = 0;
		if($request->input('after_holiday') != null) $leave_types->after_holiday = $request->input('after_holiday');
		
		$leave_types->applicable_gender = null;
		if($request->input('applicable_gender') != null) $leave_types->applicable_gender = $request->input('applicable_gender');
		
		$leave_types->applicable_employment_type = null;
		if($request->input('applicable_employment_type') != null) $leave_types->applicable_employment_type = $request->input('applicable_employment_type');
		
		$leave_types->applicable_department = null;
		if($request->input('applicable_department') != null) $leave_types->applicable_department = $request->input('applicable_department');
		
		$leave_types->applicable_designation = null;
		if($request->input('applicable_designation') != null) $leave_types->applicable_designation = $request->input('applicable_designation');
		
		$leave_types->effective_from = 0;
		if($request->input('effective_from') != null) $leave_types->effective_from = $request->input('effective_from');
		
		$leave_types->period_type = 0;
		if($request->input('period_type') != null) $leave_types->period_type = $request->input('period_type');

		$leave_types->activation_period = 0;
		if($request->input('activation_period') != null) $leave_types->activation_period = $request->input('activation_period');
		
		$leave_types->pay_status = $request->input('pay_status');

		$leave_types->organization_id = $organization_id;

		$leave_types->save();

		Custom::userby($leave_types,false);

		 return response()->json(['status' => 1, 'message' => 'Leave Types'.config('constants.flash.updated'), 'data' => ['id' => $leave_types->id, 'name' => $leave_types->name, 'code' => $leave_types->code, 'status' => $leave_types->status]]); 
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$leave_types = HrmLeaveType::findOrFail($request->id);
		$leave_types->delete();

		Custom::delete_addon('records');

		return response()->json(['status'=>1, 'message'=>'Leave Types'.config('constants.flash.deleted'),'data'=>[]]);
	}
	
	public function multidestroy(Request $request)
	{
		$leave_types = explode(',', $request->id);

		$leavetype_list = [];

		foreach ($leave_types as $leave_type_id) {
			$leave_type = HrmLeaveType::findOrFail($leave_type_id);
			$leave_type->delete();
			$leavetype_list[] = $leave_type_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Leave Type'.config('constants.flash.deleted'),'data'=>['list' => $leavetype_list]]);
	}

	public function multiapprove(Request $request)
    {
        $leave_types = explode(',', $request->id);

        $leavetype_list = [];

        foreach ($leave_types as $leave_type_id) {
            HrmLeaveType::where('id', $leave_type_id)->update(['status' => $request->input('status')]);;
            $leavetype_list[] = $leave_type_id;
        }

        return response()->json(['status'=>1, 'message'=>'Leave Type'.config('constants.flash.updated'),'data'=>['list' => $leavetype_list]]);
    }

    public function leavetypes_status_approval(Request $request)
    {
        HrmLeaveType::where('id', $request->input('id'))
          ->update(['status' => $request->input('status')]);

        return response()->json(['status'=>1, 'message'=>'Leave Type'.config('constants.flash.updated'),'data'=>[]]);
    }
}
