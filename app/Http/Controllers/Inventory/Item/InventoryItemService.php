<?php
namespace App\Http\Controllers\Inventory\Item;

use App\InventoryItem;
use Illuminate\Support\Facades\Log;
use Auth;
use Session;
use DB;

use App\Custom;
use App\VehicleRegisterDetail;
use App\VehicleSegmentDetail;
use App\VehicleVariant;
use App\WmsPriceList;

// use App\Enums\JobCardStatus;
class InventoryItemService
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(InventoryItemRepository $repo)
    {
        $this->repo = $repo;
    }

    public function findAllForProductChooser()
    {
        Log::info("InventoryItemService->findAllForProductChooser :- Inside ");

        $data = $this->repo->findAllForProductChooser();

        $data = [
            "items" => $data
        ];

        Log::info("InventoryItemService->findAllForProductChooser :- Return ");
        return [
            "status" => "SUCCESS",
            "data" => $data
        ];
    }

    public function getInventoryItemRate($item_id, $registration_id)
    {
        Log::info("InventoryItemService->getInventoryItemRate :- Inside " . $item_id . " sdsdfds " . $registration_id);

        $organization_id = Session::get('organization_id');
        /* Segment */

        $vehicle_variant_id = VehicleRegisterDetail::findOrFail($registration_id)->vehicle_variant_id;

        $variant_name = VehicleVariant::findOrFail($vehicle_variant_id)->name;

        $segments = VehicleSegmentDetail::where('vehicle_variant_name', $variant_name)->where('vehicle_variant_id', $vehicle_variant_id)->first();

        if ($segments != null) {
            $segment_id = VehicleSegmentDetail::where('vehicle_variant_name', $variant_name)->where('vehicle_variant_id', $vehicle_variant_id)->first()->vehicle_segment_id;
        }

        $wmsPriceList = WmsPriceList::where('inventory_item_id', $item_id)->first();

        /* End Segment */

        if ($wmsPriceList != null && $segments != null && $segment_id != null) {
            $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id', 'global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id', 'wms_price_lists.price as segment_price', 'inventory_item_batches.quantity AS batch_stock')->
            leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
                ->
            leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
                ->
            leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
                ->
            leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')
                ->
            leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')
                ->
            leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
                ->
            leftjoin('inventory_item_batches', 'inventory_items.id', '=', 'inventory_item_batches.item_id')
                ->
            leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id')
                ->where('inventory_items.organization_id', $organization_id)
                ->where('inventory_items.id', $item_id)
                ->
            first();
        } else {
            $item = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_category_types.id AS main_category_id', 'global_item_category_types.name AS category_name', 'inventory_item_stocks.in_stock', DB::raw('COALESCE(inventory_items.minimum_order_quantity, 1) AS minimum_order_quantity'), 'inventory_items.purchase_price', 'inventory_items.sale_price_data', 'inventory_items.tax_id', 'inventory_item_batches.quantity AS batch_stock')->
            leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
                ->
            leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
                ->
            leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
                ->
            leftjoin('global_item_category_types', 'global_item_category_types.id', '=', 'global_item_main_categories.category_type_id')
                ->
            leftjoin('inventory_item_batches', 'inventory_items.id', '=', 'inventory_item_batches.item_id')
                ->

            leftjoin('wms_price_lists', 'wms_price_lists.inventory_item_id', '=', 'inventory_items.id')
                ->
            leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
                ->leftjoin('taxes', 'taxes.id', '=', 'inventory_items.tax_id')
                ->where('inventory_items.organization_id', $organization_id)
                ->where('inventory_items.id', $item_id)
                ->first();
        }

        $sale_price = Custom::get_least_closest_date(json_decode($item->sale_price_data, true));

        $price = ($item->segment_price != null) ? $item->segment_price : $sale_price['list_price'];

        Log::info("InventoryItemService->getInventoryItemRate :- Return ");

        return $price;

    }
}
