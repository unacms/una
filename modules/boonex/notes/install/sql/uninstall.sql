
-- TABLES

DROP TABLE IF EXISTS `bx_notes_posts`, `bx_notes_files`, `bx_notes_photos_resized`, `bx_notes_cmts`, `bx_notes_votes`, `bx_notes_votes_track`, `bx_notes_views_track`;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_notes_files' OR `object` = 'bx_notes_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_notes_files' OR `object` = 'bx_notes_photos_resized';

DELETE FROM `sys_objects_transcoder_images` WHERE `object` = 'bx_notes_preview';
DELETE FROM `sys_transcoder_images_filters` WHERE `transcoder_object` = 'bx_notes_preview';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = 'bx_notes_preview';

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_notes_entry_add', 'bx_notes_entry_edit', 'bx_notes_entry_view', 'bx_notes_entry_delete');

-- COMMENTS

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_notes';

-- VOTES

DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_notes';

-- VIEWS

DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_notes';

-- STUDIO: page & widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_notes';

