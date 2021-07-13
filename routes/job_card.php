<?php
use Illuminate\Http\Request;

// JOB -- CARD -- ROUTE
 
    Route::get('jobcard', function(Request $request) {
    	
    	if($request->ajax()){
    	   return App::call('App\Http\Controllers\Tradewms\Jobcard\JobCardController@index');
        }else{
    		return view('trade_wms.jobcard.JobCardListView.JobCard-ListView');
    	}
    })->name('jobcard.index');

    Route::get('jobcard/{param}', function($param) {
        // for create
        if($param == 'create'){
            return view('trade_wms.jobcard.JobCardDetail.JobCard-Detail');
        }else if( $param >0)
        {
            // for edit
            $id = $param;
            return view('trade_wms.jobcard.JobCardDetail.JobCard-Detail', compact('id'));
        }
    })->name('jobcard.edit');

    Route::resource('jobcard', 'Tradewms\Jobcard\JobCardController')->except(['index','edit','create']);
    
    Route::post('changeJobcardStatus', ['as' => 'jobcard.changeStatus', 'uses' =>'Tradewms\Jobcard\JobCardController@changeStatus']);
    
	Route::post('jc_transactions/print', ['as' => 'jobcard_print_transaction', 'uses' => 'Tradewms\Jobcard\JobCardController@print']);
    
    Route::post('jobcard/pay_advance',['as' => 'jobcard_advance', 'uses' => 'Tradewms\Jobcard\JobCardController@jobcard_advance']);

    Route::get('findJobDetails/{id}', ['as' => 'findjobcarddetail', 'uses' => 'Tradewms\Jobcard\JobCardController@findJobCardById']);
  
    // Route::get('findJobCardImageByTID/{id}', ['as' => 'jobcard.image', 'uses' => 'Tradewms\Jobcard\JobCardController@findJobCardImageByTID']);
   
    Route::get('destroyJobCardImage/{id}', ['as' => 'destory.jobcardImage', 'uses' => 'Tradewms\Jobcard\JobCardController@destroyJobCardImage']);
    
    Route::get('jobCardsendSMS/{id}', ['as' => 'jobcard.sendSMS', 'uses' => 'Tradewms\Jobcard\JobCardController@sendSMS']);

  
    /*End*/

    /*START MASTER DATA  ROUTE FOR JOBCARD */
    
        //get basic master data for job upon first load
        Route::get('jobcardDetail/masterData/{id?}', ['uses'=>'Tradewms\Jobcard\JobCardController@getMasterData'])->name('jobcardDetail.masterData');
        
        //TODO: Move this to people/person/business controller and route file
        Route::post('findCustomerByMobile',['as'=>'findCustomerByMobile','uses'=>'Tradewms\Jobcard\JobCardController@findCustomerByMobile']);
        
        //TODO: Move this to inventory route file
        //get items for product chooser
        Route::get('inventoryItem/findAllForProductChooser', ['uses'=>'Inventory\ItemController@findAllForProductChooser'])->name('inventoryItem.findAllForProductChooser');

    /*END MASTER DATA  ROUTE DATA FOR JOBCARD */
    

// JOB CARD ROUTE --- END ------------------------------------------------------------------

// JOB -- ESTIMATE -- ROUTE

    //Create/Update estimation from jobcard
    Route::get('estimation_copy_from_jobcard/{id}', ['as' => 'jobcard.estimation', 'uses' => 'Tradewms\Estimation\EstimationController@estimation_from_jobcard']);
    
    //Filter estimate (transaction controller blade) for a specific transaction(estimate) id
    Route::get('jobEstimation/{id}',['as'=>'job_estimation.index','uses'=>'Tradewms\Estimation\EstimationController@index']);

// JOB ESTIMATE ROUT --- END -----------------------------------------------------------------

// JOB -- INVOICE -- ROUTE

    //Create/Update invoice jobcard
    Route::get('invoice_copy_from_jobcard/{id}/{type?}', ['as' => 'jobcard.invoice', 'uses' => 'Tradewms\Invoice\InvoiceController@invoice_from_jobcard']);

    //Filter invoice (transaction controller blade) for a specific transaction(invoice) id
    Route::get('jobInvoice/{id}/{type?}',['as'=>'job_invoice.index','uses'=>'Tradewms\Invoice\InvoiceController@index']);
// JOB INVOICE ROUT --- END ------------------------------------------------------------------