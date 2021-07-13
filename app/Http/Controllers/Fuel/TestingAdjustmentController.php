<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FsmTank;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use App\Unit;
use App\FsmPump;
use App\FsmProduct;
use App\TaxGroup;
use App\AccountGroup;
use App\AccountLedger;
use App\HrmEmployee;
use App\CustomerGroping;
use App\InventoryAdjustment;  
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class TestingAdjustmentController extends Controller
{
  public function testingadjusment_index(){

            return view('fuel_station.testingadjusment_index');

  }
 
 public function testingadjusment_create(){

      $tank=FsmTank::pluck('name','id');
      $tank->prepend('select a tank','');

        $adjust_by=HrmEmployee::where('status',1)->pluck('first_name','id');
        $adjust_by->prepend("Select Adjuster Name");

            return view('fuel_station.testingadjusment_create',compact('tank','adjust_by'));

  }
}
