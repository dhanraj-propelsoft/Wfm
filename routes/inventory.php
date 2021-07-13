<?php

	// Inventory MODULE STARTS


		Route::group(['prefix' => 'inventory', 'middleware' => 'modules', 'modules' => 'inventory,trade'], function () {

		Route::get('dashboard', ['as' => 'inventory.dashboard', 'uses' => 'Inventory\DashboardController@index']);

		Route::post('dashboard_search', ['as' => 'inventory.dashboard_search', 'uses' => 'Inventory\DashboardController@search_index']);

		//Route::view('dashboard', 'inventory.dashboard')->name('inventory.dashboard');		

		Route::get('rack',['as'=>'rack.index','uses'=>'Inventory\RackController@index', 'middleware' => ['permission:rack-list']]);

		Route::get('rack/create',['as'=>'rack.create','uses'=>'Inventory\RackController@create', 'middleware' => ['permission:rack-create']]);

		Route::post('rack',['as'=>'rack.store','uses'=>'Inventory\RackController@store', 'middleware' => ['permission:rack-create']]);

		Route::get('rack/{id}/edit', ['as' => 'rack.edit', 'uses' => 'Inventory\RackController@edit', 'middleware' => ['permission:rack-edit']]);

		Route::patch('rack',['as'=>'rack.update','uses'=>'Inventory\RackController@update', 'middleware' => ['permission:rack-edit']]);

		Route::delete('rack/delete',['as'=>'rack.destroy','uses'=>'Inventory\RackController@destroy', 'middleware' => ['permission:rack-delete']]);

		Route::delete('rack/multidelete',['as'=>'rack.multidestroy','uses'=>'Inventory\RackController@multidestroy', 'middleware' => ['permission:rack-delete']]);

		Route::post('rack/multiapprove', ['as'=>'rack.multiapprove', 'uses'=>'Inventory\RackController@multiapprove', 'middleware' => ['permission:rack-edit']]);

		Route::get('rack_status_approval',['as'=>'rack_status_approval','uses'=>'Inventory\RackController@rack_status_approval', 'middleware' => ['permission:rack-edit']]);

		Route::post('check/rack-name', 'Inventory\RackController@check_rack_name')->name('check_rack_name');



		Route::get('stores',['as'=>'stores.index','uses'=>'Inventory\StoreController@index', 'middleware' => ['permission:stores-list']]);

		Route::get('stores/create',['as'=>'stores.create','uses'=>'Inventory\StoreController@create', 'middleware' => ['permission:stores-create']]);

		Route::post('stores',['as'=>'stores.store','uses'=>'Inventory\StoreController@store', 'middleware' => ['permission:stores-create']]);

		Route::get('stores/{id}/edit', ['as' => 'stores.edit', 'uses' => 'Inventory\StoreController@edit', 'middleware' => ['permission:stores-edit']]);

		Route::patch('stores',['as'=>'stores.update','uses'=>'Inventory\StoreController@update', 'middleware' => ['permission:stores-edit']]);

		Route::delete('stores/delete',['as'=>'stores.destroy','uses'=>'Inventory\StoreController@destroy', 'middleware' => ['permission:stores-delete']]);

		Route::get('store_status_approval',['as'=>'store_status_approval','uses'=>'Inventory\StoreController@store_status_approval', 'middleware' => ['permission:stores-edit']]);

		Route::post('check/store-name', 'Inventory\StoreController@check_store_name')->name('check_store_name');



		Route::get('shipment/mode',['as'=>'shipment_mode.index','uses'=>'Inventory\ShipmentModeController@index', 'middleware' => ['permission:shipment-mode-list']]);

		Route::get('shipment/mode/create',['as'=>'shipment_mode.create','uses'=>'Inventory\ShipmentModeController@create', 'middleware' => ['permission:shipment-mode-create']]);

		Route::post('shipment/mode',['as'=>'shipment_mode.store','uses'=>'Inventory\ShipmentModeController@store', 'middleware' => ['permission:shipment-mode-create']]);

		Route::get('shipment/mode/{id}/edit', ['as' => 'shipment_mode.edit', 'uses' => 'Inventory\ShipmentModeController@edit', 'middleware' => ['permission:shipment-mode-edit']]);

		Route::patch('shipment/mode',['as'=>'shipment_mode.update','uses'=>'Inventory\ShipmentModeController@update', 'middleware' => ['permission:shipment-mode-edit']]);

		Route::delete('shipment/mode/delete',['as'=>'shipment_mode.destroy','uses'=>'Inventory\ShipmentModeController@destroy', 'middleware' => ['permission:shipment-mode-delete']]);

		Route::get('shipment_status_approval',['as'=>'shipment_status_approval','uses'=>'Inventory\ShipmentModeController@shipment_status_approval', 'middleware' => ['permission:shipment-mode-edit']]);

		 Route::delete('shipment/mode/multidelete', ['as' => 'shipment_mode.multidestroy', 'uses' => 'Inventory\ShipmentModeController@multidestroy', 'middleware' => ['permission:shipment-mode-delete']]);

		    Route::post('shipment/mode/multiapprove', ['as' => 'shipment_mode.multiapprove', 'uses' => 'Inventory\ShipmentModeController@multiapprove', 'middleware' => ['permission:shipment-mode-edit']]);



		Route::get('unit', ['as' => 'unit.index', 'uses' => 'Inventory\UnitController@index', 'middleware' => ['permission:unit-list']]);

			Route::get('unit/create', ['as' => 'unit.create', 'uses' => 'Inventory\UnitController@create', 'middleware' => ['permission:unit-create']]);

			Route::post('unit/store', ['as' => 'unit.store', 'uses' => 'Inventory\UnitController@store', 'middleware' => ['permission:unit-create']]);

			Route::get('unit/{id}/edit', ['as' => 'unit.edit', 'uses' => 'Inventory\UnitController@edit', 'middleware' => ['permission:unit-edit']]);

			Route::patch('unit/update',['as'=>'unit.update','uses'=>'Inventory\UnitController@update', 'middleware' => ['permission:unit-edit']]);

			Route::delete('unit/delete', ['as' => 'unit.destroy', 'uses' => 'Inventory\UnitController@destroy', 'middleware' => ['permission:unit-delete']]);

			Route::post('unit_status_approval',['as'=>'unit_status_approval','uses'=>'Inventory\UnitController@unit_status_approval', 'middleware' => ['permission:unit-edit']]);

			Route::delete('unit/multidelete', ['as' => 'unit.multidestroy', 'uses' => 'Inventory\UnitController@multidestroy', 'middleware' => ['permission:unit-delete']]);			

			Route::post('unit/multiapprove', ['as'=>'unit.multiapprove', 'uses'=>'Inventory\UnitController@multiapprove', 'middleware' => ['permission:unit-edit']]);

			Route::post('check/unit-name', 'Inventory\UnitController@check_unit_name')->name('check_unit_name');

			

		Route::get('warehouse',['as'=>'warehouse.index','uses'=>'Inventory\WarehouseController@index']);

		Route::get('warehouse/create',['as'=>'warehouse.create','uses'=>'Inventory\WarehouseController@create']);

		Route::post('warehouse',['as'=>'warehouse.store','uses'=>'Inventory\WarehouseController@store']);

		Route::get('warehouse/{id}/edit',['as'=>'warehouse.edit','uses'=>'Inventory\WarehouseController@edit']);

		Route::patch('warehouse/update',['as'=>'warehouse.update','uses'=>'Inventory\WarehouseController@update']);

		Route::delete('warehouse/delete',['as'=>'warehouse.destroy','uses'=>'Inventory\WarehouseController@destroy']);

		Route::post('warehouse/status',['as'=>'warehouse.status','uses'=>'Inventory\WarehouseController@status']);

		Route::post('check/warehouse-name', 'Inventory\WarehouseController@check_warehouse_name')->name('check_warehouse_name');



		Route::get('internal-consumption',['as'=>'internal_consumption.index','uses'=>'Inventory\InternalConsumptionController@index']);

		Route::get('internal-consumption/create',['as'=>'internal_consumption.create','uses'=>'Inventory\InternalConsumptionController@create']);

		Route::post('internal-consumption',['as'=>'internal_consumption.store','uses'=>'Inventory\InternalConsumptionController@store']);

		Route::get('internal-consumption/{id}/edit',['as'=>'internal_consumption.edit','uses'=>'Inventory\InternalConsumptionController@edit']);

		Route::patch('internal-consumption/update',['as'=>'internal_consumption.update','uses'=>'Inventory\InternalConsumptionController@update']);

		Route::delete('internal-consumption/delete',['as'=>'internal_consumption.destroy','uses'=>'Inventory\InternalConsumptionController@destroy']);

		Route::delete('internal-consumption/multidelete',['as'=>'internal_consumption.multidestroy','uses'=>'Inventory\InternalConsumptionController@multidestroy']);



		Route::get('material-receipt',['as'=>'material_receipt.index','uses'=>'Inventory\MaterialReceiptController@index']);

		Route::get('material-receipt/create',['as'=>'material_receipt.create','uses'=>'Inventory\MaterialReceiptController@create']);

		Route::post('material-receipt',['as'=>'material_receipt.store','uses'=>'Inventory\MaterialReceiptController@store']);

		Route::get('get_store', ['as' => 'get_store', 'uses' => 'Inventory\MaterialReceiptController@get_store']);

		Route::get('get_rack', ['as' => 'get_rack', 'uses' => 'Inventory\MaterialReceiptController@get_rack']);

		Route::get('material-receipt/{id}/edit',['as'=>'material_receipt.edit','uses'=>'Inventory\MaterialReceiptController@edit']);

		Route::patch('material-receipt/update',['as'=>'material_receipt.update','uses'=>'Inventory\MaterialReceiptController@update']);

		Route::delete('material-receipt/delete', ['as' => 'material_receipt.destroy', 'uses' => 'Inventory\MaterialReceiptController@destroy']);

		Route::delete('material-receipt/multidelete', ['as' => 'material_receipt.multidestroy', 'uses' => 'Inventory\MaterialReceiptController@multidestroy']);


		Route::get('contact/{name}',['as'=>'contact.index','uses'=>'Inventory\PeopleController@index']);

		Route::get('contact/create',['as'=>'contact.create','uses'=>'Inventory\PeopleController@create']);

		Route::post('contact',['as'=>'contact.store','uses'=>'Inventory\PeopleController@store']);

		Route::get('contact/{id}/edit',['as'=>'contact.edit','uses'=>'Inventory\PeopleController@edit']);

		Route::patch('contact/update',['as'=>'contact.update','uses'=>'Inventory\PeopleController@update']);

		Route::delete('contact/delete',['as'=>'contact.destroy','uses'=>'Inventory\PeopleController@destroy']);

		Route::post('contact/status',['as'=>'contact.status','uses'=>'Inventory\PeopleController@status']);

		Route::get('contact_status_approval',['as'=>'contact_status_approval','uses'=>'Inventory\PeopleController@contact_status_approval']);

		Route::delete('contact/multidelete', ['as' => 'contact.multidestroy', 'uses' => 'Inventory\PeopleController@multidestroy']);

		Route::post('contact/multiapprove', ['as' => 'contact.multiapprove', 'uses' => 'Inventory\PeopleController@multiapprove']);
		//to add user in index page
		Route::get('new/customer/{name}/data',['as' => 'new_customer_data_create','uses' => 'Inventory\PeopleController@new_customer_data_create']);
		//to store user data 
		Route::post('new/customer_data/store','Inventory\PeopleController@new_customer_data_store')->name('new_customer_data.store');
		//validate from mobile number
		Route::get('get_data/mobile_number','Inventory\PeopleController@get_data_from_mobile_number')->name('get_data_from_mobile_number');
		//validate from gst
		Route::get('get_data/gst','Inventory\PeopleController@get_data_from_gst_number')->name('get_data_from_gst_number');


		Route::get('jc-stock-report',['as'=>'jc_stock_report.index','uses'=>'Inventory\LowStockReportController@jc_index']);

		Route::get('low-stock-report',['as'=>'low_stock_report.index','uses'=>'Inventory\LowStockReportController@index']);

		Route::get('low-stock-report/create',['as'=>'low_stock_report.create','uses'=>'Inventory\LowStockReportController@create']);

		Route::post('low-stock-report',['as'=>'low_stock_report.store','uses'=>'Inventory\LowStockReportController@store']);

		Route::get('low-stock-report/{id}/edit',['as'=>'low_stock_report.edit','uses'=>'Inventory\LowStockReportController@edit']);

		Route::patch('low-stock-report/update',['as'=>'low_stock_report.update','uses'=>'Inventory\LowStockReportController@update']);

		Route::delete('low-stock-report/delete',['as'=>'low_stock_report.destroy','uses'=>'Inventory\LowStockReportController@destroy']);

		Route::post('low-stock-report/status',['as'=>'low_stock_report.status','uses'=>'Inventory\LowStockReportController@status']);
		
     //Recivables report
             Route::get('report/receipt',['as' => 'receipts_report','uses' => 'Inventory\VehicleReportController@receivables_report']);

			 Route::post('report/details',['as' => 'get_details','uses' => 'Inventory\VehicleReportController@get_details']);

           Route::get('report/{id}/view', ['as' => 'vouchers.view', 'uses' => 'Inventory\VehicleReportController@view_receipt_details']);

        //Grouped Services
        Route::get('jobcard_complaint',['as'=>'jobcard_complaint_create','uses'=>'Inventory\jobcardComplaintController@create']);

        Route::post('predefine_complaint/store','Inventory\jobcardComplaintController@store')->name('jc_complaint.store');

        Route::get('jobcard_complaint_edit_view/{id}',['as'=>'jobcard_complaint_edit','uses'=>'Inventory\jobcardComplaintController@edit']);

        Route::post('jobcard_complaint/update',['as'=>'jc_complaint.update','uses'=>'Inventory\jobcardComplaintController@update']);

        Route::post('complaints/group_values',['as'=>'get_group_values','uses'=>'Inventory\jobcardComplaintController@get_group_values']);  
         
        //to check duplicate in item name with make name
        Route::post('check/duplicate_make_name', ['as' => 'check_duplicate_make_name', 'uses' => 'Inventory\ItemController@check_duplicate_make_name']);
             
        Route::post('check/duplicate_item_name', ['as' => 'check_duplicate_item_name', 'uses' => 'Inventory\ItemController@check_duplicate_item_name']);
          //market place Report
         Route::get('market_place',['as'=>'get_market_place','uses'=>'Inventory\MarketPlaceController@index']);
         Route::get('hsn/search',['as' => 'hsn_name_search', 'uses' => 'Inventory\ItemController@hsn_name_search']);

         Route::post('gst_hsn_taxes',['as' => 'gst_hsn_taxes','uses' => 'Inventory\ItemController@gst_hsn_taxes']);
         
         Route::get('items_batch/{id}/{where}', ['as' => 'item.batch', 'uses' => 'Inventory\ItemController@batch_item']);
         
         Route::get('age_of_goods',['as'=>'age_of_goods','uses'=>'Inventory\ItemController@age_of_goods']);
         
         Route::get('today_stock_report',['as'=>'today_stock_report','uses'=>'Inventory\ItemController@today_stock_report']);
         
         Route::get('vechile_history/{vechile_no}',['as'=>'vechile_history','uses'=>'Inventory\TransactionController@vechile_history']);
         
	});
	
	// Inventory MODULE ENDS

	
