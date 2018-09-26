SET @sName = 'bx_ribbons';;

-- TABLES
DROP TABLE IF EXISTS `bx_ribbons_data`, `bx_ribbons_profiles`, `bx_ribbons_pictures`, `bx_ribbons_pictures_resized`;

-- STUDIO WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_ribbons_pictures','bx_ribbons_pictures_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_ribbons_pictures','bx_ribbons_pictures_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_ribbons_pictures');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_ribbons_picture','bx_ribbons_pictures');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_ribbons_picture','bx_ribbons_pictures');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ribbons_entry_add', 'bx_ribbons_entry_edit');



