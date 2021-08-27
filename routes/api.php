<?php
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

use Illuminate\Http\Request;
use PhpParser\Node\Expr\Include_;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', 'Api\LoginController@login');


//new Api Started By Dhana

Route::get('getPersonByMobileNo/{mobileNo}', 'Common\Controller\CommonController@getPersonByMobileNo');
Route::post('wfmlogin', 'Entitlement\Controller\LoginController@login');
Route::post('sendOtpForgetPassword', 'Common\Controller\CommonController@sendOtp');
Route::post('OTPVerification', 'Common\Controller\CommonController@OTPVerification');
Route::post('updatePassword', 'Common\Controller\CommonController@updatePassword');
Route::post('createPersonTmpFile', 'Common\Controller\CommonController@createPersonTmpFile');
Route::post('getTmpPersonFile', 'Common\Controller\CommonController@getTmpPersonFile');

Route::post('SignUp', 'Common\Controller\CommonController@signup');
Route::post('updatePassword_and_login', 'Common\Controller\CommonController@updatePassword_and_login');
Route::post('get_persondetails', 'Common\Controller\CommonController@persondetails');

Route::get('finddataByPersonId/{id}', 'Common\Controller\CommonController@finddataByPersonId');

Route::post('sendOtpPerson', 'Common\Controller\CommonController@sendOtpPerson');

Route::get('get_account_list/{mobile_no}', 'Common\Controller\CommonController@get_account_list');

Route::post('sendotp_email', 'Common\Controller\CommonController@sendotp_email');

Route::post('verifiy_email_otp', 'Common\Controller\CommonController@verifiy_email_otp');


//Ended Peron Related Api By Dhana
Route::get('get_state', 'Api\SignupController@get_state');

Route::get('get_rate/{org_id}/{vehicle_id}/{item_id}', 'Api\Wms\JobcardController@get_item_rate');

Route::get('get_city', 'Api\SignupController@get_city');

Route::get('getCity/{id}', 'Api\SignupController@getCity');




Route::post('CheckGst', 'Api\Wms\VehicleRegisterController@check_business_gst_number');

Route::post('check/business-mobile', 'Api\Wms\VehicleRegisterController@check_business_mobile_number');

Route::post('check/people-mobile', 'Api\Wms\VehicleRegisterController@check_person_mobile_number');

Route::post('simple-business-add', 'Api\Wms\VehicleRegisterController@add_organization');

Route::post('simple-people-add', 'Api\Wms\VehicleRegisterController@add_people');



/*START API FOR TESTING PURPOSE */

Route::post('CheckGst', 'Api\Wms\VehicleRegisterController@check_business_gst_number');

Route::post('check/business-mobile', 'Api\Wms\VehicleRegisterController@check_business_mobile_number');

Route::post('check/people-mobile', 'Api\Wms\VehicleRegisterController@check_person_mobile_number');

Route::post('simple-business-add', 'Api\Wms\VehicleRegisterController@add_organization');

Route::post('simple-people-add', 'Api\Wms\VehicleRegisterController@add_people');




/*END API FOR TESTING PURPOSE */



Route::post('search', 'Api\SignupController@search');


Route::post('send_propel_id', 'Api\SignupController@send_propel_id');

Route::post('store_propel', 'Api\SignupController@store_propel');
Route::post('getPeople','Api\Wms\VehicleRegisterController@getPeople');
     


