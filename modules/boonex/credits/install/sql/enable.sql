
-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_credits', '_bx_credits', 'bx_credits@modules/boonex/credits/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_credits', '_bx_credits', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_credits_enable_withdraw', '', @iCategId, '_bx_credits_option_enable_withdraw', 'checkbox', '', '', '', 1),
('bx_credits_withdraw_email', '', @iCategId, '_bx_credits_option_withdraw_email', 'digit', '', '', '', 2),
('bx_credits_withdraw_clearing', '30', @iCategId, '_bx_credits_option_withdraw_clearing', 'digit', '', '', '', 3),
('bx_credits_withdraw_minimum', '500', @iCategId, '_bx_credits_option_withdraw_minimum', 'digit', '', '', '', 4),
('bx_credits_withdraw_remaining', '100', @iCategId, '_bx_credits_option_withdraw_remaining', 'digit', '', '', '', 5),

('bx_credits_precision', '2', @iCategId, '_bx_credits_option_precision', 'digit', '', '', '', 10),
('bx_credits_conversion_rate_use', '1.0', @iCategId, '_bx_credits_option_conversion_rate_use', 'digit', '', '', '', 11),
('bx_credits_conversion_rate_withdraw', '1.0', @iCategId, '_bx_credits_option_conversion_rate_withdraw', 'digit', '', '', '', 12),

('bx_credits_code', '', @iCategId, '_bx_credits_option_code', 'digit', '', '', '', 20),
('bx_credits_icon', 'copyright', @iCategId, '_bx_credits_option_icon', 'digit', '', '', '', 21),

('bx_credits_enable_provider', '', @iCategId, '_bx_credits_enable_provider', 'checkbox', '', '', '', 30);


-- PAGE: module home
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_home', 'credits-home', '_bx_credits_page_title_sys_home', '_bx_credits_page_title_home', 'bx_credits', 5, 2147483647, 1, 'page.php?i=credits-home', '', '', '', 0, 1, 0, 'BxCreditsPageBrowse', 'modules/boonex/credits/classes/BxCreditsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_credits_home', 1, 'bx_credits', '_bx_credits_page_block_title_sys_bundles', '_bx_credits_page_block_title_bundles', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:17:"get_block_bundles";}', 0, 1, 1, 0);

-- PAGE: checkout
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `type_id`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_checkout', 'credits-checkout', '_bx_credits_page_title_sys_checkout', '_bx_credits_page_title_checkout', 'bx_credits', 2, 5, 2147483647, 1, 'page.php?i=credits-checkout', '', '', '', 0, 1, 0, 'BxCreditsPageBrowse', 'modules/boonex/credits/classes/BxCreditsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_credits_checkout', 1, 'bx_credits', '_bx_credits_page_block_title_sys_checkout', '_bx_credits_page_block_title_checkout', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:18:"get_block_checkout";}', 0, 0, 1, 0);

-- PAGE: profile's orders
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_orders_common', '_bx_credits_page_title_sys_orders_common', '_bx_credits_page_title_orders_common', 'bx_credits', 5, 2147483647, 1, 'credits-orders-common', '', '', '', '', 0, 1, 0, 'BxCreditsPageOrders', 'modules/boonex/credits/classes/BxCreditsPageOrders.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_credits_orders_common', 1, 'bx_credits', '_bx_credits_page_block_title_sys_orders_common_note', '_bx_credits_page_block_title_orders_common_note', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:21:"get_block_orders_note";}', 0, 0, 1, 0),
('bx_credits_orders_common', 1, 'bx_credits', '_bx_credits_page_block_title_sys_orders_common', '_bx_credits_page_block_title_orders_common', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:16:"get_block_orders";}', 0, 0, 1, 1);

-- PAGE: orders administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_orders_administration', '_bx_credits_page_title_sys_orders_administration', '_bx_credits_page_title_orders_administration', 'bx_credits', 5, 192, 1, 'credits-orders-administration', '', '', '', '', 0, 1, 0, 'BxCreditsPageOrders', 'modules/boonex/credits/classes/BxCreditsPageOrders.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_credits_orders_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_orders_administration', '_bx_credits_page_block_title_orders_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:16:"get_block_orders";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 0, 1, 0);

