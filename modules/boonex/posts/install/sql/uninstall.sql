
-- TABLES

DROP TABLE IF EXISTS `bx_posts_posts`, `bx_posts_files`, `bx_posts_photos_resized`, `bx_posts_cmts`, `bx_posts_votes`, `bx_posts_votes_track`, `bx_posts_views_track`, `bx_posts_meta_keywords`, `bx_posts_meta_locations`;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_posts_files' OR `object` = 'bx_posts_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_posts_files' OR `object` = 'bx_posts_photos_resized';

DELETE FROM `sys_objects_transcoder_images` WHERE `object` = 'bx_posts_preview';
DELETE FROM `sys_transcoder_images_filters` WHERE `transcoder_object` = 'bx_posts_preview';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = 'bx_posts_preview';

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_posts';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit', 'bx_posts_entry_view', 'bx_posts_entry_delete');

-- COMMENTS

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_posts';

-- VOTES

DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_posts';

-- VIEWS

DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_posts';

-- STUDIO: page & widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_posts';

