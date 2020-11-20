
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_files' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_files';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_files' OR `object` IN('bx_files_create_entry', 'bx_files_edit_entry', 'bx_files_delete_entry', 'bx_files_view_entry', 'bx_files_view_entry_comments', 'bx_files_home', 'bx_files_popular', 'bx_files_top', 'bx_files_context', 'bx_files_updated', 'bx_files_author', 'bx_files_search', 'bx_files_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_files';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_files';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_files' OR `set_name` IN('bx_files_view', 'bx_files_view_actions', 'bx_files_submenu', 'bx_files_view_submenu', 'bx_files_snippet_meta', 'bx_files_my', 'bx_files_menu_manage_tools', 'bx_files_view_inline');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_files_allow_view_to', 'bx_files_allow_view_favorite_list');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_files';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_files';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_files', 'bx_files_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_files';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_files_cats';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_files%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_files%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_files_administration', 'bx_files_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_files_administration', 'bx_files_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_files_administration', 'bx_files_common');

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_files_simple', 'bx_files_html5');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_files' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` = 'bx_files_process_data';

