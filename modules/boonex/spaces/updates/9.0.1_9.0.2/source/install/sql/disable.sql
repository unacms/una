
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_spaces' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_spaces' OR `object` IN('bx_spaces_create_profile', 'bx_spaces_delete_profile', 'bx_spaces_edit_profile', 'bx_spaces_edit_profile_cover', 'bx_spaces_invite', 'bx_spaces_view_profile', 'bx_spaces_view_profile_closed', 'bx_spaces_profile_info', 'bx_spaces_profile_comments', 'bx_spaces_home', 'bx_spaces_fans', 'bx_spaces_joined', 'bx_spaces_top', 'bx_spaces_search', 'bx_spaces_manage', 'bx_spaces_administration');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_spaces';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_spaces' OR `set_name` IN('bx_spaces_view_submenu', 'bx_spaces_submenu', 'bx_spaces_view_actions', 'bx_spaces_view_actions_more', 'bx_spaces_view_actions_all', 'bx_spaces_my', 'bx_spaces_snippet_meta', 'bx_spaces_menu_manage_tools');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_spaces';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_spaces';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_spaces';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_spaces';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_spaces';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_spaces';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_spaces';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_spaces';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_spaces';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_spaces';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_spaces_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_spaces';

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_spaces_fans';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_spaces%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_spaces%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_spaces_administration', 'bx_spaces_moderation', 'bx_spaces_common', 'bx_spaces_fans');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_spaces_administration', 'bx_spaces_moderation', 'bx_spaces_common', 'bx_spaces_fans');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_spaces_administration', 'bx_spaces_moderation', 'bx_spaces_common', 'bx_spaces_fans');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_spaces' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN('bx_spaces_allow_view_to', 'bx_spaces_allow_view_notification_to');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_spaces';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_spaces_cover_crop', 'bx_spaces_picture_crop');
