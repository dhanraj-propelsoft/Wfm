<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemCategoryType;
use App\InventoryCategory;
use App\Custom;
use Session;


class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($category)
	{
		//dd($category);
		 $organization_id = Session::get('organization_id');

		 $inventory_categories = InventoryCategory::select('inventory_categories.id', 'inventory_categories.name', 'inventory_categories.display_name', 'inventory_categories.status', 'parent.name AS parent_name', 'global_item_types.name as category_name')
		 ->leftJoin('inventory_categories AS parent', 'parent.id', '=', 'inventory_categories.parent_id')
		 ->leftJoin('global_item_types', 'global_item_types.id', '=', 'inventory_categories.category_type_id')
		 ->where('inventory_categories.organization_id', $organization_id)->get();

		 return view('inventory.category',compact('inventory_categories', 'category'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($category)
	{
		$organization_id = Session::get('organization_id');

		$inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();

		$inventory_category = InventoryCategory::where('organization_id', $organization_id)->pluck('name', 'id');

		$inventory_category->prepend('Select Parent Name', '');

		return view('inventory.category_create', compact('inventory_category', 'inventory_types', 'category'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//dd($request->all());

		$category_type = GlobalItemCategoryType::select('id')->where('name', 'service')->first()->id;
		
		$inventory_category = new InventoryCategory;
		$inventory_category->name = $request->input('name');
		$inventory_category->category_type_id = ($request->input('category_type_id') != null) ? $request->input('category_type_id') : $category_type;
		$inventory_category->display_name = $request->input('name');
		if($request->input('parent_id') != null) {
		  $inventory_category->parent_id = $request->input('parent_id');  
		}
		$inventory_category->organization_id = Session::get('organization_id');
		$inventory_category->save();

		Custom::userby($inventory_category, true);

		Custom::add_addon('records');

		return response()->json(array('status' => 1, 'message' => 'Category'.config('constants.flash.added'), 'data' => ['id' => $inventory_category->id, 'name' => $inventory_category->name, 'category_type_id' => $inventory_category->category_type_id, 'display_name' => $inventory_category->display_name, 'parent_id' =>  $inventory_category->parent_id]));

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
	public function edit($category, $id)
	{
		$organization_id = Session::get('organization_id');

		$inventory_categories = InventoryCategory::where('id', $id)->where('organization_id',$organization_id)->first();

		if(!$inventory_categories) abort(403);

		$inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();		

		$inventory_category = InventoryCategory::where('organization_id',$organization_id)->pluck('name', 'id');

		$inventory_category->prepend('Select Parent Name', '');

		return view('inventory.category_edit', compact('inventory_category', 'inventory_categories', 'inventory_types', 'category'));
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
		//return($request->all());

		$inventory_category =  InventoryCategory::findOrFail($request->input('id'));
		
		$inventory_category->name = $request->input('name');
		if($request->input('category_type_id') != null) {
			$inventory_category->category_type_id = $request->input('category_type_id');
		}
		$inventory_category->display_name = $request->input('name');
		if($request->input('parent_id') != null) {
		  $inventory_category->parent_id = $request->input('parent_id');  
		}
		else {
			 $inventory_category->parent_id = null;  
		}

		$inventory_category->organization_id = Session::get('organization_id');
		$inventory_category->save();

		Custom::userby($inventory_category, false);

		return response()->json(array('status' => 1, 'message' => 'Category'.config('constants.flash.added'), 'data' => ['id' => $inventory_category->id, 'name' => $inventory_category->name, 'category_type_id' => $inventory_category->category_type_id, 'display_name' => $inventory_category->display_name, 'parent_id' =>  $inventory_category->parent_id, 'status' => $inventory_category->status]));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$inventory_category = InventoryCategory::findOrFail($request->id);

		$inventory_category->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Category'.config('constants.flash.deleted'), 'data' =>[]]);
	}


	  public function status(Request $request)
	{
		InventoryCategory::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$inventory_categories = explode(',', $request->id);

		$category_list = [];

		foreach ($inventory_categories as $category_id) {
			$category = InventoryCategory::findOrFail($category_id);
			$category->delete();
			$category_list[] = $category_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Category'.config('constants.flash.deleted'),'data'=>['list' => $category_list]]);
	}


	public function multiapprove(Request $request)
	{
		$inventory_categories = explode(',', $request->id);

		$category_list = [];

		foreach ($inventory_categories as $category_id) {
			InventoryCategory::where('id', $category_id)->update(['status' => $request->input('status')]);;
			$category_list[] = $category_id;
		}

		return response()->json(['status'=>1, 'message'=>'Category'.config('constants.flash.updated'),'data'=>['list' => $category_list]]);
	}
}
