<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountPersonType;

class PersonTypeController extends Controller
{
    public function index()
    {
        $person_types = AccountPersonType::all();
        return view('admin.person_types', compact('person_types'));
    }
}