-- PAGE: profile's history
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_history_common', '_bx_credits_page_title_sys_history_common', '_bx_credits_page_title_history_common', 'bx_credits', 5, 2147483647, 1, 'credits-history-common', 'page.php?i=credits-history-common', '', '', '', 0, 1, 0, 'BxCreditsPageHistory', 'modules/boonex/credits/classes/BxCreditsPageHistory.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_history_common', 1, 'bx_credits', '_bx_credits_page_block_title_sys_history_common', '_bx_credits_page_block_title_history_common', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:17:"get_block_history";}}', 0, 1, 0);

-- PAGE: history administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_history_administration', '_bx_credits_page_title_sys_history_administration', '_bx_credits_page_title_history_administration', 'bx_credits', 5, 192, 1, 'credits-history-administration', 'page.php?i=credits-history-administration', '', '', '', 0, 1, 0, 'BxCreditsPageHistory', 'modules/boonex/credits/classes/BxCreditsPageHistory.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_history_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_history_administration', '_bx_credits_page_block_title_history_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:17:"get_block_history";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);

-- PAGE: profile's withdrawals
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_withdrawals_common', '_bx_credits_page_title_sys_withdrawals_common', '_bx_credits_page_title_withdrawals_common', 'bx_credits', 5, 2147483647, 1, 'credits-withdrawals-common', 'page.php?i=credits-withdrawals-common', '', '', '', 0, 1, 0, 'BxCreditsPageWithdrawals', 'modules/boonex/credits/classes/BxCreditsPageWithdrawals.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_withdrawals_common', 1, 'bx_credits', '_bx_credits_page_block_title_sys_withdrawals_common', '_bx_credits_page_block_title_withdrawals_common', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:21:"get_block_withdrawals";}}', 0, 1, 0);

-- PAGE: withdrawals administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_withdrawals_administration', '_bx_credits_page_title_sys_withdrawals_administration', '_bx_credits_page_title_withdrawals_administration', 'bx_credits', 5, 192, 1, 'credits-withdrawals-administration', 'page.php?i=credits-withdrawals-administration', '', '', '', 0, 1, 0, 'BxCreditsPageWithdrawals', 'modules/boonex/credits/classes/BxCreditsPageWithdrawals.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_withdrawals_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_withdrawals_administration', '_bx_credits_page_block_title_withdrawals_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:21:"get_block_withdrawals";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);

-- PAGE: profiles administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_profiles_administration', '_bx_credits_page_title_sys_profiles_administration', '_bx_credits_page_title_profiles_administration', 'bx_credits', 5, 192, 1, 'credits-profiles-administration', 'page.php?i=credits-profiles-administration', '', '', '', 0, 1, 0, 'BxCreditsPageProfiles', 'modules/boonex/credits/classes/BxCreditsPageProfiles.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_credits_profiles_administration', 1, 'bx_credits', '_bx_credits_page_block_title_sys_profiles_administration', '_bx_credits_page_block_title_profiles_administration', 11, 192, 'service', 'a:3:{s:6:"module";s:10:"bx_credits";s:6:"method";s:18:"get_block_profiles";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);


-- MENU: add to site menu
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 AND `order` < 9999 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_credits', 'credits-home', '_bx_credits_menu_item_title_system_credits', '_bx_credits_menu_item_title_credits', 'page.php?i=credits-home', '', '', 'copyright col-green3', 'bx_credits_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_credits', 'credits-home', '_bx_credits_menu_item_title_system_credits', '_bx_credits_menu_item_title_credits', 'page.php?i=credits-home', '', '', 'copyright col-green3', 'bx_credits_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: member toolbar
SET @iMenuToolbarMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_member', 'bx_credits', 'credits-history', '_bx_credits_menu_item_title_system_history', '_bx_credits_menu_item_title_history', 'page.php?i=credits-history-common', '', '', '', '', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:26:"get_menu_item_addon_amount";}', '', 0, 2147483646, 0, 1, 0);

