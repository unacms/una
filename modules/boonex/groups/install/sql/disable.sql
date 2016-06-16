
-- SETTINGS

SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_groups' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_groups';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_groups' OR `object` IN('bx_groups_create_profile', 'bx_groups_delete_profile', 'bx_groups_edit_profile', 'bx_groups_edit_profile_cover', 'bx_groups_view_profile', 'bx_groups_view_profile_closed', 'bx_groups_profile_info', 'bx_groups_home', 'bx_groups_fans', 'bx_groups_joined', 'bx_groups_top', 'bx_groups_manage', 'bx_groups_administration');

-- MENU

DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_groups';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_groups';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_groups' OR `set_name` IN('bx_groups_view_submenu', 'bx_groups_submenu', 'bx_groups_view_actions', 'bx_groups_view_actions_more');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_groups';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_groups';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_groups';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_groups';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_groups';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_groups';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_groups_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_groups';

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_groups_fans';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_groups_administration', 'bx_groups_moderation', 'bx_groups_common', 'bx_groups_fans');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_groups_administration', 'bx_groups_moderation', 'bx_groups_common', 'bx_groups_fans');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_groups_administration', 'bx_groups_moderation', 'bx_groups_common', 'bx_groups_fans');

-- ALERTS

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_groups' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 

DELETE FROM `sys_objects_privacy` WHERE `object` IN('bx_groups_allow_view_to', 'bx_groups_allow_view_notification_to');

-- EMAIL TEMPLATES

DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_groups';

-- UPLOADERS

DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_groups_cover_crop', 'bx_groups_picture_crop');

