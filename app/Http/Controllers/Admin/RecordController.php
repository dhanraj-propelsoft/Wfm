<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Record;

class RecordController extends Controller
{
	public function index() {

		$record = Record::select('records.*','plan_account_types.display_name as plan_accounttype');
		$record->leftJoin('plan_account_types', 'records.account_type_id', '=', 'plan_account_types.id');
		$record->orderBy('records.id', 'desc');
		$records = $record->get();
			
		return view('admin.records',compact('records'));
	}
}
