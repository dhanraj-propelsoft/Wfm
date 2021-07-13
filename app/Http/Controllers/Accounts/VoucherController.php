<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountVoucherFormat;
use App\AccountVoucherType;
use App\AccountVoucher;
use App\Http\Requests;
use App\MultiTemplate;
use App\AccountLedger;
use App\PrintTemplate;
use App\Custom;
use Validator;
use App\State;
use Session;
use Auth;
use DB;

class VoucherController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$voucher_type    = AccountVoucherType::pluck('name','id');
		$voucher_format  = AccountVoucherFormat::pluck('name','id');
		$printing_format = PrintTemplate::pluck('name','id');
		$account_ledger = AccountLedger::pluck('name','id');

	   //dd($voucher_type);
		$organization_id = Session::get('organization_id');

		$query = AccountVoucher::select('account_vouchers.*','account_vouchers.name AS voucher_name','account_voucher_types.display_name AS voucher_type_name','account_vouchers.date_setting','account_ledgers.name AS ledger_name')
		->leftjoin('account_voucher_types','account_vouchers.voucher_type_id','=','account_voucher_types.id')
		->leftjoin('account_ledgers','account_vouchers.debit_ledger_id','=','account_ledgers.id')
		->where('account_vouchers.organization_id',$organization_id);

		$account_voucher_masters = $query->get();

	   //dd($account_voucher_masters);

		return view('accounts.voucher',compact('account_voucher_masters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$voucher_type    = AccountVoucherType::pluck('display_name','id');
		$voucher_type->prepend('Select Voucher Type', '');
		$voucher_format  = AccountVoucherFormat::where('organization_id',$organization_id)->pluck('name','id');
		$voucher_format->prepend('Select Voucher Numbering format', '');
		$printing_format = PrintTemplate::where('organization_id',$organization_id)->pluck('display_name','id');
		$printing_format->prepend('Select Voucher Printing format', '');    
        
       	$multi_print_formates = PrintTemplate::where('organization_id',$organization_id)->pluck('display_name','id');
		
		$query = AccountLedger::select('account_ledgers.id',
		'account_ledgers.display_name AS name')
		->where('organization_id',$organization_id);

		$account_ledger = $query->pluck('name','id');
		$account_ledger->prepend('Select Ledger', '');

		return view('accounts.voucher_create',compact('voucher_type','voucher_format','printing_format','account_ledger','multi_print_formates'));
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
			'display_name' => 'required',
			'code' => 'required',          
			'voucher_type_id' => 'required',
			'format_id' => 'required'
		]);
        if($request->print_id == null || $request->multi_print_id){
           $print_formate_id = $request->multi_print_id;
     foreach ($print_formate_id as $value) {
        $multi_template = new MultiTemplate;
		$multi_template->voucher_id = $request->input('voucher_type_id');
		$multi_template->print_temp_id = $value;
		$multi_template->organization_id = Session::get('organization_id');
        $multi_template->created_by = Auth::user()->id;
        $multi_template->save();
         }
        }

		$user_id = Auth::user()->id;
		$organization_id = Session::get('organization_id');

		$account_voucher_masters = new AccountVoucher;
		if($request->input('name') != null)
		{
			$account_voucher_masters->name = $request->input('name');
		}
		
		$account_voucher_masters->display_name = $request->input('display_name');
		$account_voucher_masters->code = $request->input('code');
		$account_voucher_masters->voucher_type_id = $request->input('voucher_type_id');
		$account_voucher_masters->date_setting = $request->input('date_setting');
		$account_voucher_masters->format_id = $request->input('format_id');
		$account_voucher_masters->print_id = $request->input('print_id');
		$account_voucher_masters->starting_value = ($request->input('starting_value') != null) ? $request->input('starting_value') : 1;
		if($request->input('debit_ledger_id') != null){
			$account_voucher_masters->debit_ledger_id = $request->input('debit_ledger_id');
		}
		if($request->input('credit_ledger_id') != null){
			$account_voucher_masters->credit_ledger_id = $request->input('credit_ledger_id');
		}
		$account_voucher_masters->organization_id = Session::get('organization_id');
		$account_voucher_masters->user_id = $user_id;
		$account_voucher_masters->save();

		Custom::userby($account_voucher_masters, true);
		Custom::add_addon('records');

		$voucher_type_id = AccountVoucherType::findorFail($account_voucher_masters->voucher_type_id)->display_name;

		return response()->json(['status' => 1, 'message' => 'Voucher Master'.config('constants.flash.added'), 'data' => ['id' => $account_voucher_masters->id, 'name' => $account_voucher_masters->name,
			'display_name' => $account_voucher_masters->display_name, 'code' => $account_voucher_masters->code,
			'voucher_type_id' => $voucher_type_id, 'date_setting' => $account_voucher_masters->date_setting,
			'format_id' => $account_voucher_masters->format_id, 'print_id' => $account_voucher_masters->print_id,
			'debit_ledger_id' => ($account_voucher_masters->debit_ledger_id != null) ? $account_voucher_masters->debit_ledger_id : "",
			'credit_ledger_id' => ($account_voucher_masters->credit_ledger_id != null) ? $account_voucher_masters->credit_ledger_id : ""]]);
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

		$voucher_type    = AccountVoucherType::pluck('display_name','id');
		$voucher_type->prepend('Select Voucher Type', '');

		$voucher_format  = AccountVoucherFormat::where('organization_id',$organization_id)->pluck('name','id');
		$voucher_format->prepend('Select Voucher Numbering format', '');

		$printing_format = PrintTemplate::where('organization_id',$organization_id)->pluck('display_name','id');
		$printing_format->prepend('Select Voucher Printing format', '');

		$multi_print_formates = PrintTemplate::where('organization_id',$organization_id)->pluck('display_name','id');

		$print_templates = MultiTemplate::where('organization_id',$organization_id)->where('voucher_id',22)->pluck('print_temp_id');

		$sales_templates = MultiTemplate::where('organization_id',$organization_id)->where('voucher_id',21)->pluck('print_temp_id');

		$query = AccountLedger::select('account_ledgers.id',
		'account_ledgers.display_name AS name')
		->where('organization_id',$organization_id);

		$account_ledger = $query->pluck('name','id');
		$account_ledger->prepend('Select Ledger', '');

		$fixed_ledger = $query->get();

		$vouchers = AccountVoucher::select('account_vouchers.*', 'account_voucher_types.display_name AS voucher_type_name','account_voucher_types.id as voucher_type_id')->leftjoin('account_voucher_types', 'account_voucher_types.id', '=', 'account_vouchers.voucher_type_id')->where('account_vouchers.id', $id)->where('organization_id', $organization_id)->first();
		if(!$vouchers) abort(403);
		
		//return view('Accounts.voucher_edit')->withVouchers($vouchers);
		 return view('accounts.voucher_edit',compact('vouchers', 'voucher_type', 'voucher_format', 'printing_format', 'account_ledger', 'fixed_ledger','multi_print_formates','print_templates','sales_templates'));
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
		//dd($request->all());
    	$organization_id = Session::get('organization_id');
		$this->validate($request, [
			'display_name' => 'required',
			'code' => 'required',          
			'voucher_type_id' => 'required',          
			'format_id' => 'required',          
			'print_id' => 'required',            
		]);

    if($request->multi_print_id){
		 DB::table('multi_templates')->where('organization_id',$organization_id)->where('voucher_id',$request->input('voucher_type_id'))->delete();

		   $print_formate_id = $request->multi_print_id;

  		foreach ($print_formate_id as $value) {
    		$multi_template = new MultiTemplate;
			$multi_template->voucher_id = $request->input('voucher_type_id');
			$multi_template->print_temp_id = $value;
			$multi_template->organization_id = Session::get('organization_id');
        	$multi_template->created_by = Auth::user()->id;
        	$multi_template->save();

        }

    }
   
		$account_voucher_masters = AccountVoucher::findOrFail($request->input('id'));
		if($request->input('name') != null) {
			$account_voucher_masters->name = $request->input('name');
		}
		$account_voucher_masters->display_name = $request->input('display_name');
		$account_voucher_masters->code = $request->input('code');
		$account_voucher_masters->voucher_type_id = $request->input('voucher_type_id');
		$account_voucher_masters->date_setting = $request->input('date_setting');
		$account_voucher_masters->format_id = $request->input('format_id');
		$account_voucher_masters->print_id = $request->input('print_id');
		$account_voucher_masters->restart = $request->input('restart_value');

		$account_voucher_masters->starting_value = ($request->input('starting_value') != null) ? $request->input('starting_value') : 1;
		
		if($request->input('debit_ledger_id') != null){
			$account_voucher_masters->debit_ledger_id = $request->input('debit_ledger_id');
		}
		if($request->input('credit_ledger_id') != null){
			$account_voucher_masters->credit_ledger_id = $request->input('credit_ledger_id');
		}
		$account_voucher_masters->save();

		Custom::userby($account_voucher_masters, false);

		$voucher_type_id = AccountVoucherType::findorFail($account_voucher_masters->voucher_type_id)->display_name;

		return response()->json(['status' => 1, 'message' => 'Voucher Master'.config('constants.flash.updated'), 'data' => ['id' => $account_voucher_masters->id, 'name' => $account_voucher_masters->name,
			'display_name' => $account_voucher_masters->display_name, 'code' => $account_voucher_masters->code,
			'voucher_type_id' => $voucher_type_id, 'date_setting' => $account_voucher_masters->date_setting,
			'format_id' => $account_voucher_masters->format_id, 'print_id' => $account_voucher_masters->print_id,
			'debit_ledger_id' => ($account_voucher_masters->debit_ledger_id != null) ? $account_voucher_masters->debit_ledger_id : "",
			'credit_ledger_id' => ($account_voucher_masters->credit_ledger_id != null) ? $account_voucher_masters->credit_ledger_id : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$account_voucher_master = AccountVoucher::findOrFail($request->input('id'));
		$account_voucher_master->delete();
		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Voucher Master'.config('constants.flash.deleted'), 'data' => []]);
	}
}
