SET @sName = 'bx_quoteofday';

-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_quoteofday_internal');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_quoteofday_internal');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_quoteofday_internal');

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;

-- MENU
DELETE FROM `sys_menu_items` WHERE `module` = @sName;

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


