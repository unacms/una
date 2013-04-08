
-- SETTINGS

SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_persons' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_persons_pictures' OR `object` = 'bx_persons_pictures_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_persons_pictures' OR `object` = 'bx_persons_pictures_resized';

DELETE FROM `sys_objects_transcoder_images` WHERE `object` = 'bx_persons_thumb' OR `object` = 'bx_persons_preview';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = 'bx_persons_thumb' OR `transcoder_object` = 'bx_persons_preview';
-- TODO: delete resized picture files as well
TRUNCATE TABLE `bx_persons_pictures_resized`; 

-- PAGES

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_persons';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_persons' OR `object` = 'bx_persons_create_profile' OR `object` = 'bx_persons_delete_profile' OR `object` = 'bx_persons_edit_profile' OR `object` = 'bx_persons_view_profile';

-- MENU

DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_persons';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_persons';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_persons' OR `set_name` = 'bx_persons_view';

-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_persons';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_persons';

