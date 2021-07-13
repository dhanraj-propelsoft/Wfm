<!-- ONLY FOR LOCAL DB -->

### If you get query definder error. Please check you DB table Views
 
```
 
change DB UserName -<DB_UserName>

``DELIMITER $$

ALTER ALGORITHM=UNDEFINED DEFINER=`<DB_UserName>`@`localhost` SQL SECURITY DEFINER VIEW `jobcard_status_view` AS 
SELECT
  `propel_production_20200607`.`transactions`.`organization_id`          AS `organization_id`,
  `propel_production_20200607`.`transactions`.`id`                       AS `id`,
  `propel_production_20200607`.`transactions`.`order_no`                 AS `order_no`,
  `propel_production_20200607`.`vehicle_jobcard_statuses`.`id`           AS `Jobcard_Status_ID`,
  `propel_production_20200607`.`vehicle_jobcard_statuses`.`display_name` AS `display_name`
FROM (((`transactions`
     LEFT JOIN `wms_transactions`
       ON ((`propel_production_20200607`.`wms_transactions`.`transaction_id` = `propel_production_20200607`.`transactions`.`id`)))
    LEFT JOIN `account_vouchers`
      ON ((`propel_production_20200607`.`account_vouchers`.`id` = `propel_production_20200607`.`transactions`.`transaction_type_id`)))
   LEFT JOIN `vehicle_jobcard_statuses`
     ON (((`propel_production_20200607`.`vehicle_jobcard_statuses`.`id` = `propel_production_20200607`.`wms_transactions`.`jobcard_status_id`)
           OR ISNULL(`propel_production_20200607`.`wms_transactions`.`jobcard_status_id`))))
WHERE ((`propel_production_20200607`.`account_vouchers`.`name` = 'job_card')
       AND ISNULL(`propel_production_20200607`.`transactions`.`deleted_at`))$$

DELIMITER ;``

```
<!--END  ONLY FOR LOCAL DB -->

### while trying to install a package and If it show error `Installation failed,Reverting composer.json`. follow the below steps

```
DO NOT  delete the  vendor folder, just run the following commands to recover the composer.json

composer clear-cache
composer update
```

### installing Laravel Datatable Package for datatable
https://w3path.com/laravel-6-datatables-custom-filter-example-tutorial/

offical Document: https://github.com/yajra/laravel-datatables

Please follow the steps to installing Laravel Datatable package.

```
In Laravel version 5.6 support for  laravel-datatable 8.0

composer require yajra/laravel-datatables-oracle:"~8.0"
```

while using join query,Please keep this procedure
https://datatables.yajrabox.com/eloquent/joins


### if you get `no application encryption key has been specified` 
Please following the steps

```
1. php artisan key:generate
2. php artisan config:clear
3. php artisan config:cache
```

Please ensure  the key in app/config.php
```
'key' => env('APP_KEY')

```

## File changes  by Manimaran for Job card List  8/7/2020

```
1. propel-restructure\resources\views\layouts\master.blade.php
2. propel-restructure\resources\views\trade_wms\jobcard - all files
3. propel-restructure\resources\views\layouts\master.blade.php
4. propel-restructure\app\Http\Controllers\Inventory\JobCardController.php
5. propel-restructure\app\Http\Service - copy paste with folder
6. propel-restructure\config\app.php -  for Datatable 3rd party package 
```

Checked in using intilj Branch selected.


## Those changes performed by Manimaran for Job card List 5/8/2020

Installing Enum package 

composer require bensampo/laravel-enum

change bank_loan column datatype
ALTER TABLE `vehicle_register_details` CHANGE COLUMN `bank_loan` `bank_loan` VARCHAR(4);

Error Code: 1292. Incorrect date value: '0000-00-00' for column 'fc_due' at row 1
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '24');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '25');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '26');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '29');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '37');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '38');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '39');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '40');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '41');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '42');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '43');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '44');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '46');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '27');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE (`id` = '28');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10' WHERE (`id` = '24');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '25');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '26');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '29');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '37');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '38');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '39');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '40');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '41');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '42');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '43');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '44');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10', `tax_due` = '2019-01-10' WHERE (`id` = '46');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `tax_due` = '2019-01-10' WHERE (`id` = '24');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '24');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10' WHERE (`id` = '25');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '26');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '29');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '37');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '38');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '39');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '40');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '41');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '42');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '43');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '44');
UPDATE `propel_prod_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10', `month_due_date` = '2019-01-10' WHERE (`id` = '46');


UPDATE vehicle_register_details SET bank_loan = 'NO' WHERE bank_loan = 0; 
UPDATE vehicle_register_details SET bank_loan = 'YES' WHERE bank_loan = 1; 

Please migrate the following file
```
php artisan migrate --path=/database/migrations/jc-refactor/2020_09_03_234748_add_billing_city_id_to_transactions_table.php

or 

php artisan migrate --path=/database/migrations/jc-refactor
```

Migrate single file

```
php artisan migrate --path=/database/migrations/folderName
```



Other option for migrate single file
```
composer require sayeed/custom-migrate
```
You can migrate a specific file inside your database/migrations folder using:
```
php artisan migrate:custom -f 2018_10_14_054732_create_tests_table
```

Detailed document:
https://github.com/jbhasan/custom-migration


wms_attachments table  - uuid column using for image sequence number
we should be rename the column 


## New Job Card Table migration steps

1. Create a new project in Intellij by cloning from propel-api-blade.
    ```
    after it is cloned and project created. checkout jc_refactor_new_table branch.
    ```
2. copy the .env file from your existing project and paste it in the new project folder.
3. Use the same database
4. ```
   composer install
   
   php artisan passport:install
   
   php artisan config:cache
   
   composer dump-autoload
   
   php artisan route:clear
   
   php artisan route:list
   
   php artisan migrate --path=/database/migrations/jc-refactor
   
   ```
5. Copy or open the below file in mysql editor and execute all the sql statements in it 
     ```
    /database/migrations/jc-refactor/jobcard_tables_migration.sql
    ```
6. ``` php artisan config:cache ```