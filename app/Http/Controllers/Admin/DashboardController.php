<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Session;
use DB;

class DashboardController extends Controller
{
	public function index()
	{
		
		return view('admin.dashboard');
	}
}
