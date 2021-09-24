SET @sName = 'bx_artificer';


-- pages
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName; -- OR `object` IN ();


-- alerts
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


-- injections
DELETE FROM `sys_injections` WHERE `name` = @sName;