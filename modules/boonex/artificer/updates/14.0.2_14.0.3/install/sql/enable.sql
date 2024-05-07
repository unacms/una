SET @sName = 'bx_artificer';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('change_logo_dark', 'change_mark_dark') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'change_logo_dark', @iHandler),
('system', 'change_mark_dark', @iHandler);
