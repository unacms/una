
-- TABLES
DROP TABLE IF EXISTS `bx_stream_streams`, `bx_stream_covers`, `bx_stream_photos_resized`, `bx_stream_cmts`, `bx_stream_cmts_notes`, `bx_stream_votes`, `bx_stream_votes_track`, `bx_stream_reactions`, `bx_stream_reactions_track`, `bx_stream_views_track`, `bx_stream_meta_keywords`, `bx_stream_meta_locations`, `bx_stream_meta_mentions`, `bx_stream_reports`, `bx_stream_reports_track`, `bx_stream_scores`, `bx_stream_scores_track`, `bx_stream_polls`, `bx_stream_polls_answers`, `bx_stream_polls_answers_votes`, `bx_stream_polls_answers_votes_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_stream_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_stream_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_stream_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_stream_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_stream_%';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_stream';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_stream';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_stream';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_stream_%';

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_stream';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_stream_cats');

-- CATEGORIES
DELETE FROM `sys_categories` WHERE `module` = 'bx_stream';
DELETE FROM `sys_categories2objects` WHERE `module` = 'bx_stream';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_stream%';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_stream', 'bx_stream_reactions');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_stream';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_stream';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_stream';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_stream';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_stream', 'bx_stream_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_stream');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_stream';

-- LOGS
DELETE FROM `sys_objects_logs` WHERE `module` = 'bx_stream';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_stream';
