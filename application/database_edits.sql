ALTER TABLE `company_information` ADD `header_text` VARBINARY(1000) NULL AFTER `password`;
ALTER TABLE `sec_role` ADD `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER `type`;
UPDATE language SET english="Origin of Product List" WHERE phrase='oop_list';
ALTER TABLE `product_service` ADD `service_code` VARCHAR(50) NULL AFTER `service_id`;