
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_shopify' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_shopify' OR `object` IN('bx_shopify_create_entry', 'bx_shopify_edit_entry', 'bx_shopify_delete_entry', 'bx_shopify_view_entry', 'bx_shopify_view_entry_comments', 'bx_shopify_home', 'bx_shopify_popular', 'bx_shopify_updated', 'bx_shopify_author');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_shopify' OR `set_name` IN('bx_shopify_view', 'bx_shopify_submenu', 'bx_shopify_view_submenu', 'bx_shopify_my');


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_shopify_allow_view_to';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_shopify';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_shopify';


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_shopify', 'bx_shopify_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_shopify';


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_shopify_cats';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` = 'bx_shopify';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_shopify_administration', 'bx_shopify_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_shopify_administration', 'bx_shopify_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_shopify_administration', 'bx_shopify_common');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_shopify' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
