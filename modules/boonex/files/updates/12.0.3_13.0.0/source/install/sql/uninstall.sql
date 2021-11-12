
-- TABLES
DROP TABLE IF EXISTS `bx_files_main`, `bx_files_files`, `bx_files_photos_resized`, `bx_files_cmts`, `bx_files_cmts_notes`, `bx_files_votes`, `bx_files_votes_track`, `bx_files_reactions`, `bx_files_reactions_track`, `bx_files_views_track`, `bx_files_meta_keywords`, `bx_files_meta_mentions`, `bx_files_reports`, `bx_files_reports_track`, `bx_files_favorites_track`, `bx_files_favorites_lists`, `bx_files_scores`, `bx_files_scores_track`, `bx_files_bookmarks`, `bx_files_downloading_jobs`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_files_files' OR `object` = 'bx_files_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_files_files' OR `object` = 'bx_files_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_files_preview', 'bx_files_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_files_preview', 'bx_files_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_files_preview', 'bx_files_gallery');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_files';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_files';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_files';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_files_entry_upload', 'bx_files_entry_edit', 'bx_files_entry_view', 'bx_files_entry_delete');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_files';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_files_cats');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_files';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_files', 'bx_files_reactions');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_files';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_files';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_files';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_files';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_files';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_files', 'bx_files_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_files');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_files';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_files';
