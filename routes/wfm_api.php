<?php

// Category
	Route::get('CategoryList/{id}', 'Wfm\Controller\CategoryController@index');

	Route::get('CategoryCreate', 'Wfm\Controller\CategoryController@create');

	Route::post('CategoryStore', 'Wfm\Controller\CategoryController@store');

	Route::get('CategoryEdit/{id}', 'Wfm\Controller\CategoryController@edit');

	Route::post('CategoryUpdate/{id}', 'Wfm\Controller\CategoryController@update');

	Route::get('CategoryBasedProj/{id}', 'Wfm\Controller\ProjectController@CategoryBasedProj');

	Route::post('CategoryStatusChg', 'Wfm\Controller\CategoryController@status');

	Route::post('CategorySelectAll', 'Wfm\Controller\CategoryController@CategorySelectAll');

	Route::post('SwitchOrg', 'Wfm\Controller\OrganizationController@SwitchOrg');

// Category  End****


// Project
	Route::get('projectList/{id}', 'Wfm\Controller\ProjectController@index');

	Route::get('projectCreate/{id}', 'Wfm\Controller\ProjectController@create');

	Route::post('projectStore', 'Wfm\Controller\ProjectController@store');

	Route::get('projectEdit/{id}/{orgid}', 'Wfm\Controller\ProjectController@edit');

	Route::post('projectUpdate/{id}', 'Wfm\Controller\ProjectController@update');

	Route::get('ProjBasedCategory/{id}', 'Wfm\Controller\ProjectController@ProjBasedCategory');




	Route::post('ProjStatusChg', 'Wfm\Controller\ProjectController@status');

	Route::post('ProjSelectAll', 'Wfm\Controller\ProjectController@ProjSelectAll');
// Project  End****


// Task Start
	Route::get('taskCreate/{id}', 'Wfm\Controller\TaskController@create');

	Route::post('taskfollowerupdate', 'Wfm\Controller\TaskController@taskfollowerupdate');

	Route::post('taskStore', 'Wfm\Controller\TaskController@store');

	Route::get('taskDetails/{id}', 'Wfm\Controller\TaskController@findById');
// Task End***
	


// Dashboard
	Route::get('Dashboard/{id}', 'Wfm\Controller\DashboardController@index');

	Route::post('taskEdit', 'Wfm\Controller\TaskController@taskEdit');


	Route::post('taskAction', 'Wfm\Controller\TaskController@taskActionchg');


	Route::post('file_attchment', 'Wfm\Controller\ProjectController@file_send');


	Route::get('Employees/{id}', 'Wfm\Controller\DashboardController@employess_list');













