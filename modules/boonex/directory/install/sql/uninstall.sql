
-- TABLES
DROP TABLE IF EXISTS `bx_directory_entries`, `bx_directory_categories_types`, `bx_directory_categories`, `bx_directory_covers`, `bx_directory_files`, `bx_directory_photos`, `bx_directory_photos_resized`, `bx_directory_videos`, `bx_directory_videos_resized`, `bx_directory_cmts`, `bx_directory_votes`, `bx_directory_votes_track`, `bx_directory_reactions`, `bx_directory_reactions_track`, `bx_directory_views_track`, `bx_directory_meta_keywords`, `bx_directory_meta_locations`, `bx_directory_meta_mentions`, `bx_directory_reports`, `bx_directory_reports_track`, `bx_directory_favorites_track`, `bx_directory_scores`, `bx_directory_scores_track`, `bx_directory_polls`, `bx_directory_polls_answers`, `bx_directory_polls_answers_votes`, `bx_directory_polls_answers_votes_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_directory_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_directory_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_directory_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_directory_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_directory_%';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_directory';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_directory';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_directory';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_directory_%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_directory';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_directory', 'bx_directory_reactions', 'bx_directory_poll_answers');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_directory';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_directory';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_directory';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_directory';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_directory';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_directory', 'bx_directory_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_directory');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_directory';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_directory';
