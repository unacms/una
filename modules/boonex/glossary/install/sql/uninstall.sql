
-- TABLES
DROP TABLE IF EXISTS `bx_glossary_terms`, `bx_glossary_files`, `bx_glossary_photos_resized`, `bx_glossary_cmts`, `bx_glossary_cmts_notes`, `bx_glossary_votes`, `bx_glossary_votes_track`, `bx_glossary_reactions`, `bx_glossary_reactions_track`, `bx_glossary_views_track`, `bx_glossary_meta_keywords`, `bx_glossary_meta_mentions`, `bx_glossary_reports`, `bx_glossary_reports_track`, `bx_glossary_favorites_track`, `bx_glossary_favorites_lists`, `bx_glossary_scores`, `bx_glossary_scores_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_glossary_files' OR `object` = 'bx_glossary_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_glossary_files' OR `object` = 'bx_glossary_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_glossary_preview', 'bx_glossary_gallery', 'bx_glossary_cover');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_glossary_preview', 'bx_glossary_gallery', 'bx_glossary_cover');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_glossary_preview', 'bx_glossary_gallery', 'bx_glossary_cover');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_glossary';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_glossary_entry_add', 'bx_glossary_entry_edit', 'bx_glossary_entry_view', 'bx_glossary_entry_delete');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_glossary';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_glossary_cats');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_glossary';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_glossary', 'bx_glossary_reactions');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_glossary';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_glossary';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_glossary';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_glossary';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_glossary';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_glossary', 'bx_glossary_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_glossary');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_glossary';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_glossary';
