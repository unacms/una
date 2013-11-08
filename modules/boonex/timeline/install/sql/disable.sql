-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_timeline';


-- MENU
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_timeline';


-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_timeline' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` = 'bx_timeline_simple';
DEKETE FROM `sys_objects_storage` WHERE `object` IN ('bx_timeline_photos', 'bx_timeline_photos_preview');
DELETE FROM `sys_objects_transcoder_images` WHERE `object` IN ('bx_timeline_photos_preview');
DELETE FROM `sys_transcoder_images_filters` WHERE `transcoder_object` IN ('bx_timeline_photos_preview');


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_timeline';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_timeline';


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_timeline' LIMIT 1);
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_timeline' LIMIT 1;
