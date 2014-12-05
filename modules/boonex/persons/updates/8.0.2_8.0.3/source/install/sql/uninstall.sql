
-- TABLES

DROP TABLE IF EXISTS `bx_persons_data`,`bx_persons_pictures`, `bx_persons_pictures_resized`, `bx_persons_views_track`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_persons';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_persons_pictures', 'bx_persons_pictures_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_persons_pictures', 'bx_persons_pictures_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_persons_icon', 'bx_persons_thumb', 'bx_persons_avatar', 'bx_persons_picture', 'bx_persons_cover', 'bx_persons_cover_thumb');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_displays` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_person_add', 'bx_person_delete', 'bx_person_edit', 'bx_person_edit_cover', 'bx_person_view');

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_persons';

