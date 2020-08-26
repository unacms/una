
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_photos' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_photos';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_photos' OR `object` IN('bx_photos_create_entry', 'bx_photos_edit_entry', 'bx_photos_delete_entry', 'bx_photos_view_entry', 'bx_photos_view_entry_brief', 'bx_photos_view_entry_comments', 'bx_photos_home', 'bx_photos_popular', 'bx_photos_top', 'bx_photos_updated', 'bx_photos_author', 'bx_photos_context', 'bx_photos_search', 'bx_photos_manage');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_photos';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_photos';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_photos' OR `set_name` IN('bx_photos_view', 'bx_photos_view_actions', 'bx_photos_submenu', 'bx_photos_view_submenu', 'bx_photos_snippet_meta', 'bx_photos_my', 'bx_photos_menu_manage_tools');


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_photos_allow_view_to', 'bx_photos_allow_view_favorite_list');


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_photos';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_photos';


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_photos', 'bx_photos_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` IN ('bx_photos', 'bx_photos_camera');


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_photos_cats';


-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_photos%';


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_photos%';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_photos_administration', 'bx_photos_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_photos_administration', 'bx_photos_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_photos_administration', 'bx_photos_common');


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_photos_simple', 'bx_photos_html5');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_photos' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