-- MENU: Notifications
SET @iAccountNotificationsMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `order` < 9999);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_credits', 'notifications-credits', '_bx_credits_menu_item_title_system_notifications', '_bx_credits_menu_item_title_notifications', 'page.php?i=credits-history-common', '', '', 'copyright col-green3', '', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:26:"get_menu_item_addon_amount";}', '', '0', 2147483646, 1, 1, @iAccountNotificationsMenuOrder + 1);

-- MENU: account dashboard
SET @iAccountDashboardMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', 'bx_credits', 'credits-manage', '_bx_credits_menu_item_title_system_manage', '_bx_credits_menu_item_title_manage', 'page.php?i=credits-history-common', '', '', 'copyright col-green3', '', '', 2147483646, 1, 0, 1, @iAccountDashboardMenuOrder + 1);

-- MENU: module sub-menu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_submenu', '_bx_credits_menu_title_submenu', 'bx_credits_submenu', 'bx_credits', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_credits_submenu', 'bx_credits', '_bx_credits_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_credits_submenu', 'bx_credits', 'credits-home', '_bx_credits_menu_item_title_system_home', '_bx_credits_menu_item_title_home', 'page.php?i=credits-home', '', '', '', '', 2147483647, 0, 1, 1);

-- MENU: manage submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_manage_submenu', '_bx_credits_menu_title_manage_submenu', 'bx_credits_manage_submenu', 'bx_credits', 6, 0, 1, 'BxCreditsMenuManage', 'modules/boonex/credits/classes/BxCreditsMenuManage.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_credits_manage_submenu', 'bx_credits', '_bx_credits_menu_set_title_manage_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_credits_manage_submenu', 'bx_credits', 'credits-history-common', '_bx_credits_menu_item_title_system_history_common', '_bx_credits_menu_item_title_history_common', 'page.php?i=credits-history-common', '', '_self', '', '', '', 2147483646, 1, 0, 1, 1),
('bx_credits_manage_submenu', 'bx_credits', 'credits-history-administration', '_bx_credits_menu_item_title_system_history_administration', '_bx_credits_menu_item_title_history_administration', 'page.php?i=credits-history-administration', '', '_self', '', '', '', 192, 1, 0, 1, 2),
('bx_credits_manage_submenu', 'bx_credits', 'credits-orders-common', '_bx_credits_menu_item_title_system_orders_common', '_bx_credits_menu_item_title_orders_common', 'page.php?i=credits-orders-common', '', '_self', '', '', '', 2147483646, 1, 0, 1, 3),
('bx_credits_manage_submenu', 'bx_credits', 'credits-orders-administration', '_bx_credits_menu_item_title_system_orders_administration', '_bx_credits_menu_item_title_orders_administration', 'page.php?i=credits-orders-administration', '', '_self', '', '', '', 192, 1, 0, 1, 4),
('bx_credits_manage_submenu', 'bx_credits', 'credits-withdrawals-common', '_bx_credits_menu_item_title_system_withdrawals_common', '_bx_credits_menu_item_title_withdrawals_common', 'page.php?i=credits-withdrawals-common', '', '_self', '', '', '', 2147483646, 1, 0, 1, 5),
('bx_credits_manage_submenu', 'bx_credits', 'credits-withdrawals-administration', '_bx_credits_menu_item_title_system_withdrawals_administration', '_bx_credits_menu_item_title_withdrawals_administration', 'page.php?i=credits-withdrawals-administration', '', '_self', '', '', '', 192, 1, 0, 1, 6),
('bx_credits_manage_submenu', 'bx_credits', 'credits-profiles-administration', '_bx_credits_menu_item_title_system_profiles_administration', '_bx_credits_menu_item_title_profiles_administration', 'page.php?i=credits-profiles-administration', '', '_self', '', '', '', 192, 1, 0, 1, 7);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_credits', 'credits-history-administration', '_bx_credits_menu_item_title_system_admt_credits', '_bx_credits_menu_item_title_admt_credits', 'page.php?i=credits-history-administration', '', '_self', 'copyright', 'a:2:{s:6:"module";s:10:"bx_credits";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);


