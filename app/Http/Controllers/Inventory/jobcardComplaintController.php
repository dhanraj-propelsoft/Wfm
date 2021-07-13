<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WmsGroupedServiceType;
use App\WmsComplaintsGroupname;
use App\InventoryItem;
use App\Transaction;
use App\HrmEmployee;
use Carbon\Carbon;
use App\Person;
use App\WmsTransactionComplaintService;
use App\JobCardComplaintService;
use Session;
use Auth;
use DB;
use Illuminate\Support\Str;
use App\Custom;

class jobcardComplaintController extends Controller
{
	public function create()
	{		
		$organization_id = Session::get('organization_id');

		$group_type=WmsGroupedServiceType::where('active',1)->select('id')->get();

		$service_items = WmsComplaintsGroupname::where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.services','Yes')->orderby('wms_complaints_groupnames.id')->select('wms_complaints_groupnames.name','wms_complaints_groupnames.id')->get();

		/*$service_items->prepend("Select Items",'');*/

		$complaint_items=WmsComplaintsGroupname::where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.complaints','Yes')->orderby('wms_complaints_groupnames.id')->pluck('wms_complaints_groupnames.name','wms_complaints_groupnames.id');
		$complaint_items->prepend("Select Items",'');
		
		$amc_items=WmsComplaintsGroupname::where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.amc_item','Yes')->orderby('wms_complaints_groupnames.id')->pluck('wms_complaints_groupnames.name','wms_complaints_groupnames.id');
		$amc_items->prepend("Select Items",''); 

	   return view('inventory.jobcard_complaint_add',compact('service_items','complaint_items','amc_items','service_uuid','group_type'));
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

		$uuid = $request->input('uuid');

		$service_items = $request->input('service_items');

		$complaint_items = $request->input('complaint_items');

		$amc_item = $request->input('amc_items');

		$more_complaint_item = $request->input('more_complaint_item');


		if($service_items != null && $complaint_items != null){

			  $first_combination_items = array_merge($service_items,$complaint_items);

		}elseif($service_items != null && $complaint_items == null){

			$first_combination_items = $service_items;

		}elseif($service_items == null && $complaint_items != null){

			$first_combination_items = $complaint_items;

		}elseif($service_items == null && $complaint_items == null){

			$first_combination_items = null;

		}

		if($amc_item != null && $first_combination_items != null){
			 $service_group_name_id = array_merge($first_combination_items,$amc_item);
		}elseif($amc_item != null && $first_combination_items == null){

			$service_group_name_id = $amc_item;

		}elseif($amc_item == null && $first_combination_items != null){

			$service_group_name_id = $first_combination_items;

		}

		$group_id = array_filter($service_group_name_id);

		if($more_complaint_item != null){
			$final_result = array_merge($group_id,$more_complaint_item);
		}else{
			$final_result =  $group_id;
		}	   

		if($final_result != null){
		   foreach ($final_result as $key => $value) {
				   $complaint_service = new WmsTransactionComplaintService;
				   $complaint_service->uuid = $uuid;
				   $complaint_service->transaction_id = '';
				   $complaint_service->service_group_name_type = $value['type'];
				   $complaint_service->service_group_name_id = $value['value'];
				   $complaint_service->additional_complaints = $value['text'];
				   $complaint_service->service_status =$value['id'];
				   $complaint_service->organization_id = $organization_id;
				   $complaint_service->created_by = Auth::user()->id;
				   $complaint_service->save();
			} 
		}
		
	}

