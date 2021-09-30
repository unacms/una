
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_videos' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_videos';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_videos' OR `object` IN('bx_videos_create_entry', 'bx_videos_edit_entry', 'bx_videos_delete_entry', 'bx_videos_view_entry', 'bx_videos_view_entry_comments', 'bx_videos_home', 'bx_videos_popular', 'bx_videos_top', 'bx_videos_updated', 'bx_videos_author', 'bx_videos_context', 'bx_videos_search', 'bx_videos_manage');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_videos';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_videos';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_videos' OR `set_name` IN('bx_videos_view', 'bx_videos_view_actions', 'bx_videos_submenu', 'bx_videos_view_submenu', 'bx_videos_snippet_meta', 'bx_videos_my', 'bx_videos_menu_manage_tools');


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` in('bx_videos_allow_view_to', 'bx_videos_allow_view_favorite_list');


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_videos';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_videos';


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_videos', 'bx_videos_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_videos';


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_videos_cats';


-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_videos%';


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_videos%';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_videos_administration', 'bx_videos_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_videos_administration', 'bx_videos_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_videos_administration', 'bx_videos_common');


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_videos_simple', 'bx_videos_html5', 'bx_videos_record_video');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_videos' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` = 'bx_videos_oembed_update';