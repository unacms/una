
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_market' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_market';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_market' OR `object` IN('bx_market_create_entry', 'bx_market_edit_entry', 'bx_market_delete_entry', 'bx_market_download_entry', 'bx_market_view_entry', 'bx_market_view_entry_comments', 'bx_market_home', 'bx_market_categories', 'bx_market_popular', 'bx_market_updated', 'bx_market_author', 'bx_market_search', 'bx_market_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_market';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_market';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_market' OR `set_name` IN('bx_market_view', 'bx_market_submenu', 'bx_market_view_submenu', 'bx_market_my', 'bx_market_snippet');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_market_allow_view_to', 'bx_market_allow_purchase_to', 'bx_market_allow_comment_to', 'bx_market_allow_vote_to');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_market';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_market';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_market', 'bx_market_cmts');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_market';

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_market_subentries';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_market';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_market_cats';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_market', 'bx_market_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_market');

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_market%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_market%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_market_administration', 'bx_market_common', 'bx_market_licenses');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_market_administration', 'bx_market_common', 'bx_market_licenses');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_market_administration', 'bx_market_common', 'bx_market_licenses');

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_market_simple', 'bx_market_html5');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_market' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_market%';