	public function jobcard_predefind_complaint(Request $request)
	{	   
		$organization_id = Session::get('organization_id');
		$uuid = $request->input('uuid');
		$service_items = $request->input('service_items');
		$complaint_items = $request->input('complaint_items');
		$amc_item = $request->input('amc_items');
		$more_complaint_item = $request->input('more_complaint_item');


		if($service_items != null && $complaint_items != null){

			  $first_combination_items = array_merge($service_items,$complaint_items);

		}elseif($service_items != null && $complaint_items == null){

			$first_combination_items = $service_items;

		}elseif($service_items == null && $complaint_items != null){

			$first_combination_items = $complaint_items;

		}elseif($service_items == null && $complaint_items == null){

			$first_combination_items = null;

		}

		if($amc_item != null && $first_combination_items != null){
			 $service_group_name_id = array_merge($first_combination_items,$amc_item);
		}elseif($amc_item != null && $first_combination_items == null){

			$service_group_name_id = $amc_item;

		}elseif($amc_item == null && $first_combination_items != null){

			$service_group_name_id = $first_combination_items;

		}

		$group_id = array_filter($service_group_name_id);

		if($more_complaint_item != null){
			$final_result = array_merge($group_id,$more_complaint_item);
		}else{
			$final_result =  $group_id;
		}

	   

		if($final_result != null){
		   foreach ($final_result as $key => $value) {
				   $complaint_service = new JobCardComplaintService;
				   $complaint_service->uuid = $uuid;
				   $complaint_service->transaction_id = '';
				   $complaint_service->service_group_name_type = $value['type'];
				   $complaint_service->service_group_name_id = $value['value'];
				   $complaint_service->additional_complaints = $value['text'];
				   $complaint_service->service_status =$value['id'];
				   $complaint_service->organization_id = $organization_id;
				   $complaint_service->created_by = Auth::user()->id;
				   $complaint_service->save();
			} 
		}
		
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

		$service_items=WmsComplaintsGroupname::select('wms_complaints_groupnames.name','wms_complaints_groupnames.id')->where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.services','Yes')->orderby('wms_complaints_groupnames.id')->get();

		$complaint_items=WmsComplaintsGroupname::select('wms_complaints_groupnames.name','wms_complaints_groupnames.id')->where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.complaints','Yes')->orderby('wms_complaints_groupnames.id')->get();

		$amc_items=WmsComplaintsGroupname::select('wms_complaints_groupnames.name','wms_complaints_groupnames.id')->where('wms_complaints_groupnames.organization_id',$organization_id)
			->where('wms_complaints_groupnames.amc_item','Yes')->orderby('wms_complaints_groupnames.id')->get();
	   

		$service_datas = WmsTransactionComplaintService::select('wms_transaction_complaint_services.service_group_name_id as service_id','wms_complaints_groupnames.name as service','service_status as status')
			->leftjoin('wms_complaints_groupnames','wms_complaints_groupnames.id','=','wms_transaction_complaint_services.service_group_name_id')
			->where('wms_transaction_complaint_services.organization_id','=',$organization_id)
			->where('wms_transaction_complaint_services.transaction_id','=',$id)
			->where('wms_complaints_groupnames.services','=',"yes")->get();

		$complaint_datas = WmsTransactionComplaintService::select('wms_transaction_complaint_services.service_group_name_id as complaint_id','wms_complaints_groupnames.name as complaint','service_status as status')
			->leftjoin('wms_complaints_groupnames','wms_complaints_groupnames.id','=','wms_transaction_complaint_services.service_group_name_id')
			->where('wms_transaction_complaint_services.organization_id','=',$organization_id)
			->where('wms_transaction_complaint_services.transaction_id','=',$id)
			->where('wms_complaints_groupnames.complaints','=',"yes")->get();

		$more_complaint_datas = WmsTransactionComplaintService::select('wms_transaction_complaint_services.additional_complaints','wms_transaction_complaint_services.service_status as status')
		->leftjoin('wms_complaints_groupnames','wms_complaints_groupnames.id','=','wms_transaction_complaint_services.service_group_name_id')
			->where('wms_transaction_complaint_services.organization_id','=',$organization_id)
			->where('wms_transaction_complaint_services.additional_complaints','!=',null)
			->where('wms_transaction_complaint_services.transaction_id','=',$id)->get();


		$amc_datas = WmsTransactionComplaintService::select('wms_transaction_complaint_services.service_group_name_id as amc_id','wms_complaints_groupnames.name as amc','service_status as status')
			->leftjoin('wms_complaints_groupnames','wms_complaints_groupnames.id','=','wms_transaction_complaint_services.service_group_name_id')
			->where('wms_transaction_complaint_services.organization_id','=',$organization_id)
			->where('wms_transaction_complaint_services.transaction_id','=',$id)
			->where('wms_complaints_groupnames.amc_item','=',"yes")->get();

		

		$transaction_id = $id;

		$uuid = WmsTransactionComplaintService::select('uuid')->where('transaction_id',$transaction_id)->where('organization_id',$organization_id)->first();
	   


		return view('inventory.jobcard_complaint_edit', compact('service_datas','service_items','complaint_items','complaint_datas','amc_datas','amc_items','transaction_id','more_complaint_datas','uuid'));


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

	   //dd($request->all());

		$organization_id = Session::get('organization_id');

		$transaction_id = $request->input('transaction_id');

		$service_items = $request->input('service_items');

		$complaint_items = $request->input('complaint_items');

		$amc_item = $request->input('amc_items');

		$more_complaint_item = $request->input('more_complaint_item');

		if($service_items != null && $complaint_items != null){

			$first_combination_items = array_merge($service_items,$complaint_items);

		}elseif($service_items != null && $complaint_items == null){

			$first_combination_items = $service_items;

		}elseif($service_items == null && $complaint_items != null){

			$first_combination_items = $complaint_items;

		}elseif($service_items == null && $complaint_items == null){

			$first_combination_items = null;

		}

		if($amc_item != null && $first_combination_items != null){
			 $service_group_name_id = array_merge($first_combination_items,$amc_item);
		}elseif($amc_item != null && $first_combination_items == null){

			$service_group_name_id = $amc_item;

		}elseif($amc_item == null && $first_combination_items != null){

			$service_group_name_id = $first_combination_items;

		}

		$group_id = array_filter($service_group_name_id);

		if($more_complaint_item != null){
			$final_result = array_merge($group_id,$more_complaint_item);
		}else{
			$final_result = $group_id;
		}



	   $find_id = WmsTransactionComplaintService::where('transaction_id',$transaction_id)->pluck('id'); 

	   
		 if($final_result != null){
			foreach ($final_result as $key => $value) {
			   
				$complaint_service=WmsTransactionComplaintService::updateOrCreate(
				[
					'id'=> isset($find_id[$key]) ? $find_id[$key] : null,
				],[
					'uuid' => "",
					'service_group_name_type' => $value['type'],
					'service_group_name_id' => $value['value'],
					'service_status' => $value['id'],
					'additional_complaints' => $value['text'],
					'organization_id' => $organization_id,
					'transaction_id' => $transaction_id,
					'created_by' => Auth::user()->id,
				]);
			}

		 }
	   
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function get_group_values(Request $request)
	{
	   //dd($request->all());

		$organization_id = Session::get('organization_id');
		$service_group_id = $request->service_group;
		$complaints_group_id = $request->complaints_group;
		$amc_group_id= $request->amc_group;

		$person_id = Auth::user()->person_id;

		$selected_employee = HrmEmployee::select('hrm_employees.id')

		->where('hrm_employees.organization_id', $organization_id)

		->where('hrm_employees.person_id', $person_id)

		->first()->id;

		$get_items = WmsComplaintsGroupname::select('wms_complaints_groupnames.id','wms_complaints_groupnames.name AS group_name','inventory_items.name AS item_name','inventory_items.id AS item_id','inventory_items.sale_price_data','inventory_item_stocks.in_stock',DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1.00) AS quantity'),'wms_price_lists.price as segment_price','inventory_items.tax_id',DB::raw('COUNT(IF(inventory_item_batches.quantity != 0,inventory_item_batches.quantity,NULL))as count'));
		$get_items->leftjoin('wms_complaints_serviceitems','wms_complaints_serviceitems.group_id','=','wms_complaints_groupnames.id');
		$get_items->leftjoin('inventory_items','inventory_items.id','=','wms_complaints_serviceitems.item_id');
		$get_items->leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id');
		$get_items->leftjoin('inventory_item_stocks','inventory_item_stocks.id','=','inventory_items.id');
		$get_items->leftjoin('inventory_item_batches','inventory_item_batches.item_id','=','inventory_items.id');
		if($service_group_id)
		{
			foreach ($service_group_id as $key => $value) {
				if($value != null){
					$get_items->orWhere('wms_complaints_groupnames.id','=',$value);
				}    
			}
			
		}
		if($complaints_group_id)
		{
			foreach ($complaints_group_id as $key => $value) {
				if($value != null){
					$get_items->orwhere('wms_complaints_groupnames.id','=',$value);
				}
			}
		}

		if($amc_group_id)
		{
			foreach ($amc_group_id as $key => $value) {
				if($value != null){
					$get_items->orwhere('wms_complaints_groupnames.id','=',$value);
				}
			}    
		}

		$get_items->where('wms_complaints_groupnames.organization_id','=',$organization_id);

		$get_items->groupby('inventory_items.name');

		$get_items->orderby('wms_complaints_groupnames.name');

		$items = $get_items->get();

	  

		$list_price = [];

		$new_selling_price=[];

		foreach ($items as $key => $value) {

			if($items[$key]->item_id == null )
			{
				$list_price['list_price'][] = '';
				$list_price['price'][] = '';
			}

			else if($items[$key]->sale_price_data == null)
			{
				$list_price['list_price'][] = '';
				$list_price['price'][] = '';
			}

			else{
				$list = Custom::get_least_closest_date(json_decode($items[$key]->sale_price_data,true));    

				$list_price['list_price'][] = $list['list_price'];
				$list_price['price'][] = $list['price'];            
			}



			if($items[$key]->new_selling_price == null){

				$new_selling_price['new_selling_price'][] = $list_price['list_price'];

			}else{

				$new_selling_price['new_selling_price'][] = $items[$key]->new_selling_price;
			}

		}



		 return response()->json(['data' => ['items' => $items,'base_price' =>  $list_price['list_price'],'selected_employee'=>$selected_employee]]);

		
	}

}
