SET @sName = 'bx_payment';


-- TABLES
DELETE FROM `bx_payment_providers` WHERE `name`='paypal_api';
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `single_seller`, `active`, `order`, `class_name`) VALUES
('paypal_api', '_bx_payment_pp_api_cpt', '_bx_payment_pp_api_dsc', 'pp_api_', 1, 1, 1, 1, 1, 15, 'BxPaymentProviderPayPalApi');
SET @iProviderId = LAST_INSERT_ID();

DELETE FROM `bx_payment_providers_options` WHERE `name` LIKE 'pp_api_%';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'pp_api_active', 'checkbox', '_bx_payment_pp_api_active_cpt', '_bx_payment_pp_api_active_dsc', '', '', '', '', 1),
(@iProviderId, 'pp_api_hidden', 'checkbox', '_bx_payment_pp_api_hidden_cpt', '_bx_payment_pp_api_hidden_dsc', '', '', '', '', 2),
(@iProviderId, 'pp_api_mode', 'select', '_bx_payment_pp_api_mode_cpt', '_bx_payment_pp_api_mode_dsc', '1|_bx_payment_pp_api_mode_live,2|_bx_payment_pp_api_mode_test', '', '', '', 3),
(@iProviderId, 'pp_api_live_account', 'text', '_bx_payment_pp_api_live_account_cpt', '_bx_payment_pp_api_live_account_dsc', '', '', '', '', 4),
(@iProviderId, 'pp_api_live_client_id', 'text', '_bx_payment_pp_api_live_client_id_cpt', '_bx_payment_pp_api_live_client_id_dsc', '', '', '', '', 5),
(@iProviderId, 'pp_api_live_secret', 'text', '_bx_payment_pp_api_live_secret_cpt', '_bx_payment_pp_api_live_secret_dsc', '', '', '', '', 6),
(@iProviderId, 'pp_api_test_account', 'text', '_bx_payment_pp_api_test_account_cpt', '_bx_payment_pp_api_test_account_dsc', '', '', '', '', 7),
(@iProviderId, 'pp_api_test_client_id', 'text', '_bx_payment_pp_api_test_client_id_cpt', '_bx_payment_pp_api_test_client_id_dsc', '', '', '', '', 8),
(@iProviderId, 'pp_api_test_secret', 'text', '_bx_payment_pp_api_test_secret_cpt', '_bx_payment_pp_api_test_secret_dsc', '', '', '', '', 9),
(@iProviderId, 'pp_api_return_data_url', 'value', '_bx_payment_pp_api_return_data_url_cpt', '', '', '', '', '', 10),
(@iProviderId, 'pp_api_notify_url', 'value', '_bx_payment_pp_api_notify_url_cpt', '', '', '', '', '', 11);


-- GRIDS
UPDATE `sys_objects_grid` SET `show_total_count`='0' WHERE `object` IN ('bx_payment_grid_sbs_list_my', 'bx_payment_grid_sbs_list_all', 'bx_payment_grid_sbs_history');

UPDATE `sys_objects_grid` SET `filter_fields`='tac`.`email,ts`.`customer_id,ts`.`subscription_id,ts`.`date_add' WHERE `object`='bx_payment_grid_sbs_list_all';

UPDATE `sys_objects_grid` SET `show_total_count`='0' WHERE `object` IN ('bx_payment_grid_orders_history', 'bx_payment_grid_orders_processed', 'bx_payment_grid_orders_pending');
