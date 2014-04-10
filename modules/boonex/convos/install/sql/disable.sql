
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_convos' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_convos_files' OR `object` = 'bx_convos_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_convos_files' OR `object` = 'bx_convos_photos_resized';

DELETE FROM `sys_objects_transcoder_images` WHERE `object` = 'bx_convos_preview';
DELETE FROM `sys_transcoder_images_filters` WHERE `transcoder_object` = 'bx_convos_preview';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = 'bx_convos_preview';
-- TODO: delete resized photo files as well
TRUNCATE TABLE `bx_convos_photos_resized`; 

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_convos';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_convos' OR `object` IN('bx_convos_create_entry', 'bx_convos_view_entry', 'bx_convos_home');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_convos';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_convos';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_convos' OR `set_name` IN('bx_convos_view', 'bx_convos_submenu', 'bx_convos_menu_folders_more');

-- GRID
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_convos%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_convos%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_convos%';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_convos';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_convos';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_convos';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_convos';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_convos' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
