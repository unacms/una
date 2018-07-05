SET @sName = 'bx_charts';

-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` IN (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId);
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_charts%';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
