
UPDATE `sys_options` SET `AvailableValues` = 'AUD,CAD,EUR,GBP,USD,YEN' WHERE `Name` = 'pmt_default_currency_code';
UPDATE `sys_options` SET `VALUE` = 'EUR' WHERE `Name` = 'pmt_default_currency_code' AND `VALUE` = 'EURO';

ALTER TABLE `[db_prefix]providers_options` ADD `order` tinyint(4) NOT NULL default '0';

UPDATE `[db_prefix]providers_options` SET `order` = 1 WHERE `name` = 'pp_active';
UPDATE `[db_prefix]providers_options` SET `order` = 2 WHERE `name` = 'pp_mode';
UPDATE `[db_prefix]providers_options` SET `order` = 3 WHERE `name` = 'pp_business';
UPDATE `[db_prefix]providers_options` SET `order` = 4 WHERE `name` = 'pp_prc_type';
UPDATE `[db_prefix]providers_options` SET `order` = 5 WHERE `name` = 'pp_cnt_type';
UPDATE `[db_prefix]providers_options` SET `order` = 6 WHERE `name` = 'pp_token';
UPDATE `[db_prefix]providers_options` SET `order` = 7 WHERE `name` = 'pp_sandbox';

INSERT INTO `[db_prefix]providers`(`name`, `caption`, `description`, `option_prefix`, `class_name`) VALUES('2checkout', '2Checkout', '2Checkout payment provider', '2co_', 'BxPmt2Checkout');
SET @iProviderId = LAST_INSERT_ID();
INSERT INTO `[db_prefix]providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, '2co_active', 'checkbox', '_payment_2co_active_cpt', '_payment_2co_active_dsc', '', '', '', '', 1),
(@iProviderId, '2co_mode', 'select', '_payment_2co_mode_cpt', '_payment_2co_mode_dsc', '1|_payment_2co_mode_live,2|_payment_2co_mode_test', '', '', '', 2),
(@iProviderId, '2co_account_id', 'text', '_payment_2co_account_id_cpt', '_payment_2co_account_id_dsc', '', '', '', '', 3),
(@iProviderId, '2co_payment_method', 'select', '_payment_2co_payment_method_cpt', '_payment_2co_payment_method_dsc', 'CC|_payment_2co_payment_method_cc,CK|_payment_2co_payment_method_ck,AL|_payment_2co_payment_method_al,PPI|_payment_2co_payment_method_ppi', '', '', '', 4),
(@iProviderId, '2co_secret_word', 'text', '_payment_2co_secret_word_cpt', '_payment_2co_secret_word_dsc', '', '', '', '', 5);

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'payment' AND `version` = '1.0.0';

