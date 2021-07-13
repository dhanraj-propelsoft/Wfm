<?php

	// Inventory MODULE STARTS

		Route::group(['prefix' => 'workshop', 'middleware' => 'modules', 'modules' => 'workshop'], function () {

		Route::view('dashboard', 'workshop.dashboard')->name('workshop.dashboard');

		Route::get('service-type',['as'=>'service_type.index','uses'=>'Workshop\ServiceTypeController@index']);

		Route::get('model',['as'=>'vehicle_model.index','uses'=>'Workshop\VehicleModelController@index']);

		Route::get('item-type/{category}',['as'=>'division.index','uses'=>'Inventory\CategoryController@index']);

		Route::get('type/{item}',['as'=>'work.index','uses'=>'Inventory\ItemController@index']);

		Route::get('group-type/{item_group}',['as'=>'work_group.index','uses'=>'Inventory\ItemGroupController@index']);



		Route::get('transaction/{type}/{job}',['as'=>'job_estimation.index','uses'=>'Inventory\TransactionController@index']);

		Route::get('transaction/{type}/{job}/create',['as'=>'job_estimation.create','uses'=>'Inventory\TransactionController@create']);

		Route::get('transaction/{id}/{job}/edit',['as'=>'job_estimation.edit','uses'=>'Inventory\TransactionController@edit']);

		Route::get('jobs',['as'=>'jobs.index','uses'=>'Workshop\JobController@index']);


		Route::get('job-allocation',['as'=>'work_allocation.index','uses'=>'Workshop\WorkAllocationController@index']);

		Route::get('job-allocation/create',['as'=>'work_allocation.create','uses'=>'Workshop\WorkAllocationController@create']);

		Route::post('job-allocation',['as'=>'work_allocation.store','uses'=>'Workshop\WorkAllocationController@store']);

		Route::get('job-allocation/{id}/{model}/edit',['as'=>'work_allocation.edit','uses'=>'Workshop\WorkAllocationController@edit']);

		Route::patch('job-allocation',['as'=>'work_allocation.update','uses'=>'Workshop\WorkAllocationController@update']);

		Route::delete('job-allocation/delete',['as'=>'work_allocation.destroy','uses'=>'Workshop\WorkAllocationController@destroy']);

		Route::delete('job-allocation/multidelete',['as'=>'work_allocation.multidestroy','uses'=>'Workshop\WorkAllocationController@multidestroy']);

		Route::get('vehicles',['as'=>'vehicles.index','uses'=>'Workshop\VehicleController@index']);

	});
	
// Inventory MODULE ENDS


