<?php		
		
		//to show print popup
		Route::get('print_popup/create',['as' => 'print_popup.create','uses' => 'Inventory\TransactionController@show_print_popup']);

				
		// to show small modal to payment in invoice ,job card,purchase,job_invoice
		 Route::get('get_estimations_views/{id}/{name}/{identity_name}',['as'=>'get_estimations_views','uses'=>'Inventory\TransactionController@get_estimations_views']);

		 // to show small modal to view invoice,estimation,grn,dn
		 Route::get('get_estimations_view/{id}/{name}',['as'=>'get_estimations_view','uses'=>'Inventory\TransactionController@get_estimations_view']);



		 //to show small modal to view print list in invoice
		 Route::get('/get_prints_view/{id}/{name}',['as' => 'get_prints_view','uses' =>'Inventory\TransactionController@get_prints_view']);








		Route::post('jobcard_predefind_complaint','Inventory\jobcardComplaintController@jobcard_predefind_complaint')->name('jobcard_predefind_complaint');


			/* Predefined Complaints */

	        //Route::get('jobcard_complaint/create',['as'=>'jobcard_complaint.create','uses'=>'Inventory\JobCardPredefinedController@create']);

	        Route::post('jobcard_complaint','Inventory\JobCardPredefinedController@store')->name('jobcard_complaint.store');

	        Route::get('jobcard_complaint/details/{id?}',['as'=>'jobcard_complaint.details','uses'=>'Inventory\JobCardPredefinedController@create']);

	        Route::post('jobcard_complaint/update',['as'=>'jobcard_complaint.update','uses'=>'Inventory\JobCardPredefinedController@update']);

	        Route::post('jobcard/complaint_groups',['as'=>'get_complaint_groups','uses'=>'Inventory\JobCardPredefinedController@get_group_values']);

	        /* End Pre defined Complaints */




		/* End Job Card Routes */


		Route::get('transaction/{type}',['as'=>'transaction.index','uses'=>'Inventory\TransactionController@index']);

		Route::get('transaction/{type}/create',['as'=>'transaction.create','uses'=>'Inventory\TransactionController@create']);		

		Route::post('transaction/{type}',['as'=>'transaction.store','uses'=>'Inventory\TransactionController@store']);

		//Route::post('transaction/attachments/wms_attachments',['as'=>'transaction.attachment','uses'=>'Inventory\TransactionController@wms_attachments']);
		
		Route::post('transaction/attachments/wms_attachment',['as'=>'transaction.attachments','uses'=>'Inventory\TransactionController@wms_attachment']);
		Route::delete('transaction/delete_attachment',['as'=>'del.attachment','uses'=>'Inventory\TransactionController@delete_attachment']);

		Route::get('transaction-limitation',['as'=>'transaction_limitation','uses'=>'Inventory\TransactionController@transaction_limitation']);

		Route::get('sms-limitation',['as'=>'sms_limitation','uses'=>'Inventory\TransactionController@sms_limitation']);

		Route::get('promotion-sms-limitation',['as'=>'promotion_sms_limitation','uses'=>'Inventory\TransactionController@promotion_sms_limitation']);

		Route::get('ledger-limitation',['as'=>'ledger_limitation','uses'=>'Inventory\TransactionController@ledger_limitation']);

		Route::get('transaction_un_approve/',['as' => 'transaction_un_approve' ,'uses' => 'Inventory\TransactionController@transaction_un_approve'] );


		/*Route::get('transaction/{type}',['as'=>'transaction.viewdeatils','uses'=>'Inventory\TransactionController@viewdeatils']);*/

		//job_card_status_message_route

		/*Route::get('job_card/job_status_message', ['as' => 'transcation-job_status_message', 'uses' => 'Inventory\TransactionController@job_card_status_message']);
		*/

		Route::post('lowstock_store/{type}',['as'=>'lowstock_store.store','uses'=>'Inventory\TransactionController@lowstock_store']);

		Route::get('transaction/{id}/edit/{module_name?}', ['as' => 'transaction.edit', 'uses' => 'Inventory\TransactionController@edit']);
		
		Route::get('transaction_link/{id}/popup/{module_name?}', ['as' => 'transaction.popup', 'uses' => 'Inventory\TransactionController@transaction_link_popup']);

		Route::patch('transaction',['as'=>'transaction.update','uses'=>'Inventory\TransactionController@update']);

		Route::get('delete_confirmation',['as'=>'transaction.delete_confirmation','uses'=>'Inventory\TransactionController@delete_confirmation']);

		Route::delete('transaction/delete',['as'=>'transaction.destroy','uses'=>'Inventory\TransactionController@destroy']);

		Route::delete('transaction/multi-delete',['as'=>'transaction.multidestroy','uses'=>'Inventory\TransactionController@multidestroy']);

		Route::post('transaction-approve/multi-approve',['as'=>'transaction.multiapprove','uses'=>'Inventory\TransactionController@multiapprove']);

		Route::post('get/item/rate', ['as' => 'get_item_rate', 'uses' => 'Inventory\TransactionController@get_item_rate']);

		Route::post('get/transaction/preference', ['as' => 'get_customer_preference', 'uses' => 'Inventory\TransactionController@get_customer_preference']);

		// start save_job_card_status
		
		Route::post('job_card_status',['as'=>'save_job_card_status','uses'=>'Inventory\TransactionController@save_job_card_status']);
		
		// end save_job_card_status
		
		Route::post('transaction-status', 'Inventory\TransactionController@transaction_status')->name('transaction_status');

		Route::get('transactions/{type}', ['as' => 'cash_transaction.index', 'uses' => 'Inventory\CashTransactionController@index']);
		
		Route::get('get_job_card_customer_name', ['as' => 'get_job_card_customer_name', 'uses' => 'Inventory\CashTransactionController@get_job_card_customer_name']);

		Route::get('transactions/{type}/create', ['as' => 'cash_transaction.create', 'uses' => 'Inventory\CashTransactionController@create']);

		Route::post('transactions', ['as' => 'cash_transaction.store', 'uses' => 'Inventory\CashTransactionController@store']);

		Route::post('get/payment', ['as' => 'payment', 'uses' => 'Inventory\CashTransactionController@get_payment']);

		Route::post('get/invoice', ['as' => 'get_invoice', 'uses' => 'Inventory\CashTransactionController@get_invoice']);

		Route::post('get/wms_invoice', ['as' => 'get_wms_invoice', 'uses' => 'Inventory\CashTransactionController@get_wms_invoice']);

		Route::get('get/field/formats', ['as'=>'get_field_formats', 'uses'=>'Inventory\TransactionController@get_field_formats']);

		Route::get('recurring-transactions/{type}', ['as'=>'recurring_transaction', 'uses'=>'Inventory\TransactionController@recurring_transaction']);

		Route::post('get/transaction/credit-limit', ['as' => 'get_credit_limit', 'uses' => 'Inventory\TransactionController@get_credit_limit']);


		Route::post('get/transaction/get-item-details', ['as' => 'get_item_details', 'uses' => 'Inventory\TransactionController@get_item_details']);

		Route::post('get/transaction/order', ['as' => 'get_transaction_order', 'uses' => 'Inventory\TransactionController@get_transaction_order']);

		Route::post('save/field', ['as' => 'save_field', 'uses' => 'Inventory\TransactionController@save_field']);

		Route::post('transactions/print/{remote?}', ['as' => 'print_transaction', 'uses' => 'Inventory\TransactionController@print']);


		Route::post('transactions/receipt/{remote?}', ['as' => 'receipt_transaction', 'uses' => 'Inventory\TransactionController@receipt']);

		Route::post('transactions/print_receipt/{remote?}', ['as' => 'print_receipt_transaction', 'uses' => 'Inventory\TransactionController@print_receipt']);

		Route::post('transactions/generate_pdf', ['as' => 'generte_pdf', 'uses' => 'Inventory\TransactionController@generte_pdf']);
		
		Route::post('transactions/add-transaction', ['as' => 'add_to_transaction', 'uses' => 'Inventory\TransactionController@add_to_transaction']);

		Route::get('remote-transaction/{id}', ['as' => 'remote_transaction', 'uses' => 'Inventory\TransactionController@remote_transaction']);

		Route::post('remote-transaction/add-account', ['as' => 'add_to_account', 'uses' => 'Inventory\TransactionController@add_to_account']);

		Route::post('remote-transaction/item-list', ['as' => 'transaction_item_list', 'uses' => 'Inventory\TransactionController@transaction_item_list']);

		Route::post('remote-transaction/add-store', ['as' => 'add_to_store', 'uses' => 'Inventory\TransactionController@add_to_store']);

		Route::post('remote-transaction/add-expense', ['as' => 'add_to_expense', 'uses' => 'Inventory\TransactionController@add_to_expense']);

		Route::post('lowstock-account', ['as' => 'add_to_lowstock_account', 'uses' => 'Inventory\LowStockReportController@add_to_account']);
		//jc stock report
		Route::post('add-to-jc-account', ['as' => 'add_to_jc_account', 'uses' => 'Inventory\LowStockReportController@add_to_jc_account']);

		Route::post('transactions/send-all',['as'=>'transaction.send_all','uses'=>'Inventory\TransactionController@send_all']);

		Route::post('transactions/sms-send',['as'=>'transaction.sms_send','uses'=>'Inventory\TransactionController@sms_send']);

		Route::post('transactions/update-inventory',['as'=>'transaction.update_inventory','uses'=>'Inventory\TransactionController@update_inventory']);

			 //sms when estimation
      	Route::post('transactions/estimation_sms', ['as' => 'transaction.estimation_sms', 'uses' => 'Inventory\TransactionController@estimation_sms']);	
      


