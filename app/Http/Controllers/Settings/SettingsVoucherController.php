<?php

namespace App\Http\Controllers\Settings;

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

class SettingsVoucherController extends Controller
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
		
		return view('settings.settings_voucher',compact('account_voucher_masters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
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

		$vouchers = AccountVoucher::select('account_vouchers.*', 'account_voucher_types.display_name AS voucher_type_name','account_voucher_types.id AS voucher_type_id')->leftjoin('account_voucher_types', 'account_voucher_types.id', '=', 'account_vouchers.voucher_type_id')->where('account_vouchers.id', $id)->where('organization_id', $organization_id)->first();
		if(!$vouchers) abort(403);
		
		//return view('Accounts.voucher_edit')->withVouchers($vouchers);
		 return view('settings.settings_voucher_edit',compact('vouchers', 'voucher_type', 'voucher_format', 'printing_format', 'account_ledger', 'fixed_ledger','multi_print_formates','print_templates','sales_templates'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
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
	
	public function restart_all_vouchers()
	{
		$organization_id = session::get('organization_id');
		$update_all_voucher = DB::table('account_vouchers')->where('organization_id',$organization_id)->update(['restart'=> 1]);
		return response()->json(['message' => 'Successfully restarted for all vouchers']);
	}
}
