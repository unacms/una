
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_glossary' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_glossary' OR `object` IN('bx_glossary_create_entry', 'bx_glossary_edit_entry', 'bx_glossary_delete_entry', 'bx_glossary_view_entry', 'bx_glossary_view_entry_comments', 'bx_glossary_home', 'bx_glossary_popular', 'bx_glossary_updated', 'bx_glossary_author', 'bx_glossary_search', 'bx_glossary_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_glossary' OR `set_name` IN('bx_glossary_view', 'bx_glossary_submenu', 'bx_glossary_view_submenu', 'bx_glossary_snippet_meta', 'bx_glossary_my', 'bx_glossary_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_glossary_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_glossary';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_glossary';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_glossary', 'bx_glossary_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_glossary';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_glossary_cats';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_glossary%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_glossary%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_glossary_administration', 'bx_glossary_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_glossary_administration', 'bx_glossary_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_glossary_administration', 'bx_glossary_common');

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_glossary_simple', 'bx_glossary_html5');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_glossary' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
