SET @sName = 'bx_notifications';


-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_view', '_bx_ntfs_page_title_system_view', '_bx_ntfs_page_title_view', @sName, 5, 2147483647, 1, 'notifications-view', 'page.php?i=notifications-view', '', '', '', 0, 1, 0, 'BxNtfsPageView', 'modules/boonex/notifications/classes/BxNtfsPageView.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_notifications_view', 1, @sName, '_bx_ntfs_page_block_title_view', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:14:"get_block_view";}', 0, 1, 1);


-- MENU: module sub-menu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_submenu', '_bx_ntfs_menu_title_submenu', 'bx_notifications_submenu', @sName, 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notifications_submenu', @sName, '_bx_ntfs_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notifications_submenu', @sName, 'notifications-all', '_bx_ntfs_menu_item_title_system_notifications_all', '_bx_ntfs_menu_item_title_notifications_all', 'page.php?i=notifications-view', '', '', '', '', 2147483647, 1, 0, 1);

-- MENU: Notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', @sName, 'notifications-notifications', '_bx_ntfs_menu_item_title_system_notifications', '_bx_ntfs_menu_item_title_notifications', 'page.php?i=notifications-view', '', '', 'bell col-green3', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:28:"get_unread_notifications_num";}', '', 2147483646, 1, 0, @iMIOrder + 1);


-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_ntfs', 'bx_notifications@modules/boonex/notifications/|std-mi.png', @iTypeOrder + 1);
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_ntfs', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_events_per_page', '10', @iCategId, '_bx_ntfs_option_events_per_page', 'digit', '', '', '', '', 1),
('bx_notifications_events_hide', '', @iCategId, '_bx_ntfs_option_events_hide', 'rlist', '', '', '', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:21:"get_actions_checklist";}', 2);


-- PRIVACY 
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_notifications_privacy_view', @sName, 'view', '_bx_notifications_privacy_view', '3', 'bx_notifications_events', 'id', 'owner_id', 'BxNtfsPrivacy', 'modules/boonex/notifications/classes/BxNtfsPrivacy.php');


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxNtfsResponse', 'modules/boonex/notifications/classes/BxNtfsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'delete', @iHandler);


-- MODULES' CONNECTIONS
INSERT INTO `sys_modules_relations` (`module`, `on_install`, `on_uninstall`, `on_enable`, `on_disable`) VALUES
(@sName, '', 'delete_module_events', 'add_handlers', 'delete_handlers');
