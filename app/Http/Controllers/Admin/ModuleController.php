<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::where('status', 1)->get();
        return view('admin.modules', compact('modules'));

    }
}
