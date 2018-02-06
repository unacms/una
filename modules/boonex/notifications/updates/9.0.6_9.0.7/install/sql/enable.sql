SET @sName = 'bx_notifications';


-- SETTINGS
UPDATE `sys_options` SET `value`='12' WHERE `name`='bx_notifications_events_per_page';


-- MENUS
UPDATE `sys_menu_items` SET `copyable`='1' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-notifications';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='meta_mention' AND `action`='added' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('meta_mention', 'added', @iHandler);
