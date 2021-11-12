
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_ads' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_ads';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_ads' OR `object` IN ('bx_ads_create_entry', 'bx_ads_edit_entry', 'bx_ads_delete_entry', 'bx_ads_view_entry', 'bx_ads_view_entry_comments', 'bx_ads_home', 'bx_ads_popular', 'bx_ads_updated', 'bx_ads_categories', 'bx_ads_author', 'bx_ads_context', 'bx_ads_search', 'bx_ads_manage', 'bx_ads_administration', 'bx_ads_licenses', 'bx_ads_licenses_administration', 'bx_ads_offers', 'bx_ads_offers_all');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_ads';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_ads';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_ads' OR `set_name` IN ('bx_ads_create_post_attachments', 'bx_ads_view', 'bx_ads_view_actions', 'bx_ads_submenu', 'bx_ads_view_submenu', 'bx_ads_snippet_meta', 'bx_ads_my', 'bx_ads_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_ads_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_ads';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_ads';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_ads', 'bx_ads_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_ads';

-- STATS
DELETE FROM `sys_statistics` WHERE `module` = 'bx_ads';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_ads_%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_ads_%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_ads_%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_ads_%';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_ads_%';

-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name` = 'bx_ads';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_ads' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_ads_%';

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_ads';
