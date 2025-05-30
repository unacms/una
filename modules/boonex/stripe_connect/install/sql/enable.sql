SET @sName = 'bx_stripe_connect';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_stripe_connect', 'bx_stripe_connect@modules/boonex/stripe_connect/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, @sName, '_bx_stripe_connect', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_stripe_connect_mode', 'live', @iCategId, '_bx_stripe_connect_option_mode', 'select', '', '', 10, 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:16:"get_options_mode";}'),
('bx_stripe_connect_api_public_live', '', @iCategId, '_bx_stripe_connect_option_api_public_live', 'digit', '', '', 20, ''),
('bx_stripe_connect_api_secret_live', '', @iCategId, '_bx_stripe_connect_option_api_secret_live', 'digit', '', '', 30, ''),
('bx_stripe_connect_api_public_test', '', @iCategId, '_bx_stripe_connect_option_api_public_test', 'digit', '', '', 40, ''),
('bx_stripe_connect_api_secret_test', '', @iCategId, '_bx_stripe_connect_option_api_secret_test', 'digit', '', '', 50, ''),
('bx_stripe_connect_pmode', 'direct', @iCategId, '_bx_stripe_connect_option_pmode', 'select', '', '', 60, 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:17:"get_options_pmode";}'),
('bx_stripe_connect_fee_single', '', @iCategId, '_bx_stripe_connect_option_fee_single', 'digit', '', '', 70, ''),
('bx_stripe_connect_fee_recurring', '', @iCategId, '_bx_stripe_connect_option_fee_recurring', 'digit', '', '', 80, '');


-- GRIDS: Manage
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_stripe_connect_accounts', 'Sql', 'SELECT * FROM `bx_stripe_connect_accounts`', 'bx_stripe_connect_accounts', 'id', '', '', 20, NULL, 'start', '', 'live_account_id,test_account_id', 'auto', 'live_account_id,test_account_id', 128, 'BxStripeConnectGridAccounts', 'modules/boonex/stripe_connect/classes/BxStripeConnectGridAccounts.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_stripe_connect_accounts', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_stripe_connect_accounts', 'profile_id', '_bx_stripe_connect_grid_column_title_profile_id', '15%', 0, '', '', 2),
('bx_stripe_connect_accounts', 'live_account_id', '_bx_stripe_connect_grid_column_title_live_account_id', '18%', 0, 32, '', 3),
('bx_stripe_connect_accounts', 'live_details', '_bx_stripe_connect_grid_column_title_live_details', '5%', 0, '', '', 4),
('bx_stripe_connect_accounts', 'test_account_id', '_bx_stripe_connect_grid_column_title_test_account_id', '18%', 0, 32, '', 5),
('bx_stripe_connect_accounts', 'test_details', '_bx_stripe_connect_grid_column_title_test_details', '5%', 0, '', '', 6),
('bx_stripe_connect_accounts', 'added', '_bx_stripe_connect_grid_column_title_added', '10%', 0, '', '', 7),
('bx_stripe_connect_accounts', 'changed', '_bx_stripe_connect_grid_column_title_changed', '10%', 0, '', '', 8),
('bx_stripe_connect_accounts', 'actions', '', '17%', 0, '', '', 9);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_stripe_connect_accounts', 'single', 'delete', '_bx_stripe_connect_action_title_delete', 'times', 1, 1, 1),
('bx_stripe_connect_accounts', 'bulk', 'delete', '_bx_stripe_connect_action_title_delete', '', 0, 1, 1);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxStripeConnectAlertsResponse', 'modules/boonex/stripe_connect/classes/BxStripeConnectAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_payment', 'stripe_v3_get_button', @iHandler),
('bx_payment', 'stripe_v3_create_session', @iHandler),
('bx_payment', 'stripe_v3_retrieve_customer', @iHandler);
