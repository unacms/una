-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_sites' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_sites';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_sites';


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_sites';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_sites';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_sites' OR `set_name` = 'bx_sites_view';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_sites';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_sites';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_sites';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_sites_browse', 'bx_sites_overview');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_sites_browse', 'bx_sites_overview');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_sites_browse', 'bx_sites_overview');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_sites' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;