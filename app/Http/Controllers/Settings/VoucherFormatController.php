<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\AccountVoucherFormat;
use App\AccountVoucherSeparator;
use Session;
use DB;
use App\Custom;

class VoucherFormatController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$voucher_formats = AccountVoucherFormat::where('organization_id',$organization_id)->paginate(10);
		return view('settings.voucher_format', compact('voucher_formats'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');
		$separators = AccountVoucherSeparator::select('account_voucher_separators.id', 'account_voucher_separators.name', 'account_voucher_separators.display_name')->get();
		//$separators->prepend('Select separator', '');
		return view('settings.voucher_format_create', compact('separators'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$organization_id = Session::get('organization_id');

		$voucher_format = new AccountVoucherFormat;
		$voucher_format->name = $request->input('name');
		$voucher_format->icon = $request->input('icon');
		$voucher_format->organization_id = $organization_id;
		$voucher_format->save();

		if($voucher_format->id) {

			foreach($request->input('separator') as $key => $separator){

				 $format = DB::table('account_format_separator')->insert(['format_id' => $voucher_format->id , 'separator_id' => $separator, 'value' => $request->input('preceding_zeros')]);

				 DB::table('account_format_separator')->where('format_id', $voucher_format->id)->where('separator_id', $separator)->update(['order' => $key + 1]);
			}

		   
		}

		Custom::userby($voucher_format, true);

		Custom::add_addon('records');

		return response()->json(array('status' => 1, 'message' => 'Voucher Format'.config('constants.flash.added'), 'data' => ['id' => $voucher_format->id, 'name' => $voucher_format->name, 'icon' => $voucher_format->icon, 'format' => $format]));

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

		$voucher_formats = AccountVoucherFormat::findOrFail($id);

		$separators = AccountVoucherSeparator::select('account_voucher_separators.id', 'account_voucher_separators.name', 'account_voucher_separators.display_name')->get();
		//$separators->prepend('Select separator', '');

		$account_formats = DB::table('account_format_separator')->select('account_format_separator.value', 'account_format_separator.format_id', 'account_format_separator.separator_id', 'account_voucher_separators.name')
		->leftjoin('account_voucher_separators', 'account_voucher_separators.id', '=', 'account_format_separator.separator_id')
		->where('account_format_separator.format_id', $id)
		->orderby('account_format_separator.order')
		->get();

		return view('settings.voucher_format_edit', compact('separators', 'voucher_formats', 'account_formats'));
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

		$organization_id = Session::get('organization_id');

		$voucher_format = AccountVoucherFormat::findOrFail($request->input('id'));
		$voucher_format->name = $request->input('name');
		$voucher_format->icon = $request->input('icon');
		$voucher_format->organization_id = $organization_id;
		$voucher_format->save();

		DB::table('account_format_separator')->where('account_format_separator.format_id', $voucher_format->id)->delete();

		foreach($request->input('separator') as $key => $separator) {

			 $format = DB::table('account_format_separator')->insert(['format_id' => $voucher_format->id , 'separator_id' => $separator, 'value' => $request->input('preceding_zeros')]);

			 DB::table('account_format_separator')->where('format_id', $voucher_format->id)->where('separator_id', $separator)->update(['order' => $key + 1]);
		}

		Custom::userby($voucher_format, false);

		Custom::add_addon('records');

		return response()->json(array('status' => 1, 'message' => 'Voucher Format'.config('constants.flash.added'), 'data' => ['id' => $voucher_format->id, 'name' => $voucher_format->name, 'icon' => $voucher_format->icon, 'format' => $format]));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$voucher_format = AccountVoucherFormat::findOrFail($request->id);

		$voucher_format->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Voucher Format'.config('constants.flash.deleted'), 'data' =>[]]);
	}
}
