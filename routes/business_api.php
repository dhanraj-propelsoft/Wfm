<?php

Route::post('organization_store', 'Organization\Controller\OrganizationController@store');

Route::get('business_edit/{id}', 'Organization\Controller\OrganizationController@findById');

Route::get('getOrgMasterData', 'Organization\Controller\OrganizationController@getOrgMasterData');