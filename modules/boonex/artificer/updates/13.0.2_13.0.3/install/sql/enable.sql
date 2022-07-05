SET @sName = 'bx_artificer';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('change_logo', 'change_mark') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'change_logo', @iHandler),
('system', 'change_mark', @iHandler);
