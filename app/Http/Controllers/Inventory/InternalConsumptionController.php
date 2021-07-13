<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternalConsumption;
use App\InternalConsumptionItem;
use App\BusinessAddressType;
use App\AccountVoucher;
use App\BusinessCommunicationAddress;
use App\HrmDepartment;
use App\InventoryItem;
use App\InventoryItemStock;
use App\InventoryStore;
use App\InventoryRack;
use App\ShipmentMode;
use App\HrmEmployee;
use App\Custom;
use Carbon\Carbon;
use Session;
use DB;

class InternalConsumptionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', 'internal_consumption')->where('organization_id', $organization_id)->first();

		$internal_consumptions = InternalConsumption::select('internal_consumptions.id','internal_consumptions.order_no','internal_consumptions.date','hrm_employees.first_name')
		->leftjoin('hrm_employees','internal_consumptions.employee_id','=','hrm_employees.id')
		->where('internal_consumptions.organization_id', $organization_id)->get();

		return view('inventory.internal_consumption', compact('transaction_type', 'internal_consumptions'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$organization_id = Session::get('organization_id');

		$departments = HrmDepartment::where('organization_id', $organization_id)->pluck('name', 'id');
		$departments->prepend('Select Department', '');
	   
		$transaction_type = AccountVoucher::where('name', 'internal_consumption')->where('organization_id', $organization_id)->first();

		$previous_entry = InternalConsumption::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();

		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

		$voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))
		->where('organization_id', $organization_id)->pluck('name', 'id');
		$employees->prepend('Select Employee', '');


		$shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
		$shipment_mode->prepend('Select Shipment Mode', '');

		/*$items  = InventoryItem::select('inventory_items.*', 'inventory_items.name AS category')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
		->where('inventory_items.organization_id', $organization_id)
		->whereNotNull('inventory_item_stocks.id')
		->get();*/

		$items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->where('inventory_items.organization_id', $organization_id)
		->orderby('global_item_categories.display_name')
		->get();

		$inventory_item_stocks = InventoryItemStock::select('inventory_item_stocks.*')->where('inventory_item_stocks.id')->groupBy('inventory_item_stocks.id')->first();

		$warehouse_id = BusinessAddressType::where('name','warehouse')->first()->id;

		$warehouse = BusinessCommunicationAddress::select('business_communication_addresses.*')
		->leftjoin('organizations','business_communication_addresses.business_id','=','organizations.business_id')
		->where('address_type',$warehouse_id)
		->where('organizations.id', $organization_id)
		->pluck('placename','id');   
		$warehouse->prepend('Select Warehouse','');


		if($transaction_type == null) abort(404);              


		return view('inventory.internal_consumption_create', compact( 'voucher_no', 'employees', 'shipment_mode', 'items',  'transaction_type', 'voucher_terms','warehouse','departments','inventory_item_stocks'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'employee_id' => 'required',
		]);

		//return $request->all();

		$organization_id = Session::get('organization_id');

		$transaction_type = AccountVoucher::where('name', 'internal_consumption')->where('organization_id', $organization_id)->first();

		$previous_entry = InternalConsumption::where('transaction_type_id', $transaction_type->id)->where('organization_id', $organization_id)->orderby('id', 'desc')->first();


		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

		$internal_consumption = new InternalConsumption;
		
		$internal_consumption->order_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);
		$internal_consumption->gen_no = $gen_no;
		
		$internal_consumption->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		$internal_consumption->employee_id = $request->input('employee_id');
		$internal_consumption->reference_no = $request->input('reference_no');
		$internal_consumption->warehouse_id = $request->input('warehouse_id');
		$internal_consumption->store_id = $request->input('store_id');
		$internal_consumption->rack_id = $request->input('rack_id');      
		$internal_consumption->transaction_type_id = $transaction_type->id;
		$internal_consumption->organization_id = $organization_id;
		$internal_consumption->save();

		$item_id = $request->input('item_id');
		$description =$request->input('description');
		$quantity = $request->input('quantity');

		if(count($item_id) > 0)
		{
			for($i = 0;$i<count($item_id);$i++)
			{
				DB::table('internal_consumption_items')->insert([
					'internal_consumption_id' => $internal_consumption->id, 
					'item_id' => $item_id[$i], 
					'description' => $description[$i],
					'quantity' => $quantity[$i]
				]);

				$stock = InventoryItemStock::find($item_id[$i]);
				$stock->in_stock = ($stock->in_stock - $quantity[$i]);
				$stock->date = $internal_consumption->date;
				$data = json_decode($stock->data, true);
				$data[] = ["date" => $internal_consumption->date, "in_stock" => ($stock->in_stock - $quantity[$i])];
				$stock->data = json_encode($data);
				$stock->save();
			}
		}

		$internal_consumptions = InternalConsumption::select('internal_consumptions.id','internal_consumptions.order_no','internal_consumptions.date','hrm_employees.first_name as employee_name')
		->leftjoin('hrm_employees','internal_consumptions.employee_id','=','hrm_employees.id')
		->where('internal_consumptions.id',$internal_consumption->id)
		->where('internal_consumptions.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Internal Consumption'.config('constants.flash.added'), 'data' => ['id' => $internal_consumptions->id, 'employee_name' => $internal_consumptions->employee_name,'order_no' => $internal_consumptions->order_no,'date'=>$internal_consumptions->date]]);

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
	public function edit($id)
	{
		$organization_id = Session::get('organization_id');

		$departments = HrmDepartment::where('organization_id', $organization_id)
		->pluck('name', 'id');
		$departments->prepend('Select Department', '');
	   
		$transaction_type = AccountVoucher::where('name', 'internal_consumption')
		->where('organization_id', $organization_id)->first();

		$previous_entry = InternalConsumption::where('transaction_type_id', $transaction_type->id)
		->where('organization_id', $organization_id)
		->orderby('id', 'desc')->first();

		$gen_no = ($previous_entry != null) ? ($previous_entry->gen_no + 1) : $transaction_type->starting_value;

		$voucher_no = Custom::generate_accounts_number($transaction_type->name, $gen_no, false);

		$employees = HrmEmployee::select('hrm_employees.id', DB::raw('CONCAT(first_name, " ", COALESCE(last_name, "")) AS name'))
		->where('organization_id', $organization_id)->pluck('name', 'id');
		
		$employees->prepend('Select Employee', '');


		$shipment_mode = ShipmentMode::where('organization_id', $organization_id)->pluck('name', 'id');
		$shipment_mode->prepend('Select Shipment Mode', '');

		/*$items  = InventoryItem::select('inventory_items.*', 'inventory_categories.display_name AS category')
		->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
		->leftjoin('inventory_categories', 'inventory_categories.id', '=', 'inventory_items.category_id')
		->where('inventory_items.organization_id', $organization_id)
		->whereNotNull('inventory_item_stocks.id')
		->orderby('inventory_categories.display_name')
		->get();*/

		$items = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category', 'inventory_items.include_tax', 'inventory_items.include_purchase_tax')
		->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
		->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
		->where('inventory_items.organization_id', $organization_id)
		->orderby('global_item_categories.display_name')
		->get();


		$warehouse_id = BusinessAddressType::where('name','warehouse')->first()->id;

		$warehouse = BusinessCommunicationAddress::select('business_communication_addresses.*')
		->leftjoin('organizations','business_communication_addresses.business_id','=','organizations.business_id')
		->where('address_type',$warehouse_id)
		->where('organizations.id', $organization_id)
		->pluck('placename','id');   
		$warehouse->prepend('Select Warehouse','');

		$stores = InventoryStore::where('organization_id',$organization_id)->pluck('name','id');
		$stores->prepend('Select Store', '');
 
		$racks = InventoryRack::pluck('name','id');
		$racks->prepend('Select Rack', '');

		$internal_consumption = InternalConsumption::select('internal_consumptions.*')
		->where('internal_consumptions.organization_id',$organization_id)
		->where('id',$id)
		->first();

		$selected_department = DB::Table('hrm_employee_designation')
		->select('hrm_departments.id')
		->leftjoin('hrm_designations', 'hrm_designations.id', '=', 'hrm_employee_designation.designation_id')
		->leftjoin('hrm_departments', 'hrm_departments.id', '=', 'hrm_designations.department_id')
		->where('hrm_employee_designation.employee_id', $internal_consumption->employee_id)->first();

		/*$internal_consumption = InternalConsumption::where('organization_id', $organization_id)->first();*/
		
		$consumption_items = InternalConsumptionItem::select('internal_consumption_items.*','inventory_items.name as item_name','inventory_item_stocks.in_stock')
		->leftjoin('inventory_items','internal_consumption_items.item_id','=','inventory_items.id')
		->leftjoin('inventory_item_stocks','inventory_items.id','=','inventory_item_stocks.id')
		->where('internal_consumption_items.internal_consumption_id', $internal_consumption->id)
		->get();

		$voucher_no = $internal_consumption->order_no;       


		return view('inventory.internal_consumption_edit', compact('employees', 'items',  'transaction_type','warehouse','departments','internal_consumption','consumption_items','voucher_no','stores','racks','selected_department'));
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
		//return $request->all();
		$organization_id = Session::get('organization_id');

		$internal_consumption = InternalConsumption::findorFail($request->input('id'));
				
		$internal_consumption->date = ($request->input('date')!=null) ? Carbon::parse($request->input('date'))->format('Y-m-d') : null;
		$internal_consumption->employee_id = $request->input('employee_id');
		$internal_consumption->reference_no = $request->input('reference_no');
		$internal_consumption->warehouse_id = $request->input('warehouse_id');
		$internal_consumption->store_id = $request->input('store_id');
		$internal_consumption->rack_id = $request->input('rack_id');   
		
		$internal_consumption->save();

		$consumption_items = DB::table('internal_consumption_items')->where('internal_consumption_id', $internal_consumption->id);

		foreach ($consumption_items->get() as $consumption_item) {			

			$stock = InventoryItemStock::find($consumption_item->item_id);
			$stock->in_stock = ($stock->in_stock + $consumption_item->quantity);
			$stock->save();
			
		}

		$consumption_items->delete();

		$item_id = $request->input('item_id');
		$description =$request->input('description');
		$quantity = $request->input('quantity');

		if(count($item_id) > 0)
		{
			for($i = 0;$i<count($item_id);$i++)
			{
				DB::table('internal_consumption_items')->insert([
					'internal_consumption_id' => $internal_consumption->id, 
					'item_id' => $item_id[$i], 
					'description' => $description[$i],
					'quantity' => $quantity[$i]
				]);

				$stock = InventoryItemStock::find($item_id[$i]);
				$stock->in_stock = ($stock->in_stock - $quantity[$i]);
				$stock->date = $internal_consumption->date;
				$data = json_decode($stock->data, true);
				$data[] = ["date" => $internal_consumption->date, "in_stock" => ($stock->in_stock - $quantity[$i])];
				$stock->data = json_encode($data);
				$stock->save();
			}
		}

		$internal_consumptions = InternalConsumption::select('internal_consumptions.id','internal_consumptions.order_no','internal_consumptions.date','hrm_employees.first_name as employee_name')
		->leftjoin('hrm_employees','internal_consumptions.employee_id','=','hrm_employees.id')
		->where('internal_consumptions.id',$internal_consumption->id)
		->where('internal_consumptions.organization_id',$organization_id)->first();

		return response()->json(['status' => 1, 'message' => 'Internal Consumption'.config('constants.flash.updated'), 'data' => ['id' => $internal_consumptions->id, 'order_no' => $internal_consumptions->order_no,'date'=>$internal_consumptions->date,'employee_name' => $internal_consumptions->employee_name ]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
    {
        $internal_consumption = InternalConsumption::findOrFail($request->input('id'));
        $internal_consumption->delete();
        Custom::delete_addon('records');

        return response()->json(['status' => 1, 'message' => 'Internal Consumption'.config('constants.flash.deleted'), 'data' => []]);
    }

    public function multidestroy(Request $request)
    {
        $internal_consumptions = explode(',', $request->id);

        $internal_consumption_list = [];

        foreach ($internal_consumptions as $internal_consumption_id) {
            $internal_consumption = InternalConsumption::findOrFail($internal_consumption_id);
            $internal_consumption->delete();
            $internal_consumption_list[] = $internal_consumption_id;
            Custom::delete_addon('records');
        }

        return response()->json(['status'=>1, 'message'=>'Internal Consumption'.config('constants.flash.deleted'),'data'=>['list' => $internal_consumption_list]]);
    }
}
