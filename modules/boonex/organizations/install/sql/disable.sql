
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_organizations' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_organizations' OR `object` IN('bx_organizations_create_profile', 'bx_organizations_delete_profile', 'bx_organizations_join_profile', 'bx_organizations_edit_profile', 'bx_organizations_edit_profile_cover', 'bx_organizations_invite', 'bx_organizations_view_profile', 'bx_organizations_view_profile_closed', 'bx_organizations_profile_info', 'bx_organizations_profile_pricing', 'bx_organizations_fans', 'bx_organizations_profile_friends', 'bx_organizations_friend_requests', 'bx_organizations_profile_favorites', 'bx_organizations_profile_subscriptions', 'bx_organizations_profile_comments', 'bx_organizations_home', 'bx_organizations_search', 'bx_organizations_manage', 'bx_organizations_joined');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_organizations' OR `set_name` IN('bx_organizations_view_submenu', 'bx_organizations_submenu', 'bx_organizations_view_actions', 'bx_organizations_view_actions_more', 'bx_organizations_view_actions_all', 'bx_organizations_view_meta', 'bx_organizations_my', 'bx_organizations_snippet_meta', 'bx_organizations_menu_manage_tools');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_organizations';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_organizations';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_organizations';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_organizations_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_organizations', 'bx_organizations_cmts');

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_organizations_fans';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_organizations%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_organizations%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_organizations%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_organizations%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_organizations%';

-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name` = 'bx_organizations_friend_requests';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_organizations' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_organizations_allow_view_to', 'bx_organizations_allow_view_notification_to', 'bx_organizations_allow_post_to', 'bx_organizations_allow_contact_to', 'bx_organizations_allow_view_favorite_list');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_organizations';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_organizations_cover_crop', 'bx_organizations_picture_crop');

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` IN ('bx_organizations_pruning');