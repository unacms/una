SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_notifications' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action`='delete' AND `handler_id`=@iHandler LIMIT 1;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES ('profile', 'delete', @iHandler);