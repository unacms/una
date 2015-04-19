
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_albums' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_albums';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_albums' OR `object` IN('bx_albums_create_entry', 'bx_albums_edit_entry', 'bx_albums_delete_entry', 'bx_albums_view_entry', 'bx_albums_view_entry_comments', 'bx_albums_home', 'bx_albums_popular', 'bx_albums_updated', 'bx_albums_author', 'bx_albums_view_media', 'bx_albums_popular_media');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_albums';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_albums';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_albums' OR `set_name` IN('bx_albums_view', 'bx_albums_submenu', 'bx_albums_view_submenu', 'bx_albums_my');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_albums_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_albums';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_albums';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_albums', 'bx_albums_cmts', 'bx_albums_media', 'bx_albums_media_camera', 'bx_albums_media_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` IN('bx_albums', 'bx_albums_media', 'bx_albums_media_camera');

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_albums_administration', 'bx_albums_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_albums_administration', 'bx_albums_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_albums_administration', 'bx_albums_common');

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_albums_simple', 'bx_albums_html5');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_albums' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

