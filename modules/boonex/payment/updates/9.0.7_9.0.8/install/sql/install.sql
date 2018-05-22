SET @sName = 'bx_payment';


-- PROVIDERS
DELETE FROM `bx_payment_providers` WHERE `name`='chargebee_v3';
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `class_name`) VALUES
('chargebee_v3', '_bx_payment_cbee_v3_cpt', '_bx_payment_cbee_v3_dsc', 'cbee_v3_', 1, 0, 1, 'BxPaymentProviderChargebeeV3');
SET @iProviderId = LAST_INSERT_ID();

DELETE FROM `bx_payment_providers_options` WHERE `name` LIKE 'cbee_v3%';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_v3_active', 'checkbox', '_bx_payment_cbee_active_cpt', '_bx_payment_cbee_active_dsc', '', '', '', '', 1),
(@iProviderId, 'cbee_v3_mode', 'select', '_bx_payment_cbee_mode_cpt', '_bx_payment_cbee_mode_dsc', '1|_bx_payment_cbee_mode_live,2|_bx_payment_cbee_mode_test', '', '', '', 2),
(@iProviderId, 'cbee_v3_live_site', 'text', '_bx_payment_cbee_live_site_cpt', '_bx_payment_cbee_live_site_dsc', '', '', '', '', 3),
(@iProviderId, 'cbee_v3_live_api_key', 'text', '_bx_payment_cbee_live_api_key_cpt', '_bx_payment_cbee_live_api_key_dsc', '', '', '', '', 4),
(@iProviderId, 'cbee_v3_test_site', 'text', '_bx_payment_cbee_test_site_cpt', '_bx_payment_cbee_test_site_dsc', '', '', '', '', 5),
(@iProviderId, 'cbee_v3_test_api_key', 'text', '_bx_payment_cbee_test_api_key_cpt', '_bx_payment_cbee_test_api_key_dsc', '', '', '', '', 6),
(@iProviderId, 'cbee_v3_ssl', 'checkbox', '_bx_payment_cbee_ssl_cpt', '_bx_payment_cbee_ssl_dsc', '', '', '', '', 7),
(@iProviderId, 'cbee_v3_return_data_url', 'value', '_bx_payment_cbee_return_data_url_cpt', '', '', '', '', '', 8),
(@iProviderId, 'cbee_v3_notify_url', 'value', '_bx_payment_cbee_notify_url_cpt', '', '', '', '', '', 9);
