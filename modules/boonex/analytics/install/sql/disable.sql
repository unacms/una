SET @sName = 'bx_analytics';

-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` IN (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId);
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;

-- MENU
DELETE FROM `sys_menu_items` WHERE `module` = @sName ;
