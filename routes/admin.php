<?php

// HRM MODULE STARTS  
//test dhana
		Route::group(['prefix' => 'admin', 'middleware' => 'modules', 'modules' => 'super_admin'], function () {

			Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);

			Route::get('organization', ['as' => 'organization.index', 'uses' => 'Admin\OrganizationController@index']);
			Route::get('entity_mapping', ['as' => 'entity_mapping.index', 'uses' => 'Admin\EntityMappingController@index']);
			//organozation create
			 Route::get('organizationcreate/{id}/edit',['as'=>'organizationcreate','uses'=>'Admin\OrganizationController@organization_create']);

			// Route::get('organization/create', ['as' => 'organization/create', 'uses' => 'Admin\ItemController@organization_create']);

			// Route::get('organization/create', ['as' => 'organization.create', 'uses' => 'Admin\OrganizationController@organization_create']);
			//--------> extend function
			Route::post('organization/extend',['as'=>'organization.extend','uses'=>'Admin\OrganizationController@organization_extend']);
		
// gst show
			Route::get('GST HSNCODE', ['as' => 'gst', 'uses' => 'Admin\OrganizationController@gst']);
				//gst edit
			 Route::get('gst_edit/{id}/edit',['as'=>'gst_edit','uses'=>'Admin\OrganizationController@gst_edit']);

			Route::get('modules', ['as' => 'modules.index', 'uses' => 'Admin\ModuleController@index']);

//bank accoun type
		Route::get('bank-account-type', ['as' => 'bank_account_type.index', 'uses' => 'Admin\BankAccountTypeController@index']);
// //-----> create page
// 		Route::get('bank-account-type', ['as' => 'create_bankaccount', 'uses' => 'Admin\BankAccountTypeController@account_create']); 



	Route::get('account-person-type', ['as' => 'account_person_type.index', 'uses' => 'Admin\PersonTypeController@index']);
	Route::get('people', ['as' => 'people.index', 'uses' => 'Admin\PeopleController@index']);
	Route::get('person', ['as' => 'person.index', 'uses' => 'Admin\PersonController@index']);
	Route::post('person/status',['as'=>'person.status','uses'=>'Admin\PersonController@status']);
// organizations new button
	Route::get('departments/create', ['as' => 'hrm_departments_admin.create', 'uses' => 'Admin\OrganizationController@create', 'middleware' => ['permission:department-create']]);
//main category
        //product/items->main category add buttons
	Route::get('main_category/create', ['as' => 'main_category_create', 'uses' => 'Admin\ItemController@main_category_create']);
			//--------> status function
	Route::post('main_category/status',['as'=>'main_category.status','uses'=>'Admin\ItemController@main_category_status']); 

	Route::post('main_category/insertion', ['as' => 'main_category.store', 'uses' => 'Admin\ItemController@main_category_store']); 
			//product/items add check dubliate
	Route::post('categoryname_create_check', 'Admin\ItemController@check_categoryname')->name('categoryname_create_check');
			
      //product/items edit buttons
			
	Route::get('main_category/{id}/edit', ['as' => 'main_category', 'uses' => 'Admin\ItemController@main_category_edit']);

			//product/items edit check dubliate
			
// for insert checking	
	Route::post('categorynamecreate_check', 'Admin\ItemController@check_categoryname_insert')->name('categorynamecreate_check');
	Route::post('categoryname_check', 'Admin\ItemController@check_categoryname_edit')->name('categoryname_check');
			//product/items edit data update 
	Route::post('main_category',['as'=>'main_category.update','uses'=>'Admin\ItemController@main_category_update']);
//category
         //product/items->category add buttons

	Route::get('categoryname', ['as' => 'category_create', 'uses' => 'Admin\ItemController@category_create']);
			//--------> status function
	Route::post('category/status',['as'=>'category.status1','uses'=>'Admin\ItemController@category_status']); 
			// store route
	Route::post('category/insertion', ['as' => 'category_store', 'uses' => 'Admin\ItemController@category_store']); 
//product/items->category edit buttons
    Route::get('category/{id}/edit',['as'=>'category','uses'=>'Admin\ItemController@category_edit']);
           //product/items edit check dubliate
    Route::post('item_categoryname_check', 'Admin\ItemController@edittimecheck_categoryname')->name('item_categoryname_check');
           //for create
	Route::post('item_categorynamecreate_check', 'Admin\ItemController@itemcheck_categorynamecreate')->name('item_categorynamecreate_check');
			//product/items category  edit data update 
	Route::post('category_update',['as'=>'category_update','uses'=>'Admin\ItemController@itemcategory_update']);
			
//type-->create
	Route::get('itemtype/create', ['as' => 'itemtype_create', 'uses' => 'Admin\ItemController@type_create']); 
			//--------> status function
	Route::post('type/status',['as'=>'type.status','uses'=>'Admin\ItemController@type_status']); 
//type---> name check it create
	Route::post('typename_check', 'Admin\ItemController@typename_checkcreate')->name('typename_check');
//--->store in new data

	Route::post('type_store', ['as' => 'type_store', 'uses' => 'Admin\ItemController@type_store']); 
//items->types edit 
    Route::get('typeedit/{id}/edit',['as'=>'admin/typeedit','uses'=>'Admin\ItemController@type_edit']);
//type---> name check it edit
    Route::post('typename_checkedit', 'Admin\ItemController@editcheck_categoryname')->name('typename_checkedit');
//type---> name update
	Route::post('type_update',['as'=>'type_update','uses'=>'Admin\ItemController@type_update']);
			//////////////////////////////////////////
//--> item make create

	Route::get('itemmake_create', ['as' => 'itemmake_create', 'uses' => 'Admin\ItemController@make_create']); 
			//--------> status function
	Route::post('make/status',['as'=>'make.status','uses'=>'Admin\ItemController@make_status']); 
//---> item make create check name
	Route::post('makename_check', 'Admin\ItemController@makename_checkcreate')->name('makename_check');
//---> item make insert new
	Route::post('itemmake_store', ['as' => 'itemmake_store', 'uses' => 'Admin\ItemController@make_store']);

//------->item make edit 

	Route::get('makeedit/{id}/edit',['as'=>'admin/makeedit','uses'=>'Admin\ItemController@make_edit']);
//------> item make chec for edit time
	Route::post('makename_checkedit', 'Admin\ItemController@editcheck_makename')->name('makename_checkedit');
//--> item  maKE ubdate
	Route::post('make_update',['as'=>'make_update','uses'=>'Admin\ItemController@make_update']);
		  //////////////////////////////////////////
//--> item as model create 
	Route::post('select_maincategory', ['as' => 'select_maincategory', 'uses' => 'Admin\ItemController@select_maincategory']);

		  Route::get('item_create', ['as' => 'item_create', 'uses' => 'Admin\ItemController@model_create']);
		  //--------> status function
			Route::post('model/status',['as'=>'model.status','uses'=>'Admin\ItemController@model_status']); 
//---->item type model admin/get-categorytype-list
		   Route::get('get-categorytype-list/{id}', ['as' => 'get-categorytype-list/{id}', 'uses' => 'Admin\ItemController@getcategorytypelist']); 
//---> item check name create
		  Route::post('check_model', 'Admin\ItemController@model_check')->name('check_model');
//---> item catch category
		  Route::get('get-category-list/{id}', ['as' => 'get-category-list/{id}', 'uses' => 'Admin\ItemController@getcategorylist']);
//---> item type catch
		 Route::get('get-type-list/{id}', ['as' => 'get-type-list/{id}', 'uses' => 'Admin\ItemController@gettypelist']); 
//--->item catche make list
		   Route::get('get-make-list/{id}', ['as' => 'get-make-list/{id}', 'uses' => 'Admin\ItemController@getmakelist']); 
		  // Route::get('Admin/get-make-list/{id}','Admin\ItemController@getmakeList');
//--->item store 
		  Route::post('model_store', ['as' => 'model_store', 'uses' => 'Admin\ItemController@model_store']);

//----> item edit
		  Route::get('model/{id}/edit',['as'=>'admin/model','uses'=>'Admin\ItemController@model_edit']);
//------> item  edit check

  Route::post('checkedit _model', 'Admin\ItemController@model_edit_check')->name('checkedit _model');

//--> item  model ubdate
		  Route::post('model_update',['as'=>'model_update','uses'=>'Admin\ItemController@model_update']);
		  
		   Route::get('get_maincategory_name_list/{id}', ['as' => 'get_maincategory_name_list/{id}', 'uses' => 'Admin\ItemController@get_maincategory_name_list']); 
		    Route::get('get_category_name_list/{id}', ['as' => 'get_category_name_list/{id}', 'uses' => 'Admin\ItemController@get_category_name_list']); 


		 

			//vehicle_masters.

			/*vehicle_type-master Start*/

			 Route::get('vehicle_type', ['as' => 'vehicle_type.index', 'uses' => 'Admin\VehicleTypeController@index']);
			 Route::get('vehicle_type_create', ['as' => 'vehicle_type.create', 'uses' => 'Admin\VehicleTypeController@create']);
			 Route::post('vehicle_type_store', ['as' => 'vehicle_type.store', 'uses' => 'Admin\VehicleTypeController@store']);
			 Route::get('vehicle/type/{id}/edit', ['as' => 'vehicle_type.edit', 'uses' => 'Admin\VehicleTypeController@edit']);
			 Route::patch('vehicle/type/update',['as'=>'vehicle_type.update','uses'=>'Admin\VehicleTypeController@update']);
			 Route::delete('vehicle/type/multidelete', ['as' => 'vehicle_type.multidestroy', 'uses' => 'Admin\VehicleTypeController@multidestroy']);
			 Route::post('vehicle/type/multiapprove', ['as'=>'vehicle_type.multiapprove', 'uses'=>'Admin\VehicleTypeController@multiapprove']);
			 Route::post('vehicle_type_status_approval',['as'=>'vehicle_type.status_approval','uses'=>'Admin\VehicleTypeController@vehicle_type_status_approval']);
			 Route::delete('vehicle/type/delete', ['as' => 'vehicle_type.destroy', 'uses' => 'Admin\VehicleTypeController@destroy']);
			 Route::post('check-vehicle-type-name', 'Admin\VehicleTypeController@vehicle_type_name')->name('vehicle_type_name');

			/*vehicle_type-master end*/


			/*vehicle_varient dropdown */
			 Route::get('get_vehicle_category', ['as' => 'vehicle_varient.get_category', 'uses' => 'Admin\VehicleMasterController@get_category']);
			 Route::get('get_vehicle_details', ['as' => 'vehicle_varient.get_details', 'uses' => 'Admin\VehicleMasterController@get_details']);
			/*end */


			Route::get('VehicleMasters_Category', ['as' => 'VehicleMasters_Category', 'uses' => 'Admin\VehicleMasterController@index']);
			//--------> status function
			Route::post('VehicleMasters_Category/status',['as'=>'vehicle_category.status','uses'=>'Admin\VehicleMasterController@vehiclecategory_status']);

			 Route::get('vehiclemasters_categorycreate', ['as' => 'vehiclemasters_categorycreate', 'uses' => 'Admin\VehicleMasterController@category_create']);
			  Route::post(' vehicle_categoryname', 'Admin\VehicleMasterController@category_checkcreate')->name('vehicle_categoryname');
			   Route::post('vehiclemasters_categorystore', ['as' => 'vehiclemasters_categorystore', 'uses' => 'Admin\VehicleMasterController@vehiclemasters_categorystore']);

//-->vehicle masters category  --> edit
			 Route::get('vehiclemasters_categoryedit/{id}/edit',['as'=>'admin/vehiclemasters_categoryedit','uses'=>'Admin\VehicleMasterController@category_edit']);

//---->vechicle masters category-->check check name edit process

			  Route::post(' vehicle_categoryname_checkedit', 'Admin\VehicleMasterController@vehiclecategory_checkedit')->name('vehicle_categoryname_checkedit');

//---->vehicle category ubdate
			   Route::post('vehiclecategory_update',['as'=>'vehiclecategory_update','uses'=>'Admin\VehicleMasterController@vehiclecategory_update']);

			
//-->vehicle masters make menu			
			Route::get('VehicleMasters_Make', ['as' => 'VehicleMasters_Make', 'uses' => 'Admin\VehicleMasterController@make']);
			//--------> status function
			Route::post('VehicleMasters_Make/status',['as'=>'vehicle_make.status','uses'=>'Admin\VehicleMasterController@vehiclemake_status']); 
//-->vehicle masters make->create process
			Route::get('VehicleMasters_Make_create', ['as' => 'VehicleMasters_Make_create', 'uses' => 'Admin\VehicleMasterController@make_create']);
//---->vechicle masters make-->check check name process
			 Route::post(' vehicle_makenamecheck', 'Admin\VehicleMasterController@make_checkcreate')->name('vehicle_makenamecheck');
//--->vehicle masters make--->insert process
			 Route::post('vehiclecreate_store', ['as' => 'vehiclecreate_store', 'uses' => 'Admin\VehicleMasterController@make_store']);
//-->vehicle masters --> edit
			 Route::get('vehicle_makeedit/{id}/edit',['as'=>'admin/vehicle_makeedit','uses'=>'Admin\VehicleMasterController@make_edit']);
//---->vechicle masters make-->check check name edit process

			  Route::post(' vehicle_makename_checkedit', 'Admin\VehicleMasterController@make_checkedit')->name('vehicle_makename_checkedit');
//---->vehicle make ubdate
			   Route::post('vehiclemake_update',['as'=>'vehiclemake_update','uses'=>'Admin\VehicleMasterController@make_update']);


//-------> vehicle masters model
			   Route::get('VehicleMasters_Model', ['as' => 'VehicleMasters_Model', 'uses' => 'Admin\VehicleMasterController@model']);
			  
			Route::post('VehicleMasters_Model/status',['as'=>'vehicle_model.status','uses'=>'Admin\VehicleMasterController@vehiclemodel_status']); 

			   Route::get('Vehicle_Modelcreate', ['as' => 'Vehicle_Modelcreate', 'uses' => 'Admin\VehicleMasterController@model_create']);

			   Route::post(' vehilclemodel_check', 'Admin\VehicleMasterController@model_checkcreate')->name('vehilclemodel_check');

			 Route::post('vehiclemodel_store', ['as' => 'vehiclemodel_store', 'uses' => 'Admin\VehicleMasterController@model_store']);


			  Route::get('vehicle_modeledit/{id}/edit',['as'=>'admin/vehicle_modeledit','uses'=>'Admin\VehicleMasterController@model_edit']);

			  Route::post(' vehicle_modelname_checkedit', 'Admin\VehicleMasterController@model_checkedit')->name('vehicle_modelname_checkedit');

			  Route::post('vehiclemodel_update',['as'=>'vehiclemodel_update','uses'=>'Admin\VehicleMasterController@model_update']);

			  Route::get('get_typecategory', ['as' => 'vehicle_model.get_typecategory', 'uses' => 'Admin\VehicleMasterController@get_typecategory']);


/*vehicle_varient Root  start*/


			Route::get('VehicleMasters_Varient', ['as' => 'VehicleMasters_Varient', 'uses' => 'Admin\VehicleMasterController@varient']);

			Route::get('VehicleMasters_Varient/Pagination', ['as' => 'VehicleMasters_Varient_pagination', 'uses' => 'Admin\VehicleMasterController@varient_pagination']);

			Route::get('VehicleMasters_Varient/global_search', ['as' => 'VehicleMasters_Varient_global', 'uses' => 'Admin\VehicleMasterController@varient_global_search']);
			
			Route::post('VehicleMasters_Variant/status',['as'=>'vehicle_variant.status','uses'=>'Admin\VehicleMasterController@vehiclevariant_status']); 


			   Route::get('vehiclemasters_varient.create', ['as' => 'vehiclemasters_varient.create', 'uses' => 'Admin\VehicleMasterController@varient_create']);

		   Route::get('vehicleget_model-list/{id}', ['as' => 'vehicleget_model-list/{id}', 'uses' => 'Admin\VehicleMasterController@getmodellist']); 

		  Route::post('vehiclecheck_varient', 'Admin\VehicleMasterController@vehiclecheck_varient')->name('vehiclecheck_varient');

		  Route::post('vehicle_varient_store', ['as' => 'vehicle_varient_store', 'uses' => 'Admin\VehicleMasterController@vehicle_varient_store']);

		  Route::get('variants/{id}/edit',['as'=>'admin/variants','uses'=>'Admin\VehicleMasterController@vehicle_variant_edit']);

		  Route::post('variant_editcheck', 'Admin\VehicleMasterController@variant_editcheck')->name('vehiclevariant_editcheck');

		 
			  Route::post('ubdate the  vehicle varient',['as'=>'variant_update','uses'=>'Admin\VehicleMasterController@variant_update']);
			 
			  Route::get('vehiclemasters_varient.addversion', ['as' => 'vehiclemasters_varient.addversion', 'uses' => 'Admin\VehicleMasterController@addversion']);
			
			   Route::post('get_make_name/{id}', ['as' => 'get_make_name/{id}', 'uses' => 'Admin\VehicleMasterController@get_make_name']);
			   
		  Route::post('version_store', ['as' => 'version_store', 'uses' => 'Admin\VehicleMasterController@version_store']);
			  

Route::get('Support_Ticket', ['as' => 'Support_Ticket.index', 'uses' => 'Admin\SupportController@index']);
Route::get('ticketshow/{id}/view', ['as' => 'ticketshow', 'uses' => 'Admin\SupportController@show']);
	Route::post('supportticket_ubdate',['as'=>'supportticket_ubdate','uses'=>'Admin\SupportController@update']);
		Route::post('select_status', ['as' => 'select_status', 'uses' => 'Admin\SupportController@select_status']);

// //---> item catch category
// 		  Route::get('get-category-list/{id}', ['as' => 'get-category-list/{id}', 'uses' => 'Admin\ItemController@getcategorylist']);
// //---> item type catch
// 		 Route::get('get-type-list/{id}', ['as' => 'get-type-list/{id}', 'uses' => 'Admin\ItemController@gettypelist']); 
// //--->item catche make list
// 		   Route::get('get-make-list/{id}', ['as' => 'get-make-list/{id}', 'uses' => 'Admin\ItemController@getmakelist']); 
// 		  // Route::get('Admin/get-make-list/{id}','Admin\ItemController@getmakeList');



// //------> item  edit check

  
			// BUSINESS NATURE ROUTES STARTS
	
			Route::get('business-nature', 'Admin\BusinessNatureController@index')->name('business_nature.index');

			Route::get('business-nature/create', 'Admin\BusinessNatureController@create')->name('business_nature.create');

			Route::post('business-nature/create', 'Admin\BusinessNatureController@store')->name('business_nature.store');

			Route::get('business-nature/{id}/edit', 'Admin\BusinessNatureController@edit')->name('business_nature.edit');

			Route::patch('business-nature', 'Admin\BusinessNatureController@update')->name('business_nature.update');

			Route::delete('business-nature/destroy', 'Admin\BusinessNatureController@destroy')->name('business_nature.destroy');

			Route::post('business-nature/approve', 'Admin\BusinessNatureController@status_approval')->name('business_status_approval');

	        Route::delete('business-nature/multidelete',  'Admin\BusinessNatureController@multidestroy')->name('business_nature.multidestroy');

	        Route::post('business-nature/multiapprove',  'Admin\BusinessNatureController@multiapprove')->name('business_nature.multiapprove');

	        Route::post('check/business-nature-name', 'Admin\BusinessNatureController@check_business_nature_name')->name('check_business_nature_name');


		// BUSINESS NATURE ROUTES ENDS

	    // BUSINESS PROFESSIONALISMS ROUTES STARTS
	
			Route::get('business-professionalism', 'Admin\BusinessProfessionalismController@index')->name('business_professionalism.index');

			Route::get('business-professionalism/create', 'Admin\BusinessProfessionalismController@create')->name('business_professionalism.create');

			Route::post('business-professionalism/create', 'Admin\BusinessProfessionalismController@store')->name('business_professionalism.store');

			Route::get('business-professionalism/{id}/edit', 'Admin\BusinessProfessionalismController@edit')->name('business_professionalism.edit');

			Route::patch('business-professionalism', 'Admin\BusinessProfessionalismController@update')->name('business_professionalism.update');

			Route::delete('business-professionalism/destroy', 'Admin\BusinessProfessionalismController@destroy')->name('business_professionalism.destroy');

			Route::post('business-professionalism/approve', 'Admin\BusinessProfessionalismController@status_approval')->name('business_professionalism_status_approval');

	        Route::delete('business-professionalism/multidelete',  'Admin\BusinessProfessionalismController@multidestroy')->name('business_professionalism.multidestroy');

	        Route::post('business-professionalism/multiapprove',  'Admin\BusinessProfessionalismController@multiapprove')->name('business_professionalism.multiapprove');

	        Route::post('check/business-professionalism-name', 'Admin\BusinessProfessionalismController@check_business_professionalism_name')->name('check_business_professionalism_name');


		// BUSINESS PROFESSIONALISMS ROUTES ENDS


			Route::get('make', ['as' => 'make.index', 'uses' => 'Admin\ItemController@make']);

			Route::get('Main_Categories', ['as' => 'main_category.index', 'uses' => 'Admin\ItemController@main_category']);

			Route::get('Categories', ['as' => 'Admin_category.index', 'uses' => 'Admin\ItemController@category']);

			Route::get('Types', ['as' => 'type.index', 'uses' => 'Admin\ItemController@type']);

			Route::get('Items', ['as' => 'model.index', 'uses' => 'Admin\ItemController@model']);

			Route::resource('banks', 'Admin\BankController');

			Route::get('sender-id', ['as' => 'sender_id', 'uses' => 'Admin\SmsController@sender_id']);

			Route::get('sms', ['as' => 'sent_sms', 'uses' => 'Admin\SmsController@sent_sms']);

			Route::get('smsLedger/{module}', ['as' => 'smsAllocation.index', 'uses' => 'Notification\Controller\NotificationController@index']);

			Route::post('getOrganizationSms', ['as' => 'smsAllocation.getOrganizationSms', 'uses' => 'Notification\Controller\NotificationController@getOrganizationSms']);


			Route::get('smsAllocationCreate', ['as' => 'smsAllocation.Create', 'uses' => 'Notification\Controller\NotificationController@create']);

			Route::post('smsAllocationStore',['as'=>'smsAllocation.Store','uses'=>'Notification\Controller\NotificationController@store']);

			Route::get('packages', ['as' => 'package.index', 'uses' => 'Admin\PackageController@index']);

			Route::get('ledgers', ['as' => 'package_ledger.index', 'uses' => 'Admin\RecordController@index']);
			Route::get('OrganizationCredits_Ledgers', ['as' => 'package_ledger.index', 'uses' => 'Admin\RecordController@index']);
	Route::post('organization/status',['as'=>'organization.status','uses'=>'Admin\OrganizationController@status']);
	Route::get('profile/business/{id}/{type?}', ['as' => 'business_profile.show', 'uses' => 'Settings\BussinessProfileController@show']);
	Route::post('people/status',['as'=>'people.status','uses'=>'Admin\PeopleController@status']);
	//organisation credits
	Route::get('credits_ledger', ['as' => 'organization_ledgers', 'uses' => 'Admin\OrganizationController@plan_ledgers']);
	Route::get('credits_sms', ['as' => 'organization_sms', 'uses' => 'Admin\OrganizationController@plan_sms']);
	Route::get('credits_memorysize', ['as' => 'organization_memorysize', 'uses' => 'Admin\OrganizationController@plan_memory_size']);
	Route::get('Credits_Employee', ['as' => 'organization_employee', 'uses' => 'Admin\OrganizationController@plan_employee']);
		
	Route::get('Credits_Customer', ['as' => 'organization_customer', 'uses' => 'Admin\OrganizationController@plan_customer']);
	Route::get('Credits_Supplier', ['as' => 'organization_supplier', 'uses' => 'Admin\OrganizationController@plan_supplier']);
	Route::get('Credits_Purchase', ['as' => 'organization_purchase', 'uses' => 'Admin\OrganizationController@plan_purchase']);

	Route::get('Credits_Invoice', ['as' => 'organization_invoice', 'uses' => 'Admin\OrganizationController@plan_invoice']);
	Route::get('Credits_GRN', ['as' => 'organization_grn', 'uses' => 'Admin\OrganizationController@plan_grn']);
	Route::get('Credits_Vehicles', ['as' => 'organization_vehicle', 'uses' => 'Admin\OrganizationController@plan_vehicle']);
	Route::get('Credits_Jobcard', ['as' => 'organization_jobcard', 'uses' => 'Admin\OrganizationController@plan_jobcard']);
	Route::get('Credits_Transaction', ['as' => 'organization_transaction', 'uses' => 'Admin\OrganizationController@plan_transaction']);
		
	Route::get('Credits_Print', ['as' => 'organization_print', 'uses' => 'Admin\OrganizationController@plan_print']);
	Route::get('Credits_Call', ['as' => 'organization_call', 'uses' => 'Admin\OrganizationController@plan_call']);
	Route::get('Exceeded', ['as' => 'organization_exceeded', 'uses' => 'Admin\OrganizationController@exceeded']);
	Route::get('registered-vehicles', ['as' => 'admin_vehicle_registered.index', 'uses' => 'Admin\VehicleRegisteredController@index']);
	Route::POST('code_search', ['as' => 'code', 'uses' => 'Admin\OrganizationController@code_search']);

	Route::POST('gst_update',['as'=>'gst_update','uses'=>'Admin\OrganizationController@gst_update']);


	Route::get('broadcast', ['as' => 'broadcast', 'uses' => 'Admin\BroadcastController@index']);
	
	Route::get('broadcast_details/{id?}',['as'=>'broadcast_details','uses'=>'Admin\BroadcastController@create']);

	Route::post('broadcast_store/{id?}', ['as' => 'broadcast_store', 'uses' => 'Admin\BroadcastController@store']); 

	Route::post('active_broadcast/status',['as'=>'active_status','uses'=>'Admin\BroadcastController@status']);

	Route::delete('Admin/broadcast_delete', ['as' => 'admin.broadcast_delete', 'uses' => 'Admin\BroadcastController@destroy']);
	Route::get('import_csv_data_to_table', ['as' => 'import_csv_data_to_table', 'uses' => 'Admin\ImportCsvDataController@import_csv_data_to_table']);

	Route::post('/uploadFile', 'Admin\ImportCsvDataController@uploadFile');
	
		});