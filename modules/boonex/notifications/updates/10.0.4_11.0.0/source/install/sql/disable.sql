SET @sName = 'bx_notifications';


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` IN('bx_notifications_view', 'bx_notifications_settings');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName OR `set_name` IN('bx_notifications_submenu', 'bx_notifications_preview', 'bx_notifications_settings');


-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` IN (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId);
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `module` = @sName;


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name` LIKE 'bx_notifications%';


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandlerId;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;


-- GRID
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_notifications%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_notifications%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_notifications%';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_notifications%';
