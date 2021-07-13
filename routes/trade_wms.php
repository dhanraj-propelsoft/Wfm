<?php



	// TRADE WMS MODULE STARTS

	    Route::group(['prefix' => 'trade_wms'], function () {

			Route::get('dashboard', ['as' => 'trade_wms.dashboard', 'uses' => 'Tradewms\DashboardController@index']);
			
			Route::post('dashboard_search', ['as' => 'trade_wms.dashboard_search', 'uses' => 'Tradewms\DashboardController@search_index']);

		Route::get('job_board', ['as' => 'trade_wms.job_board', 'uses' => 'Tradewms\DashboardController@job_status']);
			// job board filter
			Route::post('jobstatus_dashboard_search', ['as' => 'trade_wms.jobstatus_dashboard_search', 'uses' => 'Tradewms\DashboardController@jobstatus_dashboard_search']);
           
         	Route::get('schedule_board', ['as' => 'trade_wms.schedule_board', 'uses' => 'Tradewms\ScheduleBoardController@index']);

         
			//start customer list add


			//Route::get('customers/list', ['as' => 'cutomers.index', 'uses' => 'Tradewms\PeopleController@index']);

			//Route::get('customers/list/create', ['as' => 'cutomers.create', 'uses' => 'Tradewms\PeopleController@create']);

			//end customer list add 
			
			Route::get('jc_registered-vehicles/create', ['as' => 'jc_vehicle_registered.create', 'uses' => 'Tradewms\VehicleRegisteredController@jc_create']);
			Route::get('discount_popup.create/{sub_total?}/{box_tax_amount?}', ['as' => 'discount_popup.create', 'uses' => 'Tradewms\VehicleRegisteredController@discount_popup_create']);

			Route::get('vehicle/list', ['as' => 'vehicle_list_report', 'uses' => 'Tradewms\WmsVehicleReportController@vehicle_list_report']);

			 Route::post('get/vehicle/service/report',['as' => 'get_vehicle_service_report','uses' => 'Tradewms\WmsVehicleReportController@get_vehicle_service_report']);
            //Recivables report
             Route::get('receivables/report',['as' => 'receivables_report','uses' => 'Tradewms\WmsVehicleReportController@receivables_report']);

			 Route::post('report/details',['as' => 'get_receipt_details','uses' => 'Tradewms\WmsVehicleReportController@get_receipt_details']);

           Route::get('vouchers/{id}/view', ['as' => 'vouchers.view', 'uses' => 'Tradewms\WmsVehicleReportController@view_receipt_details']);
        
			Route::get('vehicle/maintanance-history', ['as' => 'maintanance_history_report', 'uses' => 'Tradewms\WmsVehicleReportController@maintanance_history_report']);

			Route::get('service-history', ['as' => 'service_history_report', 'uses' => 'Tradewms\WmsVehicleReportController@service_history_report']);

			Route::get('search-service-history', ['as' => 'search_service_history', 'uses' => 'Tradewms\WmsVehicleReportController@search_service_history']);

			Route::get('business-customer/report', ['as' => 'business_customer_report', 'uses' => 'Tradewms\WmsVehicleReportController@business_customer_report']);

			Route::get('item-price-list', ['as' => 'wms_item_price_list', 'uses' => 'Tradewms\WmsVehicleReportController@wms_item_price_list']);
			
			Route::post('price_list_status_approval',['as'=>'price_list_status_approval','uses'=>'Tradewms\WmsVehicleReportController@price_list_status_approval']);
			
			Route::get('inventory_item_search',['as' => 'inventory_item_search', 'uses' => 'Tradewms\WmsVehicleReportController@inventory_item_search']);
			
			Route::post('price-list_search', ['as' => 'price_list_search', 'uses' => 'Tradewms\WmsVehicleReportController@price_list_search']);
			
			Route::post('price_list_update', ['as' => 'price_list_update', 'uses' => 'Tradewms\WmsVehicleReportController@price_list_update']);
      
			Route::post('get_vehicle_datas',['as'=>'get_vehicle_datas','uses'=>'Tradewms\VehicleVariantController@get_vehicle_datas']);

			Route::post('get_vehicle_all_data',['as'=>'get_vehicle_all_data','uses'=>'Tradewms\VehicleRegisteredController@get_vehicle_all_data']);


			//Customer Promotion

			Route::get('reports/customer_promotion', ['as' => 'customer_promotion', 'uses' => 'Tradewms\WmsVehicleReportController@customer_promotion']);
 
            Route::post('reports/get_search_result',['as'=>'get_search_result','uses'=>'Tradewms\WmsVehicleReportController@get_search_result']);

            Route::post('reports/send_sms',['as'=>'customer_promotion.send_sms','uses'=>'Tradewms\WmsVehicleReportController@send_sms']);

			Route::get('vehicle/category', ['as' => 'vehicle_category.index', 'uses' => 'Tradewms\VehicleCategoryController@index']);

			Route::get('vehicle/category/create', ['as' => 'vehicle_category.create', 'uses' => 'Tradewms\VehicleCategoryController@create']);

			Route::post('vehicle/category/store', ['as' => 'vehicle_category.store', 'uses' => 'Tradewms\VehicleCategoryController@store']);

			Route::get('vehicle/category/{id}/edit', ['as' => 'vehicle_category.edit', 'uses' => 'Tradewms\VehicleCategoryController@edit']);

			Route::patch('vehicle/category/update',['as'=>'vehicle_category.update','uses'=>'Tradewms\VehicleCategoryController@update']);

			Route::delete('vehicle/category/delete', ['as' => 'vehicle_category.destroy', 'uses' => 'Tradewms\VehicleCategoryController@destroy']);

			Route::post('vehicle_category_status_approval',['as'=>'vehicle_category_status_approval','uses'=>'Tradewms\VehicleCategoryController@vehicle_category_status_approval']);

			Route::delete('vehicle/category/multidelete', ['as' => 'vehicle_category.multidestroy', 'uses' => 'Tradewms\VehicleCategoryController@multidestroy']);			

			Route::post('vehicle/category/multiapprove', ['as'=>'vehicle_category.multiapprove', 'uses'=>'Tradewms\VehicleCategoryController@multiapprove']);

			Route::post('check-vehicle-category-name', 'Tradewms\VehicleCategoryController@vehicle_category_name')->name('vehicle_category_name');


			Route::get('vehicle/make', ['as' => 'vehicle_make.index', 'uses' => 'Tradewms\VehicleMakeController@index']);

			Route::get('vehicle/make/create', ['as' => 'vehicle_make.create', 'uses' => 'Tradewms\VehicleMakeController@create']);

			Route::post('vehicle/make/store', ['as' => 'vehicle_make.store', 'uses' => 'Tradewms\VehicleMakeController@store']);

			Route::get('vehicle/make/{id}/edit', ['as' => 'vehicle_make.edit', 'uses' => 'Tradewms\VehicleMakeController@edit']);

			Route::patch('vehicle/make/update',['as'=>'vehicle_make.update','uses'=>'Tradewms\VehicleMakeController@update']);

			Route::delete('vehicle/make/delete', ['as' => 'vehicle_make.destroy', 'uses' => 'Tradewms\VehicleMakeController@destroy']);

			Route::post('vehicle_make_status_approval',['as'=>'vehicle_make_status_approval','uses'=>'Tradewms\VehicleMakeController@vehicle_make_status_approval']);

			Route::delete('vehicle/make/multidelete', ['as' => 'vehicle_make.multidestroy', 'uses' => 'Tradewms\VehicleMakeController@multidestroy']);			

			Route::post('vehicle/make/multiapprove', ['as'=>'vehicle_make.multiapprove', 'uses'=>'Tradewms\VehicleMakeController@multiapprove']);

			Route::post('check-vehicle-make-name', 'Tradewms\VehicleMakeController@vehicle_make_name')->name('vehicle_make_name');



			Route::get('vehicle/model', ['as' => 'vehicle_model.index', 'uses' => 'Tradewms\VehicleModelController@index']);

			Route::get('vehicle/model/create', ['as' => 'vehicle_model.create', 'uses' => 'Tradewms\VehicleModelController@create']);

			Route::post('vehicle/model/store', ['as' => 'vehicle_model.store', 'uses' => 'Tradewms\VehicleModelController@store']);

			Route::get('vehicle/model/{id}/edit', ['as' => 'vehicle_model.edit', 'uses' => 'Tradewms\VehicleModelController@edit']);

			Route::patch('vehicle/model/update',['as'=>'vehicle_model.update','uses'=>'Tradewms\VehicleModelController@update']);

			Route::delete('vehicle/model/delete', ['as' => 'vehicle_model.destroy', 'uses' => 'Tradewms\VehicleModelController@destroy']);

			Route::post('vehicle_model_status_approval',['as'=>'vehicle_model_status_approval','uses'=>'Tradewms\VehicleModelController@vehicle_model_status_approval']);

			Route::delete('vehicle/model/multidelete', ['as' => 'vehicle_model.multidestroy', 'uses' => 'Tradewms\VehicleModelController@multidestroy']);			

			Route::post('vehicle/model/multiapprove', ['as'=>'vehicle_model.multiapprove', 'uses'=>'Tradewms\VehicleModelController@multiapprove']);

			Route::post('check-vehicle-model-name', 'Tradewms\VehicleModelController@vehicle_model_name')->name('vehicle_model_name');




			Route::get('body/type', ['as' => 'body_type.index', 'uses' => 'Tradewms\BodyTypeController@index']);

			Route::get('body/type/create', ['as' => 'body_type.create', 'uses' => 'Tradewms\BodyTypeController@create']);

			Route::post('body/type/store', ['as' => 'body_type.store', 'uses' => 'Tradewms\BodyTypeController@store']);

			Route::get('body/type/{id}/edit', ['as' => 'body_type.edit', 'uses' => 'Tradewms\BodyTypeController@edit']);

			Route::patch('body/type/update',['as'=>'body_type.update','uses'=>'Tradewms\BodyTypeController@update']);

			Route::delete('body/type/delete', ['as' => 'body_type.destroy', 'uses' => 'Tradewms\BodyTypeController@destroy']);

			Route::post('body_type_status_approval',['as'=>'body_type_status_approval','uses'=>'Tradewms\BodyTypeController@body_type_status_approval']);

			Route::delete('body/type/multidelete', ['as' => 'body_type.multidestroy', 'uses' => 'Tradewms\BodyTypeController@multidestroy']);			

			Route::post('body/type/multiapprove', ['as'=>'body_type.multiapprove', 'uses'=>'Tradewms\BodyTypeController@multiapprove']);

			Route::post('check-body-type-name', 'Tradewms\BodyTypeController@vehicle_body_type_name')->name('vehicle_body_type_name');



			Route::get('drivetrain', ['as' => 'drivetrain.index', 'uses' => 'Tradewms\DrivetrainController@index']);

			Route::get('drivetrain/create', ['as' => 'drivetrain.create', 'uses' => 'Tradewms\DrivetrainController@create']);

			Route::post('drivetrain/store', ['as' => 'drivetrain.store', 'uses' => 'Tradewms\DrivetrainController@store']);

			Route::get('drivetrain/{id}/edit', ['as' => 'drivetrain.edit', 'uses' => 'Tradewms\DrivetrainController@edit']);

			Route::patch('drivetrain/update',['as'=>'drivetrain.update','uses'=>'Tradewms\DrivetrainController@update']);

			Route::delete('drivetrain/delete', ['as' => 'drivetrain.destroy', 'uses' => 'Tradewms\DrivetrainController@destroy']);

			Route::post('drivetrain_status_approval',['as'=>'drivetrain_status_approval','uses'=>'Tradewms\DrivetrainController@drivetrain_status_approval']);

			Route::delete('drivetrain/multidelete', ['as' => 'drivetrain.multidestroy', 'uses' => 'Tradewms\DrivetrainController@multidestroy']);			

			Route::post('drivetrain/multiapprove', ['as'=>'drivetrain.multiapprove', 'uses'=>'Tradewms\DrivetrainController@multiapprove']);

			Route::post('check-drivetrain-name', 'Tradewms\DrivetrainController@vehicle_drivetrain_name')->name('vehicle_drivetrain_name');



			Route::get('fuel-type', ['as' => 'fuel_type.index', 'uses' => 'Tradewms\FuelTypeController@index']);

			Route::get('fuel-type/create', ['as' => 'fuel_type.create', 'uses' => 'Tradewms\FuelTypeController@create']);

			Route::post('fuel-type/store', ['as' => 'fuel_type.store', 'uses' => 'Tradewms\FuelTypeController@store']);

			Route::get('fuel-type/{id}/edit', ['as' => 'fuel_type.edit', 'uses' => 'Tradewms\FuelTypeController@edit']);

			Route::patch('fuel-type/update',['as'=>'fuel_type.update','uses'=>'Tradewms\FuelTypeController@update']);

			Route::delete('fuel-type/delete', ['as' => 'fuel_type.destroy', 'uses' => 'Tradewms\FuelTypeController@destroy']);

			Route::post('fuel_type_status_approval',['as'=>'fuel_type_status_approval','uses'=>'Tradewms\FuelTypeController@fuel_type_status_approval']);

			Route::delete('fuel-type/multidelete', ['as' => 'fuel_type.multidestroy', 'uses' => 'Tradewms\FuelTypeController@multidestroy']);			

			Route::post('fuel-type/multiapprove', ['as'=>'fuel_type.multiapprove', 'uses'=>'Tradewms\FuelTypeController@multiapprove']);

			Route::post('check-fuel-type-name', 'Tradewms\FuelTypeController@vehicle_fuel_type_name')->name('vehicle_fuel_type_name');



			Route::get('no-of-wheels', ['as' => 'vehicle_wheels.index', 'uses' => 'Tradewms\VehicleWheelsController@index']);

			Route::get('no-of-wheels/create', ['as' => 'vehicle_wheels.create', 'uses' => 'Tradewms\VehicleWheelsController@create']);

			Route::post('no-of-wheels/store', ['as' => 'vehicle_wheels.store', 'uses' => 'Tradewms\VehicleWheelsController@store']);

			Route::get('no-of-wheels/{id}/edit', ['as' => 'vehicle_wheels.edit', 'uses' => 'Tradewms\VehicleWheelsController@edit']);

			Route::patch('no-of-wheels/update',['as'=>'vehicle_wheels.update','uses'=>'Tradewms\VehicleWheelsController@update']);

			Route::delete('no-of-wheels/delete', ['as' => 'vehicle_wheels.destroy', 'uses' => 'Tradewms\VehicleWheelsController@destroy']);

			Route::post('vehicle_wheels_status_approval',['as'=>'vehicle_wheels_status_approval','uses'=>'Tradewms\VehicleWheelsController@vehicle_wheels_status_approval']);

			Route::delete('no-of-wheels/multidelete', ['as' => 'vehicle_wheels.multidestroy', 'uses' => 'Tradewms\VehicleWheelsController@multidestroy']);			

			Route::post('no-of-wheels/multiapprove', ['as'=>'vehicle_wheels.multiapprove', 'uses'=>'Tradewms\VehicleWheelsController@multiapprove']);

			Route::post('check-no-of-wheels-name', 'Tradewms\VehicleWheelsController@vehicle_wheels_name')->name('vehicle_wheels_name');



			Route::get('rim/wheel', ['as' => 'vehicle_rim.index', 'uses' => 'Tradewms\VehicleRimTypeController@index']);

			Route::get('rim/wheel/create', ['as' => 'vehicle_rim.create', 'uses' => 'Tradewms\VehicleRimTypeController@create']);

			Route::post('rim/wheel/store', ['as' => 'vehicle_rim.store', 'uses' => 'Tradewms\VehicleRimTypeController@store']);

			Route::get('rim/wheel/{id}/edit', ['as' => 'vehicle_rim.edit', 'uses' => 'Tradewms\VehicleRimTypeController@edit']);

			Route::patch('rim/wheel/update',['as'=>'vehicle_rim.update','uses'=>'Tradewms\VehicleRimTypeController@update']);

			Route::delete('rim/wheel/delete', ['as' => 'vehicle_rim.destroy', 'uses' => 'Tradewms\VehicleRimTypeController@destroy']);

			Route::post('vehicle_rim_status_approval',['as'=>'vehicle_rim_status_approval','uses'=>'Tradewms\VehicleRimTypeController@vehicle_rim_status_approval']);

			Route::delete('rim/wheel/multidelete', ['as' => 'vehicle_rim.multidestroy', 'uses' => 'Tradewms\VehicleRimTypeController@multidestroy']);			

			Route::post('rim/wheel/multiapprove', ['as'=>'vehicle_rim.multiapprove', 'uses'=>'Tradewms\VehicleRimTypeController@multiapprove']);

			Route::post('check-rim/wheel-name', 'Tradewms\VehicleRimTypeController@vehicle_rim_name')->name('vehicle_rim_name');



			Route::get('tyre-size', ['as' => 'tyre_size.index', 'uses' => 'Tradewms\VehicleTyreSizeController@index']);

			Route::get('tyre-size/create', ['as' => 'tyre_size.create', 'uses' => 'Tradewms\VehicleTyreSizeController@create']);

			Route::post('tyre-size/store', ['as' => 'tyre_size.store', 'uses' => 'Tradewms\VehicleTyreSizeController@store']);

			Route::get('tyre-size/{id}/edit', ['as' => 'tyre_size.edit', 'uses' => 'Tradewms\VehicleTyreSizeController@edit']);

			Route::patch('tyre-size/update',['as'=>'tyre_size.update','uses'=>'Tradewms\VehicleTyreSizeController@update']);

			Route::delete('tyre-size/delete', ['as' => 'tyre_size.destroy', 'uses' => 'Tradewms\VehicleTyreSizeController@destroy']);

			Route::post('tyre_size_status_approval',['as'=>'tyre_size_status_approval','uses'=>'Tradewms\VehicleTyreSizeController@tyre_size_status_approval']);

			Route::delete('tyre-size/multidelete', ['as' => 'tyre_size.multidestroy', 'uses' => 'Tradewms\VehicleTyreSizeController@multidestroy']);			

			Route::post('tyre-size/multiapprove', ['as'=>'tyre_size.multiapprove', 'uses'=>'Tradewms\VehicleTyreSizeController@multiapprove']);

			Route::post('check-tyre-size-name', 'Tradewms\VehicleTyreSizeController@tyre_size_name')->name('tyre_size_name');



			Route::get('tyre-type', ['as' => 'tyre_type.index', 'uses' => 'Tradewms\VehicleTyreTypeController@index']);

			Route::get('tyre-type/create', ['as' => 'tyre_type.create', 'uses' => 'Tradewms\VehicleTyreTypeController@create']);

			Route::post('tyre-type/store', ['as' => 'tyre_type.store', 'uses' => 'Tradewms\VehicleTyreTypeController@store']);

			Route::get('tyre-type/{id}/edit', ['as' => 'tyre_type.edit', 'uses' => 'Tradewms\VehicleTyreTypeController@edit']);

			Route::patch('tyre-type/update',['as'=>'tyre_type.update','uses'=>'Tradewms\VehicleTyreTypeController@update']);

			Route::delete('tyre-type/delete', ['as' => 'tyre_type.destroy', 'uses' => 'Tradewms\VehicleTyreTypeController@destroy']);

			Route::post('tyre_type_status_approval',['as'=>'tyre_type_status_approval','uses'=>'Tradewms\VehicleTyreTypeController@tyre_type_status_approval']);

			Route::delete('tyre-type/multidelete', ['as' => 'tyre_type.multidestroy', 'uses' => 'Tradewms\VehicleTyreTypeController@multidestroy']);			

			Route::post('tyre-type/multiapprove', ['as'=>'tyre_type.multiapprove', 'uses'=>'Tradewms\VehicleTyreTypeController@multiapprove']);

			Route::post('check-tyre-type-name', 'Tradewms\VehicleTyreTypeController@tyre_type_name')->name('tyre_type_name');


			


			Route::get('service-type', ['as' => 'service_type.index', 'uses' => 'Tradewms\ServiceTypeController@index']);

			Route::get('service-type/create', ['as' => 'service_type.create', 'uses' => 'Tradewms\ServiceTypeController@create']);

			Route::post('service-type/store', ['as' => 'service_type.store', 'uses' => 'Tradewms\ServiceTypeController@store']);

			Route::get('service-type/{id}/edit', ['as' => 'service_type.edit', 'uses' => 'Tradewms\ServiceTypeController@edit']);

			Route::patch('service-type/update',['as'=>'service_type.update','uses'=>'Tradewms\ServiceTypeController@update']);

			Route::delete('service-type/delete', ['as' => 'service_type.destroy', 'uses' => 'Tradewms\ServiceTypeController@destroy']);

			Route::post('service_type_status_approval',['as'=>'service_type_status_approval','uses'=>'Tradewms\ServiceTypeController@service_type_status_approval']);

			Route::delete('service-type/multidelete', ['as' => 'service_type.multidestroy', 'uses' => 'Tradewms\ServiceTypeController@multidestroy']);			

			Route::post('service-type/multiapprove', ['as'=>'service_type.multiapprove', 'uses'=>'Tradewms\ServiceTypeController@multiapprove']);

			Route::post('check-service-type-name', 'Tradewms\ServiceTypeController@service_type_name')->name('service_type_name');

		//start vehicle permit type
			Route::get('vehicle/permit-type','Tradewms\VehiclePermitTypeController@index')->name('permit_type.index');
			
			Route::get('vehicle/permit-type/create','Tradewms\VehiclePermitTypeController@create')->name('permit_type.create');

			Route::post('check-permit-type-name', 'Tradewms\VehiclePermitTypeController@vehicle_permit_type_name')->name('vehicle_permit_type_name');

			Route::post('vehicle/permit_type/store','Tradewms\VehiclePermitTypeController@store')->name('permit_type.store');

			Route::get('permit/type/{id}/edit','Tradewms\VehiclePermitTypeController@edit')->name('permit_type.edit');

			Route::patch('vehicle/permit_type/update','Tradewms\VehiclePermitTypeController@update')->name('permit_type.update');

			Route::delete('vehicle/permit_type/delete','Tradewms\VehiclePermitTypeController@destroy')->name('permit_type.destroy');



		//end vehicle permit type

         // vehicle variant

			Route::get('vehicle/variant', ['as' => 'vehicle_variant.index', 'uses' => 'Tradewms\VehicleVariantController@index']);
			
			Route::get('vehicle/variant/pagination', ['as' => 'vehicle_variant.pagination', 'uses' => 'Tradewms\VehicleVariantController@varient_pagination']);
			
			Route::get('vehicle/variant/global_search', ['as' => 'vehicle_variant.global_search', 'uses' => 'Tradewms\VehicleVariantController@varient_global_search']);

			Route::get('vehicle/variant/create', ['as' => 'vehicle_variant.create', 'uses' => 'Tradewms\VehicleVariantController@create']);

			Route::get('vehicle/variant/version/create', ['as' => 'vehicle_variant_version.create', 'uses' => 'Tradewms\VehicleVariantController@version_create']);
			
			Route::post('vehicle/get_make_name', ['as' => 'get_make_name', 'uses' => 'Tradewms\VehicleVariantController@get_make_name']);
			

			Route::post('vehicle/variant/store', ['as' => 'vehicle_variant.store', 'uses' => 'Tradewms\VehicleVariantController@store']);
			
			Route::post('vehicle/variant/version/store', ['as' => 'vehicle_variant_version.store', 'uses' => 'Tradewms\VehicleVariantController@version_store']);

			Route::get('vehicle/variant/{id}/edit', ['as' => 'vehicle_variant.edit', 'uses' => 'Tradewms\VehicleVariantController@edit']);

			Route::patch('vehicle/variant/update',['as'=>'vehicle_variant.update','uses'=>'Tradewms\VehicleVariantController@update']);

			Route::delete('vehicle/variant/delete', ['as' => 'vehicle_variant.destroy', 'uses' => 'Tradewms\VehicleVariantController@destroy']);

			Route::post('vehicle_variant_status_approval',['as'=>'vehicle_variant_status_approval','uses'=>'Tradewms\VehicleVariantController@vehicle_variant_status_approval']);

			Route::delete('vehicle/variant/multidelete', ['as' => 'vehicle_variant.multidestroy', 'uses' => 'Tradewms\VehicleVariantController@multidestroy']);			

			Route::post('vehicle/variant/multiapprove', ['as'=>'vehicle_variant.multiapprove', 'uses'=>'Tradewms\VehicleVariantController@multiapprove']);

			Route::post('check-vehicle/variant-name', 'Tradewms\VehicleVariantController@vehicle_variant_name')->name('vehicle_variant_name');
			
			Route::post('variant-name', 'Tradewms\VehicleVariantController@variant_name')->name('variant_name');
			

			


			Route::get('vehicle/configuration', ['as' => 'vehicle_configuration.index', 'uses' => 'Tradewms\VehicleConfigurationController@index']);

			Route::get('vehicle/configuration/create', ['as' => 'vehicle_configuration.create', 'uses' => 'Tradewms\VehicleConfigurationController@create']);

			Route::post('vehicle/configuration/store', ['as' => 'vehicle_configuration.store', 'uses' => 'Tradewms\VehicleConfigurationController@store']);

			Route::get('vehicle/configuration/{id}/edit', ['as' => 'vehicle_configuration.edit', 'uses' => 'Tradewms\VehicleConfigurationController@edit']);

			Route::patch('vehicle/configuration/update',['as'=>'vehicle_configuration.update','uses'=>'Tradewms\VehicleConfigurationController@update']);

			Route::delete('vehicle/configuration/delete', ['as' => 'vehicle_configuration.destroy', 'uses' => 'Tradewms\VehicleConfigurationController@destroy']);

			

			Route::delete('vehicle/configuration/multidelete', ['as' => 'vehicle_configuration.multidestroy', 'uses' => 'Tradewms\VehicleConfigurationController@multidestroy']);			

			Route::post('vehicle/configuration/multiapprove', ['as'=>'vehicle_configuration.multiapprove', 'uses'=>'Tradewms\VehicleConfigurationController@multiapprove']);

			Route::post('check-vehicle-name', 'Tradewms\VehicleConfigurationController@vehicle_name')->name('vehicle_name');




			Route::get('registered-vehicles/list', ['as' => 'vehicle_registered.index', 'uses' => 'Tradewms\VehicleRegisteredController@index']);

			Route::get('registered-vehicles/create', ['as' => 'vehicle_registered.create', 'uses' => 'Tradewms\VehicleRegisteredController@create']);

			Route::post('registered-vehicles/store', ['as' => 'vehicle_registered.store', 'uses' => 'Tradewms\VehicleRegisteredController@store']);

			Route::get('registered-vehicles/{id}/edit', ['as' => 'vehicle_registered.edit', 'uses' => 'Tradewms\VehicleRegisteredController@edit']);

			Route::patch('registered-vehicles/update',['as'=>'vehicle_registered.update','uses'=>'Tradewms\VehicleRegisteredController@update']);

			Route::delete('registered-vehicles/delete', ['as' => 'vehicle_registered.destroy', 'uses' => 'Tradewms\VehicleRegisteredController@destroy']);

			Route::post('vehicle_status_approval/status',['as'=>'vehicle_status_approval','uses'=>'Tradewms\VehicleRegisteredController@vehicle_status_approval']);

			Route::post('vehicle/register_number',['as' => 'get_register_number','uses' => 'Tradewms\VehicleRegisteredController@get_register_number' ]);

			Route::delete('registered-vehicles/multidelete', ['as' => 'vehicle_registered.multidestroy', 'uses' => 'Tradewms\VehicleRegisteredController@multidestroy']);			

			Route::post('registered-vehicles/multiapprove', ['as'=>'vehicle_registered.multiapprove', 'uses'=>'Tradewms\VehicleRegisteredController@multiapprove']);

			Route::post('get_people_name', ['as'=>'vehicle_registered.get_people_name', 'uses'=>'Tradewms\VehicleRegisteredController@get_people_name']);

			Route::get('get_category', ['as' => 'get_vehicle_category', 'uses' => 'Tradewms\VehicleRegisteredController@get_vehicle_category']);

            /*registered vehicle spec*/
			Route::post('vehicles/vehicle_spec',['as' => 'registered_vehicle_spec','uses' => 'Tradewms\VehicleRegisteredController@registered_vehicle_spec' ]);

			Route::post('vehicles/registered_vehicle',['as' => 'registered_vehicle','uses' => 'Tradewms\VehicleRegisteredController@registered_vehicle' ]);
		    /************/	
			Route::post('get-vehicle-model-name', 'Tradewms\VehicleRegisteredController@get_vehicle_model_name')->name('get_vehicle_model_name');

			Route::post('get-vehicle-variant-name', 'Tradewms\VehicleRegisteredController@get_vehicle_variant_name')->name('get_vehicle_variant_name');

			Route::post('vehicle_register/upload_image',['as'=>'upload_vehicle_image','uses'=>'Tradewms\VehicleRegisteredController@upload_vehicle_image']);


		//job card 
			Route::get('job_card', ['as' => 'job_card.index', 'uses' => 'Tradewms\JobCardController@index']);
			
			Route::get('job_card/create', ['as' => 'job_card.create', 'uses' => 'Tradewms\JobCardController@create']);

         //Reading Factor

			Route::get('reading-factor', ['as' => 'reading_factor.index', 'uses' => 'Tradewms\WmsReadingFactorController@index']);

			Route::get('reading-factor/create', ['as' => 'reading_factor.create', 'uses' => 'Tradewms\WmsReadingFactorController@create']);

			Route::post('reading-factor/store', ['as' => 'reading_factor.store', 'uses' => 'Tradewms\WmsReadingFactorController@store']);

			Route::get('reading-factor/{id}/edit', ['as' => 'reading_factor.edit', 'uses' => 'Tradewms\WmsReadingFactorController@edit']);

			Route::patch('reading-factor/update',['as'=>'reading_factor.update','uses'=>'Tradewms\WmsReadingFactorController@update']);

			Route::delete('reading-factor/delete', ['as' => 'reading_factor.destroy', 'uses' => 'Tradewms\WmsReadingFactorController@destroy']);

			Route::post('reading_factor_status_approval',['as'=>'reading_factor_status_approval','uses'=>'Tradewms\WmsReadingFactorController@reading_factor_status_approval']);

			Route::delete('reading-factor/multidelete', ['as' => 'reading_factor.multidestroy', 'uses' => 'Tradewms\WmsReadingFactorController@multidestroy']);			

			Route::post('reading-factor/multiapprove', ['as'=>'reading_factor.multiapprove', 'uses'=>'Tradewms\WmsReadingFactorController@multiapprove']);

			Route::post('check-reading-factor-name', 'Tradewms\WmsReadingFactorController@reading_factor_name')->name('reading_factor_name');	

			//vehicle checklist
           Route::get('vehicle/checklist', ['as' => 'VehicleChecklist.index', 'uses' => 'Tradewms\VehicleChecklistController@index']);
         	
          Route::get('vehicle/checklist/create', ['as' => 'checklist.create', 'uses' => 'Tradewms\VehicleChecklistController@create']);

         Route::post('vehicle/checklist/store', ['as' => 'checklist.store', 'uses' => 'Tradewms\VehicleChecklistController@store']);

         Route::get('vehicle/checklist/{id}/edit', ['as' => 'checklist.edit', 'uses' => 'Tradewms\VehicleChecklistController@edit']);

         Route::patch('vehicle/checklist/update',['as'=>'checklist.update','uses'=>'Tradewms\VehicleChecklistController@update']);

         Route::delete('vehicle/checklist/delete', ['as' => 'checklist.destroy', 'uses' => 'Tradewms\VehicleChecklistController@destroy']);
         
         //vehicle segment
          Route::get('pricingsegment', ['as' => 'segment.index', 'uses' => 'Tradewms\VehicleSegmentController@index']);
         	
          Route::get('segment/create', ['as' => 'segment.create', 'uses' => 'Tradewms\VehicleSegmentController@create']);

         Route::post('segment/store', ['as' => 'segment.store', 'uses' => 'Tradewms\VehicleSegmentController@store']);

         Route::get('segment/{id}/edit', ['as' => 'segment.edit', 'uses' => 'Tradewms\VehicleSegmentController@edit']);

         Route::patch('segment/update',['as'=>'segment.update','uses'=>'Tradewms\VehicleSegmentController@update']);

         Route::delete('segment/delete', ['as' => 'segment.destroy', 'uses' => 'Tradewms\VehicleSegmentController@destroy']);
			
      //vehicle segment details

           Route::get('segmentdetails', ['as' => 'VehicleSegmentDetail.index', 'uses' => 'Tradewms\VehicleSegmentDetailController@index']);

           Route::patch('segmentdetails/update', ['as' => 'VehicleSegmentDetail.update', 'uses' => 'Tradewms\VehicleSegmentDetailController@update']);

      //Job Status in transaction
       Route::get('jobstatus', ['as' => 'Jobstatus.index', 'uses' => 'Tradewms\JobStatusController@index']);
         
       Route::post('statusupdate', ['as' => 'Jobstatus.status_approval', 'uses' => 'Tradewms\JobStatusController@status_approval']);

       //vehicle Specification Master


           Route::get('specification_master', ['as' => 'specification_master.index', 'uses' => 'Tradewms\VehicleSpecificationmasterController@index']);

           Route::get('specification_master_create', ['as' => 'specification_master.create', 'uses' => 'Tradewms\VehicleSpecificationmasterController@create']);

           Route::post('specification_master_store', ['as' => 'specification_master.store', 'uses' => 'Tradewms\VehicleSpecificationmasterController@store']);

           Route::get('specification_masters/{id}/edit', ['as' => 'specification_master.edit', 'uses' => 'Tradewms\VehicleSpecificationmasterController@edit']);

           Route::patch('specification_master/update', ['as' => 'specification_master.update', 'uses' => 'Tradewms\VehicleSpecificationmasterController@update']);

            Route::post('specification_master_status_approval',['as'=>'specification_master_status_approval','uses'=>'Tradewms\VehicleSpecificationmasterController@specification_master_status_approval']);

            Route::delete('specification_master_delete', ['as' => 'specification_master.destroy', 'uses' => 'Tradewms\VehicleSpecificationmasterController@destroy']);

            Route::post('check_vehicle_spec_name',['as'=>'specification_master.vehicle_spec_name','uses'=>'Tradewms\VehicleSpecificationmasterController@vehicle_spec_name']);

      // Vehicle Specification
       
           Route::get('vehicle/specification', ['as' => 'specification.index', 'uses' => 'Tradewms\VehicleSpecificationController@index']);

          // Route::get('specification_create', ['as' => 'specification.create', 'uses' => 'Tradewms\VehicleSpecificationController@create']);

           Route::post('specification_store', ['as' => 'specification.store', 'uses' => 'Tradewms\VehicleSpecificationController@store']);

            Route::post('specification_status_approval',['as'=>'specification_status_approval','uses'=>'Tradewms\VehicleSpecificationController@specification_status_approval']);

          //  Route::delete('specification/delete', ['as' => 'specification.destroy', 'uses' => 'Tradewms\VehicleSpecificationController@destroy']);

            Route::post('pricing_update',['as'=>'specification.pricing_update','uses'=>'Tradewms\VehicleSpecificationController@pricing_update']);

        //   Vehicle Specification Values

            Route::get('specification_values', ['as' => 'specification_values.index', 'uses' => 'Tradewms\VehicleSpecificationValueController@index']);

           Route::get('specification_values_create', ['as' => 'specification_values.create', 'uses' => 'Tradewms\VehicleSpecificationValueController@create']);

           Route::post('specification_values_store', ['as' => 'specification_values.store', 'uses' => 'Tradewms\VehicleSpecificationValueController@store']);

             Route::get('specification_values/{id}/edit', ['as' => 'specification_values.edit', 'uses' => 'Tradewms\VehicleSpecificationValueController@edit']);

           Route::patch('specification_values/update', ['as' => 'specification_values.update', 'uses' => 'Tradewms\VehicleSpecificationValueController@update']);

            Route::post('specification_values_status_approval',['as'=>'specification_values_status_approval','uses'=>'Tradewms\VehicleSpecificationValueController@specification_values_status_approval']);

            Route::delete('specification_values/delete', ['as' => 'specification_values.destroy', 'uses' => 'Tradewms\VehicleSpecificationValueController@destroy']);

       

		// Customer Grouping

             Route::get('customer_grouping', ['as' => 'customer_grouping.index', 'uses' => 'Tradewms\CustomerGroupingController@index']);

             Route::get('customer_grouping/create', ['as' => 'customer_grouping.create', 'uses' => 'Tradewms\CustomerGroupingController@create']);

            Route::post('customer_grouping/store', ['as' => 'customer_grouping.store', 'uses' => 'Tradewms\CustomerGroupingController@store']);

            Route::post('customer_grouping/status_approval',['as'=>'customer_grouping_status_approvel','uses'=>'Tradewms\CustomerGroupingController@customer_grouping_status_approvel']);

            Route::get('customer_grouping/{id}/edit', ['as' => 'customer_grouping.edit', 'uses' => 'Tradewms\CustomerGroupingController@edit']);

            Route::patch('customer_grouping/update', ['as' => 'customer_grouping.update', 'uses' => 'Tradewms\CustomerGroupingController@update']);

            Route::delete('customer_grouping/delete', ['as' => 'customer_grouping.destroy', 'uses' => 'Tradewms\CustomerGroupingController@destroy']);

            Route::post('check-customer_grouping', 'Tradewms\CustomerGroupingController@customer_grouping_name')->name('customer_grouping_name');

            Route::delete('customer_grouping/multidelete', ['as' => 'customer_grouping.multidestroy', 'uses' => 'Tradewms\CustomerGroupingController@multidestroy']);			

			Route::post('customer_grouping/multiapprove', ['as'=>'customer_grouping.multiapprove', 'uses'=>'Tradewms\CustomerGroupingController@multiapprove']);

			Route::post('customer_grouping/discount_search',['as' => 'discount_search', 'uses' => 'Tradewms\CustomerGroupingController@discount_search']);
			//
			 Route::get('Show/All/Reports', ['as' => 'all_reports.index', 'uses' => 'Tradewms\ReportsController@index']);
			 Route::post('get_report/details', ['as' => 'get_report_details', 'uses' => 'Tradewms\ReportsController@get_report_details']);
			 Route::post('export_excel', ['as' => 'export_report', 'uses' => 'Tradewms\ReportsController@export_report']);

			 //Home Page

		    Route::get('home_page', ['as' => 'home_page.index', 'uses' => 'Tradewms\HomePageController@index']);	 

		    Route::post('home_page/pay_advance',['as' => 'home_page.pay_advance', 'uses' => 'Tradewms\HomePageController@advace_amount']);

		     

		    //to change job card status in job card index page
		   Route::post('job_card/status',['as'=>'job_card_status.update','uses'=>'Inventory\TransactionController@job_card_status_update']);



		    // Service Grouping Master


			 Route::get('visiting_jobcard',['as'=>'visiting_jobcard','uses'=>'Tradewms\JobcardVisitingController@visiting_jobcard']);
			  // reports
			 Route::post('get_wmstable_report', ['as' => 'get_wmstable_report', 'uses' => 'Tradewms\ReportsController@get_stock_report']);


            Route::post('vehicle_register/upload_image',['as'=>'upload_vehicle_image','uses'=>'Tradewms\VehicleRegisteredController@upload_vehicle_image']);

            Route::get('today_summary',['as' => 'trade_wms.today_summary', 'uses' => 'Tradewms\ReportsController@today_summary_report']);

	    });

	// TRADE MODULE ENDS

