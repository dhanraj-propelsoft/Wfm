<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountFinancialYear;
use App\Custom;
use Session;
use DateTime;
use App\HrmEmployee;
use App\AccountVoucher;
use Auth;

class FinancialYearController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$financial_years = AccountFinancialYear::where('organization_id', $organization_id)->get();
			

		return view('accounts.financial_year_list', compact('financial_years'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id=false)
	{	
			$financial_year_to = (date('m') > 3) ? date('Y',strtotime('+1 year')) : date('Y');
			$financial_year_from = $financial_year_to - 1;
			$financial_year_end_date = $financial_year_to.'-'.'03-31';
			$financial_year_start_date = $financial_year_from.'-'.'04-01';
			 if ($id==false)
		     {
		        $financial_years=[];

		        
		     }
		    else{
		         
		        $financial_years=AccountFinancialYear::where('id',$id)
		            				->first();        
		    	}

		
			return view('accounts.financial_year_create', compact('financial_year_start_date','financial_year_end_date','financial_years'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request,$id=false)
	{	
		$organization_id = Session::get('organization_id');

		$person_id = Auth::user()->person_id;
        $login_employee_id = HrmEmployee::select('hrm_employees.id')
            ->where('hrm_employees.organization_id', $organization_id)
            ->where('hrm_employees.person_id', $person_id)
            ->first()->id;

         $current_year_status = 0;
         if($request->current_year != "false"){
         	$current_year_status = 1;
         	$a=$this->change_old_fiscal_year($id);
         }


         
        if($id==false)
	       {
	          $financial_year =  new AccountFinancialYear;

	       } 
       	else 
	       {   
			  $financial_year=AccountFinancialYear::findOrFail($id);
	       }
		
		$financial_year->name = $request->financial_start_year.'-'.$request->financial_end_year;
		$financial_year->books_start_year =  $request->financial_start_year;
		$financial_year->books_end_year = $request->financial_end_year;
		$financial_year->financial_start_year = $request->financial_start_year;
		$financial_year->financial_end_year = $request->financial_end_year;
		$financial_year->voucher_year_format = $request->voucher_format;
		$financial_year->organization_id = $organization_id;
		$financial_year->user_id = Auth::user()->id;
		$financial_year->status = $current_year_status;
		$financial_year->save();     
		Custom::userby($financial_year, false);
		
		if($request->current_year == "true"){
			$update_all_voucher = AccountVoucher::where('organization_id',$organization_id)->update(['restart'=> 1]);
		}

		if($id==false)
          {
			return response()->json([ 'message' => 'Financial Year'.config('constants.flash.added'),
	            'data' =>['financial_years'=>$financial_year]]);
     	  }
     	  	return response()->json([ 'message' => 'Financial Year'.config('constants.flash.updated'),
	            'data' =>['financial_years'=>$financial_year]]);
     	  
	     	
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
	
	public function change_current_year(Request $request)
	{
		

		if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }

       	AccountFinancialYear::where('id', $request->input('id'))->update($UpdateData);
       	if($request->input('status') == "1"){
       	$a=$this->change_old_fiscal_year($request->input('id'));
       }
      
        return response()->json(array('result' => "success",'status'=>$UpdateData));  
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

		$financial_year = AccountFinancialYear::where('id', $id)->where('organization_id', $organization_id)->where('status', 1)->first();
		if(!$financial_year) abort(403);

		return view('accounts.financial_year_edit', compact('financial_year'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{   //return($request->all());
		$opening_date = explode('-', $request->input('financial_start_year'));
		$closing_date = explode('-', $request->input('financial_end_year'));

		$financial_year =  AccountFinancialYear::findOrFail($request->input('id'));
		$financial_year->financial_start_year = $opening_date[2].'-'.$opening_date[1].'-'.$opening_date[0];
		$financial_year->financial_end_year = $closing_date[2].'-'.$closing_date[1].'-'.$closing_date[0];
		$financial_year->voucher_year_format = $request->voucher_financial_year;

		$financial_year->save();     

		Custom::userby($financial_year, false);

		return response()->json(['status' => 1, 'message' => 'Financial Year'.config('constants.flash.updated'), 'data' => ['id' => $financial_year->id, 'financial_start_year' => $financial_year->financial_start_year, 'financial_end_year' => $financial_year->financial_end_year]]);
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
	    public function fiscal_year_duplicate(Request $request){
    	
    	$organization_id = Session::get('organization_id');

    	$fiscal_years = AccountFinancialYear::where('name',$request->financial_start_year.'-'.$request->financial_end_year);
    	$fiscal_years->where('organization_id',$organization_id);
    	if($request->financial_id){
    	$fiscal_years->where('id','!=',$request->financial_id);	
    	}
    	$fiscal_year = $fiscal_years->exists();
    	

    	 return response()->json($fiscal_year);
    	
    }
    function change_old_fiscal_year($id){
		$organization_id = Session::get('organization_id');
	 	$other_fis_year = AccountFinancialYear::where('id','!=',$id) ->where('organization_id', $organization_id)->get();

         for($i=0;$i<count($other_fis_year);$i++) 
        {
            $old_fis_year=AccountFinancialYear::findOrFail($other_fis_year[$i]->id);
            $old_fis_year->status='0';
            $old_fis_year->save();  
             
            
        }
    }
}