-- GRIDS: bundles
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_bundles', 'Sql', 'SELECT * FROM `bx_credits_bundles` WHERE 1 ', 'bx_credits_bundles', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 192, 'BxCreditsGridBundles', 'modules/boonex/credits/classes/BxCreditsGridBundles.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_bundles', 'checkbox', '_sys_select', '1%', 0, '', '', 1),
('bx_credits_bundles', 'order', '', '1%', 0, '', '', 2),
('bx_credits_bundles', 'name', '_bx_credits_grid_column_title_name', '23%', 0, 32, '', 3),
('bx_credits_bundles', 'title', '_bx_credits_grid_column_title_title', '30%', 1, 16, '', 4),
('bx_credits_bundles', 'amount', '_bx_credits_grid_column_title_amount', '10%', 0, 16, '', 5),
('bx_credits_bundles', 'bonus', '_bx_credits_grid_column_title_bonus', '5%', 0, 16, '', 6),
('bx_credits_bundles', 'price', '_bx_credits_grid_column_title_price', '10%', 0, 16, '', 7),
('bx_credits_bundles', 'actions', '', '20%', 0, '', '', 8);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_bundles', 'independent', 'add', '_bx_credits_grid_action_title_add', '', 0, 0, 1),
('bx_credits_bundles', 'single', 'edit', '_bx_credits_grid_action_title_edit', 'pencil-alt', 1, 0, 1),
('bx_credits_bundles', 'single', 'delete', '_bx_credits_grid_action_title_delete', 'remove', 1, 1, 2),
('bx_credits_bundles', 'bulk', 'delete', '_bx_credits_grid_action_title_delete', '', 0, 1, 1);

-- GRIDS: orders
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_orders_administration', 'Sql', 'SELECT `to`.`id` AS `id`, `to`.`profile_id` AS `profile_id`, `to`.`bundle_id` AS `bundle_id`, `tb`.`title` AS `bundle`, `to`.`order` AS `transaction`, `to`.`license` AS `license`, `to`.`type` AS `type`, `to`.`added` AS `added`, `to`.`expired` AS `expired` FROM `bx_credits_orders` AS `to` LEFT JOIN `bx_credits_bundles` AS `tb` ON `to`.`bundle_id`=`tb`.`id` WHERE 1 ', 'bx_credits_orders', 'id', 'added', '', '', 20, NULL, 'start', '', 'tb`.`title,to`.`order,to`.`license,to`.`type', '', 'like', '', '', 192, 'BxCreditsGridOrdersAdministration', 'modules/boonex/credits/classes/BxCreditsGridOrdersAdministration.php'),
('bx_credits_orders_common', 'Sql', 'SELECT `to`.`id` AS `id`, `to`.`profile_id` AS `profile_id`, `to`.`bundle_id` AS `bundle_id`, `tb`.`title` AS `bundle`, `to`.`order` AS `transaction`, `to`.`license` AS `license`, `to`.`type` AS `type`, `to`.`added` AS `added`, `to`.`expired` AS `expired` FROM `bx_credits_orders` AS `to` LEFT JOIN `bx_credits_bundles` AS `tb` ON `to`.`bundle_id`=`tb`.`id` WHERE 1 ', 'bx_credits_orders', 'id', 'added', '', '', 20, NULL, 'start', '', 'tb`.`title,to`.`order,to`.`license,to`.`type', '', 'like', '', '', 2147483647, 'BxCreditsGridOrdersCommon', 'modules/boonex/credits/classes/BxCreditsGridOrdersCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_orders_administration', 'profile_id', '_bx_credits_grid_column_title_ord_profile_id', '10%', 0, '28', '', 1),
('bx_credits_orders_administration', 'bundle', '_bx_credits_grid_column_title_ord_bundle', '20%', 1, '28', '', 2),
('bx_credits_orders_administration', 'transaction', '_bx_credits_grid_column_title_ord_transaction', '10%', 0, '32', '', 3),
('bx_credits_orders_administration', 'type', '_bx_credits_grid_column_title_ord_type', '5%', 1, '12', '', 4),
('bx_credits_orders_administration', 'added', '_bx_credits_grid_column_title_ord_added', '10%', 1, '25', '', 5),
('bx_credits_orders_administration', 'expired', '_bx_credits_grid_column_title_ord_expired', '10%', 1, '25', '', 6),

