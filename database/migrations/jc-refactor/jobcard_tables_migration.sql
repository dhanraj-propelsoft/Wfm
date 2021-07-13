-- create table from the main tables
create table job_cards as select * from transactions;

create table job_card_items as select * from transaction_items;

create table job_card_details as select * from wms_transactions;

create table job_card_attachments as select * from wms_attachments;

create table job_card_checklists as select * from wms_checklists;

ALTER TABLE job_cards ENGINE=InnoDB;
ALTER TABLE job_card_items ENGINE=InnoDB;
ALTER TABLE job_card_details ENGINE=InnoDB;
ALTER TABLE job_card_attachments ENGINE=InnoDB;
ALTER TABLE job_card_checklists ENGINE=InnoDB;

-- set safe update
SET SQL_SAFE_UPDATES = 0;

/* NOTE: ALL DELETE are taking LONG time in server, please wait for it to complete... it may timeout, just leave 10-20min and check the table count*/

/* Delete non jobcard related entries */
-- details 
select count(*) from job_cards where transaction_type_id in (select id from account_vouchers where name='job_card');
select * from job_cards where transaction_type_id in (select id from account_vouchers where name='job_card');
-- 5184
select count(*) from job_cards where transaction_type_id not in (select id from account_vouchers where name='job_card');
-- 9340
delete from job_cards where transaction_type_id not in (select id from account_vouchers where name='job_card');

-- wms transaction
select count(*) from job_card_details where transaction_id in (select id from job_cards);
select * from job_card_details where transaction_id in (select id from job_cards);
-- 5178
select count(*) from job_card_details where transaction_id not in (select id from job_cards);
-- 5807
delete from job_card_details where transaction_id not in (select id from job_cards);

-- wms items
select count(*) from job_card_items where transaction_id in (select id from job_cards);
select * from job_card_items where transaction_id in (select id from job_cards);
-- 21132
select count(*) from job_card_items where transaction_id not in (select id from job_cards);
-- 47213
delete from job_card_items where transaction_id not in (select id from job_cards);

-- wms attachement
select count(*) from job_card_attachments where transaction_id in (select id from job_cards);
select * from job_card_attachments where transaction_id in (select id from job_cards);
-- 5882
select count(*) from job_card_attachments where transaction_id not in (select id from job_cards);
-- 0
-- delete from job_card_attachments where transaction_id not in (select id from job_cards);

-- wms checklist
select count(*) from job_card_checklists where transaction_id in (select id from job_cards);
select * from job_card_checklists where transaction_id in (select id from job_cards);
-- 7972
select count(*) from job_card_checklists where transaction_id not in (select id from job_cards);
-- 39
delete from job_card_checklists where transaction_id not in (select id from job_cards);

/* Change transaction id column name to job_card_id */
ALTER TABLE `propel_production_main`.`job_card_details` 
CHANGE COLUMN `transaction_id` `job_card_id` INT(10) UNSIGNED NULL DEFAULT NULL ;
select * from job_card_details;

ALTER TABLE `propel_production_main`.`job_card_items` 
CHANGE COLUMN `transaction_id` `job_card_id` INT(10) UNSIGNED NOT NULL ;
select * from job_card_items;

ALTER TABLE `propel_production_main`.`job_card_attachments` 
CHANGE COLUMN `transaction_id` `job_card_id` INT(10) UNSIGNED NULL DEFAULT NULL ;
select * from job_card_attachments;

ALTER TABLE `propel_production_main`.`job_card_checklists` 
CHANGE COLUMN `transaction_id` `job_card_id` INT(10) UNSIGNED NOT NULL ;
select * from job_card_checklists;

/* adding index and constraints */
--
-- Indexes for table `job_cards`
--
ALTER TABLE `job_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_cards_created_by_foreign` (`created_by`),
  ADD KEY `job_cards_last_modified_by_foreign` (`last_modified_by`),
  ADD KEY `job_cards_ledger_id_foreign` (`ledger_id`),
  ADD KEY `job_cards_transaction_type_id_foreign` (`transaction_type_id`),
  ADD KEY `job_cards_reference_id_foreign` (`reference_id`),
  ADD KEY `job_cards_reference_type_id_foreign` (`reference_type_id`),
  ADD KEY `job_cards_organization_id_foreign` (`organization_id`),
  ADD KEY `job_cards_entry_id_foreign` (`entry_id`),
  ADD KEY `job_cards_payment_mode_id_foreign` (`payment_mode_id`),
  ADD KEY `job_cards_term_id_foreign` (`term_id`),
  ADD KEY `job_cards_employee_id_foreign` (`employee_id`),
  ADD KEY `job_cards_shipment_mode_id_foreign` (`shipment_mode_id`);
  
--
-- AUTO_INCREMENT for table `job_cards`
-- TODO : get the max id and use it in the AUTO_INCREMENT to increment it by 10
--
select max(id) from job_cards;

