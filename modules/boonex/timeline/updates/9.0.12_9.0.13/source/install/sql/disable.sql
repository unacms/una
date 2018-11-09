-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_timeline' OR `object` IN('bx_timeline_view', 'bx_timeline_view_home', 'bx_timeline_item', 'bx_photos_item_brief');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_timeline' OR `set_name` IN('bx_timeline_menu_item_share', 'bx_timeline_menu_item_manage', 'bx_timeline_menu_item_actions', 'bx_timeline_menu_item_meta', 'bx_timeline_menu_post_attachments');


-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_timeline' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `module` = 'bx_timeline';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_timeline';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_timeline';


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_timeline' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandlerId;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_timeline' LIMIT 1;


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_timeline' LIMIT 1;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_timeline' LIMIT 1;


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_timeline';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `Name` = 'bx_timeline' LIMIT 1;


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_timeline', 'bx_timeline_cmts');

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_timeline_administration');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_timeline_administration');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_timeline_administration');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_timeline';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_timeline';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_timeline', 'bx_timeline_cmts');


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_timeline%';


-- PRELOADER
DELETE FROM `sys_preloader` WHERE `module` = 'bx_timeline';