Route::group(['middleware' => ['auth:api']], function () {

    Route::post('change_password', 'Api\UserController@password');

	Route::post('logout', 'Api\LoginController@logout');

    Route::post('dashboard', 'Api\Personal\DashboardController@index');

    Route::post('chart', 'Api\Personal\DashboardController@getChart');

    Route::get('category', 'Api\Personal\CategoryController@index');

    Route::post('category', 'Api\Personal\CategoryController@store');

    Route::patch('category', 'Api\Personal\CategoryController@update');

    Route::delete('category', 'Api\Personal\CategoryController@destroy');

    Route::get('transaction_type', 'Api\Personal\CategoryController@get_transaction_type');


    Route::get('user_companies/{id}', 'Api\UserController@UserCompanies');


    Route::get('user', 'Api\UserController@index');

    Route::get('user/edit/{id}', 'Api\UserController@edit');

    Route::patch('user', 'Api\UserController@update');



    Route::get('account', 'Api\Personal\AccountController@index');

    Route::post('account', 'Api\Personal\AccountController@store');

    Route::patch('account', 'Api\Personal\AccountController@update');

    Route::delete('account', 'Api\Personal\AccountController@destroy');



    //Route::post('accounts/cash', 'Api\Personal\AccountController@cash');

   /* Route::post('personal/transaction/list', 'Api\Personal\PersonalTransactionContoller@index');

    Route::post('personal/transaction', 'Api\Personal\PersonalTransactionContoller@store');

    Route::patch('personal/transaction', 'Api\Personal\PersonalTransactionContoller@update');

    Route::delete('personal/transaction', 'Api\Personal\PersonalTransactionContoller@destroy');*/

    /*Route::post('bills/list', 'Api\Personal\BillController@index');

    Route::post('bills', 'Api\Personal\BillController@store');*/

    /*Route::get('images', 'Api\Personal\PersonalTransactionContoller@image_view');

    Route::post('image/store', 'Api\Personal\PersonalTransactionContoller@image_store');*/


    Route::get('account_list', 'Api\Personal\TransactionContoller@get_account_list');

    Route::get('category_list', 'Api\Personal\TransactionContoller@get_category_list');

    Route::get('transaction', 'Api\Personal\TransactionContoller@index');

    Route::get('transaction_list', 'Api\Personal\TransactionContoller@transactions');

    Route::post('transaction', 'Api\Personal\TransactionContoller@store');

    Route::patch('transaction', 'Api\Personal\TransactionContoller@update');

    Route::delete('transaction', 'Api\Personal\TransactionContoller@destroy');

    //Route::post('notification/count', 'Api\Personal\TransactionContoller@notification');

    Route::get('notification_count', 'Api\Personal\NotificationController@notification_count');

    Route::get('inward-vouchers', 'Api\Personal\NotificationController@index');

    Route::get('cash-transactions', 'Api\Personal\NotificationController@cash_transactions');

    Route::post('view_bill', 'Api\Personal\NotificationController@view_bill');

    Route::get('notification', 'Api\Personal\NotificationController@notifications');


    Route::get('relation_list', 'Api\Personal\PeopleController@get_relation_list');

    Route::get('people', 'Api\Personal\PeopleController@index');

    Route::post('people', 'Api\Personal\PeopleController@store');

    Route::patch('people', 'Api\Personal\PeopleController@update');

    Route::delete('people', 'Api\Personal\PeopleController@destroy');

    
    /*Route::post('interval', 'Api\Personal\BillController@interval');

    Route::post('customer/list', 'Api\Transactions\CustomerController@customer');

    Route::post('payment_method/list', 'Api\Transactions\CustomerController@payment_method');
    
    Route::post('sales_person/list', 'Api\Transactions\CustomerController@sales_person');
    
    Route::post('people/list', 'Api\Personal\PeopleController@getPeopleDetails');
    
    Route::post('people/AddPeople', 'Api\Personal\PeopleController@AddPeople');*/

    /*WFM*/

        // Route::post('wfm/AddProject', 'Api\Wfm\ProjectController@SaveProject');  
        // Route::post('wfm/AddProject/{id}', 'Api\Wfm\ProjectController@SaveProject'); 
        Route::post('wfm/AddTask', 'Wfm\Controller\TaskController@store'); 

        // Route::Post('dashboard/taskStore/{orgId}/{personId}',['as'=>'wfm.task_store','uses'=>'Wfm\Controller\TaskController@store']);
        
        Route::get('wfm/userProjects/{id}','Api\Wfm\ProjectController@UserProject'); 
        Route::get('wfm/getproject/{id}','Api\Wfm\ProjectController@GetProject');    
        Route::get('wfm/getprojectcategory/{id}','Api\Wfm\ProjectController@GetProjectCategory');    
        Route::post('wfm/attachement','Api\Wfm\TaskController@activity_log_attachments'); 
        Route::post('wfm/addfollowers',['as'=>'wfm.followers','uses'=>'Api\Wfm\DashboardController@Addfollowers']);   
        Route::post('wfm/update_activelog', 'Api\Wfm\DashboardController@updateActivityLog');
        Route::post('wfm/update_task/{id}','Api\Wfm\DashboardController@UpdateTask');   
        Route::post('wfm/comments','Api\Wfm\DashboardController@Add_comments');   /*
        Route::post('wfm/update_taskdetails/{id}','Api\Wfm\DashboardController@UpdateTaskDetails');*/   
        Route::delete('wfm/deletefollowers/{id}','Api\Wfm\DashboardController@delete_follower');   
        Route::delete('wfm/delete_comment/{id}','Api\Wfm\DashboardController@deleteComment');   
        Route::delete('wfm/delete_attachment/{id}','Api\Wfm\TaskController@delete_attachment');   
        Route::get('wfm/download_attachment/{id}','Api\Wfm\TaskController@DownloadAttachments');   


        // MOBILE LISTING DATA's 1.3.19 
        Route::get('wfm_dashboard/{person_id}/{project_id}','Api\Wfm\DashboardController@DashBoard');
        Route::get('wfm_addTask/{person_id}','Api\Wfm\DashboardController@getAddTaskData');  
    //    getAddTaskData($id)
        // MOBILE LISTING DATA's 1.3.19 
    /*WFM*/

  
    
        /*START WMS */
        Route::get('wms_dashboard/{id}/{org_id}','Api\Wms\DashboardController@job_status');
        Route::post('wms_JobCardList','Api\Wms\DashboardController@JobCardList');
        Route::get('wms_JobCardData/{id}/{org_id}','Api\Wms\JobcardController@create');
        Route::get('wms_getVehicleDatas/{id}/{org_id}','Api\Wms\JobcardController@get_vehicle_datas');
        Route::post('wms_jobcard/{id?}','Api\Wms\JobcardController@store');
        Route::get('jobcard_edit/{id}/{vechicle_id}/{org_id}','Api\Wms\JobcardController@edit'); 
        Route::post('jobcard_sms/{org_id}','Api\Wms\JobcardController@estimation_sms');
        
        Route::get('vehicle_list','Api\Wms\VehicleRegisterController@index');
        
        Route::post('job_invoice_list','Api\Wms\JobcardController@Job_invoice');
        
        Route::get('add_vehicle/{person_id}/{org_id}','Api\Wms\VehicleRegisterController@create');
        Route::post('save_vehicle','Api\Wms\VehicleRegisterController@store'); 
        Route::post('search_user','Api\Wms\VehicleRegisterController@search'); 
        Route::post('add_business','Api\Wms\VehicleRegisterController@add_business'); 
      
      
        Route::post('add_user','Api\Wms\VehicleRegisterController@add_user'); 

    //get_vehicle_datas($id,$organization_id=false)

        include_once ('wfm_api.php');
        include_once ('business_api.php');





     /*END WMS */

});

