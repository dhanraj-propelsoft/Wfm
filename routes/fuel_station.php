<?php

	// Fuel Station MODULE STARTS

	   		Route::group(['prefix' => 'fuel_station'], function () {

			Route::get('dashboard', ['as' => 'fuel_station.dashboard', 'uses' => 'Fuel\DashboardController@index']);

			Route::get('tank', ['as' => 'tank.index', 'uses' => 'Fuel\FuelController@tank']);

			Route::delete('fuel_station/tank_multidestroy',['as'=>'fuel_station.tank_multidestroy','uses'=>'Fuel\FuelController@tank_multidestroy']);

			Route::get('tank_create', ['as' => 'tank.create', 'uses' => 'Fuel\FuelController@tank_create']);

			Route::post('tankname_check', 'Fuel\FuelController@tankname_check')->name('tankname_check');

			Route::post('tank_store', 'Fuel\FuelController@tank_store')->name('tank_store');

			Route::get('tank_edit/{id}', ['as' => 'tank_edit/{id}', 'uses' => 'Fuel\FuelController@tank_edit']);

			Route::post('tanknameedit_check', 'Fuel\FuelController@tanknameedit_check')->name('tanknameedit_check');

			Route::Post('tank_update','Fuel\FuelController@tank_update')->name('tank_update');

			Route::delete('tank/delete', ['as' => 'tank.destroy', 'uses' =>  'Fuel\FuelController@destroy']);

			Route::post('tank/status',['as'=>'tank.status','uses'=>'Fuel\FuelController@tank_status']); 

			

			Route::get('pump_mechine', ['as' => 'pumpmechine.index', 'uses' => 'Fuel\FuelController@pumpmechine']);

			Route::delete('fuel_station/pumpmechine_multidestroy',['as'=>'fuel_station.pumpmechine_multidestroy','uses'=>'Fuel\FuelController@pumpmechine_multidestroy']);


			Route::post('pumpmechine/status',['as'=>'pumpmechine.status','uses'=>'Fuel\FuelController@pumpmechine_status']); 

			Route::get('pumpmechine_create', ['as' => 'pumpmechine.create', 'uses' => 'Fuel\FuelController@pumpmechine_create']);

			Route::post('pumpmechinename_check', 'Fuel\FuelController@pumpmechinename_check')->name('pumpmechinename_check');

			Route::Post('pumpmechine_store','Fuel\FuelController@pumpmechine_store')->name('pumpmechine_store');

			Route::get('pumpmechine_edit/{id}',['as'=>'pumpmechine_edit/{id}','uses'=>'Fuel\FuelController@pumpmechine_edit']);

			Route::post('edit_pumpmechinename_check', 'Fuel\FuelController@edit_pumpmechinename_check')->name('edit_pumpmechinename_check');
			Route::Post('pumpmechine_update','Fuel\FuelController@pumpmechine_update')->name('pumpmechine_update');



			Route::get('pump', ['as' => 'pump.index', 'uses' => 'Fuel\FuelController@pump']);

			Route::delete('fuel_station/pump_multidestroy',['as'=>'fuel_station.pump_multidestroy','uses'=>'Fuel\FuelController@pump_multidestroy']);

			Route::post('pump/status',['as'=>'pump.status','uses'=>'Fuel\FuelController@pump_status']);

			Route::get('pump_create', ['as' => 'pump.create', 'uses' => 'Fuel\FuelController@pump_create']);

			Route::get('get-mechine-list/{id}', ['as' => 'get-mechine-list/{id}', 'uses' => 'Fuel\FuelController@get_mechine_list']);

			Route::post('pumpname_check', 'Fuel\FuelController@pumpname_check')->name('pumpname_check');

			Route::Post('pump_store','Fuel\FuelController@pump_store')->name('pump_store');

			Route::get('pump_edit/{id}',['as'=>'pump_edit/{id}','uses'=>'Fuel\FuelController@pump_edit']);

			Route::post('edit_pumpname_check', 'Fuel\FuelController@edit_pumpname_check')->name('edit_pumpname_check');
			Route::post('pump_update', 'Fuel\FuelController@pump_update')->name('pump_update');

			Route::get('dipreading',['as'=>'dipreading.index','uses'=>'Fuel\FuelController@dipreading_index']);

			Route::delete('fuel_station/dipreading_multidestroy',['as'=>'fuel_station.dipreading_multidestroy','uses'=>'Fuel\FuelController@dipreading_multidestroy']);

			Route::get('dipreading/create',['as'=>'dipreading.create','uses'=>'Fuel\FuelController@dipreading_create']);


			Route::post('dipreading_store', 'Fuel\FuelController@dipreading_store')->name('dipreading_store');

			Route::post('dipreading/status',['as'=>'dipreading.status','uses'=>'Fuel\FuelController@dipreading_status']);

			Route::get('get-product-list/{id}', ['as' => 'get-product-list/{id}', 'uses' => 'Fuel\FuelController@get_product_list']);

			Route::get('get_dipreading/{reading}/{tank_id}','Fuel\FuelController@get_dipreading');

			Route::get('dipreading_edit/{id}',['as'=>'dipreading_edit','uses'=>'Fuel\FuelController@dipreading_edit']);
			Route::Post('dipreading_update','Fuel\FuelController@dipreading_update')->name('dipreading_update');

			Route::get('shiftmanagement', ['as' => 'shiftmanagement.index', 'uses' => 'Fuel\FuelController@shiftmanagement_index']);

			Route::get('shiftmanagement/create',['as'=>'shift.create','uses'=>'Fuel\FuelController@shiftmanagement_create']);
			
			Route::get('get_shift/{id}', ['as' => 'get_shift/{id}', 'uses' => 'Fuel\FuelController@get_shift']);


			Route::delete('fuel_station/multi-delete',['as'=>'fuel_station.shift_multidestroy','uses'=>'Fuel\FuelController@shift_multidestroy']);

			Route::get('get-pump-list/{id}', ['as' => 'get-pump-list/{id}', 'uses' => 'Fuel\FuelController@get_pump_list']);

			Route::post('shiftstartpump_store', 'Fuel\FuelController@shiftstartpump_store')->name('shiftstartpump_store');
			
			Route::get('end_pumpshift/{id}',['as'=>'end_pumpshift','uses'=>'Fuel\FuelController@end_pumpshift_index']);

			Route::post('shiftendpump_store', 'Fuel\FuelController@shiftendpump_store')->name('shiftendpump_store');
			
			Route::post('shiftendpump_update','Fuel\FuelController@shiftendpump_update')->name('shiftendpump_update');


			Route::get('invoice', ['as' => 'fsm_invoice', 'uses' => 'Fuel\FuelController@invoice_index']);

			Route::get('invoice_create/{id}',['as'=>'invoice_create','uses'=>'Fuel\FuelController@invoice_create']);

			
			Route::get('fsm_invoice/{id}/edit/{vehicle_id}', ['as' => 'fsm_invoice.edit', 'uses' => 'Fuel\FuelController@invoice_edit']);

			Route::get('items',['as' => 'fsm_item.index','uses' => 'Fuel\ItemController@item_index']);

			Route::get('items/create',['as'=>'fsm_item.create','uses'=>'Fuel\ItemController@item_create']);

			Route::get('get_product/{id}', ['as' => 'get_product/{id}', 'uses' => 'Fuel\ItemController@getproductlist']);

			Route::post('fsm_get/category-type', ['as'=>'item.fsm_get_category_type', 'uses'=>'Fuel\ItemController@get_category_type']);

			//customer grouping
			
			Route::get('FScustomer_grouping', ['as' => 'fsm_customer_grouping.index', 'uses' => 'Fuel\CustomerGroupingController@index']);

			  Route::get('customer_grouping/create', ['as' => 'fsm_customer_grouping.create', 'uses' => 'Fuel\CustomerGroupingController@create']);
			  // adjustment

			 Route::get('adjustment',['as'=>'fsm_adjustment.index','uses' => 'Fuel\CustomerGroupingController@adjusment_index']);
			 //stackbook
			  Route::get('stackbook',['as'=>'stackbook.index','uses' => 'Fuel\CustomerGroupingController@stackbook_index']);

			   Route::get('stockbook_create',['as'=>'stockbook_create','uses' => 'Fuel\CustomerGroupingController@stackbook_create']);
			   
			   Route::post('stackbook_store', 'Fuel\CustomerGroupingController@stackbook_store')->name('stackbook_store');

			   Route::get('stockbook_edit/{id}', ['as' => 'stockbook_edit/{id}', 'uses' => 'Fuel\CustomerGroupingController@stockbook_edit']);

			   Route::post('stockbook_update','Fuel\CustomerGroupingController@stockbook_update')->name('stockbook_update');


			   Route::delete('fuel_station/stockbook_multidestroy',['as'=>'fuel_station.stockbook_multidestroy','uses'=>'Fuel\CustomerGroupingController@stockbook_multidestroy']);

			   
			   Route::get('get_product_stock/{id}', ['as' => 'get_product_stock/{id}', 'uses' => 'Fuel\CustomerGroupingController@get_product_list']);
			   //reports

			   Route::get('shifttime_vs_sales',['as'=>'shift_vs_sales','uses' => 'Fuel\ReportController@shift_vs_sales']);

			    Route::get('invoice_base_sales',['as'=>'invoice_base_sales','uses' => 'Fuel\ReportController@invoice_base_sales']);

			     Route::get('supplier_list',['as'=>'supplier_list','uses' => 'Fuel\ReportController@supplier_list']);

			      Route::get('supplier/create',['as'=>'supplier.create','uses' => 'Fuel\ReportController@supplier_list_create']);

			      //****testing adjusment**

			 Route::get('testing_adjustment',['as'=>'fsm_testing_adjustment.index','uses' => 'Fuel\TestingAdjustmentController@testingadjusment_index']);


			 Route::get('testing_adjustment/create',['as'=>'testingadjusment.create','uses' => 'Fuel\TestingAdjustmentController@testingadjusment_create']);

			 //short cut

			 Route::get('easy_way',['as'=>'easy_way.index','uses'=>'Fuel\ShortCutController@easy_way_index']);

			 Route::get('shortcut_invoice_create/{id}',['as'=>'shortcut_invoice_create','uses'=>'Fuel\ShortCutController@shortcut_invoice_create']);

			Route::get('shortcut_invoice_registernumber/{id}/{pump_id}',['as'=>'shortcut_invoice_registernumber','uses'=>'Fuel\ShortCutController@shortcut_invoice_registernumber']);

			Route::get('due_invoice_create/{id}',['as'=>'due_invoice_create','uses'=>'Fuel\ShortCutController@due_invoice_create']);
			
			 Route::get('get_product_details/{id}', ['as' => 'get_product_details/{id}', 'uses' => 'Inventory\AdjustmentController@get_product_details']);
			  Route::post('get_customer_details','Fuel\ShortCutController@get_customer_details')->name('get_customer_details');
			  Route::get('today_rate', ['as' => 'change_itemrate', 'uses' =>'Fuel\RateController@change_rate']);
			 Route::Post('fsmitem_update','Fuel\RateController@fsmitem_update')->name('fsmitem_update');
          
	    });

	// Fuel Station ENDS

?>