<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Setting;
use Session;
use Auth;
use Response;
use App\WmsOrganizationCost;

class SettingController extends Controller
{
	public function index()
	{
		return view('settings.theme');
	}

	public function change_theme(Request $request)
	{
		$organization_id = Session::get('organization_id');
		if($organization_id != null) {
			$setting = Setting::where('organization_id', $organization_id)->where('user_id', Auth::id())->first();
		} else {
			$setting = Setting::where('user_id', Auth::id())->first();
		}

		$data = json_decode($setting->data, true);

		if($request->input('header') != null) {
			$data['header'] = $request->input('header');
		}

		if($request->input('sidebar') != null) {
			$data['sidebar'] = $request->input('sidebar');
		}
		
		$setting->data = json_encode($data);
		$setting->save();

		if($request->input('header') != null) {
			Session::put('theme_header', $request->input('header'));
		}

		if($request->input('sidebar') != null) {
			Session::put('theme_sidebar', $request->input('sidebar'));
		}

		return response()->json(['status' => 1, 'message' => 'Theme'.config('constants.flash.deleted'), 'data' => []]);
		
		
	}
	 public function propel_apps()
    {   
    		  $Current_version="WMS_19925_b.apk";
			  $previous_version="WMS_19925.apk";
		
			return view('settings.propel_more',compact('previous_version','Current_version'));
    }
    
    public function download($file)
    {
     		$headers = [
              'Content-Type' => 'application/vnd.android.package-archive',
          			   ];

			$downloadfile= public_path(). "/app/wms/".$file;

			return Response::download($downloadfile, $file, $headers);

	}
	public function org_expense_rate_index()
	{	
		$organization_id = Session::get('organization_id');
		$WmsOrganizationCost = WmsOrganizationCost::where('organization_id',$organization_id)->first();

		return view('settings.org_expense_rate',compact('WmsOrganizationCost'));
	}

	public function organizations_cost_update(Request $request,$id = false)
	{	
		 if($id==false)
	       {
	       	$organization_cost = new WmsOrganizationCost;
	       }else{
	       	$organization_cost=WmsOrganizationCost::findOrFail($id);
	       }
		
		$organization_cost->factor1 = $request->factor1;
		$organization_cost->value1 = $request->value1;

		$organization_cost->factor2 = $request->factor2;
		$organization_cost->value2 = $request->value2;

		$organization_cost->factor3 = $request->factor3;
		$organization_cost->value3 = $request->value3;

		$organization_cost->factor4 = $request->factor4;
		$organization_cost->value4 = $request->value4;

		$organization_cost->factor5 = $request->factor5;
		$organization_cost->value5 = $request->value5;

		$organization_cost->factor6 = $request->factor6;
		$organization_cost->value6 = $request->value6;

		$organization_cost->factor7 = $request->factor7;
		$organization_cost->value7 = $request->value7;

		$organization_cost->factor8 = $request->factor8;
		$organization_cost->value8 = $request->value8;

		$organization_cost->factor9 = $request->factor9;
		$organization_cost->value9 = $request->value9;

		$organization_cost->factor10 = $request->factor10;
		$organization_cost->value10 = $request->value10;

		$organization_cost->month_expense_total = $request->month_expense_total;
		$organization_cost->days_per_month = $request->days_per_month;

		$organization_cost->hours_per_day = $request->hours_per_day;
		$organization_cost->hours_per_month = $request->hours_per_month;

		$organization_cost->hourly_org_cost = $request->hourly_org_cost;
		$organization_cost->no_of_employees = $request->no_of_employees;
		
		$organization_cost->hourly_employee_cost = $request->hourly_employee_cost;
		$organization_cost->organization_id = Session::get('organization_id');
		$organization_cost->created_by = Auth::user()->id;
		$organization_cost->save();


		if($id==false)
          {
			return response()->json([ 'message' => 'Organization Cost'.config('constants.flash.added')]);
     	  }
     	  	return response()->json([ 'message' => 'Organization Cost'.config('constants.flash.updated')]);

	}  
}
