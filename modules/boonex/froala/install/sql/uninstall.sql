
-- TABLES

DROP TABLE IF EXISTS `bx_froala_files`, `bx_froala_images_resized`;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_froala_files', 'bx_froala_images_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_froala_files', 'bx_froala_images_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_froala_image');
DELETE FROM `sys_transcoder_filters` WHERE `object` IN('bx_froala_image');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_froala_image');

-- Studio page and widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_froala';

