<?php 

Route::group(['prefix' => 'vms'], function () {

	Route::get('dashboard', ['as' => 'vehicle_management.dashboard', 'uses' => 'Personal\VmsDashboardController@index']);
	Route::get('vehicle_register',['as'=>'vehicle_register','uses'=>'Personal\VehicleRegistrationController@index']);

	Route::post('vms_activestatus',['as'=>'vms_activestatus','uses'=>'Personal\VehicleRegistrationController@vms_activestatus']);

	Route::get('vehicle_registration/create', ['as' => 'vms.vehicle_registration_create', 'uses' => 'Personal\VehicleRegistrationController@register_vehicle_create']);
	
	Route::get('myvehicle_edit/{id}/edit', ['as' => 'myvehicle_edit', 'uses' =>  'Personal\VehicleRegistrationController@edit']);

	Route::post('get_myregister_number',['as'=>'get_myregister_number','uses'=> 'Personal\VehicleRegistrationController@check_registernumber']);
	
	Route::post('myvehicle_registerion/insertion', ['as' => 'myvehicle_registerion.store', 
	'uses' =>'Personal\VehicleRegistrationController@store']); 

	//update root
	Route::post('registered_vehicle_update',['as'=>'personal_update','uses'=>'Personal\VehicleRegistrationController@update']);

	
	Route::post('get_register_number',['as'=>'vms.get_register_number','uses'=>'Personal\VehicleRegistrationController@get_register_number']);

	// 
	Route::get('vehicle_register',['as'=>'vehicle_register','uses'=>'Personal\VehicleRegistrationController@index']);


	Route::get('MyComplaints',['as'=>'vehicle_complaint','uses'=>'Personal\VehicleComplaintController@index']);

	Route::get('AddMyComplaint', ['as' => 'vms.add_complaint', 'uses' => 'Personal\VehicleComplaintController@add_complaint']);

	Route::post('Mycomplaint/insertion', ['as' => 'complaint_store', 
	'uses' =>'Personal\VehicleComplaintController@store']); 

	Route::post('vms_complaint_activestatus',['as'=>'vms_complaint_activestatus','uses'=>'Personal\VehicleComplaintController@activestatus']);
	Route::get('customercomplaint_edit/{id}/edit', ['as' => 'customercomplaint_edit', 'uses' =>  'Personal\VehicleComplaintController@edit']);
	//update root
	Route::post('complaint_update',['as'=>'complaint_update','uses'=>'Personal\VehicleComplaintController@update']);

	Route::get('VehicleReport',['as'=>'vehicle_report.index','uses'=>'Personal\VehicleReportController@index']);
	
	Route::post('get/vehicle/service/report',['as' => 'personal_vehicle_report','uses' => 'Personal\VehicleReportController@get_vehicle_service_report']);

});

?>