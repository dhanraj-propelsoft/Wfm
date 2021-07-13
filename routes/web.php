<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::view('project/dashboard', 'accounts.dashboard')->name('project.dashboard');

Route::view('workshop/dashboard', 'accounts.dashboard')->name('workshop.dashboard');*/

Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});



Route::get('/composer_dumpautoload', function() {
    	Artisan::call('clear-compiled');
    exec('composer dump-autoload');
    return "Cleared!";
 });
 
 Route::get('/composer_update', function() {
	system('composer update');
	return "Cleared!";
 });
 

Route::view('script', 'errors.script');

Route::post('get/city', 'HelperController@get_city')->name('get_city');

Route::post('get/model', 'HelperController@get_model')->name('get_model');

Route::post('get/chapter', 'HelperController@get_chapter')->name('get_chapter');

Route::post('search/gst', 'HelperController@search_gst')->name('search_gst');

Route::post('search/gst/code', 'HelperController@search_gst_code')->name('search_gst_code');


Route::get('batch-migrate', 'Inventory\ItemController@batch_migrate')->name('batch_migrate');

Route::post('message-show', 'DashboardController@message_show')->name('message_show');



/*Job Card Acknowledgement*/

Route::get('/job_card_acknowledgement/{id}', 'Tradewms\Jobcard\JobCardController@job_card_acknowledgement')->name('acknowladge_page');
  
/*Estimation*/

Route::get('/viewlist/{id}/{org_id}', 'Tradewms\VehicleChecklistController@checklistby_id')->name('checklist.checklistby_id');

Route::patch('/status/{id}', 'Tradewms\VehicleChecklistController@change_status')->name('checklist.change_status');

Route::get('/viewlist/{id}/{org_id}', 'Tradewms\VehicleChecklistController@checklistby_id')->name('checklist.checklistby_id');

/*Estimaton End*/

/*Job Card*/
Route::get('/jc_acknowladge/{id}/{org_id}', 'Tradewms\VehicleChecklistController@jc_acknowladge')->name('acknowladge_page');
/*End*/

Route::post('customerpromotion/image_upload',['as'=>'upload_promotion_image','uses'=>'Tradewms\WmsVehicleReportController@upload_image_sms']);

Route::get('customerpromotion/{org_id}', ['as' => 'image_screen', 'uses' => 'Tradewms\WmsVehicleReportController@view_image']);

/*START BREADCUM*/

Route::post('/get_breadcrum', 'Wfm\DashboardController@page_breadcrum')->name('page_breadcrum');


/*END BREADCUM*/
/**
* TEST IMAGES
*
*
**/
Route::get('/thumbnails_view', 'Wfm\DashboardController@gallery_images')->name('gallery_images');
Route::get('/thumbnails', function(){
	 return view('wfm.thumbnail_show');
});




	

//AUTH ROUTE STARTS

Auth::routes();

Route::redirect('/', 'login');



Route::get('subsriptions/{id}/{order}', 'SearchUserController@get_people')->name('subsriptions');

Route::get('subsription-order/{id}/{order}', 'SearchUserController@people')->name('subsriptions-index');

Route::get('password/reset', 'Auth\ForgotPasswordController@reset_password')->name('reset_password');

Route::post('password/reset', 'Auth\ForgotPasswordController@store_password')->name('reset_store_password');

Route::post('password/reset-otp', 'Auth\ForgotPasswordController@reset_login')->name('password.login');

Route::get('user/search', 'SearchUserController@index')->name('search_register_user');

Route::get('user/search-propel', 'SearchUserController@propel_search')->name('propel_register_user');

Route::post('user/search', 'SearchUserController@search_existence')->name('search_register');

Route::post('user/check-propel', 'SearchUserController@check_propel_id')->name('check_propel_id');

Route::post('user/check-business', 'SearchUserController@check_business_id')->name('check_business_id');

Route::post('user/register-propel', 'SearchUserController@register_propel_id')->name('register_propel_id');

Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {

Route::get('business/search-propel', 'SearchBusinessController@propel_search')->name('propel_register_business');

	Route::post('business/register-propel', 'SearchBusinessController@register_propel_business_id')->name('register_propel_business_id');

});

