SET @sName = 'bx_protean';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name` IN (@sName, 'bx_protean_footer');