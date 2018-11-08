
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_channels' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_channels';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_channels' OR `object` IN('bx_channels_create_profile', 'bx_channels_delete_profile', 'bx_channels_edit_profile', 'bx_channels_edit_profile_cover', 'bx_channels_view_profile', 'bx_channels_view_profile_closed', 'bx_channels_profile_info', 'bx_channels_profile_comments', 'bx_channels_author', 'bx_channels_home', 'bx_channels_top', 'bx_channels_search', 'bx_channels_manage', 'bx_channels_administration');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_channels';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_channels';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_channels' OR `set_name` IN('bx_channels_submenu', 'bx_channels_view_actions', 'bx_channels_view_actions_more', 'bx_channels_view_actions_all', 'bx_channels_my', 'bx_channels_snippet_meta', 'bx_channels_menu_manage_tools');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_channels';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_channels';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_channels';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_channels';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_channels';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_channels';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_channels';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_channels';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_channels';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_channels';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_channels_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_channels';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_channels%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_channels%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_channels_administration', 'bx_channels_moderation');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_channels_administration', 'bx_channels_moderation');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_channels_administration', 'bx_channels_moderation');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_channels' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN('bx_channels_allow_view_to', 'bx_channels_allow_view_notification_to');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_channels';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_channels_cover_crop', 'bx_channels_picture_crop');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_channels' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
