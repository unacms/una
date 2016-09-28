SET @sName = 'bx_payment';


-- TABLES
ALTER TABLE `bx_payment_providers` CHANGE `description` `description` varchar(128) NOT NULL default '';

UPDATE `bx_payment_providers` SET `for_single`='1', `for_recurring`='0' WHERE `name`='paypal';
UPDATE `bx_payment_providers` SET `for_single`='1', `for_recurring`='0' WHERE `name`='2checkout';
UPDATE `bx_payment_providers` SET `for_single`='1', `for_recurring`='0' WHERE `name`='bitpay';
UPDATE `bx_payment_providers` SET `for_single`='0', `for_recurring`='1' WHERE `name`='chargebee';
UPDATE `bx_payment_providers` SET `for_single`='0', `for_recurring`='1' WHERE `name`='recurly';

DELETE FROM `bx_payment_providers` WHERE `name`='stripe';
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `class_name`) VALUES
('stripe', '_bx_payment_strp_cpt', '_bx_payment_strp_dsc', 'strp_', 1, 1, 1, 'BxPaymentProviderStripe');
SET @iProviderId = LAST_INSERT_ID();

DELETE FROM `bx_payment_providers_options` WHERE `name` LIKE 'strp_%';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'strp_active', 'checkbox', '_bx_payment_strp_active_cpt', '_bx_payment_strp_active_dsc', '', '', '', '', 1),
(@iProviderId, 'strp_mode', 'select', '_bx_payment_strp_mode_cpt', '_bx_payment_strp_mode_dsc', '1|_bx_payment_strp_mode_live,2|_bx_payment_strp_mode_test', '', '', '', 2),
(@iProviderId, 'strp_live_pub_key', 'text', '_bx_payment_strp_live_pub_key_cpt', '_bx_payment_strp_live_pub_key_dsc', '', '', '', '', 3),
(@iProviderId, 'strp_live_sec_key', 'text', '_bx_payment_strp_live_sec_key_cpt', '_bx_payment_strp_live_sec_key_dsc', '', '', '', '', 4),
(@iProviderId, 'strp_test_pub_key', 'text', '_bx_payment_strp_test_pub_key_cpt', '_bx_payment_strp_test_pub_key_dsc', '', '', '', '', 5),
(@iProviderId, 'strp_test_sec_key', 'text', '_bx_payment_strp_test_sec_key_cpt', '_bx_payment_strp_test_sec_key_dsc', '', '', '', '', 6),
(@iProviderId, 'strp_check_amount', 'checkbox', '_bx_payment_strp_check_amount_cpt', '_bx_payment_strp_check_amount_dsc', '', '', '', '', 7),
(@iProviderId, 'strp_ssl', 'checkbox', '_bx_payment_strp_ssl_cpt', '_bx_payment_strp_ssl_dsc', '', '', '', '', 8),
(@iProviderId, 'strp_notify_url', 'value', '_bx_payment_strp_notify_url_cpt', '', '', '', '', '', 9);


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_payment_grid_carts';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_payment_grid_cart';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_payment_grid_orders_history';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_payment_grid_orders_processed';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_payment_grid_orders_pending';