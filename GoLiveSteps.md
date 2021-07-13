##Logging changes

```
/config/logging.php

change single channel to daily
Add slack to the channels
change the slack channel user name to the proper Build name (QP Build / Production Build)

 'stack' => [
            'driver' => 'stack',
            'channels' => ['daily','slack'],
        ],
        
        
Add below to .env file with the correct slack URL

LOG_SLACK_WEBHOOK_URL="<URL goes here>"
    
```


#####################################################################
## Release Aug 2020 
#####################################################################

#### Requirement: Notification Work And Email Condition Code And New Ledger Add Code In Pettycash expenmses Screen###
#### Owner : Ajith

#### Requirement: SMS notification - categories OTP/Transaction/Promotion SMS and change the logic for the new framework
#### Owner: Dhanraj
 
#### Steps

* Dependency: Deploy Propel_api code before the below steps.
 
```
* Download code from api-blade repository master branch
* Make changes to logging file as stated in the Logging Changes section above.
* Copy code to server - refer Folders to Move section below
* php artisan config:cache
* composer dump-autoload
* php artisan route:clear
* php artisan migrate --path=/database/migrations/rel_aug_2020/  
* composer dump-autoload  
```

##### Folders To move
Task-1:(accounts->Petty Cash Expenses Screen)Old
* database\migrations\rel_aug_2020

* app
 1)ExpenseMastersController->create.
 2)ExpenseMastersController->store.
 3)ExpenseMastersController->Edit.
 4)ExpenseMastersController->createmodel.
 5)ExpenseMastersController->SaveToModel.
* resource\views   
	1)Accounts->expense_master_create.
	1)Accounts->expense_master_Edit.
* routes
Task 2:(Settings->Organization Expense Rate)New
*Database\migrations\rel_aug_2020
    2020_08_11_155531_create_wms_organization_costs_table.
 *App
    Settings controller->org_expense_rate_index.
    Settings controller->organizations_cost_update.
 *Model
    WmsOrganizationCost
 *Resources\Views
    settings.org_expense_rate
    includes->settings.
 *routes:
 
 
 
#####################################################################
## Release Aug & JC refactor 2020 
#####################################################################

#### Steps

* Dependency: Deploy Propel_api code before the below steps.
 
```
* Download code from api-blade repository master branch

* Copy code to server - refer Folders to Move section below

* composer update **** DO NOT RUN THIS
*** COPY composer.lock and Vendor folder from "propel_dev/main folder"
---- ran in my local system and copied vendor folder to server with composer.lock file
---- Check log viewer config changes from readme file and do the required steps

* composer dump-autoload

* php artisan config:cache

* php artisan route:clear

* php artisan migrate --path=/database/migrations/rel_aug_2020  
---- failed for run file for forgien key

* php artisan migrate --path=/database/migrations/jc-refactor

* composer dump-autoload 

* php artisan view:clear 
```

##### Folders To move
```
app
assets
compoesr.json
config
database/migration/jc_refactor
database/migration/rel_aug_2020
golivesteps
public/packages
pulic/tier_spinner
readme.md
release
resource/views
routes
```

##  RUN DB Queries 
* Change bank_loan column datatype

```
ALTER TABLE `propel_prod_main`.`vehicle_register_details` CHANGE COLUMN `bank_loan` `bank_loan` VARCHAR(4);
```

* Update column value in vehicle_register_detail table
```
UPDATE `propel_prod_main`.`vehicle_register_details` SET bank_loan = 'NO' WHERE bank_loan = 0; 
UPDATE `propel_prod_main`.`vehicle_register_details` SET bank_loan = 'YES' WHERE bank_loan = 1; 
```

* Error Code: 1292. Incorrect date value: '0000-00-00' for column 'fc_due' at row 1

```
UPDATE `propel_production_main`.`vehicle_register_details` SET `fc_due` = '2019-01-10' WHERE id in 
(SELECT t1.id FROM 
 (select id from `propel_production_main`.`vehicle_register_details` t2 WHERE '0000-00-00' IN (t2.fc_due)) as t1
);

UPDATE `propel_production_main`.`vehicle_register_details` SET `tax_due` = '2019-01-10' WHERE id in 
(SELECT t1.id FROM 
 (select id from `propel_production_main`.`vehicle_register_details` t2 WHERE '0000-00-00' IN (t2.tax_due)) as t1
);

UPDATE `propel_production_main`.`vehicle_register_details` SET `permit_due` = '2019-01-10' WHERE id in 
(SELECT t1.id FROM 
 (select id from `propel_production_main`.`vehicle_register_details` t2 WHERE '0000-00-00' IN (t2.permit_due)) as t1
);

UPDATE `propel_production_main`.`vehicle_register_details` SET `premium_date` = '2019-01-10' WHERE id in 
(SELECT t1.id FROM 
 (select id from `propel_production_main`.`vehicle_register_details` t2 WHERE '0000-00-00' IN (t2.premium_date)) as t1
);


UPDATE `propel_production_main`.`vehicle_register_details` SET `month_due_date` = '2019-01-10' WHERE id in 
(SELECT t1.id FROM 
 (select id from `propel_production_main`.`vehicle_register_details` t2 WHERE '0000-00-00' IN (t2.month_due_date)) as t1
);
```


# Create new table for jobcard and migrated data 

```
* execute the queries in the sql file one by one database\migrations\jc-refactor\jobcard_tables_migration.sql
--- delete statements will take time in server. leave 10min to complete. check the row count to see if delete completed
```


#logviewer follow steps

copy below files and paste them in corresponding folder under "/vendor/arcanedev/log-viewer" src folders

```
/assets/OverrideCode/log-viewer/Entities/LogEntry.php
/assets/OverrideCode/log-viewer/Utilities/Filesystem.php
/assets/OverrideCode/log-viewer/constants.php

```







#### Steps **** DO NOT USE ***

#### Requirement: Refactoring Jobcard 

### Following steps performed by Manimaran





## Run following  artisan Commands
 ```
 composer install

 composer-update

 php artisan route:clear

 php artisan route:list

 ```

 ## Migrate the newly added column in existing table

```
 php artisan migrate --path=/database/migrations/jc-refactor

```
# If migratation process is failed or show the result 'Nothing to migrate'. 
* Please follow this steps

 1. Please run this command to install custom migrate package
```
   composer require sayeed/custom-migrate
``` 

* Migrate specific directory

You can migrate a specific directory inside your database/migrations folder using:
```
php artisan migrate:custom -d migrations-subfolder
```

* You can migrate a specific file inside your database/migrations folder using:
```
php artisan migrate:custom -f <2018_10_14_054732_create_tests_table> --<migration file name>
```

##  RUN DB Queries 
* Change bank_loan column datatype

ALTER TABLE `propel_prod_main`.`vehicle_register_details` CHANGE COLUMN `bank_loan` `bank_loan` VARCHAR(4);

* Update column value in vehicle_register_detail table
```
UPDATE `propel_prod_main`.`vehicle_register_details` SET bank_loan = 'NO' WHERE bank_loan = 0; 
UPDATE `propel_prod_main`.`vehicle_register_details` SET bank_loan = 'YES' WHERE bank_loan = 1; 
```

* Error Code: 1292. Incorrect date value: '0000-00-00' for column 'fc_due' at row 1

```
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
```

# Add specific table for Jobcard 

* Please find sql file inside migrations\jc-refactor folder
\database\migrations\jc-refactor\jobcard_tables_migration.sql

* Just copy, Please rename the database name and run the DB script  
