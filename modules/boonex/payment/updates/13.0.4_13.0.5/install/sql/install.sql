SET @sName = 'bx_payment';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_payment_currencies` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(4) NOT NULL default '',
  `rate` float NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `code`(`code`)
);


SET @sCurrencyCode = (SELECT `value` FROM `sys_options` WHERE `name`='bx_payment_default_currency_code' LIMIT 1);
UPDATE `bx_payment_transactions` SET `currency`=@sCurrencyCode WHERE `currency`='';
UPDATE `bx_payment_transactions_pending` SET `currency`=@sCurrencyCode WHERE `currency`='';
UPDATE `bx_payment_invoices` SET `currency`=@sCurrencyCode WHERE `currency`='';


SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='generic' LIMIT 1);

DELETE FROM `bx_payment_providers` WHERE `id`=@iProviderId;
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId;

INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `single_seller`, `time_tracker`, `active`, `order`, `class_name`) VALUES
('generic', '_bx_payment_gc_cpt', '_bx_payment_gc_dsc', 'gc_', 0, 0, 0, 0, 0, 1, 0, 'BxPaymentProviderGeneric');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'gc_currency_code', 'select', '_bx_payment_gc_currency_code_cpt', '_bx_payment_gc_currency_code_dsc', 'a:2:{s:6:"module";s:10:"bx_payment";s:6:"method";s:34:"get_options_personal_currency_code";}', '', '', '', 1);

UPDATE `bx_payment_providers` SET `order`='5' WHERE `name`='offline';
UPDATE `bx_payment_providers` SET `order`='6' WHERE `name`='credits';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT * FROM `bx_payment_providers` WHERE 1 AND (`for_single` <> ''0'' OR `for_recurring` <> ''0'')' WHERE `object`='bx_payment_grid_providers';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`currency` AS `currency`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ' WHERE `object`='bx_payment_grid_sbs_history';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`currency` AS `currency`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''single'' ' WHERE `object`='bx_payment_grid_orders_history';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`author_id` AS `author_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `tt`.`item_count` AS `item_count`, `ttp`.`order` AS `transaction`, `ttp`.`error_msg` AS `error_msg`, `ttp`.`provider` AS `provider`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`currency` AS `currency`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` LEFT JOIN `sys_profiles` AS `tup` ON `tt`.`client_id`=`tup`.`id` LEFT JOIN `sys_accounts` AS `tua` ON `tup`.`account_id`=`tua`.`id` WHERE 1 ', `filter_fields`='ttp`.`order,tt`.`license,tt`.`amount,tt`.`date,tua`.`name,tua`.`email' WHERE `object`='bx_payment_grid_orders_processed';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`items` AS `items`, `tt`.`amount` AS `amount`, `tt`.`currency` AS `currency`, `tt`.`order` AS `transaction`, `tt`.`error_msg` AS `error_msg`, `tt`.`provider` AS `provider`, `tt`.`date` AS `date` FROM `bx_payment_transactions_pending` AS `tt` LEFT JOIN `sys_profiles` AS `tup` ON `tt`.`client_id`=`tup`.`id` LEFT JOIN `sys_accounts` AS `tua` ON `tup`.`account_id`=`tua`.`id` WHERE 1 AND (ISNULL(`tt`.`order`) OR (NOT ISNULL(`tt`.`order`) AND `tt`.`error_code`<>''0'')) ', `filter_fields`='tt`.`order,tt`.`amount,tt`.`date,tua`.`name,tua`.`email' WHERE `object`='bx_payment_grid_orders_pending';


-- PRE-VALUES
UPDATE `sys_form_pre_values` SET `Value`='JPY' WHERE `Key`='bx_payment_currencies' AND `Value`='YEN';
