<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GlobalItemCategoryType;
use App\AccountPersonType;
use App\AccountVoucher;
use App\AccountEntry;
use App\VehicleModel;
use App\Transaction;
use Carbon\Carbon;
use App\People;
use App\Custom;
use App\City;
use App\Gst;
use Session;
use DB;

class HelperController extends Controller
{
		public function get_city(Request $request)
		{  
			$this->validate($request, [
					'state'  => 'required'
			]);

			$city = City::select('id', 'name')->where('state_id', $request->input('state'))->get();

			return response()->json(array('result' => $city));
		}

		public function get_model(Request $request)
		{  
			$this->validate($request, [
					'make_id'  => 'required'
			]);

			$model = VehicleModel::select('id', 'display_name AS name')->where('vehicle_make_id', $request->input('make_id'))->get();

			return response()->json(array('result' => $model));
		}

		public function get_voucher_no(Request $request)
		{  
			$voucher_master = AccountVoucher::find($request->input('voucher_type'));

			$organization_id = Session::get('organization_id');

			$previous_entry = AccountEntry::where('voucher_id', $request->input('voucher_type'))->where('organization_id', $organization_id)->orderby('id','desc')->first();

			//$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $voucher_master->starting_value;
			
			$vou_restart_value = AccountVoucher::select('restart')->where('id',$request->voucher_type)->first();
				//dd($vou_restart_value->restart);
			   if($vou_restart_value->restart == 0)
    		  {
    		      $gen_no=($getGen_no)?$getGen_no:$transaction_type->starting_value;
    		      Log::info("TransactionController->create :- after if Custom::gen_no - ".$gen_no);
    		  }
    		  else
    		  {
    		       $gen_no=($vou_restart_value->restart == 1)?$transaction_type->starting_value:$getGen_no;
    		  	    Log::info("TransactionController->create :- after Custom::gen_no - ".$gen_no);
    		  }

			$voucher_no = Custom::generate_accounts_number($voucher_master->name, $gen_no, false);
			return response()->json(['voucher_no' => $voucher_no, 'date_setting' => $voucher_master->date_setting]);
		}

		public function search_people(Request $request)
		{  
			
			$organization_id = Session::get('organization_id');
			
			if($request->input('person_type') != "") {
				$person_type_id = AccountPersonType::where('name', $request->input('person_type'))->first()->id;
			}

			$keyword = $request->input('search');

			$search = People::select(DB::raw('IF(people.person_id IS NULL, people.business_id, people.person_id) AS id'), DB::raw('IF(people.mobile_no, CONCAT(people.display_name, " - " , people.mobile_no), people.display_name) AS name'));

			$search->leftjoin('people_person_types', 'people_person_types.people_id', '=', 'people.id');
			$search->where('people.user_type', $request->input('user_type'));
			$search->where('people.organization_id', $organization_id);

			if($request->input('person_type') != "") {
				$search->where('people_person_types.person_type_id', $person_type_id);
			}
			
			if( preg_match("/[a-z]/i", $keyword) ) {
				$search->where('people.display_name', 'LIKE', "%$keyword%");
			} else {
				$search->where('people.mobile_no', 'LIKE', "$keyword%");
			}
			
			$search->groupby('people.id');
			$people = [];

			foreach ($search->get() as  $value) {
				$people[] = ["id" => $value->id, "name" => $value->name];
			}

			//$people[] = ["id" => 0, "name" => ""];
			$people[] = ["id" => "-1", "name" => ""];
			return response()->json($people);
		}


		public function get_chapter(Request $request)
		{
			$category_type = GlobalItemCategoryType::find($request->id)->name;
			$type = "";

			if($category_type == "goods") {
				$type = "0";
			} else if($category_type == "service") {
				$type = "1";
			}

			$chapter = Gst::select('chapter', DB::raw('CONCAT(chapter, " ", COALESCE( IF( LENGTH(SUBSTRING(chapter_name, 1, 30)) < 30, SUBSTRING(chapter_name, 1, 30), CONCAT( SUBSTRING(chapter_name, 1, 28), ".." ) ), "" )) AS name'))->where('type', $type)->groupby('chapter')->get();

			return response()->json($chapter);

		}

		public function search_gst(Request $request) {
                //dd($request->all());

			$gst = Gst::select(DB::raw('DISTINCT(code)'), 'description', DB::raw('IF(rate IS NULL, "", CONCAT(rate,"%")) AS rate_percent'), 'rate');
                 
			if($request->code != null) {
				$gst->where('code', 'LIKE','%'.$request->code.'%');
			}
			if($request->keyword != null) {
				$gst->where("description", 'LIKE', '%'.$request->keyword.'%');
			}
			if($request->chapter != null) {
				$gst->where('chapter', $request->chapter);
			}
			if($request->type != null) {
				$category_type = GlobalItemCategoryType::find($request->type)->name;
				$type = "";

				if($category_type == "goods") {
					$type = "0";
				} else if($category_type == "service") {
					$type = "1";
				}
				$gst->where('type', $type);
			}
			/*$gst->where('rate','!=',null);*/
			$gsts = $gst->get();
			
		   return response()->json($gsts);
		}


		public function search_gst_code(Request $request) {

			$gst = Gst::select(DB::raw('DISTINCT(code)'), 'description', DB::raw('IF(rate IS NULL, "", CONCAT(rate, "%")) AS rate_percent'), 'rate')
			->where('code', 'LIKE', $request->code)
			->first()->rate;
			
		   return response()->json($gst);
		}

		/*public function checklist_by_id(Request $request){
             $transactions=Transaction::select('id','name')->where('id',60)->get();
             dd($transactions);
		}
*/

		
}