ALTER TABLE `job_cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19041;

--
-- Constraints for table `job_cards`
--
ALTER TABLE `job_cards`
  ADD CONSTRAINT `job_cards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_entry_id_foreign` FOREIGN KEY (`entry_id`) REFERENCES `account_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_last_modified_by_foreign` FOREIGN KEY (`last_modified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_ledger_id_foreign` FOREIGN KEY (`ledger_id`) REFERENCES `account_ledgers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_payment_mode_id_foreign` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_modes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_reference_type_id_foreign` FOREIGN KEY (`reference_type_id`) REFERENCES `reference_vouchers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_shipment_mode_id_foreign` FOREIGN KEY (`shipment_mode_id`) REFERENCES `shipment_modes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_cards_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `account_vouchers` (`id`) ON UPDATE CASCADE;  
  
--
-- Indexes for table `job_card_items`
--
ALTER TABLE `job_card_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_card_items_item_id_foreign` (`item_id`),
  ADD KEY `job_card_items_assigned_employee_id_foreign` (`assigned_employee_id`),
  ADD KEY `job_card_items_tax_id_foreign` (`tax_id`),
  ADD KEY `job_card_items_discount_id_foreign` (`discount_id`),
  ADD KEY `job_card_items_job_card_id_foreign` (`job_card_id`);

--
-- AUTO_INCREMENT for table `job_card_items`
-- TODO: check the max id from the table and increament it by 10 use it here
--
select max(id) from job_card_items;

ALTER TABLE `job_card_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188481;


--
-- Constraints for table `job_card_items`
--
ALTER TABLE `job_card_items`
  ADD CONSTRAINT `job_card_items_assigned_employee_id_foreign` FOREIGN KEY (`assigned_employee_id`) REFERENCES `hrm_employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_items_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_items_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `tax_groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_items_job_card_id_foreign` FOREIGN KEY (`job_card_id`) REFERENCES `job_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;    
  
--
-- Indexes for table `job_card_details`
--
ALTER TABLE `job_card_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_card_details_job_card_id_foreign` (`job_card_id`),
  ADD KEY `job_card_details_registration_id_foreign` (`registration_id`),
  ADD KEY `job_card_details_vehicle_usage_id_foreign` (`vehicle_usage_id`),
  ADD KEY `job_card_details_assigned_to_foreign` (`assigned_to`),
  ADD KEY `job_card_details_delivery_by_foreign` (`delivery_by`),
  ADD KEY `job_card_details_created_by_foreign` (`created_by`),
  ADD KEY `job_card_details_last_modified_by_foreign` (`last_modified_by`),
  ADD KEY `pump_id` (`pump_id`);

--
-- AUTO_INCREMENT for table `job_card_details`
-- TODO : get max id and increment by 10
--
select max(id) from job_card_details;

ALTER TABLE `job_card_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14602;

--
-- Constraints for table `job_card_details`
--
ALTER TABLE `job_card_details`
  ADD CONSTRAINT `job_card_details_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `hrm_employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_delivery_by_foreign` FOREIGN KEY (`delivery_by`) REFERENCES `hrm_employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_ibfk_1` FOREIGN KEY (`pump_id`) REFERENCES `fsm_pumps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_last_modified_by_foreign` FOREIGN KEY (`last_modified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `vehicle_register_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_job_card_id_foreign` FOREIGN KEY (`job_card_id`) REFERENCES `job_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_card_details_vehicle_usage_id_foreign` FOREIGN KEY (`vehicle_usage_id`) REFERENCES `vehicle_usages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;  
  
--
-- Indexes for table `job_card_attachments`
--
ALTER TABLE `job_card_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_card_attachments_job_card_id_foreign` (`job_card_id`);

--
-- AUTO_INCREMENT for table `job_card_attachments`
--
select max(id) from job_card_attachments;

ALTER TABLE `job_card_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6391;

--
-- Constraints for table `job_card_attachments`
--
ALTER TABLE `job_card_attachments`
  ADD CONSTRAINT `job_card_attachments_job_card_id_foreign` FOREIGN KEY (`job_card_id`) REFERENCES `job_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;     
  
--
-- Indexes for table `job_card_checklists`
--
ALTER TABLE `job_card_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_card_checklists_job_card_id_foreign` (`job_card_id`);

--
-- AUTO_INCREMENT for table `job_card_checklists`
--
select max(id) from job_card_checklists;

ALTER TABLE `job_card_checklists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15829;
  
--
-- Constraints for table `job_card_checklists`
--
ALTER TABLE `job_card_checklists`
  ADD CONSTRAINT `job_card_checklists_job_card_id_foreign` FOREIGN KEY (`job_card_id`) REFERENCES `job_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
  
--
-- Update data for the new column in transactions table
--
update `transactions` set originated_from_type='App\\Http\\Controllers\\Tradewms\\Jobcard\\Model\\JobCard', originated_from_id=id 
where  transaction_type_id in (select id from account_vouchers where name='job_card');  

--
-- Update data for the new column in transactions table for estimation and invoice created from jobcard
--
--update `transactions` set originated_from_type='App\\Http\\Controllers\\Tradewms\\Jobcard\\Model\\JobCard', originated_from_id=reference_id
--where reference_id in (
--SELECT id FROM propel_production_main.transactions
--where id in (
--SELECT reference_id FROM propel_production_main.transactions
--where  reference_id is not null and transaction_type_id not in (select id from account_vouchers where name='job_card')
--)
--and transaction_type_id in (select id from account_vouchers where name='job_card') );

update `transactions` set originated_from_type='App\\Http\\Controllers\\Tradewms\\Jobcard\\Model\\JobCard', originated_from_id=reference_id
where reference_id in
(
	SELECT t1.id from (select id FROM nonproduction_propel_dev_main.transactions
	where id in (
					SELECT reference_id FROM nonproduction_propel_dev_main.transactions
					where  reference_id is not null and transaction_type_id not in (select id from account_vouchers where name='job_card')
				)
		and transaction_type_id in (select id from account_vouchers where name='job_card') ) t1
)
;