<?php

	// Inventory MODULE STARTS


	Route::group(['prefix' => 'project', 'middleware' => 'modules', 'modules' => 'project'], function () {

		Route::view('dashboard', 'project.dashboard')->name('project.dashboard');

	});
	
	// Inventory MODULE ENDS

	
