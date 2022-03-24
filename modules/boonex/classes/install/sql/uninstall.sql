
-- TABLES
DROP TABLE IF EXISTS `bx_classes_classes`, `bx_classes_modules`, `bx_classes_statuses`, `bx_classes_covers`, `bx_classes_files`, `bx_classes_photos`, `bx_classes_photos_resized`, `bx_classes_videos`, `bx_classes_videos_resized`, `bx_classes_sounds`, `bx_classes_sounds_resized`, `bx_classes_links`, `bx_classes_links2content`, `bx_classes_cmts`, `bx_classes_cmts_notes`, `bx_classes_votes`, `bx_classes_votes_track`, `bx_classes_reactions`, `bx_classes_reactions_track`, `bx_classes_views_track`, `bx_classes_meta_keywords`, `bx_classes_meta_locations`, `bx_classes_meta_mentions`, `bx_classes_reports`, `bx_classes_reports_track`, `bx_classes_favorites_track`, `bx_classes_scores`, `bx_classes_scores_track`, `bx_classes_polls`, `bx_classes_polls_answers`, `bx_classes_polls_answers_votes`, `bx_classes_polls_answers_votes_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_classes_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_classes_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_classes_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_classes_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_classes_%';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_classes';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_classes';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_classes';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_classes_%';

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_classes';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_classes_avail', 'bx_classes_cmts', 'bx_classes_completed_when');

-- CATEGORIES
DELETE FROM `sys_categories` WHERE `module` = 'bx_classes';
DELETE FROM `sys_categories2objects` WHERE `module` = 'bx_classes';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_classes%';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_classes', 'bx_classes_reactions', 'bx_classes_poll_answers');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_classes';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_classes';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_classes';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_classes';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_classes';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_classes', 'bx_classes_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_classes');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_classes';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_classes';
