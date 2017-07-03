SET @sName = 'bx_notifications';


-- PAGES
SET @iPBCellDashboard = 3;
SET @iPBOrderDashboard = 4;
DELETE FROM `sys_pages_blocks` WHERE `object`='sys_dashboard' AND `title`='_bx_ntfs_page_block_title_view';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, @sName, '_bx_ntfs_page_block_title_view', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:14:"get_block_view";}', 0, 0, @iPBOrderDashboard);


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`=@sName;
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
(@sName, 1, 'a:3:{s:6:"module";s:16:"bx_notifications";s:6:"method";s:16:"get_live_updates";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:27:"notifications-notifications";}i:2;s:7:"{count}";}}', 1);


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='sys_profiles_friends' AND `action` IN ('connection_added', 'connection_removed') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_profiles_friends', 'connection_added', @iHandler),
('sys_profiles_friends', 'connection_removed', @iHandler);
