
-- TABLES
DROP TABLE IF EXISTS `bx_photos_entries`, `bx_photos_photos`, `bx_photos_media_resized`, `bx_photos_cmts`, `bx_photos_cmts_notes`, `bx_photos_votes`, `bx_photos_votes_track`, `bx_photos_svotes`, `bx_photos_svotes_track`, `bx_photos_reactions`, `bx_photos_reactions_track`, `bx_photos_views_track`, `bx_photos_meta_keywords_camera`, `bx_photos_meta_keywords`, `bx_photos_meta_locations`, `bx_photos_meta_mentions`, `bx_photos_reports`, `bx_photos_reports_track`, `bx_photos_favorites_track`, bx_photos_favorites_lists, `bx_photos_scores`, `bx_photos_scores_track`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_photos_photos', 'bx_photos_media_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN ('bx_photos_photos', 'bx_photos_media_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_photos_preview', 'bx_photos_gallery', 'bx_photos_cover');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_photos_preview', 'bx_photos_gallery', 'bx_photos_cover');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_photos_preview', 'bx_photos_gallery', 'bx_photos_cover');


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_photos';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_photos';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_photos';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_photos_entry_add', 'bx_photos_entry_edit', 'bx_photos_entry_view', 'bx_photos_entry_delete', 'bx_photos_entry_upload');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_photos';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_photos_cats');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_photos';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_photos', 'bx_photos_stars', 'bx_photos_reactions');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_photos';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_photos';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_photos';


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_photos';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_photos';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_photos', 'bx_photos_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_photos');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_photos';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_photos';
