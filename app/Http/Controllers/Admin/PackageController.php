<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PlanAccountType;
use App\Package;

class PackageController extends Controller
{
	public function index() {

	    $package = Package::select('packages.*','plan_account_types.display_name as plan_accounttype');
	    $package->leftJoin('plan_account_types', 'packages.account_type_id', '=', 'plan_account_types.id');
	    $package->orderBy('packages.id', 'desc');
	    $packages = $package->get();

	    return view('admin.packages', compact('packages','plan'));
	}
}
