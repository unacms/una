
-- TABLES

DROP TABLE IF EXISTS `bx_albums_albums`, `bx_albums_files`, `bx_albums_photos_resized`, `bx_albums_files2albums`, `bx_albums_cmts`, `bx_albums_cmts_media`, `bx_albums_votes`, `bx_albums_votes_track`, `bx_albums_votes_media`, `bx_albums_votes_media_track`, `bx_albums_views_track`, `bx_albums_views_media_track`, `bx_albums_meta_keywords`, `bx_albums_meta_keywords_media`, `bx_albums_meta_keywords_media_camera`, `bx_albums_meta_locations`;

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_albums_files' OR `object` = 'bx_albums_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_albums_files' OR `object` = 'bx_albums_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_albums_preview', 'bx_albums_browse', 'bx_albums_big', 'bx_albums_video_poster_browse', 'bx_albums_video_poster_preview', 'bx_albums_video_poster_big', 'bx_albums_video_mp4', 'bx_albums_video_webm', 'bx_albums_proxy_preview', 'bx_albums_proxy_browse');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_albums_preview', 'bx_albums_browse', 'bx_albums_big', 'bx_albums_video_poster_browse', 'bx_albums_video_poster_preview', 'bx_albums_video_poster_big', 'bx_albums_video_mp4', 'bx_albums_video_webm');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_albums_preview', 'bx_albums_browse', 'bx_albums_big', 'bx_albums_video_poster_browse', 'bx_albums_video_poster_preview', 'bx_albums_video_poster_big', 'bx_albums_video_mp4', 'bx_albums_video_webm');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_albums';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_albums';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_albums';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_albums_entry_add', 'bx_albums_entry_edit', 'bx_albums_entry_view', 'bx_albums_entry_delete');

-- COMMENTS

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_albums' OR `Name` = 'bx_albums_media';

-- VOTES

DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_albums' OR `Name` = 'bx_albums_media';

-- VIEWS

DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_albums' OR `Name` = 'bx_albums_media';

-- STUDIO: page & widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_albums';