('bx_credits_orders_common', 'bundle', '_bx_credits_grid_column_title_ord_bundle', '20%', 1, '28', '', 1),
('bx_credits_orders_common', 'transaction', '_bx_credits_grid_column_title_ord_transaction', '10%', 0, '32', '', 2),
('bx_credits_orders_common', 'type', '_bx_credits_grid_column_title_ord_type', '10%', 1, '12', '', 3),
('bx_credits_orders_common', 'added', '_bx_credits_grid_column_title_ord_added', '10%', 1, '25', '', 4),
('bx_credits_orders_common', 'expired', '_bx_credits_grid_column_title_ord_expired', '10%', 1, '25', '', 5);

-- GRIDS: history
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_history_administration', 'Sql', 'SELECT `th`.*, `tp`.`wdw_clearing` FROM `bx_credits_history` AS `th` LEFT JOIN `bx_credits_profiles` AS `tp` ON `th`.`first_pid`=`tp`.`id` WHERE 1 ', 'bx_credits_history', 'id', 'date', '', '', 20, NULL, 'start', '', 'th`.`direction,th`.`order,th`.`info', '', 'like', '', '', 192, 'BxCreditsGridHistoryAdministration', 'modules/boonex/credits/classes/BxCreditsGridHistoryAdministration.php'),
('bx_credits_history_common', 'Sql', 'SELECT `th`.*, `tp`.`wdw_clearing` FROM `bx_credits_history` AS `th` LEFT JOIN `bx_credits_profiles` AS `tp` ON `th`.`first_pid`=`tp`.`id` WHERE 1 ', 'bx_credits_history', 'id', 'date', '', '', 20, NULL, 'start', '', 'th`.`direction,th`.`order,th`.`info', '', 'like', '', '', 2147483647, 'BxCreditsGridHistoryCommon', 'modules/boonex/credits/classes/BxCreditsGridHistoryCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_history_administration', 'first_pid', '_bx_credits_grid_column_title_htr_first_pid', '15%', 0, '', '', 1),
('bx_credits_history_administration', 'direction', '_bx_credits_grid_column_title_htr_direction', '10%', 0, '', '', 2),
('bx_credits_history_administration', 'second_pid', '_bx_credits_grid_column_title_htr_second_pid', '15%', 0, '', '', 3),
('bx_credits_history_administration', 'amount', '_bx_credits_grid_column_title_htr_amount', '10%', 1, '', '', 4),
('bx_credits_history_administration', 'order', '_bx_credits_grid_column_title_htr_order', '15%', 0, '16', '', 5),
('bx_credits_history_administration', 'info', '_bx_credits_grid_column_title_htr_info', '15%', 1, '24', '', 6),
('bx_credits_history_administration', 'date', '_bx_credits_grid_column_title_htr_date', '10%', 0, '', '', 7),
('bx_credits_history_administration', 'cleared', '_bx_credits_grid_column_title_htr_cleared', '10%', 0, '', '', 8),

('bx_credits_history_common', 'direction', '_bx_credits_grid_column_title_htr_direction', '10%', 0, '', '', 1),
('bx_credits_history_common', 'second_pid', '_bx_credits_grid_column_title_htr_pid', '25%', 0, '', '', 2),
('bx_credits_history_common', 'amount', '_bx_credits_grid_column_title_htr_amount', '15%', 1, '', '', 3),
('bx_credits_history_common', 'order', '_bx_credits_grid_column_title_htr_order', '15%', 0, '16', '', 4),
('bx_credits_history_common', 'info', '_bx_credits_grid_column_title_htr_info', '15%', 1, '32', '', 5),
('bx_credits_history_common', 'date', '_bx_credits_grid_column_title_htr_date', '10%', 0, '', '', 6),
('bx_credits_history_common', 'cleared', '_bx_credits_grid_column_title_htr_cleared', '10%', 0, '', '', 7);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_history_administration', 'independent', 'grant', '_bx_credits_grid_action_title_htr_grant', '', 0, 0, 1),

