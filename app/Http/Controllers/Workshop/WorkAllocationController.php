<?php

namespace App\Http\Controllers\Workshop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryItem;
use App\VehicleModel;
use App\VehicleMake;
use Carbon\Carbon;
use App\Custom;
use Session;
use DB;

class WorkAllocationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$jobs = DB::table('item_model')->select('item_model.item_id', 'item_model.model_id', 'inventory_items.name AS work', 'vehicle_models.display_name', 'item_model.estimated_time', 'item_model.rate_data', 'inventory_items.include_tax', DB::raw('SUM(taxes.value) AS tax'))
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'item_model.item_id')
		->leftjoin('vehicle_models', 'vehicle_models.id', '=', 'item_model.model_id')
		->leftjoin('group_tax', 'group_tax.group_id', '=', 'inventory_items.tax_id')
		->leftjoin('taxes', 'group_tax.tax_id', '=', 'taxes.id')
		->where('inventory_items.organization_id',$organization_id)
		->whereNotNull('item_model.item_id')
		->whereNotNull('item_model.model_id')
		->where('inventory_items.status',1)
		->groupby('item_model.item_id')
		->groupby('item_model.model_id')
		->havingRaw('tax IS NOT NULL')
		->get();
		return view('workshop.work_allocation',compact('jobs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$work = InventoryItem::select('inventory_items.id','inventory_items.name','inventory_categories.category_type_id')
		->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')
		->leftjoin('inventory_category_types', 'inventory_categories.category_type_id', '=', 'inventory_category_types.id')
		->where('inventory_category_types.name', 'service')
		->where('inventory_items.organization_id', $organization_id)    
		->pluck('inventory_items.name', 'inventory_items.id');
		$work->prepend('Select Work', '');


		$make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		$selected_make = null;

		$model = ['' => 'Select Model'];

		return view('workshop.work_allocation_create',compact('make','model','selected_make','work'));
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

		//return $request->all();
		
		 if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		 }

		 $jobs = DB::table('item_model')->insertGetId([
			'model_id' => $request->input('model_id'),
			'item_id'  => $request->input('item_id'),
			'rate_data' => json_encode([["sale_price" => $request->input('sale_price'), "on_date" => $on_date]]),
			'estimated_time' => ($request->input('estimated_time') != null) ? $request->input('estimated_time') : null
		 ]);

		$work_allocation = DB::table('item_model')->select('item_model.item_id', 'item_model.model_id', 'inventory_items.name AS work', 'vehicle_models.display_name as model', 'item_model.estimated_time', 'item_model.rate_data')
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'item_model.item_id')
		->leftjoin('vehicle_models', 'vehicle_models.id', '=', 'item_model.model_id')  
		->where('item_model.item_id',$request->input('item_id'))
		->where('item_model.model_id',$request->input('model_id'))
		->where('inventory_items.organization_id', $organization_id)                
		->first();

		return response()->json(['status' => 1, 'message' => 'Work Allocation'.config('constants.flash.added'), 'data' => ['item_id' => $work_allocation->item_id, 'model_id' => $work_allocation->model_id,'model' => $work_allocation->model, 'work' => $work_allocation->work ,'estimated_time'=> $work_allocation->estimated_time ,'rate'=>$request->input('sale_price')]]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, $model_id)
	{
		$organization_id = Session::get('organization_id');

		$work = InventoryItem::select('inventory_items.id','inventory_items.name','inventory_categories.category_type_id')
		->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')
		->leftjoin('inventory_category_types', 'inventory_categories.category_type_id', '=', 'inventory_category_types.id')       
		->where('inventory_category_types.name', 'service')
		->where('inventory_items.organization_id', $organization_id)    
		->pluck('inventory_items.name', 'inventory_items.id');
		$work->prepend('Select Work', '');


		$make = VehicleMake::where('status', 1)->orWhere('organization_id', $organization_id)->pluck('display_name', 'id');
		$make->prepend('Select Make', '');

		$make_id = VehicleModel::find($model_id)->vehicle_make_id;        

		$selected_make = null;

		$model = VehicleModel::where('vehicle_make_id', $make_id)->pluck('display_name', 'id');
		$model->prepend('Select Model', '');

		$work_allocation = DB::table('item_model')->where('item_id',$id)->where('model_id',$model_id)->first();

		$sale_price = Custom::get_least_closest_date(json_decode($work_allocation->rate_data, true));
		$price = $sale_price['price'];

		 $date = Carbon::parse($sale_price['date'])->format('d-m-Y');

		if(!$work_allocation) abort(403);
		
		return view('workshop.work_allocation_edit',compact('work_allocation','make','model','selected_make','work','price','date','make_id','id','model_id'));
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
		$organization_id = Session::get('organization_id'); 

		if($request->input('on_date') != null) {
			$on_date = Carbon::parse($request->input('on_date'))->format('Y-m-d');
		 } else {
			$on_date = date('Y-m-d');
		 }

		DB::table('item_model')->where('item_id',$request->input('id'))
		->where('model_id',$request->input('model'))->delete();

		$jobs = DB::table('item_model')->insertGetId([
			'item_id'  => $request->input('item_id'),
			'model_id' => $request->input('model_id'),
			'rate_data' => json_encode([["sale_price" => $request->input('sale_price'), "on_date" => $on_date]]),
			'estimated_time' => ($request->input('estimated_time') != null) ? $request->input('estimated_time') : null
		]);


		$work_allocation = DB::table('item_model')->select('item_model.item_id', 'item_model.model_id', 'inventory_items.name AS work', 'vehicle_models.display_name as model', 'item_model.estimated_time', 'item_model.rate_data')
		->leftjoin('inventory_items', 'inventory_items.id', '=', 'item_model.item_id')
		->leftjoin('vehicle_models', 'vehicle_models.id', '=', 'item_model.model_id')  
		->where('item_model.item_id',$request->input('item_id'))
		->where('item_model.model_id',$request->input('model_id'))
		->where('inventory_items.organization_id', $organization_id)
		->first();

		return response()->json(['status' => 1, 'message' => 'Work Allocation'.config('constants.flash.updated'), 'data' => ['item_id' => $work_allocation->item_id, 'model_id' => $work_allocation->model_id, 'model' => $work_allocation->model, 'work' => $work_allocation->work ,'estimated_time'=> $work_allocation->estimated_time ,'rate'=>$request->input('sale_price')]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		DB::table('item_model')->where('item_id',$request->input('item_id'))
		->where('model_id',$request->input('model_id'))->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Work Allocation'.config('constants.flash.deleted'), 'data' => []]);
	}

	public function multidestroy(Request $request)
	{
		$item = explode(',', $request->item);

		$model = explode(',', $request->model);

		$workallocation_list = [];

		for($i=0; $i < count($item); $i++) {
			DB::table('item_model')->where('item_id',$item[$i])
		->where('model_id',$model[$i])->delete();
			$workallocation_list[] = ['item' => $item[$i], 'model' =>  $model[$i]];
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Work Allocation'.config('constants.flash.deleted'),'data'=>['list' => $workallocation_list]]);
	}
}