Route::post('user/create', 'Auth\RegisterController@add_user')->name('user_create');

Route::post('check/user-mobile', 'SearchUserController@check_user_mobile_number')->name('check_user_mobile_number');

Route::post('check/user-email', 'SearchUserController@check_user_email_address')->name('check_user_email_address');

Route::post('check/person-mobile', 'SearchUserController@check_person_mobile_number')->name('check_person_mobile_number');




Route::post('check/person-email', 'SearchUserController@check_person_email_address')->name('check_person_email_address');

Route::post('check/business-mobile', 'SearchBusinessController@check_business_mobile_number')->name('check_business_mobile_number');

Route::post('check/business-gst', 'SearchBusinessController@check_business_gst_number')->name('check_business_gst_number');

Route::post('check/business-email', 'SearchBusinessController@check_business_email_address')->name('check_business_email_address');

Route::post('user/register', 'Auth\RegisterController@store')->name('user_register.store');

Route::post('user/save-propel', 'Auth\RegisterController@store_propel')->name('user_register.store_propel');

Route::post('business/save-business', 'RegisterBusinessController@store_business')->name('business_register.store_propel');


Route::post('business/package-detail', 'RegisterBusinessController@get_package_details')->name('business_register.get_package_details');


Route::get('user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

Route::post('user/activation', 'Auth\RegisterController@activateUserLogin')->name('user.activatelogin');

Route::get('otp/resend/{token}', 'Auth\RegisterController@resend_otp')->name('otp.resend');

//AUTH ROUTE ENDS

Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {

	Route::get('password/change', 'Auth\ChangePasswordController@change_password')->name('change_password');

	Route::post('password/change', 'Auth\ChangePasswordController@store_password')->name('password.change');

	Route::post('payment-thankyou', 'PaymentController@response');	

	Route::view('settings', 'settings.settings')->name('settings');

	Route::group(['prefix' => 'settings'], function () {

		Route::get('theme', 'Settings\SettingController@index')->name('theme_settings');

		Route::post('change_theme', 'Settings\SettingController@change_theme')->name('change_theme');

		Route::get('all-people', ['as' => 'all-people.index', 'uses' => 'Admin\PeopleController@index']);


		Route::get('subscription', 'Settings\SubscriptionController@index')->name('subscription');

		Route::get('addon_subscription', 'Settings\SubscriptionController@addon_index')->name('addon_subscription');

		Route::get('plan', 'Settings\SubscriptionController@plan')->name('plan');	

		Route::get('subscribe/{type}', 'Settings\SubscriptionController@pricing')->name('subscribe');

		Route::get('addon_subscribe/{type}/{id}', 'Settings\SubscriptionController@addon_pricing')->name('addon_subscribe');

		//Route::post('subscribe', 'Settings\SubscriptionController@store_pricing')->name('subscribe.store');

		Route::post('subscribe', 'Settings\SubscriptionController@pricing_store')->name('subscribe.store');


		Route::post('addon_subscribe', 'Settings\SubscriptionController@addon_pricing_store')->name('subscribe.addon_pricing_store');

		Route::post('addon-detail', 'Settings\SubscriptionController@get_addon_details')->name('subscribe.get_addon_details');

		Route::post('get/plan-estimate', 'Settings\SubscriptionController@get_estimate_price')->name('get_estimate_price');

		Route::post('addresstype/get', 'Settings\SubscriptionController@get_address_type')->name('get_address_type');

		Route::post('address/get', 'Settings\SubscriptionController@get_address')->name('get_address');

		Route::get('buy/addon', 'Settings\SubscriptionController@buy_addon')->name('buy_addon');

		Route::post('buy/addon', 'Settings\SubscriptionController@store_addon')->name('store_addon');
		

		Route::get('profile/business', ['as' => 'business_profile.index', 'uses' => 'Settings\BussinessProfileController@index']);

		Route::get('profile/business/{id}/{type?}', ['as' => 'business.show', 'uses' => 'Settings\BussinessProfileController@show']);

		Route::patch('profile/business/business-contact/update', ['as' => 'business_profile.business_contact_update', 'uses' => 'Settings\BussinessProfileController@business_contact_update']);

		Route::patch('profile/business/communication/update', ['as' => 'business_profile.communication_update', 'uses' => 'Settings\BussinessProfileController@communication_update']);

		Route::patch('profile/business/ownership/update', ['as' => 'business_profile.ownership_update', 'uses' => 'Settings\BussinessProfileController@ownership_update']);


		Route::get('profile/user/{id}', ['as' => 'person_profile.show', 'uses' => 'Settings\PersonProfileController@show']);

		Route::patch('profile/user/personal/update', ['as' => 'person_profile.personal_details_update', 'uses' => 'Settings\PersonProfileController@personal_details_update']);

		Route::Post('profile_image_upload', ['as' => 'profile_image_upload', 'uses' => 'Settings\PersonProfileController@profile_image_upload']);


		Route::patch('profile/user/communication/update', ['as' => 'person_profile.communication_update', 'uses' => 'Settings\PersonProfileController@communication_update']);
		
		Route::Post('profile/user/get_mobile_no', ['as' => 'get_mobile_no', 'uses' => 'Settings\PersonProfileController@get_mobile_no']);

		/*Route::get('my-subscriptions', 'Settings\SubscriptionController@my_subscription')->name('my_subscription');

		Route::get('plan/premium', 'Settings\SubscriptionController@pay_subsription')->name('pay_subsription');

		Route::post('plan/premium', 'Settings\SubscriptionController@store_subsription')->name('store_subsription');

		Route::get('plan/renew', 'Settings\SubscriptionController@pay_subsription')->name('renew_subsription');

		Route::post('plan/premium/get', 'Settings\SubscriptionController@get_estimate_price')->name('get_estimate_price');

		Route::post('ledger/premium/get', 'Settings\SubscriptionController@get_ledger_price')->name('get_ledger_price');

		Route::get('package/change', 'Settings\SubscriptionController@change_package')->name('change_package');

		Route::get('addon/buy', 'Settings\SubscriptionController@buy_addon')->name('buy_addon');

		Route::post('addon/subscription', 'Settings\SubscriptionController@store_addon_subscription')->name('store_addon_subscription');*/	

		Route::get('print', ['as' => 'print.index', 'uses' => 'Settings\PrintController@index']);

		Route::get('print/create', ['as' => 'print.create', 'uses' => 'Settings\PrintController@create']);

	    Route::post('print', ['as' => 'print.store', 'uses' => 'Settings\PrintController@store']);

	    Route::get('print/{id}', ['as' => 'print.show', 'uses' => 'Settings\PrintController@show']);

	    Route::get('print/{id}/edit', ['as' => 'print.edit', 'uses' => 'Settings\PrintController@edit']);

	    Route::patch('print/{id}', ['as' => 'print.update', 'uses' => 'Settings\PrintController@update']);

	    Route::delete('print/delete', ['as' => 'print.destroy', 'uses' => 'Settings\PrintController@destroy']);

	    Route::post('print/add-image', 'Settings\PrintController@store_image')->name('print_image');

		Route::post('print/remove-image', 'Settings\PrintController@remove_image')->name('print_remove_image');

		Route::get('custom_values', ['as' => 'settings.custom_values', 'uses' => 'Settings\customvaluesController@index']);

		Route::get('settings-custom_values/{id}/edit', ['as' => 'custom_values.edit', 'uses' => 'Settings\customvaluesController@edit']);

        Route::patch('custom_values/update', 'Settings\customvaluesController@update')->name('custom_values.update');

	});

	Route::resource('home', 'GatewayController');

	Route::resource('companies', 'GatewayController');

	Route::get('persons/store', 'GatewayController@persons_store')->name('persons_store');
	
	Route::post('quick_access/', 'GatewayController@quick_access')->name('quick_access');

	Route::get('permissions/{id}', 'GatewayController@permissions')->name('permissions');

	Route::get('companies/store/{type}', 'GatewayController@companies_store')->name('companies_store');

	Route::get('business/search', 'SearchBusinessController@index')->name('search_register_business');

	Route::post('business/search', 'SearchBusinessController@search_existence')->name('search_business');

	Route::post('business/check', 'SearchBusinessController@check_business')->name('check_business');

	Route::post('business/register', 'RegisterBusinessController@create')->name('register_business');

	Route::post('business/register/store', 'RegisterBusinessController@store')->name('register_business.store');

	Route::post('business/otp', 'RegisterBusinessController@send_otp')->name('register_business.send_otp');

	Route::get('business/otp/{id}', 'RegisterBusinessController@business_otp')->name('register_business.business_otp');

	Route::post('business/add/modules', 'RegisterBusinessController@add_modules')->name('register_business.add_modules');

	Route::post('get/order/detail', ['as' => 'get_order_details', 'uses' => 'Inventory\TransactionController@get_order_details']);

	Route::post('get/transaction/tax', ['as' => 'get_tax', 'uses' => 'Inventory\TransactionController@get_tax']);
	
	Route::group(['prefix' => 'user', 'middleware' => 'account', 'account' => 'user'], function () {

		Route::get('dashboard', 'Personal\DashboardController@index')->name('user.dashboard');

		Route::post('get/notifications', 'Personal\NotificationController@get_notifications')->name('get_user_notifications');

		Route::get('notifications', 'Personal\NotificationController@notifications')->name('user_notifications');

		Route::post('notifications/discard', 'NotificationController@discard_notifications')->name('user_discard_notifications');

		Route::get('personal/category', 'Personal\CategoryController@index')->name('personal_category.index');

		Route::get('personal/category/create', 'Personal\CategoryController@create')->name('personal_category.create');

		Route::post('personal/category/store', 'Personal\CategoryController@store')->name('personal_category.store');

		Route::get('personal/category/{id}/edit', 'Personal\CategoryController@edit')->name('personal_category.edit');

		Route::patch('personal/category/update', 'Personal\CategoryController@update')->name('personal_category.update');

		Route::delete('personal/category/delete', 'Personal\CategoryController@destroy')->name('personal_category.destroy');

		Route::get('personal_category_status_approval',['as'=>'personal_category_status_approval','uses'=>'Personal\CategoryController@personal_category_status_approval']);

		Route::get('people', 'Personal\PeopleController@index')->name('personal_people.index');

		Route::get('people/create', 'Personal\PeopleController@create')->name('personal_people.create');

		Route::post('people/store', 'Personal\PeopleController@store')->name('personal_people.store');

		Route::get('people/{id}/edit', 'Personal\PeopleController@edit')->name('personal_people.edit');

		Route::patch('people/update', 'Personal\PeopleController@update')->name('personal_people.update');

		Route::delete('people/delete', 'Personal\PeopleController@destroy')->name('personal_people.destroy');

		Route::post('people_status_approval',['as'=>'personal_people_status_approval','uses'=>'Personal\PeopleController@people_status_approval']);

		Route::delete('people/multidelete', ['as' => 'personal_people.multidestroy', 'uses' => 'Personal\PeopleController@multidestroy']);
		

		Route::get('account', 'Personal\AccountController@index')->name('account.index');

		Route::get('account/create', 'Personal\AccountController@create')->name('account.create');

		Route::post('account/store', 'Personal\AccountController@store')->name('account.store');

		Route::get('account/{id}/edit', 'Personal\AccountController@edit')->name('account.edit');

		Route::patch('account/update', 'Personal\AccountController@update')->name('account.update');

		Route::delete('account/delete', 'Personal\AccountController@destroy')->name('account.destroy');

		Route::post('account_status_approval',['as'=>'account_status_approval','uses'=>'Personal\AccountController@account_status_approval']);

		Route::get('personal/bills', 'Personal\BillsController@index')->name('personal_bills.index');

		Route::get('personal/bills/{id}/show/{organization}', 'Personal\BillsController@show')->name('personal_bills.edit');

		Route::get('personal/transaction', 'Personal\TransactionController@index')->name('personal_transaction.index');

		Route::get('personal/transaction/create/{reference?}/{type?}', 'Personal\TransactionController@create')->name('personal_transaction.create');

		Route::post('personal/transaction/store', 'Personal\TransactionController@store')->name('personal_transaction.store');

		Route::get('personal/transaction/{id}/edit', 'Personal\TransactionController@edit')->name('personal_transaction.edit');

		Route::patch('personal/transaction/update', 'Personal\TransactionController@update')->name('personal_transaction.update');

		Route::delete('personal/transaction/delete', 'Personal\TransactionController@destroy')->name('personal_transaction.destroy');

		Route::delete('personal/transaction/multidelete', ['as' => 'personal_transaction.multidestroy', 'uses' => 'Personal\TransactionController@multidestroy']);

		Route::post('personal/get/transaction-category', 'Personal\TransactionController@transaction_category')->name('get_personal_transaction_category');

		Route::post('personal/get/transaction-account', 'Personal\TransactionController@transaction_account')->name('get_personal_transaction_category_account');

		Route::get('personal/transact/{type}', 'Personal\CashTransactionController@index')->name('personal_cash_transaction.index');

		include_once('vms.php');

	});
	


	Route::group(['middleware' => 'account', 'account' => 'business'], function () {

		Route::post('get/notifications', 'NotificationController@get_notifications')->name('get_notifications');

		Route::get('notifications', 'NotificationController@notifications')->name('notifications');

		Route::post('notifications/discard', 'NotificationController@discard_notifications')->name('discard_notifications');

		Route::post('notifications/ledger_notifications', 'NotificationController@ledger_notifications')->name('ledger_notifications');

		Route::post('notification-status', 'NotificationController@notification_status')->name('notification_status');		

		Route::post('get/people', 'HelperController@search_people')->name('search_people');

		Route::post('get_people_detail', 'SearchUserController@get_people_detail')->name('get_people_detail');

		Route::post('get/user/simple-search', 'SearchUserController@simple_user_search')->name('simple_user_search');

		Route::post('user/simple-add', 'SearchUserController@add_user')->name('simple_user_add');

		Route::post('user/simple-people-add', 'SearchUserController@add_people')->name('simple_people_add');

		Route::post('get/user/advanced-search', 'SearchUserController@advanced_user_search')->name('advanced_user_search');

		Route::post('user/file-upload', 'SearchUserController@user_file_upload')->name('user_file_upload');

		Route::post('user/file-remove', 'SearchUserController@user_file_remove')->name('user_file_remove');

		Route::post('user/advanced-add', 'SearchUserController@add_full_user')->name('advanced_user_add');

		Route::post('get/business/simple-search', 'SearchBusinessController@simple_business_search')->name('simple_business_search');

		Route::post('business/simple-add', 'SearchBusinessController@add_business')->name('simple_business_add');

		Route::post('business/simple-business-add', 'SearchBusinessController@add_organization')->name('simple_organization_add');

		Route::post('get/business/advanced-search', 'SearchBusinessController@advanced_business_search')->name('advanced_business_search');

		Route::post('business/file-upload', 'SearchBusinessController@user_file_upload')->name('business_file_upload');

		Route::post('business/file-remove', 'SearchBusinessController@user_file_remove')->name('business_file_remove');

		Route::post('business/advanced-add', 'SearchBusinessController@add_full_business')->name('advanced_business_add');
		
		Route::post('validate/business-mobile', 'SearchBusinessController@validate_business_mobile_number')->name('validate_business_mobile_number');
		

		Route::get('dashboard', 'DashboardController@index')->name('dashboard');

		

		Route::get('user-log', 'Settings\UserLogController@index')->name('user_logs');

		Route::post('get/user/log', 'Settings\UserLogController@get_user_log')->name('get_user_log');

		Route::post('list/user/log', 'Settings\UserLogController@list_user_log')->name('list_user_log');

		Route::post('user/log', 'Settings\UserLogController@user_log')->name('user_log');


	//  PEOPLE ROUTE STARTS

		Route::get('people', ['as' => 'people.index', 'uses' => 'Hrm\PeopleController@index']);

		Route::get('people/{id}/edit', ['as' => 'people.edit', 'uses' => 'Hrm\PeopleController@edit']);

		Route::patch('people/update', ['as' => 'people.update', 'uses' => 'Hrm\PeopleController@update']);

		Route::delete('people/delete', ['as' => 'people.destroy', 'uses' => 'Hrm\PeopleController@destroy']);

	    Route::delete('people/multidelete', ['as' => 'people.multidestroy', 'uses' => 'Hrm\PeopleController@multidestroy']);

	    Route::post('people/multiapprove', ['as' => 'people.multiapprove', 'uses' => 'Hrm\PeopleController@multiapprove']);

	    Route::get('people_status_approval',['as'=>'people_status_approval','uses'=>'Hrm\PeopleController@people_status_approval']);

	//  PEOPLE ROUTE ENDS


	//  ROLES ROUTE STARTS

		Route::get('roles', ['as' => 'roles.index', 'uses' => 'Settings\RoleController@index', 'middleware' => ['permission:role-list|role-create|role-edit|role-delete']]);

		Route::get('roles/create', ['as' => 'roles.create', 'uses' => 'Settings\RoleController@create', 'middleware' => ['permission:role-create']]);

		Route::post('roles/create', ['as' => 'roles.store', 'uses' => 'Settings\RoleController@store', 'middleware' => ['permission:role-create']]);

		Route::get('roles/{id}', ['as' => 'roles.show', 'uses' => 'Settings\RoleController@show']);
		
		Route::post('role_check', ['as' => 'role_check', 'uses' => 'Settings\RoleController@role_check']); 


		Route::get('roles/{id}/edit', ['as' => 'roles.edit', 'uses' => 'Settings\RoleController@edit', 'middleware' => ['permission:role-edit']]);

		Route::patch('roles/update', ['as' => 'roles.update', 'uses' => 'Settings\RoleController@update', 'middleware' => ['permission:role-edit']]);

		Route::delete('roles/delete', ['as' => 'roles.destroy', 'uses' => 'Settings\RoleController@destroy', 'middleware' => ['permission:role-delete']]);

		Route::get('roles/{id}/copy', ['as' => 'get_copy_role', 'uses' => 'Settings\RoleController@get_copy_role']);

		Route::post('get/voucher-no', 'HelperController@get_voucher_no')->name('get_voucher_no');

	// ROLES ROUTE ENDS

	//  PRIVILEGE ROUTE STARTS

		Route::get('privileges', ['as' => 'privilege.index', 'uses' => 'Settings\UserPrivilegeController@index']);

		Route::get('privileges/{id}/{identify_field}/edit', ['as' => 'privilege.edit', 'uses' => 'Settings\UserPrivilegeController@edit']);

		Route::patch('privileges/update', ['as' => 'privilege.update', 'uses' => 'Settings\UserPrivilegeController@update']);

		Route::delete('privileges/delete', ['as' => 'privilege.destroy', 'uses' => 'Settings\UserPrivilegeController@destroy']);

	// PRIVILEGE ROUTE ENDS


		Route::get('voucher-format', ['as' => 'voucher_format.index', 'uses' => 'Settings\VoucherFormatController@index']);

		Route::get('voucher-format/create', ['as' => 'voucher_format.create', 'uses' => 'Settings\VoucherFormatController@create']);

		Route::post('voucher-format/create', ['as' => 'voucher_format.store', 'uses' => 'Settings\VoucherFormatController@store']);

		Route::get('voucher-format/{id}', ['as' => 'voucher_format.show', 'uses' => 'Settings\VoucherFormatController@show']);

		Route::get('voucher-format/{id}/edit', ['as' => 'voucher_format.edit', 'uses' => 'Settings\VoucherFormatController@edit']);

		Route::patch('voucher-format/update', ['as' => 'voucher_format.update', 'uses' => 'Settings\VoucherFormatController@update']);

		Route::delete('voucher-format/delete', ['as' => 'voucher_format.destroy', 'uses' => 'Settings\VoucherFormatController@destroy']);


		Route::get('settings-voucher', ['as' => 'settings_voucher.index', 'uses' => 'Settings\SettingsVoucherController@index']);

		Route::get('settings-voucher/{id}/edit', ['as' => 'settings_voucher.edit', 'uses' => 'Settings\SettingsVoucherController@edit']);


		Route::get('employees', ['as' => 'staff.index', 'uses' => 'EmployeeController@index']);

	 	Route::get('employees/create', ['as' => 'staff.create', 'uses' => 'EmployeeController@create']);

	 	Route::post('employees', ['as' => 'staff.store', 'uses' => 'EmployeeController@store']);

	 	Route::get('employees/{id}/edit', ['as' => 'staff.edit', 'uses' => 'EmployeeController@edit']);

	 	Route::patch('employees/update', ['as' => 'staff.update', 'uses' => 'EmployeeController@update']);

	 	Route::delete('employees/delete', ['as' => 'staff.destroy', 'uses' => 'EmployeeController@destroy']);

	 	Route::delete('employees/multidelete', ['as' => 'staff.multidestroy', 'uses' => 'EmployeeController@multidestroy']); 	


	 	Route::get('under-construction', ['as' => 'under_construction.index', 'uses' => 'UnderConstructionController@index']);	


	/* Support ticket*/

	Route::get('support_ticket', ['as' => 'support_ticket.index', 'uses' => 'Settings\SupportController@index']);

	Route::get('support_ticket/create', ['as' => 'support_ticket_create', 'uses' => 'Settings\SupportController@create']);
	Route::post('ticketnumber_check', 'Settings\SupportController@ticketnumber_check')->name('ticketnumber_check');
	Route::post('support_ticket/insertion', ['as' => 'supportticket_store', 'uses' => 'Settings\SupportController@store']);
	Route::post('support_ticket/status',['as'=>'support_ticket.status','uses'=>'Settings\SupportController@support_ticket_status']); 
	Route::get('support_ticketshow/{id}/view', ['as' => 'support_ticketshow', 'uses' => 'Settings\SupportController@show']);
	Route::post('ticket_update', 'Settings\SupportController@update')->name('ticket_update');
	Route::post('select_status_settings', ['as' => 'select_status_settings', 'uses' => 'Settings\SupportController@select_status']);
	Route::get('morefrom_propel',['as'=>'morefrom_propel','uses'=>'Settings\SettingController@propel_apps']);

	Route::get('download/{id}', ['as' => 'app.download', 'uses' => 'Settings\SettingController@download']);

	Route::post('message-show', 'DashboardController@message_show')->name('message_show');

	Route::get('sms_template',['as'=>'sms_template','uses'=>'Settings\SmsTemplateController@index']);
	
	Route::get('sms_template_details/{id?}',['as'=>'sms_template_details','uses'=>'Settings\SmsTemplateController@create']);
	
	Route::post('sms_template_store{id?}',['as'=>'sms_template_store','uses'=>'Settings\SmsTemplateController@store']);

	Route::delete('sms_templates_delete', ['as' => 'settings.sms_templates_delete', 'uses' => 'Settings\SmsTemplateController@destroy']);
	Route::get('order_no_search', 'DashboardController@order_no_search')
	->name('order_no_search');
	
	
	Route::post('restart_all_vouchers',['as'=>'restart_all_vouchers','uses'=>'Settings\SettingsVoucherController@restart_all_vouchers']);

	Route::get('org_expense_rate',['as'=>'org_expense_rate','uses'=>'Settings\SettingController@org_expense_rate_index']);

	Route::post('organizations_cost/{id?}',['as'=>'organizations_cost_update','uses'=>'Settings\SettingController@organizations_cost_update']);
	/*End*/


		include_once('accounts.php');
		include_once('hrm.php');
		include_once('inventory.php');
		include_once('trade.php');
		include_once('project.php');
		include_once('workshop.php');
		include_once('admin.php');
		include_once('trade_wms.php');
		include_once('transaction.php');
		include_once('wfm.php');
		include_once('fuel_station.php');	
		include_once('job_card.php');	
		include_once('vehicle.php');		


		
		


	});

});
