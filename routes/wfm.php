<?php

	// WFM MODULE STARTS
		// Routes
		// Created by Manimaran
		// Created on 21.08.2018
		// Routes are comment by slash'/' symbols
		// Routes seperate with command


Route::group(['prefix' => 'wfm'], function () {

	Route::get('dashboard/summary/{id}',['as'=>'dashboard.summarylist','uses'=>'Wfm\DashboardController@summarylist']);

  	Route::get('dashboard',['as'=>'wfm.dashboard','uses'=>'Wfm\DashboardController@index']);

	Route::get('dashboard/{org_id}/{pro_id}',['as'=>'wfm.projectdetails','uses'=>'Wfm\DashboardController@index']);

	Route::get('taskdetails/{id}',['as'=>'wfm.taskdetails','uses'=>'Wfm\DashboardController@getTaskDetails']);

	Route::post('getorgdetails',['as'=>'wfm.orgdetails','uses'=>'Wfm\DashboardController@getOrgDetails']);

	


// /*Project List Details Start old*/ 
//    Route::get('dashboard/projectList',['as'=>'wfm.project_list','uses'=>'Wfm\DashboardController@all_projects']);
//    Route::get('dashboard/projectList/edit/{id}',['as'=>'dashboard.edit','uses'=>'Wfm\DashboardController@edit']);
//    Route::patch('dashboard/projectList/update/{id}',['as'=>'dashboard.update','uses'=>'Wfm\DashboardController@update']);
//  /*Project List Details End*/

/*Project List Details Start API*/ 
   // Route::get('projectList',['as'=>'project_list','uses'=>'Wfm\Controller\ProjectController@index']);

	Route::get('project/list',['as'=>'project_list','uses'=>'Wfm\Controller\ProjectController@index']);

   Route::get('project/create',['as'=>'project.create','uses'=>'Wfm\Controller\ProjectController@create']);

   Route::get('dashboard/projectList/edit/{id}',['as'=>'dashboard.edit','uses'=>'Wfm\DashboardController@edit']);

   Route::patch('dashboard/projectList/update/{id}',['as'=>'dashboard.update','uses'=>'Wfm\DashboardController@update']);
/*Project List Details API End*/


 /*Project Category Routes Start*/
	
		//project category 
	Route::resource('projectcategory', 'Wfm\Controller\ProjectMasterController');
		//Status Change in Project category

	Route::post('projectcategory/status', ['as'=>'projectcategory.status','uses'=>'Wfm\Controller\ProjectMasterController@status']);
/*Project Category Routes End*/


	// Each and Every details Update Start
 	Route::post('task_details_submit',['as'=>'task_details_submit','uses'=>'Wfm\DashboardController@task_details_submit']);

 	Route::post('task_name_submit',['as'=>'task_name_submit','uses'=>'Wfm\DashboardController@task_name_submit']);

 	Route::post('project_update',['as'=>'project_update','uses'=>'Wfm\DashboardController@project_update']);

 	Route::post('assigned_to_update',['as'=>'assigned_to_update','uses'=>'Wfm\DashboardController@assigned_to_update']);

 	Route::post('end_date_update',['as'=>'end_date_update','uses'=>'Wfm\DashboardController@end_date_update']);

 	Route::post('priority_update',['as'=>'priority_update','uses'=>'Wfm\DashboardController@priority_update']);

   

	

	//if Need to add permission use this "permission , 'middleware' =>['permission:discount-create']"

	/*Priority Master Routes Start*/
	Route::get('priority',['as'=>'priority.index','uses'=>'Wfm\PriorityController@index']);

	Route::get('priority/create',['as'=>'priority.create','uses'=>'Wfm\PriorityController@create']);

	Route::post('priority/store',['as'=>'priority.store','uses'=>'Wfm\PriorityController@store']);

	Route::get('priority/{id}/edit', ['as' => 'priority.edit', 'uses' => 'Wfm\PriorityController@edit']);

	Route::patch('priority/update',['as'=>'priority.update','uses' => 'Wfm\PriorityController@update']);

	Route::post('priority/status', ['as'=>'priority.status','uses'=>'Wfm\PriorityController@status']);

	Route::delete('priority/delete',['as'=>'priority.destroy','uses'=>'Wfm\PriorityController@destroy']);
	/*Priority Master Routes End*/


	/*Task Master Routes Start*/
	Route::get('taskstatus',['as'=>'task.index','uses'=>'Wfm\TaskController@index']);

	Route::get('task/create',['as'=>'task.create','uses'=>'Wfm\Controller\TaskController@create']);
	
	Route::get('task/attachments',['as'=>'task.attachments','uses'=>'Wfm\TaskController@add_attachments']);

	Route::post('task',['as'=>'task.store','uses'=>'Wfm\TaskController@store']);

	Route::get('task/{id}/edit', ['as' => 'task.edit', 'uses' => 'Wfm\TaskController@edit']);

	Route::patch('task/update',['as'=>'task.update','uses' => 'Wfm\TaskController@update']);

	Route::post('task/status', ['as'=>'task.status','uses'=>'Wfm\TaskController@status']);


	Route::delete('task/delete',['as'=>'task.destroy','uses'=>'Wfm\TaskController@destroy']);


	/*Task Master Routes End*/

	/* Task Substatus Routes starts*/
	Route::get('tasksubstatus',['as'=>'tasksubstatus.index','uses'=>'Wfm\TaskSubstatusController@index']);

	Route::get('tasksubstatus/create',['as'=>'tasksubstatus.create','uses'=>'Wfm\TaskSubstatusController@create']);
	/*Task Substatus Routes Ends*/

	/* Size Routes starts*/
	Route::get('size',['as'=>'size.index','uses'=>'Wfm\SizeController@index']);
	Route::get('size/create',['as'=>'size.create','uses'=>'Wfm\SizeController@create']);

	Route::post('size/store',['as'=>'size.store','uses'=>'Wfm\SizeController@store']);

	Route::get('size/{id}/edit', ['as' => 'size.edit', 'uses' => 'Wfm\SizeController@edit']);

	Route::patch('size/update',['as'=>'size.update','uses' => 'Wfm\SizeController@update']);

	Route::post('size/status', ['as'=>'size.status','uses'=>'Wfm\SizeController@status']);


	Route::delete('size/delete',['as'=>'size.destroy','uses'=>'Wfm\SizeController@destroy']);

	/*Size Routes Ends*/

	/*Label Master Route Start*/
	Route::get('label',['as'=>'label.index','uses'=>'Wfm\LabelMasterController@index']);

	Route::get('label/create',['as'=>'label.create','uses'=>'Wfm\LabelMasterController@create']);

	Route::post('label',['as'=>'label.store','uses'=>'Wfm\LabelMasterController@store', 'middleware' => ['permission:discount-create']]);

	Route::get('label/{id}/edit', ['as' => 'label.edit', 'uses' => 'Wfm\LabelMasterController@edit']);

	Route::patch('label/update',['as'=>'label.update','uses' => 'Wfm\LabelMasterController@update']);

	Route::post('label/status', ['as'=>'label.status','uses'=>'Wfm\LabelMasterController@status']);

	Route::delete('label/delete',['as'=>'label.destroy','uses'=>'Wfm\LabelMasterController@destroy']);

	

	/*Ajax functions*/

	/*Duplicate Remote validation*/
		Route::post('check/projectfields', 'Wfm\ProjectController@check_projectname')->name('check_wfm_project');

	/*END Duplicate Remote validation*/

	Route::post('check/taskfields', 'Wfm\TaskController@check_Task_fields')->name('Check_task_fields');
	Route::post('getProjectDetails', 'Wfm\ProjectController@getProjectDetails')->name('getprojectdetails');
	Route::post('gettaskform/{id}', 'Wfm\TaskController@getTaskDetails')->name('gettaskdetails');
	Route::post('getprojectform/{id}', 'Wfm\ProjectController@getProjectForm')->name('getprojectform');

	Route::get('gettaskcount/{id}/{org_id}', 'Wfm\DashboardController@getTaskCountByUserId');
	Route::get('get_task_orguser/{org_id}/{user_id}', 'Wfm\DashboardController@getTaskByOrg_User');
	//Route::get('getemployeelistbytask/{org_id}/{user_id}', 'Wfm\DashboardController@GetEmployeesByTask');
	Route::post('getactionprogress', 'Wfm\DashboardController@getTaskProgress');
	
	Route::post('getupdateprogress', 'Wfm\DashboardController@UpdateProgress');

	Route::post('reassigntask', 'Wfm\DashboardController@ReassignTask');
	Route::post('searchtask', 'Wfm\DashboardController@getTaskByCondition');

	Route::post('UpdateTask','Wfm\DashboardController@UpdateTask');
	Route::post('taskfilter','Wfm\DashboardController@GetTaskFilterData');
	Route::post('advancefilter',['as'=>'task.advancefilter','uses'=>'Wfm\DashboardController@advanced_search']);
	Route::post('save_search',['as'=>'task.savesearch','uses'=>'Wfm\DashboardController@save_search']);
	Route::get('get_savedsearch/{id}','Wfm\DashboardController@savedsearch_results');
	Route::get('attachment_download/{id}','Wfm\TaskController@DownloadAttachments');
	//Route::get('deletefollower','Wfm\DashboardController@DeleteFollower');

	/*Ajax functions*/


	Route::post('projectfile',['middleware'=>'auth','as'=>'upload.projectfile','Wfm\ProjectController@upload_file']);
//	Route::get('wfm_settings', 'settings.wfm_settings')->name('wfm_settings');
	

	Route::get('wfm_settings', function () {
    return view('wfm.wfm_settings');
    Route::get('wfm_settingsfree', function () {
    return view('wfm.wfm_settingsfree');
	});
	Route::get('wfm_settings_standard', function () {
    return view('wfm.wfm_settingsstandard');
	});
	Route::get('wfm_settings_starter', function () {
    return view('wfm.wfm_settings_starter');
	});
	Route::get('wfm_settings_lite', function () {
    return view('wfm.wfm_settings_lite');
	});
	Route::get('wfm_settings_professional', function () {
    return view('wfm.wfm_settings_professional');
	});
	Route::get('wfm_settings_enterprise', function () {
    return view('wfm.wfm_settings_enterprise');
	});
	Route::get('wfm_settings_corparate', function () {
    return view('wfm.wfm_settings_corparate');
	});

	// Route::get('projectcategory',['as'=>'projectcategory.index','uses'=>'Wfm\ProjectMasterController@index']);
	Route::get('size',['as'=>'wfm_settings.index','uses'=>'Wfm\SizeController@index']);
	Route::get('label',['as'=>'wfm_settings.index','uses'=>'Wfm\LabelMasterController@index']);
	Route::get('taskstatus',['as'=>'wfm_settings.index','uses'=>'Wfm\TaskController@index']);


});

	//	Route::get('wfm_settings',['as'=>'wfm_settings.index','uses'=>'Wfm\PriorityController@index']);




});


   



/*	Route::group(['prefix' => 'wfm_settings'], function () {

		
		Route::get('projectcategory',['as'=>'projectcategory.index','uses'=>'Wfm\ProjectMasterController@index']);

		Route::get('priority',['as'=>'priority.index','uses'=>'Wfm\PriorityController@index']);

		Route::get('size',['as'=>'size.index','uses'=>'Wfm\SizeController@index']);

		Route::get('label',['as'=>'label.index','uses'=>'Wfm\LabelMasterController@index']);

		Route::get('taskstatus',['as'=>'task.index','uses'=>'Wfm\TaskController@index']);

	});*/


/*Setting Route End*



/*Label Master Route End*/
/*
		Route::get('service-type',['as'=>'service_type.index','uses'=>'Workshop\ServiceTypeController@index']);


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

		Route::get('vehicles',['as'=>'vehicles.index','uses'=>'Workshop\VehicleController@index']);*/



// Inventory MODULE ENDS


