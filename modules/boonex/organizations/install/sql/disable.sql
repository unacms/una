
-- SETTINGS

SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_organizations' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');

DELETE FROM `sys_objects_transcoder_images` WHERE `object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
DELETE FROM `sys_transcoder_images_filters` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
-- TODO: delete resized picture files as well
TRUNCATE TABLE `bx_organizations_pics_resized`; 

-- PAGES

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_organizations' OR `object` IN('bx_organizations_create_profile', 'bx_organizations_delete_profile', 'bx_organizations_edit_profile', 'bx_organizations_edit_profile_cover', 'bx_organizations_view_profile', 'bx_organizations_profile_info', 'bx_organizations_profile_friends', 'bx_organizations_home');

-- MENU

DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_organizations' OR `set_name` IN('bx_organizations_view_submenu', 'bx_organizations_submenu', 'bx_organizations_view_actions', 'bx_organizations_view_actions_more');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_organizations';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_organizations';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_organizations';
