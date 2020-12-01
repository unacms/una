
-- TABLES
DROP TABLE IF EXISTS `bx_polls_entries`, `bx_polls_subentries`, `bx_polls_files`, `bx_polls_photos_resized`, `bx_polls_cmts`, `bx_polls_cmts_notes`, `bx_polls_votes`, `bx_polls_votes_track`, `bx_polls_votes_subentries`, `bx_polls_votes_subentries_track`, `bx_polls_reactions`, `bx_polls_reactions_track`, `bx_polls_views_track`, `bx_polls_meta_keywords`, `bx_polls_meta_locations`, `bx_polls_meta_mentions`, `bx_polls_reports`, `bx_polls_reports_track`, `bx_polls_favorites_track`, `bx_polls_favorites_lists`, `bx_polls_scores`, `bx_polls_scores_track`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_polls_files' OR `object` = 'bx_polls_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_polls_files' OR `object` = 'bx_polls_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_polls_preview', 'bx_polls_gallery', 'bx_polls_cover');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_polls_preview', 'bx_polls_gallery', 'bx_polls_cover');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_polls_preview', 'bx_polls_gallery', 'bx_polls_cover');


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_polls';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_polls';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_polls';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_polls_entry_add', 'bx_polls_entry_edit', 'bx_polls_entry_view', 'bx_polls_entry_delete');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_polls';
DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_polls_cats');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_polls%';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` LIKE 'bx_polls%';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_polls';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_polls';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_polls';


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_polls';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_polls';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_polls', 'bx_polls_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_polls');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_polls';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_polls';
