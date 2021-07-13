<?php

namespace App\Http\Controllers\Fuel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemMainCategory;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use App\Unit;
use App\ InventoryItem;
use App\FsmPump;
use App\FsmProduct;
use App\TaxGroup;
use App\AccountGroup;
use App\AccountLedger;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
  public function shift_vs_sales(){


 return view('fuel_station.shift_vs_salesreport');

  }

   public function invoice_base_sales(){


 return view('fuel_station.invoice_base_sales');

  }
  
  public function supplier_list(){


 return view('fuel_station.supplier_lists');

  }
  
   public function supplier_list_create(){


 return view('fuel_station.supplier_list_create');

  }
  

}
