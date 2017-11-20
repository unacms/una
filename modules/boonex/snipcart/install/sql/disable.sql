
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_snipcart' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_snipcart' OR `object` IN('bx_snipcart_create_entry', 'bx_snipcart_edit_entry', 'bx_snipcart_delete_entry', 'bx_snipcart_view_entry', 'bx_snipcart_view_entry_comments', 'bx_snipcart_home', 'bx_snipcart_popular', 'bx_snipcart_updated', 'bx_snipcart_author', 'bx_snipcart_search', 'bx_snipcart_manage');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_snipcart' OR `set_name` IN('bx_snipcart_view', 'bx_snipcart_submenu', 'bx_snipcart_view_submenu', 'bx_snipcart_snippet_meta', 'bx_snipcart_my');


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_snipcart_allow_view_to';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_snipcart';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_snipcart';


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_snipcart', 'bx_snipcart_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_snipcart';


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_snipcart_cats';


-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_snipcart%';


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_snipcart%';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_snipcart_administration', 'bx_snipcart_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_snipcart_administration', 'bx_snipcart_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_snipcart_administration', 'bx_snipcart_common');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_snipcart' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