('bx_credits_history_common', 'independent', 'send', '_bx_credits_grid_action_title_htr_send', '', 0, 0, 1);

-- GRIDS: withdrawals
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_withdrawals_administration', 'Sql', 'SELECT * FROM `bx_credits_withdrawals` WHERE 1 ', 'bx_credits_withdrawals', 'id', 'added', '', '', 20, NULL, 'start', '', 'message,order', '', 'like', '', '', 192, 'BxCreditsGridWithdrawalsAdministration', 'modules/boonex/credits/classes/BxCreditsGridWithdrawalsAdministration.php'),
('bx_credits_withdrawals_common', 'Sql', 'SELECT * FROM `bx_credits_withdrawals` WHERE 1 ', 'bx_credits_withdrawals', 'id', 'added', '', '', 20, NULL, 'start', '', 'message,order', '', 'like', '', '', 2147483647, 'BxCreditsGridWithdrawalsCommon', 'modules/boonex/credits/classes/BxCreditsGridWithdrawalsCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_withdrawals_administration', 'profile_id', '_bx_credits_grid_column_title_wdw_profile_id', '15%', 0, 0, '', 1),
('bx_credits_withdrawals_administration', 'amount', '_bx_credits_grid_column_title_wdw_amount', '5%', 0, 0, '', 2),
('bx_credits_withdrawals_administration', 'rate', '_bx_credits_grid_column_title_wdw_rate', '5%', 0, 0, '', 3),
('bx_credits_withdrawals_administration', 'message', '_bx_credits_grid_column_title_wdw_message', '10%', 0, 16, '', 4),
('bx_credits_withdrawals_administration', 'added', '_bx_credits_grid_column_title_wdw_added', '5%', 0, 0, '', 5),
('bx_credits_withdrawals_administration', 'performer_id', '_bx_credits_grid_column_title_wdw_performer_id', '15%', 0, 0, '', 6),
('bx_credits_withdrawals_administration', 'order', '_bx_credits_grid_column_title_wdw_order', '15%', 0, 16, '', 7),
('bx_credits_withdrawals_administration', 'confirmed', '_bx_credits_grid_column_title_wdw_confirmed', '5%', 0, 0, '', 8),
('bx_credits_withdrawals_administration', 'status', '_bx_credits_grid_column_title_wdw_status', '5%', 0, 0, '', 9),
('bx_credits_withdrawals_administration', 'actions', '', '20%', 0, 0, '', 10),

('bx_credits_withdrawals_common', 'amount', '_bx_credits_grid_column_title_wdw_amount', '10%', 0, 0, '', 1),
('bx_credits_withdrawals_common', 'rate', '_bx_credits_grid_column_title_wdw_rate', '10%', 0, 0, '', 2),
('bx_credits_withdrawals_common', 'message', '_bx_credits_grid_column_title_wdw_message', '15%', 0, 16, '', 3),
('bx_credits_withdrawals_common', 'added', '_bx_credits_grid_column_title_wdw_added', '10%', 0, 0, '', 4),
('bx_credits_withdrawals_common', 'order', '_bx_credits_grid_column_title_wdw_order', '15%', 0, 16, '', 5),
('bx_credits_withdrawals_common', 'confirmed', '_bx_credits_grid_column_title_wdw_confirmed', '10%', 0, 0, '', 6),
('bx_credits_withdrawals_common', 'status', '_bx_credits_grid_column_title_wdw_status', '10%', 0, 0, '', 7),
('bx_credits_withdrawals_common', 'actions', '', '20%', 0, 0, '', 8);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_withdrawals_administration', 'single', 'withdraw_confirm', '_bx_credits_grid_action_title_wdw_withdraw_confirm', 'check', 1, 1, 1),

