
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_events' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_events';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_events' OR `object` IN('bx_events_create_profile', 'bx_events_delete_profile', 'bx_events_join_profile', 'bx_events_edit_profile', 'bx_events_edit_profile_cover', 'bx_events_invite', 'bx_events_view_profile', 'bx_events_view_profile_closed', 'bx_events_profile_info', 'bx_events_profile_sessions', 'bx_events_profile_pricing', 'bx_events_profile_comments', 'bx_events_home', 'bx_events_fans', 'bx_events_joined', 'bx_events_favorites', 'bx_events_top', 'bx_events_upcoming', 'bx_events_past', 'bx_events_search', 'bx_events_manage', 'bx_events_administration');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_events';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_events';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_events' OR `set_name` IN('bx_events_view_submenu', 'bx_events_submenu', 'bx_events_view_actions', 'bx_events_view_actions_more', 'bx_events_view_actions_all', 'bx_events_view_meta', 'bx_events_my', 'bx_events_snippet_meta', 'bx_events_menu_manage_tools');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_events';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_events';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_events';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_events_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_events';

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_events_fans';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_events%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_events%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_events_administration', 'bx_events_moderation', 'bx_events_common', 'bx_events_fans', 'bx_events_invites', 'bx_events_sessions_manage', 'bx_events_prices_manage', 'bx_events_prices_view');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_events_administration', 'bx_events_moderation', 'bx_events_common', 'bx_events_fans', 'bx_events_invites', 'bx_events_sessions_manage', 'bx_events_prices_manage', 'bx_events_prices_view');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_events_administration', 'bx_events_moderation', 'bx_events_common', 'bx_events_fans', 'bx_events_invites', 'bx_events_sessions_manage', 'bx_events_prices_manage', 'bx_events_prices_view');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_events' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_events_allow_view_to', 'bx_events_allow_view_notification_to', 'bx_events_allow_post_to', 'bx_events_allow_view_favorite_list');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_events';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_events_cover_crop', 'bx_events_picture_crop');

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` IN ('bx_events_process_reminders', 'bx_events_pruning', 'bx_events_publishing');
