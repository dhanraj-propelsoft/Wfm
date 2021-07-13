<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BankAccountType;

class BankAccountTypeController extends Controller
{
    public function index()
    {
        $accounttypes = BankAccountType::all();
        return view('admin.bank_account_type',compact('accounttypes'));
    }
}
