SET @sName = 'bx_notifications';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_notifications_settings';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_settings', '_bx_ntfs_page_title_system_settings', '_bx_ntfs_page_title_settings', @sName, 1, 2147483647, 1, 'notifications-settings', 'page.php?i=notifications-settings', '', '', '', 0, 1, 0, 'BxNtfsPageSettings', 'modules/boonex/notifications/classes/BxNtfsPageSettings.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_notifications_settings';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_notifications_settings', 1, @sName, '_bx_ntfs_page_block_title_delivery', 13, 2147483644, 'menu', 'bx_notifications_settings', 0, 0, 1),
('bx_notifications_settings', 2, @sName, '_bx_ntfs_page_block_title_settings', 13, 2147483644, 'service', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:18:"get_block_settings";}', 0, 1, 1);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_notifications_settings';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_settings', '_bx_ntfs_menu_title_settings', 'bx_notifications_settings', @sName, 6, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_notifications_settings';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notifications_settings', @sName, '_bx_ntfs_menu_set_title_settings', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_notifications_settings';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notifications_settings', @sName, 'notifications-site', '_bx_ntfs_menu_item_title_system_notifications_site', '_bx_ntfs_menu_item_title_notifications_site', 'page.php?i=notifications-settings&delivery=site', '', '', '', '', 2147483647, 1, 0, 10),
('bx_notifications_settings', @sName, 'notifications-email', '_bx_ntfs_menu_item_title_system_notifications_email', '_bx_ntfs_menu_item_title_notifications_email', 'page.php?i=notifications-settings&delivery=email', '', '', '', '', 2147483647, 1, 0, 20),
('bx_notifications_settings', @sName, 'notifications-push', '_bx_ntfs_menu_item_title_system_notifications_push', '_bx_ntfs_menu_item_title_notifications_push', 'page.php?i=notifications-settings&delivery=push', '', '', '', '', 2147483647, 1, 0, 30);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `name`='notifications-settings';
SET @iMoAccountSettings = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `order` < 9999 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_settings', @sName, 'notifications-settings', '_bx_ntfs_menu_item_title_system_notifications', '_bx_ntfs_menu_item_title_notifications', 'page.php?i=notifications-settings', '', '', 'bell col-green3', '', '', 2147483646, 1, 0, 1, @iMoAccountSettings + 1);


-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options_categories` WHERE `name`='bx_notifications_system';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES 
(@iTypeId, 'bx_notifications_system', '_bx_ntfs_options_category_system', 1, 1);
SET @iCategId = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id`=@iCategId WHERE `name` IN ('bx_notifications_events_hide_site', 'bx_notifications_events_hide_email', 'bx_notifications_events_hide_push');

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_notifications_enable_group_settings';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_enable_group_settings', 'on', @iCategId, '_bx_ntfs_option_enable_group_settings', 'checkbox', '', '', '', '', 10);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_notifications_settings_administration', 'bx_notifications_settings_common');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_notifications_settings_administration', 'Sql', 'SELECT `ts`.*, `th`.`alert_unit` AS `unit`, `th`.`alert_action` AS `action` FROM `bx_notifications_settings` AS `ts` LEFT JOIN `bx_notifications_handlers` AS `th` ON `ts`.`handler_id`=`th`.`id` WHERE 1 ', 'bx_notifications_settings', 'id', 'order', 'active', '', 100, NULL, 'start', '', '', 'ts`.`title', 'auto', '', 192, 'BxNtfsGridSettingsAdministration', 'modules/boonex/notifications/classes/BxNtfsGridSettingsAdministration.php'),
('bx_notifications_settings_common', 'Sql', 'SELECT `tsu`.`id` AS `id`, `ts`.`group` AS `group`, `ts`.`id` AS `setting_id`, `ts`.`type` AS `type`, `ts`.`delivery` AS `delivery`, `ts`.`title` AS `title`, `tsu`.`active` AS `active`, `ts`.`order` AS `order`, `th`.`alert_unit` AS `unit`, `th`.`alert_action` AS `action` FROM `bx_notifications_settings` AS `ts` LEFT JOIN `bx_notifications_handlers` AS `th` ON `ts`.`handler_id`=`th`.`id` LEFT JOIN `bx_notifications_settings2users` AS `tsu` ON `ts`.`id`=`tsu`.`setting_id` WHERE `ts`.`active`=''1'' ', 'bx_notifications_settings2users', 'id', 'order', 'active', '', 100, NULL, 'start', '', '', 'ts`.`title', 'auto', '', 2147483647, 'BxNtfsGridSettingsCommon', 'modules/boonex/notifications/classes/BxNtfsGridSettingsCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_notifications_settings_administration', 'bx_notifications_settings_common');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_notifications_settings_administration', 'checkbox', '', '1%', 0, '', '', 1),
('bx_notifications_settings_administration', 'switcher', '', '10%', 0, '', '', 2),
('bx_notifications_settings_administration', 'title', '_bx_ntfs_grid_column_title_title', '89%', 1, '', '', 10),

('bx_notifications_settings_common', 'checkbox', '', '1%', 0, '', '', 1),
('bx_notifications_settings_common', 'switcher', '', '10%', 0, '', '', 2),
('bx_notifications_settings_common', 'title', '_bx_ntfs_grid_column_title_title', '89%', 1, '', '', 10);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_notifications_settings_administration', 'bx_notifications_settings_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_notifications_settings_administration', 'bulk', 'deactivate', '_bx_ntfs_grid_action_title_deactivate', '', 1, 0),

('bx_notifications_settings_common', 'bulk', 'deactivate', '_bx_ntfs_grid_action_title_deactivate', '', 1, 0);