Route::group(['prefix' => 'inventory', 'middleware' => 'modules', 'modules' => 'inventory,trade,trade_wms,mship'], function () {

	// TRADE MODULE STARTS

		// TRADE MODULE STARTS
   		Route::get('item-type/{category}',['as'=>'category.index','uses'=>'Inventory\CategoryController@index', 'middleware' => ['permission:item-category-list']]);

		Route::get('category/{category}/create',['as'=>'category.create','uses'=>'Inventory\CategoryController@create', 'middleware' => ['permission:item-category-create']]);

		Route::post('category',['as'=>'category.store','uses'=>'Inventory\CategoryController@store', 'middleware' => ['permission:item-category-create']]);

		Route::get('category/{category}/{id}/edit', ['as' => 'category.edit', 'uses' => 'Inventory\CategoryController@edit', 'middleware' => ['permission:item-category-edit']]);

		Route::patch('category',['as'=>'category.update','uses'=>'Inventory\CategoryController@update', 'middleware' => ['permission:item-category-edit']]);

		Route::delete('category/delete',['as'=>'category.destroy','uses'=>'Inventory\CategoryController@destroy', 'middleware' => ['permission:item-category-delete']]);

		Route::post('category/status', ['as'=>'category.status','uses'=>'Inventory\CategoryController@status', 'middleware' => ['permission:item-category-edit']]);

		Route::delete('category/multidelete', ['as' => 'category.multidestroy', 'uses' => 'Inventory\CategoryController@multidestroy', 'middleware' => ['permission:item-category-delete']]);

		Route::post('category/multiapprove', ['as'=>'category.multiapprove', 'uses'=>'Inventory\CategoryController@multiapprove', 'middleware' => ['permission:item-category-edit']]);


		Route::get('type/{item}',['as'=>'item.index','uses'=>'Inventory\ItemController@index', 'middleware' => ['permission:item-list']]);
		
		Route::get('type/{item}/pagination',['as'=>'item.pagination','uses'=>'Inventory\ItemController@Item_pagination', 'middleware' => ['permission:item-list']]);
	
		Route::get('type/{item}/global_search',['as'=>'item.global_search','uses'=>'Inventory\ItemController@Item_global_search', 'middleware' => ['permission:item-list']]);

		Route::get('items/create',['as'=>'item.create','uses'=>'Inventory\ItemController@create', 'middleware' => ['permission:item-create']]);
		//to create good and serive spearately 
		Route::get('items/create/{name}',['as'=>'item.goods_create','uses'=>'Inventory\ItemController@create']);

		Route::post('items',['as'=>'item.store','uses'=>'Inventory\ItemController@store', 'middleware' => ['permission:item-create']]);
		//to add item in job card
		Route::get('jc/items/create',['as'=>'jc_item.create','uses'=>'Inventory\ItemController@jc_create', 'middleware' => ['permission:item-create']]);

		Route::get('item-batches/{id}',['as'=>'item_batches','uses'=>'Inventory\ItemController@item_batch', 'middleware' => ['permission:item-create']]);

		Route::post('select-batch',['as'=>'select_batch','uses'=>'Inventory\ItemController@select_batch', 'middleware' => ['permission:item-create']]);

		Route::get('items/item_search', ['as'=>'item_search', 'uses'=>'Inventory\ItemController@item_search']);

		Route::get('items/{id}', ['as' => 'item.show', 'uses' => 'Inventory\ItemController@show']);

		Route::get('items/{id}/edit', ['as' => 'item.edit', 'uses' => 'Inventory\ItemController@edit', 'middleware' => ['permission:item-edit']]);

		Route::patch('items',['as'=>'item.update','uses'=>'Inventory\ItemController@update', 'middleware' => ['permission:item-edit']]);

		Route::delete('items/delete',['as'=>'item.destroy','uses'=>'Inventory\ItemController@destroy', 'middleware' => ['permission:item-delete']]);

		Route::post('items/status', ['as'=>'item.status','uses'=>'Inventory\ItemController@status', 'middleware' => ['permission:item-edit']]);

		Route::post('items/get_item', ['as'=>'item.get_item','uses'=>'Inventory\ItemController@get_item']);

		Route::delete('items/multidelete', ['as' => 'item.multidestroy', 'uses' => 'Inventory\ItemController@multidestroy', 'middleware' => ['permission:item-delete']]);

		Route::post('items/multiapprove', ['as'=>'item.multiapprove', 'uses'=>'Inventory\ItemController@multiapprove', 'middleware' => ['permission:item-edit']]);

		Route::post('data/remove', ['as'=>'item.data_remove', 'uses'=>'Inventory\ItemController@data_remove']);

		Route::post('get/category-type', ['as'=>'item.get_category_type', 'uses'=>'Inventory\ItemController@get_category_type']);

		Route::post('get/main-categories', ['as'=>'item.get_main_categories', 'uses'=>'Inventory\ItemController@get_main_categories']);

		Route::post('get/categories', ['as'=>'item.get_categories', 'uses'=>'Inventory\ItemController@get_categories']);

		Route::post('get/types', ['as'=>'item.get_types', 'uses'=>'Inventory\ItemController@get_types']);

		Route::post('get/make', ['as'=>'item.get_make', 'uses'=>'Inventory\ItemController@get_make']);

		Route::post('get/identifier', ['as'=>'item.get_identifier', 'uses'=>'Inventory\ItemController@get_identifier']);		

		Route::post('items/image-upload', 'Inventory\ItemController@item_image_upload')->name('item_image_upload');

		Route::post('items/add-global-item', ['as'=>'add_global_item', 'uses'=>'Inventory\ItemController@add_global_item']);
		
		


		Route::get('group-type/{item}',['as'=>'item_group.index','uses'=>'Inventory\ItemGroupController@index']);

		Route::get('item-group/create',['as'=>'item_group.create','uses'=>'Inventory\ItemGroupController@create']);

		Route::post('item-group',['as'=>'item_group.store','uses'=>'Inventory\ItemGroupController@store']);

		Route::get('item-group/{id}/edit', ['as' => 'item_group.edit', 'uses' => 'Inventory\ItemGroupController@edit']);

		Route::patch('item-group',['as'=>'item_group.update','uses'=>'Inventory\ItemGroupController@update']);

		Route::delete('item-group/delete',['as'=>'item_group.destroy','uses'=>'Inventory\ItemGroupController@destroy']);

		Route::delete('item-group/multidelete', ['as' => 'item_group.multidestroy', 'uses' => 'Inventory\ItemGroupController@multidestroy']);

		Route::post('item-group/multiapprove', ['as'=>'item_group.multiapprove', 'uses'=>'Inventory\ItemGroupController@multiapprove']);

		Route::get('get/categories/group', ['as'=>'get_categories_group', 'uses'=>'Inventory\ItemGroupController@get_categories_group']);

		Route::post('item-group/image-upload', 'Inventory\ItemGroupController@item_group_image_upload')->name('item_group_image_upload');

		Route::post('get_item_price', ['as' => 'item_group.get_item_price', 'uses' => 'Inventory\ItemGroupController@get_item_price']);



		Route::get('adjustment',['as'=>'adjustment.index','uses'=>'Inventory\AdjustmentController@index', 'middleware' => ['permission:adjustment-list']]);

		Route::get('adjustment/create',['as'=>'adjustment.create','uses'=>'Inventory\AdjustmentController@create', 'middleware' => ['permission:adjustment-create']]);

		Route::post('adjustment',['as'=>'adjustment.store','uses'=>'Inventory\AdjustmentController@store', 'middleware' => ['permission:adjustment-create']]);

		/*Route::get('adjustment/{id}/edit', ['as' => 'adjustment.edit', 'uses' => 'Inventory\AdjustmentController@edit', 'middleware' => ['permission:adjustment-edit']]);

		Route::patch('adjustment',['as'=>'adjustment.update','uses'=>'Inventory\AdjustmentController@update', 'middleware' => ['permission:adjustment-edit']]);
*/
		Route::delete('adjustment/delete',['as'=>'adjustment.destroy','uses'=>'Inventory\AdjustmentController@destroy', 'middleware' => ['permission:adjustment-delete']]);

		Route::post('get/available/quantity', ['as'=>'adjustment.get_available_quantity', 'uses'=>'Inventory\AdjustmentController@get_available_quantity']);	

		Route::post('get/job_card_data',['as' => 'get_job_card_data', 'uses' => 'Inventory\TransactionController@get_job_card_data']);	
		 Route::get('find_transaction_reference',['as'=>'find_reference_id','uses'=>'Inventory\TransactionController@find_reference_id']);
		 
		 Route::get('jobcard_additem',['as' => 'jobcard_additem', 'uses' => 'Inventory\TransactionController@jobcard_additem']);

		 //to show sms popup
		Route::post('job_invoice_sms',['as' => 'job_invoice_sms','uses' => 'Inventory\TransactionController@show_sms_popup']);
		//send the sms and Email
		Route::post('send_sms_and_email',['as' => 'send_sms_and_email', 'uses' => 'Inventory\TransactionController@send_sms_and_email']);

		Route::post('invoice_pdf_path',['as' => 'invoice_pdf_path', 'uses' => 'Inventory\TransactionController@invoice_pdf_path']);	

		

});