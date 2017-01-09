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
('bx_stripe_connect_api_id_live', '', @iCategId, '_bx_stripe_connect_option_api_id_live', 'digit', '', '', 20, ''),
('bx_stripe_connect_api_public_live', '', @iCategId, '_bx_stripe_connect_option_api_public_live', 'digit', '', '', 30, ''),
('bx_stripe_connect_api_secret_live', '', @iCategId, '_bx_stripe_connect_option_api_secret_live', 'digit', '', '', 40, ''),
('bx_stripe_connect_api_id_test', '', @iCategId, '_bx_stripe_connect_option_api_id_test', 'digit', '', '', 50, ''),
('bx_stripe_connect_api_public_test', '', @iCategId, '_bx_stripe_connect_option_api_public_test', 'digit', '', '', 60, ''),
('bx_stripe_connect_api_secret_test', '', @iCategId, '_bx_stripe_connect_option_api_secret_test', 'digit', '', '', 70, ''),
('bx_stripe_connect_api_scope', 'read_write', @iCategId, '_bx_stripe_connect_option_api_scope', 'select', '', '', 80, 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:21:"get_options_api_scope";}'),
('bx_stripe_connect_pmode_single', 'direct', @iCategId, '_bx_stripe_connect_option_pmode_single', 'select', '', '', 90, 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:24:"get_options_pmode_single";}'),
('bx_stripe_connect_fee_single', '', @iCategId, '_bx_stripe_connect_option_fee_single', 'digit', '', '', 100, ''),
('bx_stripe_connect_pmode_recurring', 'direct', @iCategId, '_bx_stripe_connect_option_pmode_recurring', 'select', '', '', 110, 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:27:"get_options_pmode_recurring";}'),
('bx_stripe_connect_fee_recurring', '', @iCategId, '_bx_stripe_connect_option_fee_recurring', 'digit', '', '', 120, '');


-- PAGE: result
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_stripe_connect_result', 'stripe-connect-result', '_bx_stripe_connect_page_title_sys_result', '_bx_stripe_connect_page_title_result', @sName, 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxStripeConnectPage', 'modules/boonex/stripe_connect/classes/BxStripeConnectPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_stripe_connect_result', 1, @sName, '_bx_stripe_connect_page_block_title_result', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:16:"get_block_result";}', 0, 0, 1, 1);


-- PAGES: add page block on dashboard
SET @iPBCellDashboard = 3;
SET @iPBOrderDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `cell_id` = @iPBCellDashboard LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, @sName, '_bx_stripe_connect_page_block_title_connect', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:17:"bx_stripe_connect";s:6:"method";s:17:"get_block_connect";}', 0, 1, @iPBOrderDashboard + 1);


-- GRIDS: Manage
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_stripe_connect_accounts', 'Sql', 'SELECT * FROM `bx_stripe_connect_accounts`', 'bx_stripe_connect_accounts', 'id', '', '', 10, NULL, 'start', '', 'user_id,public_key,access_token', 'auto', 'user_id,public_key,access_token', 128, 'BxStripeConnectGridAccounts', 'modules/boonex/stripe_connect/classes/BxStripeConnectGridAccounts.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_stripe_connect_accounts', 'checkbox', 'Select', '2%', 0, '', '', 1),
('bx_stripe_connect_accounts', 'author', '_bx_stripe_connect_grid_column_title_author', '16%', 0, '18', '', 2),
('bx_stripe_connect_accounts', 'user_id', '_bx_stripe_connect_grid_column_title_user_id', '18%', 0, '18', '', 3),
('bx_stripe_connect_accounts', 'public_key', '_bx_stripe_connect_grid_column_title_public_key', '18%', 0, '18', '', 4),
('bx_stripe_connect_accounts', 'access_token', '_bx_stripe_connect_grid_column_title_access_token', '18%', 0, '18', '', 5),
('bx_stripe_connect_accounts', 'added', '_bx_stripe_connect_grid_column_title_added', '10%', 0, '18', '', 6),
('bx_stripe_connect_accounts', 'actions', '', '18%', 0, '', '', 7);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_stripe_connect_accounts', 'single', 'delete', '_bx_stripe_connect_action_title_delete', 'chain-broken', 1, 1, 1),
('bx_stripe_connect_accounts', 'bulk', 'delete', '_bx_stripe_connect_action_title_delete', '', 0, 1, 1);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxStripeConnectAlertsResponse', 'modules/boonex/stripe_connect/classes/BxStripeConnectAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_payment', 'stripe_get_button', @iHandler),
('bx_payment', 'stripe_create_customer', @iHandler),
('bx_payment', 'stripe_retrieve_customer', @iHandler),
('bx_payment', 'stripe_create_charge', @iHandler),
('bx_payment', 'stripe_retrieve_charge', @iHandler),
('bx_payment', 'stripe_create_subscription', @iHandler);
