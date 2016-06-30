-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_timeline';


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_timeline';


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
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_timeline' LIMIT 1;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_timeline' LIMIT 1;


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `Name` = 'bx_timeline' LIMIT 1;


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_timeline', 'bx_timeline_cmts');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_timeline';


-- MODULES' CONNECTIONS
DELETE FROM `sys_modules_relations` WHERE `module`='bx_timeline' LIMIT 1;