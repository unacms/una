
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_classes' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_classes';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_classes' OR `object` IN('bx_classes_create_entry', 'bx_classes_edit_entry', 'bx_classes_delete_entry', 'bx_classes_view_entry', 'bx_classes_view_entry_comments', 'bx_classes_context', 'bx_classes_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_classes';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_classes';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_classes' OR `set_name` IN('bx_classes_entry_attachments', 'bx_classes_view', 'bx_classes_view_actions', 'bx_classes_view_submenu', 'bx_classes_snippet_meta', 'bx_classes_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_classes_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_classes';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_classes';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_classes', 'bx_classes_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_classes';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_classes%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_classes%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_classes_administration', 'bx_classes_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_classes_administration', 'bx_classes_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_classes_administration', 'bx_classes_common');

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_classes_%';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_classes' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_classes%';
