
-- SETTINGS

SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_persons' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_persons';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_persons' OR `object` IN('bx_persons_create_profile', 'bx_persons_delete_profile', 'bx_persons_edit_profile', 'bx_persons_edit_profile_cover', 'bx_persons_view_profile', 'bx_persons_profile_info', 'bx_persons_profile_friends', 'bx_persons_home');

-- MENU

DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_persons';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_persons';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_persons' OR `set_name` IN('bx_persons_view_submenu', 'bx_persons_submenu', 'bx_persons_view_actions', 'bx_persons_view_actions_more');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_persons';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_persons';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_persons';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_persons';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_persons_administration', 'bx_persons_moderation', 'bx_persons_common');