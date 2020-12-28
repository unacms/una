SET @sName = 'bx_payment';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_payment_credits_only', 'bx_payment_single_seller');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_credits_only', '', @iCategoryId, '_bx_payment_option_credits_only', 'checkbox', '', '', '', '', 10),
('bx_payment_single_seller', '', @iCategoryId, '_bx_payment_option_single_seller', 'checkbox', '', '', '', '', 11);


-- MENUS
UPDATE `sys_menu_items` SET `icon`='credit-card col-gray-dark' WHERE `set_name`='sys_account_settings' AND `name`='payment-details';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_payment_time_tracker';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_payment_time_tracker', '* * * * *', 'BxPaymentCronTimeTracker', 'modules/boonex/payment/classes/BxPaymentCronTimeTracker.php', '');
