SET @sName = 'bx_notifications';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' AND `name`='notifications-preview';
SET @iMenuToolbarMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_member', @sName, 'notifications-preview', '_bx_ntfs_menu_item_title_system_preview', '_bx_ntfs_menu_item_title_preview', 'javascript:void(0)', 'bx_menu_slide(''bx_notifications_preview'', this, ''site'', {id:{value:''bx_notifications_preview'', force:1}});', '', 'bell col-green3', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:28:"get_unread_notifications_num";}', 'bx_notifications_preview', 1, 2147483646, '', 1, 1, 0);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_notifications_system' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_notifications_processed_event';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_processed_event', '', @iCategId, '_bx_ntfs_option_processed_event', 'digit', '', '', '', '', 10);

UPDATE `sys_options` SET `value`=(SELECT MAX(`id`) FROM `bx_notifications_events` WHERE 1 LIMIT 1) WHERE `name`='bx_notifications_processed_event';


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`='bx_notifications_toolbar';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('bx_notifications_toolbar', 1, 'a:3:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:16:"get_live_updates";s:6:"params";a:3:{i:0;a:0:{}i:1;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:21:"notifications-preview";}i:2;s:7:"{count}";}}', 1);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_notifications_notify';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_notifications_notify', '* * * * *', 'BxNtfsCronNotify', 'modules/boonex/notifications/classes/BxNtfsCronNotify.php', '');
