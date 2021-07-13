<?php
	
	Route::get('trade-wms/dashboard', ['as' => 'trade_wms.dashboard', 'uses' => 'Tradewms\DashboardController@index']);
	
	// TRADE MODULE STARTS

	    Route::group(['prefix' => 'trade', 'middleware' => 'modules', 'modules' => 'inventory,trade'], function () {

			Route::get('dashboard', ['as' => 'trade.dashboard', 'uses' => 'Trade\DashboardController@index']);
			Route::post('dashboard_search', ['as' => 'trade.dashboard_search', 'uses' => 'Trade\DashboardController@search_index']);

			//Route::view('dashboard', 'trade.dashboard')->name('trade.dashboard');	

			Route::get('lead/status',['as'=>'lead_status.index','uses'=>'Trade\LeadStatusController@index', 'middleware' => ['permission:lead-status-list']]);

			Route::get('lead/status/create',['as'=>'lead_status.create','uses'=>'Trade\LeadStatusController@create', 'middleware' => ['permission:lead-status-create']]);
			
			Route::post('lead/status',['as'=>'lead_status.store','uses'=>'Trade\LeadStatusController@store', 'middleware' => ['permission:lead-status-create']]);

			Route::get('leadedit/{id}/edit', ['as' => 'lead_status.edit', 'uses' => 'Trade\LeadStatusController@edit', 'middleware' => ['permission:lead-status-edit']]);

			Route::patch('lead/status/update',['as'=>'lead_status.update','uses'=>'Trade\LeadStatusController@update', 'middleware' => ['permission:lead-status-edit']]);

			Route::delete('lead/status/delete',['as'=>'lead_status.destroy','uses'=>'Trade\LeadStatusController@destroy', 'middleware' => ['permission:lead-status-delete']]);

			Route::post('lead/status/status', ['as'=>'lead_status.status','uses'=>'Trade\LeadStatusController@status', 'middleware' => ['permission:lead-status-edit']]);

			Route::delete('lead/status/multidelete', ['as' => 'lead_status.multidestroy', 'uses' => 'Trade\LeadStatusController@multidestroy', 'middleware' => ['permission:lead-status-delete']]);			

			Route::post('lead/status/multiapprove', ['as'=>'lead_status.multiapprove', 'uses'=>'Trade\LeadStatusController@multiapprove', 'middleware' => ['permission:lead-status-edit']]);

               //Recivables report
             Route::get('receivables/report',['as' => 'receipt_report','uses' => 'Trade\VehicleReportController@receipt_report']);

             Route::post('report/details',['as' => 'receipt_details','uses' => 'Trade\VehicleReportController@receipt_details']);
             
           Route::get('vouchers/{id}/view', ['as' => 'vouchers.view', 'uses' => 'Trade\VehicleReportController@view_receipt_details']);

             //

			Route::get('lead/source',['as'=>'lead_source.index','uses'=>'Trade\LeadSourceController@index', 'middleware' => ['permission:lead-source-list']]);

			Route::get('lead/source/create',['as'=>'lead_source.create','uses'=>'Trade\LeadSourceController@create', 'middleware' => ['permission:lead-source-create']]);
			
			Route::post('lead/source',['as'=>'lead_source.store','uses'=>'Trade\LeadSourceController@store', 'middleware' => ['permission:lead-source-create']]);

			Route::get('lead/{id}/edit', ['as' => 'lead_source.edit', 'uses' => 'Trade\LeadSourceController@edit', 'middleware' => ['permission:lead-source-edit']]);

			Route::patch('lead/source/update',['as'=>'lead_source.update','uses'=>'Trade\LeadSourceController@update', 'middleware' => ['permission:lead-source-edit']]);

			Route::delete('lead/source/delete',['as'=>'lead_source.destroy','uses'=>'Trade\LeadSourceController@destroy', 'middleware' => ['permission:lead-source-delete']]);

			Route::post('lead/source/status', ['as'=>'lead_source.status','uses'=>'Trade\LeadSourceController@status', 'middleware' => ['permission:lead-source-edit']]);

			Route::delete('lead/source/multidelete', ['as' => 'lead_source.multidestroy', 'uses' => 'Trade\LeadSourceController@multidestroy', 'middleware' => ['permission:lead-source-delete']]);			

			Route::post('lead/source/multiapprove', ['as'=>'lead_source.multiapprove', 'uses'=>'Trade\LeadSourceController@multiapprove', 'middleware' => ['permission:lead-source-edit']]);


			Route::get('discount',['as'=>'discount.index','uses'=>'Trade\DiscountController@index', 'middleware' => ['permission:discount-list']]);

			Route::get('discount/create',['as'=>'discount.create','uses'=>'Trade\DiscountController@create', 'middleware' => ['permission:discount-create']]);

			Route::post('discount',['as'=>'discount.store','uses'=>'Trade\DiscountController@store', 'middleware' => ['permission:discount-create']]);

			Route::get('discount/{id}/edit', ['as' => 'discount.edit', 'uses' => 'Trade\DiscountController@edit', 'middleware' => ['permission:discount-edit']]);

			Route::patch('discount/update',['as'=>'discount.update','uses'=>'Trade\DiscountController@update', 'middleware' => ['permission:discount-edit']]);

			Route::delete('discount/delete',['as'=>'discount.destroy','uses'=>'Trade\DiscountController@destroy', 'middleware' => ['permission:discount-delete']]);

			Route::post('discount/status', ['as'=>'discount.status','uses'=>'Trade\DiscountController@status', 'middleware' => ['permission:discount-edit']]);

			Route::delete('discount/multidelete', ['as' => 'discount.multidestroy', 'uses' => 'Trade\DiscountController@multidestroy', 'middleware' => ['permission:discount-delete']]);			

			Route::post('discount/multiapprove', ['as'=>'discount.multiapprove', 'uses'=>'Trade\DiscountController@multiapprove', 'middleware' => ['permission:discount-edit']]);



			Route::get('warehouse/summary',['as'=>'warehouse_summary.index','uses'=>'Trade\WarehouseSummaryController@index']);

			Route::post('warehouse/summary/date', ['as'=>'warehouse_summary.date_summary', 'uses'=>'Trade\WarehouseSummaryController@date_summary']);

			

			Route::get('tax',['as'=>'tax.index','uses'=>'Trade\TaxController@index']);

			Route::get('tax/create',['as'=>'tax.create','uses'=>'Trade\TaxController@create']);

			Route::post('tax',['as'=>'tax.store','uses'=>'Trade\TaxController@store']);

			Route::get('tax/{id}/edit',['as'=>'tax.edit','uses'=>'Trade\TaxController@edit']);

			Route::patch('tax/update',['as'=>'tax.update','uses'=>'Trade\TaxController@update']);

			Route::delete('tax/delete',['as'=>'tax.destroy','uses'=>'Trade\TaxController@destroy']);

			Route::post('tax/status',['as'=>'tax.status','uses'=>'Trade\TaxController@status']);

			
			/*Route::get('people',['as'=>'people.index','uses'=>'Trade\PeopleController@index']);

			Route::get('people/create',['as'=>'people.create','uses'=>'Trade\PeopleController@create']);

			Route::post('people',['as'=>'people.store','uses'=>'Trade\PeopleController@store']);*/

			Route::get('gst-report/{module?}',['as'=>'gst_report.index','uses'=>'Trade\GstReportController@index']);

			Route::post('get-gst-report',['as'=>'gst_report.get_gst_report','uses'=>'Trade\GstReportController@get_gst_report']);

			
			
	    });

	// TRADE MODULE ENDS
