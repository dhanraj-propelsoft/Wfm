<?php

	// ACCOUNTS MODULE STARTS

		Route::group(['prefix' => 'accounts', 'middleware' => 'modules', 'modules' => 'books'], function () {

			/*Route::view('dashboard', 'accounts.dashboard')->name('books.dashboard');*/

			Route::get('dashboard', ['as' => 'books.dashboard', 'uses' => 'Accounts\DashboardController@index']);
			Route::post('account_dashboard_search', ['as' => 'account_dashboard_search', 'uses' => 'Accounts\DashboardController@account_dashboard_search']);

			Route::get('groups', ['as' => 'ledger_groups.index', 'uses' => 'Accounts\GroupController@index', 'middleware' => ['permission:ledger-group-list']]);

			Route::post('get/account_transaction/order', ['as' => 'get_account_transaction_order', 'uses' => 'Accounts\EntryController@get_transaction_order']);

			



			Route::post('group/approval', 'Accounts\GroupController@ledgergroup_approval')->name('ledgergroup_approval');

			Route::post('group/status', 'Accounts\GroupController@status')->name('group_change_status');

			Route::post('group/approval/status', 'Accounts\GroupController@approval_status')->name('change_approval_status');

			Route::post('ledger/approval', 'Accounts\LedgerController@ledger_approval')->name('ledger_approval');

			Route::post('ledger/status', 'Accounts\LedgerController@status')->name('change_ledger_status');

			Route::post('ledger/approval/status', 'Accounts\LedgerController@approval_status')->name('change_ledger_approval_status');


			Route::get('bank/state/get', 'Accounts\LedgerController@bankState')->name('get_bank_state');

			Route::get('bank/city/get', 'Accounts\LedgerController@bankCity')->name('get_bank_city');

			Route::get('bank/branch/get', 'Accounts\LedgerController@bankBranch')->name('get_bank_branch');

			Route::get('bank/code/get', 'Accounts\LedgerController@bankCode')->name('get_bank_code');

			Route::get('nbfc/branch/get', 'Accounts\LedgerController@nbfcBranch')->name('get_nbfc_branch');



			Route::get('groups/create', ['as' => 'group.create', 'uses' => 'Accounts\GroupController@create', 'middleware' => ['permission:ledger-group-create']]);

			Route::post('groups', ['as' => 'group.store', 'uses' => 'Accounts\GroupController@store', 'middleware' => ['permission:ledger-group-create']]);

			Route::get('groups/{id}', ['as' => 'group.show', 'uses' => 'Accounts\GroupController@show', 'middleware' => ['permission:ledger-group-list']]);

			Route::get('groups/{id}/edit', ['as' => 'group.edit', 'uses' => 'Accounts\GroupController@edit', 'middleware' => ['permission:ledger-group-edit']]);
			
			Route::patch('groups/update', ['as' => 'group.update', 'uses' => 'Accounts\GroupController@update', 'middleware' => ['permission:ledger-group-edit']]);

			Route::delete('groups/delete', ['as' => 'group.destroy', 'uses' => 'Accounts\GroupController@destroy', 'middleware' => ['permission:ledger-group-delete']]);


			Route::delete('group/multidelete', ['as' => 'group.multidestroy', 'uses' => 'Accounts\GroupController@multidestroy']);

			Route::post('group/multiapprove', ['as'=>'group.multiapprove', 'uses'=>'Accounts\GroupController@multiapprove']);

			Route::get('group/get', 'Accounts\GroupController@parent_group')->name('parent_group');

			Route::get('ledger/group/get', 'Accounts\LedgerController@get_ledger_group')->name('get_ledger_group');

			Route::post('ledgermodal/create', 'Accounts\LedgerController@save_ledger')->name('save_ledger');

			Route::get('bank/state/get', 'Accounts\LedgerController@bankState')->name('get_bank_state');

			Route::get('bank/city/get', 'Accounts\LedgerController@bankCity')->name('get_bank_city');

			Route::get('bank/branch/get', 'Accounts\LedgerController@bankBranch')->name('get_bank_branch');

			Route::get('bank/code/get', 'Accounts\LedgerController@bankCode')->name('get_bank_code');

			Route::get('nbfc/branch/get', 'Accounts\LedgerController@nbfcBranch')->name('get_nbfc_branch');


			Route::post('check_ledgers', 'Accounts\LedgerController@check_ledgers')->name('check_ledgers');
			
			Route::get('ledgers', ['as' => 'ledgers.index', 'uses' => 'Accounts\LedgerController@index', 'middleware' => ['permission:ledger-list']]);

			Route::get('ledger-statements', ['as' => 'ledger_statement.index', 'uses' => 'Accounts\LedgerController@statement', 'middleware' => ['permission:ledger-list']]);

			Route::get('ledgers/create/{ledger_type?}', ['as' => 'ledgers.create', 'uses' => 'Accounts\LedgerController@create', 'middleware' => ['permission:ledger-create']]);

			Route::post('ledgers', ['as' => 'ledgers.store', 'uses' => 'Accounts\LedgerController@store', 'middleware' => ['permission:ledger-create']]);

			Route::get('ledgers/{id}/edit', ['as' => 'ledgers.edit', 'uses' => 'Accounts\LedgerController@edit', 'middleware' => ['permission:ledger-edit']]);

			Route::patch('ledgers/update', ['as' => 'ledgers.update', 'uses' => 'Accounts\LedgerController@update', 'middleware' => ['permission:ledger-edit']]);

			Route::delete('ledgers/delete', ['as'=>'ledgers.destroy', 'uses'=>'Accounts\LedgerController@destroy', 'middleware' => ['permission:ledger-edit']]);

			Route::delete('ledgers/multidelete', ['as' => 'ledgers.multidestroy', 'uses' => 'Accounts\LedgerController@multidestroy', 'middleware' => ['permission:ledger-delete']]);

			Route::post('ledgers/multiapprove', ['as'=>'ledgers.multiapprove', 'uses'=>'Accounts\LedgerController@multiapprove', 'middleware' => ['permission:ledger-edit']]);

			Route::get('ledgers/{id}', ['as' => 'ledgers.show', 'uses' => 'Accounts\LedgerController@show', 'middleware' => ['permission:ledger-list']]);


			Route::get('cheque-book', ['as' => 'cheque_book.index', 'uses' => 'Accounts\ChequeBookController@index', 'middleware' => ['permission:cheque-book-list']]);

			Route::get('cheque-book/{id}/edit', ['as' => 'cheque_book.edit', 'uses' => 'Accounts\ChequeBookController@edit', 'middleware' => ['permission:cheque-book-edit']]);

			Route::get('cheque-book/continue/{id}/edit', ['as' => 'cheque_books_continue', 'uses' => 'Accounts\ChequeBookController@continue_edit', 'middleware' => ['permission:cheque-book-edit']]);

			Route::patch('cheque-book/update', ['as' => 'cheque_book.update', 'uses' => 'Accounts\ChequeBookController@update', 'middleware' => ['permission:cheque-book-edit']]);

			Route::delete('cheque-book', ['as' => 'cheque_book.destroy', 'uses' => 'Accounts\ChequeBookController@destroy', 'middleware' => ['permission:cheque-book-delete']]);


			Route::get('transactions', ['as' => 'vouchers.index', 'uses' => 'Accounts\EntryController@index', 'middleware' => ['permission:voucher-list']]);

			Route::post('Filtered_transactions', ['as' => 'vouchers.get_all_transactions', 'uses' => 'Accounts\EntryController@get_all_transactions']);

			Route::get('vouchers/create', ['as' => 'vouchers.create', 'uses' => 'Accounts\EntryController@create', 'middleware' => ['permission:voucher-create']]);

			Route::post('vouchers', ['as' => 'vouchers.store', 'uses' => 'Accounts\EntryController@store', 'middleware' => ['permission:voucher-create']]);

			Route::get('vouchers/{id}/edit', ['as' => 'vouchers.edit', 'uses' => 'Accounts\EntryController@edit', 'middleware' => ['permission:voucher-edit']]);

			Route::patch('vouchers/update', ['as' => 'vouchers.update', 'uses' => 'Accounts\EntryController@update']);

			Route::delete('vouchers/delete', ['as' => 'vouchers.destroy', 'uses' => 'Accounts\EntryController@destroy', 'middleware' => ['permission:voucher-delete']]);

			Route::post('get/ledgers', 'Accounts\EntryController@get_ledgers')->name('get_ledgers');

			Route::post('get/ledger-transactions', 'Accounts\EntryController@get_transactions')->name('get_transactions');

			Route::get('get_cheque', ['as' => 'get_cheque', 'uses' => 'Accounts\EntryController@get_cheque']);

			Route::post('get/reference-type', 'Accounts\EntryController@get_reference_type')->name('get_reference_type');


			Route::get('ledger/{id}/{parent}', ['as' => 'ledger', 'uses' => 'Accounts\ReportController@ledger_report']);

			Route::get('inventory_report/{id}', ['as' => 'inventory_report', 'uses' => 'Accounts\ReportController@inventory_report']);

			Route::get('purchase_process/{id}', ['as' => 'purchase_process', 'uses' => 'Accounts\ReportController@purchase_process']);

			Route::post('ledger/get', ['as' => 'get_ledger', 'uses' => 'Accounts\ReportController@get_ledger_report']);

			Route::get('trial-balance', ['as' => 'trial_balance', 'uses' => 'Accounts\ReportController@trial_balance']);

			Route::post('trial-balance/get', ['as' => 'get_trial_balance', 'uses' => 'Accounts\ReportController@get_trial_balance']);

			Route::get('journal-report', ['as' => 'journal_report', 'uses' => 'Accounts\ReportController@journal_report']);

			Route::post('journal-report/get', ['as' => 'get_journal_report', 'uses' => 'Accounts\ReportController@get_journal_report']);

			Route::get('stock-report', ['as' => 'stock_report', 'uses' => 'Accounts\ReportController@stock_report']);

			Route::post('stock-report/get', ['as' => 'get_stock_report', 'uses' => 'Accounts\ReportController@get_stock_report']);

			Route::get('income-expense', ['as' => 'profit_and_loss', 'uses' => 'Accounts\ReportController@profit_and_loss']);

			Route::post('income-expense/get', ['as' => 'get_profit_and_loss', 'uses' => 'Accounts\ReportController@get_profit_and_loss']);

			Route::get('balance-sheet', ['as' => 'balance_sheet', 'uses' => 'Accounts\ReportController@balance_sheet']);

			Route::post('balance-sheet/get', ['as' => 'get_balance_sheet', 'uses' => 'Accounts\ReportController@get_balance_sheet']);


			Route::get('financial-year/{id}/edit', 'Accounts\FinancialYearController@edit')->name('financial_year.edit');

			Route::patch('financial-year/update', 'Accounts\FinancialYearController@update')->name('financial_year.update');
            
            
            Route::get('financial_year/list', ['as' => 'financialyear_list.index', 'uses' => 'Accounts\FinancialYearController@index']);

			Route::get('financial_year/create/{id?}', ['as' => 'financial_year.create', 'uses' => 'Accounts\FinancialYearController@create']);

			Route::post('financial_year/store/{id?}', ['as' => 'financial_year.store', 'uses' => 'Accounts\FinancialYearController@store']);

			Route::post('change_current_year', ['as' => 'change_current_year', 'uses' => 'Accounts\FinancialYearController@change_current_year']);

			Route::post('fiscal_year_duplicate', ['as' => 'fiscal_year_duplicate', 'uses' => 'Accounts\FinancialYearController@fiscal_year_duplicate']);

			Route::get('voucher/list', ['as' => 'voucher_list.index', 'uses' => 'Accounts\VoucherController@index', 'middleware' => ['permission:voucher-master-list']]);

			Route::get('voucher/list/create', ['as' => 'voucher_list.create', 'uses' => 'Accounts\VoucherController@create', 'middleware' => ['permission:voucher-master-create']]);

			Route::post('voucher/list', ['as' => 'voucher_list.store', 'uses' => 'Accounts\VoucherController@store', 'middleware' => ['permission:voucher-master-create']]);

			Route::get('voucher/list/{id}/edit', ['as' => 'voucher_list.edit', 'uses' => 'Accounts\VoucherController@edit', 'middleware' => ['permission:voucher-master-edit']]);

			Route::patch('voucher/list/update', ['as' => 'voucher_list.update', 'uses' => 'Accounts\VoucherController@update', 'middleware' => ['permission:voucher-master-edit']]);

			Route::get('voucher/list/{id}', ['as' => 'voucher_list.show', 'uses' => 'Accounts\VoucherController@show', 'middleware' => ['permission:voucher-master-list']]);

			Route::delete('voucher/list', ['as' => 'voucher_list.destroy', 'uses' => 'Accounts\VoucherController@destroy', 'middleware' => ['permission:voucher-master-delete']]);

			//Expense_master
		Route::get('expense_masters', ['as' => 'expense_masters.index', 'uses' => 'Accounts\ExpenseMastersController@index']);
         	
        Route::get('expense_masters/create', ['as' => 'expense_masters.create', 'uses' => 'Accounts\ExpenseMastersController@create']);

        Route::post('expense_masters/store/{id?}', ['as' => 'expense_masters.store', 'uses' => 'Accounts\ExpenseMastersController@store']);

        Route::get('expense_masters/{id}/edit', ['as' => 'expense_masters.edit', 'uses' => 'Accounts\ExpenseMastersController@edit']);

        Route::delete('expense_masters/delete', ['as' => 'expense_masters.destroy', 'uses' => 'Accounts\ExpenseMastersController@destroy']);

        Route::post('expense_masters_approval',['as'=>'expense_masters.approval','uses'=>'Accounts\ExpenseMastersController@expense_masters_approval']);

		Route::delete('expense_masters/multidelete', ['as' => 'expense_masters.multidestroy', 'uses' => 'Accounts\ExpenseMastersController@multidestroy']);			

		Route::post('expense_masters/multiapprove', ['as'=>'expense_masters.multiapprove', 'uses'=>'Accounts\ExpenseMastersController@multiapprove']);

		Route::post('check-expense_masters', 'Accounts\ExpenseMastersController@expense_name')->name('expense_name');

		//Expenses
			Route::get('expenses', ['as' => 'expenses.index', 'uses' => 'Accounts\ExpensesController@index']);
			
			 Route::post('expenses/store', ['as' => 'expenses.store', 'uses' => 'Accounts\ExpensesController@store']);

			 Route::get('expenses/show', ['as' => 'expenses.show', 'uses' => 'Accounts\ExpensesController@show']);
			 
			 Route::patch('expenses/update',['as'=>'expenses.update','uses'=>'Accounts\ExpensesController@update']);

			 Route::post('expenses/report', ['as' => 'expenses.report', 'uses' => 'Accounts\ExpensesController@report']);


			  //Bank Transactions

			 Route::get('bank_transactions', ['as' => 'bank_transactions.index', 'uses' => 'Accounts\BankTransactionController@index']);

			 Route::get('/get_to_account',['as' => 'get_to_account','uses' => 'Accounts\BankTransactionController@get_to_account']);

			 Route::post('bank_transactions/store', ['as' => 'bank_transactions.store', 'uses' => 'Accounts\BankTransactionController@store']);

			  Route::get('bank_transactions/{id}/edit', ['as' => 'bank_transactions.edit', 'uses' => 'Accounts\BankTransactionController@edit']);

			  Route::patch('bank_transactions/update',['as'=>'bank_transactions.update','uses'=>'Accounts\BankTransactionController@update']);

			  Route::delete('bank_transactions/delete', ['as' => 'bank_transactions.destroy', 'uses' => 'Accounts\BankTransactionController@destroy']);

			  Route::post('bank_transactions/search', ['as' => 'bank_transactions.search', 'uses' => 'Accounts\BankTransactionController@search_result']);

			  Route::post('bank_transactions/reset_options', ['as' => 'bank_transactions.reset', 'uses' => 'Accounts\BankTransactionController@reset_options']);

			  // Company Expenses

			Route::get('company_expenses', ['as' => 'company_expenses.index', 'uses' => 'Accounts\CompanyExpensesController@index']); 

			Route::post('company_expenses/get_address', ['as' => 'company_expenses.get_address', 'uses' => 'Accounts\CompanyExpensesController@get_address']); 

			Route::post('company_expenses/store', ['as' => 'company_expenses.store', 'uses' => 'Accounts\CompanyExpensesController@store']);

			Route::get('company_expenses/{id}/edit', ['as' => 'company_expenses.edit', 'uses' => 'Accounts\CompanyExpensesController@edit']);
			
			Route::patch('company_expenses/update',['as'=>'company_expenses.update','uses'=>'Accounts\CompanyExpensesController@update']);

			Route::delete('company_expenses/delete', ['as' => 'company_expenses.destroy', 'uses' => 'Accounts\CompanyExpensesController@destroy']);

			Route::post('company_expenses/search', ['as' => 'company_expenses.search', 'uses' => 'Accounts\CompanyExpensesController@search_result']);
			
			
			Route::get('transactions/cash_payment/{type_name}', ['as' => 'cash_payment.index', 'uses' => 'Accounts\EntryController@cash_payment_index', 'middleware' => ['permission:voucher-list']]);

			Route::get('vouchers/transaction_entries_create/{type_name}', ['as' => 'vouchers.transaction_entries_create', 'uses' => 'Accounts\EntryController@transaction_entries_create', 'middleware' => ['permission:voucher-create']]);

			Route::post('Filtered_transactions_datas', ['as' => 'vouchers.get_all_transactions_datas', 'uses' => 'Accounts\EntryController@get_all_transactions_datas']);

			Route::get('vouchers/{id}/transaction_entries_edit', ['as' => 'vouchers.transaction_entries_edit', 'uses' => 'Accounts\EntryController@transaction_entries_edit', 'middleware' => ['permission:voucher-edit']]);

			Route::get('transaction/order_no_search', ['as' => 'transactions_order_no_search', 'uses' => 'Accounts\EntryController@transactions_order_no_search']);

			Route::post('get_recepits_id', ['as' => 'get_recepits_id', 'uses' => 'Accounts\EntryController@get_recepits_id']);

		});

	// ACCOUNTS MODULE ENDS 

