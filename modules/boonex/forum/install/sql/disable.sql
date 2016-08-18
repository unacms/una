SET @sName = 'bx_forum';


-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` LIKE 'bx_forum%';


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName OR `set_name` IN('bx_forum_view', 'bx_forum_submenu', 'bx_forum_my');


-- GRID
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_forum%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_forum%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_forum%';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_forum_allow_view_to');


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_forum', 'bx_forum_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = @sName;


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_forum_cats';


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = @sName;


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = @sName;


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;
