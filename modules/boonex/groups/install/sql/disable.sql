DELETE FROM `sys_objects_page` WHERE `module` = 'bx_groups';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_groups';

-- compose pages
--DELETE FROM `sys_page_compose_pages` WHERE `Name` IN('bx_groups_view', 'bx_groups_celendar', 'bx_groups_main', 'bx_groups_my');
--DELETE FROM `sys_page_compose` WHERE `Page` IN('bx_groups_view', 'bx_groups_celendar', 'bx_groups_main', 'bx_groups_my');
--DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Groups';

-- system objects
DELETE FROM `sys_objects_vote` WHERE `ObjectName` = 'bx_groups';
DELETE FROM `sys_objects_cmts` WHERE `ObjectName` = 'bx_groups';
DELETE FROM `sys_objects_views` WHERE `name` = 'bx_groups';
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_groups';
--DELETE FROM `sys_objects_actions` WHERE `Type` = 'bx_groups' OR `Type` = 'bx_groups_title';
DELETE FROM `sys_stat_site` WHERE `Name` = 'bx_groups';

-- email templates
-- DELETE FROM `sys_email_templates` WHERE `Name` = 'bx_groups_broadcast' OR `Name` = 'bx_groups_join_request' OR `Name` = 'bx_groups_join_reject' OR `Name` = 'bx_groups_join_confirm' OR `Name` = 'bx_groups_fan_remove' OR `Name` = 'bx_groups_fan_become_admin' OR `Name` = 'bx_groups_admin_become_fan' OR `Name` = 'bx_groups_sbs' OR `Name` = 'bx_groups_invitation';

-- menus
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_groups';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_groups';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_groups';

-- settings
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`='bx_groups';

-- membership levels
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Name` IN('groups view group', 'groups browse', 'groups search', 'groups add group', 'groups comments delete and edit', 'groups edit any group', 'groups delete any group', 'groups mark as featured', 'groups approve groups', 'groups broadcast message');
DELETE FROM `sys_acl_actions` WHERE `Name` IN('groups view group', 'groups browse', 'groups search', 'groups add group', 'groups comments delete and edit', 'groups edit any group', 'groups delete any group', 'groups mark as featured', 'groups approve groups', 'groups broadcast message');

-- alerts
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_groups_profile_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_groups_account_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- privacy
DELETE FROM `sys_privacy_actions` WHERE `module_uri` = 'groups';