('bx_credits_withdrawals_common', 'independent', 'withdraw_request', '_bx_credits_grid_action_title_wdw_withdraw_request', '', 0, 0, 1),
('bx_credits_withdrawals_common', 'single', 'withdraw_cancel', '_bx_credits_grid_action_title_wdw_withdraw_cancel', 'times', 1, 1, 1);


-- GRIDS: profiles
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_credits_profiles_administration', 'Sql', 'SELECT * FROM `bx_credits_profiles` WHERE 1 ', 'bx_credits_profiles', 'id', '', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 192, 'BxCreditsGridProfilesAdministration', 'modules/boonex/credits/classes/BxCreditsGridProfilesAdministration.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_profiles_administration', 'id', '_bx_credits_grid_column_title_pfl_id', '40%', 0, '', '', 1),
('bx_credits_profiles_administration', 'wdw_clearing', '_bx_credits_grid_column_title_pfl_wdw_clearing', '10%', 0, '', '', 2),
('bx_credits_profiles_administration', 'wdw_minimum', '_bx_credits_grid_column_title_pfl_wdw_minimum', '10%', 0, '', '', 3),
('bx_credits_profiles_administration', 'wdw_remaining', '_bx_credits_grid_column_title_pfl_wdw_remaining', '10%', 0, '', '', 4),
('bx_credits_profiles_administration', 'balance', '_bx_credits_grid_column_title_pfl_balance', '15%', 0, '', '', 5),
('bx_credits_profiles_administration', 'balance_cleared', '_bx_credits_grid_column_title_pfl_balance_cleared', '15%', 0, '', '', 6),
('bx_credits_profiles_administration', 'actions', '', '20%', 0, '', '', 7);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_profiles_administration', 'single', 'edit', '_bx_credits_grid_action_title_pfl_edit', 'pencil-alt', 1, 0, 1);


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_credits', 'BxCreditsAlertsResponse', 'modules/boonex/credits/classes/BxCreditsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('profile', 'add', @iHandler),
('profile', 'delete', @iHandler);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_credits', '_bx_credits_et_txt_name_granted', 'bx_credits_granted', '_bx_credits_et_txt_subject_granted', '_bx_credits_et_txt_body_granted'),
('bx_credits', '_bx_credits_et_txt_name_received', 'bx_credits_received', '_bx_credits_et_txt_subject_received', '_bx_credits_et_txt_body_received'),
('bx_credits', '_bx_credits_et_txt_name_purchased', 'bx_credits_purchased', '_bx_credits_et_txt_subject_purchased', '_bx_credits_et_txt_body_purchased'),
('bx_credits', '_bx_credits_et_txt_name_in', 'bx_credits_in', '_bx_credits_et_txt_subject_in', '_bx_credits_et_txt_body_in'),
('bx_credits', '_bx_credits_et_txt_name_out', 'bx_credits_out', '_bx_credits_et_txt_subject_out', '_bx_credits_et_txt_body_out'),
('bx_credits', '_bx_credits_et_txt_name_withdraw_requested', 'bx_credits_withdraw_requested', '_bx_credits_et_txt_subject_withdraw_requested', '_bx_credits_et_txt_body_withdraw_requested'),
('bx_credits', '_bx_credits_et_txt_name_withdraw_canceled', 'bx_credits_withdraw_canceled', '_bx_credits_et_txt_subject_withdraw_canceled', '_bx_credits_et_txt_body_withdraw_canceled'),
('bx_credits', '_bx_credits_et_txt_name_withdraw_sent', 'bx_credits_withdraw_sent', '_bx_credits_et_txt_subject_withdraw_sent', '_bx_credits_et_txt_body_withdraw_sent');


-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_credits_clearing', '0 0 * * *', 'BxCreditsCronClearing', 'modules/boonex/credits/classes/BxCreditsCronClearing.php', '');