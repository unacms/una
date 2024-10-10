
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_courses' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_courses';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_courses' OR `object` IN('bx_courses_create_profile', 'bx_courses_delete_profile', 'bx_courses_join_profile', 'bx_courses_edit_profile', 'bx_courses_edit_profile_cover', 'bx_courses_invite', 'bx_courses_view_profile', 'bx_courses_view_profile_closed', 'bx_courses_view_profile_node', 'bx_courses_profile_info', 'bx_courses_profile_pricing', 'bx_courses_profile_comments', 'bx_courses_home', 'bx_courses_fans', 'bx_courses_joined', 'bx_courses_favorites', 'bx_courses_top', 'bx_courses_search', 'bx_courses_manage', 'bx_courses_administration');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_courses';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_courses';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_courses' OR `set_name` IN('bx_courses_view_submenu', 'bx_courses_submenu', 'bx_courses_view_actions', 'bx_courses_view_actions_more', 'bx_courses_view_actions_all', 'bx_courses_view_meta', 'bx_courses_content_add', 'bx_courses_my', 'bx_courses_snippet_meta', 'bx_courses_menu_manage_tools');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_courses';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_courses';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_courses';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_courses_cats';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_courses';

-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_courses_fans';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_courses%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_courses%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_courses_administration', 'bx_courses_moderation', 'bx_courses_common', 'bx_courses_fans', 'bx_courses_invites', 'bx_courses_prices_manage', 'bx_courses_prices_view', 'bx_courses_content_manage');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_courses_administration', 'bx_courses_moderation', 'bx_courses_common', 'bx_courses_fans', 'bx_courses_invites', 'bx_courses_prices_manage', 'bx_courses_prices_view', 'bx_courses_content_manage');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_courses_administration', 'bx_courses_moderation', 'bx_courses_common', 'bx_courses_fans', 'bx_courses_invites', 'bx_courses_prices_manage', 'bx_courses_prices_view', 'bx_courses_content_manage');

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_courses' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_courses_allow_view_to', 'bx_courses_allow_view_notification_to', 'bx_courses_allow_post_to', 'bx_courses_allow_view_favorite_list');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_courses';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_courses_cover_crop', 'bx_courses_picture_crop');

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` IN ('bx_courses_pruning');