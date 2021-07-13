<?php

		/* Vehicle Route START */
		Route::get('findVehicleCategory/{id}',['as' => 'jobcard.vehicleCategory', 'uses' =>'Vehicle\VehicleController@findVehicleCatgoryById']);
		
		Route::get('findVehicle/{vehicleNo}',['as'=>'findVehicle','uses'=>'Vehicle\VehicleController@findVehicleDetail']);
		
		/* Vehicle Route END */