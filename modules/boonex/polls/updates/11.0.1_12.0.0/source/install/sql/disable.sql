
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_polls' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_polls';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_polls' OR `object` IN('bx_polls_create_entry', 'bx_polls_edit_entry', 'bx_polls_delete_entry', 'bx_polls_view_entry', 'bx_polls_view_entry_comments', 'bx_polls_home', 'bx_polls_popular', 'bx_polls_top', 'bx_polls_updated', 'bx_polls_author', 'bx_polls_search', 'bx_polls_context', 'bx_polls_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_polls';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_polls';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_polls' OR `set_name` IN('bx_polls_view', 'bx_polls_view_actions', 'bx_polls_submenu', 'bx_polls_view_submenu', 'bx_polls_snippet_meta', 'bx_polls_my', 'bx_polls_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_polls_allow_view_to', 'bx_polls_allow_view_favorite_list');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_polls';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_polls';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_polls', 'bx_polls_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_polls';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_polls_cats';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_polls%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_polls%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_polls_administration', 'bx_polls_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_polls_administration', 'bx_polls_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_polls_administration', 'bx_polls_common');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_polls' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
