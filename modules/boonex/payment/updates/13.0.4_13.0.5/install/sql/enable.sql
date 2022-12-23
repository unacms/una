SET @sName = 'bx_payment';


-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_payment_currency';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_payment_currency', '_bx_payment_options_category_currency', 5);
SET @iCategoryId = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id`= @iCategoryId WHERE `name`='bx_payment_default_currency_code';
DELETE FROM `sys_options` WHERE `name`='bx_payment_currency_exchange_api';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_currency_exchange_api', '', @iCategoryId, '_bx_payment_option_currency_exchange_api', 'digit', '', '', '', '', 10);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_payment_currency';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_payment_currency', '0 0 * * *', 'BxPaymentCronCurrency', 'modules/boonex/payment/classes/BxPaymentCronCurrency.php', '');
