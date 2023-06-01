
-- TABLES
DROP TABLE IF EXISTS `bx_snipcart_entries`, `bx_snipcart_settings`, `bx_snipcart_files`, `bx_snipcart_photos_resized`, `bx_snipcart_cmts`, `bx_snipcart_cmts_notes`, `bx_snipcart_votes`, `bx_snipcart_votes_track`, `bx_snipcart_reactions`, `bx_snipcart_reactions_track`, `bx_snipcart_views_track`, `bx_snipcart_meta_keywords`, `bx_snipcart_meta_locations`, `bx_snipcart_meta_mentions`, `bx_snipcart_reports`, `bx_snipcart_reports_track`, `bx_snipcart_favorites_track`, `bx_snipcart_scores`, `bx_snipcart_scores_track`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` = 'bx_snipcart_files' OR `object` = 'bx_snipcart_photos_resized';
DELETE FROM `sys_storage_tokens` WHERE `object` = 'bx_snipcart_files' OR `object` = 'bx_snipcart_photos_resized';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_snipcart_preview', 'bx_snipcart_gallery', 'bx_snipcart_cover');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_snipcart_preview', 'bx_snipcart_gallery', 'bx_snipcart_cover');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_snipcart_preview', 'bx_snipcart_gallery', 'bx_snipcart_cover');


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_snipcart_entry_add', 'bx_snipcart_entry_edit', 'bx_snipcart_entry_view', 'bx_snipcart_entry_view_full', 'bx_snipcart_entry_delete', 'bx_snipcart_settings_edit');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_snipcart';
DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_snipcart_cats', 'bx_snipcart_modes', 'bx_snipcart_currencies');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_snipcart%';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_snipcart', 'bx_snipcart_reactions');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_snipcart';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_snipcart';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_snipcart';


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_snipcart';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_snipcart';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_snipcart', 'bx_snipcart_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_snipcart');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_snipcart';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_snipcart';
