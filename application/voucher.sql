INSERT INTO `module` (`id`, `name`, `description`, `image`, `directory`, `status`) VALUES (25, 'voucher', NULL, NULL, NULL, '1');

INSERT INTO `sub_module` (`id`, `mid`, `name`, `description`, `image`, `directory`, `status`) VALUES 
 (NULL, '25', 'debit_voucher', NULL, NULL, 'debit_voucher', '1'), 
(NULL, '25', 'credit_voucher', NULL, NULL, 'credit_voucher', '1'),
(NULL, '25', 'contra_voucher', NULL, NULL, 'contra_voucher', '1'),
 (NULL, '25', 'journal_voucher', NULL, NULL, 'journal_voucher', '1'),
 (NULL, '25', 'aprove_v', NULL, NULL, 'aprove_v', '1');