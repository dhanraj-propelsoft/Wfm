<?php

namespace App\Http\Controllers\Inventory\Item;

use Illuminate\Support\Facades\Log;
use Session;
use App\InventoryItem;
use App\GlobalItemCategory;
use App\GlobalItemCategoryType;
use App\GlobalItemMake;

class InventoryItemRepository implements InventoryItemRepositoryInterface
{

    public function findAllForProductChooser()
    {
        
        Log::info("InventoryItemRepository->findAllForProductChooser :- Inside ");
        
        $organization_id =  Session::get('organization_id');
        
        $itemsQuery = InventoryItem::select('inventory_items.id', 'inventory_items.name', 'global_item_categories.display_name AS category',  'global_item_main_categories.category_type_id AS category_type_id', 'inventory_item_stocks.in_stock AS in_stock', 'global_item_models.make_id','global_item_makes.display_name AS ItemMake')
        ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        ->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')
        ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
        ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
        ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', 1)
        ->orderby('global_item_categories.display_name');
        
        Log::info('InventoryItemRepository->findAllForProductChooser Items query - ' . $itemsQuery->toSql());
        
        $items = $itemsQuery->get();
        
        Log::info("InventoryItemRepository->findAllForProductChooser :- Return ");
        return $items;
    }

    public function findAllItemCategories(){
        Log::info("InventoryItemRepository->findAllItemCategories :- Inside ");

        $organization_id =  Session::get('organization_id');

        $itemCategories = GlobalItemCategory::distinct()->select('global_item_categories.id', 'global_item_categories.name', 'global_item_categories.display_name AS Itemcategory')
            ->join('global_item_models', 'global_item_categories.id', '=', 'global_item_models.category_id')
            ->join('inventory_items', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->where('inventory_items.organization_id', $organization_id)
            ->where('inventory_items.status', 1)
            ->orderby('global_item_categories.display_name')
            ->get();
        Log::info("InventoryItemRepository->findAllItemCategories :- Return ");
        return $itemCategories;
    }

    public function findAllItemCategoryType(){
        Log::info("InventoryItemRepository->findAllItemCategoryType :- Inside ");

        $itemCategoryTypes = GlobalItemCategoryType::distinct()->select('global_item_category_types.id', 'global_item_category_types.name', 'global_item_category_types.display_name')
            ->orderby('global_item_category_types.display_name')
            ->get();
        Log::info("InventoryItemRepository->findAllItemCategoryType :- Return ");
        return $itemCategoryTypes;
    }

    public function findAllItemMake(){
        Log::info("InventoryItemRepository->findAllItemMake :- Inside ");
        $organization_id =  Session::get('organization_id');
        $itemMakes = GlobalItemMake::distinct()->select('global_item_makes.id', 'global_item_makes.name', 'global_item_makes.display_name AS ItemMake')
        ->join('global_item_models', 'global_item_makes.id', '=', 'global_item_models.make_id')
        ->join('inventory_items', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
        ->where('inventory_items.organization_id', $organization_id)
        ->where('inventory_items.status', 1)
        ->orderby('global_item_makes.display_name');
        Log::info('InventoryItemRepository->findAllItemMake :-items query - ' . $itemMakes->toSql());
        $itemMakes = $itemMakes->get();
        Log::info("InventoryItemRepository->findAllItemMake :- Return ");
        return $itemMakes;

    }

}
