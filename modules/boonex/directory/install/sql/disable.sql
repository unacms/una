
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_directory' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_directory';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_directory' OR `object` IN ('bx_directory_create_entry', 'bx_directory_edit_entry', 'bx_directory_delete_entry', 'bx_directory_view_entry', 'bx_directory_view_entry_comments', 'bx_directory_home', 'bx_directory_popular', 'bx_directory_updated', 'bx_directory_author', 'bx_directory_context', 'bx_directory_search', 'bx_directory_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_directory';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_directory';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_directory' OR `set_name` IN ('bx_directory_create_post_attachments', 'bx_directory_view', 'bx_directory_view_actions', 'bx_directory_submenu', 'bx_directory_view_submenu', 'bx_directory_snippet_meta', 'bx_directory_my', 'bx_directory_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_directory_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_directory';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_directory';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_directory', 'bx_directory_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_directory';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_directory%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_directory%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_directory%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_directory%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_directory%';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_directory_%';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_directory' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_directory%';
