SET @sName = 'bx_forum';


-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` LIKE 'bx_forum%';


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName OR `set_name` IN('bx_forum_view', 'bx_forum_view_more', 'bx_forum_submenu', 'bx_forum_my');


-- GRID
DELETE FROM `sys_objects_grid` WHERE `object` IN (@sName, 'bx_forum_favorite', 'bx_forum_feature', 'bx_forum_administration', 'bx_forum_common', 'bx_forum_categories');
DELETE FROM `sys_grid_fields` WHERE `object` IN (@sName, 'bx_forum_favorite', 'bx_forum_feature', 'bx_forum_administration', 'bx_forum_common', 'bx_forum_categories');
DELETE FROM `sys_grid_actions` WHERE `object` IN (@sName, 'bx_forum_favorite', 'bx_forum_feature', 'bx_forum_administration', 'bx_forum_common', 'bx_forum_categories');


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_forum_allow_view_to');


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_forum', 'bx_forum_cmts');


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_forum_subscribers';


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = @sName;


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_forum_cats';


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = @sName;


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = @sName;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = @sName;


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `Name` = @sName;


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = @sName;


-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_forum%';


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_forum%';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_forum_simple', 'bx_forum_html5');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;
