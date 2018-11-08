SET @sName = 'bx_acl';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='acl' AND `action`='deleted' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('acl', 'deleted', @iHandler);
