# Master Branch File 

## Steps to Build in local

##### When installing local build, 'composer install' failed to load '.env' file. Error was thrown in bootstrap/app.php. so removed the below line from this file

```
$env = file_get_contents(__DIR__.'/../.env');

$dotenv = new \Dotenv\Dotenv(__DIR__.'/../', '.'.$env.'.env');
$dotenv->overload();

```

###Logging changes
##### Slack logging is removed from config/logging.php just for local build. when moving to production, it has to be added.

/config/logging.php

Remove slack from the channels

```
 'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],
        
        
Add below to .env file to remove slack URL

LOG_SLACK_WEBHOOK_URL=""

```
## Log Viewer

Steps to install and config : https://www.itsolutionstuff.com/post/laravel-56-log-viewer-using-logviewer-package-exampleexample.html
							  https://github.com/ARCANEDEV/LogViewer/blob/HEAD/_docs/1.Installation-and-Setup.md#version-compatibility

URL to access: http://localhost/propel-api/log-viewer

http://qp.web.mypropelsoft.com/log-viewer/logs
http://qa.mypropelsoft.com/log-viewer/logs
http://mypropelsoft.com/log-viewer/logs
http://qp.api.mypropelsoft.com/log-viewer/logs

```

.env
LOG_CHANNEL=daily (can change this back to stack after completion, so that slack notification will be enabled back)

version 4.5.0 support laravel 5.6.*
composer require arcanedev/log-viewer:~4.5.0

if above fails with memory error follow below
	rm -rf vendor/
	rm -rf composer.lock
	composer install --prefer-dist
	composer require arcanedev/log-viewer:~4.5.0


config/app.php
'providers' => [
		....
		Arcanedev\LogViewer\LogViewerServiceProvider::class,
	]


php artisan log-viewer:publish

composer dump-autoload

php artisan config:cache

php artisan log-viewer:check

override code: override the code to add user and support prople log format

copy below files and paste them in corresponding folder under "/vendor/arcanedev/log-viewer" src folders

/assets/OverrideCode/log-viewer/Entities/LogEntry.php
/assets/OverrideCode/log-viewer/Utilities/Filesystem.php
/assets/OverrideCode/log-viewer/constants.php

```


##### Transactioncontroller.php failed with item_group undefined variable in edit function. the same works fine in production.
##### dashboardcontroller.php in accounts failed with 'sale_value' undefined while clicking books icon.


prepare .env file with environment details

```
composer install

php artisan passport:install

php artisan config:cache

composer dump-autoload

php artisan route:clear

php artisan route:list

```



 Test Dhana
### SMS Notification ###
 1)Create Notification Folder.
 2)Create Postman Folder.
 3)Create NotificationTableSeeder.
 ### Migration File ###
 
 
 ### Ajith Notification Work And Email Condition Code And New Ledger Add Code In Pettycash expenmses Screen###
 ### Ajith Tasks And It Explanations ###
 1.Notification work:
     TransactionController@transaction_item_list->undefined => bug fix for item show remote transactions
     inventory/transaction_add_account.blade--shows remote(line) =>description show & Unset for value select item & on change Item yellow color remove and unset description
     NotificationController@notifications-->transaction query add created_at & foreach loop time change
     NotificationController@get_notifications-->transaction query add created_at & foreach loop time change & add query for receipts 
     settings/Notifcation.blade-->add tooltip & style if condtion change & hide code for Make Payment ,Make receipt
     layouts.Master.blade-->function setNotification Code are unhide and Tooltip add
 2.SMS and Email Work:
     inventory/transaction.blade--->add div for pdf_view_print & add jspdf plugin
     inventory/TransactionController@print-->add return transaction_id & custome value query and return custome value
     inventory/job_invoice_sms.blade-->add new feature for send email
     route name change transaction in send_sms into send_sms_email
     add route for transactions in invoice pdf path
     inventory/TransactionController@show_sms_popup-->add query for get emailid in transaction table
     inventory/TransactionController@send_sms_email-->rename  send_sms into send_sms_email
     inventory/TransactionController@send_sms_email-->add send email code 
     create folder for Public/Invoice Pdf Path because invoice pdf are store in this folder
 3.Account Ledger Work:
     Accounts/ExpenseMasterController@create-->pluck for ledger
     Accounts/expense_master_create.blade-->add new dropdown for ledger(all_replace)
     add new colmun for DB expenses Table for ledger_id
     Accounts/ExpenseMasterController@store-->add new line for save ledger in expense table
     Accounts/ExpenseMasterController@edit-->pluck for ledger
     Accounts/ExpenseMasterController@update-->add new line for save ledger in expense table
     Accounts/ExpenseMasterController@expense_name --->checking for validations
     Add new route for Accounts in expense_name
     expense_master.blade-->ledger_add click fn
     Accounts/expense_master_edit.blade-->add new dropdown for ledger(all_replace)
     import new file inventory/ledger_create_popup & models/crud_modal_small.blade
     layouts.master.blade-->add include crud_modal_small
     change Accounts route ledger_type -->add parameters
     Accounts/LedgerController@create-->add if condition
     Accounts/expense.blade---->add tr coulmn ledger_id & Update click event chnage debit ledger into map function & editclick event change debit ledger into map function
     Accounts/ExpenseController@store--->store ledger_into account_transaction.debit_ledger_id 
     Accounts/ExpenseController@update--->store ledger_into account_transaction.debit_ledger_id

 1)Add One Extra field Expenses Table 
    1.1)php artisan make:migration ledger_id_to_expenses_table --table=expenses
    1.2)After Add This Code In Schema File
     $table->integer('ledger_id')->unsigned()->nullable();
     $table->foreign('ledger_id')->references('id')->on('account_legers')->onUpdate('cascade')->onDelete('set null');
    1.3)php artisan migrate:custom -f 2020_08_08_172552_ledger_id_to_expenses_table.
    
    
    
### Composer update error with Memory allocation

#### Error:
 ```
 [ErrorException]                                   
  proc_open(): fork failed - Cannot allocate memory
```
```
Copy the vendor folder from production to the local project folder
Copy composer.lock  from production to the local project folder
run the below command
php -dmemory_limit=2G composer.phar require sayeed/custom-migrate
Copy the vendor folder from local to production
Copy composer.lock  from local to production
```    