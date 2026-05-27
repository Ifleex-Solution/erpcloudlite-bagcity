ALTER TABLE `company_information` CHANGE `company_id` `company_id` INT(250) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`company_id`);
ALTER TABLE `company_information` ADD `footer_text` VARBINARY(1000) NOT NULL AFTER `instance_type`, ADD `password` VARBINARY(1000) NOT NULL AFTER `footer_text`;
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'add_company', 'Add Company');
INSERT INTO `sub_module` (`id`, `mid`, `name`, `description`, `image`, `directory`, `status`) VALUES (NULL, '15', 'add_company', NULL, NULL, 'add_company', '1');
ALTER TABLE `company_information` ADD `password_enable` INT(10) NOT NULL AFTER `nic_no`;
